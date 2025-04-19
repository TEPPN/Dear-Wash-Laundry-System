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
            position: relative;
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
            position: relative;
        }
        
        /* New Floating Window Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 100;
        }
        
        .order-modal {
            display: none;
            position: absolute;
            background-color: white;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            z-index: 101;
            padding: 20px;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
        
        .modal-title {
            font-size: 18px;
            font-weight: bold;
        }
        
        .close-modal {
            cursor: pointer;
            font-size: 20px;
            color: #666;
        }
        
        .service-type {
            font-weight: bold;
            margin-top: 10px;
        }
        
        .service-items {
            margin-left: 15px;
            margin-bottom: 10px;
        }
        
        .total-price {
            font-weight: bold;
            margin-top: 15px;
            text-align: right;
            font-size: 16px;
        }
        
        .modal-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }
        
        /* Navigation Styles */
        .nav-tabs {
            display: flex;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }
        
        .nav-tab {
            padding: 10px 20px;
            text-align: center;
            cursor: pointer;
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            border-bottom: none;
            border-radius: 5px 5px 0 0;
            margin-right: 5px;
        }
        
        .nav-tab.active {
            background-color: #0079bf;
            color: white;
            border-color: #0079bf;
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

        <div class="nav-tabs">
            <div class="nav-tab" onclick="location.href='index.php'">Home</div>
            <div class="nav-tab active">Progress</div>
            <div class="nav-tab" onclick="location.href='create_order.php'">Create an Order</div>
        </div>

        <div class="board">
            <!-- RECEIVED COLUMN -->
            <div class="column received">
                <div class="column-header">RECEIVED (<?php echo count($received_orders); ?>)</div>
                <?php foreach ($received_orders as $order): ?>
                    <div class="card" onclick="showOrderDetails(<?php echo $order['order_id']; ?>, 'RECEIVED')">
                        <div class="card-title">Order #<?php echo $order['order_id']; ?></div>
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
                    <div class="card" onclick="showOrderDetails(<?php echo $order['order_id']; ?>, 'PROGRESS')">
                        <div class="card-title">Order #<?php echo $order['order_id']; ?></div>
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
                    <div class="card" onclick="showOrderDetails(<?php echo $order['order_id']; ?>, 'DONE')">
                        <div class="card-title">Order #<?php echo $order['order_id']; ?></div>
                    </div>
                <?php endforeach; ?>
                <?php if (count($done_orders) == 0): ?>
                    <div class="card">
                        <div class="card-detail">No orders in this section</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Modal Overlay -->
        <div class="modal-overlay" id="modalOverlay"></div>

        <!-- Modal for Order Details -->
        <div class="order-modal" id="orderModal">
            <div class="modal-header">
                <div class="modal-title" id="modalTitle">Order Details</div>
                <span class="close-modal" onclick="closeOrderDetails()">&times;</span>
            </div>
            <div class="modal-content" id="modalContent">
                <!-- Content will be dynamically inserted here -->
            </div>
        </div>
    </div>

    <script>
        // Store all orders data in JavaScript
        const allOrders = {
            RECEIVED: <?php echo json_encode($received_orders); ?>,
            PROGRESS: <?php echo json_encode($progress_orders); ?>,
            DONE: <?php echo json_encode($done_orders); ?>
        };
        
        // Function to show order details in a modal
        function showOrderDetails(orderId, status) {
            const orderList = allOrders[status];
            let order = null;
            
            // Find the order with matching ID
            for (let i = 0; i < orderList.length; i++) {
                if (orderList[i].order_id == orderId) {
                    order = orderList[i];
                    break;
                }
            }
            
            if (!order) return;
            
            // Set modal title
            document.getElementById('modalTitle').textContent = `Order #${order.order_id}`;
            
            // Generate content for modal
            let content = `
                <div class="card-detail">Customer: ${order.customer_name}</div>
                <div class="card-detail">Date: ${formatDate(order.created_at)}</div>
            `;
            
            // Add order details if available
            if (order.order_details && order.order_details.details) {
                order.order_details.details.forEach(detail => {
                    content += `<div class="service-type">${detail.type}</div>`;
                    content += `<div class="service-items">`;
                    
                    detail.items.forEach(item => {
                        content += `<div>${item.item} x ${item.quantity} = Rp ${numberFormat(item.item_total)}</div>`;
                    });
                    
                    content += `</div>`;
                });
                
                content += `<div class="total-price">Total: Rp ${numberFormat(order.total_amount)}</div>`;
            } else {
                content += `<div class="card-detail">Total: Rp ${numberFormat(order.total_amount)}</div>`;
            }
            
            // Add action buttons for admin
            <?php if ($is_admin): ?>
            content += `<div class="modal-actions">`;
            
            if (status === "RECEIVED") {
                content += `
                    <form method="post">
                        <input type="hidden" name="order_id" value="${order.order_id}">
                        <input type="hidden" name="status" value="PROGRESS">
                        <button type="submit" name="update_status" class="move-button">Move to Progress</button>
                    </form>
                `;
            } else if (status === "PROGRESS") {
                content += `
                    <form method="post">
                        <input type="hidden" name="order_id" value="${order.order_id}">
                        <input type="hidden" name="status" value="RECEIVED">
                        <button type="submit" name="update_status" class="move-button">Move to Received</button>
                    </form>
                    <form method="post">
                        <input type="hidden" name="order_id" value="${order.order_id}">
                        <input type="hidden" name="status" value="DONE">
                        <button type="submit" name="update_status" class="move-button">Move to Done</button>
                    </form>
                `;
            } else if (status === "DONE") {
                content += `
                    <form method="post">
                        <input type="hidden" name="order_id" value="${order.order_id}">
                        <input type="hidden" name="status" value="PROGRESS">
                        <button type="submit" name="update_status" class="move-button">Move to Progress</button>
                    </form>
                `;
            }
            
            content += `</div>`;
            <?php endif; ?>
            
            // Set content and show modal
            document.getElementById('modalContent').innerHTML = content;
            document.getElementById('modalOverlay').style.display = 'block';
            document.getElementById('orderModal').style.display = 'block';
        }
        
        // Function to close order details modal
        function closeOrderDetails() {
            document.getElementById('modalOverlay').style.display = 'none';
            document.getElementById('orderModal').style.display = 'none';
        }
        
        // Close modal when clicking outside
        document.getElementById('modalOverlay').addEventListener('click', closeOrderDetails);
        
        // Helper function to format date
        function formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            return date.toLocaleDateString('en-US', options);
        }
        
        // Helper function to format numbers
        function numberFormat(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }
    </script>
</body>
</html>