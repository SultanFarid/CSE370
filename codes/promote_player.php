<?php
session_start();
require_once('dbconnect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $player_id = $_POST['player_id'];
    $jersey = $_POST['jersey_no'];
    $salary = $_POST['salary'];
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];

    // CHECK UNIQUE JERSEY
    $check = mysqli_query($conn, "SELECT * FROM regular_player WHERE Jersey_No = '$jersey'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Error: Jersey Number $jersey is already taken!'); window.location='scoutedPlayers.php';</script>";
        exit();
    }

    mysqli_begin_transaction($conn);
    try {
        // Delete from Scouted
        mysqli_query($conn, "DELETE FROM Scouted_Player WHERE Scouted_Player_ID = '$player_id'");
        // Insert into Regular
        $insert = "INSERT INTO Regular_Player (Regular_Player_ID, Jersey_No, Goals_Scored, Matches_Played) VALUES ('$player_id', '$jersey', 0, 0)";
        mysqli_query($conn, $insert);
        // Update User Role & Contract
        $update = "UPDATE users SET Role = 'regular_player', salary = '$salary', contract_start_date = '$start', contract_end_date = '$end' WHERE User_ID = '$player_id'";
        mysqli_query($conn, $update);
        // Update Player Status
        mysqli_query($conn, "UPDATE Player SET Current_Injury_Status = 'Fit' WHERE Player_ID = '$player_id'");

        mysqli_commit($conn);
        echo "<script>alert('Player Promoted Successfully!'); window.location='mySquad.php';</script>";
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "<script>alert('Error Promoting Player.'); window.location='scoutedPlayers.php';</script>";
    }
}
?>