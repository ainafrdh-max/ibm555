<?php include "config.php"; ?>
<?php
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
$uid = $_SESSION['user_id'];

// Points
$pts = $conn->query("SELECT total_points FROM user_points WHERE user_id=$uid")->fetch_assoc();
$points = $pts ? $pts['total_points'] : 0;

// Cart count
$cc = $conn->query("SELECT SUM(quantity) as c FROM cart WHERE user_id=$uid")->fetch_assoc();
$cartCount = $cc['c'] ?? 0;

// Recent orders
$orders = $conn->query("SELECT * FROM orders WHERE user_id=$uid ORDER BY created_at DESC LIMIT 3");

// User info
$user = $conn->query("SELECT * FROM users WHERE id=$uid")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home – Blank Perfume</title>
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

    /* Greeting */
    .greeting-bar {
      background: #e8f7d0;
      border-radius: 20px;
      padding: 28px 32px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 16px;
      margin-bottom: 24px;
    }

    .greeting-bar h2 {
      font-size: 24px;
      font-weight: 700;
      margin: 0;
    }

    .greeting-bar p {
      font-size: 14px;
      color: #555;
      margin: 4px 0 0;
    }

    /* Stats row */
    .stat-card {
      background: #fff;
      border-radius: 18px;
      padding: 22px 24px;
      box-shadow: 0 2px 14px rgba(0, 0, 0, 0.05);
      height: 100%;
    }

    .stat-card .label {
      font-size: 12px;
      letter-spacing: 1px;
      color: #999;
      text-transform: uppercase;
    }

    .stat-card .value {
      font-size: 32px;
      font-weight: 700;
      letter-spacing: -1px;
      margin: 6px 0 2px;
    }

    .stat-card .sub {
      font-size: 13px;
      color: #777;
    }

    .stat-icon {
      font-size: 28px;
      margin-bottom: 10px;
    }

    /* Quick actions */
    .action-card {
      background: #fff;
      border-radius: 18px;
      padding: 22px;
      box-shadow: 0 2px 14px rgba(0, 0, 0, 0.05);
      text-decoration: none;
      color: #000;
      display: flex;
      align-items: center;
      gap: 16px;
      transition: transform .2s, box-shadow .2s;
      height: 100%;
    }

    .action-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
      color: #000;
    }

    .action-icon {
      width: 48px;
      height: 48px;
      border-radius: 14px;
      background: #e8f7d0;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
      flex-shrink: 0;
    }

    .action-card h6 {
      font-weight: 700;
      font-size: 15px;
      margin: 0 0 3px;
    }

    .action-card p {
      font-size: 13px;
      color: #888;
      margin: 0;
    }

    /* Orders table */
    .section-card {
      background: #fff;
      border-radius: 20px;
      padding: 26px 30px;
      box-shadow: 0 2px 14px rgba(0, 0, 0, 0.05);
      margin-bottom: 24px;
    }

    .section-card h5 {
      font-weight: 700;
      font-size: 15px;
      border-bottom: 1.5px solid #f0f0f0;
      padding-bottom: 14px;
      margin-bottom: 18px;
    }

    .order-row {
      padding: 12px 0;
      border-bottom: 1px solid #f5f5f5;
      font-size: 14px;
    }

    .order-row:last-child {
      border-bottom: none;
    }

    .status-badge {
      font-size: 11px;
      font-weight: 700;
      letter-spacing: .5px;
      padding: 3px 12px;
      border-radius: 999px;
      text-transform: uppercase;
    }

    .status-paid {
      background: #e8f7d0;
      color: #2a6e00;
    }

    .status-pending {
      background: #fff3cd;
      color: #856404;
    }

    .status-shipped {
      background: #cfe2ff;
      color: #084298;
    }

    .btn-black {
      background: #000;
      color: #fff;
      border: none;
      border-radius: 999px;
      padding: 10px 24px;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      transition: .2s;
      text-decoration: none;
      display: inline-block;
    }

    .btn-black:hover {
      background: #222;
      color: #fff;
    }

    .btn-outline {
      background: transparent;
      color: #000;
      border: 1.5px solid #000;
      border-radius: 999px;
      padding: 9px 22px;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      transition: .2s;
      text-decoration: none;
      display: inline-block;
    }

    .btn-outline:hover {
      background: #000;
      color: #fff;
    }
  </style>
