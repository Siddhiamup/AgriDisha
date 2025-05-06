<?php
//include('config.php');
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


// Check if the user is an admin
// session_start();
// if ($_SESSION['user_role'] != 'admin') {
//     header('Location: index.php');
//     exit();
// }

$sql_products = "SELECT * FROM products";
$result_products = $conn->query($sql_products);

$sql_orders = "SELECT * FROM orders";
$result_orders = $conn->query($sql_orders);

$sql_users = "SELECT * FROM users";
$result_users = $conn->query($sql_users);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="Demo/style.css">
</head>
<body>

<header>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="admin.php">Admin Dashboard</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<section class="admin-dashboard">
    <h2>Admin Dashboard</h2>

    <div class="admin-section">
        <h3>Manage Products</h3>
        <ul>
            <?php while ($product = $result_products->fetch_assoc()) { ?>
                <li><?php echo $product['name']; ?> - <a href="edit-product.php?id=<?php echo $product['id']; ?>">Edit</a></li>
            <?php } ?>
        </ul>
    </div>

    <div class="admin-section">
        <h3>Manage Orders</h3>
        <ul>
            <?php while ($order = $result_orders->fetch_assoc()) { ?>
                <li>Order #<?php echo $order['id']; ?> - Status: <?php echo $order['status']; ?></li>
            <?php } ?>
        </ul>
    </div>

    <div class="admin-section">
        <h3>Manage Users</h3>
        <ul>
            <?php while ($user = $result_users->fetch_assoc()) { ?>
                <li><?php echo $user['username']; ?> - <a href="edit-user.php?id=<?php echo $user['id']; ?>">Edit</a></li>
            <?php } ?>
        </ul>
    </div>
</section>

</body>
</html>
