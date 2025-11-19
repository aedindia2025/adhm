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


// Function to get student count for a hostel
function getStudentCount($conn, $unique_id) {
    $query = "SELECT COUNT(*) as count FROM std_reg_s WHERE hostel_1 = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $unique_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['count'] ?? 0;
}

// Function to get student Biometric Device Register count for a hostel
function getStudentBioRegCount($conn, $unique_id) {
    $query = "SELECT COUNT(*) as count FROM std_reg_s WHERE bio_reg_status = 1 AND hostel_1 = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $unique_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['count'] ?? 0;
}

// Function to get student Biometric Device Register count for a hostel
function getStudentFaceIdCount($conn, $unique_id) {
    $query = "SELECT COUNT(*) as count FROM std_reg_s WHERE face_id_status = 1 AND hostel_1 = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $unique_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['count'] ?? 0;
}

// Fetch Hostel Details
$sql = "SELECT 
    unique_id,
    hostel_id as hostelId, 
    hostel_name as hostelName, 
    (SELECT district_name FROM district_name WHERE district_name.unique_id = hostel_name.district_name) AS districtName, 
    (SELECT taluk_name FROM taluk_creation WHERE taluk_creation.unique_id = hostel_name.taluk_name) AS talukName, 
    (SELECT hostel_type FROM hostel_type WHERE hostel_type.unique_id = hostel_name.hostel_type) AS hostelType, 
    (SELECT gender_type FROM hostel_gender_type WHERE hostel_gender_type.unique_id = hostel_name.gender_type) AS genderType, 
    sanctioned_strength as sanctionedStrength
    FROM hostel_name WHERE is_delete = 0";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $hostelData = [];
    while ($row = $result->fetch_assoc()) {
        $row['dadwoApproved'] = getStudentCount($conn, $row['unique_id']);
        $row['dataPushedCount'] = getStudentBioRegCount($conn, $row['unique_id']);
        $row['facialRegisteredCount'] = getStudentFaceIdCount($conn, $row['unique_id']);
        unset($row['unique_id']); // Remove unique_id from response
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
