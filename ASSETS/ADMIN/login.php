<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ams_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $admin_name = trim($_POST['admin_name']);
    $admin_password = trim($_POST['admin_password']);

    if (!empty($admin_name) && !empty($admin_password)) {
        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM admin_info WHERE admin_name = ? AND admin_password = ?");
        $stmt->bind_param("ss", $admin_name, $admin_password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['admin'] = $admin_name;
            header('Location: admin.php');
            exit();
        } else {
            $error = "Invalid username or password!";
        }

        $stmt->close();
    } else {
        $error = "Please fill in all fields!";
    }
}

// Check if admin is logged in
if (isset($_SESSION['admin'])) {
    $admin_name = $_SESSION['admin'];

    // Fetch admin info securely
    $admin_query = $conn->prepare("SELECT * FROM admin_info WHERE admin_name = ?");
    $admin_query->bind_param("s", $admin_name);
    $admin_query->execute();
    $admin_info = $admin_query->get_result()->fetch_assoc();
    $admin_query->close();

    // Fetch counts
    $counts = [
        'total_products' => "SELECT COUNT(*) AS total FROM products",
        'total_sellers' => "SELECT COUNT(*) AS total FROM users WHERE role='seller'",
        'total_buyers' => "SELECT COUNT(*) AS total FROM users WHERE role='buyer'",
        'pending_sellers' => "SELECT COUNT(*) AS total FROM users WHERE role='seller' AND status='pending'",
        'pending_buyers' => "SELECT COUNT(*) AS total FROM users WHERE role='buyer' AND status='pending'",
    ];

    foreach ($counts as $key => $sql) {
        $result = $conn->query($sql);
        ${$key} = $result ? $result->fetch_assoc()['total'] : 0;
    }

    // Fetch recent orders
    $orders_query = "SELECT id, status, order_date FROM orders ORDER BY order_date DESC LIMIT 5";
    $orders_result = $conn->query($orders_query);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('../IMAGES/f7.jpg') no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 320px;
        }
        .login-container h2 {
            text-align: center;
            color: #333;
        }
        .login-container label {
            display: block;
            margin: 10px 0 5px;
            color: #555;
        }
        .login-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }
        .login-container button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }
        .login-container button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST" action="">
            <label for="admin_name">Username:</label>
            <input type="text" id="admin_name" name="admin_name" required>
            <label for="admin_password">Password:</label>
            <input type="password" id="admin_password" name="admin_password" required>
            <button type="submit" name="submit">Login</button>
        </form>
    </div>
</body>
</html>
