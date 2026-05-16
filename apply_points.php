<?php
include "config.php";
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Please sign in first.']);
    exit();
}

$uid = (int) $_SESSION['user_id'];
$pointsToUse = (int) ($_POST['points'] ?? 0);
$subtotal = (float) ($_POST['subtotal'] ?? 0);
$voucherDiscount = (float) ($_POST['voucher_discount'] ?? 0);

$balance = user_points_balance($conn, $uid);
$afterVoucher = max(0, $subtotal - $voucherDiscount);

if ($pointsToUse <= 0) {
    echo json_encode([
        'success' => true,
        'points_used' => 0,
        'points_discount' => 0,
        'message' => 'Points removed.',
    ]);
    exit();
}

$maxPoints = max_redeemable_points($balance, $afterVoucher);
if ($pointsToUse > $maxPoints) {
    echo json_encode(['error' => "You can use up to $maxPoints points on this order (100 pts = RM1)."]);
    exit();
}

$discount = points_to_rm($pointsToUse);
echo json_encode([
    'success' => true,
    'points_used' => $pointsToUse,
    'points_discount' => $discount,
    'message' => "RM " . number_format($discount, 2) . " off using $pointsToUse points.",
]);
