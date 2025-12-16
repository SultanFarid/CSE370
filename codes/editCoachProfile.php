<?php
session_start();
require_once('dbconnect.php');

// GATEKEEPER
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'coach') {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// HANDLE UPDATES
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']); // NOW EDITABLE
    $age = $_POST['age'];
    $dob = $_POST['dob'];
    $phone = $_POST['phone'];
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $nid_input = $_POST['nid'];
    if (empty($nid_input)) {
        $nid_sql = "NULL";
    } else {
        $nid_sql = "'$nid_input'";
    }

    // Coach Specific Fields
    $experience = mysqli_real_escape_string($conn, $_POST['experience']);
    $speciality = mysqli_real_escape_string($conn, $_POST['speciality']);
    $availability = $_POST['availability'];
    $prev_club = mysqli_real_escape_string($conn, $_POST['prev_club']);

    // Update Users Table
    $q1 = "UPDATE users SET 
           Name='$name',
           Age='$age', 
           Date_of_Birth='$dob', 
           Phone_No='$phone', 
           Address='$address', 
           NID=$nid_sql
           WHERE User_ID='$user_id'";
    mysqli_query($conn, $q1);

    // Update Coach Table
    $q2 = "UPDATE Coach SET 
           Coach_Experience='$experience', 
           Coach_Speciality='$speciality', 
           Coach_Availability='$availability',
           Coach_Previous_Club='$prev_club'
           WHERE Coach_ID='$user_id'";
    mysqli_query($conn, $q2);

    // Update Session Name if they changed it
    $_SESSION['user_name'] = $name;

    echo "<script>alert('Profile Updated Successfully!'); window.location='coachProfile.php';</script>";
    exit();
}

// FETCH CURRENT DATA
$query = "SELECT * FROM users u JOIN Coach c ON u.User_ID = c.Coach_ID WHERE u.User_ID = '$user_id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - BUFC</title>
    <link rel="stylesheet" href="coachProfile.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .edit-input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 1rem;
            color: var(--text-main);
            background: #ffffff;
            font-family: inherit;
            box-sizing: border-box;
        }

        .edit-input:focus {
            border-color: var(--brand-blue);
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .save-btn {
            background: var(--brand-gradient);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            font-size: 1rem;
        }

        .cancel-btn {
            color: #64748b;
            text-decoration: none;
            font-weight: 600;
            margin-right: 20px;
        }

        .form-actions {
            margin-top: 30px;
            text-align: right;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .readonly-msg {
            font-size: 0.7rem;
            color: #ef4444;
            margin-top: 4px;
            font-weight: 500;
        }

        .val.readonly {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>
</head>

<body>

    <div class="dashboard-container">

        <aside class="sidebar">
            <nav class="sidebar-nav">
                <a href="coachProfile.php" class="nav-item active">Back to Profile 🠈</a>
                <a href="logout.php" class="nav-item logout-link">Logout</a>
            </nav>
        </aside>

        <div class="main-content">

            <header class="top-header">
                <div class="brand-group">
                    <img src="images/bufc-logo.jpg" alt="Logo" class="header-logo">
                    <div class="header-text-group">
                        <h1>EDIT PROFILE</h1>
                        <p class="slogan">Update your details</p>
                    </div>
                </div>
            </header>

            <div class="profile-container fade-in">
                <form method="POST" action="">

                    <div class="top-section">
                        <div class="photo-box">
                            <div class="photo-placeholder">
                                <img src="images/coaches/<?php echo $user_id; ?>.jpg" alt="Photo"
                                    onerror="this.style.display='none'; this.parentNode.innerText='Photo'">
                            </div>
                        </div>

                        <div class="details-box">
                            <div class="details-header">
                                <div class="header-left-group">
                                    <h2 class="player-name">Editing: <?php echo htmlspecialchars($data['Name']); ?></h2>
                                </div>
                            </div>

                            <div class="info-grid">

                                <div class="info-item full">
                                    <label>Full Name</label>
                                    <input type="text" name="name" class="edit-input"
                                        value="<?php echo htmlspecialchars($data['Name']); ?>" required>
                                </div>

                                <div class="info-item">
                                    <label>Age</label>
                                    <input type="number" name="age" class="edit-input"
                                        value="<?php echo htmlspecialchars($data['Age']); ?>" required>
                                </div>

                                <div class="info-item">
                                    <label>Date of Birth</label>
                                    <input type="date" name="dob" class="edit-input"
                                        value="<?php echo htmlspecialchars($data['Date_of_Birth']); ?>" required>
                                </div>

                                <div class="info-item">
                                    <label>Phone No</label>
                                    <input type="text" name="phone" class="edit-input"
                                        value="<?php echo htmlspecialchars($data['Phone_No']); ?>" required>
                                </div>

                                <div class="info-item">
                                    <label>Experience</label>
                                    <input type="text" name="experience" class="edit-input"
                                        value="<?php echo htmlspecialchars($data['Coach_Experience']); ?>" required>
                                </div>

                                <div class="info-item">
                                    <label>Speciality</label>
                                    <input type="text" name="speciality" class="edit-input"
                                        value="<?php echo htmlspecialchars($data['Coach_Speciality']); ?>" required>
                                </div>

                                <div class="info-item">
                                    <label>Availability</label>
                                    <select name="availability" class="edit-input">
                                        <option value="<?php echo $data['Coach_Availability']; ?>" selected>
                                            <?php echo $data['Coach_Availability']; ?>
                                        </option>
                                        <option value="Active">Active</option>
                                        <option value="On Leave">On Leave</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>

                                <div class="info-item full">
                                    <label>Previous Club</label>
                                    <input type="text" name="prev_club" class="edit-input"
                                        value="<?php echo htmlspecialchars($data['Coach_Previous_Club']); ?>">
                                </div>

                                <div class="info-item">
                                    <label>NID</label>
                                    <input type="text" name="nid" class="edit-input"
                                        value="<?php echo htmlspecialchars($data['NID']); ?>">
                                </div>

                                <div class="info-item full">
                                    <label>Email</label>
                                    <div class="val readonly"><?php echo htmlspecialchars($data['Email']); ?></div>
                                </div>

                                <div class="info-item full">
                                    <label>Address</label>
                                    <input type="text" name="address" class="edit-input"
                                        value="<?php echo htmlspecialchars($data['Address']); ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="coachProfile.php" class="cancel-btn">Cancel</a>
                        <button type="submit" class="save-btn">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="editCoachProfile.js"></script>
</body>

</html>