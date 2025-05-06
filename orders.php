<?php
// Include database connection
include 'db.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirect if not logged in or not a Farmer
if (!isset($_SESSION['id'])) {
    header("Location: login.php?redirect=orders.php");
    exit;
}

// Get farmer's ID
$farmer_id = $_SESSION['id'];

// Fetch farmer's orders with more details
$orders = [];
$query = "
    SELECT 
        o.id AS order_id, 
        o.quantity, 
        o.total_price, 
        o.order_date, 
        o.status,
        o.created_at,
        p.name AS product_name,
        p.price AS unit_price,
        p.image_url,
        u.username AS buyer_name,
        u.phone AS buyer_phone,
        u.email AS buyer_email,
        u.address_line1 AS buyer_address_line1,
        u.address_line2 AS buyer_address_line2,
        u.city AS buyer_city,
        u.state AS buyer_state,
        u.postal_code AS buyer_postal_code,
        t.payment_method,
        t.payment_status,
        t.transaction_date
    FROM orders o
    JOIN products p ON o.product_id = p.id
    JOIN users u ON o.buyer_id = u.id
    LEFT JOIN transactions t ON o.id = t.order_id
    WHERE o.seller_id = ?
    ORDER BY o.order_date DESC, o.created_at DESC
";

// Prepare and execute statement
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $farmer_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch orders
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}
$stmt->close();

// Process status update if form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['new_status'];
    
    $update_query = "UPDATE orders SET status = ? WHERE id = ? AND seller_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("sii", $new_status, $order_id, $farmer_id);
    
    if ($update_stmt->execute()) {
        // Success message
        $status_updated = true;
        
        // Create notification for buyer
        $get_buyer_query = "SELECT buyer_id FROM orders WHERE id = ?";
        $get_buyer_stmt = $conn->prepare($get_buyer_query);
        $get_buyer_stmt->bind_param("i", $order_id);
        $get_buyer_stmt->execute();
        $buyer_result = $get_buyer_stmt->get_result();
        
        if ($row = $buyer_result->fetch_assoc()) {
            $buyer_id = $row['buyer_id'];
            $notification_message = "Your order #$order_id has been updated to: $new_status";
            
            $notify_query = "INSERT INTO notifications (user_id, message) VALUES (?, ?)";
            $notify_stmt = $conn->prepare($notify_query);
            $notify_stmt->bind_param("is", $buyer_id, $notification_message);
            $notify_stmt->execute();
            $notify_stmt->close();
        }
        $get_buyer_stmt->close();
        
        // Refresh the page to show updated status
        header("Location: fardemo.php?tab=orders.php?success=1&message=" . urlencode("Order status updated to $new_status successfully!"));
        exit;
    } else {
        // Error message
        header("Location: orders.php?error=1&message=" . urlencode("Failed to update order status. Please try again."));
        exit;
    }
    $update_stmt->close();
}
?>
<style>
.orders-container {
    width: 100%;
    margin: 0;
    padding: 0 15px;
    box-sizing: border-box;
}

