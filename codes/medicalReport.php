<?php
session_start();
require_once('dbconnect.php');

// GATEKEEPER
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['user_role'];
$is_physio = false;

// CHECK IF COACH IS A PHYSIO
if ($role == 'coach') {
    $c_q = "SELECT Coach_Type FROM Coach WHERE Coach_ID = '$user_id'";
    $c_res = mysqli_query($conn, $c_q);
    $c_row = mysqli_fetch_assoc($c_res);
    if ($c_row && $c_row['Coach_Type'] == 'Physio') {
        $is_physio = true;
    }
}

// HANDLE FORM SUBMISSIONS (PHYSIO ONLY)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $is_physio) {

    // UPDATE EXISTING RECORD
    if (isset($_POST['update_injury'])) {
        $pres_id = $_POST['pres_id'];
        $player_id_target = $_POST['player_id'];
        $new_status = $_POST['recovery_status'];
        $new_end_date = $_POST['injured_to'];

        // Update Medical Record Table
        $update_q = "UPDATE Medical_Record
                     SET Recovery_status = '$new_status', Injured_to = '$new_end_date' 
                     WHERE Prescription_ID = '$pres_id'";

        if (mysqli_query($conn, $update_q)) {

            // Updating player's status
            $p_status = 'Injured'; // Default

            if ($new_status == 'Healed') {
                $p_status = 'Fit';
            } elseif ($new_status == 'Rehabilitating' || $new_status == 'Physiotherapy') {
                $p_status = 'Recovering';
            } elseif ($new_status == 'Treatment') {
                $p_status = 'Injured';
            }

            mysqli_query($conn, "UPDATE Player SET Current_Injury_Status = '$p_status' WHERE Player_ID = '$player_id_target'");

            echo "<script>alert('Record Updated! Player Status synced.'); window.location='medicalReport.php';</script>";
        }
    }

    // ADD NEW INJURY
    if (isset($_POST['add_injury'])) {
        $target_player = $_POST['target_player_id'];
        $injury_name = mysqli_real_escape_string($conn, $_POST['injury_name']);
        $doctor = mysqli_real_escape_string($conn, $_POST['doctor']);
        $hospital = mysqli_real_escape_string($conn, $_POST['hospital']);
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $status = $_POST['status'];

        $insert_q = "INSERT INTO Medical_Record (Player_ID, Doctor_in_charge, Recovery_status, Injury_Type, Injured_from, Injured_to)
             VALUES ('$target_player', '$doctor', '$status', '$injury_name', '$start_date', '$end_date')";

        if (mysqli_query($conn, $insert_q)) {
            // Update Player Status immediately
            $p_status = 'Injured';
            if ($status == 'Doubtful')
                $p_status = 'Doubtful';

            mysqli_query($conn, "UPDATE Player SET Current_Injury_Status = '$p_status' WHERE Player_ID = '$target_player'");
            echo "<script>alert('New Injury Report Added.'); window.location='medicalReport.php';</script>";
        }
    }
}

// FETCH DATA
$player_list = [];
if ($is_physio) {
    $p_sql = "SELECT u.User_ID, u.Name FROM users u JOIN Player p ON u.User_ID = p.Player_ID ORDER BY u.Name ASC";
    $p_res = mysqli_query($conn, $p_sql);
    while ($row = mysqli_fetch_assoc($p_res)) {
        $player_list[] = $row;
    }
}


