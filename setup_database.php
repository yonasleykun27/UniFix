<?php

require_once 'db_connect.php';

try {
    $sql_create_db = "CREATE DATABASE IF NOT EXISTS $db_name CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    $conn->exec($sql_create_db);
    echo "<div style='color:green;'>Database '$db_name' created successfully or already exists.</div>";

    $db = getDBConnection();

    $sql_create_users_table = "
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        userId VARCHAR(50) UNIQUE NOT NULL,
        username VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        fullName VARCHAR(255) NOT NULL,
        role ENUM('Admin', 'Solver', 'Student', 'Teacher') NOT NULL,
        jobTitle VARCHAR(100) DEFAULT NULL,
        warnings INT DEFAULT 0,
        isBanned BOOLEAN DEFAULT FALSE,
        isOnLeave BOOLEAN DEFAULT FALSE,
        lastWarningReason TEXT DEFAULT NULL,
        warningHistory TEXT DEFAULT NULL,
        dept VARCHAR(100) DEFAULT NULL,
        yearOfStudy VARCHAR(10) DEFAULT NULL,
        blockNumber VARCHAR(20) DEFAULT NULL,
        dormNumber VARCHAR(20) DEFAULT NULL,
        phone VARCHAR(30) DEFAULT NULL,
        email VARCHAR(255) DEFAULT NULL,
        idPhotoFront VARCHAR(255) DEFAULT NULL,
        idPhotoBack VARCHAR(255) DEFAULT NULL,
        createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $db->exec($sql_create_users_table);
    echo "<div style='color:green;'>Table 'users' created successfully.</div>";

    $sql_create_reports_table = "
    CREATE TABLE IF NOT EXISTS reports (
        id INT AUTO_INCREMENT PRIMARY KEY,
        category VARCHAR(100) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        urgency VARCHAR(50) NOT NULL,
        description TEXT NOT NULL,
        status ENUM('Pending', 'Assigned', 'In Progress', 'Finished', 'Declined') DEFAULT 'Pending',
        reporterUsername VARCHAR(100) NOT NULL,
        assignedTo VARCHAR(100) DEFAULT NULL,
        assignedSolverUsername VARCHAR(100) DEFAULT NULL,
        assignedSolverName VARCHAR(255) DEFAULT NULL,
        assignedPendingAdmin VARCHAR(100) DEFAULT NULL,
        assignedAdminUsername VARCHAR(100) DEFAULT NULL,
        specificDetails TEXT DEFAULT NULL,
        declineReason TEXT DEFAULT NULL,
        photoUrl VARCHAR(255) DEFAULT NULL,
        hiddenFromAdmin BOOLEAN DEFAULT FALSE,
        hiddenFromSolver BOOLEAN DEFAULT FALSE,
        hiddenFromReporter BOOLEAN DEFAULT FALSE,
        createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (reporterUsername) REFERENCES users(username) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";

    $db->exec($sql_create_reports_table);
    echo "<div style='color:green;'>Table 'reports' created successfully.</div>";

    $sql_create_help_requests_table = "
    CREATE TABLE IF NOT EXISTS help_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(150) NOT NULL,
        message TEXT NOT NULL,
        status ENUM('Pending', 'Replied') DEFAULT 'Pending',
        reply_message TEXT DEFAULT NULL,
        replied_at TIMESTAMP NULL DEFAULT NULL,
        replied_by VARCHAR(100) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";

    $db->exec($sql_create_help_requests_table);
    echo "<div style='color:green;'>Table 'help_requests' created successfully.</div>";

    $sql_create_ticket_messages_table = "
    CREATE TABLE IF NOT EXISTS ticket_messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        reportId INT NOT NULL,
        senderUsername VARCHAR(100) NOT NULL,
        senderRole VARCHAR(50) NOT NULL,
        message TEXT NOT NULL,
        visibility ENUM('public', 'admin_only', 'student_only') DEFAULT 'public',
        createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (reportId) REFERENCES reports(id) ON DELETE CASCADE,
        FOREIGN KEY (senderUsername) REFERENCES users(username) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";

    $db->exec($sql_create_ticket_messages_table);
    echo "<div style='color:green;'>Table 'ticket_messages' created successfully.</div>";

    $sql_create_notifications_table = "
    CREATE TABLE IF NOT EXISTS notifications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) NOT NULL,
        message TEXT NOT NULL,
        link_report_id INT DEFAULT NULL,
        is_read BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (username) REFERENCES users(username) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";

    $db->exec($sql_create_notifications_table);
    echo "<div style='color:green;'>Table 'notifications' created successfully.</div>";

    $check_admin = $db->query("SELECT * FROM users WHERE username = 'admin1001'");
    if ($check_admin->rowCount() == 0) {
        $insert_admin = "INSERT INTO users (userId, username, password, fullName, role) 
                         VALUES ('DBU-ADM-1001', 'admin1001', 'password123', 'System Admin 1', 'Admin')";
        $db->exec($insert_admin);
        echo "<div style='color:blue;'>Default Admin (admin1001) seeded.</div>";
    }

    $check_solver = $db->query("SELECT * FROM users WHERE username = 'solver2001'");
    if ($check_solver->rowCount() == 0) {
        $insert_solver = "INSERT INTO users (userId, username, password, fullName, role, jobTitle) 
                         VALUES ('DBU-SLV-2001', 'solver2001', 'password123', 'Staff General Technician', 'Solver', 'Staff General Technician')";
        $db->exec($insert_solver);
        echo "<div style='color:blue;'>Default Solver (solver2001) seeded.</div>";
    }

    // --- PATCH EXISTING DATABASES (For imported old DBs on new PCs) ---
    $queries = [
        "ALTER TABLE users ADD COLUMN email VARCHAR(255) DEFAULT NULL",
        "ALTER TABLE users ADD COLUMN dept VARCHAR(100) DEFAULT NULL",
        "ALTER TABLE users ADD COLUMN yearOfStudy VARCHAR(10) DEFAULT NULL",
        "ALTER TABLE users ADD COLUMN blockNumber VARCHAR(20) DEFAULT NULL",
        "ALTER TABLE users ADD COLUMN dormNumber VARCHAR(20) DEFAULT NULL",
        "ALTER TABLE users ADD COLUMN phone VARCHAR(30) DEFAULT NULL",
        "ALTER TABLE users ADD COLUMN idPhotoFront VARCHAR(255) DEFAULT NULL",
        "ALTER TABLE users ADD COLUMN idPhotoBack VARCHAR(255) DEFAULT NULL",
        "ALTER TABLE users ADD COLUMN warnings INT DEFAULT 0",
        "ALTER TABLE users ADD COLUMN isBanned BOOLEAN DEFAULT FALSE",
        "ALTER TABLE users ADD COLUMN isOnLeave BOOLEAN DEFAULT FALSE",
        "ALTER TABLE users ADD COLUMN warningHistory TEXT DEFAULT NULL",
        "ALTER TABLE users ADD COLUMN lastWarningReason TEXT DEFAULT NULL",
        "ALTER TABLE reports ADD COLUMN photoUrl VARCHAR(255) DEFAULT NULL",
        "ALTER TABLE reports ADD COLUMN specificDetails TEXT DEFAULT NULL",
        "ALTER TABLE reports ADD COLUMN declineReason TEXT DEFAULT NULL",
        "ALTER TABLE reports ADD COLUMN assignedPendingAdmin VARCHAR(100) DEFAULT NULL",
        "ALTER TABLE reports ADD COLUMN delegatedFrom VARCHAR(100) DEFAULT NULL",
        "ALTER TABLE reports ADD COLUMN delegationNote TEXT DEFAULT NULL",
        "ALTER TABLE reports ADD COLUMN delegationStatus VARCHAR(50) DEFAULT NULL",
        "ALTER TABLE reports ADD COLUMN slaDeadline DATETIME DEFAULT NULL",
        "ALTER TABLE reports ADD COLUMN slaEscalated BOOLEAN DEFAULT FALSE",
        "ALTER TABLE reports ADD COLUMN hiddenFromAdmin BOOLEAN DEFAULT FALSE",
        "ALTER TABLE reports ADD COLUMN hiddenFromSolver BOOLEAN DEFAULT FALSE",
        "ALTER TABLE reports ADD COLUMN hiddenFromReporter BOOLEAN DEFAULT FALSE",
        "ALTER TABLE ticket_messages ADD COLUMN visibility ENUM('public', 'admin_only', 'student_only') DEFAULT 'public'"
    ];

    foreach ($queries as $query) {
        try {
            $db->exec($query);
        } catch (PDOException $e) {
            // 1060 is "Duplicate column name", which is fine. Ignore it.
        }
    }

    // Expand visibility ENUM if it already exists but is missing 'student_only'
    try {
        $db->exec("ALTER TABLE ticket_messages MODIFY COLUMN visibility ENUM('public', 'admin_only', 'student_only') DEFAULT 'public'");
    } catch (PDOException $e) {
        // Ignore if table doesn't exist yet or other harmless error
    }

    echo "<br><h3>✅ Database Setup & Patching Complete!</h3>";
    echo "<p>You can now start migrating the frontend Javascript to use PHP endpoints.</p>";

} catch (PDOException $e) {
    echo "<div style='color:red;'>Error creating tables/database: " . $e->getMessage() . "</div>";
}
?>
