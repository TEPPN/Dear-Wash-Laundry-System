<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../connect/connect.php';

echo "<pre>SESSION: ";
print_r($_SESSION);
echo "</pre>";
// Check if user is admin
$is_admin = false;
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {
    $is_admin = true;
}

// Handle status updates (only for admins)
if ($is_admin && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    
    // Valid statuses
    $valid_statuses = ['RECEIVED', 'PROGRESS', 'DONE'];
    
    if (in_array($new_status, $valid_statuses)) {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
        $stmt->bind_param("si", $new_status, $order_id);
        $stmt->execute();
        
        // Redirect to prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Get all orders
$sql = "SELECT * FROM orders ORDER BY order_id DESC";
$result = $conn->query($sql);

// Group orders by status
$received_orders = [];
$progress_orders = [];
$done_orders = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Decode the JSON order details
        $row['order_details'] = json_decode($row['order_details'], true);
        
        switch ($row['status']) {
            case 'PROGRESS':
                $progress_orders[] = $row;
                break;
            case 'DONE':
                $done_orders[] = $row;
                break;
            default:
                // If status is not set or is RECEIVED, default to RECEIVED
                $received_orders[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laundry Tracking System</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            margin: 0;
            padding: 20px;
            background-color: #f0f2f5;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .board {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            padding-bottom: 10px;
        }
        .column {
            background-color: #ebecf0;
            border-radius: 10px;
            width: 300px;
            min-width: 300px;
            padding: 10px;
            height: fit-content;
        }
        .column-header {
            font-weight: bold;
            padding: 10px;
            text-align: center;
            margin-bottom: 10px;
            color: #172b4d;
        }
        .received .column-header {
            background-color: #ffeba6;
            border-radius: 5px;
        }
        .progress .column-header {
            background-color: #aee5ff;
            border-radius: 5px;
        }
        .done .column-header {
            background-color: #c3e6cb;
            border-radius: 5px;
        }
        .card {
            background-color: white;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12);
            cursor: pointer;
        }
        .card-title {
            font-weight: bold;
            margin-bottom: 8px;
            text-align: center;
        }
        .card-detail {
            margin-bottom: 5px;
            color: #5e6c84;
            font-size: 14px;
        }
        .card-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        button {
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            margin-left: 5px;
        }
        .move-button {
            background-color: #0079bf;
            color: white;
        }
        .user-status {
            margin-left: auto;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .hidden {
            display: none;
        }
        .order-details {
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            margin-top: 10px;
            font-size: 14px;
        }
        .service-type {
            font-weight: bold;
            margin-top: 10px;
        }
        .service-items {
            margin-left: 15px;
        }
        .total-price {
            font-weight: bold;
            margin-top: 10px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Laundry Tracking System</h1>
            <div class="user-status">
                <?php if ($is_admin): ?>
                    <span>Logged in as Admin</span>
                <?php else: ?>
                    <span>Logged in as User</span>
                <?php endif; ?>
                <a href="logout.php"><button>Logout</button></a>
            </div>
        </div>

        <div class="board">
            <!-- RECEIVED COLUMN -->
            <div class="column received">
                <div class="column-header">RECEIVED (<?php echo count($received_orders); ?>)</div>
                <?php foreach ($received_orders as $order): ?>
                    <div class="card" onclick="toggleDetails('received-<?php echo $order['order_id']; ?>')">
                        <div class="card-title">Order #<?php echo $order['order_id']; ?></div>
                        
                        <div id="received-<?php echo $order['order_id']; ?>" class="order-details hidden">
                            <div class="card-detail">Customer: <?php echo $order['customer_name']; ?></div>
                            <div class="card-detail">Date: <?php echo date('M d, Y', strtotime($order['created_at'] ?? date('Y-m-d'))); ?></div>
                            
                            <?php if (isset($order['order_details']['details'])): ?>
                                <?php foreach ($order['order_details']['details'] as $detail): ?>
                                    <div class="service-type"><?php echo $detail['type']; ?></div>
                                    <div class="service-items">
                                        <?php foreach ($detail['items'] as $item): ?>
                                            <div><?php echo $item['item']; ?> x <?php echo $item['quantity']; ?> 
                                                = Rp <?php echo number_format($item['item_total'], 0, ',', '.'); ?></div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endforeach; ?>
                                <div class="total-price">Total: Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></div>
                            <?php else: ?>
                                <div class="card-detail">Total: Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></div>
                            <?php endif; ?>
                            
                            <?php if ($is_admin): ?>
                                <div class="card-actions">
                                    <form method="post">
                                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                        <input type="hidden" name="status" value="PROGRESS">
                                        <button type="submit" name="update_status" class="move-button">Move to Progress</button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (count($received_orders) == 0): ?>
                    <div class="card">
                        <div class="card-detail">No orders in this section</div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- PROGRESS COLUMN -->
            <div class="column progress">
                <div class="column-header">PROGRESS (<?php echo count($progress_orders); ?>)</div>
                <?php foreach ($progress_orders as $order): ?>
                    <div class="card" onclick="toggleDetails('progress-<?php echo $order['order_id']; ?>')">
                        <div class="card-title">Order #<?php echo $order['order_id']; ?></div>
                        
                        <div id="progress-<?php echo $order['order_id']; ?>" class="order-details hidden">
                            <div class="card-detail">Customer: <?php echo $order['customer_name']; ?></div>
                            <div class="card-detail">Date: <?php echo date('M d, Y', strtotime($order['created_at'] ?? date('Y-m-d'))); ?></div>
                            
                            <?php if (isset($order['order_details']['details'])): ?>
                                <?php foreach ($order['order_details']['details'] as $detail): ?>
                                    <div class="service-type"><?php echo $detail['type']; ?></div>
                                    <div class="service-items">
                                        <?php foreach ($detail['items'] as $item): ?>
                                            <div><?php echo $item['item']; ?> x <?php echo $item['quantity']; ?> 
                                                = Rp <?php echo number_format($item['item_total'], 0, ',', '.'); ?></div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endforeach; ?>
                                <div class="total-price">Total: Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></div>
                            <?php else: ?>
                                <div class="card-detail">Total: Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></div>
                            <?php endif; ?>
                            
                            <?php if ($is_admin): ?>
                                <div class="card-actions">
                                    <form method="post">
                                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                        <input type="hidden" name="status" value="RECEIVED">
                                        <button type="submit" name="update_status" class="move-button">Move to Received</button>
                                    </form>
                                    <form method="post">
                                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                        <input type="hidden" name="status" value="DONE">
                                        <button type="submit" name="update_status" class="move-button">Move to Done</button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (count($progress_orders) == 0): ?>
                    <div class="card">
                        <div class="card-detail">No orders in this section</div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- DONE COLUMN -->
            <div class="column done">
                <div class="column-header">DONE (<?php echo count($done_orders); ?>)</div>
                <?php foreach ($done_orders as $order): ?>
                    <div class="card" onclick="toggleDetails('done-<?php echo $order['order_id']; ?>')">
                        <div class="card-title">Order #<?php echo $order['order_id']; ?></div>
                        
                        <div id="done-<?php echo $order['order_id']; ?>" class="order-details hidden">
                            <div class="card-detail">Customer: <?php echo $order['customer_name']; ?></div>
                            <div class="card-detail">Date: <?php echo date('M d, Y', strtotime($order['created_at'] ?? date('Y-m-d'))); ?></div>
                            
                            <?php if (isset($order['order_details']['details'])): ?>
                                <?php foreach ($order['order_details']['details'] as $detail): ?>
                                    <div class="service-type"><?php echo $detail['type']; ?></div>
                                    <div class="service-items">
                                        <?php foreach ($detail['items'] as $item): ?>
                                            <div><?php echo $item['item']; ?> x <?php echo $item['quantity']; ?> 
                                                = Rp <?php echo number_format($item['item_total'], 0, ',', '.'); ?></div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endforeach; ?>
                                <div class="total-price">Total: Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></div>
                            <?php else: ?>
                                <div class="card-detail">Total: Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></div>
                            <?php endif; ?>
                            
                            <?php if ($is_admin): ?>
                                <div class="card-actions">
                                    <form method="post">
                                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                        <input type="hidden" name="status" value="PROGRESS">
                                        <button type="submit" name="update_status" class="move-button">Move to Progress</button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (count($done_orders) == 0): ?>
                    <div class="card">
                        <div class="card-detail">No orders in this section</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Function to toggle order details visibility
        function toggleDetails(detailId) {
            const detailElement = document.getElementById(detailId);
            if (detailElement) {
                detailElement.classList.toggle('hidden');
            }
        }
    </script>
</body>
</html>