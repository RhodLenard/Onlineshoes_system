<!DOCTYPE html>
<html>
<head>
    <title>Sneakers Street</title>
    <link rel="icon" href="img/logo.jpg" />
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
        <img src="../img/logo.jpg">
        <label>Sneakers Street</label>
    </div>

    <?php include('../function/admin_login.php');?>
		<div id="admin">
        <form method="post" class="well">
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