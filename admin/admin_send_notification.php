<?php
include("../function/admin_session.php");
include("../db/dbconn.php");

// Set the default timezone to Manila
date_default_timezone_set('Asia/Manila');

// ✅ Handle multi-step order notifications, now including "cancelled"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['transaction_id'], $_POST['stage'])) {
  $transaction_id = $_POST['transaction_id'];
  $stage = $_POST['stage'];

  $order_query = $conn->query("SELECT t.transaction_id, t.customerid AS customer_id, c.firstname, p.product_name, p.product_image 
                                 FROM transaction t 
                                 JOIN customer c ON t.customerid = c.customerid 
                                 JOIN transaction_detail td ON t.transaction_id = td.transaction_id 
                                 JOIN product p ON td.product_id = p.product_id 
                                 WHERE t.transaction_id = '$transaction_id'");

  if ($order_query && $order_query->num_rows > 0) {
    $order = $order_query->fetch_assoc();
    $product_name = $order['product_name'];

    $notifications = [
      "processed" => ["Order Processed", "Your order <strong>$product_name</strong> has been processed."],
      "shipped" => ["Order Shipped", "Good news! Your order <strong>$product_name</strong> has been shipped."],
      "out_for_delivery" => ["Out for Delivery", "Heads up! Your order <strong>$product_name</strong> is out for delivery today."],
      "delivered" => ["Order Delivered", "Great news! Your order <strong>$product_name</strong> has been delivered. Enjoy!"],
      "feedback" => ["We Value Your Feedback", "How was your shopping experience? Review your order <strong>$product_name</strong>."],
      "cancelled" => ["Order Cancelled", "We're sorry to inform you that your order for <strong>$product_name</strong> has been cancelled. Contact support for more details."]
    ];
  }

  if (array_key_exists($stage, $notifications)) {
    $customer_id = $order['customer_id'];
    $product_image = $order['product_image'];
    $title = $notifications[$stage][0];
    $message = "Hi " . $order['firstname'] . ", " . $notifications[$stage][1];
    $notification_type = $title;
    $link = "transaction_details.php?transaction_id=" . $transaction_id;
    $created_at = date("Y-m-d H:i:s");

    $query = $conn->prepare("INSERT INTO notifications (customer_id, title, message, is_read, notification_type, link, created_at) VALUES (?, ?, ?, 0, ?, ?, ?)");
    $query->bind_param("isssss", $customer_id, $title, $message, $notification_type, $link, $created_at);

    echo $query->execute()
      ? json_encode(["status" => "success", "message" => "$title notification sent.", "product_image" => $product_image, "product_name" => $product_name])
      : json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
  } else {
    echo json_encode(["status" => "error", "message" => "Invalid stage specified."]);
  }
  exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sneakers Street - Real-Time Notifications</title>
  <link rel="icon" href="../images/logo.jpg">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
  <link rel="stylesheet" href="../css/admhome.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <img src="../images/logo.jpg" width="30" height="30" alt="">
        Sneakers Street
      </a>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <?php
          $id = (int) $_SESSION['admin_id'];
          $query = $conn->query("SELECT * FROM admin WHERE adminid = '$id'");
          $fetch = $query->fetch_array();
          ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
              Welcome, <?php echo $fetch['username']; ?>
            </a>
            <ul class="dropdown-menu">
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
      <li><a href="order.php">Orders</a></li>
    </ul>
  </div>

  <div class="main-content" style="padding: 60px 20px;">
    <div class="container">
      <h2>Transactions - Real-Time Notifications</h2>

      <div id="notification-response"></div>

      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Transaction ID</th>
              <th>Customer Name</th>
              <th>Transaction Date</th>
              <th>Payment Method</th> <!-- ✅ New Column -->
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $orders = $conn->query("SELECT t.transaction_id, c.firstname, c.lastname, t.order_date AS transaction_date, t.order_stat AS status, t.payment_method 
                       FROM transaction t 
                       JOIN customer c ON t.customerid = c.customerid 
                       ORDER BY t.transaction_id DESC"); // Latest first
            if ($orders) {
              while ($order = $orders->fetch_assoc()) {
                // ✅ Skip transactions without a valid transaction date
                if (empty($order['transaction_date'])) continue;

                echo "<tr>
          <td>{$order['transaction_id']}</td>
          <td>{$order['firstname']} {$order['lastname']}</td>
          <td>{$order['transaction_date']}</td>
          <td>{$order['payment_method']}</td> <!-- ✅ Payment Method Added -->
          <td>{$order['status']}</td>
          <td>
              <button class='btn btn-success btn-sm notify-btn me-1 mb-1' data-id='{$order['transaction_id']}' data-stage='processed'>Processed</button>
              <button class='btn btn-warning btn-sm notify-btn me-1 mb-1' data-id='{$order['transaction_id']}' data-stage='shipped'>Shipped</button>
              <button class='btn btn-info btn-sm notify-btn me-1 mb-1' data-id='{$order['transaction_id']}' data-stage='out_for_delivery'>Out for Delivery</button>
              <button class='btn btn-primary btn-sm notify-btn me-1 mb-1' data-id='{$order['transaction_id']}' data-stage='delivered'>Delivered</button>
              <button class='btn btn-secondary btn-sm notify-btn me-1 mb-1' data-id='{$order['transaction_id']}' data-stage='feedback'>Feedback</button>
              <button class='btn btn-danger btn-sm notify-btn me-1 mb-1' data-id='{$order['transaction_id']}' data-stage='cancelled'>Cancel Order</button>
          </td>
        </tr>";
              }
            } else {
              echo "<tr><td colspan='6'>No transactions found.</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      $('.notify-btn').on('click', function() {
        const transactionId = $(this).data('id');
        const stage = $(this).data('stage');
        $.ajax({
          url: '',
          method: 'POST',
          data: {
            transaction_id: transactionId,
            stage: stage
          },
          dataType: 'json',
          success: function(response) {
            const alertClass = response.status === 'success' ? 'alert-success' : 'alert-danger';
            const productImage = response.product_image ? `<img src="../photo/${response.product_image}" alt="Product Image" style="width: 100px; height: auto; border-radius: 8px;">` : '';
            const productName = response.product_name ? `<p><strong>Product:</strong> ${response.product_name}</p>` : '';
            $('#notification-response').html(`
                            <div class="alert ${alertClass} mt-3">
                                ${productImage}
                                ${productName}
                                <p>${response.message}</p>
                            </div>
                        `);
            setTimeout(() => $('#notification-response').html(''), 5000);
          },
          error: function() {
            $('#notification-response').html('<div class="alert alert-danger mt-3">An unexpected error occurred.</div>');
          }
        });
      });
    });
  </script>
</body>

</html>