<?php
// product_details.php
session_start();
require_once 'db.php'; // Include your database connection

// Check if product ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Redirect to index or show error
    header('Location: index.php');
    exit();
}

// Sanitize the product ID
$product_id = mysqli_real_escape_string($conn, $_GET['id']);

// Fetch comprehensive product details including seller information
$query = "SELECT 
            p.*, 
            u.username AS seller_name, 
            u.email AS seller_email,
            u.phone AS seller_phone,
            u.address_line1 AS seller_address
          FROM products p
          JOIN users u ON p.seller_id = u.id
          WHERE p.id = '$product_id'";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    // Product not found
    header('Location: index.php');
    exit();
}

$product = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details - <?php echo htmlspecialchars($product['name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <?php include 'header.php';?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <div class="position-relative">
                    <img src="<?php echo !empty($product['image_url']) ? htmlspecialchars($product['image_url']) : 'ASSETS/IMAGES/default-product.jpg'; ?>" 
                         class="img-fluid rounded shadow-sm" 
                         alt="<?php echo htmlspecialchars($product['name']); ?>">
                    
                    <?php if($product['organic_certified']): ?>
                        <span class="badge bg-success position-absolute top-0 end-0 m-2">
                            <i class="bi bi-leaf me-1"></i>Organic Certified
                        </span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Product Description</h5>
                        <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Price</h6>
                                <p class="card-text text-success fw-bold">
                                    ₹<?php echo number_format($product['price'], 2); ?> /  <?php echo htmlspecialchars($product['unit']); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Stock</h6>
                                <p class="card-text">
                                    <span class="<?php echo $product['quantity'] > 0 ? 'text-success' : 'text-danger'; ?>">
                                        <?php echo $product['quantity']; ?> <?php echo htmlspecialchars($product['unit']); ?> available
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Category</h6>
                                <p class="card-text">
                                    <?php echo htmlspecialchars($product['category']); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Seller Details</h5>
                        <p class="card-text">
                            <strong>Name:</strong> <?php echo htmlspecialchars($product['seller_name']); ?><br>
                            <strong>Location:</strong> <?php echo htmlspecialchars($product['location']); ?><br>
                            <i class="bi bi-envelope me-2"></i><?php echo htmlspecialchars($product['seller_email']); ?><br>
                            <?php if(!empty($product['seller_phone'])): ?>
                                <i class="bi bi-phone me-2"></i><?php echo htmlspecialchars($product['seller_phone']); ?>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="index.php?tab=buy" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Back to Products
                    </a>
                    <form action="add_to_cart.php" method="POST">
                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                        <input type="hidden" name="quantity" value="<?php echo ceil(15 / (preg_match('/kg/i', $product['unit']) ? 1 : 0.001)); ?>">
                                        <input type="hidden" name="return_url" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">                                        <button type="submit" class="btn btn-success add-to-cart-btn">Add to Cart</button>
                                    </form>
                </div>
            </div>
        </div>
    </div>
    <br><br>
    <?php include 'footer.php';?>
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
});
</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>