<?php
/**
 * change_password.php — Allows logged-in users to change their password.
 */
header('Content-Type: application/json');
require_once 'db_connect.php';
require_once 'auth_check.php';
requireAuth();

$data = json_decode(file_get_contents("php://input"), true);
$sessionUser = getSessionUser();

if (!isset($data['currentPassword'], $data['newPassword'])) {
    echo json_encode(["success" => false, "message" => "Missing fields."]);
    exit;
}
if (strlen($data['newPassword']) < 6) {
    echo json_encode(["success" => false, "message" => "New password must be at least 6 characters."]);
    exit;
}

try {
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = :u");
    $stmt->execute(['u' => $sessionUser['username']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(["success" => false, "message" => "User not found."]);
        exit;
    }

    $valid = false;
    if (strpos($user['password'], '$2y$') === 0) {
        $valid = password_verify($data['currentPassword'], $user['password']);
    } else {
        $valid = ($user['password'] === $data['currentPassword']);
    }

    if (!$valid) {
        echo json_encode(["success" => false, "message" => "Current password is incorrect."]);
        exit;
    }

    $newHashed = password_hash($data['newPassword'], PASSWORD_BCRYPT);
    $conn->prepare("UPDATE users SET password = :p WHERE username = :u")
         ->execute(['p' => $newHashed, 'u' => $sessionUser['username']]);

    echo json_encode(["success" => true]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "DB Error: " . $e->getMessage()]);
}
?>
