<?php
// session_start();
// if (!isset($_SESSION['admin'])) {
//     header('Location: view_product.php');
//     exit();
// }

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ams_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch product details
if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$product) {
        die("Product not found.");
    }
} else {
    header("Location: ./admin.php?tab=view_products");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $category = trim($_POST['category']);

    if ($name && $price && $category) {
        $update_stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, category = ? WHERE id = ?");
        $update_stmt->bind_param("sdsi", $name, $price, $category, $product_id);

        if ($update_stmt->execute()) {
            echo "<p class='success'>Product updated successfully!</p>";
            header("Refresh: 2; URL=./admin.php?tab=view_products");
        } else {
            echo "<p class='error'>Error updating product: " . $conn->error . "</p>";
        }
        $update_stmt->close();
    } else {
        echo "<p class='error'>All fields are required.</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Edit Product</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .admin-panel {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background: #fff;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
        }

        h2 {
            text-align: center;
            color: #4CAF50;
        }

        label {
            display: block;
            margin: 15px 0 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }

        button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #45a049;
        }

        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #4CAF50;
            text-decoration: none;
        }

        .success {
            color: green;
            text-align: center;
        }

        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="admin-panel">
    <h2>Edit Product</h2>
    <form method="POST" action="">
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" value="<?= htmlspecialchars($product['price']) ?>" required>

        <label for="category">Category:</label>
        <input type="text" id="category" name="category" value="<?= htmlspecialchars($product['category']) ?>" required>

        <button type="submit">Update Product</button>
        <a href="./admin.php?tab=view_products" class="back-link">Back to Product List</a>
    </form>
</div>
</body>
</html>