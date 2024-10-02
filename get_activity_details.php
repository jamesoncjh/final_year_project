<?php
// Include database connection
require("connect.php");

// Check if activity ID is provided
if (isset($_GET['id'])) {
    // Sanitize the input to prevent SQL injection
    $activityId = $mysqli->real_escape_string($_GET['id']);

    // Query to fetch activity details
$activityDetailsSql = "SELECT a.*, b.badminton_location_name AS activity_location, b.badminton_location_address AS activity_address, s.sports_name
                     FROM activities a 
                     INNER JOIN badminton_location b ON a.badminton_location_id = b.badminton_location_id
                     INNER JOIN sports s ON a.sports_id = s.sports_id
                     WHERE a.activity_id = $activityId"; // Add WHERE clause to filter by activity ID

    $activityDetailsResult = $mysqli->query($activityDetailsSql);

    // Check if activity details are found
    if ($activityDetailsResult->num_rows > 0) {
        // Fetch activity details
        $activityDetails = $activityDetailsResult->fetch_assoc();

        // Return activity details as JSON
        echo json_encode($activityDetails);
    } else {
        // Activity details not found
        echo json_encode(array('error' => 'Activity not found'));
    }
} else {
    // No activity ID provided
    echo json_encode(array('error' => 'No activity ID provided'));
}
?>
