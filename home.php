<?php
	require("connect.php");
	session_start();
    
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
  </header><!-- End Header -->

  <!-- ======= Home Section ======= -->
  <section id="home" class="hero d-flex align-items-center section-bg">
    <div class="container">
      <div class="row justify-content-between gy-5">
        <div class="col-lg-5 order-2 order-lg-1 d-flex flex-column justify-content-center align-items-center align-items-lg-start text-center text-lg-start">
          <h2 data-aos="fade-up">Connect with Others</h2>
          <p data-aos="fade-up" data-aos-delay="100">Unleash Your Game! 🏸🏀 Connect, Play, Win! 🌟</p>
          <div class="d-flex" data-aos="fade-up" data-aos-delay="200">
            <a href="activities.php" class="btn-book-a-table">Find Activities</a>
          </div>
        </div>
        <div class="col-lg-5 order-1 order-lg-2 text-center text-lg-start">
          <img src="assets/img/groupphoto2.png" class="img-fluid" alt="" data-aos="zoom-out" data-aos-delay="300">
        </div>
      </div>
    </div>
  </section><!-- End Hero Section -->

    <main id="main">

    
    <!-- ======= About Section ======= -->
    <section id="home" class="about">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>About Us</h2>
          <p>Learn More <span>About Us</span></p>
        </div>

        <div class="row gy-4">
          <div class="col-lg-7 position-relative about-img" style="background-image: url(assets/img/groupphoto3.jpg) ;" data-aos="fade-up" data-aos-delay="150">
          </div>
          <div class="col-lg-5 d-flex align-items-end" data-aos="fade-up" data-aos-delay="300">
            <div class="content ps-0 ps-lg-5">
              <p class="fst-italic">
                We're more than just a sports app – we're a community of enthusiasts, united by the love of the game. Our mission is to break down barriers, connect players, and bring sports lovers together. 
                Whether you're a seasoned athlete or just starting, we provide a platform where everyone is welcome.
              </p>
              <ul>
                <li><i class="bi bi-check2-all"></i> Simple and easy sports events organization, from pick-up games to tournaments.</li>
                <li><i class="bi bi-check2-all"></i> Caters to individuals of all ages, skill levels, and physical conditions.</li>
                <li><i class="bi bi-check2-all"></i> Foster strong and vibrant sports communities by connecting individuals with shared sporting interests.</li>
              </ul>
              <p>
                Join us in redefining the way we play, connect, and champion the spirit of sportsmanship. Let's make every game memorable!<br><br><br><br><br><br><br>
              </p>
            </div>
          </div>
        </div>

      </div>
    </section><!-- End About Section -->


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