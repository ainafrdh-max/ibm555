<?php include "config.php"; ?>

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
    .page-hero {
      background: #e8f7d0;
      padding: 60px 0 40px;
      text-align: center;
    }
    .page-hero h1 {
      font-size: 52px;
      letter-spacing: -1px;
    }
    .page-hero p {
      font-size: 16px;
      opacity: 0.65;
    }

    /* Filter pills */
    .filter-bar {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      justify-content: center;
      padding: 30px 0 10px;
    }
    .filter-pill {
      border: 1.5px solid #000;
      background: transparent;
      border-radius: 999px;
      padding: 6px 20px;
      font-size: 13px;
      letter-spacing: 1px;
      cursor: pointer;
      transition: .2s;
    }
    .filter-pill:hover, .filter-pill.active {
      background: #000;
      color: #fff;
    }

    /* Product grid */
    .shop-section {
      padding: 60px 0 90px;
      background: #fff;
    }
    .product-card {
      border: none;
      border-radius: 20px;
      overflow: hidden;
      transition: transform .3s, box-shadow .3s;
      background: #fff;
      box-shadow: 0 2px 16px rgba(0,0,0,0.06);
    }
    .product-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 40px rgba(0,0,0,0.12);
    }
    .product-img-wrap {
      background: #e8f7d0;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 260px;
      overflow: hidden;
      position: relative;
    }
    .product-img-wrap img {
      height: 200px;
      object-fit: contain;
      transition: transform .4s;
    }
    .product-card:hover .product-img-wrap img {
      transform: scale(1.06);
    }
    .badge-new {
      position: absolute;
      top: 14px;
      left: 14px;
      background: #000;
      color: #fff;
      font-size: 11px;
      letter-spacing: 1px;
      padding: 3px 10px;
      border-radius: 999px;
    }
    .product-body {
      padding: 20px;
    }
    .product-body h6 {
      font-weight: 600;
      font-size: 15px;
      margin-bottom: 4px;
    }
    .product-body .scent-note {
      font-size: 12px;
      color: #777;
      letter-spacing: .5px;
      margin-bottom: 12px;
    }
    .product-body .price {
      font-weight: 700;
      font-size: 16px;
    }
    .btn-add {
      background: #000;
      color: #fff;
      border: none;
      border-radius: 999px;
      padding: 8px 20px;
      font-size: 13px;
      transition: .2s;
    }
    .btn-add:hover {
      background: #333;
      color: #fff;
    }

    /* Toast */
    .cart-toast {
      position: fixed;
      bottom: 30px;
      right: 30px;
      background: #000;
      color: #fff;
      padding: 12px 24px;
      border-radius: 12px;
      font-size: 14px;
      opacity: 0;
      transform: translateY(20px);
      transition: .3s;
      z-index: 9999;
      pointer-events: none;
    }
    .cart-toast.show {
      opacity: 1;
      transform: translateY(0);
    }
  </style>
