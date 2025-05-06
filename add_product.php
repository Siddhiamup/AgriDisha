<?php
session_start(); // Start session

include 'db.php';

$errors = [];

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    die("Error: User not logged in. Please log in first.");
}

$seller_id = $_SESSION['id']; // Get seller ID from session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = mysqli_real_escape_string($conn, $_POST["category"]);
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $description = mysqli_real_escape_string($conn, $_POST["description"]);
    $quantity = mysqli_real_escape_string($conn, $_POST["quantity"]);
    $price = mysqli_real_escape_string($conn, $_POST["price"]);
    $location = mysqli_real_escape_string($conn, $_POST["location"]);
    $seller_id = $_SESSION['id'];
    // Determine organic certification
    $organic_certified = isset($_POST['organic_certified']) ? 1 : 0;
    
    // Handle delivery options
    $delivery_options = isset($_POST['delivery_options']) ? implode(',', $_POST['delivery_options']) : null;

    // Initialize image_url variable
    $image_url = null;
    
    if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES['image_url']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (!getimagesize($_FILES["image_url"]["tmp_name"])) {
            $errors[] = "File is not an image.";
        }
        if ($_FILES["image_url"]["size"] > 2000000) {
            $errors[] = "File is too large. Maximum size is 2MB.";
        }

        $allowed_types = ["jpg", "png", "jpeg", "gif"];
        if (!in_array($imageFileType, $allowed_types)) {
            $errors[] = "Only JPG, JPEG, PNG & GIF files are allowed.";
        }

        if (empty($errors) && move_uploaded_file($_FILES['image_url']['tmp_name'], $target_file)) {
            // Set the image URL to the path where the file was saved
            $image_url = $target_file;
        } else if (!empty($_FILES['image_url']['tmp_name'])) {
            $errors[] = "Error uploading file.";
        }
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO products 
            (name, description, price, unit, quantity, category, location, 
             image_url, seller_id, organic_certified, delivery_options) 
            VALUES 
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "ssdsssssiss", 
            $name, $description, $price, $_POST['unit'], $quantity, 
            $category, $location, $image_url, $seller_id, $organic_certified, $delivery_options
        );

        if ($stmt->execute()) {
            echo "<script>alert('Product added successfully!'); window.location.href='/AgriDisha/fardemo.php?tab=products';</script>";
        } else {
            $errors[] = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
    $conn->close();
}
?>