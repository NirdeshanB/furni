<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $productId = $_POST['product_id'];
    $newQuantity = $_POST['quantity'];

    // Update the quantity in the database
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
    $stmt->bind_param("ii", $newQuantity, $productId);
    $stmt->execute();

    // Recalculate the item total
    $stmt = $conn->prepare("SELECT product_price FROM cart WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $itemTotal = $product['product_price'] * $newQuantity;

    // Recalculate the cart subtotal
    $stmt = $conn->prepare("SELECT SUM(product_price * quantity) AS cart_subtotal FROM cart");
    $stmt->execute();
    $cartResult = $stmt->get_result();
    $cartData = $cartResult->fetch_assoc();
    $cartSubtotal = $cartData['cart_subtotal'];

    // Apply any coupon discount if present
    $couponDiscount = isset($_SESSION['coupon_discount']) ? $_SESSION['coupon_discount'] : 0;
    $cartTotal = $cartSubtotal - $couponDiscount;

    // Return updated totals
    echo json_encode([
        'status' => 'success',
        'item_total' => $itemTotal,
        'cart_subtotal' => $cartSubtotal,
        'cart_discount' => $couponDiscount,
        'cart_total' => $cartTotal
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>