<?php
header('Content-Type: application/json');
require_once 'db_connect.php';
require_once 'auth_check.php';
requireAuth();
require_once 'notify.php';
require_once 'push_notification.php';

$data = json_decode(file_get_contents("php://input"), true);
if (!$data && isset($_POST['action'])) {
    $data = $_POST;
    if (isset($data['report']) && is_string($data['report'])) {
        $data['report'] = json_decode($data['report'], true);
    }
}

if(!$data || !isset($data['action'])) {
    echo json_encode(["success" => false, "message" => "Invalid input."]);
    exit;
}

try {
    $conn = getDBConnection();
    $action = $data['action'];
    
    if ($action === 'submit') {
        $r = $data['report'];
        $photoUrl = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $filename = uniqid('img_') . '.' . $ext;
                $uploadPath = 'uploads/' . $filename;
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
                    $photoUrl = $uploadPath;
                }
            }
        }

        $stmt = $conn->prepare("
            INSERT INTO reports (
                category, phone, urgency, description, status, reporterUsername, 
                assignedPendingAdmin, specificDetails, photoUrl, hiddenFromAdmin, hiddenFromSolver, hiddenFromReporter
            ) VALUES (
                :cat, :phone, :urg, :desc, :stat, :repUser, :pendAdmin, :spec, :photo, 0, 0, 0
            )
        ");
        $stmt->execute([
            'cat' => $r['category'], 
            'phone' => $r['reporterPhone'] ?? $r['phone'] ?? '', 
            'urg' => $r['urgency'],
            'desc' => $r['description'], 
            'stat' => $r['status'] ?? 'Pending',
            'repUser' => $r['reporterUsername'], 
            'pendAdmin' => $r['assignedPendingAdmin'] ?? null,
            'spec' => isset($r['specificDetails']) ? json_encode($r['specificDetails']) : null,
            'photo' => $photoUrl
        ]);
        $newId = $conn->lastInsertId();

        $reporterName = $r['reporterName'] ?? $r['reporterUsername'] ?? 'A user';
        $assignedAdmin = $r['assignedPendingAdmin'] ?? null;
        if ($assignedAdmin) {
            pushNotification($conn, $assignedAdmin, "📋 New report from {$reporterName}: {$r['category']}", $newId);
        } else {
            // Fallback: notify all admins only if no specific admin assigned
            pushNotificationToRole($conn, 'Admin', "📋 New report from {$reporterName}: {$r['category']}", $newId);
        }

        echo json_encode(["success" => true, "id" => $newId]);
    }
    elseif ($action === 'updateStatus') {
        $fields = ["status = :status"];
        $params = ['status' => $data['status'], 'id' => $data['id']];
        
        if (array_key_exists('assignedTo', $data)) {
            $fields[] = "assignedTo = :assignedTo";
            $params['assignedTo'] = $data['assignedTo'];
        }
        if (array_key_exists('actingAdmin', $data)) {
            $fields[] = "assignedAdminUsername = :admin";
            $params['admin'] = $data['actingAdmin'];
        }
        if (array_key_exists('solverUsername', $data)) {
            $fields[] = "assignedSolverUsername = :solverUsername";
            $params['solverUsername'] = $data['solverUsername'];
        }
        if (array_key_exists('solverName', $data)) {
            $fields[] = "assignedSolverName = :solverName";
            $params['solverName'] = $data['solverName'];
        }
        if (array_key_exists('declineReason', $data)) {
            $fields[] = "declineReason = :reason";
            $params['reason'] = $data['declineReason'];
        }

        $oldStmt = $conn->prepare("SELECT status, assignedSolverUsername FROM reports WHERE id = :id");
        $oldStmt->execute(['id' => $data['id']]);
        $old = $oldStmt->fetch(PDO::FETCH_ASSOC);

        $sql = "UPDATE reports SET " . implode(", ", $fields) . " WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);

        // If status or solver didn't change, don't send notifications again
        $statusChanged = ($old['status'] !== $data['status']);
        $solverChanged = isset($data['solverUsername']) && ($old['assignedSolverUsername'] !== $data['solverUsername']);

        if (!$statusChanged && !$solverChanged) {
            echo json_encode(["success" => true, "message" => "No changes detected."]);
            exit;
        }
        if ($data['status'] === 'Assigned') {
            notifyAssigned($conn, $data['id']);
            $rpt = $conn->prepare("SELECT reporterUsername, category, assignedAdminUsername FROM reports WHERE id = :id");
            $rpt->execute(['id' => $data['id']]);
            $rptRow = $rpt->fetch(PDO::FETCH_ASSOC);
            $actingAdmin = $data['actingAdmin'] ?? null;
            if ($rptRow) {
                pushNotification($conn, $rptRow['reporterUsername'], "✅ Your {$rptRow['category']} report has been assigned to a solver.", $data['id']);
                $solverUser = $data['solverUsername'] ?? '';
                if (!empty($solverUser)) {
                    pushNotification($conn, $solverUser, "🔧 New task assigned to you: {$rptRow['category']}", $data['id']);
                }
                // In-app: notify the assigned admin — but NOT if they are the one performing the action
                $assignedAdmin = $rptRow['assignedAdminUsername'] ?? null;
                if ($assignedAdmin && $assignedAdmin !== $actingAdmin) {
                    pushNotification($conn, $assignedAdmin, "✅ Ticket #{$data['id']} ({$rptRow['category']}) has been assigned.", $data['id']);
                }
            }
        }
        elseif (in_array($data['status'], ['Finished', 'Completed'])) {
            notifyFinished($conn, $data['id']);
            $rpt = $conn->prepare("SELECT reporterUsername, category, assignedAdminUsername FROM reports WHERE id = :id");
            $rpt->execute(['id' => $data['id']]);
            $rptRow = $rpt->fetch(PDO::FETCH_ASSOC);
            if ($rptRow) {
                pushNotification($conn, $rptRow['reporterUsername'], "🎉 Your {$rptRow['category']} report has been completed!", $data['id']);
                if (!empty($rptRow['assignedAdminUsername'])) {
                    pushNotification($conn, $rptRow['assignedAdminUsername'], "✅ Ticket #{$data['id']} marked as {$data['status']}.", $data['id']);
                }
            }
        }
        elseif ($data['status'] === 'Declined' && isset($data['declineReason'])) {
            notifyDeclined($conn, $data['id'], $data['declineReason']);
            $rpt = $conn->prepare("SELECT reporterUsername, category FROM reports WHERE id = :id");
            $rpt->execute(['id' => $data['id']]);
            $rptRow = $rpt->fetch(PDO::FETCH_ASSOC);
            if ($rptRow) pushNotification($conn, $rptRow['reporterUsername'], "❌ Your {$rptRow['category']} report was declined: {$data['declineReason']}", $data['id']);
        }
        elseif ($data['status'] === 'In Progress') {
            $rpt = $conn->prepare("SELECT reporterUsername, category FROM reports WHERE id = :id");
            $rpt->execute(['id' => $data['id']]);
            $rptRow = $rpt->fetch(PDO::FETCH_ASSOC);
            if ($rptRow) pushNotification($conn, $rptRow['reporterUsername'], "🔄 Your {$rptRow['category']} report is now In Progress.", $data['id']);
        }
        echo json_encode(["success" => true]);
    }
    elseif ($action === 'updateContent') {
        $ALLOWED_COLUMNS = ['category', 'phone', 'urgency', 'description', 'specificDetails'];
        $updates = [];
        $params = ['id' => $data['id']];
        foreach ($data['newData'] as $k => $v) {
            if (!in_array($k, $ALLOWED_COLUMNS)) continue; // whitelist check
            $updates[] = "$k = :$k";
            $params[$k] = $v;
        }
        if (count($updates) > 0) {
            $sql = "UPDATE reports SET " . implode(', ', $updates) . " WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
        }
        echo json_encode(["success" => true]);
    }
    elseif ($action === 'hideAdmin') {
        $conn->prepare("UPDATE reports SET hiddenFromAdmin = 1 WHERE id = :id")->execute(['id' => $data['id']]);
        echo json_encode(["success" => true]);
    }
    elseif ($action === 'hideSolver') {
        $conn->prepare("UPDATE reports SET hiddenFromSolver = 1 WHERE id = :id")->execute(['id' => $data['id']]);
        echo json_encode(["success" => true]);
    }
    elseif ($action === 'softDelete') {
        $conn->prepare("UPDATE reports SET hiddenFromReporter = 1 WHERE id = :id")->execute(['id' => $data['id']]);
        echo json_encode(["success" => true]);
    }
    elseif ($action === 'hardDelete') {
        $conn->prepare("DELETE FROM reports WHERE id = :id")->execute(['id' => $data['id']]);
        echo json_encode(["success" => true]);
    }
    elseif ($action === 'updatePhoto') {
        $id = $data['id'] ?? $_POST['id'] ?? null;
        $removePhoto = ($data['removePhoto'] ?? $_POST['removePhoto'] ?? 'false') === 'true';
        
        if ($removePhoto) {
            $stmt = $conn->prepare("SELECT photoUrl FROM reports WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row && $row['photoUrl'] && file_exists($row['photoUrl'])) {
                unlink($row['photoUrl']);
            }
            $conn->prepare("UPDATE reports SET photoUrl = NULL WHERE id = :id")->execute(['id' => $id]);
            echo json_encode(["success" => true, "photoUrl" => null]);
        } else if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $stmt = $conn->prepare("SELECT photoUrl FROM reports WHERE id = :id");
                $stmt->execute(['id' => $id]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row && $row['photoUrl'] && file_exists($row['photoUrl'])) {
                    unlink($row['photoUrl']);
                }
                $filename = uniqid('img_') . '.' . $ext;
                $uploadPath = 'uploads/' . $filename;
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
                    $conn->prepare("UPDATE reports SET photoUrl = :url WHERE id = :id")->execute(['url' => $uploadPath, 'id' => $id]);
                    echo json_encode(["success" => true, "photoUrl" => $uploadPath]);
                } else {
                    echo json_encode(["success" => false, "message" => "Failed to save file."]);
                }
            } else {
                echo json_encode(["success" => false, "message" => "Invalid file type."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "No file provided."]);
        }
    }
    elseif ($action === 'delegate') {
        $checkStmt = $conn->prepare("SELECT status, assignedSolverUsername FROM reports WHERE id = :id");
        $checkStmt->execute(['id' => $data['id']]);
        $report = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$report || in_array($report['status'], ['Finished', 'Declined'])) {
            echo json_encode(["success" => false, "message" => "Cannot delegate a finished or declined ticket."]);
            exit;
        }

        $stmt = $conn->prepare("UPDATE reports SET assignedSolverUsername = :newSolver, assignedSolverName = :newName, delegatedFrom = :fromSolver, delegationNote = :note, delegationStatus = 'Pending', status = 'Assigned' WHERE id = :id");
        $stmt->execute([
            'newSolver'  => $data['newSolverUsername'],
            'newName'    => $data['newSolverName'],
            'fromSolver' => $data['fromSolverUsername'],
            'note'       => $data['note'] ?? '',
            'id'         => $data['id']
        ]);
        notifyDelegated($conn, $data['id'], $data['newSolverUsername'], $data['note'] ?? '');
        echo json_encode(["success" => true]);
    }
    elseif ($action === 'cancelDelegation') {
        $stmt = $conn->prepare("SELECT * FROM reports WHERE id = :id AND delegationStatus = 'Pending'");
        $stmt->execute(['id' => $data['id']]);
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$r) {
            echo json_encode(["success" => false, "message" => "Delegation cannot be cancelled (already accepted or not pending)."]);
            exit;
        }

        $origSolver = $conn->prepare("SELECT fullName FROM users WHERE username = :u");
        $origSolver->execute(['u' => $r['delegatedFrom']]);
        $origName = $origSolver->fetchColumn();

        $upd = $conn->prepare("UPDATE reports SET assignedSolverUsername = :origUser, assignedSolverName = :origName, delegatedFrom = NULL, delegationNote = NULL, delegationStatus = NULL WHERE id = :id");
        $upd->execute([
            'origUser' => $r['delegatedFrom'],
            'origName' => $origName,
            'id'       => $data['id']
        ]);
        echo json_encode(["success" => true]);
    }
    elseif ($action === 'acceptDelegation') {
        $conn->prepare("UPDATE reports SET delegationStatus = 'Accepted' WHERE id = :id")->execute(['id' => $data['id']]);
        
        $msg = "Delegation accepted by " . ($data['solverName'] ?? 'Solver') . ".";
        $senderUser = $data['solverUsername'];
        $chatStmt = $conn->prepare("INSERT INTO ticket_messages (reportId, senderUsername, senderRole, message) VALUES (:rid, :user, 'System', :msg)");
        $chatStmt->execute(['rid' => $data['id'], 'user' => $senderUser, 'msg' => $msg]);

        echo json_encode(["success" => true]);
    }
    elseif ($action === 'declineDelegation') {
        $stmt = $conn->prepare("SELECT * FROM reports WHERE id = :id AND delegationStatus = 'Pending'");
        $stmt->execute(['id' => $data['id']]);
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$r) {
            echo json_encode(["success" => false, "message" => "Delegation cannot be declined (already accepted or not pending)."]);
            exit;
        }

        $origSolver = $conn->prepare("SELECT fullName FROM users WHERE username = :u");
        $origSolver->execute(['u' => $r['delegatedFrom']]);
        $origName = $origSolver->fetchColumn();

        $upd = $conn->prepare("UPDATE reports SET assignedSolverUsername = :origUser, assignedSolverName = :origName, delegatedFrom = NULL, delegationNote = NULL, delegationStatus = NULL WHERE id = :id");
        $upd->execute([
            'origUser' => $r['delegatedFrom'],
            'origName' => $origName,
            'id'       => $data['id']
        ]);

        $msg = "Delegation declined by " . ($data['solverName'] ?? 'Solver') . ". Ticket returned to original solver.";
        $senderUser = $data['solverUsername'];
        $chatStmt = $conn->prepare("INSERT INTO ticket_messages (reportId, senderUsername, senderRole, message) VALUES (:rid, :user, 'System', :msg)");
        $chatStmt->execute(['rid' => $data['id'], 'user' => $senderUser, 'msg' => $msg]);

        echo json_encode(["success" => true]);
    }
    elseif ($action === 'setSLA') {
        $hours = isset($data['hours']) ? (float)$data['hours'] : 24;
        if ($hours <= 0) {
            // 5 minute: set deadline to 5 mins from now
            $stmt = $conn->prepare("UPDATE reports SET slaDeadline = DATE_ADD(NOW(), INTERVAL 5 MINUTE), slaEscalated = 0 WHERE id = :id");
            $stmt->execute(['id' => $data['id']]);
        } else {
            $stmt = $conn->prepare("UPDATE reports SET slaDeadline = DATE_ADD(NOW(), INTERVAL :hours HOUR), slaEscalated = 0 WHERE id = :id");
            $stmt->execute(['hours' => $hours, 'id' => $data['id']]);
        }
        echo json_encode(["success" => true]);
    }
    elseif ($action === 'checkSLA') {
        $stmt = $conn->query("SELECT id FROM reports WHERE slaDeadline IS NOT NULL AND slaDeadline < NOW() AND status NOT IN ('Finished', 'Declined') AND slaEscalated = 0");
        $breached = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach($breached as $b) {
            $conn->prepare("UPDATE reports SET slaEscalated = 1 WHERE id = :id")->execute(['id' => $b['id']]);
            notifySLABreach($conn, $b['id']);
        }
        
        $escalated = $conn->query("SELECT COUNT(*) FROM reports WHERE slaEscalated = 1 AND status NOT IN ('Finished', 'Declined')")->fetchColumn();
        echo json_encode(["success" => true, "escalatedCount" => (int)$escalated]);
    }
    elseif ($action === 'clearEscalation') {
        $conn->prepare("UPDATE reports SET slaEscalated = 0, slaDeadline = NULL WHERE id = :id")->execute(['id' => $data['id']]);
        echo json_encode(["success" => true]);
    }
    else {
        echo json_encode(["success" => false, "message" => "Unknown action"]);
    }
} catch(PDOException $e) {
    echo json_encode(["success" => false, "message" => "DB Error: " . $e->getMessage()]);
}
?>
