<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car List</title>
    <style>
        /* CSS styles for search functionality */
        #searchInput {
            width: 300px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        #searchButton {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #searchResult {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            z-index: 1;
        }

        #searchResult li {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #ddd;
        }

        #searchResult li:last-child {
            border-bottom: none;
        }

        #searchResult li:hover {
            background-color: #ddd;
        }

        /* Styling for car cards */
        .card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 250px;
            background-color: #fff;
        }

        .card img {
            width: 100%;
            border-radius: 5px 5px 0 0;
        }

        .card-content {
            padding: 10px;
            text-align: center;
        }

        /* Styling for buttons */
        .btn {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .remove-btn {
            background-color: #dc3545;
        }

        .remove-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <input type="text" id="searchInput" placeholder="Search...">
    <button id="searchButton">Search</button>
    <ul id="searchResult"></ul>

    <h2 style="text-align: center;">Car List</h2>

    <!-- Add New Vehicle Button -->
    <button id="addButton" class="btn">Add New Vehicle</button>

    <!-- Car Cards -->
    <div id="carCards" style="display: flex; flex-wrap: wrap; justify-content: center;"></div>

    <script>
        // Function to remove car from the list
        function removeCar(id) {
            // Assuming there's a function in the server to remove a car by its ID
            fetch(`remove_car.php?id=${id}`, { method: 'DELETE' })
                .then(response => response.json())
                .then(data => {
                    // Assuming the server returns success message if car is removed successfully
                    if (data.success) {
                        // Remove the card from the UI
                        const cardToRemove = document.querySelector(`.card[data-id="${id}"]`);
                        cardToRemove.remove();
                    } else {
                        console.error('Error removing car:', data.error);
                    }
                })
                .catch(error => console.error('Error removing car:', error));
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Function to fetch car list from server
            function fetchCarList() {
                fetch('get_car_list.php')
                    .then(response => response.json())
                    .then(data => {
                        const carCards = document.getElementById('carCards');
                        carCards.innerHTML = ''; // Clear previous car data
                        data.forEach(car => {
                            const card = createCarCard(car);
                            carCards.appendChild(card);
                        });
                    })
                    .catch(error => console.error('Error fetching car list:', error));
            }

            // Function to create a card for a car
            function createCarCard(car) {
                const card = document.createElement('div');
                card.classList.add('card');
                card.dataset.id = car.id; // Set data-id attribute for identifying the card
                card.innerHTML = `
                    <img src="${car.Image}" alt="${car.Model}">
                    <div class="card-content">
                        <h3>${car.Model}</h3>
                        <p><strong>Company:</strong> ${car.Company}</p>
                        <p><strong>Year:</strong> ${car.Year}</p>
                        <p><strong>Mileage:</strong> ${car.Mileage}</p>
                        <p><strong>Rental Price:</strong> ${car.Rental_Price}</p>
                        <p><strong>Rating:</strong> ${car.Rating}</p>
                        <p><strong>Fuel Type:</strong> ${car.FuelType}</p>
                        <p><strong>Vehicle Type:</strong> ${car.VehicleType}</p>
                        <button class="btn remove-btn" onclick="removeCar(${car.id})">Remove Vehicle</button>
                    </div>
                `;
                return card;
            }

            // Fetch car list on page load
            fetchCarList();

            // Search functionality
            const searchInput = document.getElementById('searchInput');

            function searchCars() {
                const searchValue = searchInput.value.trim().toLowerCase();
                const carCards = document.querySelectorAll('.card');
                carCards.forEach(card => {
                    const model = card.querySelector('h3').textContent.toLowerCase();
                    if (model.includes(searchValue)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }

            searchInput.addEventListener('input', searchCars);

            // Redirect to add_car.php when Add New Vehicle button is clicked
            const addButton = document.getElementById('addButton');
            addButton.addEventListener('click', function() {
                window.location.href = 'add_car.php';
            });
        });
    </script>
</body>
</html>
