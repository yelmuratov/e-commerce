<?php
session_start();

if ($_SESSION['user']['role'] != "admin") {
    echo '<script>alert("You are not authorized to perform this action."); window.location = "index.php";</script>';
    exit();
}

// Check if the form is submitted
if (isset($_POST['save'])) {
    $productId = $_POST['product_id'];
    $productName = $_POST['product_name'];
    $productPrice = $_POST['price'];

    try {
        $conn = new PDO('mysql:host=localhost;dbname=ecommerce', 'root', '');
        $sql = "UPDATE products SET product_name = :product_name, price = :price WHERE product_id = :product_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':product_name', $productName, PDO::PARAM_STR);
        $stmt->bindParam(':price', $productPrice, PDO::PARAM_STR);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        echo '<script>alert("Product updated successfully."); window.location = "index.php";</script>';
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
