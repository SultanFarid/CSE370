<?php
session_start();
require_once('dbconnect.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];

// Fetch current player data using email (since that's the link)
$query = "SELECT * FROM scouted_player WHERE scout_email = '$user_email'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $player = mysqli_fetch_assoc($result);
} else {
    $error_message = "Player data not found";
}

// Handle UPDATE
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $scout_name = $_POST['scout_name'];
    $scout_age = $_POST['scout_age'];
    $scout_date_of_birth = $_POST['scout_date_of_birth'];
    $scout_phone_no = $_POST['scout_phone_no'];
    $scout_position = $_POST['scout_position'];
    $scout_previous_club = $_POST['scout_previous_club'];
    $scout_height = $_POST['scout_height'];
    $scout_weight = $_POST['scout_weight'];
    $scout_injury_status = $_POST['scout_injury_status'];
    $scout_preferred_foot = $_POST['scout_preferred_foot'];
    
    // Update scouted_player table
    $update_query = "UPDATE scouted_player SET 
        scout_name = '$scout_name',
        scout_age = '$scout_age',
        scout_date_of_birth = '$scout_date_of_birth',
        scout_phone_no = '$scout_phone_no',
        scout_position = '$scout_position',
        scout_previous_club = '$scout_previous_club',
        scout_height = '$scout_height',
        scout_weight = '$scout_weight',
        scout_injury_status = '$scout_injury_status',
        scout_preferred_foot = '$scout_preferred_foot'
        WHERE scout_email = '$user_email'";
    
    if (mysqli_query($conn, $update_query)) {
        // Also update users table
        $update_users = "UPDATE users SET 
            age = '$scout_age',
            date_of_birth = '$scout_date_of_birth',
            phone_no = '$scout_phone_no'
            WHERE user_id = '$user_id'";
        mysqli_query($conn, $update_users);
        
        
        header("Location: scoutedPlayerProfile.php");
        exit();
    } else {
        $error_message = "Error updating profile: " . mysqli_error($conn);
    }
}

