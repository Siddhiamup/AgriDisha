<?php
// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Connect to database
include 'db.php';

// Get categories for filter
$category_query = "SELECT DISTINCT category FROM products WHERE in_stock = 1 ORDER BY category";
$category_result = mysqli_query($conn, $category_query);

// Handle filtering
$where_clause = "WHERE in_stock = 1";
$search_param = '';
$category_param = '';

if (isset($_GET['category']) && !empty($_GET['category'])) {
    $category = mysqli_real_escape_string($conn, $_GET['category']);
    $where_clause .= " AND category = '$category'";
    $category_param = $category;
}

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $where_clause .= " AND (name LIKE '%$search%' OR description LIKE '%$search%')";
    $search_param = $search;
}

// Fetch products from the database with additional details
$product_query = "SELECT p.*, u.username as seller_name
 FROM products p 
 JOIN users u ON p.seller_id = u.id 
 $where_clause ORDER BY created_at DESC";
$result = mysqli_query($conn, $product_query);

// Get user's wishlist if logged in
$wishlist_items = [];
if (isset($_SESSION['id'])) {
    $buyer_id = $_SESSION['id'];
    $wishlist_query = "SELECT product_id FROM wishlist WHERE buyer_id = '$buyer_id'";
    $wishlist_result = $conn->query($wishlist_query);
    
    if ($wishlist_result && $wishlist_result->num_rows > 0) {
        while ($item = $wishlist_result->fetch_assoc()) {
            $wishlist_items[] = $item['product_id'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Products - AgroDisha</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
 
</head>
<body>
    <div class="products-container">
        <div class="header-section">
            <!-- <h1>AgroDisha Marketplace</h1>
            <p class="lead">Browse and purchase quality agricultural products directly from farmers across India.</p> -->
            
<!-- Filters Section -->
<div class="filter-section">
    <div class="row">
        <!-- Search Bar -->
        <div class="col-12 col-md-6">
            <div class="search-wrapper">
                <form action="" method="GET" class="d-flex align-items-center">
                    <input type="hidden" name="tab" value="browse_products">
                    
                    <!-- Category Parameter Preservation -->
                    <?php if(isset($_GET['category']) && !empty($_GET['category'])): ?>
                        <input type="hidden" name="category" value="<?php echo htmlspecialchars($category_param); ?>">
                    <?php endif; ?>
                    
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search products, categories..." 
                               value="<?php echo htmlspecialchars($search_param); ?>">
                        <?php if(!empty($search_param)): ?>
                            <a href="?tab=browse_products<?php echo !empty($category_param) ? '&category='.urlencode($category_param) : ''; ?>" 
                               class="btn-outline-secondary">
                                <i class="fas fa-times-circle"></i>
                            </a>
                        <?php endif; ?>
                        <button class="btn btn-success" type="submit">Search</button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Category Dropdown -->
        <div class="col-12 col-md-6">
            <form action="" method="GET">
                <input type="hidden" name="tab" value="browse_products">
                
                <!-- Search Parameter Preservation -->
                <?php if(!empty($search_param)): ?>
                    <input type="hidden" name="search" value="<?php echo htmlspecialchars($search_param); ?>">
                <?php endif; ?>
                
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-list"></i>
                    </span>
                    <select name="category" class="form-control" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        <?php 
                        // Reset the pointer for category_result
                        mysqli_data_seek($category_result, 0);
                        while($cat = mysqli_fetch_assoc($category_result)): 
                        ?>
                            <option value="<?php echo htmlspecialchars($cat['category']); ?>" 
                                    <?php echo $category_param == $cat['category'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['category']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <?php if(!empty($category_param)): ?>
                        <a href="?tab=browse_products<?php echo !empty($search_param) ? '&search='.urlencode($search_param) : ''; ?>" 
                           class="btn-outline-secondary">
                            <i class="fas fa-times-circle"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Active Filters Display -->
    <?php if(!empty($search_param) || !empty($category_param)): ?>
        <div class="active-filters">
            <span class="text-muted">Active Filters:</span>
            <?php if(!empty($search_param)): ?>
                <span class="badge bg-primary">
                    Search: <?php echo htmlspecialchars($search_param); ?>
                    <a href="?tab=browse_products<?php echo !empty($category_param) ? '&category='.urlencode($category_param) : ''; ?>">
                        <i class="fas fa-times-circle"></i>
                    </a>
                </span>
            <?php endif; ?>
            <?php if(!empty($category_param)): ?>
                <span class="badge bg-success">
                    Category: <?php echo htmlspecialchars($category_param); ?>
                    <a href="?tab=browse_products<?php echo !empty($search_param) ? '&search='.urlencode($search_param) : ''; ?>">
                        <i class="fas fa-times-circle"></i>
                    </a>
                </span>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>        
        <!-- Success/Error Messages -->
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

        
        <!-- Products Display -->
        <div class="product-grid">
            <?php 
            if ($result && $result->num_rows > 0) {
                while ($product = $result->fetch_assoc()) { 
                    $stockStatus = $product['quantity'] > 10 ? 'In Stock' : ($product['quantity'] > 0 ? 'Low Stock' : 'Out of Stock');
                    $stockClass = $product['quantity'] > 10 ? 'in-stock' : ($product['quantity'] > 0 ? 'low-stock' : 'out-of-stock');
                    $isInWishlist = in_array($product['id'], $wishlist_items);
            ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="<?php echo !empty($product['image_url']) ? htmlspecialchars($product['image_url']) : 'ASSETS/IMAGES/default-product.jpg'; ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>">
                        
                        <?php if($isInWishlist): ?>
                            <div class="wishlist-badge">
                                <i class="fas fa-heart"></i>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($product['organic_certified']): ?>
                            <div class="organic-badge">
                                <i class="fas fa-leaf"></i> Organic
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="product-info">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        
                        <?php if(!empty($product['description'])): ?>
                            <p class="description"><?php echo htmlspecialchars($product['description']); ?></p>
                        <?php endif; ?>
                        
                        <div class="badge-container">
                            <span class="category-badge">
                                <i class="fas fa-tag"></i> <?php echo htmlspecialchars($product['category']); ?>
                            </span>
                        </div>
                        
                        <p class="seller-info">
                            <i class="fas fa-user"></i> Seller: <?php echo htmlspecialchars($product['seller_name']); ?>
                        </p>
                        
                        <div class="price-section">
                            <span class="price">
                                ₹<?php echo htmlspecialchars($product['price']); ?> <span class="unit-text">/ <?php echo htmlspecialchars($product['unit']); ?></span>
                            </span>
                            <span class="quantity-badge">
                                <?php echo htmlspecialchars($product['quantity']) . ' ' . htmlspecialchars($product['unit']); ?> available
                            </span>
                        </div>
                        
                        <div class="stock-status <?php echo $stockClass; ?>">
                            <i class="fas fa-circle"></i> <?php echo $stockStatus; ?>
                        </div>
                        
                        <div class="minimum-qty-warning">
                            <i class="fas fa-info-circle"></i> Minimum order: 15kg
                        </div>
                    </div>
                    
                    <div class="product-actions">
                        <a href="product_details.php?id=<?php echo $product['id']; ?>" class="btn view-details">
                            <i class="fas fa-info-circle"></i> View Details
                        </a>
                        
                        <div class="primary-action">
                        <form action="add_to_cart.php" method="POST">
                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                        <input type="hidden" name="quantity" value="<?php echo ceil(15 / (preg_match('/kg/i', $product['unit']) ? 1 : 0.001)); ?>">
                                        <input type="hidden" name="return_url" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">                                        <button type="submit" class="btn btn-success add-to-cart-btn">Add to Cart</button>
                                    </form>
                            
                            <form action="add_to_wishlist.php" method="POST">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <input type="hidden" name="redirect" value="bdemo.php?tab=browse_products">
                                <button type="submit" class="btn wishlist-btn <?php echo $isInWishlist ? 'active' : ''; ?>">
                                    <i class="<?php echo $isInWishlist ? 'fas fa-heart' : 'far fa-heart'; ?>"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
            <?php 
                }
            } else {
                echo '<div class="no-products">
                    <i class="fas fa-info-circle"></i>
                    No products available at this moment. Please check back later or try different search criteria.
                </div>';
            }
            ?>
            
        </div>
    </div>
    
   
<!-- Toast for cart notification -->

<div class="custom-toast-container">
    <div class="custom-toast" id="cartToast">
        <div class="custom-toast-header">
            <i class="fas fa-shopping-cart"></i>
            <strong>Agridisha</strong>
            <button type="button" class="custom-toast-close" onclick="document.getElementById('cartToast').classList.remove('show');">&times;</button>
        </div>
        <div class="custom-toast-body">
            Product added to cart successfully!
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check for success messages in PHP session
    <?php if(isset($_SESSION['cart_success'])): ?>
    // Show the custom toast
    var cartToast = document.getElementById('cartToast');
    cartToast.classList.add('show');
    
    // Auto hide after 3 seconds
    setTimeout(function() {
        cartToast.classList.remove('show');
    }, 3000);
    
    // Animate the cart icon if present
    const cartBadge = document.querySelector('.cart-badge');
    if (cartBadge) {
        cartBadge.style.animation = 'none';
        setTimeout(() => {
            cartBadge.style.animation = 'pulse 0.5s forwards';
        }, 10);
    }
    <?php 
    // Clear the session variable after using it
    unset($_SESSION['cart_success']); 
    endif; 
    ?>
    
    // Add click event listener for add to cart buttons
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Just let the form submission handle this
        });
    });
});
</script>
</body>
<style>
/* Global Styles */
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: 'Poppins', sans-serif;
    background-color: #f5f7fa;
    color: #333;
    line-height: 1.4;
    width: 100%;
    max-width: 100%;
    overflow-x: hidden;
}

