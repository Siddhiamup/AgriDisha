<?php
// buy.php - Regular buy page with improved search and filter design

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

// Get products with seller info
$query = "SELECT p.*, u.username as seller_name 
          FROM products p 
          JOIN users u ON p.seller_id = u.id 
          $where_clause 
          ORDER BY p.created_at DESC";
$result = mysqli_query($conn, $query);
?>

<link rel="stylesheet" href="ASSETS/CSS/buy_tab.css">
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1 class="display-5 mb-4">Agricultural Products Marketplace</h1>
            <p class="lead">Browse quality agricultural products directly from farmers across India.</p>
            
            <?php if(!isset($_SESSION['user_id'])): ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i> 
                    <strong>Please <a href="registration.php" class="alert-link">login or register</a> to add products to cart or make purchases.</strong>
                </div>
            <?php endif; ?>
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

        <div class="row">
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($product = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                         <img src="<?php echo !empty($product['image_url']) ? htmlspecialchars($product['image_url']) : 'ASSETS/IMAGES/default-product.jpg'; ?>" 
                             class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>"
                             style="height: 200px; object-fit: cover;"> 
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                <?php if($product['organic_certified']): ?>
                                    <span class="badge bg-success">Organic</span>
                                <?php endif; ?>
                            </div>
                            <p class="card-text text-truncate"><?php echo htmlspecialchars($product['description']); ?></p>
                            <p class="card-text">
                                <small class="text-muted">Category: <?php echo htmlspecialchars($product['category']); ?></small>
                            </p>
                            <p class="card-text">
                                <small class="text-muted">Seller: <?php echo htmlspecialchars($product['seller_name']); ?></small>
                            </p>
                            <!-- <p class="card-text">
                                <small class="text-muted">Location: <?php echo htmlspecialchars($product['location']); ?></small>
                            </p> -->
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="text-success mb-0">₹<?php echo htmlspecialchars($product['price']); ?> / <?php echo htmlspecialchars($product['unit']); ?></h5>
                                <span class="badge bg-primary"><?php echo htmlspecialchars($product['quantity']) . ' ' . htmlspecialchars($product['unit']); ?> available</span>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <div class="d-flex justify-content-between">
                                <a href="product_details.php?id=<?php echo $product['id']; ?>" class="btn btn-outline-success">View Details</a>
                                <a href="registration.php?redirect=<?php echo urlencode('index.php?tab=buy&action=add_to_cart&product_id='.$product['id']); ?>" class="btn btn-success">
                                    <i class="bi bi-cart-plus me-1"></i> Add to Cart
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">
                    No products found matching your criteria. Please try a different search or category.
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Information about farming practices -->
    <!-- <div class="row mt-5">
        <div class="col-12">
            <h3 class="mb-4">Sustainable Farming Practices</h3>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-droplet me-2 text-primary"></i>Water Conservation</h5>
                    <p class="card-text">Many of our farmers utilize drip irrigation and rainwater harvesting techniques to minimize water usage while maximizing crop yields.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-flower1 me-2 text-success"></i>Organic Farming</h5>
                    <p class="card-text">Products marked as "Organic" are grown without synthetic pesticides and fertilizers, promoting healthier ecosystems and safer food.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-people me-2 text-warning"></i>Fair Trade</h5>
                    <p class="card-text">When you purchase directly from farmers on our platform, you support fair prices for agricultural products and sustainable rural livelihoods.</p>
                </div>
            </div>
        </div>
    </div>
     -->
    <!-- Login/Register CTA -->
    <?php if(!isset($_SESSION['user_id'])): ?>
        <div class="row mt-4 mb-5">
            <div class="col-12">
                <div class="card bg-light">
                    <div class="card-body p-4 text-center">
                        <h4>Ready to start shopping?</h4>
                        <p class="mb-4">Create an account to purchase products directly from farmers and track your orders.</p>
                        <a href="registration.php?redirect=index.php?tab=buy" class="btn btn-success btn-lg">Register Now</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>