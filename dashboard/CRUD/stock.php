<?php
// Include database connection
include '../connect/connect.php'; 

class Stock {
    private $pdo;

    public function __construct() {
        $this->pdo = (new Connect())->getConnection();  // Create a new PDO connection
    }

    // Get all stock data
    public function getAllStock() {
        $sql = "SELECT * FROM stock ORDER BY STOCK_ID DESC";  // Example SQL query
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();  // Return all rows as associative array
    }

    // Create a new stock entry
    public function createStock($product_id, $quantity) {
        try {
            $sql = "INSERT INTO stock (PRODUCT_ID, QUANTITY) VALUES (?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$product_id, $quantity]);
            return true;  // Success
        } catch (Exception $e) {
            return $e->getMessage();  // Return error message
        }
    }

    // Update an existing stock entry
    public function updateStock($stock_id, $product_id, $quantity) {
        try {
            $sql = "UPDATE stock SET PRODUCT_ID = ?, QUANTITY = ? WHERE STOCK_ID = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$product_id, $quantity, $stock_id]);
            return true;  // Success
        } catch (Exception $e) {
            return $e->getMessage();  // Return error message
        }
    }

    // Delete a stock entry
    public function deleteStock($stock_id) {
        try {
            $sql = "DELETE FROM stock WHERE STOCK_ID = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$stock_id]);
            return true;  // Success
        } catch (Exception $e) {
            return $e->getMessage();  // Return error message
        }
    }
}
?>
