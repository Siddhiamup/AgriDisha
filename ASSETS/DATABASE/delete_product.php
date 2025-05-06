<?php
session_start(); // Start session at the top of the file
include 'db.php';

error_reporting(E_ALL);
ini_set('display_errors', 0); // Change to 0 to prevent error messages from breaking JSON
header('Content-Type: application/json'); // Force JSON response

// Ensure any previous output is cleared before sending JSON
if (ob_get_length()) ob_clean();

// Initialize response array
$response = array(
    'success' => false,
    'message' => ''
);

try {
    // Check user permission - allow both admin and farmer roles
    if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'Admin' && $_SESSION['role'] !== 'Farmer')) {
        $response['message'] = "You don't have permission to delete this product";
        echo json_encode($response);
        exit();
    }

    // Check if the product ID is passed
    if (isset($_GET['id'])) {
        $productId = intval($_GET['id']); // Convert to integer to ensure valid ID
        $userId = $_SESSION['id']; // Get the logged-in user's ID
        
        // For debugging
        $response['debug'] = array(
            'role' => $_SESSION['role'],
            'id' => $userId,
            'productId' => $productId
        );
        
        // Different check for admin vs farmer
        if ($_SESSION['role'] === 'Admin') {
            // Admins can delete any product
            $checkSql = "SELECT * FROM products WHERE id = ?";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bind_param("i", $productId);
        } else {
            // Farmers can only delete their own products
            $checkSql = "SELECT * FROM products WHERE id = ? AND seller_id = ?";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bind_param("ii", $productId, $userId);
        }
        
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        
        if ($result->num_rows > 0) {
            // Product belongs to the user or user is admin, get the product info first
            $product = $result->fetch_assoc();
            
            // Delete the product
            $sql = "DELETE FROM products WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $productId);
            
            if ($stmt->execute()) {
                // If there's an image, delete it
                if (!empty($product['image_url'])) {
                    // Skip file deletion if it causes errors
                    // Just log the attempt without breaking the flow if file doesn't exist
                    $imagePath = $_SERVER['DOCUMENT_ROOT'] . '/AgriDisha/uploads/' . $product['image_url'];
                    if (file_exists($imagePath)) {
                        @unlink($imagePath); // Use @ to suppress errors if file can't be deleted
                    }
                }
                
                $response['success'] = true;
                $response['message'] = 'Product deleted successfully';
                $response['productId'] = $productId; // Return the ID for frontend use
            } else {
                $response['message'] = 'Error deleting product: ' . $conn->error;
            }
        } else {
            $response['message'] = 'Product not found or you do not have permission to delete it';
        }
    } else {
        $response['message'] = 'Product ID not specified';
    }
} catch (Exception $e) {
    // Catch any exceptions to prevent breaking the JSON response
    $response['success'] = false;
    $response['message'] = 'An error occurred: ' . $e->getMessage();
}

// Return JSON response
echo json_encode($response);
exit;
?>