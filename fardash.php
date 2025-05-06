<?php
// Get dashboard statistics
$farmer_id = $_SESSION['id'];

// Corrected query to properly join payments and orders
$stats_query = "SELECT 
    (SELECT COUNT(*) FROM products WHERE seller_id = ?) as total_products,
    (SELECT COUNT(*) FROM orders WHERE seller_id = ? AND status = 'pending') as active_orders,
    (SELECT COALESCE(SUM(total_price), 0) FROM orders WHERE seller_id = ? AND status = 'completed') as total_revenue,
    (SELECT COALESCE(SUM(t.amount), 0) 
     FROM transactions t 
     JOIN orders o ON t.order_id = o.id 
     WHERE o.seller_id = ? AND t.payment_status = 'pending') as pending_payments";

$stmt = $conn->prepare($stats_query);
$stmt->bind_param("iiii", $farmer_id, $farmer_id, $farmer_id, $farmer_id);
$stmt->execute();
$result = $stmt->get_result();
$stats = $result->fetch_assoc();
?>

<div class="dashboard-cards">
    <div class="card">
        <h3>Total Products</h3>
        <p><?php echo $stats['total_products']; ?></p>
    </div>
    <div class="card">
        <h3>Active Orders</h3>
        <p><?php echo $stats['active_orders']; ?></p>
    </div>
    <div class="card">
        <h3>Total Revenue</h3>
        <p>₹<?php echo number_format($stats['total_revenue'], 2); ?></p>
    </div>
    <div class="card">
        <h3>Pending Payments</h3>
        <p>₹<?php echo number_format($stats['pending_payments'], 2); ?></p>
    </div>
</div>

<div class="table-container">
    <h2>Recent Orders</h2>
    <?php
    // Modified to show recent orders instead of activity log since there's no activity_log table
    $orders_query = "SELECT o.*, p.name as product_name, u.username as buyer_name 
                    FROM orders o
                    JOIN products p ON o.product_id = p.id
                    JOIN users u ON o.buyer_id = u.id
                    WHERE o.seller_id = ? 
                    ORDER BY o.created_at DESC LIMIT 10";
    $stmt = $conn->prepare($orders_query);
    $stmt->bind_param("i", $farmer_id);
    $stmt->execute();
    $orders = $stmt->get_result();
    ?>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Product</th>
                <th>Buyer</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($order = $orders->fetch_assoc()): ?>
            <tr>
                <td><?php echo date('Y-m-d', strtotime($order['created_at'])); ?></td>
                <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                <td><?php echo htmlspecialchars($order['buyer_name']); ?></td>
                <td>₹<?php echo number_format($order['total_price'], 2); ?></td>
                <td><?php echo htmlspecialchars($order['status']); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>