<?php 

$servername = "localhost";
$username = "root";
$password = "4/rb5sO2s3TpL4gu";
$database_name = "adi_dravidar";

$conn = new PDO("mysql:host=$servername;dbname=$database_name", $username, $password);


// $sql = $conn->query("SELECT * FROM std_reg_s AS t1 JOIN ( SELECT std_reg_no, MAX(id) AS max_id FROM std_reg_s WHERE bio_reg_status = '0' GROUP BY std_reg_no HAVING COUNT(*) > 1 ) AS duplicates ON t1.id = duplicates.max_id;");
$sql = $conn->query("SELECT * FROM std_app_s WHERE id NOT IN (SELECT MAX(id) FROM std_app_s WHERE academic_year = '6847e16e7060491061' AND application_type = 2 AND renewal_status IN (1, 2) GROUP BY std_app_no ) AND academic_year = '6847e16e7060491061' AND application_type = 2 AND renewal_status IN (1, 2) order by std_app_no");

        $row = $sql->fetchAll();
$sn = '0';
        foreach($row as $value){
           
            
             $s1_unique_id = $value['unique_id'];
             

        //     $delete_app_s = $conn->prepare("DELETE FROM std_app_s WHERE unique_id = '$s1_unique_id'");
        //     $delete_app_s->execute();

        //     $delete_batch_creation = $conn->prepare("DELETE FROM batch_creation WHERE s1_unique_id = '$s1_unique_id'");
        //     $delete_batch_creation->execute();

       
            

        }

?>