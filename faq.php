<?php include "config.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FAQ – Blank Perfume</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="styles/homestyle.css">
  <style>
    .faq-hero {
      background: #e8f7d0;
      padding: 80px 20px 60px;
      text-align: center;
    }

    .faq-hero h1 {
      font-size: 52px;
      font-weight: 700;
      letter-spacing: -1px;
    }

    .faq-hero p {
      font-size: 16px;
      opacity: .65;
      max-width: 480px;
      margin: 12px auto 0;
    }

    .faq-search-wrap {
      background: #fff;
      padding: 36px 0 0;
    }

    .faq-search {
      max-width: 500px;
      margin: 0 auto;
      position: relative;
    }

    .faq-search input {
      width: 100%;
      border: 1.5px solid #ddd;
      border-radius: 999px;
      padding: 13px 50px 13px 22px;
      font-size: 14px;
      outline: none;
      transition: .2s;
    }

    .faq-search input:focus {
      border-color: #000;
    }

    .faq-search i {
      position: absolute;
      right: 18px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 18px;
      color: #aaa;
    }

    .faq-tabs {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      justify-content: center;
      padding: 28px 0 10px;
    }

    .faq-tab {
      border: 1.5px solid #000;
      background: transparent;
      border-radius: 999px;
      padding: 6px 20px;
      font-size: 13px;
      letter-spacing: 1px;
      cursor: pointer;
      transition: .2s;
    }

    .faq-tab:hover,
    .faq-tab.active {
      background: #000;
      color: #fff;
    }

    .faq-section {
      background: #fff;
      padding: 40px 0 90px;
    }

    .faq-group {
      max-width: 760px;
      margin: 0 auto;
    }

    .faq-group-title {
      font-size: 11px;
      letter-spacing: 2.5px;
      font-weight: 700;
      color: #888;
      margin: 40px 0 12px;
      text-transform: uppercase;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .faq-group-title::after {
      content: '';
      flex: 1;
      height: 1px;
      background: #eee;
    }

    .accordion-item {
      border: 1.5px solid #eee !important;
      border-radius: 14px !important;
      margin-bottom: 10px;
      overflow: hidden;
    }

    .accordion-button {
      font-weight: 600;
      font-size: 15px;
      background: #fff !important;
      color: #000 !important;
      box-shadow: none !important;
      padding: 20px 24px;
    }

    .accordion-button:not(.collapsed) {
      background: #f9fef4 !important;
    }

    .accordion-body {
      font-size: 14px;
      color: #555;
      line-height: 1.85;
      padding: 6px 24px 22px;
      background: #f9fef4;
    }

    .accordion-body ol,
    .accordion-body ul {
      padding-left: 20px;
      margin: 8px 0 0;
    }

    .accordion-body li {
      margin-bottom: 4px;
    }

    .accordion-body a {
      color: #000;
      font-weight: 600;
    }

    .faq-item.hidden {
      display: none;
    }

    .contact-cta {
      background: #e8f7d0;
      padding: 70px 20px;
      text-align: center;
    }

    .contact-cta h3 {
      font-size: 30px;
      font-weight: 700;
      letter-spacing: -.5px;
      margin-bottom: 10px;
    }

    .contact-cta p {
      font-size: 15px;
      color: #555;
      margin-bottom: 4px;
    }

    .contact-links {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 14px;
      margin-top: 24px;
    }

    .btn-contact {
      background: #000;
      color: #fff;
      border: none;
      border-radius: 999px;
      padding: 12px 30px;
      font-size: 14px;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: .2s;
    }

    .btn-contact:hover {
      background: #333;
      color: #fff;
    }

    .btn-contact-outline {
      background: transparent;
      color: #000;
      border: 1.5px solid #000;
      border-radius: 999px;
      padding: 12px 30px;
      font-size: 14px;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: .2s;
    }

    .btn-contact-outline:hover {
      background: #000;
      color: #fff;
    }
  </style>
</head>

<body>
  <?php include 'partials/navbar.php'; ?>

  <!-- Hero -->
  <div class="faq-hero">
    <h1>FAQs</h1>
    <p>Everything you need to know about Blank Perfume.</p>
  </div>

  <!-- Search + Tabs -->
  <div class="faq-search-wrap">
    <div class="container">
      <div class="faq-search">
        <input type="text" id="faqSearch" placeholder="Search a question..." oninput="searchFAQ()">
        <i class="bi bi-search"></i>
      </div>
      <div class="faq-tabs">
        <button class="faq-tab active" onclick="filterTab('all', this)">All</button>
        <button class="faq-tab" onclick="filterTab('shipping', this)">Shipping</button>
        <button class="faq-tab" onclick="filterTab('product', this)">Product</button>
        <button class="faq-tab" onclick="filterTab('warranty', this)">Warranty</button>
        <button class="faq-tab" onclick="filterTab('how-to-use', this)">How to Use</button>
        <button class="faq-tab" onclick="filterTab('agent', this)">Become an Agent</button>
        <button class="faq-tab" onclick="filterTab('restock', this)">Restock</button>
      </div>
    </div>
  </div>

  <!-- FAQ Accordion -->
  <section class="faq-section">
    <div class="container">
      <div class="faq-group" id="faq-container">
        <div class="accordion" id="mainAccordion">

          <!-- SHIPPING -->
          <p class="faq-group-title">📦 Shipping</p>

          <div class="accordion-item faq-item" data-category="shipping">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ans1">
                How long does shipping take?
              </button>
            </h2>
            <div id="ans1" class="accordion-collapse collapse">
              <div class="accordion-body">
                Orders are usually shipped within <strong>1–3 business days</strong> via <strong>J&T Express</strong>.
              </div>
            </div>
          </div>

          <div class="accordion-item faq-item" data-category="shipping">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ans2">
                How much does shipping cost?
              </button>
            </h2>
            <div id="ans2" class="accordion-collapse collapse">
              <div class="accordion-body">
                Shipping cost is calculated automatically during checkout based on the <strong>quantity
                  purchased</strong> and your <strong>delivery location</strong>.
              </div>
            </div>
          </div>

          <div class="accordion-item faq-item" data-category="shipping">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ans3">
                Where do you ship to?
              </button>
            </h2>
            <div id="ans3" class="accordion-collapse collapse">
              <div class="accordion-body">
                We ship <strong>nationwide across Malaysia</strong>. If your location is unavailable at checkout, please
                contact us via WhatsApp at <a href="https://wa.me/601155098234">011-5509 8234</a> or email <a
                  href="mailto:blankcarfragrance@gmail.com">blankcarfragrance@gmail.com</a>.
              </div>
            </div>
          </div>

          <!-- PRODUCT LIFESPAN -->
          <p class="faq-group-title">🕐 Product Lifespan</p>

          <div class="accordion-item faq-item" data-category="product">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ans4">
                How long do the products last?
              </button>
            </h2>
            <div id="ans4" class="accordion-collapse collapse">
              <div class="accordion-body">
                Both <strong>Blank Gel</strong> and <strong>Blank Liquid</strong> fragrances typically last around
                <strong>30–45 days</strong> with regular use.
              </div>
            </div>
          </div>

          <!-- WARRANTY -->
          <p class="faq-group-title">🛡️ Warranty</p>

          <div class="accordion-item faq-item" data-category="warranty">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ans5">
                Do you provide a warranty?
              </button>
            </h2>
            <div id="ans5" class="accordion-collapse collapse">
              <div class="accordion-body">
                Yes, we provide a <strong>7-day warranty</strong> covering the following issues:
                <ul>
                  <li>Product has no scent</li>
                  <li>Cap cannot open</li>
                  <li>Item broken during delivery</li>
                </ul>
                <br>
                If purchased through an <strong>authorized agent</strong>, please contact your agent directly.<br>
                If purchased from our <strong>official website</strong>, email us at <a
                  href="mailto:blankcarfragrance@gmail.com">blankcarfragrance@gmail.com</a>.
              </div>
            </div>
          </div>

          <!-- HOW TO USE -->
          <p class="faq-group-title">📖 How to Use</p>

          <div class="accordion-item faq-item" data-category="how-to-use">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ans6">
                How do I use Blank Gel?
              </button>
            </h2>
            <div id="ans6" class="accordion-collapse collapse">
              <div class="accordion-body">
                <ol>
                  <li>Twist the lid open</li>
                  <li>Remove the plastic cap</li>
                  <li>Put the lid back on</li>
                  <li>Place in your car</li>
                  <li>Avoid direct sunlight — it may cause the gel to harden</li>
                  <li>If hardened, simply stir with a toothpick to restore</li>
                </ol>
              </div>
            </div>
          </div>

          <div class="accordion-item faq-item" data-category="how-to-use">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ans7">
                How do I use Blank Liquid?
              </button>
            </h2>
            <div id="ans7" class="accordion-collapse collapse">
              <div class="accordion-body">
                <ol>
                  <li>Unscrew the lid</li>
                  <li>Insert the wooden stick into the underside cavity of the lid</li>
                  <li>Remove the plastic cap</li>
                  <li>Put the lid back on</li>
                  <li>Place in your vehicle and enjoy</li>
                </ol>
              </div>
            </div>
          </div>

          <!-- AGENT -->
          <p class="faq-group-title">🤝 Becoming an Agent</p>

          <div class="accordion-item faq-item" data-category="agent">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ans8">
                Can I become an authorized agent?
              </button>
            </h2>
            <div id="ans8" class="accordion-collapse collapse">
              <div class="accordion-body">
                Yes! We offer authorized agent opportunities with:
                <ul>
                  <li>Low startup fee</li>
                  <li>Potential earnings of up to <strong>RM 5,000/month</strong></li>
                </ul>
                Interested? Contact us via WhatsApp: <a href="https://wa.me/601155098234"><strong>011-5509
                    8234</strong></a>
              </div>
            </div>
          </div>

          <!-- RESTOCK -->
          <p class="faq-group-title">🔄 Restock</p>

          <div class="accordion-item faq-item" data-category="restock">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ans9">
                When will out-of-stock items be restocked?
              </button>
            </h2>
            <div id="ans9" class="accordion-collapse collapse">
              <div class="accordion-body">
                There are no fixed restock dates. Follow our socials to stay updated:
                <ul>
                  <li><strong>Facebook:</strong> <a href="https://facebook.com/Blank.malaysia"
                      target="_blank">Blank.malaysia</a></li>
                  <li><strong>Instagram:</strong> <a href="https://instagram.com/blank.malaysia"
                      target="_blank">@blank.malaysia</a></li>
                  <li><strong>Tiktok:</strong> <a href="https://www.tiktok.com/@blank.my" target="_blank">@blank.my</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>

        </div><!-- /accordion -->
      </div><!-- /faq-group -->

      <p id="noResults" class="text-center text-muted mt-4" style="display:none;">No questions found. Try a different
        keyword.</p>
    </div>
  </section>

  <!-- Contact CTA -->
  <div class="contact-cta">
    <h3>Still have questions?</h3>
    <p>📍 PT 404, Tingkat 1, Bandar Baru Tunjong, Jalan Kuala Krai, 15150 Kota Bharu, Kelantan</p>
    <p>Our team is happy to help — reach out anytime.</p>
    <div class="contact-links">
      <a href="mailto:blankcarfragrance@gmail.com" class="btn-contact">
        <i class="bi bi-envelope-fill"></i> Email Us
      </a>
      <a href="https://wa.me/601155098234" class="btn-contact-outline" target="_blank">
        <i class="bi bi-whatsapp"></i> WhatsApp Us
      </a>
    </div>
  </div>

  <?php include 'partials/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Close all other open panels when one opens
    document.addEventListener('show.bs.collapse', function (e) {
      document.querySelectorAll('.accordion-collapse.show').forEach(function (openPanel) {
        if (openPanel !== e.target) {
          bootstrap.Collapse.getInstance(openPanel).hide();
        }
      });
    });

    function filterTab(category, btn) {
      document.querySelectorAll('.faq-tab').forEach(t => t.classList.remove('active'));
      btn.classList.add('active');
      document.getElementById('faqSearch').value = '';
      // Close any open panel first
      document.querySelectorAll('.accordion-collapse.show').forEach(p => bootstrap.Collapse.getInstance(p).hide());
      document.querySelectorAll('.faq-item').forEach(item => {
        item.classList.toggle('hidden', category !== 'all' && item.dataset.category !== category);
      });
      updateGroupTitles();
      checkNoResults();
    }

    function searchFAQ() {
      const q = document.getElementById('faqSearch').value.toLowerCase();
      document.querySelectorAll('.faq-tab').forEach(t => t.classList.remove('active'));
      document.querySelector('.faq-tab').classList.add('active');
      document.querySelectorAll('.faq-item').forEach(item => {
        item.classList.toggle('hidden', q.length > 0 && !item.innerText.toLowerCase().includes(q));
      });
      updateGroupTitles();
      checkNoResults();
    }

    function updateGroupTitles() {
      document.querySelectorAll('.faq-group-title').forEach(title => {
        let next = title.nextElementSibling;
        let hasVisible = false;
        while (next && !next.classList.contains('faq-group-title')) {
          if (next.classList.contains('faq-item') && !next.classList.contains('hidden')) {
            hasVisible = true;
            break;
          }
          next = next.nextElementSibling;
        }
        title.style.display = hasVisible ? '' : 'none';
      });
    }

    function checkNoResults() {
      const visible = [...document.querySelectorAll('.faq-item')].filter(i => !i.classList.contains('hidden'));
      document.getElementById('noResults').style.display = visible.length === 0 ? '' : 'none';
    }
  </script>
</body>

</html>