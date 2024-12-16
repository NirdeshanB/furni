<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Untree.co">
    <link rel="shortcut icon" href="images/furni.png" />

    <meta name="description" content="" />
    <meta name="keywords" content="bootstrap, bootstrap4" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="shortcut icon" href="images/furni.png" />
    <link rel="stylesheet" href="css/login.css" />
</head>

<body>
    <section class="container">
        <header>Registration Form</header>
        <form method="POST" enctype="multipart/form-data" action="register.php" class="form">
            <div class="input-box">
                <label>Full Name<span class="text-danger">*</span></label>
                <input type="text" name="fullname" placeholder="Enter full name" required />
            </div>

            <div class="column">
                <div class="input-box">
                    <label>Email Address<span class="text-danger">*</span></label>
                    <input type="text" name="email" placeholder="Enter email address" required />
                </div>
                <div class="input-box">
                    <label>Username<span class="text-danger">*</span></label>
                    <input type="text" name="username" placeholder="Enter username" required />
                </div>
            </div>

            <div class="column">
                <div class="input-box">
                    <label for="password" class="text-black">Password<span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="password" name="password" required />
                </div>
                <div class="input-box">
                    <label for="cpassword" class="text-black">Confirm Password<span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="cpassword" name="cpassword" required />
                </div>
            </div>

            <div class="column">
                <div class="input-box">
                    <label>Phone Number<span class="text-danger">*</span></label>
                    <input type="text" name="pnumber" placeholder="Enter phone number (+977)" minlength="10" required />
                </div>
                <div class="input-box">
                    <label>Birth Date<span class="text-danger">*</span></label>
                    <input type="date" name="dob" placeholder="Enter birth date" required />
                </div>
            </div>
            <div class="gender-box">
                <h3>Gender</h3>
                <div class="gender-option">
                    <div class="gender">
                        <input type="radio" id="check-male" name="gender" value="male" checked>
                        <label for="check-male">Male</label>
                    </div>
                    <div class="gender">
                        <input type="radio" id="check-female" name="gender" value="female">
                        <label for="check-female">Female</label>
                    </div>
                </div>
                <div class="input-box address">
                    <label>Address<span class="text-danger">*</span></label>
                    <input type="text" name="address" placeholder="Enter street address" required />
                    <div class="column">
                        <div class="select-box">
                            <select name="country" class="form-select">
                                <option selected>Country</option>
                                <option>America</option>
                                <option>Japan</option>
                                <option>India</option>
                                <option>Nepal</option>
                            </select>
                        </div>
                        <input type="text" name="city" placeholder="Enter your city" required />
                    </div>
                    <div class="column">
                        <input type="text" name="region" placeholder="Enter your region" required />
                        <input type="text" name="postalCode" placeholder="Enter postal code" required />
                    </div>
                </div>
                <div class="input-box address">
                    <label for="profile">Profile<span class="text-danger">*</span></label>
                    <input type="file" name="profile_picture" id="profile" required />
                </div>
                <div class="column">
                    <button name="submit">Submit</button>
                </div>
                <spam>Already user? </spam><a href="login.php">Login here</a>
        </form>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>

</body>

</html>
<!-- 
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
    $pnumber = $_POST["pnumber"];
    $dob = $_POST["dob"];
    $gender = $_POST["gender"];
    $address = $_POST["address"];
    $country = $_POST["country"];
    $city = $_POST["city"];
    $region = $_POST["region"];
    $postalCode = $_POST["postalCode"];
    $profilePicture = $_FILES["profile_picture"]["name"];

    // Validate input fields (add more validation as needed)
    if (
        empty($fullname) || empty($email) || empty($username) || empty($password) || empty($cpassword) ||
        empty($pnumber) || empty($dob) || empty($gender) || empty($address) || empty($country) ||
        empty($city) || empty($region) || empty($postalCode) || empty($profilePicture)
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
            $targetDir = addslashes("furni-1.0.0\\Users\\"); // Update this path if needed
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
                    $imagePath = $targetDir . $newFileName;

                    // Save data to database
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash password
                    $sql = "INSERT INTO user (Full_name, Email, Username, Password, Phone_number, Date_of_birth, Gender, Address, Country, City, Region, Postal_code, Profile_picture) VALUES 
                        ('$fullname', '$email', '$username', '$hashedPassword', '$pnumber', '$dob', '$gender', '$address', '$country', '$city', '$region', '$postalCode', '$imagePath')";

                    if ($conn->query($sql) === TRUE) {
                        echo "Data inserted successfully.";
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        }
    }
}

$conn->close();
?> -->