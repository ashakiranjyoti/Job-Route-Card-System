<?php
session_start();
include 'config.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get job card ID
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$job_id = intval($_GET['id']);
$job = $conn->query("SELECT * FROM job_cards WHERE id=$job_id")->fetch_assoc();
$operations = $conn->query("SELECT * FROM operations WHERE job_card_id=$job_id ORDER BY operation_no");

// Check if job exists
if (!$job) {
    header("Location: index.php");
    exit();
}

// Check if Dispatch operation is completed
$dispatch_completed = $conn->query("SELECT COUNT(*) as count FROM operations WHERE job_card_id=$job_id AND operation_name='Dispatch' AND operation_status='completed'")->fetch_assoc()['count'];
$job_status = ($dispatch_completed > 0) ? 'complete' : 'pending';

// Count total operations
$total_ops = $conn->query("SELECT COUNT(*) as count FROM operations WHERE job_card_id=$job_id")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Job Card - <?php echo $job['order_id']; ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        .status-complete {
            color: #27ae60;
            font-weight: bold;
        }
        .status-pending {
            color: #e67e22;
            font-weight: bold;
        }
        .progress-indicator {
            margin: 10px 0;
            font-weight: bold;
        }
        /* Word wrapping styles */
        .operations-table td:nth-child(1) { /* Date column */
            white-space: normal;
            word-wrap: break-word;
            max-width: 80px;
        }
        .operations-table td:nth-child(3) { /* Operation Name column */
            white-space: normal;
            word-wrap: break-word;
            max-width: 100px;
        }
        .operations-table td:nth-child(9) { /* Remark column */
            white-space: normal;
            word-wrap: break-word;
            max-width: 200px;
        }
        .operations-table td:nth-child(10) { /* Checked By column */
            white-space: normal;
            word-wrap: break-word;
            max-width: 100px;
        }
        .operations-table td:nth-child(12) { /* Status column */
            white-space: normal;
            word-wrap: break-word;
            max-width: 100px;
        }
        /* File attachment styles */
        .file-attachment {
            display: inline-block;
            margin-top: 5px;
            padding: 3px 8px;
            background: #f0f0f0;
            border-radius: 4px;
            font-size: 0.8em;
        }
        .file-attachment a {
            color: #3498db;
            text-decoration: none;
        }
        .file-attachment a:hover {
            text-decoration: underline;
        }
        .file-icon {
            margin-right: 5px;
            color: #7f8c8d;
        }
        /* Adjust table columns */
        .operations-table th:nth-child(13),
        .operations-table td:nth-child(13) {
            white-space: normal;
            word-wrap: break-word;
            max-width: 150px;
        }
       
    </style>
