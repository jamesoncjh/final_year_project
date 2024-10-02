<?php
require("connect.php");
session_start();

// Check if the activity ID is provided in the query string
if(isset($_GET['activity_id'])) {
    // Sanitize the input to prevent SQL injection
    $activity_id = $_GET['activity_id'];
    
    // Prepare and execute a query to retrieve the activity details based on the provided ID
    $getActivitySql = "SELECT * FROM activities WHERE activity_id = ?";
    $stmt = $mysqli->prepare($getActivitySql);
    $stmt->bind_param("i", $activity_id);
    $stmt->execute();
    $activityResult = $stmt->get_result();

    if ($activityResult === false) {
        // Error occurred while executing the SQL query
        die("Error fetching activity details: " . $mysqli->error);
    }

    // Check if the activity exists
    if ($activityResult->num_rows > 0) {
        // Fetch the activity details
        $activity = $activityResult->fetch_assoc();
        // Extract other necessary details from the $activity array
        $activity_name = $activity['activity_name'];
        $activity_description = $activity['activity_description'];
        $activity_maximum = $activity['activity_maximum'];
        $activity_date = $activity['activity_date'];
        $activity_start_time = $activity['activity_start_time'];
        $activity_duration = $activity['activity_duration'];
        $activity_end_time = $activity['activity_end_time'];
        $activity_skill_level = $activity['activity_skill_level'];
        $activity_price = $activity['activity_price'];

        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve values from the form
            $activity_name = $_POST['activity-name'];
            $activity_description = $_POST['activity-description'];
            $activity_maximum = $_POST['activity-maximum'];
            $activity_date = $_POST['activity-date'];
            $activity_start_time = $_POST['activity-start-time'];
            $activity_duration = $_POST['activity-duration'];
            $activity_end_time = $_POST['activity-end-time'];
            $activity_skill_level = $_POST['activity-skill-level'];
            $activity_price = $_POST['activity-price'];

            // Update query
            $updateActivitySql = "UPDATE activities SET 
                                  activity_name = ?, 
                                  activity_description = ?, 
                                  activity_maximum = ?,
                                  activity_date = ?,
                                  activity_start_time = TIME_FORMAT(?, '%H:%i'), -- Convert to 24-hour format
                                  activity_duration = ?,
                                  activity_end_time = TIME_FORMAT(STR_TO_DATE(?, '%h:%i %p'), '%H:%i'), -- Convert 12-hour to 24-hour format
                                  activity_skill_level = ?,
                                  activity_price = ?
                                  WHERE activity_id = ?";
            $stmt = $mysqli->prepare($updateActivitySql);
            $stmt->bind_param("ssissisiii", $activity_name, $activity_description, $activity_maximum, $activity_date, $activity_start_time, $activity_duration, $activity_end_time, $activity_skill_level, $activity_price, $activity_id);
            $stmt->execute();


            // Redirect to a success page or display a success message
            header("Location: activitydetails.php?activity_id=$activity_id");
            exit();
        }
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

        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

        <!-- jQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <!-- Bootstrap Datepicker JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
        <!-- Bootstrap Datepicker CSS -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
        <!-- Bootstrap Timepicker JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>
        <!-- Bootstrap Timepicker CSS -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css" rel="stylesheet">



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
    </head>
    <body>
      <!-- ======= Header ======= -->
      <header id="header" class="header fixed-top d-flex align-items-center">
        <div class="container d-flex align-items-center justify-content-between">

          <a href="home.php" class="logo d-flex align-items-center me-auto me-lg-0">
            <!-- Uncomment the line below if you also wish to use an image logo -->
            <!-- <img src="assets/img/logo.png" alt=""> -->
            <h1>Sportify<span>.</span></h1>
          </a>

          <nav id="navbar" class="navbar">
            <ul>
              <li><a href="home.php">Home</a></li>
              <li><a href="people.php?role=user">People</a></li>
              <li><a href="venue.php">Venue</a></li>
              <li><a href="activities.php">Activities</a></li>
              <?php if (isset($_SESSION['isLogin']) && $_SESSION["isLogin"]===true) { ?>
              <li><a href="profile.php">Profile</a></li>
              <?php } ?>
            </ul>
          </nav><!-- .navbar -->
              <?php if (isset($_SESSION['isLogin']) && $_SESSION["isLogin"]===true) { ?>
          <a class="btn-book-a-table" href="logout.php">Logout</a>
          <?php } else { ?>
          <a class="btn-book-a-table" href="login.php">Login</a>
          <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
          <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>
           <?php } ?>
        </div>
      </header><!-- End Header -->

      <main id="main">
        <section id="editactivity" class="book-a-table">
            <div class="container" data-aos="fade-down" data-aos-duration="700">
                <div class="section-header">
                <p><span>Edit Activity</span></p>
                </div>
            </div>
            <div class="container" data-aos="flip-right" data-aos-duration="750">
                <div class="hostactivitycontainer">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <a href="activities.php" class="btn"><i class="bi bi-chevron-left"></i>Back</a>
                                </div>
                                <!-- Tab content -->
                                <div class="tab-content">
                                    <!-- Edit content -->
                                    <div class="tab-pane fade show active" id="edit" role="tabpanel" aria-labelledby="edit-tab">
                                        <div class="card-body" style="height: 500px; overflow-y: auto;">
                                            <form action="editActivity.php?activity_id=<?php echo $activity_id; ?>" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                                                <input type="hidden" name="activity_id" value="<?php echo htmlspecialchars($activity_id); ?>">
                                                <div class="mb-3">
                                                    <label for="activity-name" class="form-label">Name<strong> *</strong></label>
                                                    <input type="text" class="form-control" id="activity-name" style="height: 50px" name="activity-name" value="<?php echo htmlspecialchars($activity_name); ?>" placeholder="<?php echo htmlspecialchars($activity_name); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="activity-description" class="form-label">Description <small>(optional)</small></label>
                                                    <textarea class="form-control" style="height: 100px" id="activity-description" name="activity-description" placeholder="<?php echo htmlspecialchars($activity_description); ?>"><?php echo htmlspecialchars($activity_description); ?></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="activity-maximum" class="form-label">Maximum people<strong><strong> *</strong></strong></label>
                                                    <input type="number" class="form-control" id="activity-maximum" name="activity-maximum" min="2" style="max-width: 80px;" value="<?php echo htmlspecialchars($activity_maximum); ?>" placeholder="<?php echo htmlspecialchars($activity_maximum); ?>" required>
                                                </div>
                                                <!-- Date Picker -->
                                                <div class="mb-3">
                                                    <label for="activity-date" class="form-label">Date <small>(YYYY-MM-DD)</small><strong> *</strong></label>
                                                    <input type="text" class="form-control datepicker" id="activity-date" name="activity-date" value="<?php echo htmlspecialchars($activity_date); ?>" readonly required>
                                                </div>
                                                <!-- Time Picker for Start Time -->
                                                <div class="mb-3">
                                                    <label for="activity-start-time" class="form-label">Start Time<strong> *</strong></label>
                                                    <input type="text" class="form-control timepicker" style="width: 200px" id="activity-start-time" name="activity-start-time" value="<?php echo htmlspecialchars($activity_start_time); ?>" readonly required>
                                                </div>
                                                <!-- Duration -->
                                                <div class="mb-3">
                                                    <label for="activity-duration" class="form-label">Duration <small>(in minutes)</small><strong> *</strong></label>
                                                    <input type="number" class="form-control" id="activity-duration" name="activity-duration" style="max-width: 80px;" min="0" step="30" value="<?php echo htmlspecialchars($activity_duration); ?>" placeholder="<?php echo htmlspecialchars($activity_duration); ?>"required>
                                                </div>

                                                <!-- End Time -->
                                                <div class="mb-3">
                                                    <label for="activity-end-time" class="form-label">End Time <small>(auto calculated)</small></label>
                                                    <input type="text" class="form-control" style="width: 200px" id="activity-end-time" name="activity-end-time" value="<?php echo htmlspecialchars($activity_end_time); ?>" readonly>
                                                </div>

                                                <!-- Skill Level -->
                                                <div class="mb-3">
                                                    <label for="activity-skill-level" class="form-label">Skill Level</label><br>
                                                    <div class="skill-level-container">
                                                        <input type="range" id="skill-level" name="activity-skill-level" min="1" max="5" value="<?php echo htmlspecialchars($activity_skill_level); ?>" step="1" oninput="updateSkillLevel(this.value)">
                                                        <span id="skill-level-value" class="skill-level-value"><?php echo htmlspecialchars($activity_skill_level); ?></span>
                                                    </div>
                                                    <span id="skill-level-text">Beginner</span>
                                                </div>
                                                <!-- Price -->
                                                <div class="mb-3">
                                                    <label for="activity-price" class="form-label">Price (MYR)<strong> *</strong></label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control" id="activity-price" name="activity-price" min="0" step="0.50" style="max-width: 100px;" value="<?php echo htmlspecialchars($activity_price); ?>" placeholder="<?php echo htmlspecialchars($activity_price); ?>"required>
                                                        <span class="input-group-text">/person</span>
                                                    </div>
                                                </div>
                                                <br>
                                                <input type='hidden' id='userid' name="userid" value='<?php echo $_SESSION['userid'] ?>'>
                                                <button type="submit" class="btn btn-primary" onclick="return confirmEditActivity()">Edit Activity</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
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

        <!-- Template Main JS File -->
        <script src="assets/js/main.js"></script>

        <script>
            function confirmEditActivity() {
                // Display a confirmation dialog
                var result = confirm("Are you sure you want to edit this activity?");
        
                // If the user confirms, return true to allow form submission. Otherwise, return false.
                return result;
            }
            $(document).ready(function(){
                updateSkillLevel($('#skill-level').val());

                // Datepicker
                $('.datepicker').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    startDate: 'today'
                });

                // Timepicker
                $('.timepicker').timepicker({
                    showMeridian: true, // Remove AM/PM
                    showSeconds: false,
                    defaultTime: false,
                    minuteStep: 30, // Set minute step to 1 for more precision
                    icons: {
                        up: 'bi bi-chevron-up',
                        down: 'bi bi-chevron-down'
                    }
                });

                // Format time to 24-hour format before form submission
                $('form').submit(function() {
                    var startTime12HourFormat = $('#activity-start-time').val();
                    var startTime24HourFormat = moment(startTime12HourFormat, 'hh:mm A').format('HH:mm');
                    $('#activity-start-time').val(startTime24HourFormat); // Set the formatted value back to the input field
                    return true; // Continue with form submission
                });

                // Update end time based on start time and duration
                $('#activity-duration').on('input', function() {
                    calculateEndTime();
                });

                $('#activity-start-time').on('changeTime.timepicker', function(e) {
                    calculateEndTime();
                });

function calculateEndTime() {
    var startTime = $('#activity-start-time').val();
    var duration = parseInt($('#activity-duration').val(), 10);

    if (!isNaN(duration) && startTime) {
        var startTime24HourFormat = moment(startTime, 'hh:mm A').format('HH:mm');
        var startTimeMoment = moment(startTime24HourFormat, 'HH:mm');
        var endTimeMoment = startTimeMoment.clone().add(duration, 'minutes');

        // Format output as 12-hour format with AM/PM
        var endTime12HourFormat = endTimeMoment.format('hh:mm A');
        $('#activity-end-time').val(endTime12HourFormat); // Set value to 12-hour format in the input field
    }
}


                // Call calculateEndTime initially to set the correct end time if start time and duration are pre-filled
                calculateEndTime();
            });

            function updateSkillLevel(value) {
                var text = "";
                if (value == 1) {
                    text = "Beginner";
                } else if (value == 2 || value == 3) {
                    text = "Intermediate";
                } else {
                    text = "Advanced";
                }
                document.getElementById("skill-level-text").innerText = text;
                document.getElementById("skill-level-value").innerText = value;
            }
        </script>
      </main>
    </body>
<?php
    } else {
        // No activity found with the provided ID
        echo "<p>Activity not found.</p>";
    }

} else {
    // No activity ID provided in the query string
    echo "<p>No activity ID provided.</p>";
}

// Close the database connection
$mysqli->close();
?>