<?php
// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check admin session
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

// Ensure admin data exists
if (!$admin_info) {
    die("<p style='color: red;'>Admin not found or session mismatch.</p>");
}

$admin_email = $admin_info['admin_email'];
$admin_password = $admin_info['admin_password'];

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_email = trim($_POST['email']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Verify current password
    if (!password_verify($current_password, $admin_password)) {
        echo "<p style='color: red;'>Current password is incorrect.</p>";
    } else {
        // Prepare to update password if new one is provided and matches
        $hashed_password = $admin_password; // Default to current password

        if (!empty($new_password)) {
            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
            } else {
                echo "<p style='color: red;'>New passwords do not match.</p>";
                exit();
            }
        }

        // Update admin info (email and optionally password)
        $update_stmt = $conn->prepare("UPDATE admin_info SET admin_email = ?, admin_password = ? WHERE admin_name = ?");
        $update_stmt->bind_param("sss", $new_email, $hashed_password, $admin_name);

        if ($update_stmt->execute()) {
            echo "<p style='color: green;'>Profile updated successfully!</p>";
            $_SESSION['admin_email'] = $new_email; // Update session
        } else {
            echo "<p style='color: red;'>Error updating profile: " . $conn->error . "</p>";
        }
        $update_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Profile</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="profile-container">
    <h2>Admin Profile</h2>
    <form method="POST" action="">
        <label for="admin_name">Username:</label>
        <input type="text" id="admin_name" value="<?php echo htmlspecialchars($admin_name); ?>" disabled>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($admin_email); ?>" required>

        <label for="current_password">Current Password:</label>
        <input type="password" id="current_password" name="current_password" required>

        <label for="password">New Password (optional):</label>
        <input type="password" id="password" name="password">

        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password">

        <button type="submit">Update Profile</button>
    </form>
</div>

<style>
    .profile-container {
        max-width: 600px;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .profile-container h2 {
        text-align: center;
    }
    .profile-container label {
        display: block;
        margin-top: 10px;
    }
    .profile-container input {
        width: 100%;
        padding: 8px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    .profile-container button {
        margin-top: 15px;
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    .profile-container button:hover {
        background-color: #45a049;
    }
</style>

</body>
</html>
