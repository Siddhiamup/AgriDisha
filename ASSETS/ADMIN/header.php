<?php
// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}
$admin_name = $_SESSION['admin'];
?>

<div class="sidebar">
    <div class="logo-container">
        <img src="../IMAGES/logo.jpg" alt="Logo">
    </div>
    <h2>Admin Panel</h2>
    <ul>
        <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="view_products.php"><i class="fas fa-box"></i> Products</a></li>
        <li><a href="manage_sellers.php"><i class="fas fa-store"></i> Manage Sellers</a></li>
        <li><a href="manage_buyers.php"><i class="fas fa-users"></i> Manage Buyers</a></li>
        <li><a href="manage_orders.php"><i class="fas fa-shopping-cart"></i> Orders</a></li>
        <li><a href="sales_reports.php"><i class="fas fa-chart-line"></i> Sales Reports</a></li>
        <li><a href="admin_profile.php"><i class="fas fa-user-cog"></i> Admin Profile</a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>
