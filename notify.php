<?php
/**
 * UniFix Email Notification Service
 * Sends email alerts for key ticket events.
 * Falls back to log file if mail() is not configured.
 */

require_once 'db_connect.php';
require_once __DIR__ . '/PHPMailer/Exception.php';
require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

define('SMTP_USER', 'yonasleykun27@gmail.com'); 
define('SMTP_PASS', 'hduijoiavxglmoei'); 

function unifix_notify($toEmail, $toName, $subject, $htmlBody) {
    $mail = new PHPMailer(true);
    $sent = false;
    $errorMsg = '';

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom(SMTP_USER, 'UniFix System');
        $mail->addAddress($toEmail, $toName);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $htmlBody;
        $mail->AltBody = strip_tags(str_replace(['<br>', '</p>'], "\n", $htmlBody));

        $mail->send();
        $sent = true;
    } catch (Exception $e) {
        $sent = false;
        $errorMsg = $mail->ErrorInfo;
    }

    $logDir = __DIR__ . '/uploads/email_logs';
    if (!is_dir($logDir)) mkdir($logDir, 0755, true);
    $logFile = $logDir . '/notifications.log';
    $logLine = "[" . date('Y-m-d H:i:s') . "] TO: $toEmail | SUBJECT: $subject | SENT: " . ($sent ? 'YES' : 'FAILED - ' . $errorMsg) . "\n";
    file_put_contents($logFile, $logLine, FILE_APPEND);

    return $sent;
}

function buildEmailTemplate($title, $color, $bodyHtml) {
    return "
    <div style='font-family:Arial,sans-serif;max-width:600px;margin:0 auto;border:1px solid #e0e0e0;border-radius:8px;overflow:hidden;'>
      <div style='background:{$color};color:white;padding:20px 24px;'>
        <h2 style='margin:0;font-size:20px;'>🎓 UniFix — {$title}</h2>
        <p style='margin:4px 0 0;font-size:12px;opacity:0.8;'>Debre Birhan University Problem Reporting System</p>
      </div>
      <div style='padding:24px;color:#333;'>
        {$bodyHtml}
        <hr style='border:none;border-top:1px solid #eee;margin:20px 0;'>
        <p style='font-size:11px;color:#999;'>This is an automated message from UniFix. Please do not reply to this email.</p>
      </div>
    </div>";
}

/**
 * Get user email from DB (uses phone as fallback display)
 */
function getUserEmail($conn, $username) {
    $stmt = $conn->prepare("SELECT fullName, email, phone FROM users WHERE username = :u");
    $stmt->execute(['u' => $username]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Use stored email if available, otherwise fallback to username@dbu.edu.et
    $email = (!empty($row['email'])) ? $row['email'] : $username . '@dbu.edu.et';
    
    return [
        'email'    => $email,
        'fullName' => $row['fullName'] ?? $username,
        'phone'    => $row['phone'] ?? ''
    ];
}

/**
 * Notify reporter that their ticket has been assigned
 */
function notifyAssigned($conn, $reportId) {
    $stmt = $conn->prepare("SELECT r.*, u.phone FROM reports r LEFT JOIN users u ON r.reporterUsername = u.username WHERE r.id = :id");
    $stmt->execute(['id' => $reportId]);
    $r = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$r) return;

    $user = getUserEmail($conn, $r['reporterUsername']);
    $body = "
      <p>Dear <strong>{$user['fullName']}</strong>,</p>
      <p>Your problem report has been <strong style='color:#4e73df;'>assigned</strong> and is now being handled.</p>
      <table style='width:100%;border-collapse:collapse;margin:16px 0;'>
        <tr><td style='padding:8px;background:#f8f9fa;font-weight:bold;width:35%;'>Ticket ID</td><td style='padding:8px;'>#{$r['id']}</td></tr>
        <tr><td style='padding:8px;background:#f8f9fa;font-weight:bold;'>Category</td><td style='padding:8px;'>{$r['category']}</td></tr>
        <tr><td style='padding:8px;background:#f8f9fa;font-weight:bold;'>Assigned To</td><td style='padding:8px;'>{$r['assignedTo']}</td></tr>
        <tr><td style='padding:8px;background:#f8f9fa;font-weight:bold;'>Status</td><td style='padding:8px;'><span style='color:#4e73df;font-weight:bold;'>Assigned</span></td></tr>
      </table>
      <p>You will be notified when the issue is resolved. You can also send messages via the Ticket Chat in your dashboard.</p>";

    unifix_notify($user['email'], $user['fullName'], "Ticket #{$r['id']} Assigned - UniFix", buildEmailTemplate("Ticket Assigned", "#4e73df", $body));

    if (!empty($r['assignedSolverUsername'])) {
        $solver = getUserEmail($conn, $r['assignedSolverUsername']);
        $solverBody = "
          <p>Dear <strong>{$solver['fullName']}</strong>,</p>
          <p>A new ticket has been <strong style='color:#f6c23e;'>assigned to you</strong> by the Administration.</p>
          <table style='width:100%;border-collapse:collapse;margin:16px 0;'>
            <tr><td style='padding:8px;background:#f8f9fa;font-weight:bold;width:35%;'>Ticket ID</td><td style='padding:8px;'>#{$r['id']}</td></tr>
            <tr><td style='padding:8px;background:#f8f9fa;font-weight:bold;'>Category</td><td style='padding:8px;'>{$r['category']}</td></tr>
            <tr><td style='padding:8px;background:#f8f9fa;font-weight:bold;'>Description</td><td style='padding:8px;'>" . htmlspecialchars(substr($r['description'], 0, 200)) . "...</td></tr>
          </table>
          <p>Please log in to your Solver Dashboard to view the details and handle this ticket.</p>";

        unifix_notify($solver['email'], $solver['fullName'], "New Ticket Assigned to You - UniFix", buildEmailTemplate("New Assignment", "#f6c23e", $solverBody));
    }
}

