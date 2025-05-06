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

$admin_name = $_SESSION['admin'];

// Fetch admin info
$stmt = $conn->prepare("SELECT * FROM admin_info WHERE admin_name = ?");
$stmt->bind_param("s", $admin_name);
$stmt->execute();
$admin_info = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Debugging outputs
if (!$admin_info) {
    echo "<p style='color: red;'>Admin not found or session mismatch.</p>";
    exit();
} else {
    echo "<p style='color: blue;'>Admin Found: " . htmlspecialchars($admin_info['admin_name']) . "</p>";
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_email = $_POST['email'];
    $current_password = $_POST['current_password'];
    $new_password = !empty($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = $_POST['confirm_password'];

    // Debugging current password
    echo "<p>Entered Password: " . htmlspecialchars($current_password) . "</p>";
    echo "<p>Stored Hash: " . htmlspecialchars($admin_info['admin_password']) . "</p>";

    // Verify current password
    if (!password_verify($current_password, $admin_info['admin_password'])) {
        echo "<p style='color: red;'>Current password is incorrect.</p>";
        exit();
    }

    // Use the existing password if a new one is not provided
    $hashed_password = $admin_info['admin_password'];

    if (!empty($new_password)) {
        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        } else {
            echo "<p style='color: red;'>New passwords do not match.</p>";
            exit();
        }
    }

    // Update admin info (email and password)
    $update_stmt = $conn->prepare("UPDATE admin_info SET admin_email = ?, admin_password = ? WHERE admin_name = ?");
    $update_stmt->bind_param("sss", $new_email, $hashed_password, $admin_name);

    if ($update_stmt->execute()) {
        echo "<p style='color: green;'>Profile updated successfully!</p>";
        $admin_info['admin_email'] = $new_email;
    } else {
        echo "<p style='color: red;'>Error updating profile: " . $conn->error . "</p>";
    }
    $update_stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Sellers</title>
    <link rel="stylesheet" href="style.css">
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
                <li><a href="view_products.php">Products</a></li>
                <li><a href="manage_sellers.php">Manage Sellers</a></li>
                <li><a href="manage_buyers.php">Manage Buyers</a></li>
                <li><a href="manage_orders.php">Orders</a></li>
                <li><a href="sales_reports.php">Sales Reports</a></li>
                <li><a href="admin_profile.php">Admin Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
<div class="profile-container">
    <h2>Admin Profile</h2>
    <form method="POST" action="">
        <label for="admin_name">Username:</label>
        <input type="text" id="admin_name" name="admin_name" value="<?php echo htmlspecialchars($admin_info['admin_name']); ?>" disabled>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($admin_info['admin_email']); ?>" required>

        <label for="current_password">Current Password:</label>
        <input type="password" id="current_password" name="current_password" required>

        <label for="password">New Password (leave blank to keep current):</label>
        <input type="password" id="password" name="password">

        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password">

        <button type="submit">Update Profile</button>
    </form>
</div>

<style>
    .profile-container { max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
    .profile-container h2 { text-align: center; }
    .profile-container label { display: block; margin-top: 10px; }
    .profile-container input { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px; }
    .profile-container button { margin-top: 15px; padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer; }
    .profile-container button:hover { background-color: #45a049; }
</style>
</body>
</html>
