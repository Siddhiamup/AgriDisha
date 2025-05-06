<?php
// First verify if session is started and user is logged in
// session_start();
// if (!isset($_SESSION['id'])) {
//     header("Location: login.php");
//     exit();
// }

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
$farmer_query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($farmer_query);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $_SESSION['id']);
if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}

$result = $stmt->get_result();
$farmer = $result->fetch_assoc();

// Verify if user data was found
if (!$farmer) {
    die("No user found with ID: " . $_SESSION['id']);
}
?>

<h2>Profile</h2>
<form action="./update_profile.php" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" id="username" name="usename" 
               value="<?php echo isset($farmer['username']) ? htmlspecialchars($farmer['username']) : ''; ?>" required>
    </div>
    
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" 
               value="<?php echo isset($farmer['email']) ? htmlspecialchars($farmer['email']) : ''; ?>" required>
    </div>
    
    <div class="form-group">
        <label for="phone">Phone</label>
        <input type="tel" id="phone" name="phone" 
               value="<?php echo isset($farmer['phone']) ? htmlspecialchars($farmer['phone']) : ''; ?>" required>
    </div>
    
    <div class="form-group">
        <label for="address">Address</label>
        <textarea id="address" name="address" ><?php echo isset($farmer['address']) ? htmlspecialchars($farmer['address']) : ''; ?></textarea>
    </div>
    
    <!-- <div class="form-group">
        <label for="profile_image">Profile Image</label>
        <?php if (!empty($farmer['profile_image'])): ?>
            <img src="<?php echo htmlspecialchars($farmer['profile_image']); ?>" alt="Profile" width="100">
        <?php endif; ?>
        <input type="file" id="profile_image" name="profile_image">
    </div> -->
    
    <button type="submit" class="btn">Update Profile</button>
</form>


