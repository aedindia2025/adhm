<?php 

$servername = "localhost";
$username = "root";
$password = "4/rb5sO2s3TpL4gu";
$database_name = "adi_dravidar";

$conn = new PDO("mysql:host=$servername;dbname=$database_name", $username, $password);


// $sql = $conn->query("SELECT * FROM std_reg_s AS t1 JOIN ( SELECT std_reg_no, MAX(id) AS max_id FROM std_reg_s WHERE bio_reg_status = '0' GROUP BY std_reg_no HAVING COUNT(*) > 1 ) AS duplicates ON t1.id = duplicates.max_id;");
$sql = $conn->query("select unique_id,std_app_no from std_app_s where is_delete = 0 and academic_year = '6847e16e7060491061' and application_type = '1'");

        $row = $sql->fetchAll();
$sn = '0';
$last_app_no = '';

        foreach($row as $value){
           
            // $stmt = $conn->query("SELECT std_app_no as std_app_no FROM std_app_s where is_delete = '0' and application_type = 1 and academic_year = '6847e16e7060491061' ORDER BY CAST(RIGHT(std_app_no, 6) AS UNSIGNED) DESC LIMIT 1");
            // $last_app_no = $stmt->fetchColumn();


            if ($last_app_no == '') {
                $new_seq_no = 1;
            } else {
                $last_seq_no = intval(substr($last_app_no, -6));
                $new_seq_no = $last_seq_no + 1;
            }

echo $new_seq_no.'-';

            $old_app_no = $value['std_app_no'];
            $first_set = substr($old_app_no,0,9);

echo $first_set.'-';
echo $old_app_no.'-';

            // $updated_app_no = substr_replace($old_app_no, $new_seq_no, -5);
            $updated_app_no = $first_set.str_pad($new_seq_no,6,0,STR_PAD_LEFT);

 echo $updated_app_no.'<br>';  
         
            $update_stmt = $conn->prepare("UPDATE std_app_s set std_app_no = '$updated_app_no' where unique_id = '".$value["unique_id"]."'");

            $update_stmt->execute();

            $update_stmt_batch = $conn->prepare("UPDATE batch_creation set std_app_no = '$updated_app_no' where s1_unique_id = '".$value["unique_id"]."'");

            $update_stmt_batch->execute();

$last_app_no++;

            // $update_stmt_reg_s = $conn->prepare("UPDATE std_reg_s set std_app_no = '$updated_app_no' where unique_id = '".$value["unique_id"]."'");

            // $update_stmt_reg_s->execute();

         
            

        }

?>