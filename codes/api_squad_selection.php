<?php
// SUPPRESS ALL WARNINGS to ensure clean JSON output
error_reporting(0);
ini_set('display_errors', 0);

session_start();
require_once 'dbconnect.php';

// Force JSON response header
header('Content-Type: application/json');

// Helper to send clean error JSON
function sendError($msg, $details = null) {
    echo json_encode(['error' => $msg, 'details' => $details]);
    exit();
}

// GATEKEEPER
if (!isset($_SESSION['user_id'])) {
    sendError('Not authenticated');
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['user_role'];

// Check if Head Coach
if ($role !== 'coach') {
    sendError('Unauthorized access');
}

$coach_query = mysqli_query($conn, "SELECT Coach_Type FROM coach WHERE Coach_ID = '$user_id'");
$coach_data = mysqli_fetch_assoc($coach_query);

if (!$coach_data || $coach_data['Coach_Type'] !== 'Head Coach') {
    sendError('Only Head Coach can use AI selection');
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['match_id']) || !isset($input['players'])) {
    sendError('Missing required data (match_id or players)');
}

$match_id = $input['match_id'];
$players = $input['players'];

// Get match info
$tournament_conn = mysqli_connect("localhost", "root", "", "tournament_db");
if (!$tournament_conn) {
    sendError('Failed to connect to tournament DB');
}

$match_query = mysqli_query($tournament_conn, "SELECT * FROM fixtures WHERE Match_id = '$match_id'");
$match = mysqli_fetch_assoc($match_query);

if (!$match) {
    sendError('Match not found', 'Match ID: ' . $match_id);
}

$opponent = $match['Opponent']; 
$venue = strpos($match['Stadium'], 'BRAC University') !== false ? 'home' : 'away';

// Prepare Player List
$available_players = [];
foreach ($players as $player) {
    $available_players[] = [
        'id' => $player['User_ID'],
        'name' => $player['Name'],
        'position' => $player['Position'],
        'jersey' => $player['Jersey_No'], // Fixed
        'goals' => $player['total_goals'] ?: 0,
        'matches' => $player['total_matches'] ?: 0,
        'avg_training_score' => $player['avg_training_score'] ? round($player['avg_training_score'], 2) : 0,
        'avg_match_rating' => $player['avg_match_rating'] ? round($player['avg_match_rating'], 2) : 0
    ];
}

// PROMPT
$prompt = "You are a professional football coach AI. Select the best starting XI squad from the available players.

Formation: 4-3-3
Match: BUFC vs " . $opponent . " (" . $venue . ")

Requirements:
1. Select exactly 11 starters: 1 GK, 4 Defenders (LB, CB, CB, RB), 3 Midfielders (CM), 3 Strikers (LW, ST, RW).
2. Select exactly 9 substitutes.
3. Return ONLY valid JSON. No markdown.

Players: " . json_encode($available_players) . "

JSON Format:
{
  \"starting_xi\": {
    \"GK\": id, \"LB\": id, \"CB1\": id, \"CB2\": id, \"RB\": id,
    \"CM1\": id, \"CM2\": id, \"CM3\": id,
    \"LW\": id, \"ST\": id, \"RW\": id
  },
  \"substitutes\": [id, id, id, id, id, id, id, id, id]
}";

// Call Gemini API
$api_key = '#';
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $api_key;

$data = [
    'contents' => [
        ['parts' => [['text' => $prompt]]]
    ]
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

// SSL FIX FOR XAMPP/LOCALHOST
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    sendError('Curl Connection Error', curl_error($ch));
}

$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if ($http_code !== 200) {
    sendError('Gemini API Error (HTTP ' . $http_code . ')', $response);
}

curl_close($ch);

// Parse Response
$json = json_decode($response, true);

if (isset($json['candidates'][0]['content']['parts'][0]['text'])) {
    $raw = $json['candidates'][0]['content']['parts'][0]['text'];
    
    // Clean Markdown
    $raw = preg_replace('/^```json\s*/', '', $raw);
    $raw = preg_replace('/\s*```$/', '', $raw);
    $raw = trim($raw);
    
    echo $raw;
} else {
    sendError('Invalid AI Response Structure', $json);
}

mysqli_close($conn);
mysqli_close($tournament_conn);
?>