<?php
// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Buyer') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id'];

// Connect to the database
require_once 'db.php';

// Pagination
$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$start = ($page - 1) * $limit;

// Total orders count
$count_sql = "SELECT COUNT(*) as total FROM orders WHERE buyer_id = ?";
$count_stmt = $conn->prepare($count_sql);
$count_stmt->bind_param("i", $user_id);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_records = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $limit);

// Fetch orders with pagination
$sql = "SELECT o.*, p.name as product_name, p.image_url, u.username as seller_name, 
        t.payment_method, t.payment_status 
        FROM orders o 
        JOIN products p ON o.product_id = p.id 
        JOIN users u ON o.seller_id = u.id 
        LEFT JOIN transactions t ON o.id = t.order_id 
        WHERE o.buyer_id = ? 
        ORDER BY o.created_at DESC 
        LIMIT ?, ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $user_id, $start, $limit);
$stmt->execute();
$result = $stmt->get_result();

// Filter options
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$date_from = isset($_GET['date_from']) ? $_GET['date_from'] : '';
$date_to = isset($_GET['date_to']) ? $_GET['date_to'] : '';
$payment_status = isset($_GET['payment_status']) ? $_GET['payment_status'] : '';

// If filters are applied
if ($status_filter || $date_from || $date_to || $payment_status) {
    $filter_sql = "SELECT o.*, p.name as product_name, p.image_url, u.username as seller_name, 
                  t.payment_method, t.payment_status 
                  FROM orders o 
                  JOIN products p ON o.product_id = p.id 
                  JOIN users u ON o.seller_id = u.id 
                  LEFT JOIN transactions t ON o.id = t.order_id 
                  WHERE o.buyer_id = ?";
    
    $params = array($user_id);
    $types = "i";
    
    if ($status_filter) {
        $filter_sql .= " AND o.status = ?";
        $params[] = $status_filter;
        $types .= "s";
    }
    
    if ($date_from) {
        $filter_sql .= " AND o.order_date >= ?";
        $params[] = $date_from;
        $types .= "s";
    }
    
    if ($date_to) {
        $filter_sql .= " AND o.order_date <= ?";
        $params[] = $date_to;
        $types .= "s";
    }
    
    if ($payment_status) {
        $filter_sql .= " AND t.payment_status = ?";
        $params[] = $payment_status;
        $types .= "s";
    }
    
    $filter_sql .= " ORDER BY o.created_at DESC";
    
    $filter_stmt = $conn->prepare($filter_sql);
    $filter_stmt->bind_param($types, ...$params);
    $filter_stmt->execute();
    $result = $filter_stmt->get_result();
    
    // Update total for pagination
    $count_filter_sql = str_replace("o.*, p.name as product_name, p.image_url, u.username as seller_name, 
                  t.payment_method, t.payment_status", "COUNT(*) as total", $filter_sql);
    $count_filter_stmt = $conn->prepare($count_filter_sql);
    $count_filter_stmt->bind_param($types, ...$params);
    $count_filter_stmt->execute();
    $count_filter_result = $count_filter_stmt->get_result();
    $total_records = $count_filter_result->fetch_assoc()['total'];
    $total_pages = ceil($total_records / $limit);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History - AgriDisha</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .order-card {
            transition: transform 0.3s;
            margin-bottom: 20px;
        }
        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .status-pending { color: #ffc107; }
        .status-shipped { color: #17a2b8; }
        .status-delivered { color: #28a745; }
        .status-cancelled { color: #dc3545; }
        .filter-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container my-5">
        <h2 class="text-center mb-4">Your Order History</h2>
        
        <!-- Filter Section -->
        <div class="filter-section">
            <form action="" method="GET" class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="Pending" <?php echo ($status_filter == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                            <option value="Shipped" <?php echo ($status_filter == 'Shipped') ? 'selected' : ''; ?>>Shipped</option>
                            <option value="Delivered" <?php echo ($status_filter == 'Delivered') ? 'selected' : ''; ?>>Delivered</option>
                            <option value="Cancelled" <?php echo ($status_filter == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="payment_status">Payment Status</label>
                        <select class="form-control" id="payment_status" name="payment_status">
                            <option value="">All Payments</option>
                            <option value="Paid" <?php echo ($payment_status == 'Paid') ? 'selected' : ''; ?>>Paid</option>
                            <option value="Unpaid" <?php echo ($payment_status == 'Unpaid') ? 'selected' : ''; ?>>Unpaid</option>
                            <option value="Refunded" <?php echo ($payment_status == 'Refunded') ? 'selected' : ''; ?>>Refunded</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="date_from">From Date</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" value="<?php echo $date_from; ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="date_to">To Date</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" value="<?php echo $date_to; ?>">
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="form-group mb-0 w-100">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>
            <?php if ($status_filter || $date_from || $date_to || $payment_status): ?>
                <div class="mt-2">
                    <a href="order_history.php" class="btn btn-sm btn-outline-secondary">Clear Filters</a>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Orders Section -->
        <?php if ($result && $result->num_rows > 0): ?>
            <div class="row">
                <?php while ($order = $result->fetch_assoc()): ?>
                    <?php 
                        $status_class = '';
                        switch ($order['status']) {
                            case 'Pending': $status_class = 'status-pending'; break;
                            case 'Shipped': $status_class = 'status-shipped'; break;
                            case 'Delivered': $status_class = 'status-delivered'; break;
                            case 'Cancelled': $status_class = 'status-cancelled'; break;
                        }
                    ?>
                    <div class="col-md-6">
                        <div class="card order-card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0">Order #<?php echo $order['id']; ?></h5>
                                    <small><?php echo date('F j, Y', strtotime($order['order_date'])); ?></small>
                                </div>
                                <span class="badge <?php echo $status_class ? 'badge-light' : ''; ?>">
                                    <i class="fas fa-circle <?php echo $status_class; ?> mr-1"></i>
                                    <?php echo $order['status']; ?>
                                </span>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <img src="<?php echo $order['image_url']; ?>" alt="<?php echo $order['product_name']; ?>" class="img-fluid rounded">
                                    </div>
                                    <div class="col-md-8">
                                        <h5><?php echo $order['product_name']; ?></h5>
                                        <p class="mb-1">Quantity: <?php echo $order['quantity']; ?></p>
                                        <p class="mb-1">Price: ₹<?php echo number_format($order['total_price'], 2); ?></p>
                                        <p class="mb-1">Seller: <?php echo $order['seller_name']; ?></p>
                                        <p class="mb-0">
                                            Payment: 
                                            <span class="badge <?php echo $order['payment_status'] == 'Paid' ? 'badge-success' : ($order['payment_status'] == 'Refunded' ? 'badge-info' : 'badge-warning'); ?>">
                                                <?php echo $order['payment_status']; ?>
                                            </span>
                                            <?php if ($order['payment_method']): ?>
                                                via <?php echo $order['payment_method']; ?>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-between">
                                    <a href="order_details.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> View Details
                                    </a>
                                    <?php if ($order['status'] === 'Delivered'): ?>
                                        <a href="review.php?order_id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-star"></i> Write Review
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($order['status'] === 'Pending'): ?>
                                        <a href="cancel_order.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to cancel this order?');">
                                            <i class="fas fa-times"></i> Cancel Order
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <nav aria-label="Order history pagination">
                    <ul class="pagination justify-content-center mt-4">
                        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="<?php echo ($page <= 1) ? '#' : '?page='.($page-1); ?><?php echo ($status_filter) ? '&status='.$status_filter : ''; ?><?php echo ($date_from) ? '&date_from='.$date_from : ''; ?><?php echo ($date_to) ? '&date_to='.$date_to : ''; ?><?php echo ($payment_status) ? '&payment_status='.$payment_status : ''; ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        
                        <?php
                        $start_page = max(1, $page - 2);
                        $end_page = min($total_pages, $page + 2);
                        
                        if ($start_page > 1) {
                            echo '<li class="page-item"><a class="page-link" href="?page=1';
                            echo ($status_filter) ? '&status='.$status_filter : '';
                            echo ($date_from) ? '&date_from='.$date_from : '';
                            echo ($date_to) ? '&date_to='.$date_to : '';
                            echo ($payment_status) ? '&payment_status='.$payment_status : '';
                            echo '">1</a></li>';
                            if ($start_page > 2) {
                                echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                            }
                        }
                        
                        for ($i = $start_page; $i <= $end_page; $i++) {
                            echo '<li class="page-item ';
                            echo ($page == $i) ? 'active' : '';
                            echo '"><a class="page-link" href="?page='.$i;
                            echo ($status_filter) ? '&status='.$status_filter : '';
                            echo ($date_from) ? '&date_from='.$date_from : '';
                            echo ($date_to) ? '&date_to='.$date_to : '';
                            echo ($payment_status) ? '&payment_status='.$payment_status : '';
                            echo '">'.$i.'</a></li>';
                        }
                        
                        if ($end_page < $total_pages) {
                            if ($end_page < $total_pages - 1) {
                                echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                            }
                            echo '<li class="page-item"><a class="page-link" href="?page='.$total_pages;
                            echo ($status_filter) ? '&status='.$status_filter : '';
                            echo ($date_from) ? '&date_from='.$date_from : '';
                            echo ($date_to) ? '&date_to='.$date_to : '';
                            echo ($payment_status) ? '&payment_status='.$payment_status : '';
                            echo '">'.$total_pages.'</a></li>';
                        }
                        ?>
                        
                        <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="<?php echo ($page >= $total_pages) ? '#' : '?page='.($page+1); ?><?php echo ($status_filter) ? '&status='.$status_filter : ''; ?><?php echo ($date_from) ? '&date_from='.$date_from : ''; ?><?php echo ($date_to) ? '&date_to='.$date_to : ''; ?><?php echo ($payment_status) ? '&payment_status='.$payment_status : ''; ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
            
        <?php else: ?>
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle fa-2x mb-3"></i>
                <h4>No orders found</h4>
                <p>You haven't placed any orders yet.</p>
                <a href="index.php?tab=buy" class="btn btn-primary mt-2">Start Shopping</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Order Detail Modal -->
    <div class="modal fade" id="orderDetailModal" tabindex="-1" role="dialog" aria-labelledby="orderDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDetailModalLabel">Order Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="orderDetailContent">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // View order details in modal
            $('.view-details-btn').click(function(e) {
                e.preventDefault();
                var orderId = $(this).data('order-id');
                $('#orderDetailContent').html('Loading...');
                $('#orderDetailModal').modal('show');
                
                $.ajax({
                    url: 'get_order_details.php',
                    type: 'GET',
                    data: {id: orderId},
                    success: function(response) {
                        $('#orderDetailContent').html(response);
                    },
                    error: function() {
                        $('#orderDetailContent').html('<div class="alert alert-danger">Error loading order details</div>');
                    }
                });
            });
        });
    </script>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>