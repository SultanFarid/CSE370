<?php
session_start();
require_once('dbconnect.php');

// GATEKEEPER
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role']; 

// FETCH SQUAD DATA (sorted by jersey no)
$query = "SELECT 
            u.User_ID, 
            u.Name,
            u.Age,
            p.Position, 
            p.Preferred_foot, 
            p.Current_Injury_Status, 
            rp.Jersey_No,
            rp.Goals_Scored, 
            rp.Matches_Played,
            GROUP_CONCAT(ps.Skill_Name SEPARATOR ', ') as Skills
          FROM regular_player rp
          JOIN player p ON rp.Regular_Player_ID = p.Player_ID
          JOIN users u ON p.Player_ID = u.User_ID
          LEFT JOIN player_skills ps ON p.Player_ID = ps.Player_ID
          GROUP BY rp.Regular_Player_ID
          ORDER BY rp.Jersey_No ASC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Squad - BUFC</title>
    <link rel="stylesheet" href="mySquad.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

<div class="dashboard-container">
    <aside class="sidebar">
        <nav class="sidebar-nav">
            <?php if ($user_role == 'coach'): ?>
                <a href="coachProfile.php" class="nav-item">Profile</a>
                <a href="mySquad.php" class="nav-item active">My Squad</a>
                <a href="medicalReport.php" class="nav-item">Medical Report</a>
                <a href="trainingSessions.php" class="nav-item">Training Sessions</a>
                <a href="nextMatchSquad.php" class="nav-item">Next Match Squad</a>
                <a href="coaches.php" class="nav-item">Coaches</a>
                <a href="pointsTable.php" class="nav-item">Points Table</a>
                <a href="scoutedPlayers.php" class="nav-item">Scouted Players</a>
                <a href="fixtures.php" class="nav-item">Fixtures</a>
            <?php else: ?>
                <a href="playerProfile.php" class="nav-item">Profile</a>
                <a href="mySquad.php" class="nav-item active">My Squad</a>
                <a href="medicalReport.php" class="nav-item">Medical Report</a>
                <a href="trainingSessions.php" class="nav-item">Training Sessions</a>
                <a href="nextMatchSquad.php" class="nav-item">Next Match Squad</a>
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
                    <h1>BUFC SQUAD</h1>
                    <p class="slogan">First Team Roster</p>
                </div>
            </div>
        </header>

        <div class="squad-container fade-in">
            <div class="filter-bar">
                <div class="filter-left">
                    <div class="filter-item">
                        <label>Injury Status</label>
                        <select id="statusFilter" class="custom-select">
                            <option value="all">All Statuses</option>
                            <option value="Fit">Fit</option>
                            <option value="Injured">Injured</option>
                            <option value="Recovering">Recovering</option>
                            <option value="Doubtful">Doubtful</option>
                        </select>
                    </div>

                    <div class="filter-item">
                        <label>Position</label>
                        <select id="positionFilter" class="custom-select">
                            <option value="all">All Positions</option>
                            <option value="Goalkeeper">Goalkeeper</option>
                            <option value="Defender">Defender</option>
                            <option value="Midfielder">Midfielder</option>
                            <option value="Striker">Striker</option>
                        </select>
                    </div>
                </div>
                <div class="filter-right">
                    <label>Search Player</label>
                    <div class="search-wrapper">
                        <input type="text" id="searchInput" placeholder="Enter Name or Jersey No..." autocomplete="off">
                        <button id="searchBtn" type="button">Search</button>
                    </div>
                </div>

            </div>

            <div class="squad-grid" id="squadGrid">
                
                <?php 
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        
                        // Determine Color Class based on Injury Status
                        $injury_status = $row['Current_Injury_Status'];
                        if ($injury_status == 'Fit') {
                            $status_class = 'status-green';
                        } elseif ($injury_status == 'Recovering' || $injury_status == 'Doubtful') {
                            $status_class = 'status-yellow';
                        } else {
                            // Default to Red for 'Injured'
                            $status_class = 'status-red';
                        }
                        echo '<a href="playerProfile.php?player_id=' . $row['User_ID'] . '" class="player-card ' . $status_class . '">';
                        ?>
                            
                            <div class="card-header">
                                <span class="jersey-badge">Jersey: <?php echo $row['Jersey_No']; ?></span>
                            </div>

                            <div class="card-body">
                                <h3 class="player-name"><?php echo htmlspecialchars($row['Name']); ?></h3>
                                <p class="player-pos"><?php echo htmlspecialchars($row['Position']); ?></p>
                                
                                <div class="stat-grid">
                                    <div class="stat-item">
                                        <span class="lbl">Age</span>
                                        <span class="id-badge val" style="background:none; padding:0; font-family:inherit; color:var(--brand-dark); font-size:0.9rem;"><?php echo $row['Age']; ?></span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="lbl">Foot</span>
                                        <span class="val"><?php echo htmlspecialchars($row['Preferred_foot']); ?></span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="lbl">Goals</span>
                                        <span class="val"><?php echo $row['Goals_Scored']; ?></span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="lbl">Matches</span>
                                        <span class="val"><?php echo $row['Matches_Played']; ?></span>
                                    </div>
                                </div>

                                <div class="skills-box">
                                    <span class="lbl">Skills:</span>
                                    <p class="skills-text">
                                        <?php echo $row['Skills'] ? htmlspecialchars($row['Skills']) : 'No specific skills listed'; ?>
                                    </p>
                                </div>
                            </div>

                            <div class="card-footer">
                                <span class="status-indicator">
                                    <?php echo htmlspecialchars($row['Current_Injury_Status']); ?>
                                </span>
                            </div>

                        <?php 
                            echo '</a>';
                    }
                } else {
                    echo '<div class="no-data">No players found in the squad.</div>';
                }
                ?>

            </div>
        </div>
    </div>
</div>

<script src="mySquad.js"></script>
</body>
</html>