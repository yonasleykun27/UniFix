<?php
/**
 * notifications.php — In-app notification backend
 */
header('Content-Type: application/json');
require_once 'db_connect.php';
require_once 'auth_check.php';
requireAuth();

$data = json_decode(file_get_contents("php://input"), true);
if (!$data || !isset($data['action'])) {
    echo json_encode(["success" => false, "message" => "Invalid input."]);
    exit;
}

try {
    $conn = getDBConnection();

    $conn->exec("CREATE TABLE IF NOT EXISTS notifications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) NOT NULL,
        message TEXT NOT NULL,
        link_report_id INT DEFAULT NULL,
        is_read TINYINT(1) DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        INDEX(username),
        INDEX(is_read)
    )");

    $action = $data['action'];

    if ($action === 'fetch') {
        $stmt = $conn->prepare("SELECT * FROM notifications WHERE username = :u ORDER BY created_at DESC LIMIT 30");
        $stmt->execute(['u' => $data['username']]);
        $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $unread = array_filter($notes, fn($n) => !$n['is_read']);
        echo json_encode(["success" => true, "notifications" => $notes, "unreadCount" => count($unread)]);
    }
    elseif ($action === 'markRead') {
        $conn->prepare("UPDATE notifications SET is_read = 1 WHERE username = :u")->execute(['u' => $data['username']]);
        echo json_encode(["success" => true]);
    }
    elseif ($action === 'push') {
        $stmt = $conn->prepare("INSERT INTO notifications (username, message, link_report_id) VALUES (:u, :m, :rid)");
        $stmt->execute(['u' => $data['username'], 'm' => $data['message'], 'rid' => $data['reportId'] ?? null]);
        echo json_encode(["success" => true]);
    }
    else {
        echo json_encode(["success" => false, "message" => "Unknown action"]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "DB Error: " . $e->getMessage()]);
}
?>
