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
                    <p><strong>Name:</strong> John Doe</p>
                    <p><strong>Email:</strong> johndoe@example.com</p>
                    <p><strong>Member since:</strong> January 2020</p>
                    <a href="edit_profile.php" class="btn btn-primary btn-sm">Edit Profile</a>
                </div>
            </div>
        </div>

        <!-- Orders Section -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Orders</h4>
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
                            <tr>
                                <td>#12345</td>
                                <td>2024-09-10</td>
                                <td>Shipped</td>
                                <td>$120.00</td>
                                <td><a href="order_details.php?order_id=12345" class="btn btn-info btn-sm">View</a></td>
                            </tr>
                            <tr>
                                <td>#12346</td>
                                <td>2024-09-12</td>
                                <td>Processing</td>
                                <td>$85.00</td>
                                <td><a href="order_details.php?order_id=12346" class="btn btn-info btn-sm">View</a></td>
                            </tr>
                            <!-- Add more orders as needed -->
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
                            <tr>
                                <td>#101</td>
                                <td>Modern Sofa</td>
                                <td>$450.00</td>
                                <td>Available</td>
                                <td><a href="edit_product.php?product_id=101" class="btn btn-warning btn-sm">Edit</a></td>
                            </tr>
                            <tr>
                                <td>#102</td>
                                <td>Leather Chair</td>
                                <td>$300.00</td>
                                <td>Out of Stock</td>
                                <td><a href="edit_product.php?product_id=102" class="btn btn-warning btn-sm">Edit</a></td>
                            </tr>
                            <!-- Add more products as needed -->
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
