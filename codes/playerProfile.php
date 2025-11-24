<?php
session_start();
require_once('dbconnect.php');

// Check if user is logged in, if not redirect to login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Get user data from database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BUFC - Player Dashboard</title>
    <link rel="stylesheet" href="playerProfile.css">
</head>
<body>
    <!-- Header matching login page -->
    <header class="site-header">
        <div class="header-inner">
            <div class="header-left">
                <div class="site-logo" title="BUFC logo"></div>
            </div>
            <div class="header-center">
                <div class="big-welcome">Player Dashboard</div>
                <div class="subtitle">BRAC University Football Club</div>
            </div>
            <div class="header-right">
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>
    </header>

    <main class="dashboard-container">
        <!-- Welcome Section -->
        <section class="welcome-section">
            <div class="welcome-card">
                <h2>Welcome, <?php echo $user['user_name']; ?>!</h2>
                <p>Player ID: <?php echo $user['user_id']; ?></p>
            </div>
        </section>

        <!-- Navigation Grid -->
        <section class="nav-grid">
            <div class="nav-card" onclick="location.href='home.php'">
                <h3>Home Page</h3>
                <p>Main dashboard</p>
            </div>
            
            <div class="nav-card" onclick="location.href='profile.php'">
                <h3>Profile</h3>
                <p>Your personal info</p>
            </div>
            
            <div class="nav-card" onclick="location.href='squad.php'">
                <h3>My Squad</h3>
                <p>Team members</p>
            </div>
            
            <div class="nav-card" onclick="location.href='medical.php'">
                <h3>Medical Report</h3>
                <p>Health status</p>
            </div>
            
            <div class="nav-card" onclick="location.href='training.php'">
                <h3>Training Sessions</h3>
                <p>Schedule & drills</p>
            </div>
            
            <div class="nav-card" onclick="location.href='nextmatch.php'">
                <h3>Next Match Squad</h3>
                <p>Lineup & tactics</p>
            </div>
            
            <div class="nav-card" onclick="location.href='points.php'">
                <h3>Points Table</h3>
                <p>League standings</p>
            </div>
            
            <div class="nav-card" onclick="location.href='fixtures.php'">
                <h3>Fixtures</h3>
                <p>Match schedule</p>
            </div>
        </section>
    </main>

    <script src="playerProfile.js"></script>
</body>
</html>