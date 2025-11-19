<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];



function validateCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
// // Database Country Table Name
$table = "institution_master";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action = $_POST['action'];

$zone_name = "";
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
        // Sanitize and fetch input
        $token = $_POST['csrf_token'];
        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }
        $district_name = sanitizeInput($_POST["district_name"]);
        $institution_name = sanitizeInput($_POST["institution_name"]);
        $is_active = sanitizeInput($_POST["is_active"]);
        $unique_id = sanitizeInput($_POST["unique_id"]);
    
    
        // Check if the entry already exists
        $select_sql = "SELECT COUNT(unique_id) AS count FROM $table WHERE institution_name = ? AND is_delete = 0";
        if ($unique_id) {
            $select_sql .= " AND unique_id != ?";
        }
    
        $stmt = $mysqli->prepare($select_sql);
    
        if ($unique_id) {
            $stmt->bind_param("ss", $institution_name, $unique_id);
        } else {
            $stmt->bind_param("s", $institution_name);
        }
    
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
    
        $msg = "";
        if ($data["count"] > 0) {
            $msg = "already";
	    $status = true;
        } else {
            // Prepare insert/update data
            if ($unique_id) {
                $update_sql = "UPDATE $table SET district_name = ?, institution_name = ?, is_active = ? WHERE unique_id = ?";
                $stmt = $mysqli->prepare($update_sql);
                $stmt->bind_param("ssss", $district_name, $institution_name, $is_active, $unique_id);
            } else {
                $columns = [
                    "district_name" => $district_name,
                    "institution_name" => $institution_name,
                    "is_active" => $is_active,
                    "unique_id" => unique_id($prefix)
                ];
                $insert_sql = "INSERT INTO $table (district_name, institution_name, is_active, unique_id) VALUES (?, ?, ?, ?)";
                $stmt = $mysqli->prepare($insert_sql);
                $stmt->bind_param("ssss", $district_name, $institution_name, $is_active, $columns['unique_id']);
            }
    
            if ($stmt->execute()) {
                $status = true;
                $msg = $unique_id ? "update" : "create";
                $data = $unique_id ? $unique_id : $stmt->insert_id;
                $error = "";
            } else {
                $status = false;
                $msg = "error";
                $error = $stmt->error;
                $data = [];
            }
        }
    
        $json_array = [
            "status" => $status,
            "data" => [
                "unique_id" => $unique_id ? $unique_id : $columns['unique_id'],
            ],
            "error" => $error ?? "",
            "msg" => $msg
        ];
    
        echo json_encode($json_array);
    
        $stmt->close();
        $mysqli->close();
    
        break;
    
    


    case 'datatable':
        // DataTable Variables
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
            "(select district_name from district_name where district_name.unique_id=.$table.district_name) as district_name",
            "institution_name",
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
                $value['district_name'] = disname($value['district_name']);
                $value['institution_name'] = disname($value['institution_name']);
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


        // Prepare the update query to mark the record as deleted
        $update_sql = 'UPDATE ' . $table . ' SET is_delete = ? WHERE unique_id = ?';
        $is_delete = 1;

        $stmt = $mysqli->prepare($update_sql);
        if ($stmt === false) {
            die('MySQL prepare error: ' . $mysqli->error);
        }

        // Bind the parameters to the query
        $stmt->bind_param('is', $is_delete, $unique_id);

        // Execute the query
        // $stmt->execute();

        // Prepare the JSON response
        if ($stmt->execute()) {
            $json_array = [
                "status" => true,
                "msg" => "success_delete"
            ];
        } else {
            $json_array = [
                "status" => false,
                "error" => $stmt->error,
                "msg" => "error"
            ];
        }

        // Close the statement
        $stmt->close();

        // Output the JSON response
        echo json_encode($json_array);
        break;

    default:

        break;
}
?>