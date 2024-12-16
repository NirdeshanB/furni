<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Login Page - Product Admin Template</title>
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
                            <h2 class="tm-block-title mb-4">Welcome to Dashboard, Login</h2>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="index.php" method="post" class="tm-login-form">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input name="username" type="text" class="form-control validate" id="username"
                                        value="" required />
                                </div>
                                <div class="form-group mt-3">
                                    <label for="password">Password</label>
                                    <input name="password" type="password" class="form-control validate" id="password"
                                        value="" required />
                                </div>
                                <div class="form-group row mt-3">
                                    <div class="form-group col-lg-6">
                                        <button type="submit" name="submit"
                                            class="btn btn-primary btn-block text-uppercase">
                                            Login
                                        </button>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <a href="register.php"
                                            class="btn btn-primary btn-block text-uppercase mb-3">Register</a>
                                    </div>
                                </div>
                                <a href="forgot.php" class="btn btn-primary btn-block text-uppercase mb-3">Forgot
                                    Password</a>
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
// Start the session
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Include database connection
require 'config.php';

if (isset($_POST['submit'])) {
  // Get and sanitize inputs
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (empty($username) || empty($password)) {
    echo '<script>alert("Please fill in all fields.");</script>';
  } else {
    // Prepare statement to fetch user
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();

      // Verify password
      if ($password == $row['password']) {
        // Set session variables
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['profile_picture'] = $row['profile_picture'];
        echo '<script>alert("Login Successful"); window.location.href = "home.php";</script>';
        exit;
      } else {
        echo '<script>alert("Invalid username or password!"); window.location.href="index.php";</script>';
        exit;
      }
    } else {
      echo '<script>alert("Invalid username or password!"); window.location.href="index.php";</script>';
    }
    $stmt->close();
  }
}
?>