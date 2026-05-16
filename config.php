<?php
$conn = new mysqli("localhost", "root", "", "blank_perfume");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

require_once __DIR__ . '/includes/helpers.php';
ensure_order_columns($conn);
?>