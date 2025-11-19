<?php
// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];
// // Database Country Table Name
$table = "student_transfer";
// // Include DB file and Common Functions
include '../../config/dbconfig.php';
// // Variables Declaration
$action = $_POST['action'];
$is_active = "";
$unique_id = "";
$prefix = "";
$data = "";
$msg = "";
$error = "";
$status = "";
$test = ""; // For Developer Testing Purpose

function validateCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}


switch ($action) {

    case 'createupdate':

        $token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';

        // Validate CSRF token
        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        // Sanitize and validate incoming data
        $std_id = sanitizeInput($_POST['std_id']);
        $std_reg_no = sanitizeInput($_POST['std_reg_no']);
        $std_name = sanitizeInput($_POST['std_name']);
        $from_hostel = sanitizeInput($_POST['from_hostel']);
        $from_taluk = sanitizeInput($_POST['from_taluk']);
        $from_district = sanitizeInput($_POST['from_district']);
        $to_district = sanitizeInput($_POST['to_district']);
        $to_taluk = sanitizeInput($_POST['to_taluk']);
        $to_hostel = sanitizeInput($_POST['to_hostel']);
        $insert_type = 1;


        $unique_id = isset($_POST['unique_id']) ? sanitizeInput($_POST['unique_id']) : '';

        // Prepare SQL statement
        if ($unique_id) {

            // Update existing record with file_name and file_org_name
            $sql = "UPDATE $table SET std_id=?, std_reg_no=?, std_name=?, from_hostel=?, from_taluk=?, from_district=?, to_district=?, to_taluk=?, to_hostel=? WHERE unique_id=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("ssssssssss", $std_id, $std_reg_no, $std_name, $from_hostel, $from_taluk, $from_district, $to_district, $to_taluk, $to_hostel, $unique_id);
        } else {

            $entry_date = date('Y-m-d');
            $sql = "INSERT INTO $table (std_id, std_reg_no, std_name, from_hostel, from_taluk, from_district, to_district, entry_date, unique_id, to_taluk, to_hostel, insert_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            //  print_r($sql);
            $stmt = $mysqli->prepare($sql);
            // print_r($stmt);
            $stmt->bind_param("ssssssssssss", $std_id, $std_reg_no, $std_name, $from_hostel, $from_taluk, $from_district, $to_district, $entry_date, unique_id($prefix), $to_taluk, $to_hostel, $insert_type);
        }

        // Execute SQL statement
        if ($stmt->execute()) {
            $status = true;
            $msg = $unique_id ? 'update' : 'create';
        } else {
            $status = false;
            $error = $stmt->error;
            $msg = 'error';
        }

        // Close statement and connection
        $stmt->close();
        $mysqli->close();

        // Prepare JSON response
        $response = [
            'status' => $status,
            'msg' => $msg,
            'error' => isset($error) ? $error : ''
        ];

        echo json_encode($response);
        break;


    case 'transfer_datatable':
        
        // DataTable Variables
        $length = isset($_POST['length']) ? $_POST['length'] : 10;
        $start = isset($_POST['start']) ? $_POST['start'] : 0;
        $draw = isset($_POST['draw']) ? $_POST['draw'] : 1;
        $limit = ($length == '-1') ? "" : $length;
        $acc_year = $_POST['acc_year'] ?? '';
        $data = [];

        // SQL Columns
        $columns = [
            "@a:=@a+1 s_no",
            "(SELECT amc_year from academic_year_creation where unique_id = $table.academic_year) as acc_year",
            "std_reg_no",
            "std_name",
            "to_district",
            "to_taluk",
            "to_hostel",
            "approval_status",
            "unique_id"
        ];

        $sql_columns = implode(", ", $columns);

        // Table setup with counter
        $table_with_counter = "{$table}, (SELECT @a:=?) AS a";

        // WHERE clause and parameter binding
        $where = "is_delete = ? AND from_hostel = ?";
        $params = [$start, 0, $_SESSION['hostel_id']]; // Initial counter value, is_delete, hostel_name
        $param_types = "iis";

        // Academic year condition if provided
        if (!empty($acc_year)) {
            $where .= " AND academic_year = ?";
            $params[] = $acc_year;
            $param_types .= "s";
        }

        // Main SQL query
        $sql_query = "SELECT SQL_CALC_FOUND_ROWS {$sql_columns} FROM {$table_with_counter} WHERE {$where} ORDER BY id DESC";

        // Add LIMIT if needed
        if ($limit !== "") {
            $sql_query .= " LIMIT ?, ?";
            $params[] = $start;
            $params[] = $limit;
            $param_types .= "ii";
        }

        // Total records function (used for display)
        $total_records = total_records();

        // Prepare and execute query
        $stmt = $mysqli->prepare($sql_query);
        if ($stmt) {
            $stmt->bind_param($param_types, ...$params);
            $stmt->execute();

            $res_array = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

            if ($res_array) {
                foreach ($res_array as $value) {

                    $approval_status = $value['approval_status'];

                    // Format status
                    if ($value['approval_status'] == '0') {
                        $value['approval_status'] = '<p style="color:blue">Pending</p>';
                    } elseif ($value['approval_status'] == '1') {
                        $value['approval_status'] = '<p style="color:green">Approved</p>';
                    } elseif ($value['approval_status'] == '2') {
                        $value['approval_status'] = '<p style="color:red">Rejected</p>';
                    }

                    $value['to_hostel'] = hostel_name($value['to_hostel'])[0]['hostel_name'];
                    $value['to_district'] = district_name($value['to_district'])[0]['district_name'];
                    $value['to_taluk'] = taluk_name($value['to_taluk'])[0]['taluk_name'];

                    $btn_update = btn_update($folder_name, $value['unique_id']);

                    $btn_delete = btn_delete_uniqueid($folder_name, $value['unique_id'], $value['std_reg_no']);

                    // $value['unique_id'] = $btn_update . $btn_delete;


                    if ($approval_status == '0') {

                        $value['unique_id'] = $btn_update . $btn_delete;
                    } else if ($approval_status == '1') {

                        $value['unique_id'] = '-';
                    } else if ($approval_status == '2') {

                        $value['unique_id'] = $btn_update . $btn_delete;
                    }





                    $data[] = array_values($value);
                }
            }

            // Get filtered total
            $stmt_filtered = $mysqli->query("SELECT FOUND_ROWS()");
            $total_filtered = $stmt_filtered->fetch_row()[0];

            // JSON Response
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_filtered),
                "recordsFiltered" => intval($total_filtered),
                "data" => $data,
            ];

            $stmt->close();
        } else {
            // Fallback in case of error
            error_log("MySQLi error: " . $mysqli->error);
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
            ];
        }

        // Output JSON
        echo json_encode($json_array);
        break;


    case 'approval_datatable':
        // DataTable Variables
        $length = isset($_POST['length']) ? $_POST['length'] : 10;
        $start = isset($_POST['start']) ? $_POST['start'] : 0;
        $draw = isset($_POST['draw']) ? $_POST['draw'] : 1;
        $limit = ($length == '-1') ? "" : $length;
        $acc_year = $_POST['acc_year'] ?? '';
        $data = [];

        // SQL Columns
        $columns = [
            "@a:=@a+1 s_no",
            "(SELECT amc_year from academic_year_creation where unique_id = $table.academic_year) as acc_year",
            "std_reg_no",
            "std_name",
            "(SELECT hostel_id from hostel_name where unique_id  = $table.from_hostel) as hostel_id",
            "from_district",
            "from_taluk",
            "from_hostel",
            "unique_id",
            "approval_status",
            "to_hostel",
            "std_id",
            "insert_type",
            "to_district",
            "to_taluk"
        ];
        $sql_columns = implode(", ", $columns);

        // Table setup with counter
        $table_with_counter = "{$table}, (SELECT @a:=?) AS a";

        // WHERE clause and parameter binding
        $where = "is_delete = ? AND to_hostel = ?";
        $params = [$start, 0, $_SESSION['hostel_id']]; // Initial counter value, is_delete, hostel_name
        $param_types = "iis";

        // Academic year condition if provided
        if (!empty($acc_year)) {
            $where .= " AND academic_year = ?";
            $params[] = $acc_year;
            $param_types .= "s";
        }

        // Main SQL query
        $sql_query = "SELECT SQL_CALC_FOUND_ROWS {$sql_columns} FROM {$table_with_counter} WHERE {$where} ORDER BY id DESC";

        // Add LIMIT if needed
        if ($limit !== "") {
            $sql_query .= " LIMIT ?, ?";
            $params[] = $start;
            $params[] = $limit;
            $param_types .= "ii";
        }

        // Total records function (used for display)
        $total_records = total_records();

        // Prepare and execute query
        $stmt = $mysqli->prepare($sql_query);
        if ($stmt) {
            $stmt->bind_param($param_types, ...$params);
            $stmt->execute();

            $res_array = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

            if ($res_array) {
                foreach ($res_array as $value) {
                    // Format status
                    if ($value['approval_status'] == 0) {

                        $acceptButton = '<button class="accept-btn" style="background-color: green; color: white; padding: 5px 10px; border: none; border-radius: 4px; margin-right: 8px;" data-hostel-id="' . $value['from_hostel'] . '" data-to-hostel="' . $value['to_hostel'] . '" data-district="' . $value['from_district'] . '" data-std-reg-no="' . $value['std_reg_no'] . '" data-taluk="' . $value['from_taluk'] . '" data-unique-id="' . $value['unique_id'] . '" data-std-id="' . $value['std_id'] . '" data-insert_type="' . $value['insert_type'] . '" data-to_taluk="' . $value['to_taluk'] . '" data-to_district="' . $value['to_district'] . '">Accept</button>';

                        $rejectButton = '<button class="reject-btn" style="background-color: red; color: white; padding: 5px 10px; border: none; border-radius: 4px; margin-right: 8px;"  data-hostel-id="' . $value['from_hostel'] . '" data-district="' . $value['from_district'] . '" data-taluk="' . $value['from_taluk'] . '" data-unique-id="' . $value['unique_id'] . '">Reject</button>';

                        $value['unique_id'] = $acceptButton . $rejectButton;

                    } elseif ($value['approval_status'] == 1) {

                        $value['unique_id'] = '<span style="color:green;">Accepted</span>';
                    } elseif ($value['approval_status'] == 2) {

                        $value['unique_id'] = '<span style="color:red;">Rejected</span>';
                    }

                    $value['from_hostel'] = hostel_name($value['from_hostel'])[0]['hostel_name'];

                    $value['from_district'] = district_name($value['from_district'])[0]['district_name'];
                    $value['from_taluk'] = taluk_name($value['taluk_name'])[0]['taluk_name'];

                    $value['is_active'] = is_active_show($value['is_active']);

                    $data[] = array_values($value);
                }
            }

            // Get filtered total
            $stmt_filtered = $mysqli->query("SELECT FOUND_ROWS()");
            $total_filtered = $stmt_filtered->fetch_row()[0];

            // JSON Response
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_filtered),
                "recordsFiltered" => intval($total_filtered),
                "data" => $data,
            ];

            $stmt->close();
        } else {
            // Fallback in case of error
            error_log("MySQLi error: " . $mysqli->error);
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
            ];
        }

        // Output JSON
        echo json_encode($json_array);
        break;



    case 'at_accept':

        $unique_id = $_POST['uniqueId'];

        $from_hostel = $_POST['from_hostel'];

        $to_hostel = $_POST['to_hostel'];

        $district = $_POST['district'];

        $taluk = $_POST['taluk'];

        $std_reg_no = $_POST['student_reg_no'];


        $std_id = $_POST['std_id'];
        $insert_type = $_POST['insert_type'];

        $approval_status = '1';

        $transfer_status = '0';
        // print_r('stmt');
        $table = 'student_transfer';


        $approved_date = date('Y-m-d');
        // Update for $table
        $stmt = $mysqli->prepare("UPDATE $table SET approval_status = ?, approved_date = ? WHERE unique_id = ?");


        $stmt->bind_param("sss", $approval_status, $approved_date, $unique_id);
        $status = 1;
        $stmt->execute();

        $action_obj = $stmt->affected_rows;
        $stmt->close();


        $table_ren = "renewal";

        $stmt1 = $mysqli->prepare("UPDATE $table_ren SET hostel_id = ?, transfer_status = ? WHERE std_reg_no = ?");
        $stmt1->bind_param("sss", $to_hostel, $transfer_status, $std_reg_no);
        $stmt1->execute();
        $action_obj1 = $stmt1->affected_rows;
        $stmt1->close();

        $table_std_app = "std_app_s";


        // //updating in renewal table 
        $stmt2 = $mysqli->prepare("UPDATE $table_std_app SET transfer_hostel = ? WHERE unique_id = ?");

        $stmt2->bind_param("ss", $to_hostel, $std_id);
        $status2 = 1;
        $stmt2->execute();

        $action_obj2 = $stmt2->affected_rows;

        $stmt2->close();

        if ($insert_type == 1) {
            $renewal_status = 4;
            $stmt3 = $mysqli->prepare("UPDATE $table_std_app SET renewal_status = ? WHERE unique_id = ?");
            $stmt3->bind_param("ss", $renewal_status, $std_id);
            $status3 = 1;
            $stmt3->execute();
            $action_obj3 = $stmt3->affected_rows;
            $stmt3->close();
        }



        // Handle errors if needed
        if ($action_obj === false) {
            $status = false; // Assuming $status is used to track success/failure
            $error = $mysqli->error;
            $msg = "error";
        } else {
            $status = true; // Assuming $status is used to track success/failure
            $msg = "success"; // Assuming this is the success message
        }


        $json_array = [
            "status" => $status,
            "error" => $error ?? null, // Set error to null if not defined
            "msg" => $msg,
            // Optionally include more data if needed
        ];

        echo json_encode($json_array);

        // Close MySQLi connection
        $mysqli->close();

        break;



    case 'new_app':


        $to_district = $_POST["to_district"];
        $to_hostel = $_POST["to_hostel"];
        $to_taluk = $_POST["to_taluk"];
        $old_s1_unique_id = $_POST["std_id"];
        $insert_type = $_POST["insert_type"];



        $uuid = $_POST["uuid"];

        $is_delete = '0';

        // Step 1: Fetch record from std_app_s
        $query = "SELECT * FROM std_app_s WHERE unique_id = '" . $old_s1_unique_id . "' and is_delete = 0 order by id desc limit 1";

        $stmt = $mysqli->prepare($query);
        // $stmt->bind_param("s", $old_s1_unique_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $main_data = $result->fetch_assoc();
        // print_r($main_data);
        if ($main_data) {
            // Step 2: Create a new unique ID
            $new_s1_unique_id = unique_id($prefix);

            // Replace values for insert

            $main_data['unique_id'] = $new_s1_unique_id;
            $main_data['entry_date'] = date('Y-m-d');
            $main_data['hostel_1'] = $to_hostel;
            $main_data['hostel_district_1'] = $to_district;
            $main_data['hostel_taluk_1'] = $to_taluk;
            $main_data['renewal_status'] = 4;

            // Remove the primary key or auto_increment field if needed
            unset($main_data['id']);
            unset($main_data['batch_no']);
            unset($main_data['batch_cr_date']);
            unset($main_data['hostel_2']);
            unset($main_data['hostel_3']);
            unset($main_data['hostel_district_2']);
            unset($main_data['hostel_district_3']);
            unset($main_data['hostel_taluk_2']);
            unset($main_data['hostel_taluk_3']);
            unset($main_data['status']);
            unset($main_data['submit_status']);
            unset($main_data['transfer_hostel']);
            unset($main_data['updated']);
            unset($main_data['created']);

            // Insert into std_app_s
            $columns = implode(",", array_keys($main_data));
            $placeholders = implode(",", array_fill(0, count($main_data), '?'));
            $types = str_repeat("s", count($main_data));
            $values = array_values($main_data);

            $insert = $mysqli->prepare("INSERT INTO std_app_s ($columns) VALUES ($placeholders)");
            $insert->bind_param($types, ...$values);
            $insert->execute();

            // // Function to duplicate child tables
            function duplicateChildTable($conn, $table, $old_id, $new_id)
            {
                $stmt = $conn->prepare("SELECT * FROM $table WHERE s1_unique_id = ? order by id desc limit 1");
                $stmt->bind_param("s", $old_id);
                $stmt->execute();
                $res = $stmt->get_result();
                while ($row = $res->fetch_assoc()) {
                    $row['s1_unique_id'] = $new_id;
                    $row['entry_date'] = date('Y-m-d');

                    unset($row['id']); // Remove primary key if auto_increment
                    $columns = implode(",", array_keys($row));
                    $placeholders = implode(",", array_fill(0, count($row), '?'));
                    $types = str_repeat("s", count($row));
                    $values = array_values($row);

                    $ins = $conn->prepare("INSERT INTO $table ($columns) VALUES ($placeholders)");
                    $ins->bind_param($types, ...$values);
                    $ins->execute();
                }
            }

            // Step 3: Copy data from child tables
            duplicateChildTable($mysqli, 'std_app_s2', $old_s1_unique_id, $new_s1_unique_id);
            duplicateChildTable($mysqli, 'std_app_s5', $old_s1_unique_id, $new_s1_unique_id);
            duplicateChildTable($mysqli, 'std_app_s6', $old_s1_unique_id, $new_s1_unique_id);
            duplicateChildTable($mysqli, 'std_app_emis_s3', $old_s1_unique_id, $new_s1_unique_id);
            duplicateChildTable($mysqli, 'std_app_umis_s4', $old_s1_unique_id, $new_s1_unique_id);

            $insertS7 = $mysqli->prepare("INSERT INTO std_app_s7 (s1_unique_id, hostel_name, hostel_district, hostel_taluk, priority, entry_date, gender_type, hostel_type, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $priority = 1;
            $today = date('Y-m-d');
            $gender_type = hostel_name($to_hostel)[0]['gender_type'];
            $hostel_type = hostel_name($to_hostel)[0]['hostel_type'];
            $insertS7->bind_param("sssssssss", $new_s1_unique_id, $to_hostel, $to_district, $to_taluk, $priority, $today, $gender_type, $hostel_type, unique_id());
            $insertS7->execute();

            $update_aadhar = $mysqli->prepare("UPDATE aadhar SET s1_unique_id = '$new_s1_unique_id' where s1_unique_id = '$old_s1_unique_id'");
            $update_aadhar->execute();

            echo json_encode(["status" => "succes", "new_s1_unique_id" => $new_s1_unique_id, "unique_id" => $old_s1_unique_id, "msg" => "otp"]);
        } else {
            echo json_encode(["status" => "error", "message" => "No record found."]);
        }

        break;



    case 'at_reject':

        $table = 'student_transfer';
        $unique_id = $_POST['uniqueId'];
        $status = '2';
        $reason = $_POST['reason'];


        $status_upd_date = date('Y-m-d');

        // Update for $table
        $stmt = $mysqli->prepare("UPDATE $table SET  reject_reason = ?, approval_status = ?,approved_date = ? WHERE unique_id = ?");
        $stmt->bind_param("ssss", $reason, $status, $status_upd_date, $unique_id);
        $status = 2;
        $stmt->execute();

        $action_obj = $stmt->affected_rows;
        $stmt->close();


        // Handle errors if needed
        if ($action_obj === false) {

            $status = false;
            $error = $mysqli->error;
            $msg = "error";
        } else {
            $status = true;
            $msg = "success_delete";
        }

        $json_array = [
            "status" => $status,
            "error" => $error ?? null,
            "msg" => $msg,
            // Optionally include more data if needed
        ];

        echo json_encode($json_array);

        // Close MySQLi connection
        $mysqli->close();

        break;


    case 'district_name':

        $district_name = $_POST['district_name'];


        $district_name_options = taluk_name('', $district_name);

        $taluk_name_options = select_option($district_name_options, "Select Taluk");

        echo $taluk_name_options;

        break;


    case 'delete':

        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        $unique_id = $_POST['unique_id'];

        $std_reg_no = $_POST['std_reg_no'];


        // Prepare SQL statement
        $sql = "UPDATE $table SET is_delete = ? WHERE unique_id = ?";

        // Prepare statement
        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            die('MySQL prepare error: ' . $mysqli->error);
        }

        // Bind parameters
        $is_delete = 1; // Assuming is_delete is an integer
        $stmt->bind_param('is', $is_delete, $unique_id);

        // Execute statement
        $stmt->execute();

        // Check for success
        if ($stmt->affected_rows > 0) {

            // Update transfer_status in renewal table
            $sql2 = "UPDATE renewal SET transfer_status = 0 WHERE std_reg_no = ?";
            $stmt2 = $mysqli->prepare($sql2);
            if ($stmt2 === false) {
                die('MySQL prepare error (transfer_status): ' . $mysqli->error);
            }

            $stmt2->bind_param('s', $std_reg_no);
            $stmt2->execute();
            $stmt2->close();

            $status = true;
            $data = "Successfully deleted";
            $error = "";
            $msg = "success_delete";
        } else {
            $status = false;
            $data = "";
            $error = "Delete operation failed";
            $msg = "error";
        }

        // Close statement
        $stmt->close();

        // Prepare JSON response
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg
        ];

        echo json_encode($json_array);
        break;


    case 'get_taluk':

        $to_district = $_POST['to_district'];


        $taluk_name_options = taluk_name('', $to_district);

        $taluk_name_options = select_option($taluk_name_options, "Select Taluk");

        echo $taluk_name_options;

        break;

    case 'get_hostel':

        $to_taluk = $_POST['to_taluk'];

        $gender_type = $_POST['gender_type'];


        $hostel_type = $_POST['hostel_type'];


        $hostel_name_options = hostel_gender_type('', $to_taluk, $gender_type, $hostel_type);

        $hostel_name_options = select_option_host($hostel_name_options, "Select Hostel");
        // print_r( $hostel_name_options);

        echo $hostel_name_options;

        break;


    case 'get_std_name':

        $std_id = $_POST['std_id'];

        // Validate $reg_no if needed

        // Assuming $mysqli is your MySQLi database connection
        $table = "std_reg_s";
        $columns = ["std_name", "std_reg_no"];
        $is_delete = '0';

        // Build SQL query with parameterized statement
        $sql = "SELECT " . implode(", ", $columns) . " FROM $table WHERE unique_id = ?";

        $stmt = $mysqli->prepare($sql);

        if ($stmt === false) {
            die('MySQL prepare error: ' . $mysqli->error);
        }

        // Bind parameter
        $stmt->bind_param("s", $std_id);

        // Execute statement
        $stmt->execute();

        // Bind result variables
        $stmt->bind_result($std_name, $std_reg_no);

        // Fetch result
        $stmt->fetch();

        // Close statement
        $stmt->close();

        if ($std_name !== null) {
            $json_array = [
                "student_name" => $std_name,
                "std_reg_no" => $std_reg_no,
            ];
        } else {
            $json_array = [
                "error" => "Student ID not found or query error"
            ];
        }

        echo json_encode($json_array);
        break;
}
