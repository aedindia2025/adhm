<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$db = 'adi_dravidar';
$user = 'root';
$pass = '4/rb5sO2s3TpL4gu';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 1: Truncate previous records
$conn->query("TRUNCATE TABLE attendance_missing_records");

// Step 2: Get all hostels where dev_reg = 1
$hostelQuery = "SELECT hostel_id FROM hostel_name WHERE dev_reg = 1";
$hostelResult = $conn->query($hostelQuery);

if ($hostelResult->num_rows > 0) {
    while ($row = $hostelResult->fetch_assoc()) {
        $hostel_id = $row['hostel_id'];
        $missing_morning = [];
        $missing_evening = [];

        // Step 3: Check attendance records from Jan 1, 2025, to today
        $startDate = new DateTime('2025-01-01');
        $currentDate = new DateTime();

        while ($startDate <= $currentDate) {
            $date = $startDate->format('Y-m-d');

            $attendanceQuery = "SELECT morning_punch_count, eve_punch_count 
                                FROM attendance_report 
                                WHERE hostel_id = ? AND report_date = ?";
            $stmt = $conn->prepare($attendanceQuery);
            $stmt->bind_param("ss", $hostel_id, $date);
            $stmt->execute();
            $stmt->bind_result($morning_punch, $evening_punch);
            
            if ($stmt->fetch()) {
                if ($morning_punch == 0) {
                    $missing_morning[] = $date;
                }
                if ($evening_punch == 0) {
                    $missing_evening[] = $date;
                }
            } else {
                // If no record found for the date, consider it missing in both columns
                $missing_morning[] = $date;
                $missing_evening[] = $date;
            }

            $stmt->close();
            $startDate->modify('+1 day');
        }

        // Step 4: Store missing dates in `attendance_missing_records` table
        $am_dates = implode(',', $missing_morning);
        $pm_dates = implode(',', $missing_evening);

        $insertQuery = "INSERT INTO attendance_missing_records (hostel_id, am, pm) VALUES (?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("sss", $hostel_id, $am_dates, $pm_dates);
        $insertStmt->execute();
        $insertStmt->close();
    }
}

$conn->close();
echo "Attendance status updated successfully.";
?>
