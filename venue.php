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
    <link href="img/fcuclogo.png" rel="icon">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Amatic+SC:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet">
    <link href="assets/css/find.css" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
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
                    <li class="dropdown"><a href="venue.php"><span>Venue</span></a></li>
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
    </header><!-- End Header -->

    <section id="venue" class="book-a-table">
        <div class="container" data-aos="zoom-out" data-aos-duration="1000">
            <div class="section-header">
                <p><span>Venue</span></p>
            </div> 
        </div>

<!-- Filter Form Start -->
<div class="container">
    <div class="row justify-content-center mb-4">
        <div class="col-md-6">
            <form method="GET" action="venue.php" class="row gy-2 gy-md-0 align-items-center justify-content-center"> <!-- Updated class -->
                <div class="col-md-5">
                    <select class="form-select" name="sport">
                        <option value="">Select Sport</option>
                        <option value="badminton">Badminton</option>
                        <option value="basketball">Basketball</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Filter Form End -->


        <?php if (isset($_SESSION['isLogin']) && $_SESSION["isLogin"]===true && isset($_SESSION['role']) && $_SESSION['role']=='admin') { ?>
            <div class="container">
                <div class="row">
                    <div class="col d-flex justify-content-center">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#newCourtModal" class="btn btn-primary mt-3 px-xxl-4">ADD</button>
                    </div>
                </div>
            </div>
        <?php } ?>

        <!-- Modal Start -->
        <div class="modal fade" id="newCourtModal" tabindex="-1" aria-labelledby="newCourtModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newCourtModalLabel">Insert New Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form  action="InsertLocation.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="sport" class="form-label">Sport</label>
                        <select class="form-select" id="sport" name="sport">
                            <option value="badminton">Badminton</option>
                            <option value="basketball">Basketball</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="location-name" class="form-label">Location Name</label>
                        <input type="text" class="form-control" id="location-name" name="location-name" required>
                    </div>
                    <div class="mb-3">
                        <label for="location-address" class="form-label">Address</label>
                        <textarea class="form-control" id="location-address" name="location-address" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="location-district" class="form-label">District</label>
                        <input type="text" class="form-control" id="location-district" name="location-district" placeholder="Klang" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="state" class="form-label">State</label>
                        <select class="form-select" id="state" name="state">
                            <option value="selangor">Selangor</option>
                            <option value="kualalumpur">W.P Kuala Lumpur</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="location-lat" class="form-label">Latitude</label>
                        <textarea class="form-control" id="location-lat" name="location-lat" placeholder="3.072193200492175" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="location-lng" class="form-label">Longitude</label>
                        <textarea class="form-control" id="location-lng" name="location-lng" placeholder="101.48991852682644" required></textarea>
                    </div>
                    <input type="hidden" name="state" value="<?php echo $state ?>">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
                </div>
            </div>
        </div>
        <?php
        // Check for error parameter
        if(isset($_GET['error']) && $_GET['error'] == 'insert') {
            // Output JavaScript to open modal on page load
            echo '<script>
                    $(document).ready(function() {
                        $("#newCourtModal").modal("show");
                    });
                </script>';
        }
        ?>



        <!-- Filter List Result Start -->
        <div class="container">
            <div class="row mb-0 mt-0">
                <div class="col-md-7">
                    <div id="map" style="width: 100%; height: 500px"></div>
                </div>
                <div class="col-md-4">
                <?php
                        // Check if the state parameter is set in the URL
                    if(isset($_GET['state'])){
                        // Retrieve and decode the state parameter from the URL
                        $state = urldecode($_GET['state']);
                        // Construct the SQL query to filter locations based on the selected state
                        $sql = "SELECT * FROM badminton_location WHERE badminton_location_state = '$state' ORDER BY badminton_location_name";
                    } else {
                        // If state parameter is not set, show all locations
                        $sql = "SELECT * FROM badminton_location ORDER BY badminton_location_name";
                    }

                    // Execute the SQL query to retrieve locations
                    $result = $mysqli->query($sql);

                    // Check if there are any errors during the query execution
                    if(!$result) {
                        // Output the error for debugging purposes
                        echo "Error: " . $mysqli->error;
                    } else {
                        // Fetch and display the locations
                        // Display the locations based on the retrieved result set
                    }
                ?>
                <?php
                    $state = '';

                    if(isset($_GET['state'])) {
                        $state = $_GET['state'];
                        if(isset($_GET['name'])) {
                            $name = $_GET['name'];
                            $sql = "SELECT * FROM badminton_location WHERE badminton_location_state = '$state' AND badminton_location_name LIKE '%$name%' ORDER BY badminton_location_name";
                        } else {
                            $sql = "SELECT * FROM badminton_location WHERE badminton_location_state = '$state' ORDER BY badminton_location_name";
                        }
                    } else {
                        // If state parameter is not set, show all locations
                        $sql = "SELECT * FROM badminton_location ORDER BY badminton_location_name";
                    }
                
                    $result = $mysqli->query($sql);
                    $badmintons = array();
                    $basketballs = array();

                    // Fetch badminton location information from the database
                    $sql_badminton = "SELECT * FROM badminton_location";
                    $result_badminton = $mysqli->query($sql_badminton);

                    if ($result_badminton->num_rows > 0) {
                        // Output data of each row
                        while ($row_badminton = $result_badminton->fetch_assoc()) {
                            $badminton = array(
                                'name' => $row_badminton["badminton_location_name"],
                                'address' => $row_badminton["badminton_location_address"],
                                'lat' => $row_badminton["badminton_lat"],
                                'lng' => $row_badminton["badminton_long"]
                            );
                            // Add badminton location information to the $badmintons array
                            array_push($badmintons, $badminton);
                        }
                    }

                    // Fetch basketball location information from the database
                    $sql_basketball = "SELECT * FROM basketball_location";
                    $result_basketball = $mysqli->query($sql_basketball);

                    if ($result_basketball->num_rows > 0) {
                        // Output data of each row
                        while ($row_basketball = $result_basketball->fetch_assoc()) {
                            $basketball = array(
                                'name' => $row_basketball["basketball_location_name"],
                                'address' => $row_basketball["basketball_location_address"],
                                'lat' => $row_basketball["basketball_lat"],
                                'lng' => $row_basketball["basketball_long"]
                            );
                            // Add basketball location information to the $basketballs array
                            array_push($basketballs, $basketball);
                        }
                    }

                    // Filter locations based on the sport selected
                    $filter = isset($_GET['sport']) ? $_GET['sport'] : '';

                    // Merge badminton and basketball locations into one array if either is not empty
                    if ($filter === 'badminton') {
                        $locations = $badmintons; // Assuming $badmintons contains badminton locations
                    } elseif ($filter === 'basketball') {
                        $locations = $basketballs; // Assuming $basketballs contains basketball locations
                    } else {
                        // If no button was clicked, show all locations
                        $locations = array_merge($badmintons, $basketballs);
                    }
                    // Output filtered locations
                    if (!empty($locations)) {
                        echo '<div class="card mb-5 mt-3" style="width: 500px; height: 500px">';
                            echo '<div class="card-body overflow-auto">';
                                foreach ($locations as $location) {
                                    // Output location information
                                    echo '<div class="row">';
                                        echo '<h5 class="card-title">' . $location['name'] . '</h5>';
                                    echo '</div>';

                                    echo '<div class="row" style="margin-bottom: 10px;">';
                                        echo '<p class="card-text">' . $location['address'] . '</p>';
                                    echo '</div>';
                                    if (isset($_SESSION['isLogin']) && $_SESSION["isLogin"] === true && isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
                                        echo '<div class="row">';
                                            echo '<div class="col-md-4">';
                                                echo '<button class="btn btn-primary btn-full-width" onclick="showHospitalLocation(' . $location['lat'] . ', ' . $location['lng'] . ', \'' . $location['name'] . '\')">Show location</button>';
                                            echo '</div>';
                                            echo '<div class="col-md-4">';
                                                $link = 'https://www.google.com/maps/dir/?api=1&destination=' . $location['lat'] . ',' . $location['lng'];
                                                echo '<button class="btn btn-secondary btn-full-width" onclick="getDirections(\'' . $link . '\')">Get Directions</button>';
                                            echo '</div>';
                                            // Check if the user is logged in and has an admin role
                                        
                                                // Display the delete button only for admin users
                                                echo '<div class="col-md-4">';
                                                    echo '<button class="btn btn-danger btn-full-width" onclick="deleteLocation(\'' . $location['name'] . '\', \'' . $filter . '\')">Delete</button>';
                                                echo '</div>';
                                        echo '</div>';
                                    }else{
                                        echo '<div class="row">';
                                            echo '<div class="col-md-6">';
                                                echo '<button class="btn btn-primary btn-full-width" onclick="showHospitalLocation(' . $location['lat'] . ', ' . $location['lng'] . ', \'' . $location['name'] . '\')">Show location</button>';
                                            echo '</div>';
                                            echo '<div class="col-md-6">';
                                                $link = 'https://www.google.com/maps/dir/?api=1&destination=' . $location['lat'] . ',' . $location['lng'];
                                                echo '<button class="btn btn-secondary btn-full-width" onclick="getDirections(\'' . $link . '\')">Get Directions</button>';
                                            echo '</div>';
                                        echo '</div>';
                                    }
                                    echo '<hr>';
                                }
                            echo '</div>';
                        echo '</div>';
                    } else {
                        echo '<p>No locations found.</p>';
                    }
                ?>
                </div>
            </div>
        </div>
        <!-- Filter List Result End-->
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

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>

    <script>
        function deleteLocation(name, sport) {
            // Assuming you want to confirm before deleting
            if (confirm("Are you sure you want to delete " + name + "?")) {
                // Perform deletion logic here
                // You need to send an AJAX request to the server to delete the location from the database
                $.ajax({
                    type: "POST",
                    url: "deleteLocation.php", // Replace "deleteLocation.php" with the actual path to your PHP script for deleting locations
                    data: { name: name, sport: sport }, // Pass both name and sport parameters
                    success: function(response) {
                        // Handle success response
                        alert(response); // Show success message or perform any other action
                        // You may also want to refresh the page or update the UI after successful deletion
                        location.reload(); // Reload the page after deletion
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error(xhr.responseText); // Log error message for debugging
                        alert("Error deleting location. Please try again."); // Show error message to the user
                    }
                });
            }
        }



        var map;

        // Initialize the map function
        function initMap() {
            // Set default coordinates if needed
            var defaultLat = 0;
            var defaultLon = 0;
            var defaultZoom = 13;

            // Create map with default coordinates and zoom level
            map = L.map('map').setView([defaultLat, defaultLon], defaultZoom);

            // Add tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: 'Map data � <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
                maxZoom: 18
            }).addTo(map);

            // Check if geolocation is available
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                console.log("Geolocation is not supported by this browser.");
            }
        }

        // Function to handle geolocation
        function showPosition(position) {
            var lat = position.coords.latitude;
            var lon = position.coords.longitude;

            // Set map view to user's location
            map.setView([lat, lon], 13);

            // Add marker for user's location
            L.marker([lat, lon]).addTo(map).bindPopup("You are here").openPopup();
        }

// Keep a reference to the currently displayed marker
var currentMarker = null;

// Function to show hospital location on map
function showHospitalLocation(lat, lon, name) {
    // Remove the current marker if it exists
    if (currentMarker !== null) {
        map.removeLayer(currentMarker);
    }

    // Set map view to the new location
    map.setView([lat, lon], 13);

    // Add a new marker for the hospital location
    currentMarker = L.marker([lat, lon]).addTo(map).openPopup();
}


        // Function to get directions
        function getDirections(address) {
            window.open(address);
        }

        // Initialize the map when the document is ready
        $(document).ready(function () {
            initMap();
        });
    </script>

</body>

</html>
