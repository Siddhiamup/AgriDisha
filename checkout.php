<?php
// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Check if user is logged in and is a buyer
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'Buyer') {
    // Store error message in session
    $_SESSION['error'] = "You must be logged in as a buyer to checkout.";
    // Redirect to login page
    header("Location: login.php?redirect=checkout.php");
    exit;
}

// Database connection
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

$buyer_id = $_SESSION['id'];

// Get cart items with product details
$query = "SELECT c.*, p.id as product_id, p.name, p.price, p.unit, p.image_url, p.quantity as available_quantity, 
          u.username as seller_name, p.seller_id 
          FROM cart c 
          JOIN products p ON c.product_id = p.id 
          JOIN users u ON p.seller_id = u.id 
          WHERE c.buyer_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $buyer_id);
$stmt->execute();
$result = $stmt->get_result();

// If cart is empty, redirect to cart page
if ($result->num_rows == 0) {
    $_SESSION['error'] = "Your cart is empty. Please add items before checkout.";
    header("Location: index.php?tab=cart");
    exit;
}

// Get user information for pre-filling address form
$user_query = "SELECT * FROM users WHERE id = ?";
$user_stmt = $conn->prepare($user_query);
$user_stmt->bind_param("i", $buyer_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user_data = $user_result->fetch_assoc();

// Generate unique payment reference ID
function generatePaymentId() {
    return 'AGRI' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8)) . time();
}

// Process payment function
function processPayment($payment_method, $amount, $card_info = null) {
    // In a real application, this would connect to a payment gateway
    // For demonstration purposes, we're simulating payment processing
    
    $payment_id = generatePaymentId();
    $payment_status = 'Pending';
    $payment_error = '';
    
    switch ($payment_method) {
        case 'Credit Card':
        case 'Debit Card':
            // Validate card details (basic validation)
            if (isset($card_info['card_number']) && isset($card_info['cvv']) &&
                !empty($card_info['card_number']) && 
                strlen($card_info['card_number']) >= 13 && 
                strlen($card_info['card_number']) <= 19 &&
                !empty($card_info['cvv']) && 
                strlen($card_info['cvv']) >= 3) {
                // Simulate successful payment (95% success rate for demo)
                if (rand(1, 100) <= 95) {
                    $payment_status = 'Paid';
                } else {
                    $payment_status = 'Failed';
                    $payment_error = 'Payment declined by bank. Please try again or use another payment method.';
                }
            } else {
                $payment_status = 'Failed';
                $payment_error = 'Invalid card details provided.';
            }
            break;
            
        case 'UPI':
            // UPI validation
            if (isset($card_info['upi_id']) && 
                !empty($card_info['upi_id']) && 
                strpos($card_info['upi_id'], '@') !== false) {
                // Simulate successful payment
                if (rand(1, 100) <= 95) {
                    $payment_status = 'Paid';
                } else {
                    $payment_status = 'Failed';
                    $payment_error = 'UPI payment failed. Please try again.';
                }
            } else {
                $payment_status = 'Failed';
                $payment_error = 'Invalid UPI ID provided.';
            }
            break;
            
        case 'Net Banking':
            // Net Banking validation
            if (isset($card_info['bank_name']) && !empty($card_info['bank_name'])) {
                // Simulate successful payment
                if (rand(1, 100) <= 95) {
                    $payment_status = 'Paid';
                } else {
                    $payment_status = 'Failed';
                    $payment_error = 'Net Banking payment failed. Please try again.';
                }
            } else {
                $payment_status = 'Failed';
                $payment_error = 'Bank selection is required.';
            }
            break;
            
        case 'Cash on Delivery':
            // COD doesn't need immediate payment processing
            $payment_status = 'Unpaid'; // Will be collected on delivery
            break;
            
        default:
            $payment_status = 'Failed';
            $payment_error = 'Invalid payment method selected.';
    }
    
    return [
        'payment_id' => $payment_id,
        'status' => $payment_status,
        'error' => $payment_error
    ];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect address information
    $address_line1 = trim($_POST['address_line1']);
    $address_line2 = isset($_POST['address_line2']) ? trim($_POST['address_line2']) : '';
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);
    $postal_code = trim($_POST['postal_code']);
    $country = trim($_POST['country']);
    $phone = trim($_POST['phone']);
    $payment_method = $_POST['payment_method'];
    
    // Validate required fields
    if (empty($address_line1) || empty($city) || empty($state) || empty($postal_code) || empty($country) || empty($phone)) {
        $_SESSION['error'] = "Please fill in all required fields.";
        header("Location: checkout.php");
        exit;
    }
    
    // Validate phone number format (basic validation)
    if (!preg_match('/^\d{10}$/', $phone)) {
        $_SESSION['error'] = "Please enter a valid 10-digit phone number.";
        header("Location: checkout.php");
        exit;
    }
    
