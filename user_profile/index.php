<?php
session_start();
$user_id = $_SESSION['user']['user_id'];

// Database connection
$conn = new PDO("mysql:host=localhost;dbname=ecommerce;", "root", "");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Fetch user orders where the user is the product owner
$stmtOrders = $conn->prepare("SELECT * FROM orders WHERE owner_id = :user_id");
$stmtOrders->bindParam(':user_id', $user_id);
$stmtOrders->execute();
$userOrders = $stmtOrders->fetchAll(PDO::FETCH_ASSOC);

// Fetch user's products
$stmtProducts = $conn->prepare("SELECT * FROM products WHERE owner_id = :user_id");
$stmtProducts->bindParam(':user_id', $user_id);
$stmtProducts->execute();
$userProducts = $stmtProducts->fetchAll(PDO::FETCH_ASSOC);

// Handle order status update (if user submitted form)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    // Update the order status
    $stmtUpdateStatus = $conn->prepare("UPDATE orders SET status = :status WHERE order_id = :order_id AND owner_id = :user_id");
    $stmtUpdateStatus->bindParam(':status', $new_status);
    $stmtUpdateStatus->bindParam(':order_id', $order_id);
    $stmtUpdateStatus->bindParam(':user_id', $user_id);

    if ($stmtUpdateStatus->execute()) {
        echo '<script>alert("Order status updated successfully!"); window.location.reload();</script>';
    } else {
        echo '<script>alert("Failed to update order status.");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="row">
        <!-- User Information Section -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4>User Information</h4>
                </div>
                <div class="card-body">
                    <p><strong class="p-1">Name:</strong> <?= $_SESSION['user']['first_name'] ?></p>
                    <p><strong class="p-1">Email:</strong> <?= $_SESSION['user']['email'] ?></p>
                    <p><strong class="p-1">Member since:</strong> <?= $_SESSION['user']['created_at'] ?></p>
                    <a href="edit_profile.php" class="btn btn-primary btn-sm">Edit Profile</a>
                    <a href="../admin_page/add_product.php" class="btn btn-primary btn-sm">Add Product</a>
                </div>
            </div>
        </div>

        <!-- Orders Section -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Orders Received</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($userOrders as $order): ?>
                            <tr>
                                <td>#<?= $order['order_id'] ?></td>
                                <td><?= $order['order_date'] ?></td>
                                <td><?= $order['status'] ?></td>
                                <td>$<?= number_format($order['total_amount'], 2) ?></td>
                                <td>
                                    <form action="" method="POST">
                                        <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                        <select name="status" class="form-select form-select-sm">
                                            <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                            <option value="delivered" <?= $order['status'] === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                            <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                        </select>
                                        <button type="submit" name="update_status" class="btn btn-sm btn-success mt-1">Update</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Products Section -->
            <div class="card">
                <div class="card-header">
                    <h4>Your Products</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Product ID</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($userProducts as $product): ?>
                            <tr>
                                <td>#<?= $product['product_id'] ?></td>
                                <td><?= $product['product_name'] ?></td>
                                <td>$<?= number_format($product['price'], 2) ?></td>
                                <td><?= $product['status'] === '1' ? 'Available' : 'Out of Stock' ?></td>
                                <td><a href="edit_product.php?product_id=<?= $product['product_id'] ?>" class="btn btn-warning btn-sm">Edit</a></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
