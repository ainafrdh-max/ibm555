<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Blank Perfume</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="styles/homestyle.css">
</head>

<body> <!-- NAVBAR -->
  <?php include 'partials/navbar.php'; ?>
  <!-- HERO SLIDESHOW -->
  <div id="heroCarousel" class="carousel slide hero" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active"> <img src="img/blank-rose.png" class="hero-img">
        <div class="carousel-caption">
          <h1>Blank Perfume</h1>
          <p>Minimal. Clean. Your signature scent.</p> <button class="btn btn-custom mt-3">Shop Now</button>
        </div>
      </div>
      <div class="carousel-item"> <img src="img/blank-black.png" class="hero-img">
        <div class="carousel-caption">
          <h1>Luxury in Simplicity</h1>
          <p>A fragrance that defines elegance.</p> <button class="btn btn-custom mt-3">Shop Now</button>
        </div>
      </div>
      <div class="carousel-item"> <img src="img/blank-lemon.png" class="hero-img">
        <div class="carousel-caption">
          <h1>Your Identity</h1>
          <p>Smell like confidence, wear your presence.</p> <button class="btn btn-custom mt-3">Shop Now</button>
        </div>
      </div>
    </div>
  </div> <!-- ABOUT -->
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