<?php
// Start session
session_start();

// Include database configuration
require 'config.php';

// Redirect to login page if the user is not logged in
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
    <title>Edit Product - Dashboard Admin Template</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700" />
    <!-- https://fonts.google.com/specimen/Roboto -->
    <link rel="stylesheet" href="css/fontawesome.min.css" />
    <!-- https://fontawesome.com/ -->
    <link rel="stylesheet" href="jquery-ui-datepicker/jquery-ui.min.css" type="text/css" />
    <!-- http://api.jqueryui.com/datepicker/ -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <!-- https://getbootstrap.com/ -->
    <link rel="stylesheet" href="css/templatemo-style.css">
    <!--
  Product Admin CSS Template
  https://templatemo.com/tm-524-product-admin
  -->
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="container tm-mt-big tm-mb-big">
        <div class="row">
            <div class="col-xl-9 col-lg-10 col-md-12 col-sm-12 mx-auto">
                <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
                    <div class="row">
                        <div class="col-12">
                            <h1 class="tm-block-title d-inline-block">Edit Product</h1>
                        </div>
                    </div>
                    <div class="row tm-edit-product-row">
                        <div class="col-xl-6 col-lg-6 col-md-12">
                            <form action="" method="post" class="tm-edit-product-form">
                                <?php
                                // Fetch unique product categories
                                $stmt = $conn->prepare("SELECT * FROM coupon where coupon_id = ?");
                                $stmt->bind_param("i", $_GET['id']);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                ?>
                                <?php while ($row = $result->fetch_assoc()) { ?>
                                <div class="row">
                                    <div class="form-group mb-3 col-xs-12 col-sm-6">
                                        <label for="code">Coupon Code</label>
                                        <input id="code" name="code" type="text" class="form-control validate"
                                            value="<?= htmlspecialchars($row['coupon_code']) ?>" required />
                                    </div>
                                    <div class="form-group mb-3 col-xs-12 col-sm-6">
                                        <label for="amount">Coupon Amount</label>
                                        <input id="amount" name="amount" type="text" class="form-control validate"
                                            value="<?= number_format($row['coupon_amount'], 2) ?>/-" required />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group mb-3 col-xs-12 col-sm-6">
                                        <label for="activeStatus">Active Status</label>
                                        <input id="activeStatus" name="activeStatus" type="text"
                                            class="form-control validate"
                                            value="<?= htmlspecialchars($row['is_active']) ?>" required />
                                    </div>
                                    <div class="form-group mb-3 col-xs-12 col-sm-6">
                                        <label for="number">Number of Coupon</label>
                                        <input id="number" name="number" type="text" class="form-control validate"
                                            value="<?= htmlspecialchars($row['number_of_coupons']) ?>" required />
                                    </div>
                                </div>
                        </div>
                        <?php } ?>
                        <div class="col-12">
                            <button type="submit" name="submit" class="btn btn-primary btn-block text-uppercase">Update
                                Now</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="tm-footer row tm-mt-small">
        <div class="col-12 font-weight-light">
            <p class="text-center text-white mb-0 px-4 small">
                Copyright &copy; <b>2018</b> All rights reserved.

                Design: <a rel="nofollow noopener" href="https://templatemo.com" class="tm-footer-link">Template Mo</a>
            </p>
        </div>
    </footer>

    <script src="js/jquery-3.3.1.min.js"></script>
    <!-- https://jquery.com/download/ -->
    <script src="jquery-ui-datepicker/jquery-ui.min.js"></script>
    <!-- https://jqueryui.com/download/ -->
    <script src="js/bootstrap.min.js"></script>
    <!-- https://getbootstrap.com/ -->
</body>

</html>
<?php
if (isset($_POST['submit'])) {
    $code = $_POST['code'];
    $amount = $_POST['amount'];
    $activeStatus = $_POST['activeStatus'];
    $number = $_POST['number'];

    foreach ($_SESSION['adminInfo'] as $info) {
        $modified_by = $info['fullname'];
    }

    // Insert into database
    $stmt = $conn->prepare("UPDATE coupon SET coupon_code = ?, coupon_amount = ?, is_active = ?, number_of_coupons = ?, modified_by = ? WHERE coupon_id = ?");
    $stmt->bind_param("sdiisi", $code, $amount, $activeStatus, $number, $modified_by, $_GET['id']);
    if ($stmt->execute()) {
        echo '<script>alert("Data Modified successfully."); window.location.href = "coupons.php";</script>';
    } else {
        echo '<script>alert("Database Error: " . $stmt->error);</script>';
    }

    $stmt->close();
} else {
    echo '<script>alert("Sorry, there was an error modifying your file.");</script>';
}
?>