<?php
require '../../login/check.php';
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once "../../connect/connect.php";


$customerName = $conn->real_escape_string($_POST['customer_name']);
$orderType = "regular";

$orderDetails = [];

if (!empty($_POST['complete_wash_weight'])) {
    $orderDetails['complete_wash'] = [
        'weight' => floatval($_POST['complete_wash_weight']),
        'quantity' => intval($_POST['complete_wash_quantity'] ?? 0)
    ];
}

if (!empty($_POST['ironing_weight'])) {
    $orderDetails['ironing'] = [
        'weight' => floatval($_POST['ironing_weight']),
        'quantity' => intval($_POST['ironing_quantity'] ?? 0)
    ];
}
if (!empty($_POST['bed_sheet_weight'])) {
    $orderDetails['bed_sheet'] = [
        'weight' => floatval($_POST['bed_sheet_weight']),
        'quantity' => intval($_POST['bed_sheet_quantity'] ?? 0)
    ];
}

$multiItems = ['bed_cover', 'dry_clean', 'wash_separate'];
foreach ($multiItems as $type) {
    if (!empty($_POST[$type])) {
        $items = $_POST[$type];
        $quantities = $_POST[$type . '_quantity'];
        $data = [];

        foreach ($items as $index => $item) {
            $data[] = [
                'item' => $item,
                'quantity' => intval($quantities[$index])
            ];
        }

        $orderDetails[$type] = $data;
    }
}

$timeReceived = date("Y-m-d H:i:s");
$daysToAdd = 3;
$multiplier = 1;

if ($_POST['finish_time'] === 'plus50') {
    $daysToAdd = 2;
    $multiplier = 1.5;
} elseif ($_POST['finish_time'] === 'plus100') {
    $daysToAdd = 1;
    $multiplier = 2;
}

$timeFinished = date("Y-m-d H:i:s", strtotime("+$daysToAdd days"));

$totalAmount = isset($_POST['calculated_total']) ? intval($_POST['calculated_total']) : 0;

$status = "UNRECEIVED";

$orderDetailsJson = json_encode($orderDetails, JSON_UNESCAPED_UNICODE);

$stmt = $conn->prepare("INSERT INTO `order` (customer_name, order_type, order_details, time_received, time_finished, total_amount, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssis", $customerName, $orderType, $orderDetailsJson, $timeReceived, $timeFinished, $totalAmount, $status);

if ($stmt->execute()) {
    header("Location: ../../track/track.php");
} else {
    echo "Error: " . $stmt->error;
}
