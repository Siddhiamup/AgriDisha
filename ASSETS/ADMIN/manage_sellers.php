<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ams_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all sellers
$seller_query = "SELECT id, username, email, status FROM users WHERE role='farmer'";
$seller_result = $conn->query($seller_query);

// Count sellers
$seller_count = $seller_result->num_rows;

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Sellers</title>
    <link rel="stylesheet" href="style.css"> <!-- Ensure this file includes button styles -->
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
    margin-right: 5px;
}

/* Approve Button - Green */
.btn-approve {
    background-color: #27ae60;  /* Green */
    color: white;
}

.btn-approve:hover {
    background-color: #219150;
}

/* Reject Button - Orange */
.btn-reject {
    background-color: #f39c12;  /* Orange */
    color: white;
}

.btn-reject:hover {
    background-color: #d68910;
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
    <h1>Manage Sellers</h1>

    <p>Total Sellers: <?= $seller_count ?></p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($seller_count > 0): ?>
                <?php while ($seller = $seller_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($seller['id']) ?></td>
                        <td><?= htmlspecialchars($seller['username']) ?></td>
                        <td><?= htmlspecialchars($seller['email']) ?></td>
                        <td><?= htmlspecialchars($seller['status']) ?></td>
                        <td>
                            <a href="approve_seller.php?id=<?= htmlspecialchars($seller['id']) ?>" class="btn btn-approve">Approve</a>
                            <a href="reject_seller.php?id=<?= htmlspecialchars($seller['id']) ?>" class="btn btn-reject">Reject</a>
                            <a href="delete_seller.php?id=<?= htmlspecialchars($seller['id']) ?>" class="btn btn-delete" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">No sellers found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
