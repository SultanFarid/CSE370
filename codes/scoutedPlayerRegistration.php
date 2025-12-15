<?php
session_start();
require_once('dbconnect.php');

// GATEKEEPER
if (!isset($_SESSION['signup_email']) || !isset($_SESSION['signup_password'])) {
    header("Location: login.html");
    exit();
}

$signup_email = $_SESSION['signup_email'];
$signup_password = $_SESSION['signup_password'];
$error_message = "";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get Data
    $name = $_POST['player_name'];
    $age = $_POST['age'];
    $dob = $_POST['date_of_birth'];
    $phone = $_POST['phone_no'];

    // NID HANDLING 
    $nid_input = $_POST['nid'];
    if (empty($nid_input)) {
        $nid_value = "NULL";
    } else {
        $nid_value = "'$nid_input'";
    }

    $address = $_POST['address'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $foot = $_POST['preferred_foot'];
    $position = $_POST['position'];
    $injury = $_POST['injury_status'];
    $prev_club = $_POST['previous_club'];
    $experience = $_POST['experience'];
    $bio = $_POST['bio'];

    try {
        // Create User
        $sql_user = "INSERT INTO users (Name, Age, NID, Email, Address, Phone_No, Date_of_Birth, Password, Role) 
                     VALUES ('$name', '$age', $nid_value, '$signup_email', '$address', '$phone', '$dob', '$signup_password', 'scouted_player')";

        if (!mysqli_query($conn, $sql_user)) {
            throw new Exception(mysqli_error($conn));
        }

        $new_id = mysqli_insert_id($conn);

        // Create Player Profile
        $sql_player = "INSERT INTO Player (Player_ID, Position, Preferred_foot, Height, Weight, Current_Injury_Status)
                       VALUES ('$new_id', '$position', '$foot', '$height', '$weight', '$injury')";

        if (!mysqli_query($conn, $sql_player)) {
            throw new Exception(mysqli_error($conn));
        }

        // Create Scout Application
        $sql_scout = "INSERT INTO Scouted_Player (Scouted_Player_ID, Scouted_Player_Experience, Scouted_Player_Previous_Club, Bio)
                      VALUES ('$new_id', '$experience', '$prev_club', '$bio')";

        if (!mysqli_query($conn, $sql_scout)) {
            throw new Exception(mysqli_error($conn));
        }


        // Set the Session Variables
        $_SESSION['user_id'] = $new_id;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $signup_email;
        $_SESSION['user_role'] = 'scouted_player';

        // Clear the temporary signup variables
        unset($_SESSION['signup_email']);
        unset($_SESSION['signup_password']);

        // 3. Redirect directly to the Player Dashboard
        header("Location: playerProfile.php");
        exit();

    } catch (Exception $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration - BUFC</title>
    <link rel="stylesheet" href="scoutedPlayerRegistration.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body>

    <header class="site-header">
        <div class="header-container">
            <div class="brand-area">
                <img src="images/bufc-logo.jpg" alt="BUFC Logo" class="header-logo">
                <span class="brand-name">BUFC</span>
            </div>
            <div class="slogan-area">
                <p>Welcome to BUFC <span class="divider">|</span> <span class="highlight">Together We Triumph</span></p>
            </div>
        </div>
    </header>

    <main class="main-container">
        <div class="registration-card fade-in">
            <div class="card-header">
                <h2>Player Application</h2>
                <p>Complete your profile to join the trials.</p>
            </div>

            <?php if ($error_message != ""): ?>
                <div class="form-errors">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" id="registrationForm">

                <div class="form-section">
                    <h3 class="section-title">Personal Details</h3>
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" id="player_name" name="player_name" required
                            placeholder="Enter your full name">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Age</label>
                            <input type="number" id="age" name="age" min="16" max="40" required>
                        </div>
                        <div class="form-group">
                            <label>Date of Birth</label>
                            <input type="date" id="date_of_birth" name="date_of_birth" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>National ID (Optional)</label>
                        <input type="text" id="nid" name="nid" placeholder="Enter NID Number (if available)">
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" id="address" name="address" required placeholder="Full Address">
                    </div>

                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="tel" id="phone_no" name="phone_no" required placeholder="+880...">
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="section-title">Physical Stats</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Height (cm)</label>
                            <input type="number" id="height" name="height" min="150" max="220" step="0.1" required>
                        </div>
                        <div class="form-group">
                            <label>Weight (kg)</label>
                            <input type="number" id="weight" name="weight" min="50" max="120" step="0.1" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Preferred Foot</label>
                            <div class="select-wrapper">
                                <select name="preferred_foot" id="preferred_foot" required>
                                    <option value="">Select</option>
                                    <option value="Right">Right</option>
                                    <option value="Left">Left</option>
                                    <option value="Both">Both</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Injury Status</label>
                            <div class="select-wrapper">
                                <select name="injury_status" id="injury_status" required>
                                    <option value="Fit">Fit</option>
                                    <option value="Recovering">Recovering</option>
                                    <option value="Injured">Injured</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="section-title">Football Profile</h3>
                    <div class="form-group">
                        <label>Position</label>
                        <div class="select-wrapper">
                            <select name="position" id="position" required>
                                <option value="">Select Position</option>
                                <option value="Goalkeeper">Goalkeeper</option>
                                <option value="Defender">Defender</option>
                                <option value="Midfielder">Midfielder</option>
                                <option value="Striker">Striker</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Previous Club/Academy</label>
                            <input type="text" name="previous_club" id="previous_club" required>
                        </div>
                        <div class="form-group">
                            <label>Experience (Years)</label>
                            <input type="text" name="experience" id="experience" required
                                placeholder="e.g. U-19 District Team">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Bio / Self Description</label>
                        <textarea name="bio" id="bio" required
                            placeholder="Tell us about your playing style..."></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label>Account Email</label>
                    <input type="text" value="<?php echo htmlspecialchars($signup_email); ?>" readonly
                        class="readonly-field">
                </div>

                <div class="button-group">
                    <button type="submit" class="btn-submit">Submit Application</button>
                    <a href="login.html" class="btn-cancel">Cancel Application</a>
                </div>
            </form>
        </div>
    </main>
    <script src="scoutedPlayerRegistration.js"></script>
</body>

</html>