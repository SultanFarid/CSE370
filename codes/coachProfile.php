<?php
session_start();
require_once('dbconnect.php');

// GATEKEEPER
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$current_user_id = $_SESSION['user_id'];

// DETERMINE TARGET COACH & VIEW MODE
$target_id = $current_user_id; // Default to self
$is_viewing_others = false;

// Switch to "Viewing Other" mode if the ID is not yours
if (isset($_GET['coach_id']) && $_GET['coach_id'] != $current_user_id) {
    $target_id = $_GET['coach_id'];
    $is_viewing_others = true;
}

// FETCH COACH DATA
$query = "SELECT * FROM users u 
          JOIN Coach c ON u.User_ID = c.Coach_ID 
          WHERE u.User_ID = '$target_id'";

$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) == 0) {
    echo "Coach not found.";
    exit();
}
$data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coach Profile - BUFC</title>
    <link rel="stylesheet" href="coachProfile.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body>

    <div class="dashboard-container">

        <aside class="sidebar">
            <nav class="sidebar-nav">
                <a href="coachProfile.php"
                    class="nav-item <?php echo !$is_viewing_others ? 'active' : ''; ?>">Profile</a>

                <a href="mySquad.php" class="nav-item">My Squad</a>
                <a href="medicalReport.php" class="nav-item">Medical Report</a>
                <a href="trainingSessions.php" class="nav-item">Training Sessions</a>
                <a href="nextMatchSquad.php" class="nav-item">Next Match Squad</a>

                <?php if ($is_viewing_others): ?>
                    <a href="coaches.php" class="nav-item active">Coaches</a>
                <?php else: ?>
                    <a href="coaches.php" class="nav-item">Coaches</a>
                <?php endif; ?>

                <a href="pointsTable.php" class="nav-item">Points Table</a>
                <?php if ($_SESSION['user_role'] == 'coach'): ?>
                    <a href="scoutedPlayers.php" class="nav-item">Scouted Players</a>
                <?php endif; ?>
                <a href="fixtures.php" class="nav-item">Fixtures</a>

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
                <?php if ($is_viewing_others): ?>
                    <div class="back-btn-wrapper">
                        <a href="coaches.php" class="back-btn">
                            <span>&#8592;</span> Back to Staff List
                        </a>
                    </div>
                <?php endif; ?>

                <div class="top-section">
                    <div class="photo-box">
                        <div class="photo-placeholder">
                            <img src="images/coaches/<?php echo $target_id; ?>.jpg" alt="Coach Photo"
                                onerror="this.style.display='none'; this.parentNode.innerText='Photo'">
                        </div>
                    </div>

                    <div class="details-box">
                        <div class="details-header">
                            <div class="header-left-group">
                                <h2 class="player-name"><?php echo htmlspecialchars($data['Name']); ?></h2>
                                <span class="role-pill"><?php echo htmlspecialchars($data['Coach_Type']); ?></span>
                            </div>

                            <?php if (!$is_viewing_others): ?>
                                <a href="editCoachProfile.php" class="edit-btn">Edit Profile</a>
                            <?php endif; ?>
                        </div>

                        <div class="info-grid">
                            <div class="info-item">
                                <label>Age</label>
                                <div class="val"><?php echo htmlspecialchars($data['Age']); ?> Years</div>
                            </div>
                            <div class="info-item">
                                <label>Date of Birth</label>
                                <div class="val"><?php echo htmlspecialchars($data['Date_of_Birth']); ?></div>
                            </div>
                            <div class="info-item">
                                <label>Phone</label>
                                <div class="val"><?php echo htmlspecialchars($data['Phone_No']); ?></div>
                            </div>

                            <div class="info-item">
                                <label>Experience</label>
                                <div class="val"><?php echo htmlspecialchars($data['Coach_Experience']); ?></div>
                            </div>
                            <div class="info-item">
                                <label>Speciality</label>
                                <div class="val"><?php echo htmlspecialchars($data['Coach_Speciality']); ?></div>
                            </div>
                            <div class="info-item">
                                <label>Availability</label>
                                <div class="val status-fit"><?php echo htmlspecialchars($data['Coach_Availability']); ?>
                                </div>
                            </div>

                            <div class="info-item full">
                                <label>Previous Club</label>
                                <div class="val"><?php echo htmlspecialchars($data['Coach_Previous_Club']); ?></div>
                            </div>

                            <div class="info-item">
                                <label>NID</label>
                                <div class="val"><?php echo $data['NID'] ? htmlspecialchars($data['NID']) : 'N/A'; ?>
                                </div>
                            </div>

                            <div class="info-item full">
                                <label>Email</label>
                                <div class="val"><?php echo htmlspecialchars($data['Email']); ?></div>
                            </div>
                            <div class="info-item full">
                                <label>Address</label>
                                <div class="val">
                                    <?php echo $data['Address'] ? htmlspecialchars($data['Address']) : 'N/A'; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (!$is_viewing_others): ?>
                    <hr class="divider-line">
                    <div class="bottom-section">
                        <h3>Contract Information</h3>
                        <div class="info-content">
                            <div class="stats-row">
                                <div class="stat-pill">
                                    <span class="lbl">Salary</span>
                                    <span class="num"
                                        style="color: #16a34a; font-size: 1.3rem;">$<?php echo number_format($data['salary']); ?></span>
                                </div>
                                <div class="stat-pill">
                                    <span class="lbl">Start Date</span>
                                    <span class="num"
                                        style="font-size: 1rem;"><?php echo $data['contract_start_date']; ?></span>
                                </div>
                                <div class="stat-pill">
                                    <span class="lbl">End Date</span>
                                    <span class="num"
                                        style="font-size: 1rem;"><?php echo $data['contract_end_date']; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <script src="coachProfile.js"></script>
</body>

</html>