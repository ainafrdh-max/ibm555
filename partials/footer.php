<footer>
    <div class="container text-center text-md-start">
        <div class="row">
            <div class="col-md-4 mb-4">
                <img src="img/blank-logo.png" class="logo-footer" alt="Blank Perfume">
                <p>Minimal scent. Maximum presence.</p>
                <p class="mt-2">
                    <a href="https://www.tiktok.com/@blank.my" target="_blank" rel="noopener"
                        style="color:#000;text-decoration:none;font-weight:600;">
                        <i class="bi bi-tiktok"></i> @blank.my
                    </a>
                </p>
            </div>
            <div class="col-md-4 mb-4">
                <h5>Contact</h5>
                <p>PT 404, Tingkat 1,<br>Bandar Baru Tunjong,<br>Jalan Kuala Krai,<br>15150 Kota Bharu</p>
            </div>
            <div class="col-md-4 mb-4">
                <h5>Reach Us</h5>
                <p>011-5509 8234</p>
                <p>blankcarfragrance@gmail.com</p>
            </div>
        </div>
        <hr>
        <p class="text-center mb-0">© 2026 Blank Perfume</p>
    </div>
</footer>
<?php include __DIR__ . '/chatbot.php'; ?>
<?php if (isset($_SESSION['user_id'])): ?>
<script src="assets/cart-sync.js"></script>
<?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
