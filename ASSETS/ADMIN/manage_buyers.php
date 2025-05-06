<?php
// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure only admin can access
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ams_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch buyers
$buyers_query = "SELECT id, username, email, status FROM users WHERE role='buyer'";
$buyers_result = $conn->query($buyers_query);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Buyers</title>
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
    <h1>Manage Buyers</h1>

    <table border="1">
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
            <?php if ($buyers_result->num_rows > 0): ?>
                <?php while ($buyer = $buyers_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($buyer['id']) ?></td>
                        <td><?= htmlspecialchars($buyer['username']) ?></td>
                        <td><?= htmlspecialchars($buyer['email']) ?></td>
                        <td><?= htmlspecialchars($buyer['status']) ?></td>
                        <td>
                            <?php if ($buyer['status'] == 'pending'): ?>
                                <a href="approve_buyer.php?id=<?= htmlspecialchars($buyer['id']) ?>" class="btn btn-approve">Approve</a>
                                <a href="reject_buyer.php?id=<?= htmlspecialchars($buyer['id']) ?>" class="btn btn-reject">Reject</a>
                            <?php endif; ?>
                            <a href="delete_buyer.php?id=<?= htmlspecialchars($buyer['id']) ?>" class="btn btn-delete" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">No buyers found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
