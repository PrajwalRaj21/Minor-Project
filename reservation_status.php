<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Status</title>
    <style>
        /* Reset CSS */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 600px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #007bff;
        }

        .reservation-info {
            margin-top: 20px;
        }

        .reservation-info p {
            margin-bottom: 10px;
        }

        .rate-vehicle-btn {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }

        .rate-vehicle-btn:hover {
            background-color: #0056b3;
        }

        .go-home-btn {
            text-align: center;
            margin-top: 20px;
        }

        .go-home-btn button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .go-home-btn button:hover {
            background-color: #0056b3;
        }
    </style>
   
</head>
<body>

<div class="container">
    <h1>Reservation Status</h1>
    <?php
    // Establish a connection to your database
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=car_rental', 'root', '');
        // Set PDO to throw exceptions on error
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        // Handle connection error
        die("Connection failed: " . $e->getMessage());
    }

    // Check if reservation code and email are provided
    if (isset($_GET['reservationCode']) && isset($_GET['email'])) {
        // Sanitize the input to prevent SQL injection
        $reservation_code = htmlspecialchars($_GET['reservationCode']);
        $email = htmlspecialchars($_GET['email']);

        try {
            // Prepare and execute a SQL statement to fetch data for the specific reservation
            $statement = $pdo->prepare('SELECT * FROM users WHERE reservation_code = :reservation_code AND email = :email');
            $statement->execute(array(':reservation_code' => $reservation_code, ':email' => $email));

            // Fetch the row for the specific reservation
            $reservation = $statement->fetch(PDO::FETCH_ASSOC);

            if ($reservation) {
                // Output the fetched data
                echo "<div class='reservation-info'>";
                echo "<p>Reservation Code: " . $reservation['reservation_code'] . "</p>";
                echo "<p>Email: " . $reservation['email'] . "</p>";
                echo "<p>From Date: " . $reservation['rental_start_date'] . "</p>";
                echo "<p>To Date: " . $reservation['rental_end_date'] . "</p>";
                // echo "<p>Car Model: " . $reservation['car_model'] . "</p>";
                echo "<p>Total Price: $" . $reservation['total_cost'] . "</p>";
                
                // Determine car rental status
                $currentDate = date('Y-m-d');
                if ($currentDate < $reservation['rental_start_date']) {
                    echo "<p>Status: To Be Delivered</p>";
                } elseif ($currentDate >= $reservation['rental_start_date'] && $currentDate <= $reservation['rental_end_date']) {
                    echo "<p>Status: Delivered!</p>";
                } else {
                    echo "<p>Status: Enjoyed and Returned!</p>";
                }

                // Offer option to rate the vehicle
                echo "<div class='rate-vehicle'>";
                echo "<p>Rate Vehicle:</p>";
                echo "<div class='stars'>";
                for ($i = 1; $i <= 5; $i++) {
                    echo "<span class='star' data-rating='$i'>&#9733;</span>";
                }
                echo "</div>";
                echo "</div>";

                echo "</div>";
            } else {
                echo "<p>No reservation found for the provided reservation code and email.</p>";
            }
        } catch (PDOException $e) {
            // Handle query execution error
            die("Query failed: " . $e->getMessage());
        }
    } else {
        echo "<p>Reservation code and email are required.</p>";
    }
    ?>
    <div class="go-home-btn">
        <button onclick="goHome()">Go Home</button>
    </div>
</div>

<script>
    function goHome() {
        // Redirect to the home page
        window.location.href = "home.html";
    }

    const stars = document.querySelectorAll('.star');

    function rateVehicle() {
        const rating = this.getAttribute('data-rating');
        alert('You rated the vehicle ' + rating + ' stars.');
        // Send the rating to the server or perform any other action
    }

    stars.forEach(star => star.addEventListener('click', rateVehicle));
</script>

</body>
</html>
