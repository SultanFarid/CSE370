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

// Connect to tournament database
$tournament_conn = mysqli_connect("localhost", "root", "", "tournament_db");

// Fetch upcoming fixtures
$upcoming_fixtures = [];
$upcoming_query = "SELECT * FROM fixtures WHERE Match_status = 'Scheduled' ORDER BY Match_date ASC, Match_time ASC";
$upcoming_result = mysqli_query($tournament_conn, $upcoming_query);
while ($row = mysqli_fetch_assoc($upcoming_result)) {
    // Determine if home or away based on stadium
    $row['venue'] = (strpos($row['Stadium'], 'BRAC University') !== false) ? 'home' : 'away';
    $upcoming_fixtures[] = $row;
}

// Fetch previous fixtures
$previous_fixtures = [];
$stats = ['wins' => 0, 'draws' => 0, 'losses' => 0, 'total' => 0];

$previous_query = "SELECT f.*, g.Score, mr.MVP
                   FROM fixtures f
                   LEFT JOIN generates g ON f.Match_id = g.Match_id AND g.Team_Name = 'BUFC'
                   LEFT JOIN match_results mr ON f.Match_id = mr.Match_id
                   WHERE f.Match_status IN ('Won', 'Lost', 'Draw')
                   ORDER BY f.Match_date DESC";
$previous_result = mysqli_query($tournament_conn, $previous_query);

while ($row = mysqli_fetch_assoc($previous_result)) {
    // Determine if home or away based on stadium
    $row['venue'] = (strpos($row['Stadium'], 'BRAC University') !== false) ? 'home' : 'away';

    // Parse score
    if ($row['Score']) {
        $scores = explode('-', $row['Score']);
        $row['bufc_score'] = $scores[0];
        $row['opponent_score'] = $scores[1];
    }

    // Get squad for this match
    $match_id = $row['Match_id'];
    $squad_query = "SELECT 
                        u.User_ID,
                        u.Name,
                        p.Position,
                        rp.Jersey_No,
                        pi.Rating,
                        pi.Goals_Scored,
                        pi.Status
                    FROM plays_in pi
                    JOIN regular_player rp ON pi.Regular_Player_ID = rp.Regular_Player_ID
                    JOIN users u ON rp.Regular_Player_ID = u.User_ID
                    JOIN player p ON u.User_ID = p.Player_ID
                    WHERE pi.Match_id = '$match_id'
                    ORDER BY 
                        CASE p.Position
                            WHEN 'Goalkeeper' THEN 1
                            WHEN 'Defender' THEN 2
                            WHEN 'Midfielder' THEN 3
                            WHEN 'Striker' THEN 4
                        END,
                        u.Name";

    $squad_result = mysqli_query($conn, $squad_query);
    $row['squad'] = [];
    while ($player = mysqli_fetch_assoc($squad_result)) {
        $row['squad'][] = $player;
    }

    // Determine result
    $status = strtolower($row['Match_status']);
    if ($status === 'won') {
        $row['result'] = 'won';
        $stats['wins']++;
    } elseif ($status === 'lost') {
        $row['result'] = 'lost';
        $stats['losses']++;
    } else {
        $row['result'] = 'draw';
        $stats['draws']++;
    }

    $stats['total']++;
    $previous_fixtures[] = $row;
}

