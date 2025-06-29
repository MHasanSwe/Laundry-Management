<?php
session_start();

// Database connection
$host = "localhost";
$dbname = "smart_laundry";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch order history for logged-in user
$user_id = $_SESSION['user_id'] ?? null;
$orders = [];

if ($user_id) {
    $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f8fd;
            margin: 0;
            padding: 0;
        }
        header {
            background-color:rgb(53, 57, 69);
            color: white;
            padding: 20px;
            text-align: center;
        }
        .container {
            margin: 30px auto;
            max-width: 900px;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 2px 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #eef2f7;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .status {
            font-weight: bold;
            color: green;
        }
        footer {
            text-align: center;
            padding: 15px;
            font-size: 14px;
            color: #555;
            border-top: 1px solid #ddd;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <header>
        <h1>🧺 Smart Laundry Management System</h1>
    </header>
    <div class="container">
        <h2>Your Order History</h2>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Date</th>
                <th>Customer Name</th>
                <th>Service</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= htmlspecialchars($order['order_id'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($order['order_date'] ?? '2025-01-01') ?></td>
                    <td><?= htmlspecialchars($order['customer_name'] ?? 'Unknown') ?></td>
                    <td><?= htmlspecialchars($order['product_type'] ?? 'Laundry') ?></td>
                    <td>$<?= htmlspecialchars($order['price'] ?? '0.00') ?></td>
                    <td class="status"><?= htmlspecialchars($order['status'] ?? 'Pending') ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($orders)): ?>
                <tr><td colspan="6">No orders found.</td></tr>
            <?php endif; ?>
        </table>
    </div>
    <footer>
        &copy; 2025 Smart Laundry Management System. All rights reserved.
    </footer>
</body>
</html>
