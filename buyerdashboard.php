<?php
session_start(); // Start the session

$servername = "localhost"; // Your database host
$username = "root";
$password = "";
$dbname = "ams_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure user is logged in
// if (!isset($_SESSION['id'])) {
//     die("User not logged in");
// }

$buyer_id = $_SESSION['id'];

// Fetch buyer information
$buyer_query = "SELECT * FROM users WHERE id = $buyer_id";
$buyer_result = $conn->query($buyer_query);

if ($buyer_result->num_rows > 0) {
    $buyer_data = $buyer_result->fetch_assoc();
} else {
    die("Buyer not found");
}

// Get current tab from URL parameter, default to dashboard
$current_tab = isset($_GET['tab']) ? $_GET['tab'] : 'dashboard';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="ASSETS/CSS/farmerdash.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>
    <div class="container">
        <div class="sidebar">
           <center> <img src="ASSETS/IMAGES/logo.jpg" alt="Logo" height="87" width="87"></center>
            <h2>Buyer Dashboard</h2>
            <ul>
                <li><a href="?tab=dashboard" class="<?= $current_tab == 'dashboard' ? 'active' : ''; ?>"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="?tab=profile" class="<?= $current_tab == 'profile' ? 'active' : ''; ?>"><i class="fas fa-user"></i> Profile</a></li>
                <li><a href="?tab=browse_products" class="<?= $current_tab == 'browse_products' ? 'active' : ''; ?>"><i class="fas fa-store"></i> Browse Products</a></li>
                <li><a href="?tab=cart" class="<?= $current_tab == 'cart' ? 'active' : ''; ?>"><i class="fas fa-shopping-cart"></i> My Cart</a></li>
                <li><a href="?tab=orders" class="<?= $current_tab == 'orders' ? 'active' : ''; ?>"><i class="fas fa-shopping-bag"></i> My Orders</a></li>
                <li><a href="?tab=setting" class="<?= $current_tab == 'setting' ? 'active' : ''; ?>"><i class="fas fa-cog"></i> Setting</a></li>

            </ul>
        </div>

        <div class="content">
            <div class="header">
                <h1>Welcome, <?= isset($buyer_data['username']) ? htmlspecialchars($buyer_data['username']) : "Unknown User"; ?>!</h1>
                <div class="logout">
                    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>

            <div class="main-content">
                <?php
                $allowed_tabs = ['dashboard', 'browse_products', 'my_cart', 'my_ordersorders', 'profile','setting'];
                $current_tab = in_array($current_tab, $allowed_tabs) ? $current_tab : 'dashboard';

                $tab_file = "./buyer_tabs/$current_tab.php";
                if (file_exists($tab_file)) {
                    include $tab_file;
                } else {
                    echo "<p>Tab file not found: $tab_file</p>";
                }
                ?>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        $('.add-to-cart').click(function(e) {
            e.preventDefault();
            const productId = $(this).data('product-id');

            $.ajax({
                url: 'add_to_cart.php',
                type: 'POST',
                data: { product_id: productId },
                success: function(response) {
                    alert('Product added to cart!');
                },
                error: function() {
                    alert('Error adding product to cart');
                }
            });
        });
    });
    </script>
</body>
</html>
