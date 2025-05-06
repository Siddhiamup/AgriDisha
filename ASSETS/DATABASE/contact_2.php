<?php
session_start();
//require_once 'INCLUDES/db.php';

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


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $mobile = htmlspecialchars($_POST["mobile"]);
    $comment = htmlspecialchars($_POST["comment"]);

    // Save to a database 
    
    $sql = "INSERT INTO contact_us (name,email,mobile,comment) VALUES ('$name','$email','$mobile','$comment')";

    if ($conn->query($sql) === TRUE) {
        echo '
        <script>
        alert("Thanks for contacting us, we will reach out to you very soon.");
        window.location.assign("../../index.php");
        </script>';
    } else {
        echo '
        <script>
        alert("Can\'t connect to the server at this moment. Please try again later!");
        window.location.assign("../../index.php");
        </script>';
    }
}
?>
