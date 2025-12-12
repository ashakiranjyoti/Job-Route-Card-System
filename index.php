<?php 
session_start(); // Add this line at the VERY TOP
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'config.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Job Route System - Pending Jobs</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .action-links a {
            margin-right: 10px;
        }
        .action-links a.delete {
            color: #e74c3c;
        }
        .status-pending {
            color: #e67e22;
            font-weight: bold;
        }
        .overdue {
            color: #e74c3c;
            font-weight: bold;
            animation: blink 1s infinite;
        }
        .target-date-warning {
            background-color: #ffebee;
            padding: 3px 6px;
            border-radius: 3px;
        }
        @keyframes blink {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="login-logo" style="text-align: center;">
            <a class="navbar-brand" href="#">
                
            </a>
        </div>
        <h2>Job Route System - Pending Jobs</h2>
        <div class="user-info">
            Welcome, <?php echo $_SESSION['full_name']; ?> | 
            <a href="logout.php">Logout</a>
        </div>
    </div>
    
    <div class="menu">
        <?php if ($_SESSION['role'] == 'admin') { ?>
        <a href="create_job.php">Create New Job Card</a>
        <?php } ?>
        <a href="index.php">Pending Jobs</a>
        <a href="completed_jobs.php">Completed Jobs</a>
    </div>
    
    <div class="content">
        <h3>Pending Job Cards</h3>
        
        <?php
        $sql = "SELECT * FROM job_cards WHERE status='pending' ORDER BY created_at DESC";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            echo "<table class='job-table'>";
            echo "<tr>
                    <th>S.NO</th>
                    <th>Order ID</th>
                    <th>Product Model</th>
                    <th>Customer</th>
                    <th>Quantity</th>
                    <th>Target Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>";
            
            $sno = 1;
            while($row = $result->fetch_assoc()) {
                $target_date = $row['target_date'];
                $today = date('Y-m-d');
                $is_overdue = (strtotime($today) > strtotime($target_date));
                
                echo "<tr>
                        <td>$sno</td>
                        <td>{$row['order_id']}</td>
                        <td>{$row['product_model']}</td>
                        <td>{$row['customer_name']}</td>
                        <td>{$row['quantity_produced']}</td>
                        <td class='" . ($is_overdue ? 'overdue' : '') . "'>
                            {$target_date}" . 
                            ($is_overdue ? ' <span class="target-date-warning"></span>' : '') . "
                        </td>
                        <td class='status-pending'>PENDING</td>
                        <td class='action-links'>
                            <a href='view_job.php?id={$row['id']}'>View</a>";
                            
                            if ($_SESSION['role'] == 'admin') {
                                echo "<a href='edit_job.php?id={$row['id']}'>Edit</a>";
                                echo "<a href='delete_job.php?id={$row['id']}' class='delete' onclick='return confirm(\"Are you sure you want to delete this job?\")'>Delete</a>";
                            }
                            
                            echo "<a href='generate_pdf.php?order_id={$row['order_id']}' class='pdf'>PDF</a>
                        </td>
                      </tr>";
                $sno++;
            }
            echo "</table>";
        } else {
            echo "<p>No pending job cards found.</p>";
        }
        ?>
    </div>
</body>
</html>