<?php
/**
 * reset_password_api.php — Validates token and resets password
 */
header('Content-Type: application/json');
require_once 'db_connect.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['token'], $data['newPassword'])) {
    echo json_encode(["success" => false, "message" => "Missing token or new password."]);
    exit;
}

$token = trim($data['token']);
$newPassword = trim($data['newPassword']);

if (strlen($newPassword) < 6) {
    echo json_encode(["success" => false, "message" => "Password must be at least 6 characters."]);
    exit;
}

try {
    $conn = getDBConnection();

    $stmt = $conn->prepare("SELECT * FROM password_resets WHERE token = :t AND used = 0");
    $stmt->execute(['t' => $token]);
    $reset = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reset) {
        echo json_encode(["success" => false, "message" => "Invalid or already used reset link."]);
        exit;
    }

    if (new DateTime() > new DateTime($reset['expires_at'])) {
        echo json_encode(["success" => false, "message" => "This reset link has expired. Please request a new one."]);
        exit;
    }

    $hashed = password_hash($newPassword, PASSWORD_BCRYPT);
    $conn->prepare("UPDATE users SET password = :p WHERE username = :u")
         ->execute(['p' => $hashed, 'u' => $reset['username']]);

    $conn->prepare("UPDATE password_resets SET used = 1 WHERE id = :id")
         ->execute(['id' => $reset['id']]);

    $conn->prepare("DELETE FROM login_attempts WHERE username = :u")
         ->execute(['u' => $reset['username']]);

    echo json_encode(["success" => true, "message" => "Password reset successfully! You can now log in."]);

} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
