<?php

$token = $_POST["token"];

$token_hash = hash("sha256", $token);

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


$sql = "SELECT * FROM users
        WHERE reset_token = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
          echo "<script>alert('token not found');</script>";

}

if (strtotime($user["reset_token_expiry"]) <= time()) {
        echo "<script>alert('token has expired');</script>";

}

if (strlen($_POST["password"]) < 8) {
       echo "<script>alert('Password must be at least 8 characters');</script>";

}

if ( ! preg_match("/[a-z]/i", $_POST["password"])) {
        echo "<script>alert('Password must contain at least one letter');</script>";

}

if ( ! preg_match("/[0-9]/", $_POST["password"])) {
        echo "<script>alert('Password must contain at least one number');</script>";

}

if ($_POST["password"] !== $_POST["password_confirmation"]) {
           echo "<script>alert('Passwords must match');</script>";

}

$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

$sql = "UPDATE users
        SET password_hash = ?,
            reset_token = NULL,
            reset_token_expiry = NULL
        WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("ss", $password_hash, $user["id"]);

$stmt->execute();
     echo "<script>alert('Password updated. You can now login');</script>";
