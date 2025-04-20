<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php'; // Ensure the database connection file is included
?>
<?php
include 'db.php';


// Fetch medicines from the database
$sql = "SELECT * FROM MedicineInformation";
$result = $conn->query($sql);

if (!$result) {
    die("Error executing query: " . $conn->error);
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine List</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <h1>Medicine List</h1> <!-- This is the heading -->
<?php
// Display medicines in an HTML table
if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr>
            <th>Medicine ID</th>
            <th>Medicine Name</th>
            <th>Category</th>
            <th>Formulation</th>
             <th>Dosage</th>
            <th>Retail Price</th>
          
            <th>Expiration Date</th>
            <th>Manufacture Date</th>
          </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['MedicineID'] ?? '') . "</td>
                <td>" . htmlspecialchars($row['MedicineName'] ?? '') . "</td>
                <td>" . htmlspecialchars($row['Category'] ?? '') . "</td>
                 <td>" . htmlspecialchars($row['Formulation'] ?? '') . "</td>
                  <td>" . htmlspecialchars($row['Dosage'] ?? '') . "</td>
                <td>" . htmlspecialchars($row['RetailPrice'] ?? '') . "</td>
               
                <td>" . htmlspecialchars($row['ExpirationDate'] ?? '') . "</td>
                <td>" . htmlspecialchars($row['ManufactureDate'] ?? '') . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No medicines found in the database.</p>";
}

// Close the database connection
$conn->close();
?>
