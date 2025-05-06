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

$buyer_id = (int)$_SESSION['id'];

// Fetch buyer information
$buyer_query = $conn->prepare("SELECT * FROM users WHERE id = ?");
$buyer_query->bind_param("i", $buyer_id);
$buyer_query->execute();
$buyer_result = $buyer_query->get_result();

if ($buyer_result->num_rows === 0) {
    session_destroy();
    header('Location: login.php');
    exit();
}

$buyer_data = $buyer_result->fetch_assoc();

// Fetch counts
$order_query = $conn->prepare("SELECT COUNT(*) AS order_count FROM orders WHERE buyer_id = ?");
$order_query->bind_param("i", $buyer_id);
$order_query->execute();
$order_result = $order_query->get_result()->fetch_assoc();
$order_count = $order_result['order_count'];

$wishlist_query = $conn->prepare("SELECT COUNT(*) AS wishlist_count FROM wishlist WHERE buyer_id = ?");
$wishlist_query->bind_param("i", $buyer_id);
$wishlist_query->execute();
$wishlist_result = $wishlist_query->get_result()->fetch_assoc();
$wishlist_count = $wishlist_result['wishlist_count'];

$cart_query = $conn->prepare("SELECT COUNT(*) AS cart_count FROM cart WHERE buyer_id = ?");
$cart_query->bind_param("i", $buyer_id);
$cart_query->execute();
$cart_result = $cart_query->get_result()->fetch_assoc();
$cart_count = $cart_result['cart_count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    <link rel="stylesheet" href="../ASSETS/CSS/dashboard.css">
</head>
<style>
    /* dashboard.css */
.dashboard-container {
    font-family: Arial, sans-serif;
    width: 100%;
    text-align: center;
}
.dashboard-header {
    background-color: #27ae60;
    color: white;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.logout-btn {
    color: white;
    text-decoration: none;
    padding: 5px 10px;
    background-color: red;
    border-radius: 5px;
}
</style>
<body>
    <div class="dashboard-cards">
        
 
                <div class="card">
                    <h2>My Orders</h2>
                    <p><?= $order_count ?></p>
                </div>
                <div class="card">
                    <h2>Wishlist Items</h2>
                    <p><?= $wishlist_count ?></p>
                </div>
                <div class="card">
                    <h2>Cart Items</h2>
                    <p><?= $cart_count ?></p>
                </div>
       
      
    </div>
</body>
</html>

