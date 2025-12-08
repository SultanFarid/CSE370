<?php
session_start();
require_once 'dbconnect.php';

// 1. GATEKEEPER
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['user_role'];
$user_name = $_SESSION['user_name'];

// 2. DETERMINE USER CAPABILITIES
$is_head_or_assistant = false;
$can_create_session = false;
$is_coach = false;
$coach_id = null;
$coach_data = null;

if ($role == 'coach') {
    $is_coach = true;
    $coach_query = mysqli_query($conn, "SELECT Coach_ID, Coach_Type FROM coach WHERE Coach_ID='$user_id'");
    $coach_data = mysqli_fetch_assoc($coach_query);
    $coach_id = $coach_data['Coach_ID'];
    
    if ($coach_data['Coach_Type'] == 'Head Coach' || $coach_data['Coach_Type'] == 'Assistant Coach') {
        $is_head_or_assistant = true;
        $can_create_session = true;
    }
}

// 3. FETCH DATA BASED ON ROLE
if ($role == 'regular_player' || $role == 'scouted_player') {
    // Check if scouted player is trialing
    if ($role == 'scouted_player') {
        $status_check = mysqli_query($conn, "SELECT Application_Status FROM scouted_player WHERE Scouted_Player_ID='$user_id'");
        $status_data = mysqli_fetch_assoc($status_check);
        if ($status_data['Application_Status'] != 'Trialing') {
            $no_access = true;
        }
    }
    
    // Fetch player's training sessions
    if (!isset($no_access)) {
        $query = "SELECT ts.*, tp.Technical_score, tp.Physical_score, tp.Tactical_score, 
                  tp.Coach_remarks, tp.participation_status, u.Name as Coach_Name
                  FROM training_sessions ts
                  JOIN training_participation tp ON ts.Session_id = tp.Session_id
                  JOIN users u ON tp.Coach_ID = u.User_ID
                  WHERE tp.Player_ID = '$user_id'
                  ORDER BY ts.Session_date DESC";
        $result = mysqli_query($conn, $query);
    }
} else {
    // Coach view
    if ($is_head_or_assistant) {
        $query = "SELECT ts.*, 
                  (SELECT COUNT(*) FROM training_participation WHERE Session_id = ts.Session_id) as player_count,
                  (SELECT u.Name FROM training_participation tp 
                   JOIN users u ON tp.Coach_ID = u.User_ID 
                   WHERE tp.Session_id = ts.Session_id LIMIT 1) as assigned_coach
                  FROM training_sessions ts
                  ORDER BY ts.Session_date DESC";
    } else {
        $query = "SELECT DISTINCT ts.*, 
                  (SELECT COUNT(*) FROM training_participation WHERE Session_id = ts.Session_id) as player_count
                  FROM training_sessions ts
                  JOIN training_participation tp ON ts.Session_id = tp.Session_id
                  WHERE tp.Coach_ID = '$user_id'
                  ORDER BY ts.Session_date DESC";
    }
    $result = mysqli_query($conn, $query);
}

// 4. GET PLAYER LISTS (Regular and Scouted separately)
$regular_player_list = [];
$scouted_player_list = [];
$coach_list = [];

