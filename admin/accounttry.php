<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit(); // Stop further script execution
}
require 'config.php'; // Include your database connection
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Accounts - Product Admin Template</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700" />
    <link rel="stylesheet" href="css/fontawesome.min.css" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/templatemo-style.css">
</head>

<body id="reportsPage">
    <div class="" id="home">

        <?php include 'header.php'; ?>

        <div class="container mt-5">
            <div class="row tm-content-row">
                <div class="col-12 tm-block-col">
                    <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
                        <h2 class="tm-block-title">List of Accounts</h2>
                        <p class="text-white">Users</p>
                        <select id="userSelect" class="custom-select">
                            <option value="0">Select User</option>
                            <?php
                            $query = "SELECT Username FROM user";
                            $stmt = $conn->prepare($query);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            while ($row = $result->fetch_assoc()) { ?>
                                <option value="1"><?= htmlspecialchars($row['Username']) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <!-- user information -->
            <?php
            $selectedUserData = null;
            if (isset($_GET['username'])) {
                $selectedUsername = $_GET['username'];
                $userQuery = "SELECT Full_Name, Email, Username, Phone_number, Address, Date_of_birth, Profile_picture FROM user WHERE Username = $selectedUsername";
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $selectedUserData = $result->fetch_assoc();
                } else {
                    echo "No data found for username: " . $selectedUsername;
                }
            }
            ?>
            <div id="userInfo" class="row tm-content-row"
                style="display: <?= isset($selectedUserData) ? 'block' : 'none' ?>;">
                <div class="tm-block-col tm-col-avatar">
                    <div class="tm-bg-primary-dark tm-block tm-block-avatar">
                        <h2 class="tm-block-title">Change Avatar</h2>
                        <div class="tm-avatar-container">
                            <?php if ($selectedUserData && !empty($selectedUserData['Profile_picture'])) { ?>
                                <img src="..\<?= htmlspecialchars($selectedUserData['Profile_picture']) ?>" alt="Avatar"
                                    class="tm-avatar img-fluid mb-4" />
                            <?php } else { ?>
                                <img src="../images/user-avatar.png" alt="Default Avatar"
                                    class="tm-avatar img-fluid mb-4" />
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="tm-block-col tm-col-account-settings">
                    <div class="tm-bg-primary-dark tm-block tm-block-settings">
                        <h2 class="tm-block-title">Account Settings</h2>
                        <form action="" class="tm-signup-form row">
                            <div class="form-group col-lg-6">
                                <label for="name">Account Name</label>
                                <input id="name" name="name" type="text"
                                    value="<?= $selectedUserData ? htmlspecialchars($selectedUserData['Full_Name']) : '' ?>"
                                    class="form-control validate" />
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="email">Account Email</label>
                                <input id="email" name="email" type="email"
                                    value="<?= $selectedUserData ? htmlspecialchars($selectedUserData['Email']) : '' ?>"
                                    class="form-control validate" />
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="username">Username</label>
                                <input id="username" name="username" type="text"
                                    value="<?= $selectedUserData ? htmlspecialchars($selectedUserData['Username']) : '' ?>"
                                    class="form-control validate" />
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="pnumber">Phone Number</label>
                                <input id="pnumber" name="pnumber" type="text"
                                    value="<?= $selectedUserData ? htmlspecialchars($selectedUserData['Phone_number']) : '' ?>"
                                    class="form-control validate" />
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="address">Address</label>
                                <input id="address" name="address" type="text"
                                    value="<?= $selectedUserData ? htmlspecialchars($selectedUserData['Address']) : '' ?>"
                                    class="form-control validate" />
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="dob">Date of Birth</label>
                                <input id="dob" name="dob" type="text"
                                    value="<?= $selectedUserData ? htmlspecialchars($selectedUserData['Date_of_birth']) : '' ?>"
                                    class="form-control validate" />
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-block text-uppercase">
                                    Delete Your Account
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- user information -->
        </div>
        <footer class="tm-footer row tm-mt-small">
            <div class="col-12 font-weight-light">
                <p class="text-center text-white mb-0 px-4 small">
                    Copyright &copy; <b>2018</b> All rights reserved.

                    Design: <a rel="nofollow noopener" href="https://templatemo.com" class="tm-footer-link">Template
                        Mo</a>
                </p>
            </div>
        </footer>
    </div>

    <script src="js/jquery-3.3.1.min.js"></script>
    <!-- https://jquery.com/download/ -->
    <script src="js/bootstrap.min.js"></script>
    <!-- https://getbootstrap.com/ -->
    <script>
        $(document).ready(function () {
            $('#userSelect').change(function () {
                var selectedUser = $(this).val();
                if (selectedUser > 0) {
                    $('#userInfo').show();
                } else {
                    $('#userInfo').hide();
                }
            });
        });
    </script>
</body>

</html>