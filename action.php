<?php
require 'config.php';

// Fetch data from the POST request
if (isset($_POST['pid'])) {
    $pid = $_POST['pid'];
    $pname = $_POST['pname'];
    $pprice = $_POST['pprice'];
    $pimage = $_POST['pimage'];
    $pcode = $_POST['pcode'];
    $username = $_POST['username'];
    $pqty = 1;
    $tprice = $pprice * $pqty;

    // Check if the product already exists in the cart
    $stmt = $conn->prepare("SELECT product_code FROM cart WHERE product_code=?");
    $stmt->bind_param("s", $pcode);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        // Insert the new product into the cart
        $query = $conn->prepare("INSERT INTO cart (product_code, product_name, product_price, product_image, quantity, total_price, Username) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $query->bind_param("ssisiis", $pcode, $pname, $pprice, $pimage, $pqty, $tprice, $username);
        $query->execute();

        // Send a success message
        echo '<script>alert("Item added to your cart!");</script>';
    } else {
        // Send an error message
        echo '<script>alert("Item is already in your cart!");</script>';
    }
} else {
    // Send an error message for invalid product details
    echo '<script>alert("Invalid product details!");</script>';
}
?>