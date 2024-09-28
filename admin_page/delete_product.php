<?php
session_start();

if ($_SESSION['user']['role'] != "admin") {
    echo '<script>alert("You are not authorized to perform this action."); window.location = "index.php";</script>';
    exit();
}

if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    try {
        $conn = new PDO('mysql:host=localhost;dbname=ecommerce', 'root', '');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "DELETE FROM products WHERE product_id = :product_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();

        echo '<script>alert("Product deleted successfully."); window.location = "index.php";</script>';
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo '<script>alert("No product ID provided."); window.location = "index.php";</script>';
    exit();
}
?>
