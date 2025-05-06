<?php
// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure only admin can access
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

// Handle status update securely
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $order_id = intval($_POST['order_id']);
    $new_status = $_POST['status'];

    // Ensure the status is one of the allowed values
    $allowed_statuses = ['Pending', 'Shipped', 'Delivered', 'Cancelled'];
    if (!in_array($new_status, $allowed_statuses)) {
        die("<p class='error'>Invalid status selected!</p>");
    }

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    
    if ($stmt->execute()) {
        echo "<p class='success'>Order status updated successfully.</p>";
    } else {
        echo "<p class='error'>Error updating status: " . $conn->error . "</p>";
    }
    $stmt->close();
}

// Fetch orders
$orders_query = "SELECT o.id, o.status, o.order_date, u.username AS customer_name, p.name AS product_name, o.quantity, o.total_price 
                 FROM orders o 
                 JOIN users u ON o.buyer_id = u.id 
                 JOIN products p ON o.product_id = p.id 
                 ORDER BY o.order_date DESC";
$orders_result = $conn->query($orders_query);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Orders</title>
    <link rel="stylesheet" href="style.css">
</head>
<style>
/* Table Styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    padding: 10px;
    text-align: left;
    border: 1px solid #ddd;
}

/* Success & Error Messages */
.success {
    color: green;
    font-weight: bold;
}

.error {
    color: red;
    font-weight: bold;
}

/* Form Styling */
.status-form {
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Dropdown Styling */
.status-select {
    padding: 5px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

/* Update Button */
.btn-update {
    padding: 5px 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s ease-in-out;
}

/* Different colors for different statuses */
.status-pending {
    background-color: orange;
    color: white;
}

.status-shipped {
    background-color: blue;
    color: white;
}

.status-delivered {
    background-color: green;
    color: white;
}

.status-cancelled {
    background-color: red;
    color: white;
}
</style>
<body>
    <h1>Manage Orders</h1>

    <table border="1">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Order Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($order = $orders_result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($order['id']) ?></td>
                    <td><?= htmlspecialchars($order['customer_name']) ?></td>
                    <td><?= htmlspecialchars($order['product_name']) ?></td>
                    <td><?= htmlspecialchars($order['quantity']) ?></td>
                    <td><?= htmlspecialchars($order['total_price']) ?></td>
                    <td><?= htmlspecialchars($order['status']) ?></td>
                    <td><?= htmlspecialchars($order['order_date']) ?></td>
                    <td>
                        <form method="post" class="status-form">
                            <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['id']) ?>">
                            <select name="status" class="status-select">
                                <option value="Pending" <?= $order['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="Shipped" <?= $order['status'] == 'Shipped' ? 'selected' : '' ?>>Shipped</option>
                                <option value="Delivered" <?= $order['status'] == 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                                <option value="Cancelled" <?= $order['status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                            <button type="submit" name="update_status" 
                                class="btn-update 
                                <?= $order['status'] == 'Pending' ? 'status-pending' : '' ?>
                                <?= $order['status'] == 'Shipped' ? 'status-shipped' : '' ?>
                                <?= $order['status'] == 'Delivered' ? 'status-delivered' : '' ?>
                                <?= $order['status'] == 'Cancelled' ? 'status-cancelled' : '' ?>">
                                Update
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
