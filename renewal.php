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

// Jaccard Similarity Function
function jaccardSimilarity($str1, $str2)
{
    $set1 = array_unique(str_split(mb_strtolower($str1)));
    $set2 = array_unique(str_split(mb_strtolower($str2)));

    $intersection = array_intersect($set1, $set2);
    $union = array_unique(array_merge($set1, $set2));

    $similarity = count($union) > 0 ? count($intersection) / count($union) : 0;

    return round($similarity, 1); // Round to 1 decimal place
}


// Distance calculation Function
function getDistance($fromLat, $fromLong, $toLat, $toLong, $apiKey)
{
    if (!$fromLat || !$toLat)
        return null;
    $from = "$fromLat,$fromLong";
    $to = "$toLat,$toLong";
    $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$from&destinations=$to&key=$apiKey";

    $response = @file_get_contents($url);
    $data = json_decode($response, true);

    if (
        isset($data['rows'][0]['elements'][0]['status']) &&
        $data['rows'][0]['elements'][0]['status'] === 'OK'
    ) {
        return $data['rows'][0]['elements'][0]['distance']['value'] / 1000; // in KM
    }

    return null;
}

// // Step 1: Get all students
// $studentsSql = "SELECT std_reg_no, std_name, hostel_1, uuid, unique_id, hostel_district_1 FROM std_reg_s WHERE dropout_status = 1";
// $stmtStudents = $pdo->query($studentsSql);
// $students = $stmtStudents->fetchAll(PDO::FETCH_ASSOC);

// foreach ($students as $student) {
//     $stdRegNo = $student['std_reg_no'];
//     $uniqueId = $student['unique_id'];

//     // Step 2: Fetch std_app record
//     $appSql = "SELECT emis_class, umis_std_degree, no_umis_course, year_studying, emis_name, umis_name, no_umis_name FROM std_app WHERE s1_unique_id = :unique_id LIMIT 1";
//     $stmtApp = $pdo->prepare($appSql);
//     $stmtApp->execute([':unique_id' => $uniqueId]);
//     $app = $stmtApp->fetch(PDO::FETCH_ASSOC);

//     if (!$app)
//         continue;

//     $emisClass = $app['emis_class'];
//     $umisStdDegree = $app['umis_std_degree'];
//     $noUmisCourse = $app['no_umis_course'];
//     $yearStudying = $app['year_studying'];
//     $emisName = $app['emis_name'];
//     $umisName = $app['umis_name'];
//     $noUmisName = $app['no_umis_name'];

//     // Determine if student is eligible
//     $isSchoolStudent = ($emisClass !== null && $emisClass != '12');
//     $isCollegeStudent = false;

//     if ($emisClass === null) {
//         $destYear = null;

//         $courseName = $umisStdDegree ?? $noUmisCourse;
//         if ($courseName) {
//             $courseSql = "SELECT destination_year FROM course_destination WHERE course_name = :course_name LIMIT 1";
//             $stmtCourse = $pdo->prepare($courseSql);
//             $stmtCourse->execute([':course_name' => $courseName]);
//             $course = $stmtCourse->fetch(PDO::FETCH_ASSOC);
//             if ($course)
//                 $destYear = $course['destination_year'];
//         }

//         if ($destYear !== null && $destYear != $yearStudying) {
//             $isCollegeStudent = true;
//         }
//     }

//     if (!($isSchoolStudent || $isCollegeStudent))
//         continue;

//     // Step 4: Attendance count check
//     $attSql = "
//         SELECT COUNT(*) FROM dayattreport 
//         WHERE std_reg_no = :std_reg_no 
//           AND currentDate BETWEEN '2025-01-01' AND '2025-04-15' 
//           AND (punch_mrg IS NOT NULL OR punch_eve IS NOT NULL)
//     ";
//     $stmtAtt = $pdo->prepare($attSql);
//     $stmtAtt->execute([':std_reg_no' => $stdRegNo]);
//     $attCount = (int) $stmtAtt->fetchColumn();

