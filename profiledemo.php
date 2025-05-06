<?php

// First verify if session is started and user is logged in
// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
     exit();
}

// Verify database connection
$servername = "localhost"; // Your database host
$username = "root";
$password = "";
$dbname = "ams_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Modified query with error checking
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $_SESSION['id']);
if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}

$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

// Verify if user data was found
if (!$user_data) {
    die("No user found with ID: " . $_SESSION['id']);
}
?>

<h2>Profile</h2>

<?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php 
            echo $_SESSION['success']; 
            unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php 
            echo $_SESSION['error']; 
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>
    <?php
if (isset($_GET['success'])) {
    echo "<p style='color: green;'>" . htmlspecialchars($_GET['success']) . "</p>";
}
if (isset($_GET['error'])) {
    echo "<p style='color: red;'>" . htmlspecialchars($_GET['error']) . "</p>";
}
?>
<form action="update_profile.php" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="username">Name</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user_data['username']); ?>" required>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
    </div>
    <div class="form-group">
        <label for="phone">Phone</label>
        <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user_data['phone'] ?? ''); ?>">
    </div>
    <button type="submit" class="btn">Update Profile</button>
</form>
