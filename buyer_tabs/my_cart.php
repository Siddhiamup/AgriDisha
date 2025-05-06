<?php
// cart_tab.php - This will be included by index.php when cart tab is active

// Check if user is logged in and is a buyer
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'Buyer') {
    // Store error message in session
    $_SESSION['error'] = "You must be logged in as a buyer to view your cart.";
    // Redirect to login page
    header("Location: login.php?redirect=index.php?tab=cart");
    exit;
}

// Connect to database
include 'db.php';
$buyer_id = $_SESSION['id'];

// Get cart items with product details
$query = "SELECT c.*,p.id,p.name, p.price, p.unit, p.image_url, p.quantity as available_quantity, 
          u.username as seller_name, p.seller_id 
          FROM cart c 
          JOIN products p ON c.product_id = p.id 
          JOIN users u ON p.seller_id = u.id 
          WHERE c.buyer_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $buyer_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Initialize total
$total = 0;
?>

<!-- Custom CSS for cart page -->
<style>
.cart-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.cart-title {
    font-size: 1.8rem;
    margin-bottom: 1.5rem;
    color: #333;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 10px;
}

.cart-empty {
    text-align: center;
    padding: 50px 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.cart-empty-icon {
    font-size: 3rem;
    color: #ccc;
    margin-bottom: 15px;
}

.cart-card {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.cart-items {
    padding: 20px;
}

.cart-table {
    width: 100%;
    border-collapse: collapse;
}

.cart-table th {
    background-color: #f5f5f5;
    padding: 12px 15px;
    text-align: left;
    font-weight: 600;
    color: #555;
    border-bottom: 1px solid #ddd;
}

.cart-table td {
    padding: 15px;
    vertical-align: middle;
    border-bottom: 1px solid #eee;
}

.product-info {
    display: flex;
    align-items: center;
}

.product-image {
    width: 70px;
    height: 70px;
    object-fit: cover;
    border-radius: 4px;
    margin-right: 15px;
    border: 1px solid #eee;
}

.quantity-form {
    display: flex;
    align-items: center;
}

.quantity-input {
    width: 70px;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-right: 10px;
    text-align: center;
}

.btn-update {
    padding: 6px 12px;
    background-color: #f8f9fa;
    border: 1px solid #ddd;
    border-radius: 4px;
    color: #555;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-update:hover {
    background-color: #e9ecef;
    border-color: #ccc;
}

.btn-remove {
    padding: 6px 12px;
    background-color: #fff;
    border: 1px solid #dc3545;
    border-radius: 4px;
    color: #dc3545;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-remove:hover {
    background-color: #dc3545;
    color: #fff;
}

.cart-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.btn-continue-shopping {
    padding: 10px 20px;
    background-color: #f8f9fa;
    border: 1px solid #ddd;
    border-radius: 4px;
    color: #555;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-continue-shopping:hover {
    background-color: #e9ecef;
    border-color: #ccc;
}

.btn-checkout {
    padding: 10px 25px;
    background-color: #28a745;
    border: none;
    border-radius: 4px;
    color: #fff;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-checkout:hover {
    background-color: #218838;
}

/* Alert Styles */
.alert {
    padding: 12px 15px;
    margin-bottom: 20px;
    border-radius: 4px;
}

.alert-success {
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

.alert-danger {
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}

.alert-warning {
    background-color: #fff3cd;
    border: 1px solid #ffeeba;
    color: #856404;
}

.alert-info {
    background-color: #d1ecf1;
    border: 1px solid #bee5eb;
    color: #0c5460;
}

/* Cart badge styles */
.cart-badge {
    position: absolute;
    top: -8px;
    right: -8px;
    background-color: #dc3545;
    color: white;
    border-radius: 50%;
    padding: 0.2rem 0.5rem;
    font-size: 0.75rem;
}

/* Responsive styles */
@media (max-width: 768px) {
    .cart-table {
        display: block;
        overflow-x: auto;
    }
    
    .product-image {
        width: 50px;
        height: 50px;
    }
    
    .cart-actions {
        flex-direction: column;
        gap: 15px;
    }
    
    .btn-continue-shopping, .btn-checkout {
        width: 100%;
        text-align: center;
    }
}
</style>

<div class="cart-container">
    <h1 class="cart-title">Your Shopping Cart</h1>
    
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php 
            echo $_SESSION['success']; 
            unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php 
            echo $_SESSION['error']; 
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['warning'])): ?>
        <div class="alert alert-warning">
            <?php 
            echo $_SESSION['warning']; 
            unset($_SESSION['warning']);
            ?>
        </div>
    <?php endif; ?>
    <?php
if (isset($_GET['success'])) {
    echo "<p style='color: green;'>" . htmlspecialchars($_GET['success']) . "</p>";
}
if (isset($_GET['error'])) {
    echo "<p style='color: red;'>" . htmlspecialchars($_GET['error']) . "</p>";
}
?>

    
    <?php if(mysqli_num_rows($result) == 0): ?>
        <div class="cart-empty">
            <i class="bi bi-cart3 cart-empty-icon"></i>
            <p>Your cart is empty.</p>
            <a href="index.php?tab=buy" class="btn-continue-shopping">Continue shopping</a>
        </div>
    <?php else: ?>
        <div class="cart-card">
            <div class="cart-items">
                <div class="table-responsive">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Seller</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $sellers = [];
                            while($item = mysqli_fetch_assoc($result)): 
                                $subtotal = $item['price'] * $item['quantity'];
                                $total += $subtotal;
                                
                                // Track sellers for grouped checkout
                                if (!isset($sellers[$item['seller_id']])) {
                                    $sellers[$item['seller_id']] = [
                                        'name' => $item['seller_name'],
                                        'items' => []
                                    ];
                                }
                                $sellers[$item['seller_id']]['items'][] = $item['id'];
                            ?>
                                <tr>
                                    <td>
                                        <div class="product-info">
                                            <?php if(!empty($item['image_url'])): ?>
                                                <img src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                                                    alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                                    class="product-image">
                                            <?php else: ?>
                                                <img src="ASSETS/IMAGES/default-product.jpg" 
                                                    alt="Default product image" 
                                                    class="product-image">
                                            <?php endif; ?>
                                            <div>
                                                <h6><?php echo htmlspecialchars($item['name']); ?></h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($item['seller_name']); ?></td>
                                    <td>₹<?php echo htmlspecialchars($item['price']); ?> / <?php echo htmlspecialchars($item['unit']); ?></td>
                                    <td>
                                        <form action="update_cart.php" method="POST" class="quantity-form">
                                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                            <input type="hidden" name="redirect" value="bdemo.php?tab=my_cart">
                                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" 
                                                min="1" max="<?php echo $item['available_quantity']; ?>" 
                                                class="quantity-input">
                                            <button type="submit" class="btn-update">Update</button>
                                        </form>
                                    </td>
                                    <td>₹<?php echo number_format($subtotal, 2); ?></td>
                                    <td>
                                        <form action="remove_from_cart.php" method="POST">
                                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                            <input type="hidden" name="redirect" value="bdemo.php?tab=my_cart">
                                            <button type="submit" class="btn-remove">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Total:</td>
                                <td colspan="2" class="fw-bold">₹<?php echo number_format($total, 2); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <div class="cart-actions">
                    <a href="bdemo.php?tab=browse_products" class="btn-continue-shopping">Continue Shopping</a>
                    <a href="checkout.php" class="btn-checkout">Proceed to Checkout</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>