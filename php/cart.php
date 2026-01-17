<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($action === 'add') {
    $id = $_POST['id'];
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]++;
    } else {
        $_SESSION['cart'][$id] = 1;
    }
    echo json_encode(['success' => true, 'count' => array_sum($_SESSION['cart'])]);

} elseif ($action === 'update') {
    $id = $_POST['id'];
    $qty = max(1, intval($_POST['qty']));
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] = $qty;
    }
    echo json_encode(['success' => true]);

} elseif ($action === 'remove') {
    $id = $_POST['id'];
    unset($_SESSION['cart'][$id]);
    echo json_encode(['success' => true]);

} elseif ($action === 'clear') {
    $_SESSION['cart'] = [];
    echo json_encode(['success' => true]);

} elseif ($action === 'fetch') {
    if (empty($_SESSION['cart'])) {
        echo json_encode(['items' => [], 'total' => 0]);
        exit;
    }

    $ids = array_keys($_SESSION['cart']);
    $in = str_repeat('?,', count($ids) - 1) . '?';

    try {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($in)");
        $stmt->execute($ids);
        $products = $stmt->fetchAll();

        $cartItems = [];
        $total = 0;

        foreach ($products as $p) {
            $qty = $_SESSION['cart'][$p['id']];
            $subtotal = $p['price'] * $qty;
            $total += $subtotal;

            $p['qty'] = $qty;
            $p['subtotal'] = $subtotal;
            // Ensure frontend also gets 'nom' if it expects it, or 'name' 
            // We'll normalize to 'name' but keep 'nom' for safety if frontend hasn't updated yet?
            // User requested CLEAN code, so I'll switch frontend to 'name'.
            $cartItems[] = $p;
        }

        echo json_encode(['items' => $cartItems, 'total' => $total]);
    } catch (Exception $e) {
        echo json_encode(['error' => 'Database error']);
    }
}
?>