<?php
try {
    $conn = new PDO('mysql:host=localhost;dbname=ecommerce', 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    // Update the order status in the database
    $sql = "UPDATE orders SET status = :status WHERE order_id = :order_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $oldStatus = $conn->query("SELECT status FROM orders WHERE order_id = $order_id")->fetchColumn();

    if($status != $oldStatus) {
        if ($stmt->execute()) {
            ?>
            <script>
                alert("Order status updated successfully!");
                window.history.back();
            </script>
            <?php
            exit();
        } else {
            ?>
            <script>
                alert("Failed to update order status.");
                window.history.back();
            </script>
            <?php
        }
    } else {
        ?>
        <script>
            alert("Order status is already set to <?php echo $status; ?>.");
            window.history.back();
        </script>
        <?php
    }
    
} else {
    // Redirect back to the orders page if the request method is not POST
    header("Location: index.php?orders");
    exit();
}
?>
