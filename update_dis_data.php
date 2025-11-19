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

// 1. Get students missing distance values in target year
$target_year = '6847e16e7060491061'; //new
$source_year = '664dc72a74d5299717'; //old

$q1 = "SELECT std_app_no FROM std_app_s 
       WHERE academic_year = '$target_year' 
       AND (std_to_inst_distance IS NULL OR std_to_hostel_distance IS NULL)";
$res1 = mysqli_query($conn, $q1);

if (!$res1) {
    echo json_encode(["status" => false, "msg" => "Query failed"]);
    exit;
}

$updated = 0;
$not_found = [];

while ($row1 = mysqli_fetch_assoc($res1)) {
    $std_app_no = $row1['std_app_no'];

    // 2. Fetch distances from source year
    $q2 = "SELECT std_to_inst_distance, std_to_hostel_distance FROM std_app_s 
           WHERE academic_year = '$source_year' AND std_app_no = '$std_app_no' LIMIT 1";
    $res2 = mysqli_query($conn, $q2);

    if ($res2 && mysqli_num_rows($res2) > 0) {
        $source_row = mysqli_fetch_assoc($res2);
        $inst_distance = $source_row['std_to_inst_distance'];
        $hostel_distance = $source_row['std_to_hostel_distance'];

        // 3. Update distances in target year
        $q3 = "UPDATE std_app_s 
               SET std_to_inst_distance = ?, std_to_hostel_distance = ? 
               WHERE academic_year = ? AND std_app_no = ?";
        $stmt = $pdo->prepare($q3);
        $stmt->execute([$inst_distance, $hostel_distance, $target_year, $std_app_no]);
        $updated++;
    } else {
        $not_found[] = $std_app_no;
    }
}

echo json_encode([
    "status" => true,
    "updated_count" => $updated,
    "not_found" => $not_found
]);
