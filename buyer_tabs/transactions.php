<?php
// session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ams_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$buyer_id = (int)$_SESSION['id'];

// Fetch transactions
$transaction_query = $conn->prepare("SELECT id, payment_method, amount, payment_status, transaction_date FROM transactions WHERE buyer_id = ? ORDER BY transaction_date DESC");
$transaction_query->bind_param("i", $buyer_id);
$transaction_query->execute();
$transaction_result = $transaction_query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions</title>
    <style>
         .download-btn {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            margin-right: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            background-color: #4CAF50;
            color: white;
            border: none;
        }
        
        .download-btn :hover {
            background-color: #45a049;
        }
        
    </style>
   
</head>
<body>
    <div class="table-container">
        <h2>My Transactions</h2>
        <table>
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Payment Method</th>
                    <th>Amount</th>
                    <th>Payment Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
    <?php if ($transaction_result->num_rows > 0): ?>
        <?php while ($transaction = $transaction_result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($transaction['id']) ?></td>
                <td><?= htmlspecialchars($transaction['payment_method']) ?></td>
                <td>₹<?= number_format($transaction['amount'], 2) ?></td>
                <td><?= htmlspecialchars($transaction['payment_status']) ?></td>
                <td><?= $transaction['transaction_date'] ?></td>
                <td><a href="/AgriDisha/buyer_tabs/receipt.php?id=<?= $transaction['id'] ?>" class="download-btn">Download Receipt</a></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="6">No transactions found.</td></tr>
    <?php endif; ?>
</tbody>
</html>