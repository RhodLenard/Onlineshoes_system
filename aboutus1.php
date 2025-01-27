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
</head>

<body>
	<div id="header">
		<div class="header-container">
			<div class="logo-section">
				<img src="images/logo.jpg" alt="Sneakers Street Logo">
				<label>Sneakers Street</label>
			</div>

			<?php
			$id = (int) $_SESSION['id'];
			$query = $conn->query("SELECT * FROM customer WHERE customerid = '$id'") or die(mysqli_error());
			$fetch = $query->fetch_array();
			?>

			<div class="user-section">
				<ul>
					<li>Welcome, <a href="profile.php"><i class="icon-user"></i> <?php echo $fetch['firstname']; ?> <?php echo $fetch['lastname']; ?></a></li>
					<li><a href="function/logout.php"><i class="icon-off"></i> Logout</a></li>
				</ul>
			</div>
		</div>
	</div>

	<div id="container">
		<div class="nav">
			<ul>
				<li><a href="home.php"> <i class="icon-home"></i>Home</a></li>
				<li><a href="product1.php"> <i class="icon-th-list"></i>Product</a></li>
				<li><a href="aboutus1.php" class="active"> <i class="icon-bookmark"></i>About Us</a></li>
				<li><a href="contactus1.php"><i class="icon-inbox"></i>Contact Us</a></li>
				<li><a href="privacy1.php"><i class="icon-info-sign"></i>Privacy Policy</a></li>
				<li><a href="faqs1.php"><i class="icon-question-sign"></i>FAQs</a></li>
			</ul>
		</div>

		<div style="display: flex; justify-content: center; align-items: center; min-height: 40vh;">
			<img src="img/about1.jpg" style="width: 1150px; height: 250px; border: 1px solid #000;">
		</div>

		<div id="content">
			<legend>
				<h3>Mission</h3>
			</legend>
			<h4 style="text-indent:60px;">To provide a high quality footwear that suit the athletes style and to be one of the leading sports footwear apparel in the country.</h4>
			<br />
			<legend>
				<h3>Vision</h3>
			</legend>
			<h4 style="text-indent:60px;">Online Shoe Store, the company that inspire, motivate, and give determination to the sports enthusiast.</h4>
			<br />

		</div>
		<br />
	</div>
	<br />


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