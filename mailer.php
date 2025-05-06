<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/src/Exception.php';
require 'vendor/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/src/SMTP.php';
// Use __DIR__ to construct the correct path
require __DIR__ . "/vendor/autoload.php";

$mail = new PHPMailer(true);

//  $mail->SMTPDebug = SMTP::DEBUG_SERVER;

$mail->isSMTP();
$mail->SMTPAuth = true;

// SMTP configuration
$mail->Host = "smtp.gmail.com";
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;
$mail->Username = "smartsheti333@gmail.com";
$mail->Password = "nocv twel synw tfgl"; // Use an App Password for Gmail, not your main password

$mail->isHtml(true);

return $mail;
