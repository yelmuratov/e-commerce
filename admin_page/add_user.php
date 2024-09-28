<?php
// Database connection
if($_SESSION['user']['role'] != 'admin'){
    echo '<script>alert("You are not authorized to access this page."); window.location = history.back();;</script>';
    exit();
}

$conn = new PDO('mysql:host=localhost;dbname=ecommerce', 'root', '');
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password

    // Check if the email already exists in the database
    $checkEmailStmt = $conn->prepare("SELECT user_id FROM users WHERE email = :email");
    $checkEmailStmt->bindParam(':email', $email);
    $checkEmailStmt->execute();

    if ($checkEmailStmt->rowCount() > 0) {
        // Email already exists
        echo '<script>alert("Email address is already in use. Please use a different email."); window.history.back();</script>';
        exit();
    }

    // Proceed with inserting the new user
    $sql = "INSERT INTO users (first_name, last_name, email, role, password_hash) VALUES (:name, :lname, :email, :role, :password)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':lname', $lname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':password', $password);

    if ($stmt->execute()) {
        echo '<script>alert("User added successfully!"); window.location = "index.php?users";</script>';
    } else {
        echo "Failed to add user!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4>Add New User</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="add_user.php">
                        <div class="mb-3">
                            <label for="name" class="form-label">First Name:</label>
                            <input type="text" id="name" name="first_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Last Name:</label>
                            <input type="text" id="name" name="last_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Role:</label>
                            <select id="role" name="role" class="form-select" required>
                                <option value="user" selected>User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password:</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-success">Add User</button>
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
