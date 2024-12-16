<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require 'config.php'; // Include your database connection

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Fetch user information from the database
    $query = "SELECT * FROM user WHERE Username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username); // Bind as a string
    $stmt->execute();
    $result = $stmt->get_result();
    $userDetails = $result->fetch_assoc();
} else {
    echo "User not logged in.";
    exit;
}
?>
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
    <style>
        .profilep img {
            height: 300px;
        }
    </style>
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
                        <h1>Profile</h1>
                    </div>
                </div>
                <div class="col-lg-7"></div>
            </div>
        </div>
    </div>
    <!-- End Hero Section -->

    <div class="untree_co-section">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-5 mb-md-0">
                    <h2 class="h3 mb-3 text-black">Billing Details</h2>
                    <div class="p-3 p-lg-5 border bg-white">
                        <form method="POST" enctype="multipart/form-data" action="profiletry.php" class="form">
                            <div class="form-group">
                                <label for="c_country" class="text-black">Country <span
                                        class="text-danger">*</span></label>
                                <select name="c_country" class="form-control" required>
                                    <option value="Nepal" selected>Nepal</option>
                                    <option value="India">India</option>
                                    <option value="China">China</option>
                                    <option value="USA">USA</option>
                                    <option value="UK">UK</option>
                                </select>
                            </div>
                            <div class="form-group row">
                                <div class="col-md">
                                    <label for="c_fname" class="text-black">Full Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="c_fname" name="c_fname"
                                        value="<?= $userDetails['Full_Name'] ?>" required />
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="c_address" class="text-black">Address <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="c_address" name="c_address"
                                        value="<?= $userDetails['Address'] ?>" placeholder="Street address" required />
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="c_city" class="text-black">City <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="c_city" name="c_city"
                                        value="<?= $userDetails['City'] ?>" required />
                                </div>
                                <div class="col-md-6">
                                    <label for="c_postal_zip" class="text-black">Postal / Zip <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="c_postal_zip" name="c_postal_zip"
                                        value="<?= $userDetails['Postal_code'] ?>" required />
                                </div>
                            </div>

                            <div class="form-group row mb-5">
                                <div class="col-md-6">
                                    <label for="c_email_address" class="text-black">Email Address <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="c_email_address" name="c_email_address"
                                        value="<?= $userDetails['Email'] ?>" required />
                                </div>
                                <div class="col-md-6">
                                    <label for="c_phone" class="text-black">Phone <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="c_phone" name="c_phone"
                                        value="<?= $userDetails['Phone_number'] ?>" placeholder="Phone Number"
                                        required />
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="submit" class="btn btn-black btn-lg py-3 btn-block">Update
                                    profile</button>
                            </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row mb-5">
                        <div class="col-md-12">
                            <h2 class="h3 mb-3 text-black">Your Order</h2>
                            <div class="p-3 p-lg-5 border bg-white">
                                <h3>Profile Picture</h3>
                                <div class="profilep">
                                    <img src="<?= $userDetails['Profile_picture'] ?>"
                                        class="img-fluid product-thumbnail" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>
            <!-- </form> -->
        </div>
    </div>

    <!-- Start Footer Section -->
    <?php include 'footer.php'; ?>
    <!-- End Footer Section -->
    <script src="https://kit.fontawesome.com/cc6ca513a2.js" crossorigin="anonymous"></script>
    <script src="js/tiny-slider.js"></script>
    <script src="js/custom.js"></script>
</body>

</html>
<?php
if (isset($_POST['submit'])) {
    $country = $_POST['c_country'];
    $fullname = $_POST['c_fname'];
    $address = $_POST['c_address'];
    $city = $_POST['c_city'];
    $postal = $_POST['c_postal_zip'];
    $email = $_POST['c_email_address'];
    $phone = $_POST['c_phone'];
    $username = $_SESSION['username'];

    // Debugging: Verify session and input data
    echo "Session Username: $username";
    echo "New Data: $country, $fullname, $address, $city, $postal, $email, $phone";

    // Check if the user exists
    $checkQuery = "SELECT * FROM user WHERE Username = ?";
    $stmtCheck = $conn->prepare($checkQuery);
    $stmtCheck->bind_param("s", $username);
    $stmtCheck->execute();
    $checkResult = $stmtCheck->get_result();

    if ($checkResult->num_rows == 0) {
        echo '<script>alert("No matching user found.");</script>';
        exit;
    }

    $existingData = $checkResult->fetch_assoc();

    // Check for data changes
    if (
        trim(strtolower($existingData['Country'])) === trim(strtolower($country)) &&
        trim($existingData['Full_Name']) === trim($fullname) &&
        trim($existingData['Address']) === trim($address) &&
        trim($existingData['City']) === trim($city) &&
        trim($existingData['Postal_code']) === trim($postal) &&
        trim($existingData['Email']) === trim($email) &&
        trim($existingData['Phone_number']) === trim($phone)
    ) {
        echo '<script>alert("No changes detected. Update skipped."); window.location.href = "profiletry.php";</script>';
        exit;
    }


    // Update user details
    $query = "UPDATE user SET Country = ?, Full_Name = ?, Address = ?, City = ?, Postal_code = ?, Email = ?, Phone_number = ? WHERE Username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssss", $country, $fullname, $address, $city, $postal, $email, $phone, $username);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo '<script>alert("Data Modified successfully."); window.location.href = "profiletry.php";</script>';
    } else {
        echo '<script>alert("No changes detected or an error occurred.");</script>';
    }
    $stmt->close();
}