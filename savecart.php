<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'config.php';

echo "<pre>";
print_r($_SESSION);
echo "</pre>";

if (isset($_POST['savecart'])) {
    foreach ($_SESSION['cartItems'] as $item) {
        $productId = $item['id'];
        $quantity = $item['quantity'];

        // Update the cart table in the database
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND username = ?");
        $stmt->bind_param("iis", $quantity, $productId, $_SESSION['username']);
        if ($stmt->execute()) {
            echo '<script>alert("Data Modified successfully."); window.location.href = "carttry.php";</script>';
        } else {
            echo '<script>alert("Database Error: " . $stmt->error);</script>';
        }

        $stmt->close();
    }
} else {
    echo '<script>alert("Sorry, there was an error modifying your file.");</script>';
}
?>