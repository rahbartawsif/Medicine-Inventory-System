<?php
// Start the session to use cart functionality
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine Inventory</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            text-align: center;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        table th, table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }
        table th {
            background-color: #007bff;
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        table tr:hover {
            background-color: #f1f1f1;
        }
        .footer {
            text-align: center;
            margin: 20px 0;
            color: #555;
        }
        .controls {
            text-align: center;
            margin: 20px;
        }
        .controls input {
            padding: 10px;
            font-size: 16px;
            width: 300px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

    <header>
        <h1>Medicine Inventory</h1>
    </header>

    <!-- Search Bar -->
    <div class="controls">
        <input type="text" id="searchBar" placeholder="Search for medicines or categories..." onkeyup="filterTable()">
    </div>

   
    <?php
    // Include database connection
    include 'db.php'; // Make sure 'db.php' contains the correct database connection details

    // Fetch medicines from the database
    $sql = "SELECT * FROM MedicineInformation"; // Replace 'MedicineInformation' with your actual table name
    $result = $conn->query($sql); // Execute the query

    // Check if the query was successful
    if (!$result) {
        die("Error executing query: " . $conn->error); // Stop execution if query fails and show error
    }

    // Check if there are any rows in the result set
    if ($result->num_rows > 0) {
        // Start the table structure
        echo "<table id='inventoryTable'>";
        echo "<tr>
                <th>Medicine ID</th>
                <th>Medicine Name</th>
                <th>Category</th>
                <th>Retail Price</th>
                <th>Dosage</th>
                <th>Expiration Date</th>
                <th>Manufacture Date</th>
                <th>Action</th>
              </tr>";

        // Loop through each row and display data in the table
        while ($row = $result->fetch_assoc()) {
            // Ensure no null values are passed to htmlspecialchars
            echo "<tr>
                    <td>" . htmlspecialchars($row['MedicineID'] ?? 'N/A') . "</td>
                    <td>" . htmlspecialchars($row['MedicineName'] ?? 'N/A') . "</td>
                    <td>" . htmlspecialchars($row['Category'] ?? 'N/A') . "</td>
                    <td>" . htmlspecialchars($row['RetailPrice'] ?? 'N/A') . "</td>
                    <td>" . htmlspecialchars($row['Dosage'] ?? 'N/A') . "</td>
                    <td>" . htmlspecialchars($row['ExpirationDate'] ?? 'N/A') . "</td>
                    <td>" . htmlspecialchars($row['ManufactureDate'] ?? 'N/A') . "</td>
                    <td>
                        <form action='cart.php' method='POST'>
                            <input type='hidden' name='medicineID' value='" . htmlspecialchars($row['MedicineID']) . "'>
                            <input type='hidden' name='medicineName' value='" . htmlspecialchars($row['MedicineName']) . "'>
                            <input type='hidden' name='category' value='" . htmlspecialchars($row['Category']) . "'>
                            <input type='hidden' name='retailPrice' value='" . htmlspecialchars($row['RetailPrice']) . "'>
                            <button type='submit'>Add to Cart</button>
                        </form>
                    </td>
                  </tr>";
        }

        // Close the table after data is printed
        echo "</table>";
    } else {
        // If no data is found in the database
        echo "<p>No medicines found in the database.</p>";
    }

    // Close the database connection
    $conn->close();
    ?>

    <!-- Footer Section -->
    <div class="footer">
        <button onclick="window.location.href='cart.php'">View Cart</button>
    </div>

    <script>
        function filterTable() {
            const searchBar = document.getElementById('searchBar');
            const filter = searchBar.value.toLowerCase();
            const table = document.getElementById('inventoryTable');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName('td');
                let match = false;

                for (let j = 0; j < cells.length; j++) {
                    if (cells[j]) {
                        const textValue = cells[j].textContent || cells[j].innerText;
                        if (textValue.toLowerCase().indexOf(filter) > -1) {
                            match = true;
                            break;
                        }
                    }
                }

                rows[i].style.display = match ? '' : 'none';
            }
        }
    </script>

</body>
</html>
