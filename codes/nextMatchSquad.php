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

// Check if Head Coach
$is_head_coach = false;
if ($user_role === 'coach') {
    $coach_query = mysqli_query($conn, "SELECT Coach_Type FROM coach WHERE Coach_ID = '$user_id'");
    $coach_data = mysqli_fetch_assoc($coach_query);
    if ($coach_data && $coach_data['Coach_Type'] === 'Head Coach') {
        $is_head_coach = true;
    }
}

// API HANDLER
if (isset($_GET['action'])) {
    header('Content-Type: application/json');

    // GET NEXT MATCH (Scheduled OR Published)
    if ($_GET['action'] === 'get_next_match') {
        // since we are using two databases we'll connect to the tournament_db database
        $tournament_conn = mysqli_connect("localhost", "root", "", "tournament_db");

        // taking the most recent scheduled match as the next match
        $query = "SELECT * FROM fixtures 
                  WHERE Match_status IN ('Scheduled', 'Published') 
                  ORDER BY Match_date ASC, Match_time ASC 
                  LIMIT 1";

        $result = mysqli_query($tournament_conn, $query);

        if ($row = mysqli_fetch_assoc($result)) {
            echo json_encode(['success' => true, 'match' => $row]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No upcoming matches']);
        }

        mysqli_close($tournament_conn);
        exit();
    }

    // GET ELIGIBLE PLAYERS
    if ($_GET['action'] === 'get_eligible_players') {
        $query = "
            SELECT u.User_ID, u.Name, p.Position, rp.Jersey_No,
                SUM(pi.Goals_Scored) AS total_goals,
                COUNT(DISTINCT pi.Match_id) AS total_matches,
                ROUND((SELECT AVG((tp.Technical_score + tp.Physical_score + tp.Tactical_score) / 3)
                    FROM training_participation tp WHERE tp.Player_ID = rp.Regular_Player_ID), 1) AS avg_training_score,
                ROUND((SELECT AVG(pi2.Rating) FROM plays_in pi2 WHERE pi2.Regular_Player_ID = rp.Regular_Player_ID AND pi2.Rating IS NOT NULL), 1) AS avg_match_rating
            FROM regular_player rp
            JOIN player p ON rp.Regular_Player_ID = p.Player_ID
            JOIN users u ON p.Player_ID = u.User_ID
            LEFT JOIN plays_in pi ON rp.Regular_Player_ID = pi.Regular_Player_ID
            WHERE p.Current_Injury_Status = 'Fit'
            GROUP BY rp.Regular_Player_ID
            ORDER BY p.Position, u.Name
        ";
        $result = mysqli_query($conn, $query);

        $players = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $players[] = $row;
        }

        echo json_encode(['success' => true, 'players' => $players]);
        exit();
    }

    // PUBLISH SQUAD
    if ($_GET['action'] === 'publish_squad') {
        if (!$is_head_coach) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit();
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $match_id = intval($input['match_id']);

        // Connect to Tournament DB
        $tournament_conn = mysqli_connect("localhost", "root", "", "tournament_db");

        //Clear any existing players for this match
        mysqli_query($conn, "DELETE FROM plays_in WHERE Match_id = $match_id");

        // Insert Starting XI
        foreach ($input['starting_xi'] as $pos => $pid) {
            $pid = intval($pid);
            mysqli_query($conn, "INSERT INTO plays_in (Match_id, Regular_Player_ID, Status) VALUES ($match_id, $pid, 'Started')");
        }

        // Insert Substitutes
        foreach ($input['substitutes'] as $pid) {
            $pid = intval($pid);
            mysqli_query($conn, "INSERT INTO plays_in (Match_id, Regular_Player_ID, Status) VALUES ($match_id, $pid, 'Substituted')");
        }

        // Update Fixture Status to 'Published'
        $update_status = mysqli_query($tournament_conn, "UPDATE fixtures SET Match_status = 'Published' WHERE Match_id = $match_id");

        if ($update_status) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update match status']);
        }

        mysqli_close($tournament_conn);
        exit();
    }

    // UNPUBLISH SQUAD (Revert to Scheduled)
    if ($_GET['action'] === 'unpublish_squad') {
        if (!$is_head_coach) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit();
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $match_id = intval($input['match_id']);

        $tournament_conn = mysqli_connect("localhost", "root", "", "tournament_db");

        // Delete all players from Plays_In
        mysqli_query($conn, "DELETE FROM plays_in WHERE Match_id = $match_id");

        // Revert status to 'Scheduled'
        $update_status = mysqli_query($tournament_conn, "UPDATE fixtures SET Match_status = 'Scheduled' WHERE Match_id = $match_id");

        if ($update_status) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to revert match status']);
        }

        mysqli_close($tournament_conn);
        exit();
    }

    // GET PUBLISHED SQUAD VIEW
    if ($_GET['action'] === 'get_published_squad') {
        $match_id = intval($_GET['match_id']);

        $query = "
            SELECT u.User_ID, u.Name, p.Position, rp.Jersey_No, pi.Status
            FROM plays_in pi
            JOIN regular_player rp ON pi.Regular_Player_ID = rp.Regular_Player_ID
            JOIN player        p   ON rp.Regular_Player_ID = p.Player_ID
            JOIN users         u   ON p.Player_ID         = u.User_ID
            WHERE pi.Match_id = $match_id
        ";

        $result = mysqli_query($conn, $query);

        $starting_xi = [];
        $substitutes = [];

        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['Status'] === 'Started') {
                $starting_xi[] = $row;
            } else {
                $substitutes[] = $row;
            }
        }

        echo json_encode([
            'success' => true,
            'starting_xi' => $starting_xi,
            'substitutes' => $substitutes
        ]);

        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Next Match Squad - BUFC</title>
    <link rel="stylesheet" href="nextMatchSquad.css">
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
                    <a href="nextMatchSquad.php" class="nav-item active">Next Match Squad</a>
                    <a href="coaches.php" class="nav-item">Coaches</a>
                    <a href="pointsTable.php" class="nav-item">Points Table</a>
                    <a href="scoutedPlayers.php" class="nav-item">Scouted Players</a>
                    <a href="fixtures.php" class="nav-item">Fixtures</a>
                <?php else: ?>
                    <a href="playerProfile.php" class="nav-item">Profile</a>
                    <a href="mySquad.php" class="nav-item">My Squad</a>
                    <a href="medicalReport.php" class="nav-item">Medical Report</a>
                    <a href="trainingSessions.php" class="nav-item">Training Sessions</a>
                    <a href="nextMatchSquad.php" class="nav-item active">Next Match Squad</a>
                    <a href="coaches.php" class="nav-item">Coaches</a>
                    <a href="pointsTable.php" class="nav-item">Points Table</a>
                    <a href="fixtures.php" class="nav-item">Fixtures</a>
                <?php endif; ?>
                <a href="logout.php" class="nav-item logout-link">Logout</a>
            </nav>
        </aside>

        <div class="main-content">
            <header class="top-header">
                <div class="brand-group">
                    <img src="images/bufc-logo.jpg" alt="Logo" class="header-logo">
                    <div class="header-text-group">
                        <h1>NEXT MATCH SQUAD</h1>
                        <p class="slogan">Team Selection &amp; Strategy</p>
                    </div>
                </div>
            </header>

            <div class="squad-container fade-in">
                <div id="loadingIndicator" class="loading-indicator">
                    <div class="spinner"></div>
                    <p>Loading match data...</p>
                </div>

                <div id="noMatchMessage" class="empty-state" style="display: none;">
                    <span class="empty-icon">📅</span>
                    <h3>No Upcoming Match</h3>
                    <p>There are no scheduled fixtures at the moment.</p>
                </div>

                <div id="notPublishedMessage" class="locked-state" style="display: none;">
                    <span class="lock-icon">🔒</span>
                    <h3>Squad Not Published Yet</h3>
                    <p>The Head Coach hasn't published the squad for the next match.</p>
                </div>

                <div id="matchInfoCard" class="match-info-card" style="display: none;"></div>

                <div id="actionButtons" class="action-buttons" style="display: none;">
                    <button class="btn-ai" onclick="aiAutoSelect()">
                        <span class="btn-icon">🤖</span> AI Auto-Select
                    </button>
                    <button class="btn-reset" onclick="resetSelection()">
                        <span class="btn-icon">🔄</span> Reset Selection
                    </button>
                    <button id="publishBtn" class="btn-publish" onclick="publishSquad()" disabled>
                        <span class="btn-icon">✅</span> Publish Squad
                    </button>
                </div>

                <div id="unpublishContainer" class="action-buttons" style="display: none;">
                    <button class="btn-reset" onclick="unpublishSquad()"
                        style="border-color: #ef4444; color: #ef4444; background: #fff;">
                        <span class="btn-icon">🗑️</span> Unpublish Squad
                    </button>
                </div>

                <div id="selectionArea" class="drag-drop-layout" style="display: none;">
                    <div class="players-panel">
                        <div class="panel-header">
                            <h3>Available Players</h3>
                            <p class="player-count">
                                <span id="availableCount">0</span> players available
                            </p>
                        </div>

                        <div class="position-tabs">
                            <button class="pos-tab active" onclick="filterPlayersByPosition('all')">All</button>
                            <button class="pos-tab" onclick="filterPlayersByPosition('Goalkeeper')">GK</button>
                            <button class="pos-tab" onclick="filterPlayersByPosition('Defender')">DEF</button>
                            <button class="pos-tab" onclick="filterPlayersByPosition('Midfielder')">MID</button>
                            <button class="pos-tab" onclick="filterPlayersByPosition('Striker')">FWD</button>
                        </div>

                        <div id="playersList" class="players-list"></div>
                    </div>

                    <div class="field-panel">
                        <div class="field-header">
                            <h3>4-3-3 Formation</h3>
                            <p class="selection-count">
                                Starting XI: <span id="selectedCount">0</span>/11 |
                                Substitutes: <span id="subsCount">0</span>/9
                            </p>
                        </div>

                        <div class="football-field">
                            <div class="field-lines">
                                <div class="center-circle"></div>
                                <div class="center-line"></div>
                                <div class="penalty-box left"></div>
                                <div class="goal-box left"></div>
                                <div class="penalty-box right"></div>
                                <div class="goal-box right"></div>
                            </div>

                            <div class="tactical-grid">
                                <div class="position-col goalkeeper-col">
                                    <div class="position-slot drop-zone" data-position="GK" ondrop="drop(event)"
                                        ondragover="allowDrop(event)" ondragleave="dragLeave(event)">
                                        <div class="slot-label">GK</div>
                                    </div>
                                </div>

                                <div class="position-col defenders-col">
                                    <div class="position-slot drop-zone" data-position="LB" ondrop="drop(event)"
                                        ondragover="allowDrop(event)" ondragleave="dragLeave(event)">
                                        <div class="slot-label">LB</div>
                                    </div>
                                    <div class="position-slot drop-zone" data-position="CB1" ondrop="drop(event)"
                                        ondragover="allowDrop(event)" ondragleave="dragLeave(event)">
                                        <div class="slot-label">CB</div>
                                    </div>
                                    <div class="position-slot drop-zone" data-position="CB2" ondrop="drop(event)"
                                        ondragover="allowDrop(event)" ondragleave="dragLeave(event)">
                                        <div class="slot-label">CB</div>
                                    </div>
                                    <div class="position-slot drop-zone" data-position="RB" ondrop="drop(event)"
                                        ondragover="allowDrop(event)" ondragleave="dragLeave(event)">
                                        <div class="slot-label">RB</div>
                                    </div>
                                </div>

                                <div class="position-col midfielders-col">
                                    <div class="position-slot drop-zone" data-position="CM1" ondrop="drop(event)"
                                        ondragover="allowDrop(event)" ondragleave="dragLeave(event)">
                                        <div class="slot-label">CM</div>
                                    </div>
                                    <div class="position-slot drop-zone" data-position="CM2" ondrop="drop(event)"
                                        ondragover="allowDrop(event)" ondragleave="dragLeave(event)">
                                        <div class="slot-label">CM</div>
                                    </div>
                                    <div class="position-slot drop-zone" data-position="CM3" ondrop="drop(event)"
                                        ondragover="allowDrop(event)" ondragleave="dragLeave(event)">
                                        <div class="slot-label">CM</div>
                                    </div>
                                </div>

                                <div class="position-col strikers-col">
                                    <div class="position-slot drop-zone" data-position="LW" ondrop="drop(event)"
                                        ondragover="allowDrop(event)" ondragleave="dragLeave(event)">
                                        <div class="slot-label">LW</div>
                                    </div>
                                    <div class="position-slot drop-zone" data-position="ST" ondrop="drop(event)"
                                        ondragover="allowDrop(event)" ondragleave="dragLeave(event)">
                                        <div class="slot-label">ST</div>
                                    </div>
                                    <div class="position-slot drop-zone" data-position="RW" ondrop="drop(event)"
                                        ondragover="allowDrop(event)" ondragleave="dragLeave(event)">
                                        <div class="slot-label">RW</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="substitutes-bench">
                            <div class="bench-header">
                                <h4>Substitutes Bench:</h4>
                                <p class="bench-count">Bench: <span id="subsCount2">0</span>/9</p>
                            </div>
                            <div class="bench-slots">
                                <?php for ($i = 1; $i <= 9; $i++): ?>
                                    <div class="bench-slot drop-zone" data-position="SUB<?php echo $i; ?>"
                                        ondrop="drop(event)" ondragover="allowDrop(event)" ondragleave="dragLeave(event)">
                                        <div class="slot-number"><?php echo $i; ?></div>
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="readOnlyView"></div>
            </div>
        </div>
    </div>

    <script>
        const isHeadCoach = <?php echo json_encode($is_head_coach); ?>;
    </script>
    <script src="nextMatchSquad.js"></script>
</body>

</html>