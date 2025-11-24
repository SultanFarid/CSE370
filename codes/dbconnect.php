<?php
    $db_server = "localhost";
    $db_user = "root";
    $db_name = "bufc";
    $db_pass = "";
    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

    if (!$conn) {
        die("Connection failed! Try Again..." . mysqli_connect_error());
    }
?>