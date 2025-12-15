<?php
session_start();
require_once('dbconnect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $identifier = $_POST['identifier'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    $query = "SELECT * FROM users WHERE Email='$identifier' AND Password='$password'";
    
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        $_SESSION['user_id'] = $user['User_ID'];
        $_SESSION['user_name'] = $user['Name'];
        $_SESSION['user_email'] = $user['Email'];
        $_SESSION['user_role'] = $user['Role'];

        if ($role == 'coach' && $user['Role']=='coach') {
            header("Location: coachProfile.php");
            exit();
        } else if ($role == 'player' && ($user['Role'] == 'scouted_player' || $user['Role'] == 'regular_player')){
            header("Location: playerProfile.php");
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
