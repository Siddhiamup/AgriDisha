<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ams_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    error_log("DB Connection Error: " . $conn->connect_error);
    die("Oops! Something went wrong. Please try again later.");
}

// Ensure user is authenticated
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Validate transaction ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid transaction ID");
}

$transaction_id = (int)$_GET['id'];
$buyer_id = (int)$_SESSION['id'];

// Fetch transaction details
$query = $conn->prepare("SELECT t.*, o.order_date, o.status as order_status, o.product_id, o.quantity, 
           p.name as product_name, p.price as product_price, p.unit,
           s.username as seller_name
    FROM transactions t
    JOIN orders o ON t.order_id = o.id
    JOIN products p ON o.product_id = p.id
    JOIN users s ON o.seller_id = s.id
    WHERE t.id = ? AND t.buyer_id = ?");

$query->bind_param("ii", $transaction_id, $buyer_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    die("Transaction not found or unauthorized access");
}

$transaction = $result->fetch_assoc();

// Fetch buyer information
$buyer_query = $conn->prepare("SELECT username, email, phone, address_line1, address_line2, city FROM users WHERE id = ?");
$buyer_query->bind_param("i", $buyer_id);
$buyer_query->execute();
$buyer_result = $buyer_query->get_result();
$buyer = $buyer_result->fetch_assoc();

// Company Information
$company = [
    'name' => 'AgriDisha Marketplace',
    'address' => 'Flat No. 206, Sai Nisarg Apartment, Shivane, Pune, Maharashtra - 411023',
    'phone' => '+91 9021237270',
    'email' => 'contact@agridisha.com',
    'website' => 'www.agridisha.com'
];

// Generate PDF receipt
require  '../fpdf186/fpdf.php';

class PDF extends FPDF {
    function Header() {
        $this->Image( '../ASSETS/IMAGES/logo.jpg', 10, 10, 50); // Centered logo
        $this->Ln(20);
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(190, 10, 'PAYMENT RECEIPT', 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Thank you for your purchase!', 0, 1, 'C');
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);
$pdf->SetFillColor(220, 220, 220);

// Company Details
$pdf->Cell(0, 10, $company['name'], 0, 1, 'C', true);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, $company['address'], 0, 1, 'C');
$pdf->Cell(0, 6, 'Phone: ' . $company['phone'], 0, 1, 'C');
$pdf->Cell(0, 6, 'Email: ' . $company['email'], 0, 1, 'C');
$pdf->Cell(0, 6, 'Website: ' . $company['website'], 0, 1, 'C');
$pdf->Ln(10);

// Transaction Details Table
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'Transaction Details', 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(90, 6, 'Transaction ID: ' . $transaction['id'], 1, 0, 'L');
$pdf->Cell(90, 6, 'Date: ' . date('d-m-Y', strtotime($transaction['transaction_date'])), 1, 1, 'L');
$pdf->Ln(5);

// Buyer Details
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'Buyer Information', 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, 'Name: ' . htmlspecialchars($buyer['username']), 1, 1, 'L');
$pdf->Cell(0, 6, 'Email: ' . htmlspecialchars($buyer['email']), 1, 1, 'L');
$pdf->Cell(0, 6, 'Phone: ' . htmlspecialchars($buyer['phone']), 1, 1, 'L');
$pdf->Ln(5);

// Order Details
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'Order Details', 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(90, 6, 'Order ID: ' . $transaction['order_id'], 1, 0, 'L');
$pdf->Cell(90, 6, 'Order Date: ' . date('d-m-Y', strtotime($transaction['order_date'])), 1, 1, 'L');
$pdf->Cell(90, 6, 'Seller: ' . htmlspecialchars($transaction['seller_name']), 1, 0, 'L');
$pdf->Cell(90, 6, 'Status: ' . htmlspecialchars($transaction['order_status']), 1, 1, 'L');
$pdf->Ln(5);

// Total Amount
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 8, 'Total Paid: Rs. ' . number_format($transaction['amount'], 2), 1, 1, 'C', true);

$pdf->Output('D', 'Receipt_Order_' . $transaction['order_id'] . '_' . $transaction_id . '.pdf');
exit();
?>