<?php
require_once 'db.php';
require_once 'auth.php';
checkAdminAuth();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? '';

    if (empty($id) || empty($name) || empty($price)) {
        echo json_encode(['success' => false, 'message' => 'Missing fields']);
        exit;
    }

    try {
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
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
                $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
                $stmt->execute([$id]);
                $old_prod = $stmt->fetch();
                if ($old_prod && file_exists("../uploads/" . $old_prod['image'])) {
                    unlink("../uploads/" . $old_prod['image']);
                }

                $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, image = ? WHERE id = ?");
                $stmt->execute([$name, $price, $new_filename, $id]);
            }
        } else {
            $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ? WHERE id = ?");
            $stmt->execute([$name, $price, $id]);
        }
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
}
?>