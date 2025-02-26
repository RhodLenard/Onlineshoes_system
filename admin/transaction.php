<?php
include("../function/admin_session.php");
include("../db/dbconn.php");
?>
<!DOCTYPE html>
<html>

<head>
	<title>Sneakers Street</title>
	<meta charset="UTF-8">

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="../images/logo.jpg">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
	<link rel="stylesheet" href="../css/admhome.css">
	<link rel="stylesheet" href="../css/fea.css">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
	</script>
</head>

<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
		<div class="container-fluid">
			<a class="navbar-brand" href="#">
				<img src="../images/logo.jpg" width="30" height="30" class="d-inline-block align-top" alt="">
				Sneakers Street
			</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav ms-auto">
					<?php
					$id = (int) $_SESSION['admin_id'];
					$query = $conn->query("SELECT * FROM admin WHERE adminid = '$id'") or die(mysqli_error($conn));
					$fetch = $query->fetch_array();
					?>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
							Welcome, <?php echo isset($fetch['username']) ? $fetch['username'] : 'Guest'; ?>
						</a>
						<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
							<li><a class="dropdown-item" href="../function/admin_logout.php">Logout</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</nav>

	<!-- Sidebar -->
	<div class="sidebar">
		<ul class="list-unstyled">
			<li><a href="admin_home.php">Dashboard</a></li>
			<li>
				<a href="#productsSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Products</a>
				<ul class="collapse list-unstyled" id="productsSubmenu">
					<li><a href="admin_feature.php" style="margin-left:15px;">Features</a></li>
					<li><a href="admin_product.php" style="margin-left:15px;">Basketball</a></li>
					<li><a href="admin_football.php" style="margin-left:15px;">Sneakers</a></li>
					<li><a href="admin_running.php" style="margin-left:15px;">Running</a></li>
					<li><a href="admin_sale.php" style="margin-left:15px;">Sale</a></li>
				</ul>
			</li>
			<li><a href="admin_send_notification.php">Notif Customer</a></li>
			<li><a href="transaction.php">Transactions</a></li>
			<li><a href="customer.php">Customers</a></li>
			<li><a href="message.php">Messages</a></li>
			<li><a href="order.php">SALES</a></li>
		</ul>
	</div>

	<div class="main-content" style="padding: 60px 20px;">
		<div class="alert alert-info text-center">
			<h2>Customers</h2>
		</div>
		<div class="mb-3">
			<input type="text" class="form-control" placeholder="Search Customers here..." id="filter">
		</div>

		<div class="alert alert-info">
			<!-- Added table-responsive class for mobile responsiveness -->
			<div class="table-responsive">
				<table class="table table-hover" style="background-color:;">
					<thead>
						<tr style="font-size:16px;">
							<th style="pointer-events: none;">Id</th>
							<th style="pointer-events: none;">Customer Name</th>
							<th style="pointer-events: none;">Image</th>
							<th style="pointer-events: none;">Product Name</th>
							<th style="pointer-events: none;">Date</th>
							<th style="pointer-events: none;">Total Amount</th>
							<th style="pointer-events: none;">Order Status</th>
							<th style="pointer-events: none;">Payment</th>
							<th style="pointer-events: none;">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$query = $conn->query("SELECT transaction.*, customer.*, transaction.payment_method, product.product_name, product.product_image
                       FROM transaction
                       LEFT JOIN customer ON customer.customerid = transaction.customerid
                       LEFT JOIN transaction_detail ON transaction_detail.transaction_id = transaction.transaction_id
                       LEFT JOIN product ON product.product_id = transaction_detail.product_id
                       ORDER BY transaction.order_date DESC")
							or die(mysqli_error($conn));


						while ($fetch = $query->fetch_array()) {
							$id = $fetch['transaction_id'];
							$amnt = $fetch['amount'];
							$o_stat = $fetch['order_stat'];
							$o_date = $fetch['order_date'];
							$payment_method = $fetch['payment_method']; // Fetch payment method
							$name = $fetch['firstname'] . ' ' . $fetch['lastname'];
						?>
							<tr>
								<td><?php echo $id; ?></td>
								<td><?php echo $name; ?></td>
								<td><img src="../photo/<?php echo $fetch['product_image']; ?>" alt="Product Image" width="50" height="50"></td>
								<td><?php echo $fetch['product_name']; ?></td>
								<td><?php echo $o_date; ?></td>
								<td><?php echo '₱' . number_format($amnt, 0); ?></td>
								<td><?php echo $o_stat; ?></td>
								<td><?php echo $payment_method; // Display the payment method 
										?></td>
								<td>
									<a href="receipt.php?tid=<?php echo $id; ?>" class="btn btn-primary btn-sm">
										<i class="bi bi-eye"></i> View
									</a>
									<?php if ($o_stat == 'Paid'): ?>
										<a class="btn btn-success btn-sm" href="confirm.php?id=<?= $id ?>&action=confirm">
											<i class="bi bi-check-circle"></i> Confirm
										</a>
										<a class="btn btn-danger btn-sm" href="confirm.php?id=<?= $id ?>&action=cancel">
											<i class="bi bi-x-circle"></i> Cancel
										</a>
									<?php endif; ?>
								</td>

							</tr>
						<?php
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>


	<?php
	/* stock in */
	if (isset($_POST['stockin'])) {

		$pid = $_POST['pid'];

		$result = $conn->query("SELECT * FROM `stock` WHERE product_id='$pid'") or die(mysqli_error());
		$row = $result->fetch_array();

		$old_stck = $row['qty'];
		$new_stck = $_POST['new_stck'];
		$total = $old_stck + $new_stck;

		$que = $conn->query("UPDATE `stock` SET `qty` = '$total' WHERE `product_id`='$pid'") or die(mysqli_error());

		header("Location:admin_product.php");
	}

	/* stock out */
	if (isset($_POST['stockout'])) {

		$pid = $_POST['pid'];

		$result = $conn->query("SELECT * FROM `stock` WHERE product_id='$pid'") or die(mysqli_error());
		$row = $result->fetch_array();

		$old_stck = $row['qty'];
		$new_stck = $_POST['new_stck'];
		$total = $old_stck - $new_stck;

		$que = $conn->query("UPDATE `stock` SET `qty` = '$total' WHERE `product_id`='$pid'") or die(mysqli_error());

		header("Location:admin_product.php");
	}
	?>

</body>

</html>
<script type="text/javascript">
	$(document).ready(function() {

		$('.remove').click(function() {

			var id = $(this).attr("id");


			if (confirm("Are you sure you want to delete this product?")) {


				$.ajax({
					type: "POST",
					url: "../function/remove.php",
					data: ({
						id: id
					}),
					cache: false,
					success: function(html) {
						$(".del" + id).fadeOut(2000, function() {
							$(this).remove();
						});
					}
				});
			} else {
				return false;
			}
		});
	});
</script>