<?php
$conn = mysqli_connect('localhost', 'root', '', 'school');
$result = mysqli_query($conn, "SELECT * FROM materials WHERE id = 37");
$material = mysqli_fetch_assoc($result);
print_r($material);

$result = mysqli_query($conn, "SELECT * FROM categories");
$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
print_r($categories);
?>
