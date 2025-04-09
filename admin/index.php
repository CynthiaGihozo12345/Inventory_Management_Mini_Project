<?php
require 'Admin.php'; 

$admin = new Admin();
$message = ''; // Initialize message variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action']; 
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($action == "register") {
        $message = $admin->register($username, $password); // Store the result or message
    } elseif ($action == "login") {
        $message = $admin->login($username, $password); // Store the result or message
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Register</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="./asset/style.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <script>
        // JavaScript to toggle password visibility using checkbox
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var checkbox = document.getElementById("showPassword");

            if (checkbox.checked) {
                passwordField.type = "text"; // Show password
            } else {
                passwordField.type = "password"; // Hide password
            }
        }
    </script>

    <style>
        /* Custom styles for the password container */
        .password-container {
            position: relative;
        }

        .password-container input {
            padding-right: 40px; /* Space for the checkbox if necessary */
        }

        .password-container .form-check {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h3>Login / Register</h3>
                    </div>
                    <div class="card-body">

                        <!-- Display Message as Alert -->
                        <?php if ($message): ?>
                            <div class="alert <?php echo (strpos($message, 'successful') !== false) ? 'alert-success' : 'alert-danger'; ?>">
                                <?php echo $message; ?>
                            </div>
                        <?php endif; ?>

                        <form method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" id="username" placeholder="Enter username" required>
                            </div>
                            <div class="mb-3 password-container">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="Enter password" required>
                                <!-- Checkbox to toggle password visibility -->
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="showPassword" onclick="togglePassword()">
                                    <label class="form-check-label" for="showPassword">Show Password</label>
                                </div>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary" name="action" value="register">Register</button>
                                <button type="submit" class="btn btn-success" name="action" value="login">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS & Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
