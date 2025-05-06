<?php
// Dashboard.php - Example of a tab content file

// Fetch user data
$user_id = $_SESSION['user_id'];
$user_data = getUserData($user_id); // You'll need to implement this function

// Fetch recent orders
$recent_orders = getRecentOrders($user_id, 5); // Get last 5 orders

// Fetch notifications
$notifications = getUserNotifications($user_id);

// Fetch recommended products
$recommended_products = getRecommendedProducts($user_id);
?>

<div class="dashboard-header">
    <h1>Welcome back, <?php echo htmlspecialchars($user_data['name']); ?>!</h1>
    <div class="notification-bell">
        <span class="notification-count"><?php echo count($notifications); ?></span>
    </div>
</div>

<!-- Quick Stats -->
<div class="quick-stats">
    <div class="stat-card">
        <h3>Active Orders</h3>
        <p class="stat-number"><?php echo $user_data['active_orders']; ?></p>
    </div>
    <div class="stat-card">
        <h3>Wishlist Items</h3>
        <p class="stat-number"><?php echo $user_data['wishlist_count']; ?></p>
    </div>
    <div class="stat-card">
        <h3>Cart Items</h3>
        <p class="stat-number"><?php echo $user_data['cart_count']; ?></p>
    </div>
    <div class="stat-card">
        <h3>Reward Points</h3>
        <p class="stat-number"><?php echo $user_data['reward_points']; ?></p>
    </div>
</div>

<!-- Recent Orders -->
<div class="section">
    <h2>Recent Orders</h2>
    <div class="orders-list">
        <?php foreach ($recent_orders as $order): ?>
            <div class="order-card">
                <div class="order-header">
                    <h3>Order #<?php echo htmlspecialchars($order['order_id']); ?></h3>
                    <span class="order-status <?php echo $order['status']; ?>">
                        <?php echo htmlspecialchars($order['status']); ?>
                    </span>
                </div>
                <div class="order-details">
                    <p>Date: <?php echo htmlspecialchars($order['date']); ?></p>
                    <p>Total: $<?php echo htmlspecialchars($order['total']); ?></p>
                </div>
                <a href="?tab=orders&order_id=<?php echo $order['order_id']; ?>" class="btn-view-details">
                    View Details
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Recommended Products -->
<div class="section">
    <h2>Recommended For You</h2>
    <div class="products-grid">
        <?php foreach ($recommended_products as $product): ?>
            <div class="product-card">
                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                <p class="price">$<?php echo htmlspecialchars($product['price']); ?></p>
                <button onclick="addToCart(<?php echo $product['id']; ?>)" class="btn-add-cart">
                    Add to Cart
                </button>
            </div>
        <?php endforeach; ?>
    </div>
</div>