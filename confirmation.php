<?php
// Function to calculate total cost based on rental dates
function calculateTotalCost($start_date, $end_date) {
    // Dummy calculation, replace it with your actual calculation logic
    $total_days = (strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24); // Calculate total rental days
    $daily_rate = 50; // Dummy daily rate, replace it with your actual rate
    $total_cost = $total_days * $daily_rate; // Calculate total cost
    return $total_cost;
}

// Check if the form was submitted and the variables are set
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract form data including the payment method
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
    $dl_number = $_POST['license'];
    $rental_start_date = $_POST['fromDate'];
    $rental_end_date = $_POST['toDate'];
    $payment_method = $_POST['payment_method']; // Retrieve payment method
    $reservation_code = uniqid();
    $total_cost = calculateTotalCost($rental_start_date, $rental_end_date);
    $timestamp = date("Y-m-d H:i:s");

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "car_rental";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind SQL statement (including payment method)
    $stmt = $conn->prepare("INSERT INTO users (name, email, phone, dob, dl_number, rental_start_date, rental_end_date, payment_method, reservation_code, total_cost, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssss", $name, $email, $phone, $dob, $dl_number, $rental_start_date, $rental_end_date, $payment_method, $reservation_code, $total_cost, $timestamp);

    // Execute SQL statement
    if ($stmt->execute()) {
        // Data inserted successfully
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            animation: slide-in 0.5s ease-out;
        }

        @keyframes slide-in {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        p {
            margin: 10px 0;
        }

        .button-container {
            text-align: center;
            margin-top: 30px;
        }

        .print-button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .print-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Receipt</h1>
    <p><strong>Name:</strong> <?php echo $name; ?></p>
    <p><strong>Email:</strong> <?php echo $email; ?></p>
    <p><strong>Phone:</strong> <?php echo $phone; ?></p>
    <p><strong>Date of Birth:</strong> <?php echo $dob; ?></p>
    <p><strong>Driving License Number:</strong> <?php echo $dl_number; ?></p>
    <p><strong>Rental Start Date:</strong> <?php echo $rental_start_date; ?></p>
    <p><strong>Rental End Date:</strong> <?php echo $rental_end_date; ?></p>
    <p><strong>Payment Method:</strong> <?php echo $payment_method; ?></p>
    <p><strong>Reservation Code:</strong> <?php echo $reservation_code; ?></p>
    <p><strong>Total Cost:</strong> $<?php echo number_format($total_cost, 2); ?></p>
    <p><strong>Timestamp:</strong> <?php echo $timestamp; ?></p>
    <div class="button-container">
        <button class="print-button" onclick="window.print()">Print Receipt</button>
        <button class="home-button" onclick="goHome()">Go Home</button>
    </div>

</div>
<script>
    function goHome() {
        window.location.href = "home.html"; // Replace "index.php" with the URL of your home page
    }
</script>

</body>
</html>

<?php
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // If the form was not submitted or the variables are not set, display an error message
    echo "Error: Form data not received.";
}
?>
