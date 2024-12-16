<html>

<body>
    <div class="row">
        <?php
        $db = 'localhost';
        $dbname = 'furni';
        $user = 'root';
        $pass = '';

        $conn = mysqli_connect($db, $user, $pass, $dbname);

        if ($conn->connect_error) {
            die('Could not connect' . mysqli_connect_error());
        }

        $stmt = $conn->prepare("select * from user");
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $imagePath = $row['Profile_picture'];
            ?>
            <div class="col-12 col-md-4 col-lg-3 mb-5">
                <a class="product-item" href="#">
                    <img src="<?= $imagePath ?>" class="img-fluid product-thumbnail" height="500px" width="500px" />
                </a>
            </div>
            <?php
        }
        ?>
    </div>
</body></html>