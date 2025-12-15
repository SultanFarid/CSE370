<?php
session_start();
require_once('dbconnect.php');

// GATEKEEPER
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$current_user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

// DETERMINE TARGET PLAYER
if (isset($_GET['player_id'])) {
    $target_id = $_GET['player_id'];
} else {
    $target_id = $current_user_id;
}

// Hide sensitive info (Contract, NID, Phone, Address)
$show_sensitive = false;

// I am viewing myself
if ($target_id == $current_user_id) {
    $show_sensitive = true;
}
// I am a Head Coach or Assistant Coach
elseif ($user_role == 'coach') {
    $c_q = "SELECT Coach_Type FROM Coach WHERE Coach_ID = '$current_user_id'";
    $c_res = mysqli_query($conn, $c_q);
    $c_row = mysqli_fetch_assoc($c_res);
    if ($c_row && ($c_row['Coach_Type'] == 'Head Coach' || $c_row['Coach_Type'] == 'Assistant Coach')) {
        $show_sensitive = true;
    }
}

// FETCH DATA
$query_base = "SELECT * FROM users u 
               JOIN Player p ON u.User_ID = p.Player_ID 
               WHERE u.User_ID = '$target_id'";
$result_base = mysqli_query($conn, $query_base);
$base = mysqli_fetch_assoc($result_base);

if (!$base) {
    echo "Player not found.";
    exit();
}

