<?php
ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1'); // Increase memory limit
set_time_limit(0);
// echo "dd";
$host = 'localhost';
$user = 'root';
$password = '4/rb5sO2s3TpL4gu'; // Replace with your MySQL password
$dbname = 'adi_dravidar';

// Create a MySQLi connection
$mysqli = new mysqli($host, $user, $password, $dbname);

// Check the connection
if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

$stmt_query = "select unique_id from std_reg_s where std_name IS NULL and is_delete = '0'";
$stmt = $mysqli->prepare($stmt_query);
$stmt->execute();
        $result = $stmt->get_result();

       

        $sn = '0';
            while ($row = $result->fetch_assoc()) {

                $stmt_name = "select aname from aadhar where s1_unique_id = '".$row['unique_id']."'";
$stmt_1 = $mysqli->prepare($stmt_name);
$stmt_1->execute();
$result_1 = $stmt_1->get_result();
$row_1 = $result_1->fetch_assoc();




                $insert_sql = "update std_reg_s set std_name = ? where unique_id = ?";

    $insert_stmt = $mysqli->prepare($insert_sql);
    $insert_stmt->bind_param(
        "ss",
        $row_1['aname'],
        $row['unique_id']
    );
    $insert_result = $insert_stmt->execute();

echo ++$sn.'-'.$row_1['aname'];
            }
        
// Close the connection
$mysqli->close();
?>
