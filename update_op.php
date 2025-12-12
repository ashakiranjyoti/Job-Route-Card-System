<?php
session_start();
include 'config.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get operation ID
if (!isset($_GET['op_id']) || !isset($_GET['job_id'])) {
    header("Location: index.php");
    exit();
}

$op_id = intval($_GET['op_id']);
$job_id = intval($_GET['job_id']);

// Get operation details
$operation = $conn->query("SELECT * FROM operations WHERE id=$op_id")->fetch_assoc();

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $produced_qty = intval($_POST['produced_quantity']);
    $ok_qty = intval($_POST['ok_quantity']);
    $accepted = intval($_POST['accepted_quantity']);
    $rework = intval($_POST['rework_quantity']);
    $rejected = intval($_POST['rejected_quantity']);
    $remark = sanitizeInput($_POST['remark']);
    $operation_status = sanitizeInput($_POST['operation_status']);
    
    // For Dispatch operation, always keep status as complete
    $status = ($operation['operation_name'] == 'Dispatch') ? 'complete' : 'pending';
    
    // Update operation details
    $sql = "UPDATE operations SET 
            produced_quantity=$produced_qty, 
            ok_quantity=$ok_qty, 
            accepted_quantity=$accepted, 
            rework_quantity=$rework, 
            rejected_quantity=$rejected, 
            remark='$remark', 
            operation_status='$operation_status', 
            status='$status', 
            checked_by='{$_SESSION['full_name']}'
            WHERE id=$op_id";
    
    if ($conn->query($sql)) {
        // Handle multiple file uploads
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
                $secure_name = "job_{$job_id}_op_{$operation['operation_no']}_" . time() . "_{$key}.$file_ext";
                $target_path = $upload_dir . $secure_name;
                
                if (move_uploaded_file($_FILES['operation_files']['tmp_name'][$key], $target_path)) {
                    // Save both original and secure names
                    $file_sql = "INSERT INTO operation_files 
                                (operation_id, file_path, original_name, secure_name)
                                VALUES (?, ?, ?, ?)";
                    $stmt = $conn->prepare($file_sql);
                    $stmt->bind_param("isss", $op_id, $target_path, $original_name, $secure_name);
                    if (!$stmt->execute()) {
                        $error = "Error saving file info: " . $conn->error;
                    }
                }
            }
        }
    }
}
        
        // Handle file deletions if requested
        if (!empty($_POST['delete_files'])) {
            foreach ($_POST['delete_files'] as $file_id) {
                $file_id = intval($file_id);
                $file_query = $conn->query("SELECT file_path FROM operation_files WHERE id=$file_id AND operation_id=$op_id");
                if ($file_query->num_rows > 0) {
                    $file = $file_query->fetch_assoc();
                    if (file_exists($file['file_path'])) {
                        unlink($file['file_path']);
                    }
                    $conn->query("DELETE FROM operation_files WHERE id=$file_id");
                }
            }
        }
        
        // Update job card status based on Dispatch operation
        updateJobStatus($job_id, $conn);
        
        header("Location: view_job.php?id=$job_id");
        exit();
    } else {
        $error = "Error updating operation: " . $conn->error;
    }
}


