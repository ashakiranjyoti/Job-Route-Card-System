<?php 
session_start();
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
    <title>Job Route System - Completed Jobs</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .action-links a {
            margin-right: 10px;
        }
        .status-complete {
            color: #27ae60;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
    <div class="login-logo" style="text-align: center;">
    
</div>
        <h2>Job Route System - Completed Jobs</h2>
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
        <h3>Completed Job Cards</h3>
        
        <?php
        $sql = "SELECT * FROM job_cards WHERE status='complete' ORDER BY created_at DESC";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            echo "<table class='job-table'>";
            echo "<tr>
                    <th>S.NO</th>
                    <th>Order ID</th>
                    <th>Product Model</th>
                    <th>Customer</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>";
            
            $sno = 1;
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>$sno</td>
                        <td>{$row['order_id']}</td>
                        <td>{$row['product_model']}</td>
                        <td>{$row['customer_name']}</td>
                        <td>{$row['quantity_produced']}</td>
                        <td class='status-complete'>COMPLETE</td>
                        <td class='action-links'>
                            <a href='view_job.php?id={$row['id']}'></a>";
                           
                            
                            // Show DELETE only if user is admin
                            if ($_SESSION['role'] == 'admin') {
                                echo "<a href='view_job.php?id={$row['id']}'>View</a>";
                                echo "<a href='delete_job.php?id={$row['id']}' class='delete' onclick='return confirm(\"Are you sure you want to delete this job?\")'>Delete</a>";
                            }
                            
                            echo "<a href='generate_pdf.php?order_id={$row['order_id']}' class='pdf'>PDF</a>
                        </td>
                      </tr>";
                $sno++;
            }
            echo "</table>";
        } else {
            echo "<p>No completed job cards found.</p>";
        }
        ?>
    </div>
</body>
</html>