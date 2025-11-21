<?php
// =====================================================
//  DATABASE CONNECTION
// =====================================================
$host = "localhost";
$username = "root";
$password = "4/rb5sO2s3TpL4gu";
$databasename = "adi_dravidar";

$mysqli = new mysqli($host, $username, $password, $databasename);
if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: " . $mysqli->connect_error);
}

// =====================================================
//  DATE CALCULATIONS
// =====================================================
$prevMonth = date('Y-m', strtotime('first day of last month'));
$firstDate = date('Y-m-01', strtotime('first day of last month'));
$lastDate  = date('Y-m-t', strtotime('first day of last month'));

$nextMonth = date('Y-m', strtotime('first day of this month'));

echo "===== Automated Indent Count for $nextMonth (based on $prevMonth attendance) =====\n\n";

// =====================================================
//  GET ALL HOSTELS
// =====================================================
$hostelSql = "SELECT hostel_id, unique_id, district_name, hostel_type 
              FROM hostel_name 
              WHERE is_active=1 AND is_delete=0";
$resHostel = $mysqli->query($hostelSql);

$debug_summary = []; // <-- ADD THIS ARRAY

while ($hostel = $resHostel->fetch_assoc()) {

    $hostel_id = $hostel['hostel_id'];
    $hostel_unique_id = $hostel['unique_id'];
    $district_name = $mysqli->real_escape_string($hostel['district_name']);
    $hostel_type = $mysqli->real_escape_string($hostel['hostel_type']);

    $is_online = 0;
    $base_count = 0;
    $final_count = 0;
    $percentage_applied = null;
    $calc_method = "-";

    // =====================================================
    //  CHECK ONLINE / OFFLINE
    // =====================================================
    $daysSql = "SELECT COUNT(DISTINCT report_date) AS days
                FROM attendance_report
                WHERE hostel_unique_id='$hostel_unique_id'
                AND report_date BETWEEN '$firstDate' AND '$lastDate'";

    $daysPresent = (int)$mysqli->query($daysSql)->fetch_assoc()['days'];
    $is_online = ($daysPresent >= 5) ? 1 : 0;

    // =====================================================
    //  ONLINE HOSTEL CALCULATION
    // =====================================================
    if ($is_online) {
        $attSql = "SELECT morning_punch_count, noon_punch_count, eve_punch_count
                   FROM attendance_report 
                   WHERE hostel_unique_id='$hostel_unique_id'
                   AND report_date BETWEEN '$firstDate' AND '$lastDate'";
        $resAtt = $mysqli->query($attSql);

        $dailyCounts = [];

        while ($att = $resAtt->fetch_assoc()) {

            $morning = (int)$att['morning_punch_count'];
            $noon = (int)$att['noon_punch_count'];
            $evening = (int)$att['eve_punch_count'];

            $maxME = max($morning, $evening);

            if ($maxME < (0.5 * $noon)) {
                $daily = round(($morning + $noon + $evening) / 3);
                $calc_method = "Avg(3 meals)";
            } else {
                $daily = max($maxME, $noon);
                $calc_method = "Max(M/E vs Noon)";
            }

            if ($daily > 0) {
                $dailyCounts[] = $daily;
            }
        }

        $base_count = count($dailyCounts) ? round(array_sum($dailyCounts) / count($dailyCounts)) : 0;
        $final_count = $base_count;
    }

    // =====================================================
    //  OFFLINE HOSTEL CALCULATION
    // =====================================================
    else {

        $calc_method = "Offline Student x %";

        // Student Registration Count
        $stdSql = "SELECT COUNT(*) AS cnt 
                   FROM std_reg_s 
                   WHERE status=1 AND hostel_1='$hostel_unique_id'";

        $base_count = (int)$mysqli->query($stdSql)->fetch_assoc()['cnt'];

        // Fetch District Percentage (Current Month)
        $percSql = "SELECT percentage 
                    FROM district_percentage_sub 
                    WHERE district='$district_name' 
                    AND hostel_type='$hostel_type'
                    AND month='$nextMonth'
                    LIMIT 1";

        $resPerc = $mysqli->query($percSql);

        // Fallback to previous month
        if ($resPerc->num_rows == 0) {
            $percSql2 = "SELECT percentage 
                         FROM district_percentage_sub 
                         WHERE district='$district_name' 
                         AND hostel_type='$hostel_type'
                         AND month='$prevMonth'
                         LIMIT 1";
            $resPerc = $mysqli->query($percSql2);
        }

        // FINAL FALLBACK → LAST AVAILABLE PERCENT ENTRY
        if ($resPerc->num_rows == 0) {
            $percSql3 = "SELECT percentage 
                         FROM district_percentage_sub 
                         WHERE district='$district_name' 
                         AND hostel_type='$hostel_type'
                         ORDER BY id DESC
                         LIMIT 1";
            $resPerc = $mysqli->query($percSql3);
        }

        // Default to 100% if no entry found at all
        $perc = $resPerc->fetch_assoc();
        $percentage_applied = $perc ? (float)$perc['percentage'] : 100;

        $final_count = round($base_count * $percentage_applied / 100);
    }

    // =====================================================
    //  INSERT OR UPDATE
    // =====================================================
    $checkSql = "SELECT id FROM indent_count 
                 WHERE hostel_id='$hostel_id' 
                 AND month_year='$nextMonth'";
    $checkRes = $mysqli->query($checkSql);

    if ($checkRes->num_rows > 0) {
        $updateSql = "UPDATE indent_count SET 
                        is_online='$is_online',
                        base_count='$base_count',
                        final_count='$final_count',
                        percentage_applied=" . ($percentage_applied ?? 'NULL') . ",
                        updated_at=NOW()
                      WHERE hostel_id='$hostel_id' AND month_year='$nextMonth'";
        $mysqli->query($updateSql);
    } else {
        $insertSql = "INSERT INTO indent_count 
                      (hostel_id, hostel_unique_id, district_name, month_year, hostel_type, is_online, base_count, final_count, percentage_applied)
                      VALUES
                      ('$hostel_id','$hostel_unique_id','$district_name','$nextMonth','$hostel_type','$is_online','$base_count','$final_count'," . ($percentage_applied ?? 'NULL') . ")";
        $mysqli->query($insertSql);
    }

    // =====================================================
    //  STORE SUMMARY DATA FOR DEBUG TABLE
    // =====================================================
    $debug_summary[] = [
        'hostel_id' => $hostel_id,
        'online_status' => $is_online ? "Online" : "Offline",
        'base_count' => $base_count,
        'final_count' => $final_count,
        'percentage' => $percentage_applied ?? "-",
        'days_present' => $daysPresent,
        'method' => $calc_method
    ];

}

echo "\n===== Completed for $nextMonth (based on $prevMonth) =====\n";

// =====================================================
//  DEBUG SUMMARY TABLE (HTML)
// =====================================================

echo "<hr><h2 style='color:darkred;'>🧪 Debug Summary Table</h2>";

echo "<table border='1' cellspacing='0' cellpadding='6' 
        style='border-collapse:collapse;width:95%;font-family:Arial;font-size:14px;'>
        <tr style='background:#333;color:#fff;text-align:center;'>
        <th>Hostel ID</th>
        <th>Online?</th>
        <th>Base Count</th>
        <th>Final Count</th>
        <th>Percentage</th>
        <th>Days Present</th>
        <th>Method Used</th>
        </tr>";

foreach ($debug_summary as $row) {
    echo "<tr style='text-align:center;'>
        <td>{$row['hostel_id']}</td>
        <td>{$row['online_status']}</td>
        <td>{$row['base_count']}</td>
        <td>{$row['final_count']}</td>
        <td>{$row['percentage']}</td>
        <td>{$row['days_present']}</td>
        <td>{$row['method']}</td>
    </tr>";
}

echo "</table>";

?>
