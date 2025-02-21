<?php
include("function/session.php");
include("db/dbconn.php");
?>
<!DOCTYPE html>
<html>

<head>
	<title>Sneakers Street</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="images/logo.jpg" />

	<!-- ✅ Slick Carousel -->
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />

	<!-- ✅ Bootstrap CSS & Font Awesome -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

	<!-- ✅ Custom CSS -->
	<link rel="stylesheet" href="css/home.css">
	<link rel="stylesheet" href="css/plist.css">
	<link rel="stylesheet" href="css/cartnotif.css">

	<style>
		#sale-banner img {
			width: 100%;
			max-width: 1200px;
			height: auto;
			border-radius: 10px;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
		}
	</style>
</head>

<body>

	<!-- ✅ Navigation Bar -->
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
		<a class="navbar-brand" href="#">
			<img src="images/logo.jpg" width="30" height="30" class="d-inline-block align-top" alt="">
			Sneakers Street
		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav ml-auto">
				<?php
				$id = (int) $_SESSION['id'];
				$query = $conn->query("SELECT * FROM customer WHERE customerid = '$id'") or die(mysqli_error());
				$fetch = $query->fetch_array();
				?>
				<li class="nav-item">
					<a class="nav-link" href="account.php"><i class="icon-user"></i> <?php echo $fetch['firstname']; ?> <?php echo $fetch['lastname']; ?></a>
				</li>

				<!-- 🔔 Notification Bell with Modal Trigger -->
				<li class="nav-item position-relative">
					<a class="nav-link position-relative notification-bell" href="#" style="position: relative;">
						<i class="fas fa-bell" style="position: relative;">
							<!-- Badge added here like cart-badge -->
							<span class="notif-badge" id="notif-count" style="display: none;">0</span>
						</i>
					</a>
				</li>



				<!-- 🛒 Cart Section -->
				<li class="nav-item">
					<?php
					$cartCount = 0;
					if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
						foreach ($_SESSION['cart'] as $item) {
							$cartCount += $item['quantity'];
						}
					}
					?>

					<a class="nav-link" href="cart.php" style="position: relative;">
						<i class="fas fa-shopping-cart" style="position: relative;"> <!-- Add position: relative to the icon -->
							<p style="display: inline; font: message-box;">Cart</p>
							<?php if ($cartCount > 0) : ?>
								<span class="cart-badge"><?php echo $cartCount; ?></span>
							<?php endif; ?>
						</i>
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="function/logout.php"><i class="icon-off"></i> Logout</a>
				</li>
			</ul>
		</div>
	</nav>

	<!-- 🌟 Main Content -->
	<div id="container">
		<div class="nav">
			<ul>
				<li><a href="home.php" class="active">Home</a></li>
				<li><a href="product1.php">Product</a></li>
				<li><a href="aboutus1.php">About Us</a></li>
				<li><a href="contactus1.php">Contact Us</a></li>
				<li><a href="privacy1.php">Privacy Policy</a></li>
				<li><a href="faqs1.php">FAQs</a></li>
			</ul>
		</div>
	</div>

	<!-- 🎠 Slick Carousel -->
	<div id="carousel">
		<div class="slick-carousel">
			<div><img src="images/basketball.png" alt="Basketball Sneakers"></div>
			<div><img src="images/sneakers.png" alt="Casual Sneakers"></div>
			<div><img src="images/running.png" alt="Running Sneakers"></div>
		</div>
	</div>

	<!-- 🏷️ Sale Banner -->
	<div id="sale-banner" style="text-align: center; margin: 30px 0;">
		<?php
		$sale_query = $conn->query("SELECT image_path FROM sale_banner ORDER BY id DESC LIMIT 1") or die(mysqli_error($conn));
		$sale_row = $sale_query->fetch_assoc();
		if (!empty($sale_row) && !empty($sale_row['image_path'])) {
			echo "<img src='images/" . $sale_row['image_path'] . "' alt='Sale Banner' style='width: 100%; max-width: 1200px; height: auto; border-radius: 10px;'>";
		}
		?>
	</div>

	<h3 style="text-align: center;"> <strong>Feature</h3>

	<!-- 🛍️ Product Listing ✅ RESTORED -->
	<div id="product">
		<?php
		$query = $conn->query("SELECT * FROM product WHERE category='feature' ORDER BY product_id DESC") or die(mysqli_error());
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
				echo "<img src='images/shopping-cart.png' alt='Add to Cart'>";
				echo "</div>";
				echo "<h3>" . $fetch['product_name'] . "</h3>";
				echo "<p>₱ " . number_format($fetch['product_price'], 0) . "</p>";
				echo "</a>";
				echo "</div>";
			}
		}
		if ($all_out_of_stock) {
			echo "<div style='text-align: center; margin-top: 20px;'><span style='color: red; font-weight: bold; font-size: 18px;'>No Stock</span></div>";
		}
		?>
	</div>

	<!-- 🔔 Notification Modal (Fixed & Working) -->
	<div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header bg-dark text-white">
					<h5 class="modal-title" id="notificationModalLabel">Notifications</h5>
					<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" id="notification-list">
					<!-- Notifications loaded dynamically -->
				</div>
			</div>
		</div>
	</div>

	<!-- 🔻 Footer -->
	<div style="padding: 20px;">
		<div id="footer">
			<div class="foot">&copy; Sneakers Street Inc. 2025</div>
		</div>
	</div>

	<!-- ✅ JavaScript Dependencies -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

	<!-- 🎯 Carousel Script -->
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
				arrows: true
			});
		});
	</script>

	<!-- 🔔 Notification Bell AJAX Functionality (Fixed) -->
	<script>
		$(document).ready(function() {
			fetchNotificationCount(); // Initial call

			// 🔄 Poll every 5 seconds for new notifications
			setInterval(fetchNotificationCount, 5000);

			$('.notification-bell').on('click', function() {
				$('#notificationModal').modal('show');
				fetchNotifications();
			});

			$('#notificationModal').on('hidden.bs.modal', function() {
				markNotificationsAsRead(); // ✅ Mark as read when modal is closed
			});

			function fetchNotifications() {
				$.ajax({
					url: 'function/fetch_notifications.php',
					method: 'GET',
					dataType: 'json',
					success: function(response) {
						let output = '';
						let unreadCount = 0;

						if (response.length === 0) {
							output = '<p class="text-center text-muted">No notifications available.</p>';
						} else {
							response.forEach(notification => {
								if (notification.is_read == 0) unreadCount++;
								const productImage = notification.product_image ?
									`<img src="photo/${notification.product_image}" alt="Product Image" style="width: 100px; height: auto; border-radius: 8px;">` :
									'';
								output += `
                        <div class="alert alert-${notification.is_read == 0 ? 'info' : 'secondary'}">
                            ${productImage}
                            <strong>${notification.title}</strong>
                            <p>${notification.message}</p>
                            <small class="text-muted">${new Date(notification.created_at).toLocaleString()}</small>
                        </div>`;
							});
						}
						$('#notification-list').html(output);
						updateBadge(unreadCount);
					}
				});
			}

			function fetchNotificationCount() {
				$.ajax({
					url: 'function/fetch_notifications.php',
					method: 'GET',
					dataType: 'json',
					success: function(response) {
						let unreadCount = 0;
						response.forEach(notification => {
							if (notification.is_read == 0) unreadCount++;
						});
						updateBadge(unreadCount);
					}
				});
			}

			function updateBadge(count) {
				if (count > 0) {
					$('#notif-count').text(count).show();
				} else {
					$('#notif-count').hide();
				}
			}

			function markNotificationsAsRead() {
				$.ajax({
					url: 'function/mark_notifications_read.php',
					method: 'POST',
					success: function(response) {
						$('#notif-count').hide(); // ✅ Immediately hide the badge after marking as read
					}
				});
			}
		});
	</script>
</body>

</html>