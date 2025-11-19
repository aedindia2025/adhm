<?php
// ✅ Database connection
$host = "localhost";
$username = "root";
$password = "4/rb5sO2s3TpL4gu";
$databasename = "adi_dravidar";

$mysqli = new mysqli($host, $username, $password, $databasename);
if ($mysqli->connect_errno) {
    die("❌ Failed to connect to MySQL: " . $mysqli->connect_error);
}

// Previous month (attendance source)
$prevMonth = date('Y-m', strtotime('first day of last month'));
$firstDate = date('Y-m-01', strtotime('first day of last month'));
$lastDate  = date('Y-m-t', strtotime('first day of last month'));

// Next month (indent target)
$nextMonth = date('Y-m', strtotime('first day of this month'));

echo "===== Automated Indent Count for $nextMonth (based on $prevMonth attendance) =====\n";

// 1️⃣ Get all hostels
$hostelSql = "SELECT hostel_id, unique_id, district_name, hostel_type 
              FROM hostel_name 
              WHERE is_active=1 AND is_delete=0";
$resHostel = $mysqli->query($hostelSql);

while ($hostel = $resHostel->fetch_assoc()) {
    $hostel_id = $hostel['hostel_id'];
    $hostel_unique_id = $hostel['unique_id'];
    $district_name = $mysqli->real_escape_string($hostel['district_name']);
    $hostel_type = $mysqli->real_escape_string($hostel['hostel_type']);

    $is_online = 0;
    $base_count = 0;
    $final_count = 0;
    $percentage_applied = null;

    // Step 1: Determine online/offline based on attendance of previous month
    $daysSql = "SELECT COUNT(DISTINCT report_date) AS days
                FROM attendance_report
                WHERE hostel_unique_id='$hostel_unique_id'
                AND report_date BETWEEN '$firstDate' AND '$lastDate'";
    $days = (int)$mysqli->query($daysSql)->fetch_assoc()['days'];
    $is_online = ($days >= 5) ? 1 : 0;

    if ($is_online) {
        // Online hostel: attendance-based count
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

            // ✅ Updated logic as per new requirement:
            // 1. Take maximum of morning/night
            // 2. If morning/night < 50% of noon => take average of all three
            // 3. Otherwise => take max(morning/night, noon)
            $maxME = max($morning, $evening);

            if ($maxME < (0.5 * $noon)) {
                // Average of all three meals
                $daily = round(($morning + $noon + $evening) / 3);
            } else {
                // Take max of morning/night and noon
                $daily = max($maxME, $noon);
            }

            if ($daily > 0) {
                $dailyCounts[] = $daily;
            }
        }

        // Monthly average of valid days
        $base_count = count($dailyCounts) ? round(array_sum($dailyCounts) / count($dailyCounts)) : 0;
        $final_count = $base_count;

    } else {
        // Offline hostel: student registration-based count
        $stdSql = "SELECT COUNT(*) AS cnt 
                   FROM std_reg_s 
                   WHERE status=1 AND hostel_1='$hostel_unique_id'";
        $base_count = (int)$mysqli->query($stdSql)->fetch_assoc()['cnt'];

        // Get offline percentage (district-based from district_percentage_sub) for current month
        $percSql = "SELECT percentage 
                    FROM district_percentage_sub 
                    WHERE district='$district_name' 
                    AND hostel_type='$hostel_type'
                    AND month='$nextMonth'
                    LIMIT 1";
        $resPerc = $mysqli->query($percSql);

        if ($resPerc->num_rows == 0) {
            // fallback to previous month if current month not found
            $percSql2 = "SELECT percentage 
                         FROM district_percentage_sub 
                         WHERE district='$district_name' 
                         AND hostel_type='$hostel_type'
                         AND month='$prevMonth'
                         LIMIT 1";
            $resPerc = $mysqli->query($percSql2);
        }

        $perc = $resPerc->fetch_assoc();
        $percentage_applied = $perc ? (float)$perc['percentage'] : 100;

        $final_count = round($base_count * $percentage_applied / 100);
    }

    // Step 2: Insert or Update indent_count for NEXT month
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

    echo "Hostel: $hostel_id | Online: $is_online | Base: $base_count | Final: $final_count | %: $percentage_applied\n";
}

echo "✅ Automated Indent Count Completed for $nextMonth (based on $prevMonth attendance)\n";
?>
