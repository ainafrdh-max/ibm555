<?php include "config.php"; ?>
<?php if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); } ?>
<?php
$uid = $_SESSION['user_id'];
$success = '';
$error = '';

// Handle profile update
if (isset($_POST['update_profile'])) {
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $phone    = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $address  = mysqli_real_escape_string($conn, trim($_POST['address']));

    if (empty($username)) {
        $error = "Username cannot be empty.";
    } else {
        $conn->query("UPDATE users SET username='$username', phone='$phone', address='$address' WHERE id=$uid");
        $_SESSION['username'] = $username;
        $success = "Profile updated successfully!";
    }
}

// Handle password change
if (isset($_POST['change_password'])) {
    $current = $_POST['current_password'];
    $new     = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    $user = $conn->query("SELECT * FROM users WHERE id=$uid")->fetch_assoc();
    if (!password_verify($current, $user['password'])) {
        $error = "Current password is incorrect.";
    } elseif (strlen($new) < 6) {
        $error = "New password must be at least 6 characters.";
    } elseif ($new !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $hash = password_hash($new, PASSWORD_DEFAULT);
        $conn->query("UPDATE users SET password='$hash' WHERE id=$uid");
        $success = "Password changed successfully!";
    }
}

$user = $conn->query("SELECT * FROM users WHERE id=$uid")->fetch_assoc();
$pts  = $conn->query("SELECT total_points FROM user_points WHERE user_id=$uid")->fetch_assoc();
$points = $pts ? $pts['total_points'] : 0;

