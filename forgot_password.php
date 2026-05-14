<?php
/**
 * forgot_password.php — Sends a password reset link via email (PHPMailer/Gmail)
 * Output buffering ensures ONLY valid JSON is ever returned.
 */
ob_start(); // capture any stray output (warnings, notices, die() text)

function send_json($data) {
    ob_end_clean(); // discard any stray output
    if (!headers_sent()) {
        header('Content-Type: application/json; charset=utf-8');
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

register_shutdown_function(function () {
    $err = error_get_last();
    if ($err && in_array($err['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        ob_end_clean();
        if (!headers_sent()) header('Content-Type: application/json; charset=utf-8');
        echo json_encode(["success" => false, "message" => "Server error: " . $err['message']], JSON_UNESCAPED_UNICODE);
    }
});

try {
    require_once 'db_connect.php';
    require_once 'notify.php';
} catch (Throwable $e) {
    send_json(["success" => false, "message" => "Server init failed: " . $e->getMessage()]);
}

$raw  = file_get_contents("php://input");
$data = json_decode($raw, true);

if (empty($data['username'])) {
    send_json(["success" => false, "message" => "Username is required."]);
}

$inputUsername = trim($data['username']);

try {
    $conn = getDBConnection();

    try {
        $conn->exec("ALTER TABLE users ADD COLUMN email VARCHAR(255) DEFAULT NULL");
    } catch (PDOException $ignore) { /* column already exists */ }

    $conn->exec("CREATE TABLE IF NOT EXISTS password_resets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) NOT NULL,
        token VARCHAR(64) NOT NULL UNIQUE,
        expires_at DATETIME NOT NULL,
        used TINYINT(1) DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_token (token),
        INDEX idx_username (username)
    )");

    $stmt = $conn->prepare("SELECT username, fullName, email FROM users WHERE username = :u");
    $stmt->execute(['u' => $inputUsername]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        send_json(["success" => true, "message" => "If this account exists and has an email, a reset link has been sent."]);
    }

    $email = !empty($user['email']) ? trim($user['email']) : null;
    if (!$email) {
        send_json(["success" => false, "message" => "No email is registered for this account. Please contact an administrator."]);
    }

    // Rate limiting: Check if a token was generated in the last 60 seconds
    $stmt = $conn->prepare("SELECT created_at FROM password_resets WHERE username = :u AND created_at > (NOW() - INTERVAL 1 MINUTE) LIMIT 1");
    $stmt->execute(['u' => $inputUsername]);
    if ($stmt->fetch()) {
        send_json(["success" => true, "message" => "A reset link was recently sent. Please wait a moment before trying again."]);
    }

    $token     = bin2hex(random_bytes(32));
    $expiresAt = date('Y-m-d H:i:s', strtotime('+30 minutes'));

    $conn->prepare("UPDATE password_resets SET used = 1 WHERE username = :u")->execute(['u' => $inputUsername]);

    $conn->prepare("INSERT INTO password_resets (username, token, expires_at) VALUES (:u, :t, :e)")
         ->execute(['u' => $inputUsername, 't' => $token, 'e' => $expiresAt]);

    $protocol  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host      = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $basePath  = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? '/UniFix/forgot_password.php'), '/');
    $resetLink = "{$protocol}://{$host}{$basePath}/reset_password.html?token={$token}";

    $fullName = htmlspecialchars($user['fullName'], ENT_QUOTES, 'UTF-8');
    $body = "
      <p>Dear <strong>{$fullName}</strong>,</p>
      <p>We received a request to reset your <strong>UniFix</strong> password.</p>
      <p style='text-align:center;margin:24px 0;'>
        <a href='{$resetLink}' style='display:inline-block;padding:14px 32px;background:#4e73df;color:#fff;
           text-decoration:none;border-radius:8px;font-weight:bold;font-size:16px;'>
          &#128273; Reset My Password
        </a>
      </p>
      <p style='font-size:12px;color:#888;'>This link expires in <strong>30 minutes</strong>.
         If you did not request this, ignore this email.</p>
      <p style='font-size:12px;color:#888;'>Direct link: <a href='{$resetLink}'>{$resetLink}</a></p>";

    $sent = unifix_notify($email, $user['fullName'], "Password Reset - UniFix", buildEmailTemplate("Password Reset", "#4e73df", $body));

    if ($sent) {
        send_json(["success" => true, "message" => "Password reset link sent to {$email}. Check your inbox (and spam folder)."]);
    } else {
        send_json(["success" => true, "message" => "Reset link generated. Check your email at {$email} (including spam). If not received, contact admin."]);
    }

} catch (Throwable $e) {
    send_json(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>
