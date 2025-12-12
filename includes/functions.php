<?php
// Additional functions that can be included where needed

function getJobCards($conn, $limit = 10) {
    $sql = "SELECT * FROM job_cards ORDER BY created_at DESC LIMIT $limit";
    $result = $conn->query($sql);
    return $result;
}

function getJobDetails($conn, $job_id) {
    $job_id = intval($job_id);
    $sql = "SELECT * FROM job_cards WHERE id=$job_id";
    $result = $conn->query($sql);
    return $result->fetch_assoc();
}

function getOperations($conn, $job_id) {
    $job_id = intval($job_id);
    $sql = "SELECT * FROM operations WHERE job_card_id=$job_id ORDER BY operation_no";
    return $conn->query($sql);
}

function addOperation($conn, $job_id, $data) {
    $job_id = intval($job_id);
    $op_no = intval($data['operation_no']);
    $op_name = sanitizeInput($data['operation_name']);
    $produced = intval($data['produced_quantity']);
    $checked_by = $_SESSION['full_name'];
    
    $sql = "INSERT INTO operations (job_card_id, operation_no, operation_name, 
            produced_quantity, checked_by, operation_date)
            VALUES ($job_id, $op_no, '$op_name', $produced, '$checked_by', CURDATE())";
    
    return $conn->query($sql);
}
?>