if ($can_create_session) {
    // Regular Players
    $regular_query = "SELECT u.User_ID, u.Name, p.Position, p.Current_Injury_Status
                      FROM users u
                      JOIN player p ON u.User_ID = p.Player_ID
                      WHERE u.Role = 'regular_player'
                      ORDER BY p.Position, u.Name";
    $regular_result = mysqli_query($conn, $regular_query);
    while ($p = mysqli_fetch_assoc($regular_result)) {
        $regular_player_list[] = $p;
    }
    
    // Scouted Players (Only Trialing)
    $scouted_query = "SELECT u.User_ID, u.Name, p.Position, p.Current_Injury_Status
                      FROM users u
                      JOIN player p ON u.User_ID = p.Player_ID
                      JOIN scouted_player sp ON u.User_ID = sp.Scouted_Player_ID
                      WHERE u.Role = 'scouted_player' AND sp.Application_Status = 'Trialing'
                      ORDER BY p.Position, u.Name";
    $scouted_result = mysqli_query($conn, $scouted_query);
    while ($p = mysqli_fetch_assoc($scouted_result)) {
        $scouted_player_list[] = $p;
    }
    
    // Coaches
    $coach_list_query = mysqli_query($conn, "SELECT u.User_ID, u.Name, c.Coach_Type FROM users u 
                                             JOIN coach c ON u.User_ID = c.Coach_ID 
                                             WHERE u.Role='coach' ORDER BY u.Name");
    while ($c = mysqli_fetch_assoc($coach_list_query)) {
        $coach_list[] = $c;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Sessions - BUFC</title>
    <link rel="stylesheet" href="trainingSessions.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">
        <!-- SIDEBAR -->
        <aside class="sidebar">
            <nav class="sidebar-nav">
                <?php if ($role == 'coach'): ?>
                    <a href="coachProfile.php" class="nav-item">Profile</a>
                    <a href="mySquad.php" class="nav-item">My Squad</a>
                    <a href="medicalReport.php" class="nav-item">Medical Report</a>
                    <a href="trainingSessions.php" class="nav-item active">Training Sessions</a>
                    <a href="nextMatchSquad.php" class="nav-item">Next Match Squad</a>
                    <a href="coaches.php" class="nav-item">Coaches</a>
                    <a href="pointsTable.php" class="nav-item">Points Table</a>
                    <a href="scoutedPlayers.php" class="nav-item">Scouted Players</a>
                    <a href="fixtures.php" class="nav-item">Fixtures</a>
                <?php else: ?>
                    <a href="playerProfile.php" class="nav-item">Profile</a>
                    <?php if ($role == 'regular_player'): ?>
                        <a href="mySquad.php" class="nav-item">My Squad</a>
                        <a href="medicalReport.php" class="nav-item">Medical Report</a>
                    <?php endif; ?>
                    <a href="trainingSessions.php" class="nav-item active">Training Sessions</a>
                    <?php if ($role == 'regular_player'): ?>
                        <a href="nextMatchSquad.php" class="nav-item">Next Match Squad</a>
                        <a href="coaches.php" class="nav-item">Coaches</a>
                        <a href="pointsTable.php" class="nav-item">Points Table</a>
                        <a href="fixtures.php" class="nav-item">Fixtures</a>
                    <?php endif; ?>
                <?php endif; ?>
                <a href="logout.php" class="nav-item logout-link">Logout</a>
            </nav>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="main-content">
            <!-- HEADER -->
            <header class="top-header">
                <div class="brand-group">
                    <img src="images/bufc-logo.jpg" alt="Logo" class="header-logo">
                    <div class="header-text-group">
                        <h1>TRAINING CENTER</h1>
                        <p class="slogan">Performance & Development</p>
                    </div>
                </div>
            </header>

            <!-- TRAINING CONTAINER -->
            <div class="training-container">
                <?php if (isset($no_access)): ?>
                    <!-- Scouted player not trialing -->
                    <div class="no-access-message">
                        <h3>🏃 No Training Sessions Yet</h3>
                        <p>You'll be assigned to training sessions once your application moves to "Trialing" status.</p>
                        <p>Keep an eye on your profile for updates!</p>
                    </div>
                
                <?php elseif ($role == 'regular_player' || $role == 'scouted_player'): ?>
                    <!-- PLAYER VIEW -->
                    <div class="filter-bar">
                        <div class="filter-left">
                            <div class="filter-item">
                                <label>Week Filter</label>
                                <select id="weekFilter" class="custom-select">
                                    <option value="all">All Weeks</option>
                                    <option value="current" selected>Current Week</option>
                                    <option value="last">Last Week</option>
                                    <option value="older">Older</option>
                                </select>
                            </div>
                            
                            <div class="filter-item">
                                <label>Status</label>
                                <select id="statusFilter" class="custom-select">
                                    <option value="all">All</option>
                                    <option value="Attended">Attended</option>
                                    <option value="Absent">Absent</option>
                                    <option value="Scheduled">Scheduled</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="sessions-timeline">
                        <?php 
                        if (isset($result) && mysqli_num_rows($result) > 0):
                            $sessions_by_week = [];
                            while ($session = mysqli_fetch_assoc($result)) {
                                $week_start = date('Y-m-d', strtotime('monday this week', strtotime($session['Session_date'])));
                                $sessions_by_week[$week_start][] = $session;
                            }
                            
                            foreach ($sessions_by_week as $week_start => $sessions):
                                $week_end = date('Y-m-d', strtotime('+6 days', strtotime($week_start)));
                                $week_label = date('M d', strtotime($week_start)) . ' - ' . date('M d, Y', strtotime($week_end));
                                $today = date('Y-m-d');
                                $is_current_week = ($today >= $week_start && $today <= $week_end);
                        ?>
                        
                        <div class="week-section" data-week="<?php echo $is_current_week ? 'current' : ($week_start > date('Y-m-d', strtotime('-7 days')) ? 'last' : 'older'); ?>">
                            <h3 class="week-title">📅 <?php echo $is_current_week ? 'Current Week' : 'Week of'; ?> (<?php echo $week_label; ?>)</h3>
                            
                            <div class="session-grid">
                                <?php foreach ($sessions as $s): 
                                    $status = $s['participation_status'];
                                    if ($status == 'Attended') {
                                        $status_class = 'status-green';
                                    } elseif ($status == 'Scheduled') {
                                        $status_class = 'status-blue';
                                    } else {
                                        $status_class = 'status-red';
                                    }
                                ?>
                                
                                <div class="session-card <?php echo $status_class; ?>" data-status="<?php echo $status; ?>">
                                    <div class="card-header">
                                        <div class="header-info">
                                            <h3><?php echo $s['Session_Type']; ?></h3>
                                            <span class="date-badge"><?php echo date('D, M d, Y', strtotime($s['Session_date'])); ?></span>
                                        </div>
                                        <div class="status-pill"><?php echo $status; ?></div>
                                    </div>
                                    
                                    <div class="card-body">
                                        <p><strong>Time:</strong> <?php echo $s['Session_time']; ?></p>
                                        <p><strong>Coach:</strong> <?php echo $s['Coach_Name']; ?></p>
                                        
                                        <?php if ($status == 'Attended'): ?>
                                        <div class="stat-row">
                                            <div class="stat">
                                                <span class="lbl">Technical</span>
                                                <span class="val"><?php echo number_format($s['Technical_score'], 1); ?></span>
                                            </div>
                                            <div class="stat">
                                                <span class="lbl">Physical</span>
                                                <span class="val"><?php echo number_format($s['Physical_score'], 1); ?></span>
                                            </div>
                                            <div class="stat">
                                                <span class="lbl">Tactical</span>
                                                <span class="val"><?php echo number_format($s['Tactical_score'], 1); ?></span>
                                            </div>
                                        </div>
                                        
                                        <?php if ($s['Coach_remarks']): ?>
                                        <div class="remarks-box">
                                            <strong>Coach Remarks:</strong>
                                            <p><?php echo htmlspecialchars($s['Coach_remarks']); ?></p>
                                        </div>
                                        <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <?php 
                            endforeach;
                        else: 
                        ?>
                            <div class="no-access-message">
                                <p>No training sessions found.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                <?php else: ?>
                    <!-- COACH VIEW -->
                    <div class="filter-bar">
                        <div class="filter-left">
                            <div class="filter-item">
                                <label>Status</label>
                                <select id="sessionStatusFilter" class="custom-select">
                                    <option value="all">All</option>
                                    <option value="Scheduled">Scheduled</option>
                                    <option value="Completed">Completed</option>
                                </select>
                            </div>
                            
                            <div class="filter-item">
                                <label>Type</label>
                                <select id="sessionTypeFilter" class="custom-select">
                                    <option value="all">All</option>
                                    <option value="Team Tactical Training">Tactical</option>
                                    <option value="Fitness & Conditioning">Fitness</option>
                                    <option value="Attacking Drills">Attacking</option>
                                    <option value="Technical Skills">Technical</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="filter-right">
                            <?php if ($can_create_session): ?>
                                <button class="add-session-btn" onclick="openAddModal()">Create New Session</button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="session-grid">
                        <?php 
                        if (mysqli_num_rows($result) > 0):
                            while ($session = mysqli_fetch_assoc($result)):
                                $status = $session['Session_status'];
                                $card_class = $status == 'Completed' ? 'status-green' : 'status-blue';
                                $is_future = strtotime($session['Session_date']) > time();
                                
                                // Check if this coach is assigned to this session
                                $check_assigned = mysqli_query($conn, "SELECT COUNT(*) as count FROM training_participation 
                                                                       WHERE Session_id = '{$session['Session_id']}' 
                                                                       AND Coach_ID = '$user_id'");
                                $assigned_data = mysqli_fetch_assoc($check_assigned);
                                $is_assigned = ($assigned_data['count'] > 0);
                        ?>
                        
                        <div class="session-card <?php echo $card_class; ?>" 
                             data-status="<?php echo $status; ?>" 
                             data-type="<?php echo $session['Session_Type']; ?>">
                            <div class="card-header">
                                <div class="header-info">
                                    <h3><?php echo $session['Session_Type']; ?></h3>
                                    <span class="date-badge"><?php echo date('D, M d, Y', strtotime($session['Session_date'])); ?></span>
                                </div>
                                <div class="status-pill"><?php echo $status; ?></div>
                            </div>
                            
                            <div class="card-body">
                                <p><strong>Time:</strong> <?php echo $session['Session_time']; ?></p>
                                <p><strong>Players:</strong> <?php echo $session['player_count']; ?></p>
                                <?php if ($is_head_or_assistant && isset($session['assigned_coach'])): ?>
                                <p><strong>Coach:</strong> <?php echo $session['assigned_coach']; ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="card-actions">
                                <?php if ($status == 'Scheduled' && !$is_future && $is_assigned): ?>
                                    <button class="action-btn trial-btn" onclick="completeSession(<?php echo $session['Session_id']; ?>)">
                                        ✅ Mark Complete
                                    </button>
                                <?php elseif ($status == 'Completed'): ?>
                                    <button class="action-btn promote-btn" onclick="viewSessionDetails(<?php echo $session['Session_id']; ?>)">
                                        View Details
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php 
                            endwhile;
                        else: 
                        ?>
                            <div class="no-access-message">
                                <p>No training sessions found.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if ($can_create_session): ?>
    <!-- ADD SESSION MODAL -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addModal')">&times;</span>
            <h3>Create New Training Session</h3>
            
            <form id="createSessionForm">
                <div class="form-row-split">
                    <div class="form-group">
                        <label>Session Date*</label>
                        <input type="date" name="session_date" class="form-input" required 
                               min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Session Time*</label>
                        <select name="session_time" class="form-input" required>
                            <option value="">Select Time</option>
                            <option value="Morning">Morning</option>
                            <option value="Afternoon">Afternoon</option>
                            <option value="Evening">Evening</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Session Type*</label>
                    <select name="session_type" class="form-input" required>
                        <option value="">Select Type</option>
                        <option value="Team Tactical Training">Team Tactical Training</option>
                        <option value="Fitness & Conditioning">Fitness & Conditioning</option>
                        <option value="Attacking Drills">Attacking Drills</option>
                        <option value="Technical Skills">Technical Skills</option>
                        <option value="Defensive Structure">Defensive Structure</option>
                        <option value="Match Preparation">Match Preparation</option>
                        <option value="Recovery Session">Recovery Session</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Assign Coach*</label>
                    <select name="assigned_coach" class="form-input" required>
                        <option value="">Select Coach</option>
                        <?php foreach ($coach_list as $coach): ?>
                            <option value="<?php echo $coach['User_ID']; ?>">
                                <?php echo $coach['Name']; ?> (<?php echo $coach['Coach_Type']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- TABS FOR PLAYER SELECTION -->
                <div class="form-group">
                    <label>Select Players*</label>
                    
                    <div class="player-tabs">
                        <button type="button" class="tab-btn active" onclick="switchPlayerTab('regular')">
                            Regular Players (<?php echo count($regular_player_list); ?>)
                        </button>
                        <button type="button" class="tab-btn" onclick="switchPlayerTab('scouted')">
                            Scouted Players (<?php echo count($scouted_player_list); ?>)
                        </button>
                    </div>
                    
                    <!-- Regular Players -->
                    <div id="regularPlayersSection" class="player-section active">
                        <div class="quick-actions">
                            <button type="button" class="quick-btn" onclick="selectByPosition('Goalkeeper', 'regular')">All GK</button>
                            <button type="button" class="quick-btn" onclick="selectByPosition('Defender', 'regular')">All DEF</button>
                            <button type="button" class="quick-btn" onclick="selectByPosition('Midfielder', 'regular')">All MID</button>
                            <button type="button" class="quick-btn" onclick="selectByPosition('Striker', 'regular')">All FW</button>
                            <button type="button" class="quick-btn" onclick="selectOnlyFit('regular')">Only Fit</button>
                            <button type="button" class="quick-btn reject-btn" onclick="clearAllPlayers('regular')">Clear</button>
                        </div>
                        
                        <div class="players-checklist">
                            <?php 
                            $positions = ['Goalkeeper', 'Defender', 'Midfielder', 'Striker', 'Winger'];
                            foreach ($positions as $pos):
                                $pos_players = array_filter($regular_player_list, fn($p) => $p['Position'] == $pos);
                                if (count($pos_players) > 0):
                            ?>
                                <div class="position-group">
                                    <h4><?php echo $pos; ?>s</h4>
                                    <?php foreach ($pos_players as $player): 
                                        $disabled = $player['Current_Injury_Status'] == 'Injured' ? 'disabled' : '';
                                        $class = $disabled ? 'player-injured' : '';
                                    ?>
                                        <label class="player-checkbox <?php echo $class; ?>">
                                            <input type="checkbox" name="regular_players[]" value="<?php echo $player['User_ID']; ?>" 
                                                   data-position="<?php echo $player['Position']; ?>"
                                                   data-injury="<?php echo $player['Current_Injury_Status']; ?>"
                                                   data-type="regular"
                                                   <?php echo $disabled; ?>>
                                            <?php echo $player['Name']; ?> 
                                            <span class="player-status">[<?php echo $player['Current_Injury_Status']; ?>]</span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                    </div>
                    
                    <!-- Scouted Players -->
                    <div id="scoutedPlayersSection" class="player-section">
                        <div class="quick-actions">
                            <button type="button" class="quick-btn" onclick="selectByPosition('Goalkeeper', 'scouted')">All GK</button>
                            <button type="button" class="quick-btn" onclick="selectByPosition('Defender', 'scouted')">All DEF</button>
                            <button type="button" class="quick-btn" onclick="selectByPosition('Midfielder', 'scouted')">All MID</button>
                            <button type="button" class="quick-btn" onclick="selectByPosition('Striker', 'scouted')">All FW</button>
                            <button type="button" class="quick-btn" onclick="selectOnlyFit('scouted')">Only Fit</button>
                            <button type="button" class="quick-btn reject-btn" onclick="clearAllPlayers('scouted')">Clear</button>
                        </div>
                        
                        <div class="players-checklist">
                            <?php 
                            if (count($scouted_player_list) > 0):
                                foreach ($positions as $pos):
                                    $pos_players = array_filter($scouted_player_list, fn($p) => $p['Position'] == $pos);
                                    if (count($pos_players) > 0):
                            ?>
                                <div class="position-group">
                                    <h4><?php echo $pos; ?>s</h4>
                                    <?php foreach ($pos_players as $player): 
                                        $disabled = $player['Current_Injury_Status'] == 'Injured' ? 'disabled' : '';
                                        $class = $disabled ? 'player-injured' : '';
                                    ?>
                                        <label class="player-checkbox <?php echo $class; ?>">
                                            <input type="checkbox" name="scouted_players[]" value="<?php echo $player['User_ID']; ?>" 
                                                   data-position="<?php echo $player['Position']; ?>"
                                                   data-injury="<?php echo $player['Current_Injury_Status']; ?>"
                                                   data-type="scouted"
                                                   <?php echo $disabled; ?>>
                                            <?php echo $player['Name']; ?> 
                                            <span class="player-status">[<?php echo $player['Current_Injury_Status']; ?>] 🔍</span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            <?php 
                                    endif;
                                endforeach;
                            else:
                            ?>
                                <p style="text-align: center; color: #64748b; padding: 20px;">
                                    No scouted players in trialing status.
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="promote-confirm-btn">Create Session</button>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <!-- VIEW SESSION MODAL -->
    <div id="viewModal" class="modal">
        <div class="modal-content modal-wide">
            <span class="close" onclick="closeModal('viewModal')">&times;</span>
            <h3>Session Details</h3>
            <div id="viewContent"></div>
        </div>
    </div>

    <script src="trainingSessions.js"></script>
</body>
</html>
