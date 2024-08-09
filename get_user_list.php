<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "car_rental");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Prepare a query to fetch user data
$sql = "SELECT * FROM users";

// Execute the query
$result = mysqli_query($conn, $sql);

$userList = array();

// Check for errors in query execution
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Check if any rows were returned
if (mysqli_num_rows($result) > 0) {
    // Loop through each row and add it to the $userList array
    while ($row = mysqli_fetch_assoc($result)) {
        $userList[] = $row;
    }
}

// Close connection
mysqli_close($conn);

// Return user list as JSON
header('Content-Type: application/json');
echo json_encode($userList);
?>