</head>
<body>
  <?php include 'partials/navbar.php'; ?>

  <!-- Hero -->
  <div class="page-hero">
    <h1>Our Collection</h1>
    <p>Discover your signature scent</p>
  </div>

  <!-- Filters -->
  <div class="filter-bar container">
    <button class="filter-pill active" onclick="filterProducts('all', this)">All</button>
    <button class="filter-pill" onclick="filterProducts('floral', this)">Floral</button>
    <button class="filter-pill" onclick="filterProducts('woody', this)">Woody</button>
    <button class="filter-pill" onclick="filterProducts('fresh', this)">Fresh</button>
    <button class="filter-pill" onclick="filterProducts('oriental', this)">Oriental</button>
  </div>

  <!-- Products -->
  <section class="shop-section">
    <div class="container">
      <div class="row g-4" id="product-grid">

        <!-- Product 1 -->
        <div class="col-md-4 col-sm-6 product-item" data-category="floral">
          <div class="product-card">
            <div class="product-img-wrap">
              <span class="badge-new">NEW</span>
              <img src="img/blank-rose.png" alt="Blank Rose">
            </div>
            <div class="product-body">
              <h6>Blank Rose</h6>
              <div class="scent-note">Rose · Peony · Musk</div>
              <div class="d-flex justify-content-between align-items-center">
                <span class="price">RM 189</span>
                <button class="btn-add" onclick="addToCart('Blank Rose')">Add to Cart</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Product 2 -->
        <div class="col-md-4 col-sm-6 product-item" data-category="woody">
          <div class="product-card">
            <div class="product-img-wrap">
              <img src="img/blank-black.png" alt="Blank Noir">
            </div>
            <div class="product-body">
              <h6>Blank Noir</h6>
              <div class="scent-note">Oud · Cedarwood · Amber</div>
              <div class="d-flex justify-content-between align-items-center">
                <span class="price">RM 219</span>
                <button class="btn-add" onclick="addToCart('Blank Noir')">Add to Cart</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Product 3 -->
        <div class="col-md-4 col-sm-6 product-item" data-category="fresh">
          <div class="product-card">
            <div class="product-img-wrap">
              <span class="badge-new">NEW</span>
              <img src="img/blank-lemon.png" alt="Blank Citrus">
            </div>
            <div class="product-body">
              <h6>Blank Citrus</h6>
              <div class="scent-note">Lemon · Bergamot · White Tea</div>
              <div class="d-flex justify-content-between align-items-center">
                <span class="price">RM 169</span>
                <button class="btn-add" onclick="addToCart('Blank Citrus')">Add to Cart</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Product 4 -->
        <div class="col-md-4 col-sm-6 product-item" data-category="oriental">
          <div class="product-card">
            <div class="product-img-wrap">
              <img src="img/blank-rose.png" alt="Blank Velvet">
            </div>
            <div class="product-body">
              <h6>Blank Velvet</h6>
              <div class="scent-note">Vanilla · Sandalwood · Jasmine</div>
              <div class="d-flex justify-content-between align-items-center">
                <span class="price">RM 199</span>
                <button class="btn-add" onclick="addToCart('Blank Velvet')">Add to Cart</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Product 5 -->
        <div class="col-md-4 col-sm-6 product-item" data-category="woody">
          <div class="product-card">
            <div class="product-img-wrap">
              <img src="img/blank-black.png" alt="Blank Smoke">
            </div>
            <div class="product-body">
              <h6>Blank Smoke</h6>
              <div class="scent-note">Vetiver · Birch · Black Pepper</div>
              <div class="d-flex justify-content-between align-items-center">
                <span class="price">RM 229</span>
                <button class="btn-add" onclick="addToCart('Blank Smoke')">Add to Cart</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Product 6 -->
        <div class="col-md-4 col-sm-6 product-item" data-category="floral">
          <div class="product-card">
            <div class="product-img-wrap">
              <img src="img/blank-lemon.png" alt="Blank Bloom">
            </div>
            <div class="product-body">
              <h6>Blank Bloom</h6>
              <div class="scent-note">Lily · Freesia · Green Leaves</div>
              <div class="d-flex justify-content-between align-items-center">
                <span class="price">RM 179</span>
                <button class="btn-add" onclick="addToCart('Blank Bloom')">Add to Cart</button>
              </div>
            </div>
          </div>
        </div>

      </div><!-- /row -->
    </div>
  </section>

  <!-- Toast notification -->
  <div class="cart-toast" id="cartToast">✓ Added to cart</div>

  <?php include 'partials/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function filterProducts(category, btn) {
      document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('active'));
      btn.classList.add('active');
      document.querySelectorAll('.product-item').forEach(item => {
        item.style.display = (category === 'all' || item.dataset.category === category) ? '' : 'none';
      });
    }

    function addToCart(name) {
      const toast = document.getElementById('cartToast');
      toast.textContent = `✓ ${name} added to cart`;
      toast.classList.add('show');
      setTimeout(() => toast.classList.remove('show'), 2500);
    }
  </script>
</body>
</html>
