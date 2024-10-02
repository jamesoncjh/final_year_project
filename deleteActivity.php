<?php
// Check if the activity ID is received
if(isset($_POST['activity_id'])) {
    require("connect.php");
    session_start();

    // Get the activity ID and user ID
    $activity_id = $_POST['activity_id'];
    $user_id = $_SESSION['userid'];

    // Check if the user is the creator of the activity or an admin
    $checkSql = "SELECT * FROM activities WHERE activity_id = ?";
    $checkStmt = $mysqli->prepare($checkSql);
    $checkStmt->bind_param("i", $activity_id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows == 1) {
        // Fetch the activity row
        $activity = $checkResult->fetch_assoc();
        
        // Check if the user is the creator of the activity or an admin
        if ($activity['user_id'] == $user_id || $_SESSION['role'] == "admin") {
            // Delete associated comments
            $deleteCommentsSql = "DELETE FROM comments WHERE activity_id = '$activity_id'";
            if ($mysqli->query($deleteCommentsSql) === TRUE) {
                // Comments deleted successfully, proceed with deleting the activity
            } else {
                // Handle error
                echo "Error deleting comments: " . $mysqli->error;
            }
            // User is the creator of the activity or an admin, proceed with deletion
            $deleteSql = "DELETE FROM activities WHERE activity_id = ?";
            $deleteStmt = $mysqli->prepare($deleteSql);
            $deleteStmt->bind_param("i", $activity_id);

            if ($deleteStmt->execute()) {
                // Activity deleted successfully
                echo ("<script>alert('Activity deleted successfully.');</script>");
                // Redirect back to activities.php
                echo ("<script>window.location.href='activities.php';</script>");
            } else {
                // Error deleting activity
                echo ("<script>alert('Error deleting activity: " . $mysqli->error . "');</script>");
                // Redirect back to activities.php
                echo ("<script>window.location.href='activities.php';</script>");
            }
        } else {
            // User is not authorized to delete this activity
            echo ("<script>alert('You are not authorized to delete this activity.');</script>");
            // Redirect back to activities.php
            echo ("<script>window.location.href='activities.php';</script>");
        }
    } else {
        // Activity not found
        echo ("<script>alert('Activity not found.');</script>");
        // Redirect back to activities.php
        echo ("<script>window.location.href='activities.php';</script>");
    }
} else {
    // Activity ID not received
    echo ("<script>alert('Activity ID not received.');</script>");
    // Redirect back to activities.php
    echo ("<script>window.location.href='activities.php';</script>");
}
?>
