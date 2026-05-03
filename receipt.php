<?php include "config.php"; ?>
<?php if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); } ?>
<?php
$uid = $_SESSION['user_id'];
$order_id = (int)($_GET['id'] ?? 0);

$order = $conn->query("SELECT * FROM orders WHERE id=$order_id AND user_id=$uid")->fetch_assoc();
if (!$order) { header("Location: homepage.php"); exit(); }

$items = $conn->query("
    SELECT oi.*, p.name, p.variant, p.image
    FROM order_items oi JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = $order_id
");
$user = $conn->query("SELECT * FROM users WHERE id=$uid")->fetch_assoc();
$pts = $conn->query("SELECT total_points FROM user_points WHERE user_id=$uid")->fetch_assoc();
$totalPoints = $pts ? $pts['total_points'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Receipt – Blank Perfume</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="styles/homestyle.css">
  <style>
    body { background:#f7f7f5; }
    .page-wrap { max-width:680px;margin:36px auto;padding:0 20px 80px; }

    /* Steps */
    .steps { display:flex;gap:0;margin-bottom:32px; }
    .step { flex:1;text-align:center;position:relative; }
    .step-circle { width:36px;height:36px;border-radius:50%;background:#e8f7d0;color:#2a6e00;font-weight:700;font-size:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 6px; }
    .step.done .step-circle { background:#2a6e00;color:#fff; }
    .step-label { font-size:12px;color:#999;letter-spacing:.5px; }
    .step.done .step-label { color:#2a6e00;font-weight:600; }
    .step::after { content:'';position:absolute;top:18px;left:60%;width:80%;height:2px;background:#2a6e00;z-index:0; }
    .step:last-child::after { display:none; }

    /* Receipt card */
    .receipt-card { background:#fff;border-radius:24px;padding:36px 32px;box-shadow:0 4px 30px rgba(0,0,0,0.08); }

    .receipt-header { text-align:center;padding-bottom:24px;border-bottom:2px dashed #eee;margin-bottom:24px; }
    .success-icon { font-size:52px;margin-bottom:12px; }
    .receipt-header h2 { font-size:24px;font-weight:700;letter-spacing:-.5px;margin-bottom:4px; }
    .receipt-header p { font-size:14px;color:#888; }
    .order-num { font-size:13px;font-weight:700;letter-spacing:1px;color:#555;background:#f5f5f5;border-radius:8px;padding:6px 14px;display:inline-block;margin-top:8px; }

    /* Info grid */
    .info-grid { display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px; }
    .info-box { background:#f9f9f9;border-radius:14px;padding:14px 16px; }
    .info-box .label { font-size:11px;letter-spacing:1px;color:#aaa;text-transform:uppercase;margin-bottom:4px; }
    .info-box .val { font-size:14px;font-weight:600; }

    /* Items */
    .item-row { display:flex;align-items:center;gap:14px;padding:12px 0;border-bottom:1px solid #f5f5f5;font-size:14px; }
    .item-row:last-child { border-bottom:none; }
    .item-img { width:52px;height:52px;background:#e8f7d0;border-radius:10px;display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0; }
    .item-img img { height:42px;object-fit:contain; }

    /* Totals */
    .totals-section { border-top:2px dashed #eee;margin-top:20px;padding-top:20px; }
    .total-row { display:flex;justify-content:space-between;font-size:14px;padding:5px 0;color:#666; }
    .total-row.grand { font-weight:700;font-size:18px;color:#000;margin-top:8px;padding-top:12px;border-top:1.5px solid #eee; }
    .total-row.discount { color:#2a6e00; }

    /* Points banner */
    .points-banner { background:#e8f7d0;border-radius:14px;padding:16px 20px;display:flex;justify-content:space-between;align-items:center;margin-top:24px; }
    .points-banner .earned { font-size:13px;color:#2a6e00; }
    .points-banner .total-pts { font-size:18px;font-weight:700;color:#2a6e00; }

    /* Actions */
    .action-row { display:flex;gap:12px;margin-top:24px;flex-wrap:wrap; }
    .btn-black { flex:1;background:#000;color:#fff;border:none;border-radius:999px;padding:13px;font-size:14px;font-weight:600;cursor:pointer;transition:.2s;text-decoration:none;text-align:center;display:block; }
    .btn-black:hover { background:#222;color:#fff; }
    .btn-outline { flex:1;background:transparent;color:#000;border:1.5px solid #000;border-radius:999px;padding:12px;font-size:14px;font-weight:600;cursor:pointer;transition:.2s;text-decoration:none;text-align:center;display:block; }
    .btn-outline:hover { background:#000;color:#fff; }

    @media print {
      .action-row, nav, footer { display:none !important; }
      body { background:#fff; }
    }
  </style>
</head>
<body>
  <?php include 'partials/navbar.php'; ?>

  <div class="page-wrap">
    <!-- Steps -->
    <div class="steps">
      <div class="step done"><div class="step-circle"><i class="bi bi-check"></i></div><div class="step-label">CART</div></div>
      <div class="step done"><div class="step-circle"><i class="bi bi-check"></i></div><div class="step-label">PAYMENT</div></div>
      <div class="step done"><div class="step-circle"><i class="bi bi-check"></i></div><div class="step-label">RECEIPT</div></div>
    </div>

    <div class="receipt-card">
      <!-- Header -->
      <div class="receipt-header">
        <div class="success-icon">✅</div>
        <h2>Payment Successful!</h2>
        <p>Thank you, <strong><?php echo htmlspecialchars($user['username']); ?></strong>! Your order has been confirmed.</p>
        <div class="order-num">ORDER #<?php echo str_pad($order['id'],5,'0',STR_PAD_LEFT); ?></div>
      </div>

      <!-- Info grid -->
      <div class="info-grid">
        <div class="info-box">
          <div class="label">Date</div>
          <div class="val"><?php echo date('d M Y', strtotime($order['created_at'])); ?></div>
        </div>
        <div class="info-box">
          <div class="label">Time</div>
          <div class="val"><?php echo date('g:i A', strtotime($order['created_at'])); ?></div>
        </div>
        <div class="info-box">
          <div class="label">Payment Method</div>
          <div class="val"><?php echo htmlspecialchars($order['payment_method']); ?></div>
        </div>
        <div class="info-box">
          <div class="label">Status</div>
          <div class="val" style="color:#2a6e00;">✓ <?php echo ucfirst($order['status']); ?></div>
        </div>
      </div>

      <!-- Items -->
      <div style="font-size:12px;letter-spacing:1px;color:#aaa;text-transform:uppercase;margin-bottom:10px;">Items Ordered</div>
      <?php while ($item = $items->fetch_assoc()): ?>
      <div class="item-row">
        <div class="item-img"><img src="img/<?php echo htmlspecialchars($item['image']); ?>" alt=""></div>
        <div style="flex:1;">
          <div style="font-weight:600;"><?php echo htmlspecialchars($item['name']); ?></div>
          <div style="font-size:12px;color:#999;"><?php echo htmlspecialchars($item['variant']); ?> × <?php echo $item['quantity']; ?></div>
        </div>
        <div style="font-weight:700;">RM <?php echo number_format($item['unit_price']*$item['quantity'],2); ?></div>
      </div>
      <?php endwhile; ?>

      <!-- Totals -->
      <div class="totals-section">
        <div class="total-row"><span>Subtotal</span><span>RM <?php echo number_format($order['subtotal'],2); ?></span></div>
        <?php if ($order['discount'] > 0): ?>
        <div class="total-row discount"><span>Discount <?php echo $order['voucher_code']?"({$order['voucher_code']})":''; ?></span><span>− RM <?php echo number_format($order['discount'],2); ?></span></div>
        <?php endif; ?>
        <div class="total-row"><span>Tax (6% SST)</span><span>RM <?php echo number_format($order['tax'],2); ?></span></div>
        <div class="total-row grand"><span>Total Paid</span><span>RM <?php echo number_format($order['total'],2); ?></span></div>
      </div>

      <!-- Points -->
      <div class="points-banner">
        <div>
          <div class="earned">⭐ You earned <strong><?php echo $order['points_earned']; ?></strong> points from this order</div>
          <div style="font-size:12px;color:#666;margin-top:2px;">Points can be used for future discounts</div>
        </div>
        <div>
          <div style="font-size:11px;color:#888;text-align:right;">Total Points</div>
          <div class="total-pts"><?php echo number_format($totalPoints); ?> pts</div>
        </div>
      </div>

      <!-- Actions -->
      <div class="action-row">
        <a href="products.php" class="btn-outline">Shop Again</a>
        <a href="javascript:window.print()" class="btn-black"><i class="bi bi-printer"></i> Print Receipt</a>
      </div>
      <div class="text-center mt-3">
        <a href="homepage.php" style="font-size:13px;color:#888;text-decoration:none;">← Back to Dashboard</a>
      </div>
    </div>
  </div>

  <?php include 'partials/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
