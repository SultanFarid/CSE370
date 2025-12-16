<?php
session_start();
require_once 'dbconnect.php';
header('Content-Type: application/json');

// GATEKEEPER
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['user_role'];

// CHECK if coach
if ($role != 'coach') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// VALIDATE INPUT
if (
    !isset($_POST['session_id']) || !isset($_POST['session_date']) ||
    !isset($_POST['session_time']) || !isset($_POST['session_type']) ||
    !isset($_POST['assigned_coach']) || !isset($_POST['players'])
) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit();
}

$session_id = mysqli_real_escape_string($conn, $_POST['session_id']);
$session_date = mysqli_real_escape_string($conn, $_POST['session_date']);
$session_time = mysqli_real_escape_string($conn, $_POST['session_time']);
$session_type = mysqli_real_escape_string($conn, $_POST['session_type']);
$assigned_coach = mysqli_real_escape_string($conn, $_POST['assigned_coach']);
$players = json_decode($_POST['players'], true);

// VALIDATE PLAYERS
if (empty($players) || !is_array($players)) {
    echo json_encode(['success' => false, 'message' => 'Please select at least one player']);
    exit();
}

// CHECK IF SESSION EXISTS
$check_query = "SELECT * FROM training_sessions WHERE Session_id = '$session_id'";
$check_result = mysqli_query($conn, $check_query);

if (mysqli_num_rows($check_result) == 0) {
    echo json_encode(['success' => false, 'message' => 'Session not found']);
    exit();
}

// UPDATE TRAINING SESSION
$update_session = "UPDATE training_sessions 
                   SET Session_date = '$session_date',
                       Session_time = '$session_time',
                       Session_Type = '$session_type'
                   WHERE Session_id = '$session_id'";

if (!mysqli_query($conn, $update_session)) {
    echo json_encode(['success' => false, 'message' => 'Failed to update session: ' . mysqli_error($conn)]);
    exit();
}

// DELETE OLD PARTICIPATION RECORDS
$delete_old = "DELETE FROM training_participation WHERE Session_id = '$session_id'";
mysqli_query($conn, $delete_old);

// INSERT NEW PARTICIPATION RECORDS
$success_count = 0;
foreach ($players as $player_id) {
    $player_id = mysqli_real_escape_string($conn, $player_id);

    $insert_query = "INSERT INTO training_participation 
                     (Session_id, Player_ID, Coach_ID, participation_status) 
                     VALUES ('$session_id', '$player_id', '$assigned_coach', 'Scheduled')";

    if (mysqli_query($conn, $insert_query)) {
        $success_count++;
    }
}

// SUCCESS
if ($success_count == count($players)) {
    echo json_encode([
        'success' => true,
        'message' => 'Training session updated successfully',
        'players_updated' => $success_count
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Session updated but some players failed to assign'
    ]);
}

mysqli_close($conn);
?>