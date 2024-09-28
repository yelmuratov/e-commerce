<?php

// Database connection
try {
    $conn = new PDO('mysql:host=localhost;dbname=ecommerce', 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Prepare the SQL statement
$sql = "INSERT INTO orders (user_id, owner_id, order_date, total_amount, status) VALUES (:user_id, :owner_id, :order_date, :total_amount, :status)";
$stmt = $conn->prepare($sql);

// Array of fake order data
$orders = [
    ['user_id' => 1, 'owner_id' => 2, 'order_date' => '2024-09-27 12:34:56', 'total_amount' => 100.50, 'status' => 'delivered'],
    ['user_id' => 2, 'owner_id' => 3, 'order_date' => '2024-09-28 13:45:00', 'total_amount' => 200.75, 'status' => 'pending'],
    ['user_id' => 3, 'owner_id' => 1, 'order_date' => '2024-09-29 14:50:30', 'total_amount' => 150.00, 'status' => 'cancelled'],
    ['user_id' => 4, 'owner_id' => 2, 'order_date' => '2024-09-30 15:05:10', 'total_amount' => 250.25, 'status' => 'delivered'],
    ['user_id' => 5, 'owner_id' => 3, 'order_date' => '2024-09-30 16:15:45', 'total_amount' => 300.90, 'status' => 'pending'],
    ['user_id' => 6, 'owner_id' => 4, 'order_date' => '2024-10-01 17:20:00', 'total_amount' => 50.00, 'status' => 'cancelled'],
    ['user_id' => 1, 'owner_id' => 2, 'order_date' => '2024-10-02 18:25:30', 'total_amount' => 120.40, 'status' => 'delivered'],
    ['user_id' => 2, 'owner_id' => 4, 'order_date' => '2024-10-03 19:30:15', 'total_amount' => 180.75, 'status' => 'pending'],
    ['user_id' => 3, 'owner_id' => 5, 'order_date' => '2024-10-04 20:35:50', 'total_amount' => 220.65, 'status' => 'cancelled'],
    ['user_id' => 4, 'owner_id' => 1, 'order_date' => '2024-10-05 21:40:00', 'total_amount' => 400.00, 'status' => 'delivered'],
];

// Execute the prepared statement for each order
foreach ($orders as $order) {
    $stmt->bindParam(':user_id', $order['user_id']);
    $stmt->bindParam(':owner_id', $order['owner_id']);
    $stmt->bindParam(':order_date', $order['order_date']);
    $stmt->bindParam(':total_amount', $order['total_amount']);
    $stmt->bindParam(':status', $order['status']);
    $stmt->execute();
}

echo "Fake orders have been added successfully!";
?>