//     $renewalOpt = ($attCount > 15) ? 1 : 2;

//     // Step 5: Build name_emis_umis
//     $nameEmisUmis = $emisName ?: ($umisName ?: $noUmisName);

//     // Step 6: Build std_degree
//     $stdDegree = $emisClass ?: ($umisStdDegree ?: $noUmisCourse);

//     // Step 7: Fetch father_name
//     $stmtFather = $pdo->prepare("SELECT father_name FROM std_app_s2 WHERE s1_unique_id = :unique_id LIMIT 1");
//     $stmtFather->execute([':unique_id' => $uniqueId]);
//     $fatherRow = $stmtFather->fetch(PDO::FETCH_ASSOC);
//     $fatherName = $fatherRow ? $fatherRow['father_name'] : null;

//     // Step 8: Insert into renewal table
//     $insertSql = "
//         INSERT INTO renewal (
//             std_reg_no, std_name, renewal_opt, hostel_id, uuid, s1_unique_id, hostel_district,
//             name_emis_umis, std_degree, father_name
//         )
//         VALUES (
//             :std_reg_no, :std_name, :renewal_opt, :hostel_id, :uuid, :s1_unique_id, :hostel_district,
//             :name_emis_umis, :std_degree, :father_name
//         )
//     ";
//     $stmtInsert = $pdo->prepare($insertSql);
//     $stmtInsert->execute([
//         ':std_reg_no' => $stdRegNo,
//         ':std_name' => $student['std_name'],
//         ':renewal_opt' => $renewalOpt,
//         ':hostel_id' => $student['hostel_1'],
//         ':uuid' => $student['uuid'],
//         ':s1_unique_id' => $uniqueId,
//         ':hostel_district' => $student['hostel_district_1'],
//         ':name_emis_umis' => $nameEmisUmis,
//         ':std_degree' => $stdDegree,
//         ':father_name' => $fatherName
//     ]);

//     // Step 9: Update renewal_status in std_app_s
//     $stmtUpdate = $pdo->prepare("UPDATE std_app_s SET renewal_status = :renewal_status WHERE unique_id = :unique_id");
//     $stmtUpdate->execute([
//         ':renewal_status' => $renewalOpt,
//         ':unique_id' => $uniqueId
//     ]);


// }

// // Step 10: Update name_diff in renewal using Jaccard
// $stmtRenewal = $pdo->query("SELECT id, std_name, name_emis_umis FROM renewal WHERE name_emis_umis IS NOT NULL");
// $rows = $stmtRenewal->fetchAll(PDO::FETCH_ASSOC);

// foreach ($rows as $row) {
//     $jaccard = jaccardSimilarity($row['std_name'], $row['name_emis_umis']);
//     $stmtUpdateDiff = $pdo->prepare("UPDATE renewal SET name_diff = :name_diff WHERE id = :id");
//     $stmtUpdateDiff->execute([
//         ':name_diff' => $jaccard,
//         ':id' => $row['id']
//     ]);
// }


// // Step 11: Update lat and long in aadhar table
// $updateAadhar = $pdo->query("SELECT s1_unique_id FROM renewal");
// $rows = $updateAadhar->fetchAll(PDO::FETCH_ASSOC);

// foreach ($rows as $row) {
//     $googleApiKey = 'AIzaSyAmP90skchAtlESn2MoO6vkBOMKKFpwtI0';
//     $s1_unique_id = $row['s1_unique_id'];

//     $q2 = "SELECT aaddress FROM aadhar WHERE s1_unique_id = '$s1_unique_id'";
//     $res2 = mysqli_query($conn, $q2);
//     $row2 = mysqli_fetch_assoc($res2);

//     if (!$row2 || empty($row2['aaddress'])) continue;

//     $fullAddress = $row2['aaddress'];
//     $parts = array_map('trim', explode(',', $fullAddress));

//     // Extract last 5 parts, remove the PIN code (last part)
//     $lastParts = array_slice($parts, -5);
//     array_pop($lastParts); // Remove PIN

