<?php
session_start();
if ($_SESSION['user']['role'] != 'admin') {
    ?>
    <script>
        alert("You do not have permission to access this page.");
        window.location = "../index.php";
    </script>
    <?php
    exit();
}
try{
    $conn = new PDO('mysql:host=localhost;dbname=ecommerce', 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if(isset($_POST['ok'])) {
        $category_name = $_POST['category_name'];

        // check if the category name already exists
        $checkCategoryStmt = $conn->prepare("SELECT category_name FROM categories WHERE category_name = :category_name");
        $checkCategoryStmt->bindParam(':category_name', $category_name);
        $checkCategoryStmt->execute();
        
        if ($checkCategoryStmt->rowCount() > 0) {
            echo '<script>alert("Category already exists. Please use a different category name."); window.history.back();</script>';
            exit();
        }

        $sql = "INSERT INTO categories (category_name) VALUES (:category_name)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':category_name', $category_name);

        if ($stmt->execute()) {
            echo '<script>alert("Category added successfully!"); window.location = "index.php?categories";</script>';
        } else {
            echo "Failed to add category!";
        }
    }
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

?>


