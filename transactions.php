<?php
// Include database connection
include 'db.php';

// Fetch transaction data
$sql = "SELECT * FROM Transactions";
$result = $conn->query($sql);

if ($result === false) {
    die("Error fetching transaction data: " . $conn->error);
}
?>

!<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine List</title>
    <style>
        table {
            width: 90%;
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

<h1>Transactions</h1>

<table>
    <tr>
        <th>Transaction ID</th>
        <th>Customer ID</th>
        <th>Medicine Name</th>
        <th>Purchase Date</th>
        <th>Total Charge</th>
        <th>Units Sold</th>
    </tr>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['TransactionID']); ?></td>
                <td><?php echo htmlspecialchars($row['CustomerID']); ?></td>
                <td><?php echo htmlspecialchars($row['MedicineName']); ?></td>
                <td><?php echo htmlspecialchars($row['PurchaseDate']); ?></td>
                <td><?php echo htmlspecialchars($row['TotalCharge']); ?></td>
                <td><?php echo htmlspecialchars($row['UnitsSold']); ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="6">No transactions found.</td>
        </tr>
    <?php endif; ?>
</table>

<?php
// Close the database connection
$conn->close();
?>

</body>
</html>
