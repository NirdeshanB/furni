<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

echo '<pre>';
print_r($_SESSION);
echo '</pre>';
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://a.khalti.com/api/v2/epayment/initiate/',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => json_encode(array(
        "return_url" => "http://localhost/project/furni-1.0.0/thank_you.php",
        "website_url" => "http://localhost/project/furni-1.0.0/checkout.php",
        "amount" => $total * 100,
        "product_name" => "product_name",
    )),
    CURLOPT_HTTPHEADER => array(
        'Authorization: Key live_secret_key_68791341fdd94846a146f0457ff7b455',
        'Content-Type: application/json',
    ),
));

$response = curl_exec($curl);
curl_close($curl);

$responseData = json_decode($response, true);

if (isset($responseData['payment_url'])) {
    header('Location: ' . $responseData['payment_url']);
    exit();
} else {
    $error = "Error: Unable to initiate payment.";
    echo $error;
}