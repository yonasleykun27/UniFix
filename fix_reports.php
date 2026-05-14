<?php
require 'db_connect.php';

try {
    $db = getDBConnection();
    
    // List of columns to safely add if they are missing from an older database import
    $queries = [
        "ALTER TABLE reports ADD COLUMN delegatedFrom VARCHAR(100) DEFAULT NULL",
        "ALTER TABLE reports ADD COLUMN delegationNote TEXT DEFAULT NULL",
        "ALTER TABLE reports ADD COLUMN delegationStatus VARCHAR(50) DEFAULT NULL",
        "ALTER TABLE reports ADD COLUMN slaDeadline DATETIME DEFAULT NULL",
        "ALTER TABLE reports ADD COLUMN slaEscalated BOOLEAN DEFAULT FALSE",
        "ALTER TABLE reports ADD COLUMN hiddenFromAdmin BOOLEAN DEFAULT FALSE",
        "ALTER TABLE reports ADD COLUMN hiddenFromSolver BOOLEAN DEFAULT FALSE",
        "ALTER TABLE reports ADD COLUMN hiddenFromReporter BOOLEAN DEFAULT FALSE"
    ];

    echo "<h3>Patching Reports table...</h3>";
    
    foreach ($queries as $query) {
        try {
            $db->exec($query);
            echo "<p style='color:green'>Added column successfully: " . htmlspecialchars($query) . "</p>";
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1060) {
                echo "<p style='color:gray'>Column already exists, skipping: " . htmlspecialchars($query) . "</p>";
            } else {
                echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
            }
        }
    }
    
    echo "<h3>✅ Database Patch Complete! The report submission should now work.</h3>";

} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
