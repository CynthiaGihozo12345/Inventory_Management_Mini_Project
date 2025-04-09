<?php
// Include the Connect class to get the PDO connection
include '../connect/connect.php';

// Function to create a new product
// Function to create a new product and insert into stock with quantity 0
function createProduct($product_name) {
    // Create an instance of the Connect class
    $db = new Connect();
    $pdo = $db->getConnection(); // Get the PDO connection

    // Start a transaction to ensure both insertions are successful
    $pdo->beginTransaction();

    try {
        // Insert into the products table
        $sql = "INSERT INTO products (PRODUCT_NAME, DATE_RECORDED) VALUES (:product_name, NOW())";
        $stmt = $pdo->prepare($sql);
        // Bind the parameter for product name
        $stmt->bindParam(':product_name', $product_name, PDO::PARAM_STR);
        
        // Execute the statement for inserting the product
        if ($stmt->execute()) {
            // Get the last inserted product ID
            $product_id = $pdo->lastInsertId();
            
            // Now insert into the stock table with quantity 0
            $sqlStock = "INSERT INTO stock (PRODUCT_ID, QUANTITY) VALUES (:product_id, 0)";
            $stmtStock = $pdo->prepare($sqlStock);
            // Bind the parameter for product_id
            $stmtStock->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            
            // Execute the statement for inserting into stock
            if ($stmtStock->execute()) {
                // Commit the transaction if both insertions succeed
                $pdo->commit();
                return "Product added successfully, and stock initialized with quantity 0.";
            } else {
                // Rollback if the stock insertion fails
                $pdo->rollBack();
                return "Error: " . $stmtStock->errorInfo()[2];
            }
        } else {
            // Rollback if the product insertion fails
            $pdo->rollBack();
            return "Error: " . $stmt->errorInfo()[2];
        }
    } catch (Exception $e) {
        // Rollback in case of any error
        $pdo->rollBack();
        return "Error: " . $e->getMessage();
    }
}


// Function to read all products
function getAllProducts() {
    // Create an instance of the Connect class
    $db = new Connect();
    $pdo = $db->getConnection(); // Get the PDO connection

    $sql = "SELECT * FROM products";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute()) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return "No products found.";
    }
}

// Function to read a product by ID
function getProductById($product_id) {
    // Create an instance of the Connect class
    $db = new Connect();
    $pdo = $db->getConnection(); // Get the PDO connection

    $sql = "SELECT * FROM products WHERE PRODUCT_ID = :product_id";
    $stmt = $pdo->prepare($sql);
    
    // Bind the parameter
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($product) {
            return $product;
        } else {
            return "Product not found.";
        }
    } else {
        return "Error: " . $stmt->errorInfo()[2];
    }
}

// Function to update a product's name
function updateProduct($product_id, $new_product_name) {
    // Create an instance of the Connect class
    $db = new Connect();
    $pdo = $db->getConnection(); // Get the PDO connection

    $sql = "UPDATE products SET PRODUCT_NAME = :product_name WHERE PRODUCT_ID = :product_id";
    $stmt = $pdo->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':product_name', $new_product_name, PDO::PARAM_STR);
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);

    // Execute the statement
    if ($stmt->execute()) {
        return "Product updated successfully.";
    } else {
        return "Error: " . $stmt->errorInfo()[2];
    }
}

// Function to delete a product by ID
function deleteProduct($product_id) {
    // Create an instance of the Connect class
    $db = new Connect();
    $pdo = $db->getConnection(); // Get the PDO connection

    $sql = "DELETE FROM products WHERE PRODUCT_ID = :product_id";
    $stmt = $pdo->prepare($sql);

    // Bind the parameter
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);

    // Execute the statement
    if ($stmt->execute()) {
        return "Product deleted successfully.";
    } else {
        return "Error: " . $stmt->errorInfo()[2];
    }
}
?>