/**
 * Notify reporter that their ticket is finished
 */
function notifyFinished($conn, $reportId) {
    $stmt = $conn->prepare("SELECT * FROM reports WHERE id = :id");
    $stmt->execute(['id' => $reportId]);
    $r = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$r) return;

    $user = getUserEmail($conn, $r['reporterUsername']);
    $body = "
      <p>Dear <strong>{$user['fullName']}</strong>,</p>
      <p>Great news! Your problem report has been <strong style='color:#1cc88a;'>resolved</strong>.</p>
      <table style='width:100%;border-collapse:collapse;margin:16px 0;'>
        <tr><td style='padding:8px;background:#f8f9fa;font-weight:bold;width:35%;'>Ticket ID</td><td style='padding:8px;'>#{$r['id']}</td></tr>
        <tr><td style='padding:8px;background:#f8f9fa;font-weight:bold;'>Category</td><td style='padding:8px;'>{$r['category']}</td></tr>
        <tr><td style='padding:8px;background:#f8f9fa;font-weight:bold;'>Resolved By</td><td style='padding:8px;'>{$r['assignedSolverName']}</td></tr>
        <tr><td style='padding:8px;background:#f8f9fa;font-weight:bold;'>Status</td><td style='padding:8px;'><span style='color:#1cc88a;font-weight:bold;'>✅ Finished</span></td></tr>
      </table>
      <p>If the issue persists, you may submit a new report from your dashboard.</p>";

    unifix_notify($user['email'], $user['fullName'], "Ticket #{$r['id']} Resolved - UniFix", buildEmailTemplate("Issue Resolved", "#1cc88a", $body));
}

/**
 * Notify reporter that their ticket was declined
 */
function notifyDeclined($conn, $reportId, $reason) {
    $stmt = $conn->prepare("SELECT * FROM reports WHERE id = :id");
    $stmt->execute(['id' => $reportId]);
    $r = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$r) return;

    $user = getUserEmail($conn, $r['reporterUsername']);
    $body = "
      <p>Dear <strong>{$user['fullName']}</strong>,</p>
      <p>We regret to inform you that your report has been <strong style='color:#e74a3b;'>declined</strong>.</p>
      <table style='width:100%;border-collapse:collapse;margin:16px 0;'>
        <tr><td style='padding:8px;background:#f8f9fa;font-weight:bold;width:35%;'>Ticket ID</td><td style='padding:8px;'>#{$r['id']}</td></tr>
        <tr><td style='padding:8px;background:#f8f9fa;font-weight:bold;'>Category</td><td style='padding:8px;'>{$r['category']}</td></tr>
        <tr><td style='padding:8px;background:#f8f9fa;font-weight:bold;'>Reason</td><td style='padding:8px;color:#e74a3b;'>" . htmlspecialchars($reason) . "</td></tr>
      </table>
      <p>If you believe this was a mistake, please contact your department administrator or submit a new report with more details.</p>";

    unifix_notify($user['email'], $user['fullName'], "Ticket #{$r['id']} Declined - UniFix", buildEmailTemplate("Ticket Declined", "#e74a3b", $body));
}

/**
 * Notify solver that a ticket was delegated to them
 */