mysqli_close($tournament_conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fixtures - BUFC</title>
    <link rel="stylesheet" href="fixtures.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <nav class="sidebar-nav">
                <?php if ($user_role == 'coach'): ?>
                    <a href="coachProfile.php" class="nav-item">Profile</a>
                    <a href="mySquad.php" class="nav-item">My Squad</a>
                    <a href="medicalReport.php" class="nav-item">Medical Report</a>
                    <a href="trainingSessions.php" class="nav-item">Training Sessions</a>
                    <a href="nextMatchSquad.php" class="nav-item">Next Match Squad</a>
                    <a href="coaches.php" class="nav-item">Coaches</a>
                    <a href="pointsTable.php" class="nav-item">Points Table</a>
                    <a href="scoutedPlayers.php" class="nav-item">Scouted Players</a>
                    <a href="fixtures.php" class="nav-item active">Fixtures</a>
                <?php else: ?>
                    <a href="playerProfile.php" class="nav-item">Profile</a>
                    <a href="mySquad.php" class="nav-item">My Squad</a>
                    <a href="medicalReport.php" class="nav-item">Medical Report</a>
                    <a href="trainingSessions.php" class="nav-item">Training Sessions</a>
                    <a href="nextMatchSquad.php" class="nav-item">Next Match Squad</a>
                    <a href="coaches.php" class="nav-item">Coaches</a>
                    <a href="pointsTable.php" class="nav-item">Points Table</a>
                    <a href="fixtures.php" class="nav-item active">Fixtures</a>
                <?php endif; ?>
                <a href="logout.php" class="nav-item logout-link">Logout</a>
            </nav>
        </aside>

        <div class="main-content">
            <header class="top-header">
                <div class="brand-group">
                    <img src="images/bufc-logo.jpg" alt="Logo" class="header-logo">
                    <div class="header-text-group">
                        <h1>BUFC FIXTURES</h1>
                        <p class="slogan">Match Schedule & Results</p>
                    </div>
                </div>
            </header>

            <div class="squad-container fade-in">
                <div class="stats-bar">
                    <div class="stat-box win-stat">
                        <div class="stat-number"><?php echo $stats['wins']; ?></div>
                        <div class="stat-label">Wins</div>
                    </div>
                    <div class="stat-box draw-stat">
                        <div class="stat-number"><?php echo $stats['draws']; ?></div>
                        <div class="stat-label">Draws</div>
                    </div>
                    <div class="stat-box loss-stat">
                        <div class="stat-number"><?php echo $stats['losses']; ?></div>
                        <div class="stat-label">Losses</div>
                    </div>
                    <div class="stat-box total-stat">
                        <div class="stat-number"><?php echo $stats['total']; ?></div>
                        <div class="stat-label">Total Matches</div>
                    </div>
                </div>

                <div class="main-tab-switcher">
                    <button class="main-tab active" onclick="switchMainTab('upcoming')">📅 Upcoming Fixtures</button>
                    <button class="main-tab" onclick="switchMainTab('previous')">⚽ Previous Matches</button>
                </div>

                <div id="upcoming-section" class="tab-section active">
                    <div class="filter-tabs">
                        <button class="filter-tab active" onclick="filterUpcoming('all')">All</button>
                        <button class="filter-tab" onclick="filterUpcoming('home')">Home</button>
                        <button class="filter-tab" onclick="filterUpcoming('away')">Away</button>
                    </div>

                    <div class="fixtures-list">
                        <?php if (empty($upcoming_fixtures)): ?>
                            <div class="no-data">No upcoming fixtures</div>
                        <?php else: ?>
                            <?php foreach ($upcoming_fixtures as $fixture):
                                $date = new DateTime($fixture['Match_date'] . ' ' . $fixture['Match_time']);
                                $day = $date->format('D');
                                $dateNum = $date->format('d');
                                $month = $date->format('M');
                                $time = $date->format('g:i A');
                                ?>
                                <div class="match-box upcoming" data-venue="<?php echo $fixture['venue']; ?>">
                                    <div class="match-header">
                                        <div class="match-info">
                                            <div class="match-date-box">
                                                <div class="match-day"><?php echo $day; ?></div>
                                                <div class="match-date-num"><?php echo $dateNum; ?></div>
                                                <div class="match-month"><?php echo $month; ?></div>
                                            </div>
                                            <div class="match-score-section upcoming">
                                                <div class="team-container">
                                                    <img src="images/teams/BUFC.png" alt="BUFC" class="team-logo"
                                                        onerror="this.style.display='none'">
                                                    <div class="team-name">BUFC</div>
                                                </div>
                                                <div class="vs-text">VS</div>
                                                <div class="team-container">
                                                    <img src="images/teams/<?php echo htmlspecialchars($fixture['Opponent']) ?>.png"
                                                        alt="<?php echo htmlspecialchars($fixture['Opponent']) ?>"
                                                        class="team-logo" onerror="this.style.display='none'">
                                                    <div class="team-name"><?php echo htmlspecialchars($fixture['Opponent']) ?>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="match-meta">
                                            <div class="match-status-badge scheduled"><?php echo $time; ?></div>
                                            <div class="stadium-info">📍 <?php echo htmlspecialchars($fixture['Stadium']); ?>
                                            </div>
                                            <div class="venue-badge <?php echo $fixture['venue']; ?>">
                                                <?php echo strtoupper($fixture['venue']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div id="previous-section" class="tab-section">
                    <div class="filter-tabs">
                        <button class="filter-tab active" onclick="filterPrevious('all')">All</button>
                        <button class="filter-tab" onclick="filterPrevious('home')">Home</button>
                        <button class="filter-tab" onclick="filterPrevious('away')">Away</button>
                        <button class="filter-tab" onclick="filterPrevious('won')">Won</button>
                        <button class="filter-tab" onclick="filterPrevious('lost')">Lost</button>
                        <button class="filter-tab" onclick="filterPrevious('draw')">Draw</button>
                    </div>

                    <div class="fixtures-list">
                        <?php if (empty($previous_fixtures)): ?>
                            <div class="no-data">No previous matches</div>
                        <?php else: ?>
                            <?php foreach ($previous_fixtures as $idx => $fixture):
                                $date = new DateTime($fixture['Match_date']);
                                $day = $date->format('D');
                                $dateNum = $date->format('d');
                                $month = $date->format('M');
                                $statusText = ucfirst($fixture['result']);

                                // Separate starters and substitutes
                                $starters = array_filter($fixture['squad'], fn($p) => $p['Status'] === 'Started');
                                $substitutes = array_filter($fixture['squad'], fn($p) => $p['Status'] === 'Substituted');

                                // Organize starters into tactical positions (9 roles)
                                $all_defenders = array_values(array_filter($starters, fn($p) => $p['Position'] === 'Defender'));
                                $all_midfielders = array_values(array_filter($starters, fn($p) => $p['Position'] === 'Midfielder'));
                                $all_strikers = array_values(array_filter($starters, fn($p) => $p['Position'] === 'Striker'));

                                // GK - Goalkeeper (1 player)
                                $gk = array_values(array_filter($starters, fn($p) => $p['Position'] === 'Goalkeeper'));

                                // Defenders --> LB, CB(2), RB (4 players ideally)
                                $lb = [];
                                $cb = [];
                                $rb = [];
                                $def_count = count($all_defenders);
                                if ($def_count > 0) {
                                    if ($def_count == 1) {
                                        $cb = [$all_defenders[0]]; // Only 1 --> CB
                                    } else if ($def_count == 2) {
                                        $cb = $all_defenders; // 2 defenders --> both CB
                                    } else if ($def_count == 3) {
                                        $lb = [$all_defenders[0]]; // First --> LB
                                        $cb = [$all_defenders[1]]; // Second --> CB
                                        $rb = [$all_defenders[2]]; // Third --> RB
                                    } else {
                                        $lb = [$all_defenders[0]]; // First --> LB
                                        $cb = array_slice($all_defenders, 1, 2); // Middle 2 --> CB
                                        $rb = [$all_defenders[3]]; // Last --> RB
                                    }
                                }

                                // Midfielders --> DM, CM(2), LW, RW
                                $dm = [];
                                $cm = [];
                                $lw = [];
                                $rw = [];
                                $mid_count = count($all_midfielders);
                                if ($mid_count > 0) {
                                    if ($mid_count == 1) {
                                        $dm = $all_midfielders; // 1 --> DM
                                    } else if ($mid_count == 2) {
                                        $cm = $all_midfielders; // 2 --> both CM
                                    } else if ($mid_count == 3) {
                                        $dm = [$all_midfielders[0]]; // First --> DM
                                        $cm = [$all_midfielders[1], $all_midfielders[2]]; // Last 2 → CM
                                    } else if ($mid_count == 4) {
                                        $dm = [$all_midfielders[0]]; // First --> DM
                                        $cm = [$all_midfielders[1], $all_midfielders[2]]; // Middle 2 --> CM
                                        $lw = [$all_midfielders[3]]; // Last --> LW
                                    } else {
                                        $dm = [$all_midfielders[0]]; // First --> DM
                                        $cm = [$all_midfielders[1], $all_midfielders[2]]; // Next 2 --> CM
                                        $lw = [$all_midfielders[3]]; // Next --> LW
                                        $rw = array_slice($all_midfielders, 4); // Rest --> RW
                                    }
                                }

                                // Strikers --> ST (and extra to LW/RW if needed)
                                $st = [];
                                $striker_count = count($all_strikers);
                                if ($striker_count > 0) {
                                    $st = [$all_strikers[0]]; // First striker --> ST
                                    // If more strikers and no wingers, add to wings
                                    if ($striker_count > 1 && empty($lw)) {
                                        $lw = [$all_strikers[1]];
                                    }
                                    if ($striker_count > 2 && empty($rw)) {
                                        $rw = [$all_strikers[2]];
                                    }
                                }

                                ?>
                                <div class="match-box <?php echo $fixture['result']; ?>"
                                    data-result="<?php echo $fixture['result']; ?>"
                                    data-venue="<?php echo $fixture['venue']; ?>">
                                    <div class="match-header" onclick="toggleSquad(<?php echo $idx; ?>)">
                                        <div class="match-info">
                                            <div class="match-date-box">
                                                <div class="match-day"><?php echo $day; ?></div>
                                                <div class="match-date-num"><?php echo $dateNum; ?></div>
                                                <div class="match-month"><?php echo $month; ?></div>
                                            </div>
                                            <div class="match-score-section finished">
                                                <div class="team-container">
                                                    <img src="images/teams/BUFC.png" alt="BUFC" class="team-logo"
                                                        onerror="this.style.display='none'">
                                                    <div class="team-name">BUFC</div>
                                                </div>
                                                <div class="score-display"><?php echo $fixture['bufc_score'] ?></div>
                                                <div class="vs-text">-</div>
                                                <div class="score-display"><?php echo $fixture['opponent_score'] ?></div>
                                                <div class="team-container">
                                                    <img src="images/teams/<?php echo htmlspecialchars($fixture['Opponent']) ?>.png"
                                                        alt="<?php echo htmlspecialchars($fixture['Opponent']) ?>"
                                                        class="team-logo" onerror="this.style.display='none'">
                                                    <div class="team-name"><?php echo htmlspecialchars($fixture['Opponent']) ?>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="match-meta">
                                            <div class="match-status-badge <?php echo $fixture['result']; ?>">
                                                <?php echo $statusText; ?>
                                            </div>
                                            <div class="stadium-info">📍 <?php echo htmlspecialchars($fixture['Stadium']); ?>
                                            </div>
                                            <div class="venue-badge <?php echo $fixture['venue']; ?>">
                                                <?php echo strtoupper($fixture['venue']); ?>
                                            </div>
                                            <div class="expand-icon">▼</div>
                                        </div>
                                    </div>

                                    <div class="match-details" id="details-<?php echo $idx; ?>">
                                        <div class="details-content-full">
                                            <?php if ($fixture['MVP']): ?>
                                                <div class="mvp-section-top">
                                                    <div class="mvp-icon">⭐</div>
                                                    <div>
                                                        <div class="mvp-label">Man of the Match</div>
                                                        <div class="mvp-name-top"><?php echo htmlspecialchars($fixture['MVP']); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <div class="football-field">
                                                <div class="field-lines">
                                                    <div class="center-circle"></div>
                                                    <div class="center-line"></div>
                                                    <div class="penalty-box top"></div>
                                                    <div class="penalty-box bottom"></div>
                                                    <div class="goal-box top"></div>
                                                    <div class="goal-box bottom"></div>
                                                </div>

                                                <div class="tactical-grid">
                                                    <div class="position-slot gk-slot">
                                                        <?php foreach ($gk as $player): ?>
                                                            <div class="player-card-field">
                                                                <img src="images/players/<?php echo $player['User_ID']; ?>.jpg"
                                                                    alt="<?php echo htmlspecialchars($player['Name']); ?>"
                                                                    class="player-avatar"
                                                                    onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($player['Name']); ?>&background=3b82f6&color=fff&size=80&bold=true'">
                                                                <div class="player-rating-badge">
                                                                    <?php echo $player['Rating'] ?: 'N/A'; ?>
                                                                </div>
                                                                <?php if ($player['Goals_Scored'] > 0): ?>
                                                                    <div class="goal-badge">⚽<?php echo $player['Goals_Scored']; ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <div class="player-name-field">
                                                                    <?php echo htmlspecialchars($player['Name']); ?>
                                                                </div>
                                                                <div class="player-number-field">
                                                                    #<?php echo $player['Jersey_No']; ?></div>
                                                                <div class="player-position-tag">GK</div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>

                                                    <!-- LB (Top-Left) -->
                                                    <div class="position-slot lb-slot">
                                                        <?php foreach ($lb as $player): ?>
                                                            <div class="player-card-field">
                                                                <img src="images/players/<?php echo $player['User_ID']; ?>.jpg"
                                                                    alt="<?php echo htmlspecialchars($player['Name']); ?>"
                                                                    class="player-avatar"
                                                                    onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($player['Name']); ?>&background=3b82f6&color=fff&size=80&bold=true'">
                                                                <div class="player-rating-badge">
                                                                    <?php echo $player['Rating'] ?: 'N/A'; ?>
                                                                </div>
                                                                <?php if ($player['Goals_Scored'] > 0): ?>
                                                                    <div class="goal-badge">⚽<?php echo $player['Goals_Scored']; ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <div class="player-name-field">
                                                                    <?php echo htmlspecialchars($player['Name']); ?>
                                                                </div>
                                                                <div class="player-number-field">
                                                                    #<?php echo $player['Jersey_No']; ?></div>
                                                                <div class="player-position-tag">LB</div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>

                                                    <div class="position-slot cb-slot">
                                                        <?php foreach ($cb as $player): ?>
                                                            <div class="player-card-field">
                                                                <img src="images/players/<?php echo $player['User_ID']; ?>.jpg"
                                                                    alt="<?php echo htmlspecialchars($player['Name']); ?>"
                                                                    class="player-avatar"
                                                                    onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($player['Name']); ?>&background=3b82f6&color=fff&size=80&bold=true'">
                                                                <div class="player-rating-badge">
                                                                    <?php echo $player['Rating'] ?: 'N/A'; ?>
                                                                </div>
                                                                <?php if ($player['Goals_Scored'] > 0): ?>
                                                                    <div class="goal-badge">⚽<?php echo $player['Goals_Scored']; ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <div class="player-name-field">
                                                                    <?php echo htmlspecialchars($player['Name']); ?>
                                                                </div>
                                                                <div class="player-number-field">
                                                                    #<?php echo $player['Jersey_No']; ?></div>
                                                                <div class="player-position-tag">CB</div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>

                                                    <!-- RB (Bottom-Left) -->
                                                    <div class="position-slot rb-slot">
                                                        <?php foreach ($rb as $player): ?>
                                                            <div class="player-card-field">
                                                                <img src="images/players/<?php echo $player['User_ID']; ?>.jpg"
                                                                    alt="<?php echo htmlspecialchars($player['Name']); ?>"
                                                                    class="player-avatar"
                                                                    onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($player['Name']); ?>&background=3b82f6&color=fff&size=80&bold=true'">
                                                                <div class="player-rating-badge">
                                                                    <?php echo $player['Rating'] ?: 'N/A'; ?>
                                                                </div>
                                                                <?php if ($player['Goals_Scored'] > 0): ?>
                                                                    <div class="goal-badge">⚽<?php echo $player['Goals_Scored']; ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <div class="player-name-field">
                                                                    <?php echo htmlspecialchars($player['Name']); ?>
                                                                </div>
                                                                <div class="player-number-field">
                                                                    #<?php echo $player['Jersey_No']; ?></div>
                                                                <div class="player-position-tag">RB</div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>

                                                    <!-- DM (Center-Left/Mid) -->
                                                    <div class="position-slot dm-slot">
                                                        <?php foreach ($dm as $player): ?>
                                                            <div class="player-card-field">
                                                                <img src="images/players/<?php echo $player['User_ID']; ?>.jpg"
                                                                    alt="<?php echo htmlspecialchars($player['Name']); ?>"
                                                                    class="player-avatar"
                                                                    onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($player['Name']); ?>&background=3b82f6&color=fff&size=80&bold=true'">
                                                                <div class="player-rating-badge">
                                                                    <?php echo $player['Rating'] ?: 'N/A'; ?>
                                                                </div>
                                                                <?php if ($player['Goals_Scored'] > 0): ?>
                                                                    <div class="goal-badge">⚽<?php echo $player['Goals_Scored']; ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <div class="player-name-field">
                                                                    <?php echo htmlspecialchars($player['Name']); ?>
                                                                </div>
                                                                <div class="player-number-field">
                                                                    #<?php echo $player['Jersey_No']; ?></div>
                                                                <div class="player-position-tag">DM</div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>

                                                    <div class="position-slot cm-slot">
                                                        <?php foreach ($cm as $player): ?>
                                                            <div class="player-card-field">
                                                                <img src="images/players/<?php echo $player['User_ID']; ?>.jpg"
                                                                    alt="<?php echo htmlspecialchars($player['Name']); ?>"
                                                                    class="player-avatar"
                                                                    onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($player['Name']); ?>&background=3b82f6&color=fff&size=80&bold=true'">
                                                                <div class="player-rating-badge">
                                                                    <?php echo $player['Rating'] ?: 'N/A'; ?>
                                                                </div>
                                                                <?php if ($player['Goals_Scored'] > 0): ?>
                                                                    <div class="goal-badge">⚽<?php echo $player['Goals_Scored']; ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <div class="player-name-field">
                                                                    <?php echo htmlspecialchars($player['Name']); ?>
                                                                </div>
                                                                <div class="player-number-field">
                                                                    #<?php echo $player['Jersey_No']; ?></div>
                                                                <div class="player-position-tag">CM</div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>

                                                    <!-- LW (Top-Right) -->
                                                    <div class="position-slot lw-slot">
                                                        <?php foreach ($lw as $player): ?>
                                                            <div class="player-card-field">
                                                                <img src="images/players/<?php echo $player['User_ID']; ?>.jpg"
                                                                    alt="<?php echo htmlspecialchars($player['Name']); ?>"
                                                                    class="player-avatar"
                                                                    onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($player['Name']); ?>&background=3b82f6&color=fff&size=80&bold=true'">
                                                                <div class="player-rating-badge">
                                                                    <?php echo $player['Rating'] ?: 'N/A'; ?>
                                                                </div>
                                                                <?php if ($player['Goals_Scored'] > 0): ?>
                                                                    <div class="goal-badge">⚽<?php echo $player['Goals_Scored']; ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <div class="player-name-field">
                                                                    <?php echo htmlspecialchars($player['Name']); ?>
                                                                </div>
                                                                <div class="player-number-field">
                                                                    #<?php echo $player['Jersey_No']; ?></div>
                                                                <div class="player-position-tag">LW</div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>

                                                    <div class="position-slot st-slot">
                                                        <?php foreach ($st as $player): ?>
                                                            <div class="player-card-field">
                                                                <img src="images/players/<?php echo $player['User_ID']; ?>.jpg"
                                                                    alt="<?php echo htmlspecialchars($player['Name']); ?>"
                                                                    class="player-avatar"
                                                                    onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($player['Name']); ?>&background=3b82f6&color=fff&size=80&bold=true'">
                                                                <div class="player-rating-badge">
                                                                    <?php echo $player['Rating'] ?: 'N/A'; ?>
                                                                </div>
                                                                <?php if ($player['Goals_Scored'] > 0): ?>
                                                                    <div class="goal-badge">⚽<?php echo $player['Goals_Scored']; ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <div class="player-name-field">
                                                                    <?php echo htmlspecialchars($player['Name']); ?>
                                                                </div>
                                                                <div class="player-number-field">
                                                                    #<?php echo $player['Jersey_No']; ?></div>
                                                                <div class="player-position-tag">ST</div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>

                                                    <div class="position-slot rw-slot">
                                                        <?php foreach ($rw as $player): ?>
                                                            <div class="player-card-field">
                                                                <img src="images/players/<?php echo $player['User_ID']; ?>.jpg"
                                                                    alt="<?php echo htmlspecialchars($player['Name']); ?>"
                                                                    class="player-avatar"
                                                                    onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($player['Name']); ?>&background=3b82f6&color=fff&size=80&bold=true'">
                                                                <div class="player-rating-badge">
                                                                    <?php echo $player['Rating'] ?: 'N/A'; ?>
                                                                </div>
                                                                <?php if ($player['Goals_Scored'] > 0): ?>
                                                                    <div class="goal-badge">⚽<?php echo $player['Goals_Scored']; ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <div class="player-name-field">
                                                                    <?php echo htmlspecialchars($player['Name']); ?>
                                                                </div>
                                                                <div class="player-number-field">
                                                                    #<?php echo $player['Jersey_No']; ?></div>
                                                                <div class="player-position-tag">RW</div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>


                                            <?php if (!empty($substitutes)): ?>
                                                <div class="substitutes-section">
                                                    <div class="substitutes-header">
                                                        <h4>🔄 Substitutes</h4>
                                                        <p>Players who came on during the match</p>
                                                    </div>
                                                    <div class="substitutes-grid">
                                                        <?php
                                                        $sub_times = [15, 23, 34, 56, 67, 72, 78, 82, 85, 88]; // Random substitute timings
                                                        $sub_index = 0;
                                                        foreach ($substitutes as $player):
                                                            $sub_time = $sub_times[$sub_index % count($sub_times)];
                                                            $sub_index++;
                                                            ?>
                                                            <div class="substitute-card">
                                                                <img src="images/players/<?php echo $player['User_ID']; ?>.jpg"
                                                                    alt="<?php echo htmlspecialchars($player['Name']); ?>"
                                                                    class="sub-avatar"
                                                                    onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($player['Name']); ?>&background=f59e0b&color=fff&size=60&bold=true'">
                                                                <div class="sub-info">
                                                                    <div class="sub-name">
                                                                        <?php echo htmlspecialchars($player['Name']); ?>
                                                                    </div>
                                                                    <div class="sub-details">
                                                                        <span
                                                                            class="sub-position"><?php echo $player['Position']; ?></span>
                                                                        <span
                                                                            class="sub-number">#<?php echo $player['Jersey_No']; ?></span>
                                                                    </div>
                                                                    <?php if ($player['Rating']): ?>
                                                                        <div class="sub-rating">Rating: <?php echo $player['Rating']; ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    <?php if ($player['Goals_Scored'] > 0): ?>
                                                                        <div class="sub-goals">⚽ <?php echo $player['Goals_Scored']; ?>
                                                                            goal(s)</div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="fixtures.js"></script>
</body>

</html>