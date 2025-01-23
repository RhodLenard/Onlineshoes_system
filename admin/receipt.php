<?php
	include("../function/admin_session.php");
	include("../db/dbconn.php");
?>
<!DOCTYPE html>
<html>
<head>
	<title>Sneakers Street</title>
	<link rel = "stylesheet" type = "text/css" href="../css/style.css" media="all">
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
	<script src="../js/bootstrap.js"></script>
	<script src="../js/jquery-1.7.2.min.js"></script>
	<script src="../js/carousel.js"></script>
	<script src="../js/button.js"></script>
	<script src="../js/dropdown.js"></script>
	<script src="../js/tab.js"></script>
	<script src="../js/tooltip.js"></script>
	<script src="../js/popover.js"></script>
	<script src="../js/collapse.js"></script>
	<script src="../js/modal.js"></script>
	<script src="../js/scrollspy.js"></script>
	<script src="../js/alert.js"></script>
	<script src="../js/transition.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script src="../javascripts/filter.js" type="text/javascript" charset="utf-8"></script>
	<script src="../jscript/jquery-1.9.1.js" type="text/javascript"></script>

		<!--Le Facebox-->
		<link href="../facefiles/facebox.css" media="screen" rel="stylesheet" type="text/css" />
		<script src="../facefiles/jquery-1.9.js" type="text/javascript"></script>
		<script src="../facefiles/jquery-1.2.2.pack.js" type="text/javascript"></script>
		<script src="../facefiles/facebox.js" type="text/javascript"></script>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
		$('a[rel*=facebox]').facebox()
		})
		</script>

		<script language="javascript" type="text/javascript">
        function printDiv(divID) {
            //Get the HTML of div
            var divElements = document.getElementById(divID).innerHTML;
            //Get the HTML of whole page
            var oldPage = document.body.innerHTML;

            //Reset the page's HTML with div's HTML only
            document.body.innerHTML =
              "<html><head><title></title></head><body>" +
              divElements + "</body>";

            //Print Page
            window.print();

            //Restore original HTML
            document.body.innerHTML = oldPage;
        }
		</script>
		<style>
			 form.well {
    width: 50%; /* Fixed width for the form */
    padding: 30px; /* Padding for spacing */
    background-color: #ffffff; /* White background */
    border-radius: 10px; /* Rounded corners */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Soft shadow */
    border: 1px solid #e0e0e0; /* Light border for subtle definition */
    text-align: center; /* Center-align text */
    display: flex; /* Add this */
    flex-direction: column; /* Add this */
    align-items: center; /* Add this */
    justify-content: center; /* Add this */
    box-sizing: border-box; /* Add this */
}

/* Legend (Adminstrator text) */
form.well legend {
  font-size: 24px; /* Larger font size */
  font-weight: bold; /* Bold text */
  color: #333; /* Dark gray color */
  margin-bottom: 20px; /* Spacing below the legend */ 
}
		</style>
</head>
<body>
	<div id="header" style="position:fixed;">
		<img src="../img/logo.jpg">
		<label>Sneakers Street</label>

			<?php
				$id = (int) $_SESSION['admin_id'];

					$query = $conn->query ("SELECT * FROM admin WHERE adminid = '$id' ") or die (mysqli_error());
					$fetch = $query->fetch_array ();
			?>

			<ul>
				<li><a href="../function/admin_logout.php"><i class="icon-off icon-white"></i>logout</a></li>
				<li>Welcome:&nbsp;&nbsp;&nbsp;<i class="icon-user icon-white"></i><?php echo $fetch['username']; ?></li>
			</ul>
	</div>

	<br>

	<div id="leftnav">
		<ul>
			<li><a href="admin_home.php" style="color:#333;">Dashboard</a></li>
			<li><a href="admin_home.php">Products</a>
				<ul>
					<li><a href="admin_feature.php "style="font-size:15px; margin-left:15px;">Features</a></li>
					<li><a href="admin_product.php "style="font-size:15px; margin-left:15px;">Basketball</a></li>
					<li><a href="admin_football.php" style="font-size:15px; margin-left:15px;">Football</a></li>
					<li><a href="admin_running.php"style="font-size:15px; margin-left:15px;">Running</a></li>
				</ul>
			</li>
			<li><a href="transaction.php">Transactions</a></li>
			<li><a href="customer.php">Customers</a></li>
			<li><a href="message.php">Messages</a></li>
			<li><a href="order.php">Orders</a></li>
		</ul>
	</div>

	<div id="rightcontent" style="position:absolute; top:10%;">
			<div class="alert alert-info"><center><h2>Transactions</h2></center></div>
			<br />

			<div class="alert alert-info">
			<form method="post" class="well"  style="background-color:#fff; overflow:hidden;">
	<div id="printablediv">
	<center>
	<table class="table" style="width:50%;">
	<label style="font-size:25px;">KUPAL.</label>
	<label style="font-size:20px;">Official Receipt</label>
		<tr>
			<th><h5>Quantity</h5></th>
			<th><h5>Product Name</h5></th>
			<th><h5>Size</h5></th>
			<th><h5>Price</h5></th>
		</tr>

		<?php
		$t_id = $_GET['tid'];

		// Fetch transaction data
		$query = $conn->query("SELECT * FROM transaction WHERE transaction_id = '$t_id'") or die (mysqli_error($conn));
		$fetch = $query->fetch_array();

		$amnt = $fetch['amount'];
		echo "Date: " . $fetch['order_date'] . "<br>";

		// Fetch transaction details with selected size
		$query2 = $conn->query("
			SELECT td.quantity, p.product_name, td.product_size, p.product_price
			FROM transaction_detail td
			LEFT JOIN product p ON td.product_id = p.product_id
			WHERE td.transaction_id = '$t_id'
		") or die (mysqli_error($conn));

		// Display transaction details
		while ($row = $query2->fetch_array()) {
			$oqty = $row['quantity'];
			$pname = $row['product_name'];
			$psize = $row['product_size']; // Correct size from transaction_detail
			$pprice = $row['product_price'];

			echo "<tr>
					<td>$oqty</td>
					<td>$pname</td>
					<td>$psize</td>
					<td>$pprice</td>
				  </tr>";
		}
		?>

	</table>
	<legend></legend>
	<h4>TOTAL: Php <?php echo $amnt; ?></h4>
	</center>
	</div>

	<div class='pull-right'>
	<div class="add"><a onclick="javascript:printDiv('printablediv')" name="print" style="cursor:pointer;" class="btn btn-info"><i class="icon-white icon-print"></i> Print Receipt</a></div>
	</div>
	</form>
			</div>
			</div>

</body>
</html>
