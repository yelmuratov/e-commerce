<?php
    session_start();
    if(!isset($_SESSION['user'])) {
        ?>
        <script>
            window.location = '../login.php';
        </script>
        <?php
        exit();
    }

    if (isset($_POST['product_id']) && isset($_POST['status'])) {
        $product_id = $_POST['product_id'];
        $status = $_POST['status'];
    
        try {
            // Database connection
            $conn = new PDO('mysql:host=localhost;dbname=ecommerce', 'root', '');
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            // Update the product status in the database
            $stmt = $conn->prepare("UPDATE products SET status = :status WHERE product_id = :product_id");
            $stmt->bindParam(':status', $status, PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    
            // Execute the update and check for success
            if ($stmt->execute()) {
                echo '<script>alert("Product status updated successfully!"); window.location = history.back();</script>';
            } else {
                echo '<script>alert("Failed to update product status!"); window.location = history.back();</script>';
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo '<script>alert("Invalid request!"); window.location = history.back();</script>';
    }

?>
