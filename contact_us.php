<?php
include("db/dbconn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $message = $_POST['message'];

  // Insert into database
  $query = "INSERT INTO contact (email, message) VALUES ('$email', '$message')";
  $result = mysqli_query($conn, $query);

  if ($result) {
    echo "<script>alert('Message sent successfully!'); window.location.href='contactus.php';</script>";
  } else {
    echo "<script>alert('Error sending message. Please try again later.'); window.location.href='contactus.php';</script>";
  }
}
