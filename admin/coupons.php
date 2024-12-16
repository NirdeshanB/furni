<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit(); // Stop further script execution
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Product Page - Admin HTML Template</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700" />
    <!-- https://fonts.google.com/specimen/Roboto -->
    <link rel="stylesheet" href="css/fontawesome.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <!-- https://fontawesome.com/ -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <!-- https://getbootstrap.com/ -->
    <link rel="stylesheet" href="css/templatemo-style.css" />
    <!--
  Product Admin CSS Template
  https://templatemo.com/tm-524-product-admin
  -->


</head>
<div><?php include 'header.php'; ?></div>

<div class="container mt-5">
    <div class="row tm-content-row">
        <div class="col-sm-12 col-md-12 col-lg-8 col-xl-8 tm-block-col">
            <div class="tm-bg-primary-dark tm-block tm-block-products">
                <div class="tm-product-table-container">
                    <table class="table table-hover tm-table-small tm-product-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th scope="col">COUPON CODE</th>
                                <th scope="col">COUPON AMOUNT</th>
                                <th scope="col">ACTIVE</th>
                                <th scope="col">NUMBER OF COUPON</th>
                                <th scope="col">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>


                            <?php
                            // Fetch products
                            $stmt = $conn->prepare("SELECT * FROM coupon ORDER BY coupon_code");
                            $stmt->execute();
                            $result = $stmt->get_result();

                            while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td></td>
                                <td><?= htmlspecialchars($row['coupon_code']) ?></td>
                                <td> Rs. <?= number_format($row['coupon_amount'], 2) ?>/-</td>
                                <td><?= $row['is_active'] ? 'Yes' : 'No' ?></td>
                                <td><?= htmlspecialchars($row['number_of_coupons']) ?></td>
                                <td>
                                    <form method="POST" action="#" class="form-submit" style="display:inline;">
                                        <input type="hidden" name="coupon_id" value="<?= $row['coupon_id'] ?>">
                                        <button type="button" class="btn btn-link tm-product-delete-link edit-button">
                                            <i class="fa-solid fa-pen-to-square" style="color:white;"></i>
                                        </button>
                                        <button type="submit" name="delete"
                                            class="btn btn-link tm-product-delete-link delete">
                                            <i class="far fa-trash-alt tm-product-delete-icon"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>
                <!-- table container -->
                <a href="add-coupon.php" class="btn btn-primary btn-block text-uppercase mb-3">Add new Coupon</a>
            </div>
        </div>
        <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4 tm-block-col">
            <div class="tm-bg-primary-dark tm-block tm-block-product-categories">
                <h2 class="tm-block-title">Coupons</h2>
                <div class="tm-product-table-container">
                    <table class="table tm-table-small tm-product-table">
                        <thead>
                            <tr>
                                <th scope="col">Coupon Code</th>
                                <th scope="col">Created By</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $stmt = $conn->prepare("SELECT coupon_code, created_by FROM coupon ORDER BY coupon_code");
                            $stmt->execute();
                            $result = $stmt->get_result();

                            while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td class="tm-product-name"><?= htmlspecialchars($row['coupon_code']) ?></td>
                                <td class="tm-product-name"><?= htmlspecialchars($row['created_by']) ?></td>
                            </tr>
                            <?php } ?>
                </div>
            </div>
            <!-- table container -->
            </tbody>
            </table>
        </div>
    </div>
</div>
</div>
</div>
<footer class="tm-footer row tm-mt-small">
    <div class="col-12 font-weight-light">
        <p class="text-center text-white mb-0 px-4 small">
            Copyright &copy; <b>2018</b> All rights reserved.</p>
    </div>
</footer>

<script src="js/jquery-3.3.1.min.js"></script>
<!-- https://jquery.com/download/ -->
<script src="js/bootstrap.min.js"></script>
<!-- https://getbootstrap.com/ -->
<script>
$(function() {
    $(".edit-button").on("click", function(event) {
        event.preventDefault();
        const productId = $(this).closest("form").find('input[name="coupon_id"]').val();
        window.location.href = `edit-coupon.php?id=${productId}`;
    });

    $(".delete").on("click", function(event) {
        if (!confirm("Are you sure you want to delete this product?")) {
            event.preventDefault();
        }
    });
});
</script>

</body>

</html>
<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle deleting a product
    if (isset($_POST['delete'])) {
        $coupon_id = $_POST['coupon_id'];
        $stmt = $conn->prepare("DELETE FROM coupon WHERE coupon_id = ?");
        $stmt->bind_param('i', $coupon_id);
        $stmt->execute();
        $stmt->close();
        // Redirect to prevent resubmission
        header('Location: coupons.php');
        exit();
    }
}
?>