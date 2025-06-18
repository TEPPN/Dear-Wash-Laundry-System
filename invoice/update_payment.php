<?php
include '../connect/connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['payment_status'])) {
    $order_id = intval($_POST['order_id']);
    $payment_status = $_POST['payment_status'];

    if (in_array($payment_status, ['LUNAS', 'BELUM_LUNAS'])) {
        $stmt = $conn->prepare("UPDATE `order` SET payment_status = ? WHERE order_id = ?");
        $stmt->bind_param("si", $payment_status, $order_id);
        $stmt->execute();
    }
}

header('Location: invoice.php');
exit;
