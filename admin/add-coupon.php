<?php require 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Add Product - Dashboard HTML Template</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700" />
    <link rel="stylesheet" href="css/fontawesome.min.css" />
    <link rel="stylesheet" href="jquery-ui-datepicker/jquery-ui.min.css" type="text/css" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/templatemo-style.css">
</head>

<body>

    <?php include 'header.php'; ?>

    <div class="container tm-mt-big tm-mb-big">
        <div class="row">
            <div class="col-xl-9 col-lg-10 col-md-12 col-sm-12 mx-auto">
                <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="tm-block-title d-inline-block">Add Coupon</h2>
                        </div>
                    </div>
                    <div class="row tm-edit-product-row">
                        <div class="col-xl-6 col-lg-6 col-md-12">
                            <form action="" method="POST" class="tm-edit-product-form">
                                <div class="row">
                                    <div class="form-group mb-3 col-xs-12 col-sm-6">
                                        <label for="code">Coupon Code</label>
                                        <input id="code" name="code" type="text" class="form-control validate" value=""
                                            required />
                                    </div>
                                    <div class="form-group mb-3 col-xs-12 col-sm-6">
                                        <label for="amount">Coupon Amount</label>
                                        <input id="amount" name="amount" type="text" class="form-control validate"
                                            value="" required />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group mb-3 col-xs-12 col-sm-6">
                                        <label for="activeStatus">Active Status</label>
                                        <input id="activeStatus" name="activeStatus" type="text"
                                            class="form-control validate" value="" required />
                                    </div>
                                    <div class="form-group mb-3 col-xs-12 col-sm-6">
                                        <label for="number">Number of Coupon</label>
                                        <input id="number" name="number" type="text" class="form-control validate"
                                            value="" required />
                                    </div>
                                </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" name="submit" class="btn btn-primary btn-block text-uppercase">Create
                                new coupon</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<?php
if (isset($_POST['submit'])) {
    $code = $_POST['code'];
    $amount = $_POST['amount'];
    $activeStatus = $_POST['activeStatus'];
    $number = $_POST['number'];

    foreach ($_SESSION['adminInfo'] as $info) {
        $created_by = $info['fullname'];
    }

    $stmt = $conn->prepare("INSERT INTO coupon (coupon_code, coupon_amount, is_active, number_of_coupons, created_by) VALUES
(?, ?, ?, ?, ?)");
    $stmt->bind_param("sdsis", $code, $amount, $activeStatus, $number, $created_by);
    if ($stmt->execute()) {
        echo '
<script>
alert("Data inserted successfully.");
window.location.href = "coupons.php";
</script>';
    } else {
        echo '
<script>
alert("Database Error: ".$stmt - > error);
</script>';
    }

    $stmt->close();
} else {
    echo '
<script>
alert("Sorry, there was an error creating your file.");
</script>';
}
?>