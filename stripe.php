<?php
require __DIR__ . "/vendor/autoload.php";
session_start(); // Start session

// Retrieve cart items from session
$cart_items = isset($_SESSION['cartItems']) ? $_SESSION['cartItems'] : [];

$stripe_secret_key = "sk_test_51Q2kJsEHTxEKWi7T0WORwlEEuMd5l7pO8gUpwRbwgCIcMoH1oPyVOVlwQxQtdUc9maHI1cZz7klPotDWKQFc4z3800AAGyhgka";
\Stripe\Stripe::setApiKey($stripe_secret_key);

// Prepare line items for Stripe checkout session
$line_items = [];
foreach ($cart_items as $item) {
    $line_items[] = [
        'quantity' => $item['quantity'],
        'price_data' => [
            'currency' => 'npr',
            'unit_amount' => $item['product_price'] * 100,
            'product_data' => [
                'name' => $item['product_name'],
                // 'quantity' => $item['quantity'],
                // 'total' => [$item['total']],
            ]
        ]
    ];
}

// Create Stripe Checkout session with the cart items
$checkout_session = \Stripe\Checkout\Session::create([
    "mode" => "payment",
    "success_url" => "http://localhost/php/project/furni-1.0.0/thankyou.php",
    'line_items' => $line_items
]);

http_response_code(303);
header("Location: " . $checkout_session->url);