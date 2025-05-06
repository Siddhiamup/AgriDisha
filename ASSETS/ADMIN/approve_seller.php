<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ams_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Approve seller (status update)
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $seller_id = intval($_GET['id']);
    
    // Check if seller exists
    $check_stmt = $conn->prepare("SELECT * FROM users WHERE id=? AND role='farmer'");
    $check_stmt->bind_param("i", $seller_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $check_stmt->close();

    if ($result->num_rows > 0) {
        // Update status to "approved"
        $stmt = $conn->prepare("UPDATE users SET status='approved' WHERE id=?");
        $stmt->bind_param("i", $seller_id);
        $message = $stmt->execute() ? "Seller approved successfully." : "Error approving seller.";
        $stmt->close();
    } else {
        $message = "Seller not found.";
    }
} else {
    $message = "Invalid seller ID.";
}
$conn->close();

// Alert and redirect
echo "<script>
        alert('$message');
        window.location.href = './admin.php?tab=manage_sellers';
      </script>";
?>
