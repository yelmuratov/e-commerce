<?php
session_start();

// Check if the admin is logged in
if ($_SESSION['user']['role'] != 'admin') {
    echo '<script>alert("You are not authorized to access this page."); window.location = history.back();;</script>';
    exit();
}

// Check if a user ID is provided
if (!isset($_GET['id'])) {
    echo '<script>alert("Invalid request."); window.location = history.back();</script>';
    exit();
}

$id = (int)$_GET['id'];

// Database connection
try {
    $conn = new PDO('mysql:host=localhost;dbname=ecommerce', 'root', '');
    // Delete the user
    $sql = "DELETE FROM categories WHERE category_id=:id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        echo '<script>alert("Category deleted successfully!"); window.location = history.back();</script>';
    } else {
        echo '<script>alert("Failed to delete user."); window.location = history.back();;</script>';
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}
?>
