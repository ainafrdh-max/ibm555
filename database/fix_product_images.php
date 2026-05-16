<?php
require dirname(__DIR__) . '/config.php';

$updates = [
    1 => 'blank-black-rose.png',
    2 => 'blank-rose.png',
    3 => 'blank-black.png',
    4 => 'blank-lemon.png',
    5 => 'blank-rose.png',
    6 => 'blank-summer.png',
];

foreach ($updates as $id => $image) {
    $id = (int) $id;
    $image = mysqli_real_escape_string($conn, $image);
    $conn->query("UPDATE products SET image = '$image' WHERE id = $id");
}

echo "Product images updated.\n";
