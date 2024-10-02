<?php
// Include the connection file
require("connect.php");
session_start();

// Retrieve the activity ID from the query parameters
if(isset($_GET['activity_id'])) {
    $activityId = $mysqli->real_escape_string($_GET['activity_id']);
} else {
    // Redirect or handle error if activity ID is not provided
    // For example:
    header("Location: error.php");
    exit(); // Stop further execution
}

// Retrieve user ID from session
$userId = isset($_SESSION['userid']) ? $_SESSION['userid'] : null;

// Retrieve activity details from the database
$getActivitySql = "SELECT a.*, 
                            IFNULL(b.badminton_location_name, bb.basketball_location_name) AS location_name,
                            IFNULL(b.badminton_location_address, bb.basketball_location_address) AS location_address,
                            s.sports_name 
                   FROM activities a 
                   LEFT JOIN badminton_location b ON a.badminton_location_id = b.badminton_location_id
                   LEFT JOIN basketball_location bb ON a.basketball_location_id = bb.basketball_location_id
                   INNER JOIN sports s ON a.sports_id = s.sports_id
                   WHERE a.activity_id = $activityId";

$getActivityResult = $mysqli->query($getActivitySql);

// Check if activity details are retrieved successfully
if ($getActivityResult === false || $getActivityResult->num_rows === 0) {
    // Redirect or handle error if activity details are not found
    // For example:
    header("Location: error.php");
    exit(); // Stop further execution
}

// Fetch activity details
$activityDetails = $getActivityResult->fetch_assoc();

// Query to fetch participants' profile images
$getParticipantsSql = "SELECT u.user_id, u.profile_image FROM user_activities ua 
                       INNER JOIN users u ON ua.user_id = u.user_id 
                       WHERE ua.activity_id = $activityId";

$participantsResult = $mysqli->query($getParticipantsSql);

// Check if participants' profile images are retrieved successfully
if ($participantsResult === false) {
    // Redirect or handle error if participants' profile images are not found
    // For example:
    header("Location: error.php");
    exit(); // Stop further execution
}

// Query to count the number of participants
$countParticipantsSql = "SELECT COUNT(*) AS num_participants FROM user_activities WHERE activity_id = $activityId";
$countParticipantsResult = $mysqli->query($countParticipantsSql);
$numParticipants = 0;
if ($countParticipantsResult && $countParticipantsResult->num_rows > 0) {
    $countParticipantsRow = $countParticipantsResult->fetch_assoc();
    $numParticipants = $countParticipantsRow['num_participants'];
}

// Display the count of participants alongside the activities_maximum value
$activityMax = $activityDetails['activity_maximum'];
$participantCountString = "Going $numParticipants/$activityMax";

// Check if the user is the creator of the activity
$userIsCreator = $userId == $activityDetails['user_id'];

// Check if the user has already joined the activity
$userJoined = false;
if ($userId) {
    $checkUserActivitySql = "SELECT COUNT(*) AS num_activities FROM user_activities WHERE user_id = $userId AND activity_id = $activityId";
    $checkUserActivityResult = $mysqli->query($checkUserActivitySql);
    if ($checkUserActivityResult && $checkUserActivityResult->num_rows > 0) {
        $checkUserActivityRow = $checkUserActivityResult->fetch_assoc();
        $userJoined = $checkUserActivityRow['num_activities'] > 0;
    }
}

