<?php
session_start(); // Start the session at the beginning

include('../db/dbconn.php'); // Include the database connection file

if (isset($_POST['enter'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use prepared statements to prevent SQL injection
    $query = $conn->prepare("SELECT adminid, password FROM admin WHERE username = ?");
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Regenerate session ID for security
            session_regenerate_id(true);
            // Set session variable to indicate the user is logged in
            $_SESSION['admin_id'] = $row['adminid'];
            header("Location: admin_home.php");
            exit;
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "Invalid username or password.";
    }
}
?><!DOCTYPE html>
<html>
<head>
    <title>Sneakers Street</title>
    <link rel="icon" href="../images/logo.jpg">
    <link rel="stylesheet" type="text/css" href="../css/style.css" media="all">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
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
        <img src="../images/logo.jpg">
        <label>Sneakers Street</label>
    </div>

    <?php include('../function/admin_login.php');?>
		<div id="admin">
        <form method="post" class="well" style="margin-top: 12%">
            <center>
                <legend>Adminstrator</legend>
                <div class="input-container">
                    <input type="text" name="username" id="username" placeholder=" " required>
                    <label for="username">Username</label>
                </div>
                <div class="input-container">
                    <input type="password" name="password" id="password" placeholder=" " required>
                    <label for="password">Password</label>
                </div>
                <input type="submit" name="enter" value="Enter" class="btn btn-primary" style="width:200px;">
                <br><br>
                <a href="admin_signup.php" class="btn btn-info">Create Account</a>
            </center>
        </form>
    </div>
</body>
</html>