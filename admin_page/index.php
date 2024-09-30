<?php
session_start();

if ($_SESSION['user']['role'] != "admin") {
?>
    <script>
        alert("You are not authorized to view this page.");
        window.location = "../index.php";
    </script>
<?php
}

if (isset($_POST['ok'])) {
    $categoryName = $_POST['name'];

    try {
        $conn = new PDO('mysql:host=localhost;dbname=ecommerce', 'root', '');
        $sql = "INSERT INTO categories (name) VALUES (:name)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $categoryName, PDO::PARAM_STR);
        $stmt->execute();
        echo '<script>alert("Category added successfully."); window.location = "index.php";</script>';
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}


function getUsers()
{
    $conn = new PDO('mysql:host=localhost;dbname=ecommerce', 'root', '');
    $sql = "SELECT * FROM users";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProducts($limit, $offset, $category_id = null)
{
    $conn = new PDO('mysql:host=localhost;dbname=ecommerce', 'root', '');
    if ($category_id) {
        $sql = "SELECT * FROM products WHERE category_id = :category_id ORDER BY id ASC LIMIT :limit OFFSET :offset";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
    } else {
        $sql = "SELECT * FROM products ORDER BY product_id ASC LIMIT :limit OFFSET :offset";
        $stmt = $conn->prepare($sql);
    }
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTotalProductsCount($category_id = null)
{
    $conn = new PDO('mysql:host=localhost;dbname=ecommerce', 'root', '');
    if ($category_id) {
        $sql = "SELECT COUNT(*) FROM products WHERE category_id = :category_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
    } else {
        $sql = "SELECT COUNT(*) FROM products";
        $stmt = $conn->prepare($sql);
    }
    $stmt->execute();
    return $stmt->fetchColumn();
}

$categories = [];
try {
    // Database connection with charset
    $conn = new PDO('mysql:host=localhost;dbname=ecommerce;charset=utf8mb4', 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Ensure exceptions are thrown on errors

    // Fetch all categories
    $sql = "SELECT * FROM categories";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}

function getCategoriesPaginated($limit, $offset)
{
    try {
        // Database connection with charset
        $conn = new PDO('mysql:host=localhost;dbname=ecommerce;charset=utf8mb4', 'root', '');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM categories LIMIT :limit OFFSET :offset";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}

function getTotalCategoriesCount()
{
    try {
        // Database connection with charset
        $conn = new PDO('mysql:host=localhost;dbname=ecommerce;charset=utf8mb4', 'root', '');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT COUNT(*) FROM categories";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return 0;
    }
}


function getUsersPaginated($limit, $offset)
{
    $conn = new PDO('mysql:host=localhost;dbname=ecommerce', 'root', '');
    $sql = "SELECT * FROM users LIMIT :limit OFFSET :offset";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTotalUsersCount()
{
    $conn = new PDO('mysql:host=localhost;dbname=ecommerce', 'root', '');
    $sql = "SELECT COUNT(*) FROM users";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchColumn();
}

$orders = [];

try {
    $conn = new PDO('mysql:host=localhost;dbname=ecommerce', 'root', '');
    $sql = "SELECT * FROM orders";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}

function getOrdersPaginated($limit, $offset)
{
    $conn = new PDO('mysql:host=localhost;dbname=ecommerce', 'root', '');
    $sql = "SELECT * FROM orders LIMIT :limit OFFSET :offset";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTotalOrdersCount()
{
    $conn = new PDO('mysql:host=localhost;dbname=ecommerce', 'root', '');
    $sql = "SELECT COUNT(*) FROM orders";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchColumn();
}


// Determine whether to display the users or news table
$displayUsers = isset($_GET['users']);
$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;

try {
    $limit = 10; // Number of items per page (both users and news)
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

    if ($displayUsers) {
        $totalUsers = getTotalUsersCount();
        $totalPages = ceil($totalUsers / $limit);
        $page = max(1, min($page, $totalPages)); // Ensure current page is within bounds
        $offset = ($page - 1) * $limit;

        $data = getUsersPaginated($limit, $offset); // New function to get paginated users
        $tableHeaders = ['ID', 'Username', 'Email', 'Role', 'Action'];
    } else if (isset($_GET['orders'])) {
        $totalOrders = getTotalOrdersCount();
        $totalPages = ceil($totalOrders / $limit);
        $page = max(1, min($page, $totalPages)); // Ensure current page is within bounds
        $offset = ($page - 1) * $limit;

        $data = getOrdersPaginated($limit, $offset);
        $tableHeaders = ['Order id', 'User ID', 'Owner id', 'Order date', 'Total price', 'Status'];
    } else if (isset($_GET['categories'])) {
        $total_categories = getTotalCategoriesCount();
        $totalPages = ceil($total_categories / $limit);
        $page = max(1, min($page, $totalPages)); // Ensure current page is within bounds
        $offset = ($page - 1) * $limit;

        $data = getCategoriesPaginated($limit, $offset);
        $tableHeaders = ['Category id', 'Category name', 'Action'];
    } else {
        $totalNews = getTotalProductsCount($category_id);
        $totalPages = ceil($totalNews / $limit);
        $page = max(1, min($page, $totalPages)); // Ensure current page is within bounds
        $offset = ($page - 1) * $limit;

        $data = getProducts($limit, $offset, $category_id);
        $tableHeaders = ['Product id', 'Category id', 'Product name', 'Owner id', 'status', 'Action'];
    }
} catch (Exception $e) {
    echo $e->getMessage();
    die();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Font awesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item <?php echo !$displayUsers && !$category_id ? 'active' : ''; ?>">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>

            <?php
            $displayUsers = isset($_GET['users']);
            $displayOrders = isset($_GET['orders']);
            $displayCategories = isset($_GET['categories']);
            $displayProducts = !$displayUsers && !$displayOrders && !$displayCategories; // Default to products if nothing else is selected
            ?>

            <!-- Nav Item - Categories -->
            <li class="nav-item <?php echo $displayCategories ? 'active' : ''; ?>">
                <a class="nav-link" href="index.php?categories">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-text-indent-left" viewBox="0 0 16 16">
                        <path d="M2 3.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5m.646 2.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1 0 .708l-2 2a.5.5 0 0 1-.708-.708L4.293 8 2.646 6.354a.5.5 0 0 1 0-.708M7 6.5a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5m0 3a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5m-5 3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5" />
                    </svg>
                    <span>Categories</span>
                </a>
            </li>

            <!-- Nav Item - Products -->
            <li class="nav-item <?php echo $displayProducts ? 'active' : ''; ?>">
                <a class="nav-link" href="index.php?products">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box" viewBox="0 0 16 16">
                        <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 8 5.961 14.154 3.5zM15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464z" />
                    </svg>
                    <span>Products</span>
                </a>
            </li>

            <!-- Nav Item - Users -->
            <li class="nav-item <?php echo $displayUsers ? 'active' : ''; ?>">
                <a class="nav-link" href="index.php?users">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
                        <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4" />
                    </svg>
                    <span>Users</span>
                </a>
            </li>

            <!-- Nav Item - Orders -->
            <li class="nav-item <?php echo $displayOrders ? 'active' : ''; ?>">
                <a class="nav-link" href="index.php?orders">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list-ul" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M5 11.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5m-3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2m0 4a1 1 0 1 0 0-2 1 1 0 0 0 0 2m0 4a1 1 0 1 0 0-2 1 1 0 0 0 0 2" />
                    </svg>
                    <span>Orders</span>
                </a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                </span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
                                    </svg>
                                    <?php echo $_SESSION['user']['first_name']; ?>
                                </a>
                                <a class="dropdown-item" href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-envelope" viewBox="0 0 16 16">
                                        <path
                                            d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z" />
                                    </svg>
                                    <?php echo $_SESSION['user']['email']; ?>
                                </a>
                                <a class="dropdown-item" href="../index.php">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-bar-left" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M12.5 15a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5M10 8a.5.5 0 0 1-.5.5H3.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L3.707 7.5H9.5a.5.5 0 0 1 .5.5" />
                                    </svg>
                                    Go to website
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="../logout.php" data-toggle="modal"
                                    data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Content Row -->
                    <div class="row">

                        <!-- News Amount Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total products</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo getTotalProductsCount(); ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Categories Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Total categories</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo count($categories); ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                fill="currentColor" class="text-gray-300" viewBox="0 0 16 16">
                                                <path
                                                    d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Users Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Total users</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo count(getUsers()); ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                fill="currentColor" class="text-gray-300" viewBox="0 0 16 16">
                                                <path
                                                    d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Table -->
                    <div class="card-body">
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                            <?php if ($displayUsers): ?>
                                <a href="add_user.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-user-plus fa-sm text-white-50"></i> Add User</a>
                            <?php elseif (isset($_GET['categories'])): ?>
                                <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addCategoryModal">
                                    <i class="fas fa-plus fa-sm text-white-50"></i> Add Category
                                </button>
                            <?php elseif (!isset($_GET['orders'])): ?>
                                <a href="add_product.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Add Product</a>
                            <?php endif; ?>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <?php foreach ($tableHeaders as $header): ?>
                                            <th><?php echo $header; ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data as $item) : ?>
                                        <tr>
                                            <?php if ($displayUsers): ?>
                                                <td><?php echo $item['user_id']; ?></td>
                                                <td><?php echo $item['first_name']; ?></td>
                                                <td><?php echo $item['email']; ?></td>
                                                <td><?php echo $item['role']; ?></td>
                                                <td>
                                                    <a href="edit_user.php?id=<?php echo $item['user_id']; ?>"
                                                        class="btn btn-sm btn-primary">Edit</a>
                                                    <a href="delete_user.php?id=<?php echo $item['user_id']; ?>"
                                                        class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                                </td>
                                            <?php elseif (isset($_GET['orders'])): ?>
                                                <td><?php echo $item['order_id']; ?></td>
                                                <td><?php echo $item['user_id']; ?></td>
                                                <td><?php echo $item['owner_id']; ?></td>
                                                <td><?php echo $item['order_date']; ?></td>
                                                <td><?php echo $item['total_amount']; ?></td>
                                                <td class="d-flex align-items-center justify-content-center">
                                                    <form action="update_product_status.php" method="POST" class="form-inline">
                                                        <input type="hidden" name="order_id" value="<?= $item['order_id'] ?>"> <!-- Hidden input for order ID -->
                                                        <select name="status" id="status" class="form-control mr-2">
                                                            <option value="pending" <?= $item['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                                            <option value="delivered" <?= $item['status'] == 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                                            <option value="cancelled" <?= $item['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                                        </select>
                                                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                                    </form>
                                                </td>
                                            <?php elseif (isset($_GET['categories'])): ?>
                                                <td><?php echo $item['category_id']; ?></td>
                                                <td><?php echo $item['category_name']; ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editCategoryModal<?php echo $item['category_id']; ?>">
                                                        Edit
                                                    </button>
                                                    <a href="delete_category.php?id=<?php echo $item['category_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>
                                                </td>
                                            <?php else: ?>
                                                <td><?php echo $item['product_id']; ?></td>
                                                <td><?php echo $item['category_id']; ?></td>
                                                <td><?php echo $item['product_name']; ?></td>
                                                <td><?php echo $item['owner_id']; ?></td>
                                                <td>
                                                    <!-- Form to update product status -->
                                                    <form action="change_product_status.php" method="POST">
                                                        <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>"> <!-- Hidden input for product ID -->
                                                        <select name="status" id="status" class="form-control mb-2">
                                                            <option value="0" <?= $item['status'] == 0 ? 'selected' : ''; ?>>Inactive</option>
                                                            <option value="1" <?= $item['status'] == 1 ? 'selected' : ''; ?>>Active</option>
                                                        </select>
                                                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                                    </form>

                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editProductModal<?php echo $item['product_id']; ?>">
                                                        Edit
                                                    </button>
                                                    <a href="delete_product.php?id=<?php echo $item['product_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                                                </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>


                        <!-- Pagination Controls -->
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center">
                                <!-- Previous Button -->
                                <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                                    <a class="page-link" href="?page=<?php echo max(1, $page - 1); ?>
                                        <?php echo $displayUsers ? '&users' : ''; ?>
                                        <?php echo $displayOrders ? '&orders' : ''; ?>
                                        <?php echo $displayCategories ? '&categories' : ''; ?>
                                        <?php echo isset($category_id) ? '&category_id=' . $category_id : ''; ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                </li>

                                <!-- Page Numbers -->
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                                        <a class="page-link" href="?page=<?php echo $i; ?>
                                            <?php echo $displayUsers ? '&users' : ''; ?>
                                            <?php echo $displayOrders ? '&orders' : ''; ?>
                                            <?php echo $displayCategories ? '&categories' : ''; ?>
                                            <?php echo isset($category_id) ? '&category_id=' . $category_id : ''; ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>

                                <!-- Next Button -->
                                <li class="page-item <?php if ($page >= $totalPages) echo 'disabled'; ?>">
                                    <a class="page-link" href="?page=<?php echo min($totalPages, $page + 1); ?>
                                        <?php echo $displayUsers ? '&users' : ''; ?>
                                        <?php echo $displayOrders ? '&orders' : ''; ?>
                                        <?php echo $displayCategories ? '&categories' : ''; ?>
                                        <?php echo isset($category_id) ? '&category_id=' . $category_id : ''; ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>


                    </div>
                </div>
                <!-- End of Main Content -->

                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; Your Website 2021</span>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <a class="btn btn-primary" href="../logout.php">Logout</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Category Modal -->
        <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="addCategoryForm" action="add_category.php" method="post">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="categoryName">Category Name</label>
                                <input type="text" class="form-control" id="categoryName" name="category_name" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" name="ok" class="btn btn-primary">Add Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Category Modal -->
        <?php foreach ($data as $item): ?>
            <div class="modal fade" id="editCategoryModal<?php echo $item['category_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel<?php echo $item['category_id']; ?>" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editCategoryModalLabel<?php echo $item['category_id']; ?>">Edit Category</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="editCategoryForm<?php echo $item['category_id']; ?>" action="edit_category.php" method="post">
                            <div class="modal-body">
                                <input type="hidden" name="category_id" value="<?php echo $item['category_id']; ?>">
                                <div class="form-group">
                                    <label for="categoryName<?php echo $item['category_id']; ?>">Category Name</label>
                                    <input type="text" class="form-control" id="categoryName<?php echo $item['category_id']; ?>" name="category_name" value="<?php echo $item['category_name']; ?>" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" name="ok" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Edit Product Modal -->
        <?php foreach ($data as $item): ?>
            <div class="modal fade" id="editProductModal<?php echo $item['product_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel<?php echo $item['product_id']; ?>" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProductModalLabel<?php echo $item['product_id']; ?>">Edit Product</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="edit_product.php" method="post">
                            <div class="modal-body">
                                <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                <div class="form-group">
                                    <label for="productName<?php echo $item['product_id']; ?>">Product Name</label>
                                    <input type="text" class="form-control" id="productName<?php echo $item['product_id']; ?>" name="product_name" value="<?php echo $item['product_name']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="productPrice<?php echo $item['product_id']; ?>">Price</label>
                                    <input type="number" step="0.01" class="form-control" id="productPrice<?php echo $item['product_id']; ?>" name="price" value="<?php echo $item['price']; ?>" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" name="save" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Bootstrap core JavaScript-->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="js/sb-admin-2.min.js"></script>

        <!-- Page level plugins -->
        <script src="vendor/chart.js/Chart.min.js"></script>

        <!-- Page level custom scripts -->
        <script src="js/demo/chart-area-demo.js"></script>
        <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>