<?php
include '../connect/connect.php'; 

$result = $conn->query("SELECT * FROM orders");

echo "<h2>All Orders</h2>";
echo "<table border='1'>";
echo "<tr><th>Customer Name</th><th>Order Details</th></tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['customer_name'] . "</td>";
    
    // Decode JSON data
    $order_details = json_decode($row['order_details'], true);

    echo "<td>";
    foreach ($order_details as $order) {
        echo "<strong>Type:</strong> " . $order['type'] . "<br>";
        foreach ($order['items'] as $item) {
            echo "• " . $item['item'] . " (x" . $item['quantity'] . ")<br>";
        }
        echo "<br>";
    }
    echo "</td>";

    echo "</tr>";
}
echo "</table>";
?>
