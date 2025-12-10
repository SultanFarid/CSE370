<?php
session_start();
require_once 'dbconnect.php';
header('Content-Type: application/json');

// 1. GATEKEEPER
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['user_role'];

// 2. CHECK PERMISSIONS (only coaches can delete)
if ($role != 'coach') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// 3. GET INPUT (JSON format)
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['session_id'])) {
    echo json_encode(['success' => false, 'message' => 'Session ID required']);
    exit();
}

$session_id = mysqli_real_escape_string($conn, $input['session_id']);

// 4. CHECK IF SESSION EXISTS
$check_query = "SELECT * FROM training_sessions WHERE Session_id = '$session_id'";
$check_result = mysqli_query($conn, $check_query);

if (mysqli_num_rows($check_result) == 0) {
    echo json_encode(['success' => false, 'message' => 'Session not found']);
    exit();
}

// 5. DELETE PARTICIPATION RECORDS FIRST (foreign key constraint)
$delete_participation = "DELETE FROM training_participation WHERE Session_id = '$session_id'";
if (!mysqli_query($conn, $delete_participation)) {
    echo json_encode(['success' => false, 'message' => 'Failed to delete participation records: ' . mysqli_error($conn)]);
    exit();
}

// 6. DELETE THE SESSION
$delete_session = "DELETE FROM training_sessions WHERE Session_id = '$session_id'";
if (!mysqli_query($conn, $delete_session)) {
    echo json_encode(['success' => false, 'message' => 'Failed to delete session: ' . mysqli_error($conn)]);
    exit();
}

// 7. SUCCESS
echo json_encode([
    'success' => true,
    'message' => 'Training session deleted successfully'
]);

mysqli_close($conn);
?>
