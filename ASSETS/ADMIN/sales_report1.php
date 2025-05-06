<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ams_db";

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$admin_name = $_SESSION['admin'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Reports</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <img src="images/logo.jpg" alt="Logo" class="logo">
            <h2>Admin Panel</h2>
            <ul class="nav-links">
                <li><a href="view_products.php">Products</a></li>
                <li><a href="manage_sellers.php">Manage Sellers (0)</a></li>
                <li><a href="manage_buyers.php">Manage Buyers (1)</a></li>
                <li><a href="manage_orders.php">Orders</a></li>
                <li><a href="salesdemo.php" class="active">Sales Reports</a></li>
                <li><a href="admin_profile.php">Admin Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <h1>Sales Reports</h1>
            <div class="report-container">
                <iframe title="sales" width="100%" height="600" src="https://app.powerbi.com/reportEmbed?reportId=8cc5ae94-2358-4f20-9bdd-0af12847309c&autoAuth=true&embeddedDemo=true" frameborder="0" allowFullScreen="true"></iframe>
                <div class="button-container">
                    <a href="https://app.powerbi.com/links/wsu0dZqULO?ctid=51b942db-4e9b-4228-b3f4-777327ee4809&pbi_source=linkShare" class="report-button" target="_blank">Open Full Report</a>
                </div>
            </div>
        </div>
    </div>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 220px;
            background-color: #2c3e50;
            color: white;
            padding-top: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo {
            display: block;
            margin: 0 auto 20px;
            max-width: 100px;
        }
        
        .nav-links {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        
        .nav-links li {
            margin-bottom: 5px;
        }
        
        .nav-links a {
            display: block;
            padding: 12px 15px;
            color: white;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        .nav-links a:hover, .nav-links a.active {
            background-color: #34495e;
        }
        
        .main-content {
            flex: 1;
            padding: 30px;
            background-color: white;
        }
        
        h1 {
            color: #2c3e50;
            margin-top: 0;
            margin-bottom: 30px;
            font-size: 24px;
        }
        
        .report-container {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 30px;
        }
        
        iframe {
            border: none;
            display: block;
        }
        
        .button-container {
            margin-top: 15px;
            text-align: center;
        }
        
        .report-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        
        .report-button:hover {
            background-color: #45a049;
        }
    </style>
</body>
</html>