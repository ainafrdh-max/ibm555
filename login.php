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
        echo "<script>alert('Invalid login')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #f5f5f5;
    font-family: 'Segoe UI', sans-serif;
}

.login-card {
    width: 360px;
    padding: 40px;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.05);
}

.login-title {
    font-weight: 500;
    margin-bottom: 25px;
}

.form-control {
    border: none;
    border-bottom: 1px solid #ddd;
    border-radius: 0;
    padding-left: 0;
}

.form-control:focus {
    box-shadow: none;
    border-color: #000;
}

.btn-minimal {
    background: #000;
    color: #fff;
    border-radius: 8px;
    padding: 10px;
}

.btn-minimal:hover {
    background: #333;
}

.small-text {
    font-size: 0.9rem;
    color: #777;
}
</style>

</head>

<body class="d-flex justify-content-center align-items-center vh-100">

<div class="login-card">

    <h4 class="text-center login-title">Welcome back</h4>

    <form method="POST">

        <input type="email" name="email" class="form-control mb-4" placeholder="Email" required>

        <input type="password" name="password" class="form-control mb-4" placeholder="Password" required>

        <button name="login" class="btn btn-minimal w-100">Login</button>

        <p class="mt-4 text-center small-text">
            No account? <a href="register.php">Create one</a>
        </p>

    </form>

</div>

</body>
</html>