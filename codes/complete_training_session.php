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

// CHECK IF COACH
if ($role != 'coach') {
    echo json_encode(['success' => false, 'message' => 'Only coaches can complete sessions']);
    exit();
}

// GET JSON INPUT
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['session_id']) || !isset($input['players'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required data']);
    exit();
}

$session_id = mysqli_real_escape_string($conn, $input['session_id']);
$players = $input['players'];

// VERIFY THIS COACH IS ASSIGNED TO THIS SESSION
$verify_query = "SELECT COUNT(*) as count FROM training_participation 
                 WHERE Session_id = '$session_id' AND Coach_ID = '$user_id'";
$verify_result = mysqli_query($conn, $verify_query);
$verify_data = mysqli_fetch_assoc($verify_result);

if ($verify_data['count'] == 0) {
    echo json_encode(['success' => false, 'message' => 'You are not assigned to this session']);
    exit();
}

// CHECK IF ALREADY COMPLETED
$status_check = mysqli_query($conn, "SELECT Session_status FROM training_sessions WHERE Session_id = '$session_id'");
$status_data = mysqli_fetch_assoc($status_check);

if ($status_data['Session_status'] == 'Completed') {
    echo json_encode(['success' => false, 'message' => 'Session already completed']);
    exit();
}

// UPDATE TRAINING SESSION STATUS
$update_session = "UPDATE training_sessions SET Session_status = 'Completed' WHERE Session_id = '$session_id'";

if (mysqli_query($conn, $update_session)) {
    // UPDATE PLAYER SCORES
    $success = true;
    foreach ($players as $player) {
        $player_id = mysqli_real_escape_string($conn, $player['player_id']);
        $technical = mysqli_real_escape_string($conn, $player['technical_score']);
        $physical = mysqli_real_escape_string($conn, $player['physical_score']);
        $tactical = mysqli_real_escape_string($conn, $player['tactical_score']);
        $remarks = isset($player['remarks']) ? mysqli_real_escape_string($conn, $player['remarks']) : '';

        $update_participation = "UPDATE training_participation 
                                SET Technical_score = '$technical',
                                    Physical_score = '$physical',
                                    Tactical_score = '$tactical',
                                    Coach_remarks = '$remarks',
                                    participation_status = 'Attended'
                                WHERE Session_id = '$session_id' AND Player_ID = '$player_id'";

        if (!mysqli_query($conn, $update_participation)) {
            $success = false;
            break;
        }
    }

    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Training session completed successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating player scores']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error completing session: ' . mysqli_error($conn)]);
}

mysqli_close($conn);
?>