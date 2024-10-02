<?php 
    $mysqli = new mysqli("localhost", "root", "", "socialsports");

    if($mysqli===false){
        die("Error: could not connect.".$mysqli->connect_error);
    }

?>