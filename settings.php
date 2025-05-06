<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ams_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if form is submitted for password change
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['current_password'], $_POST['new_password'], $_POST['confirm_password'])) {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword === $confirmPassword) {
        $user = $_SESSION['username'] ?? null;

        if ($user) {
            $sql = "SELECT password_hash FROM users WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $user);
            $stmt->execute();
            $result = $stmt->get_result();
            $userData = $result->fetch_assoc();

            if ($userData) {
                $hashedPassword = $userData['password_hash'] ?? null;

                if ($hashedPassword && password_verify($currentPassword, $hashedPassword)) {
                    $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $updateSql = "UPDATE users SET password_hash = ? WHERE username = ?";
                    $updateStmt = $conn->prepare($updateSql);
                    $updateStmt->bind_param("ss", $newHashedPassword, $user);
                    $updateStmt->execute();

                    echo "<script>alert('Password successfully updated.');</script>";
                } else {
                    echo "<script>alert('Current password is incorrect.');</script>";
                }
            }
        }
    } else {
        echo "<script>alert('New passwords do not match.');</script>";
    }
}

$conn->close();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Settings</title>
</head>
<body>

<h2>Settings</h2><br><br>
<div class="settings-container">
    <section class="password-section">
        <h3>Change Password</h3>
        
        <form action="" method="POST">
            <div class="form-group">
                <label for="currentPassword">Current Password</label>
                <input type="password" id="currentPassword" name="current_password" required>
            </div>
            <div class="form-group">
                <label for="newPassword">New Password</label>
                <input type="password" id="newPassword" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirmPassword">Confirm New Password</label>
                <input type="password" id="confirmPassword" name="confirm_password" required>
            </div>
            <button type="submit" class="btn">Change Password</button>
        </form>
    </section>
</div>

</body>
</html>