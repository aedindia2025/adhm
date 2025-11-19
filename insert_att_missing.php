<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
set_time_limit(0); // Prevent script timeout

// Database connection
$host = 'localhost';
$db = 'adi_dravidar';
$user = 'root';
$pass = '4/rb5sO2s3TpL4gu';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}

// Function to send data to the second website with a token
function sendDataToSecondSite($data)
{
    $url = "https://nallosaims.tn.gov.in/adw_biometric/get_att_missing.php"; // API endpoint on second website
    $token = 'your-secret-token'; // Ensure this matches the token used in get.php
    
    $payload = json_encode(['token' => $token, 'data' => $data]);
    
    $options = [
        'http' => [
            'header' => "Content-Type: application/json\r\n",
            'method' => 'POST',
            'content' => $payload
        ]
    ];
    
    $context = stream_context_create($options);
    $result = @file_get_contents($url, false, $context);
    
    if ($result === FALSE) {
        $error = error_get_last();
        echo "Error: " . $error['message'];
        return false;
    }

}

// Insert start time of cron
$current_date = date('Y-m-d');
$start_time = date('H:i:s');
$un_id = uniqid();
$query = "INSERT INTO cron_att_missing (`current_date`, `start_time`, `un_id`) VALUES (?, ?, ?)";
$query_stmt = $pdo->prepare($query);
$query_stmt->execute([$current_date, $start_time, $un_id]);

// Fetch new data from attendance_missing_records where replication_status = 0
$query = "SELECT id, hostel_id, am, pm FROM attendance_missing_records";
$stmt = $pdo->query($query);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!empty($rows)) {
    $response = sendDataToSecondSite($rows);
    echo "success";
}

// Update end time of cron
$end_time = date('H:i:s');
$update_query = "UPDATE cron_att_missing SET `end_time` = ? WHERE `un_id` = ?";
$update_stmt = $pdo->prepare($update_query);
$update_stmt->execute([$end_time, $un_id]);

?>
