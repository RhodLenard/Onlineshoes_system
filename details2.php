<?php
    include("db/dbconn.php"); // Database connection
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sneakers Street</title>
    <link rel="icon" href="images/logo.jpg" />
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
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
    list-style: none; /* Remove default list styling */
    margin: 0 0 0 auto; /* Push the ul to the right */
    display: flex; /* Display list items horizontally */
    justify-content: flex-end; /* Align links to the end (right) */
    gap: 30px; /* Increase space between the links */
    background-color: #333; /* Add a background color */
    border-radius: 5px; /* Add rounded corners */
}

#header ul li a {
    color: #fff; /* Set link color to white */
    text-decoration: none; /* Remove default underline */
    font-weight: bold; /* Make the text bold */
    position: relative; /* Required for pseudo-element positioning */
}

#header ul li a::after {
    content: ''; /* Required for pseudo-element */
    position: absolute; /* Position relative to the link */
    left: 50%; /* Start from the middle */
    bottom: -5px; /* Position below the text */
    width: 0; /* Start with no width */
    height: 2px; /* Thickness of the underline */
    background-color: #fff; /* Color of the underline */
    transition: width 0.3s ease, left 0.3s ease; /* Smooth transition */
}

#header ul li a:hover::after {
    width: 100%; /* Expand to full width */
    left: 0; /* Move to the left edge */
}

        #container {
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
            display: inline;
            margin: 0 10px;
            position: relative;
        }

        .nav ul li a {
            text-decoration: none;
            color: #333;
            font-size: 16px;
            padding: 10px 0;
            transition: color 0.3s ease;
        }

        .nav ul li a::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -5px;
            width: 100%;
            height: 3px;
            background-color: #000;
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .nav ul li a:hover::after,
        .nav ul li a:focus::after {
            transform: scaleX(1);
        }

        #content {
            margin: 20px 0;
            padding: 20px;
            background-color: #f9f9f9;
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

            .nav ul li {
                display: block;
                margin: 10px 0;
            }

            .float {
                flex: 1 1 100%;
            }
        }
    </style>
</head>
<body>
    <div id="header">
        <img src="images/logo.jpg">
        <label>Sneakers Street</label>
        <ul>
            <li><a href="signup.php">Sign Up</a></li>
            <li><a href="login.php">Login</a></li>
        </ul>
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

        <div id="content">
            <div id="product">
            <?php
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $query = $conn->query("SELECT * FROM product WHERE product_id = '$id'");
        $row = $query->fetch_array();

        // Fetch stock levels for each size
        $stockQuery = $conn->query("SELECT product_size, qty FROM stock WHERE product_id = '$id'");
        $stock = [];
        while ($stockRow = $stockQuery->fetch_assoc()) {
            $stock[$stockRow['product_size']] = $stockRow['qty'];
        }
        ?>
        <div>
            <center>
                <img class="img-polaroid" style="width:400px; height:350px;" src="photo/<?php echo $row['product_image']; ?>">
                <h2 class="text-uppercase bg-primary"><?php echo $row['product_name']; ?></h2>
                <h3 class="text-uppercase">Php <?php echo $row['product_price']; ?></h3>

                <!-- Size Selection -->
                <form action="login.php" method="POST">
    <h3 class="text-uppercase">Available Size:</h3>
    <div class="size-container">
        <?php
       
       $sizes = !empty($row['product_size']) ? explode(',', $row['product_size']) : ['No sizes available'];
       foreach ($sizes as $size) {
           $size = trim($size);
           $qty = isset($stock[$size]) ? $stock[$size] : 0;
           $disabled = ($qty == 0) ? 'disabled' : '';
           $class = ($qty == 0) ? 'size-option out-of-stock' : 'size-option';
           
           // Determine the notification message
           $stockMessage = '';
           if ($qty == 0) {
               $stockMessage = '<div class="stock-notif out-of-stock-notif">Out of Stock!</div>';
           } elseif ($qty == 1) {
               $stockMessage = '<div class="stock-notif few-stock-notif">Few Stock Left!</div>';
           }
           
           echo "
               <div class='size-group'>
                   <label style='cursor: pointer;'>
                       <input type='radio' name='product_size' value='$size' style='display: none;' $disabled required>
                       <div class='$class'>
                           $size
                       </div>
                   </label>
                   $stockMessage
               </div>";
       }
       
       
        ?>

        
    </div>

    
    <input type="hidden" name="product_id" value="<?php echo $id; ?>">
    <br><br>
    <input type="submit" class="btn btn-inverse" name="add_to_cart" value="Add to Cart">
</form>

                <br>
                <a href='product1.php'><button class='btn btn-inverse'>Back</button></a>
            </center>
        </div>
    <?php } ?>
        </div>
    </div>

    <style>
        /* Default size option styles */
.size-container {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    max-width: 400px;
    margin: 0 auto;
}

.size-option {
    border: 1px solid #ccc;
    padding: 10px 15px;
    text-align: center;
    border-radius: 5px;
    background: #f9f9f9;
    font-weight: bold;
    transition: border-color 0.3s;
    cursor: pointer;
    flex: 1 1 calc(25% - 10px);
    box-sizing: border-box;
}

.size-option:hover {
    border-color:rgb(0, 0, 0);
}

input[type="radio"]:checked + .size-option {
    border-color: #000;
    background: #f9f9f9;
    color: inherit;
}

/* Disabled size option styling */
.size-option.out-of-stock {
    background: #e0e0e0;
    color: #a0a0a0;
    cursor: not-allowed;
    border-color: #ccc;
}

/* Size group container */
.size-group {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 5px; /* Space between size box and notification */
}

/* Stock notification styling */
.stock-notif {
    display: none;
    text-align: center;
    font-size: 10px;
    opacity: 0; /* Start fully transparent */
    transition: opacity 0.3s ease; /* Smooth fade-in effect */
}

/* Out of Stock notification styling */
.out-of-stock-notif {
    color: #ff4444;
}

/* Few Stock Left notification styling */
.few-stock-notif {
    color: #ff9900;
}

/* Show the notification on hover */
.size-group:hover .stock-notif {
    display: block; /* Show the message */
    opacity: 1; /* Fade to fully visible */
}

/* Style for Add to Cart button with btn-inverse class */
.btn-inverse {
    background-color: #333; /* Dark background */
    color: white; /* White text */
    padding: 10px 20px; /* Padding for better spacing */
    border: 2px solid #333; /* Dark border */
    border-radius: 5px; /* Rounded corners */
    font-size: 16px; /* Font size */
    cursor: pointer; /* Pointer cursor on hover */
    transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease; /* Smooth hover effect */
}

/* Hover effect for Add to Cart button */
.btn-inverse:hover {
    background-color: white; /* White background on hover */
    color: #999; /* Dark text on hover */
    border-color: #333; /* Dark border on hover */
}

    </style>

    <script>
        // JavaScript to ensure selected state stays consistent visually
        document.querySelectorAll('input[type="radio"]').forEach((radio) => {
            radio.addEventListener('change', () => {
                // Uncheck other options
                document.querySelectorAll('.size-option').forEach((option) => {
                    option.classList.remove('selected');
                });

                // Mark the selected option
                if (radio.checked) {
                    radio.nextElementSibling.classList.add('selected');
                }
            });
        });
    </script>

    <div id="footer">
        <div class="foot">
            <label style="font-size:17px;"> Copyright &copy; </label>
            <p style="font-size:25px;">Sneakers Street Inc. 2025</p>
        </div>
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
