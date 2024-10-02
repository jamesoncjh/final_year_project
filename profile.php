<?php
    require("connect.php");
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['isLogin']) || $_SESSION["isLogin"] !== true) {
        // Redirect to login page if not logged in
        header("Location: login.php");
        exit;
    }

    // Fetch user details from the database based on the user_id parameter
    if (isset($_GET['user_id'])) {
        // Sanitize the input to prevent SQL injection
        $userId = $mysqli->real_escape_string($_GET['user_id']);
    } else {
        // If user_id is not provided in the URL, use the current user's ID
        $userId = $_SESSION['userid'];
    }

    // Fetch user details of the profile owner
    $getUserDetailsSql = "SELECT * FROM users WHERE user_id = '$userId'";
    $userDetailsResult = $mysqli->query($getUserDetailsSql);

    // Check if user details are found
    if ($userDetailsResult->num_rows > 0) {
        $userDetails = $userDetailsResult->fetch_assoc();
        $name = $userDetails['name'];
        $username = $userDetails['username'];
        $gender = $userDetails['gender'];
        $age = $userDetails['age'];
        $email = $userDetails['email'];
        $preferred_location = $userDetails['preferred_location'];
        $profileImage = $userDetails['profile_image'];

        // Fetch self-assessment values from the database
        $experience_skill = $userDetails['experience_skill'];
        $underhand_skill = $userDetails['underhand_skill'];
        $overhead_skill = $userDetails['overhead_skill'];
        $movement_skill = $userDetails['movement_skill'];
        $smashes_skill = $userDetails['smashes_skill'];
        $tactics_skill = $userDetails['tactics_skill'];
    } else {
        // User details not found
        $name = "";
        $username = "";
        $gender = "";
        $profileImage = "";
        $experience_skill = 0;
        $underhand_skill = 0;
        $overhead_skill = 0;
        $movement_skill = 0;
        $smashes_skill = 0;
        $tactics_skill = 0;
    }

    // Check if the currently logged-in user is the owner of the profile
    $isOwner = isset($_SESSION['userid']) && $_SESSION['userid'] == $userId;

    // Query to fetch activities created by the user
    $createdActivitiesSql = "SELECT * FROM activities WHERE user_id = ?";
    $createdStmt = $mysqli->prepare($createdActivitiesSql);
    $createdStmt->bind_param("i", $_SESSION['userid']);
    $createdStmt->execute();
    $createdResult = $createdStmt->get_result();

    // Query to fetch activities joined by the user
    $joinedActivitiesSql = "SELECT a.* FROM activities a INNER JOIN user_activities ua ON a.activity_id = ua.activity_id WHERE ua.user_id = ?";
    $joinedStmt = $mysqli->prepare($joinedActivitiesSql);
    $joinedStmt->bind_param("i", $_SESSION['userid']);
    $joinedStmt->execute();
    $joinedResult = $joinedStmt->get_result();

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


      <!-- Vendor JS Files -->
      <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
      <script src="assets/vendor/aos/aos.js"></script>
      <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
      <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
      <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
      <script src="assets/vendor/php-email-form/validate.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

      <!-- Template Main JS File -->
      <script src="assets/js/main.js"></script>
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
                <?php if (isset($_SESSION['role']) && $_SESSION['role']=="admin"){ ?>
                        <li><a href="ManageUser.php">User</a></li>
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
      </header>

    <section id="profile" class="book-a-table">
        <div class="container" data-aos="zoom-out" data-aos-duration="1000">
            <div class="section-header">
                    <p><span>Profile</span></p>
            </div>
        </div>
        <div class="profilecontainer" data-aos="zoom-in" data-aos-duration="600">
            <div class="profile-picture">
                    <img src="<?php echo $profileImage; ?>" class="profile-img">
            </div>
            <div class="profile-details">
                <div class="profile-info">
                    <div class="profile-username">
                        <p><strong>Name: </strong><?php echo $name; ?></p>
                        <p><strong>Username: </strong>@<?php echo $username; ?></p>
                        <p><strong>Gender: </strong><?php echo $gender; ?></p>
                        <p><strong>Age: </strong><?php echo $age; ?></p>

                        <br>
                        <?php if ($isOwner): ?>
                        <div class="private-info">
                            <small><strong><em> Private Info (Hidden) </small></strong></em>
                        </div>
                        <div class="line-container">
                            <hr> 
                        </div>
                        <!-- Display email only if the user is the owner -->
                        <p><strong>Email: </strong><?php echo $email; ?></p>
                        <p><strong>Preferred Location: </strong> <?php echo $preferred_location; ?></p>
                    </div>
                    <?php endif; ?>


                    <?php
                    // Calculate overall skill level and round to 1 decimal place
                    $overallSkill = round(($experience_skill + $overhead_skill + $underhand_skill + $tactics_skill + $smashes_skill + $movement_skill) / 6, 1);

                    // Function to determine the skill level description based on overall skill level
                    function getSkillLevelDescription($skillLevel) {
                        if ($skillLevel <= 1.9) {
                            return "Beginner";
                        } elseif ($skillLevel >= 2 || $skillLevel <= 3.9) {
                            return "Intermediate";
                        } else {
                            return "Advanced";
                        }
                    }

                    // Get skill level description
                    $skillLevelDescription = getSkillLevelDescription($overallSkill);
                    ?>

                    <small><strong><em> Badminton </small></strong></em>
                        <div class="line-container">
                            <hr> 
                        </div>
                    <p><strong>Skill Level: </strong><?php echo $overallSkill; ?> (<?php echo $skillLevelDescription; ?>)</p>
                    <div class="chart-container">
                        <canvas id="polarAreaChart"></canvas>
                    </div>

                </div>
                <!-- Buttons container -->
                <?php if ($isOwner): ?>
                <div class="buttons-container">
                    <button type="button" class="btn btn-primary btn-edit-profile" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</button>
                    <button type="button" class="btn btn-primary btn-self-assessment" data-bs-toggle="modal" data-bs-target="#selfAssessmentModal">Self Assessment</button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

<?php if ($isOwner): ?>   
<section id="created-activities" class="activities-container">
    <h4>Activities Created</h4>
    <div class="activities-list">
        <?php if ($createdResult->num_rows > 0) : ?>
            <!-- Loop through created activities and display them -->
            <?php while ($createdActivity = $createdResult->fetch_assoc()) : ?>
                <div class="activity-item">
                    <div class="activity-content">
                        <p class="activity-name"><?php echo $createdActivity['activity_name']; ?></p>
                         <p class="activity-date"><?php echo "<strong>Date created: </strong>" . $createdActivity['activity_date']; ?></p>
                        <!-- Display other details of the created activity -->
                    </div>
                    <div class="button-group"> <!-- Wrap buttons in a div -->
                        <form id="deleteForm_<?php echo $createdActivity['activity_id']; ?>" method="post" action="deleteActivity.php">
                        <button type='button' class="view-details-btn" data-activity-id="<?php echo $createdActivity['activity_id']; ?>" data-bs-toggle="modal" data-bs-target="#activityDetailsModal">Details</button>
                            <input type="hidden" name="activity_id" value="<?php echo $createdActivity['activity_id']; ?>">
                            <input type="hidden" name="origin_page" value="profile">
                            <button type="button" class="btn btn-danger mx-2" onclick="confirmDelete(<?php echo $createdActivity['activity_id']; ?>)">Delete</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <p>No activities created yet.</p>
        <?php endif; ?>
    </div>
</section>


<section id="joined-activities" class="activities-container">
    <h4>Activities Joined</h4>
    <div class="activities-list">
        <?php if ($joinedResult->num_rows > 0) : ?>
            <!-- Loop through joined activities and display them -->
            <?php while ($joinedActivity = $joinedResult->fetch_assoc()) : ?>
                <div class="activity-item">
                    <div class="activity-content">
                        <p class="activity-name"><?php echo $joinedActivity['activity_name']; ?></p>
                        <!-- Display other details of the joined activity -->
                    </div>
                    <button class="view-details-btn" data-activity-id="<?php echo $joinedActivity['activity_id']; ?>" data-bs-toggle="modal" data-bs-target="#activityDetailsModal">Details</button>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <p>No activities joined yet.</p>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>


    <!-- Modal for Activity Details -->
    <div class="modal fade" id="activityDetailsModal" tabindex="-1" aria-labelledby="activityDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- Modal Title -->
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Display activity details here -->
                    <p id="activityName"></p>
                    <p><i class='bi bi-clock'></i> <span id="activityDate"></span></p>
                    <i class='bi bi-geo-alt'></i> <strong><span id="activityLocation"></span></strong>
                    <br>
                    <span id="activityAddress"></span>
                    <br><br>
                    <i class='bi bi-coin'></i> <strong><span id="activityPrice"></span> MYR / person</strong>
                    <br><br>
                    <!-- Additional activity details -->
                    <small><p><i class='bi bi-text-left'> </i> Description</p></small>
                    <hr>
                    <p id="activityDescription"></p>
                </div>
            </div>
        </div>
    </div>


    <script>
    function confirmDelete(activityId) {
        if (confirm("Are you sure you want to delete this activity?")) {
            // Submit the form to delete the activity
            document.getElementById('deleteForm_' + activityId).submit();
        }
    }


        // Assign PHP variables to JavaScript variables
        var experienceSkill = Math.round(<?php echo $experience_skill; ?>);
        var overheadSkill = Math.round(<?php echo $overhead_skill; ?>);
        var underhandSkill = Math.round(<?php echo $underhand_skill; ?>);
        var smashesSkill = Math.round(<?php echo $smashes_skill; ?>);
        var tacticsSkill = Math.round(<?php echo $tactics_skill; ?>);
        var movementSkill = Math.round(<?php echo $movement_skill; ?>);

            // Polar area chart data
    var data = {
        labels: ['Experience', 'Overhead', 'Underhand', 'Smashes', 'Tactics', 'Movement'],
        datasets: [{
            label: 'Skill Level',
            data: [experienceSkill, overheadSkill, underhandSkill, smashesSkill, tacticsSkill, movementSkill],
            backgroundColor: [
                'rgba(255, 99, 132, 0.5)', // Experience
                'rgba(54, 162, 235, 0.5)',  // Overhead
                'rgba(255, 206, 86, 0.5)',  // Underhand
                'rgba(255, 159, 64, 0.5)',  // Smashes
                'rgba(75, 192, 192, 0.5)',  // Tactics
                'rgba(153, 102, 255, 0.5)'  // Movement
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)'
            ],
            borderWidth: 1
        }]
    };

    // Polar area chart options
    var options = {
        scale: {
            ticks: {
                beginAtZero: true,
                stepSize: 1, // Specify the step size for the ticks
                max: 7 // Assuming skill levels range from 1 to 7
            }
        }
    };

    // Get the canvas element
    var ctx = document.getElementById('polarAreaChart').getContext('2d');
    // Create the Polar Area chart
    var polarAreaChart = new Chart(ctx, {
        type: 'polarArea',
        data: data,
        options: options
    });

        function updateExperience(value) {
            var text = "";
            if (value == 1) {
                text = "I want to learn to play badminton. I am still learning to hit my shots without missing.";
            } else if (value == 2) {
                text = "I play occasionally. I miss my shots half the time and can't maintain rallies.";
            } else if (value == 3) {
                text = "I play occasionally. I can hit most of my shots without much accuracy. I can maintain short rallies at short pace.";
            } else if (value == 4) {
                text = "I play frequently. I can hit most of my shots with some accuracy. I can maintain short rallies at moderate to fast pace.";
            } else if (value == 5) {
                text = "I need to play badminton every week. My shots are very consistent and accurate. I can move my opponent around with my shots pretty well.";
            } else if (value == 6) {
                text = "Badminton makes my world go round. My shots are strong, accurate and consistent. I compete in provinicial tounrmanents. I can recognise my opponent's weaknesses and maximise my strengths.";
            } else if (value == 7) {
                text = "I'm a sectional or nationally ranked badminton player & don't think I require a rating. I have intensive training for national tournament at junior, college or university level. I am good enough to play in the national team.";
            }
            document.getElementById("experience-text").innerText = text;
            document.getElementById("experience-value").innerText = value;
        }
        function updateUnderhand(value) {
            var text = "";
            if (value == 1) {
                text = "I can serve, but inconsistent in both getting the shuttle to the right place and consistency.";
            } else if (value == 2) {
                text = "My serves are fairly consistent and accurate. My long serve does not reach the back line. Short serve is still a bit high at the net. I can return smashes occasionally.";
            } else if (value == 3) {
                text = "My serves are consistent and accurate. My long serve can reach the back line. Short serve is close to the net. I can return smashes occasionally.";
            } else if (value == 4) {
                text = "My serves are proficient and accurate. I can return smashes fairly consistently.";
            } else if (value == 5) {
                text = "My serves are varied, proficient and accurate. I can return smashes fairly consistently to the back alley.";
            } else if (value == 6) {
                text = "My serves are varied, proficient and accurate. I can return smashes consistently to the backcourt and change its direction. I am starting to develop flicks.";
            } else if (value == 7) {
                text = "My serves are varied, proficient and accurate. I can return smashes consistently to the backcourt and change its direction. I can do flicks and feints now.";
            }
            document.getElementById("underhand-text").innerText = text;
            document.getElementById("underhand-value").innerText = value;
        }
        function updateOverhead(value) {
            var text = "";
            if (value == 1) {
                text = "I can hit most overhead shots without accuracy at a depth up to midcourt. I'm starting to learn drop shots.";
            } else if (value == 2) {
                text = "I can hit most overhead shots with some accuracy up to midcourt. I can do some drop shots at low pace.";
            } else if (value == 3) {
                text = "I can hit all overhead shots with some accuracy to the backcourt. I'm comfortable with drop shots at low pace.";
            } else if (value == 4) {
                text = "I can hit all overhead shots with accuracy to backcourt. I am comfortable with drop shots at low pace. I can do basic backhand clears.";
            } else if (value == 5) {
                text = "I can hit all overhead shots with accuracy to backcourt. I am comfortable with drop shots at fast pace. I can do most backhand clears.";
            } else if (value == 6) {
                text = "I can hit all overhead shots with accuracy to backcourt. I am proficient with fast pace drop shots and backhand clears. I can do both slices but lacks consistency.";
            } else if (value == 7) {
                text = "I can hit all overhead with accuracy to backcourt. I am comfortable with drop shots at fast pace. I can do backhand drops consistently.";
            }
            document.getElementById("overhead-text").innerText = text;
            document.getElementById("overhead-value").innerText = value;
        }
        function updateMovement(value) {
            var text = "";
            if (value == 1) {
                text = "I'm able to anticipate where the shuttle will land but not able to consistently reach in time to return the shot.";
            } else if (value == 2) {
                text = "I'm able to anticipate where the shuttle will land and sometimes reach in time to return shots.";
            } else if (value == 3) {
                text = "I'm able to anticipate and reach in time to return most shots. I can move backwards using shuffle and chasse. I understand what a split step is and I'm learning to use it.";
            } else if (value == 4) {
                text = "I'm able to anticipate and reach in time to return most shots and use split step accasionally. I can move backwards using shuffle and chasse. I can perform a basic defensive scissor kick.";
            } else if (value == 5) {
                text = "I'm able to anticipate and reach in time to return most shots and use split step regularly. I can move backwards using shuffle and chasse at a fast pace. I can perform defensive scissor kick comfortably.";
            } else if (value == 6) {
                text = "I'm able to anticipate and reach in time to return all shots comfortably. I can move backwards using shuffle and chasse at a fast pace. I can perform defensive scissor kick comfortably. I'm able to recover quicker from defensive scissor kicks and deep lunges.";
            } else if (value == 7) {
                text = "All my movements are explosive and I'm proficient in all types of movements on the court.";
            }
            document.getElementById("movement-text").innerText = text;
            document.getElementById("movement-value").innerText = value;
        }
        function updateSmashes(value) {
            var text = "";
            if (value == 1) {
                text = "I'm not able to do smashes yet. I do not know what drive and net play are.";
            } else if (value == 2) {
                text = "I'm starting to understand when to smash and when not to. My forehand drives lack consistency. I understand what is net play, but lack consistency with my net paly shots.";
            } else if (value == 3) {
                text = "I know when to smash but my smashes lack power and accuracy. I'm comfortable with my forehand drives but my backhand drives lack consistency. I'm able to engage in net play but lack consistency.";
            } else if (value == 4) {
                text = "My smashes have power but lack accuracy. I'm comfortable with my forehand and backhand drives. I'm able to engage comfortably in net play with some consistency.";
            } else if (value == 5) {
                text = "My smashes have power and accuracy. I'm proficient with my forehand and backhand drives. I'm proficient with most net paly techniques.";
            } else if (value == 6) {
                text = "My smashes have power, accuracy and angle. I'm proficient with forehand and backhand drives. I'm proficient with all net play techniques.";
            } else if (value == 7) {
                text = "My smashes are consistently powerful, accurate and always at a good angle. I'm proficient with my forehand and backhand drives. I'm proficient with all net play techniques.";
            }
            document.getElementById("smashes-text").innerText = text;
            document.getElementById("smashes-value").innerText = value;
        }
        function updateTactics(value) {
            var text = "";
            if (value == 1) {
                text = "I can keep score, but may get confused when keeping scores in doubles. I'm just beginning to learn how to rotate in doubles and positioning in singles.";
            } else if (value == 2) {
                text = "I'm familiar with scoring. I understand the basics of singles positioning but have dificulty with speed of recovery. My doubles rotation is understood but inconsistent.";
            } else if (value == 3) {
                text = "I'm starting to understand how a tournament structure works. I'm proficient with scoring for both singles and doubles.";
            } else if (value == 4) {
                text = "I'm starting to understand and develop a strategy to construct points. I'm beginning to think ahead of my shots and play but lack consistency.";
            } else if (value == 5) {
                text = "I have a good understanding of how to move my opponent around and can manipulate the play and pace of a game to a moderate level.";
            } else if (value == 6) {
                text = "I'm now entering B level tournaments and understand how draws are made and conducted as well as the rules of tournament construction and play. I can adapt my style of play to contrast my opponent.";
            } else if (value == 7) {
                text = "I'm now entering A level tournaments and understand how draws are made and conducted as well as the rules of tournament construction and play. I can recognise my opponent's weakness and maximise my strengths.";
            }
            document.getElementById("tactics-text").innerText = text;
            document.getElementById("tactics-value").innerText = value;
        }

        // JavaScript code to handle self-assessment form submission
        document.addEventListener("DOMContentLoaded", function () {
            var form = document.getElementById("selfAssessmentForm");
            var submitButton = document.getElementById("submitAssessment");

            submitButton.addEventListener("click", function (event) {
                event.preventDefault(); // Prevent default form submission

                // Fetch form data
                var formData = new FormData(form);

                // Send form data to the server using AJAX
                fetch("editSkill.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        // If the response is successful, display a success message
                        alert("Self-assessment submitted successfully");
                        window.location.href = "profile.php";
                    } else {
                        // If there's an error, display an error message
                        alert("Error submitting self-assessment");
                    }
                })
                .catch(error => {
                    // If there's a network error, display an error message
                    alert("Network error: " + error.message);
                });
            });
        });


