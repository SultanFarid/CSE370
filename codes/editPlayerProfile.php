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

// HANDLE UPDATES
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $age = $_POST['age'];
    $dob = $_POST['dob'];
    $phone = $_POST['phone'];
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $foot = $_POST['foot'];
    $nid_input = $_POST['nid'];
    if (empty($nid_input)) {
        $nid_sql = "NULL";
    } else {
        $nid_sql = "'$nid_input'";
    }

    // --- LOGIC FOR POSITION & INJURY ---
    $curr_q = "SELECT * FROM users u JOIN Player p ON u.User_ID = p.Player_ID WHERE u.User_ID = '$user_id'";
    $curr_res = mysqli_fetch_assoc(mysqli_query($conn, $curr_q));

    if ($role == 'regular_player') {
        // Regular: Position and Injury are fixed
        $position = $curr_res['Position'];
        $injury = $curr_res['Current_Injury_Status'];
    } else {
        // Scouted: Position and Injury are editable
        $position = $_POST['position'];
        $injury = $_POST['injury_status'];
    }

    // Updating Users Table
    $q1 = "UPDATE users SET 
           Name='$name', 
           Age='$age', 
           Date_of_Birth='$dob', 
           Phone_No='$phone', 
           Address='$address',
           NID=$nid_sql
           WHERE User_ID='$user_id'";
    mysqli_query($conn, $q1);

    // Updating Player Table
    $q2 = "UPDATE Player SET 
           Height='$height', 
           Weight='$weight', 
           Preferred_foot='$foot',
           Position='$position',
           Current_Injury_Status='$injury'
           WHERE Player_ID='$user_id'";
    mysqli_query($conn, $q2);

    // Updating Bio (for Scouted players Only)
    if ($role == 'scouted_player' && isset($_POST['bio'])) {
        $bio = mysqli_real_escape_string($conn, $_POST['bio']);
        $q3 = "UPDATE Scouted_Player SET Bio='$bio' WHERE Scouted_Player_ID='$user_id'";
        mysqli_query($conn, $q3);
    }

    echo "<script>alert('Profile Updated Successfully!'); window.location='playerProfile.php';</script>";
    exit();
}

// FETCH CURRENT DATA
$query_base = "SELECT * FROM users u JOIN Player p ON u.User_ID = p.Player_ID WHERE u.User_ID = '$user_id'";
$result_base = mysqli_query($conn, $query_base);
$base = mysqli_fetch_assoc($result_base);

