<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "car_rental");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the search term
$search = $_GET['search'];

// Prepare a query to search for cars by multiple fields
$sql = "SELECT * FROM cars WHERE 
        Model LIKE '%$search%' OR 
        Company LIKE '%$search%' OR 
        Year LIKE '%$search%' OR 
        FuelType LIKE '%$search%' OR 
        VehicleType LIKE '%$search%'
        ORDER BY Model ASC";

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

// Return search results as JSON
header('Content-Type: application/json');
echo json_encode($carList);
?>
