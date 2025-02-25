<?php
include("../function/admin_session.php");
include("../db/dbconn.php");

// Fetch current sale image
$current_sale_image = null;
$check = $conn->query("SELECT image_path FROM sale_banner LIMIT 1");
if ($check->num_rows > 0) {
  $current_sale_image = $check->fetch_assoc()['image_path'];
}

// Upload sale image
if (isset($_POST['upload_sale_image'])) {
  $file_name = $_FILES['sale_image']['name'];
  $file_tmp = $_FILES['sale_image']['tmp_name'];
  $file_path = __DIR__ . "/../images/" . $file_name;

  if (move_uploaded_file($file_tmp, $file_path)) {
    if ($current_sale_image && file_exists(__DIR__ . "/../images/" . $current_sale_image)) {
      unlink(__DIR__ . "/../images/" . $current_sale_image);
    }
    if ($check->num_rows > 0) {
      $conn->query("UPDATE sale_banner SET image_path='$file_name'") or die(mysqli_error($conn));
    } else {
      $conn->query("INSERT INTO sale_banner (image_path) VALUES ('$file_name')") or die(mysqli_error($conn));
    }
    echo "<script>alert('Sale image updated successfully!'); window.location='admin_sale.php';</script>";
  } else {
    echo "Failed to upload image.<br>";
  }
}

// Delete sale image
if (isset($_POST['delete_sale_image'])) {
  if ($current_sale_image && file_exists(__DIR__ . "/../images/" . $current_sale_image)) {
    unlink(__DIR__ . "/../images/" . $current_sale_image);
  }
  $conn->query("DELETE FROM sale_banner") or die(mysqli_error($conn));
  $current_sale_image = null; // Update variable after deletion
  echo "<script>alert('Sale image deleted successfully!');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sneakers Street</title>
  <link rel="icon" href="../images/logo.jpg">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
  <link rel="stylesheet" href="../css/admhome.css">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

  <style>
    .main-content {
      margin-left: 240px;
      /* Adjust this based on the sidebar's width */
      padding: 20px;
    }

    /* Responsive Sidebar */
    @media (max-width: 768px) {
      .sidebar {
        width: 100%;
        position: relative;
        height: auto;
      }

      .main-content {
        margin-left: 0;
        padding: 10px;
      }

      .sale-banner-container {
        width: 100%;
        max-width: 100%;
        margin: 0 auto;
      }

      .upload-container {
        width: 100%;
        max-width: 100%;
      }
    }

    /* Adjustments for smaller devices like phones */
    @media (max-width: 480px) {
      .sale-banner-container img {
        width: 100%;
        height: auto;
      }

      .upload-container {
        padding: 20px;
      }

      .btn {
        width: 100%;
      }

      #preview-container {
        width: 100%;
      }
    }


    /* Centering Wrapper */
    .center-wrapper {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
    }

    .sale-banner-container {
      width: 100%;
      max-width: 800px;
      margin-bottom: 30px;
      text-align: center;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      position: relative;
      margin-top: 5vh;
    }

    .sale-banner-container img {
      width: 100%;
      height: auto;
      border-radius: 20px;
    }

    .no-banner {
      padding: 50px;
      font-size: 18px;
      font-weight: bold;
      color: #6b7280;
      background-color: #f9fafb;
      border-radius: 20px;
    }

    .delete-icon-btn {
      background-color: transparent;
      border: none;
      color: rgb(0, 0, 0);
      cursor: pointer;
      text-align: center;
      transition: color 0.3s ease;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .delete-content {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 2px;
      /* Controls the spacing between text and icon */
    }

    .delete-icon-btn:hover {
      color: #dc2626;
    }

    .delete-text {
      font-size: 14px;
      font-weight: bold;
      color: inherit;
      margin: 0;
      color: #dc2626;
      /* Removes extra spacing */
    }

    .delete-icon-btn i {
      font-size: 30px;
    }



    .upload-container {
      background-color: white;
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 450px;
      text-align: center;
    }

    .upload-container label {
      font-size: 18px;
      font-weight: bold;
      color: #333;
    }

    input[type="file"] {
      margin-top: 15px;
      padding: 10px;
      width: 100%;
      border-radius: 10px;
      border: 1px solid #d1d5db;
      cursor: pointer;
    }

    #preview-container {
      margin-top: 20px;
      display: none;
      border: 2px dashed #d1d5db;
      border-radius: 15px;
      padding: 20px;
      position: relative;
    }

    #image-preview {
      max-width: 100%;
      border-radius: 15px;
      transition: transform 0.3s ease;
    }

    #image-preview:hover {
      transform: scale(1.05);
    }

    .btn {
      display: inline-block;
      margin-top: 20px;
      padding: 12px 24px;
      border-radius: 10px;
      border: none;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
      width: 48%;
    }

    .btn-preview {
      background-color: #4f46e5;
      color: white;
    }

    .btn-preview:hover {
      background-color: #4338ca;
    }

    .btn-upload {
      background-color: #10b981;
      color: white;
      display: none;
    }

    .btn-upload:hover {
      background-color: #059669;
    }

    .btn-delete {
      background-color: #ef4444;
      color: white;
      margin-top: 10px;
      display: none;
    }

    .btn-delete:hover {
      background-color: #dc2626;
    }
  </style>
