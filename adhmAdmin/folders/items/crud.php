<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database table
$table = "item";

// Include DB connection
include '../../config/dbconfig.php';

function validateCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Get action
$action = $_POST['action'] ?? '';

switch ($action) {

    case 'createupdate':
        // Retrieve and sanitize input data
        $category = sanitizeInput($_POST['category']);
        $item = sanitizeInput($_POST['item']);
        $unit = sanitizeInput($_POST['unit']);
        $is_active = sanitizeInput($_POST['is_active']);
        $unique_id = sanitizeInput($_POST['unique_id']);

        $category_name_stmt = $mysqli->prepare("SELECT item_category FROM item_category WHERE unique_id = ?");
        if ($category_name_stmt === false) {
            die('Fetching category name statement preparation failed: ' . $mysqli->error);
        }

        // Bind and execute
        $category_name_stmt->bind_param("s", $category);
        $category_name_stmt->execute();
        $category_name_stmt->bind_result($category_name);
        $category_name_stmt->fetch();
        $category_name_stmt->close();

        // Prepare columns for SQL operations
        $columns = [
            "category" => $category,
            "category_id" => $category_name,
            "item" => $item,
            "unit" => $unit,
            "is_active" => $is_active,
            "unique_id" => unique_id($prefix) // Generate unique ID if not provided
        ];

        // Check if item already exists
        if ($unique_id) {
            $sql = 'SELECT COUNT(unique_id) AS count FROM ' . $table . ' WHERE category_id = ? AND item = ? AND is_delete = 0 AND unique_id != ?';
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("sss", $category, $item, $unique_id);
        } else {
            $sql = 'SELECT COUNT(unique_id) AS count FROM ' . $table . ' WHERE category_id = ? AND item = ? AND is_delete = 0';
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("ss", $category, $item);
        }

        if (!$stmt) {
            die('Prepare failed: ' . $mysqli->error);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        if ($data["count"] > 0) {
            $msg = "already";
        } else {
            if ($unique_id) {
                // Perform update
                $sql = 'UPDATE ' . $table . ' SET category_id = ?, category = ?, item = ?, unit = ?, is_active = ? WHERE unique_id = ?';
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("ssssis", $category, $category_name, $item, $unit, $is_active, $unique_id);
            } else {
                // Perform insert
                $sql = 'INSERT INTO ' . $table . ' (category_id, category, item, unit, is_active, unique_id) VALUES (?, ?, ?, ?, ?, ?)';
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("ssssis", $category, $category_name, $item, $unit, $is_active, $columns["unique_id"]);
            }

            if ($stmt->execute()) {
                if ($unique_id) {
                    $msg = "update";
                } else {
                    $msg = "create";
                }
            } else {
                $msg = "error";
            }
        }

        $json_array = [
            "status" => $stmt->affected_rows > 0,
            "data" => [],
            "error" => $mysqli->error,
            "msg" => $msg
        ];

        echo json_encode($json_array);

        // Close statement and connection
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
            "category",
            "item",
            "unit",
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

                $value['category'] = disname($value['category']);

                // if ($value['unit'] == 1) {
                //     $value['unit'] = "Kgs";
                // } elseif ($value['unit'] == 2) {
                //     $value['unit'] = "Grams";
                // } elseif ($value['unit'] == 3) {
                //     $value['unit'] = "Liters";
                // } elseif ($value['unit'] == 4) {
                //     $value['unit'] = "Milliliters";
                // } elseif ($value['unit'] == 5) {
                //     $value['unit'] = "Pcs";
                // } else {
                //     $value['unit'] = "-";
                // }

                $value['is_active'] = is_active_show($value['is_active']);

                // Buttons
                $btn_update = btn_update($folder_name, $value['unique_id']);
                $btn_delete = btn_delete($folder_name, $value['unique_id']);

                if ($value['unique_id'] == '5f97fc3257f2525529') {
                    $btn_update = '';
                    $btn_delete = '';
                }

                $value['unique_id'] = $btn_update . $btn_delete;

                $data[] = array_values($value);
            }

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data
            ];
        } else {
            // print_r($result);
        }

        echo json_encode($json_array);
        break;

    case 'delete':
        // Assuming $unique_id is passed via POST
        $unique_id = $_POST['unique_id'];

        // Update columns and where condition
        $columns = [
            "is_delete" => 1
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

        // Prepare statement
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
            "msg" => $msg
            // "sql" => $update_query // Uncomment for debugging
        ];

        // Output JSON
        echo json_encode($json_array);

        // Close connection
        $mysqli->close();

        break;

    default:
        break;
}