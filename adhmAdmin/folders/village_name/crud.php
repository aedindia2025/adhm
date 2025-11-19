<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "village_name";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action = $_POST['action'];

$village_name = "";
$block_name = "";
$district_name = "";
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

        $district_name = sanitizeInput($_POST["district_name"]);
        $block_name = sanitizeInput($_POST["block_name"]);
        $village_name = sanitizeInput($_POST["village_name"]);
        $is_active = sanitizeInput($_POST["is_active"]);
        $unique_id = sanitizeInput($_POST["unique_id"]);

        $status = false;
        $data = [];
        $error = "";
        $msg = "";

        // Prepare columns for SQL operation
        $columns = [
            "district_name" => $district_name,
            "block_name" => $block_name,
            "village_name" => $village_name,
            "is_active" => $is_active,
            "unique_id" => unique_id($prefix)
        ];

        // Check if the village_name already exists
        $select_where = "district_name = ? and block_name = ? and village_name = ? AND is_delete = 0";
        $params = [$district_name,$block_name,$village_name];
        $param_types = 'sss';

        // When updating, check without the current id
        if ($unique_id) {
            $select_where .= " AND unique_id != ?";
            $params[] = $unique_id;
            $param_types .= 's';
        }

        $sql_check = "SELECT COUNT(unique_id) AS count FROM $table WHERE $select_where";
        $stmt_check = $mysqli->prepare($sql_check);
        $stmt_check->bind_param($param_types, ...$params);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $row_check = $result_check->fetch_assoc();

        if ($row_check['count'] > 0) {
            $msg = "already";
            $status = true;
        } else {
            if ($unique_id) {
                // Update operation
                $sql_update = "UPDATE $table SET district_name = ?, block_name = ?, village_name = ?, is_active = ? WHERE unique_id = ?";
                $stmt_update = $mysqli->prepare($sql_update);
                $stmt_update->bind_param('sssss', $district_name, $block_name, $village_name, $is_active, $unique_id);
                $status = $stmt_update->execute();
                $stmt_update->close();
                $msg = $status ? "update" : "error";
            } else {
                // Insert operation
                $sql_insert = "INSERT INTO $table (district_name, block_name, village_name, is_active, unique_id) VALUES (?, ?, ?, ?, ?)";
                $stmt_insert = $mysqli->prepare($sql_insert);
                $stmt_insert->bind_param('sssss', $district_name, $block_name, $village_name, $is_active, $columns['unique_id']);
                $status = $stmt_insert->execute();
                $stmt_insert->close();
                $msg = "create";
            }
        }

        $stmt_check->close();

        // Prepare JSON response
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
            "(SELECT district_name FROM district_name WHERE district_name.unique_id = " . $table . ".district_name) AS district_name",
            "(SELECT block_name FROM block_name WHERE block_name.unique_id = " . $table . ".block_name) AS block_name",
            "village_name",
            "is_active",
            "unique_id"
        ];
        $column_list = implode(", ", $columns);
        $where = "is_delete = 0";
        $order_by = "";

        // Prepare SQL query
        $sql = "SELECT SQL_CALC_FOUND_ROWS $column_list FROM $table, (SELECT @a:=?) AS a WHERE $where LIMIT ?, ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("iii", $start, $start, $limit);
        $stmt->execute();
        $result = $stmt->get_result();

        // Get total records count
        $sql_count = "SELECT COUNT(unique_id) AS total_count FROM $table WHERE $where";
        $result_count = $mysqli->query($sql_count);
        $row_count = $result_count->fetch_assoc();
        $total_records = isset($row_count['total_count']) ? $row_count['total_count'] : 0;

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $row['is_active'] = is_active_show($row['is_active']);

                $btn_update = btn_update($folder_name, $row['unique_id']);
                $btn_delete = btn_delete($folder_name, $row['unique_id']);

                if ($row['unique_id'] == "5f97fc3257f2525529") {
                    $btn_update = "";
                    $btn_delete = "";
                }

                $row['unique_id'] = $btn_update . $btn_delete;
                $data[] = array_values($row);
            }

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data,
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

    case 'get_block_name':

        $district_name = $_POST['district_name'];

        $district_options = block("", $district_name);

        $block_options = select_option($district_options, "Select Block Name");

        echo $block_options;

        break;


    default:

        break;
}


?>