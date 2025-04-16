<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../connect/connect.php'; // Ensure database connection

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page or handle appropriately
    echo "<p style='color:red;'>You must be logged in to place an order.</p>";
    exit;
}

$user_id = $_SESSION['user_id'];
echo ($user_id);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Debug what we actually received
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    $customer_name = $_POST['customer_name'] ?? '';
    
    // Check if necessary arrays exist and initialize them if not
    $order_types = $_POST['order_types'] ?? [];
    $items = $_POST['items'] ?? [];
    $quantities = $_POST['quantities'] ?? [];
    
    // Define pricing
    $pricing = [
        'Complete Wash' => [
            'Shirt' => 6000,
            'Pants' => 8000,
            'Jacket' => 10000,
            'Other' => 7000
        ],
        'Dry Cleaning' => [
            'Shirt' => 7000,
            'Pants' => 9000,
            'Jacket' => 12000,
            'Other' => 8000
        ],
        'Ironing' => [
            'Shirt' => 4000,
            'Pants' => 5000,
            'Jacket' => 6000,
            'Other' => 4500
        ],
        'Other' => [
            'Shirt' => 5000,
            'Pants' => 6000,
            'Jacket' => 8000,
            'Other' => 5000
        ]
    ];
    
    $order_details = [];
    $total_price = 0;
    
    // Only process if we have order types
    if (is_array($order_types) && count($order_types) > 0) {
        // Loop through order types
        for ($i = 0; $i < count($order_types); $i++) {
            $order_type = $order_types[$i];
            $order_details[] = [
                "type" => $order_type,
                "items" => [],
                "subtotal" => 0
            ];
            
            $type_subtotal = 0;
            
            // Make sure items for this order type exist
            if (isset($items[$i]) && is_array($items[$i])) {
                // Loop through items for this order type
                for ($j = 0; $j < count($items[$i]); $j++) {
                    $item = $items[$i][$j];
                    $quantity = isset($quantities[$i][$j]) ? (int)$quantities[$i][$j] : 0;
                    
                    // Calculate price
                    $price_per_item = isset($pricing[$order_type][$item]) ? $pricing[$order_type][$item] : 5000;
                    $item_total = $price_per_item * $quantity;
                    
                    // Add to subtotal
                    $type_subtotal += $item_total;
                    
                    $order_details[count($order_details)-1]["items"][] = [
                        "item" => $item,
                        "quantity" => $quantity,
                        "price_per_item" => $price_per_item,
                        "item_total" => $item_total
                    ];
                }
            }
            
            // Update subtotal
            $order_details[count($order_details)-1]["subtotal"] = $type_subtotal;
            $total_price += $type_subtotal;
        }
    }
    
    // Create order summary
    $order_summary = [
        "details" => $order_details,
        "total_price" => $total_price
    ];
    
    // Convert to JSON
    $order_details_json = json_encode($order_summary);
    
    // Check if your table has total_amount column
    try {
        // If you have total_amount column
        $stmt = $conn->prepare("INSERT INTO orders (customer_name, order_details, total_amount) VALUES (?, ?, ?)");
        $stmt->bind_param("ssd", $customer_name, $order_details_json, $total_price);
    } catch (Exception $e) {
        // If you don't have total_amount column
        $stmt = $conn->prepare("INSERT INTO orders (customer_name, order_details) VALUES (?, ?)");
        $stmt->bind_param("ss", $customer_name, $order_details_json);
    }
    
    if ($stmt->execute()) {
        echo "<p style='color:green;'>Order successfully placed!</p>";
        echo "<p>Total Price: Rp " . number_format($total_price, 0, ',', '.') . "</p>";
        
        // Display order summary
        if (count($order_details) > 0) {
            echo "<h3>Order Summary</h3>";
            foreach ($order_details as $order) {
                echo "<h4>" . $order['type'] . " (Subtotal: Rp " . number_format($order['subtotal'], 0, ',', '.') . ")</h4>";
                echo "<ul>";
                foreach ($order['items'] as $item) {
                    echo "<li>" . $item['item'] . " x " . $item['quantity'] . " = Rp " . 
                         number_format($item['item_total'], 0, ',', '.') . "</li>";
                }
                echo "</ul>";
            }
            echo "<h4>Total: Rp " . number_format($total_price, 0, ',', '.') . "</h4>";
        }
    } else {
        echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
    }

    if ($stmt->execute()) {
        // Update the user's order count
        $update_stmt = $conn->prepare("UPDATE user SET order_count = order_count + 1 WHERE user_id = ?");
        $update_stmt->bind_param("i", $user_id);
        $update_stmt->execute();
        
        echo "<p style='color:green;'>Order successfully placed!</p>";
        echo "<p>Total Price: Rp " . number_format($total_price, 0, ',', '.') . "</p>";
        
        // Rest of your code for order summary...
    } else {
        echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
    }
}
?>