<?php
include("function/session.php");
include("db/dbconn.php");
include("function/cash.php");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/newstyle.css">
    <link rel="stylesheet" href="css/cartnotif.css">
    <script>
        function toggleSizeGuide() {
            var guide = document.getElementById("size-guide");
            if (guide.style.display === "none" || guide.style.display === "") {
                guide.style.display = "block";
            } else {
                guide.style.display = "none";
            }
        }
    </script>
    <style>
        .size-guide-link {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            color: black;
            text-decoration: none;
        }

        .size-guide-link i {
            font-size: 20px;
        }

        /* Custom width for the modal */
        @media (min-width: 992px) {

            /* Applies on larger screens */
            .modal-size-guide .modal-dialog {
                max-width: 900px;
                /* Adjust width as needed */
            }
        }

        @media (max-width: 768px) {
            .modal-dialog {
                max-width: 95% !important;
                /* Make modal almost full-width */
                margin: 10px auto;
                /* Center the modal */
            }

            .modal-body img {
                width: 100% !important;
                /* Make image responsive */
                height: auto;
            }
        }

        .thumbnail-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
            flex-wrap: wrap;
        }

        .thumbnail {
            width: 80px;
            height: auto;
            /* Maintain aspect ratio */
            object-fit: contain;
            /* Prevent cropping */
            border: 2px solid #ccc;
            cursor: pointer;
            transition: transform 0.2s, border-color 0.2s;
        }

        .thumbnail:hover {
            transform: scale(1.1);
            border-color: black;
        }

        .product-image {
            max-width: 400px;
            height: auto;
            object-fit: contain;
            /* Ensures the image is not cropped */
            border: 2px solid #000;
        }
    </style>

</head>

