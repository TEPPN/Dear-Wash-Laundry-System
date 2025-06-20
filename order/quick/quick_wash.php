<?php
require '../../login/check.php';
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once "../../connect/connect.php";

$customer_name = $_POST['customer_name'];
$order_address = $_POST['customer_address'];
$order_type = "quick wash";

$wash_only_weight = isset($_POST['wash_only_weight']) ? (float)$_POST['wash_only_weight'] : 0;
$wash_dry_weight = isset($_POST['wash_dry_weight']) ? (float)$_POST['wash_dry_weight'] : 0;
$wash_fold_weight = isset($_POST['wash_fold_weight']) ? (float)$_POST['wash_fold_weight'] : 0;

$order_details = json_encode([
    'wash_only_weight' => $wash_only_weight,
    'wash_dry_weight' => $wash_dry_weight,
    'wash_fold_weight' => $wash_fold_weight
]);

$total_amount = isset($_POST['calculated_total']) ? (int)$_POST['calculated_total'] : 0;
$time_received = date('Y-m-d H:i:s');
$time_finished = date('Y-m-d H:i:s', strtotime('+3 hours'));
$status = "UNRECEIVED";

$sql = "INSERT INTO `order` (
        customer_name, order_type, order_details, time_received, time_finished,order_address, total_amount, status
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssssssis",
    $customer_name,
    $order_type,
    $order_details,
    $time_received,
    $time_finished,
    $order_address,
    $total_amount,
    $status
);

if ($stmt->execute()) {
    header("Location: ../../track/track.php");
} else {
    echo "Error: " . $stmt->error;
}
