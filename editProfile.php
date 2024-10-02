<?php
// Include database connection and session handling code
require("connect.php");
session_start();

// Check if the user is logged in
if (!isset($_SESSION['isLogin']) || $_SESSION["isLogin"] !== true) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit;
}

// Fetch user details from the form
$name = $_POST['name'];
$gender = $_POST['gender'];
$age = $_POST['age'];
$preferred_location = $_POST['preferred_location'];
$userId = $_SESSION['userid'];

// Check if a new profile picture is uploaded
if ($_FILES["profilePicture"]["name"]) {
    // Handle Profile Picture Upload
    $targetDir = "uploads/"; // Directory where the files will be stored
    $targetFile = $targetDir . basename($_FILES["profilePicture"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check file size
    if ($_FILES["profilePicture"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $targetFile)) {
            // Update user's profile details with the new profile image
            $updateProfileSql = "UPDATE users SET name='$name', gender='$gender', age='$age', preferred_location='$preferred_location', profile_image='$targetFile' WHERE user_id='$userId'";
            if ($mysqli->query($updateProfileSql) === TRUE) {
                // Redirect back to the profile page after successful update
                header("Location: profile.php");
                exit;
            } else {
                // Handle database update error
                // You may redirect to an error page or display an error message
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
} else {
    // Update user's profile details without changing the profile image
    $updateProfileSql = "UPDATE users SET name='$name', gender='$gender', age='$age', preferred_location='$preferred_location' WHERE user_id='$userId'";
    if ($mysqli->query($updateProfileSql) === TRUE) {
        // Redirect back to the profile page after successful update
        header("Location: profile.php");
        exit;
    } else {
        // Handle database update error
        // You may redirect to an error page or display an error message
    }
}
?>
