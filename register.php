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
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex justify-content-center align-items-center vh-100">

<form method="POST" class="p-4 shadow rounded" style="width:350px;">
<h3 class="text-center mb-3">Register</h3>

<input type="text" name="username" class="form-control mb-3" placeholder="Username" required>
<input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
<input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

<button name="register" class="btn btn-dark w-100">Register</button>

<p class="mt-3 text-center">
Already have account? <a href="login.php">Login</a>
</p>

</form>

</body>
</html>