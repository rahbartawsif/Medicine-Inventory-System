<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include fetch_medicines.php if it exists
if (!file_exists('fetch_medicines.php')) {
    die('<p>Error: fetch_medicines.php file not found. Please ensure it is in the correct location.</p>');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Medicine Inventory</title>
</head>
<body>
    <h1>Medicine Inventory System</h1>

    <!-- Navigation Links -->
    <a href="add_medicine.php">Add New Medicine</a>
    <a href="update_medicine.php">Update Medicine</a>
    <a href="delete_medicine.php">Delete Medicine</a>
    <a href="stock.php"> Stock</a>
    <a href="transactions.php">   Transactions</a>

    <h2>Medicine List</h2>

    <?php include 'fetch_medicines.php'; ?>

</html>
