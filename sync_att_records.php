<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$db = 'adi_dravidar';
$user = 'root';
$pass = '4/rb5sO2s3TpL4gu';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$maxRecordsPerRun = 70000;
$processedRecords = 0;

// Insert cron start time
$current_date = date('Y-m-d');
$start_time = date('H:i:s');
$un_id = uniqid();
$query = "INSERT INTO sync_att_record_cron (`current_date`, `start_time`, `un_id`) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("sss", $current_date, $start_time, $un_id);
$stmt->execute();
$stmt->close();

try {
    // Prepare the insert query for the attendancerecordinfo table
    $insertQuery = "INSERT INTO attendancerecordinfo 
        (currentDate, userId, std_reg_no, userName, attendanceState, attendanceDateTime, 
        attendanceTime, attendanceMethod, recNo, userType, device_s_no, hostel_id, 
        hostel_unique_id, district_name, taluk_name)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($insertQuery);

    while ($processedRecords < $maxRecordsPerRun) {
        // Step 1: Fetch a batch of records (one by one processing)
        $stmt = $conn->prepare("SELECT * FROM attendance_queue WHERE processing_status = 0 AND std_reg_no IS NOT NULL ORDER BY id ASC LIMIT 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if (!$row) {
            break; // No more records to process
        }

        // Step 3: Insert into attendancerecordinfo (triggers will be executed)
        $stmtInsert->bind_param(
            "sssssssssssssss",
            $row['currentDate'],
            $row['userId'],
            $row['std_reg_no'],
            $row['userName'],
            $row['attendanceState'],
            $row['attendanceDateTime'],
            $row['attendanceTime'],
            $row['attendanceMethod'],
            $row['recNo'],
            $row['userType'],
            $row['device_s_no'],
            $row['hostel_id'],
            $row['hostel_unique_id'],
            $row['district_name'],
            $row['taluk_name']
        );
        $stmtInsert->execute();

        // Step 4: Mark the record as "Completed"
        $stmt = $conn->prepare("UPDATE attendance_queue SET processing_status = 1 WHERE id = ?");
        $stmt->bind_param("i", $row['id']);
        $stmt->execute();
        $stmt->close();

        $processedRecords++;
    }

    $stmtInsert->close();

    echo "Total Records Processed: $processedRecords\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$conn->close();

// Update cron end time
$conn_update = new mysqli($host, $user, $pass, $db);
if ($conn_update->connect_error) {
    die("Connection failed: " . $conn_update->connect_error);
}

$end_time = date('H:i:s');
$update_stmt = $conn_update->prepare("UPDATE sync_att_record_cron SET end_time = ? WHERE un_id = ?");
$update_stmt->bind_param("ss", $end_time, $un_id);
$update_stmt->execute();
$update_stmt->close();
$conn_update->close();

?>