// Function to update job status
function updateJobStatus($job_id, $conn) {
    // Check if Dispatch operation exists and is complete
    $dispatch_result = $conn->query("SELECT COUNT(*) as dispatch_complete 
                                   FROM operations 
                                   WHERE job_card_id=$job_id 
                                   AND operation_name='Dispatch' 
                                   AND status='complete'");
    $dispatch_complete = $dispatch_result->fetch_assoc()['dispatch_complete'];
    
    if ($dispatch_complete > 0) {
        $conn->query("UPDATE job_cards SET status='complete' WHERE id=$job_id");
    } else {
        $conn->query("UPDATE job_cards SET status='pending' WHERE id=$job_id");
    }
}

// Get existing files for this operation
$existing_files = $conn->query("SELECT * FROM operation_files WHERE operation_id=$op_id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Operation</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .dispatch-warning {
            color: #e67e22;
            font-weight: bold;
            margin: 10px 0;
            padding: 10px;
            background-color: #fef5e7;
            border-radius: 4px;
        }
        .status-dropdown {
            width: 100%;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .status-badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-pending { background: #FFE0B2; color: #E65100; }
        .status-in_progress { background: #BBDEFB; color: #0D47A1; }
        .status-completed { background: #C8E6C9; color: #1B5E20; }
        .file-section {
            margin: 15px 0;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .file-list {
            margin: 10px 0;
        }
        .file-item {
            padding: 8px;
            background: #f5f5f5;
            border-radius: 4px;
            margin-bottom: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .file-info {
            display: flex;
            align-items: center;
        }
        .file-icon {
            margin-right: 8px;
            font-size: 1.2em;
        }
        .file-name {
            margin-right: 10px;
        }
        .file-upload-info {
            font-size: 0.9em;
            color: #666;
            margin-top: 5px;
        }
        .delete-checkbox {
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="login-logo" style="text-align: center;">
            
        </div>
        <h2>Update Operation</h2>
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
        
        <?php if ($operation['operation_name'] == 'Dispatch'): ?>
            <div class="dispatch-warning">
                Note: Dispatch operation cannot be marked as pending. Any updates will keep it as complete.
            </div>
        <?php endif; ?>
        
        <h3>Operation: <?php echo $operation['operation_name']; ?></h3>
        <p>Job: <?php echo $job_id; ?></p>
        
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label>Produced Quantity</label>
                    <input type="number" name="produced_quantity" value="<?php echo $operation['produced_quantity']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>OK Quantity*</label>
                    <input type="number" name="ok_quantity" value="<?php echo $operation['ok_quantity']; ?>" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Accepted Quantity</label>
                    <input type="number" name="accepted_quantity" value="<?php echo $operation['accepted_quantity']; ?>">
                </div>
                
                <div class="form-group">
                    <label>Rework Quantity</label>
                    <input type="number" name="rework_quantity" value="<?php echo $operation['rework_quantity']; ?>">
                </div>
                
                <div class="form-group">
                    <label>Rejected Quantity</label>
                    <input type="number" name="rejected_quantity" value="<?php echo $operation['rejected_quantity']; ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Operation Status*</label>
                    <select name="operation_status" class="status-dropdown" required>
                        <option value="in_progress" <?= $operation['operation_status'] == 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                        <option value="completed" <?= $operation['operation_status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                        <option value="pending" <?= $operation['operation_status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Checked By</label>
                    <input type="text" value="<?php echo $_SESSION['full_name']; ?>" readonly>
                </div>
            </div>
            
            <div class="form-group">
                <label>Remark</label>
                <textarea name="remark"><?php echo $operation['remark']; ?></textarea>
            </div>
            
            <div class="file-section">
                <h4>File Attachments</h4>
                
                <?php
    $existing_files = $conn->query("SELECT * FROM operation_files WHERE operation_id=$op_id");
    if ($existing_files->num_rows > 0): ?>
        <div class="file-list">
            <?php while($file = $existing_files->fetch_assoc()): ?>
                <div class="file-item">
                    <div class="file-info">
                        <span class="file-icon"><?php echo getFileIcon($file['original_name']); ?></span>
                        <span class="file-name">
                            <a href="download.php?id=<?php echo $file['id']; ?>" download>
                                <?php echo htmlspecialchars($file['original_name']); ?>
                            </a>
                        </span>
                    </div>
                    <label class="delete-checkbox">
                        <input type="checkbox" name="delete_files[]" value="<?php echo $file['id']; ?>"> Delete
                    </label>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No files attached to this operation.</p>
    <?php endif; ?>
    
    <div class="form-group">
        <label>Add More Files (Multiple Allowed)</label>
        <input type="file" name="operation_files[]" multiple>
        <div class="file-upload-info">
            Allowed types: PDF, DOC, XLS, JPG, PNG, CSV (Max 5MB each)
        </div>
    </div>
            </div>
            
            <button type="submit">Update Operation</button>
        </form>
    </div>
</body>
</html>

<?php
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

<style>
.file-section {
    margin: 15px 0;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
}
.file-list {
    margin: 10px 0;
}
.file-item {
    padding: 8px;
    background: #f5f5f5;
    border-radius: 4px;
    margin-bottom: 5px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.file-info {
    display: flex;
    align-items: center;
}
.file-icon {
    margin-right: 8px;
}
.file-upload-info {
    font-size: 0.9em;
    color: #666;
    margin-top: 5px;
}
</style>