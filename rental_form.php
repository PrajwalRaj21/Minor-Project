<?php
require 'C:/xampp/htdocs/project/vendor/PHPMailer/src/PHPMailer.php';
require 'C:/xampp/htdocs/project/vendor/PHPMailer/src/SMTP.php';
require 'C:/xampp/htdocs/project/vendor/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function generateOTP() {
    return mt_rand(100000, 999999); // Generate a random number between 100000 and 999999
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the email address is provided
    if (isset($_POST["email"]) && !empty($_POST["email"])) {
        // Check if OTP has already been sent
        if (!isset($_POST["otp_sent"])) {
            // Generate OTP
            $otp = generateOTP();

            // Send OTP via email
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
                $mail->addAddress($_POST["email"]); // Recipient's email address
                $mail->isHTML(true);

                // Email content
                $mail->Subject = 'Your OTP for Rental Form';
                $mail->Body    = 'Your OTP is: ' . $otp;

                // Send email
                if ($mail->send()) {
                    // Email sent successfully
                    echo json_encode(['success' => true, 'message' => 'Email sent successfully', 'otp' => $otp]);
                } else {
                    // Email sending failed
                    echo json_encode(['success' => false, 'message' => 'Failed to send OTP. Please try again later.']);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Email sending failed: ' . $mail->ErrorInfo]);
            }
            exit;
        } else {
            // OTP already sent, do not send again
            echo json_encode(['success' => false, 'message' => 'OTP already sent. Please check your email.']);
            exit;
        }
    }
}

