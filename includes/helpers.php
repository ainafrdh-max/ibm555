<?php

define('POINTS_PER_RM', 100); // 100 points = RM 1.00 discount

function get_cart_count(mysqli $conn, int $userId): int
{
    $userId = (int) $userId;
    $row = $conn->query("SELECT COALESCE(SUM(quantity), 0) AS c FROM cart WHERE user_id = $userId")->fetch_assoc();
    return (int) ($row['c'] ?? 0);
}

function is_recommended_product(array $product): bool
{
    return ($product['type'] ?? '') === 'liquid'
        && strcasecmp(trim((string) ($product['variant'] ?? '')), 'Peach') === 0;
}

function get_checkout_recommendations(mysqli $conn, array $cartProductIds, int $limit = 3): array
{
    $limit = max(1, min(6, $limit));
    $exclude = array_map('intval', $cartProductIds);
    $items = [];

    $recRes = $conn->query("SELECT * FROM products WHERE type = 'liquid' AND variant = 'Peach' AND stock > 0 LIMIT 1");
    if ($recRes && ($rec = $recRes->fetch_assoc()) && !in_array((int) $rec['id'], $exclude, true)) {
        $items[] = $rec;
        $exclude[] = (int) $rec['id'];
    }

    if (count($items) < $limit) {
        $excludeSql = $exclude ? implode(',', $exclude) : '0';
        $need = $limit - count($items);
        $res = $conn->query("SELECT * FROM products WHERE stock > 0 AND id NOT IN ($excludeSql) ORDER BY type, id LIMIT $need");
        if ($res) {
            while ($p = $res->fetch_assoc()) {
                $items[] = $p;
            }
        }
    }

    return $items;
}

function product_image_src(?string $filename): string
{
    $filename = trim((string) $filename);
    if ($filename === '') {
        return 'img/blank-logo.png';
    }

    $imgDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR;
    if (is_file($imgDir . $filename)) {
        return 'img/' . rawurlencode($filename);
    }

    $fallbacks = [
        'blank-sweet nectar (soft).png' => 'blank-black-rose.png',
        'blank-sweet nectar (strong).png' => 'blank-black-rose.png',
        'blank-dreamy melon.png' => 'blank-black.png',
        'blank-lemon.png' => 'blank-lemon.png',
        'blank-rose.png' => 'blank-rose.png',
        'blank-summer.png' => 'blank-summer.png',
    ];

    $fallback = $fallbacks[$filename] ?? 'blank-logo.png';
    if (is_file($imgDir . $fallback)) {
        return 'img/' . rawurlencode($fallback);
    }

    return 'img/blank-logo.png';
}

function mask_card_number(string $number): string
{
    $digits = preg_replace('/\D/', '', $number);
    if (strlen($digits) < 4) {
        return '';
    }
    $last4 = substr($digits, -4);
    return '**** **** **** ' . $last4;
}

function detect_card_brand(string $number): string
{
    $digits = preg_replace('/\D/', '', $number);
    if (preg_match('/^4/', $digits)) {
        return 'Visa';
    }
    if (preg_match('/^5[1-5]/', $digits) || preg_match('/^2[2-7]/', $digits)) {
        return 'Mastercard';
    }
    if (preg_match('/^3[47]/', $digits)) {
        return 'Amex';
    }
    return 'Card';
}

function user_order_count(mysqli $conn, int $userId): int
{
    $userId = (int) $userId;
    $row = $conn->query("SELECT COUNT(*) AS c FROM orders WHERE user_id = $userId")->fetch_assoc();
    return (int) ($row['c'] ?? 0);
}

function user_points_balance(mysqli $conn, int $userId): int
{
    $userId = (int) $userId;
    $row = $conn->query("SELECT total_points FROM user_points WHERE user_id = $userId")->fetch_assoc();
    return (int) ($row['total_points'] ?? 0);
}

function user_has_used_voucher(mysqli $conn, int $userId, int $voucherId): bool
{
    $userId = (int) $userId;
    $voucherId = (int) $voucherId;
    $row = $conn->query("SELECT id FROM voucher_uses WHERE user_id = $userId AND voucher_id = $voucherId LIMIT 1")->fetch_assoc();
    return (bool) $row;
}

function get_voucher_by_code(mysqli $conn, string $code): ?array
{
    $code = mysqli_real_escape_string($conn, strtoupper(trim($code)));
    $row = $conn->query("SELECT * FROM vouchers WHERE code = '$code' AND is_active = 1")->fetch_assoc();
    return $row ?: null;
}

