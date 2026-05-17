<?php
header('Content-Type: application/json');
require_once 'db_connect.php';
require_once 'auth_check.php';
requireAuth();

$data = json_decode(file_get_contents("php://input"), true);
if(!$data || !isset($data['action'])) {
    echo json_encode(["success" => false, "message" => "Invalid input."]);
    exit;
}

try {
    $conn = getDBConnection();
    $action = $data['action'];

    if ($action === 'send') {
        $vis = isset($data['visibility']) ? $data['visibility'] : 'public';
        $visibility = in_array($vis, ['admin_only', 'student_only']) ? $vis : 'public';
        $stmt = $conn->prepare("INSERT INTO ticket_messages (reportId, senderUsername, senderRole, message, visibility) VALUES (:rid, :user, :role, :msg, :vis)");
        $stmt->execute([
            'rid'  => $data['reportId'],
            'user' => $data['senderUsername'],
            'role' => $data['senderRole'],
            'msg'  => $data['message'],
            'vis'  => $visibility
        ]);
        echo json_encode(["success" => true, "id" => $conn->lastInsertId()]);
    }
    elseif ($action === 'fetch') {
        // Role-based visibility:
        //   Admin  → sees ALL messages
        //   Solver → sees ONLY public messages
        //   Reporter (Student/Teacher) → sees public + student_only + their own admin_only messages
        $userRole        = !empty($data['userRole'])        ? $data['userRole']        : 'Student';
        $currentUsername = !empty($data['senderUsername'])  ? $data['senderUsername']  : '';

        if ($userRole === 'Admin') {
            // Admin sees every message
            $stmt = $conn->prepare(
                "SELECT tm.*, u.fullName as senderName "
              . "FROM ticket_messages tm "
              . "LEFT JOIN users u ON tm.senderUsername = u.username "
              . "WHERE tm.reportId = :rid "
              . "ORDER BY tm.createdAt ASC"
            );
            $stmt->execute(['rid' => $data['reportId']]);

        } elseif ($userRole === 'Solver') {
            // Solver sees ONLY public messages
            $stmt = $conn->prepare(
                "SELECT tm.*, u.fullName as senderName "
              . "FROM ticket_messages tm "
              . "LEFT JOIN users u ON tm.senderUsername = u.username "
              . "WHERE tm.reportId = :rid "
              .   "AND (tm.visibility IS NULL OR tm.visibility = 'public') "
              . "ORDER BY tm.createdAt ASC"
            );
            $stmt->execute(['rid' => $data['reportId']]);

        } else {
            // Reporter (Student / Teacher):
            //   public       – always visible
            //   student_only – admin's private reply, always visible to reporter
            //   admin_only   – visible ONLY to the sender (their own message)
            $stmt = $conn->prepare(
                "SELECT tm.*, u.fullName as senderName "
              . "FROM ticket_messages tm "
              . "LEFT JOIN users u ON tm.senderUsername = u.username "
              . "WHERE tm.reportId = :rid "
              .   "AND ("
              .       "tm.visibility IS NULL "
              .    "OR tm.visibility = 'public' "
              .    "OR tm.visibility = 'student_only' "
              .    "OR (tm.visibility = 'admin_only' AND tm.senderUsername = :uname)"
              .   ") "
              . "ORDER BY tm.createdAt ASC"
            );
            $stmt->execute(['rid' => $data['reportId'], 'uname' => $currentUsername]);
        }
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(["success" => true, "messages" => $messages]);
    }
    elseif ($action === 'edit') {
        $stmt = $conn->prepare("UPDATE ticket_messages SET message = :msg WHERE id = :id AND senderUsername = :user");
        $stmt->execute([
            'msg'  => $data['message'],
            'id'   => $data['messageId'],
            'user' => $data['senderUsername']
        ]);
        echo json_encode(["success" => true]);
    }
    elseif ($action === 'delete') {
        $stmt = $conn->prepare("DELETE FROM ticket_messages WHERE id = :id AND senderUsername = :user");
        $stmt->execute([
            'id'   => $data['messageId'],
            'user' => $data['senderUsername']
        ]);
        echo json_encode(["success" => true]);
    }
    else {
        echo json_encode(["success" => false, "message" => "Unknown action"]);
    }
} catch(PDOException $e) {
    echo json_encode(["success" => false, "message" => "DB Error: " . $e->getMessage()]);
}
?>
