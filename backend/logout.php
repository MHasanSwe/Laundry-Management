<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();

// OPTIONAL: Remove any remember-me cookies (if used)
// setcookie("remember_me", "", time() - 3600, "/");

header("Location: ../front/login.html");
exit;
?>
