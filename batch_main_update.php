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

$stmt_query = "select academic_year,hostel_name,batch_no,batch_cr_date from batch_creation group by academic_year,hostel_name,batch_no";
$stmt = $mysqli->prepare($stmt_query);
$stmt->execute();
        $result = $stmt->get_result();

       

        $sn = '0';
            while ($row = $result->fetch_assoc()) {

                $insert_sql = "INSERT INTO batch_main 
                (hostel_name, academic_year, batch_no, batch_cr_date) 
                VALUES (?, ?, ?, ?)";

    $insert_stmt = $mysqli->prepare($insert_sql);
    $insert_stmt->bind_param(
        "ssss",
        $row['hostel_name'],
        $row['academic_year'],
        $row['batch_no'],
        $row['batch_cr_date']
        
    );
    $insert_result = $insert_stmt->execute();

echo ++$sn.'-'.$row['batch_no'];
            }
        
// Close the connection
$mysqli->close();
?>
