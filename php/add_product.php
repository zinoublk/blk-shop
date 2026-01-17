<?php
require_once 'db.php';
require_once 'auth.php';
checkAdminAuth();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? '';

    if (empty($name) || empty($price) || !isset($_FILES['image'])) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit;
    }

    $target_dir = "../uploads/";
    $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
    $new_filename = uniqid() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;

    $allowed_types = ['jpg', 'jpeg', 'png'];
    if (!in_array($file_extension, $allowed_types)) {
        echo json_encode(['success' => false, 'message' => 'Invalid file type']);
        exit;
    }

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO products (name, price, image) VALUES (?, ?, ?)");
            $stmt->execute([$name, $price, $new_filename]);
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'File upload failed']);
    }
}
?>