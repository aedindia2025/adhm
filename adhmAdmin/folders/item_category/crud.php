<?php

// Get folder Name From Currnent Url
$folder_name = explode('/', $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = 'item_category';

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action = $_POST['action'];

$is_active = '';
$unique_id = '';
$prefix = '';

$data = '';
$msg = '';
$error = '';
$status = '';
$test = '';
// For Developer Testing Purpose

function validateCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

switch ($action) {

    case 'createupdate':
        // CSRF token validation
        $token = $_POST['csrf_token'];
        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        // Sanitize all inputs
        $item_category = sanitizeInput($_POST['item_category']);
        $description = sanitizeInput($_POST['description']);
        $is_active = sanitizeInput($_POST['is_active']);
        $unique_id = sanitizeInput($_POST['unique_id']);

        // Check if record already exists
        $select_where = 'item_category = ? AND is_delete = 0';
        $params = [$item_category];

        if ($unique_id) {
            $select_where .= ' AND unique_id != ?';
            $params[] = $unique_id;
        }

        $select_stmt = $mysqli->prepare("SELECT COUNT(unique_id) AS count FROM $table WHERE $select_where");
        if ($select_stmt === false) {
            die('Select statement preparation failed: ' . $mysqli->error);
        }

        // Bind parameters
        $types = str_repeat('s', count($params));
        $select_stmt->bind_param($types, ...$params);

        // Execute and fetch
        $select_stmt->execute();
        $select_stmt->bind_result($count);
        $select_stmt->fetch();
        $select_stmt->close();

        if ($count) {
            $msg = "already";
        } else {
            if ($unique_id) {
                // UPDATE existing record
                $update_columns = [
                    "item_category" => $item_category,
                    "description" => $description,
                    "is_active" => $is_active
                ];

                $update_query = "UPDATE $table SET ";
                foreach ($update_columns as $key => $value) {
                    $update_query .= "$key = ?, ";
                }
                $update_query = rtrim($update_query, ', ');
                $update_query .= " WHERE unique_id = ?";

                $update_params = array_values($update_columns);
                $update_params[] = $unique_id;

                $update_stmt = $mysqli->prepare($update_query);
                if ($update_stmt === false) {
                    die('Update statement preparation failed: ' . $mysqli->error);
                }

                $types = str_repeat('s', count($update_params));
                $update_stmt->bind_param($types, ...$update_params);

                $update_stmt->execute();

                // if ($update_stmt->affected_rows > 0) {
                //     $msg = "update";
                // } else {
                //     $msg = "error";
                // }

                if ($update_stmt->errno) {
                    $msg = "error";
                } else {
                    $msg = "update";
                }

                $update_stmt->close();
            } else {
                // INSERT new record
                $insert_columns = [
                    "item_category" => $item_category,
                    "description" => $description,
                    "is_active" => $is_active,
                    "unique_id" => unique_id($prefix)
                ];

                $insert_query = "INSERT INTO $table (" . implode(", ", array_keys($insert_columns)) . ") VALUES (" .
                    rtrim(str_repeat('?, ', count($insert_columns)), ', ') . ")";

                $insert_stmt = $mysqli->prepare($insert_query);
                if ($insert_stmt === false) {
                    die('Insert statement preparation failed: ' . $mysqli->error);
                }

                $types = str_repeat('s', count($insert_columns));
                $insert_stmt->bind_param($types, ...array_values($insert_columns));

                $insert_stmt->execute();

                if ($insert_stmt->affected_rows > 0) {
                    $msg = "create";
                } else {
                    $msg = "error";
                }

                $insert_stmt->close();
            }
        }

        // Return JSON response
        $json_array = [
            "status" => true,
            "data" => [],
            "error" => "",
            "msg" => $msg
        ];

        echo json_encode($json_array);

        $mysqli->close();
        break;

    case 'datatable':
        // DataTable Variables
        $search = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';
        $length = isset($_POST['length']) ? $_POST['length'] : 10;
        $start = isset($_POST['start']) ? $_POST['start'] : 0;
        $draw = isset($_POST['draw']) ? $_POST['draw'] : 1;
        $limit = $length;

        $data = [];

        if ($length == '-1') {
            $limit = "";
        }

        // Define columns
        $columns = [
            "@a:=@a+1 AS s_no",
            "item_category",
            "description",
            "is_active",
            "unique_id"
        ];

        // Base query setup
        $sql_function = "SQL_CALC_FOUND_ROWS";
        $table_details = "$table, (SELECT @a:= ?) AS a";
        $where = "is_delete = 0";
        $params = [0]; // Starting value for @a counter

        // Search filter
        if (!empty($search)) {
            $where .= " AND (item_category LIKE ? OR description LIKE ?)";
            $like_search = "%" . $search . "%";
            $params[] = $like_search;
            $params[] = $like_search;
        }

        // Build SQL query
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " 
            FROM $table_details 
            WHERE $where";

        // Add limit for pagination
        if ($length != '-1') {
            $sql .= " LIMIT ?, ?";
            $params[] = intval($start);
            $params[] = intval($length);
        }

        // Prepare statement
        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
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
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);

        // Execute
        $stmt->execute();
        $result = $stmt->get_result();

        // Get total records count (filtered)
        $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
        $total_records = $total_records_result->fetch_assoc()['total'];

        if ($result) {
            $res_array = $result->fetch_all(MYSQLI_ASSOC);

            foreach ($res_array as $key => $value) {
                // Active status display
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

            // Prepare JSON response
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data
            ];
        } else {
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => []
            ];
        }

        $stmt->close();

        echo json_encode($json_array);
        $mysqli->close();
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
?>