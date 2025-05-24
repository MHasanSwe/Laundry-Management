<?php
require 'connection.php'; // DB connection file

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullName = trim($_POST['fullName']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $agree = isset($_POST['agree']);

    // Validate fields
    if (empty($fullName) || empty($email) || empty($password) || !$agree) {
        die("Please fill in all required fields and agree to the terms.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    if ($password !== $confirmPassword) {
        die("Passwords do not match.");
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert into database
    try {
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$fullName, $email, $hashedPassword]);
        echo "Registration successful. <a href='login.html'>Click here to log in</a>";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            // Duplicate email
            echo "An account with this email already exists.";
        } else {
            echo "Registration failed: " . $e->getMessage();
        }
    }
} else {
    echo "Invalid request.";
}
?>
