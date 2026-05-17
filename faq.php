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
        <button class="faq-tab" onclick="filterTab('privacy', this)">Privacy Policy</button>
		<button class="faq-tab" onclick="filterTab('terms', this)">Terms & Conditions</button>
		<button class="faq-tab" onclick="filterTab('shipping', this)">Shipping & Refund Policy</button>
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
            
            <p class="faq-group-title">🔐 Privacy Policy</p>

<div class="accordion-item faq-item" data-category="privacy">
  <h2 class="accordion-header">
    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ansPrivacy">
      Privacy Policy
    </button>
  </h2>

  <div id="ansPrivacy" class="accordion-collapse collapse">
    <div class="accordion-body">

      <strong>Your Privacy</strong><br>
      We respect the privacy of your personal information and we strive to maintain the confidentiality of your personal information given by you. The objective of collecting your personal data is to deliver products and services, future marketing purposes and to improve our services to you. Only our authorized employees have access to your personal information. We will not disclose information about our customers to third parties except where it is part of providing a service to you – e.g. arranging for a product to be sent to you, carrying out credit and other security checks and for the purposes of customer research and profiling or where we have your express permission to do so. We may also be required to disclose such information to regulators, lawyers, auditors, other companies in the same group, third party service providers and appointed marketing agency.
      <br><br>

      <strong>Your Consent</strong><br>
      We will not sell your name, address, e-mail address, credit card information or personal information to any third party (excluding partners from whom you may have linked to our site) without your permission.
      <br><br>

      <strong>Communication & Marketing</strong><br>
      If you have made a purchase from our store we may occasionally update you on our latest products, news and special offers via e-mail, post & telephone. You will also be given the opportunity to receive such communications from us and selected third parties when you become a member of Blank Resources.
      <br><br>
      All Blank Resources members have the option to opt-out of receiving marketing communications. You can unsubscribe via your account settings or using the unsubscribe link in emails.
      <br><br>

      <strong>What are Cookies?</strong><br>
      A cookie is a small information file stored on your device. It helps us remember your preferences and improve your experience. You may disable cookies through your browser settings.
      <br><br>

      <strong>Site Statistics</strong><br>
      We may collect anonymised usage data such as page visits and browsing behaviour to improve our services. This information does not identify individual users.
      <br><br>

      <strong>Disclosures of your information</strong><br>
      We may share your data with group companies or third parties only when necessary for business operations, legal compliance, fraud prevention, or company restructuring.
      <br><br>

      <strong>Third Party Sites</strong><br>
      Our website may contain links to third-party websites. We are not responsible for their privacy practices and encourage users to review their policies before submitting personal data.
      <br><br>

      <strong>Checking Your Details</strong><br>
      You may request access to your personal data by contacting us. For security, we may require identity verification before disclosing information.
      <br><br>

      <strong>Contacting Us</strong><br>
      If you have any questions about this Privacy Policy or wish to update or remove your information, please contact the Blank Resources team.
      
    </div>
  </div>
</div>
            
            <p class="faq-group-title">📄 Terms & Conditions</p>

