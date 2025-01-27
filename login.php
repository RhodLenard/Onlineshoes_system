<?php

include('db/dbconn.php');

if (isset($_POST['login']))

	{
		$email=$_POST['email'];
		$password=$_POST['password'];

		
			$result=$conn->query("SELECT * FROM customer WHERE email='$email' AND password='$password' ")
				or die ('cannot login' . mysqli_error());
			$row=$result->fetch_array  ();
			$run_num_rows = $result->num_rows;
							
						if ($run_num_rows > 0 )
						{
							session_start ();
							$_SESSION['id'] = $row['customerid'];
							header ("location:home.php");
						}
						
						else
						{
							echo "<script>alert('Invalid Email or Password')</script>";
							header("location:home.php");
						}
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sneakers Street</title>
    <link rel="icon" href="images/logo.jpg">
    <style>
        /* General Styles */
        html, body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            height: 100%;
            display: flex;
            flex-direction: column;
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
            list-style-type: none;
            padding: 0;
            margin: 0;
            text-align: right;
        }

        #header ul li {
            display: inline;
            margin: 0 10px;
        }

        #header ul li a {
            color: #fff;
            text-decoration: none;
        }

        #container {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    background: linear-gradient(135deg, #f0f0f0, #e4e4e4);
    min-height: 58.5vh;
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
    display: block;
    margin-top: 20px;
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

        #footer {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        #footer .foot, #footer #develop {
            margin: 10px 0;
        }

        #develop ul {
            list-style-type: none;
            padding: 0;
        }

        #develop ul li {
            margin: 5px 0;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            #header label {
                font-size: 20px;
            }

            .form-container {
                width: 90%;
            }
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
        <h2>Login</h2>
        <form method="POST" action="login.php">
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" name="login">Login</button>
        </form>
        <p>Don't have an account? <a href="signup.php" class="signup-link" style="display: inline;"> Sign up here!</a></p>
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
