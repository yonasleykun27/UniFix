<?php
/**
 * auth_check.php — Session Authentication Middleware
 * Include this at the top of any protected PHP endpoint.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requireAuth() {
    if (empty($_SESSION['username']) || empty($_SESSION['role'])) {
        http_response_code(401);
        echo json_encode(["success" => false, "message" => "Unauthorized. Please log in."]);
        exit;
    }
}

function getSessionUser() {
    return [
        'username' => $_SESSION['username'] ?? null,
        'role'     => $_SESSION['role'] ?? null,
    ];
}
?>
