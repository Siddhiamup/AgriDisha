<?php
// Start session
session_start();

// Include database connection
include 'db.php';

// Check if user is logged in as Buyer
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'Buyer') {
    $_SESSION['error'] = "You must be logged in as a buyer to remove items.";
    header("Location: login.php");
    exit;
}

$buyer_id = $_SESSION['id'];

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get the redirect page from form input or fallback to 'index.php?tab=my_cart'
$redirect_page = isset($_POST['redirect']) ? $_POST['redirect'] : 'index.php?tab=my_cart';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Check if product_id is received
    if (!isset($_POST['product_id'])) {
        $_SESSION['error'] = "Error: Missing product ID.";
        header("Location: $redirect_page");
        exit;
    }

    // Validate product ID
    $product_id = filter_var($_POST['product_id'], FILTER_VALIDATE_INT);
    if (!$product_id) {
        $_SESSION['error'] = "Error: Invalid product ID.";
        header("Location: $redirect_page");
        exit;
    }

    try {
        // Verify if product exists in the cart
        $check_stmt = $conn->prepare("SELECT id FROM cart WHERE product_id = ? AND buyer_id = ?");
        $check_stmt->bind_param("ii", $product_id, $buyer_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows === 0) {
            $_SESSION['error'] = "Item not found in cart.";
            header("Location: $redirect_page");
            exit;
        }

        // Remove the item from the cart
        $delete_stmt = $conn->prepare("DELETE FROM cart WHERE product_id = ? AND buyer_id = ?");
        $delete_stmt->bind_param("ii", $product_id, $buyer_id);
        $delete_result = $delete_stmt->execute();

        if ($delete_result) {
            $_SESSION['success'] = "Item removed from cart successfully!";
        } else {
            $_SESSION['error'] = "Error: Failed to remove item.";
        }

        header("Location: $redirect_page");
        exit;

    } catch (Exception $e) {
        $_SESSION['error'] = "An unexpected error occurred: " . $e->getMessage();
        header("Location: $redirect_page");
        exit;
    } finally {
        $conn->close();
    }
}
?>
