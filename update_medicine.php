<?php
include 'db.php';

$medicine = null;
$message = '';

// Check if ID is provided and numeric
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sanitize and retrieve POST data
        $name = $conn->real_escape_string($_POST['name']);
        $category = $conn->real_escape_string($_POST['category']);
        $price = $conn->real_escape_string($_POST['price']);
        $dosage = $conn->real_escape_string($_POST['dosage']);
        $expirationDate = $conn->real_escape_string($_POST['expiration_date']);
        $manufactureDate = $conn->real_escape_string($_POST['manufacture_date']);

        // Update query
        $sql = "UPDATE MedicineInformation 
                SET MedicineName = '$name',
                    Category = '$category',
                    RetailPrice = '$price',
                    Dosage = '$dosage',
                    ExpirationDate = '$expirationDate',
                    ManufactureDate = '$manufactureDate'
                WHERE MedicineID = $id";

        if ($conn->query($sql) === TRUE) {
            $message = "Medicine updated successfully!";
        } else {
            $message = "Error updating record: " . $conn->error;
        }
    }

    // Fetch the record to pre-fill the form
    $sql = "SELECT * FROM MedicineInformation WHERE MedicineID = $id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $medicine = $result->fetch_assoc();
    }
}

// Fetch the whole table
$sql = "SELECT * FROM MedicineInformation";
$result = $conn->query($sql);

$medicines = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $medicines[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine Management</title>
</head>
<body>
    <h1>Medicine Management</h1>

    <!-- Message -->
    <?php if (!empty($message)) echo "<p>$message</p>"; ?>

    <!-- Display Medicine Table -->
    <h2>All Medicines</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Medicine ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Dosage</th>
                <th>Expiration Date</th>
                <th>Manufacture Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($medicines as $med): ?>
                <tr>
                    <td><?php echo htmlspecialchars($med['MedicineID']); ?></td>
                    <td><?php echo htmlspecialchars($med['MedicineName']); ?></td>
                    <td><?php echo htmlspecialchars($med['Category']); ?></td>
                    <td><?php echo htmlspecialchars($med['RetailPrice']); ?></td>
                    <td><?php echo htmlspecialchars($med['Dosage']); ?></td>
                    <td><?php echo htmlspecialchars($med['ExpirationDate']); ?></td>
                    <td><?php echo htmlspecialchars($med['ManufactureDate']); ?></td>
                    <td>
                        <a href="?id=<?php echo $med['MedicineID']; ?>">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Edit Form -->
    <?php if ($medicine): ?>
        <h2>Edit Medicine</h2>
        <form method="POST" action="?id=<?php echo $medicine['MedicineID']; ?>">
            <label for="name">Medicine Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($medicine['MedicineName']); ?>" required><br>

            <label for="category">Category:</label>
            <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($medicine['Category']); ?>" required><br>

            <label for="price">Retail Price:</label>
            <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($medicine['RetailPrice']); ?>" step="0.01" required><br>

            <label for="dosage">Dosage:</label>
            <input type="text" id="dosage" name="dosage" value="<?php echo htmlspecialchars($medicine['Dosage']); ?>" required><br>

            <label for="expiration_date">Expiration Date:</label>
            <input type="date" id="expiration_date" name="expiration_date" value="<?php echo htmlspecialchars($medicine['ExpirationDate']); ?>" required><br>

            <label for="manufacture_date">Manufacture Date:</label>
            <input type="date" id="manufacture_date" name="manufacture_date" value="<?php echo htmlspecialchars($medicine['ManufactureDate']); ?>" required><br>

            <button type="submit">Update Medicine</button>
        </form>
    <?php endif; ?>
</body>
</html>
