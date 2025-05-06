<?php
// session_start();

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

if (isset($_GET['id'])) {
    $cart_id = (int)$_GET['id'];

    // Delete item from cart
    $delete_query = $conn->prepare("DELETE FROM cart WHERE id = ?");
    $delete_query->bind_param("i", $cart_id);
    
    if ($delete_query->execute()) {
        header("Location: my_cart.php");
    } else {
        echo "Error removing item.";
    }
}
?>
