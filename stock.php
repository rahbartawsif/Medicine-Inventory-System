<?php
// Include database connection
include 'db.php';

// Fetch stock data from the stockData table
$sql = "SELECT * FROM stockData";
$result = $conn->query($sql);

if ($result === false) {
    die("Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine List</title>
    <style>
        table {
            width: 65%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-left: auto;
            margin-right: auto;
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

<h1>Stock Data</h1>

<table>
    <tr>
        <th>Medicine ID</th>
        <th>Medicine Name</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Supplier</th>
        <th>Date Added</th>
    </tr>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['MedicineID']); ?></td>
                
                <td><?php echo htmlspecialchars($row['MedicineName']); ?></td>

                <td><?php echo htmlspecialchars($row['StockQuantity']); ?></td>
                <td><?php echo htmlspecialchars($row['Price']); ?></td>
                <td><?php echo htmlspecialchars($row['Supplier']); ?></td>
                <td><?php echo htmlspecialchars($row['DateAdded']); ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="6">No stock data available</td>
        </tr>
    <?php endif; ?>
</table>

<?php
// Close the database connection
$conn->close();
?>

</body>
</html>
