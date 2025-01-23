<?php
session_start();
include("db/dbconn.php");

if (!isset($_GET['tid'])) {
    // Redirect if no transaction ID is provided
    header("Location: cart.php");
    exit;
}

$transaction_id = (int)$_GET['tid'];

// Fetch transaction details
$transactionQuery = $conn->query("SELECT * FROM transaction WHERE transaction_id = $transaction_id") or die(mysqli_error($conn));
if ($transactionQuery->num_rows == 0) {
    die("Transaction not found.");
}
$transaction = $transactionQuery->fetch_assoc();

// Fetch customer details
$customer_id = $transaction['customerid'];
$customerQuery = $conn->query("SELECT * FROM customer WHERE customerid = $customer_id") or die(mysqli_error($conn));
$customer = $customerQuery->fetch_assoc();

// Fetch transaction items
$itemsQuery = $conn->query("
    SELECT 
        product.product_name,
        transaction_detail.product_size,
        product.product_price,
        transaction_detail.order_qty
    FROM 
        transaction_detail
    LEFT JOIN 
        product ON product.product_id = transaction_detail.product_id
    WHERE 
        transaction_detail.transaction_id = $transaction_id
") or die(mysqli_error($conn));
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment Failed</title>
    <link rel="stylesheet" type="text/css" href="css/style.css" media="all">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <style>
        .receipt {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        .receipt h2 {
            color: #dc3545;
        }
        .receipt table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        .receipt table th, .receipt table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .receipt table th {
            background: #f8f9fa;
        }
        .btn-retry {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div id="header">
    <img src="img/logo.jpg">
    <label>Sneakers Street</label>
</div>

<div id="container">
    <div class="receipt">
        <h2>Payment Failed</h2>
        <p>Sorry, your payment was not successful. Please try again.</p>

        <h3>Transaction Details</h3>
        <p><strong>Transaction ID:</strong> <?php echo $transaction['transaction_id']; ?></p>
        <p><strong>Customer Name:</strong> <?php echo $customer['firstname'] . ' ' . $customer['lastname']; ?></p>
        <p><strong>Total Amount:</strong> Php <?php echo number_format($transaction['amount'], 2); ?></p>
        <p><strong>Payment Method:</strong> PayMongo (GCash/Card)</p>

        <h3>Order Items</h3>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Size</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                while ($item = $itemsQuery->fetch_assoc()) {
                    $subtotal = $item['product_price'] * $item['order_qty'];
                    $total += $subtotal;
                    echo "<tr>
                        <td>{$item['product_name']}</td>
                        <td>{$item['product_size']}</td>
                        <td>{$item['order_qty']}</td>
                        <td>Php " . number_format($item['product_price'], 2) . "</td>
                        <td>Php " . number_format($subtotal, 2) . "</td>
                    </tr>";
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align: right;"><strong>Total:</strong></td>
                    <td><strong>Php <?php echo number_format($total, 2); ?></strong></td>
                </tr>
            </tfoot>
        </table>

        <div class="text-center">
            <a href="cart.php" class="btn btn-inverse btn-lg btn-retry">
                <i class="icon-refresh"></i> Retry Payment
            </a>
            <a href="home.php" class="btn btn-inverse btn-lg">
                <i class="icon-home"></i> Return to Home
            </a>
        </div>
    </div>
</div>
</body>
</html>