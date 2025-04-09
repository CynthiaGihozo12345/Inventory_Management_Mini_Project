<?php
// Include the Stock class to fetch data
include './CRUD/stock.php';

session_start();

// Redirect if not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin/index.php");
    exit();
}

// Create an instance of the Stock class
$stock = new Stock();

// Fetch stock data from the database
$stockData = $stock->getAllStock();
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

    <!-- Content -->
    <div class="content">
        <h1>Stock Inventory</h1>

        <!-- Stock Table -->
        <div class="card">
            <div class="card-header">Stock Entries</div>
            <div class="card-body">
                <?php if (!empty($stockData)): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Stock ID</th>
                                <th>Product ID</th>
                                <th>Quantity</th>
                                <!-- <th>Actions</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stockData as $row): ?>
    <tr>
        <!-- Using numeric indexes -->
        <td><?php echo $row['STOCK_ID']; ?></td> <!-- STOCK_ID -->
        <td><?php echo $row['PRODUCT_ID']; ?></td> <!-- PRODUCT_ID -->
        <td><?php echo $row['QUANTITY']; ?></td> <!-- Quantity -->
        <!-- <td>
            <a href="edit_stock.php?id=<?php echo $row['STOCK_ID']; ?>" class="btn btn-warning btn-sm">Edit</a> 
            <a href="delete_stock.php?id=<?php echo $row['STOCK_ID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a> <!-- STOCK_ID -->
        <!-- </td> -->
    </tr>
<?php endforeach; ?>

                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No stock records found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS & Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
