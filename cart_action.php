<?php
include "config.php";
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Please sign in first.']);
    exit();
}

$uid = (int) $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

if ($action === 'count') {
    echo json_encode(['cart_count' => get_cart_count($conn, $uid)]);
    exit();
}

if ($action === 'add') {
    $pid = (int) ($_POST['product_id'] ?? 0);
    $product = $conn->query("SELECT id, stock FROM products WHERE id = $pid")->fetch_assoc();
    if (!$product) {
        echo json_encode(['error' => 'Product not found.']);
        exit();
    }
    if ((int) $product['stock'] < 1) {
        echo json_encode(['error' => 'This product is out of stock.']);
        exit();
    }

    $existing = $conn->query("SELECT id, quantity FROM cart WHERE user_id = $uid AND product_id = $pid")->fetch_assoc();
    if ($existing) {
        $newQty = (int) $existing['quantity'] + 1;
        if ($newQty > (int) $product['stock']) {
            echo json_encode(['error' => 'Cannot add more than available stock.']);
            exit();
        }
        $conn->query("UPDATE cart SET quantity = $newQty WHERE id = " . (int) $existing['id']);
    } else {
        $conn->query("INSERT INTO cart (user_id, product_id, quantity) VALUES ($uid, $pid, 1)");
    }
}

if ($action === 'update') {
    $cid = (int) ($_POST['cart_id'] ?? 0);
    $qty = (int) ($_POST['quantity'] ?? 0);
    $row = $conn->query("
        SELECT c.id, p.stock
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.id = $cid AND c.user_id = $uid
    ")->fetch_assoc();

    if (!$row) {
        echo json_encode(['error' => 'Cart item not found.']);
        exit();
    }

    if ($qty <= 0) {
        $conn->query("DELETE FROM cart WHERE id = $cid AND user_id = $uid");
    } else {
        if ($qty > (int) $row['stock']) {
            echo json_encode(['error' => 'Quantity exceeds available stock.']);
            exit();
        }
        $conn->query("UPDATE cart SET quantity = $qty WHERE id = $cid AND user_id = $uid");
    }
}

if ($action === 'remove') {
    $cid = (int) ($_POST['cart_id'] ?? 0);
    $conn->query("DELETE FROM cart WHERE id = $cid AND user_id = $uid");
}

echo json_encode(['cart_count' => get_cart_count($conn, $uid)]);
