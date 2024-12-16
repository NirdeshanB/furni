<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database configuration
require 'config.php';

// Fetch admin information
$adminInfo = [];
if ($stmt = $conn->prepare("SELECT id, full_name, phone_number, email, profile_picture FROM admin WHERE username = ?")) {
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $adminInfo[] = [
            'id' => $row['id'],
            'fullname' => $row['full_name'],
            'phonenumber' => $row['phone_number'],
            'email' => $row['email'],
            'profile_picture' => $row['profile_picture'],
        ];
    }

    $stmt->close();
}

// Store the admin info in session
$_SESSION['adminInfo'] = $adminInfo;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product Admin - Dashboard HTML Template</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700">
    <!-- https://fonts.google.com/specimen/Roboto -->
    <link rel="stylesheet" href="css\fontawesome.min.css">
    <!-- https://fontawesome.com/ -->
    <link rel="stylesheet" href="css\bootstrap.min.css">
    <!-- https://getbootstrap.com/ -->
    <link rel="stylesheet" href="css\templatemo-style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <!--
    Product Admin CSS Template
    https://templatemo.com/tm-524-product-admin
    -->
</head>

<body id="reportsPage">
    <nav class="navbar navbar-expand-xl">
        <div class="container h-100">
            <a class="navbar-brand" href="home.php">
                <h1 class="tm-site-title mb-0">Product Admin</h1>
            </a>
            <button class="navbar-toggler ml-auto mr-0" type="button" data-toggle="collapse"
                data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <i class="fas fa-bars tm-nav-icon"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto h-100">
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'home.php') ? 'active' : ''; ?>"
                            href="home.php">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                            <span class="sr-only">(current)</span>
                        </a>
                    </li>
                    <li
                        class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'coupons.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="coupons.php">
                            <i class="fa-solid fa-receipt"></i> Coupons
                        </a>
                    </li>
                    <li
                        class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'products.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="products.php">
                            <i class="fas fa-shopping-cart"></i> Products
                        </a>
                    </li>

                    <li
                        class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'accounts.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="accounts.php">
                            <i class="far fa-user"></i> Accounts
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="logout.php" onclick="event.preventDefault(); logoutAndRedirect();">
                            <img src="..\images\furni.svg" class="logo" width="15%"> Continue to Furni!
                        </a>
                    </li>

                </ul>
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['username'])) { ?>
                    <?php foreach ($_SESSION['adminInfo'] as $info): ?>
                    <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">
                        <img src="<?php echo $info['profile_picture'] ?>" class="rounded-circle me-2" width="40"
                            height="40" alt="Profile" />
                        <?php endforeach; ?>
                        <a class="d-block" href="logout.php">
                            <b>Logout</b>
                        </a>
                    </li>
                    <?php } else { ?>
                    <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">
                        <img src="img/user.svg" alt="User Icon" class="img-fluid me-2" width="20" />
                        <a class="d-block" href="index.php">
                            <b>Login</b>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>
    <script src="js/jquery-3.3.1.min.js"></script>
    <!-- https://jquery.com/download/ -->
    <script src="js/bootstrap.min.js"></script>
    <!-- https://getbootstrap.com/ -->

    <script>
    function logoutAndRedirect() {
        fetch('logout.php', {
                method: 'POST'
            }) // Log out by calling logout.php
            .then(() => window.location.href = '../index.php'); // Redirect to Furni
    }
    </script>
</body>

</html>