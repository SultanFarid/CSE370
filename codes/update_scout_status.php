<?php
require_once('dbconnect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['user_id'];
    $status = $_POST['status'];

    // Check if ID exists first
    $check = mysqli_query($conn, "SELECT * FROM Scouted_Player WHERE Scouted_Player_ID = '$id'");
    if (mysqli_num_rows($check) == 0) {
        echo "Error: Player ID not found in database.";
        exit();
    }

    // Run Update
    $q = "UPDATE Scouted_Player SET Application_Status = '$status' WHERE Scouted_Player_ID = '$id'";
    
    if (mysqli_query($conn, $q)) {
        echo "success";
    } else {
        // Print the actual SQL error
        echo "SQL Error: " . mysqli_error($conn);
    }
}
?>