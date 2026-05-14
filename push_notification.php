<?php
/**
 * push_notification.php — Server-side helper to push in-app notifications.
 * Include this file and call pushNotification($conn, $username, $message, $reportId).
 * This is NOT an API endpoint — it's used internally by other PHP scripts.
 */

function pushNotification($conn, $username, $message, $reportId = null) {
    try {
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

        $stmt = $conn->prepare("INSERT INTO notifications (username, message, link_report_id) VALUES (:u, :m, :rid)");
        $stmt->execute(['u' => $username, 'm' => $message, 'rid' => $reportId]);
        return true;
    } catch (Exception $e) {
        error_log("pushNotification failed: " . $e->getMessage());
        return false;
    }
}

/**
 * Push notification to ALL users with a specific role.
 */
function pushNotificationToRole($conn, $role, $message, $reportId = null) {
    try {
        $stmt = $conn->prepare("SELECT username FROM users WHERE role = :r AND isBanned = 0");
        $stmt->execute(['r' => $role]);
        $users = $stmt->fetchAll(PDO::FETCH_COLUMN);
        foreach ($users as $u) {
            pushNotification($conn, $u, $message, $reportId);
        }
        return true;
    } catch (Exception $e) {
        error_log("pushNotificationToRole failed: " . $e->getMessage());
        return false;
    }
}
?>
