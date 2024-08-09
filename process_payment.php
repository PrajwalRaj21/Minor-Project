<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "car_rental"; // Change this to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables to store form data
$cardNumber = $expiryDate = $cvv = "";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate form data
    $cardNumber = sanitize_input($_POST['cardNumber']);
    $expiryDate = sanitize_input($_POST['expiryDate']);
    $cvv = sanitize_input($_POST['cvv']);
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $phone = sanitize_input($_POST['phone']);
    $dob = sanitize_input($_POST['dob']);
    $license = sanitize_input($_POST['license']);
    $fromDate = sanitize_input($_POST['fromDate']);
    $toDate = sanitize_input($_POST['toDate']);
    $carModel = sanitize_input($_POST['carModel']);

    // Generate unique reservation code
    $reservationCode = generateReservationCode();

    // Prepare and bind SQL statement to store payment information
    $stmt = $conn->prepare("INSERT INTO payment_information (cardNumber, expiryDate, cvv) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $cardNumber, $expiryDate, $cvv);

    // Execute SQL statement
    if ($stmt->execute()) {
        // Redirect to confirmation page with URL parameters
        header("Location: confirmation.php?name=$name&email=$email&phone=$phone&dob=$dob&license=$license&fromDate=$fromDate&toDate=$toDate&carModel=$carModel&reservationCode=$reservationCode");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();

// Function to sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to generate a unique reservation code
function generateReservationCode() {
    // Generate a random component
    $randomComponent = bin2hex(random_bytes(4));
    // Get current timestamp
    $timestamp = time();
    // Combine timestamp and random component
    $reservationCode = $timestamp . $randomComponent;
    return $reservationCode;
}
?>
