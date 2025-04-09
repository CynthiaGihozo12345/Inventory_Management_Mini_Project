<?php
include './CRUD/stockin.php';
session_start();

// Redirect if not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin/index.php");
    exit();
}

// Create an instance of the StockIn class
$stockInObj = new StockIn();

// Handle form submission for adding a new stock-in entry
if (isset($_POST['add_stockin'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $message = $stockInObj->createStockIn($product_id, $quantity, $price);
}

// Handle stock-in deletion
if (isset($_GET['delete_id'])) {
    $stockin_id = $_GET['delete_id'];
    $message = $stockInObj->deleteStockIn($stockin_id);
}

// Handle stock-in update
if (isset($_GET['edit_id'])) {
    $stockin_id = $_GET['edit_id'];
    $stockin_details = $stockInObj->getStockInById($stockin_id);
    if ($stockin_details) {
        $product_id = $stockin_details['PRODUCT_ID'];
        $quantity = $stockin_details['QUANTITY'];
        $price = $stockin_details['PRICE'];
    }
}

if (isset($_POST['update_stockin'])) {
    $stockin_id = $_POST['stockin_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $message = $stockInObj->updateStockIn($stockin_id, $product_id, $quantity, $price);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Stock In</title>
    
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
        .table th, .table td {
            vertical-align: middle;
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
        <a href="stockin.php" class="active"><i class="fas fa-arrow-circle-down"></i> Stock In</a>
        <a href="stockout.php"><i class="fas fa-arrow-circle-up"></i> Stock Out</a>
        <a href="report.php"><i class="fas fa-chart-line"></i> Reports</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="container">
            <h1 class="my-4">Stock In</h1>

            <!-- Display messages -->
            <?php if (isset($message)) : ?>
                <div class="alert alert-info">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <!-- Add Stock In Form -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-plus"></i> Add New Stock In
                </div>
                <div class="card-body">
                    <form action="stockin.php" method="POST">
                        <div class="mb-3">
                            <label for="product_id" class="form-label">Product</label>
                            <select class="form-control" id="product_id" name="product_id" required>
                                <option value="">Select Product</option>
                                <?php
                                // Fetch all products to populate the dropdown
                                $products = $stockInObj->getAllProducts(); // Assuming getAllProducts() method in StockIn class
                                foreach ($products as $product) {
                                    echo "<option value='" . $product['PRODUCT_ID'] . "'>" . $product['PRODUCT_NAME'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" required>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" class="form-control" id="price" name="price" required>
                        </div>
                        <button type="submit" class="btn btn-primary" name="add_stockin"><i class="fas fa-save"></i> Add Stock In</button>
                    </form>
                </div>
            </div>

            <!-- Display All Stock In Records -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-box"></i> Stock In List
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Stock In ID</th>
                                <th>Product ID</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stockins = $stockInObj->getAllStockIn();
                            foreach ($stockins as $stockin) {
                                echo "<tr>";
                                echo "<td>" . $stockin['STOCKIN_ID'] . "</td>";
                                echo "<td>" . $stockin['PRODUCT_ID'] . "</td>";
                                echo "<td>" . $stockin['QUANTITY'] . "</td>";
                                echo "<td>" . $stockin['PRICE'] . "</td>";
                                echo "<td>" . $stockin['DATE'] . "</td>";
                                echo "<td>
                                        <button type='button' class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editModal' 
                                        data-id='" . $stockin['STOCKIN_ID'] . "' 
                                        data-product-id='" . $stockin['PRODUCT_ID'] . "' 
                                        data-quantity='" . $stockin['QUANTITY'] . "' 
                                        data-price='" . $stockin['PRICE'] . "'>
                                            <i class='fas fa-edit'></i> Edit
                                        </button>
                                        <a href='stockin.php?delete_id=" . $stockin['STOCKIN_ID'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this stock-in entry?\")'>
                                            <i class='fas fa-trash'></i> Delete
                                        </a>
                                      </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Stock In</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editForm" method="POST">
                                <input type="hidden" name="stockin_id" id="stockin_id">
                                <div class="mb-3">
                                    <label for="edit_product_id" class="form-label">Product</label>
                                    <select class="form-control" name="product_id" id="edit_product_id" required>
                                        <option value="">Select Product</option>
                                        <?php
                                        // Fetch all products for the edit modal as well
                                        $products = $stockInObj->getAllProducts();
                                        foreach ($products as $product) {
                                            echo "<option value='" . $product['PRODUCT_ID'] . "'>" . $product['PRODUCT_NAME'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_quantity" class="form-label">Quantity</label>
                                    <input type="number" name="quantity" id="edit_quantity" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_price" class="form-label">Price</label>
                                    <input type="number" step="0.01" name="price" id="edit_price" class="form-control" required>
                                </div>
                                <button type="submit" name="update_stockin" class="btn btn-primary">Update Stock In</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Bootstrap JS & Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <script>
        // Populate the edit modal with data
        const editButtons = document.querySelectorAll('.btn-warning');
        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const productId = this.getAttribute('data-product-id');
                const quantity = this.getAttribute('data-quantity');
                const price = this.getAttribute('data-price');

                document.getElementById('stockin_id').value = id;
                document.getElementById('edit_product_id').value = productId;
                document.getElementById('edit_quantity').value = quantity;
                document.getElementById('edit_price').value = price;
            });
        });
    </script>
</body>
</html>
