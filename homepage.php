<?php include "config.php"; ?>

<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<h1>Welcome <?php echo $_SESSION['username']; ?> 👋</h1>
<p>Your User ID: <?php echo $_SESSION['user_id']; ?></p>

<a href="logout.php">Logout</a>