// Calculate total amount from the cart table
$cart_query = "SELECT c.quantity, p.price,p.quantity as available_quantity
               FROM cart c 
               JOIN products p ON c.product_id = p.id 
               WHERE c.buyer_id = ?";
$cart_stmt = $conn->prepare($cart_query);
$cart_stmt->bind_param("i", $buyer_id);
$cart_stmt->execute();
$cart_result = $cart_stmt->get_result();

$total_amount = 0;
$cart_items = [];


// Check product availability before proceeding
while ($cart_item = $cart_result->fetch_assoc()) {
    if ($cart_item['quantity'] > $cart_item['available_quantity']) {
        $_SESSION['error'] = "Some products in your cart are no longer available in the requested quantity. Please update your cart.";
        header("Location: index.php?tab=cart");
        exit;
    }
    // Calculate subtotal using quantity from the cart table
    $subtotal = $cart_item['quantity'] * $cart_item['price']; // Calculate subtotal correctly
    $total_amount += $subtotal;
    $cart_items[] = $cart_item;
}
    
    // Prepare payment information
    $card_info = [
        'card_number' => $_POST['card_number'] ?? null,
        'cvv' => $_POST['cvv'] ?? null,
        'upi_id' => $_POST['upi_id'] ?? null,
        'bank_name' => $_POST['bank_name'] ?? null
    ];
    
    // Process payment
    $payment_result = processPayment($payment_method, $total_amount, $card_info);
    
    // If payment failed and it's not COD, show error
    if ($payment_result['status'] == 'Failed') {
        $_SESSION['error'] = "Payment failed: " . $payment_result['error'];
        header("Location: checkout.php");
        exit;
    }
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Update user address information
        $update_user = "UPDATE users SET 
                        address_line1 = ?, 
                        address_line2 = ?, 
                        city = ?, 
                        state = ?, 
                        postal_code = ?, 
                        country = ?, 
                        phone = ? 
                        WHERE id = ?";
        $update_stmt = $conn->prepare($update_user);
        $update_stmt->bind_param("sssssssi", 
                              $address_line1, $address_line2, $city, $state, 
                              $postal_code, $country, $phone, $buyer_id);
        $update_stmt->execute();
        
        // Get cart items again
        $cart_query = "SELECT c.*, p.id as product_id, p.price, p.seller_id 
                      FROM cart c 
                      JOIN products p ON c.product_id = p.id 
                      WHERE c.buyer_id = ?";
        $cart_stmt = $conn->prepare($cart_query);
        $cart_stmt->bind_param("i", $buyer_id);
        $cart_stmt->execute();
        $cart_result = $cart_stmt->get_result();
        
        $order_ids = [];
        $total_amount = 0;
        
        // Process each cart item
        while ($item = $cart_result->fetch_assoc()) {
            $product_id = $item['product_id'];
            $seller_id = $item['seller_id'];
            $item_quantity = $item['quantity'];
            $item_price = $item['price'];
            $subtotal = $item_quantity * $item_price;
            $total_amount += $subtotal;
            
            // Insert order
            $order_date = date("Y-m-d");
            $insert_order = "INSERT INTO orders (buyer_id, seller_id, product_id, quantity, total_price, order_date, status) 
                             VALUES (?, ?, ?, ?, ?, ?, 'Pending')";
            $order_stmt = $conn->prepare($insert_order);
            $order_stmt->bind_param("iiidss", $buyer_id, $seller_id, $product_id, $item_quantity, $subtotal, $order_date);
            $order_stmt->execute();
            
            $order_id = $conn->insert_id;
            $order_ids[] = $order_id;
            
            // Record transaction
            $insert_transaction = "INSERT INTO transactions (id,order_id, buyer_id, payment_method, amount, payment_status, transaction_date) 
                                   VALUES (?,?, ?, ?, ?, ?, NOW())";
            $transaction_stmt = $conn->prepare($insert_transaction);
            $payment_status = $payment_result['status'];
            $transaction_stmt->bind_param("siisds",$payment_id, $order_id, $buyer_id, $payment_method, $subtotal, $payment_status);
            $transaction_stmt->execute();
            
            // Update product quantity
            $update_product = "UPDATE products SET quantity = quantity - ? WHERE id = ?";
            $product_stmt = $conn->prepare($update_product);
            $product_stmt->bind_param("ii", $item_quantity, $product_id);
            $product_stmt->execute();
            
            // Check if product should be marked out of stock
            $check_quantity = "SELECT quantity FROM products WHERE id = ?";
            $check_stmt = $conn->prepare($check_quantity);
            $check_stmt->bind_param("i", $product_id);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            $product_data = $check_result->fetch_assoc();
            
            if ($product_data['quantity'] <= 0) {
                $update_stock = "UPDATE products SET in_stock = 0 WHERE id = ?";
                $stock_stmt = $conn->prepare($update_stock);
                $stock_stmt->bind_param("i", $product_id);
                $stock_stmt->execute();
            }
            
            // Create notification for the seller
            $seller_notification = "New order received for your product. Order ID: " . $order_id;
            $seller_notif_query = "INSERT INTO notifications (user_id, message, status, created_at) VALUES (?, ?, 'unread', NOW())";
            $seller_notif_stmt = $conn->prepare($seller_notif_query);
            $seller_notif_stmt->bind_param("is", $seller_id, $seller_notification);
            $seller_notif_stmt->execute();
        }
        
        // Clear the cart
        $clear_cart = "DELETE FROM cart WHERE buyer_id = ?";
        $clear_stmt = $conn->prepare($clear_cart);
        $clear_stmt->bind_param("i", $buyer_id);
        $clear_stmt->execute();
        
        // Create a notification for the buyer
        $order_id_list = implode(', ', $order_ids);
        $payment_status_msg = ($payment_result['status'] == 'Paid') ? 'Payment successful.' : 
                             (($payment_method == 'Cash on Delivery') ? 'Payment will be collected on delivery.' : 'Payment pending.');
        $notification = "Your order(s) #{$order_id_list} have been placed successfully. Total amount: ₹" . number_format($total_amount, 2) . ". " . $payment_status_msg;
        
        $insert_notification = "INSERT INTO notifications (user_id, message, status, created_at) VALUES (?, ?, 'unread', NOW())";
        $notification_stmt = $conn->prepare($insert_notification);
        $notification_stmt->bind_param("is", $buyer_id, $notification);
        $notification_stmt->execute();
        
        // Commit transaction
        $conn->commit();
        
        // Set success message
        $_SESSION['success'] = "Your order has been placed successfully! " . $payment_status_msg;
        $_SESSION['payment_id'] = $payment_result['payment_id'];
        $_SESSION['order_ids'] = $order_ids;
        
        header("Location: order_confirmation.php");
        exit;
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $_SESSION['error'] = "Error processing your order: " . $e->getMessage();
        header("Location: checkout.php");
        exit;
    }
}

