<style>
    .navbar-nav .nav-item.active .nav-link {
    color: #fff;
    border-radius: 5px;
    padding: 5px 10px;
}
</style>

<!-- Start Header/Navigation -->
<nav class="custom-navbar navbar navbar-expand-md navbar-dark bg-dark" aria-label="Furni navigation bar">

    <div class="container">
        <a class="navbar-brand" href="index.php">Furni<span>.</span></a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni" aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsFurni">
            <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
                <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'shop.php' ? 'active' : ''; ?>">
                    <a class="nav-link" href="shop.php">Shop</a></li>
                <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>">
                    <a class="nav-link" href="about.php">About us</a></li>
                <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'services.php' ? 'active' : ''; ?>">
                    <a class="nav-link" href="services.php">Services</a></li>
                <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'blog.php' ? 'active' : ''; ?>">
                    <a class="nav-link" href="blog.php">Blog</a></li>
                <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>">
                    <a class="nav-link" href="contact.php">Contact us</a></li>
            </ul>

            <?php
            if (isset($_SESSION['user'])) {
                $userName = $_SESSION['user']['first_name']; 
                $userEmail = $_SESSION['user']['email'];
                $cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; // Get the number of items in the cart
            ?>
                <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="images/user.svg" alt="User Profile">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <h6 class="dropdown-header"><?php echo htmlspecialchars($userName); ?></h6>
                            </li>
                            <li>
                                <p class="dropdown-item-text"><?php echo htmlspecialchars($userEmail); ?></p>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="<?php
                                if ($_SESSION['user']['role'] == 'admin') {
                                    echo 'admin_page/index.php';
                                } else {
                                    echo 'user_profile/index.php';
                                }
                            ?>">View Profile</a></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="cart.php">
                            <img src="images/cart.svg" alt="Cart">
                            <?php if ($cartCount > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?php echo $cartCount; ?>
                                    <span class="visually-hidden">items in cart</span>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>
                </ul>
            <?php
            } else {
            ?>
                <div class="button-container mt-2">
                    <p><a href="login.php" class="btn btn-secondary me-2">Login</a><a href="register.php" class="btn btn-white-outline">Register</a></p>
                </div>
            <?php
            }
            ?>
        </div>
    </div>

</nav>
<!-- End Header/Navigation -->
