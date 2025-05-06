<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ams_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    $_SESSION['error'] = "Please log in to access this page.";
    header("Location: AMSDemo/login.php");
    exit();
}

// Check if the request is a POST request and contains order_id
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = (int)$_POST['order_id'];
    
    // Check if the order exists and belongs to the current user
    $status_check = $conn->prepare("SELECT status FROM orders WHERE id = ? AND buyer_id = ?");
    $status_check->bind_param("ii", $order_id, $_SESSION['id']);
    $status_check->execute();
    $status_result = $status_check->get_result();
    
    if ($status_result->num_rows > 0) {
        $order = $status_result->fetch_assoc();
        
        if ($order['status'] == 'Pending') {
            // Cancel order
            $cancel_query = $conn->prepare("UPDATE orders SET status = 'Cancelled' WHERE id = ?");
            $cancel_query->bind_param("i", $order_id);
            
            if ($cancel_query->execute()) {
                // Success message
                $_SESSION['success'] = "Order #" . $order_id . " has been successfully cancelled.";
            } else {
                // Error message for database update failure
                $_SESSION['error'] = "Error cancelling order: " . $conn->error;
            }
        } else {
            // Error message for non-pending orders
            $_SESSION['error'] = "Sorry, only pending orders can be cancelled.";
        }
    } else {
        // Error message for order not found or not belonging to user
        $_SESSION['error'] = "Order not found or you don't have permission to cancel it.";
    }
    
    // Redirect back to my_orders.php
    header("Location: ../bdemo.php?tab=my_orders");
    exit();
} else {
    // No order ID provided or not a POST request
    $_SESSION['error'] = "Invalid request for order cancellation.";
    header("Location: ../bdemo.php?tab=my_orders");
    exit();
}

// Close the database connection
// $conn->close();
?>