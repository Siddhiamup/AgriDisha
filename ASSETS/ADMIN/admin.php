<?php
session_start();
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

// Fetch admin info securely
$admin_name = $_SESSION['admin'];
$admin_query = $conn->prepare("SELECT * FROM admin_info WHERE admin_name = ?");
$admin_query->bind_param("s", $admin_name);
$admin_query->execute();
$admin_info = $admin_query->get_result()->fetch_assoc();
$admin_query->close();

// Fetch counts
$counts = [
    'total_products' => "SELECT COUNT(*) AS total FROM products",
    'total_sellers' => "SELECT COUNT(*) AS total FROM users WHERE role='seller'",
    'total_buyers' => "SELECT COUNT(*) AS total FROM users WHERE role='buyer'",
    'pending_sellers' => "SELECT COUNT(*) AS total FROM users WHERE role='seller' AND status='pending'",
];

$data_counts = [];

foreach ($counts as $key => $sql) {
    $result = $conn->query($sql);
    if (!$result) {
        die("Error in query ($key): " . $conn->error);
    }
    $row = $result->fetch_assoc();
    $data_counts[$key] = $row['total'];
}

// Fetch recent orders with product image
$orders_query = "SELECT o.id, o.status, o.order_date, u.username AS customer_name, 
                        p.name AS product_name, p.image_url AS product_image, 
                        o.quantity, o.total_price 
                 FROM orders o 
                 JOIN users u ON o.buyer_id = u.id 
                 JOIN products p ON o.product_id = p.id 
                 ORDER BY o.order_date DESC 
                 LIMIT 5";

$orders_result = $conn->query($orders_query);
if (!$orders_result) {
    die("Error fetching orders: " . $conn->error);
}

$conn->close();

// Get the current tab
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'dashboard';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo-container">
                <img src="../IMAGES/logo.jpg" alt="Logo" width="100">
            </div>
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="?tab=dashboard">Dashboard</a></li>
                <li><a href="?tab=view_products">View Products</a></li>
                <li><a href="?tab=manage_sellers">Manage Sellers </a></li>
                <li><a href="?tab=manage_buyers">Manage Buyers</a></li>
                <li><a href="?tab=manage_orders">Orders</a></li>
                <li><a href="?tab=sales_reports">Sales Reports</a></li>
                <li><a href="?tab=admin_profile">Admin Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>

        <!-- Content -->
        <div class="content">
            <h1>Welcome, <?= htmlspecialchars($admin_info['admin_name']) ?></h1>

            <!-- Dashboard Cards -->
            <div class="dashboard-cards">
                <div class="card">Total Products: <strong><?= $data_counts['total_products'] ?></strong></div>
                <div class="card">Total Sellers: <strong><?= $data_counts['total_sellers'] ?></strong></div>
                <div class="card">Total Buyers: <strong><?= $data_counts['total_buyers'] ?></strong></div>
                <div class="card">Pending Sellers: <strong><?= $data_counts['pending_sellers'] ?></strong></div>
            </div>

            <!-- Recent Orders Table -->
            <?php if ($tab == 'dashboard'): ?>
            <h2>Recent Orders</h2>
            <table border="1">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Product</th>
                        <th>Image</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Order Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $orders_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['id']) ?></td>
                            <td><?= htmlspecialchars($order['customer_name']) ?></td>
                            <td><?= htmlspecialchars($order['product_name']) ?></td>
                            <td><img src="../ASSETS/IMAGES/products/uploadsuploads<?= htmlspecialchars($order['product_image']) ?>" alt="Product Image" width="50"></td>
                            <td><?= htmlspecialchars($order['quantity']) ?></td>
                            <td><?= htmlspecialchars($order['total_price']) ?></td>
                            <td><?= htmlspecialchars($order['status']) ?></td>
                            <td><?= htmlspecialchars($order['order_date']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php endif; ?>
            <?php
            // Include tab content dynamically
            $allowed_tabs = ['dashboard', 'view_products', 'manage_sellers', 'manage_buyers', 'manage_orders', 'sales_reports', 'admin_profile'];
            if (in_array($tab, $allowed_tabs)) {
                include "$tab.php";
            } else {
                echo "<p>Invalid tab selected.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
