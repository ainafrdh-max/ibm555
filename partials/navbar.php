<nav class="navbar navbar-expand-lg">
    <div class="container">

        <!-- LOGO -->
        <a class="navbar-brand" href="<?php echo isset($_SESSION['user_id']) ? 'homepage.php' : 'index.php'; ?>">
            <img src="img/blank-logo.png" class="logo-nav">
        </a>

        <!-- TOGGLER -->
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navMenu">☰</button>

        <!-- MENU -->
        <div class="collapse navbar-collapse justify-content-center" id="navMenu">
            <?php if (isset($_SESSION['user_id'])): ?>
                <ul class="navbar-nav gap-4 text-center">
                    <li class="nav-item"><a class="nav-link" href="homepage.php">HOME</a></li>
                    <li class="nav-item"><a class="nav-link" href="products.php">SHOP</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">ABOUT US</a></li>
                    <li class="nav-item"><a class="nav-link" href="faq.php">FAQ</a></li>
                </ul>
            <?php else: ?>
                <ul class="navbar-nav gap-4 text-center">
                    <li class="nav-item"><a class="nav-link" href="index.php">HOME</a></li>
                    <li class="nav-item"><a class="nav-link" href="shop.php">SHOP</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">ABOUT US</a></li>
                    <li class="nav-item"><a class="nav-link" href="faq.php">FAQ</a></li>
                </ul>
            <?php endif; ?>
        </div>

        <!-- RIGHT SIDE -->
        <?php if (isset($_SESSION['user_id'])): ?>

            <!-- Logged in -->
            <div class="d-none d-lg-flex align-items-center gap-3 ms-auto">

                <!-- Cart icon -->
                <?php
                $cart_count = isset($_SESSION['user_id']) ? get_cart_count($conn, (int) $_SESSION['user_id']) : 0;
                ?>
                <a href="cart.php" class="nav-icon-btn" title="Cart">
                    <i class="bi bi-cart3 nav-icon"></i>
                    <span class="cart-dot" data-cart-count style="<?php echo $cart_count > 0 ? '' : 'display:none;'; ?>"><?php echo $cart_count; ?></span>
                </a>

                <!-- User dropdown -->
                <div class="dropdown">
                    <button class="user-menu-btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="user-avatar"><?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?></span>
                        <span class="user-name"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end user-dropdown">
                        <li class="dropdown-header-item">
                            <div class="dd-username"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
                            <div class="dd-email" style="font-size:12px; color:#aaa;">Member</div>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="homepage.php"><i class="bi bi-grid-1x2"></i> Dashboard</a></li>
                        <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person-circle"></i> My Profile</a>
                        </li>
                        <li><a class="dropdown-item" href="cart.php"><i class="bi bi-cart3"></i> My Cart
                                <span class="dd-badge" data-cart-count style="<?php echo $cart_count > 0 ? '' : 'display:none;'; ?>"><?php echo $cart_count; ?></span></a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i>
                                Logout</a></li>
                    </ul>
                </div>

            </div>

            <!-- Mobile logged-in links -->
            <div class="d-lg-none w-100 mt-2 pb-3 text-center mobile-auth">
                <a href="homepage.php" class="mobile-auth-link">Dashboard</a>
                <a href="profile.php" class="mobile-auth-link">Profile</a>
                <a href="cart.php" class="mobile-auth-link">Cart <span data-cart-count-text><?php echo $cart_count > 0 ? "($cart_count)" : ''; ?></span></a>
                <a href="logout.php" class="mobile-auth-link" style="color:#c00;">Logout</a>
            </div>

        <?php else: ?>

            <!-- Not logged in -->
            <div class="d-none d-lg-flex align-items-center gap-2 ms-auto">
                <i class="bi bi-person nav-icon"></i>
                <a href="login.php" class="nav-link sign-in-link">SIGN IN / REGISTER</a>
            </div>

            <!-- Mobile not logged in -->
            <div class="d-lg-none w-100 mt-2 pb-3 text-center mobile-auth">
                <a href="login.php" class="mobile-auth-link">Sign In / Register</a>
            </div>

        <?php endif; ?>

    </div>
</nav>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
    /* Cart icon button */
    .nav-icon-btn {
        position: relative;
        color: #000;
        font-size: 20px;
        text-decoration: none;
        display: flex;
        align-items: center;
    }

    .nav-icon-btn:hover {
        opacity: .6;
    }

    .cart-dot {
        position: absolute;
        top: -6px;
        right: -8px;
        background: #000;
        color: #e8f7d0;
        font-size: 10px;
        font-weight: 700;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* User menu button */
    .user-menu-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #000;
        border: none;
        border-radius: 999px;
        padding: 6px 14px 6px 6px;
        cursor: pointer;
        transition: .2s;
    }

    .user-menu-btn:hover {
        background: #222;
    }

    .user-menu-btn::after {
        filter: invert(1);
    }

    .user-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #e8f7d0;
        color: #000;
        font-size: 13px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .user-name {
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: .3px;
    }

    /* Dropdown */
    .user-dropdown {
        border: 1.5px solid #eee;
        border-radius: 16px;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
        padding: 8px;
        min-width: 200px;
    }

    .dropdown-header-item {
        padding: 10px 14px 8px;
    }

    .dd-username {
        font-weight: 700;
        font-size: 14px;
    }

    .user-dropdown .dropdown-item {
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: .15s;
    }

    .user-dropdown .dropdown-item:hover {
        background: #f5f5f5;
    }

    .user-dropdown .dropdown-item.text-danger:hover {
        background: #fff5f5;
    }

    .user-dropdown .dropdown-divider {
        margin: 4px 8px;
    }

    .dd-badge {
        background: #000;
        color: #fff;
        font-size: 10px;
        padding: 1px 7px;
        border-radius: 999px;
        margin-left: auto;
    }

    /* Mobile auth links */
    .mobile-auth {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .mobile-auth-link {
        display: block;
        padding: 8px 0;
        color: #000;
        font-size: 13px;
        text-decoration: none;
        letter-spacing: .5px;
    }

    .mobile-auth-link:hover {
        opacity: .6;
    }
</style>