// Add event listener to View Details buttons
document.querySelectorAll('.view-details-btn').forEach(item => {
    item.addEventListener('click', event => {
        // Get the activity ID from the data-attribute
        const activityId = event.currentTarget.getAttribute('data-activity-id');
        // Make an AJAX request to fetch activity details
        fetch('get_activity_details.php?id=' + activityId)
            .then(response => response.json())
            .then(data => {
                // Update the modal with activity details
                updateModal(data);
                // Show the modal
                showModal();
            })
            .catch(error => {
                console.error('Error fetching activity details:', error);
            });
    });
});

// Function to update modal with activity details
function updateModal(activityDetails) {
    // Convert the activity date string to a Date object
    const activityDate = new Date(activityDetails.activity_date);

    // Array of month names to use in formatting
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

    // Get the day, month, and year components of the activity date
    const dayOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'][activityDate.getDay()];
    const dayOfMonth = activityDate.getDate();
    const month = monthNames[activityDate.getMonth()];

    // Construct the formatted date string
    const formattedDate = `${dayOfWeek} ${dayOfMonth} ${month}`;

    // Update modal content with activity details
    document.getElementById('modalTitle').innerText = activityDetails.activity_name;
    document.getElementById('activityName').innerText = activityDetails.activity_name;
    document.getElementById('activityDate').innerText = formattedDate;
    document.getElementById('activityLocation').innerText = activityDetails.activity_location;
    document.getElementById('activityAddress').innerText = activityDetails.activity_address;
    document.getElementById('activityPrice').innerText = activityDetails.activity_price;
    document.getElementById('activityDescription').innerText = activityDetails.activity_description;
}



