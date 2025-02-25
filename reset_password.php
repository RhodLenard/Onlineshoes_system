<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Forgot Password - Sneakers Street</title>
  <link rel="icon" href="images/logo.jpg" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="css/loginstyle.css">
  <link rel="stylesheet" href="css/p1.css">
  <link rel="stylesheet" href="css/home.css">
  <link rel="stylesheet" href="css/newstyle.css">
  <style>
    .forgot-password-container {
      margin-top: 100px;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 80vh;
    }

    .forgot-password-form {
      background-color: #fff;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }

    .forgot-password-form h2 {
      margin-bottom: 20px;
      font-weight: bold;
    }

    .forgot-password-form input[type="email"] {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border-radius: 5px;
      border: 1px solid #ddd;
    }

    .forgot-password-form button {
      width: 100%;
      padding: 10px;
      border-radius: 5px;
      background-color: #343a40;
      color: white;
      border: none;
      margin-top: 10px;
    }

    .alert-message {
      margin-top: 10px;
      font-size: 14px;
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

  <div class="forgot-password-container">
    <div class="forgot-password-form">
      <h2>Forgot Password</h2>
      <?php
      if (isset($_SESSION['reset_error'])) {
        echo '<div class="alert-message" style="color:red;">' . $_SESSION['reset_error'] . '</div>';
        unset($_SESSION['reset_error']);
      }
      if (isset($_SESSION['reset_success'])) {
        echo '<div id="success-message" class="alert-message" style="color:green;">' . $_SESSION['reset_success'] . '</div>';
        unset($_SESSION['reset_success']);
      }
      ?>
      <form action="process_reset_request.php" method="POST">
        <label style="font-weight: bold;">Email:</label>
        <input type="email" name="email" placeholder="Enter your email address" required>
        <button type="submit" name="reset_request">Send Reset Link</button>
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
  <script>
    // ✅ Updated script to avoid querySelector error
    setTimeout(() => {
      const successMessage = document.getElementById("success-message");
      if (successMessage) successMessage.remove();
    }, 3000);
  </script>
</body>

</html>