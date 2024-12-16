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
                                $stmt = $conn->prepare("SELECT * FROM product where product_id = ?");
                                $stmt->bind_param("i", $_GET['id']);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                $SESSION['product_id'] = $_GET['id'];
                                ?>
                                <?php while ($row = $result->fetch_assoc()) { ?>
                                <div class="form-group mb-3">
                                    <label for="name">Product Name
                                    </label>
                                    <input id="name" name="name" type="text" class=" form-control"
                                        value="<?= htmlspecialchars($row['product_name']) ?>" validate">

                                </div>
                                <div class="form-group mb-3">
                                    <label for="description">Description</label>
                                    <textarea name="description" class="form-control validate tm-small" rows="5"
                                        required><?= htmlspecialchars($row['product_description']) ?></textarea>
                                </div>
                                <div class="row">
                                    <div class="form-group mb-3 col-xs-12 col-sm-6">
                                        <label for="price">Product Price</label>
                                        <input id="price" name="price" type="text" class="form-control validate"
                                            value="<?= htmlspecialchars($row['product_price']) ?>" required />
                                    </div>
                                    <div class="form-group mb-3 col-xs-12 col-sm-6">
                                        <label for="code">Product Code</label>
                                        <input id="code" name="code" type="text" class="form-control validate"
                                            value="<?= htmlspecialchars($row['product_code']) ?>" required />
                                    </div>
                                </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 mx-auto mb-4">
                            <div class="tm-product-img-edit mx-auto">
                                <img src="..\\<?= $row['product_image'] ?>" class="img-fluid product-thumbnail" />
                                <i class="fas fa-cloud-upload-alt tm-upload-icon"
                                    onclick="document.getElementById('fileInput').click();"></i>
                            </div>
                            <div class="custom-file mt-3 mb-3">
                                <input id="fileInput" type="file" style="display:none;" />
                                <input type="file" name="product_picture" id="profile" />
                                <input type="button" name="updateImage"
                                    class="btn btn-primary btn-block mx-auto updateImage" value="CHANGE IMAGE NOW" />
                            </div>
                        </div>
                        <?php } ?>
                        <div class="col-12">
                            <button type="submit" name="submit" class="btn btn-primary btn-block text-uppercase ">Update
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
    <script>
    $(function() {
        $(".updateImage").on("click", function(event) {
            event.preventDefault();
            const productId = $(this).closest("form").find('input[name="product_id"]').val();
            window.location.href = `admin-updateProductImage.php?id=<?php $SESSION['product_id'] ?>`;
        });
    });
    </script>
</body>

</html>
<?php
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $code = $_POST['code'];

    $stmt = $conn->prepare("UPDATE product SET product_name = ?, product_description = ?, product_price = ?, product_code = ? WHERE product_id = ?");
    $stmt->bind_param("ssdsi", $name, $description, $price, $code, $_GET['id']);
    $stmt->execute();

    if ($stmt->execute()) {
        echo '<script>alert("Data Modified successfully."); window.location.href = "products.php";</script>';
    } else {
        echo '<script>alert("Database Error: " . $stmt->error);</script>';
    }

    $stmt->close();
} else {
    echo '<script>alert("Sorry, there was an error modifying your file.");</script>';
}
?>