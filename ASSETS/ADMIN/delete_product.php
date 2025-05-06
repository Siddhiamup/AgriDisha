<?php
// session_start();
// if (!isset($_SESSION['admin'])) {
//     header('Location: login.php');
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

// Check if product ID is provided
if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);

    // Delete product
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        // echo "<p style='color: green;'>Product deleted successfully!</p>";
        echo "<script>alert('Product deleted successfully!'); window.location.href='/AgriDisha/ASSETS/ADMIN/admin.php?tab=view_products';</script>";

        header("Refresh: 2; URL=./admin.php?tab=view_products");
    } else {
        echo "<p style='color: red;'>Error deleting product: " . $conn->error . "</p>";
    }
    $stmt->close();
} else {
    echo "<p style='color: red;'>Invalid product ID.</p>";
}

$conn->close();
?>
