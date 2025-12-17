<?php
require_once('dbconnect.php');

$apiKey = "#";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $player_id = $_POST['player_id'];
    $status = $_POST['status'];

    // FETCH BASIC STATS
    $sql_basic = "SELECT u.Name, u.Age, p.Position, p.Height, p.Weight, p.Preferred_foot, 
                  sp.Scouted_Player_Experience, sp.Scouted_Player_Previous_Club, sp.Bio
                  FROM users u 
                  JOIN Player p ON u.User_ID = p.Player_ID 
                  JOIN Scouted_Player sp ON p.Player_ID = sp.Scouted_Player_ID
                  WHERE u.User_ID = '$player_id'";
    $res_basic = mysqli_query($conn, $sql_basic);
    $basic = mysqli_fetch_assoc($res_basic);
    $pos = $basic['Position'];

    // FETCH SQUAD CONTEXT
    $sql_depth = "SELECT COUNT(*) as total, SUM(CASE WHEN Current_Injury_Status = 'Fit' THEN 1 ELSE 0 END) as fit 
                  FROM regular_player rp JOIN player p ON rp.Regular_Player_ID = p.Player_ID 
                  WHERE p.Position = '$pos'";
    $res_depth = mysqli_query($conn, $sql_depth);
    $depth = mysqli_fetch_assoc($res_depth);

    // PROMPT
    $prompt = "";

    if ($status == 'Pending') {
        // PENDING APPLICANT
        $prompt = "Act as a Senior Football Scout. Analyze this applicant for a trial.
        
        CANDIDATE: Name: {$basic['Name']}, Position: {$basic['Position']}, Age: {$basic['Age']}, Height: {$basic['Height']}cm, Weight: {$basic['Weight']}kg.
        EXP: {$basic['Scouted_Player_Experience']}. BIO: \"{$basic['Bio']}\".
        SQUAD CONTEXT: We have {$depth['total']} players in this position ({$depth['fit']} fit).
        
        STRICT OUTPUT FORMAT:
        1. First line MUST be: 'VERDICT: [CALL TO TRIAL / REJECT] in Bold'
        2. Followed by exactly 3 short bullet points (max 20-25 words each) explaining why.
        3. Do not write anything else.";

    } else {
        // TRIALIST (TRAINING DATA)
        $sql_train = "SELECT AVG(Technical_score) as tech, AVG(Physical_score) as phys, AVG(Tactical_score) as tact, 
                      GROUP_CONCAT(Coach_remarks SEPARATOR ' | ') as remarks
                      FROM Training_Participation WHERE Player_ID = '$player_id'";
        $res_train = mysqli_query($conn, $sql_train);
        $train = mysqli_fetch_assoc($res_train);

        $tech = round($train['tech'], 1) ?: "N/A";
        $phys = round($train['phys'], 1) ?: "N/A";
        $tact = round($train['tact'], 1) ?: "N/A";
        $remarks = $train['remarks'] ?: "No sessions completed.";

        $prompt = "Act as a Technical Director. Evaluate this Trialist for a Professional Contract.
        
        PLAYER: {$basic['Name']} ({$basic['Position']}), Age: {$basic['Age']}, Body: {$basic['Height']}cm / {$basic['Weight']}kg.
        
        PERFORMANCE DATA (Avg/10): 
        - Technical: $tech
        - Physical: $phys
        - Tactical: $tact
        
        COACH REMARKS: \"$remarks\".
        
        SQUAD CONTEXT: We have {$depth['total']} players in this position.
        
        TASK: Look critically at Training Scores/Remarks. Is he performing at a professional level? 
        STRICT OUTPUT FORMAT:
        1. First line MUST be: 'VERDICT: [SIGN / RELEASE]' in bold
        2. Followed by exactly 3 short bullet points (max 10 words each) based on training data.
        3. Do not write anything else.";
    }

    // SENDING TO GEMINI
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $apiKey;
    $data = ["contents" => [["parts" => [["text" => $prompt]]]]];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $json = json_decode($response, true);

    if (isset($json['candidates'][0]['content']['parts'][0]['text'])) {
        $raw = $json['candidates'][0]['content']['parts'][0]['text'];
        echo nl2br(preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $raw));
    } else {
        echo "<b>GOOGLE ERROR:</b><br>";
        var_dump($json);
    }
    curl_close($ch);
}
?>