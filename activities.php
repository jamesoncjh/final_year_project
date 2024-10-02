<?php
require("connect.php");
session_start();

// Update the 'ended' column for activities where end datetime has passed
$updateEndedSql = "UPDATE activities SET ended = 1 WHERE CONCAT(activity_date, ' ', activity_end_time) < NOW() AND ended = 0";
$updateResult = $mysqli->query($updateEndedSql);
if ($updateResult === false) {
    die("Error updating 'ended' column: " . $mysqli->error);
}

$role = isset($_GET['role']) ? $_GET['role'] : '';

if ($role == 'user') {
    $sql = "SELECT user_id, username, email, name, gender, age, profile_image, last_login FROM users WHERE role = 'user'";
    $result = $mysqli->query($sql);

    if ($result === false) {
        die("Error: " . $mysqli->error);
    }
}

// Define the base SQL query for fetching activities
$getActivitiesSql = "SELECT a.*, 
                            IFNULL(b.badminton_location_name, bb.basketball_location_name) AS location_name,
                            IFNULL(b.badminton_location_address, bb.basketball_location_address) AS location_address,
                            s.sports_name, 
                            a.activity_date, 
                            a.activity_start_time, 
                            a.activity_end_time, 
                            a.activity_price, 
                            a.activity_description 
                     FROM activities a 
                     LEFT JOIN badminton_location b ON a.badminton_location_id = b.badminton_location_id
                     LEFT JOIN basketball_location bb ON a.basketball_location_id = bb.basketball_location_id
                     INNER JOIN sports s ON a.sports_id = s.sports_id
                     WHERE a.ended = 0"; // Filter out activities that have ended



// Check if a sports type filter is applied
if(isset($_GET['sports_type'])) {
    // Sanitize the input
    $sportsType = $mysqli->real_escape_string($_GET['sports_type']);

    // If the sports type is 'all', include all sports types, otherwise filter by the selected sports type
    if($sportsType != 'all') {
        // Modify the base SQL query to include filtering by sports type
        $getActivitiesSql .= " AND s.sports_name = '$sportsType'";
    }
}

// Check if sorting parameters are provided
if(isset($_GET['sort_by']) && isset($_GET['sort_order'])) {
    // Sanitize and validate sorting parameters
    $sort_by = $mysqli->real_escape_string($_GET['sort_by']);
    $sort_order = ($_GET['sort_order'] == 'asc') ? 'ASC' : 'DESC';

    // Modify the SQL query to include sorting
    $getActivitiesSql .= " ORDER BY $sort_by $sort_order";
}

