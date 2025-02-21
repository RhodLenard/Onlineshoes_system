<?php
include("function/session.php");
include("db/dbconn.php");

// Check if the transaction ID is provided
if (!isset($_GET['tid'])) {
    header("Location: cart.php");
    exit;
}

$transaction_id = (int)$_GET['tid'];

// Fetch transaction details
$query = $conn->query("SELECT * FROM transaction WHERE transaction_id = '$transaction_id'") or die(mysqli_error($conn));
if ($query->num_rows == 0) {
    die("Transaction not found.");
}
$transaction = $query->fetch_array();

$amount = $transaction['amount'];
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
    <link rel="stylesheet" href="css/summary.css">
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
                <li><a href="product1.php" class="active"><i class="icon-th-list"></i>Product</a></li>
                <li><a href="aboutus1.php"><i class="icon-bookmark"></i>About Us</a></li>
                <li><a href="contactus1.php"><i class="icon-inbox"></i>Contact Us</a></li>
                <li><a href="privacy1.php"><i class="icon-info-sign"></i>Privacy Policy</a></li>
                <li><a href="faqs1.php"><i class="icon-question-sign"></i>FAQs</a></li>
            </ul>
        </div>
    </div>


    <form method="post" class="well">
        <table class="table">
            <label>Summary of Order/s</label>
            <thead>
                <tr>
                    <th>
                        <h5>Quantity</h5>
                    </th>
                    <th>
                        <h5>Product Name</h5>
                    </th>
                    <th>
                        <h5>Size</h5>
                    </th>
                    <th>
                        <h5>Price</h5>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query2 = $conn->query("
                SELECT 
                    product.product_name,
                    transaction_detail.product_size,
                    product.product_price,
                    transaction_detail.quantity AS order_qty
                FROM 
                    transaction_detail
                LEFT JOIN 
                    product ON product.product_id = transaction_detail.product_id
                WHERE 
                    transaction_detail.transaction_id = '$transaction_id'
            ") or die(mysqli_error($conn));

                $totalAmount = 0; // Initialize total amount

                while ($row = $query2->fetch_array()) {
                    $pname = $row['product_name'];
                    $psize = $row['product_size'];
                    $pprice = (float) str_replace(',', '', $row['product_price']); // Remove commas and convert to float
                    $oqty = (int) $row['order_qty'];

                    $subtotal = $pprice * $oqty; // Calculate subtotal for each product
                    $totalAmount += $subtotal; // Add to total amount

                    echo "<tr>";
                    echo "<td data-label='Quantity'>" . $oqty . "</td>";
                    echo "<td data-label='Product Name'>" . $pname . "</td>";
                    echo "<td data-label='Size'>" . $psize . "</td>";
                    echo "<td data-label='Price'>Php " . number_format($pprice, 2) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <legend></legend>
        <h4>TOTAL: Php <?php echo number_format($totalAmount, 2); ?></h4>
    </form>

    <div class="payment-method-container">
        <form action="process_payment.php" method="post" class="payment-form">
            <input type="hidden" name="transaction_id" value="<?php echo $transaction_id; ?>">
            <input type="hidden" name="amount" value="<?php echo $amount; ?>">

            <h4 class="payment-title">Select Payment Method:</h4>

            <div class="payment-options">
                <label class="payment-option">
                    <input type="radio" name="payment_method" value="GCash" required onclick="toggleAddressField(false)">
                    <div class="payment-card">
                        <img src="images/gcash-logo.png" alt="GCash Logo" class="payment-logo">
                        <span class="payment-name">GCash</span>
                    </div>
                </label>

                <label class="payment-option">
                    <input type="radio" name="payment_method" value="Card" required onclick="toggleAddressField(false)">
                    <div class="payment-card">
                        <img src="images/card-logo.png" alt="Card Logo" class="payment-logo">
                        <span class="payment-name">Credit/Debit Card</span>
                    </div>
                </label>

                <label class="payment-option">
                    <input type="radio" name="payment_method" value="Cash" required onclick="toggleAddressField(true)">
                    <div class="payment-card">
                        <img src="images/cash-payment.png" alt="Cash Logo" class="payment-logo">
                        <span class="payment-name">Cash on Delivery</span>
                    </div>
                </label>
            </div>

            <!-- Address Field (Hidden by Default) -->
            <div id="addressField" style="display: none; margin-top: 20px;">
                <h4 class="mb-2"><strong>Complete Delivery Address:</strong></h4>

                <label for="house_number">House Number:</label>
                <input type="text" name="house_number" id="house_number" class="form-control mb-2" required>

                <label for="street">Street:</label>
                <input type="text" name="street" id="street" class="form-control mb-2" required>

                <label for="barangay">Barangay:</label>
                <input type="text" name="barangay" id="barangay" class="form-control mb-2" required>

                <label for="city">City:</label>
                <input type="text" name="city" id="city" class="form-control mb-2" required>

                <label for="province">Province:</label>
                <input type="text" name="province" id="province" class="form-control mb-2" required>

                <label for="postal_code">Postal Code:</label>
                <input type="text" name="postal_code" id="postal_code" class="form-control mb-2" required>

                <label for="landmark">Nearest Landmark (Optional):</label>
                <input type="text" name="landmark" id="landmark" class="form-control mb-2">
            </div>

            <br>
            <button type="submit" name="confirm_payment" class="btn-pay-now">
                Confirm Payment
            </button>
        </form>
    </div>

    <script>
        function toggleAddressField(show) {
            document.getElementById("addressField").style.display = show ? "block" : "none";

            // Set required attribute only when COD is selected
            document.getElementById("house_number").required = show;
            document.getElementById("street").required = show;
            document.getElementById("barangay").required = show;
            document.getElementById("city").required = show;
            document.getElementById("province").required = show;
            document.getElementById("postal_code").required = show;
        }
    </script>


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
                <p style="font-size:25px;">Sneakers Street Inc. 2025</p>
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