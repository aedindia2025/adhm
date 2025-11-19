<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "standard_creation";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

function validateCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// // Variables Declaration
$action = $_POST['action'];

$school_unique_id = "";
$standard = "";
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
        $token = $_POST['csrf_token'];
        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }
        // Assuming these are the inputs from your form or wherever you're getting them
        $school_unique_id = sanitizeInput($_POST["school_unique_id"]);
        $standard = sanitizeInput($_POST["standard"]);
        $is_active = sanitizeInput($_POST["is_active"]);
        $unique_id = sanitizeInput($_POST["unique_id"]);

        // Initialize variables
        $status = false;
        $data = [];
        $error = "";
        $msg = "";

        // Prepare columns array
        $columns = [
            "school_unique_id" => $school_unique_id,
            "standard" => $standard,
            "is_active" => $is_active,
            "unique_id" => unique_id($prefix) // Ensure $prefix is defined or replaced appropriately
        ];

        // Check if the entry already exists
        $select_query = "SELECT COUNT(unique_id) AS count FROM $table WHERE school_unique_id = ? AND standard = ? AND is_active = ? AND is_delete = 0";

        // If updating, exclude the current record
        if ($unique_id) {
            $select_query .= " AND unique_id != ?";
        }

        // Prepare statement for select query
        $stmt = $mysqli->prepare($select_query);

        // Bind parameters for select query
        if ($unique_id) {
            $stmt->bind_param('ssss', $school_unique_id, $standard, $is_active, $unique_id);
        } else {
            $stmt->bind_param('sss', $school_unique_id, $standard, $is_active);
        }

        // Execute select query
        $stmt->execute();

        // Get result of select query
        $result = $stmt->get_result();

        // Fetch data from result
        $data = $result->fetch_assoc();

        // Handle result of select query
        if ($data['count']) {
            $msg = "already";
            $status = true;
        } else {
            // Perform update or insert based on whether unique_id is set
            if ($unique_id) {
                // Update existing record
                unset($columns['unique_id']);
                $update_query = "UPDATE $table SET school_unique_id = ?, standard = ?, is_active = ? WHERE unique_id = ?";
                $stmt = $mysqli->prepare($update_query);
                $stmt->bind_param('ssss', $school_unique_id, $standard, $is_active, $unique_id);
            } else {
                // Insert new record
                $insert_query = "INSERT INTO $table (school_unique_id, standard, is_active, unique_id) VALUES (?, ?, ?, ?)";
                $stmt = $mysqli->prepare($insert_query);
                $stmt->bind_param('ssss', $school_unique_id, $standard, $is_active, $columns['unique_id']);
            }

            // Execute update or insert query
            if ($stmt->execute()) {
                $status = true;
                $msg = $unique_id ? "update" : "create";
            } else {
                $error = $stmt->error;
                $msg = "error";
            }
        }

        // Prepare response as JSON
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg
        ];

        echo json_encode($json_array);

        // Close statement and connection
        $stmt->close();
        // $mysqli->close();

        break;


    case 'datatable':
        // DataTable Variables
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;

        $data = [];

        // Query Variables
        $json_array = "";
        $columns = [
            "@a:=@a+1 s_no",
            '(SELECT school_name FROM school_name AS dis WHERE dis.unique_id = ' . $table . '.school_unique_id) AS school_name',
            "standard",
            "is_active",
            "unique_id"
        ];
        $table_details = "$table , (SELECT @a:= ?) AS a";
        $where = "is_delete = 0";
        $bind_params = "i"; // Types of parameters (i for integer)
        $bind_values = [$start];


        $sql_function = "SQL_CALC_FOUND_ROWS";
        $order_by = ""; // Set the order by clause if necessary

        // SQL query for data fetching
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_details WHERE $where";
        if ($limit !== "") {
            $sql .= " LIMIT ?, ?";
            $bind_params .= "ii"; // Add types for limit parameters
            $bind_values[] = $start;
            $bind_values[] = $limit;
        }

        $stmt = $mysqli->prepare($sql);

        if ($stmt === false) {
            // Handle prepare error
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => $mysqli->error
            ];
            echo json_encode($json_array);
            break;
        }

        // Bind parameters dynamically
        $bind_params_arr = array_merge([$bind_params], ...array_map(function ($v) {
            return [$v];
        }, $bind_values));
        call_user_func_array([$stmt, 'bind_param'], $bind_params_arr);

        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch total records
        $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
        $total_records = $total_records_result->fetch_assoc()['total'];

        if ($result) {

            $res_array = $result->fetch_all(MYSQLI_ASSOC);

            foreach ($res_array as $key => $value) {

                // $value['school_unique_id$school_unique_id']     = disname($value['school_unique_id$school_unique_id']);
                // $value['school_unique_id']     = school_name($value['school_unique_id']);   
                // // $value['school_unique_id'] = disname($value['school_unique_id']);
                // $value['standard']     = disname($value['standard']);

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
                // "testing"           => $result->sql
            ];
        } else {
            // print_r($result);
        }

        echo json_encode($json_array);
        break;


    case 'delete':
        $token = $_POST['csrf_token'];
        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }
        $unique_id = $_POST['unique_id'];

        // Prepare SQL statement
        $sql = "UPDATE $table SET is_delete = 1 WHERE unique_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $unique_id);

        // Execute SQL statement
        if ($stmt->execute()) {
            $status = "success";
            $msg = "success_delete";
        } else {
            $status = "error";
            $msg = "error";
        }

        $stmt->close();

        $json_array = [
            "status" => $status,
            "msg" => $msg,
        ];

        echo json_encode($json_array);
        break;

    default:

        break;
}


?>