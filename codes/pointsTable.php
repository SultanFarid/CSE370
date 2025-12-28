<?php
session_start();
require_once 'dbconnect.php';

// GATEKEEPER
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

// Check If Head Coach
$is_head_coach = false;
if ($user_role === 'coach') {
    $coach_query = mysqli_query($conn, "SELECT Coach_Type FROM coach WHERE Coach_ID = '$user_id'");
    $coach_data = mysqli_fetch_assoc($coach_query);
    if ($coach_data && $coach_data['Coach_Type'] === 'Head Coach') {
        $is_head_coach = true;
    }
}

// Connect to tournament_db database
$tournament_conn = mysqli_connect("localhost", "root", "", "tournament_db");

// Fetch Standings
$query = "SELECT * FROM league_standings ORDER BY Points DESC, Matches_Won DESC";
$result = mysqli_query($tournament_conn, $query);

$standings = [];
while ($row = mysqli_fetch_assoc($result)) {
    $row['Played'] = $row['Matches_Won'] + $row['Matches_Drawn'] + $row['Matches_Lost'];
    $standings[] = $row;
}

// Check Next Match
$match_status_query = "SELECT Match_id, Match_status, Opponent FROM fixtures 
                       WHERE Match_status IN ('Scheduled', 'Published') 
                       ORDER BY Match_date ASC LIMIT 1";
$match_status_result = mysqli_query($tournament_conn, $match_status_query);
$next_match = mysqli_fetch_assoc($match_status_result);

$can_update = false;
$update_msg = "No scheduled matches found.";

if ($next_match) {
    if ($next_match['Match_status'] === 'Published') {
        $can_update = true;
        $update_msg = "Simulate match vs " . $next_match['Opponent'];
    } else {
        $update_msg = "Squad for BUFC vs " . $next_match['Opponent'] . " not published yet.";
    }
}

