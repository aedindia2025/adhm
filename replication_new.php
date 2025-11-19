<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// db_connection.php - Connection to the database
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

// Secret token for authentication
$valid_token = 'your-secret-token';

// Receive JSON data from the first website
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Read the raw POST data
    $jsonData = file_get_contents('php://input');

    // Decode the JSON data
    $data = json_decode($jsonData, true); // true to return as associative array

    // Check for decoding errors
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400); // Bad Request
        echo "Invalid JSON data";
        exit;
    }

    // Check for the authentication token
    $token = isset($data['token']) ? $data['token'] : '';
    if ($token !== $valid_token) {
        http_response_code(401); // Unauthorized
        echo "Unauthorized";
        exit;
    }

    // Sanitize data, if needed, but here it is already coming as sanitized from JSON
    $currentDate = isset($data['currentDate']) ? htmlspecialchars($data['currentDate']) : NULL;
    $userId = isset($data['userId']) ? htmlspecialchars($data['userId']) : NULL;
    $std_reg_no = isset($data['std_reg_no']) ? htmlspecialchars($data['std_reg_no']) : NULL;
    $userName = isset($data['userName']) ? htmlspecialchars($data['userName']) : NULL;
    $attendanceState = isset($data['attendanceState']) ? htmlspecialchars($data['attendanceState']) : NULL;
    $attendanceDateTime = isset($data['attendanceDateTime']) ? htmlspecialchars($data['attendanceDateTime']) : NULL;
    $attendanceTime = isset($data['attendanceTime']) ? htmlspecialchars($data['attendanceTime']) : NULL;
    $attendanceMethod = isset($data['attendanceMethod']) ? htmlspecialchars($data['attendanceMethod']) : NULL;
    $recNo = isset($data['recNo']) ? htmlspecialchars($data['recNo']) : NULL;
    $userType = isset($data['userType']) ? htmlspecialchars($data['userType']) : NULL;
    $device_s_no = isset($data['device_s_no']) ? htmlspecialchars($data['device_s_no']) : NULL;
    $hostel_id = isset($data['hostel_id']) ? htmlspecialchars($data['hostel_id']) : NULL;
    $hostel_unique_id = isset($data['hostel_unique_id']) ? htmlspecialchars($data['hostel_unique_id']) : NULL;
    $district_name = isset($data['district_name']) ? htmlspecialchars($data['district_name']) : NULL;
    $taluk_name = isset($data['taluk_name']) ? htmlspecialchars($data['taluk_name']) : NULL;

    try {
        // Insert data into the attendance_queue table
        $insertQuery = "
                INSERT INTO attendance_queue (
                    currentDate, userId, std_reg_no, userName, attendanceState, attendanceDateTime, attendanceTime, 
                    attendanceMethod, recNo, userType, device_s_no, hostel_id, 
                    hostel_unique_id, district_name, taluk_name
                ) VALUES (
                    :currentDate, :userId, :std_reg_no, :userName, :attendanceState, :attendanceDateTime, :attendanceTime,
                    :attendanceMethod, :recNo, :userType, :device_s_no, :hostel_id, 
                    :hostel_unique_id, :district_name, :taluk_name
                )
            ";

        $stmt = $pdo->prepare($insertQuery);
        $stmt->execute([
            ':currentDate' => $currentDate,
            ':userId' => $userId,
            ':std_reg_no' => $std_reg_no,
            ':userName' => $userName,
            ':attendanceState' => $attendanceState,
            ':attendanceDateTime' => $attendanceDateTime,
            ':attendanceTime' => $attendanceTime,
            ':attendanceMethod' => $attendanceMethod,
            ':recNo' => $recNo,
            ':userType' => $userType,
            ':device_s_no' => $device_s_no,
            ':hostel_id' => $hostel_id,
            ':hostel_unique_id' => $hostel_unique_id,
            ':district_name' => $district_name,
            ':taluk_name' => $taluk_name
        ]);

        echo "insertion_success";
    } catch (PDOException $e) {
        http_response_code(500); // Internal server error
        echo "Database error: " . $e->getMessage();
        exit;
    }
} else {
    http_response_code(405); // Method not allowed
    echo "Only POST requests are allowed";
}
