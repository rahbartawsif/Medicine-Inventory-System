<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $dosage = $_POST['dosage'];
    $expirationDate = $_POST['expiration_date'];
    $manufactureDate = $_POST['manufacture_date'];

    $sql = "INSERT INTO MedicineInformation (MedicineName, Category, RetailPrice, Dosage, ExpirationDate, ManufactureDate) VALUES ('$name', '$category', '$price', '$dosage', '$expirationDate', '$manufactureDate')";
    if ($conn->query($sql) === TRUE) {
        echo "New medicine added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<form method="POST" action="">
    <label for="name">Medicine Name:</label>
    <input type="text" id="name" name="name" required><br>

    <label for="category">Category:</label>
    <input type="text" id="category" name="category" required><br>

    <label for="price">Retail Price:</label>
    <input type="number" id="price" name="price" step="0.01" required><br>

    <label for="dosage">Dosage:</label>
    <input type="text" id="dosage" name="dosage" required><br>

    <label for="expiration_date">Expiration Date:</label>
    <input type="date" id="expiration_date" name="expiration_date" required><br>

    <label for="manufacture_date">Manufacture Date:</label>
    <input type="date" id="manufacture_date" name="manufacture_date" required><br>

    <button type="submit">Add Medicine</button>
</form>
