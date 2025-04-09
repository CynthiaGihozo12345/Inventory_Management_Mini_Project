<?php
session_start();
include './CRUD/report.php';  // Include the Report class file

// Redirect if not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin/index.php");
    exit();
}

// Handle the form submission to generate the report
$report_data = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['generate_report'])) {
    $report_type = $_POST['report_type'];
    $timeframe = $_POST['timeframe']; // Get the selected timeframe

    // Instantiate the Report class
    $report = new Report();

    // Calculate start and end dates based on timeframe
    if ($timeframe == 'custom') {
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
    } else {
        // Calculate the date range based on daily, weekly, monthly, or annual
        $current_date = date('Y-m-d');

        switch ($timeframe) {
            case 'daily':
                $start_date = $current_date;
                $end_date = $current_date;
                break;
            case 'weekly':
                $start_date = date('Y-m-d', strtotime('last Sunday')); // Start of the week (Sunday)
                $end_date = $current_date;  // End of the week (Today)
                break;
            case 'monthly':
                $start_date = date('Y-m-01');  // Start of the current month
                $end_date = $current_date;  // End of the month (Today)
                break;
            case 'annual':
                $start_date = date('Y-01-01');  // Start of the current year
                $end_date = $current_date;  // End of the year (Today)
                break;
            default:
                echo "Invalid timeframe selected.";
                return;
        }
    }

    // Fetch the report data based on the selected report type and date range
    if ($report_type == 'stock') {
        $report_data = $report->getStockReport($start_date, $end_date);
    } elseif ($report_type == 'stockin') {
        $report_data = $report->getStockInReport($start_date, $end_date);
    } elseif ($report_type == 'stockout') {
        $report_data = $report->getStockOutReport($start_date, $end_date);
    } elseif ($report_type == 'product') {
        $report_data = $report->getProductReport($start_date, $end_date);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Report</title>

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
        <a href="index.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="stock.php"><i class="fas fa-cogs"></i> Inventory</a>
        <a href="product.php"><i class="fas fa-box"></i> Products</a>
        <a href="stockin.php"><i class="fas fa-arrow-circle-down"></i> Stock In</a>
        <a href="stockout.php"><i class="fas fa-arrow-circle-up"></i> Stock Out</a>
        <a href="report.php"><i class="fas fa-chart-line"></i> Reports</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Content Section -->
    <div class="content">
        <h1>Generate Report</h1>

        <!-- Report Type Form -->
        <form method="POST" action="" class="mb-4">
            <div class="form-group">
                <label for="report_type">Select Report Type</label>
                <select name="report_type" id="report_type" class="form-control" required>
                    <option value="stock">Stock</option>
                    <option value="stockin">Stock In</option>
                    <option value="stockout">Stock Out</option>
                    <option value="product">Products</option>
                </select>
            </div>
            
            <div class="form-group mt-3">
                <label for="timeframe">Select Timeframe</label>
                <select name="timeframe" id="timeframe" class="form-control" required onchange="toggleDateFields()">
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                    <option value="annual">Annual</option>
                    <option value="custom">Custom Date Range</option>
                </select>
            </div>

            <div id="custom-date-range" style="display: none;">
                <div class="form-group mt-3">
                    <label for="start_date">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="form-control">
                </div>
                <div class="form-group mt-3">
                    <label for="end_date">End Date</label>
                    <input type="date" name="end_date" id="end_date" class="form-control">
                </div>
            </div>

            <button type="submit" name="generate_report" class="btn btn-primary mt-3">Generate Report</button>
        </form>

        <!-- Display Report Data -->
        <?php
        if ($report_data) {
            echo '<table class="table table-bordered mt-4">';
            echo '<thead><tr>';
            foreach (array_keys($report_data[0]) as $column) {
                echo "<th>$column</th>";
            }
            echo '</tr></thead><tbody>';
            foreach ($report_data as $row) {
                echo '<tr>';
                foreach ($row as $column_value) {
                    echo "<td>$column_value</td>";
                }
                echo '</tr>';
            }
            echo '</tbody></table>';
        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo '<p>No data found for the selected period.</p>';
        }
        ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <script>
        function toggleDateFields() {
            const timeframe = document.getElementById('timeframe').value;
            const customDateRange = document.getElementById('custom-date-range');
            if (timeframe === 'custom') {
                customDateRange.style.display = 'block';
            } else {
                customDateRange.style.display = 'none';
            }
        }
    </script>
</body>
</html>
