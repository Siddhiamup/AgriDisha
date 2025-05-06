<?php
// Include database connection
require_once 'db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and is a farmer
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'Buyer') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];
$success_message = '';
$error_message = '';

// Fetch current user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ? AND role = 'Buyer'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: login.php");
    exit();
}

$user_data = $result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';

    
    // Validate input
    if (empty($username) || empty($email)) {
        $error_message = "Username and email are required fields";
    } else {
        // Check if email is already taken by another user
        $check_email = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $check_email->bind_param("si", $email, $user_id);
        $check_email->execute();
        $email_result = $check_email->get_result();
        
        if ($email_result->num_rows > 0) {
            $error_message = "Email is already in use by another account";
        } else {
            // Update user profile
            $update_stmt = $conn->prepare("UPDATE users SET 
                username = ?,
                email = ?,
                phone = ?,
                address_line1 = ?
                WHERE id = ?");
            
            $update_stmt->bind_param("ssssi",
                $username,
                $email,
                $phone,
                $address,
                $user_id
            );
            
            if ($update_stmt->execute()) {
                $success_message = "Profile updated successfully!";
                
                // Update session data
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                //header("Location:fardemo.php?tab=profile");
                // Refresh user data
                $stmt->execute();
                $user_data = $stmt->get_result()->fetch_assoc();
            } else {
                $error_message = "Error updating profile: " . $conn->error;
            }
        }
    }
}
?>

<h2>Profile</h2>

<?php if (!empty($success_message)): ?>
    <div class="alert alert-success"><?php echo $success_message; ?></div>
<?php endif; ?>

<?php if (!empty($error_message)): ?>
    <div class="alert alert-danger"><?php echo $error_message; ?></div>
<?php endif; ?>

<form action="" method="POST" enctype="multipart/form-data">
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
    <div class="form-group">
        <label for="address">Address</label>
        <textarea id="address" name="address" ><?php echo htmlspecialchars($user_data['address_line1'] ?? ''); ?></textarea>
    </div>
    <button type="submit" class="btn">Update Profile</button>
</form>