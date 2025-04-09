<?php
session_start();
include '../connect/connect.php';  // Include database connection

// Redirect if not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin/index.php");
    exit();
}

// Get database connection
$pdo = (new Connect())->getConnection();

// Query to get the total stock quantity
$stock_query = $pdo->query("SELECT SUM(QUANTITY) AS total_inventory FROM stock");
$stock_data = $stock_query->fetch(PDO::FETCH_ASSOC);
$total_inventory = $stock_data['total_inventory'] ?: 0;

// Query to get the total stockin quantity
$stockin_query = $pdo->query("SELECT SUM(QUANTITY) AS total_stockin FROM stockin");
$stockin_data = $stockin_query->fetch(PDO::FETCH_ASSOC);
$total_stockin = $stockin_data['total_stockin'] ?: 0;

// Query to get the total stockout quantity
$stockout_query = $pdo->query("SELECT SUM(QUANTITY) AS total_stockout FROM stockout");
$stockout_data = $stockout_query->fetch(PDO::FETCH_ASSOC);
$total_stockout = $stockout_data['total_stockout'] ?: 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS (Optional) -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 10px;
        }
        .sidebar {
            background-color: #343a40;
            height: 100vh;
            position: fixed;
            width: 220px;
            top: 0;
            left: 0;
            color: white;
        }
        .sidebar a {
            color: #ddd;
            text-decoration: none;
            padding: 10px;
            display: block;
            border-bottom: 1px solid #4e555b;
            font-size: 1.1rem;
        }
        .sidebar a:hover {
            background-color: #495057;
            color: white;
        }
        .content {
            margin-left: 240px;
            padding: 20px;
        }
        .card-header {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="text-center py-4">
            <h4>Admin Panel</h4>
        </div>
        <a href="index.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="stock.php"><i class="fas fa-cogs"></i> Inventory</a>
        <a href="product.php"><i class="fas fa-box"></i> Products</a>
        <a href="stockin.php"><i class="fas fa-arrow-circle-down"></i> Stock In</a>  
        <a href="stockout.php"><i class="fas fa-arrow-circle-up"></i> Stock Out</a>  
        <a href="report.php"><i class="fas fa-chart-line"></i> Reports</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <!-- Dashboard Header -->
        <div class="container">
            <h2 class="my-4">Welcome, <?php echo $_SESSION['admin_username']; ?>!</h2>
            <p>This is your admin dashboard. Manage your inventory features from the sidebar.</p>

            <div class="row">
                <!-- Inventory Stats -->
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-box"></i> Inventory
                        </div>
                        <div class="card-body text-center">
                            <h4><?php echo number_format($total_inventory); ?></h4>
                            <p>Total stock</p>
                        </div>
                    </div>
                </div>

                <!-- stockin Stats -->
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-arrow-circle-down"></i> Stock In
                        </div>
                        <div class="card-body text-center">
                            <h4><?php echo number_format($total_stockin); ?></h4>
                            <p>Total stockin</p>
                        </div>
                    </div>
                </div>

                <!-- stockout Stats -->
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-arrow-circle-up"></i> Stock Out
                        </div>
                        <div class="card-body text-center">
                            <h4><?php echo number_format($total_stockout); ?></h4>
                            <p>Total stockout</p>
                        </div>
                    </div>
                </div>
            </div>

         

    <!-- Bootstrap JS & Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