if ($role == 'regular_player') {
    $query = "SELECT m.*, u.Name, d.Hospital 
              FROM Medical_Record m 
              JOIN users u ON m.Player_ID = u.User_ID 
              JOIN doctors d ON m.Doctor_in_charge = d.Doctor_Name
              WHERE m.Player_ID = '$user_id' 
              ORDER BY m.Injured_from DESC";
} else {
    $query = "SELECT m.*, u.Name, d.Hospital 
              FROM Medical_Record m 
              JOIN users u ON m.Player_ID = u.User_ID 
              JOIN doctors d ON m.Doctor_in_charge = d.Doctor_Name
              ORDER BY m.Injured_from DESC";
}
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Report - BUFC</title>
    <link rel="stylesheet" href="medicalReport.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body>

    <div class="dashboard-container">

        <aside class="sidebar">
            <nav class="sidebar-nav">
                <?php if ($role == 'coach'): ?>
                    <a href="coachProfile.php" class="nav-item">Profile</a>
                    <a href="mySquad.php" class="nav-item">My Squad</a>
                    <a href="medicalReport.php" class="nav-item active">Medical Report</a>
                    <a href="trainingSessions.php" class="nav-item">Training Sessions</a>
                    <a href="nextMatchSquad.php" class="nav-item">Next Match Squad</a>
                    <a href="coaches.php" class="nav-item">Coaches</a>
                    <a href="pointsTable.php" class="nav-item">Points Table</a>
                    <a href="scoutedPlayers.php" class="nav-item">Scouted Players</a>
                    <a href="fixtures.php" class="nav-item">Fixtures</a>
                <?php else: ?>
                    <a href="playerProfile.php" class="nav-item">Profile</a>
                    <a href="mySquad.php" class="nav-item">My Squad</a>
                    <a href="medicalReport.php" class="nav-item active">Medical Report</a>
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
                        <h1>MEDICAL CENTER</h1>
                        <p class="slogan">Injury Tracking & Recovery</p>
                    </div>
                </div>
            </header>

            <div class="content-wrapper fade-in">

                <div class="page-header">
                    <h2>Injury Reports</h2>
                    <?php if ($is_physio): ?>
                        <button class="add-btn" onclick="openAddModal()">+ Add New Report</button>
                    <?php endif; ?>
                </div>

                <?php if ($role == 'coach'): ?>
                    <div class="filter-container" style="margin-bottom: 25px; display: flex; gap: 20px; flex-wrap: wrap;">
                        <div class="filter-group" style="flex: 1; min-width: 200px;">
                            <input type="text" id="searchInput" class="form-input"
                                placeholder="Search by Player Name or Injury..." autocomplete="off">
                        </div>
                        <div class="filter-group" style="width: 200px;">
                            <select id="statusFilter" class="form-select">
                                <option value="all">All Statuses</option>
                                <option value="Critical">Critical</option>
                                <option value="Injured">Injured</option>
                                <option value="Rehabilitating">Rehabilitating</option>
                                <option value="Physiotherapy">Physiotherapy</option>
                                <option value="Healed">Healed</option>
                            </select>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="injury-grid">
                    <?php while ($row = mysqli_fetch_assoc($result)):
                        $status_class = 'card-orange';
                        $s = strtolower($row['Recovery_status']);
                        if (strpos($s, 'heal') !== false)
                            $status_class = 'card-green';
                        elseif (strpos($s, 'critic') !== false || strpos($s, 'tear') !== false)
                            $status_class = 'card-red';
                        ?>

                        <div class="injury-card <?php echo $status_class; ?>">
                            <div class="card-top">
                                <div class="player-info">
                                    <span class="player-name"><?php echo htmlspecialchars($row['Name']); ?></span>
                                    <span class="injury-name"><?php echo htmlspecialchars($row['Injury_Type']); ?></span>
                                </div>
                                <div class="status-pill">
                                    <?php echo htmlspecialchars($row['Recovery_status']); ?>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="detail-row">
                                    <span class="label">Date:</span>
                                    <span class="value"><?php echo $row['Injured_from']; ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Est. End:</span>
                                    <span class="value"><?php echo $row['Injured_to']; ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Hospital:</span>
                                    <span class="value"><?php echo htmlspecialchars($row['Hospital']); ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Doctor:</span>
                                    <span class="value"><?php echo htmlspecialchars($row['Doctor_in_charge']); ?></span>
                                </div>
                            </div>

                            <?php if ($is_physio): ?>
                                <div class="card-footer">
                                    <button class="edit-card-btn"
                                        onclick="openEditModal('<?php echo $row['Prescription_ID']; ?>', '<?php echo $row['Player_ID']; ?>', '<?php echo $row['Recovery_status']; ?>', '<?php echo $row['Injured_to']; ?>')">
                                        Update Status
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>

                    <?php endwhile; ?>
                </div>

            </div>
        </div>
    </div>

    <?php if ($is_physio): ?>
        <div id="editModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('editModal')">&times;</span>
                <h3>Update Recovery</h3>
                <form method="POST" action="">
                    <input type="hidden" name="update_injury" value="1">
                    <input type="hidden" id="modal_pres_id" name="pres_id">
                    <input type="hidden" id="modal_player_id" name="player_id">

                    <div class="form-group">
                        <label>Recovery Status</label>
                        <select name="recovery_status" id="modal_status" class="form-select">
                            <option value="Critical">Critical</option>
                            <option value="Treatment">Treatment</option>
                            <option value="Rehabilitating">Rehabilitating</option>
                            <option value="Physiotherapy">Physiotherapy</option>
                            <option value="Healed">Healed</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Estimated End Date</label>
                        <input type="date" name="injured_to" id="modal_date" class="form-input" required>
                    </div>
                    <button type="submit" class="save-btn">Save Updates</button>
                </form>
            </div>
        </div>

        <div id="addModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('addModal')">&times;</span>
                <h3>Report New Injury</h3>
                <form method="POST" action="">
                    <input type="hidden" name="add_injury" value="1">

                    <div class="form-group">
                        <label>Select Player</label>
                        <select name="target_player_id" class="form-select" required>
                            <option value="">-- Choose Player --</option>
                            <?php foreach ($player_list as $p): ?>
                                <option value="<?php echo $p['User_ID']; ?>"><?php echo htmlspecialchars($p['Name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-row-split">
                        <div class="form-group">
                            <label>Injury Type</label>
                            <input type="text" name="injury_name" class="form-input" placeholder="e.g. ACL Tear" required>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-select">
                                <option value="Injured">Injured</option>
                                <option value="Critical">Critical</option>
                                <option value="Doubtful">Doubtful</option>
                                <option value="Minor Injury">Minor Injury</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row-split">
                        <div class="form-group">
                            <label>Date of Injury</label>
                            <input type="date" name="start_date" class="form-input" value="<?php echo date('Y-m-d'); ?>"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Est. Recovery</label>
                            <input type="date" name="end_date" class="form-input" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Select Doctor (3NF Reference)</label>
                        <select name="doctor" class="form-select" required>
                            <option value="">-- Choose Assigned Doctor --</option>
                            <?php
                            $doc_res = mysqli_query($conn, "SELECT Doctor_Name FROM doctors ORDER BY Doctor_Name ASC");
                            while ($d = mysqli_fetch_assoc($doc_res)) {
                                $name = htmlspecialchars($d['Doctor_Name']);
                                echo "<option value='$name'>$name</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <button type="submit" class="save-btn add-confirm-btn">Add Report</button>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <script src="medicalReport.js"></script>
</body>

</html>