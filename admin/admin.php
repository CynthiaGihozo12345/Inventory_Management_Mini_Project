<?php
require_once '../connect/connect.php'; 

class Admin {
    private $pdo;

    public function __construct() {
        $db = new Connect();  
        $this->pdo = $db->getConnection();
    }

    
    public function register($username, $password) {
        $stmt = $this->pdo->prepare("SELECT id FROM admin WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->rowCount() > 0) {
            return "Username already exists!";
        }

        
        $stmt = $this->pdo->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
        if ($stmt->execute([$username, $password])) {
            return "Admin registered successfully!";
        } else {
            return "Error registering admin.";
        }
    }

    
    public function login($username, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM admin WHERE username = ? AND password = ?");
        $stmt->execute([$username, $password]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin) {
            session_start();
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];

            
            header("Location: ../dashboard/");
            exit();
        } else {
            return "Invalid username or password.";
        }
    }

    
}
?>
