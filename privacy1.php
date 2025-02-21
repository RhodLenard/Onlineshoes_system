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
	<link rel=" stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
	<link rel="stylesheet" href="css/loginstyle.css">
	<link rel="stylesheet" href="css/p1.css">
	<link rel="stylesheet" href="css/home.css">
	<link rel="stylesheet" href="css/cartnotif.css">
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


	<div id="container">
		<div class="nav">
			<ul>
				<li><a href="home.php"><i class="icon-home"></i>Home</a></li>
				<li><a href="product1.php"><i class="icon-th-list"></i>Product</a></li>
				<li><a href="aboutus1.php"><i class="icon-bookmark"></i>About Us</a></li>
				<li><a href="contactus1.php"><i class="icon-inbox"></i>Contact Us</a></li>
				<li><a href="privacy1.php" class="active"><i class="icon-info-sign"></i>Privacy Policy</a></li>
				<li><a href="faqs1.php"><i class="icon-question-sign"></i>FAQs</a></li>
			</ul>
		</div>
	</div>

	<div id="content">
		<legend>
			<h3>Privacy Policy</h3>
		</legend>
		<p>The Sneakers Streets respect the privacy of the visitors
			to the <a href="https://sneakersstreets.com/">sneakersstreets.com</a> website and the local websites connected with it, and take great care to protect your
			information.. This privacy policy tells you what information we collect from you, how we may use it and
			the steps we take to ensure that it is protected.
		</p>
		<hr>
		<h4>Protection of visitors information</h4>
		<p>In order to protect the information you provide to us by visiting our website we have implemented various
			security measures. Your personal information is contained behind secured networks and is only accessible
			by a limited number of people, who have special access rights and are required to keep the information
			confidential.Please keep in mind though that whenever you give out personal information online there is a
			risk that third parties may intercept and use that information. While Online Shoe Store strives to protect its user's
			personal information and privacy, we cannot guarantee the security of any information you disclose online
			and you do so at your own risk.</p>
		<hr>
		<h4>Use of cookies</h4>
		<p>A cookie is a small string of information that the website that you visit transfers to your computer for
			identification purposes. Cookies can be used to follow your activity on the website and that information
			helps us to understand your preferences and improve your website experience. Cookies are also used to
			remember for instance your user name and password.</p>
		<p>You can turn off all cookies, in case you prefer not to receive them. You can also have your computer warn
			you whenever cookies are being used. For both options you have to adjust your browser settings
			(like internet explorer). There are also software products available that can manage cookies for you.
			Please be aware though that when you have set your computer to reject cookies, it can limit the
			functionality of the website you visit and it’s possible then that you do not have access to some of the
			features on the website.</p>
		<hr>
		<h4>Online policy</h4>
		<p>The Privacy Policy does not extend to anything that is inherent in the operation of the internet, and
			therefore beyond adidas' control, and is not to be applied in any manner contrary to applicable law or
			governmental regulation. This online privacy policy only applies to information collected through our
			website and not to information collected offline.</p>
	</div>

	<!-- 🔔 Notification Modal (Working) -->
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

	<div style="padding: 20px;">
		<div id="footer">
			<div class="foot">
				<label style="font-size:17px;"> Copyright &copy; </label>
				<p style="font-size:25px;">Sneakers Street Inc. 2025 </p>
			</div>
		</div>

		<!-- ✅ Corrected: Full jQuery for AJAX -->
		<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

		<!-- 🔔 Fixed Notification Bell AJAX Functionality -->
		<script>
			$(document).ready(function() {
				fetchNotificationCount();
				setInterval(fetchNotificationCount, 5000);

				$('.notification-bell').on('click', function() {
					$('#notificationModal').modal('show');
					fetchNotifications();
				});

				$('#notificationModal').on('hidden.bs.modal', function() {
					markNotificationsAsRead();
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
						success: function() {
							$('#notif-count').hide();
						}
					});
				}
			});
		</script>
</body>

</html>