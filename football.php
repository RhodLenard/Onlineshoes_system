<?php
	include("function/login.php");
	include("function/customer_signup.php");
?>
<!DOCTYPE html>
<html>
<head>
	<title>Sneakers Street</title>
	<link rel="icon" href="images/logo.jpg" />
	<link rel = "stylesheet" type = "text/css" href="css/style.css" media="all">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<script src="js/bootstrap.js"></script>
	<script src="js/jquery-1.7.2.min.js"></script>
	<script src="js/carousel.js"></script>
	<script src="js/button.js"></script>
	<script src="js/dropdown.js"></script>
	<script src="js/tab.js"></script>
	<script src="js/tooltip.js"></script>
	<script src="js/popover.js"></script>
	<script src="js/collapse.js"></script>
	<script src="js/modal.js"></script>
	<script src="js/scrollspy.js"></script>
	<script src="js/alert.js"></script>
	<script src="js/transition.js"></script>
	<script src="js/bootstrap.min.js"></script>
</head>
<body>
	<div id="header">
	<img src="images/logo.jpg">
		<label>Sneakers Street</label>
			<ul>
				<li><a href="#signup"   data-toggle="modal">Sign Up</a></li>
				<li><a href="#login"   data-toggle="modal">Login</a></li>
			</ul>
	</div>
		<div id="login" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:400px;">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
				<h3 id="myModalLabel">Login...</h3>
			</div>
				<div class="modal-body">
					<form method="post">
					<center>
						<input type="email" name="email" placeholder="Email" style="width:250px;">
						<input type="password" name="password" placeholder="Password" style="width:250px;">
					</center>
				</div>
			<div class="modal-footer">
				<input class="btn btn-primary" type="submit" name="login" value="Login">
				<button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Close</button>
					</form>
			</div>
		</div>

		<div id="login1" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:400px;">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
				<h3 id="myModalLabel">Please login before purchasing...</h3>
			</div>
				<div class="modal-body">
					<form method="post">
					<center>
						<input type="email" name="email" placeholder="Email" style="width:250px;">
						<input type="password" name="password" placeholder="Password" style="width:250px;">
					</center>
				</div>
			<div class="modal-footer">
				<p style="float:left;">No account? <a href="#signup" data-toggle="modal">Sign up here!</a></p>
				<input class="btn btn-primary" type="submit" name="login" value="Login">
				<button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Close</button>
					</form>
			</div>
		</div>

		<div id="signup" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:700px;">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
					<h3 id="myModalLabel">Sign Up Here...</h3>
				</div>
					<div class="modal-body">
						<center>
					<form method="post">
						<input type="text" name="firstname" placeholder="Firstname" required>
						<input type="text" name="mi" placeholder="Middle Initial" maxlength="1" required>
						<input type="text" name="lastname" placeholder="Lastname" required>
						<input type="text" name="address" placeholder="Address" style="width:430px;"required>
						<input type="text" name="country" placeholder="Province" required>
						<input type="text" name="zipcode" placeholder="ZIP Code" required maxlength="4">
						<input type="text" name="mobile" placeholder="Mobile Number" maxlength="11">
						<input type="text" name="telephone" placeholder="Telephone Number" maxlength="8">
						<input type="email" name="email" placeholder="Email" required>
						<input type="password" name="password" placeholder="Password" required>
						</center>
					</div>
				<div class="modal-footer">
					<input type="submit" class="btn btn-primary" name="signup" value="Sign Up">
					<button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Close</button>
				</div>
					</form>
			</div>
	<br>
<div id="container">
	<div class="nav">

			 <ul>
				<li><a href="index.php"><i class="icon-home"></i>Home</a></li>
				<li><a href="product.php"><i class="icon-th-list"></i>Product</a>
				<li><a href="aboutus.php"><i class="icon-bookmark"></i>About Us</a></li>
				<li><a href="contactus.php"><i class="icon-inbox"></i>Contact Us</a></li>
				<li><a href="privacy.php"><i class="icon-info-sign"></i>Privacy Policy</a></li>
				<li><a href="faqs.php"><i class="icon-question-sign"></i>FAQs</a></li>
			</ul>
	</div>

	<div class="nav1">
		<ul>
			<li><a href="product.php">Basketball</a></li>
			<li>|</li>
			<li><a href="football.php" class="active" style="color:#111;">Sneakers</a></li>
			<li>|</li>
			<li><a href="running.php">Running</a></li>
		</ul>
	</div>

	<div id="content" style="margin-bottom: 44.9%;">
		<br />
		<br />
		<div id="product" >

			<?php

$query = $conn->query("SELECT * FROM product WHERE category='football' ORDER BY product_id DESC") or die(mysqli_error());

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
        echo "<center>";
        echo "<a href='details.php?id=" . $fetch['product_id'] . "'><img class='img-polaroid' src='photo/" . $fetch['product_image'] . "' height='300px' width='300px'></a>";
        echo "" . $fetch['product_name'] . "";
        echo "<br />";
        echo "P " . $fetch['product_price'] . "";
        echo "<br />";
        echo "</center>";
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





	<br />
</div>
	<br />
	<div id="footer">
    <div class="foot">
        <label style="font-size:17px;"> Copyright &copy; </label>
        <p style="font-size:25px;">Sneakers Street Inc. 2025  </p>
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
