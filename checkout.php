<?php
session_start();
include 'db.php'; // Include the database connection file

// Check if the cart is empty
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty. <a href='fetch.php'>Go back to shopping</a></p>";
    exit();
}

// Handle checkout submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customerID = 1; // Replace with actual dynamic customer ID logic if applicable

    // Loop through cart items and insert them into the Transactions table
    foreach ($_SESSION['cart'] as $item) {
        $medicineName = $item['medicineName'];
        $category = $item['category'];
        $retailPrice = $item['retailPrice'];
        $quantity = $item['quantity'];
        $totalCharge = $retailPrice * $quantity;
        $purchaseDate = date('Y-m-d H:i:s');

        // Insert the transaction into the database
        $stmt = $conn->prepare("INSERT INTO Transactions (CustomerID, MedicineName, PurchaseDate, TotalCharge, UnitsSold) VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("issdi", $customerID, $medicineName, $purchaseDate, $totalCharge, $quantity);
            if (!$stmt->execute()) {
                die("Error executing query: " . $stmt->error);
            }
            $stmt->close();
        } else {
            die("Error preparing statement: " . $conn->error);
        }
    }

    // Clear the cart after successful checkout
    unset($_SESSION['cart']);

    // Redirect to the transactions page or display a success message
    echo "<p>Thank you for your order! Your purchase has been completed.</p>";
    echo "<p><a href='transactions.php'>View Transactions</a></p>";
    echo "<p><a href='fetch.php'>Continue Shopping</a></p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
        }
        .checkout-btn {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .checkout-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<h1>Checkout</h1>

<table>
    <tr>
        <th>Medicine Name</th>
        <th>Category</th>
        <th>Retail Price</th>
        <th>Quantity</th>
        <th>Total</th>
    </tr>
    <?php
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $itemTotal = $item['retailPrice'] * $item['quantity'];
        $total += $itemTotal;

        echo "<tr>
                <td>{$item['medicineName']}</td>
                <td>{$item['category']}</td>
                <td>{$item['retailPrice']}</td>
                <td>{$item['quantity']}</td>
                <td>{$itemTotal}</td>
              </tr>";
    }
    ?>
    <tr>
        <td colspan="4" class="total">Grand Total</td>
        <td class="total"><?php echo $total; ?></td>
    </tr>
</table>

<form method="POST" action="">
    <button type="submit" class="checkout-btn">Confirm and Checkout</button>
</form>

</body>
</html>
