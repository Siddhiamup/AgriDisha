<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <title>Farmer Dashboard</title>
    <style>
/* Base styles and color variables */
:root {
    --primary-green: #1e8449;
    --secondary-green: #27ae60;
    --light-green: #daf1de;
    --accent-brown: #8b4513;
    --neutral-beige: #f5f0e1;
    --text-dark: #2c3e50;
    --text-light: #ffffff;
    --shadow: rgba(0, 0, 0, 0.1);
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f8f9fa;
}

.container {
    display: flex;
}

/* Sidebar styling */
.sidebar {
    width: 250px;
    background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
    color: var(--text-light);
    padding: 20px;
    height: 100vh;
    box-shadow: 2px 0 10px var(--shadow);
}

.sidebar h2 {
    text-align: center;
    padding-bottom: 15px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    margin-bottom: 25px;
}

.sidebar ul {
    list-style-type: none;
    padding: 0;
}

.sidebar ul li {
    margin: 15px 0;
}

.sidebar ul li a {
    color: var(--text-light);
    text-decoration: none;
    display: flex;
    align-items: center;
    padding: 12px 15px;
    border-radius: 8px;
    transition: all 0.3s ease;
    background-color: rgba(255, 255, 255, 0.1);
}

.sidebar ul li a:hover {
    background-color: rgba(255, 255, 255, 0.2);
    transform: translateX(5px);
}

.sidebar ul li a i {
    margin-right: 12px;
}

/* Main content area */
.content {
    flex: 1;
    padding: 30px;
    background-color: #f8f9fa;
}

/* Dashboard cards */
.dashboard-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 25px;
    margin-bottom: 30px;
}

.card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px var(--shadow);
    transition: transform 0.3s ease;
    border-left: 4px solid var(--secondary-green);
}

.card:hover {
    transform: translateY(-5px);
}

.card h3 {
    color: var(--text-dark);
    margin-bottom: 15px;
    font-size: 16px;
}

.card p {
    font-size: 24px;
    font-weight: bold;
    color: var(--primary-green);
    margin: 0;
}

/* Table styling */
.table-container {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 12px var(--shadow);
    margin-top: 25px;
}

.table-container table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.table-container table th {
    background-color: var(--light-green);
    color: var(--text-dark);
    padding: 15px;
    text-align: left;
    font-weight: 600;
    border-bottom: 2px solid var(--secondary-green);
}

