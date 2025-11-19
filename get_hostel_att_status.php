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
    $error_count = isset($data['error_count']) ? htmlspecialchars($data['error_count']) : NULL;
    $no_records = isset($data['no_records']) ? htmlspecialchars($data['no_records']) : NULL;
    $manual_user = isset($data['manual_user']) ? htmlspecialchars($data['manual_user']) : NULL;
    $am_pm = isset($data['am_pm']) ? htmlspecialchars($data['am_pm']) : NULL;
    $hostel_id = isset($data['hostel_id']) ? htmlspecialchars($data['hostel_id']) : NULL;
    $hostel_unique_id = isset($data['hostel_unique_id']) ? htmlspecialchars($data['hostel_unique_id']) : NULL;
    $district_name = isset($data['district_name']) ? htmlspecialchars($data['district_name']) : NULL;
    $taluk_name = isset($data['taluk_name']) ? htmlspecialchars($data['taluk_name']) : NULL;
    $offline = isset($data['offline']) ? htmlspecialchars($data['offline']) : NULL;
    $success = isset($data['success']) ? htmlspecialchars($data['success']) : NULL;
    $retry = isset($data['retry']) ? htmlspecialchars($data['retry']) : NULL;
    $incorrect = isset($data['incorrect']) ? htmlspecialchars($data['incorrect']) : NULL;
    

    try {
        // Insert data into the dayWise_att_status table
        $insertQuery = "
                INSERT INTO dayWise_att_status (
                    currentDate, error_count, no_records, manual_user, am_pm, hostel_id, 
                    hostel_unique_id, district_name, taluk_name, offline, success, retry, incorrect
                ) VALUES (
                    :currentDate, :error_count, :no_records, :manual_user, :am_pm, :hostel_id, 
                    :hostel_unique_id, :district_name, :taluk_name, :offline, :success, :retry, :incorrect
                )
            ";

        $stmt = $pdo->prepare($insertQuery);
        $stmt->execute([
            ':currentDate' => $currentDate,
            ':error_count' => $error_count,
            ':no_records' => $no_records,
            ':manual_user' => $manual_user,
            ':am_pm' => $am_pm,
            ':hostel_id' => $hostel_id,
            ':hostel_unique_id' => $hostel_unique_id,
            ':district_name' => $district_name,
            ':taluk_name' => $taluk_name,
            ':offline' => $offline,
            ':success' => $success,
            ':retry' => $retry,
            ':incorrect' => $incorrect
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
