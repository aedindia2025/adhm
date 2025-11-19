<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "renewal_reject_reason";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action = $_POST['action'];

$reject_reason = "";
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

        $reject_reason = sanitizeInput($_POST["reject_reason"]);
        $is_active = sanitizeInput($_POST["is_active"]);
        $unique_id = sanitizeInput($_POST["unique_id"]);


        // Check if the district already exists
        $select_sql = "SELECT COUNT(unique_id) AS count FROM $table WHERE reject_reason = ? AND is_delete = 0";
        if ($unique_id) {
            $select_sql .= " AND unique_id != ?";
        }

        $stmt = $mysqli->prepare($select_sql);

        if ($unique_id) {
            $stmt->bind_param("ss", $reject_reason, $unique_id);
        } else {
            $stmt->bind_param("s", $reject_reason);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        $msg = "";
        if ($data["count"] > 0) {
            $msg = "already";
        } else {
            // Prepare insert/update data
            if ($unique_id) {
                $columns = [
                    "reject_reason" => $reject_reason,
                    "is_active" => $is_active
                ];
                $update_sql = "UPDATE $table SET reject_reason = ?, is_active = ? WHERE unique_id = ?";
                $stmt = $mysqli->prepare($update_sql);
                $stmt->bind_param("sss", $reject_reason, $is_active, $unique_id);
            } else {
                $columns = [
                    "reject_reason" => $reject_reason,
                    "is_active" => $is_active,
                    "unique_id" => unique_id($prefix)
                ];
                $insert_sql = "INSERT INTO $table (reject_reason, is_active, unique_id) VALUES (?, ?, ?)";
                $stmt = $mysqli->prepare($insert_sql);
                $stmt->bind_param("sss", $reject_reason, $is_active, $columns['unique_id']);
            }

            if ($stmt->execute()) {
                $status = true;
                $msg = $unique_id ? "update" : "create";
            } else {
                $status = false;
                $msg = "error";
                $error = $stmt->error;
            }
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error ?? "",
            "msg" => $msg,
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
        $columns = [
            "@a:=@a+1 s_no",
            "reject_reason",
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
            ];
        } else {
            // Handle the error case
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