<?php
// submit_order.php

$host = "localhost";
$user = "root";
$pass = "";
$db = "laundry_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $_POST['customer-name'];
$address = $_POST['address'];
$pickup = $_POST['pickup-type'];
$delivery = $_POST['delivery-type'];
$total = $_POST['grand-total'];
$orders = json_decode($_POST['orders'], true);

// Insert into orders table
$sql = "INSERT INTO orders (customer_name, address, pickup_type, delivery_type, total_amount, payment_status, created_at)
        VALUES (?, ?, ?, ?, ?, 'pending', NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssd", $name, $address, $pickup, $delivery, $total);
$stmt->execute();
$order_id = $stmt->insert_id;
$stmt->close();

// Insert each item
foreach ($orders as $item) {
    $product = $item['product'];
    $service = $item['service'];
    $quantity = intval($item['quantity']);
    $unit_price = floatval($item['unitPrice']);
    $total_price = floatval($item['totalPrice']);

    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product, service, quantity, unit_price, total_price)
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issidd", $order_id, $product, $service, $quantity, $unit_price, $total_price);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
echo "Order recorded successfully";
?>
