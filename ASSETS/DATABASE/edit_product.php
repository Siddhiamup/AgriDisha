<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ams_db"; // Change this if your database name is different

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get product ID from form
    $product_id = $_POST['product_id'];
    
    // Verify the product belongs to this seller
    $check_stmt = $conn->prepare("SELECT id FROM products WHERE id = ? AND seller_id = ?");
    $check_stmt->bind_param("ii", $product_id, $_SESSION['id']);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows == 0) {
        die("You don't have permission to edit this product");
    }
    $check_stmt->close();
    
    $name = $_POST['name'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];
    $unit = $_POST['unit'];
    $price = $_POST['price'];
    $location = $_POST['location'];
    $organic_certified = isset($_POST['organic_certified']) ? 1 : 0;
    
    // Handle delivery options
    $delivery_options = isset($_POST['delivery_options']) ? $_POST['delivery_options'] : [];
    $delivery_options_str = implode(',', $delivery_options);
    
    // Basic validation
    if (empty($name)) {
        $errors[] = "Product name is required";
    }
    
    if (empty($errors)) {
        // Use prepared statement for update
        $stmt = $conn->prepare("UPDATE products SET 
                name = ?, 
                category = ?,
                description = ?, 
                quantity = ?,
                unit = ?,
                price = ?,
                location = ?,
                organic_certified = ?,
                delivery_options = ?
                WHERE id = ? AND seller_id = ?");
                
        $stmt->bind_param("sssisssisii", 
                $name, 
                $category,
                $description, 
                $quantity,
                $unit,
                $price,
                $location,
                $organic_certified,
                $delivery_options_str,
                $product_id,
                $_SESSION['id']);
        
        $update_result = $stmt->execute();
        
        if ($update_result) {
            // Handle image upload if a new image is provided
            if (isset($_FILES['image_url']) && $_FILES['image_url']['size'] > 0) {
                $target_dir = "uploads/";
                
                // Create directory if it doesn't exist
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                
                $file_extension = pathinfo($_FILES["image_url"]["name"], PATHINFO_EXTENSION);
                $new_filename = "product_" . $product_id . "." . $file_extension;
                $target_file = $target_dir . $new_filename;
                
                if (move_uploaded_file($_FILES["image_url"]["tmp_name"], $target_file)) {
                    // Update image URL in database using prepared statement
                    $img_stmt = $conn->prepare("UPDATE products SET image_url = ? WHERE id = ?");
                    $img_stmt->bind_param("si", $target_file, $product_id);
                    $img_stmt->execute();
                    $img_stmt->close();
                }
            }
            
            // Success - redirect back to products page
            echo "<script>alert('Product updated successfully!'); window.location.href='/AgriDisha/fardemo.php?tab=products&updated=1';</script>";

           // header("Location: /AMSDemo/fardemo.php?tab=products&updated=1");
            exit();
        } else {
            $errors[] = "Failed to update product: " . $stmt->error;
        }
        
        $stmt->close();
    }

    // If there were errors, redirect back with error messages
    if (!empty($errors)) {
        $_SESSION['product_errors'] = $errors;
        header("Location: /AgriDisha/fardemo.php?tab=products&action=edit&id=" . $product_id);
        exit();
    }
} else {
    // Not a POST request, redirect to products page
    header("Location: /AgriDisha/fardemo.php?tab=products");
    exit();
}
?>