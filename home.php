<?php
	include("function/session.php");
	include("db/dbconn.php");
?>
<!DOCTYPE html>
<html>
<head>
	<title>Sneakers Street</title>
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

        <?php
        $id = (int) $_SESSION['id'];
        $query = $conn->query("SELECT * FROM customer WHERE customerid = '$id'") or die(mysqli_error());
        $fetch = $query->fetch_array();
        ?>

        <ul>
            <li><a href="function/logout.php"><i class="icon-off icon-white"></i>Logout</a></li>
            <li>Welcome:&nbsp;&nbsp;&nbsp;<a href="#profile" data-toggle="modal"><i class="icon-user icon-white"></i><?php echo $fetch['firstname']; ?>&nbsp;<?php echo $fetch['lastname']; ?></a></li>
        </ul>
    </div>

		<div id="profile" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:700px;">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
					<h3 id="myModalLabel">My Account</h3>
				</div>
					<div class="modal-body">
						<?php
							$id = (int) $_SESSION['id'];

								$query = $conn->query ("SELECT * FROM customer WHERE customerid = '$id' ") or die (mysqli_error());
								$fetch = $query->fetch_array ();
						?>
						<center>
					<form method="post">
						<center>
							<table>
								<tr>
									<td class="profile">Name:</td><td class="profile"><?php echo $fetch['firstname'];?>&nbsp;<?php echo $fetch['mi'];?>&nbsp;<?php echo $fetch['lastname'];?></td>
								</tr>
								<tr>
									<td class="profile">Address:</td><td class="profile"><?php echo $fetch['address'];?></td>
								</tr>
								<tr>
									<td class="profile">Country:</td><td class="profile"><?php echo $fetch['country'];?></td>
								</tr>
								<tr>
									<td class="profile">ZIP Code:</td><td class="profile"><?php echo $fetch['zipcode'];?></td>
								</tr>
								<tr>
									<td class="profile">Mobile Number:</td><td class="profile"><?php echo $fetch['mobile'];?></td>
								</tr>
								<tr>
									<td class="profile">Telephone Number:</td><td class="profile"><?php echo $fetch['telephone'];?></td>
								</tr>
								<tr>
									<td class="profile">Email:</td><td class="profile"><?php echo $fetch['email'];?></td>
								</tr>
							</table>
						</center>
					</div>
				<div class="modal-footer">
					<a href="account.php?id=<?php echo $fetch['customerid']; ?>"><input type="button" class="btn btn-success" name="edit" value="Edit Account"></a>
					<button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Close</button>
				</div>
					</form>
			</div>




	<br>
<div id="container">




	<div id="content">
		<div class="nav">

			 <ul>
				<li><a href="home.php"><i class="icon-home"></i>Home</a></li>
				<li><a href="product1.php"><i class="icon-th-list"></i>Product</a>
				<li><a href="aboutus1.php"><i class="icon-bookmark"></i>About Us</a></li>
				<li><a href="contactus1.php"><i class="icon-inbox"></i>Contact Us</a></li>
				<li><a href="privacy1.php"><i class="icon-info-sign"></i>Privacy Policy</a></li>
				<li><a href="faqs1.php"><i class="icon-question-sign"></i>FAQs</a></li>
			</ul>
		</div>

		<div id="carousel">
			<div id="myCarousel" class="carousel slide">
				<div class="carousel-inner">
					<div class="active item" style="padding:0; border-bottom:0 solid #111;"><img src="images/basketball.png" class="carousel"></div>
					<div class="item" style="padding:0; border-bottom:0 solid #111;"><img src="images/sneakers.png" class="carousel"></div>
					<div class="item" style="padding:0; border-bottom:0 solid #111;"><img src="images/running.png" class="carousel"></div>
				</div>
					<a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
					<a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
			</div>
		</div>


		<div id="product" style="position:relative; margin-bottom:12%;">
			<center><h2><legend>Featured Items</legend></h2></center>
			<br />

			<?php
// Fetch featured products
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
