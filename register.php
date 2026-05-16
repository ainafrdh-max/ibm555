<?php include "config.php"; ?>

<?php
if (isset($_POST['register'])) {

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password)
            VALUES ('$username', '$email', '$password')";

    if ($conn->query($sql)) {
        header("Location: login.php");
        exit();
    } else {
        $error = "Registration failed. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account – Blank Perfume</title>

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
            background: #fff;
        }

        /* LEFT PANEL */
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

        .brand-logo {
            height: 52px;
            margin-bottom: 40px;
            position: relative;
            z-index: 1;
        }

        .brand-product {
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
            font-size: 28px;
            font-weight: 700;
            color: #111;
            letter-spacing: -0.5px;
        }

        .brand-tagline p {
            margin-top: 6px;
            color: #555;
            font-size: 14px;
        }

        /* RIGHT PANEL */
        .form-panel {
            width: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 48px;
            background: #fff;
        }

        .form-inner {
            width: 100%;
            max-width: 380px;
        }

        .form-inner h1 {
            font-size: 30px;
            font-weight: 700;
            margin-bottom: 6px;
            letter-spacing: -0.5px;
        }

        .subtitle {
            font-size: 14px;
            color: #888;
            margin-bottom: 36px;
        }

        /* ERROR ALERT */
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

        /* INPUTS */
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
        }

        .input-group-custom input {
            width: 100%;
            border: 1.5px solid #e5e5e5;
            border-radius: 12px;
            padding: 13px 16px 13px 44px;
            font-size: 14px;
            background: #fafafa;
            outline: none;
            transition: .2s;
        }

        .input-group-custom input:focus {
            border-color: #000;
            background: #fff;
        }

        .input-group-custom input::placeholder {
            color: #bbb;
        }

        /* BUTTON */
        .btn-register {
            width: 100%;
            background: #000;
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: .3px;
            margin-top: 6px;
            transition: .2s;
        }

        .btn-register:hover {
            background: #222;
        }

        /* DIVIDER */
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

        /* LOGIN LINK */
        .login-link {
            text-align: center;
            font-size: 14px;
            color: #888;
        }

        .login-link a {
            color: #000;
            font-weight: 600;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        /* MOBILE */
        @media (max-width: 768px) {

            body {
                flex-direction: column;
            }

            .brand-panel,
            .form-panel {
                width: 100%;
            }

            .brand-panel {
                padding: 40px 24px 30px;
            }

            .brand-product {
                height: 170px;
            }

            .brand-tagline h2 {
                font-size: 22px;
            }

            .form-panel {
                padding: 40px 24px;
            }
        }
    </style>
</head>

<body>

    <!-- LEFT -->
    <div class="brand-panel">
        <img src="img/blank-logo.png" alt="Blank Perfume" class="brand-logo">

        <img src="img/blank-rose.png" alt="Perfume" class="brand-product">

        <div class="brand-tagline">
            <h2>Create your Blank account</h2>
            <p>Join the minimalist fragrance experience.</p>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="form-panel">

        <div class="form-inner">

            <h1>Hello there ✨</h1>
            <p class="subtitle">Create your account to get started</p>

            <?php if (!empty($error)): ?>
                <div class="error-alert">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="input-group-custom">
                    <i class="bi bi-person"></i>
                    <input type="text" name="username" placeholder="Username" required
                        value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>

                <div class="input-group-custom">
                    <i class="bi bi-envelope"></i>
                    <input type="email" name="email" placeholder="Email address" required
                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>

                <div class="input-group-custom">
                    <i class="bi bi-lock"></i>
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <button type="submit" name="register" class="btn-register">
                    Create Account
                </button>

            </form>

            <div class="divider">or</div>

            <p class="login-link">
                Already have an account?
                <a href="login.php">Sign In</a>
            </p>

        </div>

    </div>

</body>

</html>