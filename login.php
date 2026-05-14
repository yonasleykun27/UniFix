<?php
// login.php — Secure login with per-user brute-force protection
header('Content-Type: application/json');
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'db_connect.php';

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->username) || !isset($data->password)) {
    echo json_encode(["success" => false, "message" => "Missing credentials."]);
    exit;
}

$input_username = trim($data->username);
$input_password = trim($data->password);

try {
    $conn = getDBConnection();

    $conn->exec("CREATE TABLE IF NOT EXISTS login_attempts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) NOT NULL UNIQUE,
        attempts INT DEFAULT 0,
        total_lockouts INT DEFAULT 0,
        locked_until DATETIME DEFAULT NULL,
        last_attempt DATETIME DEFAULT CURRENT_TIMESTAMP,
        INDEX(username)
    )");

    $attRow = $conn->prepare("SELECT * FROM login_attempts WHERE username = :u");
    $attRow->execute(['u' => $input_username]);
    $att = $attRow->fetch(PDO::FETCH_ASSOC);

    if ($att && $att['locked_until'] && new DateTime() < new DateTime($att['locked_until'])) {
        $remaining = (new DateTime($att['locked_until']))->diff(new DateTime());
        $mins = $remaining->i + ($remaining->h * 60);
        if ($mins < 1) $mins = 1;
        echo json_encode([
            "success" => false,
            "message" => "Account locked. Try again in {$mins} minute(s).",
            "locked" => true
        ]);
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $input_username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        recordFailedAttempt($conn, $input_username, $att);
        $remaining = getRemainingAttempts($att);
        $msg = "Invalid username or password.";
        if ($remaining <= 3 && $remaining > 0) {
            $msg .= " ⚠️ {$remaining} attempt(s) remaining before lock.";
        }
        echo json_encode(["success" => false, "message" => $msg, "remaining" => $remaining]);
        exit;
    }

    if ($user['isBanned']) {
        echo json_encode(["success" => false, "message" => "BANNED"]);
        exit;
    }

    $passwordValid = false;
    if (strpos($user['password'], '$2y$') === 0) {
        $passwordValid = password_verify($input_password, $user['password']);
    } else {
        if ($user['password'] === $input_password) {
            $passwordValid = true;
            $hashed = password_hash($input_password, PASSWORD_BCRYPT);
            $conn->prepare("UPDATE users SET password = :p WHERE username = :u")
                 ->execute(['p' => $hashed, 'u' => $input_username]);
        }
    }

    if (!$passwordValid) {
        $result = recordFailedAttempt($conn, $input_username, $att);
        $remaining = $result['remaining'];
        
        if ($result['justBanned']) {
            echo json_encode([
                "success" => false,
                "message" => "Your account has been BANNED due to repeated failed login attempts. Contact an administrator to restore access.",
                "banned" => true
            ]);
        } elseif ($result['justLocked']) {
            echo json_encode([
                "success" => false,
                "message" => "Too many failed attempts. Account locked for 15 minutes. ⚠️ One more lockout will BAN your account!",
                "locked" => true
            ]);
        } else {
            $msg = "Invalid username or password.";
            if ($remaining <= 3 && $remaining > 0) {
                $msg .= " ⚠️ {$remaining} attempt(s) remaining before lock.";
            }
            echo json_encode(["success" => false, "message" => $msg, "remaining" => $remaining]);
        }
        exit;
    }

    $expectedRole = null;
    if (strpos($input_username, 'admin') === 0) $expectedRole = 'Admin';
    else if (strpos($input_username, 'stud') === 0 && strlen($input_username) >= 9) $expectedRole = 'Student';
    else if (strpos($input_username, 'tech') === 0 && strlen($input_username) >= 8) $expectedRole = 'Teacher';
    else if (strpos($input_username, 'solver') === 0) $expectedRole = 'Solver';

    if ($user['role'] !== $expectedRole) {
        echo json_encode(["success" => false, "message" => "Role mismatch."]);
        exit;
    }

    $conn->prepare("DELETE FROM login_attempts WHERE username = :u")->execute(['u' => $input_username]);
    $_SESSION['username'] = $user['username'];
    $_SESSION['role']     = $user['role'];

    $userObj = [
        "username"          => $user['username'],
        "role"              => $user['role'],
        "fullName"          => $user['fullName'],
        "id"                => $user['userId'],
        "warnings"          => (int)$user['warnings'],
        "isBanned"          => (bool)$user['isBanned'],
        "jobTitle"          => $user['jobTitle'],
        "lastWarningReason" => $user['lastWarningReason'],
        "firebaseId"        => $user['id'],
        "isOnLeave"         => (bool)($user['isOnLeave'] ?? false)
    ];

    echo json_encode(["success" => true, "role" => $user['role'], "user" => $userObj]);

} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}

/**
 * Record a failed login attempt PER USERNAME.
 * - After 5 failures → 15 min lock (1st lockout)
 * - After 5 more failures (2nd lockout) → BAN the user
 * Returns: ['remaining' => int, 'justLocked' => bool, 'justBanned' => bool]
 */
function recordFailedAttempt($conn, $username, $existing) {
    $justLocked = false;
    $justBanned = false;

    if ($existing) {
        $newAttempts = $existing['attempts'] + 1;
        $totalLockouts = (int)($existing['total_lockouts'] ?? 0);
        $lockedUntil = null;

        if ($newAttempts >= 5) {
            $totalLockouts++;
            if ($totalLockouts >= 2) {
                $conn->prepare("UPDATE users SET isBanned = 1 WHERE username = :u")->execute(['u' => $username]);
                $conn->prepare("DELETE FROM login_attempts WHERE username = :u")->execute(['u' => $username]);
                $justBanned = true;
                return ['remaining' => 0, 'justLocked' => false, 'justBanned' => true];
            }
            $lockedUntil = date('Y-m-d H:i:s', strtotime('+15 minutes'));
            $newAttempts = 0; // Reset attempt counter for next round
            $justLocked = true;
        }

        $conn->prepare("UPDATE login_attempts SET attempts = :a, total_lockouts = :tl, locked_until = :l, last_attempt = NOW() WHERE username = :u")
             ->execute(['a' => $newAttempts, 'tl' => $totalLockouts, 'l' => $lockedUntil, 'u' => $username]);
    } else {
        $conn->prepare("INSERT INTO login_attempts (username, attempts, total_lockouts, last_attempt) VALUES (:u, 1, 0, NOW())")
             ->execute(['u' => $username]);
    }

    $remaining = 5 - ($existing ? ($existing['attempts'] + 1) : 1);
    if ($remaining < 0) $remaining = 0;
    return ['remaining' => $remaining, 'justLocked' => $justLocked, 'justBanned' => $justBanned];
}

function getRemainingAttempts($existing) {
    if (!$existing) return 4; // First attempt just happened
    return max(0, 5 - ($existing['attempts'] + 1));
}
?>
