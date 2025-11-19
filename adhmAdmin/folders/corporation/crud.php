<?php
session_start(); // Start the session for CSRF token validation

// Get folder Name From Current URL
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// Database Table Name
$table = "corporation";

// Include DB file and Common Functions
include '../../config/dbconfig.php';

// Variables Declaration
$action = $_POST['action'];

$corporation_name = "";
$district_name = "";
$is_active = "";
$unique_id = "";
$prefix = "";

$data = "";
$msg = "";
$error = "";
$status = "";
$test = ""; // For Developer Testing Purpose

function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

switch ($action) {
    case 'createupdate':
        $token = $_POST['csrf_token'];
        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        $district_name = sanitizeInput($_POST["district_name"]);
        $corporation_name = sanitizeInput($_POST["corporation_name"]);
        $is_active = sanitizeInput($_POST["is_active"]);
        $unique_id = sanitizeInput($_POST["unique_id"]);

        $columns = [
            "district_name" => $district_name,
            "corporation_name" => $corporation_name,
            "is_active" => $is_active,
            "unique_id" => unique_id($prefix)
        ];

        // Check if already exists
        $query = "SELECT COUNT(unique_id) AS count FROM $table WHERE corporation_name = ? AND is_delete = 0";
        if ($unique_id) {
            $query .= " AND unique_id != ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("ss", $corporation_name, $unique_id);
        } else {
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("s", $corporation_name);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $count = $row['count'];

        if ($count > 0) {
            $msg = "already";
	    $status = true;
        } else {
            if ($unique_id) {
                // Update existing record
                $query = "UPDATE $table SET district_name=?, corporation_name=?, is_active=? WHERE unique_id=?";
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param("ssss", $district_name, $corporation_name, $is_active, $unique_id);
            } else {
                // Insert new record
                $query = "INSERT INTO $table (district_name, corporation_name, is_active, unique_id) VALUES (?, ?, ?, ?)";
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param("ssss", $district_name, $corporation_name, $is_active, $columns['unique_id']);
            }

            if ($stmt->execute()) {
                $status = "success";
                $msg = $unique_id ? "update" : "create";
            } else {
                $status = "error";
                $error = $stmt->error;
                $msg = "error";
            }
            $stmt->close();
        }

        $json_array = [
            "status" => $status,
            "data" => [
                "unique_id" => $unique_id ? $unique_id : $columns['unique_id'],
            ],
            "error" => $error,
            "msg" => $msg,
        ];

        echo json_encode($json_array);
        break;

    case 'datatable':
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;

        $data = [];
        $json_array = "";
        $columns = [
            "@a:=@a+1 s_no",
            "(SELECT district_name FROM district_name AS district_name WHERE district_name.unique_id = $table.district_name) AS district_name",
            "corporation_name",
            "is_active",
            "unique_id"
        ];
        $table_details = "$table, (SELECT @a:= ?) AS a";
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
            exit; // Use exit instead of break
        }

        // Bind parameters dynamically
        $bind_params_arr = array_merge([$bind_params], $bind_values);
        $ref = new ReflectionClass('mysqli_stmt');
        $method = $ref->getMethod('bind_param');
        $method->invokeArgs($stmt, $bind_params_arr);

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
                // "testing" => $result->sql
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

        $query = "UPDATE $table SET is_delete = 1 WHERE unique_id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("s", $unique_id);

        if ($stmt->execute()) {
            $status = "success";
            $msg = "success_delete";
        } else {
            $status = "error";
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
