<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

set_time_limit(-1);
// DB connection
$pdo = new PDO('mysql:host=localhost;dbname=adi_dravidar', 'root', '4/rb5sO2s3TpL4gu');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$conn = mysqli_connect("localhost", "root", "4/rb5sO2s3TpL4gu", "adi_dravidar");
if (!$conn) {
    echo json_encode(["status" => false, "msg" => "DB connection failed"]);
    exit;
}
$google_api_key = 'AIzaSyAmP90skchAtlESn2MoO6vkBOMKKFpwtI0';


  
// 1. Get student_type and hostel_1
$q1 = "SELECT * FROM renewal where std_degree IN ('1','2','3','4','5','6','7','8','9','10','11','12') and std_to_inst_distance IS NULL";
$res1 = mysqli_query($conn, $q1);
$data1 = [];
while ($row = mysqli_fetch_assoc($res1)) {
    $data1[] = $row;
}

if (!$data1) {
    echo json_encode(["status" => false, "msg" => "Student not found"]);
    exit;
}
foreach ($data1 as $row1) {

    
    $hostel_1 = $row1['hostel_id'];
    $s1_unique_id = $row1['s1_unique_id']; 
    $uuid = $row1['uuid']; 



        $q2 = "SELECT emis_id,udise_code FROM emis WHERE s1_unique_id = '$s1_unique_id'";
        $res2 = mysqli_query($conn, $q2);
        $emis = mysqli_fetch_assoc($res2);
        $udise_code = $emis['udise_code'];
        $emis_id = $emis['emis_id'];
       
        

        $q3 = "SELECT latitude, longitude FROM scl_lat_long WHERE udise_code = '$udise_code'";
   
    // 3. Get institution lat/long
    $res3 = mysqli_query($conn, $q3);
    $inst_data = mysqli_fetch_assoc($res3);
    echo '<br>' . $inst_lat = $inst_data['latitude'] ?? null;
    echo '<br>' . $inst_long = $inst_data['longitude'] ?? null;

    // 4. Get student lat/long
    $q4 = "SELECT latitude, longitude FROM aadhar WHERE uuid = '$uuid'";
    $res4 = mysqli_query($conn, $q4);
    $stu_data = mysqli_fetch_assoc($res4);
    echo '<br>' . $stu_lat = $stu_data['latitude'] ?? null;
    echo '<br>' . $stu_long = $stu_data['longitude'] ?? null;

    // 5. Get hostel lat/long
    $q5 = "SELECT latitude, longitude FROM hostel_name WHERE unique_id = '$hostel_1'";
    $res5 = mysqli_query($conn, $q5);
    $hostel_data = mysqli_fetch_assoc($res5);
    echo '<br>' . $hostel_lat = $hostel_data['latitude'] ?? null;
    echo '<br>' . $hostel_long = $hostel_data['longitude'] ?? null;

//get_distance function

    // 6. Calculate distances
    echo '<br>' . $std_to_inst = get_distance($stu_lat, $stu_long, $inst_lat, $inst_long, $google_api_key);
    echo '<br>' . $inst_to_hostel = get_distance($inst_lat, $inst_long, $hostel_lat, $hostel_long, $google_api_key);
  
    // 7. Update std_app_s 
    $upd = "UPDATE std_app_s SET 
                std_to_inst_distance = " . ($std_to_inst !== NULL ? "'$std_to_inst'" : "NULL") . ",
                inst_to_hostel_distance = " . ($inst_to_hostel !== NULL ? "'$inst_to_hostel'" : "NULL") . "
            WHERE uuid = '$uuid'";

    $res_upd = mysqli_query($conn, $upd);

    // 8. Update renewal
    $upd_renewal = "UPDATE renewal SET 
                std_to_inst_distance = " . ($std_to_inst !== NULL ? "'$std_to_inst'" : "NULL") . "
                WHERE s1_unique_id = '$s1_unique_id'";

    $res_upd_renewal = mysqli_query($conn, $upd_renewal);
}

    // Helper to get distance using Google Distance Matrix API
    function get_distance($from_lat, $from_long, $to_lat, $to_long, $key)
    {
        if (!$from_lat || !$to_lat)
            return null;

        $from = "$from_lat,$from_long";
        $to = "$to_lat,$to_long";
 
        echo $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$from&destinations=$to&key=$key";

        $response = file_get_contents($url);
        $json = json_decode($response, true);

        if (
            isset($json['rows'][0]['elements'][0]['status']) &&
            $json['rows'][0]['elements'][0]['status'] === 'OK'
        ) {
            return $json['rows'][0]['elements'][0]['distance']['value'] / 1000; // KM
        }
        return null;
    }