<?php
include '../connect/connect.php';

class StockIn {
    private $pdo;

    public function __construct() {
        $this->pdo = (new Connect())->getConnection();
    }
   
    // Create a new stock-in entry and update the stock
    public function createStockIn($product_id, $quantity, $price) {
        try {
            // Start a transaction to ensure both actions (inserting stock-in and updating stock) are atomic
            $this->pdo->beginTransaction();

            // Insert the new stock-in entry
            $stmt = $this->pdo->prepare("INSERT INTO stockin (PRODUCT_ID, QUANTITY, PRICE, DATE) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$product_id, $quantity, $price]);

            // Check if the product already exists in the stock table
            $stmt = $this->pdo->prepare("SELECT QUANTITY FROM stock WHERE PRODUCT_ID = ?");
            $stmt->execute([$product_id]);
            $stock = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($stock) {
                // Product exists, update the stock quantity
                $new_quantity = $stock['QUANTITY'] + $quantity;
                $stmt = $this->pdo->prepare("UPDATE stock SET QUANTITY = ? WHERE PRODUCT_ID = ?");
                $stmt->execute([$new_quantity, $product_id]);
            } else {
                // Product does not exist in stock, insert a new record
                $stmt = $this->pdo->prepare("INSERT INTO stock (PRODUCT_ID, QUANTITY) VALUES (?, ?)");
                $stmt->execute([$product_id, $quantity]);
            }

            // Commit the transaction
            $this->pdo->commit();
            return "Stock-in entry added successfully";

        } catch (PDOException $e) {
            // Rollback the transaction if there is an error
            $this->pdo->rollBack();
            return "Error: " . $e->getMessage();
        }
    }

    // Read all stock-in records
    public function getAllStockIn() {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM stockin");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // Read a single stock-in record by its ID
    public function getStockInById($stockin_id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM stockin WHERE STOCKIN_ID = ?");
            $stmt->execute([$stockin_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // Update stock-in entry
    public function updateStockIn($stockin_id, $product_id, $quantity, $price) {
        try {
            $stmt = $this->pdo->prepare("UPDATE stockin SET PRODUCT_ID = ?, QUANTITY = ?, PRICE = ? WHERE STOCKIN_ID = ?");
            $stmt->execute([$product_id, $quantity, $price, $stockin_id]);
            return "Stock-in entry updated successfully.";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // Delete stock-in entry
    public function deleteStockIn($stockin_id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM stockin WHERE STOCKIN_ID = ?");
            $stmt->execute([$stockin_id]);
            return "Stock-in entry deleted successfully.";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function getAllProducts() {
        $sql = "SELECT PRODUCT_ID, PRODUCT_NAME FROM products";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
?>
