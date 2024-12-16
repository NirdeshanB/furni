<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve cart items and coupon discount from the AJAX request
    $cartItems = $_POST['cartItems'];
    $couponDiscount = $_POST['couponDiscount'];

    foreach ($cartItems as $item) {
        $productId = $item['productId'];
        $productName = $item['productName'];
        $productImage = $item['productImage'];
        $quantity = $item['quantity'];
        $total = $item['total'];

        // Prepare and execute the insert query to store/update in the cart table
        $stmt = $conn->prepare("INSERT INTO cart (product_id, product_name, product_image, quantity, product_total, coupon_discount) VALUES (?, ?, ?, ?, ?, ?)
                                ON DUPLICATE KEY UPDATE quantity = VALUES(quantity), product_total = VALUES(product_total), coupon_discount = VALUES(coupon_discount)");
        
        $stmt->bind_param("sssiid", $productId, $productName, $productImage, $quantity, $total, $couponDiscount);
        $stmt->execute();
    }

    // Send a success response
    echo json_encode(['status' => 'success']);
} else {
    // Invalid request
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>