<?php
session_start();
include('db/dbconn.php');

date_default_timezone_set('Asia/Manila'); // ✅ Set timezone to Philippines

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if (isset($_POST['reset_request'])) {
  $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['reset_error'] = "Invalid email format.";
    header("Location: reset_password.php");
    exit();
  }

  $stmt = $conn->prepare("SELECT customerid FROM customer WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $token = bin2hex(random_bytes(50));
    $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

    $deleteStmt = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
    $deleteStmt->bind_param("s", $email);
    $deleteStmt->execute();

    $insertStmt = $conn->prepare("INSERT INTO password_resets (email, token, expires) VALUES (?, ?, ?)");
    $insertStmt->bind_param("sss", $email, $token, $expires);
    $insertStmt->execute();

    $reset_link = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/new_password.php?token=$token";

    $mail = new PHPMailer(true);
    try {
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'delasnievesrhodlenard@gmail.com'; // Your Gmail address
      $mail->Password = 'jrgf kttz ehgk mpxf';    // App Password
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port = 587;

      $mail->setFrom('delasnievesrhodlenard@gmail.com', 'Sneakers Street');
      $mail->addAddress($email);
      $mail->isHTML(true);
      $mail->Subject = 'Password Reset for Sneakers Street';
      $mail->Body = "Click <a href='$reset_link'>here</a> to reset your password.";

      $mail->send();
      $_SESSION['reset_success'] = "Password reset link has been sent to your email.";
    } catch (Exception $e) {
      $_SESSION['reset_error'] = "Mailer Error: {$mail->ErrorInfo}";
    }
  } else {
    $_SESSION['reset_error'] = "Email not found.";
  }

  header("Location: reset_password.php");
  exit();
} else {
  $_SESSION['reset_error'] = "Invalid request.";
  header("Location: reset_password.php");
  exit();
}
