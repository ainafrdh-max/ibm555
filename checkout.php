<?php include "config.php"; ?>
<?php if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
} ?>
<?php
$uid = (int) $_SESSION['user_id'];
$checkout_error = '';

$items = $conn->query("
    SELECT c.id as cart_id, c.quantity, p.id as product_id,
           p.name, p.variant, p.price, p.image, p.stock
    FROM cart c JOIN products p ON c.product_id = p.id
    WHERE c.user_id = $uid
");
$rows = [];
$subtotal = 0;
while ($r = $items->fetch_assoc()) {
  $rows[] = $r;
  $subtotal += $r['price'] * $r['quantity'];
}

if (empty($rows)) {
  header("Location: cart.php");
  exit();
}

$voucher_code = trim($_POST['voucher_code'] ?? '');
$discount_amt = (float) ($_POST['discount_amount'] ?? 0);
$discount_pct = (float) ($_POST['discount_percent'] ?? 0);
$points_redeemed = (int) ($_POST['points_redeemed'] ?? 0);
$points_discount = (float) ($_POST['points_discount'] ?? 0);
$after_discount = max(0, $subtotal - $discount_amt - $points_discount);
$tax = round($after_discount * 0.06, 2);
$total = round($after_discount + $tax, 2);
$points_earned = (int) floor($subtotal);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay'])) {
  $delivery_name = trim($_POST['delivery_name'] ?? '');
  $delivery_phone = trim($_POST['delivery_phone'] ?? '');
  $delivery_email = trim($_POST['delivery_email'] ?? '');
  $delivery_address = trim($_POST['delivery_address'] ?? '');
  $payment_method = $_POST['payment_method'] ?? '';

  $payment_type = $payment_method === 'FPX' ? 'fpx' : 'card';
  $card_holder = $card_masked = $card_expiry = $card_brand = $fpx_bank = null;

  if ($delivery_name === '' || $delivery_phone === '' || $delivery_email === '' || $delivery_address === '') {
    $checkout_error = 'Please complete all delivery fields.';
  } elseif (!in_array($payment_method, ['Debit/Credit Card', 'FPX'], true)) {
    $checkout_error = 'Please select a valid payment method.';
  } elseif ($payment_type === 'card') {
    $card_holder = trim($_POST['card_holder'] ?? '');
    $card_number = preg_replace('/\D/', '', $_POST['card_number'] ?? '');
    $card_expiry = trim($_POST['card_expiry'] ?? '');
    $card_cvv = trim($_POST['card_cvv'] ?? '');

    if ($card_holder === '' || strlen($card_number) < 13 || $card_expiry === '' || strlen($card_cvv) < 3) {
      $checkout_error = 'Please enter valid card details.';
    } elseif (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $card_expiry)) {
      $checkout_error = 'Card expiry must be MM/YY.';
    } else {
      $card_masked = mysqli_real_escape_string($conn, mask_card_number($card_number));
      $card_holder = mysqli_real_escape_string($conn, $card_holder);
      $card_expiry = mysqli_real_escape_string($conn, $card_expiry);
      $card_brand = mysqli_real_escape_string($conn, detect_card_brand($card_number));
    }
  } else {
    $fpx_bank = trim($_POST['fpx_bank'] ?? '');
    if ($fpx_bank === '') {
      $checkout_error = 'Please select your FPX bank.';
    } else {
      $fpx_bank = mysqli_real_escape_string($conn, $fpx_bank);
    }
  }

  if ($checkout_error === '') {
    if ($voucher_code !== '') {
      $voucher = get_voucher_by_code($conn, $voucher_code);
      if (!$voucher) {
        $checkout_error = 'Invalid voucher code.';
      } else {
        $verr = validate_voucher_for_user($conn, $uid, $voucher, $subtotal);
        if ($verr) {
          $checkout_error = $verr;
        }
      }
    }
    $balance = user_points_balance($conn, $uid);
    $maxPts = max_redeemable_points($balance, max(0, $subtotal - $discount_amt));
    if ($points_redeemed > $maxPts || abs(points_to_rm($points_redeemed) - $points_discount) > 0.02) {
      $checkout_error = 'Invalid points redemption.';
    }
  }

  if ($checkout_error === '') {
    $method_label = mysqli_real_escape_string($conn, $payment_method);
    $vc = mysqli_real_escape_string($conn, $voucher_code);
    $dname = mysqli_real_escape_string($conn, $delivery_name);
    $dphone = mysqli_real_escape_string($conn, $delivery_phone);
    $demail = mysqli_real_escape_string($conn, $delivery_email);
    $daddr = mysqli_real_escape_string($conn, $delivery_address);

    $card_holder_sql = $card_holder !== null ? "'$card_holder'" : 'NULL';
    $card_masked_sql = $card_masked !== null ? "'$card_masked'" : 'NULL';
    $card_expiry_sql = $card_expiry !== null ? "'$card_expiry'" : 'NULL';
    $card_brand_sql = $card_brand !== null ? "'$card_brand'" : 'NULL';
    $fpx_bank_sql = $fpx_bank !== null ? "'$fpx_bank'" : 'NULL';

    $conn->query("INSERT INTO orders (
        user_id, subtotal, tax, discount, total, voucher_code, points_earned, points_redeemed, points_discount,
        status, payment_method, delivery_name, delivery_phone, delivery_email, delivery_address,
        payment_type, card_holder, card_number_masked, card_expiry, card_brand, fpx_bank
      ) VALUES (
        $uid, $subtotal, $tax, $discount_amt, $total, " . ($voucher_code !== '' ? "'$vc'" : 'NULL') . ", $points_earned, $points_redeemed, $points_discount,
        'paid', '$method_label', '$dname', '$dphone', '$demail', '$daddr',
        '$payment_type', $card_holder_sql, $card_masked_sql, $card_expiry_sql, $card_brand_sql, $fpx_bank_sql
      )");
    $order_id = $conn->insert_id;

    foreach ($rows as $r) {
      $pid = (int) $r['product_id'];
      $qty = (int) $r['quantity'];
      $price = (float) $r['price'];
      $conn->query("INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES ($order_id, $pid, $qty, $price)");
      $conn->query("UPDATE products SET stock = GREATEST(0, stock - $qty) WHERE id = $pid");
    }

    $netPoints = $points_earned - $points_redeemed;
    $conn->query("INSERT INTO user_points (user_id, total_points) VALUES ($uid, GREATEST(0, $netPoints))
        ON DUPLICATE KEY UPDATE total_points = GREATEST(0, total_points + $netPoints)");

    if ($voucher_code !== '' && isset($voucher)) {
      $vid = (int) $voucher['id'];
      $conn->query("UPDATE vouchers SET used_count = used_count + 1 WHERE id = $vid");
      $conn->query("INSERT IGNORE INTO voucher_uses (user_id, voucher_id, order_id) VALUES ($uid, $vid, $order_id)");
    }

    $conn->query("DELETE FROM cart WHERE user_id = $uid");

    header("Location: receipt.php?id=$order_id");
    exit();
  }
}

