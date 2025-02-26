<?php
include("../function/admin_session.php");
include("../db/dbconn.php");
?>
<!DOCTYPE html>
<html>

<head>
	<title>Sneakers Street</title>
	<link rel="icon" href="../images/logo.jpg">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
	<link rel="stylesheet" href="../css/admhome.css">
	<link rel="stylesheet" href="../css/fea.css">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<style>
		/* Ensure the container has proper padding and margin */
		.container {
			padding: 1rem;
			margin-left: 300px;
			margin-top: 5%;
			/* Adjust as necessary for responsiveness */
		}

		/* Responsive card layout */
		.card {
			display: flex;
			flex-direction: column;
			padding: 1rem;
			box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
			border-radius: 8px;
			border: none;
		}

		/* Adjust grid columns for different screen sizes */
		@media (max-width: 1200px) {
			.col-lg-4 {
				flex: 0 0 50%;
				max-width: 50%;
			}
		}

		@media (max-width: 768px) {
			.col-md-6 {
				flex: 0 0 100%;
				max-width: 100%;
			}

			.container {
				margin-left: 0;
				/* Remove margin for smaller screens */
				padding: 1rem;
			}
		}

		@media (max-width: 480px) {
			.container {
				padding: 0.5rem;
			}

			.card {
				padding: 0.75rem;
			}
		}

		/* Sales Summary Section */
		.sales-summary {
			background-color: #f8f9fa;
			padding: 1.5rem;
			margin-bottom: 2rem;
			border-radius: 8px;
			box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
		}

		.sales-summary h3 {
			margin-bottom: 1rem;
			font-size: 1.75rem;
			font-weight: 600;
		}

		.sales-summary .metric {
			font-size: 1.2rem;
			font-weight: 500;
		}

		/* Table Styling */
		.table-responsive {
			margin-top: 1rem;
		}

		.table thead {
			background-color: #f1f1f1;
		}

		.table th,
		.table td {
			padding: 10px;
		}
	</style>
</head>

<body style="display: inline;">
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

	<div class="container py-4">
		<!-- Sales Summary Section -->
		<div class="sales-summary card shadow-sm mb-4" style="border-left: 5px solid #007bff;">
			<div class="card-body">
				<h3 class="card-title text-primary">Sales Overview</h3>
				<div class="row">
					<div class="col-md-4 mb-3">
						<div class="metric-box border rounded p-3 bg-light">
							<h5 class="text-success">Total Sales</h5>
							<p class="metric text-success">₱
								<?php
								$total_sales = $conn->query("SELECT SUM(amount) AS total_sales FROM transaction WHERE order_stat = 'Confirmed'")->fetch_array();
								echo number_format($total_sales['total_sales'], 2);
								?>
							</p>
						</div>
					</div>
					<div class="col-md-4 mb-3">
						<div class="metric-box border rounded p-3 bg-light">
							<h5 class="text-warning">Total Orders</h5>
							<p class="metric text-warning">
								<?php
								$total_orders = $conn->query("SELECT COUNT(transaction_id) AS total_orders FROM transaction WHERE order_stat = 'Confirmed'")->fetch_array();
								echo $total_orders['total_orders'];
								?>
							</p>
						</div>
					</div>
					<div class="col-md-4 mb-3">
						<div class="metric-box border rounded p-3 bg-light">
							<h5 class="text-info">Average Order Value</h5>
							<p class="metric text-info">₱
								<?php
								$avg_order_value = $conn->query("SELECT AVG(amount) AS avg_order_value FROM transaction WHERE order_stat = 'Confirmed'")->fetch_array();
								echo number_format($avg_order_value['avg_order_value'], 2);
								?>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Search Bar -->
		<div class="mb-3">
			<input type="text" class="form-control form-control-lg" placeholder="Search sales here" id="filter">
		</div>

		<!-- Sales Data Table -->
		<div class="table-responsive">
			<table class="table table-bordered table-hover">
				<thead class="thead-dark" style="background-color: #007bff; color: white;">
					<tr>
						<th>Customer Name</th>
						<th>Shoe</th>
						<th>Transaction No.</th>
						<th>Date</th>
						<th>Amount</th>
					</tr>
				</thead>
				<tbody id="salesTableBody">
					<?php
					$Q1 = $conn->query("SELECT transaction.*, customer.firstname, customer.lastname FROM transaction
                    LEFT JOIN customer ON customer.customerid = transaction.customerid
                    WHERE transaction.order_stat = 'Confirmed'");
					while ($r1 = $Q1->fetch_array()) {
						$tid = $r1['transaction_id'];
						$customer_name = $r1['firstname'] . " " . $r1['lastname'];
						$order_date = date('Y-m-d H:i:s', strtotime($r1['order_date']));

						$Q2 = $conn->query("SELECT * FROM transaction_detail 
                        LEFT JOIN product ON product.product_id = transaction_detail.product_id 
                        WHERE transaction_detail.transaction_id = '$tid' ");
						$r2 = $Q2->fetch_array();

						$pid = $r2['product_id'];
						$p_price = $r2['product_price'];
						$brand = $r2['product_name'];

						echo "<tr style='background-color: #f9f9f9;'>";
						echo "<td>" . $customer_name . "</td>";
						echo "<td>" . $brand . "</td>";
						echo "<td>" . $tid . "</td>";
						echo "<td>" . $order_date . "</td>";
						echo "<td>₱" . number_format($p_price, 0) . "</td>";
						echo "</tr>";
					}
					?>
				</tbody>
			</table>
		</div>
	</div>

	<script>
		// Client-side Search functionality
		document.getElementById("filter").addEventListener("input", function() {
			let filter = document.getElementById("filter").value.toLowerCase();
			let rows = document.querySelectorAll("#salesTableBody tr");

			rows.forEach(function(row) {
				let customerName = row.cells[0].textContent.toLowerCase();
				let brand = row.cells[1].textContent.toLowerCase();
				let transactionNo = row.cells[2].textContent.toLowerCase();
				let date = row.cells[3].textContent.toLowerCase();
				let amount = row.cells[4].textContent.toLowerCase();

				if (customerName.includes(filter) || brand.includes(filter) || transactionNo.includes(filter) || date.includes(filter) || amount.includes(filter)) {
					row.style.display = "";
				} else {
					row.style.display = "none";
				}
			});
		});
	</script>





</body>

</html>