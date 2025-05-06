<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Include database connection
include 'db.php';

// Check if user is logged in and is a buyer
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'Buyer') {
    $_SESSION['error'] = "You must be logged in as a buyer to manage your wishlist.";
    header("Location: login.php");
    exit;
}
 // Determine redirection
$redirect_page = isset($_POST['redirect']) ? $_POST['redirect'] : "index.php?tab=buyer_buy";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    if (!isset($_POST['product_id']) || !filter_var($_POST['product_id'], FILTER_VALIDATE_INT)) {
        $_SESSION['error'] = "Invalid product selection.";
        header("Location: $redirect_page");
        exit;
    }

    $product_id = intval($_POST['product_id']); // Secure against SQL injection
    $buyer_id = $_SESSION['id'];

    try {
        // Check if the product is already in the wishlist
        $check_stmt = $conn->prepare("SELECT id FROM wishlist WHERE buyer_id = ? AND product_id = ?");
        $check_stmt->bind_param("ii", $buyer_id, $product_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            // Remove from wishlist
            $delete_stmt = $conn->prepare("DELETE FROM wishlist WHERE buyer_id = ? AND product_id = ?");
            $delete_stmt->bind_param("ii", $buyer_id, $product_id);
            $delete_stmt->execute();
            $_SESSION['success'] = "Product removed from wishlist.";
        } else {
            // Add to wishlist
            $insert_stmt = $conn->prepare("INSERT INTO wishlist (buyer_id, product_id) VALUES (?, ?)");
            $insert_stmt->bind_param("ii", $buyer_id, $product_id);
            $insert_stmt->execute();
            $_SESSION['success'] = "Product added to wishlist successfully.";
        }

        header("Location: $redirect_page");
        exit;

    } catch (Exception $e) {
        $_SESSION['error'] = "An error occurred: " . $e->getMessage();
        header("Location: $redirect_page");
        exit;
    } finally {
        $conn->close();
    }
}
?>
