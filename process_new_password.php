<?php
session_start();
include('db/dbconn.php');

if (isset($_POST['reset_password'])) {
  $email = $_SESSION['reset_email'] ?? null;
  $new_password = $_POST['new_password'];
  $confirm_password = $_POST['confirm_password'];

  if (!$email) {
    $_SESSION['reset_error'] = "Session expired. Please request another reset.";
    header("Location: reset_password.php");
    exit();
  }

  if ($new_password !== $confirm_password) {
    $_SESSION['reset_error'] = "Passwords do not match.";
    header("Location: new_password.php?token=" . $_GET['token']);
    exit();
  }

  $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
  $stmt = $conn->prepare("UPDATE customer SET password = ? WHERE email = ?");
  $stmt->bind_param("ss", $hashed_password, $email);
  $stmt->execute();

  $conn->query("DELETE FROM password_resets WHERE email = '$email'");
  unset($_SESSION['reset_email']);

  $_SESSION['reset_success'] = "Password successfully updated. You can now log in.";
  header("Location: login.php");
  exit();
} else {
  $_SESSION['reset_error'] = "Invalid request.";
  header("Location: reset_password.php");
  exit();
}
