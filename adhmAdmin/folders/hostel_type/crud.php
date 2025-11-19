<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "hostel_type";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';


// // Variables Declaration
$action = $_POST['action'];

$hostel_type = "";
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

$token = $_POST['csrf_token'];
if (!validateCSRFToken($token)) {
    die('CSRF validation failed.');
}

        $hostel_type = sanitizeInput($_POST["hostel_type"]);
        $is_active = sanitizeInput($_POST["is_active"]);
        $unique_id = sanitizeInput($_POST["unique_id"]);

        $update_where = "";

        $columns = [
            "hostel_type" => $hostel_type,
            "is_active" => $is_active,
            "unique_id" => unique_id($prefix)
        ];

        // Check already Exist Or not
        $select_where = 'hostel_type = ? AND is_delete = 0';

        // When Update Check without current id
        if ($unique_id) {
            $select_where .= ' AND unique_id != ?';
        }

        $stmt = $mysqli->prepare("SELECT COUNT(unique_id) AS count FROM $table WHERE $select_where");
        if ($unique_id) {
            $stmt->bind_param("ss", $hostel_type, $unique_id);
        } else {
            $stmt->bind_param("s", $hostel_type);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        if ($data["count"]) {
            $msg = "already";
	    $status = true;
        } else if ($data["count"] == 0) {
            // Update Begins
            if ($unique_id) {
                unset($columns['unique_id']);
                $stmt = $mysqli->prepare("UPDATE $table SET hostel_type = ?, is_active = ? WHERE unique_id = ?");
                $stmt->bind_param("sss", $hostel_type, $is_active, $unique_id);
                // Update Ends
            } else { 
                // Insert Begins
                $stmt = $mysqli->prepare("INSERT INTO $table (hostel_type, is_active, unique_id) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $hostel_type, $is_active, $columns['unique_id']);
                // Insert Ends
            }

            $status = $stmt->execute();
            if ($status) {
                $data = [];
                $error = "";
                $sql = "";

                if ($unique_id) {
                    $msg = "update";
                } else {
                    $msg = "create";
                }
            } else {
                $status = false;
                $data = [];
                $error = $stmt->error;
                $sql = "";
                $msg = "error";
            }
        }

        $json_array = [
            "status" => $status,
            "data" => [
                "unique_id" => $unique_id ? $unique_id : $columns['unique_id'],
            ],
            "error" => $error,
            "msg" => $msg,
            // "sql" => $sql
        ];

        echo json_encode($json_array);

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
            "hostel_type",
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
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => $stmt->error
            ];
        }

        echo json_encode($json_array);
        break;


        case 'delete':
            
$token = $_POST['csrf_token'];
if (!validateCSRFToken($token)) {
    die('CSRF validation failed.');
}
        
            $unique_id = $_POST['unique_id'];
    
            $stmt = $mysqli->prepare("UPDATE $table SET is_delete = 1 WHERE unique_id = ?");
            $stmt->bind_param("s", $unique_id);
            $status = $stmt->execute();
    
            if ($status) {
                $data = [];
                $error = "";
                $sql = "";
                $msg = "success_delete";
    
            } else {
                $data = [];
                $error = $stmt->error;
                $sql = "";
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

    default:

        break;
}


?>