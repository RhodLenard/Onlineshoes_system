<?php
include("function/login.php");
include("function/customer_signup.php");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Sneakers Street</title>
    <link rel="icon" href="images/logo.jpg" />
    <link rel="stylesheet" href="css/newstyle.css">
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

        #container {
            flex: 1;
            /* Expand to fill remaining space */
            padding: 20px;
        }

        #content {
            text-align: center;
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
                <li><a href="contactus.php"><i class="icon-inbox"></i>Contact Us</a></li>
                <li><a href="privacy.php" class="active"><i class="icon-info-sign"></i>Privacy Policy</a></li>
                <li><a href="faqs.php"><i class="icon-question-sign"></i>FAQs</a></li>
            </ul>
        </div>

        <div id="content">
            <legend>
                <h3>Privacy Policy</h3>
            </legend>
            <p>The Online Shoe Store Incorporated respect the privacy of the visitors
                to the Online Shoe Store.com website and the local websites connected with it, and take great care to protect your
                information.. This privacy policy tells you what information we collect from you, how we may use it and
                the steps we take to ensure that it is protected.
            </p>
            <hr>
            <h4>Protection of visitors information</h4>
            <p>In order to protect the information you provide to us by visiting our website we have implemented various
                security measures. Your personal information is contained behind secured networks and is only accessible
                by a limited number of people, who have special access rights and are required to keep the information
                confidential.Please keep in mind though that whenever you give out personal information online there is a
                risk that third parties may intercept and use that information. While Online Shoe Store strives to protect its user's
                personal information and privacy, we cannot guarantee the security of any information you disclose online
                and you do so at your own risk.</p>
            <hr>
            <h4>Use of cookies</h4>
            <p>A cookie is a small string of information that the website that you visit transfers to your computer for
                identification purposes. Cookies can be used to follow your activity on the website and that information
                helps us to understand your preferences and improve your website experience. Cookies are also used to
                remember for instance your user name and password.</p>
            <p>You can turn off all cookies, in case you prefer not to receive them. You can also have your computer warn
                you whenever cookies are being used. For both options you have to adjust your browser settings
                (like internet explorer). There are also software products available that can manage cookies for you.
                Please be aware though that when you have set your computer to reject cookies, it can limit the
                functionality of the website you visit and it’s possible then that you do not have access to some of the
                features on the website.</p>
            <hr>
            <h4>Online policy</h4>
            <p>The Privacy Policy does not extend to anything that is inherent in the operation of the internet, and
                therefore beyond adidas' control, and is not to be applied in any manner contrary to applicable law or
                governmental regulation. This online privacy policy only applies to information collected through our
                website and not to information collected offline.</p>

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