</head>

<body>
  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <img src="../images/logo.jpg" width="30" height="30" class="d-inline-block align-top" alt="">
        Sneakers Street
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <?php
          $id = (int) $_SESSION['admin_id'];
          $query = $conn->query("SELECT * FROM admin WHERE adminid = '$id'") or die(mysqli_error($conn));
          $fetch = $query->fetch_array();
          ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Welcome, <?php echo isset($fetch['username']) ? $fetch['username'] : 'Guest'; ?>
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="../function/admin_logout.php">Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Sidebar -->
  <div class="sidebar">
    <ul class="list-unstyled">
      <li><a href="admin_home.php">Dashboard</a></li>
      <li>
        <a href="#productsSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Products</a>
        <ul class="collapse list-unstyled" id="productsSubmenu">
          <li><a href="admin_feature.php" style="margin-left:15px;">Features</a></li>
          <li><a href="admin_product.php" style="margin-left:15px;">Basketball</a></li>
          <li><a href="admin_football.php" style="margin-left:15px;">Sneakers</a></li>
          <li><a href="admin_running.php" style="margin-left:15px;">Running</a></li>
          <li><a href="admin_sale.php" style="margin-left:15px;">Sale</a></li>
        </ul>
      </li>
      <li><a href="admin_send_notification.php">Notif Customer</a></li>
      <li><a href="transaction.php">Transactions</a></li>
      <li><a href="customer.php">Customers</a></li>
      <li><a href="message.php">Messages</a></li>
      <li><a href="order.php">Orders</a></li>
    </ul>
  </div>



  <!-- Main Content -->
  <div class="main-content">
    <div class="center-wrapper">
      <!-- Display Current Sale Banner -->
      <div class="sale-banner-container">
        <?php if ($current_sale_image) : ?>
          <img src="../images/<?php echo $current_sale_image; ?>" alt="Current Sale Banner">
          <form method="POST" style="position: absolute; top: 15px; right: 15px;">
            <button type="submit" class="delete-icon-btn" name="delete_sale_image" title="Delete Banner" onclick="return confirm('Are you sure you want to delete this sale banner?')">
              <div class="delete-content">
                <span class="delete-text">Delete</span>
                <i class="bi bi-trash3-fill"></i>
              </div>
            </button>
          </form>
        <?php else : ?>
          <div class="no-banner">No Sale Banner Available</div>
        <?php endif; ?>
      </div>

      <!-- Upload Form -->
      <div class="upload-container">
        <form method="POST" enctype="multipart/form-data" id="saleForm">
          <label for="sale_image">Upload Sale Image:</label>
          <input type="file" id="sale_image" name="sale_image" accept="image/*" required>

          <div id="preview-container">
            <p><strong>Image Preview:</strong></p>
            <img id="image-preview" src="" alt="Image Preview">
          </div>

          <button type="button" class="btn btn-preview" id="preview-btn">Preview Image</button>
          <button type="submit" class="btn btn-upload" id="upload-btn" name="upload_sale_image">Upload</button>
          <button type="button" class="btn btn-delete" id="delete-btn">Delete Image</button>
        </form>
      </div>
    </div>
  </div>

  <script>
    const fileInput = document.getElementById('sale_image');
    const previewContainer = document.getElementById('preview-container');
    const imagePreview = document.getElementById('image-preview');
    const previewBtn = document.getElementById('preview-btn');
    const uploadBtn = document.getElementById('upload-btn');
    const deleteBtn = document.getElementById('delete-btn');

    previewBtn.addEventListener('click', function() {
      const file = fileInput.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          imagePreview.src = e.target.result;
          previewContainer.style.display = 'block';
          uploadBtn.style.display = 'inline-block';
          deleteBtn.style.display = 'inline-block';
        };
        reader.readAsDataURL(file);
      } else {
        alert('Please select an image first.');
      }
    });

    deleteBtn.addEventListener('click', function() {
      if (confirm('Are you sure you want to delete this image?')) {
        fileInput.value = '';
        imagePreview.src = '';
        previewContainer.style.display = 'none';
        uploadBtn.style.display = 'none';
        deleteBtn.style.display = 'none';
      }
    });
  </script>
</body>

</html>