<?php
// Modified index.php
session_start();

$current_tab = isset($_GET['tab']) ? $_GET['tab'] : 'home';
$is_logged_in = isset($_SESSION['id']) && !empty($_SESSION['id']);
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : '';

// If user tries to access buy tab and is not logged in, redirect to login
if ($current_tab == 'buy' && !$is_logged_in) {
    // You can either redirect to login or still show the buy page with limited functionality
    // Uncomment the line below to force login
    // header("Location: login.php?redirect=buy");
    // exit;
}

// If user tries to access cart tab and is not logged in as buyer, redirect to login
if ($current_tab == 'cart' && (!$is_logged_in || $user_role != 'Buyer')) {
    header("Location: login.php?redirect=index.php?tab=cart");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Welcome to India's leading agricultural marketplace. Connect with farmers and buyers for seamless transactions and agricultural growth.">
    <meta name="keywords" content="Agriculture, Farmers, Marketplace, Crop, Online Store">
    <meta name="author" content="Agricultural Portal">
    <title>Home - Farmer Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="translate.css">
    <link rel="stylesheet" href="ASSETS/CSS/sell.css">
    <!-- <link rel="stylesheet" href="ASSETS/CSS/header.css"> -->
    <link rel="stylesheet" href="ASSETS/CSS/cart.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="ASSETS/JS/script.js" defer></script>
    
    <!-- Additional CSS for profile dropdown -->
    <style>
        .profile-dropdown {
            position: relative;
            display: inline-block;
        }
        
        .profile-icon {
            cursor: pointer;
            font-size: 1.5rem;
            color: white;
            padding: 8px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .profile-icon:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 180px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .dropdown-menu a {
            color: #333;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: background-color 0.3s;
        }
        
        .dropdown-menu a:hover {
            background-color: #f1f1f1;
        }
        
        .profile-dropdown:hover .dropdown-menu {
            display: block;
        }
        
        .user-info {
            padding: 12px 16px;
            border-bottom: 1px solid #eee;
            color: #333;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <!-- Custom header.php will be included separately -->
    <?php include 'header.php'; ?>
    <!-- Main Content -->
    <main>
        <?php
        if ($current_tab == 'buy') {
            // For buy tab, include different file based on login status
            if ($is_logged_in && $user_role == 'Buyer') {
                include 'buyer_buy.php'; // Special buy page for logged-in buyers

            } else {
                include 'buy.php'; // Regular buy page with limited functionality
            }
        } else if ($current_tab == 'cart' && $is_logged_in && $user_role == 'Buyer') {
            // Include cart tab for logged-in buyers
            include 'cart.php';
        } else {
            // For other tabs, include the normal content
            $tab_file = $current_tab . '.php';
            if (file_exists($tab_file)) {
                include $tab_file;
            } else {
                include 'home.php';
            }
        }
        ?>
    </main>
    
    <?php include 'footer.php'; ?>
</body>
</html>