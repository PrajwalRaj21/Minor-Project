<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Car</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f5f5f5;
}

.container {
    max-width: 500px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

input[type="text"],
input[type="number"],
input[type="file"] {
    width: calc(100% - 20px);
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

input[type="file"] {
    padding-top: 14px;
}

button[type="submit"] {
    width: calc(100% - 20px);
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 4px;
    padding: 12px 0;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button[type="submit"]:hover {
    background-color: #0056b3;
}

        
        

    </style>
</head>
<body>

<h2>Add Car</h2>

<form action="add_car_handler.php" method="post" enctype="multipart/form-data">
    <label for="model">Model:</label>
    <input type="text" id="model" name="model" required><br><br>

    <label for="company">Company:</label>
    <input type="text" id="company" name="company" required><br><br>

    <label for="year">Year:</label>
    <input type="number" id="year" name="year" required><br><br>

    <label for="mileage">Mileage:</label>
    <input type="number" id="mileage" name="mileage" required><br><br>

    <label for="rental_price">Rental Price:</label>
    <input type="number" id="rental_price" name="rental_price" required><br><br>

    <label for="rating">Rating:</label>
    <input type="number" id="rating" name="rating" step="0.1" min="0" max="5" required><br><br>

    <label for="fuel_type">Fuel Type:</label>
    <input type="text" id="fuel_type" name="fuel_type" required><br><br>

    <label for="vehicle_type">Vehicle Type:</label>
    <input type="text" id="vehicle_type" name="vehicle_type" required><br><br>

    <label for="image">Image:</label>
    <input type="file" id="image" name="image" accept="image/*" required><br><br>

    <input type="submit" value="Add Car">
</form>

</body>
</html>
