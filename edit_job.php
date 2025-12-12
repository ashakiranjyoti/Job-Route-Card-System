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
$job = $conn->query("SELECT * FROM job_cards WHERE id=$job_id")->fetch_assoc();

// Check if job exists
if (!$job) {
    header("Location: index.php");
    exit();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_model = sanitizeInput($_POST['product_model']);
    $product_desc = sanitizeInput($_POST['product_desc']);
    $customer_name = sanitizeInput($_POST['customer_name']);
    $po_number = sanitizeInput($_POST['po_number']);
    $po_date = sanitizeInput($_POST['po_date']);
    $quantity = intval($_POST['quantity_produced']);
    $target_date = sanitizeInput($_POST['target_date']);
    
    $sql = "UPDATE job_cards SET 
            product_model='$product_model', 
            product_desc='$product_desc', 
            customer_name='$customer_name',
            po_number='$po_number', 
            po_date='$po_date', 
            quantity_produced=$quantity, 
            target_date='$target_date'
            WHERE id=$job_id";
    
    if ($conn->query($sql)) {
        header("Location: view_job.php?id=$job_id");
        exit();
    } else {
        $error = "Error updating job card: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Job Card</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
    <div class="login-logo" style="text-align: center;">
    
</div>
        <h2>Edit Job Card</h2>
        <div class="user-info">
            Welcome, <?php echo $_SESSION['full_name']; ?> | 
            <a href="logout.php">Logout</a>
        </div>
    </div>
    
    <div class="menu">
        <a href="index.php">Back to Home</a>
        <a href="view_job.php?id=<?php echo $job_id; ?>">Back to Job</a>
    </div>
    
    <div class="content">
        <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Order ID</label>
                <input type="text" value="<?php echo $job['order_id']; ?>" readonly>
            </div>
            
            <div class="form-group">
                <label>Product Model*</label>
                <input type="text" name="product_model" value="<?php echo $job['product_model']; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Product Description</label>
                <textarea name="product_desc"><?php echo $job['product_desc']; ?></textarea>
            </div>
            
            <div class="form-group">
                <label>Customer Name*</label>
                <input type="text" name="customer_name" value="<?php echo $job['customer_name']; ?>" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>PO Number</label>
                    <input type="text" name="po_number" value="<?php echo $job['po_number']; ?>">
                </div>
                
                <div class="form-group">
                    <label>PO Date</label>
                    <input type="date" name="po_date" value="<?php echo $job['po_date']; ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label>Quantity Produced*</label>
                <input type="number" name="quantity_produced" value="<?php echo $job['quantity_produced']; ?>" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Start Date</label>
                    <input type="date" value="<?php echo $job['start_date']; ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label>Target Date*</label>
                    <input type="date" name="target_date" value="<?php echo $job['target_date']; ?>" required>
                </div>
                
            </div>
            
            <button type="submit">Update Job Card</button>
        </form>
    </div>
</body>
</html>