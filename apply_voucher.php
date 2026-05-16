<?php
include "config.php";
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Please sign in first.']);
    exit();
}

$uid = (int) $_SESSION['user_id'];
$code = strtoupper(trim($_POST['code'] ?? ''));
$subtotal = (float) ($_POST['subtotal'] ?? 0);

if (!$code) {
    echo json_encode(['error' => 'No code entered']);
    exit();
}

$voucher = get_voucher_by_code($conn, $code);
if (!$voucher) {
    echo json_encode(['error' => 'Invalid or expired voucher code']);
    exit();
}

$error = validate_voucher_for_user($conn, $uid, $voucher, $subtotal);
if ($error) {
    echo json_encode(['error' => $error]);
    exit();
}

$discount = round($subtotal * ((float) $voucher['discount_percent'] / 100), 2);
echo json_encode([
    'success' => true,
    'discount_percent' => (float) $voucher['discount_percent'],
    'discount_amount' => $discount,
    'message' => $voucher['discount_percent'] . '% discount applied!',
]);
