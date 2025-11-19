<?php

// Get folder Name From Currnent Url     
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table_a = "aadhar";
$table = "std_app_s";
$table_s2 = "std_app_s2";
$table_p3 = "std_app_p3";
$table_p4 = "std_app_p4";
$table_p5 = "std_app_p5";
$table_p6 = "std_app_p6";
$table_p7 = "std_app_p7";
$table_p8 = "std_app_p8";
$table_p9 = "std_app_p9";
$table_p10 = "std_app_p10";
$table_p11 = "std_app_p11";
//$table_p12 = "std_app_p12";
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

    case 'get_unique_id':
        $uuid = filter_input(INPUT_POST, 'uuid', FILTER_SANITIZE_STRING);
        $is_delete = 0;

        $mysqli = new mysqli("localhost", "root", "4/rb5sO2s3TpL4gu", "adi_dravidar");

        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Prepare the SQL statement
        $sql = "SELECT unique_id FROM $table WHERE is_delete = ? AND uuid = ? and  academic_year = '6847e16e7060491061' ";
        $stmt = $mysqli->prepare($sql);

        if (!$stmt) {
            error_log("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
            $json_array = [
                "status" => false,
                "msg" => "error",
                "unique_id" => null
            ];
            echo json_encode($json_array);
            break;
        }

        // Bind the parameters
        $stmt->bind_param("is", $is_delete, $uuid);

        // Execute the statement
        if (!$stmt->execute()) {
            error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            $json_array = [
                "status" => false,
                "msg" => "error",
                "unique_id" => null
            ];
            echo json_encode($json_array);
            break;
        }

        // Bind the result variables
        $stmt->bind_result($unique_id);

        // Fetch the result
        $status = false;
        $msg = "no_uid";
        if ($stmt->fetch()) {
            $status = true;
            $msg = "uid";
        } else {
            $unique_id = null;
        }

        // Close the statement
        $stmt->close();

        // Close the connection
        $mysqli->close();

        // Prepare JSON response
        $json_array = [
            "status" => $status,
            "msg" => $msg,
            "unique_id" => $unique_id
        ];

        echo json_encode($json_array);
        break;

    case 'edit_aad_otp_verify':
        $encodeEditAadhar = $_POST["edit_aadhar_no"];
        $edit_aadhar_no = base256_decode($encodeEditAadhar);

        $otp = base256_decode($_POST["otp"]);
        $edit_txn = $_POST["edit_txn"];
        $rrn_length = 10; // Set the length of the RRN (same as the sample RRN)
        $edit_rrn = str_pad(rand(0, pow(10, $rrn_length) - 1), $rrn_length, '0', STR_PAD_LEFT);


        // API endpoint
        $url = 'https://tnauth.tn.gov.in/clientgwapi/api/Aadhaar/KYCWithOTP';

        // API endpoint
        // $url = 'https://tnpreauth.tn.gov.in/clientgwapi/api/Aadhaar/KYCWithOTP';

        // Data to send in the request

        $data = array(
            "AUAKUAParameters" => array(
                'LAT' => "17.494568",
                'LONG' => "78.392056",
                'DEVMACID' => "11:22:33:44:55",
                'DEVID' => "public",
                'CONSENT' => "Y",
                'SHRC' => "Y",
                'VER' => "2.5",
                'SERTYPE' => "05",
                'ENV' => "2",
                'AADHAARID' => $edit_aadhar_no,
                'SLK' => "JSTUX-KODGB-TXXEF-VELPU",
                'RRN' => $edit_rrn,
                'REF' => "FROMSAMPLE",
                'TXN' => $edit_txn,
                'OTP' => $otp,
                'LANG' => "N",
                'PFR' => "N"

            ),
            'PIDXML' => "",
            'ENVIRONMENT' => "0"
        );


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
            echo 'Curl error: ' . curl_error($ch);
        }

        // Close cURL session
        curl_close($ch);

        // Output the response
        // echo $response;


        // Prepare response
        $json_array = [
            "status" => ($response !== false),
            "data" => json_decode($response),
            "error" => isset($error) ? $error : "Data is not available!..",
            "msg" => $msg,
            // "sql" => $sql
        ];

        echo json_encode($json_array);

        break;

    case 'edit_gen_otp':
        $encodeEditAadhar = $_POST["edit_aadhar_no"];

        $edit_aadhar_no = base256_decode($encodeEditAadhar);

        // API endpoint
        // $url = 'https://tnpreauth.tn.gov.in/clientgwapi/api/Aadhaar/GenerateOTP';

        // API endpoint
        $url = 'https://tnauth.tn.gov.in/clientgwapi/api/Aadhaar/GenerateOTP';

        // Data to send in the request

        $data = array(
            "AUAKUAParameters" => array(
                'LAT' => "17.494568",
                'LONG' => "78.392056",
                'DEVMACID' => "11:22:33:44:55",
                'DEVID' => "public",
                'CONSENT' => "Y",
                'SHRC' => "Y",
                'VER' => "2.5",
                'SERTYPE' => "10",
                'ENV' => "2",
                'CH' => "0",
                'AADHAARID' => $edit_aadhar_no,
                'SLK' => "JSTUX-KODGB-TXXEF-VELPU",
                'RRN' => "1668576481",
                'REF' => "FROMSAMPLE",
            ),
            'PIDXML' => "",
            'ENVIRONMENT' => "0"
        );


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
            echo 'Curl error: ' . curl_error($ch);
        }

        // Close cURL session
        curl_close($ch);

        // Output the response
        // echo $response;


        // Prepare response
        $json_array = [
            "status" => ($response !== false),
            "data" => json_decode($response),
            "error" => isset($error) ? $error : "Data is not available!..",
            "msg" => $msg,
            // "sql" => $sql
        ];

        echo json_encode($json_array);

        break;

    case 'edit_already_uuid':
        // Decoding the uuid
        $uuid = base256_decode($_POST["uuid"]);
        $is_delete = 0;

        // Include the connection
        $mysqli = new mysqli("localhost", "root", "4/rb5sO2s3TpL4gu", "adi_dravidar");

        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }
        // Prepare the SQL statement
        $sql = "SELECT COUNT(unique_id) AS count,batch_no FROM $table WHERE is_delete = ? AND uuid = ? and academic_year = '6847e16e7060491061' order by id desc limit 1";
        $stmt = $mysqli->prepare($sql);

        if (!$stmt) {
            error_log("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
            $json_array = [
                "status" => false,
                "msg" => "error"
            ];
            echo json_encode($json_array);
            break;
        }

        // Bind the parameters
        $stmt->bind_param("is", $is_delete, $uuid);

        // Execute the statement
        if (!$stmt->execute()) {
            error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            $json_array = [
                "status" => false,
                "msg" => "error"
            ];
            echo json_encode($json_array);
            break;
        }

        // Bind the result variable
        $stmt->bind_result($count, $batch_no);

        // Fetch the result
        $status = false;
        $msg = "error";
        if ($stmt->fetch()) {
            $status = true;
            $msg = ($count > 0) ? "already" : "not_found";
        }

        // Close the statement
        $stmt->close();

        // Close the connection
        $mysqli->close();

        // Prepare JSON response
        $json_array = [
            "status" => $status,
            "msg" => $msg,
            "batch_no" => $batch_no
        ];

        echo json_encode($json_array);
        break;


    case 'edit_check_aadhar':
        $encodeEditAadhar = $_POST["edit_aadhar_no"];

        $edit_aadhar_no = base256_decode($encodeEditAadhar);

        // API endpoint
        // $url = 'https://tnpreauth.tn.gov.in/auakua25dvuat/api/datavault/InsertUID?UIDToken&AadhaarNo=' . $edit_aadhar_no . '&APPKey=MQOOT-BXPBZ-COZRJ-UGHZQ';

        // API endpoint
        $url = 'https://tnauth.tn.gov.in/auakua25dv/api/datavault/GetUUID?UID=' . $edit_aadhar_no . '&AppKey=IDKJS-PPSSJ-VKAZE-DMCCG&UIDType=3';

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
            echo 'Curl error: ' . curl_error($ch);
        }

        // Close cURL session
        curl_close($ch);

        // Output the response
        // echo $response;


        // Prepare response
        $json_array = [
            "status" => ($response !== false),
            "data" => json_decode($response),
            "error" => isset($error) ? $error : "Data is not available!..",
            "msg" => $msg,
            // "sql" => $sql
        ];

        header('Content-Type: application/json');
        echo json_encode($json_array);

        break;


    case 'gen_otp':
        $encodedAadhar = $_POST["aadhar_no"];
        $aadhar_no = base256_decode($encodedAadhar);


        // API endpoint
        // $url = 'https://tnpreauth.tn.gov.in/clientgwapi/api/Aadhaar/GenerateOTP';

        // API endpoint
        $url = 'https://tnauth.tn.gov.in/clientgwapi/api/Aadhaar/GenerateOTP';


        // Data to send in the request

        $data = array(
            "AUAKUAParameters" => array(
                'LAT' => "17.494568",
                'LONG' => "78.392056",
                'DEVMACID' => "11:22:33:44:55",
                'DEVID' => "public",
                'CONSENT' => "Y",
                'SHRC' => "Y",
                'VER' => "2.5",
                'SERTYPE' => "10",
                'ENV' => "2",
                'CH' => "0",
                'AADHAARID' => $aadhar_no,
                'SLK' => "JSTUX-KODGB-TXXEF-VELPU",
                'RRN' => "1668576481",
                'REF' => "FROMSAMPLE",
            ),
            'PIDXML' => "",
            'ENVIRONMENT' => "0"
        );


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
            echo 'Curl error: ' . curl_error($ch);
        }

        // Close cURL session
        curl_close($ch);

        // Output the response
        // echo $response;


        // Prepare response
        $json_array = [
            "status" => ($response !== false),
            "data" => json_decode($response),
            "error" => isset($error) ? $error : "Data is not available!..",
            "msg" => $msg,
            // "sql" => $sql
        ];

        echo json_encode($json_array);

        break;

    case 'already_uuid':
        $uuid = base256_decode($_POST["uuid"]);
        $academic_year = base256_decode($_POST["academic_year"]);
        $alr_stay = base256_decode($_POST["alr_stay"]);
        $is_delete = 0;

        $mysqli = new mysqli("localhost", "root", "4/rb5sO2s3TpL4gu", "adi_dravidar");

        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Check if mysqli connection is established
        if ($mysqli->connect_error) {
            $msg = "error";
        } else {
            // Prepare SQL statement
            if ($alr_stay == 'No') {
                $sql = "SELECT COUNT(unique_id) AS count FROM $table WHERE is_delete = ? AND uuid = ? and status != 2";
            } else {
                $sql = "SELECT COUNT(unique_id) AS count FROM $table WHERE is_delete = ? AND uuid = ? and academic_year = ? and status != 2";
            }
            $stmt = $mysqli->prepare($sql);

            if (!$stmt) {
                $msg = "error";
            } else {
                // Bind parameters
                if ($alr_stay == 'No') {
                    $stmt->bind_param("is", $is_delete, $uuid);
                } else {
                    $stmt->bind_param("iss", $is_delete, $uuid, $academic_year);
                }

                // Execute the statement
                if ($stmt->execute()) {
                    // Bind result variables
                    $stmt->bind_result($count);

                    // Fetch the result
                    $stmt->fetch();
                    $stmt->close();

                    // Determine message
                    $msg = ($count > 0) ? "already" : "not_found";

                    if ($alr_stay == 'No') {
                        if ($count > 0) {
                            $sql = "SELECT COUNT(s1_unique_id) AS count FROM renewal WHERE uuid = ? and exit_status = 1";
                            $stmt = $mysqli->prepare($sql);
                            $stmt->bind_param("s", $uuid);
                            $stmt->execute();
                            $stmt->bind_result($count);
                            $stmt->fetch();
                            $stmt->close();
                            $msg = ($count > 0) ? 'not_found' : 'already';
                        }
                    }
                } else {
                    $msg = "error";
                }
            }
        }

        // Prepare JSON response
        $json_array = [
            "status" => ($msg !== "error"),
            "msg" => $msg
        ];

        echo json_encode($json_array);
        break;


    case 'search_uuid':

        $uuid = base256_decode($_POST["uuid"]);

        $academic_year = base256_decode($_POST["academic_year"]);
        $is_delete = 0;

        $mysqli = new mysqli("localhost", "root", "4/rb5sO2s3TpL4gu", "adi_dravidar");

        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Check if mysqli connection is established
        if ($mysqli->connect_error) {
            $msg = "error";
        } else {
            // Prepare SQL statement
            $sql = "SELECT COUNT(unique_id) AS count FROM std_app_s WHERE is_delete = ? AND uuid = ?";
            $stmt = $mysqli->prepare($sql);

            if (!$stmt) {
                $msg = "error";
            } else {
                // Bind parameters
                $stmt->bind_param("is", $is_delete, $uuid);

                // Execute the statement
                if ($stmt->execute()) {
                    // Bind result variables
                    $stmt->bind_result($count);

                    // Fetch the result
                    $stmt->fetch();
                    $stmt->close();

                    // Determine message
                    $msg = ($count > 0) ? "already" : "not_found";

                    if ($msg == 'already') {
                        $sql = "SELECT COUNT(unique_id) AS count FROM std_app_s WHERE is_delete = ? AND uuid = ? and renewal_status != 0";
                        $stmt = $mysqli->prepare($sql);
                        $stmt->bind_param("is", $is_delete, $uuid);
                        $stmt->execute();
                        $stmt->bind_result($count);
                        $stmt->fetch();
                        $stmt->close();
                        $msg = ($count > 0) ? "renewed" : 'already';
                        if ($msg == 'renewed') {
                            $sql_exit = "SELECT COUNT(s1_unique_id) AS count FROM renewal WHERE  uuid = ? and exit_status = 1";
                            $stmt_exit = $mysqli->prepare($sql_exit);
                            $stmt_exit->bind_param("s", $uuid);
                            $stmt_exit->execute();
                            $stmt_exit->bind_result($count);
                            $stmt_exit->fetch();
                            $stmt_exit->close();
                            $msg = ($count > 0) ? "already" : 'renewed';
                        }
                    }
                } else {
                    $msg = "error";
                }
            }
        }

        // Prepare JSON response
        $json_array = [
            "status" => ($msg !== "error"),
            "msg" => $msg
        ];

        echo json_encode($json_array);
        break;

    case 'get_cls_degree':
        $uuid = base256_decode($_POST["uuid"]);
        $is_delete = 0;

        $mysqli = new mysqli("localhost", "root", "4/rb5sO2s3TpL4gu", "adi_dravidar");


        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Check if mysqli connection is established
        if ($mysqli->connect_error) {
            $msg = "error";
        } else {
            // Prepare SQL statement
            $sql = "SELECT student_type,emis_class,s1_unique_id FROM std_app WHERE is_delete = ? AND uuid = ? order by id desc limit 1";
            $stmt = $mysqli->prepare($sql);

            if (!$stmt) {
                $msg = "error";
            } else {
                // Bind parameters
                $stmt->bind_param("is", $is_delete, $uuid);

                // Execute the statement
                if ($stmt->execute()) {
                    // Bind result variables
                    $stmt->bind_result($student_type, $emis_class, $s1_unique_id);

                    // Fetch the result
                    $stmt->fetch();

                    // Determine message
                    $msg = ($count > 0) ? "already" : "not_found";
                } else {
                    $msg = "error";
                }
            }
        }

        // Prepare JSON response
        $json_array = [
            "status" => ($msg !== "error"),
            "msg" => $msg,
            "student_type" => $student_type,
            "emis_class" => $emis_class,
            "s1_unique_id" => $s1_unique_id,
        ];

        echo json_encode($json_array);
        break;



    case 'check_aadhar':
        $encodedAadhar = $_POST["aadhar_no"];
        $aadhar_no = base256_decode($encodedAadhar);

        // API endpoint
        // $url = 'https://tnpreauth.tn.gov.in/auakua25dvuat/api/datavault/InsertUID?UIDToken&AadhaarNo=' . $aadhar_no . '&APPKey=MQOOT-BXPBZ-COZRJ-UGHZQ';
        $url = 'https://tnauth.tn.gov.in/auakua25dv/api/datavault/GetUUID?UID=' . $aadhar_no . '&AppKey=IDKJS-PPSSJ-VKAZE-DMCCG&UIDType=3';


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
            echo 'Curl error: ' . curl_error($ch);
        }

        // Close cURL session
        curl_close($ch);

        // Output the response
        // echo $response;


        // Prepare response
        $json_array = [
            "status" => ($response !== false),
            "data" => json_decode($response),
            "error" => isset($error) ? $error : "Data is not available!..",
            "msg" => $msg,
            // "sql" => $sql
        ];

        header('Content-Type: application/json');
        echo json_encode($json_array);

        break;


    case 'insert_uuid':
        $encodedAadhar = $_POST["aadhar_no"];
        $aadhar_no = base256_decode($encodedAadhar);

        // API endpoint
        // $url = 'https://tnpreauth.tn.gov.in/auakua25dvuat/api/datavault/InsertUID?UIDToken&AadhaarNo=' . $aadhar_no . '&APPKey=MQOOT-BXPBZ-COZRJ-UGHZQ';
        $url = 'https://tnauth.tn.gov.in/auakua25dv/api/datavault/InsertUID?UIDToken&AadhaarNo=' . $aadhar_no . '&APPKey=IDKJS-PPSSJ-VKAZE-DMCCG';

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
            echo 'Curl error: ' . curl_error($ch);
        }

        // Close cURL session
        curl_close($ch);

        // Output the response
        // echo $response;


        // Prepare response
        $json_array = [
            "status" => ($response !== false),
            "data" => json_decode($response),
            "error" => isset($error) ? $error : "Data is not available!..",
            "msg" => $msg,
        ];

        header('Content-Type: application/json');
        echo json_encode($json_array);

        break;


    case 'download_check_aadhar':
        $aadhar_no = $_POST["aadhar_no"];

        // API endpoint
        //$url = 'https://tnpreauth.tn.gov.in/auakua25dvuat/api/datavault/InsertUID?UIDToken&AadhaarNo=' . $aadhar_no . '&APPKey=MQOOT-BXPBZ-COZRJ-UGHZQ';
        $url = 'https://tnauth.tn.gov.in/auakua25dv/api/datavault/GetUUID?UID=' . $aadhar_no . '&AppKey=IDKJS-PPSSJ-VKAZE-DMCCG&UIDType=3';


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
            echo 'Curl error: ' . curl_error($ch);
        }

        // Close cURL session
        curl_close($ch);

        // Output the response
        // echo $response;


        // Prepare response
        $json_array = [
            "status" => ($response !== false),
            "data" => json_decode($response),
            "error" => isset($error) ? $error : "Data is not available!..",
            "msg" => $msg,
            // "sql" => $sql
        ];

        header('Content-Type: application/json');
        echo json_encode($json_array);

        break;



    case 'aad_otp_verify':
        $encodedAadhar = $_POST["aadhar_no"];
        $aadhar_no = base256_decode($encodedAadhar);
        $otp = base256_decode($_POST["otp"]);

        $txn = $_POST["txn"];
        $rrn_length = 10; // Set the length of the RRN (same as the sample RRN)
        $rrn = str_pad(rand(0, pow(10, $rrn_length) - 1), $rrn_length, '0', STR_PAD_LEFT);

        // API endpoint
        $url = 'https://tnauth.tn.gov.in/clientgwapi/api/Aadhaar/KYCWithOTP';

        // API endpoint
        // $url = 'https://tnpreauth.tn.gov.in/clientgwapi/api/Aadhaar/KYCWithOTP';

        // Data to send in the request

        $data = array(
            "AUAKUAParameters" => array(
                'LAT' => "17.494568",
                'LONG' => "78.392056",
                'DEVMACID' => "11:22:33:44:55",
                'DEVID' => "public",
                'CONSENT' => "Y",
                'SHRC' => "Y",
                'VER' => "2.5",
                'SERTYPE' => "05",
                'ENV' => "2",
                'AADHAARID' => $aadhar_no,
                'SLK' => "JSTUX-KODGB-TXXEF-VELPU",
                'RRN' => $rrn,
                'REF' => "FROMSAMPLE",
                'TXN' => $txn,
                'OTP' => $otp,
                'LANG' => "N",
                'PFR' => "N"

            ),
            'PIDXML' => "",
            'ENVIRONMENT' => "0"
        );


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
            echo 'Curl error: ' . curl_error($ch);
        }

        // Close cURL session
        curl_close($ch);

        // Output the response
        // echo $response;


        // Prepare response
        $json_array = [
            "status" => ($response !== false),
            "data" => json_decode($response),
            "error" => isset($error) ? $error : "Data is not available!..",
            "msg" => $msg,
            // "sql" => $sql
        ];

        echo json_encode($json_array);

        break;


    case 'aadhar_confirmation_add':


        $std_name = $_POST["std_name"];
        $std_dob = $_POST["std_dob"];
        $std_age = $_POST["std_age"];
        $std_gender = $_POST["std_gender"];
        $father_name = $_POST["father_name"];
        $std_address = $_POST["std_address"];
        $std_app_no = $_POST["std_app_no"];
        $s1_unique_id = $_POST["s1_unique_id"];



        // $is_active          = $_POST["is_active"];
        $unique_id = $_POST["unique_id"];

        $update_where = "";

        $columns = [
            "std_name" => $_POST['std_name'],
            "dob" => $_POST['std_dob'],
            "age" => $_POST['std_age'],
            "gender" => $_POST['std_gender'],
            "father_name" => $_POST['father_name'],
            "address" => $_POST['std_address'],
            // "std_app_no"    => $_POST['std_app_no'],
            "s1_unique_id" => $_POST['s1_unique_id'],






            "entry_date" => date('Y-m-d'),
            // "is_active"        => $is_active,
            "unique_id" => unique_id($prefix)
        ];

        // check already Exist Or not
        $table_details = [
            $table_s2,
            [
                "COUNT(unique_id) AS count"
            ]
        ];
        $select_where = '';
        $select_where .= ' is_delete = 0 and s1_unique_id ="' . $s1_unique_id . '"';
        // When Update Check without current id
        if ($unique_id) {
            $select_where .= ' AND unique_id !="' . $unique_id . '" ';
        }

        $action_obj = $pdo->select($table_details, $select_where);

        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;
        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "error";
        }
        if ($data[0]["count"]) {
            // $msg        = "already";
            $update_where = [
                "s1_unique_id" => $s1_unique_id
            ];
            $action_obj = $pdo->update($table_s2, $columns, $update_where);
        } else if ($data[0]["count"] == 0) {

            $action_obj = $pdo->insert($table_s2, $columns);
        }

        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;

            if ($unique_id) {
                $msg = "update";
            } else {
                $msg = "save";
            }
        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "error";
        }


        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql" => $sql
        ];

        echo json_encode($json_array);

        break;

    case 'umis_insert':

        $update_where = "";

        $columns_1 = [
            "umis_no" => $_POST['umis_no'],
            "name" => $_POST['name'],
            "emsid" => $_POST['emsid'],
            "dateOfBirth" => $_POST['dateOfBirth'],
            "nationalityId" => $_POST['nationalityId'],
            "religionId" => $_POST['religionId'],
            "communityId" => $_POST['communityId'],
            "casteId" => $_POST['casteId'],
            "isFirstGraduate" => $_POST['isFirstGraduate'],
            "isDifferentlyAbled" => $_POST['isDifferentlyAbled'],
            "isSpecialCategory" => $_POST['isSpecialCategory'],
            "udid" => $_POST['udid'],
            "disabilityId" => $_POST['disabilityId'],
            "extentOfDisability" => $_POST['extentOfDisability'],
            "bloodGroupId" => $_POST['bloodGroupId'],
            "genderId" => $_POST['genderId'],
            "salutationId" => $_POST['salutationId'],
            "instituteId" => $_POST['instituteId'],
            "umisId" => $_POST['umisId'],
            "nameAsOnCertificate" => $_POST['nameAsOnCertificate'],
            "isFirstGraduateVerifiedbyUniversity" => $_POST['isFirstGraduateVerifiedbyUniversity'],
            "isFirstGraduateVerifiedbyHod" => $_POST['isFirstGraduateVerifiedbyHod'],
            "mobileNumber" => $_POST['mobileNumber'],
            "emailId" => $_POST['emailId'],
            "permAddress" => $_POST['permAddress'],
            "s1_unique_id" => $_POST['s1_unique_id'],
            "entry_date" => date('Y-m-d'),
            "unique_id" => unique_id($prefix)
        ];

        // check already Exist Or not
        $table_details_1 = [
            "umis_1",
            [
                "COUNT(unique_id) AS count"
            ]
        ];
        $select_where_1 = '';
        $select_where_1 .= ' is_delete = 0 and s1_unique_id ="' . $_POST['s1_unique_id'] . '"';


        $action_obj_1 = $pdo->select($table_details_1, $select_where_1);

        $data = $action_obj_1->data;

        if ($data[0]["count"]) {

            $update_where_1 = [
                "s1_unique_id" => $s1_unique_id
            ];
            $action_obj = $pdo->update("umis_1", $columns_1, $update_where_1);
        } else if ($data[0]["count"] == 0) {
            $action_obj = $pdo->insert("umis_1", $columns_1);
        }

        $columns_2 = [
            "umis_no" => $_POST['umis_no'],
            "countryId" => $_POST['countryId'],
            "stateId" => $_POST['stateId'],
            "districtId" => $_POST['districtId'],
            "zoneId" => $_POST['zoneId'],
            "blockId" => $_POST['blockId'],
            "caCountryId" => $_POST['caCountryId'],
            "caStateId" => $_POST['caStateId'],
            "caDistrictId" => $_POST['caDistrictId'],
            "caZoneId" => $_POST['caZoneId'],
            "caCorporationId" => $_POST['caCorporationId'],
            "caAddress" => $_POST['caAddress'],
            "caBlockId" => $_POST['caBlockId'],
            "caVillagePanchayatId" => $_POST['caVillagePanchayatId'],
            "caWardId" => $_POST['caWardId'],
            "caTalukId" => $_POST['caTalukId'],
            "caVillageId" => $_POST['caVillageId'],
            "talukId" => $_POST['talukId'],
            "villageId" => $_POST['villageId'],
            "wardId" => $_POST['wardId'],
            "corporationId" => $_POST['corporationId'],
            "villagePanchayatId" => $_POST['villagePanchayatId'],
            "courseId" => $_POST['courseId'],
            "courseSpecializationId" => $_POST['courseSpecializationId'],
            "dateOfAdmission" => $_POST['dateOfAdmission'],
            "academicYearId" => $_POST['academicYearId'],
            "s1_unique_id" => $_POST['s1_unique_id'],
            "entry_date" => date('Y-m-d'),
            "unique_id" => unique_id($prefix)
        ];

        // check already Exist Or not
        $table_details_2 = [
            "umis_2",
            [
                "COUNT(unique_id) AS count"
            ]
        ];
        $select_where_2 = '';
        $select_where_2 .= ' is_delete = 0 and s1_unique_id ="' . $_POST['s1_unique_id'] . '"';
        // When Update Check without current id
        if ($unique_id) {
            $select_where .= ' AND unique_id !="' . $unique_id . '" ';
        }

        $action_obj_2 = $pdo->select($table_details_2, $select_where_2);
        $data = $action_obj_2->data;

        if ($data[0]["count"]) {

            $update_where_2 = [
                "s1_unique_id" => $s1_unique_id
            ];
            $action_obj_2 = $pdo->update("umis_2", $columns_2, $update_where_2);
        } else if ($data[0]["count"] == 0) {
            $action_obj_2 = $pdo->insert("umis_2", $columns_2);
        }


        $columns_3 = [
            "umis_no" => $_POST['umis_no'],
            "courseType" => $_POST['courseType'],
            "streamInfoId" => $_POST['streamInfoId'],
            "mediumOfInstructionType" => $_POST['mediumOfInstructionType'],
            "academicStatusType" => $_POST['academicStatusType'],
            "yearOfStudy" => $_POST['yearOfStudy'],
            "isLateralEntry" => $_POST['isLateralEntry'],
            "isHosteler" => $_POST['isHosteler'],
            "hostelAdmissionDate" => $_POST['hostelAdmissionDate'],
            "leavingFromHostelDate" => $_POST['leavingFromHostelDate'],
            "studentId" => $_POST['studentId'],
            "parentMobileNo" => $_POST['parentMobileNo'],
            "fatherOccupationId" => $_POST['fatherOccupationId'],
            "motherOccupationId" => $_POST['motherOccupationId'],
            "guardianOccupationId" => $_POST['guardianOccupationId'],
            "aisheId" => $_POST['aisheId'],
            "instituteName" => $_POST['instituteName'],
            "instituteTypeId" => $_POST['instituteTypeId'],
            "instituteOwnershipId" => $_POST['instituteOwnershipId'],
            "instituteCategoryId" => $_POST['instituteCategoryId'],
            "instituteStatusType" => $_POST['instituteStatusType'],
            "universityName" => $_POST['universityName'],
            "universityTypeId" => $_POST['universityTypeId'],
            "hodName" => $_POST['hodName'],
            "departmentName" => $_POST['departmentName'],
            "s1_unique_id" => $_POST['s1_unique_id'],
            "entry_date" => date('Y-m-d'),
            "unique_id" => unique_id($prefix)
        ];

        // check already Exist Or not
        $table_details_3 = [
            "umis_3",
            [
                "COUNT(unique_id) AS count"
            ]
        ];
        $select_where_3 = '';
        $select_where_3 .= ' is_delete = 0 and s1_unique_id ="' . $_POST['s1_unique_id'] . '"';
        // When Update Check without current id
        if ($unique_id) {
            $select_where .= ' AND unique_id !="' . $unique_id . '" ';
        }

        $action_obj_3 = $pdo->select($table_details_3, $select_where_3);
        $data = $action_obj_3->data;
        if ($data[0]["count"]) {

            $update_where_3 = [
                "s1_unique_id" => $s1_unique_id
            ];
            $action_obj_3 = $pdo->update("umis_3", $columns_3, $update_where_3);
        } else if ($data[0]["count"] == 0) {
            $action_obj_3 = $pdo->insert("umis_3", $columns_3);
        }



        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;

            if ($unique_id) {
                $msg = "update";
            } else {
                $msg = "save";
            }
        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "error";
        }


        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql" => $sql
        ];

        echo json_encode($json_array);

        break;



    case 'insert_emis':
        $emis_id = $_POST["emis_id"];
        $unique_id = unique_id($prefix);

        // Assuming your unique_id() function is defined correctly

        // Initialize variables
        $status = false;
        $data = [];
        $error = '';
        $msg = '';
        $sql = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'insert_emis') {
            $emis_id = isset($_POST["emis_id"]) ? $_POST["emis_id"] : '';

            // Set up API request
            $api_url = 'https://tnega.tnschools.gov.in/tnega/api/GetSchlDetails';
            $api_key = 'Authorization';
            $api_key_value = '4acdca2cc493c1ec28e1f68e0d37c49a';

            $request_data = array(
                'key' => $api_key,
                'Value' => $api_key_value,
                'EmisId' => $emis_id // Assuming $emis_id contains the EMIS Id received from the form
            );

            // Make API call
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $api_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($request_data));

            // Disable SSL certificate verification
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            $response = curl_exec($ch);

            // Check for cURL errors
            if ($response === false) {
                $error = 'cURL error: ' . curl_error($ch);
                $msg = 'error';
            } else {
                // Handle API response
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if ($http_code >= 200 && $http_code < 300) {
                    $api_data = json_decode($response, true);

                    // Check if required data is present in the API response
                    if (isset($api_data['district_name']) && isset($api_data['block_name'])) {
                        $district_name = $api_data['district_name'];
                        $block_name = $api_data['block_name'];

                        // Insert data into database
                        $table = 'emis';
                        $columns = array(
                            'emis_id' => $emis_id,
                            'district_name' => $district_name,
                            'block_name' => $block_name,
                            "unique_id" => unique_id($prefix)
                        );

                        // Assuming $pdo is your PDO connection
                        $action_obj = $pdo->insert($table, $columns);

                        // Handle database insertion result
                        if ($action_obj->status) {
                            $status = true;
                            $data = $action_obj->data;
                            $sql = $action_obj->sql;
                        } else {
                            $error = $action_obj->error;
                            $msg = 'error';
                        }
                    } else {
                        $error = 'Required data not found in API response';
                        $msg = 'error';
                    }
                } else {
                    $error = 'HTTP error: ' . $http_code;
                    $msg = 'error';
                }
            }

            curl_close($ch);
        } else {
            $error = 'Invalid request';
            $msg = 'error';
        }

        // Prepare response
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql" => $sql
        ];

        echo json_encode($json_array);

        break;








    case 'createupdate':

        $academic_year = $_POST["academic_year"];
        $std_name = $_POST["std_name"];

        $std_mobile_no = $_POST["std_mobile_no"];

        $std_app_no = reg_no($academic_year);



        // $is_active          = $_POST["is_active"];
        $unique_id = $_POST["unique_id"];

        $update_where = "";

        $columns = [
            "std_app_no" => $std_app_no,
            "academic_year" => $academic_year,
            "std_name" => $std_name,

            "std_mobile_no" => $std_mobile_no,
            "entry_date" => date('Y-m-d'),
            // "is_active"        => $is_active,
            "unique_id" => unique_id($prefix)
        ];

        // check already Exist Or not
        $table_details = [
            $table,
            [
                "COUNT(unique_id) AS count"
            ]
        ];
        $select_where = '';
        $select_where .= ' is_delete = 0 and std_mobile_no = "' . $std_mobile_no . '"';
        // When Update Check without current id
        if ($unique_id) {
            $select_where .= ' AND unique_id !="' . $unique_id . '" ';
        }

        $action_obj = $pdo->select($table_details, $select_where);
        // print_r($action_obj);die();

        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;
        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "error";
        }
        if ($data[0]["count"]) {
            $msg = "already";
        } else if ($data[0]["count"] == 0) {
            // Update Begins
            if ($unique_id) {

                unset($columns['unique_id']);

                $update_where = [
                    "unique_id" => $unique_id
                ];

                $action_obj = $pdo->update($table, $columns, $update_where);

                // Update Ends
            } else {

                // Insert Begins            
                $action_obj = $pdo->insert($table, $columns);
                // print_r($action_obj);
                // Insert Ends

            }

            if ($action_obj->status) {
                $status = $action_obj->status;
                $data = $action_obj->data;
                $error = "";
                $sql = $action_obj->sql;

                if ($unique_id) {
                    $msg = "otp";
                } else {
                    $msg = "otp";
                }
            } else {
                $status = $action_obj->status;
                $data = $action_obj->data;
                $error = $action_obj->error;
                $sql = $action_obj->sql;
                $msg = "error";
            }
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            "std_app_no" => $std_app_no,
            // "sql" => $sql
        ];

        echo json_encode($json_array);

        break;

    case 'datatable':
        // DataTable Variables
        $search = $_POST['search']['value'];
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;

        $data = [];


        if ($length == '-1') {
            $limit = "";
        }

        // Query Variables
        $json_array = "";
        $columns = [
            "@a:=@a+1 s_no",
            '(SELECT district_name FROM district_name AS dis WHERE dis.unique_id = ' . $table . '.district_name) AS district_name',
            '(SELECT taluk_name FROM taluk_creation AS taluk WHERE taluk.unique_id = ' . $table . '.zone_name) AS taluk_name',
            "hostel_name",
            "is_active",
            "unique_id"
        ];
        $table_details = [
            $table . " , (SELECT @a:= " . $start . ") AS a ",
            $columns
        ];
        $where = "is_delete = 0";
        $order_by = "";

        if ($_POST['search']['value']) {
            $where .= " AND hostel_name LIKE '" . mysql_like($_POST['search']['value']) . "' ";
        }

        // Datatable Searching
        $search = datatable_searching($search, $columns);

        if ($search) {
            if ($where) {
                $where .= " AND ";
            }

            $where .= $search;
        }

        $sql_function = "SQL_CALC_FOUND_ROWS";

        $result = $pdo->select($table_details, $where, $limit, $start, $order_by, $sql_function);
        $total_records = total_records();

        if ($result->status) {

            $res_array = $result->data;

            foreach ($res_array as $key => $value) {
                $value['hostel_name'] = disname($value['hostel_name']);
                $value['is_active'] = is_active_show($value['is_active']);

                $btn_update = btn_update($folder_name, $value['unique_id']);
                $btn_delete = btn_delete($folder_name, $value['unique_id']);

                if ($value['unique_id'] == "5f97fc3257f2525529") {
                    $btn_update = "";
                    $btn_delete = "";
                }

                $value['unique_id'] = $btn_update . $btn_delete;
                $data[] = array_values($value);
            }

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data,
                "testing" => $result->sql
            ];
        } else {
            print_r($result);
        }

        echo json_encode($json_array);
        break;


    case 'edit':
        // DataTable Variables
        $edit_mobile_no = $_POST['edit_mobile_no'];
        // $edit_mobile_no = $_POST['edit_mobile_no'];
        // $app_no = application_no($_POST['application_no'])[0]['unique_id'];
        // Query Variables
        $json_array = "";
        $table_details = [
            $table,
            [
                "COUNT(unique_id) AS count",
                "unique_id",
                "std_app_no",

            ]

        ];

        $select_where = '';
        $select_where .= ' is_delete = 0 and std_mobile_no = "' . $edit_mobile_no . '"';
        // When Update Check without current id


        $action_obj = $pdo->select($table_details, $select_where);
        // print_r($action_obj);
        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;
        }
        if ($data[0]["count"]) {
            // $msg        = "Mobile Number Not Exist";
            $std_app_no = $data[0]["std_app_no"];
            $msg = "otp";
        } else if ($data[0]["count"] == 0) {
            // Update Begins

            // $std_app_no = "Mobile Number Not exist";
            $msg = "not_exist";
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            "std_app_no" => $std_app_no,
            // "sql" => $sql
        ];

        echo json_encode($json_array);

        break;




    case 'delete':

        $unique_id = $_POST['unique_id'];

        $columns = [
            "is_delete" => 1
        ];

        $update_where = [
            "unique_id" => $unique_id
        ];

        $action_obj = $pdo->update($table, $columns, $update_where);

        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;
            $msg = "success_delete";
        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "error";
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql" => $sql
        ];

        echo json_encode($json_array);
        break;


    case 'get_zone_name':

        $district_name = $_POST['district_name'];

        $district_name_options = zone_name("", $district_name);

        $district_name_options = select_option($district_name_options, "Select Zone");

        echo $district_name_options;

        break;

    case 'get_university':

        $stream_type = $_POST['stream_type'];

        $stream_type_options = university_name("", $stream_type);

        $district_name_options = select_option($stream_type_options, "Select University");

        echo $district_name_options;

        break;

    case 'get_college':

        $stream_type = $_POST['stream_type'];
        $std_university = $_POST['std_university'];

        $stream_type_options = college_name("", $stream_type, $std_university);

        $district_name_options = select_option($stream_type_options, "Select college");

        echo $district_name_options;

        break;

    case 'get_course':

        $stream_type = $_POST['stream_type'];
        $std_university = $_POST['std_university'];
        $std_college_name = $_POST['std_college_name'];

        $stream_type_options = course_name("", $stream_type, $std_university, $std_college_name);

        $district_name_options = select_option($stream_type_options, "Select course");

        echo $district_name_options;

        break;

    case 'get_group':

        $std_class = $_POST['std_class'];


        $stream_type_options = group_name("", $std_class);

        $district_name_options = select_option($stream_type_options, "Select course");

        echo $district_name_options;

        break;

    case 'get_taluk_name':

        $hostel_district = $_POST['hostel_district'];

        $hostel_district_options = taluk_name("", $hostel_district);

        $hostel_taluk_options = select_option($hostel_district_options, "Select Taluk");

        echo $hostel_taluk_options;

        break;

    case 'get_school':

        $last_scl_district = $_POST['last_scl_district'];

        $last_scl_district_options = school_name("", $last_scl_district);

        $school_name_options = select_option($last_scl_district_options, "Select School");

        echo $school_name_options;

        break;

    case 'get_gender_name':

        $hostel_district = $_POST['hostel_district'];
        $hostel_taluk = $_POST['hostel_taluk'];

        $hostel_district_options = hostel_gender_type("", $hostel_district, $hostel_taluk);

        $hostel_taluk_options = select_option($hostel_district_options, "Select Gender Type");

        echo $hostel_taluk_options;

        break;

    case 'get_school_name':

        $hostel_district = $_POST['hostel_district'];
        $hostel_taluk = $_POST['hostel_taluk'];

        $hostel_district_options = school_name("", $hostel_district, $hostel_taluk);

        $hostel_taluk_options = select_option($hostel_district_options, "Select School");

        echo $hostel_taluk_options;

        break;

    case 'get_hostel_type':

        $hostel_district = $_POST['hostel_district'];
        $hostel_taluk = $_POST['hostel_taluk'];
        $hostel_gender_type = $_POST['hostel_gender_type'];

        $hostel_district_options = hostel_type("", $hostel_district, $hostel_taluk, $hostel_gender_type);

        $hostel_taluk_options = select_option($hostel_district_options, "Select Hostel Type");

        echo $hostel_taluk_options;

        break;

    case 'get_hostel_name':

        $hostel_district = $_POST['hostel_district'];
        $hostel_taluk = $_POST['hostel_taluk'];
        $hostel_gender_type = $_POST['hostel_gender_type'];
        $hostel_type = $_POST['hostel_type'];

        $hostel_district_options = hostel_name("", $hostel_district, $hostel_taluk, $hostel_gender_type, $hostel_type);

        $hostel_taluk_options = select_option($hostel_district_options, "Select Hostel");

        echo $hostel_taluk_options;

        break;

    case 'hostel_sub_add_update':

        $priority = $_POST["priority"];
        $hostel_district = $_POST["hostel_district"];
        $hostel_taluk = $_POST["hostel_taluk"];

        $hostel_gender_type = $_POST["hostel_gender_type"];
        $hostel_type = $_POST["hostel_type"];
        $hostel_name = $_POST["hostel_name"];
        $p1_unique_id = $_POST["p1_unique_id"];




        // $is_active          = $_POST["is_active"];
        $unique_id = $_POST["unique_id"];

        $update_where = "";

        $columns = [
            "priority" => $priority,
            "hostel_district" => $hostel_district,
            "hostel_taluk" => $hostel_taluk,
            "hostel_gender_type" => $hostel_gender_type,

            "hostel_type" => $hostel_type,
            "hostel_name" => $hostel_name,
            "p1_unique_id" => $p1_unique_id,
            "entry_date" => date('Y-m-d'),
            // "is_active"        => $is_active,
            "unique_id" => unique_id($prefix)
        ];

        if ($priority == 1) {
            $columns["hostel_district"] = $hostel_district;
            $columns["hostel_taluk"] = $hostel_taluk;
            $columns["hostel_name"] = $hostel_name;

            // Insert into another table if priority is 1
            $second_table_columns = [
                "hostel_name" => $hostel_name,
                "hostel_district" => $hostel_district,
                "hostel_taluk" => $hostel_taluk,
                // Add other necessary columns
            ];
            $second_select_where .= 'unique_id ="' . $p1_unique_id . '"';

            $pdo->update($table, $second_table_columns, $second_select_where); // Insert into the second table
        }


        // check already Exist Or not
        $table_details = [
            $table_p2,
            [
                // "COUNT(unique_id) AS count"
            ]
        ];
        $select_where = '';
        $select_where .= ' is_delete = 0';
        // When Update Check without current id
        // if ($p1_unique_id) {
        //     $select_where   .= ' AND p1_unique_id ="'.$p1_unique_id.'" ';
        // }

        $action_obj = $pdo->select($table_details, $select_where);
        //print_r($action_obj);

        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;
        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "error";
        }
        if ($data[0]["count"]) {
            $msg = "already";
        } else if ($data[0]["count"] == 0) {
            // Update Begins
            if ($unique_id) {

                unset($columns['unique_id']);

                $update_where = [
                    "unique_id" => $unique_id
                ];

                $action_obj = $pdo->update($table_p2, $columns, $update_where);

                // Update Ends
            } else {

                // Insert Begins            
                $action_obj = $pdo->insert($table_p2, $columns);
                //print_r($action_obj);
                // Insert Ends

            }

            if ($action_obj->status) {
                $status = $action_obj->status;
                $data = $action_obj->data;
                $error = "";
                $sql = $action_obj->sql;

                if ($unique_id) {
                    $msg = "update";
                } else {
                    $msg = "save";
                }
            } else {
                $status = $action_obj->status;
                $data = $action_obj->data;
                $error = $action_obj->error;
                $sql = $action_obj->sql;
                $msg = "error";
            }
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql" => $sql
        ];

        echo json_encode($json_array);

        break;


    case 'hostel_sub_datatable':
        // DataTable Variables
        $search = $_POST['search']['value'];
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;

        $data = [];
        $folder_name = "hostel_sub";

        if ($length == '-1') {
            $limit = "";
        }
        $p1_unique_id = $_POST['p1_unique_id'];

        // Query Variables
        $json_array = "";
        $columns = [
            "@a:=@a+1 s_no",
            '(SELECT district_name FROM district_name AS dis WHERE dis.unique_id = ' . $table_p2 . '.hostel_district) AS district_name',
            '(SELECT taluk_name FROM taluk_creation AS taluk WHERE taluk.unique_id = ' . $table_p2 . '.hostel_taluk) AS taluk_name',
            "(select gender_type from hostel_gender_type where hostel_gender_type.unique_id = std_app_p2.hostel_gender_type) as hostel_gender_type",
            "(select hostel_type from hostel_type where hostel_type.unique_id = std_app_p2.hostel_type) as hostel_type",
            "(select hostel_name from hostel_name where hostel_name.unique_id = std_app_p2.hostel_name) as hostel_name",
            // "is_active",
            "unique_id"
        ];
        $table_details = [
            $table_p2 . " , (SELECT @a:= '" . $start . "') AS a ",
            $columns
        ];
        $where = "is_delete = 0 and p1_unique_id = '" . $p1_unique_id . "'";
        $order_by = "";

        if ($_POST['search']['value']) {
            $where .= " AND hostel_name LIKE '" . mysql_like($_POST['search']['value']) . "' ";
        }

        // Datatable Searching
        $search = datatable_searching($search, $columns);

        if ($search) {
            if ($where) {
                $where .= " AND ";
            }

            $where .= $search;
        }

        $sql_function = "SQL_CALC_FOUND_ROWS";

        $result = $pdo->select($table_details, $where, $limit, $start, $order_by, $sql_function);
        // print_r($result);
        $total_records = total_records();

        if ($result->status) {

            $res_array = $result->data;

            foreach ($res_array as $key => $value) {
                $value['hostel_name'] = disname($value['hostel_name']);
                // $value['is_active'] = is_active_show($value['is_active']);

                // $btn_update         = btn_update($folder_name,$value['unique_id']);
                $btn_delete = btn_delete($folder_name, $value['unique_id']);

                if ($value['unique_id'] == "5f97fc3257f2525529") {
                    $btn_update = "";
                    $btn_delete = "";
                }

                $value['unique_id'] = $btn_delete;
                $data[] = array_values($value);
            }

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data,
                "testing" => $result->sql
            ];
        } else {
            print_r($result);
        }

        echo json_encode($json_array);
        break;


    case 'hostel_sub_delete':

        $unique_id = $_POST['unique_id'];

        $columns = [
            "is_delete" => 1
        ];

        $update_where = [
            "unique_id" => $unique_id
        ];

        $action_obj = $pdo->update($table_p2, $columns, $update_where);

        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;
            $msg = "success_delete";
        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "error";
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql" => $sql
        ];

        echo json_encode($json_array);
        break;

    case 'std_img_upload':


        $p1_unique_id = $_POST["p1_unique_id"];


        if (!empty($_FILES["test_file"]['name'])) {
            $allowedExts = array("image");
            $extension = pathinfo($_FILES["test_file"]['name'], PATHINFO_EXTENSION);
            //   print_r($_FILES["test_file"]['name']);
            // if ((($_FILES["test_file"]["type"] == "image/jpeg")|| ($_FILES["test_file"]["type"] == "image/png")|| ($_FILES["test_file"]["type"] == "image/jpg"))){



            $file_exp = explode(".", $_FILES["test_file"]['name']);
            // print_r("gh".$file_exp);

            $tem_name = random_strings(25) . "." . $file_exp[1];
            // print_r("gg".$tem_name);
            move_uploaded_file($_FILES["test_file"]["tmp_name"], "student_img/" . $tem_name);
            // }
            if (!empty($_FILES["test_file"]['name'])) {
                // print_r("hh".$tem_name);
                $file_names = $tem_name;
                $file_org_names = $_FILES["test_file"]['name'];
            }


            // $is_active          = $_POST["is_active"];
            $unique_id = $_POST["unique_id"];

            $update_where = "";

            $columns = [
                "p1_unique_id" => $p1_unique_id,
                "std_img" => $file_names,
                "std_img_org_name" => $file_org_names,




                "entry_date" => date('Y-m-d'),
                // "is_active"        => $is_active,
                "unique_id" => unique_id($prefix)
            ];
        } else {
            $columns = [
                "p1_unique_id" => $p1_unique_id,
                "unique_id" => unique_id($prefix)
            ];
        }
        // check already Exist Or not
        $table_details = [
            $table_p3,
            [
                "COUNT(unique_id) AS count"
            ]
        ];
        $select_where = '';
        $select_where .= ' is_delete = 0 and p1_unique_id = "' . $p1_unique_id . '"';
        // When Update Check without current id
        if ($unique_id) {
            $select_where .= ' AND unique_id !="' . $unique_id . '" ';
        }

        $action_obj = $pdo->select($table_details, $select_where);

        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;
        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "error";
        }
        if ($data[0]["count"]) {
            $update_where = [
                "p1_unique_id" => $p1_unique_id
            ];
            $action_obj = $pdo->update($table_p3, $columns, $update_where);
        } else if ($data[0]["count"] == 0) {



            $action_obj = $pdo->insert($table_p3, $columns);




            if ($action_obj->status) {
                $status = $action_obj->status;
                $data = $action_obj->data;
                $error = "";
                $sql = $action_obj->sql;

                if ($unique_id) {
                    $msg = "update";
                } else {
                    $msg = "save";
                }
            } else {
                $status = $action_obj->status;
                $data = $action_obj->data;
                $error = $action_obj->error;
                $sql = $action_obj->sql;
                $msg = "error";
            }
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql" => $sql
        ];

        echo json_encode($json_array);

        break;





    case 'personal_add_update':


        $p1_unique_id = $_POST["p1_unique_id"];




        // $is_active          = $_POST["is_active"];
        $unique_id = $_POST["unique_id"];

        $update_where = "";

        $columns = [
            "is_renewal" => $_POST['is_renewal'],
            "emis_no" => $_POST['emis_no'],
            "umis_no" => $_POST['umis_no'],
            "std_dob" => $_POST['std_dob'],
            "age" => $_POST['age'],
            "blood_group" => $_POST['blood_group'],
            "gender" => $_POST['gender'],
            "email_id" => $_POST['email_id'],
            "religion" => $_POST['religion'],
            "mother_tongue" => $_POST['mother_tongue'],
            "community_cer_no" => $_POST['community_cer_no'],
            "std_caste" => $_POST['std_caste'],
            "std_sub_caste" => $_POST['std_sub_caste'],
            "contact_no_type" => $_POST['contact_no_type'],
            "contact_no" => $_POST['contact_no'],
            "income_cer_no" => $_POST['income_cer_no'],
            "annual_income" => $_POST['annual_income'],
            "remarks" => $_POST['remarks'],
            "physically_challenge" => $_POST['physically_challenge'],
            "phy_category" => $_POST['phy_category'],
            "phy_percentage" => $_POST['phy_percentage'],
            "phy_id_no" => $_POST['phy_id_no'],
            "srilankan_refugees" => $_POST['srilankan_refugees'],
            "orphan" => $_POST['orphan'],
            "single_parent" => $_POST['single_parent'],
            "first_graduate" => $_POST['first_graduate'],
            "graduate_no" => $_POST['graduate_no'],

            "p1_unique_id" => $p1_unique_id,


            "entry_date" => date('Y-m-d'),
            // "is_active"        => $is_active,
            "unique_id" => unique_id($prefix)
        ];

        // check already Exist Or not
        $table_details = [
            $table_p4,
            [
                "COUNT(unique_id) AS count"
            ]
        ];
        $select_where = '';
        $select_where .= ' is_delete = 0 and p1_unique_id ="' . $p1_unique_id . '"';
        // When Update Check without current id
        if ($unique_id) {
            $select_where .= ' AND unique_id !="' . $unique_id . '" ';
        }

        $action_obj = $pdo->select($table_details, $select_where);

        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;
        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "error";
        }
        if ($data[0]["count"]) {
            // $msg        = "already";
            $update_where = [
                "p1_unique_id" => $p1_unique_id
            ];
            $action_obj = $pdo->update($table_p4, $columns, $update_where);
        } else if ($data[0]["count"] == 0) {

            $action_obj = $pdo->insert($table_p4, $columns);
        }

        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;

            if ($unique_id) {
                $msg = "update";
            } else {
                $msg = "save";
            }
        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "error";
        }


        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql" => $sql
        ];

        echo json_encode($json_array);

        break;


    case 'institution_details_add':


        $p1_unique_id = $_POST["p1_unique_id"];




        // $is_active          = $_POST["is_active"];
        $unique_id = $_POST["unique_id"];

        $update_where = "";

        $columns = [
            "std_scl_district" => $_POST['std_district'],
            "std_scl_taluk" => $_POST['std_taluk'],
            "std_school_name" => $_POST['std_school_name'],
            "std_class" => $_POST['std_class'],
            "std_group" => $_POST['std_group'],
            "std_medium" => $_POST['std_medium'],
            "scl_std_scholarship_no" => $_POST['scl_std_scholarship_no'],
            "std_stream" => $_POST['std_stream'],
            "std_university" => $_POST['std_university'],
            "std_college_name" => $_POST['std_college_name'],
            "std_degree" => $_POST['std_degree'],
            "std_subject" => $_POST['std_subject'],
            "std_studying_year" => $_POST['std_studying_year'],
            "clg_std_medium" => $_POST['clg_std_medium'],
            "clg_std_scholarship_no" => $_POST['clg_std_scholarship_no'],

            "p1_unique_id" => $p1_unique_id,


            "entry_date" => date('Y-m-d'),
            // "is_active"        => $is_active,
            "unique_id" => unique_id($prefix)
        ];

        // check already Exist Or not
        $table_details = [
            $table_p5,
            [
                "COUNT(unique_id) AS count"
            ]
        ];
        $select_where = '';
        $select_where .= ' is_delete = 0 and p1_unique_id ="' . $p1_unique_id . '"';
        // When Update Check without current id
        if ($unique_id) {
            $select_where .= ' AND unique_id !="' . $unique_id . '" ';
        }

        $action_obj = $pdo->select($table_details, $select_where);

        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;
        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "error";
        }
        if ($data[0]["count"]) {
            // $msg        = "already";
            $update_where = [
                "p1_unique_id" => $p1_unique_id
            ];
            $action_obj = $pdo->update($table_p5, $columns, $update_where);
        } else if ($data[0]["count"] == 0) {

            $action_obj = $pdo->insert($table_p5, $columns);
        }

        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;

            if ($unique_id) {
                $msg = "update";
            } else {
                $msg = "save";
            }
        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "error";
        }


        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql" => $sql
        ];

        echo json_encode($json_array);

        break;

    case 'last_institution_details_add':


        $p1_unique_id = $_POST["p1_unique_id"];




        // $is_active          = $_POST["is_active"];
        $unique_id = $_POST["unique_id"];

        $update_where = "";

        $columns = [
            "last_std_scl_name" => $_POST['last_std_scl_name'],
            "last_std_class" => $_POST['last_std_class'],
            "last_std_group" => $_POST['last_std_group'],
            "last_std_medium" => $_POST['last_std_medium'],
            "last_scl_district" => $_POST['last_scl_district'],
            "last_clg_district" => $_POST['last_clg_district'],
            "last_std_stream" => $_POST['last_std_stream'],
            "last_std_university" => $_POST['last_std_university'],
            "last_std_college_name" => $_POST['last_std_college_name'],
            "last_std_degree" => $_POST['last_std_degree'],
            "last_std_subject" => $_POST['last_std_subject'],
            "last_std_studying_year" => $_POST['last_std_studying_year'],
            "last_clg_std_medium" => $_POST['last_clg_std_medium'],
            "last_std_scl_add" => $_POST['last_std_scl_add'],
            "last_clg_address" => $_POST['last_clg_address'],

            "p1_unique_id" => $p1_unique_id,


            "entry_date" => date('Y-m-d'),
            // "is_active"        => $is_active,
            "unique_id" => unique_id($prefix)
        ];

        // check already Exist Or not
        $table_details = [
            $table_p6,
            [
                "COUNT(unique_id) AS count"
            ]
        ];
        $select_where = '';
        $select_where .= ' is_delete = 0 and p1_unique_id ="' . $p1_unique_id . '"';
        // When Update Check without current id
        if ($unique_id) {
            $select_where .= ' AND unique_id !="' . $unique_id . '" ';
        }

        $action_obj = $pdo->select($table_details, $select_where);

        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;
        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "error";
        }
        if ($data[0]["count"]) {
            // $msg        = "already";
            $update_where = [
                "p1_unique_id" => $p1_unique_id
            ];
            $action_obj = $pdo->update($table_p6, $columns, $update_where);
        } else if ($data[0]["count"] == 0) {

            $action_obj = $pdo->insert($table_p6, $columns);
        }

        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;

            if ($unique_id) {
                $msg = "update";
            } else {
                $msg = "save";
            }
        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "error";
        }


        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql" => $sql
        ];

        echo json_encode($json_array);

        break;

    case 'address_details_add':


        $p1_unique_id = $_POST["p1_unique_id"];
        $door_no = $_POST["door_no"];
        $area_name = $_POST["area_name"];
        $landmark = $_POST["landmark"];
        $district_name = $_POST["district_name"];
        $taluk_name = $_POST["taluk_name"];
        $village_name = $_POST["village_name"];
        $pincode = $_POST["pincode"];




        // $is_active          = $_POST["is_active"];
        $unique_id = $_POST["unique_id"];

        $update_where = "";

        $columns = [
            "door_no" => $_POST['door_no'],
            "area_name" => $_POST['area_name'],
            "landmark" => $_POST['landmark'],
            "district_name" => $_POST['district_name'],
            "taluk_name" => $_POST['taluk_name'],
            "village_name" => $_POST['village_name'],
            "pincode" => $_POST['pincode'],


            "p1_unique_id" => $p1_unique_id,


            "entry_date" => date('Y-m-d'),
            // "is_active"        => $is_active,
            "unique_id" => unique_id($prefix)
        ];

        // check already Exist Or not
        $table_details = [
            $table_p7,
            [
                "COUNT(unique_id) AS count"
            ]
        ];
        $select_where = '';
        $select_where .= ' is_delete = 0 and p1_unique_id ="' . $p1_unique_id . '"';
        // When Update Check without current id
        if ($unique_id) {
            $select_where .= ' AND unique_id !="' . $unique_id . '" ';
        }

        $action_obj = $pdo->select($table_details, $select_where);

        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;
        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "error";
        }
        if ($data[0]["count"]) {
            // $msg        = "already";
            $update_where = [
                "p1_unique_id" => $p1_unique_id
            ];
            $action_obj = $pdo->update($table_p7, $columns, $update_where);
        } else if ($data[0]["count"] == 0) {

            $action_obj = $pdo->insert($table_p7, $columns);
        }

        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;

            if ($unique_id) {
                $msg = "update";
            } else {
                $msg = "save";
            }
        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "error";
        }


        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql" => $sql
        ];

        echo json_encode($json_array);

        break;


    case 'distance_details_add':


        $p1_unique_id = $_POST["p1_unique_id"];
        $hos_dis_home = $_POST["hos_dis_home"];
        $hos_dis_insti = $_POST["hos_dis_insti"];



        // $is_active          = $_POST["is_active"];
        $unique_id = $_POST["unique_id"];

        $update_where = "";

        $columns = [
            "hos_dis_home" => $_POST['hos_dis_home'],
            "hos_dis_insti" => $_POST['hos_dis_insti'],



            "p1_unique_id" => $p1_unique_id,


            "entry_date" => date('Y-m-d'),
            // "is_active"        => $is_active,
            "unique_id" => unique_id($prefix)
        ];

        // check already Exist Or not
        $table_details = [
            $table_p8,
            [
                "COUNT(unique_id) AS count"
            ]
        ];
        $select_where = '';
        $select_where .= ' is_delete = 0 and p1_unique_id ="' . $p1_unique_id . '"';
        // When Update Check without current id
        if ($unique_id) {
            $select_where .= ' AND unique_id !="' . $unique_id . '" ';
        }

        $action_obj = $pdo->select($table_details, $select_where);

        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;
        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "error";
        }
        if ($data[0]["count"]) {
            // $msg        = "already";
            $update_where = [
                "p1_unique_id" => $p1_unique_id
            ];
            $action_obj = $pdo->update($table_p8, $columns, $update_where);
        } else if ($data[0]["count"] == 0) {

            $action_obj = $pdo->insert($table_p8, $columns);
        }

        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;

            if ($unique_id) {
                $msg = "update";
            } else {
                $msg = "save";
            }
        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "error";
        }


        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql" => $sql
        ];

        echo json_encode($json_array);

        break;


    case 'bank_details_add':


        $p1_unique_id = $_POST["p1_unique_id"];
        $bank_name = $_POST["bank_name"];
        $bank_acc_no = $_POST["bank_acc_no"];
        $ifsc_code = $_POST["ifsc_code"];
        $branch_name = $_POST["branch_name"];



        // $is_active          = $_POST["is_active"];
        $unique_id = $_POST["unique_id"];

        $update_where = "";

        $columns = [
            "bank_name" => $bank_name,
            "bank_acc_no" => $bank_acc_no,
            "ifsc_code" => $ifsc_code,
            "branch_name" => $branch_name,



            "p1_unique_id" => $p1_unique_id,


            "entry_date" => date('Y-m-d'),
            // "is_active"        => $is_active,
            "unique_id" => unique_id($prefix)
        ];

        // check already Exist Or not
        $table_details = [
            $table_p10,
            [
                "COUNT(unique_id) AS count"
            ]
        ];
        $select_where = '';
        $select_where .= ' is_delete = 0 and p1_unique_id ="' . $p1_unique_id . '"';
        // When Update Check without current id
        if ($unique_id) {
            $select_where .= ' AND unique_id !="' . $unique_id . '" ';
        }

        $action_obj = $pdo->select($table_details, $select_where);

        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;
        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "error";
        }
        if ($data[0]["count"]) {
            // $msg        = "already";
            $update_where = [
                "p1_unique_id" => $p1_unique_id
            ];
            $action_obj = $pdo->update($table_p10, $columns, $update_where);
        } else if ($data[0]["count"] == 0) {

            $action_obj = $pdo->insert($table_p10, $columns);
        }

        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;

            if ($unique_id) {
                $msg = "update";
            } else {
                $msg = "save";
            }
        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "error";
        }


        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql" => $sql
        ];

        echo json_encode($json_array);

        break;

    case 'identity_details_add':


        $p1_unique_id = $_POST["p1_unique_id"];
        $aadhar_no = $_POST["aadhar_no"];
        $ration_card_no = $_POST["ration_card_no"];




        // $is_active          = $_POST["is_active"];
        $unique_id = $_POST["unique_id"];

        $update_where = "";

        $columns = [
            "aadhar_no" => $aadhar_no,
            "ration_card_no" => $ration_card_no,




            "p1_unique_id" => $p1_unique_id,


            "entry_date" => date('Y-m-d'),
            // "is_active"        => $is_active,
            "unique_id" => unique_id($prefix)
        ];

        // check already Exist Or not
        $table_details = [
            $table_p9,
            [
                "COUNT(unique_id) AS count"
            ]
        ];
        $select_where = '';
        $select_where .= ' is_delete = 0 and p1_unique_id ="' . $p1_unique_id . '"';
        // When Update Check without current id
        if ($unique_id) {
            $select_where .= ' AND unique_id !="' . $unique_id . '" ';
        }

        $action_obj = $pdo->select($table_details, $select_where);

        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;
        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "error";
        }
        if ($data[0]["count"]) {
            // $msg        = "already";
            $update_where = [
                "p1_unique_id" => $p1_unique_id
            ];
            $action_obj = $pdo->update($table_p9, $columns, $update_where);
        } else if ($data[0]["count"] == 0) {

            $action_obj = $pdo->insert($table_p9, $columns);
        }

        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;

            if ($unique_id) {
                $msg = "update";
            } else {
                $msg = "save";
            }
        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "error";
        }


        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql" => $sql
        ];

        echo json_encode($json_array);

        break;


    case 'family_details_add':


        $p1_unique_id = $_POST["p1_unique_id"];
        $father_name = $_POST["father_name"];
        $father_occuption = $_POST["father_occuption"];
        $father_qualification = $_POST["father_qualification"];
        $father_mob_no = $_POST["father_mob_no"];
        $mother_name = $_POST["mother_name"];
        $mother_occupation = $_POST["mother_occupation"];
        $mother_qualification = $_POST["mother_qualification"];
        $mother_mob_no = $_POST["mother_mob_no"];
        $guardian_name = $_POST["guardian_name"];
        $guardian_occuption = $_POST["guardian_occuption"];
        $guardian_qualification = $_POST["guardian_qualification"];
        $guardian_mob_no = $_POST["guardian_mob_no"];



        // $is_active          = $_POST["is_active"];
        $unique_id = $_POST["unique_id"];

        $update_where = "";

        $columns = [
            "father_name" => $father_name,
            "father_occuption" => $father_occuption,
            "father_qualification" => $father_qualification,
            "father_mob_no" => $father_mob_no,
            "mother_occupation" => $mother_occupation,
            "mother_name" => $mother_name,
            "mother_qualification" => $mother_qualification,
            "mother_mob_no" => $mother_mob_no,
            "guardian_name" => $guardian_name,
            "guardian_occuption" => $guardian_occuption,
            "guardian_qualification" => $guardian_qualification,
            "guardian_mob_no" => $guardian_mob_no,



            "p1_unique_id" => $p1_unique_id,


            "entry_date" => date('Y-m-d'),
            // "is_active"        => $is_active,
            "unique_id" => unique_id($prefix)
        ];

        // check already Exist Or not
        $table_details = [
            $table_p11,
            [
                "COUNT(unique_id) AS count"
            ]
        ];
        $select_where = '';
        $select_where .= ' is_delete = 0 and p1_unique_id ="' . $p1_unique_id . '"';
        // When Update Check without current id
        if ($unique_id) {
            $select_where .= ' AND unique_id !="' . $unique_id . '" ';
        }

        $action_obj = $pdo->select($table_details, $select_where);

        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;
        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "error";
        }
        if ($data[0]["count"]) {
            // $msg        = "already";
            $update_where = [
                "p1_unique_id" => $p1_unique_id
            ];
            $action_obj = $pdo->update($table_p11, $columns, $update_where);
        } else if ($data[0]["count"] == 0) {

            $action_obj = $pdo->insert($table_p11, $columns);
        }

        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;

            if ($unique_id) {
                $msg = "update";
            } else {
                $msg = "save";
            }
        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "error";
        }


        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql" => $sql
        ];

        echo json_encode($json_array);

        break;


    case 'doc_upload':


        $p1_unique_id = $_POST["p1_unique_id"];
        $aadhar_img = $_POST["aadhar_img"];
        $bonafide_img = $_POST["bonafide_img"];
        $passbook_img = $_POST["passbook_img"];
        $aadhar_org_name = $_POST["aadhar_org_name"];
        $bonafide_org_name = $_POST["bonafide_org_name"];
        $bank_passbook_org_name = $_POST["bank_passbook_org_name"];



        $allowedExts = array("image");
        $extension = pathinfo($_FILES["aadhar_file"]['name'], PATHINFO_EXTENSION);
        //   print_r($_FILES["test_file"]['name']);
        // if ((($_FILES["test_file"]["type"] == "image/jpeg")|| ($_FILES["test_file"]["type"] == "image/png")|| ($_FILES["test_file"]["type"] == "image/jpg"))){



        $aadhar_file_exp = explode(".", $_FILES["aadhar_file"]['name']);
        // print_r("gh".$file_exp);

        $aadhar_tem_name = random_strings(25) . "." . $aadhar_file_exp[1];
        // print_r("gg".$tem_name);
        move_uploaded_file($_FILES["aadhar_file"]["tmp_name"], "doc_upload/" . $aadhar_tem_name);
        // }
        if (!empty($_FILES["aadhar_file"]['name'])) {
            // print_r("hh".$tem_name);
            $aadhar_file_names = $aadhar_tem_name;
            $aadhar_file_org_names = $_FILES["aadhar_file"]['name'];
        } else {
            $aadhar_file_names = $aadhar_img;
            $aadhar_file_org_names = $aadhar_org_name;
        }


        $bonafide_file_exp = explode(".", $_FILES["bonafide_file"]['name']);
        // print_r("gh".$file_exp);

        $bonafide_tem_name = random_strings(25) . "." . $bonafide_file_exp[1];
        // print_r("gg".$tem_name);
        move_uploaded_file($_FILES["bonafide_file"]["tmp_name"], "doc_upload/" . $bonafide_tem_name);
        // }
        if (!empty($_FILES["bonafide_file"]['name'])) {
            // print_r("hh".$tem_name);
            $bonafide_file_names = $bonafide_tem_name;
            $bonafide_file_org_names = $_FILES["bonafide_file"]['name'];
        } else {
            $bonafide_file_names = $bonafide_img;
            $bonafide_file_org_names = $bonafide_org_name;
        }


        $bank_passbook_file_exp = explode(".", $_FILES["bank_passbook_file"]['name']);
        // print_r("gh".$file_exp);

        $bank_passbook_tem_name = random_strings(25) . "." . $bank_passbook_file_exp[1];
        // print_r("gg".$tem_name);
        move_uploaded_file($_FILES["bank_passbook_file"]["tmp_name"], "doc_upload/" . $bank_passbook_tem_name);
        // }
        if (!empty($_FILES["bank_passbook_file"]['name'])) {
            // print_r("hh".$tem_name);
            $bank_passbook_file_names = $bank_passbook_tem_name;
            $bank_passbook_file_org_names = $_FILES["bank_passbook_file"]['name'];
        } else {
            $bank_passbook_file_names = $passbook_img;
            $bank_passbook_file_org_names = $bank_passbook_org_name;
        }


        // $is_active          = $_POST["is_active"];
        $unique_id = $_POST["unique_id"];

        $update_where = "";

        $columns = [
            "p1_unique_id" => $p1_unique_id,
            "aadhar_file" => $aadhar_file_names,
            "aadhar_org_name" => $aadhar_file_org_names,
            "bonafide_file" => $bonafide_file_names,
            "bonafide_org_name" => $bonafide_file_org_names,
            "bank_passbook_file" => $bank_passbook_file_names,
            "bank_passbook_org_name" => $bank_passbook_file_org_names,




            "entry_date" => date('Y-m-d'),
            // "is_active"        => $is_active,
            "unique_id" => unique_id($prefix)
        ];

        // check already Exist Or not
        $table_details = [
            $table_p12,
            [
                "COUNT(unique_id) AS count"
            ]
        ];
        $select_where = '';
        $select_where .= ' is_delete = 0 and p1_unique_id = "' . $p1_unique_id . '"';
        // When Update Check without current id
        if ($unique_id) {
            $select_where .= ' AND unique_id !="' . $unique_id . '" ';
        }

        $action_obj = $pdo->select($table_details, $select_where);

        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;
        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "error";
        }
        if ($data[0]["count"]) {
            $update_where = [
                "p1_unique_id" => $p1_unique_id
            ];

            $action_obj = $pdo->update($table_p12, $columns, $update_where);
        } else if ($data[0]["count"] == 0) {



            $action_obj = $pdo->insert($table_p12, $columns);




            if ($action_obj->status) {
                $status = $action_obj->status;
                $data = $action_obj->data;
                $error = "";
                $sql = $action_obj->sql;

                if ($unique_id) {
                    $msg = "update";
                } else {
                    $msg = "save";
                }
            } else {
                $status = $action_obj->status;
                $data = $action_obj->data;
                $error = $action_obj->error;
                $sql = $action_obj->sql;
                $msg = "error";
            }
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql" => $sql
        ];

        echo json_encode($json_array);

        break;


    case 'search':
        // DataTable Variables
        $app_number = $_POST['app_number'];
        $date = $_POST['date'];
        $app_no = application_no($_POST['app_number'])[0]['unique_id'];
        // print_r($app_no);die();
        $table = "std_app_p4";
        // Query Variables
        $json_array = "";
        $table_details = [
            $table,
            [
                "COUNT(unique_id) AS count",
                "p1_unique_id",

            ]

        ];



        $select_where = '';
        $select_where .= ' is_delete = 0 and p1_unique_id = "' . $app_no . '" and std_dob = "' . $date . '"';
        // When Update Check without current id
        // and std_dob = "'.$date.'"


        $action_obj = $pdo->select($table_details, $select_where);
        // print_r($action_obj);
        $select_datas = $action_obj->data;
        // die();
        $p1_unique_id = $select_datas[0]['p1_unique_id'];
        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;
        }
        if ($data[0]["count"]) {
            // $msg        = "Mobile Number Not Exist";
            // $std_app_no = $data[0]["std_app_no"];
            $json_array = [
                "status" => $status,
                "data" => $data,
                "error" => $error,
                "msg" => $msg,
                "p1_unique_id" => $p1_unique_id,
                // "sql" => $sql
            ];
        } else if ($data[0]["count"] == 0) {
            // Update Begins
            $data = "Enter Valid Deatails";
            $json_array = [
                "status" => $status,
                "data" => $data,
                "error" => $error,
                "msg" => $msg,
                "std_app_no" => $std_app_no,
                // "sql" => $sql
            ];
        }



        echo json_encode($json_array);

        break;


    default:

        break;
}






