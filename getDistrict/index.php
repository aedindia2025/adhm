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
    district_name as districtName
    FROM district_name WHERE is_delete = 0";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $districtData = [];
    while ($row = $result->fetch_assoc()) {

        $districtData[] = $row;
    }
    http_response_code(200); // OK
    echo json_encode(["status" => "Success", "data" => $districtData]);
} else {
    http_response_code(404); // Not Found
    echo json_encode(["status" => "Failed", "message" => "No records found"]);
}

// Close Connection
$conn->close();
