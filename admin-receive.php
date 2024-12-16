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

        echo "<pre>";
        print_r('files:' . $_FILES);
        print ("fullname: " . $fullname);
        print ("email: " . $email);
        print ("username: " . $username);
        print ("password: " . $password);
        print ("cpassword: " . $cpassword);
        print ("pnumber: " . $pnumber);
        print ("profilePicture: " . $profilePicture);
        print ("root: " . $_SERVER['DOCUMENT_ROOT']);
        echo "</pre>";
    } elseif ($password !== $cpassword) {
        echo "Passwords do not match.";
    } else {
        // Validate email format (add more validation if needed)
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format.";
        } else {
            // Handle profile picture upload
            $targetDir = addslashes("furni-1.0.0\\Admin\\"); // Update this path if needed
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

            echo "Target Directory: " . $targetDir . "<br>";
            echo "Temporary File: " . $_FILES["profile_picture"]["tmp_name"] . "<br>";
            echo "Target File: " . $targetFile . "<br>";


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
                    $sql = "INSERT INTO admin (Full_name, Username, Phone_number, email, Password, Profile_picture) VALUES 
                        ('$fullname', '$username', '$pnumber', '$email', '$hashedPassword', '$imagePath')";

                    if ($conn->query($sql) === TRUE) {
                        echo '<script>alert("Data inserted successfully.");</script>';
                        header('Location: admin\index.php');
                        exit;
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