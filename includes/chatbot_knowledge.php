<?php

function chatbot_products(mysqli $conn): array
{
    $items = [];
    $res = $conn->query("SELECT name, variant, type, price, description FROM products ORDER BY type, id");
    while ($p = $res->fetch_assoc()) {
        $items[] = $p;
    }
    return $items;
}

function chatbot_faq(): array
{
    return [
        ['q' => 'shipping time', 'a' => 'Orders ship within 1–3 business days via J&T Express.'],
        ['q' => 'shipping cost', 'a' => 'Shipping is calculated at checkout based on quantity and your location.'],
        ['q' => 'ship where', 'a' => 'We ship nationwide across Malaysia.'],
        ['q' => 'how long product last', 'a' => 'Blank Gel and Blank Liquid typically last around 30–45 days with regular use.'],
        ['q' => 'warranty', 'a' => 'We offer a 7-day warranty for no scent, cap issues, or damage during delivery.'],
        ['q' => 'blank gel how to use', 'a' => 'Twist open, remove plastic cap, replace lid, place in car. Avoid direct sunlight.'],
        ['q' => 'blank liquid how to use', 'a' => 'Unscrew lid, insert wooden stick into lid cavity, remove plastic cap, replace lid.'],
        ['q' => 'recommend', 'a' => 'Our recommended pick is <strong>Blank Liquid — Peach</strong>. It’s our most popular scent — fruity, fun, and great for everyday drives.'],
        ['q' => 'become agent', 'a' => 'Yes! Contact us on WhatsApp 011-5509 8234 for agent opportunities.'],
        ['q' => 'restock', 'a' => 'Follow @blank.malaysia on Instagram or Blank.malaysia on Facebook for restock updates.'],
    ];
}

