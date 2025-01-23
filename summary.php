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
    <link rel="icon" href="img/logo.jpg" />
    <link rel="stylesheet" type="text/css" href="css/style.css" media="all">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <script src="js/bootstrap.js"></script>
    <script src="js/jquery-1.7.2.min.js"></script>

    <style>
        /* Payment Method Container */
        .payment-method-container {
            width: 20%;
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Payment Form */
        .payment-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        /* Payment Title */
        .payment-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
            text-align: center;
        }

        /* Payment Options */
        .payment-options {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        /* Payment Option */
        .payment-option {
            display: block;
            cursor: pointer;
        }

        .payment-option input[type="radio"] {
            display: none; /* Hide the default radio button */
        }

        .payment-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .payment-card:hover {
            border-color: #007bff;
            background: #f9f9f9;
        }

        .payment-option input[type="radio"]:checked + .payment-card {
            border-color: #007bff;
            background: #e3f2fd;
        }

        /* Payment Logo */
        .payment-logo {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        /* Payment Name */
        .payment-name {
            font-size: 16px;
            font-weight: 500;
            color: #333;
        }

        /* Pay Now Button */
        .btn-pay-now {
            background: #007bff;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-pay-now:hover {
            background: #0056b3;
        }

        .btn-pay-now i {
            font-size: 18px;
        }

        form.well {
    width: 50%; /* Fixed width for the form */
    padding: 30px; /* Padding for spacing */
    background-color: #ffffff; /* White background */
    border-radius: 10px; /* Rounded corners */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Soft shadow */
    border: 1px solid #e0e0e0; /* Light border for subtle definition */
    text-align: center; /* Center-align text */
    display: flex; /* Add this */
    flex-direction: column; /* Add this */
    align-items: center; /* Add this */
    justify-content: center; /* Add this */
    box-sizing: border-box; /* Add this */
}

/* Legend (Adminstrator text) */
form.well legend {
  font-size: 24px; /* Larger font size */
  font-weight: bold; /* Bold text */
  color: #333; /* Dark gray color */
  margin-bottom: 20px; /* Spacing below the legend */ 
}
    </style>
</head>
<body>
    <div id="header">
        <img src="img/logo.jpg">
        <label>Sneakers Street</label>

        <?php
        $id = (int)$_SESSION['id'];
        $query = $conn->query("SELECT * FROM customer WHERE customerid = '$id'") or die(mysqli_error($conn));
        $fetch = $query->fetch_array();
        ?>

        <ul>
            <li><a href="function/logout.php"><i class="icon-off icon-white"></i>Logout</a></li>
            <li>Welcome:&nbsp;&nbsp;&nbsp;<a href="#profile" data-toggle="modal"><i class="icon-user icon-white"></i><?php echo $fetch['firstname']; ?>&nbsp;<?php echo $fetch['lastname']; ?></a></li>
        </ul>
    </div>

    <div id="profile" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:700px;">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            <h3 id="myModalLabel">My Account</h3>
        </div>
        <div class="modal-body">
            <?php
            $query = $conn->query("SELECT * FROM customer WHERE customerid = '$id'") or die(mysqli_error($conn));
            $fetch = $query->fetch_array();
            ?>
            <center>
                <table>
                    <tr>
                        <td class="profile">Name:</td>
                        <td class="profile"><?php echo $fetch['firstname']; ?>&nbsp;<?php echo $fetch['mi']; ?>&nbsp;<?php echo $fetch['lastname']; ?></td>
                    </tr>
                    <tr>
                        <td class="profile">Address:</td>
                        <td class="profile"><?php echo $fetch['address']; ?></td>
                    </tr>
                    <tr>
                        <td class="profile">Country:</td>
                        <td class="profile"><?php echo $fetch['country']; ?></td>
                    </tr>
                    <tr>
                        <td class="profile">ZIP Code:</td>
                        <td class="profile"><?php echo $fetch['zipcode']; ?></td>
                    </tr>
                    <tr>
                        <td class="profile">Mobile Number:</td>
                        <td class="profile"><?php echo $fetch['mobile']; ?></td>
                    </tr>
                    <tr>
                        <td class="profile">Telephone Number:</td>
                        <td class="profile"><?php echo $fetch['telephone']; ?></td>
                    </tr>
                    <tr>
                        <td class="profile">Email:</td>
                        <td class="profile"><?php echo $fetch['email']; ?></td>
                    </tr>
                </table>
            </center>
        </div>
        <div class="modal-footer">
            <a href="account.php?id=<?php echo $fetch['customerid']; ?>"><input type="button" class="btn btn-success" name="edit" value="Edit Account"></a>
            <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Close</button>
        </div>
    </div>

    <div id="container" style="
    display: flex;
    flex-direction: column; /* Stack items vertically */
    align-items: center; /* Center items horizontally */
    justify-content: center; /* Center items vertically */
    text-align: center; /* Center text inside elements */
    width: 100%; /* Ensure the container takes full width */
    min-height: 100vh; /* Ensure the container takes at least the full viewport height */
    padding: 20px; /* Add some padding for spacing */
    box-sizing: border-box;">
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

        <form method="post" class="well" style="background-color:#fff; overflow:hidden;">
            <table class="table" style="width:50%;">
                <label style="font-size:25px;">Summary of Order/s</label>
                <tr>
                    <th><h5>Quantity</h5></th>
                    <th><h5>Product Name</h5></th>
                    <th><h5>Size</h5></th>
                    <th><h5>Price</h5></th>
                </tr>

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
            
            while ($row = $query2->fetch_array()) {
                $pname = $row['product_name'];
                $psize = $row['product_size'];
                $pprice = $row['product_price'];
                $oqty = $row['order_qty'];
            
                echo "<tr>";
                echo "<td>" . $oqty . "</td>";
                echo "<td>" . $pname . "</td>";
                echo "<td>" . $psize . "</td>";
                echo "<td>Php " . number_format($pprice, 2) . "</td>";
                echo "</tr>";
            }
                ?>
            </table>
            <legend></legend>
            <h4>TOTAL: Php <?php echo number_format($amount, 2); ?></h4>
        </form>

        <div class="payment-method-container">
        <form action="process_payment.php" method="post" class="payment-form">
                <input type="hidden" name="transaction_id" value="<?php echo $transaction_id; ?>">
                <input type="hidden" name="amount" value="<?php echo $amount; ?>">

                <h4 class="payment-title">Select Payment Method:</h4>

                <div class="payment-options">
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="GCash" required>
                        <div class="payment-card">
                            <img src="images/gcash-logo.png" alt="GCash Logo" class="payment-logo">
                            <span class="payment-name">GCash</span>
                        </div>
                    </label>

                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="Card" required>
                        <div class="payment-card">
                            <img src="images/card-logo.png" alt="Card Logo" class="payment-logo">
                            <span class="payment-name">Credit/Debit Card</span>
                        </div>
                    </label>

                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="Cash" required>
                        <div class="payment-card">
                            <img src="images/cash-payment.png" alt="Cash Logo" class="payment-logo">
                            <span class="payment-name">Cash</span>
                        </div>
                    </label>
                </div>

                <br>
                <button type="submit" name="confirm_payment" class="btn-pay-now">
    <i class="icon-check"></i> Confirm Payment
</button>
            </form>
        </div>
    </div>

    <div id="footer">
        <div class="foot">
            <label style="font-size:17px;">Copyright &copy;</label>
            <p style="font-size:25px;">Online Shoe Store Inc. 2024 Brought To You by JHARIL JACINTO PINPIN.</p>
        </div>
        <div id="foot">
            <h4>Links</h4>
            <ul>
                <a href="https://www.facebook.com/Mr.JharilJacintoPinpin"><li>Facebook</li></a>
                <a href="https://www.instagram.com/jhaaareal__/"><li>Instagram</li></a>
                <a href="https://ph.pinterest.com/kickscrewcom/"><li>Pinterest</li></a>
                <a href="https://www.tumblr.com/kicksaddictny"><li>Tumblr</li></a>
            </ul>
        </div>
    </div>
</body>
</html>