<?php
// pointsTable_action.php
header('Content-Type: application/json');

session_start();
require_once 'dbconnect.php';

// GATEKEEPER
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'coach') {
     header('Location: login.html');
    exit();
}

$user_id = $_SESSION['user_id'];
$coach_q = mysqli_query($conn, "SELECT Coach_Type FROM coach WHERE Coach_ID = '$user_id'");
$coach_d = mysqli_fetch_assoc($coach_q);

if (!$coach_d || $coach_d['Coach_Type'] !== 'Head Coach') {
    echo json_encode(['success' => false, 'message' => 'Only Head Coach can update table']);
    exit();
}

// CONNECT TO tournament_db database
$t_conn = mysqli_connect("localhost", "root", "", "tournament_db");
if (!$t_conn) {
    echo json_encode(['success' => false, 'message' => 'Tournament DB Connection Failed']);
    exit();
}

if (isset($_GET['action']) && $_GET['action'] === 'update_table') {
    $q = "SELECT Match_id, Opponent, Stadium FROM fixtures WHERE Match_status = 'Published' LIMIT 1";
    $res = mysqli_query($t_conn, $q);
    $match = mysqli_fetch_assoc($res);

    if (!$match) {
        echo json_encode(['success' => false, 'message' => 'No Published match found']);
        exit();
    }

    $match_id = $match['Match_id'];
    $opponent = $match['Opponent'];

    $is_home = (strpos($match['Stadium'], 'BRAC') !== false);
    $bufc_goals = $is_home ? rand(1, 4) : rand(0, 3);
    $opp_goals = rand(0, 3);

    $status = 'Draw';
    if ($bufc_goals > $opp_goals)
        $status = 'Won';
    elseif ($bufc_goals < $opp_goals)
        $status = 'Lost';

    $score_str = "$bufc_goals-$opp_goals";

    mysqli_query($t_conn, "UPDATE fixtures SET Match_status = '$status' WHERE Match_id = $match_id");

    // Initial insert with TBD
    mysqli_query($t_conn, "INSERT INTO generates (Match_id, Team_Name, Score) 
                       VALUES ($match_id, 'BUFC', '$score_str')
                       ON DUPLICATE KEY UPDATE Score='$score_str'");

    mysqli_query($t_conn, "INSERT INTO match_results (Match_id, MVP) 
                       VALUES ($match_id, 'TBD')
                       ON DUPLICATE KEY UPDATE MVP='TBD'");

    // Update BUFC Standings
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

    // Update Opponent Standings
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

    // 1. Calculate Ratings for BUFC Players
    $squad_data = [];
    $squad_check = mysqli_query($conn, "SELECT Regular_Player_ID FROM plays_in WHERE Match_id = $match_id");

    if ($squad_check) {
        while ($player = mysqli_fetch_assoc($squad_check)) {
            $pid = $player['Regular_Player_ID'];
            // Base rating 6.0 - 7.5
            $base_rating = rand(60, 75) / 10;
            $squad_data[$pid] = ['goals' => 0, 'rating' => $base_rating];

            // Increment Matches Played
            mysqli_query($conn, "UPDATE regular_player SET Matches_Played = Matches_Played + 1 WHERE Regular_Player_ID = $pid");
        }
    }

    // 2. Distribute Goals to BUFC Players
    if ($bufc_goals > 0 && !empty($squad_data)) {
        $scorers_query = "SELECT rp.Regular_Player_ID FROM regular_player rp
            JOIN plays_in pi ON rp.Regular_Player_ID = pi.Regular_Player_ID
            JOIN player p ON rp.Regular_Player_ID = p.Player_ID
            WHERE pi.Match_id = $match_id AND p.Position != 'Goalkeeper'";

        $scorers_res = mysqli_query($conn, $scorers_query);
        $eligible_scorers = [];
        while ($row = mysqli_fetch_assoc($scorers_res)) {
            $eligible_scorers[] = $row['Regular_Player_ID'];
        }

        if (!empty($eligible_scorers)) {
            for ($i = 0; $i < $bufc_goals; $i++) {
                $random_index = array_rand($eligible_scorers);
                $scorer_id = $eligible_scorers[$random_index];

                if (isset($squad_data[$scorer_id])) {
                    $squad_data[$scorer_id]['goals'] += 1;
                    $squad_data[$scorer_id]['rating'] += 1.5;
                    if ($squad_data[$scorer_id]['rating'] > 10)
                        $squad_data[$scorer_id]['rating'] = 10;
                }
                mysqli_query($conn, "UPDATE regular_player SET Goals_Scored = Goals_Scored + 1 WHERE Regular_Player_ID = $scorer_id");
            }
        }
    }

    // Save Ratings & Find Best BUFC Player
    $highest_rating = 0;
    $best_bufc_player_id = null;

    foreach ($squad_data as $pid => $stats) {
        $final_rating = $stats['rating'];
        $goals = $stats['goals'];

        mysqli_query($conn, "UPDATE plays_in 
                             SET Rating = $final_rating, 
                                 Goals_Scored = $goals 
                             WHERE Regular_Player_ID = $pid AND Match_id = $match_id");

        if ($final_rating > $highest_rating) {
            $highest_rating = $final_rating;
            $best_bufc_player_id = $pid;
        }
    }

    // DECIDE FINAL MVP (The Logic is if my team wins then most rated player is the mvp if opponent wins then their captain)
    $final_mvp_name = "TBD";

    if ($status == 'Lost') {
        // OPPONENT WON -> MVP is Opponent Captain
        $cap_query = mysqli_query($t_conn, "SELECT Captain FROM league_standings WHERE Team_Name = '$opponent'");
        $cap_row = mysqli_fetch_assoc($cap_query);
        if ($cap_row) {
            $final_mvp_name = $cap_row['Captain'];
        } else {
            $final_mvp_name = $opponent . " Captain"; // Fallback
        }
    } else {
        // BUFC WON OR DRAW -> MVP is Best Rated BUFC Player
        if ($best_bufc_player_id) {
            $name_q = mysqli_query($conn, "SELECT Name FROM users WHERE User_ID = $best_bufc_player_id");
            $name_row = mysqli_fetch_assoc($name_q);
            if ($name_row) {
                $final_mvp_name = $name_row['Name'];
            }
        }
    }

    // Update MVP in Database
    mysqli_query($t_conn, "UPDATE match_results SET MVP = '$final_mvp_name' WHERE Match_id = $match_id");
    $other_teams = [];
    $other_teams_q = mysqli_query($t_conn, "SELECT Team_Name FROM league_standings WHERE Team_Name NOT IN ('BUFC', '$opponent')");
    while ($row = mysqli_fetch_assoc($other_teams_q)) {
        $other_teams[] = $row['Team_Name'];
    }

    shuffle($other_teams);

    for ($i = 0; $i < count($other_teams) - 1; $i += 2) {
        $teamA = $other_teams[$i];
        $teamB = $other_teams[$i + 1];
        $result = rand(0, 2);

        if ($result == 0) { // A Wins
            mysqli_query($t_conn, "UPDATE league_standings SET Points = Points + 3, Matches_Won = Matches_Won + 1 WHERE Team_Name = '$teamA'");
            mysqli_query($t_conn, "UPDATE league_standings SET Points = Points + 0, Matches_Lost = Matches_Lost + 1 WHERE Team_Name = '$teamB'");
        } elseif ($result == 1) { // B Wins
            mysqli_query($t_conn, "UPDATE league_standings SET Points = Points + 0, Matches_Lost = Matches_Lost + 1 WHERE Team_Name = '$teamA'");
            mysqli_query($t_conn, "UPDATE league_standings SET Points = Points + 3, Matches_Won = Matches_Won + 1 WHERE Team_Name = '$teamB'");
        } else { // Draw
            mysqli_query($t_conn, "UPDATE league_standings SET Points = Points + 1, Matches_Drawn = Matches_Drawn + 1 WHERE Team_Name = '$teamA'");
            mysqli_query($t_conn, "UPDATE league_standings SET Points = Points + 1, Matches_Drawn = Matches_Drawn + 1 WHERE Team_Name = '$teamB'");
        }
    }

    echo json_encode([
        'success' => true,
        'match_result' => "BUFC $score_str $opponent"
    ]);
}
?>