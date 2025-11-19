<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// db_connection.php - Connection to the second website database
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
    $hostel_id = $_POST['hostel_id'];
    $status = $_POST['status'];
   
    // Insert data into the second website's dayattreport table
    try {
        $updatequery = " UPDATE cctv_live SET status = :status where cam_name = :hostel_id";

        $stmt = $pdo->prepare($updatequery);
        $stmt->execute([
            ':status' => $status,
            ':hostel_id' => $hostel_id
           
        ]);

        echo "Updated For Hostel ID = ".$hostel_id;
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