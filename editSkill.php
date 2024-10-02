<?php
    // Include database connection and session handling code
    require("connect.php");
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['isLogin']) || $_SESSION["isLogin"] !== true) {
        // Redirect to login page if not logged in
        header("Location: login.php");
        exit;
    }

    // Fetch user details from the form
    $experience = $_POST['experience'];
    $overhead = $_POST['overhead'];
    $underhand = $_POST['underhand'];
    $tactics = $_POST['tactics'];
    $smashes = $_POST['smashes'];
    $movement = $_POST['movement'];
    $userId = $_SESSION['userid'];

     // Calculate overall skill level
    $overallSkill = round((float)($experience + $overhead + $underhand + $tactics + $smashes + $movement) / 6, 1);

    // Update skill levels in the database
    $sql = "UPDATE users SET experience_skill = ?, overhead_skill = ?, underhand_skill = ?, tactics_skill = ?, smashes_skill = ?, movement_skill = ?, skill_level = ? WHERE user_id = ?";
    $stmt = $mysqli->prepare($sql);

    // Error handling for preparing the SQL statement
    if (!$stmt) {
        die('Error preparing statement: ' . $mysqli->error);
    }

    // Bind parameters to the prepared statement
    $stmt->bind_param("dddddddi", $experience, $overhead, $underhand, $tactics, $smashes, $movement, $overallSkill, $userId);


    // Execute the prepared statement
    if ($stmt->execute()) {
        // Skill levels updated successfully
        $_SESSION['success_message'] = "Skill levels updated successfully";

        // Save the self-assessment values in session variables
        $_SESSION['experience_skill'] = $experience;
        $_SESSION['overhead_skill'] = $overhead;
        $_SESSION['underhand_skill'] = $underhand;
        $_SESSION['tactics_skill'] = $tactics;
        $_SESSION['smashes_skill'] = $smashes;
        $_SESSION['movement_skill'] = $movement;
        $_SESSION['skill_level'] = $overallSkill;

        echo '<script>window.alert("Skill levels updated successfully"); window.location.href="profile.php";</script>';
        exit; // Add exit to prevent further execution
    } else {
        // Error updating skill levels
        $_SESSION['error_message'] = "Error updating skill levels: " . $stmt->error;
    }

    // Close the prepared statement
    $stmt->close();
?>
