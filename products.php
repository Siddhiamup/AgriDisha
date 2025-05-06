<style>
    /* Agriculture Management System Form Styling */
.form-container {
    max-width: 100%;
    margin: 0 auto;
    padding: 20px;
    background-color: #f8f9fa;
    border-radius: 8px;
}

.form-title {
    background-color:rgb(22, 139, 71); /* Darker green for agriculture theme */
    color: white;
    padding: 12px 15px;
    margin-bottom: 20px;
    font-size: 20px;
    border-radius: 4px;
    text-align: center;
}

/* Grid layout for form fields */
.form-layout {
    display: grid;
    grid-template-columns: 1fr 1fr; /* Two equal columns */
    gap: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
    font-size: 14px;
    color: rgb(22, 139, 71); /* Dark green for agriculture theme */
}

.form-group input[type="text"],
.form-group input[type="number"],
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 8px 10px;
    border: 1px solid #c5e1a5; /* Light green border */
    border-radius: 4px;
    font-size: 14px;
}

.form-group textarea {
    height: 100px;
    resize: vertical;
}

.input-group {
    display: flex;
    align-items: center;
}

.currency-symbol {
    display: inline-block;
    padding: 8px 12px;
    background: #e8f5e9; /* Very light green */
    border: 1px solid #c5e1a5;
    border-right: none;
    border-radius: 4px 0 0 4px;
    font-size: 14px;
    color:rgb(22, 139, 71);
}

.input-group input {
    border-radius: 0 4px 4px 0;
}

.unit-select {
    width: auto !important;
    min-width: 80px;
    max-width: 120px;
    margin-left: 5px;
}

/* Checkbox specific styling */
.form-check {
    margin-bottom: 8px;
    display: flex;
    align-items: center;
}

/* Fix for checkbox width */
.form-check input[type="checkbox"] {
    width: 16px;
    height: 16px;
    margin-right: 8px;
    cursor: pointer;
    flex-shrink: 0; /* Prevent checkbox from shrinking */
}

/* Delivery options layout */
.delivery-options {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    border-left: 4px solid rgb(22, 139, 71);
    padding: 10px;
    background-color: #f1f8e9;
    border-radius: 0 4px 4px 0;
}

/* Delivery options section heading */
.delivery-options-header {
    margin-bottom: 8px;
    color:rgb(22, 139, 71);
    font-weight: 500;
}

/* Option checkboxes in a horizontal layout */
.delivery-checkbox-row {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
}

/* Individual checkbox container */
.checkbox-container {
    display: flex;
    align-items: center;
    margin-right: 20px;
}

/* Action buttons */
.form-actions {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #c5e1a5;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

.btn {
    padding: 8px 18px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 500;
    font-size: 14px;
    transition: background-color 0.2s;
}

.btn-primary {
    background-color: rgb(22, 139, 71); /* Dark green */
    color: white;
}

.btn-primary:hover {
    background-color:rgb(18, 122, 61); /* Darker green on hover */
}

.btn-secondary {
    background-color: #78909c;
    color: white;
}

.btn-secondary:hover {
    background-color: #607d8b;
}

/* File input styling */
input[type="file"] {
    padding: 6px;
    border: 1px dashed #aed581;
    border-radius: 4px;
    width: 100%;
}

/* Error messages styling */
#errorMessages {
    margin-bottom: 15px;
}

#errorMessages p {
    margin: 5px 0;
    padding: 8px 12px;
    background-color: #ffebee;
    border-left: 4px solid #e57373;
    font-size: 14px;
    border-radius: 0 4px 4px 0;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .form-layout {
        grid-template-columns: 1fr;
    }
    
    .delivery-options {
        grid-template-columns: 1fr;
    }
}

