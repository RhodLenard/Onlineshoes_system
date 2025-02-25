<?php
session_start();
include('db/dbconn.php');

if (!isset($_GET['token'])) {
  echo "Invalid token.";
  exit();
}

$token = $_GET['token'];
$stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = ? AND expires > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
  echo "Invalid or expired token.";
  exit();
}
$_SESSION['reset_email'] = $result->fetch_assoc()['email'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Reset Password - Sneakers Street</title>
  <link rel="icon" href="images/logo.jpg" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="css/loginstyle.css">
  <link rel="stylesheet" href="css/p1.css">
  <link rel="stylesheet" href="css/home.css">
  <link rel="stylesheet" href="css/newstyle.css">
  <style>
    .reset-password-container {
      margin-top: 100px;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 80vh;
    }

    .reset-password-form {
      background-color: #fff;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }

    .reset-password-form h2 {
      margin-bottom: 20px;
      font-weight: bold;
    }

    .reset-password-form input[type="password"] {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border-radius: 5px;
      border: 1px solid #ddd;
    }

    .reset-password-form button {
      width: 100%;
      padding: 10px;
      border-radius: 5px;
      background-color: #343a40;
      color: white;
      border: none;
      margin-top: 10px;
    }

    .reset-password-form label {
      font-weight: bold;
      text-align: left;
      display: block;
      margin-top: 10px;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="#">
      <img src="images/logo.jpg" width="30" height="30" class="d-inline-block align-top" alt="">
      Sneakers Street
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="product.php">Product</a></li>
        <li class="nav-item"><a class="nav-link" href="aboutus.php">About Us</a></li>
        <li class="nav-item"><a class="nav-link" href="contactus.php">Contact Us</a></li>
        <li class="nav-item"><a class="nav-link" href="privacy.php">Privacy Policy</a></li>
        <li class="nav-item"><a class="nav-link" href="faqs.php">FAQs</a></li>
      </ul>
    </div>
  </nav>

  <div class="reset-password-container">
    <div class="reset-password-form">
      <h2>Reset Your Password</h2>
      <form action="process_new_password.php" method="POST">
        <label>New Password:</label>
        <input type="password" name="new_password" placeholder="Enter new password" required>
        <label>Confirm Password:</label>
        <input type="password" name="confirm_password" placeholder="Confirm new password" required>
        <button type="submit" name="reset_password">Update Password</button>
      </form>
    </div>
  </div>

  <div style="padding: 20px;">
    <div id="footer">
      <div class="foot">&copy; Sneakers Street Inc. 2025</div>
    </div>
  </div>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Popper.js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>