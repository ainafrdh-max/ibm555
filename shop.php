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
    body {
      background: #f9f9f9;
    }

    .page-hero {
      background: linear-gradient(135deg, #e8f7d0, #f6ffe9);
      padding: 70px 20px 50px;
      text-align: center;
    }

    .page-hero h1 {
      font-size: 52px;
      font-weight: 800;
      margin-bottom: 10px;
    }

    .page-hero p {
      color: #555;
      font-size: 16px;
    }

    /* Filter */
    .filter-bar {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
      justify-content: center;
      padding: 30px 0;
    }

    .filter-pill {
      border: 1.5px solid #000;
      background: #fff;
      border-radius: 999px;
      padding: 8px 22px;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      transition: 0.25s ease;
    }

    .filter-pill:hover,
    .filter-pill.active {
      background: #000;
      color: #fff;
    }

    /* Section */
    .shop-section {
      padding: 20px 0 80px;
    }

    .product-type-title {
      font-size: 13px;
      letter-spacing: 2px;
      font-weight: 700;
      color: #888;
      text-transform: uppercase;
      display: flex;
      align-items: center;
      gap: 12px;
      margin: 45px 0 25px;
    }

    .product-type-title::after {
      content: '';
      flex: 1;
      height: 1px;
      background: #ddd;
    }

    /* Card */
    .product-card {
      border: none;
      border-radius: 22px;
      overflow: hidden;
      background: #fff;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
      transition: 0.3s ease;
      height: 100%;
    }

    .product-card:hover {
      transform: translateY(-7px);
      box-shadow: 0 16px 40px rgba(0, 0, 0, 0.12);
    }

    .product-img-wrap {
      background: #eef9de;
      height: 250px;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
      padding: 20px;
    }

    .product-img-wrap img {
      max-height: 190px;
      width: auto;
      object-fit: contain;
      transition: 0.3s;
    }

    .product-card:hover img {
      transform: scale(1.06);
    }

    .product-type-badge {
      position: absolute;
      top: 14px;
      left: 14px;
      background: #000;
      color: #fff;
      font-size: 10px;
      padding: 5px 10px;
      border-radius: 999px;
      letter-spacing: 1px;
    }

    .product-body {
      padding: 20px;
    }

    .product-body h6 {
      font-size: 17px;
      font-weight: 700;
      margin-bottom: 6px;
    }

    .variant-note {
      color: #777;
      font-size: 14px;
      margin-bottom: 12px;
    }

    .strength-tag {
      display: inline-block;
      font-size: 11px;
      font-weight: 600;
      padding: 5px 12px;
      border-radius: 999px;
      background: #f3f3f3;
      margin-right: 6px;
      margin-top: 5px;
    }

    /* CTA */
    .enquire-section {
      background: linear-gradient(135deg, #e8f7d0, #f7ffe8);
      padding: 70px 20px;
      text-align: center;
    }

    .enquire-section h3 {
      font-size: 32px;
      font-weight: 800;
    }

    .enquire-section p {
      color: #555;
      margin: 12px 0 25px;
    }

    .btn-enquire,
    .btn-enquire-outline {
      padding: 13px 30px;
      border-radius: 999px;
      font-weight: 600;
      text-decoration: none;
      margin: 5px;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: 0.25s;
    }

    .btn-enquire {
      background: #000;
      color: #fff;
    }

    .btn-enquire:hover {
      background: #222;
      color: #fff;
    }

    .btn-enquire-outline {
      border: 1.5px solid #000;
      color: #000;
      background: transparent;
    }

    .btn-enquire-outline:hover {
      background: #000;
      color: #fff;
    }

    @media(max-width:768px) {
      .page-hero h1 {
        font-size: 38px;
      }

      .product-img-wrap {
        height: 220px;
      }
    }
  </style>
</head>

<body>

  <?php include 'partials/navbar.php'; ?>

  <!-- Hero -->
  <div class="page-hero">
    <h1>Our Products</h1>
    <p>Simple scents for your most personal space.</p>
  </div>

  <!-- Filter -->
  <div class="container">
    <div class="filter-bar">
      <button class="filter-pill active" onclick="filterProducts('all', this)">All</button>
      <button class="filter-pill" onclick="filterProducts('gel', this)">Blank Gel</button>
      <button class="filter-pill" onclick="filterProducts('liquid', this)">Blank Liquid</button>
    </div>
  </div>

  <!-- Products -->
  <section class="shop-section">
    <div class="container">

      <!-- GEL -->
      <div class="category-group" data-group="gel">
        <p class="product-type-title">🫙 Blank Gel</p>

        <div class="row g-4">
          <div class="col-md-4 col-sm-6 product-item" data-category="gel">
            <div class="product-card">
              <div class="product-img-wrap">
                <span class="product-type-badge">Blank Gel</span>
                <img src="img/blank-black-rose.png">
              </div>
              <div class="product-body">
                <h6>Sweet Nectar</h6>
                <div class="variant-note">Black Rose</div>
                <span class="strength-tag">🌸 Soft</span>
                <span class="strength-tag">🔥 Strong</span>
              </div>
            </div>
          </div>

          <div class="col-md-4 col-sm-6 product-item" data-category="gel">
            <div class="product-card">
              <div class="product-img-wrap">
                <span class="product-type-badge">Blank Gel</span>
                <img src="img/blank-black.png">
              </div>
              <div class="product-body">
                <h6>Dreamy Melon</h6>
                <div class="variant-note">Black</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- LIQUID -->
      <div class="category-group" data-group="liquid">
        <p class="product-type-title">💧 Blank Liquid</p>

        <div class="row g-4">
          <div class="col-md-4 col-sm-6 product-item" data-category="liquid">
            <div class="product-card">
              <div class="product-img-wrap">
                <span class="product-type-badge">Blank Liquid</span>
                <img src="img/blank-lemon.png">
              </div>
              <div class="product-body">
                <h6>Blank Liquid</h6>
                <div class="variant-note">Lemon</div>
              </div>
            </div>
          </div>

          <div class="col-md-4 col-sm-6 product-item" data-category="liquid">
            <div class="product-card">
              <div class="product-img-wrap">
                <span class="product-type-badge">Blank Liquid</span>
                <img src="img/blank-rose.png">
              </div>
              <div class="product-body">
                <h6>Blank Liquid</h6>
                <div class="variant-note">Rose</div>
              </div>
            </div>
          </div>

          <div class="col-md-4 col-sm-6 product-item" data-category="liquid">
            <div class="product-card">
              <div class="product-img-wrap">
                <span class="product-type-badge">Blank Liquid</span>
                <img src="img/blank-summer.png">
              </div>
              <div class="product-body">
                <h6>Blank Liquid</h6>
                <div class="variant-note">Summer</div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- CTA -->
  <div class="enquire-section">
    <h3>Interested in our products?</h3>
    <p>Contact us to place an order or find your nearest agent.</p>

    <a href="https://wa.me/601155098234" class="btn-enquire" target="_blank">
      <i class="bi bi-whatsapp"></i> WhatsApp Us
    </a>

    <a href="mailto:blankcarfragrance@gmail.com" class="btn-enquire-outline">
      <i class="bi bi-envelope-fill"></i> Email Us
    </a>
  </div>

  <?php include 'partials/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    function filterProducts(category, btn) {
      document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('active'));
      btn.classList.add('active');

      document.querySelectorAll('.category-group').forEach(group => {
        if (category === 'all') {
          group.style.display = 'block';
        } else {
          group.style.display = group.dataset.group === category ? 'block' : 'none';
        }
      });
    }
  </script>

</body>

</html>