// Check if the activity is full
$activityFull = $numParticipants >= $activityMax;
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
        <div class="container" data-aos="flip-right" data-aos-duration="700">
            <div class="activitydetailscontainer">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <a href="activities.php" class="btn"><i class="bi bi-chevron-left"></i></a><?= $activityDetails['activity_name'] ?>
                            </div>
                            <!-- Tab navigation -->
                            <div class="card-header">
                                <ul class="nav nav-tabs card-header-tabs">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="details-tab" data-bs-toggle="tab" href="#details" role="tab" aria-controls="details" aria-selected="true">Details</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="discussion-tab" data-bs-toggle="tab" href="#discussion" role="tab" aria-controls="discussion" aria-selected="false">Discussion</a>
                                    </li>
                                </ul>
                            </div>
                            <!-- Tab content -->
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
                                    <div class="card-body" style="height: 570px; overflow-y: auto;">
                                        <!-- Details content -->
                                        <?php
                                        // Display details content here
                                        echo '<small><span>';
                                        if ($activityDetails['sports_id'] == 1) {
                                            echo 'BADMINTON';
                                        } elseif ($activityDetails['sports_id'] == 2) {
                                            echo 'BASKETBALL';
                                        }
                                        echo '</span></small>';
                                        echo '<p>' . $activityDetails['activity_name'] . '</p>';
                                        $startDate = date('D j M', strtotime($activityDetails['activity_date']));
                                        $startTime = date('g:i A', strtotime($activityDetails['activity_start_time']));
                                        $endTime = date('g:i A', strtotime($activityDetails['activity_end_time']));
                                        echo '<p><i class="bi bi-clock"></i> <span>' . $startDate . ', <strong>' . $startTime . ' - ' . $endTime . '</span></strong></p>';
                                        echo '<p><i class="bi bi-geo-alt"></i> <span><strong>' . $activityDetails['location_name'] . '</span></strong></p>';
                                        echo '<p>' . $activityDetails['location_address'] . '</p>';
                                        echo '<p><i class="bi bi-coin"></i> <span><strong>' . $activityDetails['activity_price'] . ' MYR / person</strong></span><br>';
                                        echo 'Recommended for players with skill level <strong>' . $activityDetails['activity_skill_level'] . '</strong>.</p>';
                                        echo '<hr>';
                                        echo '<p><small><strong><p><i class="bi bi-text-left"> </i> Description</p></small></strong></p>';
                                        echo '<p>' . nl2br($activityDetails['activity_description']) . '</p>';
                                        echo '<hr>';
                                        echo '<p><strong>' . $participantCountString . '</strong></p>';
                                        echo '<div>';
                                        while ($participantRow = $participantsResult->fetch_assoc()) {
                                            $userId = $participantRow['user_id'];
                                            echo "<a href='profile.php?user_id=$userId'><img src='" . $participantRow['profile_image'] . "' alt='Profile Image' style='width: 40px; height: 40px; margin-right: 5px; border-radius: 50%;'></a>";
                                        }
                                        echo '</div>';
                                        ?>
                                        <br><br>
                                        <div style="text-align: center;">
                                            <?php if ($userIsCreator) : ?>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <!-- Display edit button -->
                                                        <form id="editForm" method="GET" action="editActivity.php">
                                                            <input type="hidden" name="activity_id" value="<?= $activityId ?>">
                                                            <button type="submit" class="btn btn-primary btn-lg btn-block btn-full-width">Edit</button>
                                                        </form>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <!-- Display delete button -->
                                                        <form id="deleteForm" method="POST" action="deleteActivity.php">
                                                            <input type="hidden" name="activity_id" value="<?= $activityId ?>">
                                                            <button type="submit" class="btn btn-danger btn-lg btn-block btn-full-width">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            <?php elseif ($userJoined) : ?>
                                                <!-- Display Leave button -->
                                                <form id="leaveForm" method="POST" action="leaveActivity.php" onsubmit="return confirm('Are you sure you want to leave this activity?');">
                                                    <input type="hidden" name="activity_id" value="<?= $activityId ?>">
                                                    <button type="submit" class="btn btn-danger btn-lg btn-block btn-full-width">Leave</button>
                                                </form>
                                            <?php elseif ($userId) : ?>
                                                <!-- Display Join button -->
                                                <form id="joinForm" method="POST" action="joinActivity.php" onsubmit="return confirm('Are you sure you want to join this activity?');">
                                                    <input type="hidden" name="activity_id" value="<?= $activityId ?>">
                                                    <button type="submit" class="btn btn-primary btn-lg btn-block btn-full-width">Join</button>
                                                </form>
                                            <?php else : ?>
                                                <!-- Prompt to log in -->
                                                <button type='button' class='btn btn-primary mx-2' onclick='JoinActivityRedirectToLogin()'>Join</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="discussion" role="tabpanel" aria-labelledby="discussion-tab">
                                    <div class="card-body" style="height: 500px; overflow-y: auto;">
                                        <!-- Comments content -->
                                        <?php
                                        // Fetch comments associated with the activity from the database
                                        $getCommentsSql = "SELECT c.*, u.name, u.profile_image FROM comments c 
                                                            INNER JOIN users u ON c.user_id = u.user_id 
                                                            WHERE c.activity_id = $activityId";

                                        $commentsResult = $mysqli->query($getCommentsSql);

                                        // Check if comments are retrieved successfully
                                        if ($commentsResult && $commentsResult->num_rows > 0) {
                                            while ($comment = $commentsResult->fetch_assoc()) {
                                                echo "<div class='comment'>";
                                                echo "<div class='row mb-2'>";
                                                // Display user's profile image and name in the same container
                                                echo "<div class='col-md-4 d-flex align-items-center'>";
                                                echo "<img src='{$comment['profile_image']}' alt='Profile Image' style='width: 25px; height: 25px; margin-right: 10px; border-radius: 50%;'>";
                                                echo "<strong>{$comment['name']}</strong>";
                                                echo "</div>";
                                                // Display comment timestamp with data attribute
                                                echo "<div class='col-md-8 text-end'>";
                                                echo "<small class='timestamp' data-timestamp='{$comment['comment_timestamp']}'> </small>";
                                                echo "</div>";
                                                echo "</div>";
                                                // Display comment content
                                                echo "<div class='row'>";
                                                echo "<div class='col-md-12'>";
                                                if (startsWith($comment['comment_content'], '(System)')) {
                                                    // Display system-generated message differently
                                                    echo "<p><span style='color: grey;'>{$comment['comment_content']}</span></p>";
                                                } else {
                                                    echo "<p>{$comment['comment_content']}</p>";
                                                }
                                                echo "</div>";
                                                echo "<hr>";
                                                echo "</div>";
                                                echo "</div>";
                                            }
                                        } else {
                                            echo "<p>No comments yet. Be the first to comment!</p>";
                                        }

                                        // Function to check if a string starts with a specific prefix
                                        function startsWith($string, $prefix) {
                                            return substr($string, 0, strlen($prefix)) === $prefix;
                                        }
                                        ?>
                                    </div>
                                    <?php if (isset($_SESSION['isLogin']) && $_SESSION["isLogin"] === true) : ?>
                                        <!-- Comment input box -->
                                        <form id="commentForm" method="POST" action="insertComment.php">
                                            <input type="hidden" name="activity_id" value="<?= $activityId ?>">
                                            <textarea id="commentContent" name="comment_content" rows="1" placeholder="Add a comment" required></textarea>
                                        </form>
                                    <?php else : ?>
                                        <!-- Prompt to log in -->
    <div class="commentBox">
        <p>You must be <a href="login.php">logged in</a> to add a comment.</p>
    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Add this script to your HTML file -->
    <script>
        function JoinActivityRedirectToLogin() {
            if (confirm("You need to be logged in to join this activity. Do you want to proceed to the login page?")) {
                window.location.href = 'login.php'; // Redirect to the login page
            }
        }

        // Function to update time ago text
        function updateTimeAgo() {
            // Select all elements with the 'timestamp' class
            document.querySelectorAll('.timestamp').forEach(function(element) {
                // Get the timestamp from the data attribute
                var timestamp = element.getAttribute('data-timestamp');
                // Convert timestamp to Date object
                var date = new Date(timestamp);
                // Get the current time
                var now = new Date();
                // Calculate the time difference in milliseconds
                var timeDiff = now - date;

                // Calculate time ago based on time difference
                var seconds = Math.floor(timeDiff / 1000);
                var minutes = Math.floor(seconds / 60);
                var hours = Math.floor(minutes / 60);
                var days = Math.floor(hours / 24);
                var weeks = Math.floor(days / 7);
                var months = Math.floor(weeks / 4.3);
                var years = Math.floor(months / 12);

                // Update the text content of the element
                if (years > 0) {
                    element.textContent = years + " year" + (years == 1 ? "" : "s") + " ago";
                } else if (months > 0) {
                    element.textContent = months + " month" + (months == 1 ? "" : "s") + " ago";
                } else if (weeks > 0) {
                    element.textContent = weeks + " week" + (weeks == 1 ? "" : "s") + " ago";
                } else if (days > 0) {
                    element.textContent = days + " day" + (days == 1 ? "" : "s") + " ago";
                } else if (hours > 0) {
                    element.textContent = hours + " hour" + (hours == 1 ? "" : "s") + " ago";
                } else if (minutes > 0) {
                    element.textContent = minutes + " minute" + (minutes == 1 ? "" : "s") + " ago";
                } else {
                    element.textContent = "Just now";
                }
            });
        }

        // Call updateTimeAgo function when the page loads
        window.onload = function() {
            updateTimeAgo();
            // Update time ago every 60 seconds (adjust as needed)
            setInterval(updateTimeAgo, 60000);
        };

        // Function to handle form submission when Enter key is pressed
        document.getElementById("commentContent").addEventListener("keypress", function(event) {
            // Check if Enter key is pressed (key code 13)
            if (event.keyCode === 13) {
                event.preventDefault(); // Prevent default behavior (e.g., adding newline)
                document.getElementById("commentForm").submit(); // Submit the form
            }
        });
    </script>

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