// Execute the SQL query
$getActivitiesStmt = $mysqli->query($getActivitiesSql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Sportify</title>
    <!-- Favicons -->
    <link href="assets/img/website-logo.jpg" rel="icon">
    <link href="assets/img/website-logo.jpg" rel="apple-touch-icon">
    <meta content="" name="description">
    <meta content="" name="keywords">
    <!-- Tab Icon -->
    <link href="img/fcuclogo" rel="icon">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Amatic+SC:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <!-- Template Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" integrity="NEW_INTEGRITY_VALUE" crossorigin="anonymous" />
</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="home.php" class="logo d-flex align-items-center me-auto me-lg-0">
                <h1>Sportify<span>.</span></h1>
            </a>
            <!-- Navigation bar -->
            <nav id="navbar" class="navbar">
                <ul>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="people.php?role=user">People</a></li>
                    <li><a href="venue.php">Venue</a></li>
                    <li><a href="activities.php">Activities</a></li>
                    <?php if (isset($_SESSION['isLogin']) && $_SESSION["isLogin"] === true) { ?>
                        <li><a href="profile.php">Profile</a></li>
                    <?php } ?>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role']=="admin"){ ?>
                         <li><a href="ManageUser.php">User</a></li>
                    <?php } ?>
                </ul>
            </nav>
            <?php if (isset($_SESSION['isLogin']) && $_SESSION["isLogin"] === true) { ?>
                <a class="btn-book-a-table" href="logout.php">Logout</a>
            <?php } else { ?>
                <a class="btn-book-a-table" href="login.php">Login</a>
                <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
                <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>
            <?php } ?>
        </div>
    </header>

    <section id="activities" class="book-a-table">
        <div class="container" data-aos="zoom-out" data-aos-duration="1000">
            <div class="section-header">
                <p><span>Activities</span></p>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col d-flex justify-content-center">
                    <?php if (isset($_SESSION['isLogin']) && $_SESSION["isLogin"] === true) { ?>
                        <a href="hostActivity.php" class="btn btn-primary mt-3 mx-5 px-xxl-4">Host Activity</a>
                    <?php } else { ?>
                        <button class="btn btn-primary mt-3 mx-5 px-xxl-4" onclick="redirectToLogin('hostActivity.php')">Host Activity</button>
                    <?php } ?>
                </div>
            </div>
        </div>
        <br>

    <!-- Filter Function -->
    <div style="text-align: center;">
        <form action="activities.php" method="get">
            <!-- Filter by Sports Type -->
            <label for="sports_type">Filter by Sports Type:</label>
            <select name="sports_type" id="sports_type">
                <option value="all"<?php if(!isset($_GET['sports_type']) || (isset($_GET['sports_type']) && $_GET['sports_type'] == 'all')) echo " selected"; ?>>All</option>
                <!-- Add options dynamically from your database -->
                <?php
                    // Query to fetch sports types from the database
                    $getSportsSql = "SELECT sports_name FROM sports";
                    $sportsResult = $mysqli->query($getSportsSql);

                    // Check if there are any sports types
                    if ($sportsResult->num_rows > 0) {
                        // Output options for each sports type
                        while ($row = $sportsResult->fetch_assoc()) {
                            // Map original sport type to display name
                            $sportTypeDisplayName = ($row['sports_name'] == 'badminton') ? 'Badminton Meetup' : (($row['sports_name'] == 'basketball') ? 'Basketball Meetup' : $row['sports_name']);
                            // Check if this option is selected
                            $selected = isset($_GET['sports_type']) && $_GET['sports_type'] == $row['sports_name'] ? "selected" : "";
                            // Output the dropdown option with display name
                            echo "<option value='" . $row['sports_name'] . "' $selected>" . $sportTypeDisplayName . "</option>";
                        }
                    }
                ?>
            </select>
            <!-- Filter by Date/ Name/ Skill Level -->
            <label for="sort_by">Sort By:</label>
            <select name="sort_by" id="sort_by">
                <option value="activity_date"<?php if(isset($_GET['sort_by']) && $_GET['sort_by'] == 'activity_date') echo " selected"; ?>>Date</option>
                <option value="activity_name"<?php if(isset($_GET['sort_by']) && $_GET['sort_by'] == 'activity_name') echo " selected"; ?>>Activity Name</option>
                <option value="activity_skill_level"<?php if(isset($_GET['sort_by']) && $_GET['sort_by'] == 'activity_skill_level') echo " selected"; ?>>Skill Level</option>
            </select>
            <label for="sort_order">Sort Order:</label>
            <select name="sort_order" id="sort_order">
                <option value="asc"<?php if(isset($_GET['sort_order']) && $_GET['sort_order'] == 'asc') echo " selected"; ?>>Ascending</option>
                <option value="desc"<?php if(isset($_GET['sort_order']) && $_GET['sort_order'] == 'desc') echo " selected"; ?>>Descending</option>
            </select>
            <button type="submit">Filter</button>

        </form>
    </div>
        <br>

<?php
// Container for activity boxes
echo "<div style='display: flex; flex-wrap: wrap; justify-content: center;'>";

