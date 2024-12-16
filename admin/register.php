<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Register Page - Product Admin Template</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700" />
    <!-- https://fonts.google.com/specimen/Open+Sans -->
    <link rel="stylesheet" href="css/fontawesome.min.css" />
    <!-- https://fontawesome.com/ -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <!-- https://getbootstrap.com/ -->
    <link rel="stylesheet" href="css/templatemo-style.css" />
    <!--
  Product Admin CSS Template
  https://templatemo.com/tm-524-product-admin
  -->
</head>

<body>
    <div><?php include 'header.php'; ?></div>

    <div class="container tm-mt-big tm-mb-big">
        <div class="row">
            <div class="col-12 mx-auto tm-login-col">
                <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
                    <div class="row">
                        <div class="col-12 text-center">
                            <h2 class="tm-block-title mb-4">Register to Admin</h2>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="register.php" method="POST" enctype="multipart/form-data"
                                class="tm-login-form">
                                <div class="form-group">
                                    <label for="fullname">Full Name</label>
                                    <input name="fullname" type="text" class="form-control validate" id="fullname"
                                        required />
                                </div>
                                <div class="form-group row mt-3">
                                    <div class="form-group col-lg-6">
                                        <label for="username">Username</label>
                                        <input name="username" type="text" class="form-control validate" id="username"
                                            required />
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="phonenumber">Phone Number</label>
                                        <input name="phonenumber" type="text" class="form-control validate"
                                            id="phonenumber" required />
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="email">Email</label>
                                    <input name="email" type="email" class="form-control validate" id="email" value=""
                                        required />
                                </div>
                                <div class="form-group row mt-3">
                                    <div class="form-group col-lg-6">
                                        <label for="password">Password</label>
                                        <input name="password" type="password" class="form-control validate"
                                            id="password" required />
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="cpassword">Confirm Password</label>
                                        <input name="cpassword" type="password" class="form-control validate"
                                            id="cpassword" required />
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="profile">Profile Picture</label>
                                    <input type="file" name="profile_picture" id="profile" required />
                                </div>
                                <button type="submit" name="submit" class="btn btn-primary btn-block text-uppercase">
                                    Register
                                </button>
                            </form>
                        </div>
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
</body>

</html>

<?php
// Database connection details
require 'config.php';

// Validate form data
if (isset($_POST['submit'])) {
    $fullname = $_POST["fullname"];
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $cpassword = $_POST["cpassword"];
    $pnumber = $_POST["phonenumber"];
    $profilePicture = $_FILES["profile_picture"]["name"];

    // Validate input fields (add more validation as needed)
    if (
        empty($fullname) || empty($email) || empty($username) || empty($password) || empty($cpassword) ||
        empty($pnumber) || empty($profilePicture)
    ) {
        echo "Please fill in all required fields.";
    } elseif ($password !== $cpassword) {
        echo "Passwords do not match.";
    } else {
        // Validate email format (add more validation if needed)
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format.";
        } else {
            // Handle profile picture upload
            $targetDir = addslashes("..\\furni-1.0.0\\Admin\\"); // Update this path if needed
            $imageFileType = strtolower(pathinfo($profilePicture, PATHINFO_EXTENSION));
            $newFileName = $username . "." . $imageFileType; // Rename file to username.extension
            $targetFile = $targetDir . $newFileName;
            $uploadOk = 1;

            // Check file size
            if ($_FILES["profile_picture"]["size"] > 500000) { // 500 KB
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                echo "Sorry, only JPG, PNG & JPEG files are allowed.";
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
                if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFile)) {
                    // Path to store in the database (you can store either relative or absolute path)

                    // Save data to database
                    $sql = "INSERT INTO admin (Full_name, Username, Phone_number, email, Password, Profile_picture) VALUES 
                        ('$fullname', '$username', '$pnumber', '$email', '$password', '$targetFile')";

                    if ($conn->query($sql) === TRUE) {
                        echo '<script>alert("Data inserted successfully.");</script>';
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                } else {
                    echo '<script>alert("Sorry, there was an error uploading your file.");</script>';
                }
            }
        }
    }
}

$conn->close();
?>