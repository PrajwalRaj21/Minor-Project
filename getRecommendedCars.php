<?php
// Your database connection details
$servername = "localhost";
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "car_rental"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch 5 random cars from the database
$sql = "SELECT Model, Company, Rating, Image, Rental_Price, FuelType FROM cars ORDER BY RAND() LIMIT 5";
$result = $conn->query($sql);

$cars = array();

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        // Ensure that the "car_images" directory path is appended only once
        // Check if the image URL already contains "car_images/"
        if(strpos($row['Image'], "car_images/") === false) {
            $row['Image'] = "car_images/" . $row['Image'];
        }
        $cars[] = $row;
    }
} else {
    echo "0 results";
}

// Close connection
$conn->close();

// Output the JSON data
header('Content-Type: application/json');
echo json_encode($cars);
?>
