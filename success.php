<?php
session_start();
include("db/dbconn.php");

if (!isset($_GET['tid'])) {
    // Redirect if no transaction ID is provided
    header("Location: cart.php");
    exit;
}

$transaction_id = (int)$_GET['tid']; // Cast to integer for safety
if ($transaction_id <= 0) {
    die("Invalid transaction ID.");
}

// Fetch transaction details using prepared statements
$stmt = $conn->prepare("SELECT * FROM transaction WHERE transaction_id = ?");
$stmt->bind_param("i", $transaction_id);
$stmt->execute();
$transaction = $stmt->get_result()->fetch_assoc();
if (!$transaction) {
    die("Transaction not found.");
}

// Fetch customer details
$customer_id = $transaction['customerid'];
$stmt = $conn->prepare("SELECT * FROM customer WHERE customerid = ?");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$customer = $stmt->get_result()->fetch_assoc();

// Fetch transaction items
$itemsQuery = $conn->query("
    SELECT 
        product.product_name,
        transaction_detail.product_size,
        product.product_price,
        transaction_detail.quantity
    FROM 
        transaction_detail
    LEFT JOIN 
        product ON product.product_id = transaction_detail.product_id
    WHERE 
        transaction_detail.transaction_id = $transaction_id
");
if (!$itemsQuery) {
    die("Error fetching transaction items: " . mysqli_error($conn));
}

// Clear the cart after payment is confirmed
if (isset($_SESSION['cart'])) {
    unset($_SESSION['cart']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment Success</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/logo.jpg" />
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
            color: #28a745;
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
        .btn-print {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-print:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
<div id="header">
    <img src="images/logo.jpg">
    <label>Sneakers Street</label>
</div>

<div id="container">
    <div class="receipt">
        <h2>Payment Successful</h2>
        <p>Thank you for your payment! Your order has been processed successfully.</p>

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
                    $subtotal = $item['product_price'] * $item['quantity'];
                    $total += $subtotal;
                    echo "<tr>
                        <td>{$item['product_name']}</td>
                        <td>{$item['product_size']}</td>
                        <td>{$item['quantity']}</td>
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
            <button onclick="window.print()" class="btn-print">
                <i class="icon-print"></i> Print Receipt
            </button>
            <a href="home.php" class="btn-print">
                <i class="icon-home"></i> Return to Home
            </a>
        </div>
    </div>
</div>
</body>
</html>