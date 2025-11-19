<?php
ini_set('max_execution_time', 0);

ini_set('memory_limit', '-1'); // Increase memory limit
set_time_limit(0);
$host = 'localhost'; // Replace with your host name
$user = 'root'; // Replace with your MySQL username
$password = '4/rb5sO2s3TpL4gu'; // Replace with your MySQL password
$dbname = 'adi_dravidar'; // Replace with your database name

// Create a MySQLi connection
$mysqli = new mysqli($host, $user, $password, $dbname);

// Check the connection
if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

// echo "Connected successfully";


$sql = 'select s1_unique_id,new_uuid from aadhar';

$stmt = $mysqli->prepare($sql);
$stmt->execute(); 
$result = $stmt->get_result();
$sn = '1';

while ($row = $result->fetch_assoc()) {
    
    $stmt_app = $mysqli->prepare("UPDATE std_app_s SET new_uuid = '".$row['new_uuid']."' WHERE unique_id = '".$row['s1_unique_id']."'");
    $stmt_reg = $mysqli->prepare("UPDATE std_reg_s SET new_uuid = '".$row['new_uuid']."' WHERE unique_id = '".$row['s1_unique_id']."'");
   
    

    // Execute the statement
   $stmt_app->execute();   
   $stmt_reg->execute();

}
echo $sn++;
?>
