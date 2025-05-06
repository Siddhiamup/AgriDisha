<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entered_otp = $_POST['otp'];
    
    if ($entered_otp == $_SESSION['otp']) {
        $user_data = $_SESSION['user_data'];
        
        // Insert user data into database
        $insert_query = "INSERT INTO users (username, email, password_hash,role) VALUES 
                        ('{$user_data['username']}', '{$user_data['email']}', '{$user_data['password']}','{$user_data['role']}')";
        
        if (mysqli_query($conn, $insert_query)) {
            // Clear session data
            unset($_SESSION['otp']);
            unset($_SESSION['user_data']);
            
            echo "<script>alert('Registration successful! You can now login.');</script>";
            // Redirect to login page after 2 seconds
            header("refresh:2;url=login.php");
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "<script>alert('Invalid OTP. Please try again.');</script>";

        header("refresh:2;url=verify-otp.php");
    }
}
?>