//     // Build address string for geocoding
//     $addressLatLong = implode('+', $lastParts);

//     // Call Google Geocoding API
//     $apiUrl = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($addressLatLong) . "&key=" . $googleApiKey;

//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, $apiUrl);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//     $apiResponse = curl_exec($ch);
//     curl_close($ch);

//     $lat = null;
//     $lng = null;
//     $apiData = json_decode($apiResponse, true);

//     if (!empty($apiData['results'][0]['geometry']['location'])) {
//         $lat = $apiData['results'][0]['geometry']['location']['lat'];
//         $lng = $apiData['results'][0]['geometry']['location']['lng'];

//         // Update aadhar table
//         $qUpdate = "UPDATE aadhar SET latitude = ?, longitude = ? WHERE s1_unique_id = ?";
//         $stmt = mysqli_prepare($conn, $qUpdate);
//         if ($stmt) {
//             mysqli_stmt_bind_param($stmt, "dds", $lat, $lng, $s1_unique_id);
//             mysqli_stmt_execute($stmt);
//             mysqli_stmt_close($stmt);
//         }
//     }
// }


// Step 12: Calculate distances and update renewal table
// $googleApiKey_2 = 'AIzaSyAmP90skchAtlESn2MoO6vkBOMKKFpwtI0';

// $getDistance = $pdo->query("SELECT s1_unique_id FROM renewal WHERE std_to_inst_distance IS NULL AND std_to_hostel_distance IS NULL");
// $rows = $getDistance->fetchAll(PDO::FETCH_ASSOC);

// foreach ($rows as $row) {
//     $s1_unique_id = $row['s1_unique_id'];

//     // Get student_type and hostel_1 from std_app_s
//     $stmtAppS = $pdo->prepare("SELECT student_type, hostel_1 FROM std_app_s WHERE unique_id = :unique_id");
//     $stmtAppS->execute([':unique_id' => $s1_unique_id]);
//     $appS = $stmtAppS->fetch(PDO::FETCH_ASSOC);

//     if (!$appS) continue;

//     $studentType = $appS['student_type'];
//     $hostel1 = $appS['hostel_1'];

//     // Get institution lat/long based on student type
//     if ($studentType === '65f00a259436412348') { // EMIS
//         $stmtEmis = $pdo->prepare("SELECT udise_code FROM emis WHERE s1_unique_id = :unique_id");
//         $stmtEmis->execute([':unique_id' => $s1_unique_id]);
//         $emisId = $stmtEmis->fetchColumn();

//         $stmtLatLong = $pdo->prepare("SELECT latitude, longitude FROM scl_lat_long WHERE udise_code = :udise_code");
//         $stmtLatLong->execute([':udise_code' => $emisId]);
//     } else { // UMIS
//         $stmtUmis = $pdo->prepare("SELECT instituteId FROM umis_1 WHERE s1_unique_id = :unique_id");
//         $stmtUmis->execute([':unique_id' => $s1_unique_id]);
//         $umisNo = $stmtUmis->fetchColumn();

//         $stmtLatLong = $pdo->prepare("SELECT latitude, longitude FROM clg_lat_long WHERE instituteId = :instituteId");
//         $stmtLatLong->execute([':instituteId' => $umisNo]);
//     }

//     $instLatLong = $stmtLatLong->fetch(PDO::FETCH_ASSOC);
//     $instLat = $instLatLong['latitude'] ?? null;
//     $instLong = $instLatLong['longitude'] ?? null;

//     // Get student lat/long
//     $stmtStuLat = $pdo->prepare("SELECT latitude, longitude FROM aadhar WHERE s1_unique_id = :unique_id");
//     $stmtStuLat->execute([':unique_id' => $s1_unique_id]);
//     $stuLatLong = $stmtStuLat->fetch(PDO::FETCH_ASSOC);
//     $stuLat = $stuLatLong['latitude'] ?? null;
//     $stuLong = $stuLatLong['longitude'] ?? null;

