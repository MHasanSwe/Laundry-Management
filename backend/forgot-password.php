<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = $_POST['email'] ?? '';

  // TODO: Generate and send reset link
  echo "Password reset link has been sent to $email";
}
?>
