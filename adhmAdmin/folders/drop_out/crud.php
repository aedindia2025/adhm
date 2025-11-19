<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "dropout";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action = $_POST['action'];
// $action_obj         = (object) [
//     "status"    => 0,
//     "data"      => "",
//     "error"     => "Action Not Performed"
// ];

$json_array = "";
$sql = "";

$feedback_type = "";
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
        // Assuming $pdo->select, $pdo->update, and $pdo->insert are replaced with mysqli queries
        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

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
        $is_active = sanitizeInput($_POST["is_active"]);
        $unique_id = sanitizeInput($_POST["unique_id"]);

        // Check if student already exists
        $select_where = 'student_id = ? AND is_delete = 0';
        $params = [$student_id];

        if ($unique_id) {
            $select_where .= ' AND unique_id != ?';
            $params[] = $unique_id;
        }

        $select_stmt = $mysqli->prepare("SELECT COUNT(unique_id) AS count FROM $table WHERE $select_where");
        if ($select_stmt === false) {
            die('Select statement preparation failed: ' . $mysqli->error);
        }

        // Bind parameters
        $types = str_repeat('s', count($params));
        $select_stmt->bind_param($types, ...$params);

        // Execute query
        $select_stmt->execute();

        // Bind result variables
        $select_stmt->bind_result($count);

        // Fetch result
        $select_stmt->fetch();

        // Close statement
        $select_stmt->close();

        if ($count) {
            $msg = "already";
        } else {
            if ($unique_id) {
                // Update existing record
                $update_columns = [
                    "student_name" => $student_name,
                    "drop_discontinue_date" => $drop_discontinue_date,
                    "reason" => $reason,
                    "staff_id" => $staff_id,
                    "staff_name" => $staff_name,
                    "district_id" => $district_id,
                    "taluk_id" => $taluk_id,
                    "hostel_id" => $hostel_id,
                    "is_active" => $is_active,
                ];

                // Build update query
                $update_query = "UPDATE $table SET ";
                foreach ($update_columns as $key => $value) {
                    $update_query .= "$key = ?, ";
                }
                $update_query = rtrim($update_query, ', ');
                $update_query .= " WHERE unique_id = ?";

                $update_params = array_values($update_columns);
                $update_params[] = $unique_id;

                $update_stmt = $mysqli->prepare($update_query);
                if ($update_stmt === false) {
                    die('Update statement preparation failed: ' . $mysqli->error);
                }

                // Bind parameters
                $types = str_repeat('s', count($update_params));
                $update_stmt->bind_param($types, ...$update_params);

                // Execute update query
                $update_stmt->execute();

                // Check for update success
                if ($update_stmt->affected_rows > 0) {
                    $msg = "update";
                } else {
                    $msg = "error";
                }

                // Close statement
                $update_stmt->close();
            } else {
                // Insert new record
                $insert_columns = [
                    "student_id" => $student_id,
                    "student_name" => $student_name,
                    "drop_discontinue_date" => $drop_discontinue_date,
                    "reason" => $reason,
                    "staff_id" => $staff_id,
                    "staff_name" => $staff_name,
                    "district_id" => $district_id,
                    "taluk_id" => $taluk_id,
                    "hostel_id" => $hostel_id,
                    "is_active" => $is_active,
                    "unique_id" => unique_id($prefix) // Assuming unique_id function exists
                ];

                // Build insert query
                $insert_query = "INSERT INTO $table (";
                $insert_columns_str = implode(", ", array_keys($insert_columns));
                $insert_query .= $insert_columns_str . ") VALUES (";
                $insert_query .= rtrim(str_repeat('?, ', count($insert_columns)), ', ') . ")";

                $insert_stmt = $mysqli->prepare($insert_query);
                if ($insert_stmt === false) {
                    die('Insert statement preparation failed: ' . $mysqli->error);
                }

                // Bind parameters
                $types = str_repeat('s', count($insert_columns));
                $insert_stmt->bind_param($types, ...array_values($insert_columns));

                // Execute insert query
                $insert_stmt->execute();

                // Check for insert success
                if ($insert_stmt->affected_rows > 0) {
                    $msg = "create";
                } else {
                    $msg = "error";
                }

                // Close statement
                $insert_stmt->close();
            }
        }

        // Prepare response JSON
        $json_array = [
            "status" => true, // Assuming success based on your implementation
            "data" => [], // Assuming data to be empty array
            "error" => "", // Assuming error to be empty string
            "msg" => $msg,
            // "sql"       => $sql
        ];

        // Output JSON
        echo json_encode($json_array);

        // Close connection
        $mysqli->close();

        break;

    case 'datatable':
        // DataTable Variables
        $length = isset($_POST['length']) ? $_POST['length'] : 10;
        $start = isset($_POST['start']) ? $_POST['start'] : 0;
        $draw = isset($_POST['draw']) ? $_POST['draw'] : 1;
        $limit = $length;

        $data = [];

        $district_name = sanitizeInput($_POST["district_name"]);
        $taluk_name = sanitizeInput($_POST["taluk_name"]);
        $hostel_name = sanitizeInput($_POST["hostel_name"]);

        if ($length == '-1') {
            $limit = "";
        }

        // Query Variables
        $columns = [
            "@a:=@a+1 s_no",
            "(select hostel_name from hostel_name where hostel_name.unique_id = dropout.hostel_id) as hostel_name",

            "student_name",
            "(SELECT std_reg_no FROM std_reg_s WHERE std_reg_s.unique_id = dropout.student_id) AS student_id",
            "dropout_reason",
            "status",
            "status_upd_date",
            // "is_active",
            "unique_id",
            "cust_reason"
        ];

        // $table = "your_table_name"; // Replace with your actual table name
        $table_details = "$table , (SELECT @a:= ?) AS a ";
        $where = "is_delete = 0";

        // Prepare WHERE clause based on filters
        $params = array(0); // Starting value for @a


        if (!empty($district_name)) {
            $where .= " AND district_id = ?";
            $params[] = $district_name;
        }
        if (!empty($taluk_name)) {
            $where .= " AND taluk_id = ?";
            $params[] = $taluk_name;
        }
        if (!empty($hostel_name)) {
            $where .= " AND hostel_id = ?";
            $params[] = $hostel_name;
        }
        // Datatable Searching
        $sql_function = "SQL_CALC_FOUND_ROWS";

        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_details WHERE $where";
        if ($length != '-1') {
            $sql .= " LIMIT ?, ?";
            $params[] = intval($start);
            $params[] = intval($length);
        }
        // print_r($sql);

        // Prepare and execute SQL query with parameter binding
        $stmt = $mysqli->prepare($sql);

        if ($stmt) {
            // Dynamically bind parameters based on types (all assumed to be strings here)
            $types = str_repeat('s', count($params)); // Assuming all parameters are strings
            $stmt->bind_param($types, ...$params);
            $stmt->execute();

            // Get result and total records count
            $result = $stmt->get_result();
            $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
            $total_records = $total_records_result->fetch_assoc()['total'];

            // Check if query execution was successful
            if ($result) {
                // Fetch all rows into an associative array
                $res_array = $result->fetch_all(MYSQLI_ASSOC);


                // Get total records count (unfiltered)
                $total_records_query = "SELECT FOUND_ROWS() AS total";
                $total_result = $mysqli->query($total_records_query);
                $total_records = 0;
                if ($total_result) {
                    $total_records_row = $total_result->fetch_assoc();
                    $total_records = isset($total_records_row['total']) ? $total_records_row['total'] : 0;
                }

                // Process data for DataTables format
                foreach ($res_array as $key => $value) {
                    $unique_id = $value['unique_id'];

                    if($value['dropout_reason'] == '673f05bd7d90c91668'){
                        $value['dropout_reason'] = $value['cust_reason'];
                        }else{
                            $value['dropout_reason'] = dropout_reason($value['dropout_reason'])[0]['dropout_reason'];
                        }

                        if(!$value['status_upd_date']){
                            $value['status_upd_date'] = '-';
                        }

                        if($value['status'] == '1'){
                            $value['status'] = '<p style="color:green">Approved</p>';
                        }elseif($value['status'] == '2'){
                            $value['status'] = '<p style="color:red">Rejected</p>';
                        }else{
                            $value['status'] = '<p style="color:blue">Pending</p>';
                        }
                        
                    $eye_button = '<a class="btn btn-action specl2"  href="javascript:leave_print(\'' . $unique_id . '\')"><button type="button" ><i class="fa fa-eye" ></i></button></a>';

                    $value['unique_id'] = $eye_button;
                    $data[] = array_values($value);
                }

                // Prepare response JSON
                $json_array = [
                    "draw" => intval($draw),
                    "recordsTotal" => intval($total_records),
                    "recordsFiltered" => intval($total_records),
                    "data" => $data,
                    // "testing"           => $query
                ];
            } else {
                // Handle query execution failure
                $json_array = [
                    "draw" => intval($draw),
                    "recordsTotal" => 0,
                    "recordsFiltered" => 0,
                    "data" => [],
                    // "error"             => $mysqli->error
                ];
            }
            $stmt->close();
        } else {
            // Handle prepare statement error
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => $mysqli->error
            ];
        }
        // Output JSON
        echo json_encode($json_array);

        // Close connection
        $mysqli->close();


        break;
    

    case 'district_name':

        $district_name = $_POST['district_name'];


        $district_name_options = taluk_name('', $district_name);

        $taluk_name_options = select_option($district_name_options, "Select Taluk");

        echo $taluk_name_options;

        break;


    case 'get_hostel_by_taluk_name':

        $taluk_name = $_POST['taluk_name'];


        $hostel_name_options = hostel_name('', $taluk_name);

        $hostel_name_options = select_option_host($hostel_name_options, "Select Hostel");

        echo $hostel_name_options;

        break;


    case 'delete':

        // Assuming $unique_id is passed via POST
        $unique_id = $_POST['unique_id'];

        // Update columns and where condition
        $columns = [
            "is_delete" => 1
        ];

        $update_where = [
            "unique_id" => $unique_id
        ];

        // Build update query
        $update_query = "UPDATE $table SET ";
        $update_params = [];
        foreach ($columns as $key => $value) {
            $update_query .= "$key = ?, ";
            $update_params[] = $value;
        }
        $update_query = rtrim($update_query, ', ');
        $update_query .= " WHERE unique_id = ?";

        $update_params[] = $unique_id;

        $update_stmt = $mysqli->prepare($update_query);
        if ($update_stmt === false) {
            die('Update statement preparation failed: ' . $mysqli->error);
        }

        // Bind parameters
        $types = str_repeat('s', count($update_params));
        $update_stmt->bind_param($types, ...$update_params);

        // Execute update query
        $update_stmt->execute();

        // Check for update success
        if ($update_stmt->affected_rows > 0) {
            $status = true;
            $msg = "success_delete";
            $error = "";
            $data = [];
        } else {
            $status = false;
            $msg = "error";
            $error = "No rows updated";
            $data = [];
        }

        // Close statement
        $update_stmt->close();

        // Prepare response JSON
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql" => $update_query
        ];

        // Output JSON
        echo json_encode($json_array);

        // Close connection
        $mysqli->close();

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
                "testing" => $result->sql
            ];
        } else {
            print_r($result);
        }

        echo json_encode($json_array);
        break;


    default:

        break;
}

?>