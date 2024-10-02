<?php
require("connect.php"); // Include your database connection file

// Check if the user is logged in and is an admin
if (!(isset($_SESSION['isLogin']) && $_SESSION['isLogin'] === true && isset($_SESSION['role']) && $_SESSION['role'] == "admin")) {
    // Redirect the user if not logged in as admin
    header("Location: login.php");
    exit;
}


// Check if both the name and sport parameters are received from the AJAX request
if(isset($_POST['name'], $_POST['sport'])) {
    $name = $_POST['name'];
    $sport = $_POST['sport'];
    
    // Construct the SQL query to delete the location with the given name and sport
    $sql = "DELETE FROM {$sport}_location WHERE {$sport}_location_name = '$name'";
    
    // Execute the SQL query
    if($mysqli->query($sql) === TRUE) {
        // If deletion is successful, return success message
        echo "Location " . $name . " deleted successfully.";
    } else {
        // If an error occurs during deletion, return error message
        echo "Error deleting location: " . $mysqli->error;
    }
} else {
    // If name or sport parameters are missing, return error message
    echo "Name or sport parameter is missing.";
}
?>