$user = $conn->query("SELECT * FROM users WHERE id = $uid")->fetch_assoc();

$fpx_banks = [
  'Maybank2u',
  'CIMB Clicks',
  'Public Bank',
  'RHB Now',
  'Hong Leong Connect',
  'AmBank',
  'Bank Islam',
  'BSN',
  'Affin Bank',
  'Bank Rakyat',
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout – Blank Perfume</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="styles/homestyle.css">
  <style>
    body {
      background: #f7f7f5;
    }

    .page-wrap {
      max-width: 920px;
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

    .steps {
      display: flex;
      gap: 0;
      margin-bottom: 32px;
    }

    .step {
      flex: 1;
      text-align: center;
      position: relative;
    }

    .step-circle {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: #e8f7d0;
      color: #2a6e00;
      font-weight: 700;
      font-size: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 6px;
    }

    .step.active .step-circle {
      background: #000;
      color: #fff;
    }

    .step.done .step-circle {
      background: #2a6e00;
      color: #fff;
    }

    .step-label {
      font-size: 12px;
      color: #999;
      letter-spacing: .5px;
    }

    .step.active .step-label {
      color: #000;
      font-weight: 600;
    }

    .step::after {
      content: '';
      position: absolute;
      top: 18px;
      left: 60%;
      width: 80%;
      height: 2px;
      background: #eee;
      z-index: 0;
    }

    .step:last-child::after {
      display: none;
    }

    .method-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
    }

    .method-card {
      border: 2px solid #eee;
      border-radius: 14px;
      padding: 18px 12px;
      text-align: center;
      cursor: pointer;
      transition: .2s;
    }

    .method-card:hover {
      border-color: #000;
    }

    .method-card.selected {
      border-color: #000;
      background: #f9fef4;
    }

    .method-card input {
      display: none;
    }

    .method-icon {
      font-size: 28px;
      margin-bottom: 8px;
    }

    .method-name {
      font-size: 14px;
      font-weight: 600;
    }

    .payment-panel {
      display: none;
      margin-top: 20px;
      padding-top: 20px;
      border-top: 1.5px solid #f0f0f0;
    }

    .payment-panel.active {
      display: block;
    }

    .card-preview {
      background: linear-gradient(135deg, #1a1a1a, #333);
      color: #fff;
      border-radius: 16px;
      padding: 22px;
      margin-bottom: 18px;
      min-height: 140px;
    }

    .card-preview .chip {
      width: 40px;
      height: 28px;
      background: #d4af37;
      border-radius: 6px;
      margin-bottom: 24px;
    }

    .card-preview .number {
      font-size: 18px;
      letter-spacing: 2px;
      font-family: monospace;
      margin-bottom: 16px;
    }

    .card-preview .meta {
      display: flex;
      justify-content: space-between;
      font-size: 12px;
      opacity: .85;
    }

    .oi-row {
      display: flex;
      align-items: center;
      gap: 14px;
      padding: 10px 0;
      border-bottom: 1px solid #f5f5f5;
      font-size: 14px;
    }

    .oi-row:last-child {
      border-bottom: none;
    }

    .oi-img {
      width: 52px;
      height: 52px;
      background: #e8f7d0;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      flex-shrink: 0;
    }

    .oi-img img {
      height: 42px;
      object-fit: contain;
    }

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

    .btn-pay {
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
      margin-top: 14px;
    }

    .btn-pay:hover {
      background: #222;
    }

    .points-banner {
      background: #e8f7d0;
      border-radius: 12px;
      padding: 12px 16px;
      font-size: 13px;
      color: #2a6e00;
      margin-bottom: 16px;
    }

    label.field-label {
      font-size: 13px;
      font-weight: 600;
      color: #555;
      margin-bottom: 5px;
      display: block;
    }

    .field-input {
      width: 100%;
      border: 1.5px solid #e5e5e5;
      border-radius: 12px;
      padding: 11px 14px;
      font-size: 14px;
      outline: none;
      transition: .2s;
      background: #fafafa;
    }

    .field-input:focus {
      border-color: #000;
      background: #fff;
    }

    .pay-note {
      font-size: 12px;
      color: #888;
      margin-top: 10px;
    }

    .checkout-alert {
      background: #fff0f0;
      border: 1.5px solid #ffcccc;
      border-radius: 12px;
      padding: 12px 16px;
      color: #cc0000;
      font-size: 14px;
      margin-bottom: 20px;
    }

    .fpx-list {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
      gap: 8px;
    }

    .fpx-option {
      border: 1.5px solid #e5e5e5;
      border-radius: 10px;
      padding: 10px;
      text-align: center;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
      transition: .2s;
    }

    .fpx-option:hover,
    .fpx-option.selected {
      border-color: #000;
      background: #f9fef4;
    }

    .fpx-option input {
      display: none;
    }
  </style>
</head>

<body>
  <?php include 'partials/navbar.php'; ?>

  <div class="page-wrap">
    <div class="page-title">Checkout</div>

    <div class="steps">
      <div class="step done">
        <div class="step-circle"><i class="bi bi-check"></i></div>
        <div class="step-label">CART</div>
      </div>
      <div class="step active">
        <div class="step-circle">2</div>
        <div class="step-label">PAYMENT</div>
      </div>
      <div class="step">
        <div class="step-circle">3</div>
        <div class="step-label">RECEIPT</div>
      </div>
    </div>

    <?php if ($checkout_error): ?>
      <div class="checkout-alert"><i class="bi bi-exclamation-circle-fill me-2"></i><?php echo htmlspecialchars($checkout_error); ?></div>
    <?php endif; ?>

    <form method="POST" id="checkoutForm" novalidate>
      <input type="hidden" name="voucher_code" value="<?php echo htmlspecialchars($voucher_code); ?>">
      <input type="hidden" name="discount_amount" value="<?php echo $discount_amt; ?>">
      <input type="hidden" name="discount_percent" value="<?php echo $discount_pct; ?>">
      <input type="hidden" name="points_redeemed" value="<?php echo (int) $points_redeemed; ?>">
      <input type="hidden" name="points_discount" value="<?php echo $points_discount; ?>">

      <div class="row g-4">
        <div class="col-md-7">

          <div class="section-card">
            <h5>Delivery Information</h5>
            <div class="row g-3">
              <div class="col-12">
                <label class="field-label">Full Name</label>
                <input class="field-input" type="text" name="delivery_name"
                  value="<?php echo htmlspecialchars($_POST['delivery_name'] ?? $user['username']); ?>" required>
              </div>
              <div class="col-md-6">
                <label class="field-label">Phone Number</label>
                <input class="field-input" type="text" name="delivery_phone"
                  value="<?php echo htmlspecialchars($_POST['delivery_phone'] ?? ($user['phone'] ?? '')); ?>"
                  placeholder="e.g. 011-1234 5678" required>
              </div>
              <div class="col-md-6">
                <label class="field-label">Email</label>
                <input class="field-input" type="email" name="delivery_email"
                  value="<?php echo htmlspecialchars($_POST['delivery_email'] ?? $user['email']); ?>" required>
              </div>
              <div class="col-12">
                <label class="field-label">Delivery Address</label>
                <textarea class="field-input" name="delivery_address" rows="3" placeholder="Enter your full delivery address"
                  required><?php echo htmlspecialchars($_POST['delivery_address'] ?? ($user['address'] ?? '')); ?></textarea>
              </div>
            </div>
          </div>

          <div class="section-card">
            <h5>Payment Method</h5>
            <p class="pay-note mb-3">Demo checkout — no real payment API. Your payment details are saved to the database for this assignment.</p>

            <div class="method-grid">
              <label class="method-card selected" data-method="card">
                <input type="radio" name="payment_method" value="Debit/Credit Card" checked>
                <div class="method-icon">💳</div>
                <div class="method-name">Debit / Credit Card</div>
              </label>
              <label class="method-card" data-method="fpx">
                <input type="radio" name="payment_method" value="FPX">
                <div class="method-icon">🏦</div>
                <div class="method-name">FPX Online Banking</div>
              </label>
            </div>

            <div class="payment-panel active" id="panel-card">
              <div class="card-preview">
                <div class="chip"></div>
                <div class="number" id="previewNumber">**** **** **** ****</div>
                <div class="meta">
                  <span id="previewName">CARDHOLDER NAME</span>
                  <span id="previewExpiry">MM/YY</span>
                </div>
              </div>

              <div class="row g-3">
                <div class="col-12">
                  <label class="field-label">Name on Card</label>
                  <input class="field-input" type="text" name="card_holder" id="cardHolder" placeholder="As shown on card"
                    autocomplete="cc-name">
                </div>
                <div class="col-12">
                  <label class="field-label">Card Number</label>
                  <input class="field-input" type="text" name="card_number" id="cardNumber" placeholder="1234 5678 9012 3456"
                    maxlength="19" inputmode="numeric" autocomplete="cc-number">
                </div>
                <div class="col-md-6">
                  <label class="field-label">Expiry (MM/YY)</label>
                  <input class="field-input" type="text" name="card_expiry" id="cardExpiry" placeholder="MM/YY" maxlength="5"
                    autocomplete="cc-exp">
                </div>
                <div class="col-md-6">
                  <label class="field-label">CVV</label>
                  <input class="field-input" type="password" name="card_cvv" id="cardCvv" placeholder="123" maxlength="4"
                    autocomplete="cc-csc">
                </div>
              </div>
            </div>

            <div class="payment-panel" id="panel-fpx">
              <label class="field-label">Select Bank</label>
              <div class="fpx-list">
                <?php foreach ($fpx_banks as $i => $bank): ?>
                  <label class="fpx-option <?php echo $i === 0 ? 'selected' : ''; ?>">
                    <input type="radio" name="fpx_bank" value="<?php echo htmlspecialchars($bank); ?>" <?php echo $i === 0 ? 'checked' : ''; ?>>
                    <?php echo htmlspecialchars($bank); ?>
                  </label>
                <?php endforeach; ?>
              </div>
              <p class="pay-note">You will be redirected to your bank’s FPX page in a live system. Here, confirming completes the order.</p>
            </div>
          </div>

        </div>

        <div class="col-md-5">
          <div class="section-card">
            <h5>Order Summary</h5>
            <?php foreach ($rows as $r): ?>
              <div class="oi-row">
                <div class="oi-img"><img src="<?php echo htmlspecialchars(product_image_src($r['image'])); ?>" alt=""></div>
                <div style="flex:1;">
                  <div style="font-weight:600;"><?php echo htmlspecialchars($r['name']); ?></div>
                  <div style="font-size:12px;color:#999;"><?php echo htmlspecialchars($r['variant']); ?> × <?php echo (int) $r['quantity']; ?></div>
                </div>
                <div style="font-weight:700;">RM <?php echo number_format($r['price'] * $r['quantity'], 2); ?></div>
              </div>
            <?php endforeach; ?>

            <div style="margin-top:16px;">
              <div class="summary-row"><span>Subtotal</span><span>RM <?php echo number_format($subtotal, 2); ?></span></div>
              <?php if ($discount_amt > 0): ?>
                <div class="summary-row discount"><span>Voucher (<?php echo $discount_pct; ?>%)</span><span>− RM <?php echo number_format($discount_amt, 2); ?></span></div>
              <?php endif; ?>
              <?php if ($points_discount > 0): ?>
                <div class="summary-row discount"><span>Points (<?php echo $points_redeemed; ?> pts)</span><span>− RM <?php echo number_format($points_discount, 2); ?></span></div>
              <?php endif; ?>
              <div class="summary-row"><span>Tax (6% SST)</span><span>RM <?php echo number_format($tax, 2); ?></span></div>
              <div class="summary-row total"><span>Total</span><span>RM <?php echo number_format($total, 2); ?></span></div>
            </div>

            <div class="points-banner mt-3">⭐ You'll earn <strong><?php echo $points_earned; ?></strong> points from this order</div>

            <button type="submit" name="pay" class="btn-pay">Pay RM <?php echo number_format($total, 2); ?></button>
            <div class="text-center mt-3"><a href="cart.php" style="font-size:13px;color:#888;text-decoration:none;">← Back to Cart</a></div>
          </div>
        </div>
      </div>
    </form>
  </div>

  <?php include 'partials/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const methodCards = document.querySelectorAll('.method-card');
    const panelCard = document.getElementById('panel-card');
    const panelFpx = document.getElementById('panel-fpx');
    const cardFields = ['cardHolder', 'cardNumber', 'cardExpiry', 'cardCvv'];

    methodCards.forEach(card => {
      card.addEventListener('click', () => {
        methodCards.forEach(c => c.classList.remove('selected'));
        card.classList.add('selected');
        card.querySelector('input').checked = true;
        const isFpx = card.dataset.method === 'fpx';
        panelCard.classList.toggle('active', !isFpx);
        panelFpx.classList.toggle('active', isFpx);
        cardFields.forEach(id => {
          const el = document.getElementById(id);
          if (el) el.required = !isFpx;
        });
      });
    });

    document.querySelectorAll('.fpx-option').forEach(opt => {
      opt.addEventListener('click', () => {
        document.querySelectorAll('.fpx-option').forEach(o => o.classList.remove('selected'));
        opt.classList.add('selected');
        opt.querySelector('input').checked = true;
      });
    });

    const cardNumber = document.getElementById('cardNumber');
    const cardHolder = document.getElementById('cardHolder');
    const cardExpiry = document.getElementById('cardExpiry');

    cardNumber?.addEventListener('input', () => {
      let v = cardNumber.value.replace(/\D/g, '').slice(0, 16);
      cardNumber.value = v.replace(/(.{4})/g, '$1 ').trim();
      const masked = v.padEnd(16, '*').replace(/(.{4})/g, '$1 ').trim();
      document.getElementById('previewNumber').textContent = masked || '**** **** **** ****';
    });

    cardHolder?.addEventListener('input', () => {
      document.getElementById('previewName').textContent = cardHolder.value.toUpperCase() || 'CARDHOLDER NAME';
    });

    cardExpiry?.addEventListener('input', () => {
      let v = cardExpiry.value.replace(/\D/g, '').slice(0, 4);
      if (v.length >= 3) v = v.slice(0, 2) + '/' + v.slice(2);
      cardExpiry.value = v;
      document.getElementById('previewExpiry').textContent = v || 'MM/YY';
    });

    document.getElementById('checkoutForm').addEventListener('submit', (e) => {
      const isFpx = document.querySelector('input[name="payment_method"]:checked')?.value === 'FPX';
      if (!isFpx) return;
      cardFields.forEach(id => {
        const el = document.getElementById(id);
        if (el) el.required = false;
      });
    });

    cardFields.forEach(id => {
      const el = document.getElementById(id);
      if (el && id !== 'cardCvv') el.required = true;
    });
    document.getElementById('cardCvv').required = true;
  </script>
</body>

</html>
