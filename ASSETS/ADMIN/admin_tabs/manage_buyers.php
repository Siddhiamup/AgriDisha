<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ams_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT * FROM users WHERE role = 'buyer'";  // Fetch all buyers
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Buyers</title>
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
            <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="view_products.php">View Products</a></li>
                <li><a href="manage_sellers.php">Manage Sellers</a></li>
                <li><a href="manage_buyers.php">Manage Buyers</a></li>
                <li><a href="manage_orders.php">Orders</a></li>
                <li><a href="sales_reports.php">Sales Reports</a></li>
                <li><a href="admin_profile.php">Admin Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>

        <div class="content">
            <h1>Buyers List</h1>
            <table>
                <tr>
                    <th>Buyer ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
                <?php while ($buyer = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $buyer['id']; ?></td>
                        <td><?php echo $buyer['username']; ?></td>
                        <td><?php echo $buyer['email']; ?></td>
                        <td>
                            <a href="edit_buyer.php?id=<?php echo $buyer['id']; ?>">Accept</a> |
                            <a href="delete_buyer.php?id=<?php echo $buyer['id']; ?>">RejectS</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</body>
</html>
