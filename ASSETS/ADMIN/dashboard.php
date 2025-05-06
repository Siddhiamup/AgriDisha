<?php
// session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ams_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch admin info
$admin_name = $_SESSION['admin'];
$admin_query = "SELECT * FROM admin_info WHERE admin_name = '$admin_name'";
$admin_info = $conn->query($admin_query)->fetch_assoc();

// Fetch counts
$counts = [
    'total_products' => "SELECT COUNT(*) AS total FROM products",
    'total_sellers' => "SELECT COUNT(*) AS total FROM users WHERE role='seller'",
    'total_buyers' => "SELECT COUNT(*) AS total FROM users WHERE role='buyer'",
    'pending_sellers' => "SELECT COUNT(*) AS total FROM users WHERE role='seller' AND status='pending'",
];

foreach ($counts as $key => $sql) {
    ${$key} = $conn->query($sql)->fetch_assoc()['total'];
}

// Fetch recent orders
$orders_query = "SELECT id, status, order_date FROM orders ORDER BY order_date DESC LIMIT 5";
$orders_result = $conn->query($orders_query);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- <div class="container">
        <!-- Sidebar -->
        <!-- <div class="sidebar"> -->
            <!-- <div class="logo-container">
                <img src="../IMAGES/logo.jpg" alt="Logo" width="100">
            </div>
            <h2>Admin Panel</h2> -->
            <!-- <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="view_products.php">Products</a></li>
                <li><a href="manage_sellers.php">Manage Sellers </li>
                <li><a href="manage_buyers.php">Manage Buyers ()</a></li>
                <li><a href="manage_orders.php">Orders</a></li>
                <li><a href="sales_reports.php">Sales Reports</a></li>
                <li><a href="admin_profile.php">Admin Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul> -->
        <!-- </div> --> 


            

            
            </div>
        </div>
    </div>
</body>
</html>
