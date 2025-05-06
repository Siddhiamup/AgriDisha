<?php

if (isset($_POST["email"]) && !empty($_POST["email"])) {
    $email = $_POST["email"];
} else {
    echo "<script>alert('Email is required');</script>";

}

$token = bin2hex(random_bytes(16));
$token_hash = hash("sha256", $token);
$expiry = date("Y-m-d H:i:s", time() + 60 * 30);

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



$sql = "UPDATE users
        SET reset_token = ?,
            reset_token_expiry= ?
        WHERE email = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Database error: " . $conn->error);
}

$stmt->bind_param("sss", $token_hash, $expiry, $email);

if (!$stmt->execute()) {
    die("Database error: " . $stmt->error);
}

if ($conn->affected_rows) {
    $mail = require __DIR__ . "/mailer.php";

    if (!$mail) {
         echo "<script>alert('Mailer configuration error');</script>";

    }

    $mail->setFrom("smartsheti333@gmail.com");
    $mail->addAddress($email); // Send to the user's email address
    $mail->Subject = "Password Reset";

    $resetLink = htmlspecialchars("https://1081-49-248-221-170.ngrok-free.app/AMSDemo/reset-password.php?token=$token", ENT_QUOTES, 'UTF-8');
    $mail->Body = <<<END
    Click <a href="$resetLink">here</a> to reset your password.
    END;

    try {
        $mail->send();
         echo "<script>alert('Message sent, please check your inbox');</script>";

    } catch (Exception $e) {
          echo "<script>alert('Message could not be sent. Mailer error: {$mail->ErrorInfo}');</script>";

    }
} else {
   echo "<script>alert('No user found with that email address');window.location.href='/AgriDisha/forgot-password.php';</script>";

}
