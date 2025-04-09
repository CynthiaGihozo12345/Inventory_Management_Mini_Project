<?php
include './CRUD/stockot.php';
session_start();

// Redirect if not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin/index.php");
    exit();
}

$stockOut = new StockOut();
$successMessage = ''; // Initialize success message variable

// Fetch all products for the product dropdown
$products = $stockOut->getAllProducts(); // Fetch products

// Handle delete request
if (isset($_GET['delete'])) {
    $stockOutId = $_GET['delete'];
    $stockOut->deleteStockOut($stockOutId);
}

// Fetch all stock-out records
$stockOutData = $stockOut->getAllStockOut();

// Handle adding a new stock-out entry
if (isset($_POST['add_stockout'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    $result = $stockOut->createStockOut($product_id, $quantity, $price);

    // Show success message only if the creation was successful
    if ($result === true) {
        $successMessage = 'Stock Out successfully added!';
        header("location:stockout.php");
    } else {
        $successMessage = " $result";  // In case the result is an error message
    }
}

// Handle updating the stock-out entry
if (isset($_POST['update_stockout'])) {
    $stockout_id = $_POST['stockout_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    $result = $stockOut->updateStockOut($stockout_id, $product_id, $quantity, $price);

    // Show success message only if the update was successful
    if ($result === true) {
        $successMessage = 'Stock Out successfully updated!';
    } else {
        $successMessage = "Error: $result";  // In case the result is an error message
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Stock Out</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
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
        <a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="stock.php"><i class="fas fa-cogs"></i> Inventory</a>
        <a href="product.php"><i class="fas fa-box"></i> Products</a>
        <a href="stockin.php"><i class="fas fa-arrow-circle-down"></i> Stock In</a>  
        <a href="stockout.php" class="active"><i class="fas fa-arrow-circle-up"></i> Stock Out</a>  
        <a href="report.php"><i class="fas fa-chart-line"></i> Reports</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Content -->
    <div class="content">
        <h1>Stock Out Records</h1>

        <!-- Display Success Message -->
        <?php if ($successMessage): ?>
            <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <!-- Add Stock-Out Form -->
        <div class="card mb-4">
            <div class="card-header">Add New Stock-Out</div>
            <div class="card-body">
                <form action="stockout.php" method="POST">
                    <div class="mb-3">
                        <label for="product_id" class="form-label">Product</label>
                        <select name="product_id" class="form-control" required>
                            <option value="">Select a Product</option>
                            <?php foreach ($products as $product): ?>
                                <option value="<?php echo $product['PRODUCT_ID']; ?>"><?php echo $product['PRODUCT_NAME']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" name="quantity" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" step="0.01" name="price" class="form-control" required>
                    </div>
                    <button type="submit" name="add_stockout" class="btn btn-primary">Add Stock Out</button>
                </form>
            </div>
        </div>

        <!-- Stock-Out Table -->
        <div class="card">
            <div class="card-header">Stock-Out Entries</div>
            <div class="card-body">
                <?php if (!empty($stockOutData)): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Stock Out ID</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stockOutData as $row): ?>
                                <tr>
                                    <td><?php echo $row['STOCKOUT_ID']; ?></td>
                                    <td><?php echo $row['PRODUCT_ID']; ?></td>
                                    <td><?php echo $row['QUANTITY']; ?></td>
                                    <td><?php echo $row['PRICE']; ?></td>
                                    <td><?php echo $row['DATE']; ?></td>
                                    <td>
                                        <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" data-id="<?php echo $row['STOCKOUT_ID']; ?>" data-product-id="<?php echo $row['PRODUCT_ID']; ?>" data-quantity="<?php echo $row['QUANTITY']; ?>" data-price="<?php echo $row['PRICE']; ?>">Edit</a>
                                        <a href="?delete=<?php echo $row['STOCKOUT_ID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No stock-out records found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS & Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Stock-Out</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST">
                        <input type="hidden" name="stockout_id" id="stockout_id">
                        <div class="mb-3">
                            <label for="edit_product_id" class="form-label">Product ID</label>
                            <input type="number" name="product_id" id="edit_product_id" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_quantity" class="form-label">Quantity</label>
                            <input type="number" name="quantity" id="edit_quantity" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_price" class="form-label">Price</label>
                            <input type="number" step="0.01" name="price" id="edit_price" class="form-control" required>
                        </div>
                        <button type="submit" name="update_stockout" class="btn btn-primary">Update Stock Out</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Populate the edit modal with data
        const editButtons = document.querySelectorAll('.btn-warning');
        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const productId = this.getAttribute('data-product-id');
                const quantity = this.getAttribute('data-quantity');
                const price = this.getAttribute('data-price');

                document.getElementById('stockout_id').value = id;
                document.getElementById('edit_product_id').value = productId;
                document.getElementById('edit_quantity').value = quantity;
                document.getElementById('edit_price').value = price;
            });
        });
    </script>
</body>
</html>
