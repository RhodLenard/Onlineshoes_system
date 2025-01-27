<?php
session_start(); // Start the session
include('db/dbconn.php'); // Include database connection

if (isset($_POST['signup'])) {
    // Retrieve form data
    $firstname = $_POST['firstname'];
    $mi = $_POST['mi'];
    $lastname = $_POST['lastname'];
    $address = $_POST['address'];
    $country = $_POST['country'];
    $zipcode = $_POST['zipcode'];
    $mobile = $_POST['mobile'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email already exists
    $query = $conn->query("SELECT * FROM `customer` WHERE `email` = '$email'");
    $check = $query->num_rows;

    if ($check == 1) {
        $_SESSION['signup_message'] = "EMAIL ALREADY EXISTS";
    } else {
        // Insert new user into the database
        $sql = "INSERT INTO customer (firstname, mi, lastname, address, country, zipcode, mobile, telephone, email, password)
                VALUES ('$firstname', '$mi', '$lastname', '$address', '$country', '$zipcode', '$mobile', '$telephone', '$email', '$password')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['signup_message'] = "Signup successful! Redirecting to login page...";
        } else {
            $_SESSION['signup_message'] = "Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Sign Up - Sneakers Street</title>
    <link rel="icon" href="images/logo.jpg" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <script>
        // JavaScript to handle redirection after 2 seconds
        function redirectToLogin() {
            setTimeout(function() {
                window.location.href = "login.php";
            }, 2000); // 2000 milliseconds = 2 seconds
        }
    </script>
    <style>
        /* General Styles */
        html,
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100%;
            /* Ensure full height */
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            /* Minimum height to fill the viewport */
        }

        #header {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        #header img {
            height: 50px;
            vertical-align: middle;
        }

        #header label {
            font-size: 24px;
            vertical-align: middle;
            margin-left: 10px;
        }

        #header ul {
            list-style: none;
            margin: 0;
            display: flex;
            justify-content: flex-end;
            gap: 30px;
            padding: 0;
        }

        #header ul li a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            position: relative;
        }

        #header ul li a::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: -5px;
            width: 0;
            height: 2px;
            background-color: #fff;
            transition: width 0.3s ease, left 0.3s ease;
        }

        #header ul li a:hover::after {
            width: 100%;
            left: 0;
        }

        #container {
            flex: 1;
            /* Expand to fill remaining space */
            padding: 20px;
        }

        .nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            text-align: center;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .nav ul li {
            position: relative;
        }

        .nav ul li a {
            text-decoration: none;
            color: #333;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .nav ul li a::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -5px;
            width: 100%;
            height: 3px;
            background-color: rgb(0, 0, 0);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .nav ul li a:hover::after,
        .nav ul li a:focus::after {
            transform: scaleX(1);
        }

        #content {
            background-color: #f9f9f9;
            padding: 20px;
            margin: 20px 0;
        }

        #product {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .float {
            margin: 10px;
            text-align: center;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 15px;
            width: 250px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            /* Required for absolute positioning of the cart icon */
        }

        .float:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .float img {
            width: 200px;
            height: 200px;
            object-fit: contain;
            border-radius: 10px;
        }

        .float h3 {
            margin: 10px 0;
            font-size: 18px;
            color: #333;
        }

        .float p {
            margin: 5px 0;
            font-size: 16px;
            color: #007bff;
        }

        .float a {
            text-decoration: none;
            color: inherit;
        }

        /* Cart Icon */
        .cart-icon {
            position: absolute;
            top: 10px;
            /* Adjust as needed */
            right: 10px;
            /* Adjust as needed */
            background-color: rgba(255, 255, 255, 0.8);
            /* Semi-transparent background */
            border-radius: 50%;
            /* Circular shape */
            padding: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .cart-icon:hover {
            background-color: rgba(255, 255, 255, 1);
            /* Solid background on hover */
        }

        .cart-icon img {
            width: 24px;
            /* Adjust icon size */
            height: 24px;
            /* Adjust icon size */
        }

        #footer {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
            margin-top: auto;
            /* Ensures footer stays at the bottom */
        }

        #footer .foot,
        #footer #develop {
            margin: 10px 0;
        }

        #develop ul {
            list-style-type: none;
            padding: 0;
        }

        .nav1 {
            text-align: center;
            padding: 30px 0;
            /* Increase padding for more spacing */
        }

        .nav1 ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: inline-flex;
            gap: 25px;
            /* Add gap for better spacing between links */
        }

        .nav1 ul li {
            margin: 0;
            /* Removed redundant margin */
        }

        .nav1 ul li a {
            text-decoration: none;
            color: #111;
            font-size: 18px;
            /* Increase font size */
            font-weight: bold;
            /* Make the text bolder */
            position: relative;
            padding-bottom: 8px;
            /* Add extra padding for clickable area */
        }

        .nav1 ul li a::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: 0;
            width: 0;
            height: 3px;
            /* Increase underline thickness */
            background-color: #111;
            transition: width 0.3s ease, left 0.3s ease;
        }

        .nav1 ul li a:hover::after,
        .nav1 ul li a.active::after {
            width: 100%;
            /* Expand underline to full width */
            left: 0;
            /* Align underline with the link text */
        }

        #fcontainer {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: linear-gradient(135deg, #f0f0f0, #e4e4e4);
            min-height: 50vh;
            /* Adjust to fill the full viewport height */
            margin: 0;
            /* Ensure no margins on the body */
        }

        .form-container {
            max-width: 400px;
            width: 100%;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            animation: slideIn 0.5s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateY(30px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
            font-family: 'Arial', sans-serif;
            font-size: 26px;
            font-weight: bold;
        }

        .form-container label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #555;
        }

        .form-container input {
            box-sizing: border-box;
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
            font-size: 16px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-container input:focus {
            border-color: #007bff;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.25);
            background-color: #fff;
        }

        .form-container button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .form-container button:hover {
            background: linear-gradient(135deg, #0056b3, #003f7f);
            transform: scale(1.02);
        }

        .form-container button:active {
            transform: scale(0.98);
        }

        .form-container .signup-link {
            display: inline;
            margin-top: 20px;
            margin-left: 5px;
            text-align: center;
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .form-container .signup-link:hover {
            color: #0056b3;
        }
    </style>
</head>

<body>
    <div id="header">
        <img src="images/logo.jpg" alt="Logo">
        <label>Sneakers Street</label>
    </div>

    <div id="container">
        <div class="nav">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="product.php">Product</a></li>
                <li><a href="aboutus.php">About Us</a></li>
                <li><a href="contactus.php">Contact Us</a></li>
                <li><a href="privacy.php">Privacy Policy</a></li>
                <li><a href="faqs.php">FAQs</a></li>
            </ul>
        </div>

        <div id="fcontainer">
            <div class="form-container">
                <h2>Sign Up</h2>
                <?php
                // Display the session message if it exists
                if (isset($_SESSION['signup_message'])) {
                    $message_class = strpos($_SESSION['signup_message'], 'success') !== false ? 'success' : 'error';
                    echo '<div class="message ' . $message_class . '">' . $_SESSION['signup_message'] . '</div>';
                    unset($_SESSION['signup_message']); // Clear the message after displaying it

                    // If the message is a success message, trigger the redirection
                    if ($message_class === 'success') {
                        echo '<script>redirectToLogin();</script>';
                    }
                }
                ?>
                <form method="POST" action="" onsubmit="return validateForm()">
                    <label for="firstname">First Name:</label>
                    <input type="text" id="firstname" name="firstname" pattern="[A-Za-z]+" title="First name should only contain letters." required>

                    <label for="mi">Middle Initial:</label>
                    <input type="text" id="mi" name="mi" maxlength="1" pattern="[A-Za-z]" title="Middle initial should be a single letter." required>

                    <label for="lastname">Last Name:</label>
                    <input type="text" id="lastname" name="lastname" pattern="[A-Za-z]+" title="Last name should only contain letters." required>

                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" required>

                    <label for="country">Province:</label>
                    <input type="text" id="country" name="country" pattern="[A-Za-z\s]+" title="Province should only contain letters." required>

                    <label for="zipcode">ZIP Code:</label>
                    <input type="text" id="zipcode" name="zipcode" pattern="\d{4}" title="ZIP Code should be exactly 4 digits." maxlength="4" required>

                    <label for="mobile">Mobile Number:</label>
                    <input type="text" id="mobile" name="mobile" pattern="\d{11}" title="Mobile number should be exactly 11 digits." maxlength="11" required>

                    <label for="telephone">Telephone Number:</label>
                    <input type="text" id="telephone" name="telephone" pattern="\d{8}" title="Telephone number should be exactly 8 digits." maxlength="8" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" minlength="8" title="Password should be at least 8 characters long." required>

                    <button type="submit" name="signup">Sign Up</button>
                </form>

                <script>
                    function validateForm() {
                        const firstname = document.getElementById("firstname").value.trim();
                        const mi = document.getElementById("mi").value.trim();
                        const lastname = document.getElementById("lastname").value.trim();
                        const address = document.getElementById("address").value.trim();
                        const country = document.getElementById("country").value.trim();
                        const zipcode = document.getElementById("zipcode").value.trim();
                        const mobile = document.getElementById("mobile").value.trim();
                        const telephone = document.getElementById("telephone").value.trim();
                        const email = document.getElementById("email").value.trim();
                        const password = document.getElementById("password").value;

                        // Example: Check that mobile and telephone are numbers
                        if (isNaN(mobile) || mobile.length !== 11) {
                            alert("Mobile number should be exactly 11 digits.");
                            return false;
                        }

                        if (isNaN(telephone) || telephone.length !== 8) {
                            alert("Telephone number should be exactly 8 digits.");
                            return false;
                        }

                        // Check password length
                        if (password.length < 8) {
                            alert("Password must be at least 8 characters long.");
                            return false;
                        }

                        return true; // Allow form submission
                    }
                </script>

                <p>Already have an account?<a href="login.php" class="signup-link" tyle="display: inline;">Login here!</a></p>
            </div>
        </div>
    </div>

    <div id="footer">
        <div class="foot">&copy; Sneakers Street Inc. 2025</div>
        <div id="develop">
            <h4>Developed By:</h4>
            <ul>
                <li>JHARIL JACINTO PINPIN</li>
                <li>JONATHS URAGA</li>
                <li>JOSHUA MUSNGI</li>
                <li>TALLE TUBIG</li>
            </ul>
        </div>
    </div>
</body>

</html>