// Handle form submission and redirect to confirmation page
session_start(); // Start the session to access session variables

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Store form data in session variables
    $_SESSION['name'] = $_POST['name'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['phone'] = $_POST['phone'];
    $_SESSION['dob'] = $_POST['dob'];
    $_SESSION['license'] = $_POST['dl_number']; // Adjusted to match the form field name
    $_SESSION['fromDate'] = $_POST['rental_start_date']; // Adjusted to match the form field name
    $_SESSION['toDate'] = $_POST['rental_end_date']; // Adjusted to match the form field name
    $_SESSION['paymentMethod'] = $_POST['payment_method']; // Adjusted to match the form field name
    $_SESSION['car.Model'] = $_POST['car_model']; // Adjusted to match the form field name

    // Redirect to confirmation page
    header("Location: confirmation.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Form</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,300;1,300&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        h2 {
            margin-top: 0;
            text-align: center;
        }
        
        /* Form Steps */
        .steps {
            position: relative;
            margin-bottom: 30px;
        }
        
        .step {
            display: none;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        
        .step.active {
            display: block;
        }
        
        fieldset {
            margin-bottom: 20px;
            border: none;
        }
        
        legend {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        
        label {
            display: block;
            margin-bottom: 10px;
            color: #555;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="date"],
        input[type="file"],
        input[type="radio"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        input[type="file"] {
            width: auto;
        }
        
        input[type="radio"] {
            margin-right: 5px;
        }
        
        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        
        button:hover {
            background-color: #0056b3;
        }
        
        /* Slider */
        .slider {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        
        .slider-step {
            width: 30px; /* Increased size of slider step */
            height: 30px; /* Increased size of slider step */
            background-color: #ccc;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            color: #fff;
            font-size: 14px; /* Increased font size of slider step */
        }
        
        .slider-step.active {
            background-color: #007bff;
        }
        
        /* Credit Card Form */
        #creditCardForm {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            z-index: 9999;
        }
        
        #creditCardForm label {
            margin-bottom: 5px;
            color: #333;
        }
        
        #creditCardForm input[type="text"],
        #creditCardForm input[type="number"],
        #creditCardForm select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        #creditCardForm button {
            width: 100%;
            margin-top: 10px;
        }
        /* CSS for Step 4 */
        /* CSS for Step 4 */
.step {
    margin-bottom: 40px; /* Add more space between steps */
}

.payment-method {
    margin-top: 20px; /* Add space above buttons */
}

.payment-method button {
    padding: 15px 30px; /* Increase padding for buttons */
    margin-right: 20px; /* Add space between buttons */
}

#paymentMessage {
    margin-top: 20px; /* Add space below buttons for the message */
}


    </style>
</head>
<body>

<header class="header">
    <!-- Add header content -->
</header>

<main class="main-content">
    <div class="container">
        <h2>Rental Form</h2>
        <form id="rentalForm" action="confirmation.php" method="post" enctype="multipart/form-data">
            <!-- Step 1: Personal Information -->
            <div id="step1" class="step active">
                <fieldset>
                    <legend>Step 1: Personal Information</legend>
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
        
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    <span id="emailError" style="color: red;"></span>
                    <button type="button" id="sendOTP">Send OTP</button>
                    <div id="otpSection" style="display: none;">
                        <label for="otp">OTP:</label>
                        <input type="text" id="otp" name="otp" required>
                    </div>
        
                    <label for="phone">Phone:</label>
                    <input type="tel" id="phone" name="phone" required>
        
                    <label for="dob">Date of Birth:</label>
                    <input type="date" id="dob" name="dob" required>

                    <legend>Agree to Terms and Conditions</legend>
                    <label for="agreeTerms">
                        <input type="checkbox" id="agreeTerms" name="agreeTerms" required>
                        I agree to the <a href="terms_and_condition.php" target="_blank">Terms and Conditions</a>
                    </label>
        
                    <button type="button" id="nextStep1">Next</button>
                </fieldset>
            </div>
            <!-- Step 2: License and Dates -->
            <div id="step2" class="step">
                <fieldset>
                    <legend>Step 2: License and Dates</legend>
                    <label for="license">Driving License Number:</label>
                    <input type="text" id="license" name="license" required>
        
                    <label for="fromDate">Pick Up Date:</label>
                    <input type="date" id="fromDate" name="fromDate" required>
        
                    <label for="toDate">Drop Off Date:</label>
                    <input type="date" id="toDate" name="toDate" required>
        
                    <button type="button" id="prevStep2">Previous</button>
                    <button type="button" id="nextStep2">Next</button>
                </fieldset>
            </div>
            <!-- Step 3: Upload License -->
            <div id="step3" class="step">
                <fieldset>
                    <legend>Step 3: Upload License</legend>
                    <label for="licenseImage">Upload Driving License:</label>
                    <input type="file" id="licenseImage" name="licenseImage" accept="image/*" required>
        
                    <button type="button" id="prevStep3">Previous</button>
                    <button type="button" id="nextStep3">Next</button>
                </fieldset>
            </div>
            <!-- Step 4: Payment Method -->
            <div id="step4" class="step">
    <fieldset>
        <legend>Step 4: Payment Method</legend>
        <div id="totalPriceSection">
            <div id="totalPriceSection">
                <span id="totalPrice">
                    <?php
                    $rental_price = isset($_GET['price']) ? $_GET['price'] : '';
                    echo "Rental Price: $" . $rental_price;
                    ?>
                </span><br>
                Security Deposit (Refunded after PDI): $150<br>
                Service Charge: $50
            </div>
        </div>
        
        <input type="hidden" id="car_model" name="car_model" value="<?php echo isset($_GET['car.Model']) ? htmlspecialchars($_GET['car.Model']) : ''; ?>">
        
       
        
        <!-- Insert the select element for payment method -->
        <label for="payment_method">Payment Method:</label>
        <select id="payment_method" name="payment_method" required>
            <option value="">Select Payment Method</option>
            <option value="Cash">Cash</option>
            <option value="Card">Card</option>
        </select>
        
        <div id="paymentMessage"></div>
        
        <button type="button" id="prevStep4">Previous</button>
        <button type="submit" id="proceedToCheckout">Proceed To Checkout</button>
    </fieldset>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script>
    $(document).ready(function() {
        // Function to show the specified step
        function showStep(stepId) {
            $('.step').hide(); // Hide all steps
            $('#' + stepId).show(); // Show the specified step
        }

        // Step 1: Send OTP
        $('#sendOTP').click(function() {
            var email = $('#email').val();
            if (email) {
                // Send AJAX request to PHP script to send OTP
                $.ajax({
                    url: '<?php echo $_SERVER["PHP_SELF"]; ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {email: email},
                    success: function(response) {
                        if (response.success) {
                            // Show success message
                            alert(response.message);
                            // Store OTP in localStorage
                            localStorage.setItem('otp', response.otp);
                            // Show OTP section
                            $('#otpSection').show();
                        } else {
                            // Show error message
                            alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Show error message
                        alert('Failed to send OTP. Please try again later. Error: ' + error);
                    }
                });
            } else {
                // Show error message if email is not provided
                alert('Please enter your email address.');
            }
        });

        // Step 1: Validate OTP and move to Step 2
        $('#nextStep1').click(function() {
            if ($('#rentalForm').valid()) {
                // Validate OTP
                if ($('#otp').val() === localStorage.getItem('otp')) {
                    // Move to Step 2
                    showStep('step2');
                } else {
                    // Show error message for invalid OTP
                    alert('Invalid OTP. Please enter the correct OTP.');
                }
            }
        });

        // Step 2: Move to Step 1
        $('#prevStep2').click(function() {
            showStep('step1');
        });

        // Step 2: Move to Step 3
        $('#nextStep2').click(function() {
            if ($('#rentalForm').valid()) {
                showStep('step3');
            }
        });

        // Step 3: Move to Step 2
        $('#prevStep3').click(function() {
            showStep('step2');
        });

        // Step 3: Move to Step 4
        $('#nextStep3').click(function() {
            if ($('#rentalForm').valid()) {
                showStep('step4');
            }
        });

        // Step 4: Move to Step 3
        $('#prevStep4').click(function() {
            showStep('step3');
        });

        // Validate OTP
        $.validator.addMethod("validOTP", function(value, element) {
            return value === localStorage.getItem('otp');
        }, "Invalid OTP.");

        // Form validation rules
        $('#rentalForm').validate({
            rules: {
                otp: {
                    validOTP: true
                }
            },
            messages: {
                otp: {
                    validOTP: "Invalid OTP. Please enter the correct OTP."
                }
            }
        });
    });
    $(document).ready(function() {
    // Step 4: Handle payment method buttons
    $('#payWithCash').click(function() {
        $('#paymentMessage').text("You are obliged to pay when you pick up the car.");
    });

    $('#payWithCard').click(function() {
        $('#paymentMessage').text("Redirecting to payment gateway...");
        setTimeout(function() {
            window.location.href = "payment_gateway.php"; // Redirect after 3 seconds
        }, 3000); // Redirect after 3 seconds
    });
});

   
</script>

</body>
</html>
