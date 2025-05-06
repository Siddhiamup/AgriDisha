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


session_start();

// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit();
// }

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $query = "SELECT * FROM product WHERE id = '$product_id'";
    $result = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($result);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    $update_query = "UPDATE product SET name = '$name', description = '$description', 
                     quantity = '$quantity', price = '$price' WHERE id = '$product_id'";
    mysqli_query($conn, $update_query);

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Edit Product</h1>

<form method="POST">
    <label for="name">Product Name:</label>
    <input type="text" name="name" id="name" value="<?php echo $product['name']; ?>" required><br><br>

    <label for="description">Description:</label>
    <textarea name="description" id="description" required><?php echo $product['description']; ?></textarea><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" id="quantity" value="<?php echo $product['quantity']; ?>" required><br><br>

    <label for="price">Price:</label>
    <input type="number" name="price" id="price" value="<?php echo $product['price']; ?>" step="0.01" required><br><br>

    <button type="submit">Update Product</button>
</form>

</body>
</html>
