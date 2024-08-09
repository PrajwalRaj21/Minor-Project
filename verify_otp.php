<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Generate random OTP
    $otp = rand(100000, 999999);
    
    // Send OTP to the user's email
    $to = $_POST['email'];
    $subject = "Your OTP for Rental Form";
    $message = "Your OTP is: " . $otp;
    $headers = "From: christprajwal@gmail.com"; // Update with your email
    
    if (mail($to, $subject, $message, $headers)) {
        // Store OTP in session for verification
        $_SESSION['otp'] = $otp;
        echo "success";
    } else {
        echo "error";
    }
}
?>
