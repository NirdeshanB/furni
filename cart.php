<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'config.php';

// Handle product removal from the cart
if (isset($_GET['remove'])) {
    $id = $_GET['remove'];

    // Prepare and execute delete query
    $stmt = $conn->prepare("DELETE FROM cart WHERE id=?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
}

// Initialize total and subtotal variables
$grandTotal = 0; // Initialize
$coupon_discount = 0; // Initialize

// Calculate grand total from the database
$stmt = $conn->prepare("SELECT SUM(product_price * quantity) AS grand_total FROM cart where username=?");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
if ($row) {
    $grandTotal = $row['grand_total'];
}

if (isset($_POST["submit"])) {
    $coupon_code = $_POST["coupon"];
    $stmt = $conn->prepare("SELECT * FROM coupon WHERE coupon_code = ? AND is_active = 1");
    $stmt->bind_param("s", $coupon_code);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $coupon_discount = $row["coupon_amount"];
    }
}

$cartItems = [];
$stmt = $conn->prepare("SELECT * FROM cart where username=?");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $cartItems[] = [
        'id' => $row['id'],
        'product_name' => $row['product_name'],
        'product_price' => $row['product_price'],
        'quantity' => $row['quantity'],
        'product_image' => $row['product_image'],
        'item_total' => $row['product_price'] * $row['quantity']
    ];
}

$_SESSION['cartItems'] = $cartItems;
$_SESSION['coupon_discount'] = $coupon_discount;
$_SESSION['grandTotal'] = $grandTotal - $coupon_discount;


$isCartEmpty = ($result->num_rows === 0);