function validate_voucher_for_user(mysqli $conn, int $userId, array $voucher, float $subtotal): ?string
{
    if ($voucher['expires_at'] && strtotime($voucher['expires_at']) < time()) {
        return 'This voucher has expired.';
    }
    if ((int) $voucher['used_count'] >= (int) $voucher['max_uses']) {
        return 'This voucher has reached its global usage limit.';
    }
    if ($subtotal < (float) $voucher['min_order']) {
        return 'Minimum order of RM ' . number_format((float) $voucher['min_order'], 2) . ' required.';
    }
    if (user_has_used_voucher($conn, $userId, (int) $voucher['id'])) {
        return 'You have already used this voucher. Each voucher can only be used once per account.';
    }
    if (!empty($voucher['new_user_only']) && user_order_count($conn, $userId) > 0) {
        return 'This voucher is for new customers on their first order only.';
    }
    return null;
}

function get_available_vouchers(mysqli $conn, int $userId): array
{
    $userId = (int) $userId;
    $orderCount = user_order_count($conn, $userId);
    $list = [];
    $res = $conn->query("SELECT * FROM vouchers WHERE is_active = 1 ORDER BY id");
    while ($v = $res->fetch_assoc()) {
        $used = user_has_used_voucher($conn, $userId, (int) $v['id']);
        $expired = $v['expires_at'] && strtotime($v['expires_at']) < time();
        $globalLimit = (int) $v['used_count'] >= (int) $v['max_uses'];
        $newUserOnly = !empty($v['new_user_only']);
        $eligible = !$used && !$expired && !$globalLimit && (!$newUserOnly || $orderCount === 0);

        $list[] = [
            'code' => $v['code'],
            'discount_percent' => (float) $v['discount_percent'],
            'min_order' => (float) $v['min_order'],
            'expires_at' => $v['expires_at'],
            'new_user_only' => $newUserOnly,
            'eligible' => $eligible,
            'reason' => $used ? 'Already used' : ($expired ? 'Expired' : ($globalLimit ? 'Fully redeemed' : ($newUserOnly && $orderCount > 0 ? 'First order only' : ''))),
        ];
    }
    return $list;
}

function points_to_rm(int $points): float
{
    return round($points / POINTS_PER_RM, 2);
}

function max_redeemable_points(int $balance, float $subtotalAfterVoucher): int
{
    $maxBySubtotal = (int) floor($subtotalAfterVoucher * POINTS_PER_RM);
    return max(0, min($balance, $maxBySubtotal));
}

function ensure_order_columns(mysqli $conn): void
{
    static $done = false;
    if ($done) {
        return;
    }
    $done = true;

    $orderColumns = [
        'delivery_name' => "VARCHAR(150) DEFAULT NULL",
        'delivery_phone' => "VARCHAR(30) DEFAULT NULL",
        'delivery_email' => "VARCHAR(150) DEFAULT NULL",
        'delivery_address' => "TEXT DEFAULT NULL",
        'payment_type' => "ENUM('card','fpx') DEFAULT 'card'",
        'card_holder' => "VARCHAR(150) DEFAULT NULL",
        'card_number_masked' => "VARCHAR(30) DEFAULT NULL",
        'card_expiry' => "VARCHAR(7) DEFAULT NULL",
        'card_brand' => "VARCHAR(30) DEFAULT NULL",
        'fpx_bank' => "VARCHAR(100) DEFAULT NULL",
        'points_redeemed' => "INT NOT NULL DEFAULT 0",
        'points_discount' => "DECIMAL(10,2) NOT NULL DEFAULT 0.00",
    ];

    foreach ($orderColumns as $name => $definition) {
        $check = $conn->query("SHOW COLUMNS FROM orders LIKE '$name'");
        if ($check && $check->num_rows === 0) {
            $conn->query("ALTER TABLE orders ADD COLUMN $name $definition");
        }
    }

    $check = $conn->query("SHOW COLUMNS FROM products LIKE 'description'");
    if ($check && $check->num_rows === 0) {
        $conn->query("ALTER TABLE products ADD COLUMN description TEXT DEFAULT NULL");
    }

    $check = $conn->query("SHOW COLUMNS FROM vouchers LIKE 'new_user_only'");
    if ($check && $check->num_rows === 0) {
        $conn->query("ALTER TABLE vouchers ADD COLUMN new_user_only TINYINT(1) NOT NULL DEFAULT 0");
    }

    $conn->query("CREATE TABLE IF NOT EXISTS voucher_uses (
        id INT(11) NOT NULL AUTO_INCREMENT,
        user_id INT(11) NOT NULL,
        voucher_id INT(11) NOT NULL,
        order_id INT(11) DEFAULT NULL,
        used_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY user_voucher (user_id, voucher_id),
        KEY voucher_id (voucher_id),
        CONSTRAINT voucher_uses_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        CONSTRAINT voucher_uses_voucher FOREIGN KEY (voucher_id) REFERENCES vouchers(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}
