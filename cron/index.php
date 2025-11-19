<?php 

$mysqli = new mysqli("localhost", "root", "4/rb5sO2s3TpL4gu", "adi_dravidar");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
$datetime = date("Y-m-d H:i:s");
$update_sql = "insert into date_time (date_time) values('$datetime')";
        $stmt = $mysqli->prepare($update_sql);
        $stmt->execute();
        echo "stored successfully";


?>