<?php
// buyer_buy.php - This is the custom buy page for logged-in buyers
// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and is a buyer
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'Buyer') {
    // Redirect if not properly authenticated
    header("Location: login.php");
    exit;
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

// Get products
$query = "SELECT p.*, u.username as seller_name
 FROM products p 
 JOIN users u ON p.seller_id = u.id 
 $where_clause ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

// Get wishlist items for this buyer
$buyer_id = $_SESSION['id'];
$wishlist_query = "SELECT product_id FROM wishlist WHERE buyer_id = '$buyer_id'";
$wishlist_result = mysqli_query($conn, $wishlist_query);
$wishlist_items = [];
while ($item = mysqli_fetch_assoc($wishlist_result)) {
    $wishlist_items[] = $item['product_id'];
}
?>
<link rel="stylesheet" href="ASSETS/CSS/buy_tab.css">


<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1 class="display-5 mb-4">Agridisha Marketplace</h1>
            <p class="lead">Browse and purchase quality agricultural products directly from farmers across India.</p>
        </div>
    </div>
    
    <!-- Filters Section -->
    <div class="filter-section bg-light p-3 rounded-3 mb-4">
        <div class="row g-3">
            <!-- Search Bar -->
            <div class="col-12 col-md-6">
                <div class="search-wrapper position-relative">
                    <form action="" method="GET" class="d-flex align-items-center">
                        <input type="hidden" name="tab" value="buy">
                        
                        <!-- Category Parameter Preservation -->
                        <?php if(isset($_GET['category']) && !empty($_GET['category'])): ?>
                            <input type="hidden" name="category" value="<?php echo htmlspecialchars($category_param); ?>">
                        <?php endif; ?>
                        
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0 shadow-none" 
                                   placeholder="Search products, categories..." 
                                   value="<?php echo htmlspecialchars($search_param); ?>">
                            <?php if(!empty($search_param)): ?>
                                <a href="?tab=buy<?php echo !empty($category_param) ? '&category='.urlencode($category_param) : ''; ?>" 
                                   class="btn btn-outline-secondary" type="button">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                            <?php endif; ?>
                            <button class="btn btn-success" type="submit">Search</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Category Dropdown -->
            <div class="col-12 col-md-6">
                <form action="" method="GET" class="d-flex align-items-center">
                    <input type="hidden" name="tab" value="buy">
                    
                    <!-- Search Parameter Preservation -->
                    <?php if(!empty($search_param)): ?>
                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search_param); ?>">
                    <?php endif; ?>
                    
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-list-nested text-muted"></i>
                        </span>
                        <select name="category" class="form-select border-start-0 shadow-none" onchange="this.form.submit()">
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
                            <a href="?tab=buy<?php echo !empty($search_param) ? '&search='.urlencode($search_param) : ''; ?>" 
                               class="btn btn-outline-secondary" type="button">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Active Filters Display -->
        <?php if(!empty($search_param) || !empty($category_param)): ?>
            <div class="active-filters mt-3">
                <span class="text-muted me-2">Active Filters:</span>
                <?php if(!empty($search_param)): ?>
                    <span class="badge bg-primary me-2">
                        Search: <?php echo htmlspecialchars($search_param); ?>
                        <a href="?tab=buy<?php echo !empty($category_param) ? '&category='.urlencode($category_param) : ''; ?>" 
                           class="text-white ms-1">
                            <i class="bi bi-x-circle-fill"></i>
                        </a>
                    </span>
                <?php endif; ?>
                <?php if(!empty($category_param)): ?>
                    <span class="badge bg-success me-2">
                        Category: <?php echo htmlspecialchars($category_param); ?>
                        <a href="?tab=buy<?php echo !empty($search_param) ? '&search='.urlencode($search_param) : ''; ?>" 
                           class="text-white ms-1">
                            <i class="bi bi-x-circle-fill"></i>
                        </a>
                    </span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Success/Error Messages -->
    <?php if(isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['success']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['error']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <!-- Products Display -->
    <div class="row">
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($product = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="position-relative">
                            <img src="<?php echo !empty($product['image_url']) ? htmlspecialchars($product['image_url']) : 'ASSETS/IMAGES/default-product.jpg'; ?>" 
                                 class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>"
                                 style="height: 200px; object-fit: cover;">
                            <?php if(in_array($product['id'], $wishlist_items)): ?>
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-danger"><i class="bi bi-heart-fill"></i></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text text-truncate"><?php echo htmlspecialchars($product['description']); ?></p>
                            <div class="d-flex flex-wrap mb-2">
                                <span class="category-badge">
                                    <i class="bi bi-tag"></i> <?php echo htmlspecialchars($product['category']); ?>
                                </span>
                                <?php if($product['organic_certified']): ?>
                                    <span class="organic-badge">
                                        <i class="bi bi-patch-check-fill"></i> Organic
                                    </span>
                                <?php endif; ?>
                            </div>
                            <p class="card-text">
                                <small class="text-muted">Seller: <?php echo htmlspecialchars($product['seller_name']); ?></small>
                            </p>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="text-success mb-0">₹<?php echo htmlspecialchars($product['price']); ?> / <?php echo htmlspecialchars($product['unit']); ?></h5>
                                <span class="badge bg-primary"><?php echo htmlspecialchars($product['quantity']) . ' ' . htmlspecialchars($product['unit']); ?> available</span>
                            </div>
                            <div class="minimum-qty-warning">
                                <i class="bi bi-info-circle"></i> Minimum order: 15kg
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <div class="d-flex justify-content-between">
                                <a href="product_details.php?id=<?php echo $product['id']; ?>" class="btn btn-outline-success">View Details</a>
                                <div class="btn-group">
                                    <form action="add_to_cart.php" method="POST">
                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                        <input type="hidden" name="quantity" value="<?php echo ceil(15 / (preg_match('/kg/i', $product['unit']) ? 1 : 0.001)); ?>">
                                        <input type="hidden" name="return_url" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">                                        
                                        <button type="submit" class="btn btn-success add-to-cart-btn">Add to Cart</button>
                                    </form>
                                    <form action="add_to_wishlist.php" method="POST" class="ms-2">
                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                        <input type="hidden" name="redirect" value="index.php?tab=buyer_buy">
                                        <button type="submit" class="btn btn-outline-primary wishlist-btn <?php echo in_array($product['id'], $wishlist_items) ? 'active' : ''; ?>">
                                            <i class="bi <?php echo in_array($product['id'], $wishlist_items) ? 'bi-heart-fill' : 'bi-heart'; ?>"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    No products found matching your criteria. Please try a different search or category.
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Toast for cart notification -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div class="toast" id="cartToast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-white">
            <i class="bi bi-cart-check me-2"></i>
            <strong class="me-auto">Agridisha</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Product added to cart successfully!
        </div>
    </div>
</div>

<!-- Cart Animation Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check for success messages in PHP session
    <?php if(isset($_SESSION['cart_success'])): ?>
    var cartToast = new bootstrap.Toast(document.getElementById('cartToast'));
    cartToast.show();
    
    // Animate the cart icon
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
            // We'll just let the form submission handle this,
            // but we could add client-side animations here if desired
        });
    });
});
</script>

