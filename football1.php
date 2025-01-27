<?php
include("function/session.php");
include("db/dbconn.php");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Sneakers Street</title>
    <link rel="icon" href="images/logo.jpg" />
    <link rel="stylesheet" href="css/newstyle.css">
    <style>
        /* General Styles */
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

        /* Cart Button Styles */
        .cart-btn {
            position: fixed;
            top: 30%;
            /* Position at the vertical center */
            right: 10px;
            /* 20px from the right edge */
            transform: translateY(-50%);
            /* Center the button vertically */
            z-index: 1000;
            /* Ensure it appears above other elements */
        }

        .btn-cart {
            background-color: rgb(0, 0, 0);
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            padding: 10px 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-cart:hover {
            background-color: rgb(92, 93, 93);
            transform: scale(1.05);
        }

        .btn-cart:active {
            background-color: rgb(164, 166, 168);
            transform: scale(0.95);
        }
    </style>
</head>

<body>
    <div id="header">
        <img src="images/logo.jpg">
        <label>Sneakers Street</label>

        <?php
        $id = (int) $_SESSION['id'];
        $query = $conn->query("SELECT * FROM customer WHERE customerid = '$id'") or die(mysqli_error());
        $fetch = $query->fetch_array();
        ?>

        <ul>
            <li>Welcome:&nbsp;&nbsp;&nbsp;<a href="#profile" data-toggle="modal"><i class="icon-user icon-white"></i><?php echo $fetch['firstname']; ?>&nbsp;<?php echo $fetch['lastname']; ?></a></li>
            <li><a href="function/logout.php"><i class="icon-off icon-white"></i>Log Out</a></li>
        </ul>
    </div>


    <div id="container">
        <div class="nav">
            <ul>
                <li><a href="home.php"><i class="icon-home"></i>Home</a></li>
                <li><a href="product1.php" class="active"><i class="icon-th-list"></i>Product</a></li>
                <li><a href="aboutus1.php"><i class="icon-bookmark"></i>About Us</a></li>
                <li><a href="contactus1.php"><i class="icon-inbox"></i>Contact Us</a></li>
                <li><a href="privacy1.php"><i class="icon-info-sign"></i>Privacy Policy</a></li>
                <li><a href="faqs1.php"><i class="icon-question-sign"></i>FAQs</a></li>
            </ul>
        </div>

        <div class="nav1">
            <ul>
                <li><a href="product1.php">Basketball</a></li>
                <li><a href="football1.php" class="active">Sneakers</a></li>
                <li><a href="running1.php">Running</a></li>
            </ul>
            <a href="cart.php?id=<?php echo $id; ?>&action=view" class="cart-btn">
                <button class="btn-cart">
                    <i class="icon-shopping-cart"></i> View Cart
                </button>
            </a>
        </div>



        <div id="content">
            <div id="product">
                <form method="post" style="display: flex; justify-content:center; align-items:center; flex-wrap:wrap;">
                    <?php
                    $query = $conn->query("SELECT * FROM product WHERE category='football' ORDER BY product_id DESC") or die(mysqli_error());

                    $all_out_of_stock = true;

                    while ($fetch = $query->fetch_array()) {
                        $pid = $fetch['product_id'];
                        $query1 = $conn->query("SELECT * FROM stock WHERE product_id = '$pid'") or die(mysqli_error());
                        $rows = $query1->fetch_array();
                        if ($rows && isset($rows['qty']) && $rows['qty'] > 0) {
                            $all_out_of_stock = false;
                            echo "<div class='float'>";
                            echo "<a href='details.php?id=" . $fetch['product_id'] . "'>";
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
                    if ($all_out_of_stock) {
                        echo "<div style='text-align: center; margin-top: 20px;'>";
                        echo "<span style='color: red; font-weight: bold; font-size: 18px;'>No Stock</span>";
                        echo "</div>";
                    }
                    ?>
                </form>
            </div>
        </div>
    </div>

    <div style="padding: 20px;">
        <div id="footer">
            <div class="foot">
                <label style="font-size:17px;"> Copyright &copy; </label>
                <p style="font-size:25px;">Sneakers Street Inc. 2025</p>
            </div>
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
</body>

</html>