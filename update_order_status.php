<?php
session_start();

// Ensure only logged-in farmers can access this
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'Farmer') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

require_once 'db.php';

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Validate input
if (!isset($input['order_id']) || !isset($input['action'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$order_id = $input['order_id'];
$action = $input['action'];

try {
    // Verify the order belongs to the farmer
    $verify_stmt = $conn->prepare("
        SELECT o.id 
        FROM orders o
        JOIN products p ON o.product_id = p.id
        WHERE o.id = ? AND p.seller_id = ? AND o.status = 'Pending'
    ");
    $verify_stmt->bind_param("ii", $order_id, $_SESSION['id']);
    $verify_stmt->execute();
    $verify_result = $verify_stmt->get_result();

    if ($verify_result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Order not found or cannot be updated']);
        exit;
    }

    // Update order status
    if ($action === 'ship') {
        $update_stmt = $conn->prepare("
            UPDATE orders 
            SET status = 'Shipped' 
            WHERE id = ?
        ");
        $update_stmt->bind_param("i", $order_id);
        $update_result = $update_stmt->execute();

        if ($update_result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update order status']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} catch (Exception $e) {
    error_log("Order status update error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred']);
}