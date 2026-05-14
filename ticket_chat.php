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
        $stmt = $conn->prepare("INSERT INTO ticket_messages (reportId, senderUsername, senderRole, message) VALUES (:rid, :user, :role, :msg)");
        $stmt->execute([
            'rid'  => $data['reportId'],
            'user' => $data['senderUsername'],
            'role' => $data['senderRole'],
            'msg'  => $data['message']
        ]);
        echo json_encode(["success" => true, "id" => $conn->lastInsertId()]);
    }
    elseif ($action === 'fetch') {
        $stmt = $conn->prepare("SELECT tm.*, u.fullName as senderName FROM ticket_messages tm LEFT JOIN users u ON tm.senderUsername = u.username WHERE tm.reportId = :rid ORDER BY tm.createdAt ASC");
        $stmt->execute(['rid' => $data['reportId']]);
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
