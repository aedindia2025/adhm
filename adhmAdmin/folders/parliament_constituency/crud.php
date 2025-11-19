<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "parliament_constituency";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action = $_POST['action'];

$parliament_constituency = "";
$district_name = "";

$is_active = "";
$unique_id = "";
$prefix = "";

$data = "";
$msg = "";
$error = "";
$status = "";
$test = ""; // For Developer Testing Purpose
// $mysqli = new mysqli("localhost", "adhm_root", "!qIWrUoGzQp6shOE", "adi_dravidar");

$action = isset($_POST['action']) ? $_POST['action'] : '';

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

        // Retrieve POST data
        $district_name = sanitizeInput($_POST["district_name"]);
        $parliament_const_name = sanitizeInput($_POST["parliament_const_name"]);
        $is_active = $_POST["is_active"];
        $unique_id = $_POST["unique_id"];

        // Prepare update columns
        $columns = [
            "district_name" => $district_name,
            "parliament_const_name" => $parliament_const_name,
            "is_active" => $is_active,
            "unique_id" => $unique_id
        ];

        // Check if unique_id exists for update scenario
        if ($unique_id) {
            $update_where = "unique_id = ?";
            $query = "UPDATE $table SET district_name=?, parliament_const_name=?, is_active=? WHERE unique_id=?";
        } else {
            // Insert scenario
            $columns['unique_id'] = unique_id($prefix); // Assuming unique_id function generates the ID
            $query = "INSERT INTO $table (district_name, parliament_const_name, is_active, unique_id) VALUES (?, ?, ?, ?)";
        }
                                                                     
        // Prepare and bind parameters
        $stmt = $mysqli->prepare($query);
        if (!$stmt) {
            $json_array = [
                "status" => false,
                "error" => "Prepare statement failed: " . $mysqli->error
            ];
            echo json_encode($json_array);
            break;
        }

        // Bind parameters
        if ($unique_id) {
            $stmt->bind_param("ssss", $district_name,  $parliament_const_name, $is_active, $unique_id);
        } else {
            $stmt->bind_param("ssss", $district_name,  $parliament_const_name, $is_active, $columns['unique_id']);
        }

        // Execute the statement
        // $stmt->execute();

        if ($stmt->execute()) {
            if ($unique_id) {
                $msg = "update";
            } else {
                $msg = "create";
            }
            $status = true;
            $data = ["affected_rows" => $stmt->affected_rows];
            $error = "";
        } else {
            $status = false;
            $data = [];
            $error = "Execute statement failed: " . $stmt->error;
            $msg = "error";
        }

        // Close statement
        $stmt->close();

        // JSON response 
        $json_array = [
            "status" => $status,
            "data" => [
                "unique_id" => $unique_id ? $unique_id : $columns['unique_id'],
            ],
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
            "(SELECT district_name FROM district_name AS district_name WHERE district_name.unique_id = " . $table . ".district_name ) AS district_name",
            "parliament_const_name",
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
            $stmt->bind_param('s', $unique_id);
            if ($stmt->execute()) {
                $status = true;
                $msg = "success_delete";
            } else {
                $status = false;
                $error = $stmt->error;
                $msg = "error";
            }
            $stmt->close();
    
            $json_array = [
                "status" => $status,
                "data" => $data,
                "error" => $error,
                "msg" => $msg,
            ];
    
            echo json_encode($json_array);
            break;

   

    default:

        break;
}


?>