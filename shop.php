<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="author" content="Untree.co" />
    <link rel="shortcut icon" href="images/furni.png" />
    <title>Furni</title>
    <!-- Styles -->
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
</head>

<body>
    <!-- Header -->
    <?php include 'header.php'; ?>

    <!-- Shop Section -->
    <div class="untree_co-section product-section before-footer-section">
        <div class="container">
            <div id="message"></div>
            <div class="row">
                <?php
                // Database connection
                $conn = new mysqli('localhost', 'root', '', 'furni');
                if ($conn->connect_error) {
                    die('Could not connect: ' . $conn->connect_error);
                }

                // Fetch products
                $stmt = $conn->prepare("SELECT * FROM product order by product_name");
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) { ?>
                <div class="col-12 col-md-4 col-lg-3 mb-5">
                    <a class="product-item" href="#">
                        <img src="<?= $row['product_image'] ?>" class="img-fluid product-thumbnail" />
                        <div class="product">
                            <h3 class="product-title"><?= $row['product_name'] ?></h3>
                            <strong class="product-price">Rs. <?= number_format($row['product_price'], 2) ?>/-</strong>
                        </div>
                        <span class="icon-cross">
                            <form class="form-submit">
                                <input type="hidden" name="pid" value="<?= $row['product_id'] ?>">
                                <input type="hidden" name="pname" value="<?= $row['product_name'] ?>">
                                <input type="hidden" name="pprice" value="<?= $row['product_price'] ?>">
                                <input type="hidden" name="pimage" value="<?= $row['product_image'] ?>">
                                <input type="hidden" name="pcode" value="<?= $row['product_code'] ?>">
                                <button type="submit" name="submit"><img src="images/cart.svg"
                                        class="img-fluid" /></button>
                            </form>
                        </span>
                    </a>
                </div>
                <?php }
                $conn->close();
                ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
        $(".form-submit").on("submit", function(e) {
            e.preventDefault();

            // Check if user is logged in (using session check)
            <?php if (!isset($_SESSION['username'])) { ?>
            // Redirect to login page if not logged in
            alert("You need to log in to add items to the cart.");
            window.location.href = "login.php";
            <?php } else { ?>
            // Proceed to add item to cart
            var $form = $(this);
            let productdetails = {
                pid: $form.find("input[name='pid']").val(),
                pname: $form.find("input[name='pname']").val(),
                pprice: $form.find("input[name='pprice']").val(),
                pimage: $form.find("input[name='pimage']").val(),
                pcode: $form.find("input[name='pcode']").val(),
                username: <?= json_encode($_SESSION['username']) ?> // Pass username
            };

            // Send the AJAX request
            $.ajax({
                url: 'action.php',
                method: 'POST',
                data: productdetails,
                success: function(response) {
                    $("#message").html(response);
                }
            });
            <?php } ?>
        });
    });
    </script>
</body>

</html>