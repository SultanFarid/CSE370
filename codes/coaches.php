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

// CHECK If Head Coach
// We also need to fetch the specific coach type
$is_head_coach = false;
if ($user_role == 'coach') {
    $hc_query = "SELECT Coach_Type FROM Coach WHERE Coach_ID = '$user_id'";
    $hc_result = mysqli_query($conn, $hc_query);
    if ($hc_row = mysqli_fetch_assoc($hc_result)) {
        if ($hc_row['Coach_Type'] == 'Head Coach') {
            $is_head_coach = true;
        }
    }
}

// FETCH ALL COACHES 
$query = "SELECT 
            u.User_ID, 
            u.Name, 
            c.Coach_Type, 
            c.Coach_Availability, 
            c.Coach_Experience, 
            c.Coach_Speciality
          FROM Coach c
          JOIN users u ON c.Coach_ID = u.User_ID
          ORDER BY u.Name ASC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coaching Staff - BUFC</title>
    <link rel="stylesheet" href="coaches.css">
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
                    <a href="coaches.php" class="nav-item active">Coaches</a>
                    <a href="pointsTable.php" class="nav-item">Points Table</a>
                    <a href="scoutedPlayers.php" class="nav-item">Scouted Players</a>
                    <a href="fixtures.php" class="nav-item">Fixtures</a>
                <?php else: ?>
                    <a href="playerProfile.php" class="nav-item">Profile</a>
                    <a href="mySquad.php" class="nav-item">My Squad</a>
                    <a href="medicalReport.php" class="nav-item">Medical Report</a>
                    <a href="trainingSessions.php" class="nav-item">Training Sessions</a>
                    <a href="nextMatchSquad.php" class="nav-item">Next Match Squad</a>
                    <a href="coaches.php" class="nav-item active">Coaches</a>
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
                        <h1>COACHING STAFF</h1>
                        <p class="slogan">The Minds Behind the Game</p>
                    </div>
                </div>
            </header>

            <div class="squad-container fade-in">
                <!-- FILTER BAR -->
                <div class="filter-bar">
                    <!-- LEFT: Filter by Availability -->
                    <div class="filter-left">
                        <div class="filter-item">
                            <label>Availability</label>
                            <select id="availabilityFilter" class="custom-select">
                                <option value="all">All Statuses</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="On Leave">On Leave</option>
                            </select>
                        </div>
                    </div>
                    <!-- RIGHT: Search by Name -->
                    <div class="filter-right">
                        <label>Search Coach</label>
                        <div class="search-wrapper">
                            <input type="text" id="searchInput" placeholder="Enter Name..." autocomplete="off">
                            <button id="searchBtn" type="button"
                                style="padding: 0 20px; background: var(--brand-blue); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 700;">Search</button>
                        </div>
                    </div>

                </div>
                <!-- COACHES GRID -->
                <div class="squad-grid" id="squadGrid">

                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $availability = trim($row['Coach_Availability']);

                            if ($availability === 'Active') {
                                $status_class = 'status-green';
                            } elseif ($availability === 'On Leave') {
                                $status_class = 'status-yellow';
                            } else {
                                $status_class = 'status-red';
                            }

                            echo '<a href="coachProfile.php?coach_id=' . $row['User_ID'] . '" class="player-card ' . $status_class . '">';
                            ?>


                            <div class="card-body">
                                <h3 class="player-name"><?php echo htmlspecialchars($row['Name']); ?></h3>
                                <p class="player-pos"><?php echo htmlspecialchars($row['Coach_Type']); ?></p>

                                <div class="stat-grid">
                                    <div class="stat-item full-width">
                                        <span class="lbl">Speciality</span>
                                        <span class="val"><?php echo htmlspecialchars($row['Coach_Speciality']); ?></span>
                                    </div>
                                    <div class="stat-item full-width">
                                        <span class="lbl">Experience</span>
                                        <span class="val"><?php echo htmlspecialchars($row['Coach_Experience']); ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <span class="status-indicator">
                                    <?php echo htmlspecialchars($row['Coach_Availability']); ?>
                                </span>
                            </div>

                            <?php
                            echo '</a>';
                        }
                    } else {
                        echo '<div class="no-data">No coaches found.</div>';
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>

    <script src="coaches.js"></script>
</body>

</html>