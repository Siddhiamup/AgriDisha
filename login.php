<?php
session_start();
$error_message = "";
if (isset($_SESSION['error'])) {
    $error_message = $_SESSION['error'];
    unset($_SESSION['error']); // Remove error after displaying
}
?>

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Agricultural Marketplace</title>
    <style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    padding: 20px;
}

/* Create a pseudo-element for the blurred background */
body::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: url('ASSETS/IMAGES/f10.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    filter: blur(5px); /* Add blur only to the background */
    z-index: -1;
}

.container {
    max-width: 500px;
    width: 100%;
    background: rgba(5, 31, 9, 0.7); /* Dark semi-transparent background */
    padding: 35px 40px;
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
    backdrop-filter: blur(5px); /* Adds a subtle blur effect */
    border: 1px solid rgba(255, 255, 255, 0.1);
    position: relative;
    z-index: 1;
}

.title {
    font-size: 32px;
    font-weight: 600;
    color: white;
    position: relative;
    margin-bottom: 30px;
    text-align: center;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
}

.title::before {
    content: '';
    position: absolute;
    left: 0;
    bottom: -5px;
    height: 3px;
    width: 30px;
}

form .form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    color: white;
    font-weight: 500;
    margin-bottom: 5px;
    font-size: 16px;
    letter-spacing: 0.5px;
}

.form-group input,
.form-group select {
    height: 50px;
    width: 100%;
    outline: none;
    border-radius: 5px;
    border: 1px solid #ccc;
    padding: 0 15px;
    font-size: 16px;
    transition: all 0.3s ease;
}

.error {
    color: #ff0000;
    font-size: 14px;
    margin-top: 5px;
    display: none;
}

.success-message {
    color: #28a745;
    text-align: center;
    margin-bottom: 15px;
}

.btn {
    height: 55px;
    width: 100%;
    background-color: #4CAF50;
    color: white;
    padding: 12px 15px;
    border: none;
    border-radius: 10px;
    font-size: 18px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    margin-top: 10px;
    letter-spacing: 1px;
}

.btn:hover {
    background-color: #45a049;
}

#otp-form {
    display: none;
}

.alert {
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 5px;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.error-message {
    color: red;
    font-size: 14px;
    margin-bottom: 10px;
}

.password-container {
    position: relative;
    width: 100%;
}

.password-container input {
    width: 100%;
    padding-right: 40px; /* Space for the eye icon */
}

.toggle-password {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    font-size: 18px;
    color: #666;
}

.toggle-password:hover {
    color: #333;
}

/* Style for the form text and links */
h2 {
    color: white;
    text-align: center;
    margin-bottom: 20px;
    font-size: 28px;
}

p {
    color: white;
    margin-top: 20px;
    text-align: center;
}

a {
    color: #4CAF50;
    text-decoration: none;
    font-weight: bold;
}

a:hover {
    text-decoration: underline;
}

.password-container p {
    text-align: right;
    margin-top: 5px;
    font-size: 14px;
}
</style>
<link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"> <!-- Font Awesome -->
</head>
<body>
    <div class="container">
        <form method='POST' action="ASSETS/DATABASE/login_2.php">
            <h2>Login</h2>

            <!-- Display error message here -->
            <?php if (!empty($error_message)) : ?>
                <center><p class="error-message"><?php echo $error_message; ?></p></center>
            <?php endif; ?>

            <div class="form-group">
            <label for="email"><i class="fa-solid fa-envelope"></i>&nbsp;Email</label>
            <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group password-container">
            <label for="password"><i class="fa-solid fa-lock"></i> &nbsp;Password</label>
            <input type="password" id="password" name="password" required>
                <i class="fa-solid fa-eye toggle-password" onclick="togglePassword()"></i> 
                <p><a href="forgot-password.php">Forgot Password?</a></p>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        
        <p>Don't have an account? <a href="registration.php">Register here</a></p>
    </div>

    <script>
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var toggleIcon = document.querySelector(".toggle-password");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash"); // Change to hidden eye icon
            } else {
                passwordField.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye"); // Change back to visible eye icon
            }
        }
    </script>
</body>
</html>