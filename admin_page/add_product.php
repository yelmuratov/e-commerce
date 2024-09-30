<?php
session_start();
if (!isset($_SESSION['user'])) {
    ?>
    <script>
        alert("You do not have permission to access this page.");
        window.location = "../index.php";
    </script>
    <?php
    exit();
}

$categories = [];
try {
    $conn = new PDO('mysql:host=localhost;dbname=ecommerce', 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->query("SELECT * FROM categories");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if (isset($_POST['ok'])) {
    $product_name = $_POST['product_name'];
    $category_id = $_POST['categories'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $owner_id = $_SESSION['user']['user_id'];
    $image_url = $_FILES['image_url']['name'];
    
    if (!empty($image_url)) {
        move_uploaded_file($_FILES['image_url']['tmp_name'], '../images/' . $image_url);
        $image_url = 'images/' . $image_url;
    }

    try {
        $sql = "INSERT INTO products (product_name, category_id, price, image_url, owner_id, quantity) 
                VALUES (:product_name, :category_id, :price, :image_url, :owner_id, :quantity)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':product_name', $product_name);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':image_url', $image_url);
        $stmt->bindParam(':owner_id', $owner_id);
        $stmt->bindParam(':quantity', $quantity);

        if ($stmt->execute()) {
            echo '<script>alert("Product added successfully!"); window.location = history.back();</script>';
        } else {
            echo "Failed to add product!";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4>Add New Product</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="" enctype="multipart/form-data" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="title" class="form-label">Product name:</label>
                            <input type="text" id="title" name="product_name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Category id:</label>
                            <select name="categories" class="form-control" id="category">
                                <?php foreach ($categories as $category) { ?>
                                    <option  value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="text" class="form-label">Price:</label>
                            <input type="number" id="price" name="price" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="text" class="form-label">Quantity:</label>
                            <input type="number" id="price" name="quantity" class="form-control" required>
                        </div>

                        <!-- product image file loader -->
                        <div class="mb-3">
                            <label for="image" class="form-label">Product image:</label>
                            <input type="file" id="image" name="image_url" class="form-control">
                        </div>

                        <button type="submit" name="ok" class="btn btn-success">Add product</button>
                        <a href="index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
