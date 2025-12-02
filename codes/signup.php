<?php
session_start();
require_once('dbconnect.php');

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Check if user already exists
    $check_query = "SELECT * FROM users WHERE Email='$email'";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        header("Location: login.html?error=user_exists");
        exit();
    } else {
        // Store signup data as separate session variables
        $_SESSION['signup_email'] = $email;
        $_SESSION['signup_password'] = $password;
        header("Location: scoutedPlayerRegistration.php");
        exit();
    }
} else {
    header("Location: login.html?error=missing_fields");
    exit();
}
?>