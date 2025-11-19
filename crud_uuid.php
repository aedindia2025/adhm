<?php

set_time_limit(0); // No time limit


// Get folder Name From Currnent Url     
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "aadhar";

// // Include DB file and Common Functions
include 'config/dbconfig.php';

// // Variables Declaration
$action = $_POST['action'];

$hostel_name = "";
$is_active = "";
$unique_id = "";
$prefix = "";

$data = "";
$msg = "";
$error = "";
$status = "";
$test = ""; // For Developer Testing Purpose

switch ($action) {
    case 'getRecord':
        try {
            // Fetch UUIDs from the aadhar table
            $table_details = [
                "aadhar_new",
                ["uuid"]
            ];
            $where = 'temp_aadhar IS NULL';
            $action_obj = $pdo->select($table_details,$where);

            if (!$action_obj->status) {
                throw new Exception("Error fetching UUIDs: " . $action_obj->error);
            }

            $uuids = $action_obj->data;
            $stored_records = 0;
            $failed_records = 0;

            foreach ($uuids as $device) {
                $uuid = $device["uuid"];

                // API endpoint
                $url = 'https://tnpreauth.tn.gov.in/auakua25dvuat/api/datavault/GetUID?UUID=' . $uuid . '&AppKey=MQOOT-BXPBZ-COZRJ-UGHZQ&UIDType=1';

                // Initialize cURL
                $ch = curl_init($url);

                // Set cURL options
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([]));
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

                // Execute cURL request
                $response = curl_exec($ch);
                curl_close($ch);

                // Decode the response
                $response_data = json_decode($response, true);

                if (isset($response_data['RESPONSE']['UID'])) {
                    $uid = $response_data['RESPONSE']['UID'];

                    // Create a MySQLi connection
                    $mysqli = new mysqli("localhost", "root", "4/rb5sO2s3TpL4gu", "adi_dravidar");

                    // Check the connection
                    if ($mysqli->connect_error) {
                        throw new Exception("Connection failed: " . $mysqli->connect_error);
                    }

                    // Prepare the SQL statement
                    $stmt = $mysqli->prepare("UPDATE aadhar_new SET temp_aadhar = ? WHERE uuid = ?");
                    if (!$stmt) {
                        throw new Exception("Prepare failed: " . $mysqli->error);
                    }

                    // Bind the parameters
                    $stmt->bind_param("ss", $uid, $uuid);

                    // Execute the statement
                    if (!$stmt->execute()) {
                        throw new Exception("Execute failed: " . $stmt->error);
                    }

                    // Check if any rows were updated
                    if ($stmt->affected_rows > 0) {
                        $stored_records++;
                    } else {
                        $failed_records++;
                    }

                    // Close the statement and connection
                    $stmt->close();
                    $mysqli->close();
                } else {
                    $failed_records++;
                }
            }

            $status = true;
            $msg = "Records processed successfully. Stored: $stored_records, Failed: $failed_records.";
            $error = "";

        } catch (Exception $e) {
            $status = false;
            $msg = "An error occurred while processing records.";
            $error = $e->getMessage();
        }

        // Prepare the final response
        $json_array = [
            "status" => $status,
            "data" => "",
            "error" => $error,
            "msg" => $msg,
        ];

        echo json_encode($json_array);
        break;



    case 'check_aadhar':
        $uuid = $_POST["uuid"];

        // API endpoint
        $url = 'https://tnpreauth.tn.gov.in/auakua25dvuat/api/datavault/GetUID?UUID=' . $uuid . '&AppKey=MQOOT-BXPBZ-COZRJ-UGHZQ&UIDType=1';

        // Data to send in the request
        $data = array();

        // Convert data array to JSON
        $data_json = json_encode($data);

        // Set headers
        $headers = array(
            'Content-Type: application/json',
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

        // Check for errors
        if (curl_errno($ch)) {
            $error = 'Curl error: ' . curl_error($ch);
            $response = false;
        }

        // Close cURL session
        curl_close($ch);

        // Prepare response
        if ($response !== false) {
            $response_data = json_decode($response, true);

            $uid = $response_data['RESPONSE']['UID'];

            $json_array = [
                "data" => [
                    "UID" => $uid
                ],
            ];

        } else {
            $json_array = [
                "status" => false,
                "data" => null,
                "error" => isset($error) ? $error : "Data is not available.",
                "msg" => "Data retrieval failed."
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($json_array);

        break;

    default:

        break;
}
