    <?php
	    require("connect.php");
	    session_start();
    
    $getLocationsSql = "SELECT DISTINCT * FROM badminton_location ORDER BY badminton_location_state, badminton_location_name, badminton_location_id";
    $getLocationsResult = $mysqli->query($getLocationsSql);
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

        <!-- Bootstrap Timepicker JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>

        <!-- Bootstrap Datepicker CSS -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">

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

        <section id="hostActivity" class="book-a-table">
            <div class="container" data-aos="fade-down" data-aos-duration="700">
                <div class="section-header">
                <p><span>Host Activity</span></p>
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
                                <!-- Tab navigation -->
                                <div class="card-header">
                                    <ul class="nav nav-tabs card-header-tabs">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="badminton-tab" data-bs-toggle="tab" href="#badminton" role="tab" aria-controls="badminton" aria-selected="true">Badminton</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="basketball-tab" data-bs-toggle="tab" href="#basketball" role="tab" aria-controls="basketball" aria-selected="false">Basketball</a>
                                        </li>
                                    </ul>
                                </div>
                                <!-- Tab content -->
                                <div class="tab-content">
                                    <!-- Badminton content -->
                                    <div class="tab-pane fade show active" id="badminton" role="tabpanel" aria-labelledby="badminton-tab">
                                        <div class="card-body" style="height: 450px; overflow-y: auto;">
                                            <form action="createActivity.php?sport=1" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">          
                                                <!-- Location options for Badminton -->
                                                <div class="mb-3 location-select" id="location-select-2">
                                                    <label for="activity-location-badminton" class="form-label">Location <small>(Badminton)</small><strong> *</strong></label>
                                                    <select class="form-select" id="activity-location-badminton" name="activity-location-badminton" required>
                                                        <option value="" disabled selected>Select Location</option>
                                                        <?php 
                                                        // Fetch and categorize badminton locations by state
                                                        $getBadmintonLocationsSql = "SELECT * FROM badminton_location ORDER BY badminton_location_state, badminton_location_name, badminton_location_id";
                                                        $getBadmintonLocationsResult = $mysqli->query($getBadmintonLocationsSql);

                                                        $current_state = ''; // Variable to keep track of the current state

                                                        // Loop through badminton locations
                                                        while ($location = $getBadmintonLocationsResult->fetch_assoc()) {
                                                            // If the state has changed, display state as an option group
                                                            if ($location['badminton_location_state'] !== $current_state) {
                                                                // If it's not the first state, close the previous optgroup
                                                                if ($current_state !== '') {
                                                                    echo '</optgroup>';
                                                                }
                                                                // Update the current state
                                                                $current_state = $location['badminton_location_state'];
                                                                // Display a new optgroup for the current state
                                                                echo '<optgroup label="' . $current_state . '">';
                                                            }
                                                            // Display the location within the optgroup
                                                            echo '<option value="' . $location['badminton_location_id'] . '">' . $location['badminton_location_name'] . '</option>';
                                                        }
                                                        // Close the last optgroup
                                                        echo '</optgroup>';
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="activity-name" class="form-label">Name<strong> *</strong></label>
                                                    <!-- Remove the height style to allow the input field to adjust its height based on content -->
                                                    <textarea class="form-control" style="height: 50px" id="activity-name" name="activity-name" placeholder="Activity Name" required></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="activity-description" class="form-label">Description <small>(optional)</small></label>
                                                    <textarea class="form-control" style="height: 100px" id="activity-description" name="activity-description" placeholder="Activity Description"></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="activity-maximum" class="form-label">Maximum people<strong> *</strong></label>
                                                    <input type="number" class="form-control" id="activity-maximum" name="activity-maximum" min="2" style="max-width: 80px;" placeholder="8" required>
                                                </div>

                                                <!-- Date Picker -->
                                                <div class="mb-3">
                                                    <label for="activity-date" class="form-label">Date <small>(YYYY-MM-DD)</small><strong> *</strong></label>
                                                    <input type="text" class="form-control datepicker" id="activity-date" name="activity-date" readonly required>
                                                </div>

                                                <!-- Time Picker for Start Time -->
                                                <div class="mb-3">
                                                    <label for="activity-start-time" class="form-label">Start Time<strong> *</strong></label>
                                                    <input type="text" class="form-control timepicker" id="activity-start-time-badminton" name="activity-start-time-badminton" readonly required>
                                                </div>

                                                <!-- Duration -->
                                                <div class="mb-3">
                                                    <label for="activity-duration" class="form-label">Duration <small>(in minutes)</small><strong> *</strong></label>
                                                    <input type="number" class="form-control" id="activity-duration-badminton" name="activity-duration-badminton" style="max-width: 80px;" min="0" step="30" required>
                                                </div>

                                                <!-- End Time -->
                                                <div class="mb-3">
                                                    <label for="activity-end-time" class="form-label">End Time <small>(auto calculated)</small></label>
                                                    <input type="text" class="form-control" id="activity-end-time-badminton" name="activity-end-time-badminton" readonly>
                                                </div>

                                                <!-- Skill Level -->
                                                <div class="mb-3">
                                                    <label for="activity-skill-level" class="form-label">Skill Level</label><br>
                                                    <div class="skill-level-container">
                                                        <input type="range" id="skill-level-badminton" name="skill-level-badminton" min="1" max="5" value="1" step="1" oninput="updateSkillLevelBadminton(this.value)">
                                                        <span id="skill-level-value-badminton" class="skill-level-value">1</span>
                                                    </div>
                                                    <span id="skill-level-text-badminton">Beginner</span>
                                                </div>

                                                <!-- Price -->
                                                <div class="mb-3">
                                                    <label for="activity-price" class="form-label">Price (MYR)<strong> *</strong></label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control" id="activity-price" name="activity-price" min="0" step="0.50" style="max-width: 100px;" required>
                                                        <span class="input-group-text">/person</span>
                                                    </div>
                                                </div>
                                                <br>
                                                <input type='hidden' id='userid' name="userid" value='<?php echo $_SESSION['userid'] ?>'>
                                                <button type="submit" class="btn btn-primary">Create Activity</button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Basketball content -->
                                    <div class="tab-pane fade" id="basketball" role="tabpanel" aria-labelledby="basketball-tab">
                                        <div class="card-body" style="height: 450px; overflow-y: auto;">
                                            <form action="createActivity.php?sport=2" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">   
                                                <!-- Location options for Basketball -->
                                                <div class="mb-3 location-select" id="location-select-2">
                                                    <label for="activity-location-basketball" class="form-label">Location <small>(Basketball)</small><strong> *</strong></label>
                                                    <select class="form-select" id="activity-location-basketball" name="activity-location-basketball">
                                                        <option value="" disabled selected>Select Location</option>
                                                        <?php 
                                                        // Fetch and categorize basketball locations by state
                                                        $getBasketballLocationsSql = "SELECT * FROM basketball_location ORDER BY basketball_location_state, basketball_location_name, basketball_location_id";
                                                        $getBasketballLocationsResult = $mysqli->query($getBasketballLocationsSql);

                                                        $current_state = ''; // Variable to keep track of the current state

                                                        // Loop through basketball locations
                                                        while ($location = $getBasketballLocationsResult->fetch_assoc()) {
                                                            // If the state has changed, display state as an option group
                                                            if ($location['basketball_location_state'] !== $current_state) {
                                                                // If it's not the first state, close the previous optgroup
                                                                if ($current_state !== '') {
                                                                    echo '</optgroup>';
                                                                }
                                                                // Update the current state
                                                                $current_state = $location['basketball_location_state'];
                                                                // Display a new optgroup for the current state
                                                                echo '<optgroup label="' . $current_state . '">';
                                                            }
                                                            // Display the location within the optgroup
                                                            echo '<option value="' . $location['basketball_location_id'] . '">' . $location['basketball_location_name'] . '</option>';
                                                        }
                                                        // Close the last optgroup
                                                        echo '</optgroup>';
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="activity-name" class="form-label">Name<strong> *</strong></label>
                                                    <!-- Remove the height style to allow the input field to adjust its height based on content -->
                                                    <textarea class="form-control" style="height: 50px" id="activity-name" name="activity-name" placeholder="Activity Name" required></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="activity-description" class="form-label">Description <small>(Optional)</small></label>
                                                    <textarea class="form-control" style="height: 100px" id="activity-description" name="activity-description" placeholder="Activity Description"></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="activity-maximum" class="form-label">Maximum people<strong> *</strong></label>
                                                    <input type="number" class="form-control" id="activity-maximum" name="activity-maximum" min="2" style="max-width: 80px;" placeholder="8" required>
                                                </div>

                                                <!-- Date Picker -->
                                                <div class="mb-3">
                                                    <label for="activity-date" class="form-label">Date <small>(YYYY-MM-DD)</small><strong> *</strong></label>
                                                    <input type="text" class="form-control datepicker" id="activity-date" name="activity-date" readonly required>
                                                </div>

                                                <!-- Time Picker for Start Time -->
                                                <div class="mb-3">
                                                    <label for="activity-start-time" class="form-label">Start Time<strong> *</strong></label>
                                                    <input type="text" class="form-control timepicker" id="activity-start-time-basketball" name="activity-start-time-basketball" readonly required>
                                                </div>

                                                <!-- Duration -->
                                                <div class="mb-3">
                                                    <label for="activity-duration" class="form-label">Duration <small>(in minutes)</small><strong> *</strong></label>
                                                    <input type="number" class="form-control" id="activity-duration-basketball" name="activity-duration-basketball" style="max-width: 80px;" min="0" step="30" required>
                                                </div>

                                                <!-- End Time -->
                                                <div class="mb-3">
                                                    <label for="activity-end-time" class="form-label">End Time <small>(auto calculated)</small></label>
                                                    <input type="text" class="form-control" id="activity-end-time-basketball" name="activity-end-time-basketball" readonly>
                                                </div>

                                                <!-- Skill Level -->
                                                <div class="mb-3">
                                                    <label for="activity-skill-level" class="form-label">Skill Level</label><br>
                                                    <div class="skill-level-container">
                                                        <input type="range" id="skill-level-basketball" name="skill-level-basketball" min="1" max="5" value="1" step="1" oninput="updateSkillLevelBasketball(this.value)">
                                                        <span id="skill-level-value-basketball" class="skill-level-value">1</span>
                                                    </div>
                                                    <span id="skill-level-text-basketball">Beginner</span>
                                                </div>

                                                <!-- Price -->
                                                <div class="mb-3">
                                                    <label for="activity-price" class="form-label">Price (MYR)<strong> *</strong></label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control" id="activity-price" name="activity-price" min="0" step="0.50" style="max-width: 100px;" required>
                                                        <span class="input-group-text">/person</span>
                                                    </div>
                                                </div>
                                                <br>
                                                <input type='hidden' id='userid' name="userid" value='<?php echo $_SESSION['userid'] ?>'>
                                                <button type="submit" class="btn btn-primary">Create Activity</button>
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
        $(document).ready(function() {
            // Initialize Date Picker
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd', // Date format
                autoclose: true, // Close the picker when a date is selected
                startDate: new Date() // Set the start date to today
            });

            // Initialize Time Picker
            $('.timepicker').timepicker({
                format: 'LT', // HH:MM AM/PM format
                icons: {
                    time: 'bi bi-clock' // Clock icon
                },
                showMeridian: true // Show AM/PM
            });

            // Function to calculate end time based on start time and duration for badminton tab
            function calculateEndTime() {
                var startTime = $('#activity-start-time-badminton').val();
                var duration = parseInt($('#activity-duration-badminton').val());

                if (startTime && !isNaN(duration)) {
                    // Parse start time to moment object
                    var start = moment(startTime, 'hh:mm A');

                    // Add duration minutes to start time
                    var end = start.clone().add(duration, 'minutes');

                    // Update end time field
                    $('#activity-end-time-badminton').val(end.format('hh:mm A')); // Adjust ID for badminton tab
                }
            }

            // Call calculateEndTime function when either start time or duration changes
            $('#activity-start-time-badminton, #activity-duration-badminton').on('change', calculateEndTime); // Adjust IDs for badminton tab

            // Call calculateEndTime function initially to set end time based on default values
            calculateEndTime();

            // Function to calculate end time based on start time and duration for basketball tab
            function calculateEndTimeBasketball() {
                var startTime = $('#activity-start-time-basketball').val(); // Adjust ID for basketball tab
                var duration = parseInt($('#activity-duration-basketball').val()); // Adjust ID for basketball tab

                if (startTime && !isNaN(duration)) {
                    // Parse start time to moment object
                    var start = moment(startTime, 'hh:mm A');

                    // Add duration minutes to start time
                    var end = start.clone().add(duration, 'minutes');

                    // Update end time field
                    $('#activity-end-time-basketball').val(end.format('hh:mm A')); // Adjust ID for basketball tab
                }
            }

            // Call calculateEndTime function when either start time or duration changes for basketball tab
            $('#activity-start-time-basketball, #activity-duration-basketball').on('change', calculateEndTimeBasketball); // Adjust IDs for basketball tab

            // Call calculateEndTime function initially to set end time based on default values for basketball tab
            calculateEndTimeBasketball();
        });

        // Function to update skill level text and value for badminton tab
        function updateSkillLevelBadminton(value) {
            var text = "";
            if (value == 1) {
                text = "Beginner";
            } else if (value == 2 || value == 3) {
                text = "Intermediate";
            } else {
                text = "Advanced";
            }
            document.getElementById("skill-level-text-badminton").innerText = text; // Adjust ID for badminton tab
            document.getElementById("skill-level-value-badminton").innerText = value; // Adjust ID for badminton tab
        }

        // Function to update skill level text and value for basketball tab
        function updateSkillLevelBasketball(value) {
            var text = "";
            if (value == 1) {
                text = "Beginner";
            } else if (value == 2 || value == 3) {
                text = "Intermediate";
            } else {
                text = "Advanced";
            }
            document.getElementById("skill-level-text-basketball").innerText = text; // Adjust ID for basketball tab
            document.getElementById("skill-level-value-basketball").innerText = value; // Adjust ID for basketball tab
        }


        </script>
      </main>
    </body>
