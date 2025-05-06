<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ams_db"; // Change this if your database name is different

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// farmer/dashboard.php

//require_once 'auth_check.php';
//checkUserRole('farmer');
// Rest of your dashboard code
// Get current tab from URL parameter, default to dashboard
$current_tab = isset($_GET['tab']) ? $_GET['tab'] : 'dashboard';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="ASSETS/CSS/buyer.css">
    <style>
        .sidebar img {
    display: block;
    margin: 20px auto;
    border-radius: 50%;  /* Makes the logo circular */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);  /* Subtle shadow effect */
    object-fit: cover;  /* Ensures image covers the area without distortion */
    transition: transform 0.3s ease;  /* Smooth transform animation */
    background-color: white;  /* In case of transparent logos */
    padding: 8px;  /* Creates some space around the logo */
}

/* Optional hover effect */
.sidebar img:hover {
    transform: scale(1.0);  /* Slightly enlarges logo on hover */
}
.logout {
    position: absolute;  /* Position absolutely within the nearest positioned ancestor */
    top: 20px;          /* Distance from top */
    right: 20px;        /* Distance from right */
    padding: 10px;      /* Reduced padding to make it more compact */
    border: none;       /* Removed the top border since it's now floating */
}

.logout a {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #dc3545;
    text-decoration: none;
    padding: 8px 15px;    /* Slightly reduced padding for a more compact look */
    border-radius: 8px;
    transition: all 0.3s ease;
    font-size: 0.9rem;    /* Optional: slightly smaller font size */
}

.logout a:hover {
    background-color: #dc3545;
    color: white;
}
    </style>
    <title>Farmer Dashboard</title>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <img src="ASSETS\IMAGES\logo.jpg" height="87"width="87">
            <h2>Farmer Dashboard</h2>
            <ul>
                <li><a href="?tab=fardash" class="<?php echo $current_tab == 'fardash' ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i>&nbsp;&nbsp;Dashboard</a></li>
                <li><a href="?tab=products" class="<?php echo $current_tab == 'products' ? 'active' : ''; ?>">
                    <i class="fas fa-box"></i>&nbsp;&nbsp;Manage Products</a></li>
                <li><a href="?tab=orders" class="<?php echo $current_tab == 'orders' ? 'active' : ''; ?>">
                    <i class="fas fa-shopping-cart"></i>&nbsp;&nbsp;Orders</a></li>
                <li><a href="?tab=payments" class="<?php echo $current_tab == 'payments' ? 'active' : ''; ?>">
                    <i class="fas fa-money-bill-wave"></i>&nbsp;&nbsp;Payment History</a></li>
                <li><a href="?tab=profile" class="<?php echo $current_tab == 'profile' ? 'active' : ''; ?>">
                    <i class="fas fa-user"></i>&nbsp;&nbsp;Profile</a></li>
                <li><a href="?tab=settings" class="<?php echo $current_tab == 'settings' ? 'active' : ''; ?>">
                    <i class="fas fa-cog"></i>&nbsp;&nbsp;Settings</a></li>
            </ul>
        </div>

        <div class="content">
            <div class="header">
                <h1>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
            </div>
            <div class="logout">
                <b><a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a></b>
            </div>
            <?php
            // Include the appropriate tab content based on selection
            $tab_file = $current_tab . '.php';
            if (file_exists($tab_file)) {
                include $tab_file;
            } else {
                include 'fardash.php';
            }
            ?>
        </div>
    </div>
</body>
</html>