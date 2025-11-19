<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "designation_creation";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';
function validateCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}


// // Variables Declaration
$action = $_POST['action'];

$user_type = "";
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


        $designation_type = sanitizeInput($_REQUEST["designation_type"]);
        $designation_name = sanitizeInput($_REQUEST["designation_name"]);
        $is_active = sanitizeInput($_REQUEST["is_active"]);
        $unique_id = sanitizeInput($_REQUEST["unique_id"]);

        $update_where = "";

        $columns = [
            "designation_type" => $designation_type,
            "designation_name" => $designation_name,
            "is_active" => $is_active,
            "unique_id" => unique_id($prefix)
        ];

        // Check if the designation already exists
        $sql = 'SELECT COUNT(unique_id) AS count FROM ' . $table . ' WHERE designation_name = ? AND is_delete = 0';
        if ($unique_id) {
            $sql .= ' AND unique_id != ?';
        }

        $stmt = $mysqli->prepare($sql);

        if ($unique_id) {
            $stmt->bind_param("ss", $designation_name, $unique_id);
        } else {
            $stmt->bind_param("s", $designation_name);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        if ($data["count"] > 0) {
            $status = true;
            $msg = "already";
            $data = [];
            $error = "";
            $sql = $stmt->sqlstate;
        } else {
            if ($unique_id) {
                unset($columns['unique_id']);
                $sql = 'UPDATE ' . $table . ' SET designation_type = ?, designation_name = ?, is_active = ? WHERE unique_id = ?';
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("ssis", $designation_type, $designation_name, $is_active, $unique_id);
            } else {
                $sql = 'INSERT INTO ' . $table . ' (designation_type, designation_name, is_active, unique_id) VALUES (?, ?, ?, ?)';
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("ssis", $designation_type, $designation_name, $is_active, $columns["unique_id"]);
            }

            if ($stmt->execute()) {
                $status = true;
                $data = $stmt->affected_rows;
                $error = "";
                $sql = $stmt->sqlstate;
                $msg = $unique_id ? "update" : "create";
            } else {
                $status = false;
                $data = [];
                $error = $stmt->error;
                $sql = $stmt->sqlstate;
                $msg = "error";
            }
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            "sql" => $sql
        ];

        echo json_encode($json_array);

        // Close connection
        $stmt->close();

        break;

    case 'datatable':



        // DataTable Variables
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length == '-1' ? "" : $length;

        $data = [];

        // Query Variables
        $json_array = "";
        $columns = [
            "@a:=@a+1 s_no",
            "designation_type",
            "designation_name",
            "is_active",
            "unique_id"
        ];
        $table_details = $table . " , (SELECT @a:= ?) AS a ";
        $where = "is_delete = 0";
        $order_by = ""; // You can modify this to add an order by clause if needed

        $sql_function = "SQL_CALC_FOUND_ROWS";

        // SQL query for data fetching
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_details WHERE $where";
        if ($limit) {
            $sql .= " LIMIT ?, ?";
        }

        $stmt = $mysqli->prepare($sql);
        if ($limit) {
            $stmt->bind_param("iii", $start, $start, $limit);
        } else {
            $stmt->bind_param("i", $start);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch total records
        $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
        $total_records = $total_records_result->fetch_assoc()['total'];

        if ($result) {
            $res_array = $result->fetch_all(MYSQLI_ASSOC);

            foreach ($res_array as $key => $value) {
                $value['designation_name'] = disname($value['designation_name']);
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
                "testing" => $stmt->sqlstate
            ];
        } else {
            // Handle the error case
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => $stmt->error,
                "testing" => $stmt->sqlstate
            ];
        }

        echo json_encode($json_array);

        // Close connection
        $stmt->close();

        break;
    case 'delete':

        $token = $_POST['csrf_token'];
        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }


        $unique_id = $_POST['unique_id'];

        // Prepare the SQL statement for updating the record
        $sql = "UPDATE $table SET is_delete = ? WHERE unique_id = ?"; // Replace 'your_table_name' with the actual table name
        $stmt = $mysqli->prepare($sql);

        if ($stmt) {
            $is_delete = 1;
            $stmt->bind_param("is", $is_delete, $unique_id);

            if ($stmt->execute()) {
                $status = true;
                $data = $stmt->affected_rows;
                $error = "";
                $sqlstate = $stmt->sqlstate;
                $msg = "success_delete";
            } else {
                $status = false;
                $data = [];
                $error = $stmt->error;
                $sqlstate = $stmt->sqlstate;
                $msg = "error";
            }

            $stmt->close();
        } else {
            $status = false;
            $data = [];
            $error = $mysqli->error;
            $sqlstate = $mysqli->sqlstate;
            $msg = "error";
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            "sql" => $sqlstate
        ];

        echo json_encode($json_array);

        // Close connection
        $mysqli->close();

        break;

    default:

        break;
}

?>