function chatbot_reply(string $message, mysqli $conn, ?int $userId): string
{
    $msg = strtolower(trim($message));
    if ($msg === '') {
        return 'Hi! I\'m Blank Assistant. Ask me about products, prices, payment, vouchers, points, shipping, or FAQs.';
    }

    if (preg_match('/\b(hi|hello|hey|help)\b/', $msg)) {
        return 'Hello! I can help you with:<br>• Products & prices<br>• Payment (Card / FPX)<br>• Promo codes & points<br>• Shipping & FAQ<br>• Navigation (shop, cart, profile)<br><br>Try: "What products do you have?" or "How do I use points?"';
    }

    if (preg_match('/\b(navigate|where|find|go to|page)\b/', $msg)) {
        if (preg_match('/\b(cart|checkout)\b/', $msg)) {
            return $userId ? 'Your cart is at <a href="cart.php">cart.php</a>. Add items from <a href="products.php">Shop</a> first.' : 'Please <a href="login.php">sign in</a> to use your cart and checkout.';
        }
        if (preg_match('/\b(shop|product|buy)\b/', $msg)) {
            return $userId ? 'Browse products here: <a href="products.php">Shop</a>.' : 'View our catalog at <a href="shop.php">Shop</a> or <a href="login.php">sign in</a> to purchase.';
        }
        if (preg_match('/\b(profile|account)\b/', $msg)) {
            return $userId ? 'Manage your account at <a href="profile.php">My Profile</a>.' : 'Please <a href="login.php">sign in</a> to access your profile.';
        }
        if (preg_match('/\b(home|dashboard)\b/', $msg)) {
            return $userId ? 'Your dashboard: <a href="homepage.php">Home</a>.' : 'Visit our <a href="index.php">homepage</a>.';
        }
        if (preg_match('/\b(faq|question)\b/', $msg)) {
            return 'Read FAQs at <a href="faq.php">FAQ page</a>.';
        }
        return 'Quick links: ' . ($userId ? '<a href="homepage.php">Home</a> · <a href="products.php">Shop</a> · <a href="cart.php">Cart</a> · <a href="profile.php">Profile</a> · <a href="faq.php">FAQ</a>' : '<a href="index.php">Home</a> · <a href="shop.php">Shop</a> · <a href="faq.php">FAQ</a> · <a href="login.php">Sign in</a>');
    }

    if (preg_match('/\b(payment|pay|card|fpx|bank)\b/', $msg)) {
        return '<strong>Payment methods</strong> at checkout:<br>• <strong>Debit / Credit Card</strong> — enter card number, name, expiry & CVV (demo — stored masked only)<br>• <strong>FPX Online Banking</strong> — choose your bank (Maybank, CIMB, Public Bank, etc.)<br><br>No real payment API — for assignment demo only.';
    }

    if (preg_match('/\b(point|points|redeem)\b/', $msg)) {
        return '<strong>Points</strong><br>• Earn <strong>1 point per RM1</strong> spent<br>• Redeem at cart: <strong>100 points = RM1</strong> off your order<br>• Applied before tax, together with one voucher<br><br>' . ($userId ? 'Check balance on <a href="cart.php">Cart</a> or <a href="profile.php">Profile</a>.' : '<a href="login.php">Sign in</a> to earn and use points.');
    }

    if (preg_match('/\b(voucher|promo|promotion|discount code|coupon)\b/', $msg)) {
        if (!$userId) {
            return 'Sign in to see <strong>your available promo codes</strong>. We offer welcome discounts for new users and seasonal codes — visible on the Cart page after login.';
        }
        $vouchers = get_available_vouchers($conn, $userId);
        $eligible = array_filter($vouchers, fn($v) => $v['eligible']);
        if (empty($eligible)) {
            return 'You have no unused vouchers right now. Each code can only be used <strong>once per account</strong>.';
        }
        $html = '<strong>Your available promo codes:</strong><br>';
        foreach ($eligible as $v) {
            $label = $v['new_user_only'] ? ' (new user)' : '';
            $html .= '• <strong>' . htmlspecialchars($v['code']) . '</strong> — ' . $v['discount_percent'] . '% off';
            if ($v['min_order'] > 0) {
                $html .= ', min RM ' . number_format($v['min_order'], 2);
            }
            $html .= $label . '<br>';
        }
        $html .= '<br>Apply one code on your <a href="cart.php">Cart</a> (one voucher per order).';
        return $html;
    }

    if (preg_match('/\b(recommend|recommended|best seller|best scent|most popular|top pick)\b/', $msg)) {
        $shop = $userId ? 'products.php' : 'shop.php';
        return 'Our <strong>recommended</strong> product is <strong>Blank Liquid — Peach</strong> — a playful fruity scent with a sweet bubble gum twist. Most customers love it!<br><br><a href="' . $shop . '">View in shop →</a>';
    }

    if (preg_match('/\b(product|scent|fragrance|gel|liquid|price|rm)\b/', $msg)) {
        $products = chatbot_products($conn);
        $html = '<strong>Our products:</strong><br>';
        foreach ($products as $p) {
            $html .= '• <strong>' . htmlspecialchars($p['name']) . '</strong> — ' . htmlspecialchars($p['variant']);
            $html .= ' (' . ucfirst($p['type']) . ') — <strong>RM ' . number_format((float) $p['price'], 2) . '</strong>';
            if (is_recommended_product($p)) {
                $html .= ' <em>★ Recommended</em>';
            }
            $html .= '<br>';
            if (!empty($p['description']) && (preg_match('/\b(soft|strong|melon|summer|peach|nectar|lemon|rose)\b/', $msg) || strlen($msg) < 30)) {
                $plain = strip_tags($p['description']);
                $short = substr($plain, 0, 120);
                if (strlen($plain) > 120) {
                    $short .= '…';
                }
                $html .= '<span style="color:#666;font-size:12px;">' . htmlspecialchars($short) . '</span><br>';
            }
        }
        $html .= ($userId ? '<br><a href="products.php">Shop now →</a>' : '<br><a href="shop.php">View shop →</a>');
        return $html;
    }

    if (preg_match('/\b(sweet nectar|nectar)\b/', $msg)) {
        return '<strong>Sweet Nectar (Blank Gel)</strong><br><strong>Soft</strong> — gentle peach, calming & light.<br><strong>Strong</strong> — richer peach, bolder & longer-lasting.<br>Both RM 25.00. <a href="products.php">Shop →</a>';
    }

    if (preg_match('/\b(dreamy melon|melon)\b/', $msg)) {
        return '<strong>Dreamy Melon</strong> (Blank Gel) — fresh honeydew-inspired scent, smooth & calming. RM 25.00.';
    }

    if (preg_match('/\b(summer|paradise)\b/', $msg)) {
        return '<strong>Summer Paradise</strong> (Blank Liquid) — fruity blend with melon, peach, lychee & apple. Tropical & fresh. RM 22.00.';
    }

    if (preg_match('/\b(blank liquid.*peach|liquid peach|peach liquid)\b/', $msg) || (preg_match('/\bpeach\b/', $msg) && preg_match('/\bliquid\b/', $msg))) {
        $shop = $userId ? 'products.php' : 'shop.php';
        return '<strong>Blank Liquid — Peach</strong> ★ <em>Recommended</em><br>A playful fruity scent with a sweet bubble gum twist — fresh, fun, and great for everyday drives.<br><br><a href="' . $shop . '">Shop Peach →</a>';
    }

    foreach (chatbot_faq() as $item) {
        $keywords = explode(' ', $item['q']);
        $match = 0;
        foreach ($keywords as $kw) {
            if (strlen($kw) > 2 && str_contains($msg, $kw)) {
                $match++;
            }
        }
        if ($match >= 2 || str_contains($msg, $item['q'])) {
            return $item['a'] . ' <a href="faq.php">More FAQs →</a>';
        }
    }

    if (preg_match('/\b(tiktok|social)\b/', $msg)) {
        return 'Follow us on TikTok: <a href="https://www.tiktok.com/@blank.my" target="_blank">@blank.my</a>';
    }

    if (preg_match('/\b(contact|whatsapp|email|phone)\b/', $msg)) {
        return 'Contact us:<br>📞 011-5509 8234<br>✉️ blankcarfragrance@gmail.com<br><a href="https://wa.me/601155098234" target="_blank">WhatsApp</a>';
    }

    return 'I\'m not sure about that. Try asking about <strong>products</strong>, <strong>payment</strong>, <strong>vouchers</strong>, <strong>points</strong>, or <strong>shipping</strong>. Or visit <a href="faq.php">FAQ</a>.';
}
