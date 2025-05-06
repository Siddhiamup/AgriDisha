<?php
ob_start();
include 'db.php';

// Function to handle redirects
function redirect($location, $message = null, $error = false) {
    if($message) {
        if($error) {
            $_SESSION['error'] = $message;
        } else {
            $_SESSION['message'] = $message;
        }
    }
    header("Location: $location");
    exit();
}

// Search and Filter Logic
$where_conditions = ["p.in_stock = 1"];
$params = [];
$param_types = "";

// Search functionality
if(isset($_GET['search']) && !empty($_GET['search'])) {
    $search = "%" . $_GET['search'] . "%";
    $where_conditions[] = "(p.name LIKE ? OR p.description LIKE ?)";
    $params[] = $search;
    $params[] = $search;
    $param_types .= "ss";
}

// Category filter
if(isset($_GET['category']) && !empty($_GET['category'])) {
    $where_conditions[] = "p.category = ?";
    $params[] = $_GET['category'];
    $param_types .= "s";
}

// Price range filter
if(isset($_GET['min_price']) && is_numeric($_GET['min_price'])) {
    $where_conditions[] = "p.price >= ?";
    $params[] = $_GET['min_price'];
    $param_types .= "d";
}
if(isset($_GET['max_price']) && is_numeric($_GET['max_price'])) {
    $where_conditions[] = "p.price <= ?";
    $params[] = $_GET['max_price'];
    $param_types .= "d";
}

// Sort options
$sort_options = [
    'price_asc' => 'p.price ASC',
    'price_desc' => 'p.price DESC',
    'newest' => 'p.created_at DESC',
    'name_asc' => 'p.name ASC'
];

$sort = isset($_GET['sort']) && array_key_exists($_GET['sort'], $sort_options) 
    ? $sort_options[$_GET['sort']] 
    : 'p.created_at DESC';

// Build the query
$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";
$query = "
    SELECT p.*, u.username as seller_name, 
           (SELECT COUNT(*) FROM wishlist w WHERE w.product_id = p.id) as wishlist_count 
    FROM products p 
    JOIN users u ON p.seller_id = u.id 
    $where_clause 
    ORDER BY $sort
";

