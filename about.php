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
      background:
        linear-gradient(135deg, rgba(232, 247, 208, 0.82), rgba(246, 255, 233, 0.88)),
        url('img/allBlank.png') center / cover no-repeat;
      min-height: 380px;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 80px 20px 60px;
    }

    .about-hero h1 {
      font-size: clamp(38px, 6vw, 56px);
      letter-spacing: -1.5px;
      font-weight: 800;
    }

    .about-hero p {
      font-size: 17px;
      color: #555;
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
      height: auto;
      max-height: 600px;
      object-fit: contain;
      border-radius: 24px;
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
      <p>Perfuming your most personal space — one car at a time.</p>
    </div>
  </div>

  <!-- Story -->
  <section class="story-section">
    <div class="container">
      <div class="row align-items-center g-5">
        <div class="col-md-6">
          <img src="img/blank-founder.png" alt="Blank Resources Founder">
        </div>
        <div class="col-md-6 story-text">
          <h2>Where It All Began</h2>
          <p>Cars are an incredibly personal space. In this crowded, cluttered world, a car is a mobile chamber that
            offers the rare luxury of privacy and intimacy. It's where you begin your first step towards a road trip;
            where you steal kisses, hold hands and whisk a loved one away to a surprise getaway; your last moments of
            freedom before a long work day begins — and a welcome respite when it ends.</p>
          <p>In 2021, we established Blank Resources to help people create the personal space they deserve. Focusing on
            car fragrances upon our launch, we released two products: Blank Gel & Blank Liquid. In just one short year,
            they sold over <strong>625,000 units</strong> across Southeast Asia — endorsed by familiar names like Syahmi
            Sazli and Emma Maembong, and winning <strong>The Natural Health Readers' Choice Awards 2021</strong>.</p>
          <p>Now a company with over <strong>1,000 agents and dropshippers</strong> in Malaysia, Brunei & Singapore, we
            are ready to expand our range of products — starting with a bespoke line of home fragrances. We continue to
            be humbled by the amount of love Blank receives, and look forward to perfuming all your sweetest memories.
          </p>
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
            <span class="value-icon">🚗</span>
            <h5>Your Personal Space</h5>
            <p>We believe your car deserves to feel as personal and intimate as your home.</p>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="value-item">
            <span class="value-icon">🏆</span>
            <h5>Award-Winning</h5>
            <p>Winners of The Natural Health Readers' Choice Awards 2021 — a mark of quality.</p>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="value-item">
            <span class="value-icon">🌏</span>
            <h5>Southeast Asia Reach</h5>
            <p>Over 625,000 units sold across the region in our very first year.</p>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="value-item">
            <span class="value-icon">🤝</span>
            <h5>1,000+ Agents</h5>
            <p>A thriving network of agents and dropshippers across Malaysia, Brunei & Singapore.</p>
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
            <div class="number">625K+</div>
            <div class="label">UNITS SOLD</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="stat-item">
            <div class="number">1,000+</div>
            <div class="label">AGENTS & DROPSHIPPERS</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="stat-item">
            <div class="number">3</div>
            <div class="label">COUNTRIES</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="stat-item">
            <div class="number">2021</div>
            <div class="label">AWARD WINNER</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <?php include 'partials/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>