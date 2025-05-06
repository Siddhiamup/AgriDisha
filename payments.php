<?php
// Modify the query to work with the current database schema
$payments_query = "SELECT t.*, 
                          o.id AS order_id, 
                          o.product_id,
                          o.quantity,
                          p.name AS product_name,
                          u.username AS buyer_name
                   FROM transactions t
                   LEFT JOIN orders o ON t.order_id = o.id
                   LEFT JOIN products p ON o.product_id = p.id
                   LEFT JOIN users u ON t.buyer_id = u.id
                   WHERE o.seller_id = ?
                   ORDER BY t.transaction_date DESC";

// Prepare and execute the statement
$stmt = $conn->prepare($payments_query);
$stmt->bind_param("i", $_SESSION['id']); // Assuming farmer's ID is stored in session
$stmt->execute();
$payments = $stmt->get_result();
?>

<h2>Farmer Payment History</h2>
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Transaction Date</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Buyer Name</th>
                <th>Total Amount</th>
                <th>Payment Method</th>
                <th>Payment Status</th>
                <th>Transaction ID</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($payments->num_rows > 0): ?>
                <?php while ($payment = $payments->fetch_assoc()): ?>
                <tr>
                    <td><?php echo date('d-m-Y H:i', strtotime($payment['transaction_date'])); ?></td>
                    <td><?php echo htmlspecialchars($payment['product_name'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($payment['quantity'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($payment['buyer_name']); ?></td>
                    <td>₹<?php echo number_format($payment['amount'], 2); ?></td>
                    <td><?php echo htmlspecialchars($payment['payment_method']); ?></td>
                    <td>
                        <?php 
                        // Color-code payment status
                        $status = htmlspecialchars($payment['payment_status']);
                        $status_class = '';
                        switch ($status) {
                            case 'Paid':
                                $status_class = 'status-success';
                                break;
                            case 'Unpaid':
                                $status_class = 'status-warning';
                                break;
                            case 'Refunded':
                                $status_class = 'status-error';
                                break;
                        }
                        echo "<span class='$status_class'>$status</span>";
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($payment['id']); ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                   <center> <td colspan="8" class="text-center">No payment history found</td></center>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- <style>
.table-container {
    width: 100%;
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

table th, table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

table th {
    background-color: #f2f2f2;
    font-weight: bold;
}

.status-success {
    color: green;
    font-weight: bold;
}

.status-warning {
    color: orange;
    font-weight: bold;
}

.status-error {
    color: red;
    font-weight: bold;
}


</style> -->