// Handle DELETE
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    // Delete from scouted_player table
    $delete_scouted = "DELETE FROM scouted_player WHERE scout_email = '$user_email'";
    
    // Delete from users table
    $delete_user = "DELETE FROM users WHERE user_id = '$user_id'";
    
    if (mysqli_query($conn, $delete_scouted) && mysqli_query($conn, $delete_user)) {
        session_destroy();
        header("Location: login.html");
        exit();
    } else {
        $error_message = "Error deleting account: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Player Profile - BUFC</title>
    <link rel="stylesheet" href="editScoutedPlayerProfile.css">
</head>
<body>
    <!-- Header -->
    <header class="site-header">
        <div class="header-inner">
            <div class="site-logo"></div>
            <div class="header-center">
                <h1 class="site-title">BUFC Player Management</h1>
                <p class="site-subtitle">Edit Your Profile</p>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-container">
        <div class="edit-card">
            <div class="card-header">
                <h2>Edit Player Profile</h2>
                <a href="scoutedPlayerProfile.php" class="back-btn">← Back to Profile</a>
            </div>

            <?php if (isset($error_message)): ?>
                <div class="alert alert-error"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <?php if (isset($player)): ?>
            <form method="POST" action="" id="editForm">
                
                <!-- Personal Information Section -->
                <div class="form-section">
                    <h3 class="section-title">Personal Information</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="scout_name">Full Name *</label>
                            <input type="text" id="scout_name" name="scout_name" 
                                   value="<?php echo htmlspecialchars($player['scout_name']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="scout_age">Age *</label>
                            <input type="number" id="scout_age" name="scout_age" min="16" max="40" 
                                   value="<?php echo htmlspecialchars($player['scout_age']); ?>" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="scout_date_of_birth">Date of Birth *</label>
                            <input type="date" id="scout_date_of_birth" name="scout_date_of_birth" 
                                   value="<?php echo htmlspecialchars($player['scout_date_of_birth']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="scout_phone_no">Phone Number *</label>
                            <input type="tel" id="scout_phone_no" name="scout_phone_no" 
                                   value="<?php echo htmlspecialchars($player['scout_phone_no']); ?>" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="scout_height">Height (cm) *</label>
                            <input type="number" id="scout_height" name="scout_height" min="150" max="220" step="0.1"
                                   value="<?php echo htmlspecialchars($player['scout_height']); ?>" 
                                   required placeholder="e.g., 175">
                        </div>

                        <div class="form-group">
                            <label for="scout_weight">Weight (kg) *</label>
                            <input type="number" id="scout_weight" name="scout_weight" min="50" max="120" step="0.1"
                                   value="<?php echo htmlspecialchars($player['scout_weight']); ?>" 
                                   required placeholder="e.g., 70">
                        </div>
                    </div>
                </div>

                <!-- Football Information Section -->
                <div class="form-section">
                    <h3 class="section-title">Football Information</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="scout_position">Playing Position *</label>
                            <select id="scout_position" name="scout_position" required>
                                <option value="">Select your position</option>
                                <option value="Goalkeeper" <?php if($player['scout_position']=='Goalkeeper') echo 'selected'; ?>>Goalkeeper</option>
                                <option value="Defender" <?php if($player['scout_position']=='Defender') echo 'selected'; ?>>Defender</option>
                                <option value="Center Back" <?php if($player['scout_position']=='Center Back') echo 'selected'; ?>>Center Back</option>
                                <option value="Full Back" <?php if($player['scout_position']=='Full Back') echo 'selected'; ?>>Full Back</option>
                                <option value="Midfielder" <?php if($player['scout_position']=='Midfielder') echo 'selected'; ?>>Midfielder</option>
                                <option value="Defensive Midfielder" <?php if($player['scout_position']=='Defensive Midfielder') echo 'selected'; ?>>Defensive Midfielder</option>
                                <option value="Attacking Midfielder" <?php if($player['scout_position']=='Attacking Midfielder') echo 'selected'; ?>>Attacking Midfielder</option>
                                <option value="Winger" <?php if($player['scout_position']=='Winger') echo 'selected'; ?>>Winger</option>
                                <option value="Forward" <?php if($player['scout_position']=='Forward') echo 'selected'; ?>>Forward</option>
                                <option value="Striker" <?php if($player['scout_position']=='Striker') echo 'selected'; ?>>Striker</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="scout_previous_club">Previous Club</label>
                            <input type="text" id="scout_previous_club" name="scout_previous_club" 
                                   value="<?php echo htmlspecialchars($player['scout_previous_club']); ?>" 
                                   placeholder="Optional">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="scout_injury_status">Injury Status *</label>
                            <select id="scout_injury_status" name="scout_injury_status" required>
                                <option value="">Select status</option>
                                <option value="Fit" <?php if($player['scout_injury_status']=='Fit') echo 'selected'; ?>>Fit</option>
                                <option value="Minor Injury" <?php if($player['scout_injury_status']=='Minor Injury') echo 'selected'; ?>>Minor Injury</option>
                                <option value="Major Injury" <?php if($player['scout_injury_status']=='Major Injury') echo 'selected'; ?>>Major Injury</option>
                                <option value="Recovering" <?php if($player['scout_injury_status']=='Recovering') echo 'selected'; ?>>Recovering</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="scout_preferred_foot">Preferred Foot *</label>
                            <select id="scout_preferred_foot" name="scout_preferred_foot" required>
                                <option value="">Select</option>
                                <option value="Right" <?php if($player['scout_preferred_foot']=='Right') echo 'selected'; ?>>Right</option>
                                <option value="Left" <?php if($player['scout_preferred_foot']=='Left') echo 'selected'; ?>>Left</option>
                                <option value="Both" <?php if($player['scout_preferred_foot']=='Both') echo 'selected'; ?>>Both</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" name="update" class="btn btn-primary">Update Profile</button>
                    <a href="scoutedPlayerProfile.php" class="btn btn-secondary">Cancel</a>
                </div>

            </form>

            <!-- Delete Account Section -->
            <div class="danger-zone">
                <h3 class="danger-title">Danger Zone</h3>
                <p class="danger-text">Once you delete your account, there is no going back. Please be certain.</p>
                <form method="POST" action="" onsubmit="return confirm('Are you absolutely sure you want to delete your account? This action cannot be undone!');">
                    <button type="submit" name="delete" class="btn btn-danger">Delete Account</button>
                </form>
            </div>

            <?php endif; ?>
        </div>
    </main>

    <script src="editScoutedPlayerProfile.js"></script>
</body>
</html>