</head>

<body>
  <?php include 'partials/navbar.php'; ?>

  <div class="page-wrap">

    <!-- Greeting -->
    <div class="greeting-bar">
      <div>
        <h2>Hey, <?php echo htmlspecialchars($user['username']); ?> 👋</h2>
        <p>Welcome back to Blank Perfume. What would you like today?</p>
      </div>
      <a href="products.php" class="btn-black">Shop Now</a>
    </div>

    <!-- Stats -->
    <div class="row g-3 mb-4">
      <div class="col-6 col-md-3">
        <div class="stat-card">
          <div class="stat-icon">⭐</div>
          <div class="label">My Points</div>
          <div class="value"><?php echo number_format($points); ?></div>
          <div class="sub">Earn points by buying products</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-card">
          <div class="stat-icon">🛒</div>
          <div class="label">Cart Items</div>
          <div class="value"><?php echo $cartCount; ?></div>
          <div class="sub"><a href="cart.php" style="color:#000;">View cart</a></div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-card">
          <div class="stat-icon">📦</div>
          <div class="label">Total Orders</div>
          <?php $oc = $conn->query("SELECT COUNT(*) as c FROM orders WHERE user_id=$uid")->fetch_assoc(); ?>
          <div class="value"><?php echo $oc['c']; ?></div>
          <div class="sub"><a href="profile.php#history" style="color:#000;">View history</a></div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-card">
          <div class="stat-icon">💰</div>
          <div class="label">Total Spent</div>
          <?php $ts = $conn->query("SELECT SUM(total) as s FROM orders WHERE user_id=$uid")->fetch_assoc(); ?>
          <div class="value" style="font-size:22px;">RM <?php echo number_format($ts['s'] ?? 0, 2); ?></div>
          <div class="sub">All time</div>
        </div>
      </div>
    </div>

    <!-- Quick actions -->
    <div class="row g-3 mb-4">
      <div class="col-6 col-md-3">
        <a href="products.php" class="action-card">
          <div class="action-icon">🛍️</div>
          <div>
            <h6>Shop</h6>
            <p>Browse products</p>
          </div>
        </a>
      </div>
      <div class="col-6 col-md-3">
        <a href="cart.php" class="action-card">
          <div class="action-icon">🛒</div>
          <div>
            <h6>My Cart</h6>
            <p>Review items</p>
          </div>
        </a>
      </div>
      <div class="col-6 col-md-3">
        <a href="profile.php" class="action-card">
          <div class="action-icon">👤</div>
          <div>
            <h6>Profile</h6>
            <p>Edit your info</p>
          </div>
        </a>
      </div>
      <div class="col-6 col-md-3">
        <a href="logout.php" class="action-card">
          <div class="action-icon">🚪</div>
          <div>
            <h6>Logout</h6>
            <p>Sign out</p>
          </div>
        </a>
      </div>
    </div>

    <!-- Recent Orders -->
    <div class="section-card">
      <h5>Recent Orders</h5>
      <?php if ($orders->num_rows === 0): ?>
        <p class="text-muted" style="font-size:14px;">No orders yet. <a href="products.php">Start shopping!</a></p>
      <?php else:
        while ($o = $orders->fetch_assoc()): ?>
          <div class="order-row d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
              <div style="font-weight:600;">Order #<?php echo str_pad($o['id'], 5, '0', STR_PAD_LEFT); ?></div>
              <div style="color:#888;font-size:13px;"><?php echo date('d M Y, g:ia', strtotime($o['created_at'])); ?></div>
            </div>
            <div class="text-center">
              <span class="status-badge status-<?php echo $o['status']; ?>"><?php echo ucfirst($o['status']); ?></span>
            </div>
            <div style="font-weight:700;">RM <?php echo number_format($o['total'], 2); ?></div>
            <a href="receipt.php?id=<?php echo $o['id']; ?>" class="btn-outline">Receipt</a>
          </div>
        <?php endwhile; endif; ?>
      <?php if ($orders->num_rows > 0): ?>
        <div class="mt-3"><a href="profile.php#history" class="btn-outline">View all orders</a></div>
      <?php endif; ?>
    </div>

  </div>

  <?php include 'partials/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>