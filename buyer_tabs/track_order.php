<?php
// Include database connection
// require_once 'db.php';
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


session_start();

// Redirect if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php?redirect=track_order.php");
    exit;
}

$user_id = $_SESSION['id'];
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

// Validate order_id
if ($order_id <= 0) {
    header("Location: my_orders.php");
    exit;
}
// Get order details
$query = "
    SELECT 
        o.id AS order_id, 
        o.quantity, 
        o.total_price, 
        o.order_date, 
        o.status,
        p.name AS product_name,
        p.description AS product_description,
        p.image_url,
        u.username AS seller_name,
        u.phone AS seller_phone,
        t.payment_method,
        t.payment_status,
        t.transaction_date
    FROM orders o
    JOIN products p ON o.product_id = p.id
    JOIN users u ON p.seller_id = u.id
    LEFT JOIN transactions t ON o.id = t.order_id
    WHERE o.id = ? AND (o.buyer_id = ? OR p.seller_id = ?)
";

// Prepare and execute statement
$stmt = $conn->prepare($query);
$stmt->bind_param("iii", $order_id, $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if order exists and belongs to this user (either as buyer or seller)
if ($result->num_rows == 0) {
    header("Location: my_orders.php");
    exit;
}

$order = $result->fetch_assoc();

// Get current tracking info
$current_tracking = null;
$current_query = "
    SELECT 
        status,
        update_time,
        location,
        notes
    FROM order_tracking
    WHERE order_id = ? AND is_current = 1
";

$current_stmt = $conn->prepare($current_query);
$current_stmt->bind_param("i", $order_id);
$current_stmt->execute();
$current_result = $current_stmt->get_result();

if ($current_result && $current_result->num_rows > 0) {
    $current_tracking = $current_result->fetch_assoc();
}

// Get tracking history
$tracking_history = [];
$history_query = "
    SELECT 
        status,
        update_time,
        location,
        notes
    FROM order_tracking
    WHERE order_id = ?
    ORDER BY update_time DESC
";

$history_stmt = $conn->prepare($history_query);
$history_stmt->bind_param("i", $order_id);
$history_stmt->execute();
$history_result = $history_stmt->get_result();

if ($history_result) {
    while ($row = $history_result->fetch_assoc()) {
        $tracking_history[] = $row;
    }
}

// Calculate estimated delivery date (if not yet delivered)
$estimated_delivery = null;
if ($order['status'] != 'Delivered' && $order['status'] != 'Cancelled') {
    // Estimate 5 days from order date for delivery
    $estimated_delivery = date('Y-m-d', strtotime($order['order_date'] . ' + 5 days'));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Order #<?php echo $order_id; ?></title>
    <!-- <link rel="stylesheet" href="css/styles.css"> -->
    <!-- <link rel="stylesheet" href="/ASSETS/CSS/track-order.css"> -->

    <style>
        /* Track Order Page Styles */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    line-height: 1.6;
    color: #333;
    background-color:whitesmoke;
    margin: 0;
    padding: 0;
}

.container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
   /* background-color: #27ae60;  */

}

.tracking-container {
    max-width: 900px;
    margin: 30px auto;
    background: #fff;
    background-color:#27ae60;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 30px;
    
}

h1 {
    color: #2c3e50;
    text-align: center;
    margin-bottom: 30px;
    font-size: 28px;
    border-bottom: 2px solid #eaeaea;
    padding-bottom: 15px;
}

.order-details {
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 30px;
    padding: 25px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    background-color: #f9f9f9;
    box-shadow: 0 1px 5px rgba(0, 0, 0, 0.05);
}

.product-image {
    width: 220px;
    margin-right: 25px;
}

.product-image img {
    max-width: 100%;
    border-radius: 6px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    object-fit: cover;
    height: 200px;
}

.order-info {
    flex: 1;
    min-width: 300px;
}

.order-info h2 {
    color: #2c3e50;
    margin-top: 0;
    font-size: 22px;
    margin-bottom: 15px;
}

.order-info p {
    margin: 8px 0;
    font-size: 15px;
}

.order-info strong {
    font-weight: 600;
    color: #555;
}

