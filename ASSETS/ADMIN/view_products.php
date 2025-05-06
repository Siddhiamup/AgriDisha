<?php
// Start session and check admin access
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Include database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ams_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all products securely
$product_query = "SELECT * FROM products";
$product_result = $conn->query($product_query);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Products</title>
    <link rel="stylesheet" href="style.css">
</head>
<style>
    /* General Table Styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    padding: 10px;
    text-align: left;
    border: 1px solid #ddd;
}

/* Button Styling */
.btn {
    text-decoration: none;
    padding: 8px 12px;
    border-radius: 5px;
    font-weight: bold;
    display: inline-block;
    transition: 0.3s ease-in-out;
}

/* Edit Button - Green */
.btn-edit {
    background-color: #27ae60;  /* Green */
    color: white;
}

.btn-edit:hover {
    background-color: #219150;
}

/* Delete Button - Red */
.btn-delete {
    background-color: #e74c3c;  /* Red */
    color: white;
}

.btn-delete:hover {
    background-color: #c0392b;
}

    </style>
<body>
    <h1>Product List</h1>

    <table>
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($product_result->num_rows > 0): ?>
                <?php while ($product = $product_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($product['id']) ?></td>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td>₹<?= htmlspecialchars(number_format($product['price'], 2)) ?></td>
                        <td><?= htmlspecialchars($product['category']) ?></td>
                        <td>
                            <a href="edit_product.php?id=<?= htmlspecialchars($product['id']) ?>" class="btn btn-edit">✏️ Edit</a>
                            <a href="delete_product.php?id=<?= htmlspecialchars($product['id']) ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this product?');">🗑️ Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">No products available.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
