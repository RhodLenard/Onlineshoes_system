<?php
include("../function/admin_session.php");
include("../db/dbconn.php");

// Form Processing Logic
if (isset($_POST['add'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $brand = $_POST['brand'];
    $category = $_POST['category'];
    $product_size = isset($_POST['product_size']) ? implode(",", $_POST['product_size']) : '';
    $code = rand(0, 98987787866533499);

    $name = $code . $_FILES["product_image"]["name"];
    $type = $_FILES["product_image"]["type"];
    $size = $_FILES["product_image"]["size"];
    $temp = $_FILES["product_image"]["tmp_name"];
    $error = $_FILES["product_image"]["error"];

    if ($error > 0) {
        die("Error uploading file! Code $error.");
    } else {
        if ($size > 30000000000) {
            die("Format is not allowed or file size is too big!");
        } else {
            move_uploaded_file($temp, "../photo/" . $name);

            // Generate a unique product_id
            $product_code = rand(0, 999999999); // Adjust the range as needed

            // Check if the product_id already exists
            $check_query = $conn->query("SELECT * FROM product WHERE product_id = '$product_code'");
            if ($check_query->num_rows > 0) {
                die("Duplicate product_id generated. Please try again.");
            }

            // Insert into product table
            $conn->query("INSERT INTO product (product_id, product_name, product_price, product_image, brand, category, product_size)
                          VALUES ('$product_code', '$product_name', '$product_price', '$name', '$brand', '$category', '$product_size')");

            // Insert into stock table for each selected size
            $sizes = ["US 7", "US 7.5", "US 8", "US 8.5", "US 9", "US 9.5", "US 10", "US 10.5", "US 11", "US 11.5", "US 12"];
            foreach ($sizes as $size) {
                if (isset($_POST['product_size']) && in_array($size, $_POST['product_size'])) {
                    $sanitized_size = str_replace([' ', '.'], '_', $size); // Sanitize size for the quantity key
                    $qty_key = 'qty_' . $sanitized_size; // e.g., qty_US_7_5
                    $qty = isset($_POST[$qty_key]) ? (int)$_POST[$qty_key] : 0; // Ensure quantity is an integer

                    // Debugging: Print size and quantity
                    echo "Size: $size, Quantity: $qty<br>";

                    // Insert into stock table
                    $insert_query = $conn->query("INSERT INTO stock (product_id, product_size, qty)
                                                  VALUES ('$product_code', '$size', '$qty')");
                    if (!$insert_query) {
                        die("Error inserting stock: " . mysqli_error($conn));
                    }
                }
            }

            // Redirect to prevent form resubmission
            header("Location: admin_feature.php");
            exit();
        } // Close the else block
    } // Close the else block
} // Close the if (isset($_POST['add'])) block
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sneakers Street</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css" media="all">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
    <script src="../js/bootstrap.js"></script>
    <script src="../js/jquery-1.7.2.min.js"></script>
    <script src="../js/carousel.js"></script>
    <script src="../js/button.js"></script>
    <script src="../js/dropdown.js"></script>
    <script src="../js/tab.js"></script>
    <script src="../js/tooltip.js"></script>
    <script src="../js/popover.js"></script>
    <script src="../js/collapse.js"></script>
    <script src="../js/modal.js"></script>
    <script src="../js/scrollspy.js"></script>
    <script src="../js/alert.js"></script>
    <script src="../js/transition.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../javascripts/filter.js" type="text/javascript" charset="utf-8"></script>
    <script src="../jscript/jquery-1.9.1.js" type="text/javascript"></script>

    <!-- Le Facebox -->
    <link href="../facefiles/facebox.css" media="screen" rel="stylesheet" type="text/css" />
    <script src="../facefiles/jquery-1.9.js" type="text/javascript"></script>
    <script src="../facefiles/jquery-1.2.2.pack.js" type="text/javascript"></script>
    <script src="../facefiles/facebox.js" type="text/javascript"></script>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('a[rel*=facebox]').facebox();
        });
    </script>

    <style>
        /* Style for size container */
        .size-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        /* Style for each size group */
        .size-group {
            display: flex;
            flex-direction: column;
            align-items: center; /* Center align items */
            gap: 5px; /* Space between size button and quantity input */
        }

        /* Style for size buttons */
        .size-button {
            border: 2px solid #ccc;
            padding: 10px 15px;
            text-align: center;
            background: #f9f9f9;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s, color 0.3s, border-color 0.3s;
            min-width: 60px; /* Fixed width for uniformity */
        }

        /* Hover effect */
        .size-button:hover {
            background: #007bff;
            color: #fff;
            border-color: #007bff;
        }

        /* Selected effect */
        input[type="checkbox"]:checked + .size-button {
            background: #007bff;
            color: #fff;
            border-color: #007bff;
        }

        /* Quantity input style */
        .quantity-input {
            width: 50px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div id="header" style="position:fixed;">
        <img src="../img/logo.jpg">
        <label>Sneakers Street</label>

        <?php
        $id = (int) $_SESSION['admin_id'];
        $query = $conn->query("SELECT * FROM admin WHERE adminid = '$id'") or die(mysqli_error());
        $fetch = $query->fetch_array();
        ?>

<ul>
        <li><a href="../function/admin_logout.php"><i class="icon-off icon-white"></i>logout</a></li>
        <li>Welcome:&nbsp;&nbsp;&nbsp;<i class="icon-user icon-white"></i>
            <?php
            // Check if $fetch['username'] exists before echoing it
            if (isset($fetch['username'])) {
                echo $fetch['username'];
            } else {
                echo "Guest"; // Fallback if username is not available
            }
            ?>
        </li>
    </ul>
    </div>

    <br>

    <a href="#add" role="button" class="btn btn-info" data-toggle="modal" style="position:absolute;margin-left:222px; margin-top:140px;"><i class="icon-plus-sign icon-white"></i>Add Product</a>
    <div id="add" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:400px;">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            <h3 id="myModalLabel">Add Product...</h3>
        </div>
        <div class="modal-body">
            <form method="post" enctype="multipart/form-data">
                <center>
                    <table>
                        <tr>
                            <td><input type="file" name="product_image" required></td>
                        </tr>
                        <?php include("random_id.php");
                        echo '<tr>
                            <td><input type="hidden" name="product_code" value="'.$code.'" required></td>
                        </tr>';
                        ?>
                        <tr>
                            <td><input type="text" name="product_name" placeholder="Product Name" style="width:250px;" required></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="product_price" placeholder="Price" style="width:250px;" required></td>
                        </tr>
                        <tr>
                            <td>
                                <h4>Select Available Sizes:</h4>
                                <div class="size-container">
                                    <?php
                                    $sizes = ["US 7", "US 7.5", "US 8", "US 8.5", "US 9", "US 9.5", "US 10", "US 10.5", "US 11", "US 11.5", "US 12"];
                                    foreach ($sizes as $size) {
                                        $sanitized_size = str_replace([' ', '.'], '_', $size); // Replace spaces and dots with underscores
                                        echo "
                                        <div class='size-group'>
                                            <label style='cursor: pointer;'>
                                                <input type='checkbox' name='product_size[]' value='$size' style='display: none;'>
                                                <div class='size-button'>$size</div>
                                            </label>
                                            <input type='number' name='qty_$sanitized_size' placeholder='Qty' class='quantity-input'>
                                        </div>";
                                    }
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><input type="text" name="brand" placeholder="Brand Name" style="width:250px;" required></td>
                        </tr>
                        <tr>
                            <td><input type="hidden" name="category" value="feature"></td>
                        </tr>
                    </table>
                </center>
        </div>
        <div class="modal-footer">
            <input class="btn btn-primary" type="submit" name="add" value="Add">
            <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Close</button>
            </form>
        </div>
    </div>

    <div id="leftnav">
        <ul>
            <li><a href="admin_home.php" style="color:#333;">Dashboard</a></li>
            <li><a href="admin_home.php">Products</a>
                <ul>
                    <li><a href="admin_feature.php" style="font-size:15px; margin-left:15px;">Features</a></li>
                    <li><a href="admin_product.php" style="font-size:15px; margin-left:15px;">Basketball</a></li>
                    <li><a href="admin_football.php" style="font-size:15px; margin-left:15px;">Sneakers</a></li>
                    <li><a href="admin_running.php" style="font-size:15px; margin-left:15px;">Running</a></li>
                </ul>
            </li>
            <li><a href="transaction.php">Transactions</a></li>
            <li><a href="customer.php">Customers</a></li>
            <li><a href="message.php">Messages</a></li>
            <li><a href="order.php">Orders</a></li>
        </ul>
    </div>

    <div id="rightcontent">
        <div class="alert alert-info"><center><h2>Features</h2></center></div>
        <br />
        <label style="padding:5px; float:right;"><input type="text" name="filter" placeholder="Search Product here..." id="filter"></label>
        <br />

        <div class="alert alert-info">
            <table class="table table-hover" style="background-color:;">
                <thead>
                    <tr style="font-size:20px;">
                        <th>Product Image</th>
                        <th>Product Name</th>
                        <th>Product Price</th>
                        <th>Product Sizes</th>
                        <th>No. of Stock</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                  $query = $conn->query("SELECT * FROM `product` WHERE category='feature' ORDER BY product_id DESC") or die(mysqli_error());
                  while($fetch = $query->fetch_array()) {
                      $id = $fetch['product_id'];
                  ?>
                  <tr class="del<?php echo $id ?>">
                      <td><img class="img-polaroid" src="../photo/<?php echo $fetch['product_image'] ?>" height="70px" width="80px"></td>
                      <td><?php echo $fetch['product_name'] ?></td>
                      <td><?php echo $fetch['product_price'] ?></td>
                      <td><?php echo $fetch['product_size'] ?></td>
                  
                      <?php
                      // Fetch and display the total stock quantity for the product
                      $query1 = $conn->query("SELECT SUM(qty) AS total_qty FROM `stock` WHERE product_id='$id'") or die(mysqli_error());
                      $fetch1 = $query1->fetch_array();
                      $qty = $fetch1['total_qty'] ?? 0; // Default to 0 if no stock is found
                      ?>
                  
                      <td><?php echo $qty; ?></td>
                      <td>
    <div style="display: flex; gap: 5px; align-items: center;">
        <a href='stockin.php?id=<?php echo $id; ?>' class='btn btn-success' rel='facebox'>
            <i class='icon-plus-sign icon-white'></i> Stock In
        </a>
        <a href='stockout.php?id=<?php echo $id; ?>' class='btn btn-danger' rel='facebox'>
            <i class='icon-minus-sign icon-white'></i> Stock Out
        </a>
        <a href='admin_feature.php?delete_id=<?php echo $id; ?>' 
           class='btn btn-danger delete-button'
           onclick="return confirm('Are you sure you want to delete this product?');">
            <i class='icon-trash icon-white'></i> Delete
        </a>
    </div>
</td>
                  </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php
     /* Add or update stock */
   if (isset($_POST['stockin'])) {
    $pid = $_POST['pid']; // Product ID
    $selected_sizes = $_POST['product_size'] ?? []; // Selected sizes
    $quantities = []; // Quantities for selected sizes

    foreach ($selected_sizes as $size) {
        $sanitized_size = str_replace([' ', '.'], '_', $size); // Sanitize size for the quantity key
        $qty_key = 'qty_' . $sanitized_size;
        $quantities[$size] = isset($_POST[$qty_key]) ? (int)$_POST[$qty_key] : 0;
    }

    foreach ($quantities as $size => $qty) {
        if ($qty > 0) { // Process only sizes with valid quantities
            $result = $conn->query("SELECT qty FROM stock WHERE product_id='$pid' AND product_size='$size'");
            if ($result->num_rows > 0) {
                $row = $result->fetch_array();
                $old_qty = (int)$row['qty'];
                $new_qty = $old_qty + $qty;

                $conn->query("UPDATE stock SET qty = '$new_qty' WHERE product_id='$pid' AND product_size='$size'") or die(mysqli_error($conn));
            } else {
                $conn->query("INSERT INTO stock (product_id, product_size, qty) VALUES ('$pid', '$size', '$qty')") or die(mysqli_error($conn));
            }
        }
    }

    // Fetch all sizes for this product and update the product_size field in the product table
    $all_sizes_query = $conn->query("SELECT DISTINCT product_size FROM stock WHERE product_id='$pid'") or die(mysqli_error($conn));
    $all_sizes = [];
    while ($row = $all_sizes_query->fetch_assoc()) {
        $all_sizes[] = $row['product_size'];
    }
    $sizes_string = implode(',', $all_sizes);

    // Update the product_size field in the product table
    $conn->query("UPDATE product SET product_size = '$sizes_string' WHERE product_id = '$pid'") or die(mysqli_error($conn));

    echo "<script>
            alert('Stock added successfully and sizes updated!');
            window.location = 'admin_feature.php';
          </script>";
    exit();
}




    /* stock out */
if (isset($_POST['stockout'])) {
    $pid = $_POST['pid']; // Product ID
    $sizes_to_remove = $_POST['size']; // Array of sizes and quantities to remove

    // Loop through sizes to update stock
    foreach ($sizes_to_remove as $size => $qty_to_remove) {
        $size = $conn->real_escape_string($size); // Sanitize size input
        $qty_to_remove = (int)$qty_to_remove; // Ensure quantity is an integer

        if ($qty_to_remove > 0) {
            // Fetch current stock for the selected size
            $result = $conn->query("SELECT qty FROM stock WHERE product_id='$pid' AND product_size='$size'") or die(mysqli_error($conn));
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $current_stock = (int)$row['qty'];

                if ($qty_to_remove > $current_stock) {
                    echo "<script>alert('Error: Cannot remove more stock than available for size $size.');</script>";
                } else {
                    // Calculate new stock quantity
                    $new_stock = $current_stock - $qty_to_remove;

                    // Update stock if stock remains, or delete row if stock is 0
                    if ($new_stock > 0) {
                        $conn->query("UPDATE stock SET qty = '$new_stock' WHERE product_id='$pid' AND product_size='$size'") or die(mysqli_error($conn));
                    } else {
                        $conn->query("DELETE FROM stock WHERE product_id='$pid' AND product_size='$size'") or die(mysqli_error($conn));
                    }
                }
            } else {
                echo "<script>alert('Error: Size $size not found in stock.');</script>";
            }
        }
    }

    // Redirect back to the product page
    echo "<script>alert('Stock updated successfully!'); window.location = 'admin_feature.php';</script>";
    exit();
}


// Check if delete_id is set in the URL
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id']; // Sanitize product ID

    // Delete associated stock entries
    $conn->query("DELETE FROM stock WHERE product_id = '$delete_id'") or die(mysqli_error($conn));

    // Delete product entry
    $conn->query("DELETE FROM product WHERE product_id = '$delete_id'") or die(mysqli_error($conn));

    // Redirect back with success message
    echo "<script>
            alert('Product deleted successfully!');
            window.location = 'admin_feature.php';
          </script>";
    exit();
}
    ?>
</body>
</html>
<script type="text/javascript">
    $(document).ready(function() {
        $('.remove').click(function() {
            var id = $(this).attr("id");
            if(confirm("Are you sure you want to delete this product?")) {
                $.ajax({
                    type: "POST",
                    url: "../function/remove.php",
                    data: ({id: id}),
                    cache: false,
                    success: function(html) {
                        $(".del" + id).fadeOut(2000, function() { $(this).remove(); });
                    }
                });
            } else {
                return false;
            }
        });
    });
</script>