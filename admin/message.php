<?php
include("../function/admin_session.php");
include("../db/dbconn.php");

// Delete message logic
if (isset($_GET['delete_id'])) {
	$contact_id = $_GET['delete_id'];
	$delete_query = "DELETE FROM contact WHERE contact_id = '$contact_id'";
	$result = $conn->query($delete_query) or die(mysqli_error($conn));
	if ($result) {
		echo "<script>alert('Message deleted successfully!'); window.location.href='message.php';</script>";
	}
}
?>
<!DOCTYPE html>
<html>

<head>
	<title>Sneakers Street</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
		<div class="alert alert-info text-center">
			<h2>Customer Messages</h2>
		</div>

		<!-- Search Bar -->
		<div class="mb-3">
			<input type="text" class="form-control" placeholder="Search Customers here..." id="filter">
		</div>

		<!-- Table with Modern Style -->
		<div class="table-responsive">
			<table class="table table-striped table-bordered">
				<thead>
					<tr style="font-size: 18px;">
						<th>Email</th>
						<th>Message</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$query = $conn->query("SELECT * FROM `contact`") or die(mysqli_error());
					while ($fetch = $query->fetch_array()) {
					?>
						<tr>
							<td><?php echo $fetch['email']; ?></td>
							<td>
								<!-- Truncated message -->
								<?php echo substr($fetch['message'], 0, 50) . '...'; ?>
							</td>
							<td>
								<!-- Button to open modal -->
								<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#messageModal<?php echo $fetch['contact_id']; ?>">
									View Full Message
								</button>

								<!-- Delete Button -->
								<a href="?delete_id=<?php echo $fetch['contact_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this message?');">
									<i class="bi bi-trash"></i> Delete
								</a>
							</td>
						</tr>

						<!-- Modal to show full message -->
						<div class="modal fade" id="messageModal<?php echo $fetch['contact_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="messageModalLabel">Full Message</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<!-- Full Message -->
										<p><?php echo nl2br(htmlspecialchars($fetch['message'])); ?></p>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									</div>
								</div>
							</div>
						</div>
					<?php
					}
					?>
				</tbody>
			</table>
		</div>
	</div>

	<!-- Include Bootstrap JS -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>



</body>

</html>