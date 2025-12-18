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

// CHECK if Head/Assistant Coach
$can_manage = false;
if ($user_role == 'coach') {
    $c_q = "SELECT Coach_Type FROM Coach WHERE Coach_ID = '$user_id'";
    $c_res = mysqli_query($conn, $c_q);
    $c_row = mysqli_fetch_assoc($c_res);
    if ($c_row && ($c_row['Coach_Type'] == 'Head Coach' || $c_row['Coach_Type'] == 'Assistant Coach')) {
        $can_manage = true;
    }
}

// FETCH DATA
$query = "SELECT u.User_ID, u.Name, u.Age, p.Position, p.Height, p.Weight, p.Preferred_foot,
            sp.Scouted_Player_Experience, sp.Scouted_Player_Previous_Club, sp.Application_Status, sp.Bio
          FROM Scouted_Player sp
          JOIN Player p ON sp.Scouted_Player_ID = p.Player_ID
          JOIN users u ON p.Player_ID = u.User_ID
          WHERE sp.Application_Status != 'Rejected' 
          ORDER BY FIELD(sp.Application_Status, 'Trialing', 'Pending') ASC, u.Name ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scouting Network - BUFC</title>
    <link rel="stylesheet" href="scoutedPlayers.css">
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
                    <a href="scoutedPlayers.php" class="nav-item active">Scouted Players</a>
                    <a href="fixtures.php" class="nav-item">Fixtures</a>
                <?php else: ?>
                    <a href="playerProfile.php" class="nav-item">Profile</a>
                <?php endif; ?>
                <a href="logout.php" class="nav-item logout-link">Logout</a>
            </nav>
        </aside>

        <div class="main-content">
            <header class="top-header">
                <div class="brand-group">
                    <img src="images/bufc-logo.jpg" alt="Logo" class="header-logo">
                    <div class="header-text-group">
                        <h1>SCOUTING CENTER</h1>
                        <p class="slogan">Recruitment & Analysis</p>
                    </div>
                </div>
            </header>

            <div class="scout-container fade-in">
                <div class="filter-bar">
                    <div class="filter-left">
                        <div class="filter-item">
                            <label>Status</label>
                            <select id="statusFilter" class="custom-select">
                                <option value="all">All Applications</option>
                                <option value="Trialing">Trialing</option>
                                <option value="Pending">Pending</option>
                            </select>
                        </div>
                        <div class="filter-item">
                            <label>Position</label>
                            <select id="positionFilter" class="custom-select">
                                <option value="all">All Positions</option>
                                <option value="Striker">Striker</option>
                                <option value="Midfielder">Midfielder</option>
                                <option value="Defender">Defender</option>
                                <option value="Goalkeeper">Goalkeeper</option>
                            </select>
                        </div>
                    </div>
                    <div class="filter-right">
                        <label>Search Name</label>
                        <div class="search-wrapper">
                            <input type="text" id="searchInput" placeholder="Search by name..." autocomplete="off">
                        </div>
                    </div>
                </div>

                <div class="scout-grid">
                    <?php while ($row = mysqli_fetch_assoc($result)):
                        $status = $row['Application_Status'];
                        $status_class = ($status == 'Trialing') ? 'status-blue' : 'status-yellow';
                        ?>

                        <?php
                        // If Coach, allow clicking to view profile
                        if ($user_role == 'coach') {
                            // Added &from=scout to the URL so the back button works
                            echo '<a href="playerProfile.php?player_id=' . $row['User_ID'] . '&from=scout" class="scout-card ' . $status_class . '" style="text-decoration:none; color:inherit; display:flex;">';
                        } else {
                            echo '<div class="scout-card ' . $status_class . '">';
                        }
                        ?>
                        <div class="card-header">
                            <div class="header-info">
                                <h3><?php echo htmlspecialchars($row['Name']); ?></h3>
                                <span class="pos-badge"><?php echo htmlspecialchars($row['Position']); ?></span>
                            </div>
                            <span class="status-pill"><?php echo $status; ?></span>
                        </div>
                        <div class="card-body">
                            <div class="stat-row">
                                <div class="stat"><span class="lbl">Age</span><span
                                        class="val"><?php echo $row['Age']; ?></span></div>
                                <div class="stat"><span class="lbl">Height</span><span
                                        class="val"><?php echo $row['Height']; ?>cm</span></div>
                                <div class="stat"><span class="lbl">Weight</span><span
                                        class="val"><?php echo $row['Weight']; ?>kg</span></div>
                            </div>
                            <div class="detail-block">
                                <p><strong>Prev. Club:</strong>
                                    <?php echo htmlspecialchars($row['Scouted_Player_Previous_Club']); ?></p>
                                <p><strong>Exp:</strong> <?php echo htmlspecialchars($row['Scouted_Player_Experience']); ?>
                                </p>
                            </div>
                            <div class="hidden-id" style="display:none;"><?php echo $row['User_ID']; ?></div>
                            <div class="hidden-bio" style="display:none;"><?php echo htmlspecialchars($row['Bio']); ?></div>
                        </div>

                        <?php if ($can_manage): ?>
                            <div class="card-actions">
                                <?php if ($status == 'Pending'): ?>
                                    <button class="action-btn ai-btn" onclick="event.preventDefault(); generateReport(this)">✨
                                        Evaluate Application</button>

                                    <div class="btn-group">
                                        <button class="action-btn trial-btn"
                                            onclick="event.preventDefault(); updateStatus('<?php echo $row['User_ID']; ?>', 'Trialing')">Call
                                            to Trial</button>
                                        <button class="action-btn reject-btn"
                                            onclick="event.preventDefault(); updateStatus('<?php echo $row['User_ID']; ?>', 'Rejected')">Reject</button>
                                    </div>
                                <?php elseif ($status == 'Trialing'): ?>

                                    <button class="action-btn ai-btn" onclick="event.preventDefault(); generateReport(this)">
                                        ✨ Evaluate Trialist
                                    </button>

                                    <div class="btn-group">
                                        <button class="action-btn demote-btn"
                                            onclick="event.preventDefault(); updateStatus('<?php echo $row['User_ID']; ?>', 'Pending')">
                                            Demote
                                        </button>

                                        <button class="action-btn promote-btn"
                                            onclick="event.preventDefault(); openPromoteModal('<?php echo $row['User_ID']; ?>', '<?php echo $row['Name']; ?>')">
                                            Promote
                                        </button>
                                    </div>

                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php
                        if ($user_role == 'coach') {
                            echo '</a>';
                        } else {
                            echo '</div>';
                        }
                        ?>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>

    <div id="aiModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('aiModal')">&times;</span>
            <div class="ai-header">
                <h3>🤖 Scout Analysis</h3>
            </div>
            <div id="aiLoader" class="loader" style="display:none;">Analyzing Data & Squad Depth...</div>
            <div id="aiResult" class="ai-body"></div>
            <button class="close-btn" onclick="closeModal('aiModal')">Close Report</button>
        </div>
    </div>

    <div id="promoteModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('promoteModal')">&times;</span>
            <h3>Sign Professional Contract</h3>
            <p>Promoting <span id="pName" style="font-weight:bold;"></span> to First Team.</p>
            <form id="promoteForm" action="promote_player.php" method="POST">
                <input type="hidden" name="player_id" id="promoteId">
                <div class="form-group"><label>Assign Jersey Number</label><input type="number" name="jersey_no"
                        class="form-input" required placeholder="Unique No"></div>
                <div class="form-group"><label>Monthly Salary (BDT)</label><input type="number" name="salary"
                        class="form-input" required placeholder="e.g. 50000"></div>
                <div class="form-row-split">
                    <div class="form-group"><label>Start Date</label><input type="date" name="start_date"
                            class="form-input" required value="<?php echo date('Y-m-d'); ?>"></div>
                    <div class="form-group"><label>End Date</label><input type="date" name="end_date" class="form-input"
                            required></div>
                </div>
                <button type="submit" class="save-btn promote-confirm-btn">Confirm Signing</button>
            </form>
        </div>
    </div>

    <script src="scoutedPlayers.js"></script>
</body>

</html>