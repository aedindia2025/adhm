<?php
// Enable error reporting (Remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Authorization, Content-Type");

// Secure API Key
$API_KEY = "YHSTHST-RWNSLRT-PNSTOZU";

// Get Headers
$headers = array_change_key_case(getallheaders(), CASE_LOWER);

// Validate API Key
if (!isset($headers['authorization']) || $headers['authorization'] !== "$API_KEY") {
    http_response_code(401); // Unauthorized
    echo json_encode(["status" => "Rejected", "message" => "Unauthorized"]);
    exit;
}


// Validate input parameters
if (
    empty($_GET['hostelId']) || 
    empty($_GET['fromDate']) || 
    empty($_GET['toDate'])
) {
    http_response_code(400); // Bad Request
    echo json_encode(["status" => "Failed", "message" => "Missing required parameters"]);
    exit;
}

// Sanitize input
$hostel_id = trim($_GET['hostelId']); 
$from_date = trim($_GET['fromDate']);
$to_date = trim($_GET['toDate']);

// Validate date format (YYYY-MM-DD)
if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $from_date) || !preg_match("/^\d{4}-\d{2}-\d{2}$/", $to_date)) {
    http_response_code(400); // Bad Request
    echo json_encode(["status" => "Failed", "message" => "Invalid date format. Use YYYY-MM-DD"]);
    exit;
}

// Database Connection
$host = "localhost";
$user = "root";
$pass = "4/rb5sO2s3TpL4gu";
$db = "adi_dravidar";

$conn = new mysqli($host, $user, $pass, $db);


// Prepare and execute the SQL query
$sql = "SELECT 
    currentDate AS attendanceDate,
    userId AS biometricId, 
    std_reg_no AS studentRegNo, 
    userName AS studentName, 
    punch_mrg AS punchMorning, 
    punch_eve AS punchEvening, 
    hostel_id AS hostelId,
    district_name_value AS districtName,
    taluk_name_value AS talukName,
    hostel_name AS hostelName
    FROM dayattreport 
    WHERE hostel_id = ? 
    AND currentDate BETWEEN ? AND ?
    AND dropout_status = 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $hostel_id, $from_date, $to_date);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $attendanceData = [];
    while ($row = $result->fetch_assoc()) {
        $attendanceData[] = $row;
    }
    http_response_code(200); // OK
    echo json_encode(["status" => "Success", "data" => $attendanceData]);
} else {
    http_response_code(404); // Not Found
    echo json_encode(["status" => "Failed", "message" => "No records found"]);
}

// Close Connection
$stmt->close();
$conn->close();
?>
