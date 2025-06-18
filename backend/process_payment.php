<?php
// process_payment.php

$host = "localhost";
$user = "root";
$pass = "";
$db = "laundry_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assuming latest order belongs to the user
$method = $_POST['method'];
$sql = "UPDATE orders SET payment_status='paid', payment_method=? WHERE id = (SELECT MAX(id) FROM orders)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $method);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Payment processed.";
} else {
    http_response_code(500);
    echo "Error processing payment.";
}

$stmt->close();
$conn->close();
?>
