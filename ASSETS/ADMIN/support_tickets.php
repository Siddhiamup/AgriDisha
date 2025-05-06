<?php
// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ams_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT * FROM support_tickets ORDER BY created_at DESC";  // Fetch all tickets
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Support Tickets</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="view_products.php">Products</a></li>
                <li><a href="manage_sellers.php">Manage Sellers</a></li>
                <li><a href="manage_buyers.php">Manage Buyers</a></li>
                <li><a href="manage_orders.php">Orders</a></li>
                <li><a href="sales_reports.php">Sales Reports</a></li>
                <li><a href="admin_profile.php">Admin Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>

        <div class="content">
            <h1>Support Tickets</h1>
            <table>
                <tr>
                    <th>Ticket ID</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                <?php while ($ticket = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $ticket['id']; ?></td>
                        <td><?php echo $ticket['subject']; ?></td>
                        <td><?php echo $ticket['status']; ?></td>
                        <td>
                            <a href="view_ticket.php?id=<?php echo $ticket['id']; ?>">View</a> |
                            <a href="close_ticket.php?id=<?php echo $ticket['id']; ?>">Close</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</body>
</html>
