<?php
include './CRUD/product.php';
session_start();

// Redirect if not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin/index.php");
    exit();
}

// Handle form submission for adding a new product
if (isset($_POST['add_product'])) {
    $product_name = $_POST['product_name'];
    $message = createProduct($product_name);
}

// Handle product deletion
if (isset($_GET['delete_id'])) {
    $product_id = $_GET['delete_id'];
    $message = deleteProduct($product_id);
}

// Handle product update
if (isset($_POST['update_product'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $message = updateProduct($product_id, $product_name);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Products</title>

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
    <a href="product.php" class="active"><i class="fas fa-box"></i> Products</a>
    <a href="stockin.php"><i class="fas fa-arrow-circle-down"></i> Stock In</a>
    <a href="stockout.php"><i class="fas fa-arrow-circle-up"></i> Stock Out</a>
    <a href="report.php"><i class="fas fa-chart-line"></i> Reports</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="content">
    <div class="container">
        <h1 class="my-4">Products</h1>

        <!-- Display message after adding or deleting a product -->
        <?php if (isset($message)) : ?>
            <div class="alert alert-info">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Add Product Form -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-plus"></i> Add New Product
            </div>
            <div class="card-body">
                <form action="product.php" method="POST">
                    <div class="mb-3">
                        <label for="product_name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="product_name" name="product_name" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="add_product"><i class="fas fa-save"></i> Add Product</button>
                </form>
            </div>
        </div>

        <!-- Products List -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-box"></i> Products List
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Date Recorded</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch all products from the database
                        $products = getAllProducts();
                        if (is_array($products)) {
                            foreach ($products as $product) {
                                echo "<tr>";
                                echo "<td>" . $product['PRODUCT_ID'] . "</td>";
                                echo "<td>" . $product['PRODUCT_NAME'] . "</td>";
                                echo "<td>" . $product['DATE_RECORDED'] . "</td>";
                                echo "<td>
                                        <a href='#' class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#updateModal' 
                                            data-id='" . $product['PRODUCT_ID'] . "' 
                                            data-name='" . $product['PRODUCT_NAME'] . "'>
                                            <i class='fas fa-edit'></i> Edit
                                        </a>
                                        <a href='product.php?delete_id=" . $product['PRODUCT_ID'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this product?\")'>
                                            <i class='fas fa-trash'></i> Delete
                                        </a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No products found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal for updating product -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="product.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="product_name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="product_name" name="product_name" required>
                        <input type="hidden" id="product_id" name="product_id">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="update_product">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS & Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

<!-- JavaScript to pre-fill modal fields -->
<script>
    const updateModal = document.getElementById('updateModal');
    updateModal.addEventListener('show.bs.modal', (event) => {
        const button = event.relatedTarget;
        const productId = button.getAttribute('data-id');
        const productName = button.getAttribute('data-name');
        
        const modalTitle = updateModal.querySelector('.modal-title');
        const modalProductName = updateModal.querySelector('#product_name');
        const modalProductId = updateModal.querySelector('#product_id');

        modalProductName.value = productName;
        modalProductId.value = productId;
    });
</script>

</body>
</html>
