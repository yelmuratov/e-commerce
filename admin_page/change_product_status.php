<?php
    session_start();
    if($_SESSION['user']['role'] != 'admin') {
        ?>
        <script>
            alert("You do not have permission to access this page.");
            window.location = history.back();
        </script>
        <?php
        exit();
    }

    $product_id = $_GET['id'];
    $status = $_GET['status'];

    try {
        $conn = new PDO('mysql:host=localhost;dbname=ecommerce', 'root', '');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("UPDATE products SET status = :status WHERE product_id = :product_id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':product_id', $product_id);

        if ($stmt->execute()) {
            echo '<script>alert("Product status updated successfully!"); window.location = history.back();</script>';
        } else {
            echo '<script>alert("Failed to update product status!"); window.location = history.back();</script>';
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

?>
