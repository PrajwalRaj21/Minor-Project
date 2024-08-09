<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "car_rental");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch all cars from the database
$sql = "SELECT * FROM cars ORDER BY Model ASC";
$result = mysqli_query($conn, $sql);

$carList = array();
if (mysqli_num_rows($result) > 0) {
    // Output data of each row
    while ($row = mysqli_fetch_assoc($result)) {
        $carList[] = $row;
    }
}

// Close connection
mysqli_close($conn);

// Return car list as JSON
header('Content-Type: application/json');
echo json_encode($carList);
?>
