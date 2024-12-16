<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Untree.co">
    <link rel="shortcut icon" href="images/furni.png" />

    <meta name="description" content="" />
    <meta name="keywords" content="bootstrap, bootstrap4" />
    <link rel="stylesheet" href="css/login.css" />
    <title>Login</title>
</head>

<body>
    <section class="container">
        <header>Login Form</header>
        <form action="login.php" method="post" class="form">
            <div class="input-box">
                <label>Username</label>
                <input type="text" name="username" placeholder="Enter username" required />
            </div>

            <div class="input-box">
                <label for="password" class="text-black">Password</label>
                <input type="password" class="form-control" id="password" name="password" required />
            </div>
            <div class="column">
                <button name="submit">Submit</button>
            </div>
            <spam>Don't have an account? </spam><a href="register.php">Register here</a> ||
            <a href="forgot-password.php">Forgot password?</a>
        </form>
    </section>
</body>

</html>

<!-- if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM user WHERE Username=? AND Password=?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Set session and redirect
        $_SESSION['user_id'] = $row['User_id'];
        $_SESSION['username'] = $row['Username'];
        $_SESSION['profile_picture'] = $row['Profile_picture']; // Assuming you store profile picture
        header('Location:index.php');
        exit;
    } else {
        echo '<script>alert("Invalid username or password!");</script>';
    }
    $stmt->close();
} -->

<?php
require 'config.php';
session_start();

if (isset($_POST['submit'])) {
    // Get and sanitize inputs
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        echo '<script>alert("Please fill in all fields.");</script>';
    } else {
        // Prepare statement to fetch user
        $stmt = $conn->prepare("SELECT * FROM user WHERE Username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Verify password
            if ($password == $row['Password']) {
                // Set session variables
                $_SESSION['user_id'] = $row['User_id'];
                $_SESSION['username'] = $row['Username'];
                $_SESSION['fullname'] = $row['Full_Name'];
                $_SESSION['profile_picture'] = $row['Profile_picture'];

                // Redirect to index
                header('Location: index.php');
                exit;
            } else {
                echo '<script>alert("Invalid username or password!"); window.location.href="login.php";</script>';
                exit;
            }
        } else {
            echo '<script>alert("Invalid username or password!"); window.location.href="login.php";</script>';
        }
        $stmt->close();
    }
}
?>