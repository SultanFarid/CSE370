<?php
session_start();
require_once('dbconnect.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_email = $_SESSION['user_email'];

// Fetch player data from scouted_players table
$query = "SELECT * FROM scouted_player WHERE scout_email = '$user_email'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $player = mysqli_fetch_assoc($result);
} else {
    $error_message = "Player data not found";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Profile - BUFC</title>
    <link rel="stylesheet" href="scoutedPlayerProfile.css">
</head>
<body>
    <!-- Header -->
    <header class="site-header">
        <div class="header-inner">
            <div class="site-logo"></div>
            <div class="header-center">
                <h1 class="site-title">BUFC Player Management</h1>
                <p class="site-subtitle">Scout Player Dashboard</p>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <div class="dashboard-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h3>Menu</h3>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li class="active">
                        <a href="#" class="nav-link" data-target="profile-section">
                            <span class="nav-icon">👤</span>
                            <span>Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link" data-target="training-section">
                            <span class="nav-icon">⚽</span>
                            <span>Training</span>
                        </a>
                    </li>
                    <li>
                        <a href="logout.php" class="nav-link logout-link">
                            <span class="nav-icon">🚪</span>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">

            <!-- Profile Section -->
            <section id="profile-section" class="content-section active">
                <div class="section-header">
                    <h2>Player Profile</h2>
                    <a href="editScoutedPlayerProfile.php" class="btn-edit">
                        <span class="edit-icon">✏️</span> Edit Profile
                    </a>
                </div>

                <?php if (isset($player)): ?>
                <div class="profile-container">
                    
                    <!-- Photo Section -->
                    <div class="profile-photo">
                        <div class="photo-placeholder">
                            <span class="photo-icon">📷</span>
                            <p>No Photo</p>
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="info-card">
                        <h4 class="card-title">Personal Information</h4>
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Full Name:</label>
                                <span><?php echo htmlspecialchars($player['scout_name']); ?></span>
                            </div>
                            <div class="info-item">
                                <label>Email:</label>
                                <span><?php echo htmlspecialchars($player['scout_email']); ?></span>
                            </div>
                            <div class="info-item">
                                <label>Date of Birth:</label>
                                <span><?php echo htmlspecialchars($player['scout_date_of_birth']); ?></span>
                            </div>
                            <div class="info-item">
                                <label>Age:</label>
                                <span><?php echo htmlspecialchars($player['scout_age']); ?> years</span>
                            </div>
                            <div class="info-item">
                                <label>Phone:</label>
                                <span><?php echo htmlspecialchars($player['scout_phone_no']); ?></span>
                            </div>
                            <div class="info-item">
                                <label>Height:</label>
                                <span><?php echo $player['scout_height'] ? htmlspecialchars($player['scout_height']) . ' cm' : '-'; ?></span>
                            </div>
                            <div class="info-item">
                                <label>Weight:</label>
                                <span><?php echo $player['scout_weight'] ? htmlspecialchars($player['scout_weight']) . ' kg' : '-'; ?></span>
                            </div>
                            <div class="info-item">
                                <label>Injury Status:</label>
                                <span class="status-badge <?php echo strtolower(str_replace(' ', '-', $player['scout_injury_status'] ?? 'unknown')); ?>">
                                    <?php echo $player['scout_injury_status'] ? htmlspecialchars($player['scout_injury_status']) : '-'; ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Football Information -->
                    <div class="info-card">
                        <h4 class="card-title">Football Information</h4>
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Position:</label>
                                <span class="highlight"><?php echo htmlspecialchars($player['scout_position']); ?></span>
                            </div>
                            <div class="info-item">
                                <label>Previous Club:</label>
                                <span><?php echo $player['scout_previous_club'] ? htmlspecialchars($player['scout_previous_club']) : '-'; ?></span>
                            </div>
                            <div class="info-item">
                                <label>Preferred Foot:</label>
                                <span><?php echo $player['scout_preferred_foot'] ? htmlspecialchars($player['scout_preferred_foot']) : '-'; ?></span>
                            </div>
                            <div class="info-item">
                                <label>Experience:</label>
                                <span><?php echo $player['scout_experience'] ? htmlspecialchars($player['scout_experience']) . ' years' : '-'; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                    <div class="alert alert-error">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
            </section>

            <!-- Training Section -->
            <section id="training-section" class="content-section">
                <div class="section-header">
                    <h2>Training Sessions</h2>
                </div>

                <div class="info-card">
                    <h4 class="card-title">Training Session Data</h4>
                    <p class="placeholder-text">
                        Training session information will be displayed here once the training session table is set up.
                    </p>
                    <div class="placeholder-info">
                        <p>This section will show:</p>
                        <ul>
                            <li>Upcoming training sessions</li>
                            <li>Session schedules and timings</li>
                            <li>Training focus areas</li>
                            <li>Coach instructions</li>
                        </ul>
                    </div>
                </div>
            </section>

        </main>
    </div>

    <script src="scoutedPlayerProfile.js"></script>
</body>
</html>
