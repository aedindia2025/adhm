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

// Receive data from the first website
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check for the authentication token
    $token = isset($_POST['token']) ? $_POST['token'] : '';
    if ($token !== $valid_token) {
        http_response_code(401); // Unauthorized
        echo "Unauthorized";
        exit;
    }

    // Sanitize POST data, pass NULL if not provided
    $currentDate = !empty($_POST['currentDate']) ? htmlspecialchars($_POST['currentDate']) : NULL;
    $userId = !empty($_POST['userId']) ? htmlspecialchars($_POST['userId']) : NULL;
    $std_reg_no = !empty($_POST['std_reg_no']) ? htmlspecialchars($_POST['std_reg_no']) : NULL;
    $userName = !empty($_POST['userName']) ? htmlspecialchars($_POST['userName']) : NULL;
    $attendanceState = !empty($_POST['attendanceState']) ? htmlspecialchars($_POST['attendanceState']) : NULL;
    $attendanceDateTime = !empty($_POST['attendanceDateTime']) ? htmlspecialchars($_POST['attendanceDateTime']) : NULL;
    $attendanceTime = !empty($_POST['attendanceTime']) ? htmlspecialchars($_POST['attendanceTime']) : NULL;
    $attendanceMethod = !empty($_POST['attendanceMethod']) ? htmlspecialchars($_POST['attendanceMethod']) : NULL;
    $recNo = !empty($_POST['recNo']) ? htmlspecialchars($_POST['recNo']) : NULL;
    $userType = !empty($_POST['userType']) ? htmlspecialchars($_POST['userType']) : NULL;
    $device_s_no = !empty($_POST['device_s_no']) ? htmlspecialchars($_POST['device_s_no']) : NULL;
    $hostel_id = !empty($_POST['hostel_id']) ? htmlspecialchars($_POST['hostel_id']) : NULL;
    $hostel_unique_id = !empty($_POST['hostel_unique_id']) ? htmlspecialchars($_POST['hostel_unique_id']) : NULL;
    $district_name = !empty($_POST['district_name']) ? htmlspecialchars($_POST['district_name']) : NULL;
    $taluk_name = !empty($_POST['taluk_name']) ? htmlspecialchars($_POST['taluk_name']) : NULL;

    // Insert data into the attendancerecordinfo table
    try {
        $insertQuery = "
            INSERT INTO attendancerecordinfo (
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

?>