if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $productId = $_POST['product_id'];
    $newQuantity = (int) $_POST['quantity'];

    // Update the quantity directly in the database
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
    $stmt->bind_param("ii", $newQuantity, $productId);
    $stmt->execute();

    // Recalculate grand total from the database
    $stmt = $conn->prepare("SELECT SUM(product_price * quantity) AS grand_total FROM cart where username=?");
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $grandTotal = $row['grand_total'];

    // Send a response back to the client
    echo json_encode([
        'status' => 'success',
        'grand_total' => $grandTotal
    ]);
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
                        <h1>Cart</h1>
                    </div>
                </div>
                <div class="col-lg-7"></div>
            </div>
        </div>
    </div>
    <!-- End Hero Section -->

    <div class="untree_co-section before-footer-section">
        <div class="container">
            <div class="row mb-5">
                <form class="col-md-12" method="post">
                    <div class="site-blocks-table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="product-thumbnail">Image</th>
                                    <th class="product-name">Product</th>
                                    <th class="product-price">Price</th>
                                    <th class="product-quantity">Quantity</th>
                                    <th class="product-total">Total</th>
                                    <th class="product-remove">Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $stmt = $conn->prepare("SELECT * FROM cart where username=?");
                                $stmt->bind_param("s", $_SESSION['username']);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $grandTotal = 0; // Initialize grand total
                                
                                while ($row = $result->fetch_assoc()) {
                                    $itemTotal = $row['product_price'] * $row['quantity'];
                                    $grandTotal += $itemTotal;
                                    ?>
                                    <tr>
                                        <td class="product-thumbnail">
                                            <img src="<?= $row['product_image'] ?>" alt="Image" class="img-fluid" />
                                        </td>
                                        <td class="product-name">
                                            <h2 class="h5 text-black"><?= $row['product_name'] ?></h2>
                                        </td>
                                        <td class="product-price" data-id="<?= $row['id'] ?>"
                                            data-price="<?= $row['product_price'] ?>">
                                            Rs. <?= number_format($row['product_price'], 2) ?>/-
                                        </td>
                                        <td>
                                            <div class="input-group mb-3 d-flex align-items-center quantity-container"
                                                style="max-width: 120px">
                                                <div class="input-group-prepend">
                                                    <button class="btn btn-outline-black decrease" type="button">−</button>
                                                </div>
                                                <input class="form-control text-center quantity-amount"
                                                    value="<?= $row['quantity'] ?>" name="quantity" min="1"
                                                    data-id="<?= $row['id'] ?>" />
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-black increase" type="button">+</button>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="item-total" id="total_<?= $row['id'] ?>">
                                            Rs. <?= number_format($itemTotal, 2) ?>/-
                                        </td>
                                        <td>
                                            <a href="cart.php?remove=<?= $row['id'] ?>" class="btn btn-black btn-sm">X</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>

            <!-- Cart Totals Section -->
            <div class="row">
                <div class="col-md-6">
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <a href="shop.php">
                                <button class="btn btn-outline-black btn-sm btn-block">
                                    Continue Shopping
                                </button></a>
                        </div>
                    </div>
                    <div class="row">
                        <form method="post">
                            <div class="col-md-12">
                                <label class="text-black h4" for="coupon">Coupon</label>
                                <p>Enter your coupon code if you have one.</p>
                            </div>
                            <div class="col-md-8 mb-3 mb-md-0">
                                <input type="text" class="form-control py-3" name="coupon" id="coupon"
                                    placeholder="Coupon Code" />
                            </div>
                            <div class="col-md-4">
                                <button type="submit" name="submit" class="btn btn-black">Apply Coupon</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-6 pl-5">
                    <div class="row justify-content-end">
                        <div class="col-md-7">
                            <div class="row">
                                <div class="col-md-12 text-right border-bottom mb-5">
                                    <h3 class="text-black h4 text-uppercase">Cart Totals</h3>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-5">
                                    <span class="text-black">Subtotal</span>
                                </div>
                                <div class="col-md-6 text-right">
                                    <strong class="text-black cart-subtotal">Rs.
                                        <?= number_format($grandTotal, 2) ?>/-</strong>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-5">
                                    <span class="text-black">Coupon Discount</span>
                                </div>
                                <div class="col-md-6 text-right">
                                    <strong class="text-black cart-discount">Rs.
                                        <?= number_format($coupon_discount, 2) ?>/-</strong>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-5">
                                    <span class="text-black h4">Total</span>
                                </div>
                                <div class="col-md-6 text-right">
                                    <strong class="text-black cart-total">Rs.
                                        <?= number_format($grandTotal - $coupon_discount, 2) ?>/-</strong>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="btn">
                                        <a href="checkout.php">
                                            <button class="btn btn-black btn-lg py-3 btn-block" id="checkout-button"
                                                <?php echo $isCartEmpty ? 'disabled' : 'enabled'; ?>>
                                                Proceed To Checkout
                                            </button></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- footer section -->
    <?php include 'footer.php'; ?>
    <!-- End Footer Section -->


    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/tiny-slider.js"></script>
    <script src="js/aos.js"></script>
    <script>
        document.querySelectorAll('.increase').forEach(button => {
            button.addEventListener('click', function () {
                const input = this.closest('.quantity-container').querySelector('.quantity-amount');
                let quantity = parseInt(input.value);
                quantity++;
                input.value = quantity;

                const productId = input.getAttribute('data-id');
                updateTotals(productId, quantity);
            });
        });

        document.querySelectorAll('.decrease').forEach(button => {
            button.addEventListener('click', function () {
                const input = this.closest('.quantity-container').querySelector('.quantity-amount');
                let quantity = parseInt(input.value);
                if (quantity > 1) {
                    quantity--;
                    input.value = quantity;

                    const productId = input.getAttribute('data-id');
                    updateTotals(productId, quantity);
                }
            });
        });

        document.getElementById('checkout-button').addEventListener('click', function () {
            const cartItems = [];

            // Gather all cart items' data
            document.querySelectorAll('.item-total').forEach(itemTotal => {
                const productRow = itemTotal.closest('tr');
                const productId = productRow.querySelector('.product-price').getAttribute('data-id');
                const productName = productRow.querySelector('.product-name h2').innerText;
                const productImage = productRow.querySelector('.product-thumbnail img').src;
                const quantity = parseInt(productRow.querySelector('.quantity-amount').value);
                const productcode = productRow.querySelector('.product-code').innerText;
                const total = parseFloat(itemTotal.innerText.replace('Rs. ', '').replace('/-', '').replace(
                    /,/g, ''));

                cartItems.push({
                    productId,
                    productName,
                    productImage,
                    quantity,
                    total
                });
            });

            const couponDiscount = parseFloat(document.querySelector('.cart-discount').innerText.replace('Rs. ', '')
                .replace('/-', '').replace(/,/g, '')) || 0;

            // Send the cart items and coupon discount to the server
            $.ajax({
                url: 'checkout_process.php',
                method: 'POST',
                data: {
                    cartItems: cartItems,
                    couponDiscount: couponDiscount
                },
                success: function (response) {
                    const data = JSON.parse(response);
                    if (data.status === 'success') {
                        // Redirect to the success page or order confirmation
                        window.location = 'order_confirmation.php'; // or whatever the next page is
                    } else {
                        alert('Error during checkout: ' + data.message);
                    }
                }
            });
        });


        function updateTotals(productId, quantity) {
            const priceElement = document.querySelector(`.product-price[data-id='${productId}']`);
            const price = parseFloat(priceElement.getAttribute('data-price'));
            const itemTotal = price * quantity;

            const totalElement = document.getElementById(`total_${productId}`);
            totalElement.innerText = 'Rs. ' + itemTotal.toFixed(2) + '/-';

            let grandTotal = 0;
            document.querySelectorAll('.item-total').forEach(total => {
                const totalValue = parseFloat(total.innerText.replace('Rs. ', '').replace('/-', '').replace(/,/g,
                    ''));
                grandTotal += totalValue;
            });

            const couponDiscount = parseFloat(document.querySelector('.cart-discount').innerText.replace('Rs. ', '')
                .replace('/-', '').replace(/,/g, '')) || 0;

            document.querySelector('.cart-total').innerText = 'Rs. ' + (grandTotal - couponDiscount).toFixed(2) + '/-';
            document.querySelector('.cart-subtotal').innerText = 'Rs. ' + grandTotal.toFixed(2) + '/-';

            // Check if there are any items in the cart
            const cartItems = document.querySelectorAll('.item-total');
            const isCartEmpty = cartItems.length === 0;

            // Enable or disable the checkout button
            const checkoutButton = document.getElementById('checkout-button');
            checkoutButton.disabled = isCartEmpty;


            document.addEventListener('DOMContentLoaded', () => {
                const cartItems = document.querySelectorAll('.item-total');
                const checkoutButton = document.getElementById('checkout-button');
                checkoutButton.disabled = cartItems.length === 0;
            });
            // Send the updated data to the server via AJAX
            $.ajax({
                url: 'cart.php',
                method: 'POST',
                data: {
                    product_id: productId,
                    quantity: quantity
                },
                success: function (response) {
                    const data = JSON.parse(response);
                    if (data.status === 'success') {
                        console.log('Cart updated successfully:', data);
                    } else {
                        console.log('Error updating cart:', data.message);
                    }
                }
            });
        }
    </script>


</body>

</html>