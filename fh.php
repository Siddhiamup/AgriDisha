<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="ASSETS/CSS/farmerdash.css">

    <title>Farmer Dashboard</title>

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
                                <th>Category</th>
                                <th>Product Name</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="productsTableBody">
                        <?php
                        include 'db.php';
                        $sql = "SELECT * FROM products ORDER BY id DESC";
$result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['productName']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['price']) . "</td>";
                    if (!empty($row['image_path'])) {
                        echo "<td><img src='" . htmlspecialchars($row['image_path']) . "' alt='Product Image' width='100'></td>";
                    } else {
                        echo "<td>No image available</td>";
                    }
                    echo"<td><button>Edit</button>
                    <button>Remove</button></td>";

                     echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No products found.</td></tr>";
            }
            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Add Product Form Section -->
<!-- Add Product Form Section -->
<div class="tab-content" id="addProductForm">
    <div class="form-container">
        <h2>Add New Product</h2>

        <!-- Error message container -->
        <div id="errorMessages"></div>

        <form id="addProductForm" method="post" action="ASSETS/DATABASE/add_product.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <option value="">Select Category</option>
                    <option value="grains">Grains</option>
                    <option value="pulses">Pulses</option>
                    <option value="spices">Spices</option>
                    <option value="vegetables">Vegetables</option>
                </select>
            </div>
            <div class="form-group">
                <label for="productName">Product Name</label>
                <input type="text" id="productName" name="productName" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity (kg)</label>
                <input type="number" id="quantity" min="0" step="0.1" name="quantity" required>
            </div>
            <div class="form-group">
                <label for="price">Price per kg (₹)</label>
                <input type="number" id="price" min="0" step="0.01" name="price" required>
            </div>
            <div class="form-group">
                <label for="image">Product Image</label>
                <input type="file" id="image_path" name="image_path" required>
            </div>
            <button type="submit" class="btn" >Add Product</button>
        </form>
        <button onclick="showTab('products')">Back to Products</button>
    </div>
</div>

<script>
    // Capture errors from PHP if available
    <?php
    if (!empty($errors)) {
        echo "var errors = " . json_encode($errors) . ";";
        echo "var errorMessagesDiv = document.getElementById('errorMessages');";
        echo "errors.forEach(function(error) {";
        echo "    var p = document.createElement('p');";
        echo "    p.style.color = 'red';";
        echo "    p.innerText = error;";
        echo "    errorMessagesDiv.appendChild(p);";
        echo "});";
    }
    ?>
</script>
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
