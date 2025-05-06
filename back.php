<?php
session_start();
include 'db.php'; // Include database connection

// Fetch farmer's products
$farmer_id = $_SESSION['id'];
$sql = "SELECT * FROM products WHERE farmer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $farmer_id);
$stmt->execute();
$products = $stmt->get_result();

// Fetch orders for the farmer
$sql_orders = "SELECT * FROM orders WHERE farmer_id = ?";
$stmt_orders = $conn->prepare($sql_orders);
$stmt_orders->bind_param("i", $farmer_id);
$stmt_orders->execute();
$orders = $stmt_orders->get_result();

// Handle adding a product
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addProduct'])) {
    $category = $_POST['category'];
    $productName = $_POST['productName'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    
    // Handle file upload
    $image_path = '';
    if (isset($_FILES['image_path']) && $_FILES['image_path']['error'] == 0) {
        $targetDir = "uploads/";
        $image_path = $targetDir . basename($_FILES['image_path']['name']);
        move_uploaded_file($_FILES['image_path']['tmp_name'], $image_path);
    }
    
    $sql = "INSERT INTO products (farmer_id, category, productName, description, quantity, price, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssdds", $farmer_id, $category, $productName, $description, $quantity, $price, $image_path);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Product added successfully!";
    } else {
        $_SESSION['error'] = "Failed to add product!";
    }
    header("Location: farmer_dashboard.php");
    exit();
}

// Handle deleting a product
if (isset($_GET['deleteProduct'])) {
    $productId = $_GET['deleteProduct'];
    $sql = "DELETE FROM products WHERE id = ? AND farmer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $productId, $farmer_id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Product deleted successfully!";
    } else {
        $_SESSION['error'] = "Failed to delete product!";
    }
    header("Location: farmer_dashboard.php");
    exit();
}

// Handle updating profile
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateProfile'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    
    $sql = "UPDATE farmers SET name=?, email=?, phone=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $name, $email, $phone, $farmer_id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Profile updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update profile!";
    }
    header("Location: farmer_dashboard.php");
    exit();
}

// Handle password change
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['changePassword'])) {
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];
    
    if ($newPassword !== $confirmPassword) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: farmer_dashboard.php");
        exit();
    }
    
    $sql = "UPDATE farmers SET password=? WHERE id=?";
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $hashedPassword, $farmer_id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Password changed successfully!";
    } else {
        $_SESSION['error'] = "Failed to change password!";
    }
    header("Location: farmer_dashboard.php");
    exit();
}
?>
