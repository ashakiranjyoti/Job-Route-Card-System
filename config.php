<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$dbname = "job_route_system";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// Set timezone
date_default_timezone_set('Asia/Kolkata');

// Common functions
function sanitizeInput($data) {
    global $conn;
    return mysqli_real_escape_string($conn, trim($data));
}
?>