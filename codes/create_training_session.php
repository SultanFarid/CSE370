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

// CHECK if Head/Assistant Coach
if ($role != 'coach') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$coach_query = mysqli_query($conn, "SELECT Coach_Type FROM coach WHERE Coach_ID='$user_id'");
$coach_data = mysqli_fetch_assoc($coach_query);

if (!in_array($coach_data['Coach_Type'], ['Head Coach', 'Assistant Coach'])) {
    echo json_encode(['success' => false, 'message' => 'Only Head/Assistant coaches can create sessions']);
    exit();
}

// VALIDATE INPUT
if (
    !isset($_POST['session_date']) || !isset($_POST['session_time']) ||
    !isset($_POST['session_type']) || !isset($_POST['assigned_coach']) ||
    !isset($_POST['players'])
) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit();
}

$session_date = mysqli_real_escape_string($conn, $_POST['session_date']);
$session_time = mysqli_real_escape_string($conn, $_POST['session_time']);
$session_type = mysqli_real_escape_string($conn, $_POST['session_type']);
$assigned_coach = mysqli_real_escape_string($conn, $_POST['assigned_coach']);
$players = json_decode($_POST['players'], true);

if (empty($players)) {
    echo json_encode(['success' => false, 'message' => 'No players selected']);
    exit();
}

// CREATE TRAINING SESSION
$insert_session = "INSERT INTO training_sessions (Session_Type, Session_date, Session_time, Session_status) 
                   VALUES ('$session_type', '$session_date', '$session_time', 'Scheduled')";

if (mysqli_query($conn, $insert_session)) {
    $session_id = mysqli_insert_id($conn);

    // ASSIGN PLAYERS TO SESSION
    $success = true;
    foreach ($players as $player_id) {
        $player_id = mysqli_real_escape_string($conn, $player_id);

        $insert_participation = "INSERT INTO training_participation 
                                (Session_id, Player_ID, Coach_ID, participation_status) 
                                VALUES ('$session_id', '$player_id', '$assigned_coach', 'Scheduled')";

        if (!mysqli_query($conn, $insert_participation)) {
            $success = false;
            break;
        }
    }

    if ($success) {
        echo json_encode([
            'success' => true,
            'message' => 'Training session created successfully',
            'session_id' => $session_id
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error assigning players to session']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error creating session: ' . mysqli_error($conn)]);
}

mysqli_close($conn);
?>