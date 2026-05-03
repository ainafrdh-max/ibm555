<?php
include "config.php";
if (!isset($_SESSION['user_id'])) { echo json_encode(['error'=>'not logged in']); exit(); }
$uid = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

if ($action === 'add') {
    $pid = (int)$_POST['product_id'];
    $existing = $conn->query("SELECT id, quantity FROM cart WHERE user_id=$uid AND product_id=$pid")->fetch_assoc();
    if ($existing) {
        $conn->query("UPDATE cart SET quantity=quantity+1 WHERE id={$existing['id']}");
    } else {
        $conn->query("INSERT INTO cart (user_id, product_id, quantity) VALUES ($uid, $pid, 1)");
    }
}

if ($action === 'update') {
    $cid = (int)$_POST['cart_id'];
    $qty = (int)$_POST['quantity'];
    if ($qty <= 0) {
        $conn->query("DELETE FROM cart WHERE id=$cid AND user_id=$uid");
    } else {
        $conn->query("UPDATE cart SET quantity=$qty WHERE id=$cid AND user_id=$uid");
    }
}

if ($action === 'remove') {
    $cid = (int)$_POST['cart_id'];
    $conn->query("DELETE FROM cart WHERE id=$cid AND user_id=$uid");
}

$cc = $conn->query("SELECT SUM(quantity) as c FROM cart WHERE user_id=$uid")->fetch_assoc();
echo json_encode(['cart_count' => (int)($cc['c'] ?? 0)]);
