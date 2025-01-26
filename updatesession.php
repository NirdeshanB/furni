<?php
session_start();

if (isset($_POST['quantity'])) {
    $_SESSION['Updatedquantity'] = $_POST['quantity'];
    echo json_encode([
        'status' => 'success',
        'message' => 'Quantity updated in session.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Quantity not provided.'
    ]);
}