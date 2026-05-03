<?php include "config.php"; ?>
<?php if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
} ?>
<?php
$uid = $_SESSION['user_id'];
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
$pts = $conn->query("SELECT total_points FROM user_points WHERE user_id=$uid")->fetch_assoc();
$points = $pts ? $pts['total_points'] : 0;
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
                <div class="item-img"><img src="img/<?php echo htmlspecialchars($r['image']); ?>" alt=""></div>
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
            <h5>Voucher Code</h5>
            <div class="voucher-row">
              <input type="text" class="voucher-input" id="voucherCode" placeholder="Enter voucher code (e.g. WELCOME10)"
                style="text-transform:uppercase;">
              <button class="btn-apply" onclick="applyVoucher()">Apply</button>
            </div>
            <div class="voucher-msg" id="voucherMsg"></div>
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
            <div class="summary-row"><span>Tax (6% SST)</span><span>RM <span
                  id="sumTax"><?php echo number_format($subtotal * 0.06, 2); ?></span></span></div>
            <div class="summary-row total"><span>Total</span><span>RM <span
                  id="sumTotal"><?php echo number_format($subtotal * 1.06, 2); ?></span></span></div>

            <form method="POST" action="checkout.php" id="checkoutForm">
              <input type="hidden" name="voucher_code" id="hiddenVoucher" value="">
              <input type="hidden" name="discount_amount" id="hiddenDiscount" value="0">
              <input type="hidden" name="discount_percent" id="hiddenDiscountPct" value="0">
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    let baseSubtotal = <?php echo $subtotal; ?>;
    let discountPct = 0;
    let discountAmt = 0;

    function updateSummary() {
      const sub = baseSubtotal;
      const disc = parseFloat((sub * discountPct / 100).toFixed(2));
      const afterDisc = sub - disc;
      const tax = parseFloat((afterDisc * 0.06).toFixed(2));
      const total = afterDisc + tax;

      document.getElementById('sumSubtotal').textContent = sub.toFixed(2);
      document.getElementById('sumTax').textContent = tax.toFixed(2);
      document.getElementById('sumTotal').textContent = total.toFixed(2);
      document.getElementById('earnPts').textContent = Math.floor(sub);

      if (discountPct > 0) {
        document.getElementById('discountRow').style.display = '';
        document.getElementById('discPct').textContent = discountPct;
        document.getElementById('discAmt').textContent = disc.toFixed(2);
      } else {
        document.getElementById('discountRow').style.display = 'none';
      }
      document.getElementById('hiddenDiscount').value = disc.toFixed(2);
    }

    function changeQty(cartId, delta, price) {
      const qtyEl = document.getElementById('qty-' + cartId);
      let qty = parseInt(qtyEl.textContent) + delta;
      if (qty < 1) { removeItem(cartId); return; }
      qtyEl.textContent = qty;
      document.getElementById('line-' + cartId).textContent = (price * qty).toFixed(2);

      // Recalculate subtotal
      let newSub = 0;
      document.querySelectorAll('[id^="qty-"]').forEach(el => {
        const cid = el.id.replace('qty-', '');
        const lineEl = document.getElementById('line-' + cid);
        if (lineEl) newSub += parseFloat(lineEl.textContent);
      });
      baseSubtotal = parseFloat(newSub.toFixed(2));
      updateSummary();

      fetch('cart_action.php', {
        method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=update&cart_id=${cartId}&quantity=${qty}`
      });
    }

    function removeItem(cartId) {
      const row = document.getElementById('row-' + cartId);
      if (!row) return;
      const lineEl = document.getElementById('line-' + cartId);
      if (lineEl) baseSubtotal = parseFloat((baseSubtotal - parseFloat(lineEl.textContent)).toFixed(2));
      row.style.opacity = '0';
      setTimeout(() => { row.remove(); updateSummary(); }, 300);
      fetch('cart_action.php', {
        method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=remove&cart_id=${cartId}`
      });
    }

    function applyVoucher() {
      const code = document.getElementById('voucherCode').value.trim();
      const msgEl = document.getElementById('voucherMsg');
      if (!code) { msgEl.innerHTML = '<span class="voucher-err">Please enter a voucher code.</span>'; return; }

      fetch('apply_voucher.php', {
        method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `code=${encodeURIComponent(code)}&subtotal=${baseSubtotal}`
      })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            discountPct = data.discount_percent;
            document.getElementById('hiddenVoucher').value = code;
            document.getElementById('hiddenDiscountPct').value = discountPct;
            msgEl.innerHTML = `<span class="voucher-ok">✓ ${data.message}</span>`;
            updateSummary();
          } else {
            discountPct = 0;
            document.getElementById('hiddenVoucher').value = '';
            msgEl.innerHTML = `<span class="voucher-err">✗ ${data.error}</span>`;
            updateSummary();
          }
        });
    }
  </script>
</body>

</html>