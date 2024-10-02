<?php
require("connect.php");

$locationName = $_POST['location-name'];
$locationAddress = $_POST['location-address'];
$locationDistrict = $_POST['location-district'];
$locationState = $_POST['location-state'];
$locationLat = $_POST['location-lat'];
$locationLng = $_POST['location-lng'];
$sport = $_POST['sport']; // Get the selected sport type

// Check the sport type and insert into the corresponding table
if ($sport === 'badminton') {
    $sql = "INSERT INTO badminton_location (badminton_location_name, badminton_location_address,badminton_location_district, badminton_location_state, badminton_lat, badminton_long) VALUES ('$locationName', '$locationAddress', '$locationDistrict', '$locationState', '$locationLat', '$locationLng')";
} elseif ($sport === 'basketball') {
    $sql = "INSERT INTO basketball_location (basketball_location_name, basketball_location_address, basketball_location_district, basketball_location_state, basketball_lat, basketball_long) VALUES ('$locationName', '$locationAddress', '$locationDistrict', '$locationState', '$locationLat', '$locationLng')";
}

if($mysqli->query($sql)){
    echo ("<script LANGUAGE='JavaScript'>
    window.alert('Location inserted successfully.');
    window.location.href='venue.php?state={$state}';
    </script>");
} else {
    echo "Error: " . $mysqli->error;
}
?>
