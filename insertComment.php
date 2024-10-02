<?php
// Include the connection file
require("connect.php");
session_start();

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    // Redirect the user to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Retrieve form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs (you should implement this)
    $userId = $_SESSION['userid'];
    $activityId = $mysqli->real_escape_string($_POST['activity_id']);
    $commentContent = $mysqli->real_escape_string($_POST['comment_content']);
    
    // Insert the comment into the database
    $insertCommentSql = "INSERT INTO comments (user_id, activity_id, comment_content) 
                         VALUES ('$userId', '$activityId', '$commentContent')";
    if ($mysqli->query($insertCommentSql)) {
        // Redirect the user back to the activity details page after adding the comment
        header("Location: activitydetails.php?activity_id=$activityId");
        exit();
    } else {
        // Handle the case where the comment insertion fails
        echo "Error: " . $mysqli->error;
    }
} else {
    // If the form was not submitted via POST method, redirect the user to an error page
    header("Location: error.php");
    exit();
}
?>