.products-container {
    width: 100%;
    max-width: 100%;
    margin: 0 auto;
    padding: 15px;
}

/* Header Section */
.header-section {
    text-align: center;
    margin-bottom: 20px;
    background-color: #fff;
    padding: 18px;
    border-radius: 10px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
}

.header-section h1 {
    color: #2e7d32;
    margin-bottom: 8px;
    font-size: 1.8rem;
    font-weight: 700;
}

.lead {
    font-size: 0.95rem;
    color: #666;
    margin-bottom: 16px;
}

  /* Filter Section */
 /* Filter Section Improvements */
.filter-section {
  background-color: white;
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 20px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
}

.filter-section .row {
  display: flex;
  flex-wrap: wrap;
  gap: 15px;
}

.filter-section .col-12 {
  flex: 1;
  min-width: 250px;
}

.search-wrapper {
  width: 100%;
}

.input-group {
  display: flex;
  align-items: center;
  border: 1px solid #ddd;
  border-radius: 4px;
  overflow: hidden;
}

.input-group-text {
  display: flex;
  align-items: center;
  padding: 8px 12px;
  background-color: white;
  border: none;
}

.input-group .form-control {
  flex: 1;
  padding: 10px 15px;
  border: none;
  outline: none;
}

.input-group .btn-outline-secondary {
  background: none;
  border: none;
  color: #777;
  padding: 0 10px;
  font-size: 16px;
  cursor: pointer;
}

