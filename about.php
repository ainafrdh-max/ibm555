<?php include "config.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us – Blank Perfume</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="styles/homestyle.css">
  <style>
    /* Page Hero */
    .about-hero {
      background: #e8f7d0;
      min-height: 380px;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 80px 20px 60px;
    }
    .about-hero h1 {
      font-size: 56px;
      letter-spacing: -1.5px;
      font-weight: 700;
    }
    .about-hero p {
      font-size: 17px;
      opacity: .65;
      max-width: 500px;
      margin: 14px auto 0;
    }

    /* Story section */
    .story-section {
      padding: 90px 0;
      background: #fff;
    }
    .story-section img {
      width: 100%;
      height: 420px;
      object-fit: cover;
      border-radius: 24px;
      filter: brightness(0.9);
    }
    .story-text h2 {
      font-size: 38px;
      font-weight: 700;
      letter-spacing: -1px;
      margin-bottom: 20px;
    }
    .story-text p {
      font-size: 15px;
      line-height: 1.85;
      color: #444;
      margin-bottom: 14px;
    }

    /* Values */
    .values-section {
      background: #e8f7d0;
      padding: 90px 0;
    }
    .values-section h2 {
      font-size: 38px;
      font-weight: 700;
      letter-spacing: -1px;
      margin-bottom: 50px;
    }
    .value-item {
      text-align: center;
      padding: 20px;
    }
    .value-icon {
      font-size: 36px;
      margin-bottom: 16px;
      display: block;
    }
    .value-item h5 {
      font-weight: 600;
      margin-bottom: 8px;
    }
    .value-item p {
      font-size: 14px;
      color: #555;
    }

    /* Team */
    .team-section {
      background: #fff;
      padding: 90px 0;
    }
    .team-section h2 {
      font-size: 38px;
      font-weight: 700;
      letter-spacing: -1px;
      margin-bottom: 50px;
    }
    .team-card {
      text-align: center;
    }
    .team-avatar {
      width: 110px;
      height: 110px;
      border-radius: 50%;
      background: #e8f7d0;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 16px;
      font-size: 38px;
    }
    .team-card h6 {
      font-weight: 600;
      font-size: 16px;
      margin-bottom: 4px;
    }
    .team-card p {
      font-size: 13px;
      color: #888;
      letter-spacing: .5px;
    }

    /* Stats banner */
    .stats-section {
      background: #000;
      color: #fff;
      padding: 70px 0;
    }
    .stat-item {
      text-align: center;
    }
    .stat-item .number {
      font-size: 48px;
      font-weight: 700;
      letter-spacing: -2px;
    }
    .stat-item .label {
      font-size: 13px;
      letter-spacing: 1px;
      opacity: .6;
      margin-top: 4px;
    }
  </style>
</head>
<body>
  <?php include 'partials/navbar.php'; ?>

  <!-- Hero -->
  <div class="about-hero">
    <div>
      <h1>Our Story</h1>
      <p>Born from a love of simplicity. Built for those who wear their identity quietly.</p>
    </div>
  </div>

  <!-- Story -->
  <section class="story-section">
    <div class="container">
      <div class="row align-items-center g-5">
        <div class="col-md-6">
          <img src="img/blank-rose.png" alt="Our story">
        </div>
        <div class="col-md-6 story-text">
          <h2>Where It All Began</h2>
          <p>Blank Perfume was founded in 2020 in Kota Bharu, with one simple belief — that a great scent should feel like a second skin, not a statement piece.</p>
          <p>We were tired of loud fragrances competing for attention. So we stripped everything back. No unnecessary notes. No over-designed bottles. Just the essence of who you are.</p>
          <p>Every bottle in our collection is the result of months of careful development, using only ethically sourced ingredients from trusted suppliers across France, UAE, and Malaysia.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Values -->
  <section class="values-section text-center">
    <div class="container">
      <h2>What We Stand For</h2>
      <div class="row g-4">
        <div class="col-md-3 col-6">
          <div class="value-item">
            <span class="value-icon">🌿</span>
            <h5>Ethical Sourcing</h5>
            <p>All ingredients are sustainably and ethically sourced from responsible suppliers.</p>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="value-item">
            <span class="value-icon">🧪</span>
            <h5>Clean Formula</h5>
            <p>No parabens, no phthalates. Just pure, clean fragrance ingredients.</p>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="value-item">
            <span class="value-icon">♻️</span>
            <h5>Eco Packaging</h5>
            <p>Our packaging is made from recycled materials and is fully recyclable.</p>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="value-item">
            <span class="value-icon">🤝</span>
            <h5>Community First</h5>
            <p>10% of every sale goes towards supporting local artisan communities.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Stats -->
  <section class="stats-section">
    <div class="container">
      <div class="row g-4">
        <div class="col-6 col-md-3">
          <div class="stat-item">
            <div class="number">6+</div>
            <div class="label">FRAGRANCES</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="stat-item">
            <div class="number">5K+</div>
            <div class="label">HAPPY CUSTOMERS</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="stat-item">
            <div class="number">4</div>
            <div class="label">YEARS IN BUSINESS</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="stat-item">
            <div class="number">100%</div>
            <div class="label">CRUELTY FREE</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Team -->
  <section class="team-section text-center">
    <div class="container">
      <h2>Meet the Team</h2>
      <div class="row g-4 justify-content-center">
        <div class="col-md-3 col-6">
          <div class="team-card">
            <div class="team-avatar">👩</div>
            <h6>Aini Rahayu</h6>
            <p>FOUNDER & CEO</p>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="team-card">
            <div class="team-avatar">👨</div>
            <h6>Haziq Izzat</h6>
            <p>HEAD PERFUMER</p>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="team-card">
            <div class="team-avatar">👩</div>
            <h6>Nurul Farhana</h6>
            <p>CREATIVE DIRECTOR</p>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="team-card">
            <div class="team-avatar">👨</div>
            <h6>Arif Syazwan</h6>
            <p>MARKETING LEAD</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <?php include 'partials/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
