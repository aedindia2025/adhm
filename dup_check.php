<?php

$servername = "localhost";
$username = "root";
$password = "4/rb5sO2s3TpL4gu";
$database_name = "adi_dravidar";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// $stmt = $conn->query("SELECT std_app_no FROM std_app_s WHERE is_delete = '0' and academic_year = '" . $academic_year . "' and application_type = 1 ORDER BY CAST(RIGHT(std_app_no, 6) AS UNSIGNED) DESC LIMIT 1");
// $last_reg_no = $stmt->fetchColumn();

// if ($last_reg_no === false) {
//     $new_seq_no = 1;
// } else {
//     $last_numeric_part = intval(substr($last_reg_no, -6));
//     $new_seq_no = $last_numeric_part + 1;
// }
$app_no = '2025ADTWC000001';

function get_app_no($conn,$app_no){
try {

    $insert_stmt = $conn->prepare("INSERT INTO dup_check (app_no) values ('$app_no')");
    $insert_stmt->execute();
} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        // ⚠️ Duplicate key error → retry
        continue;
    } else {
        throw $e; // Other DB error
    }
}
}





?>