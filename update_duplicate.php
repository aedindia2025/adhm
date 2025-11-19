<?php 

$servername = "localhost";
$username = "root";
$password = "4/rb5sO2s3TpL4gu";
$database_name = "adi_dravidar";

$conn = new PDO("mysql:host=$servername;dbname=$database_name", $username, $password);


//$sql = $conn->query("SELECT * FROM dup_entry_upd");
$sql = $conn->query("SELECT * FROM dup_entry_upd_1");
        $row = $sql->fetchAll();
$sn = '0';
        foreach($row as $value){
           
           $reg_no = $value['std_reg_no'];
            $old_reg_no = $value['update_for'];
         echo $reg_no.'-'.$old_reg_no.'<br>'; 
            $update_stmt = $conn->prepare("UPDATE std_reg_s set std_reg_no = '$reg_no', user_name =  '$reg_no', update_status = '2', update_for = '$old_reg_no'  where unique_id = '".$value["unique_id"]."'");

            $update_stmt->execute();
             

        }

?>