<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../connect/connect.php';

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
    $valid_statuses = ['UNRECEIVED', 'RECEIVED', 'PROGRESS', 'DONE'];

    if (in_array($new_status, $valid_statuses)) {
        $stmt = $conn->prepare("UPDATE `order` SET status = ? WHERE order_id = ?");
        $stmt->bind_param("si", $new_status, $order_id);
        $stmt->execute();

        // Redirect to prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}



// Get all orders
$sql = "SELECT * FROM `order` ORDER BY order_id DESC";
$result = $conn->query($sql);

// Group orders by status
$unreceived_orders = [];
$received_orders = [];
$progress_orders = [];
$done_orders = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['order_details'] = json_decode($row['order_details'], true);
        switch ($row['status']) {
            case 'UNRECEIVED':
                $unreceived_orders[] = $row;
                break;
            case 'RECEIVED':
                $received_orders[] = $row;
                break;
            case 'PROGRESS':
                $progress_orders[] = $row;
                break;
            case 'DONE':
                $done_orders[] = $row;
                break;
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
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <div>
            <a href="../profile/profile.php">Back</a>
        </div>
        <div class="header">
            <h1>Laundry Tracking System</h1>
            <div style="margin: 10px 0;">
                <a href="../invoice/invoice.php" style="background-color: #007BFF; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Invoice</a>
            </div>
            <div class="user-status">
                <?php if ($is_admin): ?>
                    <span>Logged in as Admin</span>
                <?php else: ?>
                    <span>Logged in as User</span>
                <?php endif; ?>
            </div>
        </div>

        <div class="board">
            <!-- UNRECEIVED COLUMN -->
            <div class="column received">
                <div class="column-header">UNRECEIVED (<?php echo count($unreceived_orders); ?>)</div>
                <?php foreach ($unreceived_orders as $order): ?>
                    <div class="card" onclick="showOrderDetails(<?php echo $order['order_id']; ?>, 'UNRECEIVED')">
                        <div class="card-title">
                            <?php echo $order['order_id'] . ' - ' . htmlspecialchars($order['customer_name']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (count($unreceived_orders) == 0): ?>
                    <div class="card">
                        <div class="card-detail">No orders in this section</div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- RECEIVED COLUMN -->
            <div class="column received">
                <div class="column-header">RECEIVED (<?php echo count($received_orders); ?>)</div>
                <?php foreach ($received_orders as $order): ?>
                    <div class="card" onclick="showOrderDetails(<?php echo $order['order_id']; ?>, 'RECEIVED')">
                        <div class="card-title">
                            <?php echo $order['order_id'] . ' - ' . htmlspecialchars($order['customer_name']); ?>
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
                    <div class="card" onclick="showOrderDetails(<?php echo $order['order_id']; ?>, 'PROGRESS')">
                        <div class="card-title">
                            <?php echo $order['order_id'] . ' - ' . htmlspecialchars($order['customer_name']); ?>
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
                    <div class="card" onclick="showOrderDetails(<?php echo $order['order_id']; ?>, 'DONE')">
                        <div class="card-title">
                            <?php echo $order['order_id'] . ' - ' . htmlspecialchars($order['customer_name']); ?>
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

        <!-- Modal Overlay -->
        <div class="modal-overlay" id="modalOverlay"></div>

        <!-- Modal for Order Details -->
        <div class="order-modal" id="orderModal">
            <div class="modal-header">
                <div class="modal-title" id="modalTitle">Order Details</div>
                <span class="close-modal" onclick="closeOrderDetails()">&times;</span>
            </div>
            <div class="modal-content" id="modalContent"></div>
        </div>
    </div>

    <script>
        const allOrders = {
            UNRECEIVED: <?php echo json_encode($unreceived_orders); ?>,
            RECEIVED: <?php echo json_encode($received_orders); ?>,
            PROGRESS: <?php echo json_encode($progress_orders); ?>,
            DONE: <?php echo json_encode($done_orders); ?>
        };
        const isAdmin = <?php echo json_encode($is_admin); ?>;
    </script>
    <script src="track.js"></script>
</body>

</html>