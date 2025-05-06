<?php
// Include database connection
// include '../db.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


// Redirect if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php?redirect=my_orders.php");
    exit;
}

// Get user's ID
$user_id = $_SESSION['id'];
$user_type = $_SESSION['user_type'] ?? 'Buyer';

// Fetch user's orders
$orders = [];

if ($user_type == 'Buyer') {
    // Fetch orders for buyer
    $query = "
        SELECT 
            o.id AS order_id, 
            o.quantity, 
            o.total_price, 
            o.order_date, 
            o.status,
            p.name AS product_name,
            p.image_url,
            u.username AS seller_name,
            t.payment_method,
            t.payment_status
        FROM orders o
        JOIN products p ON o.product_id = p.id
        JOIN users u ON p.seller_id = u.id
        LEFT JOIN transactions t ON o.id = t.order_id
        WHERE o.buyer_id = ?
        ORDER BY o.order_date DESC
    ";
    
    // Prepare and execute statement
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
} else if ($user_type == 'Farmer') {
    // This page is already implemented in my_orders.php for farmers
    header("Location: my_orders.php");
    exit;
}

$stmt->execute();
$result = $stmt->get_result();

// Fetch orders
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<h1>My Orders</h1>
<?php
// Add this at the top of my-orders.php
// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?><?php
// Display success message if exists
if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
    unset($_SESSION['success']); // Clear the message after displaying
}

// Display error message if exists
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']); // Clear the message after displaying
}
?>       
      
        <?php if (empty($orders)): ?>
            <div class="no-orders">
                <p>You haven't placed any orders yet.</p>
                <a href="marketplace.php" class="btn btn-primary">Browse Products</a>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Product</th>
                            <th>Seller</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?php echo $order['order_id']; ?></td>
                            <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($order['seller_name']); ?></td>
                            <td><?php echo $order['quantity']; ?></td>
                            <td>₹<?php echo number_format($order['total_price'], 2); ?></td>
                            <td><?php echo date('M j, Y', strtotime($order['order_date'])); ?></td>
                            <td>
                                <span class="status-<?php echo strtolower($order['status']); ?>">
                                    <?php echo htmlspecialchars($order['status']); ?>
                                </span>
                            </td>
                            <td>
                                <form action="./buyer_tabs/track_order.php" method="GET" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                    <button type="submit" class="btn btn-info">Track Order</button>
                                </form>
                                <?php if ($order['status'] == 'Pending'): ?>
                                    <form action="/AMSDemo/buyer_tabs/cancel_order.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this order?');">Cancel Order</button>
                                    </form>
                                <?php endif; ?>
                                <?php if ($order['status'] == 'Delivered'): ?>
                                    <a href="write_review.php?order_id=<?php echo $order['order_id']; ?>" class="btn btn-success">Write Review</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
 
    
</body>
</html>