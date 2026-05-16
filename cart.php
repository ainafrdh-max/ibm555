<?php include "config.php"; ?>
<?php if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
} ?>
<?php
$uid = (int) $_SESSION['user_id'];
$items = $conn->query("
    SELECT c.id as cart_id, c.quantity, p.id as product_id,
           p.name, p.variant, p.price, p.image, p.type
    FROM cart c JOIN products p ON c.product_id = p.id
    WHERE c.user_id = $uid ORDER BY c.created_at DESC
");
$rows = [];
$subtotal = 0;
while ($r = $items->fetch_assoc()) {
  $rows[] = $r;
  $subtotal += $r['price'] * $r['quantity'];
}
$points = user_points_balance($conn, $uid);
$availableVouchers = get_available_vouchers($conn, $uid);
$maxPointsRedeem = max_redeemable_points($points, $subtotal);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cart – Blank Perfume</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="styles/homestyle.css">
  <style>
    body {
      background: #f7f7f5;
    }

    .page-wrap {
      max-width: 960px;
      margin: 36px auto;
      padding: 0 20px 80px;
    }

    .page-title {
      font-size: 30px;
      font-weight: 700;
      letter-spacing: -1px;
      margin-bottom: 24px;
    }

    .section-card {
      background: #fff;
      border-radius: 20px;
      padding: 26px 28px;
      box-shadow: 0 2px 14px rgba(0, 0, 0, 0.05);
      margin-bottom: 20px;
    }

    .section-card h5 {
      font-weight: 700;
      font-size: 15px;
      border-bottom: 1.5px solid #f0f0f0;
      padding-bottom: 12px;
      margin-bottom: 18px;
    }

    .cart-item {
      display: flex;
      align-items: center;
      gap: 16px;
      padding: 14px 0;
      border-bottom: 1px solid #f5f5f5;
    }

    .cart-item:last-child {
      border-bottom: none;
    }

    .item-img {
      width: 72px;
      height: 72px;
      background: #e8f7d0;
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      flex-shrink: 0;
    }

    .item-img img {
      height: 58px;
      object-fit: contain;
    }

    .item-name {
      font-weight: 700;
      font-size: 14px;
    }

    .item-variant {
      font-size: 12px;
      color: #999;
      margin-top: 2px;
    }

    .item-price {
      font-weight: 700;
      font-size: 15px;
    }

    .qty-control {
      display: flex;
      align-items: center;
      gap: 0;
    }

    .qty-btn {
      width: 32px;
      height: 32px;
      border: 1.5px solid #e0e0e0;
      background: #fff;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 700;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: .2s;
    }

    .qty-btn:hover {
      background: #000;
      color: #fff;
      border-color: #000;
    }

    .qty-val {
      width: 40px;
      text-align: center;
      font-weight: 700;
      font-size: 15px;
    }

    .remove-btn {
      background: none;
      border: none;
      color: #ccc;
      font-size: 18px;
      cursor: pointer;
      padding: 4px;
      transition: .2s;
    }

    .remove-btn:hover {
      color: #cc0000;
    }

    /* Voucher */
    .voucher-row {
      display: flex;
      gap: 10px;
    }

    .voucher-input {
      flex: 1;
      border: 1.5px solid #e0e0e0;
      border-radius: 12px;
      padding: 11px 16px;
      font-size: 14px;
      outline: none;
      transition: .2s;
    }

    .voucher-input:focus {
      border-color: #000;
    }

    .btn-apply {
      background: #000;
      color: #fff;
      border: none;
      border-radius: 12px;
      padding: 11px 24px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      transition: .2s;
    }

    .btn-apply:hover {
      background: #333;
    }

    .voucher-msg {
      font-size: 13px;
      margin-top: 8px;
    }

    .voucher-ok {
      color: #2a6e00;
    }

    .voucher-err {
      color: #cc0000;
    }

    /* Summary */
    .summary-row {
      display: flex;
      justify-content: space-between;
      font-size: 14px;
      padding: 7px 0;
      color: #555;
    }

    .summary-row.total {
      font-weight: 700;
      font-size: 17px;
      color: #000;
      border-top: 1.5px solid #f0f0f0;
      margin-top: 8px;
      padding-top: 14px;
    }

    .summary-row.discount {
      color: #2a6e00;
    }

    .btn-checkout {
      width: 100%;
      background: #000;
      color: #fff;
      border: none;
      border-radius: 14px;
      padding: 15px;
      font-size: 16px;
      font-weight: 700;
      cursor: pointer;
      transition: .2s;
      margin-top: 16px;
    }

    .btn-checkout:hover {
      background: #222;
    }

    .btn-checkout:disabled {
      background: #ccc;
      cursor: not-allowed;
    }

    .points-badge {
      background: #e8f7d0;
      border-radius: 12px;
      padding: 12px 16px;
      font-size: 13px;
      color: #2a6e00;
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 16px;
    }

    .voucher-list { display: flex; flex-direction: column; gap: 8px; margin-top: 14px; }
    .voucher-chip {
      display: flex; justify-content: space-between; align-items: center;
      border: 1.5px solid #e8e8e8; border-radius: 12px; padding: 10px 14px;
      font-size: 13px; cursor: pointer; transition: .2s; background: #fafafa;
    }
    .voucher-chip.eligible:hover { border-color: #000; background: #f9fef4; }
    .voucher-chip.used { opacity: .5; cursor: not-allowed; }
    .voucher-chip .code { font-weight: 700; letter-spacing: .5px; }
    .voucher-chip .meta { font-size: 11px; color: #888; }
    .points-redeem { margin-top: 16px; padding-top: 16px; border-top: 1.5px solid #f0f0f0; }
    .points-redeem label { font-size: 13px; font-weight: 600; }
    .points-slider-row { display: flex; gap: 10px; align-items: center; margin-top: 8px; }
    .points-slider-row input[type=range] { flex: 1; }
  </style>
</head>

<body>
  <?php include 'partials/navbar.php'; ?>

  <div class="page-wrap">
    <div class="page-title">My Cart 🛒</div>

    <?php if (empty($rows)): ?>
      <div class="section-card text-center py-5">
        <div style="font-size:48px;">🛒</div>
        <h5 class="mt-3" style="border:none;">Your cart is empty</h5>
        <p class="text-muted mb-4">Browse our products and add something you love.</p>
        <a href="products.php"
          style="background:#000;color:#fff;border-radius:999px;padding:12px 32px;text-decoration:none;font-weight:600;">Shop
          Now</a>
      </div>
    <?php else: ?>

      <div class="row g-4">
        <!-- Cart items -->
        <div class="col-md-7">
          <div class="section-card">
            <h5>Items (<?php echo count($rows); ?>)</h5>
            <?php foreach ($rows as $r): ?>
              <div class="cart-item" id="row-<?php echo $r['cart_id']; ?>">
                <div class="item-img"><img src="<?php echo htmlspecialchars(product_image_src($r['image'])); ?>" alt=""></div>
                <div style="flex:1;">
                  <div class="item-name"><?php echo htmlspecialchars($r['name']); ?></div>
                  <div class="item-variant"><?php echo htmlspecialchars($r['variant']); ?></div>
                  <div class="item-price mt-2">RM <?php echo number_format($r['price'], 2); ?></div>
                </div>
                <div class="d-flex flex-column align-items-end gap-2">
                  <button class="remove-btn" onclick="removeItem(<?php echo $r['cart_id']; ?>)"><i
                      class="bi bi-trash3"></i></button>
                  <div class="qty-control">
                    <button class="qty-btn"
                      onclick="changeQty(<?php echo $r['cart_id']; ?>, -1, <?php echo $r['price']; ?>)">−</button>
                    <span class="qty-val" id="qty-<?php echo $r['cart_id']; ?>"><?php echo $r['quantity']; ?></span>
                    <button class="qty-btn"
                      onclick="changeQty(<?php echo $r['cart_id']; ?>, 1, <?php echo $r['price']; ?>)">+</button>
                  </div>
                  <div style="font-size:13px;color:#888;">RM <span
                      id="line-<?php echo $r['cart_id']; ?>"><?php echo number_format($r['price'] * $r['quantity'], 2); ?></span>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <!-- Voucher -->
          <div class="section-card">
            <h5>Promo Code <small style="font-weight:400;color:#999;">(one per order, one-time use)</small></h5>
            <div class="voucher-row">
              <input type="text" class="voucher-input" id="voucherCode" placeholder="Enter code"
                style="text-transform:uppercase;">
              <button type="button" class="btn-apply" onclick="applyVoucher()">Apply</button>
            </div>
            <div class="voucher-msg" id="voucherMsg"></div>
            <p class="mt-3 mb-1" style="font-size:12px;font-weight:600;color:#888;">YOUR AVAILABLE VOUCHERS</p>
            <div class="voucher-list">
              <?php foreach ($availableVouchers as $v): ?>
                <div class="voucher-chip <?php echo $v['eligible'] ? 'eligible' : 'used'; ?>"
                  <?php if ($v['eligible']): ?>onclick="selectVoucher('<?php echo htmlspecialchars($v['code'], ENT_QUOTES); ?>')"<?php endif; ?>>
                  <div>
                    <span class="code"><?php echo htmlspecialchars($v['code']); ?></span>
                    <?php if ($v['new_user_only']): ?><span class="badge bg-dark ms-1" style="font-size:9px;">NEW USER</span><?php endif; ?>
                    <div class="meta"><?php echo $v['discount_percent']; ?>% off<?php echo $v['min_order'] > 0 ? ' · min RM ' . number_format($v['min_order'], 2) : ''; ?></div>
                  </div>
                  <span class="meta"><?php echo $v['eligible'] ? 'Tap to use' : htmlspecialchars($v['reason']); ?></span>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <!-- Summary -->
        <div class="col-md-5">
          <div class="section-card">
            <h5>Order Summary</h5>
            <div class="points-badge">⭐ You have <strong
                style="margin:0 4px;"><?php echo number_format($points); ?></strong> points &nbsp;·&nbsp; Earn <strong
                id="earnPts" style="margin:0 4px;"><?php echo floor($subtotal); ?></strong> pts today</div>

            <div class="summary-row"><span>Subtotal</span><span>RM <span
                  id="sumSubtotal"><?php echo number_format($subtotal, 2); ?></span></span></div>
            <div class="summary-row discount" id="discountRow" style="display:none;"><span>Discount (<span
                  id="discPct"></span>%)</span><span>− RM <span id="discAmt">0.00</span></span></div>
            <div class="summary-row discount" id="pointsRow" style="display:none;"><span>Points</span><span>− RM <span id="pointsDiscAmt">0.00</span></span></div>
            <div class="summary-row"><span>Tax (6% SST)</span><span>RM <span
                  id="sumTax"><?php echo number_format($subtotal * 0.06, 2); ?></span></span></div>
            <div class="summary-row total"><span>Total</span><span>RM <span
                  id="sumTotal"><?php echo number_format($subtotal * 1.06, 2); ?></span></span></div>

            <?php if ($points > 0 && $maxPointsRedeem > 0): ?>
            <div class="points-redeem">
              <label>Use points (max <?php echo number_format($maxPointsRedeem); ?>)</label>
              <div class="points-slider-row">
                <input type="range" id="pointsSlider" min="0" max="<?php echo $maxPointsRedeem; ?>" value="0" step="100" oninput="onPointsSlider(this.value)">
                <span id="pointsSliderLabel">0 pts</span>
              </div>
              <button type="button" class="btn-apply mt-2" onclick="applyPoints()">Apply Points</button>
              <div class="voucher-msg" id="pointsMsg"></div>
            </div>
            <?php endif; ?>

            <form method="POST" action="checkout.php" id="checkoutForm">
              <input type="hidden" name="voucher_code" id="hiddenVoucher" value="">
              <input type="hidden" name="discount_amount" id="hiddenDiscount" value="0">
              <input type="hidden" name="discount_percent" id="hiddenDiscountPct" value="0">
              <input type="hidden" name="points_redeemed" id="hiddenPointsRedeemed" value="0">
              <input type="hidden" name="points_discount" id="hiddenPointsDiscount" value="0">
              <button type="submit" class="btn-checkout">Proceed to Checkout →</button>
            </form>
            <div class="text-center mt-3">
              <a href="products.php" style="font-size:13px;color:#888;text-decoration:none;">← Continue Shopping</a>
            </div>
          </div>
        </div>
      </div>

    <?php endif; ?>
  </div>

  <?php include 'partials/footer.php'; ?>
  <?php if (!empty($rows)): ?>
  <script src="assets/cart-page.js"></script>
  <script>
    initCartPage({ subtotal: <?php echo $subtotal; ?>, pointsBalance: <?php echo (int) $points; ?>, pointsPerRm: <?php echo POINTS_PER_RM; ?> });
  </script>
  <?php endif; ?>
</body>

</html>