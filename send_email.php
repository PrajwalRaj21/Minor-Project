<?php
// Include PHPMailer autoloader
require 'C:/xampp/htdocs/project/vendor/PHPMailer/src/PHPMailer.php';
require 'C:/xampp/htdocs/project/vendor/PHPMailer/src/SMTP.php';
require 'C:/xampp/htdocs/project/vendor/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Function to generate a random 6-digit OTP
function generateOTP() {
    return mt_rand(100000, 999999); // Generate a random number between 100000 and 999999
}

// Create a new PHPMailer instance
$mail = new PHPMailer();

try {
    // SMTP configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';

    // Gmail SMTP authentication
    $mail->Username = 'sawaribhada@gmail.com'; // Your Gmail email address
    $mail->Password = 'vwitgyrlbfpbevdf'; // Your Gmail password

    // Sender and recipient settings
    $mail->setFrom('sawaribhada@gmail.com', 'Sawari Bhada'); // Your Gmail email address and your name
    $mail->addAddress('purnika929@gmail.com', 'Pawan Kumar Giri'); // Recipient's email address and name

    // Email content
    $mail->isHTML(true);
    $mail->Subject = 'Your OTP for Rental Form';
    $mail->Body    = 'Your OTP is: ' . generateOTP(); // Define generateOTP() function

    // Send email
    $mail->send();
    echo 'Email sent successfully';
} catch (Exception $e) {
    echo "Email sending failed: {$mail->ErrorInfo}";
}
?>