function app_no($academic_year)
{
    // $date = date("Y");
    // $st_date = substr($date, 4);

    $servername = "localhost";
    $username = "root";
    $password = "4/rb5sO2s3TpL4gu";
    $database_name = "adi_dravidar";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=adi_dravidar", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "Connected successfully";
    } catch (PDOException $e) {
        // echo "Connection failed: " . $e->getMessage();
    }

    // $sql = $conn->query("SELECT * FROM academic_year where unique_id = '$user_acc_year' ");
    // $row = $sql->fetch();

    $acc_year = $academic_year;
    $acmc_year = academic_year($acc_year)[0]['acc_year'];
    $a = str_split($acmc_year);
    $splt_acc_yr = $a[0] . $a[1] . $a[2] . $a[3];




    // echo "SELECT * FROM apply_application_form where application_no LIKE 'APN%' order by id desc";echo "<br>";
    $stmt = $conn->query("SELECT max(std_app_no) as std_app_no FROM std_app_p1 where is_delete = '0'  order by id desc");
    // $bill = $stmt->fetch();
    // $res_array = $bill['id'];
    // $result = $res_array + 1;


    // if($res1=$stmt->fetch($stmt))
    if ($res1 = $stmt->fetch()) {
        $pur_array = explode('-', $res1['std_app_no']);




        $booking_no = $pur_array[1];
    }
    //  $booking_nos = 'APN-' . $splt_acc_yr .'-' . ''. $splt_dis .'-' .'' .$splt_zone.'-' .'' .$splt_host.'-' .'-0001';
    if ($booking_no == '') {
        $booking_nos = $splt_acc_yr . 'ADW-' . '' . '0001';
    }
    // else if ($year != date("Y")){
    //     $booking_nos = 'APN-'.date('Y'). $splt_acc_yr . ''.$splt_dis.'' .$splt_zone.'' .$splt_host.'-0001';
    // }
    else {
        $booking_no += 1;

        $booking_nos = $splt_acc_yr . 'ADW-' . str_pad($booking_no, 4, '0', STR_PAD_LEFT);
    }

    return $booking_nos;
}


