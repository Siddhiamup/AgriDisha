<?php
session_start();
var_dump($_POST);
// Check if user is logged in and is a buyer
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'Buyer') {
    // Redirect to login page with return URL
    header("Location: login.php?redirect=" . urlencode($_SERVER['HTTP_REFERER']));
    exit;
}

// Check if product_id is provided
if (!isset($_POST['product_id']) || empty($_POST['product_id'])) {
    $_SESSION['error'] = "Invalid product selection.";
    header("Location: " . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "index.php?tab=buy"));
    exit;
}

// Get product ID
$product_id = intval($_POST['product_id']);
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
$buyer_id = $_SESSION['id'];

// Get source page for proper return - this helps with multiple pages using this script
$source_page = isset($_POST['source_page']) ? $_POST['source_page'] : 'browse_products';

// Sanitize quantity
if ($quantity < 1) $quantity = 1;

// Connect to database
include 'db.php';

// Check if product exists and is available
$query = "SELECT * FROM products WHERE id = ? AND in_stock = 1";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $product_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    $_SESSION['error'] = "Product is not available.";
    header("Location: " . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "index.php?tab=buy"));
    exit;
}

$product = mysqli_fetch_assoc($result);

// Get product name for better notification
$product_name = htmlspecialchars($product['name']);

// Separate quantity check - compare with available quantity
if ($quantity > $product['quantity']) {
    // If requested quantity exceeds available, adjust quantity and add a warning
    $quantity = $product['quantity'];
    $_SESSION['warning'] = "Requested quantity exceeds available stock. Added maximum available quantity instead.";
}

// Check if product is already in cart
$check_query = "SELECT * FROM cart WHERE buyer_id = ? AND product_id = ?";
$check_stmt = mysqli_prepare($conn, $check_query);
mysqli_stmt_bind_param($check_stmt, "ii", $buyer_id, $product_id);
mysqli_stmt_execute($check_stmt);
$check_result = mysqli_stmt_get_result($check_stmt);

if (mysqli_num_rows($check_result) > 0) {
    // Update existing cart item
    $cart_item = mysqli_fetch_assoc($check_result);
    $new_quantity = $cart_item['quantity'] + $quantity;
    
    // Check if new quantity exceeds available stock
    if ($new_quantity > $product['quantity']) {
        $new_quantity = $product['quantity'];
        $_SESSION['warning'] = "Cart updated with maximum available quantity.";
    }
    
    $update_query = "UPDATE cart SET quantity = ? WHERE buyer_id = ? AND product_id = ?";
    $update_stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($update_stmt, "iii", $new_quantity, $buyer_id, $product_id);
    
    if (mysqli_stmt_execute($update_stmt)) {
        $_SESSION['cart_success'] = "\"$product_name\" quantity updated in your cart!"; // Set a message for toast notification
    } else {
        $_SESSION['error'] = "Failed to update cart: " . mysqli_error($conn);
    }
} else {
    // Insert new cart item
    $insert_query = "INSERT INTO cart (buyer_id, product_id, quantity) VALUES (?, ?, ?)";
    $insert_stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($insert_stmt, "iii", $buyer_id, $product_id, $quantity);
    
    if (mysqli_stmt_execute($insert_stmt)) {
        $_SESSION['cart_success'] = "\"$product_name\" added to your cart!"; // Set a message for toast notification with product name
    } else {
        $_SESSION['error'] = "Failed to add to cart: " . mysqli_error($conn);
    }
}

// Get the referring page
$referring_page = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "index.php?tab=buy";

// Check if there's a hidden input with a custom return URL
if (isset($_POST['return_url']) && !empty($_POST['return_url'])) {
    $referring_page = $_POST['return_url'];
}

// Redirect back to the page that initiated the request
header("Location: " . $referring_page);
exit;