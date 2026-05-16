<?php
require dirname(__DIR__) . '/config.php';

$nectarSoft = 'A gentle peach fragrance with a smooth and comforting scent experience. Lightly sweet, clean, and calming — perfect for those who enjoy a softer fruity aroma that feels relaxing without being overpowering. Creates a cozy atmosphere while staying fresh and easy on the senses.';

$nectarStrong = 'A richer peach scent with a stronger premium fragrance that instantly freshens up your space. Sweet, fruity, and long-lasting with a bolder presence — while still maintaining a smooth finish that doesn\'t feel too sharp or overwhelming. Perfect for those who prefer a more noticeable scent without causing headaches.';

$dreamyMelon = 'A fresh and soft honeydew-inspired scent that brings a calming atmosphere to every drive. Smooth, clean, and comforting with just the right touch of sweetness — never too overpowering. Perfect for creating a relaxing, peaceful vibe in your car anytime, anywhere.';

$summer = 'A refreshing fruity blend with soft melon notes balanced by hints of peach, lychee, and apple. Lightly sweet, smooth, and tropical — creating a relaxing summer-like atmosphere that feels fresh and clean all day long.';

$dreamyPeach = 'A playful fruity scent blended with a sweet bubble gum twist that instantly brightens your mood. Fresh, fun, and youthful without being too heavy — perfect for adding a cheerful vibe to every drive.';

$lemon = 'A bright citrus fragrance that keeps your car feeling clean and energised throughout the day.';

$updates = [
    1 => ['blank-black-rose.png', $nectarSoft],
    2 => ['blank-black-rose.png', $nectarStrong],
    3 => ['blank-black.png', $dreamyMelon],
    4 => ['blank-lemon.png', $lemon],
    5 => ['blank-rose.png', $dreamyPeach],
    6 => ['blank-summer.png', $summer],
];

foreach ($updates as $id => $data) {
    $img = mysqli_real_escape_string($conn, $data[0]);
    $desc = mysqli_real_escape_string($conn, $data[1]);
    $conn->query("UPDATE products SET image = '$img', description = '$desc' WHERE id = " . (int) $id);
}

$conn->query("INSERT INTO vouchers (code, discount_percent, min_order, max_uses, used_count, expires_at, is_active, new_user_only)
    SELECT 'NEWUSER15', 15.00, 0.00, 9999, 0, '2026-12-31', 1, 1
    FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM vouchers WHERE code = 'NEWUSER15')");

$conn->query("UPDATE vouchers SET new_user_only = 0 WHERE code IN ('WELCOME10', 'BLANK20')");

echo "Products, descriptions, images, and NEWUSER15 voucher updated.\n";
