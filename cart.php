<?php
session_start();

// Include database connection
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit;
}

// Handle adding items to the cart
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $medicineID = $_POST['medicineID'];
    $medicineName = $_POST['medicineName'];
    $category = $_POST['category'];
    $retailPrice = $_POST['retailPrice'];

    // Initialize cart if it's not set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the item is already in the cart
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['medicineID'] === $medicineID) {
            $item['quantity'] += 1; // Increment quantity if already in cart
            $found = true;
            break;
        }
    }

    // If not found, add the new item to the cart
    if (!$found) {
        $_SESSION['cart'][] = [
            'medicineID' => $medicineID,
            'medicineName' => $medicineName,
            'category' => $category,
            'retailPrice' => $retailPrice,
            'quantity' => 1
        ];
    }

    // Redirect to the cart page to show updated cart
    header('Location: cart.php');
    exit();
}

// Handle removing items from the cart
if (isset($_GET['remove'])) {
    $medicineID = $_GET['remove'];
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['medicineID'] == $medicineID) {
            unset($_SESSION['cart'][$key]); // Remove the item from the cart
            break;
        }
    }
    // Re-index the array after removal
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    header('Location: cart.php'); // Refresh the page after removal
    exit();
}

// Handle checkout process
if (isset($_GET['checkout']) && !empty($_SESSION['cart'])) {
    // Get the user ID from the session
    $customerID = $_SESSION['user_id'];

    // Loop through the cart and insert transaction records
    foreach ($_SESSION['cart'] as $item) {
        $medicineName = $item['medicineName'];
        $retailPrice = $item['retailPrice'];
        $quantity = $item['quantity'];
        $totalCharge = $retailPrice * $quantity;

        // Prepare SQL statement to insert transaction into the database
        $sql = "INSERT INTO Transactions (CustomerID, MedicineName, PurchaseDate, TotalCharge, UnitsSold)
                VALUES (?, ?, NOW(), ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isdi", $customerID, $medicineName, $totalCharge, $quantity);
        $stmt->execute();
    }

    // Clear the cart after successful transaction
    unset($_SESSION['cart']);
    header('Location: profile.php'); // Redirect to a page that shows the user's transaction history
    exit();
}

// Display Cart HTML
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <style>
         body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        h1 {
            color: #333;
            text-align: center;
        }

        table {
            width: 80%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
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

        td a {
            color: #e74c3c;
            text-decoration: none;
        }

        td a:hover {
            text-decoration: underline;
        }

        .total {
            font-size: 18px;
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
        }

        .footer button {
            padding: 12px 24px;
            background-color: #28a745;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .footer button:hover {
            background-color: #218838;
        }
        
    </style>
    
</head>
<body>

    <h1>Your Cart</h1>

    <?php
    if (!empty($_SESSION['cart'])) {
        echo "<table>
                <tr>
                    <th>Medicine Name</th>
                    <th>Category</th>
                    <th>Retail Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>";

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
                    <td><a href='cart.php?remove={$item['medicineID']}'>Remove</a></td>
                  </tr>";
        }

        echo "<tr><td colspan='4' class='total'>Total</td><td class='total'>{$total}</td><td></td></tr>";
        echo "</table>";
    } else {
        echo "<p>Your cart is empty.</p>";
    }
    ?>

    <div class="footer">
    <button onclick="window.location.href='fetch.php'">Continue Shopping</button>
    <?php if (!empty($_SESSION['cart'])): ?>
        <button onclick="window.location.href='cart.php?checkout=true'">Checkout</button>
    <?php endif; ?>
    </div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
