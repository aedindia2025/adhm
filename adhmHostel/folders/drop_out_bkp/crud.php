<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// Database Country Table Name
$table = "dropout";

// Include DB file and Common Functions
include '../../config/dbconfig.php';

// Variables Declaration
$action = $_POST['action'];

$json_array = "";
$sql = "";

$feedback_type = "";
//$is_active = "";
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

        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        $vali_student_id = filter_input(INPUT_POST, 'student_id', FILTER_SANITIZE_STRING);
        $vali_student_name = filter_input(INPUT_POST, 'student_name', FILTER_SANITIZE_STRING);
        $vali_drop_discontinue_date = filter_input(INPUT_POST, 'drop_discontinue_date', FILTER_SANITIZE_STRING);
        $vali_reason = filter_input(INPUT_POST, 'reason', FILTER_SANITIZE_STRING);
        $vali_staff_id = filter_input(INPUT_POST, 'staff_id', FILTER_SANITIZE_STRING);
        $vali_staff_name = filter_input(INPUT_POST, 'staff_name', FILTER_SANITIZE_STRING);
        $vali_academic_year = filter_input(INPUT_POST, 'academic_year', FILTER_SANITIZE_STRING);
        $vali_district_id = filter_input(INPUT_POST, 'district_id', FILTER_SANITIZE_STRING);
        $vali_taluk_id = filter_input(INPUT_POST, 'taluk_id', FILTER_SANITIZE_STRING);
        $vali_hostel_id = filter_input(INPUT_POST, 'hostel_id', FILTER_SANITIZE_STRING);

        // if (
        //     !$vali_student_id || !$vali_student_name || !$vali_drop_discontinue_date || !$vali_reason || !$vali_staff_name
        //     || !$vali_academic_year || !$vali_district_id || !$vali_taluk_id || !$vali_hostel_id
        // ) {
        //     $msg = "form_alert";
        //     // echo "dd";
        // } else {

            $student_id = sanitizeInput($_POST["student_id"]);
            $student_name = sanitizeInput($_POST["student_name"]);
            $drop_discontinue_date = sanitizeInput($_POST["drop_discontinue_date"]);
            $reason = sanitizeInput($_POST["reason"]);
            $staff_id = sanitizeInput($_POST["staff_id"]);
            $staff_name = sanitizeInput($_POST["staff_name"]);
            $academic_year = sanitizeInput($_POST["academic_year"]);
            $district_id = sanitizeInput($_POST["district_id"]);
            $taluk_id = sanitizeInput($_POST["taluk_id"]);
            $hostel_id = sanitizeInput($_POST["hostel_id"]);
            $acc_year = sanitizeInput($_SESSION["academic_year"]);

            $unique_id = $_POST["unique_id"];

            $columns = [
                "student_id" => $student_id,
                "student_name" => $student_name,
                "drop_discontinue_date" => $drop_discontinue_date,
                "reason" => $reason,
                "staff_id" => $staff_id,
                "staff_name" => $staff_name,
                "district_id" => $district_id,
                "taluk_id" => $taluk_id,
                "hostel_id" => $hostel_id,
                "acc_year" => $acc_year,
                "unique_id" => unique_id($prefix)
            ];

            // Check if record already exists
            $select_query = 'SELECT COUNT(unique_id) AS count FROM ' . $table . ' WHERE student_id = ? AND is_delete = ?';
            $select_params = [$student_id, 0];
            $select_types = "si";

            if ($unique_id) {
                $select_query .= ' AND unique_id != ?';
                $select_params[] = $unique_id;
                $select_types .= "s";
            }

            $stmt = $mysqli->prepare($select_query);
            $stmt->bind_param($select_types, ...$select_params);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();

            if ($data["count"] > 0) {
                $msg = "already";
            } else {
                if ($unique_id) {
                    unset($columns['unique_id']);
                    $update_query = 'UPDATE ' . $table . ' SET student_id = ?, student_name = ?, drop_discontinue_date = ?, reason = ?, staff_id = ?, staff_name = ?, district_id = ?, taluk_id = ?, hostel_id = ?, acc_year = ? WHERE unique_id = ?';
                    $update_params = array_values($columns);
                    $update_params[] = $unique_id;
                    $update_types = str_repeat("s", count($columns)) . "s";

                    $stmt = $mysqli->prepare($update_query);
                    $stmt->bind_param($update_types, ...$update_params);
                    $action_result = $stmt->execute();

                    if ($action_result) {
                        $msg = "update";
                    } else {
                        $msg = "error";
                    }
                } else {
                    $insert_keys = implode(", ", array_keys($columns));
                    $insert_placeholders = implode(", ", array_fill(0, count($columns), "?"));

                    $insert_query = 'INSERT INTO ' . $table . ' (' . $insert_keys . ') VALUES (' . $insert_placeholders . ')';
                    $stmt = $mysqli->prepare($insert_query);

                    $insert_params = array_values($columns);
                    $insert_types = str_repeat("s", count($insert_params));

                    $stmt->bind_param($insert_types, ...$insert_params);
                    $action_result = $stmt->execute();

                    if ($action_result) {
                        $update_columns = [
                            "dropout_status" => '2',
                            "dropout_date" => date('Y-m-d')
                        ];

                        $update_main_query = 'UPDATE std_reg_s SET dropout_status = ?, dropout_date = ? WHERE unique_id = ?';
                        $update_main_params = [$update_columns['dropout_status'], $update_columns['dropout_date'], $student_id];
                        $update_main_types = "sss";

                        $stmt = $mysqli->prepare($update_main_query);
                        $stmt->bind_param($update_main_types, ...$update_main_params);
                        $update_action_result = $stmt->execute();

                        if ($update_action_result) {
                            $msg = "create";
                        } else {
                            $msg = "error";
                        }

                    } else {
                        $msg = "error";
                    }
                }

            }
        // }

        $json_array = [
            "status" => isset($status) ? $status : false,
            "data" => isset($data) ? $data : [],
            "error" => isset($error) ? $error : '',
            "msg" => $msg
        ];

        echo json_encode($json_array);

        // Close connection

        // $mysqli->close();

        break;


        case 'datatable':
            // DataTable Variables
            $length = isset($_POST['length']) ? $_POST['length'] : 10;
            $start = isset($_POST['start']) ? $_POST['start'] : 0;
            $draw = isset($_POST['draw']) ? $_POST['draw'] : 1;
            $limit = $length;
        
            if ($length == '-1') {
                $limit = "";
            }
        
            // Initialize response data
            $data = [];
            $json_array = [];
            
            // SQL Query Variables
            $columns = [
                "@a:=@a+1 s_no",
                "student_name",
                "(SELECT std_reg_no FROM std_reg_s WHERE std_reg_s.unique_id = dropout.student_id) AS student_id",
                "reason",
                "unique_id"
            ];
            
            $sql_columns = implode(", ", $columns);
            $table_with_counter = $table . ", (SELECT @a:=?) AS a";
            $where = "is_delete = ? AND hostel_id = ?";
            $is_delete = "0";
            // Initialize total records variable
            $total_records = total_records();
            
            // Prepare SQL query
            $sql_query = "SELECT SQL_CALC_FOUND_ROWS {$sql_columns} FROM {$table_with_counter} WHERE {$where}";
            
            if ($limit !== "") {
                $sql_query .= " LIMIT ?, ?";
            }
        
            // Execute query with parameterized statements
            $stmt = $mysqli->prepare($sql_query);
            if ($stmt) {
                // Bind parameters
                if ($limit !== "") {
                    $stmt->bind_param("issii", $start, $is_delete,$_SESSION['hostel_id'], $start, $limit);
                } else {
                    $stmt->bind_param("iss", $start, $is_delete,$_SESSION['hostel_id']);
                }
        
                // Execute the query
                $stmt->execute();
                
                // Fetch the result
                $result = $stmt->get_result();
                
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        $unique_id = $row['unique_id'];
                        $btn_update = btn_update($folder_name, $unique_id);
                        $btn_delete = btn_delete($folder_name, $unique_id);
                        $eye_button = '<a class="btn btn-action specl2" href="javascript:leave_print(\'' . $unique_id . '\')"><button type="button"><i class="fa fa-eye"></i></button></a>';
            
                        $row['unique_id'] = $btn_update . $btn_delete . $eye_button;
                        $data[] = array_values($row);
                    }
        
                    // Fetch the total filtered records
                    $stmt_filtered = $mysqli->prepare("SELECT FOUND_ROWS()");
                    if ($stmt_filtered) {
                        $stmt_filtered->execute();
                        $stmt_filtered->bind_result($total_filtered);
                        $stmt_filtered->fetch();
                        $stmt_filtered->close();
                    } else {
                        $total_filtered = $total_records;
                    }
        
                    // Prepare JSON response
                    $json_array = [
                        "draw" => intval($draw),
                        "recordsTotal" => intval($total_filtered),
                        "recordsFiltered" => intval($total_filtered),
                        "data" => $data,
                    ];
                }
                
                $stmt->close();
            }
        
            // Output JSON response
            echo json_encode($json_array);
        
            break;
        

    case 'delete':
        
        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }
        
        $unique_id = $_POST['unique_id'];

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

    case 'get_std_name':

        $student_id = $_POST['student_id'];


        // Query Variables
        $json_array = [];
        $columns = [

            "std_name"

        ];
        $table_details = [
            "std_reg_s2",
            $columns
        ];
        $where = "is_delete = 0 and s1_unique_id = '" . $student_id . "'";




        $sql_function = "SQL_CALC_FOUND_ROWS";

        $result = $pdo->select($table_details, $where);
        // print_r($result);
        $total_records = total_records();

        if ($result->status) {

            $res_array = $result->data;

            foreach ($res_array as $key => $value) {


                $student_name = $value['std_name'];

                // $data[]             = array_values($value);
            }

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data,
                "student_name" => $student_name,
                // "testing"           => $result->sql
            ];
        } else {
            // print_r($result);
        }

        echo json_encode($json_array);
        break;

    default:

        break;
}

?>