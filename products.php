<?php include "config.php"; ?>
<?php if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
} ?>
<?php
$uid = (int) $_SESSION['user_id'];
$filter = $_GET['type'] ?? 'all';
if (!in_array($filter, ['all', 'gel', 'liquid'], true)) {
  $filter = 'all';
}

$gels = [];
$liquids = [];
$sql = "SELECT * FROM products";
if ($filter !== 'all') {
  $type = mysqli_real_escape_string($conn, $filter);
  $sql .= " WHERE type = '$type'";
}
$sql .= " ORDER BY type, id";
$res = $conn->query($sql);
if ($res) {
  while ($p = $res->fetch_assoc()) {
    if ($p['type'] === 'gel') {
      $gels[] = $p;
    } else {
      $liquids[] = $p;
    }
  }
}
$cartCount = get_cart_count($conn, $uid);
$totalShown = count($gels) + count($liquids);
if ($filter === 'gel') {
  $totalShown = count($gels);
} elseif ($filter === 'liquid') {
  $totalShown = count($liquids);
}
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
    body {
      background: #f4f4f2;
    }

    .shop-hero {
      background:
        linear-gradient(135deg, rgba(232, 247, 208, 0.82), rgba(246, 255, 233, 0.88)),
        url('img/allBlank.png') center / cover no-repeat;
      padding: 48px 20px 36px;
      text-align: center;
    }

    .shop-hero h1 {
      font-size: clamp(36px, 6vw, 52px);
      font-weight: 800;
      letter-spacing: -1px;
      margin-bottom: 8px;
    }

    .shop-hero p {
      color: #555;
      font-size: 16px;
      margin: 0;
    }

    .shop-toolbar {
      max-width: 1100px;
      margin: -22px auto 0;
      padding: 0 20px;
      position: relative;
      z-index: 2;
    }

    .toolbar-inner {
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
      padding: 18px 22px;
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
    }

    .filter-bar {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }

    .filter-pill {
      border: 1.5px solid #000;
      background: #fff;
      border-radius: 999px;
      padding: 8px 20px;
      font-size: 13px;
      font-weight: 600;
      transition: 0.25s ease;
      text-decoration: none;
      color: #000;
    }

    .filter-pill:hover,
    .filter-pill.active {
      background: #000;
      color: #fff;
    }

    .product-count {
      font-size: 13px;
      color: #888;
      font-weight: 500;
    }

    .product-count strong {
      color: #000;
    }

    .shop-main {
      max-width: 1100px;
      margin: 0 auto;
      padding: 36px 20px 100px;
    }

    .section-label {
      font-size: 13px;
      letter-spacing: 2px;
      font-weight: 700;
      color: #888;
      text-transform: uppercase;
      display: flex;
      align-items: center;
      gap: 12px;
      margin: 40px 0 22px;
    }

    .section-label:first-of-type {
      margin-top: 8px;
    }

    .section-label::after {
      content: '';
      flex: 1;
      height: 1px;
      background: #ddd;
    }

    .product-card {
      background: #fff;
      border-radius: 22px;
      overflow: hidden;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      height: 100%;
      display: flex;
      flex-direction: column;
      border: 1px solid rgba(0, 0, 0, 0.04);
    }

    .product-card:hover {
      transform: translateY(-7px);
      box-shadow: 0 16px 40px rgba(0, 0, 0, 0.12);
    }

    .product-img-wrap {
      background: linear-gradient(180deg, #eef9de 0%, #f8fff0 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      height: 230px;
      overflow: hidden;
      position: relative;
      padding: 16px;
    }

    .product-img-wrap img {
      max-height: 175px;
      width: auto;
      object-fit: contain;
      transition: transform 0.35s ease;
    }

    .product-card:hover .product-img-wrap img {
      transform: scale(1.06);
    }

    .type-badge {
      position: absolute;
      top: 14px;
      left: 14px;
      font-size: 10px;
      font-weight: 700;
      letter-spacing: 0.5px;
      padding: 5px 11px;
      border-radius: 999px;
      text-transform: uppercase;
    }

    .type-gel {
      background: #000;
      color: #fff;
    }

    .type-liquid {
      background: #333;
      color: #fff;
    }

    .recommended-badge {
      position: absolute;
      top: 14px;
      right: 14px;
      background: #e8f7d0;
      color: #000;
      border: 1.5px solid #000;
      font-size: 10px;
      font-weight: 700;
      padding: 5px 10px;
      border-radius: 999px;
      z-index: 1;
    }

    .product-card.recommended {
      box-shadow: 0 8px 28px rgba(42, 110, 0, 0.14);
      outline: 1.5px solid #c5e6a8;
    }

    .product-body {
      padding: 20px 22px 22px;
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .product-category {
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      color: #aaa;
      margin-bottom: 4px;
    }

    .product-title {
      font-size: 17px;
      font-weight: 700;
      margin: 0 0 6px;
      line-height: 1.3;
    }

    .recommended-note {
      font-size: 12px;
      font-weight: 600;
      color: #2a6e00;
      margin-bottom: 10px;
      display: flex;
      align-items: flex-start;
      gap: 6px;
    }

    .product-desc {
      font-size: 13px;
      color: #666;
      line-height: 1.55;
      margin-bottom: 14px;
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .product-meta {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 8px;
      margin-bottom: 16px;
    }

    .stock-pill {
      font-size: 11px;
      font-weight: 600;
      padding: 4px 10px;
      border-radius: 999px;
      background: #e8f7d0;
      color: #2a6e00;
    }

    .stock-pill.out {
      background: #ffe8e8;
      color: #cc0000;
    }

    .product-footer {
      margin-top: auto;
      padding-top: 14px;
      border-top: 1px solid #f0f0f0;
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 12px;
    }

    .price {
      font-weight: 800;
      font-size: 20px;
      letter-spacing: -0.5px;
    }

    .price small {
      font-size: 12px;
      font-weight: 600;
      color: #999;
      display: block;
      margin-top: 2px;
    }

    .btn-add {
      background: #000;
      color: #fff;
      border: none;
      border-radius: 999px;
      padding: 10px 22px;
      font-size: 13px;
      font-weight: 600;
      transition: 0.2s;
      cursor: pointer;
      white-space: nowrap;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .btn-add:hover:not(:disabled) {
      background: #333;
      transform: scale(1.02);
    }

    .btn-add:disabled {
      background: #ddd;
      color: #888;
      cursor: not-allowed;
    }

    .empty-state {
      text-align: center;
      padding: 60px 24px;
      background: #fff;
      border-radius: 22px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
    }

    .empty-state i {
      font-size: 48px;
      color: #ccc;
      margin-bottom: 16px;
    }

    .cart-float {
      position: fixed;
      bottom: 28px;
      right: 28px;
      background: #000;
      color: #fff;
      border-radius: 999px;
      padding: 14px 26px;
      font-size: 14px;
      font-weight: 600;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 10px;
      box-shadow: 0 10px 32px rgba(0, 0, 0, 0.25);
      z-index: 999;
      transition: 0.2s;
    }

    .cart-float:hover {
      background: #222;
      color: #fff;
      transform: translateY(-3px);
    }

    .cart-float .badge {
      background: #e8f7d0;
      color: #000;
      border-radius: 999px;
      min-width: 22px;
      padding: 2px 8px;
      font-size: 12px;
      text-align: center;
    }

    .toast-msg {
      position: fixed;
      bottom: 96px;
      right: 28px;
      background: #111;
      color: #fff;
      padding: 14px 24px;
      border-radius: 14px;
      font-size: 14px;
      font-weight: 500;
      opacity: 0;
      transform: translateY(12px);
      transition: 0.3s;
      z-index: 9999;
      pointer-events: none;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
    }

    .toast-msg.show {
      opacity: 1;
      transform: translateY(0);
    }

    .toast-msg.error {
      background: #8b0000;
    }

    @media (max-width: 768px) {
      .toolbar-inner {
        flex-direction: column;
        align-items: stretch;
      }

      .filter-bar {
        justify-content: center;
      }

      .product-count {
        text-align: center;
      }

      .product-footer {
        flex-direction: column;
        align-items: stretch;
      }

      .btn-add {
        justify-content: center;
        width: 100%;
      }
    }
  </style>
</head>

<body>
  <?php include 'partials/navbar.php'; ?>

  <header class="shop-hero">
    <h1>Our Products</h1>
    <p>Pick your scent. Freshen your drive.</p>
  </header>

  <div class="shop-toolbar">
    <div class="toolbar-inner">
      <nav class="filter-bar" aria-label="Product filters">
        <a href="products.php" class="filter-pill <?php echo $filter === 'all' ? 'active' : ''; ?>">All</a>
        <a href="products.php?type=gel" class="filter-pill <?php echo $filter === 'gel' ? 'active' : ''; ?>">Blank Gel</a>
        <a href="products.php?type=liquid" class="filter-pill <?php echo $filter === 'liquid' ? 'active' : ''; ?>">Blank Liquid</a>
      </nav>
      <p class="product-count mb-0">
        <strong><?php echo (int) $totalShown; ?></strong>
        <?php echo $totalShown === 1 ? 'product' : 'products'; ?>
        <?php if ($filter !== 'all'): ?>
          · <?php echo $filter === 'gel' ? 'Blank Gel' : 'Blank Liquid'; ?>
        <?php endif; ?>
      </p>
    </div>
  </div>

  <main class="shop-main">
    <?php
    function renderProducts(array $arr): void
    {
      ?>
      <div class="row g-4">
        <?php foreach ($arr as $p):
          $inStock = (int) $p['stock'] > 0;
          $imgSrc = product_image_src($p['image']);
          $recommended = is_recommended_product($p);
          $typeLabel = $p['type'] === 'gel' ? 'Blank Gel' : 'Blank Liquid';
          ?>
          <div class="col-lg-4 col-md-6">
            <article class="product-card<?php echo $recommended ? ' recommended' : ''; ?>">
              <div class="product-img-wrap">
                <span class="type-badge type-<?php echo htmlspecialchars($p['type']); ?>"><?php echo ucfirst($p['type']); ?></span>
                <?php if ($recommended): ?>
                  <span class="recommended-badge"><i class="bi bi-star-fill"></i> Recommended</span>
                <?php endif; ?>
                <img src="<?php echo htmlspecialchars($imgSrc); ?>" alt="<?php echo htmlspecialchars($p['variant']); ?>">
              </div>
              <div class="product-body">
                <div class="product-category"><?php echo htmlspecialchars($typeLabel); ?></div>
                <h2 class="product-title"><?php echo htmlspecialchars($p['variant']); ?></h2>
                <?php if ($recommended): ?>
                  <p class="recommended-note">
                    <i class="bi bi-star-fill"></i>
                    <span>Our team's top pick — most customers love this scent.</span>
                  </p>
                <?php endif; ?>
                <?php if (!empty($p['description'])): ?>
                  <p class="product-desc"><?php echo htmlspecialchars($p['description']); ?></p>
                <?php endif; ?>
                <div class="product-meta">
                  <span class="stock-pill <?php echo $inStock ? '' : 'out'; ?>">
                    <i class="bi bi-<?php echo $inStock ? 'check-circle' : 'x-circle'; ?>"></i>
                    <?php echo $inStock ? 'In stock (' . (int) $p['stock'] . ')' : 'Out of stock'; ?>
                  </span>
                </div>
                <div class="product-footer">
                  <div class="price">
                    RM <?php echo number_format((float) $p['price'], 2); ?>
                  </div>
                  <button type="button" class="btn-add" data-product-id="<?php echo (int) $p['id']; ?>"
                    data-variant="<?php echo htmlspecialchars($p['variant'], ENT_QUOTES); ?>" <?php echo $inStock ? '' : 'disabled'; ?>>
                    <i class="bi bi-bag-plus"></i> Add to cart
                  </button>
                </div>
              </div>
            </article>
          </div>
        <?php endforeach; ?>
      </div>
      <?php
    }
    ?>

    <?php if (empty($gels) && empty($liquids)): ?>
      <div class="empty-state">
        <i class="bi bi-inbox"></i>
        <h5>No products found</h5>
        <p class="text-muted mb-3">Try a different filter or check back later.</p>
        <a href="products.php" class="filter-pill active">View all products</a>
      </div>
    <?php endif; ?>

    <?php if (!empty($gels) && ($filter === 'all' || $filter === 'gel')): ?>
      <div class="section-label">🫙 Blank Gel</div>
      <?php renderProducts($gels); ?>
    <?php endif; ?>

    <?php if (!empty($liquids) && ($filter === 'all' || $filter === 'liquid')): ?>
      <div class="section-label">💧 Blank Liquid</div>
      <?php renderProducts($liquids); ?>
    <?php endif; ?>
  </main>

  <a href="cart.php" class="cart-float" aria-label="View cart">
    <i class="bi bi-cart3"></i> Cart
    <span class="badge" id="cartBadge"><?php echo $cartCount; ?></span>
  </a>
  <div class="toast-msg" id="toastMsg" role="status" aria-live="polite"></div>

  <script>
    document.querySelectorAll('.btn-add[data-product-id]').forEach(btn => {
      btn.addEventListener('click', () => {
        const productId = btn.dataset.productId;
        const variant = btn.dataset.variant;
        btn.disabled = true;
        fetch('cart_action.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `action=add&product_id=${productId}`
        })
          .then(r => r.json())
          .then(data => {
            if (data.error) {
              showToast(data.error, true);
              return;
            }
            if (typeof updateNavCartCount === 'function') updateNavCartCount(data.cart_count);
            const badge = document.getElementById('cartBadge');
            if (badge) badge.textContent = data.cart_count;
            showToast(`${variant} added to cart`);
          })
          .catch(() => showToast('Could not add to cart', true))
          .finally(() => {
            if (!btn.hasAttribute('data-out-of-stock')) {
              btn.disabled = false;
            }
          });
      });
    });

    document.querySelectorAll('.btn-add:disabled').forEach(btn => {
      btn.setAttribute('data-out-of-stock', '1');
    });

    function showToast(msg, isError = false) {
      const t = document.getElementById('toastMsg');
      t.textContent = msg;
      t.classList.toggle('error', isError);
      t.classList.add('show');
      setTimeout(() => {
        t.classList.remove('show');
        t.classList.remove('error');
      }, 2500);
    }
  </script>

  <?php include 'partials/footer.php'; ?>
