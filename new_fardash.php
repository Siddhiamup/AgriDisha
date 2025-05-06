<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* style.css */
:root {
    --primary-color: #4CAF50;
    --secondary-color: #45a049;
    --text-color: #333;
    --light-gray: #f5f5f5;
    --border-color: #ddd;
    --shadow: 0 2px 4px rgba(0,0,0,0.1);
    --sidebar-width: 250px;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Arial', sans-serif;
}

body {
    background-color: #f0f2f5;
}

/* Dashboard Layout */
.dashboard-container {
    display: flex;
    min-height: 100vh;
}

/* Sidebar Styles */
.sidebar {
    width: var(--sidebar-width);
    background-color: white;
    padding: 20px;
    box-shadow: var(--shadow);
    position: fixed;
    height: 100vh;
    display: flex;
    flex-direction: column;
}

.logo {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 20px 0;
    border-bottom: 1px solid var(--border-color);
}

.logo img {
    width: 40px;
    height: 40px;
}

.logo h2 {
    color: var(--primary-color);
    font-size: 1.5rem;
}

.nav-menu {
    margin-top: 30px;
    flex-grow: 1;
}

.nav-menu ul {
    list-style: none;
}

.nav-menu li {
    margin-bottom: 5px;
}

.nav-menu a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 15px;
    color: var(--text-color);
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.nav-menu li.active a,
.nav-menu a:hover {
    background-color: var(--primary-color);
    color: white;
}

.nav-menu i {
    width: 20px;
}

.logout {
    padding: 20px 0;
    border-top: 1px solid var(--border-color);
}

.logout a {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #dc3545;
    text-decoration: none;
    padding: 12px 15px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.logout a:hover {
    background-color: #dc3545;
    color: white;
}

/* Main Content Area */
.main-content {
    flex: 1;
    margin-left: var(--sidebar-width);
    padding: 20px;
}

/* Top Bar */
.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background-color: white;
    border-radius: 12px;
    box-shadow: var(--shadow);
    margin-bottom: 30px;
}

.toggle-menu {
    display: none;
    cursor: pointer;
}

.search-bar {
    display: flex;
    align-items: center;
    background-color: var(--light-gray);
    border-radius: 8px;
    padding: 8px 15px;
    flex: 0 1 400px;
}

.search-bar input {
    border: none;
    background: none;
    outline: none;
    padding: 5px;
    width: 100%;
}

.user-menu {
    display: flex;
    align-items: center;
    gap: 20px;
}

.notifications {
    position: relative;
    cursor: pointer;
}

.badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background-color: #dc3545;
    color: white;
    font-size: 12px;
    padding: 2px 6px;
    border-radius: 50%;
}

.profile {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
}

.profile img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

/* Stats Cards */
.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background-color: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: var(--shadow);
    display: flex;
    align-items: center;
    gap: 20px;
}

.stat-icon {
    width: 60px;
    height: 60px;
    background-color: var(--primary-color);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
}

.stat-details h3 {
    color: var(--text-color);
    font-size: 0.9rem;
    margin-bottom: 5px;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--text-color);
    margin-bottom: 5px;
}

.stat-trend {
    font-size: 0.8rem;
}

.stat-trend.positive {
    color: var(--primary-color);
}

.stat-trend.negative {
    color: #dc3545;
}

/* Dashboard Grid */
.dashboard-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
}

.dashboard-card {
    background-color: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: var(--shadow);
}

/* Recent Activity */
.activity-list {
    margin-top: 20px;
}

.activity-item {
    display: flex;
    align-items: start;
    gap: 15px;
    padding: 15px 0;
    border-bottom: 1px solid var(--border-color);
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.activity-icon.order {
    background-color: #4CAF50;
}

.activity-icon.product {
    background-color: #2196F3;
}

.activity-icon.payment {
    background-color: #FFC107;
}

.activity-details h4 {
    margin-bottom: 5px;
    color: var(--text-color);
}

.activity-time {
    font-size: 0.8rem;
    color: #666;
}

/* Quick Actions */
.actions-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    margin-top: 20px;
}

