<?php
// Include database connection
include 'db.php';

// Handle editing supplier information
if (isset($_POST['edit_supplier'])) {
    $supplierID = $_POST['SupplierID'];
    $companyName = $_POST['CompanyName'];
    $contactNumber = $_POST['Contact'];
    $email = $_POST['Email'];
    $paymentTerms = $_POST['PaymentTerms'];
    $bankDetails = $_POST['BankDetails'];

    // Update query
    $updateSql = "UPDATE Supplier SET CompanyName=?, Contact=?, Email=?, PaymentTerms=?, BankDetails=? WHERE SupplierID=?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("sssssi", $companyName, $contactNumber, $email, $paymentTerms, $bankDetails, $supplierID);

    if ($stmt->execute()) {
        echo "<p>Supplier information updated successfully!</p>";
    } else {
        echo "<p>Error updating supplier: " . $stmt->error . "</p>";
    }
}

// Handle adding a new supplier
if (isset($_POST['add_supplier'])) {
    $companyName = $_POST['CompanyName'];
    $contact = $_POST['Contact'];
    $email = $_POST['Email'];
    $paymentTerms = $_POST['PaymentTerms'];
    $bankDetails = $_POST['BankDetails'];

    // Insert query
    $insertSql = "INSERT INTO Supplier (CompanyName, Contact, Email, PaymentTerms, BankDetails) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertSql);
    $stmt->bind_param("sssss", $companyName, $contact, $email, $paymentTerms, $bankDetails);

    if ($stmt->execute()) {
        echo "<p>New supplier added successfully!</p>";
    } else {
        echo "<p>Error adding supplier: " . $stmt->error . "</p>";
    }
}

// Fetch supplier data
$sql = "SELECT * FROM Supplier";
$result = $conn->query($sql);
if ($result === false) {
    die("Error fetching supplier data: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Table</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        form {
            display: inline;
        }
    </style>
</head>
<body>

<h1>Supplier Table</h1>

<!-- Form to add a new supplier -->
<h2>Add New Supplier</h2>
<form method="POST" action="">
    <label for="CompanyName">Company Name:</label>
    <input type="text" id="CompanyName" name="CompanyName" required><br><br>

    <label for="Contact">Contact :</label>
    <input type="text" id="Contact" name="Contact" required><br><br>

    <label for="Email">Email:</label>
    <input type="email" id="Email" name="Email" required><br><br>

    <label for="PaymentTerms">Payment Terms:</label>
    <input type="text" id="PaymentTerms" name="PaymentTerms" required><br><br>

    <label for="BankDetails">Bank Details:</label>
    <input type="text" id="BankDetails" name="BankDetails" required><br><br>

    <button type="submit" name="add_supplier">Add Supplier</button>
</form>

<table>
    <tr>
        <th>Supplier ID</th>
        <th>Company Name</th>
        <th>Contact Number</th>
        <th>Email</th>
        <th>Payment Terms</th>
        <th>Bank Details</th>
        <th>Action</th>
    </tr>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <form method="POST" action="">
                    <td><?php echo htmlspecialchars($row['SupplierID']); ?></td>
                    <td><input type="text" name="CompanyName" value="<?php echo htmlspecialchars($row['CompanyName']); ?>" required></td>
                    <td><input type="text" name="Contact" value="<?php echo htmlspecialchars($row['Contact']); ?>" required></td>
                    <td><input type="email" name="Email" value="<?php echo htmlspecialchars($row['Email']); ?>" required></td>
                    <td><input type="text" name="PaymentTerms" value="<?php echo htmlspecialchars($row['PaymentTerms']); ?>" required></td>
                    <td><input type="text" name="BankDetails" value="<?php echo htmlspecialchars($row['BankDetails']); ?>" required></td>
                    <td>
                        <input type="hidden" name="SupplierID" value="<?php echo htmlspecialchars($row['SupplierID']); ?>">
                        <button type="submit" name="edit_supplier">Save</button>
                    </td>
                </form>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="7">No suppliers found.</td>
        </tr>
    <?php endif; ?>
</table>

<?php
// Close the database connection
$conn->close();
?>

</body>
</html>