$orders = $conn->query("SELECT * FROM orders WHERE user_id=$uid ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Profile – Blank Perfume</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="styles/homestyle.css">
  <style>
    body { background:#f7f7f5; }
    .page-wrap { max-width:960px;margin:36px auto;padding:0 20px 80px; }
    .page-title { font-size:30px;font-weight:700;letter-spacing:-1px;margin-bottom:24px; }
    .section-card { background:#fff;border-radius:20px;padding:26px 28px;box-shadow:0 2px 14px rgba(0,0,0,0.05);margin-bottom:20px; }
    .section-card h5 { font-weight:700;font-size:15px;border-bottom:1.5px solid #f0f0f0;padding-bottom:12px;margin-bottom:20px; }

    /* Tabs */
    .tab-bar { display:flex;gap:6px;margin-bottom:24px;border-bottom:1.5px solid #eee;padding-bottom:0; }
    .tab-btn { border:none;background:none;padding:10px 20px;font-size:14px;font-weight:600;color:#aaa;cursor:pointer;border-bottom:2.5px solid transparent;margin-bottom:-1.5px;transition:.2s; }
    .tab-btn.active { color:#000;border-bottom-color:#000; }
    .tab-content { display:none; }
    .tab-content.active { display:block; }

    /* Profile avatar */
    .avatar-wrap { display:flex;align-items:center;gap:20px;margin-bottom:28px; }
    .avatar { width:72px;height:72px;border-radius:50%;background:#e8f7d0;display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:700;color:#2a6e00;flex-shrink:0; }
    .avatar-info h4 { font-size:20px;font-weight:700;margin:0 0 4px; }
    .avatar-info p { font-size:13px;color:#888;margin:0; }

    /* Points card */
    .points-card { background:#e8f7d0;border-radius:14px;padding:16px 20px;display:flex;justify-content:space-between;align-items:center;margin-bottom:24px; }
    .points-card .pts-num { font-size:28px;font-weight:700;color:#2a6e00; }
    .points-card .pts-label { font-size:12px;color:#555;letter-spacing:.5px; }
    .pts-info { font-size:13px;color:#555; }

    /* Form fields */
    label.fl { font-size:13px;font-weight:600;color:#555;margin-bottom:5px;display:block; }
    .fi { width:100%;border:1.5px solid #e5e5e5;border-radius:12px;padding:11px 14px;font-size:14px;outline:none;transition:.2s;background:#fafafa;margin-bottom:16px; }
    .fi:focus { border-color:#000;background:#fff; }

    .btn-save { background:#000;color:#fff;border:none;border-radius:999px;padding:11px 30px;font-size:14px;font-weight:600;cursor:pointer;transition:.2s; }
    .btn-save:hover { background:#222; }

    /* Order history */
    .order-card { border:1.5px solid #f0f0f0;border-radius:16px;padding:18px 20px;margin-bottom:14px;transition:.2s; }
    .order-card:hover { border-color:#ddd;box-shadow:0 4px 16px rgba(0,0,0,0.06); }
    .order-meta { display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px; }
    .order-num { font-weight:700;font-size:15px; }
    .order-date { font-size:12px;color:#999;margin-top:2px; }
    .status-badge { font-size:11px;font-weight:700;letter-spacing:.5px;padding:4px 12px;border-radius:999px;text-transform:uppercase; }
    .status-paid { background:#e8f7d0;color:#2a6e00; }
    .status-pending { background:#fff3cd;color:#856404; }
    .status-shipped { background:#cfe2ff;color:#084298; }
    .status-completed { background:#d1e7dd;color:#0a3622; }
    .order-items-preview { font-size:13px;color:#777;margin-top:8px; }
    .order-total { font-weight:700;font-size:16px; }

    .alert-success { background:#e8f7d0;border:none;border-radius:12px;color:#2a6e00;font-size:14px;padding:12px 18px; }
    .alert-danger { background:#fff0f0;border:1.5px solid #ffcccc;border-radius:12px;color:#cc0000;font-size:14px;padding:12px 18px; }
  </style>
</head>
<body>
  <?php include 'partials/navbar.php'; ?>

  <div class="page-wrap">
    <div class="page-title">My Account</div>

    <?php if ($success): ?>
      <div class="alert-success mb-3"><i class="bi bi-check-circle-fill me-2"></i><?php echo $success; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="alert-danger mb-3"><i class="bi bi-exclamation-circle-fill me-2"></i><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Avatar + points -->
    <div class="section-card">
      <div class="avatar-wrap">
        <div class="avatar"><?php echo strtoupper(substr($user['username'],0,1)); ?></div>
        <div class="avatar-info">
          <h4><?php echo htmlspecialchars($user['username']); ?></h4>
          <p><?php echo htmlspecialchars($user['email']); ?></p>
        </div>
      </div>
      <div class="points-card">
        <div>
          <div class="pts-label">MY POINTS BALANCE</div>
          <div class="pts-num">⭐ <?php echo number_format($points); ?> pts</div>
          <div class="pts-info">Earn 1 point for every RM1 spent</div>
        </div>
        <div style="text-align:right;">
          <div style="font-size:12px;color:#555;margin-bottom:4px;">Redeemable soon</div>
          <div style="font-size:22px;">🎁</div>
        </div>
      </div>
    </div>

    <!-- Tabs -->
    <div class="tab-bar">
      <button class="tab-btn active" onclick="switchTab('profile', this)">Profile Info</button>
      <button class="tab-btn" onclick="switchTab('password', this)">Change Password</button>
      <button class="tab-btn" id="historyTabBtn" onclick="switchTab('history', this)">Order History</button>
    </div>

    <!-- Tab: Profile -->
    <div class="tab-content active" id="tab-profile">
      <div class="section-card">
        <h5>Edit Profile</h5>
        <form method="POST">
          <div class="row g-0">
            <div class="col-md-6 pe-md-3">
              <label class="fl">Username</label>
              <input class="fi" type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="col-md-6 ps-md-3">
              <label class="fl">Email (read-only)</label>
              <input class="fi" type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled style="background:#f0f0f0;color:#999;">
            </div>
            <div class="col-md-6 pe-md-3">
              <label class="fl">Phone Number</label>
              <input class="fi" type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" placeholder="e.g. 011-1234 5678">
            </div>
            <div class="col-12">
              <label class="fl">Delivery Address</label>
              <textarea class="fi" name="address" rows="3" placeholder="Your default delivery address"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
            </div>
          </div>
          <button type="submit" name="update_profile" class="btn-save">Save Changes</button>
        </form>
      </div>
    </div>

    <!-- Tab: Password -->
    <div class="tab-content" id="tab-password">
      <div class="section-card">
        <h5>Change Password</h5>
        <form method="POST" style="max-width:480px;">
          <label class="fl">Current Password</label>
          <input class="fi" type="password" name="current_password" required>
          <label class="fl">New Password</label>
          <input class="fi" type="password" name="new_password" required minlength="6">
          <label class="fl">Confirm New Password</label>
          <input class="fi" type="password" name="confirm_password" required minlength="6">
          <button type="submit" name="change_password" class="btn-save">Update Password</button>
        </form>
      </div>
    </div>

    <!-- Tab: Order History -->
    <div class="tab-content" id="tab-history">
      <?php if ($orders->num_rows === 0): ?>
        <div class="section-card text-center py-4">
          <div style="font-size:40px;">📦</div>
          <h5 style="border:none;margin-top:12px;">No orders yet</h5>
          <p class="text-muted mb-4">Your order history will appear here once you make a purchase.</p>
          <a href="products.php" style="background:#000;color:#fff;border-radius:999px;padding:11px 28px;text-decoration:none;font-weight:600;">Start Shopping</a>
        </div>
      <?php else: ?>
      <div class="section-card">
        <h5>All Orders (<?php echo $orders->num_rows; ?>)</h5>
        <?php while ($o = $orders->fetch_assoc()):
          $oi = $conn->query("SELECT oi.quantity, p.name, p.variant FROM order_items oi JOIN products p ON oi.product_id=p.id WHERE oi.order_id={$o['id']} LIMIT 3");
          $preview = [];
          while ($i = $oi->fetch_assoc()) $preview[] = $i['name'].' '.$i['variant'].' ×'.$i['quantity'];
        ?>
        <div class="order-card">
          <div class="order-meta">
            <div>
              <div class="order-num">Order #<?php echo str_pad($o['id'],5,'0',STR_PAD_LEFT); ?></div>
              <div class="order-date"><?php echo date('d M Y, g:ia', strtotime($o['created_at'])); ?> · <?php echo htmlspecialchars($o['payment_method']); ?></div>
              <div class="order-items-preview"><?php echo implode(', ', $preview); ?></div>
            </div>
            <div style="text-align:right;">
              <span class="status-badge status-<?php echo $o['status']; ?>"><?php echo ucfirst($o['status']); ?></span>
              <div class="order-total mt-2">RM <?php echo number_format($o['total'],2); ?></div>
              <?php if ($o['points_earned']): ?>
              <div style="font-size:12px;color:#2a6e00;">+<?php echo $o['points_earned']; ?> pts</div>
              <?php endif; ?>
            </div>
          </div>
          <div class="mt-2">
            <a href="receipt.php?id=<?php echo $o['id']; ?>" style="font-size:13px;font-weight:600;color:#000;text-decoration:none;"><i class="bi bi-receipt me-1"></i>View Receipt</a>
          </div>
        </div>
        <?php endwhile; ?>
      </div>
      <?php endif; ?>
    </div>

  </div>

  <?php include 'partials/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function switchTab(name, btn) {
      document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
      document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
      document.getElementById('tab-' + name).classList.add('active');
      btn.classList.add('active');
    }
    // Auto-open history if anchor in URL
    if (window.location.hash === '#history') {
      switchTab('history', document.getElementById('historyTabBtn'));
    }
  </script>
</body>
</html>
