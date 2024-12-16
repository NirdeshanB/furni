<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch the current cart data
    $stmt = $conn->prepare("SELECT * FROM cart");
    $stmt->execute();
    $result = $stmt->get_result();

    // Create an order or save cart items as needed
    while ($row = $result->fetch_assoc()) {
        // Example: Insert each cart item into the orders table
        $stmtInsert = $conn->prepare("INSERT INTO orders (product_id, product_name, product_price, quantity, user_id) VALUES (?, ?, ?, ?, ?)");
        $stmtInsert->bind_param("isdii", $row['product_id'], $row['product_name'], $row['product_price'], $row['quantity'], $_SESSION['user_id']);
        $stmtInsert->execute();
    }

    // Optionally clear the cart
    $conn->query("DELETE FROM cart WHERE user_id = " . $_SESSION['user_id']);

    // Return success response
    echo json_encode(['status' => 'success', 'message' => 'Cart saved and proceeding to checkout']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>