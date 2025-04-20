<?php
session_start();
include 'db.php'; // Include your database connection file

// Fetch dashboard data
$totalSalesToday = 0;
$totalTransactionsToday = 0;
$expiredProducts = [];
$totalStock = 0; // Variable for total stock

// Query for total sales and transactions based on sales table structure
$dateToday = date('Y-m-d');
$querySales = "SELECT SUM(TotalCharge) AS total_sales, COUNT(*) AS total_transactions FROM transactions WHERE DATE(PurchaseDate) = '$dateToday'";
$resultSales = $conn->query($querySales);

if ($resultSales && $row = $resultSales->fetch_assoc()) {
    $totalSalesToday = $row['total_sales'] ?? 0;
    $totalTransactionsToday = $row['total_transactions'] ?? 0;
}

// Query for expired products
$queryExpired = "SELECT MedicineName, dosage FROM medicineinformation WHERE ExpirationDate < NOW()";
$resultExpired = $conn->query($queryExpired);

if ($resultExpired) {
    while ($row = $resultExpired->fetch_assoc()) {
        $expiredProducts[] = $row;
    }
}

// Query for total stock quantity
$queryStock = "SELECT SUM(StockQuantity) AS totalstock FROM medicineinformation";
$resultStock = $conn->query($queryStock);

if ($resultStock && $row = $resultStock->fetch_assoc()) {
    $totalStock = $row['totalstock'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy Sales and Inventory System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
        }
        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            background-color: #343a40;
            color: white;
            padding: 15px;
            transition: width 0.3s;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            font-size: 16px;
        }
        .sidebar a i {
            margin-right: 10px;
        }
        .sidebar a:hover {
            background-color: #495057;
            padding-left: 10px;
            border-radius: 5px;
        }
        .main-content {
            margin-left: 260px;
            padding: 20px;
        }
        .navbar {
            background-color: #007bff;
            color: white;
            padding: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar span {
            font-size: 18px;
            font-weight: bold;
        }
        .card {
            margin-bottom: 20px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-title {
            font-size: 1.3em;
            font-weight: bold;
        }
        .card p {
            font-size: 1.5em;
            margin: 0;
        }
        .alert {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-radius: 8px;
        }
        h3 {
            margin-bottom: 20px;
            font-size: 24px;
        }
        h4 {
            margin-top: 40px;
            font-size: 20px;
        }
        .card-body {
    background-color: #f8f9fa; /* Light gray background */
    color: #333; /* Dark text color for better readability */
}

    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <h4>Pharmacy System</h4>
            <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
            <a href="fetch_medicines.php"><i class="fas fa-pills"></i> Medicine List</a>
            <a href="index.php"><i class="fas fa-box"></i> Inventory</a>
            <a href="transactions.php"><i class="fas fa-exchange-alt"></i> Transactions</a>
            <a href="#"><i class="fas fa-list"></i> Medicine Category</a>
            <a href="stock.php"><i class="fas fa-capsules"></i> Stock</a>
            <a href="update_medicine.php"><i class="fas fa-edit"></i> Update Medicine List</a>
            <a href="#"><i class="fas fa-exclamation-triangle"></i> Expired List</a>
            <a href="supplier.php"><i class="fas fa-truck"></i> Supplier List</a>
            <a href="admin_logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Navbar -->
            <div class="navbar">
                <span>Sales and Inventory System</span>
                <span>Administrator</span>
            </div>

            <h3>Welcome back, Administrator!</h3>

            <div class="row">
                <div class="col-md-6">
                    <div class="card text-white bg-gradient-primary">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-dollar-sign"></i> Total Sales Today</h5>
                            <p class="card-text"><?= number_format($totalSalesToday, 2) ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card text-white bg-gradient-success">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-receipt"></i> Total Transactions Today</h5>
                            <p class="card-text"><?= $totalTransactionsToday ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card text-white bg-gradient-info">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-box"></i> Total Stock</h5>
                            <p class="card-text"><?= $totalStock ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <h4>Expired Products</h4>
            <?php if (!empty($expiredProducts)): ?>
                <?php foreach ($expiredProducts as $product): ?>
                    <div class="alert alert-danger">
                        <span><i class="fas fa-times-circle"></i> <?= $product['MedicineName'] ?> <?= $product['dosage'] ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">No expired products at the moment.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
