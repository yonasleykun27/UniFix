<?php
header('Content-Type: application/json');
require_once 'db_connect.php';

// Support both JSON (staff add) and FormData (student/teacher registration with photos)
$isFormData = !empty($_POST);

if ($isFormData) {
    $data = $_POST;
} else {
    $data = json_decode(file_get_contents("php://input"), true);
}

if(!$data) {
    echo json_encode(["success" => false, "message" => "Invalid input."]);
    exit;
}

try {
    $conn = getDBConnection();
    
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = :username OR userId = :userId");
    $stmt->execute(['username' => $data['username'], 'userId' => $data['id']]);
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(["success" => false, "message" => "Duplicate ID or Username."]);
        exit;
    }

    $idPhotoFrontPath = null;
    $idPhotoBackPath  = null;
    $uploadDir = 'uploads/id_photos/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    foreach (['idPhotoFront' => &$idPhotoFrontPath, 'idPhotoBack' => &$idPhotoBackPath] as $field => &$pathVar) {
        if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','gif','webp'])) {
                $filename = uniqid($field . '_') . '.' . $ext;
                $dest = $uploadDir . $filename;
                if (move_uploaded_file($_FILES[$field]['tmp_name'], $dest)) {
                    $pathVar = $dest;
                }
            }
        }
    }

    $stmt = $conn->prepare("
        INSERT INTO users 
            (userId, username, password, fullName, email, role, jobTitle, warnings, isBanned, dept, yearOfStudy, blockNumber, dormNumber, phone, idPhotoFront, idPhotoBack)
        VALUES 
            (:userId, :username, :password, :fullName, :email, :role, :jobTitle, 0, 0, :dept, :yearOfStudy, :blockNumber, :dormNumber, :phone, :idPhotoFront, :idPhotoBack)
    ");
    
    $stmt->execute([
        'userId'       => $data['id'],
        'username'     => $data['username'],
        'password'     => password_hash($data['password'], PASSWORD_BCRYPT),
        'fullName'     => $data['fullName'],
        'email'        => $data['email'] ?? null,
        'role'         => $data['role'],
        'jobTitle'     => $data['jobTitle'] ?? null,
        'dept'         => $data['dept'] ?? null,
        'yearOfStudy'  => $data['year'] ?? null,
        'blockNumber'  => $data['block'] ?? null,
        'dormNumber'   => $data['dorm'] ?? null,
        'phone'        => $data['phone'] ?? null,
        'idPhotoFront' => $idPhotoFrontPath,
        'idPhotoBack'  => $idPhotoBackPath,
    ]);
    
    echo json_encode(["success" => true]);
} catch(PDOException $e) {
    echo json_encode(["success" => false, "message" => "DB Error: " . $e->getMessage()]);
}
?>
