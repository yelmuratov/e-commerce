<?php
session_start();

// Decode the received cart data from POST
if (isset($_POST['cartData']) && !empty($_POST['cartData'])) {
    $cartData = json_decode($_POST['cartData'], true);  // Convert JSON back to array
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "Invalid cart data!";
        exit();
    }
} else {
    echo "No cart data found!";
    exit();
}

$CartProducts = [];
$totalAmount = 0;

// Retrieve products from the database based on the product_ids from $cartData
if (!empty($cartData)) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=ecommerce;", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Prepare the product IDs for the SQL query
        $product_ids = implode(",", array_keys($cartData));
        $stmt = $conn->prepare("SELECT * FROM products WHERE product_id IN ($product_ids)");
        $stmt->execute();
        $CartProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Calculate total amount based on quantities from $cartData
        foreach ($CartProducts as $product) {
            $product_id = $product['product_id'];
            $quantity = $cartData[$product_id];
            $totalAmount += $product['price'] * $quantity;
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Handle order submission and save to database
if (isset($_POST['ok'])) {
    // Preserving cart data for order submission
    $country = $_POST['c_country'];
    $address = $_POST['c_address'];
    $state_country = $_POST['c_state_country'];
    $postal_zip = $_POST['c_postal_zip'];
    $phone = $_POST['c_phone'];
    $message = $_POST['c_order_notes'];
    $user_id = $_SESSION['user']['user_id']; // Assuming user ID is stored in the session
    $status = 'pending'; // Default status for new orders
    $order_date = date('Y-m-d H:i:s'); // Current timestamp for order date

    try {
        $conn->beginTransaction(); // Start transaction

        // Insert order into the orders table
        $stmt = $conn->prepare("
            INSERT INTO orders (user_id, country, address, state, zip, phone, order_date, message, total_amount, status) 
            VALUES (:user_id, :country, :address, :state_country, :postal_zip, :phone, :order_date, :message, :total_amount, :status)
        ");
        
        // Bind order-level parameters
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':state_country', $state_country);
        $stmt->bindParam(':postal_zip', $postal_zip);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':order_date', $order_date);
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':total_amount', $totalAmount);
        $stmt->bindParam(':status', $status);

        // Execute order insertion
        if ($stmt->execute()) {
            $order_id = $conn->lastInsertId(); // Get the inserted order ID

            // Insert each product into the order_details table
            $stmtItems = $conn->prepare("
                INSERT INTO order_details (order_id, product_id, quantity, total_amount) 
                VALUES (:order_id, :product_id, :quantity, :total_amount)
            ");

            foreach ($CartProducts as $product) {
                $product_id = $product['product_id'];
                $quantity = $cartData[$product_id];
                $product_total = $product['price'] * $quantity;

                // Bind product-level parameters
                $stmtItems->bindParam(':order_id', $order_id);
                $stmtItems->bindParam(':product_id', $product_id);
                $stmtItems->bindParam(':quantity', $quantity);
                $stmtItems->bindParam(':total_amount', $product_total);

                // Execute the insertion for each product in the order
                $stmtItems->execute();
            }

            $conn->commit(); // Commit the transaction
            echo '<script>alert("Order placed successfully!"); window.location = "thankyou.php";</script>';
            $_SESSION['cart'] = []; // Clear the cart after successful order
        } else {
            echo "Failed to place order!";
        }

    } catch (PDOException $e) {
        $conn->rollBack(); // Rollback transaction on error
        echo "Error: " . $e->getMessage();
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Untree.co">
    <link rel="shortcut icon" href="favicon.png">

    <meta name="description" content="" />
    <meta name="keywords" content="bootstrap, bootstrap4" />

    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="css/tiny-slider.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <title>Checkout - Furni</title>
</head>

<body>

    <?php include 'components/header.php'; ?>

    <!-- Start Hero Section -->
    <div class="hero">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-5">
                    <div class="intro-excerpt">
                        <h1>Checkout</h1>
                    </div>
                </div>
                <div class="col-lg-7"></div>
            </div>
        </div>
    </div>
    <!-- End Hero Section -->

    <div class="untree_co-section">
        <div class="container">
            <form action="" method="post" class="row">
                <!-- Hidden field to preserve cart data -->
                <input type="hidden" name="cartData" value="<?= htmlentities(json_encode($cartData)) ?>">

                <div class="col-md-6 mb-5 mb-md-0">
                    <h2 class="h3 mb-3 text-black">Billing Details</h2>
                    <div class="p-3 p-lg-5 border bg-white">
                        <!-- Billing details form -->
                        <div class="form-group">
                            <label for="c_country" class="text-black">Country <span class="text-danger">*</span></label>
                            <select id="c_country" class="form-control" name="c_country">
                                <option value="Uzbekistan">Uzbekistan</option>
                                <option value="Russia">Russia</option>
                                <option value="United States">United States</option>
                            </select>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="c_address" class="text-black">Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="c_address" name="c_address" placeholder="Street address">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="c_state_country" class="text-black">State / Country <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="c_state_country" name="c_state_country">
                            </div>
                            <div class="col-md-6">
                                <label for="c_postal_zip" class="text-black">Postal / Zip <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="c_postal_zip" name="c_postal_zip">
                            </div>
                        </div>

                        <div class="form-group row mb-5">
                            <div class="col-md-12">
                                <label for="c_phone" class="text-black">Phone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="c_phone" name="c_phone" placeholder="Phone Number">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="c_order_notes" class="text-black">Order Notes</label>
                            <textarea name="c_order_notes" id="c_order_notes" cols="30" rows="5" class="form-control" placeholder="Write your notes here..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row mb-5">
                        <div class="col-md-12">
                            <h2 class="h3 mb-3 text-black">Your Order</h2>
                            <div class="p-3 p-lg-5 border bg-white">
                                <table class="table site-block-order-table mb-5">
                                    <thead>
                                        <th>Product</th>
                                        <th>Total</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($CartProducts as $product) {
                                            $product_id = $product['product_id'];
                                            $quantity = $cartData[$product_id];
                                            $productTotal = $product['price'] * $quantity;
                                            echo "
                                            <tr>
                                                <td>{$product['product_name']} <strong class='mx-2'>x</strong> {$quantity}</td>
                                                <td>\${$productTotal}</td>
                                            </tr>";
                                        }
                                        ?>
                                        <tr>
                                            <td class="text-black font-weight-bold"><strong>Cart Subtotal</strong></td>
                                            <td class="text-black">$<?= number_format($totalAmount, 2) ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-black font-weight-bold"><strong>Order Total</strong></td>
                                            <td class="text-black font-weight-bold"><strong>$<?= number_format($totalAmount, 2) ?></strong></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="form-group">
                                    <button type="submit" name="ok" class="btn btn-black btn-lg py-3 btn-block">Place Order</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <?php include 'components/footer.php'; ?>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/tiny-slider.js"></script>
    <script src="js/custom.js"></script>
</body>
</html>
