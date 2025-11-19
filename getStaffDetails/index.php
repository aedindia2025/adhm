<?php
// Enable error reporting for debugging (Remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set Headers
header("Access-Control-Allow-Origin: *"); // Replace '*' with specific domain if needed
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Authorization, Content-Type");

// Secure API Authentication Key
$API_KEY = "YHSTHST-RWNSLRT-PNSTOZU";

// Get all headers (case-insensitive)
$headers = array_change_key_case(getallheaders(), CASE_LOWER);

// Debugging: Log all received headers (Check server logs)
error_log(print_r($headers, true));

// Validate API Key
if (!isset($headers['authorization']) || $headers['authorization'] !== "$API_KEY") {
    http_response_code(401); // Unauthorized
    echo json_encode(["status" => "Rejected", "message" => "Unauthorized"]);
    exit;
}

// Database Connection
$host = "localhost";
$user = "root";
$pass = "4/rb5sO2s3TpL4gu";
$db = "adi_dravidar";

$conn = new mysqli($host, $user, $pass, $db);


// Fetch Hostel Details
$sql = "SELECT 
    staff_name as staffName, 
    dob as dob, 
    mobile_num as mobileNo,
    ifhrms_id as ifhrmsId,
    (SELECT district_name FROM district_name WHERE district_name.unique_id = establishment_registration.district_office) AS districtName, 
    (SELECT taluk_name FROM taluk_creation WHERE taluk_creation.unique_id = establishment_registration.taluk_office) AS talukName, 
    (SELECT hostel_name FROM hostel_name WHERE hostel_name.unique_id = establishment_registration.hostel_name) AS hostelName, 
    (SELECT establishment_type FROM establishment_type WHERE establishment_type.unique_id = establishment_registration.designation) AS designation, 
    status
    FROM establishment_registration WHERE is_delete = 0";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $hostelData = [];
    while ($row = $result->fetch_assoc()) {
        if($row['status'] == 1){
            $row['status'] = "Approved";
        }
        elseif($row['status'] == 2){
            $row['status'] = "Rejected";
        }
        else{
            $row['status'] = "Pending";
        }
        $hostelData[] = $row;
    }
    http_response_code(200); // OK
    echo json_encode(["status" => "Success", "data" => $hostelData]);
} else {
    http_response_code(404); // Not Found
    echo json_encode(["status" => "Failed", "message" => "No records found"]);
}

// Close Connection
$conn->close();