// Loop through each activity
while ($row = $getActivitiesStmt->fetch_assoc()) {
    // Activity box styling
    echo "<div style='border: 1px solid #ccc; border-radius: 5px; padding: 10px; width: calc(50% - 300px); margin: 10px;'>";

    // Map original sport type to display name
    $sportTypeDisplayName = ($row['sports_name'] == 'Badminton') ? 'Badminton Meetup' : (($row['sports_name'] == 'Basketball') ? 'Basketball Meetup' : $row['sports_name']);

    // Display the sport type
    echo "<p>" . $sportTypeDisplayName . "</p>";

    // Activity information
    echo "<p><span style='font-weight:bold;'>" . $row['activity_name'] . "</p>";

    // Display participants' profile images
    echo "<div style='display: flex; flex-wrap: wrap; align-items: center;'>";

    // Query to fetch users who joined the activity
    $getParticipantsSql = "SELECT u.user_id, u.profile_image FROM user_activities ua INNER JOIN users u ON ua.user_id = u.user_id WHERE ua.activity_id = " . $row['activity_id'];
    $participantsResult = $mysqli->query($getParticipantsSql);

    if ($participantsResult === false) {
        die("Error fetching participants for activity: " . $mysqli->error);
    }

    // Fetch profile images of participants and display them
    while ($participantRow = $participantsResult->fetch_assoc()) {
        $userId = $participantRow['user_id'];
        echo "<a href='profile.php?user_id=$userId'><img src='" . $participantRow['profile_image'] . "' alt='Profile Image' style='width: 40px; height: 40px; margin-right: 5px; border-radius: 50%;'></a>";
    }
    echo "</div>"; // Close div for participants' profile images
    echo "<p></p>";

    // Query to count the number of users who have joined the activity
    $countUsersSql = "SELECT COUNT(*) AS num_users FROM user_activities WHERE activity_id = " . $row['activity_id'];
    $countUsersResult = $mysqli->query($countUsersSql);

    if ($countUsersResult === false) {
        die("Error counting users for activity: " . $mysqli->error);
    }

    // Fetch the count of users
    if ($countUsersRow = $countUsersResult->fetch_assoc()) {
        $numUsers = $countUsersRow['num_users'];
        $activityMax = $row['activity_maximum']; // Assuming you have a column named 'activity_maximum' in the activities table
        // Display the count of participants
        echo "<p>$numUsers/$activityMax Going&nbsp;&nbsp;&nbsp;";
        // Check if the maximum number of participants is reached
        if ($numUsers >= $activityMax) {
            // Display a message indicating that the maximum number of participants has been reached
            echo "<em><strong><small>Maximum participants reached.</strong></small></em>";
        }
        echo "</p>";
    }

    echo "<p><i class='bi bi-coin'></i><strong> " . $row['activity_price'] . "</span> MYR / person</strong><br>";
    echo "Recommended for players with skill level <strong>" . $row['activity_skill_level'] . "</strong>.</p>";

    $activity_date = date('D j F', strtotime($row['activity_date'])); // Format date as 'day, day Month' (e.g., 'Monday, 29 March')
    $start_time = date('g:i A', strtotime($row['activity_start_time'])); // Format start time as 'hour:minute AM/PM' (e.g., '2:30 PM')
    $end_time = date('g:i A', strtotime($row['activity_end_time'])); // Format end time as 'hour:minute AM/PM' (e.g., '6:30 PM')

    echo "<p><i class='bi bi-clock'></i> " . $activity_date . ", " . $start_time . " - " . $end_time . "</p>";
    echo "<p><i class='bi bi-geo-alt'></i> " . $row['location_name'] . "</p>";

    // Container for all buttons
    echo "<div style='display: flex; justify-content: center;'>";

// Join/Leave Button
if (isset($_SESSION['userid'])) {
    // Check if the current user has joined the activity
    $checkJoinSql = "SELECT COUNT(*) AS joined FROM user_activities WHERE activity_id = " . $row['activity_id'] . " AND user_id = " . $_SESSION['userid'];
    $checkJoinResult = $mysqli->query($checkJoinSql);
    if ($checkJoinResult === false) {
        die("Error checking if user has joined activity: " . $mysqli->error);
    }
    $joinRow = $checkJoinResult->fetch_assoc();
    $joined = ($joinRow['joined'] > 0); // Update joined variable based on the query result
    
    // Display the appropriate button based on whether the user has joined the activity
    if ($joined) {
        // Display Leave button if user has joined the activity and is not the creator
        if ($_SESSION['userid'] != $row['user_id']) {
            echo "<form id='leaveForm' method='POST' action='leaveActivity.php'>";
            echo "<input type='hidden' id='activityId' name='activity_id' value='" . $row['activity_id'] . "'>"; // Add activity ID as value
            echo "<button type='submit' class='btn btn-danger mx-2' onclick='confirmLeave(" . $row['activity_id'] . ")'>Leave</button>";
            echo "</form>";
        }
    } else {
        // Display Join button if user has not joined the activity
        echo "<form id='joinForm' method='POST' action='joinActivity.php'>";
        echo "<input type='hidden' id='activityId' name='activity_id' value='" . $row['activity_id'] . "'>"; // Add activity ID as value
        echo "<button type='submit' class='btn btn-primary mx-2'>Join</button>";
        echo "</form>";
    }
}
 else {
        // If user is not logged in, display a button to redirect to the login page
        echo "<button type='button' class='btn btn-primary mx-2' onclick='JoinActivityRedirectToLogin()'>Join</button>";
    }

    // Display Delete and Details buttons only if the activity is created by the current user or if the user is an admin
    if ((isset($_SESSION['userid']) && $row['user_id'] == $_SESSION['userid']) || (isset($_SESSION['role']) && $_SESSION['role'] == "admin")) {
        // Delete Button
        echo "<form id='deleteForm" . $row['activity_id'] . "' method='post' action='deleteActivity.php'>";
        echo "<input type='hidden' name='activity_id' value='" . $row['activity_id'] . "'>";
        echo "<button type='button' class='btn btn-danger mx-2' style='width: 80px;' onclick='confirmDelete(" . $row['activity_id'] . ")'>Delete</button>";
        echo "</form>";
        // Edit Button
        echo "<button type='button' class='btn btn-primary mx-2 edit-btn' style='width: 80px;' 
        data-activity-id='" . $row['activity_id'] . "'>Edit</button>"; // Edit button
    }

    // Details Button
    echo "<button type='button' class='btn btn-primary mx-2 details-btn' style='width: 80px;' 
        data-activity-id='" . $row['activity_id'] . "'>Details</button>"; // Details button

    echo "</div>"; // Close container for all buttons

    echo "</div>"; // Close activity box
}

