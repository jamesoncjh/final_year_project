﻿<?php
    require("connect.php");
    session_start();
    
    // Check if there's an error message in the URL
    $error_message = isset($_GET['error']) && $_GET['error'] == 'invalid' ? "You have entered the incorrect email/password." : '';
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
        </ul>
      </nav><!-- .navbar -->

      <a class="btn-book-a-table" href="login.php">Login</a>
      <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
      <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>

    </div>
  </header><!-- End Header -->

  <br><br><br>

<main id="main">
    <section id="login" class="book-a-table">
        <div class="container" data-aos="zoom-in" data-aos-duration="700">
            <div class="section-header">
                <p><span>Login</span></p>
            </div>

            <!-- Form Start-->

            <div class="container">
                <div class="row justify-content-center align-items-center">
                    <div class="col-md-6 col-lg-5">
                        <div class="card">
                            <div class="card-body">
                                <!-- Display error message if present -->
                                <?php if ($error_message): ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?php echo $error_message; ?>
                                    </div>
                                <?php endif; ?>
                                <form method="POST" action="VerifyLogin.php" class="needs-validation" novalidate>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email address<strong> *</strong></label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                        <div class="invalid-feedback">Please enter a valid email address.</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password<strong> *</strong></label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                        <div class="invalid-feedback">Please enter your password.</div>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Login</button>
                                </form>
                            </div>
                            <div class="card-footer">
                                <p>Don't have an account? <a href="signup.php">Register here</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

 <br><br>
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