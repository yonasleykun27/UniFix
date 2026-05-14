<?php
header('Content-Type: application/json');
require_once 'db_connect.php';
require_once 'auth_check.php';
requireAuth();
require_once 'notify.php';
require_once 'push_notification.php';

$data = json_decode(file_get_contents("php://input"), true);
if(!$data || !isset($data['action'])) {
    echo json_encode(["success" => false, "message" => "Invalid input."]);
    exit;
}

try {
    $conn = getDBConnection();
    $action = $data['action'];
    
    if ($action === 'warn') {
        $username = $data['username'];
        $reason = $data['reason'];
        
        $stmt = $conn->prepare("SELECT warnings, warningHistory FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $warnings = (int)$row['warnings'];
        $history = $row['warningHistory'] ? json_decode($row['warningHistory'], true) : [];
        $history[] = [
            'reason' => $reason,
            'date' => date('Y-m-d H:i:s')
        ];
        
        $newWarnings = $warnings + 1;
        $isBanned = $newWarnings >= 3 ? 1 : 0;
        
        $upd = $conn->prepare("UPDATE users SET warnings = :w, isBanned = :b, lastWarningReason = :r, warningHistory = :h WHERE username = :username");
        $upd->execute(['w' => $newWarnings, 'b' => $isBanned, 'r' => $reason, 'h' => json_encode($history), 'username' => $username]);
        notifyWarning($conn, $username, $reason);

        $banMsg = $isBanned ? " Your account has been BANNED." : "";
        pushNotification($conn, $username, "⚠️ Warning #{$newWarnings}: {$reason}.{$banMsg}");

        echo json_encode(["success" => true, "warnings" => $newWarnings]);
    }
    elseif ($action === 'retractWarning') {
        $username = $data['username'];
        $stmt = $conn->prepare("SELECT warnings, warningHistory, isBanned FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            echo json_encode(["success" => false, "message" => "User not found."]);
            exit;
        }

        $warnings = (int)$row['warnings'];
        if ($warnings <= 0) {
            echo json_encode(["success" => false, "message" => "No warnings to retract."]);
            exit;
        }

        $history = $row['warningHistory'] ? json_decode($row['warningHistory'], true) : [];
        if (!empty($history)) array_pop($history); // Remove last warning

        $newWarnings = max(0, $warnings - 1);
        $wasBanned = (bool)$row['isBanned'];
        $isBanned = $newWarnings >= 3 ? 1 : 0;

        $lastReason = !empty($history) ? end($history)['reason'] : null;
        $upd = $conn->prepare("UPDATE users SET warnings = :w, isBanned = :b, lastWarningReason = :r, warningHistory = :h WHERE username = :username");
        $upd->execute(['w' => $newWarnings, 'b' => $isBanned, 'r' => $lastReason, 'h' => json_encode($history), 'username' => $username]);

        // If user was banned and now unbanned, clear login attempts too
        if ($wasBanned && !$isBanned) {
            $conn->prepare("DELETE FROM login_attempts WHERE username = :u")->execute(['u' => $username]);
        }

        pushNotification($conn, $username, "✅ A warning has been retracted. You now have {$newWarnings} warning(s).");

        echo json_encode(["success" => true, "warnings" => $newWarnings, "isBanned" => (bool)$isBanned]);
    }
    elseif ($action === 'unban') {
        $username = $data['username'];
        $conn->prepare("UPDATE users SET isBanned = 0 WHERE username = :username")->execute(['username' => $username]);
        $conn->prepare("DELETE FROM login_attempts WHERE username = :u")->execute(['u' => $username]);
        
        pushNotification($conn, $username, "✅ Your account ban has been lifted by an administrator.");

        echo json_encode(["success" => true]);
    }
    elseif ($action === 'toggleLeave') {
        $username = $data['username'];
        $stmt = $conn->prepare("SELECT isOnLeave FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $isOnLeave = (int)$stmt->fetchColumn();
        
        $newLeaveStatus = $isOnLeave ? 0 : 1;
        $conn->prepare("UPDATE users SET isOnLeave = :s WHERE username = :username")->execute(['s' => $newLeaveStatus, 'username' => $username]);
        
        echo json_encode(["success" => true, "isOnLeave" => (bool)$newLeaveStatus]);
    }
    elseif ($action === 'updateContact') {
        $username = $data['username'];
        $email = $data['email'] ?? null;
        $phone = $data['phone'] ?? null;
        
        $upd = $conn->prepare("UPDATE users SET email = :e, phone = :p WHERE username = :u");
        $upd->execute(['e' => $email, 'p' => $phone, 'u' => $username]);
        
        echo json_encode(["success" => true]);
    }
    elseif ($action === 'delete') {
        $username = $data['username'];
        $conn->prepare("DELETE FROM login_attempts WHERE username = :u")->execute(['u' => $username]);
        $conn->prepare("DELETE FROM notifications WHERE username = :u")->execute(['u' => $username]);
        $conn->prepare("DELETE FROM users WHERE username = :username")->execute(['username' => $username]);
        echo json_encode(["success" => true]);
    }
} catch(PDOException $e) {
    echo json_encode(["success" => false, "message" => "DB Error: " . $e->getMessage()]);
}
?>
