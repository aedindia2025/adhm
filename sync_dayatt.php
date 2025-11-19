<?php
$host = 'localhost';
$db = 'adi_dravidar';
$user = 'root';
$pass = '4/rb5sO2s3TpL4gu';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
    // Call the stored procedure
    if (!$conn->query("CALL ProcessAttendance()")) {
        throw new Exception("Stored procedure failed: " . $conn->error);
    }
    echo "Stored procedure executed successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

// Update cron end time
$end_time = date('H:i:s');
$update_stmt = $conn->prepare("UPDATE sync_att_record_cron SET end_time = ? WHERE un_id = ?");
$update_stmt->bind_param("ss", $end_time, $un_id);
$update_stmt->execute();
$update_stmt->close();

// Close connection
$conn->close();
?>