.input-group .btn-outline-secondary:hover {
  color: #333;
}

.input-group .btn-success {
  background-color: #2e7d32;
  color: white;
  border: none;
  padding: 10px 15px;
  transition: background-color 0.2s;
}

.input-group .btn-success:hover {
  background-color: #226a25;
}

/* Active Filters */
.active-filters {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  margin-top: 12px;
  gap: 8px;
}

.active-filters .text-muted {
  font-size: 0.85rem;
}

.active-filters .badge {
  display: flex;
  align-items: center;
  padding: 6px 12px;
  border-radius: 20px;
  font-weight: 500;
  font-size: 0.85rem;
}

.active-filters .badge a {
  display: inline-flex;
  align-items: center;
  margin-left: 6px;
}

.active-filters .badge a i {
  font-size: 14px;
}

/* Fix Bootstrap Icons to Font Awesome */
.input-group-text .bi-search {
  font-family: 'Font Awesome 5 Free';
  font-weight: 900;
}

.input-group-text .bi-search:before {
  content: "\f002";
}

.input-group-text .bi-list-nested {
  font-family: 'Font Awesome 5 Free';
  font-weight: 900;
}

.input-group-text .bi-list-nested:before {
  content: "\f0ca";
}

.btn-outline-secondary .bi-x-circle {
  font-family: 'Font Awesome 5 Free';
  font-weight: 900;
}

.btn-outline-secondary .bi-x-circle:before {
  content: "\f057";
}

.active-filters .badge .bi-x-circle-fill {
  font-family: 'Font Awesome 5 Free';
  font-weight: 900;
}

.active-filters .badge .bi-x-circle-fill:before {
  content: "\f057";
}

/* Alert Messages */
.alert {
    padding: 12px 16px;
    margin-bottom: 16px;
    border-radius: 6px;
    position: relative;
    display: flex;
    align-items: center;
    animation: fadeIn 0.5s;
}

.alert i {
    margin-right: 10px;
    font-size: 1.1rem;
}

.alert-success {
    background-color: #e8f5e9;
    color: #2e7d32;
    border-left: 4px solid #2e7d32;
}

.alert-danger {
    background-color: #ffebee;
    color: #d32f2f;
    border-left: 4px solid #d32f2f;
}

.btn-close {
    position: absolute;
    right: 15px;
    background: none;
    border: none;
    font-size: 1.2rem;
    cursor: pointer;
    color: inherit;
    opacity: 0.7;
}

.btn-close:hover {
    opacity: 1;
}

/* Product Grid - 3 items per row, matching screenshot */
.product-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-top: 16px;
    padding: 0;
}

.product-card {
    background-color: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    transition: transform 0.2s, box-shadow 0.2s;
    position: relative;
    display: flex;
    flex-direction: column;
    height: auto;
    min-height: 0;
}

.product-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

