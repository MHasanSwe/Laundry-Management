<?php
session_start();
require 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Login success
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_role'] = $user['role']; // 1 = admin, 0 = normal

            // Redirect to dashboard if admin
            if ($user['role'] == 1) {
                header("Location: ../front/dashboard.html");
            } else {
                // You can create a separate user dashboard if needed
                header("Location: ../front/user_home.html");
            }
            exit;
        } else {
            // Invalid credentials
            header("Location: ../front/login.html?error=invalid_credentials");
            exit;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}
?>