function reg_no($academic_year)
{
    // $date = date("Y");
    // $st_date = substr($date, 4);

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database_name = "adi_dravidar";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=adi_dravidar", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
    }

    $sql = $conn->query("SELECT * FROM academic_year_creation ORDER BY unique_id DESC LIMIT 1");
    $row = $sql->fetch();

    $acc_year = $row['acc_year'];
    $a = str_split($acc_year);
    $splt_acc_yr = $a[0] . $a[1] . $a[2] . $a[3];

    $stmt = $conn->query("SELECT max(std_app_no) as std_app_no FROM std_app_p1 where is_delete = '0'");
    $last_reg_no = $stmt->fetchColumn();

    if ($last_reg_no == '') {
        $new_seq_no = 1;
    } else {
        // Extract year and sequence number from the last registration number
        $last_seq_no = intval(substr($last_reg_no, -4)); // Extract last 4 digits

        // Increment the sequence number
        $new_seq_no = $last_seq_no + 1;
    }

    // Format the new registration number
    $registration_no = $splt_acc_yr . 'ADW' . str_pad($new_seq_no, 4, '0', STR_PAD_LEFT);

    return $registration_no;
}


function random_strings($length_of_string)
{

    // String of all alphanumeric character
    $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    // Shuffle the $str_result and returns substring
    // of specified length
    return substr(
        str_shuffle($str_result),
        0,
        $length_of_string
    );
}




function base256_decode($str)
{
    $result = '';
    for ($i = 0; $i < strlen($str); $i += 3) {
        $charCode = intval(substr($str, $i, 3));
        $result .= chr($charCode);
    }
    return $result;
}
