<?php
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'smart_laundry';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    // ✅ Select only users, not admins
    $stmt = $pdo->query("
        SELECT first_name, last_name, email, phone_number, created_at 
        FROM sign_up 
        WHERE role = 'user' 
        ORDER BY created_at DESC
    ");

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
