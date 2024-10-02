<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    // Redirect the user to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Check if the activity ID is provided
if (!isset($_POST['activity_id'])) {
    // Redirect the user back to the activities page if no activity ID is provided
    header("Location: activities.php");
    exit();
}

// Include the database connection
include_once "connect.php";

// Get the activity ID from the POST data
$activity_id = $_POST['activity_id'];

// Prepare SQL statement to delete the user's participation in the activity
$leaveActivitySql = "DELETE FROM user_activities WHERE user_id = ? AND activity_id = ?";
if ($stmt = $mysqli->prepare($leaveActivitySql)) {
    // Bind the parameters
    $stmt->bind_param("ii", $_SESSION['userid'], $activity_id);

    // Execute the statement
    if ($stmt->execute()) {
        // Insert comment indicating user left the activity
        $comment_content = $_SESSION['name'] . " left the activity";
        date_default_timezone_set('Asia/Kuala_Lumpur');
        $timestamp = date('Y-m-d H:i:s');
        $insertCommentSql = "INSERT INTO comments (user_id, activity_id, comment_content, comment_timestamp) VALUES (?, ?, ?, ?)";
        $insertCommentStmt = $mysqli->prepare($insertCommentSql);
        $insertCommentStmt->bind_param("iiss", $_SESSION['userid'], $activity_id, $comment_content, $timestamp);
        $insertCommentStmt->execute();

        // Redirect the user back to the activities page after leaving the activity
        header("Location: activities.php");
        exit();
    } else {
        // If execution fails, display an error message
        echo "Error leaving activity: " . $mysqli->error;
    }

    // Close the statement
    $stmt->close();
} else {
    // If preparation fails, display an error message
    echo "Error preparing statement: " . $mysqli->error;
}

// Close the database connection
$mysqli->close();
?>
