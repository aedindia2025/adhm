<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
set_time_limit(0); // Prevent script timeout

// db_connection.php - Connection to the first website database
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
    $url = "https://nallosaims.tn.gov.in/adw_biometric/get.php"; // API endpoint on second website
    $token = 'your-secret-token'; // Ensure this matches the token used in get.php
    $data['token'] = $token;

    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        ]
    ];

    $context = stream_context_create($options);
    $result = @file_get_contents($url, false, $context);

    if ($result === FALSE) {
        $error = error_get_last();
        echo "Error: " . $error['message'];
    } else {
        return $result;
    }
}

//insert start time of cron
$current_date = date('Y-m-d');
$start_time = date('H:i:s');
$un_id = uniqid();
$query = "INSERT into insert_api_crontime (`current_date`, `start_time`, `un_id`) values ('$current_date','$start_time','$un_id')";
$query_stmt = $pdo->prepare($query);
$query_stmt->execute();

// Fetch new data from std_reg_s and related pro_image from the aadhar table
$query = "
    SELECT *
    FROM std_reg_s 
    WHERE sync_status = 0
";
$stmt = $pdo->query($query);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as $row) {
    // Send data to the second website
    $response = sendDataToSecondSite($row);

    echo $response;

    // If the API call is successful, mark the record as synced
    if ($response === "success") {
        $updateQuery = "UPDATE std_reg_s SET sync_status = 1 WHERE id = :id";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->execute([':id' => $row['id']]);
    } else {
        echo "Failed to sync data for ID: " . $row['id'] . "\n";
    }
}

//update end time of cron
$end_time = date('H:i:s');
$update_query = "update insert_api_crontime set `end_time` = '$end_time' where `un_id` = '$un_id'";
$update_stmt = $pdo->prepare($update_query);
$update_stmt->execute();

?>
