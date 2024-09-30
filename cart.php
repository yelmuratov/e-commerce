<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit();
}

// Redirect if user is not a regular user (e.g., admin)
if ($_SESSION['user']['role'] != 'user') {
  header("Location: index.php");
  exit();
}

try {
  $conn = new PDO("mysql:host=localhost;dbname=ecommerce;", "root", "");
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $stmt = $conn->prepare("SELECT * FROM products");
  $stmt->execute();
  $products = [];
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $products[$row['product_id']] = $row;
  }
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
  die();
}

$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$quantities = isset($_SESSION['quantities']) ? $_SESSION['quantities'] : [];

// Calculate initial totals
$subtotal = 0;
foreach ($cartItems as $itemId) {
  if (isset($products[$itemId])) {  // Ensure the product exists in the array
    $quantity = isset($quantities[$itemId]) ? $quantities[$itemId] : 1;
    $subtotal += $products[$itemId]['price'] * $quantity;
  }
}
$total = $subtotal;
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
  <title>Cart - Furni</title>
</head>

<body>

  <?php include 'components/header.php'; ?>

  <!-- Start Hero Section -->
  <div class="hero">
    <div class="container">
      <div class="row justify-content-between">
        <div class="col-lg-5">
          <div class="intro-excerpt">
            <h1>Cart</h1>
          </div>
        </div>
        <div class="col-lg-7"></div>
      </div>
    </div>
  </div>
  <!-- End Hero Section -->

  <div class="untree_co-section before-footer-section">
    <div class="container">
      <div class="row mb-5">
        <form class="col-md-12" method="post" action="update_cart.php">
          <div class="site-blocks-table">
            <table class="table">
              <thead>
                <tr>
                  <th class="product-thumbnail">Image</th>
                  <th class="product-name">Product</th>
                  <th class="product-price">Price</th>
                  <th class="product-quantity">Quantity</th>
                  <th class="product-total">Total</th>
                  <th class="product-remove">Remove</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($cartItems)): ?>
                  <tr>
                    <td colspan="6" class="text-center">Your cart is empty.</td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($cartItems as $itemId): ?>
                    <?php if (isset($products[$itemId])): ?>
                      <?php $quantity = isset($quantities[$itemId]) ? $quantities[$itemId] : 1; ?>
                      <tr>
                        <td class="product-thumbnail">
                          <img src="<?= $products[$itemId]['image_url'] ?>" alt="Image" class="img-fluid">
                        </td>
                        <td class="product-name">
                          <h2 class="h5 text-black"><?= $products[$itemId]['product_name'] ?></h2>
                        </td>
                        <td>$<?= number_format($products[$itemId]['price'], 2) ?></td>
                        <td>
                          <div class="input-group mb-3 d-flex align-items-center quantity-container" style="max-width: 120px;">
                            <input type="number" id="quantity-<?= $itemId ?>" name="quantities[<?= $itemId ?>]" class="form-control text-center quantity-amount" value="<?= $quantity ?>" min="1" max="<?= $products[$itemId]['quantity'] ?>" data-price="<?= $products[$itemId]['price'] ?>" data-item-id="<?= $itemId ?>">
                          </div>
                        </td>
                        <td id="total-<?= $itemId ?>">$<?= number_format($products[$itemId]['price'] * $quantity, 2) ?></td>
                        <td><a href="remove_from_cart.php?product_id=<?= $itemId ?>" class="btn btn-black btn-sm">X</a></td>
                      </tr>
                    <?php endif; ?>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </form>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="row mb-5">
            <div class="col-md-6 mb-3 mb-md-0">
              <button class="btn btn-black btn-sm btn-block">Update Cart</button>
            </div>
            <div class="col-md-6">
              <a href="shop.php" class="btn btn-outline-black btn-sm btn-block">Continue Shopping</a>
            </div>
          </div>
        </div>
        <div class="col-md-6 pl-5">
          <div class="row justify-content-end">
            <div class="col-md-7">
              <div class="row">
                <div class="col-md-12 text-right border-bottom mb-5">
                  <h3 class="text-black h4 text-uppercase">Cart Totals</h3>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-6">
                  <span class="text-black">Subtotal</span>
                </div>
                <div class="col-md-6 text-right">
                  <strong class="text-black" id="subtotal">$<?= number_format($subtotal, 2) ?></strong>
                </div>
              </div>
              <div class="row mb-5">
                <div class="col-md-6">
                  <span class="text-black">Total</span>
                </div>
                <div class="col-md-6 text-right">
                  <strong class="text-black" id="total">$<?= number_format($total, 2) ?></strong>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <form id="checkoutForm" method="POST" action="checkout.php">
                    <input type="hidden" name="cartData" id="cartDataInput">
                    <a onclick="proceedToCheckout()" class="btn btn-black btn-lg py-3 btn-block">
                      Proceed To Checkout
                    </a>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Start Footer Section -->
  <footer class="footer-section">
    <div class="container relative">
      <div class="sofa-img">
        <img src="images/sofa.png" alt="Image" class="img-fluid">
      </div>

      <div class="row">
        <div class="col-lg-8">
          <div class="subscription-form">
            <h3 class="d-flex align-items-center"><span class="me-1"><img src="images/envelope-outline.svg" alt="Image" class="img-fluid"></span><span>Subscribe to Newsletter</span></h3>
            <form action="#" class="row g-3">
              <div class="col-auto">
                <input type="text" class="form-control" placeholder="Enter your name">
              </div>
              <div class="col-auto">
                <input type="email" class="form-control" placeholder="Enter your email">
              </div>
              <div class="col-auto">
                <button class="btn btn-primary"><span class="fa fa-paper-plane"></span></button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="row g-5 mb-5">
        <div class="col-lg-4">
          <div class="mb-4 footer-logo-wrap"><a href="#" class="footer-logo">Furni<span>.</span></a></div>
          <p class="mb-4">Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique. Pellentesque habitant</p>

          <ul class="list-unstyled custom-social">
            <li><a href="#"><span class="fa fa-brands fa-facebook-f"></span></a></li>
            <li><a href="#"><span class="fa fa-brands fa-twitter"></span></a></li>
            <li><a href="#"><span class="fa fa-brands fa-instagram"></span></a></li>
            <li><a href="#"><span class="fa fa-brands fa-linkedin"></span></a></li>
          </ul>
        </div>

        <div class="col-lg-8">
          <div class="row links-wrap">
            <div class="col-6 col-sm-6 col-md-3">
              <ul class="list-unstyled">
                <li><a href="#">About us</a></li>
                <li><a href="#">Services</a></li>
                <li><a href="#">Blog</a></li>
                <li><a href="#">Contact us</a></li>
              </ul>
            </div>

            <div class="col-6 col-sm-6 col-md-3">
              <ul class="list-unstyled">
                <li><a href="#">Support</a></li>
                <li><a href="#">Knowledge base</a></li>
                <li><a href="#">Live chat</a></li>
              </ul>
            </div>

            <div class="col-6 col-sm-6 col-md-3">
              <ul class="list-unstyled">
                <li><a href="#">Jobs</a></li>
                <li><a href="#">Our team</a></li>
                <li><a href="#">Leadership</a></li>
                <li><a href="#">Privacy Policy</a></li>
              </ul>
            </div>

            <div class="col-6 col-sm-6 col-md-3">
              <ul class="list-unstyled">
                <li><a href="#">Nordic Chair</a></li>
                <li><a href="#">Kruzo Aero</a></li>
                <li><a href="#">Ergonomic Chair</a></li>
              </ul>
            </div>
          </div>
        </div>

      </div>

      <div class="border-top copyright">
        <div class="row pt-4">
          <div class="col-lg-6">
            <p class="mb-2 text-center text-lg-start">Copyright &copy;<script>
                document.write(new Date().getFullYear());
              </script>. All Rights Reserved. &mdash; Designed with love by <a href="https://untree.co">Untree.co</a> Distributed By <a href="https://themewagon.com">ThemeWagon</a></p>
          </div>

          <div class="col-lg-6 text-center text-lg-end">
            <ul class="list-unstyled d-inline-flex ms-auto">
              <li class="me-4"><a href="#">Terms & Conditions</a></li>
              <li><a href="#">Privacy Policy</a></li>
            </ul>
          </div>
        </div>
      </div>

    </div>
  </footer>
  <!-- End Footer Section -->

  <!-- JavaScript to handle cart quantities and checkout -->
  <script>
        document.querySelectorAll('.quantity-amount').forEach(input => {
            input.addEventListener('input', function () {
                const max = parseInt(this.max);
                const quantity = parseInt(this.value);
                if (quantity > max) {
                    this.value = max;
                }

                const price = parseFloat(this.dataset.price);
                const itemId = this.dataset.itemId;

                const total = (this.value * price).toFixed(2);
                document.getElementById(`total-${itemId}`).textContent = `$${total}`;

                let subtotal = 0;
                document.querySelectorAll('.quantity-amount').forEach(input => {
                    const itemTotal = parseFloat(input.value) * parseFloat(input.dataset.price);
                    subtotal += itemTotal;
                });

                document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
                document.getElementById('total').textContent = `$${subtotal.toFixed(2)}`;
            });
        });

        function proceedToCheckout() {
            const cartData = {};

            // Collect product_id and quantity from the input fields
            document.querySelectorAll('.quantity-amount').forEach(input => {
                const productId = input.dataset.itemId;
                const quantity = input.value;
                cartData[productId] = quantity;
            });

            // Serialize the cart data to JSON
            const cartDataJSON = JSON.stringify(cartData);

            // Set the serialized cart data into the hidden input field
            document.getElementById('cartDataInput').value = cartDataJSON;

            // Submit the form to checkout
            document.getElementById('checkoutForm').submit();
        }
    </script>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/tiny-slider.js"></script>
    <script src="js/custom.js"></script>
</body>

</html>