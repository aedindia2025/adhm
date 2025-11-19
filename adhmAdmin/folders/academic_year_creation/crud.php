<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "academic_year_creation";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

function validateCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
// // Variables Declaration
$action = $_POST['action'];

$fund_name = "";
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
        $from_year = sanitizeInput($_POST["from_year"]);
        $to_year = sanitizeInput($_POST["to_year"]);
        $amc_year = sanitizeInput($_POST["amc_year"]);
        $is_active = $_POST["is_active"];
        $unique_id = $_POST["unique_id"];
        $update_where = "";

        // Check if record already exists
        $stmt = $mysqli->prepare("SELECT unique_id FROM $table WHERE from_year = ? AND to_year = ? AND amc_year = ?");
        $stmt->bind_param("sss", $from_year, $to_year, $amc_year);
        $stmt->execute();
        $stmt->bind_result($existing_unique_id);
        $stmt->fetch();
        $stmt->close();

        if ($existing_unique_id) {
            // Update existing record
            $stmt = $mysqli->prepare("UPDATE $table SET from_year = ?, to_year = ?, amc_year = ?, is_active = ? WHERE unique_id = ?");
            $stmt->bind_param("sssss", $from_year, $to_year, $amc_year, $is_active, $existing_unique_id);
            $msg = "update";
        } else {
            if ($unique_id) {
                // Update record with provided unique_id
                $stmt = $mysqli->prepare("UPDATE $table SET from_year = ?, to_year = ?, amc_year = ?, is_active = ? WHERE unique_id = ?");
                $stmt->bind_param("sssss", $from_year, $to_year, $amc_year, $is_active, $unique_id);
                $msg = "update";
            } else {

                // Insert new record
                $uniqueid = unique_id(); // Generate a new unique_id if needed
                $stmt = $mysqli->prepare("INSERT INTO $table (from_year, to_year, amc_year, is_active, unique_id) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $from_year, $to_year, $amc_year, $is_active, $uniqueid);
                $msg = "create";
            }
        }

        if ($stmt->execute()) {
            $status = true;
        } else {
            $status = false;
            $msg = "error";
	    
            $error = $stmt->error;
        }
        $stmt->close();

        $json_array = [
            "status" => $status,
	    "data" => [
                "unique_id" => $unique_id ? $unique_id : $uniqueid,
            ],
            "msg" => $msg,
            "error" => isset($error) ? $error : "",
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

        $from_year = $_POST["from_year"];
        $to_year = $_POST["to_year"];
        $amc_year = $_POST["amc_year"];
        $is_active = $_POST["is_active"];
        $unique_id = $_POST["unique_id"];

        // Query Variables
        $json_array = "";
        $columns = [
            "@a:=@a+1 s_no",
            "from_year",
            "to_year",
            "amc_year",

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
                // $value['product_category'] = disname($value['product_category']);
                // $value['description'] = disname($value['description']);

                // $from_year = $value['from_year'];

                // $to_year   = $value['$to_year'];
                $value['is_active'] = is_active_show($value['is_active']);


                $btn_update = btn_update($folder_name, $value['unique_id']);
                $btn_delete = btn_delete($folder_name, $value['unique_id']);

                // if ( $value['unique_id'] == "5f97fc3257f2525529") {
                //     $btn_update         = "";
                //     $btn_delete         = "";
                // } 

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

        if ($stmt->execute()) {
            $status = true;
            $msg = "success_delete";
        } else {
            $status = false;
            $msg = "error";
            $error = $stmt->error;
        }

        $stmt->close();

        $json_array = [
            "status" => $status,
            "msg" => $msg,
            "error" => isset($error) ? $error : "",
        ];

        echo json_encode($json_array);
        break;

    default:

        break;
}

//
?>