<?php
// Modified header.php
$current_tab = isset($_GET['tab']) ? $_GET['tab'] : 'home';
$is_logged_in = isset($_SESSION['id']) && !empty($_SESSION['id']);
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
?>
   <style>
<style>
:root {
            --primary-color: #2ecc71;
            --secondary-color: #27ae60;
            --background-color: #f8f9fa;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
/* Reset and base styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    padding-top: 120px;
    font-family: system-ui, -apple-system, sans-serif;
}

/* Header Container */
.header-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background: linear-gradient(to right, #228b22,#228b22);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    z-index: 1000;
    padding: 1rem 0;
}

/* Main Navigation */
.nav-content {
    max-width: 1400px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 2rem;
}

/* Logo */
.logo {
    height: 80px;
    display: flex;
    align-items: center;
}

.logo img {
    height: 100%;
    width: auto;
    border-radius: 8px;
}

/* Navigation Links */
.nav-links {
    display: flex;
    flex-grow: 1;
    justify-content: center;
    gap: 2rem;
}

.nav-links a {
    text-decoration: none;
    color: rgba(255, 255, 255, 0.9);
    font-weight: 500;
    padding: 0.7rem 1.5rem;
    border-radius: 6px;
    transition: all 0.3s ease;
    font-size: 1.2rem;
}

.nav-links a:hover,
.nav-links a.active {
    color: #ffffff;
    background-color: rgba(255, 255, 255, 0.1);
    transform: translateY(-2px);
}

/* Auth Buttons (Cart & Profile) */
.auth-buttons {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    position: relative;
}

.cart-icon, .profile-icon {
    position: relative;
    font-size: 1.8rem;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem;
    border-radius: 6px;
    background-color: rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}

.cart-icon:hover, .profile-icon:hover {
    background-color: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
}

.cart-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background-color: #ff5722;
    color: white;
    font-size: 0.8rem;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    z-index: 10;
}

/* Profile Dropdown Styling */
.profile-dropdown {
    position: relative;
}

