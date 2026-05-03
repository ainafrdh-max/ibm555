<?php
include "config.php";
if (!isset($_SESSION['user_id'])) { echo json_encode(['error'=>'not logged in']); exit(); }

$code = strtoupper(trim($_POST['code'] ?? ''));
$subtotal = (float)($_POST['subtotal'] ?? 0);

if (!$code) { echo json_encode(['error'=>'No code entered']); exit(); }

$code_safe = mysqli_real_escape_string($conn, $code);
$v = $conn->query("SELECT * FROM vouchers WHERE code='$code_safe' AND is_active=1")->fetch_assoc();

if (!$v) {
    echo json_encode(['error'=>'Invalid or expired voucher code']); exit();
}
if ($v['expires_at'] && strtotime($v['expires_at']) < time()) {
    echo json_encode(['error'=>'This voucher has expired']); exit();
}
if ($v['used_count'] >= $v['max_uses']) {
    echo json_encode(['error'=>'This voucher has reached its usage limit']); exit();
}
if ($subtotal < $v['min_order']) {
    echo json_encode(['error'=>"Minimum order of RM".number_format($v['min_order'],2)." required"]); exit();
}

$discount = round($subtotal * ($v['discount_percent'] / 100), 2);
echo json_encode([
    'success' => true,
    'discount_percent' => $v['discount_percent'],
    'discount_amount' => $discount,
    'message' => $v['discount_percent'].'% discount applied!'
]);
