<?php
require("connect.php");
session_start();

$sport = $_GET['sport'];
$activityName = $_POST['activity-name'];
$activityDesc = $_POST['activity-description'];
$activityMax = $_POST['activity-maximum'];
$activityDate = $_POST['activity-date'];

// Checking for sport type
if ($sport == '1') {
    $activityDuration = $_POST['activity-duration-badminton'];
    $activityStartTime = $_POST['activity-start-time-badminton'];
    $activityEndTime = $_POST['activity-end-time-badminton'];
    $activitySkillLevel = $_POST['skill-level-badminton'];
    $activityLocation = isset($_POST['activity-location-badminton']) ? $_POST['activity-location-badminton'] : null;
    $locationColumn = "badminton_location_id";
} else {
    $activityDuration = $_POST['activity-duration-basketball'];
    $activityStartTime = $_POST['activity-start-time-basketball'];
    $activityEndTime = $_POST['activity-end-time-basketball'];
    $activitySkillLevel = $_POST['skill-level-basketball'];
    $activityLocation = isset($_POST['activity-location-basketball']) ? $_POST['activity-location-basketball'] : null;
    $locationColumn = "basketball_location_id";
}

$activityPrice = $_POST['activity-price'];
date_default_timezone_set('Asia/Kuala_Lumpur');
$timestamp = date('Y-m-d H:i:s');
$user_id = $_POST['userid'];

// Convert time values to proper time format
$activityStartTime = date('H:i:s', strtotime($activityStartTime));
$activityEndTime = date('H:i:s', strtotime($activityEndTime));

// Insert new activity
$sql = "INSERT INTO activities (sports_id, activity_name, activity_description, activity_maximum, activity_date, activity_start_time, activity_duration, activity_end_time, $locationColumn, activity_skill_level, activity_price, user_id, ended) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)";
$stmt = $mysqli->prepare($sql);
// Error handling for preparing the SQL statement
if (!$stmt) {
    die('Error preparing statement: ' . $mysqli->error);
}

$stmt->bind_param("ssssssssssis", $sport, $activityName, $activityDesc, $activityMax, $activityDate, $activityStartTime, $activityDuration, $activityEndTime, $activityLocation, $activitySkillLevel, $activityPrice, $user_id);

if ($stmt->execute()) {
    // Insert into user_activities table
    $activity_id = $mysqli->insert_id; // Get the ID of the last inserted activity
    $userActivitySql = "INSERT INTO user_activities (user_id, activity_id) VALUES (?, ?)";
    $userActivityStmt = $mysqli->prepare($userActivitySql);
    $userActivityStmt->bind_param("ii", $user_id, $activity_id);
    $userActivityStmt->execute();

    // Insert comment indicating activity creation
    $comment_content = "Activity created";
    $insertCommentSql = "INSERT INTO comments (user_id, activity_id, comment_content, comment_timestamp) VALUES (?, ?, ?, ?)";
    $insertCommentStmt = $mysqli->prepare($insertCommentSql);
    $insertCommentStmt->bind_param("iiss", $user_id, $activity_id, $comment_content, $timestamp);
    $insertCommentStmt->execute();

    // Update the 'ended' column for activities where end datetime has passed
    $updateEndedSql = "UPDATE activities 
                       SET ended = 1 
                       WHERE CONVERT_TZ(CONCAT(activity_date, ' ', activity_end_time), 'UTC', @@session.time_zone) < NOW() 
                       AND ended = 0";
    $mysqli->query($updateEndedSql);

    echo ("<script LANGUAGE='JavaScript'>
        window.alert('Activity created successfully.');
        window.location.href='activities.php';
        </script>");
} else {
    echo ("<script LANGUAGE='JavaScript'>
        window.alert('Error creating activity: " . $mysqli->error . "');
        window.location.href='hostActivity.php?error=insert';
        </script>");
}
?>
