<?php
session_start();
$user_id = $_SESSION['user']['user_id'];

// Database connection
$conn = new PDO("mysql:host=localhost;dbname=ecommerce;", "root", "");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// ----- Orders Pagination Variables -----
$limit_orders = 5;  // Number of orders per page
$page_orders = isset($_GET['page_orders']) ? (int)$_GET['page_orders'] : 1;  // Current page number for orders
$offset_orders = ($page_orders - 1) * $limit_orders;  // Calculate offset for pagination

// Fetch orders for products owned by the logged-in user (JOIN with products and users)
$stmtOrders = $conn->prepare("
    SELECT 
        o.order_id, 
        o.order_date, 
        o.status, 
        o.total_amount, 
        u.first_name, 
        u.email, 
        p.product_name,
        od.quantity
    FROM orders o
    INNER JOIN users u ON o.user_id = u.user_id    -- The user who placed the order
    INNER JOIN order_details od ON o.order_id = od.order_id
    INNER JOIN products p ON od.product_id = p.product_id
    WHERE p.owner_id = :user_id    -- Products owned by the logged-in user
    LIMIT :limit OFFSET :offset
");
$stmtOrders->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmtOrders->bindParam(':limit', $limit_orders, PDO::PARAM_INT);
$stmtOrders->bindParam(':offset', $offset_orders, PDO::PARAM_INT);
$stmtOrders->execute();
$userOrders = $stmtOrders->fetchAll(PDO::FETCH_ASSOC);

// Get total number of orders for pagination
$stmtCountOrders = $conn->prepare("
    SELECT COUNT(*) AS total_orders 
    FROM orders o
    INNER JOIN order_details od ON o.order_id = od.order_id
    INNER JOIN products p ON od.product_id = p.product_id
    WHERE p.owner_id = :user_id
");
$stmtCountOrders->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmtCountOrders->execute();
$totalOrders = $stmtCountOrders->fetch(PDO::FETCH_ASSOC)['total_orders'];

$totalPagesOrders = ceil($totalOrders / $limit_orders);  // Calculate total pages for orders

// ----- Products Pagination Variables -----
$limit_products = 5;  // Number of products per page
$page_products = isset($_GET['page_products']) ? (int)$_GET['page_products'] : 1;  // Current page number for products
$offset_products = ($page_products - 1) * $limit_products;  // Calculate offset for pagination

// Fetch user's products
$stmtProducts = $conn->prepare("SELECT * FROM products WHERE owner_id = :user_id LIMIT :limit OFFSET :offset");
$stmtProducts->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmtProducts->bindParam(':limit', $limit_products, PDO::PARAM_INT);
$stmtProducts->bindParam(':offset', $offset_products, PDO::PARAM_INT);
$stmtProducts->execute();
$userProducts = $stmtProducts->fetchAll(PDO::FETCH_ASSOC);

// Get total number of products for pagination
$stmtCountProducts = $conn->prepare("SELECT COUNT(*) AS total_products FROM products WHERE owner_id = :user_id");
$stmtCountProducts->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmtCountProducts->execute();
$totalProducts = $stmtCountProducts->fetch(PDO::FETCH_ASSOC)['total_products'];

$totalPagesProducts = ceil($totalProducts / $limit_products);  // Calculate total pages for products

// Handle order status update (if user submitted form)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];     
    $new_status = $_POST['status'];

    // Update the order status
    $stmtUpdateStatus = $conn->prepare("UPDATE orders SET status = :status WHERE order_id = :order_id");
    $stmtUpdateStatus->bindParam(':status', $new_status);
    $stmtUpdateStatus->bindParam(':order_id', $order_id);

    if ($stmtUpdateStatus->execute()) {
        echo '<script>alert("Order status updated successfully!"); history.back();</script>';
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
    <title>User Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="row">
        <!-- User Information Section -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5>User Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> <?= $_SESSION['user']['first_name'] ?></p>
                    <p><strong>Email:</strong> <?= $_SESSION['user']['email'] ?></p>
                    <p><strong>Member since:</strong> <?= date('F j, Y', strtotime($_SESSION['user']['created_at'])) ?></p>
                    <a href="edit_profile.php" class="btn btn-secondary btn-sm">Edit Profile</a>
                    <a href="../admin_page/add_product.php" class="btn btn-success btn-sm">Add Product</a>
                </div>
            </div>
        </div>

        <!-- Orders Received Section -->
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5>Orders Received for Your Products</h5>
                </div>
                <div class="card-body">
                    <?php if (count($userOrders) > 0): ?>
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Order ID</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Ordered By</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($userOrders as $order): ?>
                            <tr>
                                <td>#<?= $order['order_id'] ?></td>
                                <td><?= $order['product_name'] ?></td>
                                <td><?= $order['quantity'] ?></td>
                                <td><?= $order['first_name'] ?> (<?= $order['email'] ?>)</td>
                                <td><?= date('F j, Y', strtotime($order['order_date'])) ?></td>
                                <td>$<?= number_format($order['total_amount'], 2) ?></td>
                                <td><?= ucfirst($order['status']) ?></td>
                                <td>
                                    <form action="" method="POST">
                                        <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                        <select name="status" class="form-select form-select-sm">
                                            <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
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

                    <!-- Pagination for Orders -->
                    <nav aria-label="Orders pagination">
                        <ul class="pagination justify-content-center">
                            <?php if ($page_orders > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page_orders=<?= $page_orders - 1 ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php for ($i = 1; $i <= $totalPagesOrders; $i++): ?>
                            <li class="page-item <?= $i == $page_orders ? 'active' : '' ?>">
                                <a class="page-link" href="?page_orders=<?= $i ?>"><?= $i ?></a>
                            </li>
                            <?php endfor; ?>
                            <?php if ($page_orders < $totalPagesOrders): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page_orders=<?= $page_orders + 1 ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                    <?php else: ?>
                        <p class="text-center">No orders received yet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- User Products Section -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5>Your Products</h5>
                </div>
                <div class="card-body">
                    <?php if (count($userProducts) > 0): ?>
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
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

                    <!-- Pagination for Products -->
                    <nav aria-label="Products pagination">
                        <ul class="pagination justify-content-center">
                            <?php if ($page_products > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page_products=<?= $page_products - 1 ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php for ($i = 1; $i <= $totalPagesProducts; $i++): ?>
                            <li class="page-item <?= $i == $page_products ? 'active' : '' ?>">
                                <a class="page-link" href="?page_products=<?= $i ?>"><?= $i ?></a>
                            </li>
                            <?php endfor; ?>
                            <?php if ($page_products < $totalPagesProducts): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page_products=<?= $page_products + 1 ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                    <?php else: ?>
                        <p class="text-center">No products added yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
