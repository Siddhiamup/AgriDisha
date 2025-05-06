
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ams_db"; // Change this if your database name is different

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $query = "UPDATE products SET quantity = quantity - 1 WHERE id = '$product_id'";
    mysqli_query($conn, $query);
}

header("Location: index.php");
exit();
?>