mysqli_close($tournament_conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>League Standings - BUFC</title>
    <link rel="stylesheet" href="pointsTable.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <nav class="sidebar-nav">
                <?php if ($user_role === 'coach'): ?>
                    <a href="coachProfile.php" class="nav-item">Profile</a>
                    <a href="mySquad.php" class="nav-item">My Squad</a>
                    <a href="medicalReport.php" class="nav-item">Medical Report</a>
                    <a href="trainingSessions.php" class="nav-item">Training Sessions</a>
                    <a href="nextMatchSquad.php" class="nav-item">Next Match Squad</a>
                    <a href="coaches.php" class="nav-item">Coaches</a>
                    <a href="pointsTable.php" class="nav-item active">Points Table</a>
                    <a href="scoutedPlayers.php" class="nav-item">Scouted Players</a>
                    <a href="fixtures.php" class="nav-item">Fixtures</a>
                <?php else: ?>
                    <a href="playerProfile.php" class="nav-item">Profile</a>
                    <a href="mySquad.php" class="nav-item">My Squad</a>
                    <a href="medicalReport.php" class="nav-item">Medical Report</a>
                    <a href="trainingSessions.php" class="nav-item">Training Sessions</a>
                    <a href="nextMatchSquad.php" class="nav-item">Next Match Squad</a>
                    <a href="coaches.php" class="nav-item">Coaches</a>
                    <a href="pointsTable.php" class="nav-item active">Points Table</a>
                    <a href="fixtures.php" class="nav-item">Fixtures</a>
                <?php endif; ?>
                <a href="logout.php" class="nav-item logout-link">Logout</a>
            </nav>
        </aside>

        <div class="main-content">
            <header class="top-header">
                <div class="brand-group">
                    <img src="images/bufc-logo.jpg" class="header-logo">
                    <div class="header-text-group">
                        <h1>League Standings</h1>
                        <p class="slogan">Inter-University Tournament 2025</p>
                    </div>
                </div>
            </header>

            <div class="squad-container fade-in">

                <?php if ($is_head_coach): ?>
                    <div class="action-bar-right">
                        <button id="updateTableBtn" class="btn-primary <?php echo $can_update ? '' : 'disabled'; ?>"
                            onclick="updateLeagueGeneration()" <?php echo $can_update ? '' : 'disabled'; ?>
                            title="<?php echo $update_msg; ?>">
                            UPDATE TABLE
                        </button>
                        <?php if (!$can_update): ?>
                            <p class="status-warning-text">⚠️ <?php echo $update_msg; ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="field-panel full-width-panel" style="padding: 0; overflow: hidden;">

                    <div class="table-header-custom">
                        <div class="header-title-section">
                            <h3>Current Standings</h3>
                            <p>Live updates after every match week</p>
                        </div>
                        <div class="legend-container">
                            <div class="legend-item"><span class="dot q"></span> Qualification</div>
                            <div class="legend-item"><span class="dot r"></span> Relegation</div>
                        </div>
                    </div>

                    <div class="responsive-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Pos</th>
                                    <th style="text-align: left;">Team Name</th>
                                    <th>MP</th>
                                    <th>W</th>
                                    <th>D</th>
                                    <th>L</th>
                                    <th>Pts</th>
                                    <th>Last 5</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $pos = 1;
                                foreach ($standings as $team):
                                    $is_bufc = ($team['Team_Name'] === 'BUFC');
                                    $row_class = $is_bufc ? 'highlight-row' : '';
                                    $pos_class = ($pos <= 4) ? 'status-q' : (($pos >= 14) ? 'status-r' : '');
                                    $team_json = htmlspecialchars(json_encode($team), ENT_QUOTES, 'UTF-8');
                                    ?>
                                    <tr class="clickable-row <?php echo $row_class; ?>"
                                        onclick='openTeamModal(<?php echo $team_json; ?>)'>
                                        <td>
                                            <div class="position-badge <?php echo $pos_class; ?>"><?php echo $pos; ?></div>
                                        </td>
                                        <td class="team-cell">
                                            <img src="images/teams/<?php echo $team['Team_Name']; ?>.png"
                                                class="team-mini-logo" onerror="this.src='images/default_team.png'">
                                            <span class="team-name"><?php echo $team['Team_Name']; ?></span>
                                        </td>
                                        <td><?php echo $team['Played']; ?></td>
                                        <td><?php echo $team['Matches_Won']; ?></td>
                                        <td><?php echo $team['Matches_Drawn']; ?></td>
                                        <td><?php echo $team['Matches_Lost']; ?></td>
                                        <td class="points-val-cell"><span
                                                class="points-pill"><?php echo $team['Points']; ?></span></td>
                                        <td>
                                            <div class="form-boxes">
                                                <?php for ($k = 0; $k < 5; $k++):
                                                    $r = rand(0, 2);
                                                    // 0=W, 1=D, 2=L
                                                    $letter = ($r == 0) ? 'W' : (($r == 1) ? 'D' : 'L');
                                                    $class = ($r == 0) ? 'w' : (($r == 1) ? 'd' : 'l');
                                                    ?>
                                                    <span class="form-box <?php echo $class; ?>"><?php echo $letter; ?></span>
                                                <?php endfor; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php $pos++; endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="teamModal" class="modal-overlay" onclick="closeModal()">
        <div class="modal-content fade-in" onclick="event.stopPropagation()">
            <button class="close-btn" onclick="closeModal()">&times;</button>
            <div class="modal-header">
                <img id="modalLogo" src="" class="modal-logo">
                <h2 id="modalTeamName"></h2>
            </div>
            <div class="modal-stats-grid">
                <div class="stat-box"><span class="label">Coach</span><span class="value" id="modalCoach"></span></div>
                <div class="stat-box"><span class="label">Captain</span><span class="value" id="modalCaptain"></span>
                </div>
                <div class="stat-box full"><span class="label">⭐ Best Player</span><span class="value highlight"
                        id="modalBestPlayer"></span></div>
                <div class="stat-box"><span class="label">⚽ Top Scorer</span><span class="value"
                        id="modalTopScorer"></span></div>
                <div class="stat-box"><span class="label">👟 Most Assists</span><span class="value"
                        id="modalAssist"></span></div>
            </div>
        </div>
    </div>

    <script src="pointsTable.js"></script>
</body>

</html>