.tracking-status {
    margin-top: 35px;
    padding: 25px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    background-color: #fff;
    box-shadow: 0 1px 5px rgba(0, 0, 0, 0.05);
}

.tracking-status h2 {
    color: #2c3e50;
    margin-top: 0;
    font-size: 22px;
    margin-bottom: 20px;
    border-bottom: 1px solid #eaeaea;
    padding-bottom: 10px;
}

.tracking-timeline {
    margin-top: 35px;
    position: relative;
    padding: 25px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    background-color: #fff;
    box-shadow: 0 1px 5px rgba(0, 0, 0, 0.05);
}

.tracking-timeline h2 {
    color: #2c3e50;
    margin-top: 0;
    font-size: 22px;
    margin-bottom: 20px;
    border-bottom: 1px solid #eaeaea;
    padding-bottom: 10px;
}

.timeline-item {
    padding: 18px 25px;
    border-left: 3px solid #4CAF50;
    position: relative;
    margin-left: 25px;
    margin-bottom: 25px;
    background-color: #f9f9f9;
    border-radius: 0 8px 8px 0;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.timeline-item:before {
    content: '';
    width: 15px;
    height: 15px;
    background: #4CAF50;
    border-radius: 50%;
    position: absolute;
    left: -9px;
    top: 22px;
    box-shadow: 0 0 0 4px rgba(76, 175, 80, 0.2);
}

.timeline-item h3 {
    margin-top: 0;
    margin-bottom: 8px;
    color: #2c3e50;
    font-size: 18px;
}

.timeline-date {
    color: #777;
    font-size: 0.9em;
    margin-bottom: 10px;
    display: block;
}

.timeline-item p {
    margin: 8px 0;
    color: #555;
}

/* Status Colors */
.status-pending { color: #FFC107; font-weight: 600; }
.status-processing { color: #2196F3; font-weight: 600; }
.status-shipped { color: #9C27B0; font-weight: 600; }
.status-delivered { color: #4CAF50; font-weight: 600; }
.status-cancelled { color: #F44336; font-weight: 600; }

.delivery-confirmation {
    background-color: #e8f5e9;
    border-left: 4px solid #4CAF50;
    padding: 15px;
    margin-top: 20px;
    border-radius: 4px;
}

.delivery-confirmation h3 {
    color: #2e7d32;
    margin-top: 0;
    margin-bottom: 8px;
}

.action-buttons {
    margin-top: 30px;
    text-align: center;
}

.btn {
    display: inline-block;
    padding: 10px 20px;
    margin: 0 5px 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 15px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-primary {
    background-color: white;
    color: #2c3e50;
}

.btn-primary:hover {
    background-color:rgb(129, 241, 167);
}

.btn-danger {
    background-color: #e74c3c;
    color: white;
}

.btn-danger:hover {
    background-color: #c0392b;
}

.btn-success {
    background-color: #2ecc71;
    color: white;
}

.btn-success:hover {
    background-color: #27ae60;
}

.btn-info {
    background-color: #9b59b6;
    color: white;
}

.btn-info:hover {
    background-color: #8e44ad;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .tracking-container {
        padding: 20px 15px;
    }
    
    .order-details {
        flex-direction: column;
    }
    
    .product-image {
        width: 100%;
        margin-right: 0;
        margin-bottom: 20px;
        text-align: center;
    }
    
    .product-image img {
        max-width: 250px;
    }
    
    .timeline-item {
        margin-left: 15px;
        padding: 15px;
    }
}
    </style>

    
</head>
<body>
    
    <div class="container tracking-container">
        <h1>Order #<?php echo $order_id; ?> Tracking</h1>
        
        <div class="order-details">
            <div class="product-image">
                <?php if (!empty($order['image_url'])): ?>
                    <img src="/AgriDisha/<?= htmlspecialchars($order['image_url']) ?>
                    " alt="<?php echo htmlspecialchars($order['product_name']); ?>">
                <?php else: ?>
                    <img src="images/product-placeholder.jpg" alt="Product Image">
                <?php endif; ?>
            </div>
            
            <div class="order-info">
                <h2><?php echo htmlspecialchars($order['product_name']); ?></h2>
                <p><strong>Quantity:</strong> <?php echo $order['quantity']; ?></p>
                <p><strong>Total Price:</strong> ₹<?php echo number_format($order['total_price'], 2); ?></p>
                <p><strong>Order Date:</strong> <?php echo date('F j, Y, g:i a', strtotime($order['order_date'])); ?></p>
                <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($order['payment_method'] ?? 'N/A'); ?></p>
                <p><strong>Payment Status:</strong> <?php echo htmlspecialchars($order['payment_status'] ?? 'N/A'); ?></p>
                <p><strong>Status:</strong> <span class="status-<?php echo strtolower($order['status']); ?>"><?php echo htmlspecialchars($order['status']); ?></span></p>
                
                <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'Buyer'): ?>
                    <p><strong>Seller:</strong> <?php echo htmlspecialchars($order['seller_name']); ?></p>
                    <p><strong>Contact:</strong> <?php echo htmlspecialchars($order['seller_phone']); ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="tracking-status">
            <h2>Current Status</h2>
            
            <?php if ($current_tracking): ?>
                <p><strong>Status:</strong> <span class="status-<?php echo strtolower($current_tracking['status']); ?>"><?php echo htmlspecialchars($current_tracking['status']); ?></span></p>
                <p><strong>Last Updated:</strong> <?php echo date('F j, Y, g:i a', strtotime($current_tracking['update_time'])); ?></p>
                
                <?php if (!empty($current_tracking['location'])): ?>
                    <p><strong>Current Location:</strong> <?php echo htmlspecialchars($current_tracking['location']); ?></p>
                <?php endif; ?>
                
                <?php if (!empty($current_tracking['notes'])): ?>
                    <p><strong>Notes:</strong> <?php echo htmlspecialchars($current_tracking['notes']); ?></p>
                <?php endif; ?>
            <?php else: ?>
                <p>No tracking information available yet.</p>
            <?php endif; ?>
            
            <?php if ($estimated_delivery): ?>
                <p><strong>Estimated Delivery:</strong> <?php echo date('F j, Y', strtotime($estimated_delivery)); ?></p>
            <?php endif; ?>
            
            <?php if ($order['status'] == 'Delivered'): ?>
                <div class="delivery-confirmation">
                    <h3>Order Delivered</h3>
                    <p>Your order has been successfully delivered. Thank you for shopping with us!</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="tracking-timeline">
            <h2>Tracking History</h2>
            
            <?php if (empty($tracking_history)): ?>
                <p>No tracking updates available yet.</p>
            <?php else: ?>
                <?php foreach ($tracking_history as $history): ?>
                    <div class="timeline-item">
                        <h3><?php echo htmlspecialchars($history['status']); ?></h3>
                        <div class="timeline-date"><?php echo date('F j, Y, g:i a', strtotime($history['update_time'])); ?></div>
                        <?php if (!empty($history['location'])): ?>
                            <p><strong>Location:</strong> <?php echo htmlspecialchars($history['location']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($history['notes'])): ?>
                            <p><?php echo htmlspecialchars($history['notes']); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="action-buttons">
            <a href="/AgriDisha/bdemo.php?tab=my_orders" class="btn btn-primary">Back to Orders</a>
            
            <?php if ($order['status'] == 'Pending' && isset($_SESSION['user_type'])): ?>
                <?php if ($_SESSION['user_type'] == 'Buyer'): ?>
                    <form action="cancel_order.php" method="POST" style="display:inline;">
                        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this order?');">Cancel Order</button>
                    </form>
                <?php elseif ($_SESSION['user_type'] == 'Farmer'): ?>
                    <form action="update_order_status.php" method="POST" style="display:inline;">
                        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                        <input type="hidden" name="status" value="Processing">
                        <button type="submit" class="btn btn-success">Accept Order</button>
                    </form>
                    <form action="cancel_order.php" method="POST" style="display:inline;">
                        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this order?');">Reject Order</button>
                    </form>
                <?php endif; ?>
            <?php endif; ?>
            
            <?php if ($order['status'] == 'Delivered' && isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'Buyer'): ?>
                <a href="write_review.php?order_id=<?php echo $order_id; ?>" class="btn btn-info">Write Review</a>
            <?php endif; ?>
        </div>
    </div>
    
</body>
</html>