<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - AgriDisha | Revolutionizing Agricultural Trade</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="ASSETS/CSS/about.css"> -->
    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background: #f8f9fa;
    color: #333;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

.hero-section {
    background: url('/AMSDemo/ASSETS/IMAGES/f6.jpg') center/cover no-repeat;
    min-height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: white;
    padding: 2rem;
    position: relative;
    margin-bottom: 2rem;
}

.hero-section::before {
    content: "";
    position: absolute;
    top: 0; left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
}

.hero-content {
    position: relative;
    z-index: 1;
    text-align: center;
}

.hero-content h1 {
    font-size: 2.5rem;
    font-weight: bold;
    margin-bottom: 1rem;
}

.stats-section {
    position: relative;
    margin-top: -60px;
    z-index: 2;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1.5rem;
    padding: 0 1rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-align: center;
    transform: translateY(50px);
    opacity: 0;
    animation: slideUp 0.6s ease forwards;
}

.stat-card:nth-child(1) { animation-delay: 0.1s; }
.stat-card:nth-child(2) { animation-delay: 0.3s; }
.stat-card:nth-child(3) { animation-delay: 0.5s; }
.stat-card:nth-child(4) { animation-delay: 0.7s; }

@keyframes slideUp {
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.stat-icon {
    font-size: 2rem;
    color: #4CAF50;
    margin-bottom: 0.5rem;
}

.stat-number {
    font-size: 2rem;
    color: #4CAF50;
    font-weight: bold;
    margin: 0.5rem 0;
}

.section-title {
    text-align: center;
    margin: 3rem 0;
}

.section-title h2 {
    font-size: 2.2rem;
    color: #4CAF50;
    margin-bottom: 0.5rem;
}

.section-title p {
    font-size: 1.1rem;
    color: #666;
}

.about-content {
    max-width: 800px;
    margin: 0 auto;
    text-align: center;
    line-height: 1.6;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
}

.offerings-section {
    padding: 2rem 0;
}

.offerings-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
    padding: 0 1rem;
}

.offering-card {
    background: white;
    padding: 1.2rem;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-align: center;
    transform: translateY(30px);
    opacity: 0;
    animation: slideUp 0.6s ease forwards;
}

.offering-card:nth-child(1) { animation-delay: 0.1s; }
.offering-card:nth-child(2) { animation-delay: 0.3s; }
.offering-card:nth-child(3) { animation-delay: 0.5s; }
.offering-card:nth-child(4) { animation-delay: 0.7s; }

.offering-icon {
    font-size: 1.8rem;
    color: #4CAF50;
    margin-bottom: 0.5rem;
}

.offering-card h3 {
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
    color: #333;
}

.offering-card p {
    font-size: 0.9rem;
    color: #666;
    line-height: 1.4;
}

@media (max-width: 992px) {
    .offerings-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .hero-content h1 {
        font-size: 2rem;
    }
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .offerings-grid {
        grid-template-columns: 1fr;
    }
    .stat-number {
        font-size: 1.8rem;
    }
    .section-title h2 {
        font-size: 2rem;
    }
}

    </style>
</head>
<body>
<?php
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

// Get platform statistics
function getPlatformStats($conn) {
    $stats = array();
    
    // Get total farmers
    $query = "SELECT COUNT(*) as count FROM users WHERE role = 'Farmer'";
    $result = mysqli_query($conn, $query);
    $stats['farmers'] = mysqli_fetch_assoc($result)['count'];
    
    // Get total buyers
    $query = "SELECT COUNT(*) as count FROM users WHERE role = 'Buyer'";
    $result = mysqli_query($conn, $query);
    $stats['buyers'] = mysqli_fetch_assoc($result)['count'];
    
    // Get total products
    $query = "SELECT COUNT(*) as count FROM products";
    $result = mysqli_query($conn, $query);
    $stats['products'] = mysqli_fetch_assoc($result)['count'];
    
    // Get total transaction value
    $query = "SELECT SUM(amount) as total FROM transactions WHERE payment_status = 'Paid'";
    $result = mysqli_query($conn, $query);
    $stats['transaction_value'] = mysqli_fetch_assoc($result)['total'] ?? 0;
    
    return $stats;
}

