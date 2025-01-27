<?php
include("db/dbconn.php"); // Database connection
?>
<!DOCTYPE html>
<html>

<head>
    <title>Sneakers Street</title>
    <link rel="icon" href="images/logo.jpg" />
    <link rel="stylesheet" href="css/newstyle.css" />
    <link rel=" stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />

    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
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
            /* Remove default list styling */
            margin: 0 0 0 auto;
            /* Push the ul to the right */
            display: flex;
            /* Display list items horizontally */
            justify-content: flex-end;
            /* Align links to the end (right) */
            gap: 30px;
            /* Increase space between the links */
            background-color: #333;
            /* Add a background color */
            border-radius: 5px;
            /* Add rounded corners */
        }

        #header ul li a {
            color: #fff;
            /* Set link color to white */
            text-decoration: none;
            /* Remove default underline */
            font-weight: bold;
            /* Make the text bold */
            position: relative;
            /* Required for pseudo-element positioning */
        }

        #header ul li a::after {
            content: '';
            /* Required for pseudo-element */
            position: absolute;
            /* Position relative to the link */
            left: 50%;
            /* Start from the middle */
            bottom: -5px;
            /* Position below the text */
            width: 0;
            /* Start with no width */
            height: 2px;
            /* Thickness of the underline */
            background-color: #fff;
            /* Color of the underline */
            transition: width 0.3s ease, left 0.3s ease;
            /* Smooth transition */
        }

        #header ul li a:hover::after {
            width: 100%;
            /* Expand to full width */
            left: 0;
            /* Move to the left edge */
        }

        #container {
            flex: 1;
            /* Expand to fill remaining space */
            padding: 20px;
        }

        .nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            text-align: center;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .nav ul li {
            position: relative;
        }

        .nav ul li a {
            text-decoration: none;
            color: #333;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .nav ul li a::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -5px;
            width: 100%;
            height: 3px;
            background-color: rgb(0, 0, 0);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .nav ul li a:hover::after,
        .nav ul li a:focus::after {
            transform: scaleX(1);
        }

        #carousel {
            margin: 20px 0;
            display: flex;
            justify-content: center;
            /* Center horizontally */
            align-items: center;
            /* Center vertically */
        }

        .slick-carousel {
            width: 60%;
            /* Adjust the width to make it smaller */
            max-width: 800px;
            /* Optional: Set a maximum width */
        }

        .slick-carousel img {
            width: 100%;
            /* Make images fill the carousel container */
            height: auto;
            /* Maintain aspect ratio */
            border-radius: 10px;
        }

        #content {
            margin: 20px 0;
            padding: 20px;
            background-color: #f9f9f9;
            /* Light background for the content section */
        }

        #product {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            /* Space between product cards */
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

        /* Responsive Styles */
        @media (max-width: 768px) {
            .float {
                width: calc(50% - 40px);
                /* Two columns on tablets */
            }
        }

        @media (max-width: 480px) {
            .float {
                width: 100%;
                /* One column on mobile */
            }
        }

        #footer {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        #footer .foot,
        #footer #develop {
            margin: 10px 0;
        }

        #develop ul {
            list-style-type: none;
            padding: 0;
        }

        #develop ul li {
            margin: 5px 0;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            #header label {
                font-size: 20px;
            }

            .nav ul li {
                display: block;
                margin: 10px 0;
            }

            .float {
                flex: 1 1 100%;
            }
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
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="product.php">Product</a></li>
                <li><a href="aboutus.php">About Us</a></li>
                <li><a href="contactus.php">Contact Us</a></li>
                <li><a href="privacy.php">Privacy Policy</a></li>
                <li><a href="faqs.php">FAQs</a></li>
            </ul>
        </div>

        <!-- New Slick Carousel -->
        <div id="carousel">
            <div class="slick-carousel">
                <div><img src="images/basketball.png" alt="Basketball Sneakers"></div>
                <div><img src="images/sneakers.png" alt="Casual Sneakers"></div>
                <div><img src="images/running.png" alt="Running Sneakers"></div>
            </div>
        </div>

        <div id="content">
            <div id="product">
                <?php
                $query = $conn->query("SELECT * FROM product WHERE category='feature' ORDER BY product_id DESC") or die(mysqli_error());
                $all_out_of_stock = true; // Assume all products are out of stock initially

                while ($fetch = $query->fetch_array()) {
                    $pid = $fetch['product_id'];

                    // Fetch stock information for the product
                    $query1 = $conn->query("SELECT * FROM stock WHERE product_id = '$pid'") or die(mysqli_error());
                    $rows = $query1->fetch_array();

                    // Check if stock data exists and quantity is greater than 0
                    if ($rows && isset($rows['qty']) && $rows['qty'] > 0) {
                        $all_out_of_stock = false; // At least one product is in stock

                        // Display the product if it's in stock
                        echo "<div class='float'>";
                        echo "<a href='details2.php?id=" . $fetch['product_id'] . "'>";
                        echo "<img src='photo/" . $fetch['product_image'] . "' alt='" . $fetch['product_name'] . "'>";
                        echo "<div class='cart-icon' onclick='addToCart(" . $fetch['product_id'] . ")'>";
                        echo "<img src='images/shopping-cart.png' alt='Add to Cart'>"; // Replace with your cart icon
                        echo "</div>";
                        echo "<h3>" . $fetch['product_name'] . "</h3>";
                        echo "<p>P " . $fetch['product_price'] . "</p>";
                        echo "</a>";
                        echo "</div>";
                    }
                }

                // If all products are out of stock, display a single "No Stock" message in the center
                if ($all_out_of_stock) {
                    echo "<div style='text-align: center; margin-top: 20px;'>";
                    echo "<span style='color: red; font-weight: bold; font-size: 18px;'>No Stock</span>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>

        <div id="footer">
            <div class="foot">&copy; Sneakers Street Inc. 2025</div>
            <div id="develop">
                <h4>Developed By:</h4>
                <ul>
                    <li>JHARIL JACINTO PINPIN</li>
                    <li>JONATHS URAGA</li>
                    <li>JOSHUA MUSNGI</li>
                    <li>TALLE TUBIG</li>
                </ul>
            </div>
        </div>

        <!-- jQuery and Slick Carousel JS -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
        <script>
            $(document).ready(function() {
                $('.slick-carousel').slick({
                    dots: true,
                    infinite: true,
                    speed: 300,
                    slidesToShow: 1,
                    adaptiveHeight: true,
                    autoplay: true,
                    autoplaySpeed: 2000,
                    arrows: true,
                    responsive: [{
                            breakpoint: 768,
                            settings: {
                                arrows: false,
                                dots: true
                            }
                        },
                        {
                            breakpoint: 480,
                            settings: {
                                arrows: false,
                                dots: true
                            }
                        }
                    ]
                });
            });
        </script>
</body>

</html>