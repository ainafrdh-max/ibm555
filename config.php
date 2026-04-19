<?php
$conn = new mysqli("localhost", "root", "", "blank_perfume");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();
?>