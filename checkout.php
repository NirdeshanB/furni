<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require 'config.php'; // Include your database connection

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Fetch user information from the database
    $query = "SELECT * FROM user WHERE Username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username); // Bind as a string
    $stmt->execute();
    $result = $stmt->get_result();
    $userDetails = $result->fetch_assoc();
} else {
    echo "User not logged in.";
    exit;
}

// Retrieve cart items from the session
$cart_items = isset($_SESSION['cart_items']) ? $_SESSION['cart_items'] : [];

// Calculate total amount
$totalAmount = 0;
foreach ($cart_items as $item) {
    $totalAmount += $item['total'];
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="author" content="Untree.co" />
    <link rel="shortcut icon" href="images/furni.png" />
    <meta name="description" content="" />
    <meta name="keywords" content="bootstrap, bootstrap4" />

    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="css/tiny-slider.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <link rel="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" />
    <title>Furni</title>
    <style>
        .paymentmethods img {
            height: 50px;
            margin: 10px;
        }
    </style>
</head>

<body>
    <!-- Start Header/Navigation -->
    <?php include 'header.php'; ?>
    <!-- End Header/Navigation -->

    <!-- Start Hero Section -->
    <div class="hero">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-5">
                    <div class="intro-excerpt">
                        <h1>Checkout</h1>
                    </div>
                </div>
                <div class="col-lg-7"></div>
            </div>
        </div>
    </div>
    <!-- End Hero Section -->

    <div class="untree_co-section">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-5 mb-md-0">
                    <h2 class="h3 mb-3 text-black">Billing Details</h2>
                    <div class="p-3 p-lg-5 border bg-white">
                        <div class="form-group">
                            <label for="c_country" class="text-black">Country <span class="text-danger">*</span></label>
                            <select id="c_country" class="form-control" required>
                                <option value="1" selected>Nepal</option>
                                <option value="2">Korea</option>
                                <option value="3">China</option>
                                <option value="4">Indonesia</option>
                                <option value="5">Ghana</option>
                                <option value="6">Albania</option>
                                <option value="7">Bahrain</option>
                                <option value="8">India</option>
                                <option value="9">Dominican Republic</option>
                                <option value="10">Bangladesh</option>
                                <option value="11">Pakistan</option>
                            </select>
                        </div>
                        <div class="form-group row">
                            <div class="col-md">
                                <label for="c_fname" class="text-black">Full Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="c_fname" name="c_fname"
                                    value="<?= $userDetails['Full_Name'] ?>" required />
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="c_address" class="text-black">Address <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="c_address" name="c_address"
                                    value="<?= $userDetails['Address'] ?>" placeholder="Street address" required />
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="c_city" class="text-black">City <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="c_city" name="c_city"
                                    value="<?= $userDetails['City'] ?>" required />
                            </div>
                            <div class="col-md-6">
                                <label for="c_postal_zip" class="text-black">Postal / Zip <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="c_postal_zip" name="c_postal_zip"
                                    value="<?= $userDetails['Postal_code'] ?>" required />
                            </div>
                        </div>

                        <div class="form-group row mb-5">
                            <div class="col-md-6">
                                <label for="c_email_address" class="text-black">Email Address <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="c_email_address" name="c_email_address"
                                    value="<?= $userDetails['Email'] ?>" required />
                            </div>
                            <div class="col-md-6">
                                <label for="c_phone" class="text-black">Phone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="c_phone" name="c_phone"
                                    value="<?= $userDetails['Phone_number'] ?>" placeholder="Phone Number" required />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row mb-5">
                        <div class="col-md-12">
                            <h2 class="h3 mb-3 text-black">Your Order</h2>
                            <div class="p-3 p-lg-5 border bg-white">
                                <div class="checkout-container">
                                    <h3>Checkout</h3>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Quantity</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($_SESSION['cartItems'] as $item): ?>
                                                <tr>
                                                    <td><?= $item['product_name'] ?></td>
                                                    <td><?= $item['quantity'] ?></td>
                                                    <td> Rs. <?= number_format($item['item_total'], 2) ?>/-</td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr>
                                                <td colspan='2' align="center"><b>Discount Amount</b></td>
                                                <td>
                                                    <b>Rs. <?= number_format($_SESSION['coupon_discount'], 2) ?>/-</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan='2' align="center"><b>Total Amount</b></td>
                                                <td>
                                                    <b>Rs. <?= number_format($_SESSION['grandTotal'], 2) ?>/-</b>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>


                                <div class="border p-3 mb-5">
                                    <h3>Payment Methods</h3>
                                    <div class="py-2 paymentmethods">
                                        <!-- <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="paymentMethods"
                                                id="inlineRadio1" value="paypal">
                                            <label class="form-check-label" for="inlineRadio1"><img
                                                    src="images/paypal.png" /></label>
                                        </div> -->
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="paymentMethods"
                                                id="inlineRadio2" value="stripe">
                                            <label class="form-check-label" for="inlineRadio2"><img
                                                    src="images/stripe.png" /></label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="paymentMethods"
                                                id="inlineRadio4" value="khalti">
                                            <label class="form-check-label" for="inlineRadio4"><img
                                                    src="images/khalti.png" /></label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="paymentMethods"
                                                id="inlineRadio3" value="cash">
                                            <label class="form-check-label" for="inlineRadio3"><img
                                                    src="images/cash.png" /></label>
                                        </div>

                                    </div>

                                    <div class="form-group">
                                        <button class="btn btn-black btn-lg py-3 btn-block" id="placeOrderButton">Place
                                            Order</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- </form> -->
        </div>
    </div>

    <!-- Start Footer Section -->
    <?php include 'footer.php'; ?>
    <!-- End Footer Section -->
    <script src="https://kit.fontawesome.com/cc6ca513a2.js" crossorigin="anonymous"></script>
    <script src="js/tiny-slider.js"></script>
    <script src="js/custom.js"></script>
    <script>
        //disable the place order button if all required fields are not filled
        document.getElementById("placeOrderButton").addEventListener("click", function () {
            var selectedPaymentMethod = document.querySelector('input[name="paymentMethods"]:checked').value;

            // Form validation
            var formFields = document.querySelectorAll(".form-control[required]");
            var isFormFilled = true;

            for (var i = 0; i < formFields.length; i++) {
                if (formFields[i].value === "") {
                    isFormFilled = false;
                    break; // Stop the loop if a field is empty
                }
            }
            if (isFormFilled) {
                // Payment method selection logic (unchanged)
                switch (selectedPaymentMethod) {
                    // case "paypal":
                    //     window.location.href = "https://www.paypal.com";
                    //     break;
                    case "stripe":
                        window.location = "stripe.php";
                        break;
                    case "cash":
                        window.location.href = "thankyou.php";
                        break;
                    case "khalti":

                        break;
                    default:
                        alert("Please select a payment method.");
                }
            } else {
                alert("Please fill out all required fields before placing your order.");
            }
        });
    </script>
</body>

</html>