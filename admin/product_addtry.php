<?php require 'config.php'; ?>

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
                            <h2 class="tm-block-title d-inline-block">Add Product</h2>
                        </div>
                    </div>
                    <div class="row tm-edit-product-row">
                        <div class="col-xl-6 col-lg-6 col-md-12">
                            <form action="product_addtry.php" class="tm-edit-product-form" method="POST"
                                enctype="multipart/form-data">
                                <?php
                                // Fetch unique product categories
                                $stmt = $conn->prepare("SELECT DISTINCT category FROM categories");
                                $stmt->execute();
                                $result = $stmt->get_result();
                                ?>

                                <div class="form-group mb-3">
                                    <label for="category">Product Category</label>
                                    <select class="custom-select tm-select-accounts" id="category" name="category"
                                        required>
                                        <option selected>Select category</option>
                                        <?php while ($row = $result->fetch_assoc()) { ?>
                                            <option value="<?= htmlspecialchars($row['category']) ?>">
                                                <?= htmlspecialchars($row['category']) ?>
                                            </option>
                                        <?php }
                                        $stmt->close();
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="name">Product Name</label>
                                    <input id="name" name="name" type="text" class="form-control validate" required />
                                </div>
                                <div class="row">
                                    <div class="form-group mb-3 col-xs-12 col-sm-6">
                                        <label for="price">Product Price</label>
                                        <input id="price" name="price" type="text" class="form-control validate"
                                            required />
                                    </div>
                                    <div class="form-group mb-3 col-xs-12 col-sm-6">
                                        <label for="code">Product Code</label>
                                        <input id="code" name="code" type="text" class="form-control validate"
                                            required />
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="description">Description</label>
                                    <textarea id="description" name="description" class="form-control validate" rows="3"
                                        required></textarea>
                                </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 mx-auto mb-4">
                            <div class="form-group mb-3">
                                <label for="image">Product Image</label>
                            </div>
                            <div class="custom-file mt-3 mb-3">
                                <input type="file" name="product_picture" id="profile" required />
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" name="submit" class="btn btn-primary btn-block text-uppercase">Add
                                Product
                                Now</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // JavaScript to handle showing the new category input field
        document.getElementById('category').addEventListener('change', function () {
            const newCategoryField = document.getElementById('new-category-field');
            newCategoryField.style.display = this.value === 'new' ? 'block' : 'none';
        });
    </script>
    <script>
        window.onload = function () {
            document.querySelector('.tm-edit-product-form').reset();
        };
    </script>

</body>

</html>

<?php
if (isset($_POST['submit'])) {
    $category = $_POST['category'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $code = $_POST['code'];
    $description = $_POST['description'];
    $pimage = $_FILES['product_picture']['name'];

    $count = 1;
    if (in_array($category, ['chair', 'table', 'sofa'])) {
        $count = 16; // Update this logic as per your requirement
    }

    $targetDir = addslashes("..\\furni-1.0.0\\images\\"); // Update this path if needed
    $imageFileType = strtolower(pathinfo($pimage, PATHINFO_EXTENSION));
    $newFileName = $category . "-$count" . "." . $imageFileType; // Rename file to username.extension
    $targetFile = $targetDir . $newFileName;
    $uploadOk = 1;


    // Check file size
    if ($_FILES["product_picture"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (!in_array($imageFileType, ['jpg', 'png', 'jpeg'])) {
        echo "Sorry, only JPG, PNG, and JPEG files are allowed.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($targetFile)) {
        // If a file already exists with this username, delete it
        unlink($targetFile);
    }

    // Check if $uploadOk is set to 0 by errors
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // Move the uploaded file
        if (move_uploaded_file($_FILES["product_picture"]["tmp_name"], $targetFile)) {
            // Path to store in the database (you can store either relative or absolute path)
            $imagePath = $targetDir . $newFileName;
            $modified_by = "0";
            foreach ($_SESSION['adminInfo'] as $info) {
                $added_by = $info['fullname'];
            }
            // Insert into database
            $stmt = $conn->prepare("INSERT INTO product (product_category, product_name, product_price, product_image, product_code, product_description, modified_by, added_by) VALUES (?, ?, ?,?, ?, ?,?,?)");
            $stmt->bind_param("ssdsssss", $category, $name, $price, $imagePath, $code, $description, $modified_by, $added_by);
            if ($stmt->execute()) {
                echo '<script>alert("Data inserted successfully.");</script>';
                $count++;
            } else {
                echo '<script>alert("Database Error: " . $stmt->error);</script>';
            }

            $stmt->close();
        } else {
            echo '<script>alert("Sorry, there was an error uploading your file.");</script>';
        }
    }
}
?>