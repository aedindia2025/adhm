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


$sql = 'SELECT * from view_std';

$stmt = $mysqli->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$sn = '1';

while ($row = $result->fetch_assoc()) {
    
    $count = '0';
    $sql_cnt = 'SELECT count(s1_unique_id) as cnt from std_app_report where s1_unique_id = "' . $row['s1_unique_id'] . '"';
    $stmt_cnt = $mysqli->prepare($sql_cnt);
    $stmt_cnt->execute();
    $result_cnt = $stmt_cnt->get_result();
    $row_cnt = $result_cnt->fetch_assoc();
    
    // Check if the record exists
    if ($row_cnt['cnt'] > 0) {
        // Update operation
        $sql_update = "UPDATE std_app_report SET 
            std_app_no = ?, 
            academic_year = ?, 
            aca_year = ?, 
            entry_date = ?, 
           
            application_type = ?, 
            student_type = ?, 
            gender = ?, 
            std_address = ?, 
            hostel_1 = ?, 
            hostel_district_1 = ?, 
            hostel_taluk_1 = ?, 
            hostel_name = ?, 
            hostel_district = ?, 
            hostel_taluk = ?, 
            submit_status = ?, 
            status = ?, 
            status_upd_date = ?, 
            emis_no = ?, 
            emis_name = ?, 
            umis_no = ?, 
            umis_name = ?, 
            no_umis_name = ?
            
           
            
        WHERE s1_unique_id = ?";
        
        $stmt_update = $mysqli->prepare($sql_update);
        $stmt_update->bind_param('sssssssssssssssssssssss', 
            $row['std_app_no'], 
            $row['academic_year'], 
            $row['aca_year'], 
            $row['entry_date'], 
           
            $row['application_type'], 
            $row['student_type'], 
            $row['gender'], 
            $row['std_address'], 
            $row['hostel_1'], 
            $row['hostel_district_1'], 
            $row['hostel_taluk_1'], 
            $row['hostel_name'], 
            $row['hostel_district'], 
            $row['hostel_taluk'], 
            $row['submit_status'], 
            $row['status'], 
            $row['status_upd_date'], 
            $row['emis_no'], 
            $row['emis_name'], 
            $row['umis_no'], 
            $row['umis_name'], 
            $row['no_umis_name'], 
           
           
            $row['s1_unique_id']
        );
        $stmt_update->execute();
    } else {
        // Insert operation
        $sql_insert = "INSERT INTO std_app_report (
            std_app_no, 
            academic_year, 
            aca_year, 
            entry_date, 
            s1_unique_id, 
            application_type, 
            student_type, 
            gender, 
            std_address, 
            hostel_1, 
            hostel_district_1, 
            hostel_taluk_1, 
            hostel_name, 
            hostel_district, 
            hostel_taluk, 
            submit_status, 
            status, 
            status_upd_date, 
            emis_no, 
            emis_name, 
            umis_no, 
            umis_name, 
            no_umis_name           
            
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt_insert = $mysqli->prepare($sql_insert);
        $stmt_insert->bind_param('sssssssssssssssssssssss', 
            $row['std_app_no'], 
            $row['academic_year'], 
            $row['aca_year'], 
            $row['entry_date'], 
            $row['s1_unique_id'], 
            $row['application_type'], 
            $row['student_type'], 
            $row['gender'], 
            $row['std_address'], 
            $row['hostel_1'], 
            $row['hostel_district_1'], 
            $row['hostel_taluk_1'], 
            $row['hostel_name'], 
            $row['hostel_district'], 
            $row['hostel_taluk'], 
            $row['submit_status'], 
            $row['status'], 
            $row['status_upd_date'], 
            $row['emis_no'], 
            $row['emis_name'], 
            $row['umis_no'], 
            $row['umis_name'], 
            $row['no_umis_name']
           
           
        ); 
        $stmt_insert->execute();
    }
    
echo $sn++.'-'.$row['std_app_no'].'<br>';
    // Execute the statement
   
    

    // Close the statement
    // $stmt_update->close();
    // $stmt_insert->close();
}

$stmt->close();
?>