/* Product Image */
.product-image {
    position: relative;
    height: 160px;
    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.product-card:hover .product-image img {
    transform: scale(1.05);
}

.wishlist-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    background-color: rgba(255, 255, 255, 0.9);
    color: #e91e63;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    z-index: 2;
}

.organic-badge {
    position: absolute;
    top: 8px;
    left: 8px;
    background-color: #4caf50;
    color: white;
    padding: 4px 8px;
    font-size: 0.7rem;
    border-radius: 4px;
    display: flex;
    align-items: center;
    gap: 3px;
    z-index: 2;
}

/* Product Info */
.product-info {
    padding:10px 15px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.product-info h3 {
    font-size: 1rem;
    color: #333;
    margin-bottom: 2px;
    font-weight: 600;
}

.description {
    font-size: 0.8rem;
    color: #666;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.3;
    height: 1.3em;
    margin-bottom: 4px;
}

.badge-container {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 2px;
}

.category-badge {
    background-color: #f1f8e9;
    color: #558b2f;
    padding: 3px 8px;
    font-size: 0.75rem;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 3px;
}

.seller-info {
    font-size: 0.85rem;
    color: #666;
    display: flex;
    align-items: center;
    gap: 4px;
}

.price-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 4px 0;
}

.price {
    font-size: 1.2rem;
    font-weight: 600;
    color: #2e7d32;
}

.unit-text {
    font-size: 0.8rem;
    font-weight: normal;
    color: #666;
}

.quantity-badge {
    font-size: 0.75rem;
    color: #666;
    background-color: #f5f5f5;
    padding: 3px 8px;
    border-radius: 20px;
}

.stock-status {
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 4px;
    margin-bottom: 4px;
}

.in-stock {
    color: #2e7d32;
}

.low-stock {
    color: #ff9800;
}

.out-of-stock {
    color: #e53935;
}

.minimum-qty-warning {
    font-size: 0.8rem;
    color: #616161;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Product Actions */
.product-actions {
    padding: 10px 15px;
    border-top: 1px solid #f0f0f0;
    display: flex;
    flex-direction: column;
    gap:8px;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    padding: 6px 10px;
    border-radius: 5px;
    font-size: 0.85rem;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
}

.view-details {
    background-color: #f5f5f5;
    color: #555;
    width: 100%;
    border-radius: 20px;
}

.view-details:hover {
    background-color: #e0e0e0;
}

.primary-action {
    display: flex;
    gap: 10px;
}

.primary-action form {
    display: flex;
    flex: 1;
}

.add-to-cart-btn {
    flex: 1;
    background-color: #2e7d32;
    color: white;
    width: 100%;
    padding: 8px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.add-to-cart-btn:hover {
    background-color: #1b5e20;
}

.add-to-cart-btn.clicked {
    transform: scale(0.95);
}

.wishlist-btn {
    background-color: #f5f5f5;
    color: #616161;
    padding: 8px;
    min-width: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.2s ease;
}

.wishlist-btn:hover {
    background-color: #fce4ec;
    color: #e91e63;
}

.wishlist-btn.active {
    background-color: #fce4ec;
    color: #e91e63;
}

/* No Products */
.no-products {
    grid-column: 1 / -1;
    text-align: center;
    padding: 30px 20px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
    color: #666;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
}

.no-products i {
    font-size: 2.2rem;
    color: #9e9e9e;
}

/* Notification */
/* Custom Toast Styles */
.custom-toast-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1060;
}

.custom-toast {
    visibility: hidden;
    min-width: 280px;
    margin: 15px;
    background-color: white;
    color: #333;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    opacity: 0;
    transition: opacity 0.5s, visibility 0.5s;
}

.custom-toast.show {
    visibility: visible;
    opacity: 1;
}

.custom-toast-header {
    padding: 12px 15px;
    background-color: #2e7d32;
    color: white;
    display: flex;
    align-items: center;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
}

.custom-toast-header i {
    margin-right: 10px;
}

.custom-toast-header strong {
    flex: 1;
}

.custom-toast-close {
    background: none;
    border: none;
    font-size: 20px;
    color: white;
    cursor: pointer;
}

.custom-toast-body {
    padding: 15px;
    background-color: white;
    border-bottom-left-radius: 8px;
    border-bottom-right-radius: 8px;
}

/* Add this animation if you want to keep the pulse effect */
@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
    }
}


/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-8px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
    }
}

/* Responsive Styles */
@media (max-width: 1200px) {
    .product-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 992px) {
    .product-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .product-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .filter-controls {
        flex-direction: column;
        align-items: stretch;
    }
}

@media (max-width: 576px) {
    .product-grid {
        grid-template-columns: 1fr;
    }
    
    .primary-action {
        flex-direction: column;
    }
    
    .products-container {
        padding: 10px;
    }
}
</style>
</html>