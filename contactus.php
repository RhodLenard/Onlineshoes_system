<?php
include("function/login.php");
include("function/customer_signup.php");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Sneakers Street</title>
    <link rel="stylesheet" href="css/newstyle.css">
    <link rel="icon" href="images/logo.jpg" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        html,
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100%;
            /* Ensure full height */
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            /* Minimum height to fill the viewport */
        }

        #header {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        #header img {
            height: 50px;
            vertical-align: middle;
        }

        #header label {
            font-size: 24px;
            vertical-align: middle;
            margin-left: 10px;
        }

        #header ul {
            list-style: none;
            margin: 0;
            display: flex;
            justify-content: flex-end;
            gap: 30px;
            padding: 0;
        }

        #header ul li a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            position: relative;
        }

        #header ul li a::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: -5px;
            width: 0;
            height: 2px;
            background-color: #fff;
            transition: width 0.3s ease, left 0.3s ease;
        }

        #header ul li a:hover::after {
            width: 100%;
            left: 0;
        }

        #content {
            background-color: #f9f9f9;
            padding: 20px;
            margin: 20px 0;
        }

        #product {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .float {
            margin: 10px;
            text-align: center;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 15px;
            width: 250px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            /* Required for absolute positioning of the cart icon */
        }

        .float:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .float img {
            width: 200px;
            height: 200px;
            object-fit: contain;
            border-radius: 10px;
        }

        .float h3 {
            margin: 10px 0;
            font-size: 18px;
            color: #333;
        }

        .float p {
            margin: 5px 0;
            font-size: 16px;
            color: #007bff;
        }

        .float a {
            text-decoration: none;
            color: inherit;
        }

        /* Cart Icon */
        .cart-icon {
            position: absolute;
            top: 10px;
            /* Adjust as needed */
            right: 10px;
            /* Adjust as needed */
            background-color: rgba(255, 255, 255, 0.8);
            /* Semi-transparent background */
            border-radius: 50%;
            /* Circular shape */
            padding: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .cart-icon:hover {
            background-color: rgba(255, 255, 255, 1);
            /* Solid background on hover */
        }

        .cart-icon img {
            width: 24px;
            /* Adjust icon size */
            height: 24px;
            /* Adjust icon size */
        }

        #footer {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
            margin-top: auto;
            /* Ensures footer stays at the bottom */
        }

        #footer .foot,
        #footer #develop {
            margin: 10px 0;
        }

        #develop ul {
            list-style-type: none;
            padding: 0;
        }

        .nav1 {
            text-align: center;
            padding: 30px 0;
            /* Increase padding for more spacing */
        }

        .nav1 ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: inline-flex;
            gap: 25px;
            /* Add gap for better spacing between links */
        }

        .nav1 ul li {
            margin: 0;
            /* Removed redundant margin */
        }

        .nav1 ul li a {
            text-decoration: none;
            color: #111;
            font-size: 18px;
            /* Increase font size */
            font-weight: bold;
            /* Make the text bolder */
            position: relative;
            padding-bottom: 8px;
            /* Add extra padding for clickable area */
        }

        .nav1 ul li a::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: 0;
            width: 0;
            height: 3px;
            /* Increase underline thickness */
            background-color: #111;
            transition: width 0.3s ease, left 0.3s ease;
        }

        .nav1 ul li a:hover::after,
        .nav1 ul li a.active::after {
            width: 100%;
            /* Expand underline to full width */
            left: 0;
            /* Align underline with the link text */
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            /* Space between links */
            flex-wrap: wrap;
            /* Allow wrapping on smaller screens */
        }

        /* General Social Link Styling */
        .social-link {
            display: inline-flex;
            align-items: center;
            padding: 12px 24px;
            font-size: 16px;
            text-decoration: none;
            border-radius: 50px;
            /* Rounded corners */
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* Subtle shadow */
        }

        /* Hover Effect */
        .social-link:hover {
            transform: translateY(-5px);
            /* Lift the button on hover */
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            /* Enhanced shadow on hover */
        }

        /* Gmail Link Styling */
        .social-link.email {
            background: linear-gradient(135deg, #dd4b39, #c23321);
            /* Gradient background */
        }

        .social-link.email:hover {
            background: linear-gradient(135deg, #c23321, #dd4b39);
            /* Reverse gradient on hover */
        }

        /* Facebook Link Styling */
        .social-link.facebook {
            background: linear-gradient(135deg, #3b5998, #2d4373);
            /* Gradient background */
        }

        .social-link.facebook:hover {
            background: linear-gradient(135deg, #2d4373, #3b5998);
            /* Reverse gradient on hover */
        }

        /* Instagram Link Styling */
        .social-link.instagram {
            background: linear-gradient(135deg, #e4405f, #c13584);
            /* Gradient background */
        }

        .social-link.instagram:hover {
            background: linear-gradient(135deg, #c13584, #e4405f);
            /* Reverse gradient on hover */
        }

        /* Icon Styling */
        .social-link i {
            margin-right: 8px;
            /* Space between icon and text */
            font-size: 18px;
            /* Slightly larger icon */
        }
    </style>
</head>

<body>
    <div id="header">
        <img src="images/logo.jpg">
        <label>Sneakers Street</label>
        <ul>
            <li><a href="login.php">Login</a></li>
            <li><a href="signup.php">Sign Up</a></li>
        </ul>
    </div>

    <div id="container">
        <div class="nav">
            <ul>
                <li><a href="index.php"> <i class="icon-home"></i>Home</a></li>
                <li><a href="product.php"> <i class="icon-th-list"></i>Product</a></li>
                <li><a href="aboutus.php"> <i class="icon-bookmark"></i>About Us</a></li>
                <li><a href="contactus.php" class="active"><i class="icon-inbox"></i>Contact Us</a></li>
                <li><a href="privacy.php"><i class="icon-info-sign"></i>Privacy Policy</a></li>
                <li><a href="faqs.php"><i class="icon-question-sign"></i>FAQs</a></li>
            </ul>
        </div>


        <div style="display: flex; justify-content:center; align-items:center; min-height:40vh;">
            <img src="img/contactus.jpg" style="width:1150px; height:250px; border:1px solid #000; ">
        </div>

        <div id="content">
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

            <div id="develop">
                <h4>Developed By:</h4>
                <ul style="list-style-type: none; /* Removes the bullets */">
                    <li>JHARIL JACINTO PINPIN</li>
                    <li>JONATHS URAGA</li>
                    <li>JOSHUA MUSNGI</li>
                    <li>TALLE TUBIG</li>
                </ul>
            </div>
        </div>
</body>

</html>