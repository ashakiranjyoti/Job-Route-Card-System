<?php
session_start();
include 'config.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get job ID
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$job_id = intval($_GET['id']);

// Check if job exists
$job = $conn->query("SELECT * FROM job_cards WHERE id=$job_id")->fetch_assoc();
if (!$job) {
    header("Location: index.php");
    exit();
}

// Delete operations first (to maintain referential integrity)
$conn->query("DELETE FROM operations WHERE job_card_id=$job_id");

// Then delete the job card
if ($conn->query("DELETE FROM job_cards WHERE id=$job_id")) {
    $_SESSION['message'] = "Job card deleted successfully";
} else {
    $_SESSION['error'] = "Error deleting job card: " . $conn->error;
}

header("Location: index.php");
exit();
?>