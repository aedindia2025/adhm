<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$host = 'localhost';
$user = 'root';
$password = '4/rb5sO2s3TpL4gu';
$dbname = 'adi_dravidar';
$sql_file = '/var/www/html/attendancerecordinfo.sql';

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Read SQL file
$query = file_get_contents($sql_file);

if ($conn->multi_query($query)) {
    echo "Import successful!";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?> 