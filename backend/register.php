<?php
require 'connection.php'; // DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST["fullName"] ?? '';
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';
    $confirm_password = $_POST["confirmPassword"] ?? '';
    $role = isset($_POST["role"]) ? (int)$_POST["role"] : 0;

    // Password mismatch
    if ($password !== $confirm_password) {
        header("Location: ../front/register.html?error=password_mismatch");
        exit;
    }

    // Sanitize
    $full_name = htmlspecialchars($full_name, ENT_QUOTES);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (:full_name, :email, :password, :role)");
        $stmt->bindParam(":full_name", $full_name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $hashed_password);
        $stmt->bindParam(":role", $role);
        $stmt->execute();

        // Registration successful → go to login page with success flag
        header("Location: ../front/login.html?success=registered");
        exit;

    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            header("Location: ../front/register.html?error=email_exists");
        } else {
            echo "Error: " . $e->getMessage();
        }
        exit;
    }
} else {
    echo "Invalid request method.";
}
?>