// Prepare and execute the query
$stmt = $conn->prepare($query);
if(!empty($params)) {
    $stmt->bind_param($param_types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Fetch categories for filter
$categories = $conn->query("SELECT DISTINCT category FROM products WHERE in_stock = 1 ORDER BY category");

include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Products - AgriDisha</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- CSS will be in separate artifact --><style>
        :root {
    --primary-color: #2F5E1E;
    --secondary-color: #4A8B2C;
    --background-color: #f8f9fa;
    --text-dark: #2D3A1E;
    --text-light: #ffffff;
    --card-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    background-color: var(--background-color);
    line-height: 1.6;
}

.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 1rem;
}

/* Search Container */
.search-container {
    background: white;
    padding: 1.25rem;
    border-radius: 8px;
    box-shadow: var(--card-shadow);
    margin-bottom: 1.5rem;
}

.search-form {
    width: 100%;
}

.search-filters {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.search-input,
.filter-select,
.price-input,
.sort-select {
    width: 100%;
    padding: 0.6rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 0.9rem;
}

.search-btn {
    padding: 0.6rem 1.2rem;
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
    width: 100%;
}

.search-btn:hover {
    background: var(--secondary-color);
}

/* Products Grid */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 1rem;
}

.product-card {
    background: white;
    border-radius: 8px;
    box-shadow: var(--card-shadow);
    overflow: hidden;
    position: relative;
    transition: transform 0.3s;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.product-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.product-image {
    width: 100%;
    height: 160px;
    object-fit: cover;
}

.product-details {
    padding: 0.75rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.product-title {
    font-size: 1rem;
    margin-bottom: 0.5rem;
    color: var(--text-dark);
}

.product-price {
    font-size: 1.1rem;
    color: var(--primary-color);
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.product-seller {
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 0.5rem;
}

.product-description {
    font-size: 0.85rem;
    color: #555;
    margin-bottom: 0.75rem;
    flex-grow: 1;
}

.organic-badge {
    position: absolute;
    top: 8px;
    left: 8px;
    background: rgba(46, 204, 113, 0.9);
    color: white;
    padding: 0.3rem 0.6rem;
    border-radius: 12px;
    font-size: 0.8rem;
}

.wishlist-count {
    position: absolute;
    top: 8px;
    right: 8px;
    background: rgba(0, 0, 0, 0.6);
    color: white;
    padding: 0.3rem 0.6rem;
    border-radius: 12px;
    font-size: 0.8rem;
}

/* Cart Form */
.cart-form {
    margin-top: auto;
}

.quantity-wrapper {
    display: flex;
    gap: 0.5rem;
}

.quantity-input {
    width: 70px;
    padding: 0.4rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 0.9rem;
}

.add-to-cart-btn {
    flex-grow: 1;
    padding: 0.4rem;
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.9rem;
    transition: background-color 0.3s;
}

.add-to-cart-btn:hover {
    background: var(--secondary-color);
}

.add-to-cart-btn:disabled {
    background: #ccc;
    cursor: not-allowed;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .container {
        padding: 0.75rem;
    }

    .search-filters {
        grid-template-columns: 1fr;
    }

    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    }

    .product-image {
        height: 140px;
    }

    .product-title {
        font-size: 0.9rem;
    }

    .quantity-wrapper {
        flex-direction: column;
    }

    .quantity-input {
        width: 100%;
    }
}
    </style>
</head>
<body>
    <div class="container">
        <!-- Search and Filter Section -->
        <div class="search-container">
            <form method="GET" action="" class="search-form">
                <div class="search-filters">
                    <input type="text" name="search" placeholder="Search products..." 
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                           class="search-input">
                    
                    <select name="category" class="filter-select">
                        <option value="">All Categories</option>
                        <?php while($category = $categories->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($category['category']); ?>"
                                    <?php echo isset($_GET['category']) && $_GET['category'] == $category['category'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['category']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

                    <input type="number" name="min_price" placeholder="Min Price" 
                           value="<?php echo isset($_GET['min_price']) ? htmlspecialchars($_GET['min_price']) : ''; ?>"
                           class="price-input">
                           
                    <input type="number" name="max_price" placeholder="Max Price"
                           value="<?php echo isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : ''; ?>"
                           class="price-input">

                    <select name="sort" class="sort-select">
                        <option value="newest" <?php echo (!isset($_GET['sort']) || $_GET['sort'] == 'newest') ? 'selected' : ''; ?>>Newest First</option>
                        <option value="price_asc" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'price_asc' ? 'selected' : ''; ?>>Price: Low to High</option>
                        <option value="price_desc" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'price_desc' ? 'selected' : ''; ?>>Price: High to Low</option>
                        <option value="name_asc" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'name_asc' ? 'selected' : ''; ?>>Name: A to Z</option>
                    </select>

                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </form>
        </div>

        <!-- Products Grid -->
        <div class="products-grid">
            <?php while($product = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <div class="wishlist-count">
                        <i class="fas fa-heart"></i> <?php echo $product['wishlist_count']; ?>
                    </div>
                    
                    <img src="<?php echo htmlspecialchars($product['image_url'] ?? 'default-product.jpg'); ?>" 
                         alt="<?php echo htmlspecialchars($product['name']); ?>" 
                         class="product-image">
                    
                    <div class="product-details">
                        <h3 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="product-price">₹<?php echo number_format($product['price'], 2); ?> per <?php echo htmlspecialchars($product['unit']); ?></p>
                        <p class="product-seller">
                            <i class="fas fa-user"></i> <?php echo htmlspecialchars($product['seller_name']); ?>
                        </p>
                        <p class="product-description"><?php echo htmlspecialchars(substr($product['description'], 0, 100)) . '...'; ?></p>

                        <?php if($product['organic_certified']): ?>
                            <div class="organic-badge">
                                <i class="fas fa-leaf"></i> Organic
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="" class="cart-form">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <div class="quantity-wrapper">
                                <input type="number" name="quantity" value="1" min="1" 
                                       max="<?php echo $product['quantity']; ?>" 
                                       class="quantity-input">
                                <button type="submit" name="add_to_cart" class="add-to-cart-btn">
                                       
                                    <i class="fas fa-cart-plus"></i> Add to Cart
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
<?php ob_end_flush(); ?>
