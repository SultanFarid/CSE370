<?php
session_start();
require_once 'dbconnect.php';

header('Content-Type: application/json');

// GATEKEEPER
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// VALIDATE INPUT (the session_id is provided in the url since training_sessions.js theke get_sessions_details.php?session_id hoye request ta ashtese)
if (!isset($_GET['session_id'])) {
    header("Location: login.html");
    exit();
}

$session_id = mysqli_real_escape_string($conn, $_GET['session_id']);

// GET SESSION DETAILS
$session_query = "SELECT * FROM training_sessions WHERE Session_id = '$session_id'";
$session_result = mysqli_query($conn, $session_query);

if (mysqli_num_rows($session_result) == 0) {
    echo json_encode(['success' => false, 'message' => 'Session not found']);
    exit();
}

$session = mysqli_fetch_assoc($session_result);

// GET PLAYERS IN THIS SESSION (Players those who participated in that training session)
$players_query = "SELECT u.User_ID as Player_ID, u.Name, p.Position, p.Current_Injury_Status,
                  tp.Technical_score, tp.Physical_score, tp.Tactical_score, 
                  tp.Coach_remarks, tp.participation_status, tp.Coach_ID
                  FROM training_participation tp
                  JOIN users u ON tp.Player_ID = u.User_ID
                  JOIN player p ON u.User_ID = p.Player_ID
                  WHERE tp.Session_id = '$session_id'
                  ORDER BY p.Position, u.Name";

$players_result = mysqli_query($conn, $players_query);
$players = [];

while ($player = mysqli_fetch_assoc($players_result)) {
    $players[] = $player;
}

$coach_id = null;
if (count($players) > 0) {
    $coach_id = $players[0]['Coach_ID'];
}

echo json_encode([
    'success' => true,
    'session' => $session,
    'players' => $players,
    'coach_id' => $coach_id
]);

mysqli_close($conn);
?>