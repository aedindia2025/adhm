<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(0);

// DB Credentials
$host = 'localhost';
$db = 'adi_dravidar';
$user = 'root';
$pass = '4/rb5sO2s3TpL4gu';

$current_date = date('Y-m-d');
$start_time = date('H:i:s');
$un_id = uniqid();
$execution_error = true; // Default to error, only mark success after completion

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// ===== Check for Previous Incomplete Cron =====
$check = $conn->query("SELECT end_time FROM sync_att_record_cron ORDER BY id DESC LIMIT 1");
if ($check && $row = $check->fetch_assoc()) {
    if (empty($row['end_time'])) {
        echo "Previous cron still running or did not finish. Exiting...\n";
        $conn->close();
        exit;
    }
}
$check->close();

// ===== Insert New Cron Start Record =====
$stmt = $conn->prepare("INSERT INTO sync_att_record_cron (`current_date`, `start_time`, `un_id`) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $current_date, $start_time, $un_id);
$stmt->execute();
$stmt->close();

// ===== Ensure End Time is Always Updated Even on Fatal Error =====
register_shutdown_function(function () use ($conn, $un_id) {
    $end_time = date('H:i:s');
    $stmt = $conn->prepare("UPDATE sync_att_record_cron SET `end_time` = ? WHERE `un_id` = ?");
    $stmt->bind_param("ss", $end_time, $un_id);
    $stmt->execute();
    $stmt->close();
    $conn->close();
});

try {
    $query = "SELECT id, userId, currentDate, std_reg_no, userName, attendanceDateTime,
                     hostel_id, hostel_unique_id, district_name, taluk_name
              FROM attendance_queue
              WHERE processing_status = 0 AND std_reg_no IS NOT NULL";

    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $v_id = $row['id'];
        $v_userId = $row['userId'];
        $v_currentDate = $row['currentDate'];
        $v_std_reg_no = $row['std_reg_no'];
        $v_userName = $row['userName'];
        $v_attendanceDateTime = $row['attendanceDateTime'];
        $v_hostel_id = $row['hostel_id'];
        $v_hostel_unique_id = $row['hostel_unique_id'];
        $v_district_name = $row['district_name'];
        $v_taluk_name = $row['taluk_name'];

        $dt = DateTime::createFromFormat('d/m/Y h:i A', $v_attendanceDateTime);
        if (!$dt) continue;

        $v_punchTime = $dt->format('H:i:s');
        $hour = (int)$dt->format('H');
        $v_punchColumn = ($hour >= 6 && $hour <= 9) ? 'MORNING' : (($hour >= 19 && $hour <= 22) ? 'EVENING' : 'NOON');

        $v_districtNameValue = fetchSingleValue($conn, "SELECT district_name FROM district_name WHERE unique_id = ? LIMIT 1", $v_district_name);
        $v_talukNameValue = fetchSingleValue($conn, "SELECT taluk_name FROM taluk_creation WHERE unique_id = ? LIMIT 1", $v_taluk_name);
        $v_hostelName = fetchSingleValue($conn, "SELECT hostel_name FROM hostel_name WHERE unique_id = ? LIMIT 1", $v_hostel_unique_id);
        $v_dropoutValue = fetchSingleValue($conn, "SELECT dropout_status FROM std_reg_s WHERE std_reg_no = ? ORDER BY academic_year DESC LIMIT 1", $v_std_reg_no) ?? '1';

        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM dayattreport WHERE currentDate = ? AND std_reg_no = ?");
        $checkStmt->bind_param("ss", $v_currentDate, $v_std_reg_no);
        $checkStmt->execute();
        $checkStmt->bind_result($exists);
        $checkStmt->fetch();
        $checkStmt->close();

        if ($exists) {
            switch ($v_punchColumn) {
                case 'MORNING':
                    $update = $conn->prepare("UPDATE dayattreport SET punch_mrg = ? WHERE currentDate = ? AND std_reg_no = ?");
                    break;
                case 'NOON':
                    $update = $conn->prepare("UPDATE dayattreport SET punch_noon = ? WHERE currentDate = ? AND std_reg_no = ?");
                    break;
                case 'EVENING':
                    $update = $conn->prepare("UPDATE dayattreport SET punch_eve = ? WHERE currentDate = ? AND std_reg_no = ?");
                    break;
            }
            $update->bind_param("sss", $v_punchTime, $v_currentDate, $v_std_reg_no);
            $update->execute();
            $update->close();
        } else {
            $punch_mrg = $v_punchColumn === 'MORNING' ? $v_punchTime : null;
            $punch_noon = $v_punchColumn === 'NOON' ? $v_punchTime : null;
            $punch_eve = $v_punchColumn === 'EVENING' ? $v_punchTime : null;

            $insert = $conn->prepare("INSERT INTO dayattreport (
                currentDate, userId, std_reg_no, userName,
                punch_mrg, punch_noon, punch_eve,
                hostel_id, hostel_unique_id, district_name, taluk_name,
                district_name_value, taluk_name_value, hostel_name, dropout_status
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $insert->bind_param(
                "sssssssssssssss",
                $v_currentDate, $v_userId, $v_std_reg_no, $v_userName,
                $punch_mrg, $punch_noon, $punch_eve,
                $v_hostel_id, $v_hostel_unique_id, $v_district_name, $v_taluk_name,
                $v_districtNameValue, $v_talukNameValue, $v_hostelName, $v_dropoutValue
            );
            $insert->execute();
            $insert->close();
        }

        $updateQueue = $conn->prepare("UPDATE attendance_queue SET processing_status = 1 WHERE id = ?");
        $updateQueue->bind_param("i", $v_id);
        $updateQueue->execute();
        $updateQueue->close();
    }

    $execution_error = false; // Only here it's considered successful

} catch (Throwable $e) {
    error_log("Error: " . $e->getMessage());
}

if ($execution_error) {
    echo "Execution failed. Cron end time updated.\n";
} else {
    echo "completed\n";
}

// === Helper Function ===
function fetchSingleValue($conn, $sql, $param)
{
    $val = null;
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $param);
        $stmt->execute();
        $stmt->bind_result($val);
        $stmt->fetch();
        $stmt->close();
    }
    return $val;
}
