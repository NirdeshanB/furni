<?php
require 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_POST['submit'])) {
    $category = $_POST['category'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $code = $_POST['code'];
    $description = $_POST['description'];
    $pimage = $_FILES['product_picture']['name'];

    // Default initial counts for specific categories
    $initialCounts = [
        'chair' => 16,
        'table' => 16,
        'sofa' => 16,
        'light' => 5,
    ];

    // Determine the starting count for the category
    if (array_key_exists($category, $initialCounts)) {
        $count = $initialCounts[$category];
    } else {
        $count = 1; // Default starting count for new categories
    }

    // Fetch the highest count for the category from the database
    $stmt = $conn->prepare("SELECT MAX(CAST(SUBSTRING_INDEX(product_image, '-', -1) AS UNSIGNED)) AS max_count FROM product WHERE product_category = ?");
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $currentMaxCount = $row['max_count'];
        if (!is_null($currentMaxCount)) {
            $count = $currentMaxCount + 1; // Increment the count based on the max found
        }
    }
    $stmt->close();

    // Increment the initial count for specific categories
    if (array_key_exists($category, $initialCounts)) {
        $initialCounts[$category] += 1;
    }

    $targetDir = addslashes("furni-1.0.0\\images\\"); // Update this path if needed
    $imageFileType = strtolower(pathinfo($pimage, PATHINFO_EXTENSION));
    $newFileName = $category . "-$count" . "." . $imageFileType; // Rename file to category-count.extension
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
        // If a file already exists with this name, delete it
        unlink($targetFile);
    }

    foreach ($_SESSION['adminInfo'] as $info) {
        $added_by = $info['fullname'];
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

            // Insert into database
            $stmt = $conn->prepare("INSERT INTO product (product_category, product_name, product_price, product_image, product_code, product_description, modified_by, added_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdsssss", $category, $name, $price, $imagePath, $code, $description, $modified_by, $added_by);
            if ($stmt->execute()) {
                echo '<script>alert("Data inserted successfully.");window.location.href = "admin\products.php"</script>';
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