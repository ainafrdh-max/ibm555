<?php include "config.php"; ?>
<?php if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); } ?>
<?php
$uid = $_SESSION['user_id'];
$filter = $_GET['type'] ?? 'all';

$where = $filter !== 'all' ? "WHERE type='".mysqli_real_escape_string($conn, $filter)."'" : '';
$products = $conn->query("SELECT * FROM products $where ORDER BY type, id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shop – Blank Perfume</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="styles/homestyle.css">
  <style>
    body { background: #f7f7f5; }
    .page-wrap { max-width: 1100px; margin: 36px auto; padding: 0 20px 80px; }
    .page-title { font-size: 32px; font-weight: 700; letter-spacing: -1px; margin-bottom: 6px; }
    .page-sub { font-size: 14px; color: #888; margin-bottom: 28px; }

    .filter-bar { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 28px; }
    .filter-pill {
      border: 1.5px solid #000; background: transparent;
      border-radius: 999px; padding: 6px 20px; font-size: 13px;
      letter-spacing: 1px; cursor: pointer; transition: .2s;
      text-decoration: none; color: #000;
    }
    .filter-pill:hover, .filter-pill.active { background: #000; color: #fff; }

    .product-card {
      background: #fff; border-radius: 20px; overflow: hidden;
      box-shadow: 0 2px 16px rgba(0,0,0,0.06);
      transition: transform .3s, box-shadow .3s; height: 100%;
      display: flex; flex-direction: column;
    }
    .product-card:hover { transform: translateY(-6px); box-shadow: 0 12px 36px rgba(0,0,0,0.11); }
    .product-img-wrap {
      background: #e8f7d0; display: flex; align-items: center;
      justify-content: center; height: 210px; overflow: hidden; position: relative;
    }
    .product-img-wrap img { height: 160px; object-fit: contain; transition: transform .4s; }
    .product-card:hover .product-img-wrap img { transform: scale(1.05); }
    .type-badge {
      position: absolute; top: 12px; left: 12px;
      font-size: 10px; font-weight: 700; letter-spacing: 1px;
      padding: 3px 10px; border-radius: 999px; text-transform: uppercase;
    }
    .type-gel { background: #000; color: #fff; }
    .type-liquid { background: #333; color: #fff; }

    .product-body { padding: 18px 20px 20px; flex: 1; display: flex; flex-direction: column; }
    .product-body h6 { font-weight: 700; font-size: 15px; margin-bottom: 2px; }
    .variant-note { font-size: 12px; color: #999; margin-bottom: 14px; }
    .price { font-weight: 700; font-size: 17px; }
    .btn-add {
      background: #000; color: #fff; border: none;
      border-radius: 999px; padding: 9px 20px; font-size: 13px;
      font-weight: 600; transition: .2s; cursor: pointer;
    }
    .btn-add:hover { background: #333; }
    .btn-add:disabled { background: #ccc; cursor: not-allowed; }

    /* Cart float button */
    .cart-float {
      position: fixed; bottom: 30px; right: 30px;
      background: #000; color: #fff;
      border-radius: 999px; padding: 14px 24px;
      font-size: 14px; font-weight: 600;
      text-decoration: none; display: flex; align-items: center; gap: 8px;
      box-shadow: 0 8px 30px rgba(0,0,0,0.2); z-index: 999; transition: .2s;
    }
    .cart-float:hover { background: #222; color: #fff; transform: translateY(-2px); }
    .cart-float .badge { background: #e8f7d0; color: #000; border-radius: 999px; padding: 2px 8px; font-size: 12px; }

    /* Toast */
    .toast-msg {
      position: fixed; bottom: 100px; right: 30px;
      background: #111; color: #fff; padding: 12px 22px;
      border-radius: 12px; font-size: 14px; opacity: 0;
      transform: translateY(16px); transition: .3s; z-index: 9999; pointer-events: none;
    }
    .toast-msg.show { opacity: 1; transform: translateY(0); }

    .section-label {
      font-size: 11px; letter-spacing: 2.5px; font-weight: 700; color: #999;
      text-transform: uppercase; display: flex; align-items: center; gap: 10px;
      margin: 36px 0 18px;
    }
    .section-label::after { content:''; flex:1; height:1px; background:#e5e5e5; }
  </style>
</head>
<body>
  <?php include 'partials/navbar.php'; ?>

  <div class="page-wrap">
    <div class="page-title">Our Products</div>
    <div class="page-sub">Pick your scent. Freshen your drive.</div>

    <div class="filter-bar">
      <a href="products.php" class="filter-pill <?php echo $filter==='all'?'active':''; ?>">All</a>
      <a href="products.php?type=gel" class="filter-pill <?php echo $filter==='gel'?'active':''; ?>">Blank Gel</a>
      <a href="products.php?type=liquid" class="filter-pill <?php echo $filter==='liquid'?'active':''; ?>">Blank Liquid</a>
    </div>

    <?php
    $conn->data_seek($products ?? null, 0);
    $all = [];
    $gels = [];
    $liquids = [];
    $res = $conn->query("SELECT * FROM products ".($filter!=='all'?"WHERE type='".mysqli_real_escape_string($conn,$filter)."'":'')." ORDER BY type,id");
    while($p = $res->fetch_assoc()) {
      if($p['type']==='gel') $gels[]=$p; else $liquids[]=$p;
    }
    function renderProducts($arr) { ?>
      <div class="row g-4">
        <?php foreach($arr as $p): ?>
        <div class="col-md-4 col-sm-6">
          <div class="product-card">
            <div class="product-img-wrap">
              <span class="type-badge type-<?php echo $p['type']; ?>"><?php echo ucfirst($p['type']); ?></span>
              <img src="img/<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['variant']); ?>">
            </div>
            <div class="product-body">
              <h6><?php echo htmlspecialchars($p['name']); ?></h6>
              <div class="variant-note"><?php echo htmlspecialchars($p['variant']); ?></div>
              <div class="d-flex justify-content-between align-items-center mt-auto">
                <span class="price">RM <?php echo number_format($p['price'],2); ?></span>
                <button class="btn-add" onclick="addToCart(<?php echo $p['id']; ?>, '<?php echo htmlspecialchars($p['variant']); ?>')">
                  <i class="bi bi-plus"></i> Add
                </button>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    <?php } ?>

    <?php if (!empty($gels) && ($filter==='all'||$filter==='gel')): ?>
      <div class="section-label">🫙 Blank Gel</div>
      <?php renderProducts($gels); ?>
    <?php endif; ?>

    <?php if (!empty($liquids) && ($filter==='all'||$filter==='liquid')): ?>
      <div class="section-label">💧 Blank Liquid</div>
      <?php renderProducts($liquids); ?>
    <?php endif; ?>
  </div>

  <!-- Floating cart button -->
  <?php
  $cc = $conn->query("SELECT SUM(quantity) as c FROM cart WHERE user_id=$uid")->fetch_assoc();
  $cartCount = $cc['c'] ?? 0;
  ?>
  <a href="cart.php" class="cart-float">
    <i class="bi bi-cart3"></i> Cart
    <span class="badge" id="cartBadge"><?php echo $cartCount; ?></span>
  </a>
  <div class="toast-msg" id="toastMsg"></div>

  <?php include 'partials/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function addToCart(productId, name) {
      fetch('cart_action.php', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: `action=add&product_id=${productId}`
      })
      .then(r => r.json())
      .then(data => {
        document.getElementById('cartBadge').textContent = data.cart_count;
        showToast(`✓ ${name} added to cart`);
      });
    }

    function showToast(msg) {
      const t = document.getElementById('toastMsg');
      t.textContent = msg;
      t.classList.add('show');
      setTimeout(() => t.classList.remove('show'), 2500);
    }
  </script>
</body>
</html>