// Initialize total
$total = 0;
$items = [];

// Process cart items for display
mysqli_data_seek($result, 0); // Reset result pointer
while ($item = $result->fetch_assoc()) {
    $subtotal = $item['price'] * $item['quantity'];
    $total += $subtotal;
    $items[] = $item;
}


// Close the database connection
$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - AgriDisha</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
       .payment-method-form {
    display: none;
}
.payment-method-form.active {
    display: block;
}

/* Multi-step Indicator Styles */
.checkout-steps {
    display: flex;
    justify-content: space-between;
    margin-bottom: 30px;
    position: relative;
}

.checkout-step {
    flex: 1;
    text-align: center;
    padding: 10px;
    position: relative;
    z-index: 1;
}

.checkout-step::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    width: 100%;
    height: 2px;
    background-color: #e0e0e0;
    z-index: -1;
}

.checkout-step-circle {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background-color: #e0e0e0;
    color: white;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 10px;
    transition: all 0.3s ease;
}

.checkout-step.active .checkout-step-circle,
.checkout-step.completed .checkout-step-circle {
    background-color: #28a745;
    color: white;
}

.checkout-step.active .checkout-step-circle {
    border: 2px solid #28a745;
}

.checkout-step-title {
    font-size: 0.9em;
    color: #6c757d;
}

.checkout-step.active .checkout-step-title,
.checkout-step.completed .checkout-step-title {
    color: #28a745;
    font-weight: bold;
}

/* Hide/Show Steps */
.checkout-step-content {
    display: none;
}

.checkout-step-content.active {
    display: block;
}

/* Green Theme Additions */
.card-header.bg-primary {
    background-color: #28a745 !important;
}