function notifyDelegated($conn, $reportId, $newSolverUsername, $note) {
    $stmt = $conn->prepare("SELECT * FROM reports WHERE id = :id");
    $stmt->execute(['id' => $reportId]);
    $r = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$r) return;

    $solver = getUserEmail($conn, $newSolverUsername);
    $body = "
      <p>Dear <strong>{$solver['fullName']}</strong>,</p>
      <p>A ticket has been <strong style='color:#f6c23e;'>delegated to you</strong>.</p>
      <table style='width:100%;border-collapse:collapse;margin:16px 0;'>
        <tr><td style='padding:8px;background:#f8f9fa;font-weight:bold;width:35%;'>Ticket ID</td><td style='padding:8px;'>#{$r['id']}</td></tr>
        <tr><td style='padding:8px;background:#f8f9fa;font-weight:bold;'>Category</td><td style='padding:8px;'>{$r['category']}</td></tr>
        <tr><td style='padding:8px;background:#f8f9fa;font-weight:bold;'>Delegated From</td><td style='padding:8px;'>{$r['delegatedFrom']}</td></tr>
        <tr><td style='padding:8px;background:#f8f9fa;font-weight:bold;'>Note</td><td style='padding:8px;'>" . htmlspecialchars($note ?: 'No note') . "</td></tr>
        <tr><td style='padding:8px;background:#f8f9fa;font-weight:bold;'>Description</td><td style='padding:8px;'>" . htmlspecialchars(substr($r['description'], 0, 200)) . "...</td></tr>
      </table>
      <p>Please log in to your Solver Dashboard to handle this ticket.</p>";

    unifix_notify($solver['email'], $solver['fullName'], "New Ticket Delegated to You - UniFix", buildEmailTemplate("Ticket Delegated", "#f6c23e", $body));
}

/**
 * Notify user they received a warning
 */
function notifyWarning($conn, $username, $reason) {
    $user = getUserEmail($conn, $username);
    $body = "
      <p>Dear <strong>{$user['fullName']}</strong>,</p>
      <p>You have received a <strong style='color:#e74a3b;'>warning</strong> from the UniFix administration.</p>
      <table style='width:100%;border-collapse:collapse;margin:16px 0;'>
        <tr><td style='padding:8px;background:#fff3cd;font-weight:bold;width:35%;'>⚠️ Reason</td><td style='padding:8px;'>" . htmlspecialchars($reason) . "</td></tr>
      </table>
      <p>Please note: <strong>3 warnings will result in account suspension.</strong> Contact admin if you have questions.</p>";

    unifix_notify($user['email'], $user['fullName'], "Warning Issued - UniFix", buildEmailTemplate("Warning Issued", "#f6c23e", $body));
}

/**
 * Notify admin/solver of SLA breach
 */
function notifySLABreach($conn, $reportId) {
    $stmt = $conn->prepare("SELECT r.*, u.fullName as solverName FROM reports r LEFT JOIN users u ON r.assignedSolverUsername = u.username WHERE r.id = :id");
    $stmt->execute(['id' => $reportId]);
    $r = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$r) return;

    if ($r['assignedAdminUsername']) {
        $admin = getUserEmail($conn, $r['assignedAdminUsername']);
        $body = "
          <p>Dear <strong>{$admin['fullName']}</strong>,</p>
          <p>⏱️ A ticket under your management has <strong style='color:#e74a3b;'>breached its SLA deadline</strong>.</p>
          <table style='width:100%;border-collapse:collapse;margin:16px 0;'>
            <tr><td style='padding:8px;background:#f8d7da;font-weight:bold;width:35%;'>Ticket ID</td><td style='padding:8px;'>#{$r['id']}</td></tr>
            <tr><td style='padding:8px;background:#f8d7da;font-weight:bold;'>Category</td><td style='padding:8px;'>{$r['category']}</td></tr>
            <tr><td style='padding:8px;background:#f8d7da;font-weight:bold;'>SLA Deadline</td><td style='padding:8px;color:#e74a3b;'>{$r['slaDeadline']}</td></tr>
            <tr><td style='padding:8px;background:#f8d7da;font-weight:bold;'>Assigned Solver</td><td style='padding:8px;'>{$r['solverName']}</td></tr>
          </table>
          <p>Please take immediate action in the Admin Dashboard.</p>";
        unifix_notify($admin['email'], $admin['fullName'], "🚨 SLA Breach Alert — Ticket #{$r['id']}", buildEmailTemplate("SLA Breach Alert", "#e74a3b", $body));
    }
}
?>
