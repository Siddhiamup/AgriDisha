<?php
// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}
// Check if order data is available
if (!isset($_SESSION['success']) || !isset($_SESSION['order_ids'])) {
    header("Location: index.php");
    exit;
}
$success_message = $_SESSION['success'];
$order_ids = $_SESSION['order_ids'];
$payment_id = $_SESSION['payment_id'] ?? 'N/A';
// Clear the session variables after displaying them
unset($_SESSION['success']);
unset($_SESSION['order_ids']);
unset($_SESSION['payment_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - AgriDisha</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-success text-white text-center">
                        <h3>Order Confirmation</h3>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <i class="fa fa-check-circle text-success" style="font-size: 64px;"></i>
                            <h4 class="mt-3"><?php echo $success_message; ?></h4>
                            <p>Thank you for shopping with AgriDisha!</p>
                        </div>

                        <div class="order-details">
                            <h5>Order Details:</h5>
                            <p><strong>Order ID(s):</strong> <?php echo implode(", ", $order_ids); ?></p>
                            <p><strong>Payment ID:</strong> <?php echo $payment_id; ?></p>
                            <p><strong>Date:</strong> <?php echo date("Y-m-d H:i:s"); ?></p>
                        </div>

                        <?php
                        // Connect to the database
                        require_once 'db.php';
                        
                        // Prepare the order IDs for SQL query
                        $order_ids_str = implode(',', array_map('intval', $order_ids));
                        
                        // Fetch order details
                        $sql = "SELECT o.*, p.name as product_name, p.price, p.image_url, u.username as seller_name 
                                FROM orders o 
                                JOIN products p ON o.product_id = p.id 
                                JOIN users u ON p.seller_id = u.id 
                                WHERE o.id IN ($order_ids_str)";
                        $result = $conn->query($sql);
                        
                        if ($result && $result->num_rows > 0) {
                        ?>
                            <div class="table-responsive mt-4">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Seller</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $total = 0;
                                        while ($row = $result->fetch_assoc()) {
                                            $total += $row['total_price'];
                                        ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['product_name']; ?>" style="width: 50px; height: 50px; object-fit: cover;" class="mr-2">
                                                        <?php echo $row['product_name']; ?>
                                                    </div>
                                                </td>
                                                <td><?php echo $row['quantity']; ?></td>
                                                <td>₹<?php echo number_format($row['total_price'], 2); ?></td>
                                                <td><?php echo $row['seller_name']; ?></td>
                                                <td>
                                                    <span class="badge badge-warning"><?php echo $row['status']; ?></span>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                        <tr>
                                            <td colspan="2" class="text-right"><strong>Total Amount:</strong></td>
                                            <td colspan="3"><strong>₹<?php echo number_format($total, 2); ?></strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        <?php
                        } else {
                            echo "<div class='alert alert-warning'>No order details found.</div>";
                        }
                        
                        // Close the database connection
                        $conn->close();
                        ?>

                        <div class="text-center mt-4">
                            <p>You will receive an email confirmation shortly.</p>
                            <div class="mt-4">
                                <a href="order_history.php" class="btn btn-primary mr-2">View Order History</a>
                                <a href="index.php?tab=buy" class="btn btn-success">Continue Shopping</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <p class="mb-0">For any queries, please contact our <a href="contact.php">customer support</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>
</html>