</head>
<body>
    <div class="header">
        <div class="login-logo" style="text-align: center;">
           
        </div>
        <h2>Job Card Details</h2>
        <div class="user-info">
            Welcome, <?php echo $_SESSION['full_name']; ?> | 
            <a href="logout.php">Logout</a>
        </div>
    </div>
    
    <div class="menu">
        <a href="index.php">Back to Home</a>
        <?php if ($_SESSION['role'] == 'admin') { ?>
        <a href="create_job.php">Create New Job Card</a>
        <?php } ?>
    </div>
    
    <div class="content">
        <h3>Job Information</h3>
        
        <div class="progress-indicator">
            Operations Completed: <?php echo $total_ops; ?>/22
        </div>
        
        <table class="job-info">
            <tr>
                <th>Order ID</th>
                <td><?php echo $job['order_id']; ?></td>
                <th>Product Model</th>
                <td><?php echo $job['product_model']; ?></td>
            </tr>
            <tr>
                <th>Customer Name</th>
                <td><?php echo $job['customer_name']; ?></td>
                <th>PO Number</th>
                <td><?php echo $job['po_number']; ?></td>
            </tr>
            <tr>
                <th>Quantity</th>
                <td><?php echo $job['quantity_produced']; ?></td>
                <th>Status</th>
                <td class="status-<?php echo $job_status; ?>">
                    <?php echo strtoupper($job_status); ?>
                </td>
            </tr>
            <tr>
                <th>Start Date</th>
                <td><?php echo date('d-m-Y', strtotime($job['start_date'])); ?></td>
                <th>Target Date</th>
                <td><?php echo date('d-m-Y', strtotime($job['target_date'])); ?></td>
            </tr>
            <tr>
            <th>Created By</th>
            <td ><?php echo $job['created_by']; ?></td>
                <th>PO Date</th>
                <td><?php echo date('d-m-Y', strtotime($job['po_date'])); ?></td>
            </tr>
            <!-- <tr>
                <th>Created By</th>
                <td colspan="3"><?php echo $job['created_by']; ?></td>
                <th>PO Date</th>
                <td><?php echo date('d-m-Y', strtotime($job['po_date'])); ?></td>
            </tr> -->
            <tr>
                <th>Description</th>
                <td colspan="3"><?php echo $job['product_desc']; ?></td>
            </tr>
        </table>
        
        <h3>Operations</h3>
        
        <table class="operations-table">
            <tr>
                <th>Date</th>
                <th>Sr.No</th>
                <th>Operation Name</th>
                <th>PRD</th>
                <th>OK</th>
                <th>ACC </th>
                <th>RWK </th>
                <th>REJ </th>
                <th>Remark</th>
                <th>Checked By</th>
                <th>Status</th>
                <th>Attachment</th>
                <th>Action</th>
            </tr>
            
            <?php
            if ($operations->num_rows > 0) {
                while($op = $operations->fetch_assoc()) {
                    echo "<tr>
                            <td>".date('d-m-Y H:i:s', strtotime($op['operation_date']))."</td>
                            <td>{$op['operation_no']}</td>
                            <td>{$op['operation_name']}</td>
                            <td>{$op['produced_quantity']}</td>
                            <td>{$op['ok_quantity']}</td>
                            <td>{$op['accepted_quantity']}</td>
                            <td>{$op['rework_quantity']}</td>
                            <td>{$op['rejected_quantity']}</td>
                            <td>{$op['remark']}</td>
                            <td>{$op['checked_by']}</td>
                            <td>{$op['operation_status']}</td>
                            <td>";
                    
                            // Get files for this operation
    $files = $conn->query("SELECT * FROM operation_files WHERE operation_id={$op['id']}");
    if ($files->num_rows > 0) {
        while($file = $files->fetch_assoc()) {
            echo '<div class="file-attachment">
                <span class="file-icon">'.getFileIcon($file['original_name']).'</span>
                <a href="download.php?id='.$file['id'].'" download title="'.$file['original_name'].'">
                    '.htmlspecialchars($file['original_name']).'
                </a>
              </div>';
        }
    } else {
        echo "-";
    }
                    
                    echo "</td>
                            <td class='action-links'>
                                <a href='update_op.php?job_id=$job_id&op_id={$op['id']}'>Update</a>";
                    
                    // Only show delete for admin users
                    if ($_SESSION['role'] == 'admin') {
                        echo "<a href='delete_operation.php?job_id=$job_id&op_id={$op['id']}' 
                              class='delete' 
                              onclick='return confirm(\"Are you sure you want to delete this operation?\");'>
                              Delete</a>";
                    }
                    
                    echo "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='13'>No operations added yet</td></tr>";
            }
            
            // Function to get appropriate icon for file type
            function getFileIcon($filename) {
                $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                switch($extension) {
                    case 'pdf':
                        return '📄';
                    case 'doc':
                    case 'docx':
                        return '📝';
                    case 'xls':
                    case 'xlsx':
                        return '📊';
                    case 'jpg':
                    case 'jpeg':
                    case 'png':
                    case 'gif':
                        return '🖼️';
                    case 'csv':
                        return '📋';
                    default:
                        return '📎';
                }
            }
            ?>
        </table>
        
        <div class="action-buttons">
            <?php if ($total_ops < 22): ?>
                <a href="add_operation.php?job_id=<?php echo $job_id; ?>" class="button">Add Operation</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<!-- Add this CSS -->
<style>
.file-attachment {
    margin: 3px 0;
    padding: 3px;
    background: #f5f5f5;
    border-radius: 3px;
}
.file-icon {
    margin-right: 5px;
}
</style>