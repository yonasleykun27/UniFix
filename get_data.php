<?php
header('Content-Type: application/json');
require_once 'db_connect.php';
require_once 'auth_check.php';
requireAuth();

try {
    $conn = getDBConnection();
    
    $usersQuery = $conn->query("SELECT * FROM users");
    $users = $usersQuery->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($users as &$u) {
        $u['firebaseId'] = $u['id']; // map to maintain JS compatibility
        $u['warnings'] = (int)$u['warnings'];
        $u['isBanned'] = (bool)$u['isBanned'];
        $u['isOnLeave'] = (bool)($u['isOnLeave'] ?? false);
        $u['warningHistory'] = !empty($u['warningHistory']) ? json_decode($u['warningHistory'], true) : [];
        if (isset($u['userId'])) {
            $u['id'] = $u['userId'];
        }
    }
    
    $reportsQuery = $conn->query("
        SELECT r.*, u.fullName as reporterName, u.userId as reporterId, u.role as reporterRole 
        FROM reports r 
        LEFT JOIN users u ON r.reporterUsername = u.username
        ORDER BY r.createdAt DESC
    ");
    $reports = $reportsQuery->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($reports as &$r) {
        $r['id'] = (int)$r['id'];
        $r['firebaseId'] = $r['id']; // map to maintain JS compatibility
        $r['hiddenFromAdmin'] = (bool)$r['hiddenFromAdmin'];
        $r['hiddenFromSolver'] = (bool)$r['hiddenFromSolver'];
        $r['hiddenFromReporter'] = (bool)$r['hiddenFromReporter'];
        $r['slaEscalated'] = (bool)($r['slaEscalated'] ?? false);
        $r['reporterPhone'] = $r['phone']; // Map back to JS expected key
        $r['date'] = explode(" ", $r['createdAt'])[0]; // Map createdAt to JS expected 'date' field
        if (isset($r['specificDetails']) && $r['specificDetails']) {
            $r['specificDetails'] = json_decode($r['specificDetails'], true);
        }
        $msgStmt = $conn->prepare("SELECT COUNT(*) FROM ticket_messages WHERE reportId = :rid");
        $msgStmt->execute(['rid' => $r['id']]);
        $r['messageCount'] = (int)$msgStmt->fetchColumn();
    }
    
    echo json_encode(["success" => true, "users" => $users, "reports" => $reports]);
} catch(PDOException $e) {
    echo json_encode(["success" => false, "message" => "DB Error: " . $e->getMessage()]);
}
?>
