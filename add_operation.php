<?php
session_start();
include 'config.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fixed operations list (for name dropdown) - Updated to 16 operations
$fixed_operations = [
    "Order Accepted",
    "Material receipt",
    "Inspection",
    "Material Storage",
    "Material moved for PCB assembly",
    "Inspection of assembled PCB",
    "Storage",
    "Remaining assembly, components, like connector",
    "Programming & testing",
    "Conformal Coating",
    "Final assembly",
    "Final Testing",
    "Pre-Dispatch inspection",
    "Storage - Ready Material",
    "Wiring",  
    "Partial Dispatch",  
    "On Hold",  
    "Packing",  
    "Billing Done",  
    "Delivery Done",  
    "Fabrication",  
    "Dispatch"  
];

// Get job ID
if (!isset($_GET['job_id'])) {
    header("Location: index.php");
    exit();
}

$job_id = intval($_GET['job_id']);
$job = $conn->query("SELECT * FROM job_cards WHERE id=$job_id")->fetch_assoc();

// Check if job exists
if (!$job) {
    header("Location: index.php");
    exit();
}

// Get completed operations count and list
$completed_ops = $conn->query("SELECT * FROM operations WHERE job_card_id=$job_id ORDER BY operation_no");
$completed_count = $completed_ops->num_rows;
$next_op_no = $completed_count + 1;

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $operation_no = $next_op_no;
    $operation_name = sanitizeInput($_POST['operation_name']);
    $produced_quantity = intval($_POST['produced_quantity']);
    $ok_quantity = intval($_POST['ok_quantity']);
    $accepted_quantity = intval($_POST['accepted_quantity']);
    $rework_quantity = intval($_POST['rework_quantity']);
    $rejected_quantity = intval($_POST['rejected_quantity']);
    $remark = sanitizeInput($_POST['remark']);
    $operation_status = sanitizeInput($_POST['operation_status']);
    $checked_by = $_SESSION['full_name'];
    
    // Set operation status to complete when added
    $status = 'complete';
    
    // Get current date and time
    $current_datetime = date('Y-m-d H:i:s');
    
    // File upload handling
    $file_path = '';
    if (isset($_FILES['operation_file']) && $_FILES['operation_file']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/operations/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_name = basename($_FILES['operation_file']['name']);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Validate file type
        $allowed_types = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'csv'];
        if (in_array($file_ext, $allowed_types)) {
            $new_file_name = "job_{$job_id}_op_{$operation_no}_" . time() . ".$file_ext";
            $target_path = $upload_dir . $new_file_name;
            
            if (move_uploaded_file($_FILES['operation_file']['tmp_name'], $target_path)) {
                $file_path = $target_path;
            } else {
                $error = "Failed to upload file.";
            }
        } else {
            $error = "Invalid file type. Allowed types: " . implode(', ', $allowed_types);
        }
    }
    
    $sql = "INSERT INTO operations (job_card_id, operation_no, operation_name, 
            produced_quantity, ok_quantity, accepted_quantity, rework_quantity,
            rejected_quantity, remark, checked_by, operation_date, operation_status)
            VALUES ($job_id, $operation_no, '$operation_name', 
            $produced_quantity, $ok_quantity, $accepted_quantity, $rework_quantity,
            $rejected_quantity, '$remark', '$checked_by', '$current_datetime', '$operation_status')";
    
    if ($conn->query($sql)) {
        $operation_id = $conn->insert_id;
        
        // Handle multiple file uploads
        if (!empty($_FILES['operation_files']['name'][0])) {
            $upload_dir = 'uploads/operations/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            foreach ($_FILES['operation_files']['name'] as $key => $name) {
                if ($_FILES['operation_files']['error'][$key] == UPLOAD_ERR_OK) {
                    $original_name = basename($name);
                    $file_ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
                    
                    // Validate file type
                    $allowed_types = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'csv'];
                    if (in_array($file_ext, $allowed_types)) {
                        // Create secure storage name
                        $secure_name = "job_{$job_id}_op_{$operation_no}_" . time() . "_{$key}.$file_ext";
                        $target_path = $upload_dir . $secure_name;
                        
                        if (move_uploaded_file($_FILES['operation_files']['tmp_name'][$key], $target_path)) {
                            // Save both original and secure names
                            $file_sql = "INSERT INTO operation_files 
                                        (operation_id, file_path, original_name, secure_name)
                                        VALUES (?, ?, ?, ?)";
                            $stmt = $conn->prepare($file_sql);
                            $stmt->bind_param("isss", $operation_id, $target_path, $original_name, $secure_name);
                            $stmt->execute();
                        }
                    }
                }
            
    
}
        }
        
        // Update job status if Dispatch is completed
        if ($operation_name == "Dispatch" && $operation_status == 'completed') {
            $conn->query("UPDATE job_cards SET status='complete' WHERE id=$job_id");
        }
        
        header("Location: view_job.php?id=$job_id");
        exit();
    } else {
        $error = "Error adding operation: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Operation - Job Route System</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .completed-ops {
            list-style-type: none;
            padding: 0;
            margin: 20px 0;
        }
        .completed-ops li {
            background: #f0f0f0;
            padding: 8px;
            margin: 5px 0;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
        }
        .op-status {
            font-weight: bold;
        }
        .status-complete {
            color: #27ae60;
        }
        .job-status {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            font-weight: bold;
        }
        .job-pending {
            background-color: #f39c12;
            color: white;
        }
        .job-complete {
            background-color: #27ae60;
            color: white;
        }
        select, input[type="number"], textarea, input[type="file"] {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
        }
        .datetime-display {
            font-weight: bold;
        }
        /* Status dropdown styles */
        .status-dropdown {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
            width: 100%;
        }
        .status-in-progress { color: #f39c12; }
        .status-completed { color: #27ae60; }
        .status-pending { color: #e74c3c; }
        .file-upload-info {
            font-size: 0.9em;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="login-logo" style="text-align: center;">
            
        </div>
        <h2>Add New Operation</h2>
        <div class="user-info">
            Welcome, <?php echo $_SESSION['full_name']; ?> | 
            <a href="logout.php">Logout</a>
        </div>
    </div>
    
    <div class="menu">
        <a href="view_job.php?id=<?php echo $job_id; ?>">Back to Job</a>
        <a href="index.php">Back to Home</a>
    </div>
    
    <div class="content">
        <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
        
        <h3>Job: <?php echo $job['order_id']; ?> - <?php echo $job['product_model']; ?></h3>
        
        <!-- Job Status Indicator -->
        <div class="job-status <?php echo ($job['status'] == 'complete') ? 'job-complete' : 'job-pending'; ?>">
            Job Status: <?php echo strtoupper($job['status']); ?>
            (<?php echo $completed_count; ?>/22 operations completed)
        </div>
        
        <?php if ($completed_count < 22): ?>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label>Date & Time</label>
                    <div class="datetime-display">
                        <?php echo date('d-m-Y H:i:s'); ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Operation No*</label>
                    <input type="text" value="<?php echo $next_op_no; ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label>Operation Name*</label>
                    <select name="operation_name" required>
                        <option value="">Select Operation Name</option>
                        <?php foreach ($fixed_operations as $index => $op_name): ?>
                            <option value="<?php echo $op_name; ?>">
                                <?php echo ($index+1) . ". " . $op_name; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Produced Quantity*</label>
                    <input type="number" name="produced_quantity" value="<?php echo $job['quantity_produced']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>OK Quantity*</label>
                    <input type="number" name="ok_quantity" required>
                </div>
                
                <div class="form-group">
                    <label>Accepted Quantity</label>
                    <input type="number" name="accepted_quantity">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Rework Quantity</label>
                    <input type="number" name="rework_quantity">
                </div>
                
                <div class="form-group">
                    <label>Rejected Quantity</label>
                    <input type="number" name="rejected_quantity">
                </div>
                
                <div class="form-group">
                    <label>Checked By</label>
                    <input type="text" value="<?php echo $_SESSION['full_name']; ?>" readonly>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Operation Status*</label>
                    <select name="operation_status" class="status-select" required>
                        <option value="in_progress">In Progress</option>
                        <option value="completed" selected>Completed</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Remark</label>
                    <textarea name="remark"></textarea>
                </div>
            </div>
            
            <div class="form-row">
        <div class="form-group">
            <label>Attach Files (Multiple Allowed)</label>
            <input type="file" name="operation_files[]" id="operation_files" multiple>
            <div class="file-upload-info">
                Allowed file types: PDF, DOC, XLS, JPG, PNG, CSV (Max 5MB each)
            </div>
        </div>
    </div>
            
            <button type="submit">Add Operation</button>
        </form>
        <?php else: ?>
            <p class="success">All 22 operations for this job are already completed.</p>
        <?php endif; ?>
    </div>
</body>
</html>