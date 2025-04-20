<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html"); // Redirect to login page if not logged in
    exit;
}

include 'db.php'; // Include your database connection

// Fetch user details from the database using the session user ID
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM Customer WHERE CustomerID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Store user details in session variables for easy access
    $_SESSION['user_name'] = $user['CustomerName'];
    $_SESSION['user_email'] = $user['Email'];
    $_SESSION['user_contact'] = $user['ContactInfo'];
} else {
    echo "User not found!";
    exit;
}

// Fetch transaction details for the user
$transaction_query = "SELECT * FROM transactions WHERE customerID = ?";
$transaction_stmt = $conn->prepare($transaction_query);
$transaction_stmt->bind_param("i", $user_id);
$transaction_stmt->execute();
$transaction_result = $transaction_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 24px;
            position: relative;
        }
        header a {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
        }
        header a:hover {
            background-color: #0056b3;
        }
        .profile-container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .profile-container h1 {
            text-align: center;
        }
        .profile-container p {
            font-size: 18px;
            margin: 10px 0;
        }
        .logout-btn {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            text-align: center;
            border-radius: 5px;
            width: 150px;
        }
        .logout-btn:hover {
            background-color: #0056b3;
        }
        .transaction-table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .transaction-table th, .transaction-table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .transaction-table th {
            background-color: #007bff;
            color: white;
        }
        .transaction-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .transaction-table tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <header>
        My Profile
        <a href="fetch.php">  Inventory</a> <!-- Inventory link at the top right -->
        <a href="cart.php" style="right: 100px;">   Cart</a>
    </header>
    
    <div class="profile-container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
        <p><strong>Contact Info:</strong> <?php echo htmlspecialchars($_SESSION['user_contact']); ?></p>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <!-- Transaction Table -->
    <table class="transaction-table">
        <thead>
            <tr>
                <th>Transaction ID</th>
                <th>Medicine Name</th>
                <th>Purchase Date</th>
                <th>Total Charge</th>
                <th>Units Sold</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($transaction_result->num_rows > 0): ?>
                <?php while ($transaction = $transaction_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($transaction['TransactionID']); ?></td>
                        <td><?php echo htmlspecialchars($transaction['MedicineName']); ?></td>
                        <td><?php echo htmlspecialchars($transaction['PurchaseDate']); ?></td>
                        <td><?php echo htmlspecialchars($transaction['TotalCharge']); ?></td>
                        <td><?php echo htmlspecialchars($transaction['UnitsSold']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No transactions found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
