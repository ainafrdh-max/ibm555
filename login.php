<?php include "config.php"; ?>

<?php
if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['id']; // ✅ STORE USER ID
        $_SESSION['username'] = $user['username'];

        header("Location: homepage.php"); // ✅ REDIRECT
        exit();

    } else {
        echo "<script>alert('Invalid login')</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex justify-content-center align-items-center vh-100">

<form method="POST" class="p-4 shadow rounded" style="width:350px;">
<h3 class="text-center mb-3">Login</h3>

<input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
<input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

<button name="login" class="btn btn-dark w-100">Login</button>

<p class="mt-3 text-center">
No account? <a href="register.php">Register</a>
</p>

</form>

</body>
</html>