.table-container table td {
    padding: 15px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

/* Form styling */
.form-container {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 12px var(--shadow);
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: var(--text-dark);
    font-weight: 500;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: var(--secondary-green);
    outline: none;
    box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
}

/* Button styling */
button {
    background-color: var(--secondary-green);
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s ease;
}

button:hover {
    background-color: var(--primary-green);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(39, 174, 96, 0.2);
}

/* Tab content styling */
.tab-content {
    display: none;
    animation: fadeIn 0.3s ease-in;
}

.tab-content.active {
    display: block;
}

/* Header styling */
.header {
    margin-bottom: 30px;
}

.header h1 {
    color: var(--text-dark);
    font-size: 28px;
    margin-bottom: 10px;
}

/* Animation keyframes */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive design */
@media (max-width: 768px) {
    .container {
        flex-direction: column;
    }
    
    .sidebar {
        width: 100%;
        height: auto;
        min-height: auto;
    }
    
    .content {
        padding: 20px;
    }
    
    .dashboard-cards {
        grid-template-columns: 1fr;
    }
    
    .card {
        margin-bottom: 15px;
    }
}    </style>
    <script>
        let products = [];

        function showTab(tabName) {
            // Hide all tabs
            const tabs = document.querySelectorAll('.tab-content');
            tabs.forEach(tab => {
                tab.classList.remove('active');
            });

            // Show the selected tab
            const activeTab = document.getElementById(tabName);
            activeTab.classList.add('active');
        }

        // Default view - show dashboard on page load
        window.onload = () => {
            showTab('dashboard');
        };

        // Function to handle the "Respond to Order" action
        function respondToOrder(orderId) {
            alert(`Responding to Order #${orderId}`);
        }

        // Function to update profile
        function updateProfile(event) {
            event.preventDefault();
            alert("Profile updated successfully!");
        }

        // Function to change password
        function changePassword(event) {
            event.preventDefault();
            const currentPassword = document.getElementById('currentPassword').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            if (newPassword !== confirmPassword) {
                alert("Passwords do not match!");
                return;
            }

            alert("Password changed successfully!");
        }

    // Function to add a new product
    function addProduct(event) {
            event.preventDefault();
            const name = document.getElementById('productName').value;
            const description = document.getElementById('description').value;
            const quantity = document.getElementById('quantity').value;
            const price = document.getElementById('price').value;
            const image = document.getElementById('image').files[0];

            // Add product to the list
            const newProduct = {
                name,
                description,
                quantity,
                price,
                image: image ? URL.createObjectURL(image) : null
            };

            products.push(newProduct);

            // Clear form fields
            document.getElementById('productName').value = '';
            document.getElementById('description').value = '';
            document.getElementById('quantity').value = '';
            document.getElementById('price').value = '';
            document.getElementById('image').value = '';

            // Refresh the products table
            displayProducts();

            // Alert user
            alert('Product added successfully!');
        }

        // Function to display the list of products
        function displayProducts() {
            const productTableBody = document.getElementById('productsTableBody');
            productTableBody.innerHTML = '';

            products.forEach((product, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${product.name}</td>
                    <td>${product.description}</td>
                    <td>${product.quantity}</td>
                    <td>${product.price}</td>
                    <td><img src="${product.image}" alt="Product Image" width="50"></td>
                    <td>
                        <button onclick="editProduct(${index})">Edit</button>
                        <button onclick="removeProduct(${index})">Remove</button>
                    </td>
                `;
                productTableBody.appendChild(row);
            });
        }

        // Function to remove a product
        function removeProduct(index) {
            products.splice(index, 1);  // Remove the product from the array
            displayProducts();  // Refresh the table
            alert('Product removed successfully!');
        }

        // Function to edit a product
        function editProduct(index) {
            const product = products[index];
            document.getElementById('productName').value = product.name;
            document.getElementById('description').value = product.description;
            document.getElementById('quantity').value = product.quantity;
            document.getElementById('price').value = product.price;
            document.getElementById('image').value = '';
            showTab('addProductForm');
            }
                </script>
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <h2>Farmer Dashboard</h2>
            <ul>
                <li><a href="#" onclick="showTab('dashboard')"> <i class="fas fa-home"></i>&nbsp;&nbsp;Dashboard</a></li>
                <li><a href="#" onclick="showTab('products')"> <i class="fas fa-box"></i>&nbsp;&nbsp;Manage Products</a></li>
                <li><a href="#" onclick="showTab('orders')"> <i class="fas fa-shopping-cart"></i>&nbsp;&nbsp;Orders</a></li>
                <li><a href="#" onclick="showTab('payments')"> <i class="fas fa-money-bill-wave"></i>&nbsp;&nbsp;Payment History</a></li>
                <li><a href="#" onclick="showTab('profile')"> <i class="fas fa-user"></i>&nbsp;&nbsp;Profile</a></li>
                <li><a href="#" onclick="showTab('settings')"><i class="fas fa-cog"></i> 
                &nbsp;&nbsp;Settings</a></li>
            </ul>
        </div>

        <div class="content">
            <!-- Dashboard Section -->
              <!-- Header -->
            <div class="header">
                <h1>Welcome, <span id="farmerName">Farmer</span>!</h1>
               
            </div>
            <div class="tab-content" id="dashboard">
                <div class="dashboard-cards">
                    <div class="card">
                        <h3>Total Products</h3>
                        <p>24</p>
                    </div>
                    <div class="card">
                        <h3>Active Orders</h3>
                        <p>12</p>
                    </div>
                    <div class="card">
                        <h3>Total Revenue</h3>
                        <p>₹45,000</p>
                    </div>
                    <div class="card">
                        <h3>Pending Payments</h3>
                        <p>₹5,000</p>
                    </div>
                </div>

                <div class="table-container">
                    <h2>Recent Activity</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Activity</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="recentActivityBody">
                            <tr>
                                <td>2025-01-25</td>
                                <td>Added 10 new products</td>
                                <td>Completed</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Products Section -->
<!-- Products Section -->
<div class="tab-content" id="products">
                <h2>Manage Products</h2>
                <button onclick="showTab('addProductForm')">Add Product</button>

                <div class="table-container">
                    <h2>My Products</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="productsTableBody">
                            <!-- Products will be displayed here -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Add Product Form Section -->
            <div class="tab-content" id="addProductForm">
                <div class="form-container">
                    <h2>Add New Product</h2>
                    <form id="addProductForm" onsubmit="addProduct(event)">
                        <div class="form-group">
                            <label for="productName">Product Name</label>
                            <input type="text" id="productName" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="quantity">Quantity (kg)</label>
                            <input type="number" id="quantity" min="0" step="0.1" required>
                        </div>
                        <div class="form-group">
                            <label for="price">Price per kg (₹)</label>
                            <input type="number" id="price" min="0" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="image">Product Image</label>
                            <input type="file" id="image" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn">Add Product</button>
                    </form>
                    <button onclick="showTab('products')">Back to Products</button>
                </div>
            </div>
            <!-- Orders Section -->
            <div class="tab-content" id="orders">
                <h2>Orders</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Product</th>
                                <th>Buyer</th>
                                <th>Quantity</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#1234</td>
                                <td>Rice</td>
                                <td>John Doe</td>
                                <td>20 kg</td>
                                <td>₹800</td>
                                <td>Pending</td>
                                <td><button onclick="respondToOrder(1234)">Respond</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Payments Section -->
            <div class="tab-content" id="payments">
                <h2>Payment History</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2025-01-20</td>
                                <td>₹5000</td>
                                <td>Completed</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Profile Section -->
            <div class="tab-content" id="profile">
                <h2>Profile</h2>
                <form onsubmit="updateProfile(event)">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" value="John Doe" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" value="johndoe@example.com" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="tel" id="phone" value="1234567890" required>
                    </div>
                    <button type="submit" class="btn">Update Profile</button>
                </form>
            </div>

            <!-- Settings Section -->
            <div class="tab-content" id="settings">
                <h2>Settings</h2>
                <form onsubmit="changePassword(event)">
                    <div class="form-group">
                        <label for="currentPassword">Current Password</label>
                        <input type="password" id="currentPassword" required>
                    </div>
                    <div class="form-group">
                        <label for="newPassword">New Password</label>
                        <input type="password" id="newPassword" required>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Confirm New Password</label>
                        <input type="password" id="confirmPassword" required>
                    </div>
                    <button type="submit" class="btn">Change Password</button>
                </form>
            </div>

        </div>
    </div>

</body>

</html>
