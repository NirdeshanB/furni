<?php
require 'config.php';
session_start();


if (isset($_POST['updateImage'])) {
    $product_id = $SESSION['product_id'];
    $pimage = $_FILES['product_picture']['name'];

    $targetDir = addslashes("furni-1.0.0\\images\\"); // Update this path if needed
    $imageFileType = strtolower(pathinfo($pimage, PATHINFO_EXTENSION));
    $newFileName = $product_id . "." . $imageFileType;
    $targetFile = $targetDir . $newFileName;
    $uploadOk = 1;

    // Check file size
    if ($_FILES["product_picture"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (!in_array($imageFileType, ['jpg', 'png', 'jpeg'])) {
        echo "Sorry, only JPG, JPEG, PNG files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["product_picture"]["tmp_name"], $targetFile)) {
            $stmt = $conn->prepare("UPDATE product SET product_image = ? WHERE product_id = ?");
            $stmt->bind_param("si", $targetFile, $product_id);
            $stmt->execute();
            if ($stmt->execute()) {
                echo '<script>alert("Data Modified successfully."); window.location.href = "admin\products.php";</script>';
            } else {
                echo '<script>alert("Database Error: " . $stmt->error);</script>';
            }

            $stmt->close();
        } else {
            echo '<script>alert("Sorry, there was an error modifying your file.");</script>';
        }
    }
}
?>