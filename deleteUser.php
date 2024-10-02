<?php
require("connect.php");
session_start();

// Check if the user is logged in and is an admin
if (!(isset($_SESSION['isLogin']) && $_SESSION['isLogin'] === true && isset($_SESSION['role']) && $_SESSION['role'] == "admin")) {
    // Redirect the user if not logged in as admin
    header("Location: login.php");
    exit;
}

// Check if the user_id is provided via POST request
if (isset($_POST['user_id'])) {
    // Sanitize the user_id to prevent SQL injection
    $user_id = $mysqli->real_escape_string($_POST['user_id']);

    // Prepare a DELETE statement for activities associated with the user
    $deleteActivitiesSql = "DELETE FROM user_activities WHERE user_id = '$user_id'";
    // Prepare a DELETE statement for comments associated with the user
    $deleteCommentsSql = "DELETE FROM comments WHERE user_id = '$user_id'";
    // Prepare a DELETE statement for the user
    $deleteUserSql = "DELETE FROM users WHERE user_id = '$user_id'";

    // Execute the DELETE statement for activities
    if ($mysqli->query($deleteActivitiesSql) === true) {
        // Execute the DELETE statement for comments
        if ($mysqli->query($deleteCommentsSql) === true) {
            // Execute the DELETE statement for the user
            if ($mysqli->query($deleteUserSql) === true) {
                // User and associated data deleted successfully
                $_SESSION['success_message'] = "User and associated data deleted successfully.";
            } else {
                // Error occurred while deleting user
                $_SESSION['error_message'] = "Error deleting user: " . $mysqli->error;
            }
        } else {
            // Error occurred while deleting comments
            $_SESSION['error_message'] = "Error deleting comments: " . $mysqli->error;
        }
    } else {
        // Error occurred while deleting activities
        $_SESSION['error_message'] = "Error deleting activities: " . $mysqli->error;
    }
} else {
    // Redirect if user_id is not provided
    $_SESSION['error_message'] = "User ID not provided.";
}


// Redirect back to manageuser.php
header("Location: manageuser.php");
exit;
?>
