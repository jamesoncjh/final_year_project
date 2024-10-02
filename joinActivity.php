<?php
require("connect.php");
session_start();

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    // Redirect to the login page if the user is not logged in
    header("Location: login.php");
    exit(); // Stop further execution
}

// Check if the activity ID is received
if (isset($_POST['activity_id'])) {
    // Get the activity ID and user ID
    $activity_id = $_POST['activity_id'];
    $user_id = $_SESSION['userid'];

    // Fetch the user's name from the database
    $getUserSql = "SELECT name FROM users WHERE user_id = ?";
    $getUserStmt = $mysqli->prepare($getUserSql);
    $getUserStmt->bind_param("i", $user_id);
    $getUserStmt->execute();
    $getUserResult = $getUserStmt->get_result();

    if ($getUserResult->num_rows > 0) {
        $user_row = $getUserResult->fetch_assoc();
        $user_name = $user_row['name'];

        // Insert into user_activities table
        $userActivitySql = "INSERT INTO user_activities (user_id, activity_id) VALUES (?, ?)";
        $userActivityStmt = $mysqli->prepare($userActivitySql);
        $userActivityStmt->bind_param("ii", $user_id, $activity_id);

        if ($userActivityStmt->execute()) {
            // Activity joined successfully
            echo ("<script>alert('You have successfully joined this activity.'); window.location.href = 'activities.php'</script>");
            
            // Insert comment indicating user joined the activity
            $comment_content = "$user_name joined the activity";
            date_default_timezone_set('Asia/Kuala_Lumpur');
            $timestamp = date('Y-m-d H:i:s');
            $insertCommentSql = "INSERT INTO comments (user_id, activity_id, comment_content, comment_timestamp) VALUES (?, ?, ?, ?)";
            $insertCommentStmt = $mysqli->prepare($insertCommentSql);
            $insertCommentStmt->bind_param("iiss", $user_id, $activity_id, $comment_content, $timestamp);
            $insertCommentStmt->execute();
        } else {
            // Error joining activity
            echo ("<script>alert('Error joining activity: " . $mysqli->error . "');</script>");
        }
    } else {
        // Unable to fetch user's name
        echo ("<script>alert('Unable to fetch user information.');</script>");
    }
} else {
    // Activity ID not received
    echo ("<script>alert('Activity ID not received.');</script>");
}
?>
