<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("HTTP/1.0 401 Unauthorized");
    exit("Unauthorized access");
}

// Get file ID
$file_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get file info from database
$stmt = $conn->prepare("SELECT * FROM operation_files WHERE id=?");
$stmt->bind_param("i", $file_id);
$stmt->execute();
$file = $stmt->get_result()->fetch_assoc();

// Check if file exists
if (!$file || !file_exists($file['file_path'])) {
    header("HTTP/1.0 404 Not Found");
    exit("File not found");
}

// Set headers for download
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.basename($file['original_name']).'"');
header('Content-Length: ' . filesize($file['file_path']));
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');

// Output the file
readfile($file['file_path']);
exit;
?>