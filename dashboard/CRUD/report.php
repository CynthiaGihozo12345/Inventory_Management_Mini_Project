<?php
// Include the connection class
include '../connect/connect.php';

class Report {
    private $pdo;

    public function __construct() {
        $this->pdo = (new Connect())->getConnection();  // Initialize the PDO connection
    }

    // This function will fetch stock report
    public function getStockReport($start_date, $end_date) {
        $sql = "
            SELECT s.STOCK_ID, 
                   s.PRODUCT_ID, 
                   p.PRODUCT_NAME, 
                   s.QUANTITY AS STOCK_QUANTITY, 
                   p.DATE_RECORDED AS PRODUCT_DATE_RECORDED
            FROM stock s
            LEFT JOIN products p ON s.PRODUCT_ID = p.PRODUCT_ID
            WHERE p.DATE_RECORDED BETWEEN ? AND ?
            ORDER BY s.STOCK_ID DESC
        ";

        // Prepare and execute the SQL query
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$start_date, $end_date]);

        // Fetch all the results
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // This function will fetch stock-in report
    public function getStockInReport($start_date, $end_date) {
        $sql = "
            SELECT si.STOCKIN_ID, 
                   si.PRODUCT_ID, 
                   p.PRODUCT_NAME, 
                   si.QUANTITY AS STOCKIN_QUANTITY, 
                   si.PRICE, 
                   si.DATE AS STOCKIN_DATE
            FROM stockin si
            LEFT JOIN products p ON si.PRODUCT_ID = p.PRODUCT_ID
            WHERE si.DATE BETWEEN ? AND ?
            ORDER BY si.STOCKIN_ID DESC
        ";

        // Prepare and execute the SQL query
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$start_date, $end_date]);

        // Fetch all the results
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // This function will fetch stock-out report
    public function getStockOutReport($start_date, $end_date) {
        $sql = "
            SELECT so.STOCKOUT_ID, 
                   so.PRODUCT_ID, 
                   p.PRODUCT_NAME, 
                   so.QUANTITY AS STOCKOUT_QUANTITY, 
                   so.PRICE, 
                   so.DATE AS STOCKOUT_DATE
            FROM stockout so
            LEFT JOIN products p ON so.PRODUCT_ID = p.PRODUCT_ID
            WHERE so.DATE BETWEEN ? AND ?
            ORDER BY so.STOCKOUT_ID DESC
        ";

        // Prepare and execute the SQL query
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$start_date, $end_date]);

        // Fetch all the results
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // This function will fetch product report
    public function getProductReport($start_date, $end_date) {
        $sql = "
            SELECT p.PRODUCT_ID, 
                   p.PRODUCT_NAME, 
                
                   p.DATE_RECORDED AS PRODUCT_DATE_RECORDED
            FROM products p
            WHERE p.DATE_RECORDED BETWEEN ? AND ?
            ORDER BY p.PRODUCT_ID DESC
        ";

        // Prepare and execute the SQL query
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$start_date, $end_date]);

        // Fetch all the results
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