<div class="accordion-item faq-item" data-category="terms">
  <h2 class="accordion-header">
    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ansTerms">
      Terms & Conditions
    </button>
  </h2>

  <div id="ansTerms" class="accordion-collapse collapse">
    <div class="accordion-body">
      Welcome to Blank Resources for online store. Terms and conditions stated below applies to all visitors and users of https://www.blankhq.com.my/. You are bound by these terms and conditions as long as you’re on https://www.blankhq.com.my/
      <br><br>

      <strong>General</strong><br>
      The content of terms and conditions may be change, move or delete at any time. Please note that https://www.blankhq.com.my/ have the rights to change the contents of the terms and conditions without any notice. Any violation of rules and regulations of these terms and conditions, https://www.blankhq.com.my/ will take immediate actions against the offender(s).
      <br><br>

      <strong>Site Contents & Copyrights</strong><br>
      Unless otherwise noted, all materials, including images, illustrations, designs, icons, photographs, video clips, and written and other materials that appear as part of this Site, in other words “Contents of the Site” are copyrights, trademarks, trade dress and/or other intellectual properties owned, controlled or licensed by Blank Resources.
      <br><br>

      <strong>Comments and Feedbacks</strong><br>
      All comments and feedbacks to Blank Resources will be remain app@blankhq.com.my.<br><br>

      User shall agree that there will be no comment(s) submitted to the https://www.blankhq.com.my/ will violate any rights of any third party, including copyrights, trademarks, privacy of other personal or proprietary right(s). Furthermore, the user shall agree there will not be content of unlawful, abusive, or obscene material(s) submitted to the site. User will be the only one responsible for any comment’s content made.
      <br><br>

      <strong>Product Information</strong><br>
      We cannot guarantee all actual products will be exactly the same shown on the monitor as that is depending on the user monitor.
      <br><br>

      <strong>Newsletter</strong><br>
      User shall agree that https://www.blankhq.com.my/ may send newsletter regarding the latest news/products/promotions etc through email to the user.
      <br><br>

      <strong>Indemnification</strong><br>
      The user shall agree to defend, indemnify and hold https://www.blankhq.com.my/ harmless from and against any and all claims, damages, costs and expenses, including attorneys’ fees, arising from or related to your use of the Site.
      <br><br>

      <strong>Link to other sites</strong><br>
      Any access link to third party sites is at your own https://www.blankhq.com.my/ will not be related or involve to any such website if the user’s content/product(s) got damaged or loss have any connection with third party site.
      <br><br>

      <strong>Inaccuracy Information</strong><br>
      From time to time, there may be information on https://www.blankhq.com.my/ that contains typographical error, inaccuracies, omissions, that may relate to product description, pricing, availability and article contents. We reserve the rights to correct any errors, inaccuracies, change or edit information without prior notice to the customers. If you are not satisfy with your purchased product(s), please return it back to us with the invoice.
      <br><br>

      <strong>Termination</strong><br>
      This agreement is effective unless and until either by the customer or https://www.blankhq.com.my/. Customer may terminate this agreement at any time. However, https://www.blankhq.com.my/ may also terminate the agreement with the customer without any prior notice and will be denying the access of the customer who is unable to comply the terms and conditions above.
      <br><br>

      <strong>Shipping and Delivery Policy</strong><br>
      Items in stock: 2-5 working days for Standard Delivery items. Items that are out of stock: Please email or call us for assistance.
      <br><br>

      <strong>Payments</strong><br>
      All Goods purchased are subject to a one-time payment. Payment can be made through various payment methods we have available, such as Visa, MasterCard or online payment methods.<br><br>

      Payments cards (credit cards or debit cards) are subject to validation checks and authorization by Your card issuer. If we do not receive the required authorization, we will not be liable for any delay or non-delivery of Your Order.
    </div>
  </div>
</div>
            
            <p class="faq-group-title">🚚 Shipping & Refund Policy</p>

<div class="accordion-item faq-item" data-category="shipping">
  <h2 class="accordion-header">
    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ansShipping">
      Shipping & Refund Policy
    </button>
  </h2>

  <div id="ansShipping" class="accordion-collapse collapse">
    <div class="accordion-body">

      <strong>Cancellation Prior to Shipment</strong><br>
      If you cancel your order(s) before it ships from our warehouse, you will not be charged any additional fees. We require a cancellation request to be submitted by emailing us at app@blankhq.com.my<br><br>
      Once the cancellation request is received, a full refund will be initiated. We would advise a cancellation request within 12 hours upon your order submission in order for a cancellation prior to goods shipment.
      <br><br>

      <strong>Return Policy</strong><br>
      The following are the policies to be eligible for return requests after shipment/ receipt of goods:<br>
      1. All goods sold are non-refundable except (i) Failed Delivery (ii) Wrong Delivery and (iii) Damaged good during delivery.<br>
      2. Only items purchased directly from app@blankhq.com.my Online Store are eligible for return.<br>
      3. Products purchased through other retailers are not eligible and must follow respective retailer policies.<br>
      4. Goods are eligible for return if incorrect or damaged:<br>
      • Incorrect items include wrong product, wrong size, wrong colour, or missing items.<br>
      • Damaged items include items received damaged or tampered.<br>
      Customers will be responsible for return shipping charges. Items must be returned within 7 working days with proof of purchase, in original packaging, unused, and in new condition.<br>
      5. Change of mind cancellations are not accepted after payment confirmation.<br>
      6. We reserve the right to reject any unreasonable return or refund request.
      <br><br>

      <strong>Refund Policy</strong><br>
      Full refunds will be issued once returned goods are received and inspected.<br>
      1. Online bank transfer refunds will be processed within 3–5 working days.<br>
      2. Credit card refunds will be sent to the issuing bank. Processing time depends on the bank.
      <br><br>

      <strong>Shipping Policy</strong><br>
      We ship only to valid addresses provided during checkout. P.O. Boxes are not accepted. Incorrect address submissions may result in additional re-delivery charges.
      <br><br>
      Requests to change shipping address must be made within 12 hours of order submission. Changes after 24 hours may incur additional shipping charges.
      <br><br>
      Delivery typically takes 2–5 working days (Monday–Friday, 9:00 am–5:00 pm).
      <br><br>

      <strong>Tracking Number</strong><br>
      Tracking details will be provided once the order is shipped. Updates will be communicated via email, SMS, or mobile app.
      <br><br>

      We reserve the right to update this policy at any time without prior notice.

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