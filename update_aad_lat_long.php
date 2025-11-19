<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
set_time_limit(0);

// DB connection
$conn = mysqli_connect("localhost", "root", "4/rb5sO2s3TpL4gu", "adi_dravidar");
if (!$conn) {
    die(json_encode(["status" => false, "msg" => "DB connection failed"]));
}

// Distance calculation function using Google API
function getDistance($fromLat, $fromLng, $toLat, $toLng, $apiKey)
{
    if (!$fromLat || !$fromLng || !$toLat || !$toLng) return null;

    $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins={$fromLat},{$fromLng}&destinations={$toLat},{$toLng}&key={$apiKey}";
    $response = @file_get_contents($url);
    $data = json_decode($response, true);

    if (
        isset($data['rows'][0]['elements'][0]['status']) &&
        $data['rows'][0]['elements'][0]['status'] === 'OK'
    ) {
        return $data['rows'][0]['elements'][0]['distance']['value'] / 1000; // KM
    }
    return null;
}

// API Key
$googleApiKey = 'AIzaSyAmP90skchAtlESn2MoO6vkBOMKKFpwtI0';

// Fetch applicable students
$sql = "SELECT uuid, unique_id, student_type FROM std_app_s 
        WHERE std_to_hostel_distance IS NULL 
          AND std_to_inst_distance IS NULL 
          AND academic_year != '664dc72a74d5299717' 
          AND submit_status = 1
          AND is_delete = 0";

$res = mysqli_query($conn, $sql);
$count = 0;

while ($row = mysqli_fetch_assoc($res)) {
    $count++;
    $s1_unique_id = $row['unique_id'];
    $student_type = $row['student_type'];
    $uuid = $row['uuid'];

    echo "\n\n==== Processing $count : $s1_unique_id ====\n";

    // Get student lat/lng
    $qAadhar = "SELECT latitude, longitude FROM aadhar WHERE uuid = '$uuid'";
    $resAadhar = mysqli_query($conn, $qAadhar);

    if (!$resAadhar) {
        echo "MySQL Error: " . mysqli_error($conn) . " while running: $qAadhar\n";
        continue;
    }

    $aadhar = mysqli_fetch_assoc($resAadhar);
    if (!$aadhar) {
        echo "Skipping $s1_unique_id No matching record in aadhar table for this s1_unique_id\n";
        continue;
    }

    if (empty($aadhar['latitude']) || empty($aadhar['longitude'])) {
        echo "Skipping $s1_unique_id Aadhar record exists but latitude or longitude is empty\n";
        continue;
    }

    $stuLat = $aadhar['latitude'];
    $stuLng = $aadhar['longitude'];

    // Get hostel lat/lng
    $hostelRes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT hostel_1 FROM std_app_s WHERE unique_id = '$s1_unique_id'"));
    if (!$hostelRes || !$hostelRes['hostel_1']) {
        echo "Skipping $s1_unique_id Missing hostel_1 field in std_app_s\n";
        continue;
    }

    $hostelId = $hostelRes['hostel_1'];
    $hostelLoc = mysqli_fetch_assoc(mysqli_query($conn, "SELECT latitude, longitude FROM hostel_name WHERE unique_id = '$hostelId'"));
    if (!$hostelLoc || !$hostelLoc['latitude'] || !$hostelLoc['longitude']) {
        echo "Skipping $s1_unique_id Missing hostel coordinates for hostel_id: $hostelId\n";
        continue;
    }

    $hostelLat = $hostelLoc['latitude'];
    $hostelLng = $hostelLoc['longitude'];

    // Calculate distance to hostel
    $hostelDistance = getDistance($stuLat, $stuLng, $hostelLat, $hostelLng, $googleApiKey);
    if (is_null($hostelDistance)) {
        echo "Skipping $s1_unique_id Failed to calculate distance to hostel\n";
        continue;
    } else {
        echo "Hostel Distance for $s1_unique_id: $hostelDistance KM\n";
    }

    // Determine institute coordinates
    $instLat = $instLng = null;

    if ($student_type === '65f00a259436412348') {
        $emis = mysqli_fetch_assoc(mysqli_query($conn, "SELECT udise_code FROM emis WHERE s1_unique_id = '$s1_unique_id'"));
        if ($emis && $emis['udise_code']) {
            $udise = $emis['udise_code'];
            $inst = mysqli_fetch_assoc(mysqli_query($conn, "SELECT latitude, longitude FROM scl_lat_long WHERE udise_code = '$udise'"));
            if ($inst) {
                $instLat = $inst['latitude'];
                $instLng = $inst['longitude'];
            } else {
                echo "Skipping $s1_unique_id No lat/long found for udise_code: $udise\n";
                continue;
            }
        } else {
            echo "Skipping $s1_unique_id No udise_code in emis table\n";
            continue;
        }
    } else {
        $umis = mysqli_fetch_assoc(mysqli_query($conn, "SELECT instituteId FROM umis_1 WHERE s1_unique_id = '$s1_unique_id'"));
        $instId = $umis['instituteId'] ?? null;

        if (!$instId) {
            $umisAlt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT no_umis_inst_id FROM std_app_umis_s4 WHERE s1_unique_id = '$s1_unique_id'"));
            $instId = $umisAlt['no_umis_inst_id'] ?? null;
        }

        if ($instId) {
            $inst = mysqli_fetch_assoc(mysqli_query($conn, "SELECT latitude, longitude FROM clg_lat_long WHERE instituteId = '$instId'"));
            if ($inst) {
                $instLat = $inst['latitude'];
                $instLng = $inst['longitude'];
            } else {
                echo "Skipping $s1_unique_id No lat/long found in clg_lat_long for instituteId: $instId\n";
                continue;
            }
        } else {
            echo "Skipping $s1_unique_id No instituteId or no_umis_inst_id found\n";
            continue;
        }
    }

    // Calculate distance to institution
    $instDistance = getDistance($stuLat, $stuLng, $instLat, $instLng, $googleApiKey);
    if (is_null($instDistance)) {
        echo "Skipping $s1_unique_id Failed to calculate distance to institution\n";
        continue;
    } else {
        echo "Institute Distance for $s1_unique_id: $instDistance KM\n";
    }

    // Update both distances
    $stmt = mysqli_prepare($conn, "UPDATE std_app_s SET std_to_hostel_distance = ?, std_to_inst_distance = ? WHERE unique_id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "dds", $hostelDistance, $instDistance, $s1_unique_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo "✅ Updated distances for $s1_unique_id\n";
    } else {
        echo "❌ Failed to prepare statement for updating $s1_unique_id\n";
    }
}

echo "\nAll records processed.\n";
