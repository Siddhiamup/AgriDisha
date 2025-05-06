<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role =$conn->real_escape_string($_POST['role']);

    
    // Check if email already exists
    $check_query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($result) > 0) {
     echo "<script>alert('Email already registered!');</script>";
     header("refresh:2;url=registration.php");


        exit();
    }
    
    // Generate OTP
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['user_data'] = [
        'username' => $username,
        'email' => $email,
        'password' => $password,
        'role' => $role

    ];
    
    // Send OTP
    if (sendOTP($email, $otp)) {
        header("Location: verify-otp.php");
        exit();
    } else {
        echo "<script>alert('Error sending OTP. Please try again.');</script>";
        header("refresh:2;url=registration.php");


    }


}
?>