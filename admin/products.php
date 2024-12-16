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
                                <th scope="col">PRODUCT CODE</th>
                                <th scope="col">PRODUCT CATEGORY</th>
                                <th scope="col">PRODUCT NAME</th>
                                <th scope="col">RATE</th>
                                <th scope="col">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>


                            <?php
                            // Fetch products
                            $stmt = $conn->prepare("SELECT * FROM product ORDER BY product_name");
                            $stmt->execute();
                            $result = $stmt->get_result();

                            while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td></td>
                                <td><?= htmlspecialchars($row['product_code']) ?></td>
                                <td class="tm-product-name"><?= htmlspecialchars($row['product_category']) ?></td>
                                <td><?= htmlspecialchars($row['product_name']) ?></td>
                                <td>Rs. <?= number_format($row['product_price'], 2) ?>/-</td>
                                <td>
                                    <form method="POST" action="#" class="form-submit" style="display:inline;">
                                        <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
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
                <a href="add-product.php" class="btn btn-primary btn-block text-uppercase mb-3">Add new product</a>
            </div>
        </div>
        <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4 tm-block-col">
            <div class="tm-bg-primary-dark tm-block tm-block-product-categories">
                <h2 class="tm-block-title">Product Categories</h2>
                <div class="tm-product-table-container">
                    <table class="table tm-table-small tm-product-table">
                        <tbody>

                            <?php
                            $stmt = $conn->prepare("SELECT DISTINCT category FROM categories ORDER BY category");
                            $stmt->execute();
                            $result = $stmt->get_result();

                            while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td class="tm-product-name"><?= htmlspecialchars($row['category']) ?></td>
                            </tr>
                            <?php } ?>
                </div>
            </div>
            <!-- table container -->
            </tbody>
            </table>
        </div>
        <button class="btn btn-primary btn-block text-uppercase mb-3 addcategory">Add new Category</button>
        <div id="newcategory-container" style="display: none;">
            <form method="POST" action="#">
                <label for="new-category">Enter New Category</label>
                <input type="text" class="form-control" id="new-category" name="new-category" required>
                <button type="submit" name="submit" class="btn btn-primary btn-block text-uppercase mt-3">Save</button>
            </form>
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
        const productId = $(this).closest("form").find('input[name="product_id"]').val();
        window.location.href = `edit-product.php?id=${productId}`;
    });

    $(".delete").on("click", function(event) {
        if (!confirm("Are you sure you want to delete this product?")) {
            event.preventDefault();
        }
    });

    $(".addcategory").on("click", function() {
        $("#newcategory-container").toggle();
    });
});
</script>

</body>

</html>
<?php
require 'config.php';
// Redirect to login page if the user is not logged in
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle adding a new category
    if (isset($_POST['submit'])) {
        $newCategory = filter_var(trim($_POST['new-category']), FILTER_SANITIZE_STRING);
        if (!empty($newCategory)) {
            $stmt = $conn->prepare("INSERT INTO categories (category) VALUES (?)");
            $stmt->bind_param('s', $newCategory);
            $stmt->execute();
            $stmt->close();
            // Redirect to prevent resubmission
            header('Location: products.php');
            exit();
        } else {
            echo "<script>alert('Category name cannot be empty.');</script>";
        }
    }

    // Handle deleting a product
    if (isset($_POST['delete'])) {
        $product_id = $_POST['product_id'];
        $stmt = $conn->prepare("DELETE FROM product WHERE product_id = ?");
        $stmt->bind_param('i', $product_id);
        $stmt->execute();
        $stmt->close();
        // Redirect to prevent resubmission
        header('Location: products.php');
        exit();
    }
}
?>