<?php 

$servername = "localhost";
$username = "root";
$password = "4/rb5sO2s3TpL4gu";
$database_name = "adi_dravidar";

$conn = new PDO("mysql:host=$servername;dbname=$database_name", $username, $password);


// $sql = $conn->query("SELECT * FROM std_reg_s AS t1 JOIN ( SELECT std_reg_no, MAX(id) AS max_id FROM std_reg_s WHERE bio_reg_status = '0' GROUP BY std_reg_no HAVING COUNT(*) > 1 ) AS duplicates ON t1.id = duplicates.max_id;");
$sql = $conn->query("SELECT * FROM std_reg_s AS t1 JOIN ( SELECT std_reg_no, MAX(id) AS max_id FROM std_reg_s GROUP BY std_reg_no HAVING COUNT(*) > 1 ) AS duplicates ON t1.id = duplicates.max_id");

        $row = $sql->fetchAll();
$sn = '0';
        foreach($row as $value){
           
            $stmt = $conn->query("SELECT std_reg_no FROM std_reg_s ORDER BY CAST(RIGHT(std_reg_no, 6) AS UNSIGNED) DESC LIMIT 1");
            $last_reg_no = $stmt->fetchColumn();

            if ($last_reg_no == '') {
                $new_seq_no = 1;
            } else {
                $last_seq_no = intval(substr($last_reg_no, -6));
                $new_seq_no = $last_seq_no + 1;
            }

            $old_reg_no = $value['std_reg_no'];

            $updated_reg_no = substr_replace($old_reg_no, $new_seq_no, -5);

            
         
            $update_stmt = $conn->prepare("UPDATE std_reg_s set std_reg_no = '$updated_reg_no', user_name =  '$updated_reg_no', update_status = '5', update_for = '$old_reg_no' where unique_id = '".$value["unique_id"]."'");

            $update_stmt->execute();
echo ++$sn."old - ".$old_reg_no."new - ".$updated_reg_no;            

        }

?>