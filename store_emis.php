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




$googleApiKey_2 = 'AIzaSyAmP90skchAtlESn2MoO6vkBOMKKFpwtI0';

$get_emis = $pdo->query("select emis_no,(select s1_unique_id from std_app_emis_s3 where std_app_emis_s3.emis_no = no_emis.emis_no order by id asc limit 1) as s1_unique_id from no_emis where emis_no = '1291911433'");

$rows = $get_emis->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as $row) {

    $s1_unique_id = $row['s1_unique_id'];
    $emis_no = $row['emis_no'];


    $url = 'https://tnega.tnschools.gov.in/tnega/api/GetSchlDetails';

    // Data to send in the request
    $data = array(
        'EmisId' => $emis_no
    );

    // Convert data array to JSON
    $data_json = json_encode($data);

    // Authorization token
    $authorization_token = '4acdca2cc493c1ec28e1f68e0d37c49a';

    // Set headers
    $headers = array(
        'Content-Type: application/json',
        'Authorization: ' . $authorization_token
    );

    // Initialize cURL
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Execute cURL request
    $response = curl_exec($ch);

    curl_close($ch);

    // Decode the response
    $data = json_decode($response, true);

    $udise_code = $data['result'][0]['udise_code'];
    $name = $data['result'][0]['name'];
    $dob = $data['result'][0]['dob'];
    $class_studying_id = $data['result'][0]['class_studying_id'];
    $group_name = $data['result'][0]['group_name'];
    $class_section = $data['result'][0]['class_section'];
    $MEDINSTR_DESC = $data['result'][0]['MEDINSTR_DESC'];
    $school_name = $data['result'][0]['school_name'];
    $block_name = $data['result'][0]['block_name'];
    $district_name = $data['result'][0]['district_name'];
    $community_name = $data['result'][0]['community_name'];
    $group_code_id = $data['result'][0]['group_code_id'];
    $mother_occupation = $data['result'][0]['mother_occupation'];
    $father_occupation = $data['result'][0]['father_occupation'];
    $mother_name = $data['result'][0]['mother_name'];
    $father_name = $data['result'][0]['father_name'];
    $s1_unique_id = $row['s1_unique_id'];
    $emis_no = $row['emis_no'];
    $entry_date = date('Y-m-d');
    $unique_id = unique_id();


    // $insert_query = $pdo->prepare("INSERT INTO emis (udise_code,name,dob,class_studying_id,group_name,class_section,MEDINSTR_DESC,school_name,block_name,district_name,community_name,group_code_id,mother_occupation,father_occupation,mother_name,father_name,s1_unique_id,emis_id,entry_date,unique_id) VALUES (:udise_code, :name, :dob, :class_studying_id, :group_name, :class_section,:MEDINSTR_DESC, :school_name, :block_name, :district_name, :community_name,:group_code_id, :mother_occupation, :father_occupation, :mother_name, :father_name,:s1_unique_id, :emis_id, :entry_date, :unique_id)");

    // $insert_query->execute([
    //     'udise_code' => $udise_code,
    //     'name' => $name,
    //     'dob' => $dob,
    //     'class_studying_id' => $class_studying_id,
    //     'group_name' => $group_name,
    //     'class_section' => $class_section,
    //     'MEDINSTR_DESC' => $MEDINSTR_DESC,
    //     'school_name' => $school_name,
    //     'block_name' => $block_name,
    //     'district_name' => $district_name,
    //     'community_name' => $community_name,
    //     'group_code_id' => $group_code_id,
    //     'mother_occupation' => $mother_occupation,
    //     'father_occupation' => $father_occupation,
    //     'mother_name' => $mother_name,
    //     'father_name' => $father_name,
    //     's1_unique_id' => $s1_unique_id,
    //     'emis_id' => $emis_no,
    //     'entry_date' => $entry_date,
    //     'unique_id' => $unique_id
    // ]);

    // $update_query = $pdo->prepare("UPDATE no_emis set udisecode = '$udise_code' where emis_no = '$emis_no'");

    // $update_query->execute();

    // echo "Inserted for emis no - " .$emis_no. "  for s1_unique_id - " .$s1_unique_id. "<br>"; 



 


}



function unique_id($prefix = "")
{

    $unique_id = uniqid() . rand(10000, 99999);

    if ($prefix) {
        $unique_id = $prefix . $unique_id;
    }

    return $unique_id;
}


?>