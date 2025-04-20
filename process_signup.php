<?php
// Database connection details
$host = "localhost";
$dbname = "medicine_inventory";
$username = "root";
$password = "";

try {
    // Establish a connection to the database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customerName = $_POST['customerName'];
    $contactInfo = $_POST['contactInfo'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate input fields
    if (empty($customerName) || empty($contactInfo) || empty($email) || empty($password)) {
        echo "All fields are required.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
    } else {
        try {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Insert data into the Customer table
            $sql = "INSERT INTO Customer (CustomerName, ContactInfo, Email, Password) VALUES (:customerName, :contactInfo, :email, :password)";
            $stmt = $conn->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':customerName', $customerName);
            $stmt->bindParam(':contactInfo', $contactInfo);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);

            // Execute the query
            $stmt->execute();
            echo "Customer added successfully!";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - Medicine Inventory</title>
</head>
<body>
    <h1>Signup - Medicine Inventory</h1>
    <form method="POST" action="">
        <label for="customerName">Customer Name:</label><br>
        <input type="text" id="customerName" name="customerName" required><br><br>

        <label for="contactInfo">Contact Info:</label><br>
        <input type="text" id="contactInfo" name="contactInfo" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Signup</button>
    </form>
</body>
</html>
