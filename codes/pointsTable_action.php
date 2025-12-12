<?php
// pointsTable_action.php
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');

session_start();
require_once 'dbconnect.php'; // Connection to BUFC (variable: $conn)

// 1. SECURITY CHECK
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'coach') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$user_id = $_SESSION['user_id'];
$coach_q = mysqli_query($conn, "SELECT Coach_Type FROM coach WHERE Coach_ID = '$user_id'");
$coach_d = mysqli_fetch_assoc($coach_q);

if (!$coach_d || $coach_d['Coach_Type'] !== 'Head Coach') {
    echo json_encode(['success' => false, 'message' => 'Only Head Coach can update table']);
    exit();
}

// 2. CONNECT TO TOURNAMENT DB
$t_conn = mysqli_connect("localhost", "root", "", "tournament_db");
if (!$t_conn) {
    echo json_encode(['success' => false, 'message' => 'Tournament DB Connection Failed']);
    exit();
}

if (isset($_GET['action']) && $_GET['action'] === 'update_table') {

    // ======================================================
    // PHASE A: SIMULATE THE MATCH RESULT
    // ======================================================

    // 1. Find the Published Match
    $q = "SELECT Match_id, Opponent, Stadium FROM fixtures WHERE Match_status = 'Published' LIMIT 1";
    $res = mysqli_query($t_conn, $q);
    $match = mysqli_fetch_assoc($res);

    if (!$match) {
        echo json_encode(['success' => false, 'message' => 'No Published match found']);
        exit();
    }

    $match_id = $match['Match_id'];
    $opponent = $match['Opponent'];

    // 2. Determine Score (Home advantage logic)
    $is_home = (strpos($match['Stadium'], 'BRAC') !== false);
    $bufc_goals = $is_home ? rand(1, 4) : rand(0, 3);
    $opp_goals = rand(0, 3);

    $status = 'Draw';
    if ($bufc_goals > $opp_goals)
        $status = 'Won';
    elseif ($bufc_goals < $opp_goals)
        $status = 'Lost';

    $score_str = "$bufc_goals-$opp_goals";

    // ======================================================
    // PHASE B: UPDATE TOURNAMENT DATA (Team Level)
    // ======================================================

    // 1. Update Match Status
    mysqli_query($t_conn, "UPDATE fixtures SET Match_status = '$status' WHERE Match_id = $match_id");

    // 2. Update Generates Table
    mysqli_query($t_conn, "INSERT INTO generates (Match_id, Team_Name, Score, MVP) 
                           VALUES ($match_id, 'BUFC', '$score_str', 'Pending')
                           ON DUPLICATE KEY UPDATE Score='$score_str'");

    // 3. Update BUFC Standings
    $pts = ($status == 'Won') ? 3 : (($status == 'Draw') ? 1 : 0);
    $w = ($status == 'Won') ? 1 : 0;
    $d = ($status == 'Draw') ? 1 : 0;
    $l = ($status == 'Lost') ? 1 : 0;

    mysqli_query($t_conn, "UPDATE league_standings 
                           SET Points = Points + $pts, 
                               Matches_Won = Matches_Won + $w, 
                               Matches_Drawn = Matches_Drawn + $d, 
                               Matches_Lost = Matches_Lost + $l
                           WHERE Team_Name = 'BUFC'");

    // 4. Update Opponent Standings
    $opt_pts = ($status == 'Lost') ? 3 : (($status == 'Draw') ? 1 : 0);
    $opt_w = ($status == 'Lost') ? 1 : 0;
    $opt_d = ($status == 'Draw') ? 1 : 0;
    $opt_l = ($status == 'Won') ? 1 : 0;

    mysqli_query($t_conn, "UPDATE league_standings 
                           SET Points = Points + $opt_pts, 
                               Matches_Won = Matches_Won + $opt_w, 
                               Matches_Drawn = Matches_Drawn + $opt_d, 
                               Matches_Lost = Matches_Lost + $opt_l
                           WHERE Team_Name = '$opponent'");


    // ======================================================
    // PHASE C: UPDATE PLAYER STATS (Individual Level)
    // ======================================================

    // 1. Update Matches Played for EVERYONE in the squad
    // We get the list of players who were set to 'Started' or 'Substituted' for this match
    $squad_check = mysqli_query($conn, "SELECT Regular_Player_ID FROM plays_in WHERE Match_id = $match_id");

    if ($squad_check) {
        while ($player = mysqli_fetch_assoc($squad_check)) {
            $pid = $player['Regular_Player_ID'];
            // Increment Matches Played in Profile
            mysqli_query($conn, "UPDATE regular_player SET Matches_Played = Matches_Played + 1 WHERE Regular_Player_ID = $pid");
        }
    }

    // 2. Distribute Goals (If BUFC Scored)
    if ($bufc_goals > 0) {
        // Fetch eligible scorers (Strikers/Midfielders/Defenders from the squad)
        // We exclude Goalkeepers generally for realism
        $scorers_query = "
            SELECT rp.Regular_Player_ID 
            FROM regular_player rp
            JOIN plays_in pi ON rp.Regular_Player_ID = pi.Regular_Player_ID
            JOIN player p ON rp.Regular_Player_ID = p.Player_ID
            WHERE pi.Match_id = $match_id
            AND p.Position != 'Goalkeeper'
        ";

        $scorers_res = mysqli_query($conn, $scorers_query);
        $eligible_scorers = [];

        while ($row = mysqli_fetch_assoc($scorers_res)) {
            $eligible_scorers[] = $row['Regular_Player_ID'];
        }

        // Randomly assign goals
        if (!empty($eligible_scorers)) {
            for ($i = 0; $i < $bufc_goals; $i++) {
                // Pick a random player ID from the eligible list
                $random_index = array_rand($eligible_scorers);
                $scorer_id = $eligible_scorers[$random_index];

                // Update Career Goals
                mysqli_query($conn, "UPDATE regular_player SET Goals_Scored = Goals_Scored + 1 WHERE Regular_Player_ID = $scorer_id");

                // Update Match Specific Goals
                mysqli_query($conn, "UPDATE plays_in SET Goals_Scored = Goals_Scored + 1 WHERE Regular_Player_ID = $scorer_id AND Match_id = $match_id");
            }
        }
    }

    // ======================================================
    // PHASE D: SIMULATE REST OF LEAGUE
    // ======================================================
    $other_teams_q = mysqli_query($t_conn, "SELECT Team_Name FROM league_standings WHERE Team_Name NOT IN ('BUFC', '$opponent')");

    while ($team = mysqli_fetch_assoc($other_teams_q)) {
        $r = rand(1, 100);
        $s_pts = 0;
        $s_w = 0;
        $s_d = 0;
        $s_l = 0;

        if ($r < 40) {
            $s_pts = 3;
            $s_w = 1;
        }      // 40% Win
        elseif ($r < 70) {
            $s_pts = 1;
            $s_d = 1;
        }  // 30% Draw
        else {
            $s_l = 1;
        }                          // 30% Loss

        $t_name = $team['Team_Name'];
        mysqli_query($t_conn, "UPDATE league_standings 
                               SET Points = Points + $s_pts, 
                                   Matches_Won = Matches_Won + $s_w, 
                                   Matches_Drawn = Matches_Drawn + $s_d, 
                                   Matches_Lost = Matches_Lost + $s_l
                               WHERE Team_Name = '$t_name'");
    }

    // Return Success
    echo json_encode([
        'success' => true,
        'match_result' => "BUFC $score_str $opponent"
    ]);
}
?>