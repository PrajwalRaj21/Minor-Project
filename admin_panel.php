<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Custom CSS */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .container {
            display: flex;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .sidebar {
            width: 280px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 20px;
        }

        .sidebar h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar li {
            margin-bottom: 10px;
        }

        .sidebar li a {
            color: #333;
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .sidebar li a:hover {
            background-color: #eee;
        }

        .content {
            flex: 1;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 20px;
            margin-left: 20px;
        }

        .content h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .cards {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .card {
            width: 45%;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            display: flex;
        }

        .card .user-details {
            flex: 1;
            margin-right: 20px;
        }

        .card .user-details h2 {
            margin-bottom: 10px;
            color: #333;
        }

        .card .user-details p {
            margin: 5px 0;
            color: #666;
        }

        .card .car-image {
            flex: 1;
            text-align: center;
        }

        .card .car-image img {
            max-width: 100%;
            border-radius: 10px;
        }

        .no-data {
            text-align: center;
            color: #666;
        }

        /* Contact User Button */
        .contact-user-btn {
            font-size: 12px;
            padding: 5px 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        .contact-user-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Admin Dashboard</h2>
            <ul>
                <li><a href="carlist.php">Cars</a></li>
                <li><a href="userlist.php">Users</a></li>
                <li><a href="#" id="contactRequestsLink">Contact Requests</a></li>
            </ul>
        </div>
        <div class="content">
            <h2>Active Users</h2>
            <div class="cards">
                <?php
                // Connect to the database
                $conn = mysqli_connect("localhost", "root", "", "car_rental");

                // Check connection
                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                // Fetch users with rented cars from the database
                $sql = "SELECT * FROM users WHERE rental_start_date <= NOW() AND rental_end_date >= NOW()";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<div class='card'>";
                        echo "<div class='user-details'>";
                        echo "<h2>{$row['name']}</h2>";
                        echo "<p>Email: {$row['email']}</p>";
                        echo "<p>Phone: {$row['phone']}</p>";
                        echo "<p>Rental Start Date: {$row['rental_start_date']}</p>";
                        echo "<p>Rental End Date: {$row['rental_end_date']}</p>";
                        echo "</div>";
                        // Check if 'Image' key exists in the $row array
                        if (isset($row['Image'])) {
                            echo "<div class='car-image'>";
                            echo "<img src='{$row['Image']}' alt='Car Image'>";
                            echo "</div>";
                        }
                        echo "<button class='contact-user-btn'>Contact User</button>"; // Add contact user button
                        echo "</div>";
                    }
                } else {
                    echo "<p class='no-data'>No cars currently being used.</p>";
                }

                // Close connection
                mysqli_close($conn);
                ?>
            </div>
        </div>
    </div>
    <script>
    document.getElementById("contactRequestsLink").addEventListener("click", function(event) {
        event.preventDefault(); // Prevents the default action of following the href

        // Open sawaribhada@gmail.com's Gmail in a new tab
        window.open("https://mail.google.com");
    });

    document.querySelectorAll('.contact-user-btn').forEach(button => {
        button.addEventListener('click', function() {
            // Get the user's email from the card
            const userEmail = this.closest('.card').querySelector('p:nth-of-type(2)').textContent.split(' ')[1];

            // Open Gmail compose window with recipient's email pre-filled
            window.open(`https://mail.google.com/mail/u/0/#inbox?compose=new&to=${userEmail}`);
        });
    });
    </script>
</body>
</html>