// Get recent transactions for possible future use
function getRecentTransactions($conn) {
    $query = "SELECT t.amount, u.username as buyer_name, p.name as product_name 
             FROM transactions t 
             JOIN orders o ON t.order_id = o.id 
             JOIN users u ON o.buyer_id = u.id 
             JOIN products p ON o.product_id = p.id 
             WHERE t.payment_status = 'Paid' 
             ORDER BY t.created_at DESC 
             LIMIT 3";
    
    $result = mysqli_query($conn, $query);
    $transactions = array();
    
    while($row = mysqli_fetch_assoc($result)) {
        $transactions[] = $row;
    }
    
    return $transactions;
}

$stats = getPlatformStats($conn);
$recent_transactions = getRecentTransactions($conn);
?>

    <section class="hero-section">
        <div class="hero-content">
            <h1>About AgriDisha</h1>
            <p>Transforming Agricultural Trade Through Innovation</p>
        </div>
    </section>

    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-card">
                    <i class="fas fa-users stat-icon"></i>
                    <div class="stat-number"><?php echo number_format($stats['farmers']); ?>+</div>
                    <p>Registered Farmers</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-store stat-icon"></i>
                    <div class="stat-number"><?php echo number_format($stats['buyers']); ?>+</div>
                    <p>Active Buyers</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-shopping-basket stat-icon"></i>
                    <div class="stat-number"><?php echo number_format($stats['products']); ?>+</div>
                    <p>Products Listed</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-indian-rupee-sign stat-icon"></i>
                    <div class="stat-number">₹<?php echo number_format($stats['transaction_value']/10000000, 1); ?>Cr+</div>
                    <p>Transaction Value</p>
                </div>
            </div>
        </div>
    </section>

    <section class="mission-section">
        <div class="container">
            <div class="section-title">
                <h2>Our Mission</h2>
                <p>Empowering Indian Agriculture Through Technology</p>
            </div>
            <div class="about-content">
                <p>
                    AgriDisha is an innovative online agricultural marketplace dedicated to revolutionizing the farming sector by connecting farmers directly with buyers. We eliminate intermediaries and ensure farmers receive fair prices for their produce while providing buyers with access to fresh, high-quality crops.
                </p>
                <p>
                    Through our AI-powered platform, we're creating a transparent, efficient, and sustainable agricultural ecosystem that benefits both farmers and consumers. Our commitment to innovation and farmer empowerment drives us to continuously improve and expand our services.
                </p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <h3><i class="fas fa-bullseye"></i> Our Vision</h3>
                    <p>Creating a future where every farmer has access to fair markets, advanced technology, and sustainable farming solutions, leading to a stronger and more efficient agricultural economy.</p>
                </div>
                <div class="feature-card">
                    <h3><i class="fas fa-chart-line"></i> Our Goals</h3>
                    <p>Eliminate intermediaries, enhance farmer income through direct market access, ensure transparent trade practices, and leverage cutting-edge technology for agricultural growth.</p>
                </div>
                <div class="feature-card">
                    <h3><i class="fas fa-handshake"></i> Our Values</h3>
                    <p>Built on the foundations of transparency, innovation, farmer empowerment, and sustainable agriculture, we strive to create lasting positive impact in the farming community.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="offerings-section">
        <div class="container">
            <div class="section-title">
                <h2>What We Offer</h2>
                <p>Comprehensive Solutions for Agricultural Success</p>
            </div>
            <div class="offerings-grid">
                <div class="offering-card">
                    <i class="fas fa-balance-scale offering-icon"></i>
                    <h3>Fair Trade</h3>
                    <p>Direct farmer-to-buyer connections ensuring better profits and transparency</p>
                </div>
                <div class="offering-card">
                    <i class="fas fa-leaf offering-icon"></i>
                    <h3>Quality Produce</h3>
                    <p>Fresh, high-quality crops sourced directly from verified farmers</p>
                </div>
                <div class="offering-card">
                    <i class="fas fa-shield-alt offering-icon"></i>
                    <h3>Secure Payments</h3>
                    <p>Safe and transparent transaction system with instant settlements</p>
                </div>
                <div class="offering-card">
                    <i class="fas fa-chart-bar offering-icon"></i>
                    <h3>Market Insights</h3>
                    <p>AI-powered analytics for demand prediction and pricing optimization</p>
                </div>
            </div>
        </div>
    </section>

   

    <?php mysqli_close($conn); ?>

</body>
</html>