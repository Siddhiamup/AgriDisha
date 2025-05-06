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

// Redirect to login page if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch current user details
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Update profile if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
   // $address =$_POST['address'];

    // Update query
    $update_query = "UPDATE users SET username = '$username', 
                     email = '$email' WHERE id = '$user_id'";
    mysqli_query($conn, $update_query);

    // Redirect to dashboard after update
    if($update_query->execute()){
    alert('Profile updated successfully!');}
    header("Location: fardemo.php");
    
    exit();
    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Edit Your Profile</h1>

<form method="POST">
    <label for="user_name">User Name:</label>
    <input type="text" name="username" id="username" value="<?php echo $user['username']; ?>" required><br><br>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" value="<?php echo $user['email']; ?>" required><br><br>


    <button type="submit">Update Profile</button>
</form>

</body>
</html>
