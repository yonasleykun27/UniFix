<?php

$host     = '127.0.0.1';
$db_name  = 'unifix_db';
$username = 'root';
$password = '';

// Top-level connection (for scripts that use $conn directly) — throws instead of die
try {
    $conn = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    throw new RuntimeException("DB connection failed: " . $e->getMessage());
}

function getDBConnection() {
    global $host, $db_name, $username, $password;
    try {
        $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $conn;
    } catch (PDOException $e) {
        throw new RuntimeException("DB connection to '{$db_name}' failed: " . $e->getMessage());
    }
}
?>