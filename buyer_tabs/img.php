<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ams_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch products from the database
$product_query = "SELECT * FROM products";
$result = $conn->query($product_query);
while ($row = $result->fetch_assoc()) {
    $image_path = '../' . $row['image_path'];
    if (file_exists($image_path)) {
        echo "<p>File exists: $image_path</p>";
        echo "<img src='$image_path' alt='Product Image'>";
    } else {
        echo "<p style='color:red;'>Image not found: $image_path</p>";
    }
}
?>