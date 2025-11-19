<?php 

$servername = "localhost";
$username = "root";
$password = "";
$database_name = "adi_dravidar";

$conn = new PDO("mysql:host=$servername;dbname=$database_name", $username, $password);


$sql = $conn->query("SELECT hostel_id,(select count(id) from std_reg_s where hostel_name.unique_id = std_reg_s.hostel_1) as approved_cnt,(select count(id) from std_reg_s where hostel_name.unique_id = std_reg_s.hostel_1 and bio_reg_status = 1) as bio_reg_count from hostel_name where dev_reg = 1");
        $row = $sql->fetchAll();
$sn = '0';
        foreach($row as $value){
           
           $hostel_id = $value['hostel_id'];
            $bio_reg_count = $value['bio_reg_count'];
            $approved_cnt = $value['approved_cnt'];
echo $hostel_id.'-'.$approved_cnt.'-'.$bio_reg_count.'<br>';
      
  $sql = "update attendance_report set biometric_reg_count = $bio_reg_count, dadwo_approved_count = $approved_cnt where hostel_id = '".$hostel_id."'";
            echo $sql.'<br>';

         
            $update_stmt = $conn->prepare($sql);

           $update_stmt->execute();
            
            
 
        }

?>