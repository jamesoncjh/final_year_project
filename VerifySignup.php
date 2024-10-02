<?php
    session_start();

    require ("connect.php");

    $username = $_POST['username'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $role = 'user';
    $experience_skill = 1;
    $underhand_skill = 1;
    $overhead_skill = 1;
    $movement_skill = 1;
    $smashes_skill = 1;
    $tactics_skill = 1;
    $defaultProfileImage = "assets/img/default-image.jpg"; // Path to the default profile image
    
    $sql = "SELECT username FROM users";
    $flag=true;
    $result = $mysqli->query($sql);

    while($row = $result -> fetch_array(MYSQLI_NUM)){
        if($username==$row[0]){
            echo ("<script LANGUAGE='JavaScript'>
            window.alert('Your username has been used. Please try another username');
            window.location.href='Signup.php';
            </script>");
            $flag=false;
        }
    }

        if($email==$row[0]){
            echo ("<script LANGUAGE='JavaScript'>
            window.alert('Your email has been used. Please try another email');
            window.location.href='Signup.php';
            </script>");
            $flag=false;
        }

        // Validate Email
        if(empty($email)) {
            $errors[] = "Please enter your email";
        } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Please enter a valid email address";
        }

        //check password and confirm password
        if($password!=$confirmPassword){
            echo ("<script LANGUAGE='JavaScript'>
            window.alert('Password and Confirm Password does not match. Please try again.');
            window.location.href='Signup.php';
            </script>");
            $flag=false;
        }

        if(strlen($password) < 8){
            echo ("<script LANGUAGE='JavaScript'>
            window.alert('Password must be at least 8 characters long.');
            window.location.href='Signup.php';
            </script>");
            $flag=false;
        }

        if($age<13 || $age>120){
            echo ("<script LANGUAGE='JavaScript'>
            window.alert('You must be atleast 13 years old.');
            window.location.href='Signup.php';
            </script>");
            $flag=false;
        }

        // Insert the new user into the database with default profile picture
        if ($flag) {
            // Insert the user data into the database
            $sql = "INSERT INTO users(`username`, `email`, `password`, `name`, `gender`, `age`,`experience_skill`, `overhead_skill`, `underhand_skill`, `tactics_skill`, `smashes_skill`, `movement_skill`, `role`, `profile_image`) VALUES 
                    ('$username', '$email','$password','$name', '$gender', '$age','$experience_skill', '$overhead_skill', '$underhand_skill', '$tactics_skill', '$smashes_skill', '$movement_skill', '$role', '$defaultProfileImage')";
            $mysqli->query($sql);
            // Redirect to the login page after successful registration
            header("Location: Login.php");
        }
?>

