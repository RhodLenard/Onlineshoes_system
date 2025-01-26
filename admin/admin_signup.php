<?php
include('../db/dbconn.php'); // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate input
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if username already exists
        $query = $conn->query("SELECT * FROM admin WHERE username = '$username'") or die(mysqli_error($conn));
        if ($query->num_rows > 0) {
            $error = "Username already exists.";
        } else {
            // Insert new admin into the database
            $conn->query("INSERT INTO admin (username, password) VALUES ('$username', '$password')") or die(mysqli_error($conn));
            $success = "Account created successfully. You can now log in.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Sign Up - Sneakers Street</title>
    <link rel="icon" href="../images/logo.jpg">
    <link rel="stylesheet" type="text/css" href="../css/style.css" media="all">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
    <script src="../js/bootstrap.js"></script>
    <script src="../js/jquery-1.7.2.min.js"></script>
    <style>
        /* Form container */
        form.well {
            display: flex;
  justify-content: center;
  align-items: center;
  height: 50vh; /* Full viewport height for centering */
  width: 350px; /* Fixed width for the form */
  padding: 30px; /* Padding for spacing */
  background-color: #ffffff; /* White background */
  border-radius: 10px; /* Rounded corners */
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Soft shadow */
  border: 1px solid #e0e0e0; /* Light border for subtle definition */
  text-align: center; /* Center-align text */
  margin-top:30%;
}

/* Legend (Adminstrator text) */
form.well legend {
  font-size: 24px; /* Larger font size */
  font-weight: bold; /* Bold text */
  color: #333; /* Dark gray color */
  margin-bottom: 20px; /* Spacing below the legend */
}

/* Input container */
.input-container {
  position: relative;
  margin: 10px 0;
}

        /* Input fields */
        .input-container input {
  width: 80%; /* Slightly less than full width for better centering */
  display: block; /* Ensure inputs are block-level for margin auto to work */
  border: 1px solid #ddd; /* Light border */
  border-radius: 8px; /* Rounded corners */
  font-size: 14px; /* Readable font size */
  outline: none; /* Remove default outline */
  transition: border-color 0.3s ease-in-out; /* Smooth transition */
}

        .input-container input:focus {
            border-color: #007bff; /* Highlight input on focus */
        }

        /* Placeholder labels */
        .input-container label {
  position: absolute;
  top: 50%;
  left: 10%;
  transform: translateY(-50%);
  font-size: 14px;
  color: #999;
  pointer-events: none;
  transition: all 0.3s ease-in-out;
}

        /* Move label up when input is focused or has value */
        .input-container input:focus ~ label,
.input-container input:not(:placeholder-shown) ~ label {
  top: 0;
  left: 5%;
  font-size: 12px;
  color: #007bff;
  background-color: #ffffff;
  padding: 0 5px;
}

/* Submit button */
form.well input[type="submit"] {
  width: 200px; /* Fixed width as per your HTML */
  padding: 12px; /* Comfortable padding */
  background-color: #007bff; /* Blue button */
  color: #fff; /* White text */
  border: none; /* Remove border */
  border-radius: 8px; /* Rounded corners */
  font-size: 16px; /* Larger font size */
  cursor: pointer; /* Pointer cursor */
  transition: background-color 0.3s ease-in-out; /* Smooth transition */
  margin: 20px 0 10px 0;
}

form.well input[type="submit"]:hover {
  background-color: #0056b3; /* Darker blue on hover */
}

/* Sign-up button */
form.well a.btn.btn-info {
  width: 200px; /* Fixed width to match the submit button */
  padding: 12px; /* Comfortable padding */
  background-color: #17a2b8; /* Info button color */
  color: #fff; /* White text */
  border: none; /* Remove border */
  border-radius: 8px; /* Rounded corners */
  font-size: 16px; /* Larger font size */
  cursor: pointer; /* Pointer cursor */
  transition: background-color 0.3s ease-in-out; /* Smooth transition */
  text-decoration: none; /* Remove underline */
}

form.well a.btn.btn-info:hover {
  background-color: #138496; /* Darker info color on hover */
}

        /* Alerts */
        .alert {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 8px;
            font-size: 14px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

          /* Add this new CSS for the "Back to Login" button */
        .btn-back-to-login {
            width: 200px; /* Match the width of the Sign Up button */
            padding: 12px; /* Comfortable padding */
            background-color: #17a2b8; /* Info button color */
            color: #fff; /* White text */
            border: none; /* Remove border */
            border-radius: 8px; /* Rounded corners */
            font-size: 16px; /* Larger font size */
            cursor: pointer; /* Pointer cursor */
            transition: background-color 0.3s ease-in-out; /* Smooth transition */
            margin-top: 10px; /* Spacing above the button */
        }

        .btn-back-to-login:hover {
            background-color: #138496; /* Darker info color on hover */
        }
    </style>
</head>
<body>
    <div id="header">
        <img src="../img/logo.jpg">
        <label>Sneakers Street</label>
    </div>

    <div id="signup" style="margin: 50px auto; width: 400px;">
        <form method="POST" class="well">
            <center>
                <legend>Create Admin Account</legend>
                <?php
                if (isset($error)) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
                if (isset($success)) {
                    echo "<div class='alert alert-success'>$success</div>";
                    echo "<script>
                        setTimeout(function() {
                            window.location = 'admin_index.php';
                        }, 1000); // 1000 milliseconds = 1 second
                    </script>";
                }
                ?>
                <div class="input-container">
                    <input type="text" name="username" id="username" placeholder=" " required>
                    <label for="username">Username</label>
                </div>
                <div class="input-container">
                    <input type="password" name="password" id="password" placeholder=" " required>
                    <label for="password">Password</label>
                </div>
                <div class="input-container">
                    <input type="password" name="confirm_password" id="confirm_password" placeholder=" " required>
                    <label for="confirm_password">Confirm Password</label>
                </div>
                <input type="submit" value="Sign Up" class="btn btn-primary">
                <!-- "Back to Login" button inside the form -->
                <button type="button" onclick="window.location.href='admin_index.php'" class="btn-back-to-login">
                    Back to Login
                </button>
            </center>
        </form>
    </div>
</body>
</html>