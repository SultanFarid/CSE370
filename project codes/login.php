<?php
session_start();
require_once('dbconnect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $identifier = $_POST['identifier'];  // This is the username OR email field
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    // Use the identifier for both username and email in the query
    $query = "SELECT * FROM users WHERE (user_email='$identifier' OR user_name = '$identifier') AND user_password='$password'";
    
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['user_name'];
        $_SESSION['user_email'] = $user['user_email'];

        if ($role == 'coach' && $user['user_type']=='coach') {
            header("Location: coachProfile.php");
            exit();
        } else if ($role == 'player' && $user['user_type']=='player') {
            header("Location: playerProfile.php");
            exit();
        } else if ($role == 'player' && $user['user_type']=='scouted') {
            header("Location: scoutedPlayerProfile.php");
            exit();
        } else {
            header("Location: login.html");
            exit();
        }
    } else {
        header("Location: login.html");
        exit();
    }
}
?>
