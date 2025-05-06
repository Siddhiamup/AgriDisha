<?php
session_start();

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];




    // Input validation
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Please fill in all fields";
        header("Location: /AgriDisha/login.php");
        exit();

    }

    // Validate user credentials
    $query = "SELECT id, email, password_hash, role, username,status FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if ($user['status'] === 'pending' && $user['role'] === 'Farmer') { // Check if admin has approved the user
            // User not approved
            $_SESSION['error'] = "Your account is pending approval from admin. Please wait or contact administrator.";
            header("Location: /AgriDisha/login.php");
            exit();

        } else {
            // Verify password
            if (password_verify($password, $user['password_hash'])) {
                // Set session variables
                $_SESSION['id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['logged_in'] = true;

                // Redirect based on role
                switch ($user['role']) {
                    case 'Admin':
                        header("Location: ASSETS/ADMIN/admin.php");
                        break;
                    case 'Farmer':
                        header("Location: /AgriDisha/fardemo.php");
                        break;
                    case 'Buyer':
                        header("Location: /AgriDisha/index.php?tab=buy");
                        break;
                    default:
                        $_SESSION['error'] = "Invalid role";
                        header("Location: /AgriDisha/login.php");
                        break;
                }
                exit();
            } else {
                $_SESSION['error'] = "Invalid password";
                header("Location: /AgriDisha/login.php");
                exit();
            }

        }
    } else {
        $_SESSION['error'] = "Invalid email";
        header("Location: /AgriDisha/login.php");
        exit();
    }
}