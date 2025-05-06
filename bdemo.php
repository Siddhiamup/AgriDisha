<?php
session_start();

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ams_db"; // Change this if your database name is different

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$buyer_id = (int)$_SESSION['id'];

// Fetch buyer information using prepared statement
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

// Sanitize and validate current tab
$allowed_tabs = ['dashboard', 'browse_products', 'my_cart', 'my_orders', 'profile', 'settings', 'wishlist', 'transactions'];
$current_tab = isset($_GET['tab']) ? $_GET['tab'] : 'dashboard';
$current_tab = in_array($current_tab, $allowed_tabs) ? $current_tab : 'dashboard';

// Tab activity checker
function isTabActive($tab_name) {
    global $current_tab;
    return $current_tab === $tab_name ? 'active' : '';
}

// CSRF Protection
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Buyer Dashboard - <?= htmlspecialchars(ucfirst($current_tab)) ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="ASSETS/CSS/buyer.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
    <div class="container">
        <!-- Sidebar Navigation -->
        <div class="sidebar">
            <div class="logo-container">
                <img src="ASSETS/IMAGES/logo.jpg" alt="Logo" height="87" width="87">
                <h3>Buyer Dashboard</h3>
            </div>
            <nav>
                <ul>
                    <li><a href="?tab=dashboard" class="<?= isTabActive('dashboard') ?>">
                        <i class="fas fa-home"></i> Dashboard
                    </a></li>
                    <li><a href="?tab=profile" class="<?= isTabActive('profile') ?>">
                        <i class="fas fa-user"></i> Profile
                    </a></li>
                    <li><a href="?tab=browse_products" class="<?= isTabActive('browse_products') ?>">
                        <i class="fas fa-store"></i> Browse Products
                    </a></li>
                    <li><a href="?tab=my_orders" class="<?= isTabActive('my_orders') ?>">
                        <i class="fas fa-box"></i> My Orders
                    </a></li>
                    <li><a href="?tab=my_cart" class="<?= isTabActive('my_cart') ?>">
                        <i class="fas fa-shopping-cart"></i> My Cart
                    </a></li>
                    <li><a href="?tab=wishlist" class="<?= isTabActive('wishlist') ?>">
                        <i class="fas fa-heart"></i> Wishlist
                    </a></li>
                    <li><a href="?tab=transactions" class="<?= isTabActive('transactions') ?>">
                        <i class="fas fa-credit-card"></i> Transactions
                    </a></li>
                    <li><a href="?tab=setting" class="<?= isTabActive('setting') ?>">
                        <i class="fas fa-cogs"></i> Settings
                    </a></li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="content">
            <div class="header">
                <h1>Welcome, <?= htmlspecialchars($buyer_data['username']) ?>!</h1>
                <div class="user-actions">
                    <a href="notifications.php" class="notification-icon">
                        <i class="fas fa-bell"></i>
                        <span class="notification-count">0</span>
                    </a>
                    <a href="logout.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>

            <div class="main-content">
                <?php
                $tab_file = "./buyer_tabs/$current_tab.php";
                if (file_exists($tab_file)) {
                    include $tab_file;
                } else {
                    echo "<div class='error-message'>Tab content not found</div>";
                }
                ?>
            </div>
        </div>
    </div>

   
</body>
</html>