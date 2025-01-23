<?php
	include("function/session.php");
	include("db/dbconn.php");
?>
<!DOCTYPE html>
<html>
<head>
	<title>Sneakers Street</title>
	<link rel = "stylesheet" type = "text/css" href="css/style.css" media="all">
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
</head>
<body>
<div id="header">
    <img src="img/logo.jpg">
    <label>Sneakers Street</label>

    <?php
    $id = (int)$_SESSION['id']; // Cast to integer for safety

    // Fetch customer details securely
    $stmt = $conn->prepare("SELECT * FROM customer WHERE customerid = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $fetch = $result->fetch_assoc();
    } else {
        die("Customer not found.");
    }
    ?>
    <ul>
        <li><a href="function/logout.php"><i class="icon-off icon-white"></i>logout</a></li>
        <li><a href="#profile" href data-toggle="modal">Welcome:&nbsp;&nbsp;&nbsp;
                <i class="icon-user icon-white"></i><?php echo htmlspecialchars($fetch['firstname'] . ' ' . $fetch['lastname']); ?></a></li>
    </ul>
</div>

<div id="container">
    <div id="account">
        <form method="POST" action="function/edit_customer.php">
            <center>
                <h3>Edit My Account...</h3>
                <table>
                    <tr>
                        <td>Firstname:</td>
                        <td><input type="text" name="firstname" placeholder="Firstname" required value="<?php echo htmlspecialchars($fetch['firstname']); ?>"></td>
                    </tr>
                    <tr>
                        <td>M.I. :</td>
                        <td><input type="text" name="mi" placeholder="Middle Initial" maxlength="1" required value="<?php echo htmlspecialchars($fetch['mi']); ?>"></td>
                    </tr>
                    <tr>
                        <td>Lastname:</td>
                        <td><input type="text" name="lastname" placeholder="Lastname" required value="<?php echo htmlspecialchars($fetch['lastname']); ?>"></td>
                    </tr>
                    <tr>
                        <td>Address:</td>
                        <td><input type="text" name="address" placeholder="Address" style="width:430px;" required value="<?php echo htmlspecialchars($fetch['address']); ?>"></td>
                    </tr>
                    <tr>
                        <td>Province:</td>
                        <td><input type="text" name="country" placeholder="Province" required value="<?php echo htmlspecialchars($fetch['country']); ?>"></td>
                    </tr>
                    <tr>
                        <td>ZIP Code:</td>
                        <td><input type="text" name="zipcode" placeholder="ZIP Code" required value="<?php echo htmlspecialchars($fetch['zipcode']); ?>" maxlength="4"></td>
                    </tr>
                    <tr>
                        <td>Mobile Number:</td>
                        <td><input type="text" name="mobile" placeholder="Mobile Number" value="<?php echo htmlspecialchars($fetch['mobile']); ?>" maxlength="11"></td>
                    </tr>
                    <tr>
                        <td>Telephone Number:</td>
                        <td><input type="text" name="telephone" placeholder="Telephone Number" value="<?php echo htmlspecialchars($fetch['telephone']); ?>" maxlength="8"></td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td><input type="email" name="email" placeholder="Email" required value="<?php echo htmlspecialchars($fetch['email']); ?>"></td>
                    </tr>
                    <tr>
                        <td>Password</td>
                        <td><input type="password" name="password" placeholder="Password" required value="<?php echo htmlspecialchars($fetch['password']); ?>"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type="submit" name="edit" value="Save Changes" class="btn btn-primary">
                            <a href="home.php"><input type="button" name="cancel" value="Cancel" class="btn btn-danger"></a>
                        </td>
                    </tr>
                </table>
            </center>
        </form>
    </div>
</div>



	<br>

</div>
</body>
</html>