//     // Get hostel lat/long
//     $stmtHostel = $pdo->prepare("SELECT latitude, longitude FROM hostel_name WHERE unique_id = :hostel_id");
//     $stmtHostel->execute([':hostel_id' => $hostel1]);
//     $hostelLatLong = $stmtHostel->fetch(PDO::FETCH_ASSOC);
//     $hostelLat = $hostelLatLong['latitude'] ?? null;
//     $hostelLong = $hostelLatLong['longitude'] ?? null;

//     // Skip if any required lat/long is missing
//     if (!$stuLat || !$stuLong || !$instLat || !$instLong || !$hostelLat || !$hostelLong) continue;

//     // Compute distances
//     $stdToInst = getDistance($stuLat, $stuLong, $instLat, $instLong, $googleApiKey_2);
//     $stdToHostel = getDistance($stuLat, $stuLong, $hostelLat, $hostelLong, $googleApiKey_2);

//     // Update in renewal
//     $stmtUpdateRenewal = $pdo->prepare("UPDATE renewal SET std_to_inst_distance = ?, std_to_hostel_distance = ? WHERE s1_unique_id = ?");
//     $stmtUpdateRenewal->execute([$stdToInst, $stdToHostel, $s1_unique_id]);

//     // Update in std_app_s
//     $stmtUpdateStdAppS = $pdo->prepare("UPDATE std_app_s SET std_to_inst_distance = ?, std_to_hostel_distance = ? WHERE unique_id = ?");
//     $stmtUpdateStdAppS->execute([$stdToInst, $stdToHostel, $s1_unique_id]);
// }

$googleApiKey_2 = 'AIzaSyAmP90skchAtlESn2MoO6vkBOMKKFpwtI0';

