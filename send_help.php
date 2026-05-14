<?php
header('Content-Type: application/json');
require_once 'db_connect.php';
require_once 'notify.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid data format']);
    exit;
}

$name = htmlspecialchars($data['name'] ?? 'Unknown');
$email = htmlspecialchars($data['email'] ?? 'Unknown');
$message = htmlspecialchars($data['message'] ?? '');

try {
    $conn = getDBConnection();
    $stmt = $conn->prepare("INSERT INTO help_requests (name, email, message) VALUES (:n, :e, :m)");
    $stmt->execute(['n' => $name, 'e' => $email, 'm' => $message]);

    $userSubject = "We received your request - UniFix Support";
    $userBodyHtml = "
      <p>Dear <strong>$name</strong>,</p>
      <p>Thank you for reaching out! We have received your message and our support team will get back to you as soon as possible.</p>
      <table style='width:100%;border-collapse:collapse;margin:16px 0;'>
        <tr><td style='padding:8px;background:#f8f9fa;font-weight:bold;width:30%;'>Your Message</td><td style='padding:8px;'>$message</td></tr>
      </table>
      <p>If you have any further details to add, please wait for our official reply to this email thread.</p>";
    unifix_notify($email, $name, $userSubject, buildEmailTemplate("Help Request Received", "#4e73df", $userBodyHtml));

    // 2. Send the Request details to the Admin (Fast Email Reply workflow)
    $adminEmail = 'yonasleykun27@gmail.com';
    $adminSubject = "New Help Request: $name";
    $adminBodyHtml = "
      <p>You have received a new help request from the UniFix platform homepage.</p>
      <table style='width:100%;border-collapse:collapse;margin:16px 0;'>
        <tr><td style='padding:8px;background:#f8f9fa;font-weight:bold;width:30%;'>Sender Name</td><td style='padding:8px;'>$name</td></tr>
        <tr><td style='padding:8px;background:#f8f9fa;font-weight:bold;'>Sender Email</td><td style='padding:8px;'>$email</td></tr>
        <tr><td style='padding:8px;background:#f8f9fa;font-weight:bold;'>Message</td><td style='padding:8px;'>$message</td></tr>
      </table>
      <p>Please reply directly to the sender at <strong>$email</strong>.</p>";
    unifix_notify($adminEmail, "UniFix Admin", $adminSubject, buildEmailTemplate("New Help Request", "#00ff88", $adminBodyHtml));

    echo json_encode(['success' => true, 'message' => 'Message sent successfully.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
