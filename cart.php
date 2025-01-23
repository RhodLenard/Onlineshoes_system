<?php
include("function/session.php");
include("db/dbconn.php");

// Handle adding to cart
if (isset($_POST['add_to_cart'])) {
    // Validate and sanitize inputs
    $productId = (int)$_POST['product_id'];
    $size = trim($_POST['product_size']);

    // Check if product ID and size are valid
    if ($productId <= 0 || empty($size)) {
        die("Invalid product or size.");
    }

    // Initialize the cart if not already set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Create a composite key using product_id and size
    $compositeKey = $productId . '_' . $size;

    // Check if the product with the same size already exists in the cart
    if (isset($_SESSION['cart'][$compositeKey])) {
        $_SESSION['cart'][$compositeKey]['quantity'] += 1;
    } else {
        // Add product with size and quantity
        $_SESSION['cart'][$compositeKey] = [
            'product_id' => $productId,
            'size' => $size,
            'quantity' => 1,
        ];
    }

    // Ensure a pending transaction exists
    $customerId = (int)$_SESSION['id']; // Ensure customer ID is valid
    if ($customerId <= 0) {
        die("Invalid customer ID.");
    }

    // Fetch or create a pending transaction
    $transactionQuery = $conn->query("SELECT transaction_id FROM transaction WHERE customerid = $customerId AND order_stat = 'Pending' LIMIT 1");
    if ($transactionQuery->num_rows == 0) {
        // Create a new pending transaction
        $conn->query("INSERT INTO transaction (customerid, order_stat) VALUES ($customerId, 'Pending')") or die(mysqli_error($conn));
        $transactionId = $conn->insert_id; // Get the last inserted ID
    } else {
        $transaction = $transactionQuery->fetch_assoc();
        $transactionId = (int)$transaction['transaction_id'];
    }

    // Update the database (transaction_detail table)
    $conn->query("INSERT INTO transaction_detail (transaction_id, product_id, quantity, product_size) 
                  VALUES ($transactionId, $productId, 1, '$size') 
                  ON DUPLICATE KEY UPDATE quantity = quantity + 1")
        or die(mysqli_error($conn));

    // Redirect to the cart page
    header("Location: cart.php");
    exit;
}

// Handle add and remove actions
if (isset($_GET['id'], $_GET['action'], $_GET['size'])) {
    $productId = (int)$_GET['id'];
    $action = $_GET['action']; // Define $action here
    $size = $_GET['size'];

    // Create a composite key using product_id and size
    $compositeKey = $productId . '_' . $size;

    // Initialize the cart if not already set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Fetch the most recent pending transaction for the customer
    $customerId = $_SESSION['id'];
    $transactionQuery = $conn->query("SELECT transaction_id FROM transaction WHERE customerid = $customerId AND order_stat = 'Pending' LIMIT 1");
    if ($transactionQuery->num_rows > 0) {
        $transaction = $transactionQuery->fetch_assoc();
        $transactionId = $transaction['transaction_id'];

        if ($action === 'add') {
            if (isset($_SESSION['cart'][$compositeKey])) {
                // Increment the quantity in the session
                $_SESSION['cart'][$compositeKey]['quantity'] += 1;
        
                // Update the quantity in the database (transaction_detail table)
                $quantity = $_SESSION['cart'][$compositeKey]['quantity'];
                $conn->query("UPDATE transaction_detail 
                              SET quantity = $quantity 
                              WHERE product_id = $productId 
                                AND transaction_id = $transactionId
                                AND product_size = '$size'") // Ensure size is included in the query
                    or die("Error updating transaction_detail: " . mysqli_error($conn));

                    header("Location: cart.php");
                     exit;
            }
        } 
        elseif ($action === 'remove') {
            if (isset($_SESSION['cart'][$compositeKey])) {
                // Decrement the quantity in the session
                $_SESSION['cart'][$compositeKey]['quantity'] -= 1;
        
                // If quantity reaches zero, remove the item from the session and database
                if ($_SESSION['cart'][$compositeKey]['quantity'] <= 0) {
                    unset($_SESSION['cart'][$compositeKey]);
        
                    // Delete the item from the database (transaction_detail table)
                    $conn->query("DELETE FROM transaction_detail 
                                  WHERE product_id = $productId 
                                    AND transaction_id = $transactionId
                                    AND product_size = '$size'")
                        or die("Error deleting from transaction_detail: " . mysqli_error($conn));
        
                    // Check if the transaction has any remaining items
                    $remainingItemsQuery = $conn->query("SELECT COUNT(*) AS item_count FROM transaction_detail WHERE transaction_id = $transactionId");
                    $remainingItems = $remainingItemsQuery->fetch_assoc()['item_count'];
        
                    // If no items are left in the transaction, delete the transaction
                    if ($remainingItems == 0) {
                        $conn->query("DELETE FROM transaction WHERE transaction_id = $transactionId")
                            or die("Error deleting transaction: " . mysqli_error($conn));
                    }
                } else {
                    // Update the quantity in the database (transaction_detail table)
                    $quantity = $_SESSION['cart'][$compositeKey]['quantity'];
                    $conn->query("UPDATE transaction_detail 
                                  SET quantity = $quantity 
                                  WHERE product_id = $productId 
                                    AND transaction_id = $transactionId
                                    AND product_size = '$size'")
                        or die("Error updating transaction_detail: " . mysqli_error($conn));
                }
        
                // Redirect to refresh the cart page
                header("Location: cart.php");
                exit;
            } else {
                echo "<script>alert('Item not found in the cart.');</script>";
            }
        }
    } else {
        // No pending transaction found
        echo "<script>alert('No pending transaction found. Please start a new order.');</script>";
        echo "<script>window.location.href = 'cart.php';</script>";
        exit;
    }
}

// Handle payment
if (isset($_POST['pay_now'])) {
    // Check if the cart is empty
    if (empty($_SESSION['cart'])) {
        echo "<script>alert('Your cart is empty. Please add items to your cart before proceeding to payment.');</script>";
        echo "<script>window.location.href = 'cart.php';</script>";
        exit;
    }

    $customer_id = $_SESSION['id'];
    $total_amount = 0;
    $current_date = date('Y-m-d H:i:s'); // Get the current date and time

    // Calculate the total amount
    foreach ($_SESSION['cart'] as $compositeKey => $details) {
        if (!isset($details['product_id']) || empty($details['product_id'])) {
            continue; // Skip this item if product_id is missing
        }

        $productId = $details['product_id'];
        $query = $conn->query("SELECT * FROM product WHERE product_id = $productId") or die(mysqli_error($conn));
        $product = $query->fetch_assoc();
        $total_amount += $product['product_price'] * $details['quantity'];
    }

    // Fetch the pending transaction for the customer
    $transactionQuery = $conn->query("SELECT transaction_id FROM transaction WHERE customerid = $customer_id AND order_stat = 'Pending' LIMIT 1");
    if ($transactionQuery->num_rows > 0) {
        $transaction = $transactionQuery->fetch_assoc();
        $transaction_id = $transaction['transaction_id'];

        // Update the transaction amount and order date (but keep status as 'Pending')
        $conn->query("UPDATE transaction 
                      SET amount = '$total_amount', order_date = '$current_date' 
                      WHERE transaction_id = $transaction_id")
            or die(mysqli_error($conn));
    } else {
        // If no pending transaction exists, create a new one with status 'Pending'
        $conn->query("INSERT INTO transaction (customerid, amount, order_stat, order_date) 
                      VALUES ('$customer_id', '$total_amount', 'Pending', '$current_date')")
            or die(mysqli_error($conn));
        $transaction_id = $conn->insert_id; // Get the last inserted ID
    }

    // Redirect to the payment summary page
    header("Location: summary.php?tid=$transaction_id");
    exit;
}
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
    <script src="js/carousel.js"></script>
    <script src="js/button.js"></script>
    <script src="js/dropdown.js"></script>
    <script src="js/tab.js"></script>
    <script src="js/tooltip.js"></script>
    <script src="js/popover.js"></script>
    <script src="js/collapse.js"></script>
    <script src="js/modal.js"></script>
    <script src="js/scrollspy.js"></script>
    <script src="js/alert.js"></script>
    <script src="js/transition.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <style>

form.well {
  width: 100%; /* Fixed width for the form */
  padding: 30px; /* Padding for spacing */
  background-color: #ffffff; /* White background */
  border-radius: 10px; /* Rounded corners */
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Soft shadow */
  border: 1px solid #e0e0e0; /* Light border for subtle definition */
  text-align: center; /* Center-align text */
  margin-bottom: 38%;
}

/* Legend (Adminstrator text) */
form.well legend {
  font-size: 24px; /* Larger font size */
  font-weight: bold; /* Bold text */
  color: #333; /* Dark gray color */
  margin-bottom: 20px; /* Spacing below the legend */ 
}

/* Style for the "Add" link */
a[href*="action=add"] {
    background-color: #3498db; /* Blue background */
    color: #ffffff; /* White text */
    padding: 5px 10px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 14px;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

a[href*="action=add"]:hover {
    background-color: #2980b9; /* Darker blue on hover */
    transform: translateY(-2px);
}

/* Style for the "Remove" link */
a[href*="action=remove"] {
    background-color: #e74c3c; /* Red background */
    color: #ffffff; /* White text */
    padding: 5px 10px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 14px;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

a[href*="action=remove"]:hover {
    background-color: #c0392b; /* Darker red on hover */
    transform: translateY(-2px);
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
        $id = (int)$_SESSION['id'];
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

<div id="container">
    <div class="nav" style="margin-top: 20px;">
        <ul>
            <li><a href="home.php"><i class="icon-home"></i>Home</a></li>
            <li><a href="product1.php"><i class="icon-th-list"></i>Product</a></li>
            <li><a href="aboutus1.php"><i class="icon-bookmark"></i>About Us</a></li>
            <li><a href="contactus1.php"><i class="icon-inbox"></i>Contact Us</a></li>
            <li><a href="privacy1.php"><i class="icon-info-sign"></i>Privacy Policy</a></li>
            <li><a href="faqs1.php"><i class="icon-question-sign"></i>FAQs</a></li>
        </ul>
    </div>

    <form method="post" class="well" style="background-color:#fff;">
        <table class="table">
            <label style="font-size:25px;">My Cart</label>
            <tr>
                <th>Image</th>
                <th>Product Name</th>
                <th>Size</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
                <th>Action</th>
            </tr>

            <?php
            if (!empty($_SESSION['cart'])) {
                $total = 0;
                foreach ($_SESSION['cart'] as $compositeKey => $details) {
                    // Ensure product_id is set and valid
                    if (!isset($details['product_id']) || empty($details['product_id'])) {
                        continue; // Skip this item if product_id is missing
                    }

                    $productId = $details['product_id'];
                    $size = $details['size'];
                    $quantity = $details['quantity'];

                    // Fetch product details from the database
                    $query = $conn->query("SELECT * FROM product WHERE product_id = $productId") or die(mysqli_error($conn));
                    if ($query->num_rows > 0) {
                        $product = $query->fetch_assoc();

                        $name = $product['product_name'];
                        $price = $product['product_price'];
                        $image = $product['product_image'];

                        $subtotal = $price * $quantity;
                        $total += $subtotal;

                        echo "<tr>
                            <td><img src='photo/{$image}' width='50'></td>
                            <td>{$name}</td>
                            <td>{$size}</td>
                            <td>{$quantity}</td>
                            <td>{$price}</td>
                            <td>{$subtotal}</td>
                            <td>
                                <a href='cart.php?id={$productId}&size={$size}&action=add'>Add</a>
                                <a href='cart.php?id={$productId}&size={$size}&action=remove'>Remove</a>
                            </td>
                        </tr>";
                    }
                }

                echo "<tr>
                    <td colspan='5'><strong>Total</strong></td>
                    <td colspan='2'><strong>₱ {$total}</strong></td>
                </tr>";
            } else {
                echo "<tr><td colspan='7'>Your cart is empty.</td></tr>";
            }
            ?>
        </table>

        <div class='pull-right'>
            <a href='home.php' class='btn btn-inverse btn-lg'>Continue Shopping</a>
            <button name='pay_now' type='submit' class='btn btn-inverse btn-lg'>Purchase</button>
        </div>
    </form>
</div>

<div id="footer">
    <div class="foot">
        <label style="font-size:17px;"> Copyright &copy; </label>
        <p style="font-size:25px;">Online Shoe Store Inc. 2024 Brought To You by JHARIL JACINTO PINPIN. </p>
    </div>

    <div id="foot">
        <h4>Links</h4>
        <ul>
            <a href="https://www.facebook.com/Mr.JharilJacintoPinpin"><li>Facebook</li></a>
            <a href="https://www.instagram.com/jhaaareal__/"><li>Instagram</li></a>
            <a href="https://ph.pinterest.com/kickscrewcom/"><li>Pinterest</li></a>
            <a href="https://www.tumblr.com/kicksaddictny?redirect_to=%2Fkicksaddictny&source=blog_peepr_view_login_wall"><li>Tumblr</li></a>
        </ul>
    </div>
</div>
</body>
</html>