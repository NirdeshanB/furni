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
                        <h1>Shop</h1>
                        <p class="mb-4">
                            Shop by category, table, sofa, and more. Filter by price, and material to find the furniture
                            that fits your needs.
                        </p>
                        <p>
                            <a href="" class="btn btn-secondary me-2">Shop Now</a><a href="#"
                                class="btn btn-white-outline">Explore</a>
                        </p>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="hero-img-wrap">
                        <img src="images/couch.png" class="img-fluid" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Hero Section -->

    <div class="untree_co-section product-section before-footer-section">
        <div class="container">
            <div id="message"></div>
            <div class="row">
                <!-- Start Column 1 -->
                <?php
                $db = 'localhost';
                $dbname = 'furni';
                $user = 'root';
                $pass = '';

                $conn = mysqli_connect($db, $user, $pass, $dbname);

                if ($conn->connect_error) {
                    die('Could not connect' . mysqli_connect_error());
                }
                $stmt = $conn->prepare("select * from product");
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) { ?>
                    <div class="col-12 col-md-4 col-lg-3 mb-5">
                        <a class="product-item" href="#">
                            <img src="<?= $row['product_image'] ?>" class="img-fluid product-thumbnail" height="500px"
                                width="500px" />
                            <div class="product">
                                <h3 class="product-title"><?= $row['product_name'] ?></h3>
                                <strong class="product-price">Rs. <?= number_format($row['product_price'], 2) ?>/-</strong>
                            </div>
                            <span class="icon-cross">
                                <form action="action.php" method="post" class="form-submit">
                                    <input type="hidden" name="pid" value="<?= $row['product_id'] ?>">
                                    <input type="hidden" name="pname" value="<?= $row['product_name'] ?>">
                                    <input type="hidden" name="pprice" value="<?= $row['product_price'] ?>">
                                    <input type="hidden" name="pimage" value="<?= $row['product_image'] ?>">
                                    <input type="hidden" name="pcode" value="<?= $row['product_code'] ?>">
                                    <button type="submit" name="submit"><img src="images/cross.svg"
                                            class="img-fluid" /></button>
                                </form>
                            </span>
                        </a>
                    </div>

                    <?php
                }
                $conn->close();
                ?>
            </div>
        </div>
    </div>

    <!-- Start Footer Section -->
    <?php include 'footer.php'; ?>
    <!-- End Footer Section -->

    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/tiny-slider.js"></script>
    <script src="js/custom.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            // Target form submit event for handling AJAX request
            $(".form-submit").on("submit", function (e) {
                e.preventDefault();
                var $form = $(this); // Get the form element

                // Gather form data
                let productdetails = {
                    pid: $form.find("input[name='pid']").val(),
                    pname: $form.find("input[name='pname']").val(),
                    pprice: $form.find("input[name='pprice']").val(),
                    pimage: $form.find("input[name='pimage']").val(),
                    pcode: $form.find("input[name='pcode']").val(),
                };

                // Send the AJAX request
                $.ajax({
                    url: 'action.php',
                    method: 'POST',
                    data: productdetails,
                    success: function (response) {
                        $("#message").html(response);
                    }
                });
            });
        });
    </script>
</body>

</html>