echo "</div>"; // Close container for activity boxes
?>




<?php
// Execute the SQL query
$getActivitiesStmt = $mysqli->query($getActivitiesSql);

// Check if the query executed successfully
if ($getActivitiesStmt === false) {
    // Error occurred while executing the SQL query
    echo "<p>Error occurred while fetching activities: " . $mysqli->error . "</p>";
} else {
    // Check if there are existing activities
    if ($getActivitiesStmt->num_rows > 0) {
        // Display existing activities
        // Your existing code for displaying activities goes here
    }
}
?>

<!-- Modal Start -->
<div class="modal fade" id="newDiseaseModal" tabindex="-1" aria-labelledby="newDiseaseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!-- Modal Title -->
                <h5 class="modal-title" id="modalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Display activity name -->
                <p id="activityName"></p>
                <p><i class='bi bi-clock'></i> <span id="activityDate"></span></p>

                <i class='bi bi-geo-alt'></i> <strong><span id="activityLocation"></span></strong>
                <br>
                <span id="activityAddress"></span>
                <br><br>
                <i class='bi bi-coin'></i> <strong><span id="activityPrice"></span></strong>
            </div>
            <div class="modal-body">
                <small><p><i class='bi bi-text-left'> </i> Description</p></small>
                <hr>
                <p id="activityDescription"></p>
            </div>
        </div>
    </div>
</div>
    </section>


