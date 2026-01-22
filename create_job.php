<?php 
session_start();
include 'config.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Generate default order ID (format: YYYYMMDDNN)
$date_part = date('Ymd');
$sql = "SELECT MAX(CAST(SUBSTRING(order_id, 9) AS UNSIGNED)) as last_num 
        FROM job_cards 
        WHERE order_id LIKE '$date_part%'";
$result = $conn->query($sql);
$last_num = $result->fetch_assoc()['last_num'];
$next_num = ($last_num) ? $last_num + 1 : 1;
$order_id = $date_part . str_pad($next_num, 2, '0', STR_PAD_LEFT);

// Set default start date to today
$start_date = date('Y-m-d');

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = sanitizeInput($_POST['order_id']);
    $product_model = sanitizeInput($_POST['product_model']);
    $product_desc = sanitizeInput($_POST['product_desc']);
    $customer_name = sanitizeInput($_POST['customer_name']);
    $po_number = sanitizeInput($_POST['po_number']);
    $po_date = sanitizeInput($_POST['po_date']);
    $quantity = intval($_POST['quantity_produced']);
    $target_date = sanitizeInput($_POST['target_date']);
    $created_by = $_SESSION['full_name']; // Get logged-in user's name
    
    $sql = "INSERT INTO job_cards (order_id, product_model, product_desc, customer_name, 
            po_number, po_date, quantity_produced, start_date, target_date, created_by)
            VALUES ('$order_id', '$product_model', '$product_desc', '$customer_name',
            '$po_number', '$po_date', $quantity, '$start_date', '$target_date', '$created_by')";
    
    if ($conn->query($sql)) {
        $job_id = $conn->insert_id;
        header("Location: view_job.php?id=$job_id");
        exit();
    } else {
        $error = "Error creating job card: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Job Card</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
    <div class="login-logo" style="text-align: center;">
    
</div>
        <h2>Create New Job Card</h2>
        <div class="user-info">
            Welcome, <?php echo $_SESSION['full_name']; ?> | 
            <a href="logout.php">Logout</a>
        </div>
    </div>
    
    <div class="menu">
        <a href="index.php">Back to Home</a>
    </div>
    
    <div class="content">
        <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Order ID*</label>
                <input type="text" name="order_id" value="<?php echo $order_id; ?>" readonly required>
            </div>
            
            <div class="form-group">
                <label>Product Model*</label>
                <input type="text" name="product_model" required>
            </div>
            
            <div class="form-group">
                <label>Product Description</label>
                <textarea name="product_desc"></textarea>
            </div>
            
            <div class="form-group">
                <label>Customer Name*</label>
                <input type="text" name="customer_name" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>PO Number</label>
                    <input type="text" name="po_number">
                </div>
                
                <div class="form-group">
                    <label>PO Date</label>
                    <input type="date" name="po_date">
                </div>
            </div>
            
            <div class="form-group">
                <label>Quantity Produced*</label>
                <input type="number" name="quantity_produced" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Start Date*</label>
                    <input type="date" name="start_date" value="<?php echo $start_date; ?>" readonly required>
                </div>
                
                <div class="form-group">
                    <label>Target Date*</label>
                    <input type="date" name="target_date" required>
                </div>
                <div class="form-group">
    <label>Created By</label>
    <input type="text" value="<?php echo $_SESSION['full_name']; ?>" readonly>
    <!-- Hidden field if you want to submit it -->
    <input type="hidden" name="created_by" value="<?php echo $_SESSION['full_name']; ?>">
</div>
            </div>
            
            <button type="submit">Create Job Card</button>
        </form>
    </div>
</body>

</html>
