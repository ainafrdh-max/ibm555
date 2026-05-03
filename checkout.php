<?php include "config.php"; ?>
<?php if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); } ?>
<?php
$uid = $_SESSION['user_id'];

// Load cart
$items = $conn->query("
    SELECT c.id as cart_id, c.quantity, p.id as product_id,
           p.name, p.variant, p.price, p.image
    FROM cart c JOIN products p ON c.product_id = p.id
    WHERE c.user_id = $uid
");
$rows = [];
$subtotal = 0;
while ($r = $items->fetch_assoc()) { $rows[] = $r; $subtotal += $r['price'] * $r['quantity']; }

if (empty($rows)) { header("Location: cart.php"); exit(); }

// From cart form
$voucher_code   = trim($_POST['voucher_code'] ?? '');
$discount_amt   = (float)($_POST['discount_amount'] ?? 0);
$discount_pct   = (float)($_POST['discount_percent'] ?? 0);
$after_discount = $subtotal - $discount_amt;
$tax            = round($after_discount * 0.06, 2);
$total          = round($after_discount + $tax, 2);
$points_earned  = floor($subtotal);

// Handle payment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay'])) {
    $method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $vc     = mysqli_real_escape_string($conn, $voucher_code);

    // Insert order
    $conn->query("INSERT INTO orders (user_id, subtotal, tax, discount, total, voucher_code, points_earned, status, payment_method)
        VALUES ($uid, $subtotal, $tax, $discount_amt, $total, '$vc', $points_earned, 'paid', '$method')");
    $order_id = $conn->insert_id;

    // Insert order items
    foreach ($rows as $r) {
        $pid = $r['product_id']; $qty = $r['quantity']; $price = $r['price'];
        $conn->query("INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES ($order_id, $pid, $qty, $price)");
    }

    // Add points
    $conn->query("INSERT INTO user_points (user_id, total_points) VALUES ($uid, $points_earned)
        ON DUPLICATE KEY UPDATE total_points = total_points + $points_earned");

    // Mark voucher used
    if ($voucher_code) {
        $conn->query("UPDATE vouchers SET used_count = used_count + 1 WHERE code = '$vc'");
    }

    // Clear cart
    $conn->query("DELETE FROM cart WHERE user_id=$uid");

    header("Location: receipt.php?id=$order_id");
    exit();
}

$user = $conn->query("SELECT * FROM users WHERE id=$uid")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout – Blank Perfume</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="styles/homestyle.css">
  <style>
    body { background:#f7f7f5; }
    .page-wrap { max-width:920px;margin:36px auto;padding:0 20px 80px; }
    .page-title { font-size:30px;font-weight:700;letter-spacing:-1px;margin-bottom:24px; }
    .section-card { background:#fff;border-radius:20px;padding:26px 28px;box-shadow:0 2px 14px rgba(0,0,0,0.05);margin-bottom:20px; }
    .section-card h5 { font-weight:700;font-size:15px;border-bottom:1.5px solid #f0f0f0;padding-bottom:12px;margin-bottom:18px; }

    /* Steps */
    .steps { display:flex;gap:0;margin-bottom:32px; }
    .step { flex:1;text-align:center;position:relative; }
    .step-circle { width:36px;height:36px;border-radius:50%;background:#e8f7d0;color:#2a6e00;font-weight:700;font-size:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 6px; }
    .step.active .step-circle { background:#000;color:#fff; }
    .step.done .step-circle { background:#2a6e00;color:#fff; }
    .step-label { font-size:12px;color:#999;letter-spacing:.5px; }
    .step.active .step-label { color:#000;font-weight:600; }
    .step::after { content:'';position:absolute;top:18px;left:60%;width:80%;height:2px;background:#eee;z-index:0; }
    .step:last-child::after { display:none; }

    /* Payment methods */
    .method-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:12px; }
    .method-card { border:2px solid #eee;border-radius:14px;padding:16px 12px;text-align:center;cursor:pointer;transition:.2s; }
    .method-card:hover { border-color:#000; }
    .method-card.selected { border-color:#000;background:#f9fef4; }
    .method-card input { display:none; }
    .method-icon { font-size:26px;margin-bottom:8px; }
    .method-name { font-size:13px;font-weight:600; }

    /* Order items in checkout */
    .oi-row { display:flex;align-items:center;gap:14px;padding:10px 0;border-bottom:1px solid #f5f5f5;font-size:14px; }
    .oi-row:last-child { border-bottom:none; }
    .oi-img { width:52px;height:52px;background:#e8f7d0;border-radius:10px;display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0; }
    .oi-img img { height:42px;object-fit:contain; }

    .summary-row { display:flex;justify-content:space-between;font-size:14px;padding:7px 0;color:#555; }
    .summary-row.total { font-weight:700;font-size:17px;color:#000;border-top:1.5px solid #f0f0f0;margin-top:8px;padding-top:14px; }
    .summary-row.discount { color:#2a6e00; }

    .btn-pay { width:100%;background:#000;color:#fff;border:none;border-radius:14px;padding:15px;font-size:16px;font-weight:700;cursor:pointer;transition:.2s;margin-top:14px; }
    .btn-pay:hover { background:#222; }
    .points-banner { background:#e8f7d0;border-radius:12px;padding:12px 16px;font-size:13px;color:#2a6e00;display:flex;align-items:center;gap:8px;margin-bottom:16px; }

    /* Delivery fields */
    label.field-label { font-size:13px;font-weight:600;color:#555;margin-bottom:5px;display:block; }
    .field-input { width:100%;border:1.5px solid #e5e5e5;border-radius:12px;padding:11px 14px;font-size:14px;outline:none;transition:.2s;background:#fafafa; }
    .field-input:focus { border-color:#000;background:#fff; }
  </style>
</head>
<body>
  <?php include 'partials/navbar.php'; ?>

  <div class="page-wrap">
    <div class="page-title">Checkout</div>

    <!-- Steps -->
    <div class="steps">
      <div class="step done"><div class="step-circle"><i class="bi bi-check"></i></div><div class="step-label">CART</div></div>
      <div class="step active"><div class="step-circle">2</div><div class="step-label">PAYMENT</div></div>
      <div class="step"><div class="step-circle">3</div><div class="step-label">RECEIPT</div></div>
    </div>

    <form method="POST">
      <!-- Pass through values -->
      <input type="hidden" name="voucher_code" value="<?php echo htmlspecialchars($voucher_code); ?>">
      <input type="hidden" name="discount_amount" value="<?php echo $discount_amt; ?>">
      <input type="hidden" name="discount_percent" value="<?php echo $discount_pct; ?>">

      <div class="row g-4">
        <div class="col-md-7">

          <!-- Delivery info -->
          <div class="section-card">
            <h5>Delivery Information</h5>
            <div class="row g-3">
              <div class="col-12">
                <label class="field-label">Full Name</label>
                <input class="field-input" type="text" name="delivery_name" value="<?php echo htmlspecialchars($user['username']); ?>" required>
              </div>
              <div class="col-md-6">
                <label class="field-label">Phone Number</label>
                <input class="field-input" type="text" name="delivery_phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" placeholder="e.g. 011-1234 5678" required>
              </div>
              <div class="col-md-6">
                <label class="field-label">Email</label>
                <input class="field-input" type="email" name="delivery_email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
              </div>
              <div class="col-12">
                <label class="field-label">Delivery Address</label>
                <textarea class="field-input" name="delivery_address" rows="3" placeholder="Enter your full delivery address" required><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
              </div>
            </div>
          </div>

          <!-- Payment method -->
          <div class="section-card">
            <h5>Payment Method</h5>
            <div class="method-grid">
              <?php
              $methods = [
                ['value'=>'Online Banking','icon'=>'🏦','label'=>'Online Banking'],
                ['value'=>'Credit Card','icon'=>'💳','label'=>'Credit Card'],
                ['value'=>'Touch n Go','icon'=>'📱','label'=>'Touch n Go'],
                ['value'=>'GrabPay','icon'=>'🟢','label'=>'GrabPay'],
                ['value'=>'Boost','icon'=>'⚡','label'=>'Boost'],
                ['value'=>'ShopeePay','icon'=>'🛍️','label'=>'ShopeePay'],
              ];
              foreach ($methods as $i => $m): ?>
              <label class="method-card <?php echo $i===0?'selected':''; ?>" onclick="selectMethod(this)">
                <input type="radio" name="payment_method" value="<?php echo $m['value']; ?>" <?php echo $i===0?'checked':''; ?>>
                <div class="method-icon"><?php echo $m['icon']; ?></div>
                <div class="method-name"><?php echo $m['label']; ?></div>
              </label>
              <?php endforeach; ?>
            </div>
          </div>

        </div>

        <!-- Summary -->
        <div class="col-md-5">
          <div class="section-card">
            <h5>Order Summary</h5>
            <?php foreach ($rows as $r): ?>
            <div class="oi-row">
              <div class="oi-img"><img src="img/<?php echo htmlspecialchars($r['image']); ?>" alt=""></div>
              <div style="flex:1;">
                <div style="font-weight:600;"><?php echo htmlspecialchars($r['name']); ?></div>
                <div style="font-size:12px;color:#999;"><?php echo htmlspecialchars($r['variant']); ?> × <?php echo $r['quantity']; ?></div>
              </div>
              <div style="font-weight:700;">RM <?php echo number_format($r['price']*$r['quantity'],2); ?></div>
            </div>
            <?php endforeach; ?>

            <div style="margin-top:16px;">
              <div class="summary-row"><span>Subtotal</span><span>RM <?php echo number_format($subtotal,2); ?></span></div>
              <?php if ($discount_amt > 0): ?>
              <div class="summary-row discount"><span>Discount (<?php echo $discount_pct; ?>%)</span><span>− RM <?php echo number_format($discount_amt,2); ?></span></div>
              <?php endif; ?>
              <div class="summary-row"><span>Tax (6% SST)</span><span>RM <?php echo number_format($tax,2); ?></span></div>
              <div class="summary-row total"><span>Total</span><span>RM <?php echo number_format($total,2); ?></span></div>
            </div>

            <div class="points-banner mt-3">⭐ You'll earn <strong style="margin:0 4px;"><?php echo $points_earned; ?></strong> points from this order</div>

            <button type="submit" name="pay" class="btn-pay">Pay RM <?php echo number_format($total,2); ?></button>
            <div class="text-center mt-3"><a href="cart.php" style="font-size:13px;color:#888;text-decoration:none;">← Back to Cart</a></div>
          </div>
        </div>
      </div>
    </form>
  </div>

  <?php include 'partials/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function selectMethod(el) {
      document.querySelectorAll('.method-card').forEach(c => c.classList.remove('selected'));
      el.classList.add('selected');
      el.querySelector('input').checked = true;
    }
  </script>
</body>
</html>
