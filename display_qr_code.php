<?php
session_start();
include("db/dbconn.php");

$qr_url = urldecode($_GET['qr_url']); // QR code URL from PayMongo
$transaction_id = (int)$_GET['tid']; // Transaction ID

// Fetch transaction and customer details
$query = "
    SELECT c.customerid, c.firstname, c.lastname, c.email, c.mobile, t.transaction_id, t.amount, t.order_stat
    FROM customer c
    JOIN transaction t ON c.customerid = t.customerid
    WHERE t.transaction_id = $transaction_id
";
$result = $conn->query($query) or die(mysqli_error($conn));

if ($result->num_rows > 0) {
    $transaction = $result->fetch_assoc();
    $customer_name = $transaction['firstname'] . ' ' . $transaction['lastname'];
    $amount = $transaction['amount'];
    $status = $transaction['order_stat'];
    $email = $transaction['email'];
    $mobile = $transaction['mobile'];
} else {
    die("Transaction not found.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>GCash Payment</title>
    <link rel="icon" href="images/logo.jpg" />
    <link rel="stylesheet" type="text/css" href="css/style.css" media="all">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <style>
        .well {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 20px auto;
        }
        .btn-success {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-success:hover {
            background-color: #218838;
        }
        .btn-inverse {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-inverse:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
<div id="header">
<img src="images/logo.jpg">
    <label>Sneakers Street</label>
</div>

<div id="container">
    <div class="nav">
        <ul>
            <li><a href="home.php"><i class="icon-home"></i>Home</a></li>
            <li><a href="product1.php"><i class="icon-th-list"></i>Product</a></li>
            <li><a href="aboutus1.php"><i class="icon-bookmark"></i>About Us</a></li>
            <li><a href="contactus1.php"><i class="icon-inbox"></i>Contact Us</a></li>
            <li><a href="privacy1.php"><i class="icon-info-sign"></i>Privacy Policy</a></li>
            <li><a href="faqs1.php"><i class="icon-question-sign"></i>FAQs</a></li>
        </ul>
    </div>

    <div class="well">
        <h2>GCash Payment</h2>
        <p>Please complete your payment using GCash.</p>

        <h3>Transaction Details</h3>
        <p><strong>Transaction ID:</strong> <?php echo $transaction['transaction_id']; ?></p>
        <p><strong>Customer Name:</strong> <?php echo $customer_name; ?></p>
        <p><strong>Total Amount:</strong> Php <?php echo number_format($amount, 2); ?></p>
        <p><strong>Email:</strong> <?php echo $email; ?></p>
        <p><strong>Mobile:</strong> <?php echo $mobile; ?></p>

        <h3>GCash Payment Instructions</h3>
        <ol>
            <li>Open the GCash app on your phone.</li>
            <li>Go to <strong>Scan QR</strong>.</li>
            <li>Scan the QR code below:</li>
        </ol>

        <!-- Display the GCash QR Code -->
        <div style="text-align: center;">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=<?php echo urlencode($qr_url); ?>" alt="GCash QR Code">
            <p><strong>Reference Number:</strong> <?php echo $transaction['transaction_id']; ?></p>
        </div>

        <p>Once the payment is completed, you will be redirected to the success page.</p>

    </div>
</div>

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

<script>
    function checkPaymentStatus() {
        const transactionId = <?php echo $transaction_id; ?>; // Pass the transaction ID to JavaScript
        fetch(`check_payment_status.php?tid=${transactionId}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'Paid') {
                    // Redirect to the success page
                    window.location.href = `success.php?tid=${transactionId}`;
                }
            })
            .catch(error => console.error('Error checking payment status:', error));
    }

    // Check payment status every 5 seconds
    setInterval(checkPaymentStatus, 5000);
</script>
</body>
</html>