<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        .goback-btn {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            transition-duration: 0.4s;
        }

        .goback-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>User List</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Date of Birth</th>
                    <th>Driver's License Number</th>
                    <th>Rental Start Date</th>
                    <th>Rental End Date</th>
                    <th>Payment Method</th>
                    <th>Reservation Code</th>
                    <th>Total Cost</th>
                </tr>
            </thead>
            <tbody id="userList">
                <!-- User data will be populated here dynamically -->
            </tbody>
        </table>

        <!-- Go Back Button -->
        <button class="goback-btn" onclick="goBack()">Go Back</button>
    </div>

    <!-- Script to fetch and populate user data -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to fetch user list from server
            function fetchUserList() {
                fetch('get_user_list.php')
                    .then(response => response.json())
                    .then(data => {
                        const userList = document.getElementById('userList');
                        userList.innerHTML = ''; // Clear previous user data
                        data.forEach(user => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td>${user.id}</td>
                                <td>${user.name}</td>
                                <td>${user.email}</td>
                                <td>${user.phone}</td>
                                <td>${user.dob}</td>
                                <td>${user.dl_number}</td>
                                <td>${user.rental_start_date}</td>
                                <td>${user.rental_end_date}</td>
                                <td>${user.payment_method}</td>
                                <td>${user.reservation_code}</td>
                                <td>${user.total_cost}</td>
                            `;
                            userList.appendChild(tr);
                        });
                    })
                    .catch(error => console.error('Error fetching user list:', error));
            }

            // Fetch user list on page load
            fetchUserList();
        });

        // Go Back function
        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>