<!-- JavaScript to set activity_id -->
<script>
    // Add event listener to Join buttons
    document.querySelectorAll('.join-btn').forEach(item => {
        item.addEventListener('click', event => {
            // Get the activity ID from the data-attribute
            const activityId = event.currentTarget.getAttribute('data-activity-id');
            // Set the activity ID in the hidden input field
            document.getElementById("activityId").value = activityId;
            // Submit the form
            document.getElementById("joinForm").submit();
        });
    });

    // Event listener for Edit buttons
    document.querySelectorAll('.edit-btn').forEach(item => {
        item.addEventListener('click', event => {
            // Get the activity ID from the data-attribute
            const activityId = event.currentTarget.getAttribute('data-activity-id');
            // Redirect to editActivity.php with activity ID as a query parameter
            window.location.href = `editActivity.php?activity_id=${activityId}`;
        });
    });

    // New event listener for Details buttons
document.querySelectorAll('.details-btn').forEach(item => {
    item.addEventListener('click', event => {
        // Get the activity ID from the data-attribute
        const activityId = event.currentTarget.getAttribute('data-activity-id');
        // Redirect to activitydetails.php with activity ID as a query parameter
        window.location.href = `activitydetails.php?activity_id=${activityId}`;
    });
});


    function redirectToLogin(url) {
        if (confirm("You need to be logged in to access this page. Do you want to proceed to the login page?")) {
            window.location.href = 'login.php'; // Redirect to the login page
        }
    }

        function JoinActivityRedirectToLogin() {
        if (confirm("You need to be logged in to join this activity. Do you want to proceed to the login page?")) {
            window.location.href = 'login.php'; // Redirect to the login page
        }
    }

    function confirmLeave(activityId) {
        if (confirm("Are you sure you want to leave this activity?")) {
            // Submit the form to delete the activity
            document.getElementById('leaveForm' + activityId).submit();
        }
    }

    function confirmDelete(activityId) {
        if (confirm("Are you sure you want to delete this activity?")) {
            // Submit the form to delete the activity
            document.getElementById('deleteForm' + activityId).submit();
        }
    }

// Handle the form submission and set sorting criteria and order
if(isset($_GET['sort_by']) && isset($_GET['sort_order'])) {
    $sort_by = $_GET['sort_by'];
    $sort_order = $_GET['sort_order'];
} else {
    // Default sorting criteria and order
    $sort_by = 'activity_date';
    $sort_order = 'asc';
}

</script>


    <?php
        // Check for error parameter
        if(isset($_GET['error']) && $_GET['error'] == 'insert') {
            // Output JavaScript to open modal on page load
            echo '<script>
                    $(document).ready(function() {
                        $("#newDiseaseModal").modal("show");
                    });
                </script>';
        }
    ?>





    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">

    <div class="container">
        <div class="row gy-3">
        <div class="col-lg-3 col-md-6 d-flex">
            <i class="bi bi-geo-alt icon"></i>
            <div>
            <h4>Address</h4>
            <p>
                No.1, Persiaran Bukit Utama, <br>
                Bandar Utama,<br>
                47800 Petaling Jaya, Selangor<br>
            </p>
            </div>

        </div>

        <div class="col-lg-3 col-md-6 footer-links d-flex">
            <i class="bi bi-telephone icon"></i>
            <div>
            <h4>Contact Us</h4>
            <p>
                <strong>Phone:</strong> +60 16-216 5527<br>
                <strong>Email:</strong> b1716@student.firstcity.edu.my<br>
            </p>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 footer-links d-flex">
        </div>

        <div class="col-lg-3 col-md-6 footer-links">
            <h4>Follow Us</h4>
            <div class="social-links d-flex">
            <a href="https://twitter.com/home" class="twitter"><i class="bi bi-twitter"></i></a>
            <a href="https://www.facebook.com/firstcitywayahead" class="facebook"><i class="bi bi-facebook"></i></a>
            <a href="https://www.instagram.com/jamxxon__/" class="instagram"><i class="bi bi-instagram"></i></a>
            <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
            </div>
        </div>

        </div>
    </div>
    <div class="container">
        <div class="copyright">
        &copy; Copyright <strong><span>Sportify</span></strong>. All Rights Reserved
        </div>
    </div>
    </footer>
    <!-- End Footer -->

    <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

      <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js" integrity="NEW_INTEGRITY_VALUE" crossorigin="anonymous"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>
