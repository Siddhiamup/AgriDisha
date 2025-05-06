
<?php
// Include database connection
require_once 'db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in 
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];
$success_message = '';
$error_message = '';

// Fetch current user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ? ");
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
    
    // Validate input
    if (empty($username) || empty($email)) {
        $_SESSION['error']= "Username and email are required fields";
    } else {
        // Check if email is already taken by another user
        $check_email = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $check_email->bind_param("si", $email, $user_id);
        $check_email->execute();
        $email_result = $check_email->get_result();
        
        if ($email_result->num_rows > 0) {
            $_SESSION['error'] = "Email is already in use by another account";
        } else {
            // Update user profile
            $update_stmt = $conn->prepare("UPDATE users SET 
                username = ?,
                email = ?,
                phone = ?
                WHERE id = ?");
            
            $update_stmt->bind_param("sssi",
                $username,
                $email,
                $phone,
                $user_id
            );
            
            if ($update_stmt->execute()) {
                $_SESSION['success'] = "Profile updated successfully!";
                
                // Update session data
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                
                // Refresh user data
                $stmt->execute();
                $user_data = $stmt->get_result()->fetch_assoc();
            } else {
                $_SESSION['error']= "Error updating profile: " . $conn->error;
            }
        }
    }
}
?>
