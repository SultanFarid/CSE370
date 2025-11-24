<?php
session_start();
require_once('dbconnect.php');

// Check if user came from signup
if (!isset($_SESSION['signup_username']) || !isset($_SESSION['signup_email']) || !isset($_SESSION['signup_password'])) {
    header("Location: login.html");
    exit();
}

// Get signup data from session
$signup_username = $_SESSION['signup_username'];
$signup_email = $_SESSION['signup_email'];
$signup_password = $_SESSION['signup_password'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $scout_name = $_POST['player_name'];
    $scout_age = $_POST['age'];
    $scout_date_of_birth = $_POST['date_of_birth'];
    $scout_phone_no = $_POST['phone_no'];
    $scout_position = $_POST['position'];
    $scout_previous_club = $_POST['previous_club'];
    $scout_height = $_POST['height'];
    $scout_weight = $_POST['weight'];
    $scout_injury_status = $_POST['injury_status'];
    $scout_preferred_foot = $_POST['preferred_foot'];
    $scout_experience = $_POST['experience'];

    // Insert into scouted_players table
    $insert_scouted = "INSERT INTO scouted_player
        (scout_name, scout_email, scout_password, scout_age, scout_date_of_birth, scout_phone_no, scout_position, scout_previous_club, scout_height, scout_weight, scout_injury_status, scout_preferred_foot, scout_experience)
        VALUES 
        ('$scout_name', '$signup_email', '$signup_password', '$scout_age', '$scout_date_of_birth', '$scout_phone_no', '$scout_position', '$scout_previous_club', '$scout_height', '$scout_weight', '$scout_injury_status', '$scout_preferred_foot', '$scout_experience')";
    
    if (mysqli_query($conn, $insert_scouted)) {
        // Also insert into users table for login purposes
        $insert_user = "INSERT INTO users 
            (user_name, user_email, user_password, user_type, age, date_of_birth, phone_no) 
            VALUES 
            ('$signup_username', '$signup_email', '$signup_password', 'scouted', '$scout_age', '$scout_date_of_birth', '$scout_phone_no')";
        
        if (mysqli_query($conn, $insert_user)) {
            // Get the user_id that was just created
            $user_id = mysqli_insert_id($conn);
            
            // Set session variables
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $scout_name;
            $_SESSION['user_email'] = $signup_email;
            
            // Clear signup session data
            unset($_SESSION['signup_username']);
            unset($_SESSION['signup_email']);
            unset($_SESSION['signup_password']);
            
            // Redirect to profile
            header("Location: scoutedPlayerProfile.php");
            exit();
        } else {
            $error_message = "Error creating user account: " . mysqli_error($conn);
        }
    } else {
        $error_message = "Error registering player: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scouted Player Registration - BUFC</title>
    <link rel="stylesheet" href="scoutedPlayerRegistration.css">
</head>
<body>
    <!-- Header -->
    <header class="site-header">
        <div class="header-inner">
            <div class="site-logo"></div>
            <div class="header-center">
                <h1 class="site-title">BUFC Player Management</h1>
                <p class="site-subtitle">Complete Your Registration</p>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-container">
        <div class="registration-card">
            <div class="card-header">
                <h2>Player Information</h2>
                <p class="card-subtitle">Please provide your details to complete registration</p>
            </div>

            <?php if (isset($error_message)): ?>
                <div class="alert alert-error"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form method="POST" action="" id="registrationForm">
                
                <!-- Basic Information -->
                <div class="form-section">
                    <div class="form-group">
                        <label for="player_name">Full Name *</label>
                        <input type="text" id="player_name" name="player_name" 
                               required placeholder="Enter your full name">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="age">Age *</label>
                            <input type="number" id="age" name="age" min="16" max="40" 
                                   required placeholder="e.g., 22">
                        </div>

                        <div class="form-group">
                            <label for="date_of_birth">Date of Birth *</label>
                            <input type="date" id="date_of_birth" name="date_of_birth" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone_no">Phone Number *</label>
                        <input type="tel" id="phone_no" name="phone_no" 
                               required placeholder="e.g., +880 1234-567890">
                    </div>
                </div>

                <!-- Physical Information -->
                <div class="form-section">
                    <h3 class="section-divider">Physical Information</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="height">Height (cm) *</label>
                            <input type="number" id="height" name="height" 
                                   min="150" max="220" step="0.1" 
                                   required placeholder="e.g., 175">
                            <small class="field-hint">Enter height in centimeters</small>
                        </div>

                        <div class="form-group">
                            <label for="weight">Weight (kg) *</label>
                            <input type="number" id="weight" name="weight" 
                                   min="50" max="120" step="0.1" 
                                   required placeholder="e.g., 70">
                            <small class="field-hint">Enter weight in kilograms</small>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="injury_status">Injury Status *</label>
                            <select id="injury_status" name="injury_status" required>
                                <option value="">Select status</option>
                                <option value="Fit">Fit</option>
                                <option value="Minor Injury">Minor Injury</option>
                                <option value="Major Injury">Major Injury</option>
                                <option value="Recovering">Recovering</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="preferred_foot">Preferred Foot *</label>
                            <select id="preferred_foot" name="preferred_foot" required>
                                <option value="">Select foot</option>
                                <option value="Right">Right</option>
                                <option value="Left">Left</option>
                                <option value="Both">Both</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Football Information -->
                <div class="form-section">
                    <h3 class="section-divider">Football Information</h3>
                    
                    <div class="form-group">
                        <label for="position">Playing Position *</label>
                        <select id="position" name="position" required>
                            <option value="">Select your position</option>
                            <option value="Goalkeeper">Goalkeeper</option>
                            <option value="Defender">Defender</option>
                            <option value="Center Back">Center Back</option>
                            <option value="Full Back">Full Back</option>
                            <option value="Midfielder">Midfielder</option>
                            <option value="Defensive Midfielder">Defensive Midfielder</option>
                            <option value="Attacking Midfielder">Attacking Midfielder</option>
                            <option value="Winger">Winger</option>
                            <option value="Forward">Forward</option>
                            <option value="Striker">Striker</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="previous_club">Previous Club/Team *</label>
                        <input type="text" id="previous_club" name="previous_club" 
                               required placeholder="e.g., BUFC Academy">
                    </div>

                    <div class="form-group">
                        <label for="experience">Years of Experience *</label>
                        <input type="number" id="experience" name="experience" 
                            min="0" max="30" required placeholder="e.g., 5">
                    </div>
                </div>

                <!-- Email (Read-only) -->
                <div class="form-section">
                    <div class="form-group">
                        <label for="player_email">Email (pre-filled from signup)</label>
                        <input type="email" id="player_email" 
                               value="<?php echo htmlspecialchars($signup_email); ?>" 
                               readonly class="readonly-field">
                        <small class="field-hint">Email cannot be changed</small>
                    </div>
                </div>

                <!-- Error Display -->
                <div id="formErrors" class="form-errors"></div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit">Complete Registration</button>

                <p class="form-footer">
                    By registering, you agree to BUFC's terms and conditions.
                    <br>
                    <a href="login.html" class="link-back">Return to login</a>
                </p>
            </form>
        </div>
    </main>

    <script src="scoutedPlayerRegistration.js"></script>
</body>
</html>
