<?php
    include("connection.php");
    session_start();

    if(isset($_POST['submit'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
    }

    $sql = "SELECT * FROM users WHERE email = $email AND password = $password";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['email' => $email, 'password' => $password]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row) {
        $_SESSION['email'] = $email;
        header("Location: welcome.php");
    } else {
        echo '<script> 
              window.location.href="login.php";
              alert("Invalid email or password");
          </script>';
    }
?>