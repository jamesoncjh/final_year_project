<?php
require("connect.php");
session_start();

$sql = "SELECT user_id, username, email, name, email, gender, age, profile_image, last_login, skill_level FROM users WHERE role = 'user'";

$result = $mysqli->query($sql);

if ($result === false) {
    die("Error: " . $mysqli->error);
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
                    <?php if (isset($_SESSION['isLogin']) && $_SESSION["isLogin"] === true) { ?>
                        <li><a href="profile.php">Profile</a></li>
                    <?php } ?>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role']=="admin"){ ?>
                         <li><a href="ManageUser.php">User</a></li>
                    <?php } ?>
                </ul>
            </nav><!-- .navbar -->
            <?php if (isset($_SESSION['isLogin']) && $_SESSION["isLogin"] === true) { ?>
                <a class="btn-book-a-table" href="logout.php">Logout</a>
            <?php } else { ?>
                <a class="btn-book-a-table" href="login.php">Login</a>
                <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
                <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>
            <?php } ?>
        </div>
    </header>

    <?php
    // Function to get skill level description based on skill level value
    function getSkillLevelDescription($skillLevel) {
        if ($skillLevel <= 1.9) {
            return "Beginner";
        } elseif ($skillLevel >= 2 && $skillLevel <= 3.9) {
            return "Intermediate";
        } else {
            return "Advanced";
        }
    }
    ?>

<section id="people" class="book-a-table">
    <div class="container" data-aos="zoom-out" data-aos-duration="1000">
        <div class="section-header">
            <p><span>Manage Users</span></p>
        </div>
    </div>
    <div class="container" data-aos="slide-up" data-aos-duration="800">
        <div class="row justify-content-center">
            <?php 
            $count = 0; // Initialize counter
            while ($row = $result->fetch_assoc()) {
                // Check if three users have been displayed in the current row
                if ($count % 3 == 0) {
                    echo '</div><div class="row justify-content-center">'; // Start a new row
                }
            ?>
                <div class="col-lg-4"> <!-- Change the column size here -->
                    <a href='profile.php?user_id=<?php echo $row['user_id']; ?>' class="member-custom" data-aos="fade-up">
                        <div class="member-container">
                            <div class="member-img">
                                <img src="<?php echo $row['profile_image']; ?>" class="img-fluid" alt="Profile Picture">
                            </div>
                            <div class="member-details">
                                <h6><strong><?php echo $row['name']; ?> <small>@<?php echo $row['username']; ?></small></strong></h6>
                                <p><?php echo $row['email']; ?></p>
                                <p><?php echo $row['gender']; ?></p>
                                <p><?php echo $row['skill_level']; ?> (<?php echo getSkillLevelDescription($row['skill_level']); ?>)</p>
                                <br>
                                <?php if ($_SESSION['role'] == "admin") { ?>
                                    <form id="deleteForm<?php echo $row['user_id']; ?>" action="deleteUser.php" method="post" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                                        <!-- Add the onclick event to call the confirmDelete function -->
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                <?php } ?>

                            </div>
                        </div>
                    </a>
                </div>
            <?php 
                $count++; // Increment the counter
            } 
            ?>
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

</body>

</html>
