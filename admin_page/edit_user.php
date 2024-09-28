<?php
session_start();

if ($_SESSION['user']['role'] != 'admin') {
    echo '<script>alert("You are not authorized to access this page."); window.location = "../login.php";</script>';
    exit();
}

try {
    $conn = new PDO("mysql:host=localhost;dbname=ecommerce;charset=utf8mb4", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if(isset($_GET['id'])){
    $sql = "SELECT * FROM users WHERE user_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

if(isset($_POST['ok'])){
    $id = $_POST['id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = md5($_POST['password']);

    $sql = "UPDATE users SET first_name = :fname, last_name = :lname, email = :email, role = :role";
    if(!empty($password)){
        $sql .= ", password_hash = :password";
    }
    $sql .= " WHERE user_id = :id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':fname', $fname);
    $stmt->bindParam(':lname', $lname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':role', $role);
    if(!empty($password)){
        $stmt->bindParam(':password', $password);
    }
    $stmt->execute();

    echo "<script>alert('User updated successfully'); window.location = 'index.php?users';</script>";
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4>Edit User</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <input type="hidden" name="id" value="<?php echo $user['user_id']; ?>">

                            <div class="mb-3">
                                <label for="name" class="form-label">First name:</label>
                                <input type="text" id="name" name="fname" class="form-control" value="<?php echo $user['first_name']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">Last name:</label>
                                <input type="text" id="name" name="lname" class="form-control" value="<?php echo $user['last_name']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" id="email" name="email" class="form-control" value="<?php echo $user['email']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="role" class="form-label">Role:</label>
                                <select id="role" name="role" class="form-select" required>
                                    <option value="user" <?php echo $user['role'] == 'user' ? 'selected' : ''; ?>>User</option>
                                    <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">New Password:</label>
                                <input type="password" id="password" name="password" class="form-control" placeholder="Rewrite password">
                            </div>

                            <button type="submit" name="ok" class="btn btn-success">Update User</button>
                            <a href="index.php?users" class="btn btn-secondary">Cancel</a>
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