// Function to show the modal
function showModal() {
    $('#activityDetailsModal').modal('show');
}

    </script>


<br><br><br><br><br><br><br>    

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

</body>
                    <!-- The modal -->
                    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <!-- Include the form for editing profile details -->
                            <?php include("editprofile-form.php"); ?>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- The self-assessment modal -->
                    <div class="modal fade" id="selfAssessmentModal" tabindex="-1" aria-labelledby="selfAssessmentModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="selfAssessmentModalLabel">Self Assessment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>

                          <div class="modal-body">
                            <form id="selfAssessmentForm">
                              <div class="mb-3">
                                <label for="experience" class="form-label"><b>Describe your badminton experience.</b></label>
                                <input type="range" class="form-control" id="experience" name="experience" min="1" max="7" value="<?php echo $experience_skill; ?>" step="1" oninput="updateExperience(this.value)">
                                <span id="experience-text">I want to learn to play badminton. I am still learning to hit my shots without missing.</span>
                              </div>
                              <hr>
                              <div class="mb-3">
                                <label for="underhand" class="form-label"><b>How's your underhand strokes?</b></label>
                                <input type="range" class="form-control" id="underhand" name="underhand" min="1" max="7" value="<?php echo $underhand_skill; ?>" step="1" oninput="updateUnderhand(this.value)">
                                <span id="underhand-text">I can serve, but inconsistent in both getting the shuttle to the right place and consistency.</span>
                              </div>
                              <hr>
                              <div class="mb-3">
                                <label for="overhead" class="form-label"><b>How's your overhead strokes?</b></label>
                                <input type="range" class="form-control" id="overhead" name="overhead" min="1" max="7" value="<?php echo $overhead_skill; ?>" step="1" oninput="updateOverhead(this.value)">
                                <span id="overhead-text">I can serve, but inconsistent in both getting the shuttle to the right place and consistency.</span>
                              </div>
                              <hr>
                              <div class="mb-3">
                                <label for="movement" class="form-label"><b>How's your movement strokes?</b></label>
                                <input type="range" class="form-control" id="movement" name="movement" min="1" max="7" value="<?php echo $movement_skill; ?>" step="1" oninput="updateMovement(this.value)">
                                <span id="movement-text">I am able to anticipate where the shuttle will land but not able to consistently reach in time to return the shot.</span>
                              </div>
                              <hr>
                              <div class="mb-3">
                                <label for="smashes" class="form-label"><b>How's your smashes, drives and net plays?</b></label>
                                <input type="range" class="form-control" id="smashes" name="smashes" min="1" max="7" value="<?php echo $smashes_skill; ?>" step="1" oninput="updateSmashes(this.value)">
                                <span id="smashes-text">I'm not able to do smashes yet. I do not know what drive and net play are.</span>
                              </div>
                              <hr>
                              <div class="mb-3">
                                <label for="tactics" class="form-label"><b>How's your game knowledge and tactics?</b></label>
                                <input type="range" class="form-control" id="tactics" name="tactics" min="1" max="7" value="<?php echo $tactics_skill; ?>" step="1" oninput="updateTactics(this.value)">
                                <span id="tactics-text">I'm not able to do smashes yet. I do not know what drive and net play are.</span>
                              </div>
                            </form>
                          </div>

                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="submitAssessment">Submit</button>
                          </div>
                        </div>
                      </div>
                    </div>
</html>