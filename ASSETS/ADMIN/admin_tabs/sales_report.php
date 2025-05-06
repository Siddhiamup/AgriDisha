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

// Fetch total sales
$sales_query = "SELECT SUM(total_price) AS total_sales FROM orders WHERE status = 'completed'";
$sales_result = $conn->query($sales_query);
$total_sales = $sales_result->fetch_assoc()['total_sales'];

// Fetch sales by product
$product_sales_query = "SELECT product_name, SUM(quantity) AS quantity_sold FROM orders WHERE status = 'completed' GROUP BY product_name";
$product_sales_result = $conn->query($product_sales_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Reports</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="view_products.php">Products</a></li>
                <li><a href="manage_sellers.php">Manage Sellers</a></li>
                <li><a href="manage_buyers.php">Manage Buyers</a></li>
                <li><a href="manage_orders.php">Orders</a></li>
                <li><a href="sales_reports.php">Sales Reports</a></li>
                <li><a href="admin_profile.php">Admin Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>

        <div class="content">
            <h1>Sales Reports</h1>
            <p>Total Sales: $<?php echo number_format($total_sales, 2); ?></p>
            <h2>Sales by Product</h2>
            <table>
                <tr>
                    <th>Product</th>
                    <th>Quantity Sold</th>
                </tr>
                <?php while ($product_sale = $product_sales_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $product_sale['product_name']; ?></td>
                        <td><?php echo $product_sale['quantity_sold']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</body>
</html>