</style>
<?php
if (isset($_GET['action']) && $_GET['action'] == 'add') {
    ?>
  
     
   <div class="form-container">
    <h2 class="form-title">Add New Product</h2>
    
    <!-- Error message container -->
    <div id="errorMessages"></div>

    <form action="add_product.php" method="POST" enctype="multipart/form-data">
        <div class="form-layout">
            <!-- First column -->
            <div>
                <div class="form-group">
                    <label for="name">Product Name</label>
                    <input type="text" id="name" name="name" required 
                        placeholder="Enter product name (e.g., Wheat, Turmeric)" maxlength="255">
                </div>
                
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
                    <label for="price">Price per Unit</label>
                    <div class="input-group">
                        <span class="currency-symbol">₹</span>
                        <input type="number" id="price" name="price" step="0.01" min="0" required placeholder="Enter price">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" placeholder="Brief product description" rows="1"></textarea>
                </div>
            </div>
            
            <!-- Second column -->
            <div>
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <div class="input-group">
                        <input type="number" id="quantity" name="quantity" min="1" required placeholder="Total available quantity">
                        <select name="unit" class="unit-select">
                            <option value="kg">KG</option>
                            <option value="quintal">Quintal</option>
                            <option value="ton">Ton</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location" required placeholder="Farm/Delivery Location">
                </div>
                
                <div class="form-group">
                    <label for="product_image">Product Image</label>
                    <input type="file" id="image_url" name="image_url" accept="image/jpeg,image/png,image/jpg">
                </div>
                
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" id="organic_certified" name="organic_certified" value="1">
                        <label for="organic_certified">Organic Certified</label>
                    </div>
                </div>
            </div>
            
            <!-- Checkboxes section - spans both columns -->
            <div class="checkboxes-section">
                <label>Delivery Options</label>
                <div class="delivery-options">
                    <div class="form-check">
                        <input type="checkbox" id="local_pickup" name="delivery_options[]" value="local_pickup">
                        <label for="local_pickup">Local Pickup Available</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="home_delivery" name="delivery_options[]" value="home_delivery">
                        <label for="home_delivery">Home Delivery</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="bulk_order" name="delivery_options[]" value="bulk_order">

                        <label for="bulk_order">Bulk Order Discount</label>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-actions">
            <input type="hidden" name="seller_id" value="<?php echo $_SESSION['id']; ?>">
            <button type="submit" class="btn btn-primary">Add Product</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
        </div>
    </form>
</div>

    <script>
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

    <?php
} elseif (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ? AND seller_id = ?");
    $stmt->bind_param("ii", $product_id, $_SESSION['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    if (!$product) {
        echo "<div class='alert alert-danger'>Product not found or you don't have permission to edit it.</div>";
        exit;
    }

    // Get delivery options from the delivery_options column
    $delivery_options = [];
    if (!empty($product['delivery_options'])) {
        $delivery_options = explode(',', $product['delivery_options']);
    }
?>
  
  <?php if (isset($_GET['updated']) && $_GET['updated'] == 1): ?>
    <div class="alert alert-success">
        Product updated successfully!
    </div>
<?php endif; ?>

<div class="form-container">
    <h2 class="form-title">Edit Product</h2>
    
    <!-- Error message container -->
    <div id="errorMessages"></div>

    <form action="ASSETS/DATABASE/edit_product.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
        <div class="form-layout">
            <!-- First column -->
            <div>
                <div class="form-group">
                    <label for="name">Product Name</label>
                    <input type="text" id="name" name="name" required 
                        placeholder="Enter product name (e.g., Wheat, Turmeric)" maxlength="255"
                        value="<?php echo htmlspecialchars($product['name']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category" name="category" required>
                        <option value="">Select Category</option>
                        <option value="grains" <?php echo ($product['category'] == 'grains') ? 'selected' : ''; ?>>Grains</option>
                        <option value="pulses" <?php echo ($product['category'] == 'pulses') ? 'selected' : ''; ?>>Pulses</option>
                        <option value="spices" <?php echo ($product['category'] == 'spices') ? 'selected' : ''; ?>>Spices</option>
                        <option value="vegetables" <?php echo ($product['category'] == 'vegetables') ? 'selected' : ''; ?>>Vegetables</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="price">Price per Unit</label>
                    <div class="input-group">
                        <span class="currency-symbol">₹</span>
                        <input type="number" id="price" name="price" step="0.01" min="0" required placeholder="Enter price"
                               value="<?php echo htmlspecialchars($product['price']); ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" placeholder="Brief product description" rows="1"><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>
            </div>
            
            <!-- Second column -->
            <div>
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <div class="input-group">
                        <input type="number" id="quantity" name="quantity" min="1" required placeholder="Total available quantity"
                               value="<?php echo htmlspecialchars($product['quantity']); ?>">
                        <select name="unit" class="unit-select">
                            <option value="kg" <?php echo ($product['unit'] == 'kg') ? 'selected' : ''; ?>>KG</option>
                            <option value="quintal" <?php echo ($product['unit'] == 'quintal') ? 'selected' : ''; ?>>Quintal</option>
                            <option value="ton" <?php echo ($product['unit'] == 'ton') ? 'selected' : ''; ?>>Ton</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location" required placeholder="Farm/Delivery Location"
                           value="<?php echo htmlspecialchars($product['location']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="product_image">Product Image</label>
                    <?php if (!empty($product['image_url'])): ?>
                        <p>Current image: <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="Product Image" width="50"></p>
                    <?php endif; ?>
                    <input type="file" id="image_url" name="image_url" accept="image/jpeg,image/png,image/jpg">
                </div>
                
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" id="organic_certified" name="organic_certified" value="1"
                               <?php echo ($product['organic_certified'] == 1) ? 'checked' : ''; ?>>
                        <label for="organic_certified">Organic Certified</label>
                    </div>
                </div>
            </div>
            
            <!-- Checkboxes section - spans both columns -->
            <div class="checkboxes-section">
                <label>Delivery Options</label>
                <div class="delivery-options">
                    <div class="form-check">
                        <input type="checkbox" id="local_pickup" name="delivery_options[]" value="local_pickup"
                               <?php echo (in_array('local_pickup', $delivery_options)) ? 'checked' : ''; ?>>
                        <label for="local_pickup">Local Pickup Available</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="home_delivery" name="delivery_options[]" value="home_delivery"
                               <?php echo (in_array('home_delivery', $delivery_options)) ? 'checked' : ''; ?>>
                        <label for="home_delivery">Home Delivery</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="bulk_order" name="delivery_options[]" value="bulk_order"
                               <?php echo (in_array('bulk_order', $delivery_options)) ? 'checked' : ''; ?>>
                        <label for="bulk_order">Bulk Order Discount</label>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-actions">
            <input type="hidden" name="seller_id" value="<?php echo $_SESSION['id']; ?>">
            <button type="submit" class="btn btn-primary">Update Product</button>
            <a href="?tab=products" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
<script>
    <?php
    if (isset($_SESSION['product_errors']) && !empty($_SESSION['product_errors'])) {
        echo "var errors = " . json_encode($_SESSION['product_errors']) . ";";
        echo "var errorMessagesDiv = document.getElementById('errorMessages');";
        echo "errors.forEach(function(error) {";
        echo "    var p = document.createElement('p');";
        echo "    p.style.color = 'red';";
        echo "    p.innerText = error;";
        echo "    errorMessagesDiv.appendChild(p);";
        echo "});";
        // Clear the errors after displaying them
        unset($_SESSION['product_errors']);
    }
    ?>
</script>

    <?php
} else {
   // Products listing code remains unchanged
    $products_query = "SELECT * FROM products WHERE seller_id = ? ORDER BY id DESC";
    $stmt = $conn->prepare($products_query);
    $stmt->bind_param("i", $_SESSION['id']);
    $stmt->execute();
    $products = $stmt->get_result();
    ?>
    <h2>Manage Products</h2>
    <button onclick="window.location.href='?tab=products&action=add'">Add Product</button>

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
            <tbody>
                <?php while ($product = $products->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['category']); ?></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars($product['description']); ?></td>
                        <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                        <td>₹<?php echo htmlspecialchars($product['price']); ?></td>
                        <td>
                            <?php if (!empty($product['image_url'])): ?>
                                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="Product Image" width="50">
                            <?php else: ?>
                                No image
                            <?php endif; ?>
                        </td>

                        <td>
                            <button onclick="editProduct(<?php echo $product['id']; ?>)" class="btn-edit">Edit</button>
                            <button onclick="deleteProduct(<?php echo $product['id']; ?>)" class="btn-delete">Remove</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <script>
        function deleteProduct(productId) {
            if (confirm('Are you sure you want to delete this product?')) {
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'ASSETS/DATABASE/delete_product.php?id=' + productId, true);
                xhr.onload = function () {
                    console.log("Raw Response:", xhr.responseText);
                    if (xhr.status === 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                const productRow = document.querySelector(`button[onclick="deleteProduct(${productId})"]`).closest('tr');
                                if (productRow) {
                                    productRow.style.transition = 'opacity 0.5s';
                                    productRow.style.opacity = '0';
                                    setTimeout(function() {
                                        productRow.remove();
                                    }, 500);
                                }
                                alert('Product removed successfully!');
                            } else {
                                alert(response.message || 'Error deleting product');
                            }
                        } catch (e) {
                            console.error('Error parsing JSON:', e);
                            console.error('Response Text:', xhr.responseText);
                            alert('Error processing response');
                        }
                    } else {
                        alert('Error deleting product');
                    }
                };
                xhr.send();
            }
        }

       function editProduct(productId) {
    window.location.href = '?tab=products&action=edit&id=' + productId;
}
    </script>
    <?php
}
?>