$bio_text = "";
if ($role == 'scouted_player') {
    $res = mysqli_query($conn, "SELECT Bio FROM Scouted_Player WHERE Scouted_Player_ID='$user_id'");
    $row = mysqli_fetch_assoc($res);
    $bio_text = $row['Bio'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - BUFC</title>
    <link rel="stylesheet" href="playerProfile.css">
    <link rel="stylesheet" href="editPlayerProfile.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body>

    <div class="dashboard-container">

        <aside class="sidebar">
            <nav class="sidebar-nav">
                <a href="playerProfile.php" class="nav-item active">Back to Profile</a>
                <a href="logout.php" class="nav-item logout-link">Logout</a>
            </nav>
        </aside>

        <div class="main-content">
            <header class="top-header">
                <div class="brand-group">
                    <img src="images/bufc-logo.jpg" alt="Logo" class="header-logo">
                    <div class="header-text-group">
                        <h1>EDIT PROFILE</h1>
                        <p class="slogan">Update your information</p>
                    </div>
                </div>
            </header>

            <div class="profile-container fade-in">
                <form method="POST" action="">

                    <div class="top-section">
                        <div class="photo-box">
                            <div class="photo-placeholder">
                                <img src="images/players/<?php echo $user_id; ?>.jpg" alt="Photo"
                                    onerror="this.style.display='none'; this.parentNode.innerText='Photo'">
                            </div>
                        </div>

                        <div class="details-box">
                            <div class="details-header">
                                <div class="header-left-group">
                                    <h2 class="player-name">Editing: <?php echo htmlspecialchars($base['Name']); ?></h2>
                                </div>
                            </div>

                            <div class="info-grid">

                                <div class="info-item full">
                                    <label>Full Name</label>
                                    <input type="text" name="name" class="edit-input"
                                        value="<?php echo htmlspecialchars($base['Name']); ?>" required>
                                </div>

                                <div class="info-item">
                                    <label>Age</label>
                                    <input type="number" name="age" class="edit-input"
                                        value="<?php echo htmlspecialchars($base['Age']); ?>" required>
                                </div>

                                <div class="info-item">
                                    <label>Date of Birth</label>
                                    <input type="date" name="dob" class="edit-input"
                                        value="<?php echo htmlspecialchars($base['Date_of_Birth']); ?>" required>
                                </div>

                                <div class="info-item">
                                    <label>Phone No</label>
                                    <input type="text" name="phone" class="edit-input"
                                        value="<?php echo htmlspecialchars($base['Phone_No']); ?>" required>
                                </div>

                                <div class="info-item">
                                    <label>Position</label>
                                    <?php if ($role == 'regular_player'): ?>
                                        <div class="val readonly"><?php echo $base['Position']; ?></div>
                                        <div class="readonly-msg">Contact Coach to change</div>
                                    <?php else: ?>
                                        <select name="position" class="edit-input">
                                            <option value="<?php echo $base['Position']; ?>" selected>
                                                <?php echo $base['Position']; ?>
                                            </option>
                                            <option value="Goalkeeper">Goalkeeper</option>
                                            <option value="Defender">Defender</option>
                                            <option value="Midfielder">Midfielder</option>
                                            <option value="Striker">Striker</option>
                                        </select>
                                    <?php endif; ?>
                                </div>

                                <div class="info-item">
                                    <label>Foot</label>
                                    <select name="foot" class="edit-input">
                                        <option value="<?php echo $base['Preferred_foot']; ?>" selected>
                                            <?php echo $base['Preferred_foot']; ?>
                                        </option>
                                        <option value="Right">Right</option>
                                        <option value="Left">Left</option>
                                        <option value="Both">Both</option>
                                    </select>
                                </div>

                                <div class="info-item">
                                    <label>Injury Status</label>
                                    <?php if ($role == 'scouted_player'): ?>
                                        <select name="injury_status" class="edit-input">
                                            <option value="<?php echo $base['Current_Injury_Status']; ?>" selected>
                                                <?php echo $base['Current_Injury_Status']; ?>
                                            </option>
                                            <option value="Fit">Fit</option>
                                            <option value="Recovering">Recovering</option>
                                            <option value="Injured">Injured</option>
                                        </select>
                                    <?php else: ?>
                                        <div class="val readonly"><?php echo $base['Current_Injury_Status']; ?></div>
                                        <div class="readonly-msg">Only Physio can update</div>
                                    <?php endif; ?>
                                </div>

                                <div class="info-item">
                                    <label>Height (cm)</label>
                                    <input type="number" name="height" step="0.1" class="edit-input"
                                        value="<?php echo htmlspecialchars($base['Height']); ?>" required>
                                </div>

                                <div class="info-item">
                                    <label>Weight (kg)</label>
                                    <input type="number" name="weight" step="0.1" class="edit-input"
                                        value="<?php echo htmlspecialchars($base['Weight']); ?>" required>
                                </div>

                                <div class="info-item">
                                    <label>NID</label>
                                    <input type="text" name="nid" class="edit-input"
                                        value="<?php echo htmlspecialchars($base['NID']); ?>">
                                </div>

                                <div class="info-item full">
                                    <label>Email</label>
                                    <div class="val readonly"><?php echo htmlspecialchars($base['Email']); ?></div>
                                </div>

                                <div class="info-item full">
                                    <label>Address</label>
                                    <input type="text" name="address" class="edit-input"
                                        value="<?php echo htmlspecialchars($base['Address']); ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="divider-line">

                    <?php if ($role == 'scouted_player'): ?>
                        <div class="bottom-section">
                            <h3>Update Bio</h3>
                            <div class="info-content">
                                <textarea name="bio" class="edit-input"
                                    style="min-height: 100px; resize: vertical; width: 100%;"><?php echo htmlspecialchars($bio_text); ?></textarea>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="form-actions">
                        <a href="playerProfile.php" class="cancel-btn">Cancel</a>
                        <button type="submit" class="save-btn">Save Changes</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script src="editPlayerProfile.js"></script>
</body>

</html>