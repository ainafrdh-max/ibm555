<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Blank Perfume</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="styles/homestyle.css">
  <style>
    .product-preview-section {
      background: #fff;
    }

    .product-preview-section h2 {
      font-size: clamp(28px, 4vw, 40px);
      font-weight: 700;
      letter-spacing: -0.5px;
      margin-bottom: 14px;
    }

    .product-preview-section .lead {
      color: #555;
      font-size: 16px;
      line-height: 1.6;
      margin-bottom: 24px;
    }

    .video-preview-wrap {
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 16px 48px rgba(0, 0, 0, 0.12);
      background: #000;
      line-height: 0;
    }

    .video-preview-wrap video {
      width: 100%;
      display: block;
      aspect-ratio: 16 / 9;
      object-fit: cover;
      background: #111;
    }
  </style>
</head>

<body> <!-- NAVBAR -->
  <?php include 'partials/navbar.php'; ?>
  <!-- HERO SLIDESHOW -->
  <div id="heroCarousel" class="carousel slide hero" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active"> <img src="img/blank-rose.png" class="hero-img">
        <div class="carousel-caption">
          <h1>Blank Perfume</h1>
          <p>Minimal. Clean. Your signature scent.</p> <a href="login.php" class="btn btn-custom mt-3">Shop Now</a>
        </div>
      </div>
      <div class="carousel-item"> <img src="img/blank-black.png" class="hero-img">
        <div class="carousel-caption">
          <h1>Luxury in Simplicity</h1>
          <p>A fragrance that defines elegance.</p> <a href="login.php" class="btn btn-custom mt-3">Shop Now</a>
        </div>
      </div>
      <div class="carousel-item"> <img src="img/blank-lemon.png" class="hero-img">
        <div class="carousel-caption">
          <h1>Your Identity</h1>
          <p>Smell like confidence, wear your presence.</p> <a href="login.php" class="btn btn-custom mt-3">Shop Now</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Product preview video -->
  <section class="product-preview-section section-white">
    <div class="container">
      <div class="row align-items-center g-5">
        <div class="col-lg-5 text-center text-lg-start">
          <h2>See Blank in action</h2>
          <p class="lead">Watch how our car fragrances look and work in your space — minimal design, lasting freshness.</p>
          <a href="login.php" class="btn btn-custom">Shop Now</a>
        </div>
        <div class="col-lg-7">
          <div class="video-preview-wrap">
            <video src="video/BlankVideo.mp4" autoplay muted loop playsinline preload="auto" poster="img/allBlank.png"
              aria-label="Blank Perfume product preview">
              Your browser does not support video playback.
            </video>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ABOUT -->
  <section class="section-white text-center">
    <div class="container">
      <h2>Our Philosophy</h2>
      <p class="mt-3 col-md-6 mx-auto"> We focus on simplicity. Every fragrance is designed to feel effortless yet
        unforgettable. </p>
    </div>
  </section> <!-- FEATURES -->
  <section class="section-grey text-center">
    <div class="container">
      <h2>Why Choose Us</h2>
      <div class="row mt-5 g-4">
        <div class="col-md-4">
          <div class="card card-custom">
            <h5>Minimal</h5>
            <p>Pure and simple design.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card card-custom">
            <h5>Premium</h5>
            <p>High-quality ingredients.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card card-custom">
            <h5>Long-lasting</h5>
            <p>Stay fresh all day.</p>
          </div>
        </div>
      </div>
    </div>
  </section> <!-- FOOTER -->
  <?php include 'partials/footer.php'; ?>

</html>