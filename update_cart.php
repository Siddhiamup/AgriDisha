<?php
// update_cart.php

// Include database connection 
include 'db.php';

session_start(); 

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    $message = "Please log in to update cart";
    header("Location: login.php?error=" . urlencode($message));
    exit;
}

// Verify user is a Buyer
if ($_SESSION['role'] !== 'Buyer') {
    $message = "Only buyers can update cart";
    header("Location: index.php?error=" . urlencode($message));
    exit;
}

$buyer_id = $_SESSION['id'];

// Validate inputs
if (!isset($_POST['product_id']) || !isset($_POST['quantity'])) {
    $message = "Missing required fields";
    header("Location: index.php?tab=cart&error=" . urlencode($message));
    exit;
}

$product_id = filter_var($_POST['product_id'], FILTER_VALIDATE_INT);
$quantity = filter_var($_POST['quantity'], FILTER_VALIDATE_INT);

// Validate inputs
if (!$product_id) {
    $message = "Invalid product ID";
    header("Location: index.php?tab=cart&error=" . urlencode($message));
    exit;
}

// Ensure quantity is at least 15 kg
if ($quantity < 15) {
    $message = "Minimum purchase quantity is 15 kg";
    header("Location: index.php?tab=cart&error=" . urlencode($message));
    exit;
}

try {
    // First, find the existing cart item for this product and buyer
    $find_cart_stmt = $conn->prepare("
        SELECT id, quantity AS current_quantity 
        FROM cart 
        WHERE buyer_id = ? AND product_id = ?
    ");
    $find_cart_stmt->bind_param("ii", $buyer_id, $product_id);
    $find_cart_stmt->execute();
    $cart_result = $find_cart_stmt->get_result();

    // Check product availability
    $product_stmt = $conn->prepare("
        SELECT id, name, quantity AS max_available 
        FROM products 
        WHERE id = ?
    ");
    $product_stmt->bind_param("i", $product_id);
    $product_stmt->execute();
    $product_result = $product_stmt->get_result();

    if ($product_result->num_rows === 0) {
        $message = "Product not found";
        header("Location: index.php?tab=cart&error=" . urlencode($message));
        exit;
    }

    $product = $product_result->fetch_assoc();

    // Check available stock
    if ($quantity > $product['max_available']) {
        $message = "Requested quantity exceeds available stock";
        header("Location: index.php?tab=cart&error=" . urlencode($message));
        exit;
    }

    // If cart item exists, update it
    if ($cart_result->num_rows > 0) {
        $cart_item = $cart_result->fetch_assoc();
        $update_stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $update_stmt->bind_param("ii", $quantity, $cart_item['id']);
        
        if (!$update_stmt->execute()) {
            $message = "Failed to update cart: " . $conn->error;
            header("Location: index.php?tab=cart&error=" . urlencode($message));
            exit;
        }
        $update_stmt->close();
    } 
    // If no cart item exists, create a new one
    else {
        $insert_stmt = $conn->prepare("INSERT INTO cart (buyer_id, product_id, quantity) VALUES (?, ?, ?)");
        $insert_stmt->bind_param("iii", $buyer_id, $product_id, $quantity);
        
        if (!$insert_stmt->execute()) {
            $message = "Failed to add to cart: " . $conn->error;
            header("Location: index.php?tab=cart&error=" . urlencode($message));
            exit;
        }
        $insert_stmt->close();
    }

    // Redirect with success message
    $message = "Cart updated successfully";
    header("Location: index.php?tab=cart&success=" . urlencode($message));
    exit;

} catch (Exception $e) {
    $message = "An unexpected error occurred: " . $e->getMessage();
    header("Location: index.php?tab=cart&error=" . urlencode($message));
    exit;
} finally {
    $conn->close();
}
?>