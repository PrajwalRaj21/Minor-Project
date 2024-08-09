<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection parameters
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

    // Get form data
    $model = $_POST['model'];
    $company = $_POST['company'];
    $year = $_POST['year'];
    $mileage = $_POST['mileage'];
    $rentalPrice = $_POST['rental_price']; // Adjusted column name
    $rating = $_POST['rating'];
    $fuelType = $_POST['fuel_type'];
    $vehicleType = $_POST['vehicle_type'];

    // Image handling
    $targetDir = "images/";
    $targetFile = $targetDir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            exit();
        }

        // Move uploaded file to destination directory
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            // Insert car data into database
            $sql = "INSERT INTO cars (Model, Company, Year, Mileage, Rental_Price, Rating, FuelType, VehicleType, Image) 
                    VALUES ('$model', '$company', $year, $mileage, $rentalPrice, $rating, '$fuelType', '$vehicleType', '$targetFile')";
            
            if ($conn->query($sql) === TRUE) {
                echo "Car added successfully.";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "File is not an image.";
    }

    // Close database connection
    $conn->close();
}
?>
