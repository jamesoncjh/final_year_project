<?php
require("connect.php");
session_start();

$email = $_POST['email'];
$password = $_POST['password'];

// Use prepared statement to prevent SQL injection
$stmt = $mysqli->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // No matching user found, redirect back to login page with error message
    header("Location: login.php?error=invalid");
    exit();
} else {
    $row = $result->fetch_assoc();
    $role = $row['role'];
    $_SESSION['role'] = $role;
    $_SESSION['isLogin'] = true;
    $_SESSION['username'] = $row['username'];
    $_SESSION['email'] = $row['email'];
    $_SESSION['password'] = $password;
    $_SESSION['userid'] = $row['user_id'];
    $_SESSION['name'] = $row['name'];
    date_default_timezone_set('Asia/Kuala_Lumpur');
    $timestamp = date('Y-m-d H:i:s');
    $user_id = $row['user_id'];
    $update_sql = "UPDATE users SET last_login = '$timestamp' WHERE user_id = $user_id";
    $mysqli->query($update_sql);
    
    // Redirect users based on their role
    if ($role == 'user') {
        header("Location: home.php");
    } else if ($role == 'admin') {
        header("Location: manageUser.php");
    }
}

$stmt->close();
$mysqli->close();
?>