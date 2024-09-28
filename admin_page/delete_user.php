<?php
session_start();

if ($_SESSION['user']['role'] != 'admin') {
    echo '<script>alert("You are not authorized to access this page."); window.location = "../login.php";</script>';
    exit();
}

// Check if a user ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request."); window.location = "index.php";</script>';
    exit();
}

$user_id = (int)$_GET['id'];

// Database connection
try {
    $conn = new PDO('mysql:host=localhost;dbname=ecommerce', 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Delete the user
    $sql = "DELETE FROM users WHERE user_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo '<script>alert("User deleted successfully!"); window.location = "index.php?users";</script>';
    } else {
        echo '<script>alert("Failed to delete user."); window.location = "index.php?users";</script>';
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}
?>
