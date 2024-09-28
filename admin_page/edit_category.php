<?php
session_start();
if($_SESSION['user']['role'] != 'admin'){
    echo '<script>alert("You are not authorized to access this page."); window.location = history.back();;</script>';
    exit();
}

if (isset($_POST['ok'])) {
    $category_name = $_POST['category_name'];
    $category_id = $_POST['category_id']; 

    try {
        $conn = new PDO("mysql:host=localhost;dbname=ecommerce", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if the new category name is different from the current one
        $checkSql = "SELECT category_name FROM categories WHERE category_id = :category_id";
        $stmt = $conn->prepare($checkSql);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->execute();
        $currentName = $stmt->fetchColumn();

        if ($currentName === $category_name) {
            echo "<script>alert('You haven\'t changed anything');window.location=history.back();</script>";
        } else {
            // Update the category name
            $sql = "UPDATE categories SET category_name = :category_name WHERE category_id = :category_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':category_name', $category_name, PDO::PARAM_STR);
            $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
            $stmt->execute();

            echo "<script>alert('Update successfully');window.location=history.back();</script>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Something went wrong";
}
?>
