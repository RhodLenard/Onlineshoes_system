<?php
// Start the session
session_start();
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
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user into the database
        $sql = "INSERT INTO customer (firstname, mi, lastname, address, country, zipcode, mobile, telephone, email, password)
                VALUES ('$firstname', '$mi', '$lastname', '$address', '$country', '$zipcode', '$mobile', '$telephone', '$email', '$hashed_password')";

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
    <title>Sneakers Street</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/logo.jpg" />
    <link rel=" stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/loginstyle.css">
    <link rel="stylesheet" href="css/p1.css">
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/newstyle.css">
    <link rel="stylesheet" href="css/darkmode.css">
    <style>
        /* Default (Light Mode) */
        #fcontainer {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: linear-gradient(135deg, #f0f0f0, #e4e4e4);
            min-height: 58vh;
            margin: 0;
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

        /* Dark Mode Styles */
        body[data-theme='dark'] #fcontainer {
            background: linear-gradient(135deg, #2c2c2c, #444444);
        }

        body[data-theme='dark'] .form-container {
            background-color: #333;
            /* Dark background for form */
            color: #dcdcdc;
            /* Light text color */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            /* Darker shadow */
        }

        body[data-theme='dark'] .form-container h2 {
            color: #dcdcdc;
            /* Light text for heading */
        }

        body[data-theme='dark'] .form-container label {
            color: #ddd;
            /* Lighter text for labels */
        }

        body[data-theme='dark'] .form-container input {
            background-color: #555;
            /* Dark background for input */
            border: 1px solid #777;
            /* Darker border for input */
            color: #dcdcdc;
            /* Light text inside inputs */
        }

        body[data-theme='dark'] .form-container input:focus {
            border-color: #007bff;
            /* Highlight color on focus */
            background-color: #666;
            /* Dark background on focus */
        }

        body[data-theme='dark'] .form-container button {
            background: linear-gradient(135deg, #0056b3, #003f7f);
            /* Darker blue gradient */
        }

        body[data-theme='dark'] .form-container button:hover {
            background: linear-gradient(135deg, #003f7f, #0056b3);
        }

        body[data-theme='dark'] .form-container .signup-link {
            color: #77b5fe;
            /* Lighter color for signup link in dark mode */
        }

        body[data-theme='dark'] .form-container .signup-link:hover {
            color: #b0d4fe;
            /* Lighter hover color */
        }
    </style>
    <script>
        // JavaScript to handle redirection after 2 seconds
        function redirectToLogin() {
            setTimeout(function() {
                window.location.href = "login.php";
            }, 2000); // 2000 milliseconds = 2 seconds
        }
    </script>
</head>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="#">
        <img src="images/logo.jpg" width="30" height="30" class="d-inline-block align-top" alt="">
        Sneakers Street
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Corrected structure: Now includes the navigation links inside the collapsible div -->
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <div class="theme-switch">
                <div class="toggle-label" id="darkModeToggle">
                    <div class="toggle-knob"></div>
                </div>
            </div>
            <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="product.php">Product</a></li>
            <li class="nav-item"><a class="nav-link" href="aboutus.php">About Us</a></li>
            <li class="nav-item"><a class="nav-link" href="contactus.php">Contact Us</a></li>
            <li class="nav-item"><a class="nav-link" href="privacy.php">Privacy Policy</a></li>
            <li class="nav-item"><a class="nav-link" href="faqs.php">FAQs</a></li>
        </ul>
    </div>
</nav>

<div id="container">
    <div class="nav">
    </div>
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
            <input type="text" id="firstname" name="firstname" pattern="[A-Za-z\s]+" title="First name should only contain letters." required>

            <label for="mi">Middle Initial:</label>
            <input type="text" id="mi" name="mi" maxlength="1" pattern="[A-Za-z]" title="Middle initial should be a single letter." required>

            <label for="lastname">Last Name:</label>
            <input type="text" id="lastname" name="lastname" pattern="[A-Za-z\s]+" title="Last name should only contain letters." required>

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

<div style="padding: 20px;">
    <div id="footer">
        <div class="foot">&copy; Sneakers Street Inc. 2025</div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Popper.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="js/darkMode.js"></script>
    </body>

</html>