.orders-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.orders-summary {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.summary-card {
    flex: 1;
    min-width: 120px;
    background-color: #f9f9f9;
    border-radius: 5px;
    padding: 15px;
    text-align: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.summary-card h3 {
    margin-top: 0;
    color: #333;
}

.summary-card p {
    font-size: 1.5em;
    font-weight: bold;
    margin: 10px 0 0;
}

.pending { color: #FFC107; }
.shipped { color: #2196F3; }
.delivered { color: #4CAF50; }
.cancelled { color: #F44336; }

.orders-filters {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
    flex-wrap: wrap;
}

.order-status {
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 0.8em;
    text-transform: uppercase;
    display: inline-block;
}

.status-pending { background-color: #FFC107; color: black; }
.status-shipped { background-color: #2196F3; color: white; }
.status-delivered { background-color: #4CAF50; color: white; }
.status-cancelled { background-color: #F44336; color: white; }

.payment-status {
    font-weight: bold;
    text-transform: uppercase;
    font-size: 0.8em;
}

.payment-unpaid { color: #F44336; }
.payment-paid { color: #4CAF50; }

/* Enhance action buttons layout and spacing */
.action-buttons {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
    justify-content: flex-start;
    align-items: center;
}

/* Make buttons consistent in size */
.action-btn {
    padding: 6px 10px;
    border-radius: 4px;
    cursor: pointer;
    border: none;
    font-size: 0.85em;
    font-weight: 500;
    min-width: 70px;
    text-align: center;
    transition: all 0.2s ease;
    margin: 2px 0;
}

/* Specific button styles */
.view-btn {
    background-color: #2196F3;
    color: white;
}

.view-btn:hover {
    background-color: #0d8bf2;
}

.ship-btn {
    background-color: #4CAF50;
    color: white;
}

.ship-btn:hover {
    background-color: #3d9140;
}

.cancel-btn {
    background-color: #F44336;
    color: white;
}

.cancel-btn:hover {
    background-color: #e53935;
}

.complete-btn {
    background-color: #9C27B0;
    color: white;
}

.complete-btn:hover {
    background-color: #8e24aa;
}

/* Make the action column wider */
table th:last-child, 
table td:last-child {
    min-width: 180px;
    width: auto;
}


.search-filter {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.search-filter input, 
.search-filter select {
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.date-range {
    display: flex;
    gap: 5px;
    align-items: center;
}

/* Table container style */
.table-container {
    width: 100%;
    overflow-x: auto;
    overflow-y: hidden;
}

table {
    width: 100%;
    border-collapse: collapse;
    border-spacing: 0;
    table-layout: fixed;
}

table th, table td {
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid #ddd;
    word-wrap: break-word;
}

/* Modal styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: white;
    margin: 5% auto;
    padding: 20px;
    border-radius: 8px;
    width: 90%;
    max-width: 600px;
    position: relative;
    max-height: 80vh;
    overflow-y: auto;
}

.close-modal {
    position: absolute;
    right: 20px;
    top: 10px;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.order-detail-grid {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 10px;
    margin-bottom: 20px;
}

.order-detail-grid div {
    padding: 8px;
}

.order-detail-grid div:nth-child(odd) {
    font-weight: bold;
    background-color: #f5f5f5;
}

.alert {
    padding: 10px 15px;
    margin-bottom: 15px;
    border-radius: 4px;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.print-button {
    background-color: #607D8B;
    color: white;
}

@media print {
    .no-print {
        display: none;
    }
    
    body {
        font-size: 12pt;
    }
    
    .order-detail-grid {
        border: 1px solid #ddd;
    }
}

@media (max-width: 768px) {
    .orders-summary {
        flex-direction: column;
    }
    
    .summary-card {
        min-width: 100%;
    }
    
    .search-filter {
        flex-direction: column;
        align-items: stretch;
    }
    
    .date-range {
        flex-wrap: wrap;
    }
    .action-buttons {
        flex-direction: column;
        align-items: stretch;
    }
    
    .action-btn {
        margin: 3px 0;
        width: 100%;
    }
    
    table th:last-child, 
    table td:last-child {
        min-width: 100px;
    }
}</style>
<div class="orders-container">
    <div class="orders-header">
        <h2>My Orders</h2>
        <button onclick="exportOrders()" class="action-btn print-button">Export CSV</button>
    </div>
    
    <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">
        <?php echo htmlspecialchars($_GET['message'] ?? 'Order status updated successfully!'); ?>
    </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-error">
        <?php echo htmlspecialchars($_GET['message'] ?? 'There was an error updating the order status. Please try again.'); ?>
    </div>
    <?php endif; ?>
    
    <div class="orders-summary">
        <div class="summary-card">
            <h3>Pending</h3>
            <p class="pending"><?php echo count(array_filter($orders, function($o) { return $o['status'] === 'Pending'; })); ?></p>
        </div>
        <div class="summary-card">
            <h3>Shipped</h3>
            <p class="shipped"><?php echo count(array_filter($orders, function($o) { return $o['status'] === 'Shipped'; })); ?></p>
        </div>
        <div class="summary-card">
            <h3>Delivered</h3>
            <p class="delivered"><?php echo count(array_filter($orders, function($o) { return $o['status'] === 'Delivered'; })); ?></p>
        </div>
        <div class="summary-card">
            <h3>Cancelled</h3>
            <p class="cancelled"><?php echo count(array_filter($orders, function($o) { return $o['status'] === 'Cancelled'; })); ?></p>
        </div>
    </div>
    
    <div class="orders-filters search-filter">
        <select id="status-filter">
            <option value="">All Statuses</option>
            <option value="Pending">Pending</option>
            <option value="Shipped">Shipped</option>
            <option value="Delivered">Delivered</option>
            <option value="Cancelled">Cancelled</option>
        </select>
        
        <select id="payment-filter">
            <option value="">All Payments</option>
            <option value="Paid">Paid</option>
            <option value="Unpaid">Unpaid</option>
        </select>
        
        <div class="date-range">
            <label for="date-from">From:</label>
            <input type="date" id="date-from">
            <label for="date-to">To:</label>
            <input type="date" id="date-to">
        </div>
        
        <input type="text" id="search-input" placeholder="Search orders...">
        
        <button onclick="resetFilters()" class="action-btn">Reset</button>
    </div>

    <?php if (empty($orders)): ?>
        <div class="no-orders" style="text-align: center; padding: 20px; color: #888;">
            <p>No orders yet</p>
        </div>
    <?php else: ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Date</th>
                        <th>Product</th>
                        <th>Buyer</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr data-status="<?php echo htmlspecialchars($order['status']); ?>" 
                            data-payment="<?php echo htmlspecialchars($order['payment_status'] ?? 'Unpaid'); ?>"
                            data-date="<?php echo date('Y-m-d', strtotime($order['order_date'])); ?>">
                            <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                            <td><?php echo date('d M Y', strtotime($order['order_date'])); ?></td>
                            <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($order['buyer_name']); ?></td>
                            <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                            <td>₹<?php echo number_format($order['total_price'], 2); ?></td>
                            <td>
                                <span class="order-status status-<?php echo strtolower(htmlspecialchars($order['status'])); ?>">
                                    <?php echo htmlspecialchars($order['status']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="payment-status payment-<?php echo strtolower(htmlspecialchars($order['payment_status'] ?? 'unpaid')); ?>">
                                    <?php echo htmlspecialchars($order['payment_status'] ?? 'Unpaid'); ?>
                                </span>
                                <br>
                                <small><?php echo htmlspecialchars($order['payment_method'] ?? 'N/A'); ?></small>
                            </td>
                            <td class="action-buttons">
                                <button class="action-btn view-btn" onclick="viewOrderDetails(<?php echo $order['order_id']; ?>)">View</button>
                                
                                <?php if ($order['status'] === 'Pending'): ?>
                                    <button class="action-btn ship-btn" onclick="updateOrderStatus(<?php echo $order['order_id']; ?>, 'Shipped')">Ship</button>
                                    <button class="action-btn cancel-btn" onclick="updateOrderStatus(<?php echo $order['order_id']; ?>, 'Cancelled')">Cancel</button>
                                <?php elseif ($order['status'] === 'Shipped'): ?>
                                    <button class="action-btn complete-btn" onclick="updateOrderStatus(<?php echo $order['order_id']; ?>, 'Delivered')">Mark Delivered</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Order Details Modal -->
<div id="orderDetailsModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeModal()">&times;</span>
        <h2>Order Details</h2>
        <div id="orderDetails"></div>
        <div class="action-buttons no-print" style="margin-top: 20px; justify-content: flex-end;">
            <button class="action-btn print-button" onclick="window.print()">Print</button>
            <button class="action-btn" onclick="closeModal()">Close</button>
        </div>
    </div>
</div>

<!-- Status Update Form -->
<form id="statusUpdateForm" method="POST" style="display: none;">
    <input type="hidden" name="order_id" id="update_order_id">
    <input type="hidden" name="new_status" id="update_new_status">
    <input type="hidden" name="update_status" value="1">
</form>

<script>
    // Update order status function
    function updateOrderStatus(orderId, newStatus) {
        const statusText = {
            'Shipped': 'ship this order',
            'Delivered': 'mark this order as delivered',
            'Cancelled': 'cancel this order'
        };
        
        if (confirm(`Are you sure you want to ${statusText[newStatus]}?`)) {
            document.getElementById('update_order_id').value = orderId;
            document.getElementById('update_new_status').value = newStatus;
            document.getElementById('statusUpdateForm').submit();
        }
    }
    
    // Order details view with improved display
    function viewOrderDetails(orderId) {
        const orders = <?php echo json_encode($orders); ?>;
        const order = orders.find(o => o.order_id == orderId);
        
        if (!order) {
            alert("Order details not found");
            return;
        }
        
        // Format date properly
        const orderDate = new Date(order.order_date);
        const formattedDate = orderDate.toLocaleDateString('en-IN', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
        
        // Create a better order details view
        let detailsHTML = `
            <div class="order-detail-grid">
                <div>Order ID</div>
                <div>#${order.order_id}</div>
                
                <div>Date</div>
                <div>${formattedDate}</div>
                
                <div>Status</div>
                <div><span class="order-status status-${order.status.toLowerCase()}">${order.status}</span></div>
                
                <div>Product</div>
                <div>${order.product_name}</div>
                
                <div>Unit Price</div>
                <div>₹${parseFloat(order.unit_price).toFixed(2)}</div>
                
                <div>Quantity</div>
                <div>${order.quantity}</div>
                
                <div>Total Amount</div>
                <div>₹${parseFloat(order.total_price).toFixed(2)}</div>
                
                <div>Payment Method</div>
                <div>${order.payment_method || 'N/A'}</div>
                
                <div>Payment Status</div>
                <div><span class="payment-status payment-${(order.payment_status || 'unpaid').toLowerCase()}">${order.payment_status || 'Unpaid'}</span></div>
                
                <div>Buyer Information</div>
                <div>
                    <strong>${order.buyer_name}</strong><br>
                    Phone: ${order.buyer_phone || 'N/A'}<br>
                    Email: ${order.buyer_email || 'N/A'}
                </div>
                
                <div>Delivery Address</div>
                <div>
                    ${order.buyer_address_line1 || 'N/A'}<br>
                    ${order.buyer_address_line2 ? order.buyer_address_line2 + '<br>' : ''}
                    ${order.buyer_city ? order.buyer_city + ', ' : ''}
                    ${order.buyer_state || ''} ${order.buyer_postal_code || ''}
                </div>
            </div>
            
            <div class="no-print">
                <h3>Update Status</h3>
                <div class="action-buttons">
                    ${order.status === 'Pending' ? `
                        <button class="action-btn ship-btn" onclick="updateOrderStatus(${order.order_id}, 'Shipped')">Mark as Shipped</button>
                        <button class="action-btn cancel-btn" onclick="updateOrderStatus(${order.order_id}, 'Cancelled')">Cancel Order</button>
                    ` : ''}
                    
                    ${order.status === 'Shipped' ? `
                        <button class="action-btn complete-btn" onclick="updateOrderStatus(${order.order_id}, 'Delivered')">Mark as Delivered</button>
                    ` : ''}
                </div>
            </div>
        `;
        
        document.getElementById('orderDetails').innerHTML = detailsHTML;
        document.getElementById('orderDetailsModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('orderDetailsModal').style.display = 'none';
    }
    
    // Filter functions
    function applyFilters() {
        const statusFilter = document.getElementById('status-filter').value;
        const paymentFilter = document.getElementById('payment-filter').value;
        const searchTerm = document.getElementById('search-input').value.toLowerCase();
        const dateFrom = document.getElementById('date-from').value;
        const dateTo = document.getElementById('date-to').value;
        
        const rows = document.querySelectorAll('table tbody tr');
        
        rows.forEach(row => {
            const rowStatus = row.dataset.status;
            const rowPayment = row.dataset.payment;
            const rowDate = row.dataset.date;
            const rowText = row.textContent.toLowerCase();
            
            let showRow = true;
            
            // Status filter
            if (statusFilter && rowStatus !== statusFilter) {
                showRow = false;
            }
            
            // Payment filter
            if (paymentFilter && rowPayment !== paymentFilter) {
                showRow = false;
            }
            
            // Date range filter
            if (dateFrom && rowDate < dateFrom) {
                showRow = false;
            }
            
            if (dateTo && rowDate > dateTo) {
                showRow = false;
            }
            
            // Search term
            if (searchTerm && !rowText.includes(searchTerm)) {
                showRow = false;
            }
            
            row.style.display = showRow ? '' : 'none';
        });
    }
    
    function resetFilters() {
        document.getElementById('status-filter').value = '';
        document.getElementById('payment-filter').value = '';
        document.getElementById('search-input').value = '';
        document.getElementById('date-from').value = '';
        document.getElementById('date-to').value = '';
        
        const rows = document.querySelectorAll('table tbody tr');
        rows.forEach(row => {
            row.style.display = '';
        });
    }
    
    // Export orders to CSV
    function exportOrders() {
        const orders = <?php echo json_encode($orders); ?>;
        
        // Define CSV headers
        let csvContent = "Order ID,Date,Product,Buyer,Quantity,Total Price,Status,Payment Status,Payment Method\n";
        
        // Add each order as a row
        orders.forEach(order => {
            const row = [
                order.order_id,
                order.order_date,
                order.product_name,
                order.buyer_name,
                order.quantity,
                order.total_price,
                order.status,
                order.payment_status || 'Unpaid',
                order.payment_method || 'N/A'
            ].map(item => `"${item}"`).join(',');
            
            csvContent += row + "\n";
        });
        
        // Create download link
        const encodedUri = encodeURI("data:text/csv;charset=utf-8," + csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "orders_export_" + new Date().toISOString().slice(0,10) + ".csv");
        document.body.appendChild(link);
        
        // Trigger download
        link.click();
    }
    
    // Event listeners
    document.getElementById('status-filter').addEventListener('change', applyFilters);
    document.getElementById('payment-filter').addEventListener('change', applyFilters);
    document.getElementById('search-input').addEventListener('input', applyFilters);
    document.getElementById('date-from').addEventListener('change', applyFilters);
    document.getElementById('date-to').addEventListener('change', applyFilters);
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('orderDetailsModal');
        if (event.target == modal) {
            closeModal();
        }
    }
</script>