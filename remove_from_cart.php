<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Ensure the product_id is passed via GET and the cart is initialized
if (isset($_GET['product_id']) && isset($_SESSION['cart'])) {
    $product_id = $_GET['product_id'];

    // Remove the product from the cart
    if (($key = array_search($product_id, $_SESSION['cart'])) !== false) {
        unset($_SESSION['cart'][$key]);
    }
}

// Redirect back to the cart page
header("Location: cart.php");
exit();
