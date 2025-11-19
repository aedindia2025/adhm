<?php 

$servername = "localhost";
$username = "root";
$password = "4/rb5sO2s3TpL4gu";
$database_name = "adi_dravidar";

$conn = new PDO("mysql:host=$servername;dbname=$database_name", $username, $password);


// $sql = $conn->query("SELECT * FROM std_reg_s AS t1 JOIN ( SELECT std_reg_no, MAX(id) AS max_id FROM std_reg_s WHERE bio_reg_status = '0' GROUP BY std_reg_no HAVING COUNT(*) > 1 ) AS duplicates ON t1.id = duplicates.max_id;");
$sql = $conn->query("SELECT id from attendancerecordinfo");

        $row = $sql->fetchAll();

        foreach($row as $value){
        // echo $value['id'];die();
            $update_stmt = $conn->prepare("UPDATE attendancerecordinfo set sync_status = '3' where id = '".$value["id"]."'");

            $update_stmt->execute();
  echo $value['id'];die();

        }

?>