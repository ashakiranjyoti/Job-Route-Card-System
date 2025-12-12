<?php
session_start();
include 'config.php';

// Only allow admin users
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Check if operation ID and job ID are provided
if (!isset($_GET['op_id']) || !isset($_GET['job_id'])) {
    header("Location: index.php");
    exit();
}

$op_id = intval($_GET['op_id']);
$job_id = intval($_GET['job_id']);

// Delete the operation
$sql = "DELETE FROM operations WHERE id=$op_id";
if ($conn->query($sql)) {
    // Re-sequence operation numbers after deletion
    $conn->query("SET @count = 0");
    $conn->query("UPDATE operations SET operation_no = @count:= @count + 1 
                 WHERE job_card_id=$job_id ORDER BY operation_no");
    
    // Update job status in case Dispatch was deleted
    updateJobStatus($job_id, $conn);
    
    header("Location: view_job.php?id=$job_id");
    exit();
} else {
    die("Error deleting operation: " . $conn->error);
}

function updateJobStatus($job_id, $conn) {
    // Check if Dispatch operation exists and is complete
    $dispatch_result = $conn->query("SELECT COUNT(*) as dispatch_complete 
                                   FROM operations 
                                   WHERE job_card_id=$job_id 
                                   AND operation_name='Dispatch' 
                                   AND operation_status='completed'");
    $dispatch_complete = $dispatch_result->fetch_assoc()['dispatch_complete'];
    
    if ($dispatch_complete > 0) {
        $conn->query("UPDATE job_cards SET status='complete' WHERE id=$job_id");
    } else {
        $conn->query("UPDATE job_cards SET status='pending' WHERE id=$job_id");
    }
}
?>