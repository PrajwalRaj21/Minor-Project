<?php
// Database configuration
$host = 'localhost'; // Change this to your database host if it's different
$dbname = 'car_rental'; // Change this to your actual database name
$username = 'root'; // Change this to your database username
$password = ''; // Change this to your database password

// Attempt to connect to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set charset to UTF8
    $pdo->exec("set names utf8");
} catch (PDOException $e) {
    // If connection fails, respond with an error message
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Connection failed: ' . $e->getMessage()]);
    exit(); // Exit the script if connection fails
}

// Check if the request method is DELETE
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Assuming you have sanitized the input and validated the ID
    $carId = $_GET['id'];

    try {
        // Perform the deletion in your database
        // Assuming you have a table named 'cars' with an 'id' column
        $query = "DELETE FROM cars WHERE CarID = ?";
        $statement = $pdo->prepare($query);
        $statement->execute([$carId]);

        // Respond with a success message
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        // If deletion fails, respond with an error message
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => 'Error removing car: ' . $e->getMessage()]);
    }
} else {
    // If the request method is not DELETE, respond with an error
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method not allowed']);
}
?>