$getDistance = $pdo->query("SELECT s1_unique_id FROM renewal WHERE std_to_inst_distance IS NULL AND std_to_hostel_distance IS NULL");
$rows = $getDistance->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as $row) {
    $s1_unique_id = $row['s1_unique_id'];

    // Get student_type and hostel_1 from std_app_s
    $stdAppSSql = "SELECT student_type, hostel_1 FROM std_app_s WHERE unique_id = :unique_id";
    $stmtAppS = $pdo->prepare($stdAppSSql);
    $stmtAppS->execute([':unique_id' => $s1_unique_id]);
    $appS = $stmtAppS->fetch(PDO::FETCH_ASSOC);

    if (!$appS)
        continue;

    $studentType = $appS['student_type'];
    $hostel1 = $appS['hostel_1'];

    // Get EMIS or UMIS lat/long
    if ($studentType == '65f00a259436412348') {
        $emisSql = "SELECT emis_no FROM std_app_emis_s3 WHERE s1_unique_id = :unique_id";
        $stmtEmis = $pdo->prepare($emisSql);
        $stmtEmis->execute([':unique_id' => $s1_unique_id]);
        $emisData = $stmtEmis->fetch(PDO::FETCH_ASSOC);
        $emisId = $emisData['emis_no'] ?? null;

        $emisResponse = file_get_contents('https://tnega.tnschools.gov.in/tnega/api/GetSchlDetails', false, stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/json\r\nAuthorization: 4acdca2cc493c1ec28e1f68e0d37c49a\r\n",
                'content' => json_encode(['EmisId' => $emisId])
            ]
        ]));

        $emisJson = json_decode($emisResponse, true);
        $udiseCode = $emisJson['result'][0]['udise_code'] ?? null;

        $instLatLongSql = "SELECT latitude, longitude FROM scl_lat_long WHERE udise_code = :udise_code";
        $stmtLatLong = $pdo->prepare($instLatLongSql);
        $stmtLatLong->execute([':udise_code' => $udiseCode]);
        if (!empty($udiseCode)) {
            $instLatLongSql = "SELECT latitude, longitude FROM scl_lat_long WHERE udise_code = :udise_code";
            $stmtLatLong = $pdo->prepare($instLatLongSql);
            $stmtLatLong->execute([':udise_code' => $udiseCode]);

        } else {
            error_log("Missing or invalid udise_code. emisResponse: " . $emisResponse);
        }
    } else {
        $umisSql = "SELECT umis_no FROM std_app_umis_s4 WHERE s1_unique_id = :unique_id";
        $stmtUmis = $pdo->prepare($umisSql);
        $stmtUmis->execute([':unique_id' => $s1_unique_id]);
        $umisData = $stmtUmis->fetch(PDO::FETCH_ASSOC);
        $umisNo = $umisData['umis_no'] ?? null;

        $umisResponse = file_get_contents("https://umisapi.tnega.org/api/ADWD/GetStudentData/$umisNo", false, stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => "Content-Type: application/json\r\nAuthorization: 4acdca2cc493c1ec28e1f68e0d37c49a\r\n"
            ]
        ]));

        $umisJson = json_decode($umisResponse, true);
        $instituteId = $umisJson['instituteId'] ?? null;

        $instLatLongSql = "SELECT latitude, longitude FROM clg_lat_long WHERE instituteId = :instituteId";
        $stmtLatLong = $pdo->prepare($instLatLongSql);
        $stmtLatLong->execute([':instituteId' => $instituteId]);
    }

    $instLatLong = $stmtLatLong->fetch(PDO::FETCH_ASSOC);
    $instLat = $instLatLong['latitude'] ?? null;
    $instLong = $instLatLong['longitude'] ?? null;

    // Get student lat/long
    $stuLatSql = "SELECT latitude, longitude FROM aadhar WHERE s1_unique_id = :unique_id";
    $stmtStuLat = $pdo->prepare($stuLatSql);
    $stmtStuLat->execute([':unique_id' => $s1_unique_id]);
    $stuLatLong = $stmtStuLat->fetch(PDO::FETCH_ASSOC);
    $stuLat = $stuLatLong['latitude'] ?? null;
    $stuLong = $stuLatLong['longitude'] ?? null;

    // Get hostel lat/long
    $hostelLatSql = "SELECT latitude, longitude FROM hostel_name WHERE unique_id = :hostel_id";
    $stmtHostel = $pdo->prepare($hostelLatSql);
    $stmtHostel->execute([':hostel_id' => $hostel1]);
    $hostelLatLong = $stmtHostel->fetch(PDO::FETCH_ASSOC);
    $hostelLat = $hostelLatLong['latitude'] ?? null;
    $hostelLong = $hostelLatLong['longitude'] ?? null;

    // Compute distances
    $stdToInst = getDistance($stuLat, $stuLong, $instLat, $instLong, $googleApiKey_2);
    $stdToHostel = getDistance($stuLat, $stuLong, $hostelLat, $hostelLong, $googleApiKey_2);

    // Update in renewal
    $updateRenewal = "UPDATE renewal SET std_to_inst_distance = ?, std_to_hostel_distance = ? WHERE s1_unique_id = ?";
    $stmtUpdateRenewal = mysqli_prepare($conn, $updateRenewal);
    mysqli_stmt_bind_param($stmtUpdateRenewal, "sss", $stdToInst, $stdToHostel, $s1_unique_id);
    mysqli_stmt_execute($stmtUpdateRenewal);
    mysqli_stmt_close($stmtUpdateRenewal);

    // Update in StdAppS
    $updateStdAppS = "UPDATE std_app_s SET std_to_inst_distance = ?, std_to_hostel_distance = ? WHERE unique_id = ?";
    $stmtUpdateStdAppS = mysqli_prepare($conn, $updateStdAppS);
    mysqli_stmt_bind_param($stmtUpdateStdAppS, "sss", $stdToInst, $stdToHostel, $s1_unique_id);
    mysqli_stmt_execute($stmtUpdateStdAppS);
    mysqli_stmt_close($stmtUpdateStdAppS);
}

echo "Renewal processing completed with Jaccard similarity.";
