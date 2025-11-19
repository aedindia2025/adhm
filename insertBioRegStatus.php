<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "loclhost";
$user = "root";
$pass = "4/rb5sO2s3TpL4gu";
$db = "adi_dravidar";

// Connect to the database
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to send data to second server
function sendDataToSecondSite($data)
{
    $url = "https://nallosaims.tn.gov.in/adw_biometric/getBioRegStatus.php"; // API endpoint on second site
    $token = 'your-secret-token';
    $data['token'] = $token;

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        ]
    ];

    $context = stream_context_create($options);
    $result = @file_get_contents($url, false, $context);

    return ($result === FALSE) ? null : $result;
}

//insert start time of cron
$current_date = date('Y-m-d');
$start_time = date('H:i:s');
$un_id = uniqid();
$query = "INSERT into cron_insert_BioStatus (`current_date`, `start_time`, `un_id`) values ('$current_date','$start_time','$un_id')";
$query_stmt = $conn->prepare($query);
$query_stmt->execute();

// Fetch all records where bio_reg_status = 0 (up to 10 records)
$sql = "SELECT std_reg_no FROM std_reg_s WHERE fingerprint_status = 0";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {  // Loop through all records
        $std_reg_no = $row['std_reg_no'];

        // Send std_reg_no to second server
        $response = sendDataToSecondSite(['std_reg_no' => $std_reg_no]);

        if ($response) {
            $data = json_decode($response, true);
            if ($data && isset($data['bio_reg_status'])) {
                // Update std_reg_s table with received data
                $stmt = $conn->prepare("UPDATE std_reg_s SET bio_reg_status=?, bio_reg_date=?, face_id_status=?, fingerprint_status=? WHERE std_reg_no=?");
                $stmt->bind_param("sssss", $data['bio_reg_status'], $data['bio_reg_date'], $data['face_id_status'], $data['fingerprint_status'], $std_reg_no);
                $stmt->execute();
                $stmt->close();
                echo "Updated std_reg_no: $std_reg_no\n";
            } else {
                echo "Invalid response for std_reg_no: $std_reg_no\n";
            }
        } else {
            echo "Error: No response from second site for std_reg_no: $std_reg_no\n";
        }
    }
} else {
    echo "No pending records found\n";
}


//update end time of cron
$end_time = date('H:i:s');
$update_query = "update cron_insert_BioStatus set `end_time` = '$end_time' where `un_id` = '$un_id'";
$update_stmt = $conn->prepare($update_query);
$update_stmt->execute();


$conn->close();

?>