.action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10
    </style>
</head>
<body>
    <!-- Main Dashboard Container -->
    <div class="dashboard-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="logo">
                <img src="/api/placeholder/50/50" alt="Logo">
                <h2>AgriManage</h2>
            </div>
            
            <nav class="nav-menu">
                <ul>
                    <li class="active">
                        <a href="#"><i class="fas fa-home"></i>Dashboard</a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-box"></i>Products</a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-shopping-cart"></i>Orders</a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-chart-line"></i>Reports</a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-user"></i>Profile</a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-question-circle"></i>Support</a>
                    </li>
                </ul>
            </nav>
            
            <div class="logout">
                <a href="#"><i class="fas fa-sign-out-alt"></i>Logout</a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Top Bar -->
            <div class="top-bar">
                <div class="toggle-menu">
                    <i class="fas fa-bars"></i>
                </div>
                <div class="search-bar">
                    <input type="text" placeholder="Search...">
                    <i class="fas fa-search"></i>
                </div>
                <div class="user-menu">
                    <div class="notifications">
                        <i class="fas fa-bell"></i>
                        <span class="badge">3</span>
                    </div>
                    <div class="profile">
                        <img src="/api/placeholder/40/40" alt="Profile">
                        <span>John Farmer</span>
                    </div>
                </div>
            </div>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <!-- Welcome Section -->
                <div class="welcome-section">
                    <h1>Welcome back, John!</h1>
                    <p>Here's what's happening with your farm today.</p>
                </div>

                <!-- Stats Cards -->
                <div class="stats-container">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="stat-details">
                            <h3>Total Products</h3>
                            <p class="stat-number">24</p>
                            <p class="stat-trend positive">↑ 12% from last month</p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="stat-details">
                            <h3>Pending Orders</h3>
                            <p class="stat-number">8</p>
                            <p class="stat-trend negative">↓ 5% from last month</p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="stat-details">
                            <h3>Revenue</h3>
                            <p class="stat-number">$2,845</p>
                            <p class="stat-trend positive">↑ 23% from last month</p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-details">
                            <h3>Total Customers</h3>
                            <p class="stat-number">156</p>
                            <p class="stat-trend positive">↑ 8% from last month</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity and Quick Actions -->
                <div class="dashboard-grid">
                    <!-- Recent Activity -->
                    <div class="dashboard-card recent-activity">
                        <h2>Recent Activity</h2>
                        <div class="activity-list">
                            <div class="activity-item">
                                <div class="activity-icon order">
                                    <i class="fas fa-shopping-bag"></i>
                                </div>
                                <div class="activity-details">
                                    <h4>New Order Received</h4>
                                    <p>Order #123 - Organic Tomatoes</p>
                                    <span class="activity-time">2 hours ago</span>
                                </div>
                            </div>

                            <div class="activity-item">
                                <div class="activity-icon product">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div class="activity-details">
                                    <h4>Product Updated</h4>
                                    <p>Updated stock for Organic Potatoes</p>
                                    <span class="activity-time">5 hours ago</span>
                                </div>
                            </div>

                            <div class="activity-item">
                                <div class="activity-icon payment">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                                <div class="activity-details">
                                    <h4>Payment Received</h4>
                                    <p>Payment for Order #120</p>
                                    <span class="activity-time">1 day ago</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="dashboard-card quick-actions">
                        <h2>Quick Actions</h2>
                        <div class="actions-grid">
                            <button class="action-btn">
                                <i class="fas fa-plus"></i>
                                Add New Product
                            </button>
                            <button class="action-btn">
                                <i class="fas fa-list"></i>
                                View Orders
                            </button>
                            <button class="action-btn">
                                <i class="fas fa-chart-bar"></i>
                                Sales Report
                            </button>
                            <button class="action-btn">
                                <i class="fas fa-cog"></i>
                                Settings
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="script.js"></script>
</body>
</html>