.profile-dropdown .profile-icon {
    cursor: pointer;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.dropdown-menu {
    position: absolute;
    top: calc(100% + 8px);
    right: 0;
    background-color: white;
    border-radius: 6px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    min-width: 220px;
    padding: 0;
    z-index: 1100;
    border: 1px solid #e0e0e0;
    overflow: hidden;
    transition: all 0.3s ease;
    display: none;
}

.dropdown-menu.show {
    display: block;
}

.dropdown-menu::before {
    content: '';
    position: absolute;
    top: -10px;
    right: 10px;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
    border-bottom: 10px solid white;
    z-index: 1101;
}

.dropdown-menu .user-info {
    padding: 12px 15px;
    background-color: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
}

.dropdown-menu .user-info span {
    font-weight: 600;
    color: #333;
    display: block;
    font-size: 0.95rem;
}

.dropdown-menu a {
    display: flex;
    align-items: center;
    padding: 10px 15px;
    text-decoration: none;
    color: #333;
    transition: background-color 0.2s ease;
}

.dropdown-menu a:hover {
    background-color: #f0f0f0;
}

.dropdown-menu a i {
    margin-right: 10px;
    color: #228b22;
    font-size: 1rem;
}

/* Auth Buttons */
.auth-buttons {
    display: flex;
    gap: 1rem;
    margin-left: 2rem;
}

.register-btn {
    background-color: #ffffff;
    color: #1b5e20;
    padding: 0.7rem 1.5rem;
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.3s ease;
    font-weight: 600;
    font-size: 1.1rem;
}

.register-btn:hover {
    background-color: #e8f5e9;
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* Ensure Google Translate Element doesn't disrupt layout */
#google_translate_element {
    margin-left: 1rem;
}
</style>
<header class="header-container">
    <!-- Main navigation -->
    <nav class="main-nav">
        <div class="nav-content">
            <div class="logo">
                <img src="ASSETS/IMAGES/logo1.jpg" alt="AgriDisha Logo">
            </div>
            <div class="nav-links">
                <a href="?tab=home" class="<?php echo $current_tab == 'home' ? 'active' : ''; ?>">Home</a>
                <a href="?tab=buy" class="<?php echo $current_tab == 'buy' ? 'active' : ''; ?>">Buy</a>
                <a href="?tab=sell" class="<?php echo $current_tab == 'sell' ? 'active' : ''; ?>">Sell</a>
                <a href="?tab=about" class="<?php echo $current_tab == 'about' ? 'active' : ''; ?>">About Us</a>
                <a href="?tab=blogs" class="<?php echo $current_tab == 'blogs' ? 'active' : ''; ?>">Blogs</a>
                <a href="?tab=contact" class="<?php echo $current_tab == 'contact' ? 'active' : ''; ?>">Contact</a>
                <div id="google_translate_element"></div>
            </div>
            
            <div class="auth-buttons">
                <?php if ($is_logged_in): ?>
                    <!-- Show cart icon if user is logged in as buyer -->
                    <?php if ($user_role == 'Buyer'): ?>
                        <?php
                        // Connect to DB and get cart count
                        $cart_count = 0;
                        if (isset($_SESSION['id'])) {
                            include 'db.php';
                            $buyer_id = $_SESSION['id'];
                            $cart_query = "SELECT COUNT(*) as count FROM cart WHERE buyer_id = ?";
                            $stmt = mysqli_prepare($conn, $cart_query);
                            mysqli_stmt_bind_param($stmt, "i", $buyer_id);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            if ($row = mysqli_fetch_assoc($result)) {
                                $cart_count = $row['count'];
                            }
                        }
                        ?>
                        <a href="?tab=cart" class="cart-icon" title="Your Cart">
                            <i class="bi bi-cart3"></i>
                            <?php if ($cart_count > 0): ?>
                                <span class="cart-badge"><?php echo $cart_count; ?></span>
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>
                    
                    <!-- Profile dropdown for logged-in users -->
                    <div class="profile-dropdown">
                        <div class="profile-icon">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <div class="dropdown-menu">
                            <div class="user-info">
                                <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                            </div>
                            <a href="bdemo.php">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                            <a href="bdemo.php?tab=profile">
                                <i class="bi bi-person me-2"></i> My Profile
                            </a>
                            <?php if ($user_role == 'Buyer'): ?>
                                <a href="bdemo.php?tab=my_orders">
                                    <i class="bi bi-bag me-2"></i> My Orders
                                </a>
                                <a href="bdemo.php?tab=wishlist">
                                    <i class="bi bi-heart me-2"></i> My Wishlist
                                </a>
                            <?php endif; ?>
                            <?php if ($user_role == 'Farmer'): ?>
                                <a href="my_products.php">
                                    <i class="bi bi-basket me-2"></i> My Products
                                </a>
                                <a href="sales.php">
                                    <i class="bi bi-graph-up me-2"></i> Sales History
                                </a>
                            <?php endif; ?>
                            <a href="logout.php">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Display registration/login button for guests -->
                    <a href="login.php" class="register-btn">Login</a>
                    <a href="registration.php" class="register-btn">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</header>

<!-- Add some space after the header -->
<div style="height: 3px;"></div>

<!-- Script for Google Translate -->
<script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'en',
            includedLanguages: 'en,hi,mr,te,ur',
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
            autoDisplay: false
        }, 'google_translate_element');
    }
    document.addEventListener('DOMContentLoaded', function() {
    const profileIcon = document.querySelector('.profile-icon');
    const dropdownMenu = document.querySelector('.dropdown-menu');

    // Toggle dropdown on profile icon click
    profileIcon.addEventListener('click', function(e) {
        e.stopPropagation(); // Prevent event from bubbling
        dropdownMenu.classList.toggle('show');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!profileIcon.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.classList.remove('show');
        }
    });

    // Prevent dropdown from closing when clicking inside
    dropdownMenu.addEventListener('click', function(e) {
        e.stopPropagation();
    });
});
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>