<?php
session_start(); // Start the session

// Prevent caching of this page
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    // Retrieve form data
    $product_id = $_POST['product_id'];
    $product_size = $_POST['product_size'];

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        // Store product ID and size in the session for logged-out users
        $_SESSION['add_to_cart_data'] = [
            'product_id' => $product_id,
            'product_size' => $product_size
        ];

        // Redirect to the login page
        header("Location: login.php");
        exit();
    } else {
        // If the user is logged in, process the Add to Cart action
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        if (!isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] = [];
        }
        $_SESSION['cart'][$product_id][$product_size] = ($_SESSION['cart'][$product_id][$product_size] ?? 0) + 1;

        // Redirect to the product details page
        header("Location: details.php?id=$product_id");
        exit();
    }
}
?>