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
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background: #f8f9fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        #header {
            background-color: #333;
            color: #fff;
            padding: 15px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        #header img {
            height: 50px;
            vertical-align: middle;
        }

        #header label {
            font-size: 24px;
            vertical-align: middle;
            margin-left: 10px;
            font-weight: bold;
        }

        #container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .form-container {
            width: 100%;
            max-width: 400px;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            margin: 0 0 20px;
            font-size: 24px;
            text-align: center;
            color: #333;
            font-weight: bold;
        }

        .form-container label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .form-container input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }

        .form-container .signup-link {
            margin-top: 10px;
            text-align: center;
            font-size: 14px;
        }

        .form-container .signup-link a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .form-container .signup-link a:hover {
            color: #0056b3;
        }

        #footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px;
            font-size: 14px;
        }

        #footer ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        #footer ul li {
            margin: 5px 0;
        }

        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: center;
            font-weight: bold;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
<div id="header">
        <img src="images/logo.jpg" alt="Logo">
        <label>Sneakers Street</label>
    </div>

    <div id="design" style="padding: 20px;">
        <div id="container">
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
                <form method="POST" action="">
                    <label for="firstname">First Name:</label>
                    <input type="text" id="firstname" name="firstname" required>
                    <label for="mi">Middle Initial:</label>
                    <input type="text" id="mi" name="mi" maxlength="1" required>
                    <label for="lastname">Last Name:</label>
                    <input type="text" id="lastname" name="lastname" required>
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" required>
                    <label for="country">Province:</label>
                    <input type="text" id="country" name="country" required>
                    <label for="zipcode">ZIP Code:</label>
                    <input type="text" id="zipcode" name="zipcode" maxlength="4" required>
                    <label for="mobile">Mobile Number:</label>
                    <input type="text" id="mobile" name="mobile" maxlength="11" required>
                    <label for="telephone">Telephone Number:</label>
                    <input type="text" id="telephone" name="telephone" maxlength="8" required>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <button type="submit" name="signup">Sign Up</button>
                </form>
                <div class="signup-link">
                    Already have an account? <a href="login.php">Login here!</a>
                </div>
            </div>
        </div>
    </div>
    
    <div style="padding:20px">
    <div id="footer">
        <div>&copy; Sneakers Street Inc. 2025</div>
        <div>
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