<body>
    <!-- ✅ Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="#">
            <img src="images/logo.jpg" width="30" height="30" class="d-inline-block align-top" alt="">
            Sneakers Street
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <?php
                $id = (int) $_SESSION['id'];
                $query = $conn->query("SELECT * FROM customer WHERE customerid = '$id'") or die(mysqli_error());
                $fetch = $query->fetch_array();
                ?>
                <li class="nav-item">
                    <a class="nav-link" href="account.php"><i class="icon-user"></i> <?php echo $fetch['firstname']; ?> <?php echo $fetch['lastname']; ?></a>
                </li>

                <!-- 🔔 Notification Bell with Modal Trigger -->
                <li class="nav-item position-relative">
                    <a class="nav-link position-relative notification-bell" href="#" style="position: relative;">
                        <i class="fas fa-bell" style="position: relative;">
                            <!-- Badge added here like cart-badge -->
                            <span class="notif-badge" id="notif-count" style="display: none;">0</span>
                        </i>
                    </a>
                </li>

                <!-- 🛒 Cart Section -->
                <li class="nav-item">
                    <?php
                    $cartCount = 0;
                    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $item) {
                            $cartCount += $item['quantity'];
                        }
                    }
                    ?>

                    <a class="nav-link" href="cart.php" style="position: relative;">
                        <i class="fas fa-shopping-cart" style="position: relative;"> <!-- Add position: relative to the icon -->
                            <p style="display: inline; font: message-box;">Cart</p>
                            <?php if ($cartCount > 0) : ?>
                                <span class="cart-badge"><?php echo $cartCount; ?></span>
                            <?php endif; ?>
                        </i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="function/logout.php"><i class="icon-off"></i> Logout</a>
                </li>
            </ul>
        </div>
    </nav>


    <div id="container">
        <div class="nav">
            <ul>
                <li><a href="home.php"><i class="icon-home"></i>Home</a></li>
                <li><a href="product1.php" class="active"><i class="icon-th-list"></i>Product</a></li>
                <li><a href="aboutus1.php"><i class="icon-bookmark"></i>About Us</a></li>
                <li><a href="contactus1.php"><i class="icon-inbox"></i>Contact Us</a></li>
                <li><a href="privacy1.php"><i class="icon-info-sign"></i>Privacy Policy</a></li>
                <li><a href="faqs1.php"><i class="icon-question-sign"></i>FAQs</a></li>
            </ul>
        </div>
    </div>




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

        <div id="content">
            <div id="product">

                <center>

                    <!-- Main Product Image -->
                    <center>
                        <img id="main-product-image" class="product-image img-polaroid" src="photo/<?php echo $row['product_image']; ?>" alt="Product Image">
                    </center>

                    <!-- Thumbnail Images -->
                    <div class="thumbnail-container">
                        <img class="thumbnail" src="photo/<?php echo $row['product_image']; ?>" onmouseover="changeMainImage('photo/<?php echo $row['product_image']; ?>')">

                        <?php
                        $imagePaths = ["photo/" . $row['product_image']]; // Include main image in cycle
                        $imageQuery = $conn->query("SELECT image_path FROM product_images WHERE product_id = '$id'");
                        while ($imageRow = $imageQuery->fetch_assoc()) {
                            $imagePaths[] = "photo/" . $imageRow['image_path'];
                            echo "<img class='thumbnail' src='photo/{$imageRow['image_path']}' onmouseover='changeMainImage(\"photo/{$imageRow['image_path']}\")'>";
                        }
                        ?>

                    </div>
                    <h2 class="text-uppercase"><?php echo $row['product_name']; ?></h2>
                    <h3 class="text-uppercase">₱ <?php echo number_format($row['product_price'], 0); ?></h3>


                    <!-- Size Selection -->
                    <form action="cart.php" method="POST">
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
                            <a class="size-guide-link" data-toggle="modal" data-target="#sizeGuideModal">
                                <i class="fas fa-ruler"></i> Size Guide
                            </a>
                            <div id="size-guide" style="display: none;">
                                <img src="images/size-guide.jpg" style="width: 100%; max-width: 800px; height: auto; border: 1px solid #000; display: block; margin: 10px auto;">
                            </div>
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

        <!-- Add CSS and JavaScript -->
        <style>
            /* Default size option styles */
            .size-container {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                max-width: 360px;
                /* Ensure the container fits a 3x3 grid */
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
                flex: 1 1 calc(33.333% - 10px);
                /* Each option takes 1/3 of the row, minus gap */
                box-sizing: border-box;
            }

            .size-option:hover {
                border-color: rgb(0, 0, 0);
            }

            input[type="radio"]:checked+.size-option {
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
                gap: 5px;
                /* Space between size box and notification */
            }

            /* Stock notification styling */
            .stock-notif {
                display: none;
                text-align: center;
                font-size: 10px;
                opacity: 0;
                /* Start fully transparent */
                transition: opacity 0.3s ease;
                /* Smooth fade-in effect */
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
                display: block;
                /* Show the message */
                opacity: 1;
                /* Fade to fully visible */
            }

            /* Style for Add to Cart button with btn-inverse class */
            .btn-inverse {
                background-color: #333;
                /* Dark background */
                color: white;
                /* White text */
                padding: 10px 20px;
                /* Padding for better spacing */
                border: 2px solid #333;
                /* Dark border */
                border-radius: 5px;
                /* Rounded corners */
                font-size: 16px;
                /* Font size */
                cursor: pointer;
                /* Pointer cursor on hover */
                transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
                /* Smooth hover effect */
            }

            /* Hover effect for Add to Cart button */
            .btn-inverse:hover {
                background-color: white;
                /* White background on hover */
                color: #999;
                /* Dark text on hover */
                border-color: #333;
                /* Dark border on hover */
            }
        </style>

        <!-- Size Guide Modal -->
        <div class="modal fade" id="sizeGuideModal" tabindex="-1" role="dialog" aria-labelledby="sizeGuideModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-fullscreen-sm-down" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="sizeGuideModalLabel">Size Guide</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="images/size-guide.jpg" class="img-fluid" alt="Size Guide">
                    </div>
                </div>
            </div>
        </div>


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
        </div>


        <!-- 🔔 Notification Modal (Working) -->
        <div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="notificationModalLabel">Notifications</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="notification-list">
                        <!-- Notifications loaded dynamically -->
                    </div>
                </div>
            </div>
        </div>

        <div style="padding: 20px;">
            <div id="footer">
                <div class="foot">
                    <label style="font-size:17px;"> Copyright &copy; </label>
                    <p style="font-size:25px;">Sneakers Street Inc. 2025 </p>
                </div>
            </div>


            <!-- ✅ Corrected: Full jQuery for AJAX -->
            <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

            <!-- 🔔 Fixed Notification Bell AJAX Functionality -->
            <script>
                $(document).ready(function() {
                    fetchNotificationCount();
                    setInterval(fetchNotificationCount, 5000);

                    $('.notification-bell').on('click', function() {
                        $('#notificationModal').modal('show');
                        fetchNotifications();
                    });

                    $('#notificationModal').on('hidden.bs.modal', function() {
                        markNotificationsAsRead();
                    });

                    function fetchNotifications() {
                        $.ajax({
                            url: 'function/fetch_notifications.php',
                            method: 'GET',
                            dataType: 'json',
                            success: function(response) {
                                let output = '';
                                let unreadCount = 0;
                                if (response.length === 0) {
                                    output = '<p class="text-center text-muted">No notifications available.</p>';
                                } else {
                                    response.forEach(notification => {
                                        if (notification.is_read == 0) unreadCount++;
                                        const productImage = notification.product_image ?
                                            `<img src="photo/${notification.product_image}" alt="Product Image" style="width: 100px; height: auto; border-radius: 8px;">` :
                                            '';
                                        output += `
                                        <div class="alert alert-${notification.is_read == 0 ? 'info' : 'secondary'}">
                                            ${productImage}
                                            <strong>${notification.title}</strong>
                                            <p>${notification.message}</p>
                                            <small class="text-muted">${new Date(notification.created_at).toLocaleString()}</small>
                                        </div>`;
                                    });
                                }
                                $('#notification-list').html(output);
                                updateBadge(unreadCount);
                            }
                        });
                    }

                    function fetchNotificationCount() {
                        $.ajax({
                            url: 'function/fetch_notifications.php',
                            method: 'GET',
                            dataType: 'json',
                            success: function(response) {
                                let unreadCount = 0;
                                response.forEach(notification => {
                                    if (notification.is_read == 0) unreadCount++;
                                });
                                updateBadge(unreadCount);
                            }
                        });
                    }

                    function updateBadge(count) {
                        if (count > 0) {
                            $('#notif-count').text(count).show();
                        } else {
                            $('#notif-count').hide();
                        }
                    }

                    function markNotificationsAsRead() {
                        $.ajax({
                            url: 'function/mark_notifications_read.php',
                            method: 'POST',
                            success: function() {
                                $('#notif-count').hide();
                            }
                        });
                    }
                });
            </script>

            <script>
                let imageIndex = 0;
                let imagePaths = <?php echo json_encode($imagePaths); ?>;
                let cycleInterval;
                let firstImageSrc = imagePaths[0]; // Store the first image as default

                function changeMainImage(imageSrc) {
                    document.getElementById("main-product-image").src = imageSrc;
                }

                // Hovering over the main image starts cycling through images
                document.getElementById("main-product-image").addEventListener("mouseover", function() {
                    clearInterval(cycleInterval); // Reset cycle when hovering again
                    cycleInterval = setInterval(() => {
                        document.getElementById("main-product-image").src = imagePaths[imageIndex];
                        imageIndex = (imageIndex + 1) % imagePaths.length; // Loop through images
                    }, 1000); // Change image every 1 second
                });

                // Stop cycling when mouse leaves and reset to the first image
                document.getElementById("main-product-image").addEventListener("mouseleave", function() {
                    clearInterval(cycleInterval);
                    document.getElementById("main-product-image").src = firstImageSrc; // Reset to first image
                    imageIndex = 0; // Reset index
                });

                // Hovering over a thumbnail updates the main image
                document.querySelectorAll(".thumbnail").forEach(thumbnail => {
                    thumbnail.addEventListener("mouseover", function() {
                        changeMainImage(this.src);
                    });

                    thumbnail.addEventListener("mouseleave", function() {
                        document.getElementById("main-product-image").src = firstImageSrc; // Reset to first image
                    });
                });
            </script>
</body>

</html>