// FETCH ROLE SPECIFIC DATA
$spec = [];
if ($base['Role'] == 'regular_player') {
    $q = "SELECT * FROM Regular_Player WHERE Regular_Player_ID = '$target_id'";
    $spec = mysqli_fetch_assoc(mysqli_query($conn, $q));
} else {
    $q = "SELECT * FROM Scouted_Player WHERE Scouted_Player_ID = '$target_id'";
    $spec = mysqli_fetch_assoc(mysqli_query($conn, $q));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - BUFC</title>
    <link rel="stylesheet" href="playerProfile.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body>

    <div class="dashboard-container">

        <aside class="sidebar">
            <nav class="sidebar-nav">
                <?php if ($user_role == 'coach'): ?>
                    <?php
                    $squad_active = 'active';
                    $scout_active = '';
                    // If URL says ?from=scout, switch the blue highlight
                    if (isset($_GET['from']) && $_GET['from'] == 'scout') {
                        $squad_active = '';
                        $scout_active = 'active';
                    }
                    ?>
                    <a href="coachProfile.php" class="nav-item">Profile</a>
                    <a href="mySquad.php" class="nav-item <?php echo $squad_active; ?>">My Squad</a>
                    <a href="medicalReport.php" class="nav-item">Medical Report</a>
                    <a href="trainingSessions.php" class="nav-item">Training Sessions</a>
                    <a href="nextMatchSquad.php" class="nav-item">Next Match Squad</a>
                    <a href="coaches.php" class="nav-item">Coaches</a>
                    <a href="pointsTable.php" class="nav-item">Points Table</a>
                    <a href="scoutedPlayers.php" class="nav-item <?php echo $scout_active; ?>">Scouted Players</a>
                    <a href="fixtures.php" class="nav-item">Fixtures</a>

                <?php else: ?>
                    <a href="playerProfile.php"
                        class="nav-item <?php echo ($target_id == $current_user_id) ? 'active' : ''; ?>">Profile</a>

                    <?php if (isset($base['Role']) && $base['Role'] == 'regular_player'): ?>
                        <a href="mySquad.php"
                            class="nav-item <?php echo ($target_id != $current_user_id) ? 'active' : ''; ?>">My Squad</a>
                        <a href="medicalReport.php" class="nav-item">Medical Report</a>
                    <?php endif; ?>

                    <a href="trainingSessions.php" class="nav-item">Training Sessions</a>

                    <?php if (isset($base['Role']) && $base['Role'] == 'regular_player'): ?>
                        <a href="nextMatchSquad.php" class="nav-item">Next Match Squad</a>
                        <a href="coaches.php" class="nav-item">Coaches</a>
                        <a href="pointsTable.php" class="nav-item">Points Table</a>
                        <a href="fixtures.php" class="nav-item">Fixtures</a>
                    <?php endif; ?>
                <?php endif; ?>

                <a href="logout.php" class="nav-item logout-link">Logout</a>
            </nav>
        </aside>

        <div class="main-content">

            <header class="top-header">
                <div class="brand-group">
                    <img src="images/bufc-logo.jpg" alt="Logo" class="header-logo">
                    <div class="header-text-group">
                        <h1>WELCOME TO BUFC</h1>
                        <p class="slogan">Together We Triumph</p>
                    </div>
                </div>
            </header>

            <div class="profile-container fade-in">

                <?php
                $show_back_button = false;
                $back_link = '';
                $back_text = '';

                // CASE 1: Coach is viewing
                if ($_SESSION['user_role'] == 'coach') {
                    $show_back_button = true;
                    if (isset($_GET['from']) && $_GET['from'] == 'scout') {
                        $back_link = 'scoutedPlayers.php';
                        $back_text = 'Back to Scouting';
                    } else {
                        $back_link = 'mySquad.php';
                        $back_text = 'Back to Squad';
                    }
                }
                // CASE 2: Player is viewing SOMEONE ELSE
                elseif ($target_id != $current_user_id) {
                    $show_back_button = true;
                    $back_link = 'mySquad.php';
                    $back_text = 'Back to Squad';
                }
                ?>

                <?php if ($show_back_button): ?>
                    <div class="back-btn-wrapper">
                        <a href="<?php echo $back_link; ?>" class="back-btn">
                            <span>&#8592;</span> <?php echo $back_text; ?>
                        </a>
                    </div>
                <?php endif; ?>

                <div class="top-section">
                    <div class="photo-box">
                        <div class="photo-placeholder">
                            <img src="images/players/<?php echo $target_id; ?>.jpg" alt="No Photo"
                                onerror="this.style.display='none'; this.parentNode.innerText='Photo'">
                        </div>
                    </div>

                    <div class="details-box">
                        <div class="details-header">
                            <div class="header-left-group">
                                <h2 class="player-name"><?php echo htmlspecialchars($base['Name']); ?></h2>
                                <?php if ($base['Role'] == 'regular_player'): ?>
                                    <span class="role-pill">First Team</span>
                                <?php else: ?>
                                    <span class="role-pill status-<?php echo strtolower($spec['Application_Status']); ?>">
                                        <?php echo htmlspecialchars($spec['Application_Status']); ?></span>
                                <?php endif; ?>
                            </div>

                            <?php if ($target_id == $current_user_id): ?>
                                <a href="editPlayerProfile.php" class="edit-btn">Edit Profile</a>
                            <?php endif; ?>
                        </div>

                        <div class="info-grid">
                            <div class="info-item"><label>Age</label>
                                <div class="val"><?php echo htmlspecialchars($base['Age']); ?> Years</div>
                            </div>
                            <div class="info-item"><label>Date of Birth</label>
                                <div class="val"><?php echo htmlspecialchars($base['Date_of_Birth']); ?></div>
                            </div>
                            <div class="info-item"><label>Position</label>
                                <div class="val"><?php echo htmlspecialchars($base['Position']); ?></div>
                            </div>
                            <div class="info-item"><label>Foot</label>
                                <div class="val"><?php echo htmlspecialchars($base['Preferred_foot']); ?></div>
                            </div>
                            <div class="info-item">
                                <label>Injury Status</label>
                                <div
                                    class="val status-<?php echo strtolower(str_replace(' ', '-', $base['Current_Injury_Status'])); ?>">
                                    <?php echo htmlspecialchars($base['Current_Injury_Status']); ?>
                                </div>
                            </div>
                            <div class="info-item"><label>Height</label>
                                <div class="val"><?php echo htmlspecialchars($base['Height']); ?> cm</div>
                            </div>
                            <div class="info-item"><label>Weight</label>
                                <div class="val"><?php echo htmlspecialchars($base['Weight']); ?> kg</div>
                            </div>
                            <div class="info-item"><label>Phone</label>
                                <div class="val"><?php echo htmlspecialchars($base['Phone_No']); ?></div>
                            </div>
                            <div class="info-item"><label>NID</label>
                                <div class="val"><?php echo $base['NID'] ? htmlspecialchars($base['NID']) : 'N/A'; ?>
                                </div>
                            </div>
                            <div class="info-item full"><label>Email</label>
                                <div class="val"><?php echo htmlspecialchars($base['Email']); ?></div>
                            </div>
                            <div class="info-item full"><label>Address</label>
                                <div class="val">
                                    <?php echo $base['Address'] ? htmlspecialchars($base['Address']) : 'N/A'; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="divider-line">

                <div class="bottom-section">
                    <h3>Additional Information</h3>

                    <?php if ($base['Role'] == 'scouted_player'): ?>
                        <div class="info-content">
                            <div class="data-row"><span class="data-label">Experience:</span>
                                <?php echo $spec['Scouted_Player_Experience']; ?></div>
                            <div class="data-row"><span class="data-label">Previous Club:</span>
                                <?php echo $spec['Scouted_Player_Previous_Club']; ?></div>
                            <div class="bio-box"><label>Bio</label>
                                <p>"<?php echo htmlspecialchars($spec['Bio']); ?>"</p>
                            </div>
                        </div>

                    <?php else: ?>
                        <div class="split-grid <?php echo !$show_sensitive ? 'single-col' : ''; ?>">

                            <div class="info-content">
                                <h4 class="box-title">Season Stats</h4>
                                <div class="stats-row">
                                    <div class="stat-pill">
                                        <span class="lbl">Jersey</span>
                                        <span class="num">#<?php echo $spec['Jersey_No']; ?></span>
                                    </div>
                                    <div class="stat-pill">
                                        <span class="lbl">Goals</span>
                                        <span class="num"><?php echo $spec['Goals_Scored']; ?></span>
                                    </div>
                                    <div class="stat-pill">
                                        <span class="lbl">Matches</span>
                                        <span class="num"><?php echo $spec['Matches_Played']; ?></span>
                                    </div>
                                </div>
                            </div>

                            <?php if ($show_sensitive): ?>
                                <div class="info-content">
                                    <h4 class="box-title">Contract Info</h4>
                                    <div class="stats-row">
                                        <div class="stat-pill"><span class="lbl">Salary</span><span class="num"
                                                style="color: #16a34a;">$<?php echo number_format($base['salary']); ?></span>
                                        </div>
                                        <div class="stat-pill"><span class="lbl">Start</span><span
                                                class="num"><?php echo $base['contract_start_date']; ?></span></div>
                                        <div class="stat-pill"><span class="lbl">End</span><span
                                                class="num"><?php echo $base['contract_end_date']; ?></span></div>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>

</body>

</html>