<?php
require 'C:/xampp/htdocs/project/vendor/PHPMailer/src/PHPMailer.php';
require 'C:/xampp/htdocs/project/vendor/PHPMailer/src/SMTP.php';
require 'C:/xampp/htdocs/project/vendor/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $company = $_POST['company'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    // Compose the email message
    $subject = "Contact Form Submission";
    $body = "Name: $name\n";
    $body .= "Company: $company\n";
    $body .= "Email: $email\n";
    $body .= "Phone: $phone\n";
    $body .= "Message: $message\n";

    // Configure PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'sawaribhada@gmail.com'; // Replace with your Gmail email address
        $mail->Password = 'vwitgyrlbfpbevdf'; // Replace with your Gmail password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom($email, $name); // Set sender's email address and name

        $mail->addAddress('sawaribhada@gmail.com'); // Add your recipient email address

        // Content
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body = $body;

        // Send email
        $mail->send();
        echo "Email sent successfully!";
    } catch (Exception $e) {
        echo "Email sending failed: {$mail->ErrorInfo}";
    }
}
?>
