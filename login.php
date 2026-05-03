<?php include "config.php"; ?>

<?php
if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        header("Location: homepage.php");
        exit();

    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In – Blank Perfume</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "SF Pro Display", "Helvetica Neue", Arial, sans-serif;
            min-height: 100vh;
            display: flex;
        }

        /* Left panel – brand */
        .brand-panel {
            width: 50%;
            background: #e8f7d0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 40px;
            position: relative;
            overflow: hidden;
        }

        .brand-panel::before {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.35);
            bottom: -100px;
            right: -100px;
        }

        .brand-panel::after {
            content: '';
            position: absolute;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            top: -60px;
            left: -60px;
        }

        .brand-panel img.brand-logo {
            height: 52px;
            object-fit: contain;
            margin-bottom: 40px;
            position: relative;
            z-index: 1;
        }

        .brand-panel img.brand-product {
            height: 280px;
            object-fit: contain;
            position: relative;
            z-index: 1;
            filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.12));
        }

        .brand-tagline {
            margin-top: 32px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .brand-tagline h2 {
            font-size: 26px;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: #111;
        }

        .brand-tagline p {
            font-size: 14px;
            color: #555;
            margin-top: 6px;
        }

        /* Right panel – form */
        .form-panel {
            width: 50%;
            background: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 48px;
        }

        .form-inner {
            width: 100%;
            max-width: 380px;
        }

        .form-inner h1 {
            font-size: 30px;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 6px;
        }

        .form-inner .subtitle {
            font-size: 14px;
            color: #888;
            margin-bottom: 36px;
        }

        /* Error alert */
        .error-alert {
            background: #fff0f0;
            border: 1.5px solid #ffcccc;
            border-radius: 10px;
            padding: 10px 16px;
            font-size: 13px;
            color: #cc0000;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Input */
        .input-group-custom {
            position: relative;
            margin-bottom: 16px;
        }

        .input-group-custom i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            font-size: 16px;
            z-index: 2;
            pointer-events: none;
        }

        .input-group-custom input {
            width: 100%;
            border: 1.5px solid #e5e5e5;
            border-radius: 12px;
            padding: 13px 16px 13px 44px;
            font-size: 14px;
            outline: none;
            transition: border-color .2s;
            background: #fafafa;
        }

        .input-group-custom input:focus {
            border-color: #000;
            background: #fff;
        }

        .input-group-custom input::placeholder {
            color: #bbb;
        }


        /* Submit button */
        .btn-signin {
            width: 100%;
            background: #000;
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: .3px;
            cursor: pointer;
            transition: background .2s;
            margin-top: 6px;
        }

        .btn-signin:hover {
            background: #222;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 24px 0;
            color: #ccc;
            font-size: 12px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #eee;
        }

        .register-link {
            text-align: center;
            font-size: 14px;
            color: #888;
        }

        .register-link a {
            color: #000;
            font-weight: 600;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        /* Mobile: stack panels */
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            .brand-panel {
                width: 100%;
                padding: 40px 24px 30px;
            }

            .brand-panel img.brand-product {
                height: 160px;
            }

            .brand-tagline h2 {
                font-size: 20px;
            }

            .form-panel {
                width: 100%;
                padding: 40px 24px;
            }
        }
    </style>
</head>

<body>

    <!-- Left: Brand panel -->
    <div class="brand-panel">
        <img src="img/blank-logo.png" alt="Blank Perfume" class="brand-logo">
        <img src="img/blank-rose.png" alt="Blank Product" class="brand-product">
        <div class="brand-tagline">
            <h2>Your car. Your scent.</h2>
            <p>Minimal. Clean. Unforgettable.</p>
        </div>
    </div>

    <!-- Right: Form panel -->
    <div class="form-panel">
        <div class="form-inner">
            <h1>Welcome back 👋</h1>
            <p class="subtitle">Sign in to your Blank account</p>

            <?php if (!empty($error)): ?>
                <div class="error-alert">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="input-group-custom">
                    <i class="bi bi-envelope"></i>
                    <input type="email" name="email" placeholder="Email address" required
                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>

                <div class="input-group-custom">
                    <i class="bi bi-lock"></i>
                    <input type="password" name="password" id="passwordInput" placeholder="Password" required>
                </div>

                <button type="submit" name="login" class="btn-signin">Sign In</button>
            </form>

            <div class="divider">or</div>

            <p class="register-link">
                Don't have an account? <a href="register.php">Register now</a>
            </p>
        </div>
    </div>

</body>

</html>