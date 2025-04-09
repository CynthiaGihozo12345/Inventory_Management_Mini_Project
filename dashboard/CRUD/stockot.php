<?php
include '../connect/connect.php';

class StockOut {
    private $pdo;

    public function __construct() {
        $this->pdo = (new Connect())->getConnection();
    }

    // Create a new stock-out entry
    public function createStockOut($product_id, $quantity, $price) {
        try {
            // Step 1: Check the available stock for the given product_id
            $stmt = $this->pdo->prepare("SELECT QUANTITY FROM stock WHERE PRODUCT_ID = ?");
            $stmt->execute([$product_id]);
            $stock = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Step 2: Check if the product exists in stock
            if (!$stock) {
                return "Error: Product not found in stock.";
            }
    
            // Step 3: Check if there is enough stock available
            if ($stock['QUANTITY'] < $quantity) {
                return "Insufficient stock.";
            }
    
            // Step 4: Insert the stock-out entry into the stockout table
            $stmt = $this->pdo->prepare("INSERT INTO stockout (PRODUCT_ID, QUANTITY, PRICE, DATE) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$product_id, $quantity, $price]);
    
            // Step 5: Update the stock quantity after the stock-out
            $new_quantity = $stock['QUANTITY'] - $quantity;
            $stmtUpdate = $this->pdo->prepare("UPDATE stock SET QUANTITY = ? WHERE PRODUCT_ID = ?");
            $stmtUpdate->execute([$new_quantity, $product_id]);
    
            return "Stock-out entry added successfully";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }
    
    // Read all stock-out records
    public function getAllStockOut() {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM stockout");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // Read a single stock-out record by its ID
    public function getStockOutById($stockout_id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM stockout WHERE STOCKOUT_ID = ?");
            $stmt->execute([$stockout_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // Update stock-out entry
    public function updateStockOut($stockout_id, $product_id, $quantity, $price) {
        try {
            $stmt = $this->pdo->prepare("UPDATE stockout SET PRODUCT_ID = ?, QUANTITY = ?, PRICE = ? WHERE STOCKOUT_ID = ?");
            $stmt->execute([$product_id, $quantity, $price, $stockout_id]);
            return "Stock-out entry updated successfully.";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // Delete stock-out entry
    public function deleteStockOut($stockout_id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM stockout WHERE STOCKOUT_ID = ?");
            $stmt->execute([$stockout_id]);
            return "Stock-out entry deleted successfully.";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }



    
        public function getAllProducts() {
            // Assuming you have a database connection in $this->db
            $query = "SELECT PRODUCT_ID, PRODUCT_NAME FROM products"; // Adjust table name and column names
            $result = $this->pdo->query($query);
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }
   
}
?>
