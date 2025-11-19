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
        echo "Unauthorized";
        exit;
    }

    // Sanitize and fetch POST data
    $std_reg_no = isset($_POST['std_reg_no']) ? $_POST['std_reg_no'] : '';
    $bio_reg_status = isset($_POST['bio_reg_status']) ? $_POST['bio_reg_status'] : '';

    // Update data in the std_reg_s table
    $updateQuery = "
        UPDATE std_reg_s 
        SET bio_reg_status = :bio_reg_status 
        WHERE std_reg_no = :std_reg_no
    ";

    $stmt = $pdo->prepare($updateQuery);
    $stmt->execute([
        ':bio_reg_status' => $bio_reg_status,
        ':std_reg_no' => $std_reg_no
    ]);

    echo "success";
}
?>
