<?php
include("function/login.php");
include("function/customer_signup.php");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Sneakers Street</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/logo.jpg" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/loginstyle.css">
    <link rel="stylesheet" href="css/p1.css">
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/newstyle.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="#">
            <img src="images/logo.jpg" width="30" height="30" class="d-inline-block align-top" alt="">
            Sneakers Street
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="login.php">
                        <i class="fas fa-shopping-cart">
                            <p style="display: inline; font:message-box;">Cart</p>
                        </i>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" a href="login.php"><i class="icon-user"></i> Login</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="signup.php"><i class="icon-off"></i>Sign Up</a>
                </li>
            </ul>
        </div>
    </nav>

    <div id="container">
        <div class="nav">
            <ul>
                <li><a href="index.php"><i class="icon-home"></i>Home</a></li>
                <li><a href="product.php"><i class="icon-th-list"></i>Product</a></li>
                <li><a href="aboutus.php"><i class="icon-bookmark"></i>About Us</a></li>
                <li><a href="contactus.php" class="active"><i class="icon-inbox"></i>Contact Us</a></li>
                <li><a href="privacy.php"><i class="icon-info-sign"></i>Privacy Policy</a></li>
                <li><a href="faqs.php"><i class="icon-question-sign"></i>FAQs</a></li>
            </ul>
        </div>
    </div>

    <div style="display: flex; justify-content: center; align-items: center; min-height: 45vh;">
        <img src="img/contactus.jpg" style="width: 100%; max-width: 1150px; height: auto; border: 1px solid #000;">
    </div>

    <div id="content" style="padding: 0 20px;">
        <div class="modern-contact-links">
            <h2>Contact Us</h2>
            <p>Feel free to reach out through the following platforms:</p>
            <div class="social-links">
                <a href="https://accounts.google.com/v3/signin/identifier?continue=https%3A%2F%2Fmail.google.com%2Fmail%2Fu%2F0%2F&emr=1&followup=https%3A%2F%2Fmail.google.com%2Fmail%2Fu%2F0%2F&ifkv=AVdkyDmZbPsSo261wzniWV0BVt6O0CW-C8Qdq1xF5aKDo0nWwkxWdAdxDuPFuZGm9tMkDACitaldhg&osid=1&passive=1209600&service=mail&flowName=GlifWebSignIn&flowEntry=ServiceLogin&dsh=S-1458509828%3A1737997365599107&ddm=1" class="social-link email">
                    <i class="fas fa-envelope">sneakersstreets@gmail.com</i> Gmail
                </a>
                <a href="https://www.facebook.com/profile.php?id=61557130073352" target="_blank" class="social-link facebook">
                    <i class="fab fa-facebook">sneakers street</i> Facebook
                </a>
                <a href="https://www.instagram.com/" target="_blank" class="social-link instagram">
                    <i class="fab fa-instagram">Insta</i> Instagram
                </a>
            </div>
        </div>
    </div>
    </div>


    <div style="padding: 20px;">
        <div id="footer">
            <div class="foot">
                <label style="font-size:17px;"> Copyright &copy; </label>
                <p style="font-size:25px;">Sneakers Street Inc. 2025 </p>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Popper.js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>

        <!-- Bootstrap JS -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>