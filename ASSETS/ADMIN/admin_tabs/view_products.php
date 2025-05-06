<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ams_db";

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle product deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $product_id = $_GET['delete'];
    $delete_stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $delete_stmt->bind_param("i", $product_id);
    
    if ($delete_stmt->execute()) {
        $success_message = "Product deleted successfully!";
    } else {
        $error_message = "Error deleting product: " . $conn->error;
    }
    $delete_stmt->close();
}

// Pagination
$items_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;

// Search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search_condition = '';
if (!empty($search)) {
    $search_condition = " WHERE product_name LIKE ? OR description LIKE ?";
}

// Count total products
$count_sql = "SELECT COUNT(*) as total FROM products" . $search_condition;
$count_stmt = $conn->prepare($count_sql);
if (!empty($search)) {
    $search_param = "%$search%";
    $count_stmt->bind_param("ss", $search_param, $search_param);
}
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_products = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_products / $items_per_page);
$count_stmt->close();

// Fetch products with pagination and search
$products_sql = "SELECT p.*, u.user_name as seller_name 
                FROM products p 
                LEFT JOIN users u ON p.seller_id = u.id" . 
                $search_condition . 
                " ORDER BY p.id DESC LIMIT ?, ?";

$stmt = $conn->prepare($products_sql);
if (!empty($search)) {
    $search_param = "%$search%";
    $stmt->bind_param("ssii", $search_param, $search_param, $offset, $items_per_page);
} else {
    $stmt->bind_param("ii", $offset, $items_per_page);
}
$stmt->execute();
$products = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .content-area {
            margin-left: 240px;
            padding: 20px;
        }
        .search-box {
            margin-bottom: 20px;
        }
        .search-box input {
            padding: 8px;
            width: 300px;
        }
        .search-box button {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
        }
        .action-links a {
            margin-right: 10px;
            text-decoration: none;
        }
        .delete-link {
            color: red;
        }
        .pagination {
            margin-top: 20px;
            text-align: center;
        }
        .pagination a {
            display: inline-block;
            padding: 8px 16px;
            text-decoration: none;
            color: black;
            margin: 0 4px;
            border: 1px solid #ddd;
        }
        .pagination a.active {
            background-color: #4CAF50;
            color: white;
            border: 1px solid #4CAF50;
        }
        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }
        .success-message {
            background-color: #dff0d8;
            color: #3c763d;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .error-message {
            background-color: #f2dede;
            color: #a94442;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo-container">
                <img src="../IMAGES/logo.jpg" alt="Logo" width="100">
            </div>
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="view_products.php" class="active">Products</a></li>
                <li><a href="manage_sellers.php">Manage Sellers</a></li>
                <li><a href="manage_buyers.php">Manage Buyers</a></li>
                <li><a href="manage_orders.php">Orders</a></li>
                <li><a href="sales_reports.php">Sales Reports</a></li>
                <li><a href="admin_profile.php">Admin Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>

        <!-- Content -->
        <div class="content-area">
            <h1>Products Management</h1>
            
            <?php if (isset($success_message)): ?>
                <div class="success-message"><?= htmlspecialchars($success_message) ?></div>
            <?php endif; ?>
            
            <?php if (isset($error_message)): ?>
                <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
            <?php endif; ?>

            <!-- Search Box -->
            <div class="search-box">
                <form action="" method="GET">
                    <input type="text" name="search" placeholder="Search products..." value="<?= htmlspecialchars($search) ?>">
                    <button type="submit">Search</button>
                    <?php if (!empty($search)): ?>
                        <a href="view_products.php">Clear</a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Products Table -->
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Seller</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($products->num_rows > 0): ?>
                        <?php while ($product = $products->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($product['id']) ?></td>
                                <td>
                                    <?php if (!empty($product['image_url'])): ?>
                                        <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="Product" class="product-image">
                                    <?php else: ?>
                                        <span>No Image</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($product['product_name']) ?></td>
                                <td>$<?= htmlspecialchars(number_format($product['price'], 2)) ?></td>
                                <td><?= htmlspecialchars($product['seller_name']) ?></td>
                                <td><?= htmlspecialchars($product['status']) ?></td>
                                <td class="action-links">
                                    <a href="edit_product.php?id=<?= $product['id'] ?>">Edit</a>
                                    <a href="javascript:void(0);" class="delete-link" onclick="confirmDelete(<?= $product['id'] ?>)">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">No products found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($current_page > 1): ?>
                        <a href="?page=<?= $current_page - 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>">&laquo; Previous</a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>" <?= $i == $current_page ? 'class="active"' : '' ?>><?= $i ?></a>
                    <?php endfor; ?>
                    
                    <?php if ($current_page < $total_pages): ?>
                        <a href="?page=<?= $current_page + 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>">Next &raquo;</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function confirmDelete(productId) {
            if (confirm("Are you sure you want to delete this product?")) {
                window.location.href = "view_products.php?delete=" + productId;
            }
        }
    </script>
</body>
</html>