.card-header.bg-success {
    background-color: #28a745 !important;
}

.btn-primary {
    background-color: #28a745;
    border-color: #28a745;
}

.btn-primary:hover, .btn-primary:focus, .btn-primary:active {
    background-color: #218838;
    border-color: #1e7e34;
}

.btn-success {
    background-color: #28a745;
    border-color: #28a745;
}

.btn-success:hover, .btn-success:focus, .btn-success:active {
    background-color: #218838;
    border-color: #1e7e34;
}

.text-success {
    color: #28a745 !important;
}

.custom-control-input:checked ~ .custom-control-label::before {
    background-color: #28a745;
    border-color: #28a745;
}

.form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}
    </style>
</head>
<body>
 
    
    <div class="container my-5">
        <h2 class="text-center mb-4">Checkout</h2>
        
        <!-- Multi-Step Indicator -->
        <div class="checkout-steps">
            <div class="checkout-step active" data-step="1">
                <div class="checkout-step-circle">1</div>
                <div class="checkout-step-title">Shipping Info</div>
            </div>
            <div class="checkout-step" data-step="2">
                <div class="checkout-step-circle">2</div>
                <div class="checkout-step-title">Payment Method</div>
            </div>
            <div class="checkout-step" data-step="3">
                <div class="checkout-step-circle">3</div>
                <div class="checkout-step-title">Order Summary</div>
            </div>
        </div>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <form action="checkout.php" method="post" id="checkout-form">
            <!-- Shipping Information Step -->
            <div class="checkout-step-content active" data-step="1">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-4">
                            <div class="card-header bg-success text-white">
                                <h4>Shipping Information</h4>
                            </div>
                            <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address_line1">Address Line 1 *</label>
                                        <input type="text" class="form-control" id="address_line1" name="address_line1" required
                                               value="<?php echo htmlspecialchars($user_data['address_line1'] ?? ''); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address_line2">Address Line 2</label>
                                        <input type="text" class="form-control" id="address_line2" name="address_line2"
                                               value="<?php echo htmlspecialchars($user_data['address_line2'] ?? ''); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="city">City *</label>
                                        <input type="text" class="form-control" id="city" name="city" required
                                               value="<?php echo htmlspecialchars($user_data['city'] ?? ''); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="state">State *</label>
                                        <input type="text" class="form-control" id="state" name="state" required
                                               value="<?php echo htmlspecialchars($user_data['state'] ?? ''); ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="postal_code">Postal Code *</label>
                                        <input type="text" class="form-control" id="postal_code" name="postal_code" required
                                               value="<?php echo htmlspecialchars($user_data['postal_code'] ?? ''); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="country">Country *</label>
                                        <input type="text" class="form-control" id="country" name="country" required
                                               value="<?php echo htmlspecialchars($user_data['country'] ?? 'India'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="phone">Phone *</label>
                                        <input type="text" class="form-control" id="phone" name="phone" required
                                               value="<?php echo htmlspecialchars($user_data['phone'] ?? ''); ?>">
                                    </div>
                                </div>
                            </div>
                                <div class="text-right mt-4">
                                <a href="index.php?tab=cart" class="btn btn-success">Back To Cart</a>
                                    <button type="button" class="btn btn-primary next-step">Continue to Payment</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-dark text-white">
                                <h4>Order Summary</h4>
                            </div>
                            <div class="card-body">
                            <div class="summary-items mb-3">
                            <?php foreach($items as $item): ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <div>
                                        <span class="font-weight-bold"><?php echo htmlspecialchars($item['name']); ?></span>
                                        <small class="d-block"><?php echo $item['quantity']; ?> x ₹<?php echo number_format($item['price'], 2); ?></small>
                                    </div>
                                    <div>
                                        ₹<?php echo number_format($item['quantity'] * $item['price'], 2); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between">
                            <h5>Subtotal:</h5>
                            <h5>₹<?php echo number_format($total, 2); ?></h5>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <h5>Shipping:</h5>
                            <h5>Free</h5>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between">
                            <h4>Total:</h4>
                            <h4 class="text-success">₹<?php echo number_format($total, 2); ?></h4>
                        </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Payment Method Step -->
            <div class="checkout-step-content" data-step="2">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h4>Payment Method</h4>
                            </div>
                            <div class="card-body">
                            <div class="form-group">
                                        <div class="custom-control custom-radio mb-2">
                                            <input type="radio" id="cod" name="payment_method" value="Cash on Delivery" class="custom-control-input payment-method" checked>
                                            <label class="custom-control-label" for="cod">Cash on Delivery</label>
                                        </div>
                                        
                                        <div class="custom-control custom-radio mb-2">
                                            <input type="radio" id="credit_card" name="payment_method" value="Credit Card" class="custom-control-input payment-method">
                                            <label class="custom-control-label" for="credit_card">Credit Card</label>
                                        </div>
                                        
                                        <div class="custom-control custom-radio mb-2">
                                            <input type="radio" id="debit_card" name="payment_method" value="Debit Card" class="custom-control-input payment-method">
                                            <label class="custom-control-label" for="debit_card">Debit Card</label>
                                        </div>
                                        
                                        <div class="custom-control custom-radio mb-2">
                                            <input type="radio" id="upi" name="payment_method" value="UPI" class="custom-control-input payment-method">
                                            <label class="custom-control-label" for="upi">UPI</label>
                                        </div>
                                        
                                        <div class="custom-control custom-radio mb-2">
                                            <input type="radio" id="net_banking" name="payment_method" value="Net Banking" class="custom-control-input payment-method">
                                            <label class="custom-control-label" for="net_banking">Net Banking</label>
                                        </div>
                                    </div>
                                    
                                    <!-- Credit/Debit Card Form -->
                                    <div id="card-form" class="payment-method-form mt-3">
                                        <div class="form-group">
                                            <label for="card_number">Card Number</label>
                                            <input type="text" class="form-control" id="card_number" name="card_number" placeholder="XXXX XXXX XXXX XXXX">
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="expiry_date">Expiry Date</label>
                                                    <input type="text" class="form-control" id="expiry_date" name="expiry_date" placeholder="MM/YY">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="cvv">CVV</label>
                                                    <input type="text" class="form-control" id="cvv" name="cvv" placeholder="XXX">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="card_name">Name on Card</label>
                                            <input type="text" class="form-control" id="card_name" name="card_name">
                                        </div>
                                    </div>
                                    
                                    <!-- UPI Form -->
                                    <div id="upi-form" class="payment-method-form mt-3">
                                        <div class="form-group">
                                            <label for="upi_id">UPI ID</label>
                                            <input type="text" class="form-control" id="upi_id" name="upi_id" placeholder="username@upi">
                                        </div>
                                    </div>
                                    
                                    <!-- Net Banking Form -->
                                    <div id="net-banking-form" class="payment-method-form mt-3">
                                        <div class="form-group">
                                            <label for="bank_name">Select Bank</label>
                                            <select class="form-control" id="bank_name" name="bank_name">
                                                <option value="">Select a bank</option>
                                                <option value="SBI">State Bank of India</option>
                                                <option value="ICICI">ICICI Bank</option>
                                                <option value="HDFC">HDFC Bank</option>
                                                <option value="Axis">Axis Bank</option>
                                                <option value="BOI">Bank of India</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                <div class="text-right mt-4">
                                    <button type="button" class="btn btn-secondary prev-step">Back to Shipping</button>
                                    <button type="button" class="btn btn-primary next-step">Review Order</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-dark text-white">
                                <h4>Order Summary</h4>
                            </div>
                            <div class="card-body">
                            <div class="summary-items mb-3">
                            <?php foreach($items as $item): ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <div>
                                        <span class="font-weight-bold"><?php echo htmlspecialchars($item['name']); ?></span>
                                        <small class="d-block"><?php echo $item['quantity']; ?> x ₹<?php echo number_format($item['price'], 2); ?></small>
                                    </div>
                                    <div>
                                        ₹<?php echo number_format($item['quantity'] * $item['price'], 2); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between">
                            <h5>Subtotal:</h5>
                            <h5>₹<?php echo number_format($total, 2); ?></h5>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <h5>Shipping:</h5>
                            <h5>Free</h5>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between">
                            <h4>Total:</h4>
                            <h4 class="text-success">₹<?php echo number_format($total, 2); ?></h4>
                        </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Order Review Step -->
            <div class="checkout-step-content" data-step="3">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h4>Review Your Order</h4>
                            </div>
                            <div class="card-body">
                                <!-- Combine Shipping and Payment Details -->
                                <h5>Shipping Information</h5>
                                <p id="review-shipping-info"></p>
                                
                                <hr>
                                
                                <h5>Payment Method</h5>
                                <p id="review-payment-method"></p>
                                
                                <div class="text-right mt-4">
                                    <button type="button" class="btn btn-secondary prev-step">Back to Payment</button>
                                    <button type="submit" class="btn btn-success">Place Order</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-dark text-white">
                                <h4>Order Summary</h4>
                            </div>
                            <div class="card-body">
                            <div class="summary-items mb-3">
                            <?php foreach($items as $item): ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <div>
                                        <span class="font-weight-bold"><?php echo htmlspecialchars($item['name']); ?></span>
                                        <small class="d-block"><?php echo $item['quantity']; ?> x ₹<?php echo number_format($item['price'], 2); ?></small>
                                    </div>
                                    <div>
                                        ₹<?php echo number_format($item['quantity'] * $item['price'], 2); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between">
                            <h5>Subtotal:</h5>
                            <h5>₹<?php echo number_format($total, 2); ?></h5>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <h5>Shipping:</h5>
                            <h5>Free</h5>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between">
                            <h4>Total:</h4>
                            <h4 class="text-success">₹<?php echo number_format($total, 2); ?></h4>
                        </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Existing payment method selection script
            $('.payment-method').change(function() {
                $('.payment-method-form').removeClass('active');
                
                var selectedMethod = $('input[name="payment_method"]:checked').val();
                
                if (selectedMethod === 'Credit Card' || selectedMethod === 'Debit Card') {
                    $('#card-form').addClass('active');
                } else if (selectedMethod === 'UPI') {
                    $('#upi-form').addClass('active');
                } else if (selectedMethod === 'Net Banking') {
                    $('#net-banking-form').addClass('active');
                }
            });
            
            // Multi-step navigation
            function updateSteps(currentStep, direction) {
                // Update step indicators
                $('.checkout-steps .checkout-step').each(function() {
                    var stepNum = parseInt($(this).data('step'));
                    
                    if (stepNum < currentStep) {
                        $(this).removeClass('active').addClass('completed');
                    } else if (stepNum === currentStep) {
                        $(this).addClass('active').removeClass('completed');
                    } else {
                        $(this).removeClass('active').removeClass('completed');
                    }
                });
                
                // Update step content
                $('.checkout-step-content').removeClass('active');
                $(`.checkout-step-content[data-step="${currentStep}"]`).addClass('active');
            }
            
            // Next step button
            $('.next-step').click(function() {
                var currentStep = parseInt($('.checkout-step-content.active').data('step'));
                
                // Validation for each step
                if (currentStep === 1) {
                    // Validate shipping information
                    var isValid = true;
                    $('#checkout-form input[required]').each(function() {
                        if ($(this).val().trim() === '') {
                            isValid = false;
                            $(this).addClass('is-invalid');
                        } else {
                            $(this).removeClass('is-invalid');
                        }
                    });
                    
                    if (!isValid) {
                        alert('Please fill in all required shipping information.');
                        return;
                    }
                    
                    // Populate review shipping info
                    var shippingInfo = [
                        $('#address_line1').val(),
                        $('#address_line2').val() ? $('#address_line2').val() : '',
                        $('#city').val(),
                        $('#state').val(),
                        $('#postal_code').val(),
                        $('#country').val(),
                        $('#phone').val()
                    ].filter(Boolean).join(', ');
                    $('#review-shipping-info').text(shippingInfo);
                }
                
                if (currentStep === 2) {
                    // Validate payment method
                    var selectedMethod = $('input[name="payment_method"]:checked').val();
                    
                    if (selectedMethod === 'Credit Card' || selectedMethod === 'Debit Card') {
                        if ($('#card_number').val().trim() === '' || $('#cvv').val().trim() === '') {
                            alert('Please fill in all card details');
                            return;
                        }
                    } else if (selectedMethod === 'UPI') {
                        if ($('#upi_id').val().trim() === '') {
                            alert('Please enter your UPI ID');
                            return;
                        }
                    } else if (selectedMethod === 'Net Banking') {
                        if ($('#bank_name').val() === '') {
                            alert('Please select your bank');
                            return;
                        }
                    }
                    
                    // Populate review payment method
                    $('#review-payment-method').text(selectedMethod);
                }
                
                updateSteps(currentStep + 1, 'next');
            });
            
            // Previous step button
            $('.prev-step').click(function() {
                var currentStep = parseInt($('.checkout-step-content.active').data('step'));
                updateSteps(currentStep - 1, 'prev');
            });
            
            // Form validation
            $('#checkout-form').submit(function(e) {
                // Final validation can be added here if needed
            });
        });
    </script>
</body>
</html>