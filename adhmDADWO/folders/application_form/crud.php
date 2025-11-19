<?php

include '../../../config/dbconfig.php';


// Get folder Name From Currnent Url     
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

$host = "localhost";
$username = "root";
$password = "4/rb5sO2s3TpL4gu";
$databasename = "adi_dravidar";

$mysqli = new mysqli($host, $username, $password, $databasename);




// // Database Country Table Name
$table_income = "income_certificate";
$table_a = "aadhar";
$table_a_ref = "aadhar_ref";
$table = "std_app_s";
$table_s2 = "std_app_s2";
$table_s3 = "std_app_emis_s3";
$table_s4 = "std_app_umis_s4";
$table_s5 = "std_app_s5";
$table_s6 = "std_app_s6";
$table_s7 = "std_app_s7";
$table_p8 = "std_app_p8";
$table_p9 = "std_app_p9";
$table_p10 = "std_app_p10";
$table_p11 = "std_app_p11";
$table_p12 = "std_app_p12";
// // Include DB file and Common Functions


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

    case 'createupdate':

        $academic_year = base64_decode($_POST["academic_year"]);
        $hostel_type = base64_decode($_POST["hostel_type"]);
        $std_name = base64_decode($_POST["std_name"]);
        $student_status = base64_decode($_POST["student_status"]);


        $app_type = $_POST["app_type"];

        $uuid = $_POST["uuid"];

        if ($hostel_type == '65f00a259436412348') {
            $host_type = 'S';
        } elseif ($hostel_type == '65f00a327c08582160') {
            $host_type = 'I';
        } elseif ($hostel_type == '65f00a3e3c9a337012') {
            $host_type = 'D';
        } elseif ($hostel_type == '65f00a495599589293' || $hostel_type == '65f00a53eef3015995') {
            $host_type = 'C';
        }
        $is_delete = '0';

        $std_app_no = reg_no($academic_year, $host_type);

        $student_status = $student_status ? $student_status : 0;


        if ($student_status == 1) {
            $unique_id = unique_id($prefix);
            $sql = "INSERT INTO $table (std_app_no, academic_year, application_type, student_type, entry_date, unique_id, student_status) VALUES (?, ?, ?, ?, ?, ?, ?)";

            $params = [
                reg_no($academic_year, $host_type),
                $academic_year,
                base64_decode($app_type),
                $hostel_type,
                date('Y-m-d'),
                $unique_id,
                $student_status
            ];

        } else {

            $sql = "SELECT COUNT(unique_id) AS count FROM $table WHERE is_delete = ? AND uuid = ?";
            $params = [$is_delete, base64_decode($uuid)];

            if ($unique_id) {
                $sql .= " AND unique_id != ?";
                $params[] = $unique_id;
            }

            $stmt = $mysqli->prepare($sql);
            if ($stmt === false) {
                die('MySQL prepare error: ' . $mysqli->error);
            }

            // Bind parameters
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);

            // Execute statement
            $stmt->execute();
            $result = $stmt->get_result();

            // Fetch count
            $row = $result->fetch_assoc();
            $count = $row['count'];

            // Close statement
            $stmt->close();

            // Check if record already exists
            if ($count > 0) {
                $msg = "already";
            } else {
                // Prepare insert or update query
                if ($unique_id) {
                    // Update operation
                    $sql = "UPDATE $table SET std_app_no = ?, academic_year = ?, application_type = ?, uuid = ?, student_type = ?, entry_date = ? WHERE unique_id = ?";
                    $params = [
                        $std_app_no,
                        $academic_year,
                        base64_decode($app_type),
                        base64_decode($uuid),
                        $hostel_type,
                        date('Y-m-d'),
                        $unique_id
                    ];
                } else {
                    // Insert operation

                    $unique_id = unique_id($prefix);
                    $sql = "INSERT INTO $table (std_app_no, academic_year, application_type, uuid, student_type, entry_date, unique_id, std_name, student_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

                    $params = [
                        reg_no($academic_year, $host_type),
                        $academic_year,
                        base64_decode($app_type),
                        base64_decode($uuid),
                        $hostel_type,
                        date('Y-m-d'),
                        $unique_id,
                        $std_name,
                        $student_status
                    ];
                }
            }
        }

        // Prepare and execute insert/update statement

        $stmt = $mysqli->prepare($sql);

        if ($stmt === false) {
            die('MySQL prepare error: ' . $mysqli->error);
        }

        // Bind parameters
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);

        // Execute statement
        // $stmt->execute();
        // Check for success
        if ($stmt->execute()) {

            $status = true;
            $msg = $unique_id ? "otp" : "otp"; // Adjust messages as needed for update or insert

            $password = '3sc3RLrpd17';
            $enc_method = 'aes-256-cbc';
            $enc_password = substr(hash('sha256', $password, true), 0, 32);
            $enc_iv = 'av3DYGLkwBsErphc';
            $menu_screen = 'application_form/model';
            $url = base64_encode(openssl_encrypt($menu_screen, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));
            $encrypted_unique_id = base64_encode(openssl_encrypt($unique_id, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));


        } else {
            $error = $stmt->error;
            $msg = "error";
        }

        // Close statement
        $stmt->close();




        // Prepare JSON response
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            "url" => $url,
            "unique_id" => $unique_id,
            "encrypted_unique_id" => $encrypted_unique_id
        ];

        echo json_encode($json_array);

        break;

    case 'community_certificate':

        $s1_unique_id = base64_decode($_POST["s1_unique_id"]);
        $applicant_name = $_POST["applicant_name"];
        $father_name = $_POST["father_name"];
        $address = $_POST["address"];
        $village_town = $_POST["village_town"];
        $taluk_name = $_POST["taluk_name"];
        $district = $_POST["district"];
        $gender = $_POST["gender"];
        $pincode = $_POST["pincode"];
        $religion = $_POST["religion"];
        $community = $_POST["community"];
        $caste = $_POST["caste"];
        $serial_no = $_POST["serial_no"];
        $issuing_authority = $_POST["issuing_authority"];
        $date_issue = $_POST["date_issue"];
        $date_expiry = $_POST["date_expiry"];
        $certificate_no = $_POST["certificate_no"];
        $attachment = $_POST["attachment"];
        $output_pdf = $_POST["output_pdf"];
        $unique_id = unique_id($prefix);

        // Prepare data for insertion or update
        $columns = [
            "s1_unique_id" => $s1_unique_id,
            "applicant_name" => base64_decode($applicant_name),
            "father_name" => base64_decode($father_name),
            "address" => base64_decode($address),
            "village_town" => base64_decode($village_town),
            "taluk_name" => base64_decode($taluk_name),
            "district" => base64_decode($district),
            "pincode" => base64_decode($pincode),
            "gender" => base64_decode($gender),
            "religion" => base64_decode($religion),
            "community" => base64_decode($community),
            "caste" => base64_decode($caste),
            "serial_no" => base64_decode($serial_no),
            "issuing_authority" => base64_decode($issuing_authority),
            "date_issue" => base64_decode($date_issue),
            "date_expiry" => base64_decode($date_expiry),
            "certificate_no" => base64_decode($certificate_no),
            "attachment" => base64_decode($attachment),
            "output_pdf" => base64_decode($output_pdf),
            "entry_date" => date('Y-m-d'),
            "unique_id" => $unique_id
        ];

        // Check if entry already exists
        $table_details = [
            "community_certificate",
            ["COUNT(id) AS count"]
        ];

        $select_where = ' is_delete = 0 and s1_unique_id = "' . $s1_unique_id . '"';
        $action_obj = $pdo->select($table_details, $select_where);

        if ($action_obj->status) {
            $data = $action_obj->data;
            $count = $data[0]["count"];

            if ($count > 0) {
                unset($columns['unique_id']);
                $update_where = ["s1_unique_id" => $s1_unique_id];
                $action_obj = $pdo->update("community_certificate", $columns, $update_where);
                // if ($unique_id) {

                // } 
            } else {
                $action_obj = $pdo->insert("community_certificate", $columns);
            }

            if ($action_obj->status) {
                $msg = "otp"; // Change message as needed
            } else {
                $msg = "error";
                $error = $action_obj->error;
            }
        }
        // } else {
        //     $msg = "error";
        //     $error = $action_obj->error;
        // }

        // Prepare JSON response
        $json_array = [
            "status" => $action_obj->status,
            "data" => $action_obj->data,
            "error" => $error ?? "",
            "msg" => $msg,
            "unique_id" => $unique_id,
            // "sql" => $action_obj->sql
        ];

        echo json_encode($json_array);

        break;

    case 'umis_already':
        // Decoding the uuid
        $table = "std_app_umis_s4"; 
        $umis_no = $_POST["umis_no"];
        $is_delete = 0;

        // Include the connection
        $mysqli = new mysqli("localhost", "root", "4/rb5sO2s3TpL4gu", "adi_dravidar");

        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Prepare the SQL statement
        $sql = "SELECT COUNT(unique_id) AS count FROM $table WHERE is_delete = ? AND umis_no = ?";
        $stmt = $mysqli->prepare($sql);


        // Bind the parameters
        $stmt->bind_param("is", $is_delete, $umis_no);

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
        $stmt->bind_result($count);

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
            "msg" => $msg
        ];

        echo json_encode($json_array);
        break;

    case 'emis_already':
        // Decoding the uuid
        $table = "std_app_emis_s3";
        $emis_no = $_POST["emis_no"];
        $is_delete = 0;

        // Include the connection
        $mysqli = new mysqli("localhost", "root", "4/rb5sO2s3TpL4gu", "adi_dravidar");

        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Prepare the SQL statement
        $sql = "SELECT COUNT(unique_id) AS count FROM $table WHERE is_delete = ? AND emis_no = ?";
        $stmt = $mysqli->prepare($sql);


        // Bind the parameters
        $stmt->bind_param("is", $is_delete, $emis_no);

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
        $stmt->bind_result($count);

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
            "msg" => $msg
        ];

        echo json_encode($json_array);
        break;

    case 'cancel_app':

        $s1_unique_id = $_POST['s1_unique_id'];

        $stmt = $mysqli->prepare("SELECT COUNT(unique_id) AS count FROM std_app_s WHERE is_delete = 0 AND unique_id = ?");
        $stmt->bind_param('s', $s1_unique_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        // if ($data['count'] > 0) {

        $is_delete = '1';
        $stmt_s = $mysqli->prepare("UPDATE std_app_s SET is_delete = ? WHERE unique_id = ?");
        $stmt_s->bind_param('ss', $is_delete, $s1_unique_id);
        $stmt_s->execute();
        $status = true;
        $msg = 'cancel_app';

        $stmt_1 = $mysqli->prepare("DELETE from aadhar where s1_unique_id = ?");
        $stmt_1->bind_param('s', $s1_unique_id);
        $stmt_1->execute();

        $stmt_2 = $mysqli->prepare("DELETE from std_app_s2 where s1_unique_id = ?");
        $stmt_2->bind_param('s', $s1_unique_id);
        $stmt_2->execute();

        $stmt_3 = $mysqli->prepare("DELETE from std_app_emis_s3 where s1_unique_id = ?");
        $stmt_3->bind_param('s', $s1_unique_id);
        $stmt_3->execute();

        $stmt_4 = $mysqli->prepare("DELETE from std_app_umis_s4 where s1_unique_id = ?");
        $stmt_4->bind_param('s', $s1_unique_id);
        $stmt_4->execute();

        $stmt_5 = $mysqli->prepare("DELETE from std_app_s5 where s1_unique_id = ?");
        $stmt_5->bind_param('s', $s1_unique_id);
        $stmt_5->execute();

        $stmt_6 = $mysqli->prepare("DELETE from std_app_s6 where s1_unique_id = ?");
        $stmt_6->bind_param('s', $s1_unique_id);
        $stmt_6->execute();

        $stmt_7 = $mysqli->prepare("DELETE from std_app_s7 where s1_unique_id = ?");
        $stmt_7->bind_param('s', $s1_unique_id);
        $stmt_7->execute();

        $stmt = $mysqli->prepare("DELETE from umis_1 where s1_unique_id = ?");
        $stmt->bind_param('s', $s1_unique_id);
        $stmt->execute();
        $stmt_u2 = $mysqli->prepare("DELETE from umis_2 where s1_unique_id = ?");
        $stmt_u2->bind_param('s', $s1_unique_id);
        $stmt_u2->execute();
        $stmt_u3 = $mysqli->prepare("DELETE from umis_3 where s1_unique_id = ?");
        $stmt_u3->bind_param('s', $s1_unique_id);
        $stmt_u3->execute();

        $stmt_e = $mysqli->prepare("DELETE from emis where s1_unique_id = ?");
        $stmt_e->bind_param('s', $s1_unique_id);
        $stmt_e->execute();

        $stmt_i = $mysqli->prepare("DELETE from income_certificate where s1_unique_id = ?");
        $stmt_i->bind_param('s', $s1_unique_id);
        $stmt_i->execute();

        $stmt_c = $mysqli->prepare("DELETE from community_certificate where s1_unique_id = ?");
        $stmt_c->bind_param('s', $s1_unique_id);
        $stmt_c->execute();

        $password = '3sc3RLrpd17';
        $enc_method = 'aes-256-cbc';
        $enc_password = substr(hash('sha256', $password, true), 0, 32);
        $enc_iv = 'av3DYGLkwBsErphc';
        $menu_screen = 'application_form/list';
        $url = base64_encode(openssl_encrypt($menu_screen, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));





        // }


        $json_array = [
            'status' => $status,
            'data' => $data,
            'error' => $error,
            'msg' => $msg,
            'url' => $url,
        ];

        echo json_encode($json_array);

        break;


    case 'income_create':

        $s1_unique_id = $_POST["s1_unique_id"];
        $applicantName = !empty($_POST["applicantName"]) ? base64_decode($_POST["applicantName"]) : null;
        $applicantMother = !empty($_POST["applicantMother"]) ? base64_decode($_POST["applicantMother"]) : null;
        $applicantFather = !empty($_POST["applicantFather"]) ? base64_decode($_POST["applicantFather"]) : null;
        $applicantIncome = !empty($_POST["applicantIncome"]) ? base64_decode($_POST["applicantIncome"]) : null;
        $applicantOccupation = !empty($_POST["applicantOccupation"]) ? base64_decode($_POST["applicantOccupation"]) : null;
        $applicantOutput = !empty($_POST["applicantOutput"]) ? base64_decode($_POST["applicantOutput"]) : null;
        $applicantAddress = !empty($_POST["applicantAddress"]) ? base64_decode($_POST["applicantAddress"]) : null;
        $applicantVillage = !empty($_POST["applicantVillage"]) ? base64_decode($_POST["applicantVillage"]) : null;
        $applicantTaluk = !empty($_POST["applicantTaluk"]) ? base64_decode($_POST["applicantTaluk"]) : null;
        $applicantDistrict = !empty($_POST["applicantDistrict"]) ? base64_decode($_POST["applicantDistrict"]) : null;
        $applicantPincode = !empty($_POST["applicantPincode"]) ? base64_decode($_POST["applicantPincode"]) : null;
        $applicantAuthority = !empty($_POST["applicantAuthority"]) ? base64_decode($_POST["applicantAuthority"]) : null;
        $applicantIssueDate = !empty($_POST["applicantIssueDate"]) ? base64_decode($_POST["applicantIssueDate"]) : null;
        $applicantAttachement = !empty($_POST["applicantAttachement"]) ? base64_decode($_POST["applicantAttachement"]) : null;
        $applicantexpiry = !empty($_POST["applicantexpiry"]) ? base64_decode($_POST["applicantexpiry"]) : null;
        $applicantCertificateNo = !empty($_POST["applicantCertificateNo"]) ? base64_decode($_POST["applicantCertificateNo"]) : null;

        $unique_id = $_POST["unique_id"];

        // echo $applicantexpiry;
        // echo $applicantIssueDate;
        // die();

        $columns = [
            "s1_unique_id" => $s1_unique_id,
            "applicant_name" => $applicantName,
            "father_name" => $applicantFather,
            "address" => $applicantAddress,
            "village_town" => $applicantVillage,
            "taluk_name" => $applicantTaluk,
            "district" => $applicantDistrict,
            "pincode" => $applicantPincode,
            "occupation" => $applicantOccupation,
            "annual_income" => $applicantIncome,
            "mother_name" => $applicantMother,
            "issuing_authority" => $applicantAuthority,
            "date_issue" => $applicantIssueDate,
            "date_expiry" => $applicantexpiry,
            "certificate_no" => $applicantCertificateNo,
            "attachment" => $applicantAttachement,
            "output_pdf" => $applicantOutput,
            "entry_date" => date('Y-m-d'),
            "unique_id" => unique_id($prefix) // Replace with your unique ID generation logic
        ];

        // Check if record exists
        $select_sql = "SELECT COUNT(unique_id) AS count FROM $table_income WHERE s1_unique_id = ?";
        if ($unique_id) {
            $select_sql .= " AND unique_id != ?";
            $stmt = $mysqli->prepare($select_sql);
            $stmt->bind_param("ss", $s1_unique_id, $unique_id);
        } else {
            $stmt = $mysqli->prepare($select_sql);
            $stmt->bind_param("s", $s1_unique_id);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        if ($data['count'] > 0) {
            $update_columns = [
                "s1_unique_id" => $s1_unique_id,
                "applicant_name" => $applicantName,
                "father_name" => $applicantFather,
                "address" => $applicantAddress,
                "village_town" => $applicantVillage,
                "taluk_name" => $applicantTaluk,
                "district" => $applicantDistrict,
                "pincode" => $applicantPincode,
                "occupation" => $applicantOccupation,
                "annual_income" => $applicantIncome,
                "mother_name" => $applicantMother,
                "issuing_authority" => $applicantAuthority,
                "date_issue" => $applicantIssueDate,
                "date_expiry" => $applicantexpiry,
                "certificate_no" => $applicantCertificateNo,
                "attachment" => $applicantAttachement,
                "output_pdf" => $applicantOutput,
                "entry_date" => date('Y-m-d')
            ];

            $update_sql = "UPDATE $table_income SET applicant_name = ?, father_name = ?, address = ?, village_town = ?, taluk_name = ?, district = ?, pincode = ?, occupation = ?, annual_income = ?, mother_name = ?, issuing_authority = ?, date_issue = ?, date_expiry = ?, certificate_no = ?, attachment = ?, output_pdf = ?, entry_date = ? WHERE s1_unique_id = ?";
            $stmt = $mysqli->prepare($update_sql);
            $stmt->bind_param("ssssssssssssssssss", $update_columns['applicant_name'], $update_columns['father_name'], $update_columns['address'], $update_columns['village_town'], $update_columns['taluk_name'], $update_columns['district'], $update_columns['pincode'], $update_columns['occupation'], $update_columns['annual_income'], $update_columns['mother_name'], $update_columns['issuing_authority'], $update_columns['date_issue'], $update_columns['date_expiry'], $update_columns['certificate_no'], $update_columns['attachment'], $update_columns['output_pdf'], $update_columns['entry_date'], $s1_unique_id);
            $status = $stmt->execute();
        } else {
            $insert_sql = "INSERT INTO $table_income (s1_unique_id, applicant_name, father_name, address, village_town, taluk_name, district, pincode, occupation, annual_income, mother_name, issuing_authority, date_issue, date_expiry, certificate_no, attachment, output_pdf, entry_date, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($insert_sql);
            $stmt->bind_param("sssssssssssssssssss", $columns['s1_unique_id'], $columns['applicant_name'], $columns['father_name'], $columns['address'], $columns['village_town'], $columns['taluk_name'], $columns['district'], $columns['pincode'], $columns['occupation'], $columns['annual_income'], $columns['mother_name'], $columns['issuing_authority'], $columns['date_issue'], $columns['date_expiry'], $columns['certificate_no'], $columns['attachment'], $columns['output_pdf'], $columns['entry_date'], $columns['unique_id']);
            $status = $stmt->execute();
        }

        if ($status) {
            $msg = $unique_id ? "update" : "create";
            $status = true;
            $error = "";
        } else {
            $msg = "error";
            $status = false;
            $error = $stmt->error;
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg
        ];

        echo json_encode($json_array);

        break;

    case 'umis_insert':

        $s1_unique_id = base64_decode($_POST['s1_unique_id']);
        // $unique_id = $_POST['unique_id'];

        $columns_1 = [
            "umis_no" => base64_decode($_POST['umis_no']),
            "name" => base64_decode($_POST['name']),
            "emsid" => base64_decode($_POST['emsid']),
            "dateOfBirth" => base64_decode($_POST['dateOfBirth']),
            "nationalityId" => base64_decode($_POST['nationalityId']),
            "religionId" => base64_decode($_POST['religionId']),
            "communityId" => base64_decode($_POST['communityId']),
            "casteId" => base64_decode($_POST['casteId']),
            "isFirstGraduate" => base64_decode($_POST['isFirstGraduate']),
            "isDifferentlyAbled" => base64_decode($_POST['isDifferentlyAbled']),
            "isSpecialCategory" => base64_decode($_POST['isSpecialCategory']),
            "udid" => base64_decode($_POST['udid']),
            "disabilityId" => base64_decode($_POST['disabilityId']),
            "extentOfDisability" => base64_decode($_POST['extentOfDisability']),
            "bloodGroupId" => base64_decode($_POST['bloodGroupId']),
            "genderId" => base64_decode($_POST['genderId']),
            "salutationId" => base64_decode($_POST['salutationId']),
            "instituteId" => base64_decode($_POST['instituteId']),
            "umisId" => base64_decode($_POST['umisId']),
            "nameAsOnCertificate" => base64_decode($_POST['nameAsOnCertificate']),
            "isFirstGraduateVerifiedbyUniversity" => base64_decode($_POST['isFirstGraduateVerifiedbyUniversity']),
            "isFirstGraduateVerifiedbyHod" => base64_decode($_POST['isFirstGraduateVerifiedbyHod']),
            "mobileNumber" => base64_decode($_POST['mobileNumber']),
            "emailId" => base64_decode($_POST['emailId']),
            "permAddress" => base64_decode($_POST['permAddress']),
            "s1_unique_id" => $s1_unique_id,
            "entry_date" => date('Y-m-d'),
            "unique_id" => unique_id($prefix) // Replace with your unique ID generation logic
        ];

        // Check if record exists in umis_1
        $select_sql_1 = "SELECT COUNT(unique_id) AS count FROM umis_1 WHERE is_delete = 0 AND s1_unique_id = ?";
        $stmt = $mysqli->prepare($select_sql_1);
        $stmt->bind_param("s", $s1_unique_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data_1 = $result->fetch_assoc();

        if ($data_1['count'] > 0) {

            $update_sql_1 = "UPDATE umis_1 SET umis_no = ?, name = ?, emsid = ?, dateOfBirth = ?, nationalityId = ?, religionId = ?, communityId = ?, casteId = ?, isFirstGraduate = ?, isDifferentlyAbled = ?, isSpecialCategory = ?, udid = ?, disabilityId = ?, extentOfDisability = ?, bloodGroupId = ?, genderId = ?, salutationId = ?, instituteId = ?, umisId = ?, nameAsOnCertificate = ?, isFirstGraduateVerifiedbyUniversity = ?, isFirstGraduateVerifiedbyHod = ?, mobileNumber = ?, emailId = ?, permAddress = ?, entry_date = ? WHERE s1_unique_id = ?";
            $stmt = $mysqli->prepare($update_sql_1);
            $stmt->bind_param("sssssssssssssssssssssssssss", $columns_1['umis_no'], $columns_1['name'], $columns_1['emsid'], $columns_1['dateOfBirth'], $columns_1['nationalityId'], $columns_1['religionId'], $columns_1['communityId'], $columns_1['casteId'], $columns_1['isFirstGraduate'], $columns_1['isDifferentlyAbled'], $columns_1['isSpecialCategory'], $columns_1['udid'], $columns_1['disabilityId'], $columns_1['extentOfDisability'], $columns_1['bloodGroupId'], $columns_1['genderId'], $columns_1['salutationId'], $columns_1['instituteId'], $columns_1['umisId'], $columns_1['nameAsOnCertificate'], $columns_1['isFirstGraduateVerifiedbyUniversity'], $columns_1['isFirstGraduateVerifiedbyHod'], $columns_1['mobileNumber'], $columns_1['emailId'], $columns_1['permAddress'], $columns_1['entry_date'], $s1_unique_id);
            $status_1 = $stmt->execute();
        } else {

            $insert_sql_1 = "INSERT INTO umis_1 (umis_no, name, emsid, dateOfBirth, nationalityId, religionId, communityId, casteId, isFirstGraduate, isDifferentlyAbled, isSpecialCategory, udid, disabilityId, extentOfDisability, bloodGroupId, genderId, salutationId, instituteId, umisId, nameAsOnCertificate, isFirstGraduateVerifiedbyUniversity, isFirstGraduateVerifiedbyHod, mobileNumber, emailId, permAddress, s1_unique_id, entry_date, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($insert_sql_1);
            $stmt->bind_param("ssssssssssssssssssssssssssss", $columns_1['umis_no'], $columns_1['name'], $columns_1['emsid'], $columns_1['dateOfBirth'], $columns_1['nationalityId'], $columns_1['religionId'], $columns_1['communityId'], $columns_1['casteId'], $columns_1['isFirstGraduate'], $columns_1['isDifferentlyAbled'], $columns_1['isSpecialCategory'], $columns_1['udid'], $columns_1['disabilityId'], $columns_1['extentOfDisability'], $columns_1['bloodGroupId'], $columns_1['genderId'], $columns_1['salutationId'], $columns_1['instituteId'], $columns_1['umisId'], $columns_1['nameAsOnCertificate'], $columns_1['isFirstGraduateVerifiedbyUniversity'], $columns_1['isFirstGraduateVerifiedbyHod'], $columns_1['mobileNumber'], $columns_1['emailId'], $columns_1['permAddress'], $columns_1['s1_unique_id'], $columns_1['entry_date'], $columns_1['unique_id']);
            $status_1 = $stmt->execute();

        }

        // Repeat the same for umis_2
        $columns_2 = [
            "umis_no" => base64_decode($_POST['umis_no']),
            "countryId" => base64_decode($_POST['countryId']),
            "stateId" => base64_decode($_POST['stateId']),
            "districtId" => base64_decode($_POST['districtId']),
            "zoneId" => base64_decode($_POST['zoneId']),
            "blockId" => base64_decode($_POST['blockId']),
            "caCountryId" => base64_decode($_POST['caCountryId']),
            "caStateId" => base64_decode($_POST['caStateId']),
            "caDistrictId" => base64_decode($_POST['caDistrictId']),
            "caZoneId" => base64_decode($_POST['caZoneId']),
            "caCorporationId" => base64_decode($_POST['caCorporationId']),
            "caAddress" => base64_decode($_POST['caAddress']),
            "caBlockId" => base64_decode($_POST['caBlockId']),
            "caVillagePanchayatId" => base64_decode($_POST['caVillagePanchayatId']),
            "caWardId" => base64_decode($_POST['caWardId']),
            "caTalukId" => base64_decode($_POST['caTalukId']),
            "caVillageId" => base64_decode($_POST['caVillageId']),
            "talukId" => base64_decode($_POST['talukId']),
            "villageId" => base64_decode($_POST['villageId']),
            "wardId" => base64_decode($_POST['wardId']),
            "corporationId" => base64_decode($_POST['corporationId']),
            "villagePanchayatId" => base64_decode($_POST['villagePanchayatId']),
            "courseId" => base64_decode($_POST['courseId']),
            "courseSpecializationId" => base64_decode($_POST['courseSpecializationId']),
            "dateOfAdmission" => base64_decode($_POST['dateOfAdmission']),
            "academicYearId" => base64_decode($_POST['academicYearId']),
            "s1_unique_id" => $s1_unique_id,
            "entry_date" => date('Y-m-d'),
            "unique_id" => unique_id($prefix) // Replace with your unique ID generation logic
        ];

        $select_sql_2 = "SELECT COUNT(unique_id) AS count FROM umis_2 WHERE is_delete = 0 AND s1_unique_id = ?";

        $stmt = $mysqli->prepare($select_sql_2);

        $stmt->bind_param("s", $s1_unique_id);

        $stmt->execute();

        $result = $stmt->get_result();
        $data_2 = $result->fetch_assoc();

        if ($data_2['count'] > 0) {

            $update_sql_2 = "UPDATE umis_2 SET umis_no = ?, countryId = ?, stateId = ?, districtId = ?, zoneId = ?, blockId = ?, caCountryId = ?, caStateId = ?, caDistrictId = ?, caZoneId = ?, caCorporationId = ?, caAddress = ?, caBlockId = ?, caVillagePanchayatId = ?, caWardId = ?, caTalukId = ?, caVillageId = ?, talukId = ?, villageId = ?, wardId = ?, corporationId = ?, villagePanchayatId = ?, courseId = ?, courseSpecializationId = ?, dateOfAdmission = ?, academicYearId = ?, entry_date = ? WHERE s1_unique_id = ?";
            $stmt = $mysqli->prepare($update_sql_2);
            $stmt->bind_param("ssssssssssssssssssssssssssss", $columns_2['umis_no'], $columns_2['countryId'], $columns_2['stateId'], $columns_2['districtId'], $columns_2['zoneId'], $columns_2['blockId'], $columns_2['caCountryId'], $columns_2['caStateId'], $columns_2['caDistrictId'], $columns_2['caZoneId'], $columns_2['caCorporationId'], $columns_2['caAddress'], $columns_2['caBlockId'], $columns_2['caVillagePanchayatId'], $columns_2['caWardId'], $columns_2['caTalukId'], $columns_2['caVillageId'], $columns_2['talukId'], $columns_2['villageId'], $columns_2['wardId'], $columns_2['corporationId'], $columns_2['villagePanchayatId'], $columns_2['courseId'], $columns_2['courseSpecializationId'], $columns_2['dateOfAdmission'], $columns_2['academicYearId'], $columns_2['entry_date'], $s1_unique_id);
            $status_2 = $stmt->execute();
        } else {
            $insert_sql_2 = "INSERT INTO umis_2 (umis_no, countryId, stateId, districtId, zoneId, blockId, caCountryId, caStateId, caDistrictId, caZoneId, caCorporationId, caAddress, caBlockId, caVillagePanchayatId, caWardId, caTalukId, caVillageId, talukId, villageId, wardId, corporationId, villagePanchayatId, courseId, courseSpecializationId, dateOfAdmission, academicYearId, s1_unique_id, entry_date, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($insert_sql_2);

            $stmt->bind_param("sssssssssssssssssssssssssssss", $columns_2['umis_no'], $columns_2['countryId'], $columns_2['stateId'], $columns_2['districtId'], $columns_2['zoneId'], $columns_2['blockId'], $columns_2['caCountryId'], $columns_2['caStateId'], $columns_2['caDistrictId'], $columns_2['caZoneId'], $columns_2['caCorporationId'], $columns_2['caAddress'], $columns_2['caBlockId'], $columns_2['caVillagePanchayatId'], $columns_2['caWardId'], $columns_2['caTalukId'], $columns_2['caVillageId'], $columns_2['talukId'], $columns_2['villageId'], $columns_2['wardId'], $columns_2['corporationId'], $columns_2['villagePanchayatId'], $columns_2['courseId'], $columns_2['courseSpecializationId'], $columns_2['dateOfAdmission'], $columns_2['academicYearId'], $columns_2['s1_unique_id'], $columns_2['entry_date'], $columns_2['unique_id']);
            $status_2 = $stmt->execute();

        }

        // Repeat the same for umis_3
        $columns_3 = [
            "umis_no" => base64_decode($_POST['umis_no']),
            "courseType" => base64_decode($_POST['courseType']),
            "streamInfoId" => base64_decode($_POST['streamInfoId']),
            "mediumOfInstructionType" => base64_decode($_POST['mediumOfInstructionType']),
            "academicStatusType" => base64_decode($_POST['academicStatusType']),
            "yearOfStudy" => base64_decode($_POST['yearOfStudy']),
            "isLateralEntry" => base64_decode($_POST['isLateralEntry']),
            "isHosteler" => base64_decode($_POST['isHosteler']),
            "hostelAdmissionDate" => base64_decode($_POST['hostelAdmissionDate']),
            "leavingFromHostelDate" => base64_decode($_POST['leavingFromHostelDate']),
            "studentId" => base64_decode($_POST['studentId']),
            "parentMobileNo" => base64_decode($_POST['parentMobileNo']),
            "fatherOccupationId" => base64_decode($_POST['fatherOccupationId']),
            "motherOccupationId" => base64_decode($_POST['motherOccupationId']),
            "guardianOccupationId" => base64_decode($_POST['guardianOccupationId']),
            "aisheId" => base64_decode($_POST['aisheId']),
            "instituteName" => base64_decode($_POST['instituteName']),
            "instituteTypeId" => base64_decode($_POST['instituteTypeId']),
            "instituteOwnershipId" => base64_decode($_POST['instituteOwnershipId']),
            "instituteCategoryId" => base64_decode($_POST['instituteCategoryId']),
            "instituteStatusType" => base64_decode($_POST['instituteStatusType']),
            "universityName" => base64_decode($_POST['universityName']),
            "universityTypeId" => base64_decode($_POST['universityTypeId']),
            "hodName" => base64_decode($_POST['hodName']),
            "departmentName" => base64_decode($_POST['departmentName']),
            "s1_unique_id" => $s1_unique_id,
            "entry_date" => date('Y-m-d'),
            "unique_id" => unique_id($prefix) // Replace with your unique ID generation logic
        ];

        $select_sql_3 = "SELECT COUNT(unique_id) AS count FROM umis_3 WHERE is_delete = 0 AND s1_unique_id = ?";
        $stmt = $mysqli->prepare($select_sql_3);
        $stmt->bind_param("s", $s1_unique_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data_3 = $result->fetch_assoc();

        if ($data_3['count'] > 0) {

            $update_sql_3 = "UPDATE umis_3 SET umis_no = ?, courseType = ?, streamInfoId = ?, mediumOfInstructionType = ?, academicStatusType = ?, yearOfStudy = ?, isLateralEntry = ?, isHosteler = ?, hostelAdmissionDate = ?, leavingFromHostelDate = ?, studentId = ?, parentMobileNo = ?, fatherOccupationId = ?, motherOccupationId = ?, guardianOccupationId = ?, aisheId = ?, instituteName = ?, instituteTypeId = ?, instituteOwnershipId = ?, instituteCategoryId = ?, instituteStatusType = ?, universityName = ?, universityTypeId = ?, hodName = ?, departmentName = ?, entry_date = ? WHERE s1_unique_id = ?";
            $stmt = $mysqli->prepare($update_sql_3);
            $stmt->bind_param("sssssssssssssssssssssssssss", $columns_3['umis_no'], $columns_3['courseType'], $columns_3['streamInfoId'], $columns_3['mediumOfInstructionType'], $columns_3['academicStatusType'], $columns_3['yearOfStudy'], $columns_3['isLateralEntry'], $columns_3['isHosteler'], $columns_3['hostelAdmissionDate'], $columns_3['leavingFromHostelDate'], $columns_3['studentId'], $columns_3['parentMobileNo'], $columns_3['fatherOccupationId'], $columns_3['motherOccupationId'], $columns_3['guardianOccupationId'], $columns_3['aisheId'], $columns_3['instituteName'], $columns_3['instituteTypeId'], $columns_3['instituteOwnershipId'], $columns_3['instituteCategoryId'], $columns_3['instituteStatusType'], $columns_3['universityName'], $columns_3['universityTypeId'], $columns_3['hodName'], $columns_3['departmentName'], $columns_3['entry_date'], $s1_unique_id);
            $status_3 = $stmt->execute();
        } else {

            $insert_sql_3 = "INSERT INTO umis_3 (umis_no, courseType, streamInfoId, mediumOfInstructionType, academicStatusType, yearOfStudy, isLateralEntry, isHosteler, hostelAdmissionDate, leavingFromHostelDate, studentId, parentMobileNo, fatherOccupationId, motherOccupationId, guardianOccupationId, aisheId, instituteName, instituteTypeId, instituteOwnershipId, instituteCategoryId, instituteStatusType, universityName, universityTypeId, hodName, departmentName, s1_unique_id, entry_date, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($insert_sql_3);
            $stmt->bind_param("ssssssssssssssssssssssssssss", $columns_3['umis_no'], $columns_3['courseType'], $columns_3['streamInfoId'], $columns_3['mediumOfInstructionType'], $columns_3['academicStatusType'], $columns_3['yearOfStudy'], $columns_3['isLateralEntry'], $columns_3['isHosteler'], $columns_3['hostelAdmissionDate'], $columns_3['leavingFromHostelDate'], $columns_3['studentId'], $columns_3['parentMobileNo'], $columns_3['fatherOccupationId'], $columns_3['motherOccupationId'], $columns_3['guardianOccupationId'], $columns_3['aisheId'], $columns_3['instituteName'], $columns_3['instituteTypeId'], $columns_3['instituteOwnershipId'], $columns_3['instituteCategoryId'], $columns_3['instituteStatusType'], $columns_3['universityName'], $columns_3['universityTypeId'], $columns_3['hodName'], $columns_3['departmentName'], $columns_3['s1_unique_id'], $columns_3['entry_date'], $columns_3['unique_id']);
            $status_3 = $stmt->execute();
        }

        if ($status_1 && $status_2 && $status_3) {
            $json_array = [
                "status" => "success",
                "data" => null,
                "error" => null,
                "msg" => "Data successfully inserted/updated"
            ];
        } else {
            $json_array = [
                "status" => "error",
                "data" => null,
                "error" => "An error occurred while processing the request",
                "msg" => null
            ];
        }

        echo json_encode($json_array);
        break;


    case 'a_create':


        $encoded_s1_unique_id = $_POST["s1_unique_id"];
        $encoded_uuid = $_POST["uuid"];
        $encoded_dob = $_POST["dob"];
        $encoded_gender = $_POST["gender"];
        $encoded_name = $_POST["name"];
        $encoded_pc = $_POST["pc"];
        $encoded_fatherName = $_POST["fatherName"];
        $encoded_address = $_POST["address"];
        $encoded_aadhar_no = $_POST["aadhar_no"];
        $encoded_addressLatLong = $_POST['addressLatLong'];



        $s1_unique_id = $encoded_s1_unique_id;
        $uuid = $encoded_uuid;
        $dob = $encoded_dob;
        $gender = $encoded_gender;
        $name = $encoded_name;
        $pc = $encoded_pc;
        $fatherName = $encoded_fatherName;
        $address = $encoded_address;
        $aadhar_no = base64_decode($encoded_aadhar_no);
        $addressLatLong = $encoded_addressLatLong;

        $pro_image = $_POST["pro_image"];




        $sql = "SELECT COUNT(unique_id) AS count FROM $table_a WHERE uuid = ?";
        $params = [$uuid];

        if ($unique_id) {
            $sql .= " AND unique_id != ?";
            $params[] = $unique_id;
        }

        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            die('MySQL prepare error: ' . $mysqli->error);
        }

        // Bind parameters
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);

        // Execute statement
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch count
        $row = $result->fetch_assoc();
        $count = $row['count'];

        // Close statement
        $stmt->close();

        // Check if record already exists
        if ($count > 0) {
            $msg = "already";
        } else {
            $unique_id = unique_id($prefix);
            // Prepare insert query
            $sql = "INSERT INTO $table_a (s1_unique_id, uuid, adob, agender, aname, aaddress, apincode, afatherName, pro_image, entry_date, unique_id, temp_aadhar) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $params = [
                $s1_unique_id,
                $uuid,
                $dob,
                $gender,
                $name,
                $address,
                $pc,
                $fatherName,
                $pro_image,
                date('Y-m-d'),
                $unique_id,
                $aadhar_no
            ];

            // Prepare and execute insert statement
            $stmt = $mysqli->prepare($sql);
            if ($stmt === false) {
                die('MySQL prepare error: ' . $mysqli->error);
            }

            // Bind parameters
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);

            // Execute statement
            $stmt->execute();

            // Check for success
            if ($stmt->affected_rows > 0) {
                $status = true;
                $msg = "otp"; // Message for successful insertion
            } else {
                $error = $stmt->error;
                $msg = "error"; // Message for insertion failure
            }

            // Close statement
            $stmt->close();

            $googleApiKey = 'AIzaSyAmP90skchAtlESn2MoO6vkBOMKKFpwtI0';
            $apiUrl = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($addressLatLong) . "&key=" . $googleApiKey;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $apiResponse = curl_exec($ch);
            curl_close($ch);

            $lat = null;
            $lng = null;
            $apiData = json_decode($apiResponse, true);
            if (!empty($apiData['results'][0]['geometry']['location'])) {
                $lat = $apiData['results'][0]['geometry']['location']['lat'];
                $lng = $apiData['results'][0]['geometry']['location']['lng'];

                // Update latitude and longitude in DB
                $updateSql = "UPDATE $table_a SET latitude = ?, longitude = ? WHERE s1_unique_id = ?";
                $stmt = $mysqli->prepare($updateSql);
                if ($stmt) {
                    $stmt->bind_param("dds", $lat, $lng, $s1_unique_id);
                    $stmt->execute();
                    $stmt->close();
                }
            }

        }

        // Prepare JSON response
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            "unique_id" => $unique_id
        ];

        echo json_encode($json_array);

        break;

    case 'calculate_distances':

        $s1_unique_id = $_POST['s1_unique_id'];
        $google_api_key = 'AIzaSyAmP90skchAtlESn2MoO6vkBOMKKFpwtI0';

        $conn = mysqli_connect("localhost", "root", "4/rb5sO2s3TpL4gu", "adi_dravidar");
        if (!$conn) {
            echo json_encode(["status" => false, "msg" => "Database connection failed"]);
            exit;
        }

        // 1. Get student_type and hostel_1
        $q1 = "SELECT student_type, hostel_1 FROM std_app_s WHERE unique_id = '$s1_unique_id'";
        $res1 = mysqli_query($conn, $q1);
        $data1 = mysqli_fetch_assoc($res1);

        if (!$data1) {
            echo json_encode(["status" => false, "msg" => "Student not found"]);
            exit;
        }

        $student_type = $data1['student_type'];
        $hostel_1 = $data1['hostel_1'];

        // 2. Get either emis_no or umis_no
        if ($student_type == '65f00a259436412348') {
            $q2 = "SELECT emis_no FROM std_app_emis_s3 WHERE s1_unique_id = '$s1_unique_id'";
            $res2 = mysqli_query($conn, $q2);
            $emis = mysqli_fetch_assoc($res2);
            $emis_id = $emis['emis_no'];

            // EMIS API Call
            $authorization_token = '4acdca2cc493c1ec28e1f68e0d37c49a';
            $emis_data = ['EmisId' => $emis_id];
            $emis_ch = curl_init('https://tnega.tnschools.gov.in/tnega/api/GetSchlDetails');
            curl_setopt($emis_ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($emis_ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($emis_ch, CURLOPT_POSTFIELDS, json_encode($emis_data));
            curl_setopt($emis_ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: ' . $authorization_token
            ]);
            $emis_response = curl_exec($emis_ch);
            curl_close($emis_ch);

            $emis_json = json_decode($emis_response, true);
            // Check if result exists and is an array with at least one item
            if (!empty($emis_json['result'][0]['udise_code'])) {
                $udise_code = $emis_json['result'][0]['udise_code'];
            } else {
                $udise_code = null; // or handle the error
            }
            $q3 = "SELECT latitude, longitude FROM scl_lat_long WHERE udise_code = '$udise_code'";
        } else {
            $q2 = "SELECT umis_no FROM std_app_umis_s4 WHERE s1_unique_id = '$s1_unique_id'";
            $res2 = mysqli_query($conn, $q2);
            $umis = mysqli_fetch_assoc($res2);

            if (!$umis || !$umis['umis_no']) {
                echo json_encode(["status" => false, "msg" => "UMIS number not available"]);
                exit;
            }

            $umis_no = $umis['umis_no'];

            // UMIS API Call
            $authorization_token_umis = '4acdca2cc493c1ec28e1f68e0d37c49a';
            $umis_ch = curl_init("https://umisapi.tnega.org/api/ADWD/GetStudentData/$umis_no");
            curl_setopt($umis_ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($umis_ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($umis_ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: ' . $authorization_token_umis
            ]);
            $umis_response = curl_exec($umis_ch);
            curl_close($umis_ch);

            $umis_json = json_decode($umis_response, true);
            $instituteId = isset($umis_json['instituteId']) ? $umis_json['instituteId'] : null;

            $q3 = "SELECT latitude, longitude FROM clg_lat_long WHERE instituteId = '$instituteId'";
        }

        // 3. Get institution lat/long
        $res3 = mysqli_query($conn, $q3);
        $inst_data = mysqli_fetch_assoc($res3);
        $inst_lat = $inst_data['latitude'] ?? null;
        $inst_long = $inst_data['longitude'] ?? null;

        // 4. Get student lat/long
        $q4 = "SELECT latitude, longitude FROM aadhar WHERE s1_unique_id = '$s1_unique_id'";
        $res4 = mysqli_query($conn, $q4);
        $stu_data = mysqli_fetch_assoc($res4);
        $stu_lat = $stu_data['latitude'] ?? null;
        $stu_long = $stu_data['longitude'] ?? null;

        // 5. Get hostel lat/long
        $q5 = "SELECT latitude, longitude FROM hostel_name WHERE unique_id = '$hostel_1'";
        $res5 = mysqli_query($conn, $q5);
        $hostel_data = mysqli_fetch_assoc($res5);
        $hostel_lat = $hostel_data['latitude'] ?? null;
        $hostel_long = $hostel_data['longitude'] ?? null;

        // Helper to get distance using Google Distance Matrix API
        function get_distance($from_lat, $from_long, $to_lat, $to_long, $key)
        {
            if (!$from_lat || !$to_lat)
                return null;

            $from = "$from_lat,$from_long";
            $to = "$to_lat,$to_long";

            $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$from&destinations=$to&key=$key";

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

        // 6. Calculate distances
        $std_to_inst = get_distance($stu_lat, $stu_long, $inst_lat, $inst_long, $google_api_key);
        $std_to_hostel = get_distance($stu_lat, $stu_long, $hostel_lat, $hostel_long, $google_api_key);
        $inst_to_hostel = get_distance($inst_lat, $inst_long, $hostel_lat, $hostel_long, $google_api_key);

        // 7. Update std_app_s
        $upd = "UPDATE std_app_s SET 
                std_to_inst_distance = " . ($std_to_inst !== null ? "'$std_to_inst'" : "NULL") . ",
                std_to_hostel_distance = " . ($std_to_hostel !== null ? "'$std_to_hostel'" : "NULL") . ",
                inst_to_hostel_distance = " . ($inst_to_hostel !== null ? "'$inst_to_hostel'" : "NULL") . "
            WHERE unique_id = '$s1_unique_id'";

        $res_upd = mysqli_query($conn, $upd);

        echo json_encode([
            "status" => true,
            "msg" => "Distance calculated",
            "student_to_inst" => $std_to_inst,
            "student_to_hostel" => $std_to_hostel,
            "inst_to_hostel" => $inst_to_hostel,
        ]);

        break;



    case 'a_ref_create':


        $encoded_s1_unique_id = $_POST["s1_unique_id"];
        $encoded_uuid = $_POST["uuid"];
        $encoded_dob = $_POST["dob"];
        $encoded_gender = $_POST["gender"];
        $encoded_name = $_POST["name"];
        $encoded_txn = $_POST["txn"];
        $encoded_rrn = $_POST["rrn"];

        $s1_unique_id = base256_decode($encoded_s1_unique_id);
        $uuid = base256_decode($encoded_uuid);
        $dob = base256_decode($encoded_dob);
        $gender = base256_decode($encoded_gender);
        $name = base256_decode($encoded_name);
        $txn = base256_decode($encoded_txn);
        $rrn = base256_decode($encoded_rrn);

        $sql = "SELECT COUNT(unique_id) AS count FROM $table_a_ref WHERE uuid = ?";
        $params = [$uuid];

        if ($unique_id) {
            $sql .= " AND unique_id != ?";
            $params[] = $unique_id;
        }

        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            die('MySQL prepare error: ' . $mysqli->error);
        }

        // Bind parameters
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);

        // Execute statement
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch count
        $row = $result->fetch_assoc();
        $count = $row['count'];

        // Close statement
        $stmt->close();

        // Check if record already exists
        if ($count > 0) {
            $msg = "already";
        } else {
            $unique_id = unique_id($prefix);
            // Prepare insert query
            $sql = "INSERT INTO $table_a_ref (s1_unique_id, uuid, adob, agender, aname, txn, rrn, entry_date, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $params = [
                $s1_unique_id,
                $uuid,
                $dob,
                $gender,
                $name,
                $txn,
                $rrn,
                date('Y-m-d'),
                $unique_id
            ];

            // Prepare and execute insert statement
            $stmt = $mysqli->prepare($sql);
            if ($stmt === false) {
                die('MySQL prepare error: ' . $mysqli->error);
            }

            // Bind parameters
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);

            // Execute statement
            $stmt->execute();

            // Check for success
            if ($stmt->affected_rows > 0) {
                $status = true;
                $msg = "otp"; // Message for successful insertion
            } else {
                $error = $stmt->error;
                $msg = "error"; // Message for insertion failure
            }

            // Close statement
            $stmt->close();
        }

        // Prepare JSON response
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            "unique_id" => $unique_id
        ];

        echo json_encode($json_array);

        break;


    case 'insert_emis':
        $emis_id = $_POST["emis_id"];
        // $unique_id = unique_id($prefix);

        // Assuming your unique_id() function is defined correctly


        // API endpoint
        $url = 'https://tnega.tnschools.gov.in/tnega/api/GetSchlDetails';

        // Data to send in the request
        $data = array(
            'EmisId' => $emis_id
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


    case 'insert_umis':
        $umis_no = $_POST["umis_no"];
        // $unique_id = unique_id($prefix);

        // Assuming your unique_id() function is defined correctly


        // API endpoint
        //$url = 'https://umis.tn.gov.in/api/api/ADWD/GetStudentData/9000000207';
        // $url = 'https://umis.tn.gov.in/api/UMIS/ADW/GetStudentData/' . $umis_no;
        $url = 'https://umisapi.tnega.org/api/ADWD/GetStudentData/' . $umis_no;



        // Data to send in the request
        $data = array(
            'umis_id' => $umis_no
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
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
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

    case 'insert_community':
        $communityno = $_POST["communityno"];
        $source = "AD welfare";
        $service_code = "REV-101";

        // API endpoint
        $url = 'https://tnedistrict.tn.gov.in/eda/getEsevaiResponse';

        // Create a SimpleXMLElement object
        $request = new SimpleXMLElement('<REQUEST></REQUEST>');

        // Add data to the XML
        $request->addChild('SOURCE', $source);
        $request->addChild('CERTIFICATENO', $communityno);
        $request->addChild('SERVICECODE', $service_code);

        // Convert SimpleXMLElement object to XML string
        $data_xml = $request->asXML();

        // Set headers
        $headers = array(
            'Content-Type: application/xml',
        );

        // Initialize cURL
        $ch = curl_init($url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_xml); // Send XML data
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'DEFAULT:!DH');
        curl_setopt($ch, CURLOPT_SSL_OPTIONS, CURLSSLOPT_ALLOW_BEAST);
        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        curl_setopt($ch, CURLOPT_SSL_ENABLE_ALPN, false);

        // Execute cURL request
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }

        // Close cURL session
        curl_close($ch);

        // Prepare response
        $json_array = [
            "status" => ($response !== false),
            "data" => $response,
            "error" => isset($error) ? $error : "Data is not available!..",
            "msg" => $msg,
        ];

        echo json_encode($json_array);

        break;


    case 'insert_income':
        $incomecerno = $_POST["incomecerno"];
        $source = "AD welfare";
        $service_code = "REV-103";
        // $unique_id = unique_id($prefix);

        // Assuming your unique_id() function is defined correctly


        // API endpoint
        $url = 'https://tnedistrict.tn.gov.in/eda/getEsevaiResponse';

        // Create a SimpleXMLElement object
        $request = new SimpleXMLElement('<REQUEST></REQUEST>');

        // Add data to the XML
        $request->addChild('SOURCE', $source);
        $request->addChild('CERTIFICATENO', $incomecerno);
        $request->addChild('SERVICECODE', $service_code);

        // Convert SimpleXMLElement object to XML string
        $data_xml = $request->asXML();

        // Convert data array to JSON
        // $data_json = json_encode($data);

        // Authorization token
        //$authorization_token = '4acdca2cc493c1ec28e1f68e0d37c49a';

        // Set headers
        $headers = array(
            'Content-Type: application/xml',
        );

        // Initialize cURL
        $ch = curl_init($url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_xml); // Send XML data
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

        // json_decode($response)
        // Prepare response
        $json_array = [
            "status" => ($response !== false),
            "data" => $response,
            "error" => isset($error) ? $error : "Data is not available!..",
            "msg" => $msg,
            // "sql" => $sql
        ];

        echo json_encode($json_array);

        break;




    case 'certificateinfocreat':
        $table = 'std_app_s5';

        $communityno = !empty($_POST['communityno']) ? base64_decode($_POST['communityno']) : null;
        $fullname1 = !empty($_POST['fullname1']) ? base64_decode($_POST['fullname1']) : null;
        $castename = !empty($_POST['castename']) ? base64_decode($_POST['castename']) : null;
        $subcastename = !empty($_POST['subcastename']) ? base64_decode($_POST['subcastename']) : null;
        $fathername3 = !empty($_POST['fathername3']) ? base64_decode($_POST['fathername3']) : null;
        $mothername3 = !empty($_POST['mothername3']) ? base64_decode($_POST['mothername3']) : null;
        $incomecerno = !empty($_POST['incomecerno']) ? base64_decode($_POST['incomecerno']) : null;
        $fullname4 = !empty($_POST['fullname4']) ? base64_decode($_POST['fullname4']) : null;
        $incomelevel = !empty($_POST['incomelevel']) ? base64_decode($_POST['incomelevel']) : null;
        $fathername4 = !empty($_POST['fathername4']) ? base64_decode($_POST['fathername4']) : null;
        $mothername4 = !empty($_POST['mothername4']) ? base64_decode($_POST['mothername4']) : null;
        $fatherincomesource = !empty($_POST['fatherincomesource']) ? base64_decode($_POST['fatherincomesource']) : null;
        $motherincomesource = !empty($_POST['motherincomesource']) ? base64_decode($_POST['motherincomesource']) : null;
        $diffabled = !empty($_POST['diffabled']) ? base64_decode($_POST['diffabled']) : null;
        $category = !empty($_POST['category']) ? base64_decode($_POST['category']) : null;
        $idnumber = !empty($_POST['idnumber']) ? base64_decode($_POST['idnumber']) : null;
        $income_pdf = !empty($_POST['income_pdf']) ? base64_decode($_POST['income_pdf']) : null;
        $community_pdf = !empty($_POST['community_pdf']) ? base64_decode($_POST['community_pdf']) : null;
        $disabilitypercentage = !empty($_POST['disabilitypercentage']) ? base64_decode($_POST['disabilitypercentage']) : null;
        $s1_unique_id = !empty($_POST['s1_unique_id']) ? base64_decode($_POST['s1_unique_id']) : null;
        $com_name = !empty($_POST['com_name']) ? base64_decode($_POST['com_name']) : null;
        $cert_detail = !empty($_POST['cert_detail']) ? base64_decode($_POST['cert_detail']) : null;
        $input_status = !empty($_POST['input_status']) ? $_POST['input_status'] : '0';


        $allowedExts = array("image");
        $file_names = $file_org_names = $file_names1 = $file_org_names1 = $file_names2 = $file_org_names2 = null;

        // Upload community certificate
        if (!empty($_FILES["communitycer"]['name'])) {
            $file_exp = explode(".", $_FILES["communitycer"]['name']);
            $tem_name = random_strings(25) . "." . end($file_exp);
            move_uploaded_file($_FILES["communitycer"]["tmp_name"], "../../../uploads/" . $tem_name);
            $file_names = $tem_name;
            $file_org_names = $_FILES["communitycer"]['name'];
        }


        // Upload community certificate
        if (!empty($_FILES["incomecer"]['name'])) {
            $file_exp = explode(".", $_FILES["incomecer"]['name']);
            $tem_name = random_strings(25) . "." . end($file_exp);
            move_uploaded_file($_FILES["incomecer"]["tmp_name"], "../../../uploads/" . $tem_name);
            $file_names1 = $tem_name;
            $file_org_names1 = $_FILES["incomecer"]['name'];
        }



        // Upload disability certificate
        if (!empty($_FILES["disabilitycertificate"]['name'])) {
            $file_exp2 = explode(".", $_FILES["disabilitycertificate"]['name']);
            $tem_name2 = random_strings(25) . "." . end($file_exp2);
            move_uploaded_file($_FILES["disabilitycertificate"]["tmp_name"], "../../../uploads/" . $tem_name2);
            $file_names2 = $tem_name2;
            $file_org_names2 = $_FILES["disabilitycertificate"]['name'];
        }

        // Check if record exists
        $stmt = $mysqli->prepare("SELECT COUNT(unique_id) AS count FROM $table WHERE is_delete = 0 AND s1_unique_id = ?");
        $stmt->bind_param("s", $s1_unique_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        if ($data['count'] > 0) {
            // Update existing record
            $stmt = $mysqli->prepare("UPDATE std_app_s5 SET 
                    c_no = ?, c_name = ?, caste_name = ?, sub_caste_name = ?, c_father_name = ?, c_file_name = ?, c_file_org_name = ?, 
                    i_no = ?, i_name = ?, income_level = ?, i_father_name = ?, i_mother_name = ?, f_income_source = ?, m_income_source = ?, 
                    i_file_name = ?, i_file_org_name = ?, diffabled = ?, com_name = ?, cert_detail = ?, category = ?, idnumber = ?, 
                    income_pdf = ?, community_pdf = ?, disability_percent = ?, p_file_name = ?, p_file_org_name = ?, input_status = ?, entry_date = ?, unique_id = ?
                    WHERE s1_unique_id = ?");
            $stmt->bind_param(
                "ssssssssssssssssssssssssssssss",
                $communityno,
                $fullname1,
                $castename,
                $subcastename,
                $fathername3,
                $file_names,
                $file_org_names,
                $incomecerno,
                $fullname4,
                $incomelevel,
                $fathername4,
                $mothername4,
                $fatherincomesource,
                $motherincomesource,
                $file_names1,
                $file_org_names1,
                $diffabled,
                $com_name,
                $cert_detail,
                $category,
                $idnumber,
                $income_pdf,
                $community_pdf,
                $disabilitypercentage,
                $file_names2,
                $file_org_names2,
                $input_status,
                date('Y-m-d'),
                unique_id($prefix),
                $s1_unique_id
            );
        } else {
            // Insert new record

            $stmt = $mysqli->prepare("INSERT INTO std_app_s5 (
                    s1_unique_id, c_no, c_name, caste_name, sub_caste_name, c_father_name, c_file_name, c_file_org_name, 
                    i_no, i_name, income_level, i_father_name, i_mother_name, f_income_source, m_income_source, 
                    i_file_name, i_file_org_name, diffabled, com_name, cert_detail, category, idnumber, 
                    income_pdf, community_pdf, disability_percent, p_file_name, p_file_org_name, input_status, entry_date, unique_id
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->bind_param(
                "ssssssssssssssssssssssssssssss",
                $s1_unique_id,
                $communityno,
                $fullname1,
                $castename,
                $subcastename,
                $fathername3,
                $file_names,
                $file_org_names,
                $incomecerno,
                $fullname4,
                $incomelevel,
                $fathername4,
                $mothername4,
                $fatherincomesource,
                $motherincomesource,
                $file_names1,
                $file_org_names1,
                $diffabled,
                $com_name,
                $cert_detail,
                $category,
                $idnumber,
                $income_pdf,
                $community_pdf,
                $disabilitypercentage,
                $file_names2,
                $file_org_names2,
                $input_status,
                date('Y-m-d'),
                unique_id($prefix)
            );

        }

        $status = $stmt->execute();

        $error = $stmt->error;
        $stmt->close();

        $msg = $status ? ($data['count'] > 0 ? "update" : "save") : "error";

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
        ];

        echo json_encode($json_array);

        break;

    case 'familyinfos':
        $table = 'std_app_s6';

        $dob = base64_decode($_POST['dob']);
        $formatted_date = !empty($dob) ? date('Y-m-d', strtotime($dob)) : null;

        $age = base64_decode($_POST['age']);
        $bloodgroup = base64_decode($_POST['bloodgroup']);
        $mailid = base64_decode($_POST['mailid']);
        $religion = base64_decode($_POST['religion']);
        $mothertongue = base64_decode($_POST['mothertongue']);
        $aadharno = base64_decode($_POST['aadharno']);
        $refugee = base64_decode($_POST['refugee']);
        $orphan = base64_decode($_POST['orphan']);
        $singleparent = base64_decode($_POST['singleparent']);
        $firstgraduate = base64_decode($_POST['firstgraduate']);
        $dadname = base64_decode($_POST['dadname']);
        $momname = base64_decode($_POST['momname']);
        $dadqualification = base64_decode($_POST['dadqualification']);
        $momqualification = base64_decode($_POST['momqualification']);
        $dadOccupation = base64_decode($_POST['dadOccupation']);
        $momOccupation = base64_decode($_POST['momOccupation']);
        $dadmobno = base64_decode($_POST['dadmobno']);
        $guardianno = base64_decode($_POST['guardianno']);
        $door_no = base64_decode($_POST['door_no']);
        $block = base64_decode($_POST['taluk']);
        $District = base64_decode($_POST['District']);
        $Pincode = base64_decode($_POST['Pincode']);
        $street_name = base64_decode($_POST['street_name']);
        $area_name = base64_decode($_POST['area_name']);
        $s1_unique_id = base64_decode($_POST['s1_unique_id']);

        // Set empty values to NULL
        $dob = !empty($dob) ? $formatted_date : null;
        $age = !empty($age) ? $age : null;
        $bloodgroup = !empty($bloodgroup) ? $bloodgroup : null;
        $mailid = !empty($mailid) ? $mailid : null;
        $religion = !empty($religion) ? $religion : null;
        $mothertongue = !empty($mothertongue) ? $mothertongue : null;
        $aadharno = !empty($aadharno) ? $aadharno : null;
        $refugee = !empty($refugee) ? $refugee : null;
        $orphan = !empty($orphan) ? $orphan : null;
        $singleparent = !empty($singleparent) ? $singleparent : null;
        $firstgraduate = !empty($firstgraduate) ? $firstgraduate : null;
        $dadname = !empty($dadname) ? $dadname : null;
        $momname = !empty($momname) ? $momname : null;
        $dadqualification = !empty($dadqualification) ? $dadqualification : null;
        $momqualification = !empty($momqualification) ? $momqualification : null;
        $dadOccupation = !empty($dadOccupation) ? $dadOccupation : null;
        $momOccupation = !empty($momOccupation) ? $momOccupation : null;
        $dadmobno = !empty($dadmobno) ? $dadmobno : null;
        $guardianno = !empty($guardianno) ? $guardianno : null;
        $door_no = !empty($door_no) ? $door_no : null;
        $block = !empty($block) ? $block : null;
        $District = !empty($District) ? $District : null;
        $Pincode = !empty($Pincode) ? $Pincode : null;
        $street_name = !empty($street_name) ? $street_name : null;
        $area_name = !empty($area_name) ? $area_name : null;
        $s1_unique_id = !empty($s1_unique_id) ? $s1_unique_id : null;



        // Check if record exists
        $stmt = $mysqli->prepare("SELECT COUNT(unique_id) AS count FROM $table WHERE is_delete = 0 AND s1_unique_id = ?");
        $stmt->bind_param("s", $s1_unique_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        if ($data['count'] > 0) {
            // Update existing record
            $stmt = $mysqli->prepare("UPDATE $table SET 
                        dob = ?, age = ?, blood_group = ?, email_id = ?, religion = ?, mother_tongue = ?, refugee = ?, orphan = ?, single_parent = ?, first_graduate = ?, 
                        father_name = ?, mother_name = ?, father_qual = ?, mother_qual = ?, father_occu = ?, mother_occu = ?, father_no = ?, guardian_no = ?, 
                        door_no = ?, block = ?, district = ?, area_name = ?, street_name = ?, pincode = ?, entry_date = ? 
                        WHERE s1_unique_id = ?");
            $stmt->bind_param(
                "ssssssssssssssssssssssssss",
                $formatted_date,
                $age,
                $bloodgroup,
                $mailid,
                $religion,
                $mothertongue,
                $refugee,
                $orphan,
                $singleparent,
                $firstgraduate,
                $dadname,
                $momname,
                $dadqualification,
                $momqualification,
                $dadOccupation,
                $momOccupation,
                $dadmobno,
                $guardianno,
                $door_no,
                $block,
                $District,
                $area_name,
                $street_name,
                $Pincode,
                date('Y-m-d'),
                $s1_unique_id
            );
        } else {
            // Insert new record
            $stmt = $mysqli->prepare("INSERT INTO $table (
                        dob, age, blood_group, email_id, religion, mother_tongue, refugee, orphan, single_parent, first_graduate, father_name, mother_name, father_qual, mother_qual, 
                        father_occu, mother_occu, father_no, guardian_no, door_no, block, district, area_name, street_name, s1_unique_id, pincode, entry_date, unique_id
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param(
                "sssssssssssssssssssssssssss",
                $formatted_date,
                $age,
                $bloodgroup,
                $mailid,
                $religion,
                $mothertongue,
                $refugee,
                $orphan,
                $singleparent,
                $firstgraduate,
                $dadname,
                $momname,
                $dadqualification,
                $momqualification,
                $dadOccupation,
                $momOccupation,
                $dadmobno,
                $guardianno,
                $door_no,
                $block,
                $District,
                $area_name,
                $street_name,
                $s1_unique_id,
                $Pincode,
                date('Y-m-d'),
                unique_id($prefix)
            );
        }

        $status = $stmt->execute();
        $error = $stmt->error;
        $stmt->close();

        $msg = $status ? ($data['count'] > 0 ? "update" : "save") : "error";

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
        ];

        echo json_encode($json_array);

        break;

    case 'get_adjacent_district':
        $table = "district_name_test";

        $hostel_district = $_POST['hostel_district'];
        $emis_school_district = $_POST['emis_school_district'];
        $umis_district = $_POST['umis_district'];


        $json_array = "";
        $table_details = [
            $table,
            [

                "group_district_unique_id",
                "group_district",
                "district_name",
            ]

        ];

        $select_where = '';
        if ($hostel_district != '') {
            $select_where .= ' is_delete = 0 and unique_id = "' . $hostel_district . '" ';
        } else if ($emis_school_district) {
            $select_where .= ' is_delete = 0 and district_name LIKE "' . $emis_school_district . '" ';
        } else if ($umis_district) {
            $select_where .= ' is_delete = 0 and district_name LIKE "' . $umis_district . '" ';
        }
        // When Update Check without current id


        $action_obj = $pdo->select($table_details, $select_where);
        // print_r($action_obj);die();
        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;

        }

        $adjacent_district_id = $data[0]["group_district_unique_id"];
        $adjacent_district_name = $data[0]["group_district"];
        $district_name = $data[0]["district_name"];

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            "adjacent_district" => $adjacent_district_id,
            "adjacent_district_name" => $adjacent_district_name,
            "district_name" => $district_name,

            // "sql" => $sql
        ];

        echo json_encode($json_array);

        break;


    case 'aadhar_confirmation_add':


        $std_name = base64_decode($_POST["std_name"]);
        $std_dob = base64_decode($_POST["std_dob"]);
        $std_age = base64_decode($_POST["std_age"]);
        $std_gender = base64_decode($_POST["std_gender"]);
        $father_name = base64_decode($_POST["father_name"]);
        $std_address = base64_decode($_POST["std_address"]);
        $std_app_no = base64_decode($_POST["std_app_no"]);
        $student_status = base64_decode($_POST["student_status"]);
        $std_mobile_no = base64_decode($_POST["std_mobile_no"]);
        $s1_unique_id = base64_decode($_POST["s1_unique_id"]);
        $srilankan_id_no = base64_decode($_POST["srilankan_id_no"]);
        $unique_id = $_POST["unique_id"];

        if ($student_status == 1) {
            if (!empty($_FILES["std_image"]['name'])) {
                $file_exp = explode(".", $_FILES["std_image"]['name']);
                $tem_name = random_strings(25) . "." . end($file_exp);
                move_uploaded_file($_FILES["std_image"]["tmp_name"], "../../../uploads/" . $tem_name);
                $file_names = $tem_name;
                $file_org_names = $_FILES["std_image"]['name'];
            }
        }

        // Check if record already exists
        $sql_check = "SELECT COUNT(unique_id) AS count FROM $table_s2 WHERE is_delete = ? AND s1_unique_id = ?";
        $params_check = ['0', $s1_unique_id];

        if ($unique_id) {
            $sql_check .= " AND unique_id != ?";
            $params_check[] = $unique_id;
        }

        // Prepare and execute SELECT query
        $stmt_check = $mysqli->prepare($sql_check);
        if ($stmt_check === false) {
            die('MySQL prepare error: ' . $mysqli->error);
        }

        // Bind parameters for SELECT query
        $types_check = str_repeat('s', count($params_check));
        $stmt_check->bind_param($types_check, ...$params_check);

        // Execute SELECT statement
        $stmt_check->execute();

        // Bind result variables for SELECT query
        $stmt_check->bind_result($count);

        // Fetch result from SELECT query
        $stmt_check->fetch();

        // Close SELECT statement
        $stmt_check->close();

        // Determine action based on count
        if ($count > 0) {
            // Update existing record

            if ($file_names) {
                $sql_update = "UPDATE $table_s2 SET std_name=?, dob=?, age=?, gender=?, father_name=?, address=?, mobile_no=?, std_app_no=?, std_image=?, srilankan_id_no=? WHERE s1_unique_id=?";
                $params_update = [
                    $std_name,
                    $std_dob,
                    $std_age,
                    $std_gender,
                    $father_name,
                    $std_address,
                    $std_mobile_no,
                    $std_app_no,
                    $file_names,
                    $srilankan_id_no,
                    $s1_unique_id
                ];
            } else {
                $sql_update = "UPDATE $table_s2 SET std_name=?, dob=?, age=?, gender=?, father_name=?, address=?, mobile_no=?, std_app_no=?, srilankan_id_no=? WHERE s1_unique_id=?";
                $params_update = [
                    $std_name,
                    $std_dob,
                    $std_age,
                    $std_gender,
                    $father_name,
                    $std_address,
                    $std_mobile_no,
                    $std_app_no,
                    $srilankan_id_no,
                    $s1_unique_id
                ];
            }



            // Prepare and execute UPDATE query
            $stmt_update = $mysqli->prepare($sql_update);
            if ($stmt_update === false) {
                die('MySQL prepare error: ' . $mysqli->error);
            }

            // Bind parameters for UPDATE query
            $types_update = str_repeat('s', count($params_update));
            $stmt_update->bind_param($types_update, ...$params_update);

            // Execute UPDATE statement
            // $stmt_update->execute();

            // Check if UPDATE was successful
            if ($stmt_update->execute()) {
                $status = true;
                $msg = "update";
            } else {
                $status = false;
                $error = "Update operation failed.";
            }

            // Close UPDATE statement
            $stmt_update->close();



        } else {
            // Insert new record

            if (!$file_names) {
                $sql_insert = "INSERT INTO $table_s2 (std_name, dob, age, gender, father_name, address, mobile_no, std_app_no, s1_unique_id, entry_date, srilankan_id_no, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $params_insert = [
                    $std_name,
                    $std_dob,
                    $std_age,
                    $std_gender,
                    $father_name,
                    $std_address,
                    $std_mobile_no,
                    $std_app_no,
                    $s1_unique_id,
                    date('Y-m-d'),
                    $srilankan_id_no,
                    unique_id($prefix)
                ];
            } else {
                $sql_insert = "INSERT INTO $table_s2 (std_name, dob, age, gender, father_name, address, mobile_no, std_app_no, s1_unique_id, entry_date, unique_id, std_image, srilankan_id_no) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $params_insert = [
                    $std_name,
                    $std_dob,
                    $std_age,
                    $std_gender,
                    $father_name,
                    $std_address,
                    $std_mobile_no,
                    $std_app_no,
                    $s1_unique_id,
                    date('Y-m-d'),
                    unique_id($prefix),
                    $file_names,
                    $srilankan_id_no
                ];
            }

            // Prepare and execute INSERT query
            $stmt_insert = $mysqli->prepare($sql_insert);


            // Bind parameters for INSERT query
            $types_insert = str_repeat('s', count($params_insert));
            $stmt_insert->bind_param($types_insert, ...$params_insert);

            // Execute INSERT statement
            // $stmt_insert->execute();

            // Check if INSERT was successful
            if ($stmt_insert->execute()) {
                $status = true;
                $msg = "save";
            } else {
                $status = false;
                $error = "Insert operation failed.";
            }

            // Close INSERT statement
            $stmt_insert->close();
        }

        $sql_update_s = "UPDATE std_app_s SET std_name=? WHERE unique_id=?";

        $params_update_s = [
            $std_name,
            $s1_unique_id
        ];

        $stmt_update_s = $mysqli->prepare($sql_update_s);
        if ($stmt_update_s === false) {
            die('MySQL prepare error: ' . $mysqli->error);
        }

        // Bind parameters for UPDATE query
        $types_update_s = str_repeat('s', count($params_update_s));
        $stmt_update_s->bind_param($types_update_s, ...$params_update_s);
        $stmt_update_s->execute();

        // Prepare JSON response
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg
        ];

        echo json_encode($json_array);

        break;

    case 'emis_details_add':


        $emis_name = base64_decode($_POST["emis_name"]);
        $emis_no = base64_decode($_POST["emis_no"]);
        $emis_dob = base64_decode($_POST["emis_dob"]);
        $emis_class = base64_decode($_POST["emis_class"]);
        $emis_group = base64_decode($_POST["emis_group"]);
        $emis_medium = base64_decode($_POST["emis_medium"]);
        $emis_school_name = base64_decode($_POST["emis_school_name"]);
        $emis_school_block = base64_decode($_POST["emis_school_block"]);
        $emis_school_district = base64_decode($_POST["emis_school_district"]);
        $s1_unique_id = base64_decode($_POST["s1_unique_id"]);
        $unique_id = $_POST["unique_id"];



        // Initialize variables
        $status = false;
        $data = null;
        $error = null;
        $msg = "";

        // Check if record already exists
        $sql_check = "SELECT COUNT(unique_id) AS count FROM $table_s3 WHERE is_delete = ? AND s1_unique_id = ?";
        $params_check = ['0', $s1_unique_id];

        if ($unique_id) {
            $sql_check .= " AND unique_id != ?";
            $params_check[] = $unique_id;
        }

        // Prepare and execute SELECT query
        $stmt_check = $mysqli->prepare($sql_check);
        if ($stmt_check === false) {
            die('MySQL prepare error: ' . $mysqli->error);
        }

        // Bind parameters for SELECT query
        $types_check = str_repeat('s', count($params_check));
        $stmt_check->bind_param($types_check, ...$params_check);

        // Execute SELECT statement
        $stmt_check->execute();

        // Bind result variables for SELECT query
        $stmt_check->bind_result($count);

        // Fetch result from SELECT query
        $stmt_check->fetch();

        // Close SELECT statement
        $stmt_check->close();

        // Determine action based on count
        if ($count > 0) {
            // Update existing record
            $sql_update = "UPDATE $table_s3 SET emis_no=?, std_name=?, dob=?, class=?, group_name=?, medium=?, school_name=?, school_block=?, school_district=? WHERE s1_unique_id=?";
            $params_update = [
                $emis_no,
                $emis_name,
                $emis_dob,
                $emis_class,
                $emis_group,
                $emis_medium,
                $emis_school_name,
                $emis_school_block,
                $emis_school_district,
                $s1_unique_id
            ];

            // Prepare and execute UPDATE query
            $stmt_update = $mysqli->prepare($sql_update);

            // Bind parameters for UPDATE query
            $types_update = str_repeat('s', count($params_update));
            $stmt_update->bind_param($types_update, ...$params_update);

            // Execute UPDATE statement
            // $stmt_update->execute();

            // Check if UPDATE was successful
            if ($stmt_update->execute()) {
                $status = true;
                $msg = "update";
            } else {
                $status = false;
                $error = "Update operation failed.";
            }

            // Close UPDATE statement
            $stmt_update->close();
        } else {
            // Insert new record
            $sql_insert = "INSERT INTO $table_s3 ( std_name, emis_no, dob, class, group_name, medium, school_name, school_block, school_district, s1_unique_id, entry_date, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $params_insert = [
                $emis_name,
                $emis_no,
                $emis_dob,
                $emis_class,
                $emis_group,
                $emis_medium,
                $emis_school_name,
                $emis_school_block,
                $emis_school_district,
                $s1_unique_id,
                date('Y-m-d'),
                unique_id($prefix)
            ];

            // Prepare and execute INSERT query
            $stmt_insert = $mysqli->prepare($sql_insert);

            // Bind parameters for INSERT query
            $types_insert = str_repeat('s', count($params_insert));
            $stmt_insert->bind_param($types_insert, ...$params_insert);

            // Execute INSERT statement
            // $stmt_insert->execute();

            // Check if INSERT was successful
            if ($stmt_insert->execute()) {
                $status = true;
                $msg = "save";
            } else {
                $status = false;
                $error = "Insert operation failed.";
            }

            // Close INSERT statement
            $stmt_insert->close();
        }

        // Prepare JSON response
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg
        ];

        echo json_encode($json_array);
        break;

    case 'store_emis':


        // Decode POST data
        $emis_name = base64_decode($_POST["emis_name"]);

        $emis_dob = base64_decode($_POST["emis_dob"]);
        $emis_class = base64_decode($_POST["emis_class"]);
        $emis_group = base64_decode($_POST["emis_group"]);
        $emis_medium = base64_decode($_POST["emis_medium"]);
        $emis_school_name = base64_decode($_POST["emis_school_name"]);
        $emis_school_block = base64_decode($_POST["emis_school_block"]);
        $emis_school_district = base64_decode($_POST["emis_school_district"]);
        $s1_unique_id = base64_decode($_POST["s1_unique_id"]);
        $unique_id = $_POST["unique_id"];



        // Initialize variables
        $status = false;
        $data = null;
        $error = null;
        $msg = "";

        // Check if record already exists
        $sql_check = "SELECT COUNT(unique_id) AS count FROM emis WHERE s1_unique_id = ?";
        $params_check = [$s1_unique_id];

        if ($unique_id) {
            $sql_check .= " AND unique_id != ?";
            $params_check[] = $unique_id;
        }

        // Prepare and execute SELECT query
        $stmt_check = $mysqli->prepare($sql_check);
        if ($stmt_check === false) {
            die('MySQL prepare error: ' . $mysqli->error);
        }

        // Bind parameters for SELECT query
        $types_check = 's';
        $stmt_check->bind_param($types_check, ...$params_check);

        // Execute SELECT statement
        $stmt_check->execute();

        // Bind result variables for SELECT query
        $stmt_check->bind_result($count);

        // Fetch result from SELECT query
        $stmt_check->fetch();

        // Close SELECT statement
        $stmt_check->close();

        // Determine action based on count
        if ($count > 0) {
            // Update existing record
            $sql_update = "UPDATE emis SET name=?, emis_id=?, dob=?, group_name=?, class_studying_id=?, school_name=?, district_name=?, MEDINSTR_DESC=?, block_name=?, father_name=?, mother_name=?, father_occupation=?, mother_occupation=?, group_code_id=?, community_name=?, class_section=?, udise_code=? WHERE s1_unique_id=?";
            $params_update = [
                $emis_name,
                base64_decode($_POST['emis_no']),
                $emis_dob,
                $emis_group,
                $emis_class,
                $emis_school_name,
                $emis_school_district,
                $emis_medium,
                $emis_school_block,
                base64_decode($_POST['emis_father_name']),
                base64_decode($_POST['emis_mother_name']),
                base64_decode($_POST['emis_father_occupation']),
                base64_decode($_POST['emis_mother_occupation']),
                base64_decode($_POST['group_code_id']),
                base64_decode($_POST['community_name']),
                base64_decode($_POST['class_section']),
                base64_decode($_POST['udise_code']),
                $s1_unique_id
            ];

            // Prepare and execute UPDATE query
            $stmt_update = $mysqli->prepare($sql_update);
            if ($stmt_update === false) {
                die('MySQL prepare error: ' . $mysqli->error);
            }

            // Bind parameters for UPDATE query
            $types_update = str_repeat('s', count($params_update));
            $stmt_update->bind_param($types_update, ...$params_update);

            // Execute UPDATE statement
            $stmt_update->execute();

            // Check if UPDATE was successful
            if ($stmt_update->affected_rows > 0) {
                $status = true;
                $msg = "update";
            } else {
                $status = false;
                $error = "Update operation failed.";
            }

            // Close UPDATE statement
            $stmt_update->close();
        } else {
            // Insert new record
            $sql_insert = "INSERT INTO emis (name, emis_id, dob, group_name, class_studying_id, school_name, district_name, MEDINSTR_DESC, block_name, s1_unique_id, father_name, mother_name, father_occupation, mother_occupation, group_code_id, community_name, class_section, udise_code, entry_date, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $params_insert = [
                $emis_name,
                base64_decode($_POST['emis_no']),
                $emis_dob,
                $emis_group,
                $emis_class,
                $emis_school_name,
                $emis_school_district,
                $emis_medium,
                $emis_school_block,
                $s1_unique_id,
                base64_decode($_POST['emis_father_name']),
                base64_decode($_POST['emis_mother_name']),
                base64_decode($_POST['emis_father_occupation']),
                base64_decode($_POST['emis_mother_occupation']),
                base64_decode($_POST['group_code_id']),
                base64_decode($_POST['community_name']),
                base64_decode($_POST['class_section']),
                base64_decode($_POST['udise_code']),
                date('Y-m-d'),
                unique_id($prefix)
            ];

            // Prepare and execute INSERT query
            $stmt_insert = $mysqli->prepare($sql_insert);
            if ($stmt_insert === false) {
                die('MySQL prepare error: ' . $mysqli->error);
            }

            // Bind parameters for INSERT query
            $types_insert = str_repeat('s', count($params_insert));
            $stmt_insert->bind_param($types_insert, ...$params_insert);

            // Execute INSERT statement
            $stmt_insert->execute();

            // Check if INSERT was successful
            if ($stmt_insert->affected_rows > 0) {
                $status = true;
                $msg = "save";
            } else {
                $status = false;
                $error = "Insert operation failed.";
            }

            // Close INSERT statement
            $stmt_insert->close();
        }

        // Prepare JSON response
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg
        ];

        echo json_encode($json_array);



        break;

    case 'umis_details_add':

        $unique_id = $_POST["unique_id"];
        $s1_unique_id = base64_decode($_POST["s1_unique_id"]);

        $columns = [
            "got_addmission" => base64_decode($_POST['umisSelect']),
            "umis_no" => base64_decode($_POST['umis_no']),
            "umis_name" => base64_decode($_POST['umis_std_name']),
            "umis_yoa" => base64_decode($_POST['umis_yoa']),
            "umis_dob" => base64_decode($_POST['umis_dob']),
            "umis_yos" => base64_decode($_POST['umis_yos']),
            "umis_clg_name" => base64_decode($_POST['umis_clg_name']),
            "umis_clg_add" => base64_decode($_POST['umis_clg_add']),
            "umis_std_degree" => base64_decode($_POST['umis_std_degree']),
            "umis_std_course" => base64_decode($_POST['umis_std_course']),
            "caDistrictId" => base64_decode($_POST['caDistrictId']),
            "no_umis_college" => institute_id(base64_decode($_POST['no_umis_college']))[0]['institution_name'],
            "no_umis_inst_id" => base64_decode($_POST['no_umis_college']),
            "no_umis_name" => base64_decode($_POST['no_umis_name']),
            "no_umis_course" => base64_decode($_POST['no_umis_course']),
            "no_umis_branch" => base64_decode($_POST['no_umis_branch']),
            "no_umis_stream" => base64_decode($_POST['no_umis_stream']),
            "no_umis_clg_district" => base64_decode($_POST['no_umis_clg_district']),
            "no_umis_pincode" => base64_decode($_POST['no_umis_pincode']),
            "no_umis_yoa" => base64_decode($_POST['no_umis_yoa']),
            "no_umis_yos" => base64_decode($_POST['no_umis_yos']),
            "year_studying" => base64_decode($_POST['yr_stdy']),
            "lateral_entry" => base64_decode($_POST['lat_entry']),
            "s1_unique_id" => $s1_unique_id,
            "entry_date" => date('Y-m-d'),
            "unique_id" => unique_id($prefix) // Replace with your unique ID generation logic
        ];

        // Check if record exists
        $select_sql = "SELECT COUNT(unique_id) AS count FROM $table_s4 WHERE is_delete = 0 AND s1_unique_id = ?";
        if ($unique_id) {
            $select_sql .= " AND unique_id != ?";
            $stmt = $mysqli->prepare($select_sql);
            $stmt->bind_param("ss", $s1_unique_id, $unique_id);
        } else {
            $stmt = $mysqli->prepare($select_sql);
            $stmt->bind_param("s", $s1_unique_id);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        foreach ($columns as $key => $value) {
            if ($value === '') {
                $columns[$key] = NULL;
            }
        }


        if ($data['count'] > 0) {


            $update_sql = "UPDATE $table_s4 SET got_addmission = ?, umis_no = ?, umis_name = ?, umis_yoa = ?, umis_dob = ?, umis_yos = ?, umis_clg_name = ?, umis_clg_add = ?, umis_std_degree = ?, umis_std_course = ?, caDistrictId = ?, no_umis_college = ?, no_umis_name = ?, no_umis_course = ?, no_umis_stream = ?, no_umis_clg_district = ?, no_umis_pincode = ?, no_umis_yoa = ?, no_umis_yos = ?, year_studying = ?, lateral_entry = ?, entry_date = ?, no_umis_inst_id=?, no_umis_branch=? WHERE s1_unique_id = ?";
            $stmt = $mysqli->prepare($update_sql);
            $stmt->bind_param("sssssssssssssssssssssssss", $columns['got_addmission'], $columns['umis_no'], $columns['umis_name'], $columns['umis_yoa'], $columns['umis_dob'], $columns['umis_yos'], $columns['umis_clg_name'], $columns['umis_clg_add'], $columns['umis_std_degree'], $columns['umis_std_course'], $columns['caDistrictId'], $columns['no_umis_college'], $columns['no_umis_name'], $columns['no_umis_course'], $columns['no_umis_stream'], $columns['no_umis_clg_district'], $columns['no_umis_pincode'], $columns['no_umis_yoa'], $columns['no_umis_yos'], $columns['year_studying'], $columns['lateral_entry'], $columns['entry_date'], $columns['no_umis_inst_id'], $columns['no_umis_branch'], $s1_unique_id);
            $status = $stmt->execute();
        } else {
            $insert_sql = "INSERT INTO $table_s4 (got_addmission, umis_no, umis_name, umis_yoa, umis_dob, umis_yos, umis_clg_name, umis_clg_add, umis_std_degree, umis_std_course, caDistrictId, no_umis_college, no_umis_name, no_umis_course, no_umis_stream, no_umis_clg_district, no_umis_pincode, no_umis_yoa, no_umis_yos, year_studying, lateral_entry, s1_unique_id, entry_date, unique_id, no_umis_inst_id, no_umis_branch) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($insert_sql);
            $stmt->bind_param("ssssssssssssssssssssssssss", $columns['got_addmission'], $columns['umis_no'], $columns['umis_name'], $columns['umis_yoa'], $columns['umis_dob'], $columns['umis_yos'], $columns['umis_clg_name'], $columns['umis_clg_add'], $columns['umis_std_degree'], $columns['umis_std_course'], $columns['caDistrictId'], $columns['no_umis_college'], $columns['no_umis_name'], $columns['no_umis_course'], $columns['no_umis_stream'], $columns['no_umis_clg_district'], $columns['no_umis_pincode'], $columns['no_umis_yoa'], $columns['no_umis_yos'], $columns['year_studying'], $columns['lateral_entry'], $s1_unique_id, $columns['entry_date'], $columns['unique_id'], $columns['no_umis_inst_id'], $columns['no_umis_branch']);
            $status = $stmt->execute();
        }

        if ($status) {
            $msg = $unique_id ? "update" : "save";
            $status = true;
            $error = "";
        } else {
            $msg = "error";
            $status = false;
            $error = $stmt->error;
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg
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



    case 'main_datatable':
        // DataTable Variables
        $search = $_POST['search']['value'];
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;

        $data = [];


        $table_app_s = 'std_app_s';

        if ($length == '-1') {
            $limit = "";
        }

        // Query Variables
        $json_array = "";
        $columns = [
            "@a:=@a+1 s_no",
            "(SELECT amc_year from academic_year_creation where unique_id = $table_app_s.academic_year) as acc_year",
            "std_app_no",
            "(SELECT std_name from std_app_s2 where s1_unique_id = $table_app_s.unique_id ) as student_name",
            "submit_status",
            "student_status",
            "unique_id",
            "batch_no"
        ];
        $table_details = [
            $table . " , (SELECT @a:= " . $start . ") AS a ",
            $columns
        ];
        $where = "is_delete = 0 AND student_status = 1";
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

                $submit_status = $value['submit_status'];

                $student_status = $value['student_status'];

                if ($submit_status == 0) {

                    $value['submit_status'] = 'Partially Submitted';
                } else if ($submit_status == 1) {

                    $value['submit_status'] = 'Completed';

                }

                if ($student_status == 1) {
                    $value['student_status'] = 'Srilankan Refugee';
                }

                $value['is_active'] = is_active_show($value['is_active']);

                if ($value['batch_no'] == '' || $value['batch_no'] == NULL) {
                    $btn_update = btn_update_encrypt($folder_name, $value['unique_id']);
                } else {
                    $btn_update = '';
                }

                // if ($value['unique_id'] == "5f97fc3257f2525529") {
                //     $btn_update = "";
                //     $btn_delete = "";
                // }

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

        $aadhar_no = $_POST['aadhar_no'];
        $academic_year = $_POST['academic_year'];

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
        $select_where .= ' is_delete = 0 and aadhar_no = "' . $aadhar_no . '" ';
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
            $unique_id = $data[0]["unique_id"];
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
            "unique_id" => $unique_id,
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

    case 'get_priority_count':
        $s1_unique_id = $_POST['s1_unique_id'];
        // $academic_year = $_POST['academic_year'];

        $json_array = "";
        $table_details = [
            "std_app_s7",
            [
                "GROUP_CONCAT(priority SEPARATOR ', ') AS priority",

            ]

        ];

        $select_where = '';
        $select_where .= 'is_delete = 0 and s1_unique_id = "' . $s1_unique_id . '" ';
        // When Update Check without current id


        $action_obj = $pdo->select($table_details, $select_where);
        // print_r($action_obj);die();
        $data = $action_obj->data;

        $priority = $data[0]['priority'];

        $priority_type_options = priority('', $priority);
        $priority_type_options = select_option($priority_type_options, "Select Priority");

        echo $priority_type_options;

        break;

    case 'get_host_cnt':
        $s1_unique_id = $_POST['s1_unique_id'];

        //    print_r($s1_unique_id);die();
        // $academic_year = $_POST['academic_year'];

        $json_array = "";
        $columns = [];
        $table_details = [
            "std_app_s7",
            ["count(*) as cnt"]

        ];

        $select_where = '';
        $select_where .= 'is_delete = 0 and s1_unique_id = "' . $s1_unique_id . '" and priority = "1"';
        // When Update Check without current id


        $action_obj = $pdo->select($table_details, $select_where);
        // print_r($action_obj);die();
        $data = $action_obj->data;

        // print_r($data);die();

        $host_cnt = $data[0]['cnt'];
        if ($host_cnt > 0) {
            $columns = [
                "submit_status" => 1
            ];

            $update_where = [
                "unique_id" => $s1_unique_id
            ];

            $action_obj = $pdo->update("std_app_s", $columns, $update_where);
        }

        $json_array = [

            "data" => $host_cnt,

        ];

        echo json_encode($json_array);

        // echo $host_cnt;

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

    case 'get_occu':
        $father_occu = $_POST['father_occu'];

        $father_occu = occupation($father_occu)[0]['occu_name'];

        echo $father_occu;

        break;

    case 'get_umis_occu':
        $father_occu = $_POST['father_occu'];

        $father_occu = umis_occupation($father_occu)[0]['name'];

        echo $father_occu;

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


    case 'get_district_id':

        $institude_id = $_POST['institude_id'];

        $umis_district = umis_district_id($institude_id)[0]['InstituteDistrictId'];

        echo $umis_district;

        break;

    case 'get_umis_district':

        $caDistrictId = $_POST['caDistrictId'];

        $umis_district = umis_district($caDistrictId)[0]['DistrictName'];

        echo $umis_district;

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

    case 'get_institute_name':

        $no_umis_clg_district = $_POST['no_umis_clg_district'];

        $institute_id_options = institute_id("", $no_umis_clg_district);

        $institute_id_options = select_option($institute_id_options, "Select Institute");

        echo $institute_id_options;

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

    case 'get_degree':

        $no_umis_stream = $_POST['no_umis_stream'];


        $hostel_district_options = course_name_options("", $no_umis_stream);

        $hostel_taluk_options = select_option($hostel_district_options, "Select Degree");

        echo $hostel_taluk_options;

        break;


    case 'get_course_branch':

        $no_umis_course = $_POST['no_umis_course'];


        $hostel_district_options = course_branch_options("", $no_umis_course);

        $hostel_taluk_options = select_option($hostel_district_options, "Select Branch");

        echo $hostel_taluk_options;

        break;

    case 'get_blood_group':

        $bloodgroup = $_POST['bloodgroup'];

        $blood_group = blood_group($bloodgroup)[0]['name'];

        // $hostel_taluk_options = select_option($hostel_district_options, "Select School");

        echo $blood_group;

        break;

    case 'get_courseName':

        $courseId = $_POST['courseId'];

        $courseName = courseName($courseId);

        echo $courseName;

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
        $s1_unique_id = $_POST['s1_unique_id'];
        // $academic_year = $_POST['academic_year'];

        $json_array = "";
        $table_details = [
            "std_app_s7",
            [
                "GROUP_CONCAT(CONCAT('\'', hostel_name, '\'') SEPARATOR ', ') AS hostel_name",

            ]

        ];

        $select_where = '';
        $select_where .= ' is_delete = 0 and s1_unique_id = "' . $s1_unique_id . '" ';
        // When Update Check without current id


        $action_obj = $pdo->select($table_details, $select_where);
        // print_r($action_obj);die();
        $data = $action_obj->data;

        $hostel_name = $data[0]['hostel_name'];

        $hostel_district = $_POST['hostel_district'];
        $hostel_taluk = $_POST['hostel_taluk'];
        $hostel_gender_type = $_POST['hostel_gender_type'];
        $hostel_type = $_POST['hostel_type'];

        $hostel_district_options = hostel_name("", $hostel_district, $hostel_taluk, $hostel_gender_type, $hostel_type, $hostel_name);

        $hostel_taluk_options = select_option($hostel_district_options, "Select Hostel");

        echo $hostel_taluk_options;

        break;

    case 'hostel_sub_add_update':

        $priority = base64_decode($_POST["priority"]);
        $hostel_district = base64_decode($_POST["hostel_district"]);
        $hostel_taluk = base64_decode($_POST["hostel_taluk"]);
        $gender_type = base64_decode($_POST["gender_type"]);
        $hostel_type = base64_decode($_POST["hostel_type"]);
        $hostel_name = base64_decode($_POST["hostel_name"]);
        $s1_unique_id = base64_decode($_POST["s1_unique_id"]);
        $unique_id = $_POST["unique_id"];

        // $columns = [
        //     "priority" => $priority,
        //     "hostel_district" => $hostel_district,
        //     "hostel_taluk" => $hostel_taluk,
        //     "gender_type" => $gender_type,
        //     "hostel_type" => $hostel_type,
        //     "hostel_name" => $hostel_name,
        //     "s1_unique_id" => $s1_unique_id,
        //     "entry_date" => date('Y-m-d'),
        //     "unique_id" => unique_id($prefix); // Replace with your unique ID generation logic
        // ];

        // Check if record exists
        $stmt = $mysqli->prepare("SELECT COUNT(unique_id) AS count, priority FROM $table_s7 WHERE is_delete = 0 AND s1_unique_id = ? AND (priority = ? OR hostel_name = ?)");
        $stmt->bind_param("sss", $s1_unique_id, $priority, $hostel_name);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        if ($data['count'] > 0) {
            $msg = "already";
            $update_columns = [
                "is_delete" => 1
            ];

            $stmt_7 = $mysqli->prepare("UPDATE $table_s7 SET is_delete = 1 WHERE hostel_name = ? AND s1_unique_id = ?");
            $stmt_7->bind_param("ss", $hostel_name, $s1_unique_id);
            $stmt_7->execute();


            if ($data['priority'] == 1) {
                $hostel_name = NULL;
                $hostel_taluk = NULL;
                $hostel_district = NULL;
                $stmt_1 = $mysqli->prepare("UPDATE $table SET hostel_1 = ?, hostel_taluk_1 = ?, hostel_district_1 = ? WHERE unique_id = ?");
            } elseif ($data['priority'] == 2) {
                $hostel_name = NULL;
                $hostel_taluk = NULL;
                $hostel_district = NULL;
                $stmt_1 = $mysqli->prepare("UPDATE $table SET hostel_2 = ?, hostel_taluk_2 = ?, hostel_district_2 = ? WHERE unique_id = ?");
            } elseif ($data['priority'] == 3) {
                $hostel_name = NULL;
                $hostel_taluk = NULL;
                $hostel_district = NULL;
                $stmt_1 = $mysqli->prepare("UPDATE $table SET hostel_3 = ?, hostel_taluk_3 = ?, hostel_district_3 = ? WHERE unique_id = ?");
            }


            $stmt_1->bind_param("ssss", $hostel_name, $hostel_taluk, $hostel_district, $s1_unique_id);
            $stmt_1->execute();
        } else {
            //    if($priority != 1){
            $priority_1_cnt = priority_1_count($s1_unique_id);
            //    }

            if ($priority_1_cnt > 0 || $priority == 1) {

                $stmt_s7 = $mysqli->prepare("INSERT INTO $table_s7 (priority, hostel_district, hostel_taluk, gender_type, hostel_type, hostel_name, s1_unique_id, entry_date, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt_s7->bind_param("sssssssss", $priority, $hostel_district, $hostel_taluk, $gender_type, $hostel_type, $hostel_name, $s1_unique_id, date('Y-m-d'), unique_id($prefix));

                $status = $stmt_s7->execute();

                if ($priority == 1) {

                    $stmt1 = $mysqli->prepare("UPDATE $table SET hostel_1 = ?, hostel_taluk_1 = ?, hostel_district_1 = ? WHERE unique_id = ?");

                } elseif ($priority == 2) {

                    $stmt1 = $mysqli->prepare("UPDATE $table SET hostel_2 = ?, hostel_taluk_2 = ?, hostel_district_2 = ? WHERE unique_id = ?");
                } elseif ($priority == 3) {

                    $stmt1 = $mysqli->prepare("UPDATE $table SET hostel_3 = ?, hostel_taluk_3 = ?, hostel_district_3 = ? WHERE unique_id = ?");
                }


                $stmt1->bind_param("ssss", $hostel_name, $hostel_taluk, $hostel_district, $s1_unique_id);
                $stmt1->execute();
                $msg = "save";
            } else {
                $msg = "no_priority_1";
            }
        }

        // if ($stmt1->execute()) {
        //     $msg = $unique_id ? "update" : "save";
        //     $status = true;
        //     $error = "";
        // } else {
        //     $msg = "error";
        //     $status = false;
        //     $error = $stmt->error;
        // }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
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
        $s1_unique_id = $_POST['s1_unique_id'];
        $adjacent_district = $_POST['adjacent_district'];
        $gender_type = $_POST['gender_type'];
        $hostel_type = $_POST['hostel_type'];

        if ($_POST['hostel_district']) {
            $hostel_district = $_POST['hostel_district'];
            $where_host = "(FIND_IN_SET(district_name, '" . $adjacent_district . "') or district_name = '" . $hostel_district . "') and";

        } else if ($_POST['emis_hostel_district']) {
            $hostel_district_name = $_POST['emis_hostel_district'];
            $hostel_district = district_name_test("", $hostel_district_name)[0]['unique_id'];

            $where_host = "(FIND_IN_SET(district_name, '" . $adjacent_district . "') or district_name = '" . $hostel_district . "') and";

        } else if ($_POST['umis_district']) {
            $hostel_district_name = $_POST['umis_district'];
            $hostel_district = district_name_test("", $hostel_district_name)[0]['unique_id'];
            $where_host = "(FIND_IN_SET(district_name, '" . $adjacent_district . "') or district_name = '" . $hostel_district . "') and";
        }
        $table = "hostel_name";

        // Query Variables
        $json_array = "";
        $columns = [
            "'' as s_no",
            '(SELECT district_name FROM district_name AS dis WHERE dis.unique_id = ' . $table . '.district_name) AS district_name',
            '(SELECT taluk_name FROM taluk_creation AS taluk WHERE taluk.unique_id = ' . $table . '.taluk_name) AS taluk_name',
            // "hostel_id",
            // "(select gender_type from hostel_gender_type where hostel_gender_type.unique_id = std_app_p2.hostel_gender_type) as hostel_gender_type",
            // "(select hostel_type from hostel_type where hostel_type.unique_id = std_app_p2.hostel_type) as hostel_type",
            // "(select hostel_name from hostel_name where hostel_name.unique_id = std_app_s7.hostel_name) as hostel_name",
            "hostel_name",
            "address",
            "'' as priority",
            // "is_active",
            "unique_id",
            "district_name as district_id",
            "taluk_name as taluk_id",
            "hybrid_hostel"
        ];
        $table_details = [
            $table . " , (SELECT @a:= '" . $start . "') AS a ",
            $columns
        ];
        $where = "is_delete = 0 and is_active = 1 and " . $where_host . " gender_type = '" . $gender_type . "' and (hostel_type = '" . $hostel_type . "' or hybrid_hostel = 'Yes')   order by district_name";
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
        // print_r($result);die();
        $total_records = total_records();

        if ($result->status) {

            $res_array = $result->data;
            $s_no = 1;

            foreach ($res_array as $key => $value) {
                if ($value['hybrid_hostel'] == 'Yes') {
                    $hybrid_hostel = '( Hybrid )';
                } else if ($value['hybrid_hostel'] == 'No') {
                    $hybrid_hostel = '';
                } else {
                    $hybrid_hostel = '';
                }
                $value['s_no'] = $s_no++;
                $value['hostel_name'] = disname($value['hostel_name']) . ' <br> <b style="color:blue">' . $hybrid_hostel . '</b> ';
                // $value['is_active'] = is_active_show($value['is_active']);

                // $btn_update         = btn_update($folder_name,$value['unique_id']);
                $btn_delete = btn_delete($folder_name, $value['unique_id'], $value['priority']);

                $priority = priority_count($s1_unique_id);


                $host_priority = get_priority($s1_unique_id, $value['unique_id']);



                $priority_type_options = priority($host_priority, $priority);
                $priority_type_options = select_option($priority_type_options, "Select Priority", $host_priority);

                if ($host_priority) {
                    $border_color = 'border-color: #006400;';
                } else {
                    $border_color = '';
                }



                $status_select = '<select class="select2 form-control" style="cursor: pointer; ' . $border_color . '" onchange="hostel_sub_add_update(\'' . $s1_unique_id . '\', \'' . $value['district_id'] . '\', \'' . $value['taluk_id'] . '\', \'' . $value['unique_id'] . '\', \'' . $gender_type . '\', \'' . $hostel_type . '\', this.value)">';
                $status_select .= $priority_type_options;
                $status_select .= '</select>';


                $value['priority'] = $status_select;

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


    case 'hostel_sub_datatable_old':
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
        $s1_unique_id = $_POST['s1_unique_id'];

        // Query Variables
        $json_array = "";
        $columns = [
            "@a:=@a+1 s_no",
            '(SELECT district_name FROM district_name AS dis WHERE dis.unique_id = ' . $table_s7 . '.hostel_district) AS district_name',
            '(SELECT taluk_name FROM taluk_creation AS taluk WHERE taluk.unique_id = ' . $table_s7 . '.hostel_taluk) AS taluk_name',
            // "(select gender_type from hostel_gender_type where hostel_gender_type.unique_id = std_app_p2.hostel_gender_type) as hostel_gender_type",
            // "(select hostel_type from hostel_type where hostel_type.unique_id = std_app_p2.hostel_type) as hostel_type",
            "(select hostel_name from hostel_name where hostel_name.unique_id = std_app_s7.hostel_name) as hostel_name",
            "priority",
            // "is_active",
            "unique_id"
        ];
        $table_details = [
            $table_s7 . " , (SELECT @a:= '" . $start . "') AS a ",
            $columns
        ];
        $where = "is_delete = 0 and s1_unique_id = '" . $s1_unique_id . "'";
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
                $btn_delete = btn_delete($folder_name, $value['unique_id'], $value['priority']);
                switch ($value['priority']) {
                    case 1:
                        $value['priority'] = 'Priority 1';
                        break;
                    case 2:
                        $value['priority'] = 'Priority 2';
                        break;
                    case 3:
                        $value['priority'] = 'Priority 3';
                        break;
                }

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
        $priority = $_POST['priority'];
        $s1_unique_id = $_POST['s1_unique_id'];

        $columns = [
            "is_delete" => 1
        ];

        $update_where = [
            "unique_id" => $unique_id
        ];

        $action_obj = $pdo->update($table_s7, $columns, $update_where);


        if ($priority == '1') {
            $columns_2 = [
                "hostel_1" => '',
                "hostel_district_1" => '',
                "hostel_taluk_1" => '',
            ];
        } else if ($priority == '2') {
            $columns_2 = [
                "hostel_2" => '',
                "hostel_district_2" => '',
                "hostel_taluk_2" => '',
            ];
        } else if ($priority == '3') {
            $columns_2 = [
                "hostel_3" => '',
                "hostel_district_3" => '',
                "hostel_taluk_3" => '',
            ];
        }

        $update_where_2 = [
            "unique_id" => $s1_unique_id
        ];

        $action_obj_2 = $pdo->update("std_app_s", $columns_2, $update_where_2);
        //         print_r($action_obj_2);

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
        $select_data = $action_obj->data;
        // die();
        $p1_unique_id = $select_data[0]['p1_unique_id'];

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

    case 'hostelChoice':
        $s1_unique_id = $_POST['s1_unique_id'];

        $table_details = [
            "std_app_s",
            [
                "student_type",
            ]
        ];

        $select_where = 'is_delete = 0 and unique_id = "' . $s1_unique_id . '"';
        $action_obj = $pdo->select($table_details, $select_where);

        $data = $action_obj->data;

        $student_type = $data[0]['student_type'];

        if ($student_type != '65f00a259436412348') {
            $count_table = 'std_app_umis_s4';
        } else {
            $count_table = 'std_app_emis_s3';
        }

        $table_details_count = [
            $count_table,
            [
                "COUNT(unique_id) AS count",

            ]
        ];

        $select_where_count = 's1_unique_id = "' . $s1_unique_id . '"';

        $action_obj_count = $pdo->select($table_details_count, $select_where_count);


        $data_count = $action_obj_count->data;

        $count = $data_count[0]['count'];

        if ($count == 1) {
            echo 'success';
        } else {
            echo 'failure';
        }
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
    $password = "";
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


function reg_no1($academic_year, $host_type)
{
    // $date = date("Y");
    // $st_date = substr($date, 4);

    $servername = "localhost";
    $username = "root";
    $password = "H_Cw3O4CM*fXcGtz";
    $database_name = "adi_dravidar";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=adi_dravidar", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
    }

    $sql = $conn->query("SELECT * FROM academic_year_creation where unique_id = '" . $academic_year . "' and is_delete = '0' ");
    $row = $sql->fetch();

    $acc_year = $row['amc_year'];
    $a = str_split($acc_year);
    $splt_acc_yr = $a[0] . $a[1] . $a[2] . $a[3];

    $stmt = $conn->query("SELECT std_app_no as std_app_no FROM std_app_s where is_delete = '0' order by id desc LIMIT 1");
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
    $registration_no = $splt_acc_yr . 'ADTW' . $host_type . str_pad($new_seq_no, 4, '0', STR_PAD_LEFT);

    return $registration_no;
}

function reg_no($academic_year, $host_type)
{
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

    $sql = $conn->prepare("SELECT amc_year FROM academic_year_creation WHERE unique_id = :academic_year AND is_delete = '0' order by s_no desc limit 1");
    $sql->execute(['academic_year' => $academic_year]);
    $row = $sql->fetch();

    if (!$row) {
        throw new Exception("Academic year not found or deleted.");
    }

    $acc_year = $row['amc_year'];
    $splt_acc_yr = substr($acc_year, 0, 4);

    $stmt = $conn->query("SELECT std_app_no FROM std_app_s WHERE is_delete = '0' and academic_year = '" . $academic_year . "' and application_type = 1 ORDER BY CAST(RIGHT(std_app_no, 6) AS UNSIGNED) DESC LIMIT 1");
    $last_reg_no = $stmt->fetchColumn();

    if ($last_reg_no === false) {
        $new_seq_no = 1;
    } else {
        // Extract the numeric part of the last registration number
        // $last_numeric_part = intval(preg_replace('/[^0-9]/', '', substr($last_reg_no, -6)));
        // $new_seq_no = $last_numeric_part + 1;
        $last_numeric_part = intval(substr($last_reg_no, -6));
        $new_seq_no = $last_numeric_part + 1;
    }


    $registration_no = $splt_acc_yr . 'ADTW' . $host_type . str_pad($new_seq_no, 6, '0', STR_PAD_LEFT);


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

function get_priority($s1_unique_id = "", $hostel_id = "")
{
    // echo $zone_name;

    global $pdo;

    $table_name = "std_app_s7";
    $where = "";
    $table_columns = [
        "priority",
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    $where = "is_delete = '0' and is_active = '1' and hostel_name = '" . $hostel_id . "' and s1_unique_id = '" . $s1_unique_id . "'";

    // if ($unique_id) {
    //     // $where              = [];
    //     $where["batch_no"] .= $unique_id;
    // }
    // if ($unique_id) {
    //     $where              = [];
    //     $where["unique_id"] = $unique_id;
    // }

    $amc_name_list = $pdo->select($table_details, $where);

    // print_r( $amc_name_list);

    if ($amc_name_list->status) {
        return $amc_name_list->data[0]['priority'];
    } else {
        print_r($amc_name_list);
        return 0;
    }
}

function priority_1_count($s1_unique_id = "")
{

    global $pdo;

    $table_name = "std_app_s7";
    $where = "";
    $table_columns = [
        "count(priority) as priority",
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    // $where = [
    //     "is_active" => 1,
    //     "is_delete" => 0
    // ];

    $where = 'is_delete = 0 and s1_unique_id = "' . $s1_unique_id . '" and priority = "1"';



    $amc_name_list = $pdo->select($table_details, $where);



    if ($amc_name_list->status) {
        return $amc_name_list->data[0]['priority'];
    } else {
        print_r($amc_name_list);
        return 0;
    }
}


function priority_count($s1_unique_id = "")
{

    global $pdo;

    $table_name = "std_app_s7";
    $where = "";
    $table_columns = [
        "GROUP_CONCAT(priority SEPARATOR ', ') AS priority",
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    // $where = [
    //     "is_active" => 1,
    //     "is_delete" => 0
    // ];

    $where = 'is_delete = 0 and s1_unique_id = "' . $s1_unique_id . '" ';



    $amc_name_list = $pdo->select($table_details, $where);



    if ($amc_name_list->status) {
        return $amc_name_list->data[0]['priority'];
    } else {
        print_r($amc_name_list);
        return 0;
    }
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





function btn_update_encrypt($folder_name = "", $unique_id = "", $prefix = "", $suffix = "")
{

    // $final_str = '<a class="btn btn-primary btn-action mr-1" data-toggle="modal" data-target="#exampleModal" data-unique_id = "'.$unique_id.'"  data-toggle="tooltip" title="Edit"><i
    //                           class="fas fa-pencil-alt"></i></a>';

    $password = '3sc3RLrpd17';
    $enc_method = 'aes-256-cbc';
    $enc_password = substr(hash('sha256', $password, true), 0, 32);
    $enc_iv = "av3DYGLkwBsErphc";


    $menu_screen = $folder_name . "/model";
    $file_name_update = base64_encode(openssl_encrypt($menu_screen, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));

    $uni_id = base64_encode(openssl_encrypt($unique_id, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));

    //    $final_str = ' <a href="index.php?file='.$prefix.$folder_name.$suffix.'/model&unique_id='.$unique_id.'" class="font-18 text-info me-2" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Edit" data-bs-original-title="Edit"><i class="uil uil-pen"></i></a>';

    $final_str = ' <a href="index.php?file=' . $file_name_update . '&unique_id=' . $uni_id . '" class="font-18 text-info me-2" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Edit" data-bs-original-title="Edit"><i class="uil uil-pen"></i></a>';


    return $final_str;
}
