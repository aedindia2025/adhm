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
$table = "master_diet_chart";
$table_sub = "master_diet_chart_sublist";

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
        // Retrieve and sanitize input
        $hostel_type = sanitizeInput($_POST['hostel_type']);
        $description = sanitizeInput($_POST['description']);
        $unique_id = sanitizeInput($_POST['main_unique_id']);
        $screen_unique_id = sanitizeInput($_POST['screen_unique_id']);

        if ($unique_id) {

            // UPDATE
            $sql = "UPDATE master_diet_chart SET hostel_type = ?, description = ? WHERE unique_id = ?";
            $stmt = $mysqli->prepare($sql);
            if ($stmt === false)
                die("Prepare failed: " . $mysqli->error);

            $stmt->bind_param("sss", $hostel_type, $description, $unique_id);
            $stmt->execute();

            $msg = $stmt->affected_rows > 0 ? "update" : "error";
        } else {

            // INSERT
            $new_uid = unique_id();
            $sql = "INSERT INTO master_diet_chart (hostel_type, description, screen_unique_id, unique_id) VALUES (?, ?, ?, ?)";

            $stmt = $mysqli->prepare($sql);
            if ($stmt === false)
                die("Prepare failed: " . $mysqli->error);

            $stmt->bind_param("ssss", $hostel_type, $description, $screen_unique_id, $new_uid);
            $stmt->execute();

            $msg = $stmt->affected_rows > 0 ? "create" : "error";
        }

        $json_array = [
            "status" => $stmt->affected_rows > 0,
            "data" => [],
            "error" => $mysqli->error,
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
            "(SELECT hostel_type FROM hostel_type WHERE hostel_type.unique_id = $table.hostel_type) AS hostel_type",
            "description",
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

                // Buttons
                // $btn_copy = btn_copy($folder_name, $value['unique_id']);
                $btn_copy = '<a href="#" class="text-primary" id="openPopup" title="Open Popup">
                                <i class="fa fa-copy fa-lg"></i>
                            </a>';
                $btn_update = btn_update($folder_name, $value['unique_id']);
                $btn_delete = btn_delete($folder_name, $value['unique_id']);

                if ($value['unique_id'] == '5f97fc3257f2525529') {
                    $btn_update = '';
                    $btn_delete = '';
                }

                $value['unique_id'] = $btn_copy . $btn_update . $btn_delete;

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

    case 'get_unit':

        $item = sanitizeInput($_POST['item']); // Always sanitize input

        // Columns to select
        $columns = ["unit"]; // Also select 'unit' if you want to return it

        // Table details
        $table_details = [
            "item", // table name
            $columns
        ];

        $where = "is_delete = 0 AND unique_id = '" . $item . "'";

        $result = $pdo->select($table_details, $where);

        $unit = "-";

        if ($result->status && !empty($result->data)) {
            $record = $result->data[0];
            $unit = $record['unit'];

            // Map unit number to text
            $units_map = [
                1 => "Kgs",
                2 => "Grams",
                3 => "Liters",
                4 => "Milliliters",
                5 => "Pcs"
            ];
            $unit = $units_map[$unit] ?? "-";
        }

        $json_array = [
            "unit" => $unit,
            "sql" => $result->sql // for debugging
        ];

        echo json_encode($json_array);
        break;

    case 'item_category':

        $item_category = $_POST['item_category'];


        $item_category_options = item('', $item_category);

        $item_name_options = select_option($item_category_options, "Select item");

        echo $item_name_options;

        break;

    case 'add_diet_entry':

        // Validate CSRF token
        $token = $_POST['csrf_token'];
        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        // Sanitize and collect inputs
        $item_category = sanitizeInput($_POST['item_category']);
        $item = sanitizeInput($_POST['item']);
        $quantity = sanitizeInput($_POST['quantity']);
        $screen_unique_id = sanitizeInput($_POST['screen_unique_id']);
        $unique_id = isset($_POST['unique_id']) ? sanitizeInput($_POST['unique_id']) : '';

        // Prepare SQL for insert or update
        if ($unique_id) {
            // Update existing entry
            $sql = "UPDATE $table_sub SET 
                    category = ?, 
                    item = ?, 
                    quantity = ?, 
                    screen_unique_id = ?
                WHERE unique_id = ? AND is_delete = 0";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("ssiss", $item_category, $item, $quantity, $screen_unique_id, $unique_id);
        } else {
            // Insert new entry
            $new_unique_id = unique_id();
            $sql = "INSERT INTO $table_sub 
                    (category, item, quantity, screen_unique_id, unique_id) 
                VALUES (?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("ssiss", $item_category, $item, $quantity, $screen_unique_id, $new_unique_id);
        }

        // Execute statement
        $status = $stmt->execute();

        // Prepare response
        if ($status) {
            $msg = $unique_id ? "update" : "add";
            $error = "";
        } else {
            $msg = "error";
            $error = $stmt->error;
        }

        $stmt->close();

        // Send JSON response
        $json_array = [
            "status" => $status,
            "error" => $error,
            "msg" => $msg
        ];

        echo json_encode($json_array);
        break;

    case 'diet_chart_sub_datatable':

        // Function Name button prefix
        $btn_edit_delete = 'diet_chart_sub';

        $screen_unique_id = $_POST['screen_unique_id'];

        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;

        $data = [];

        if ($length == '-1') {
            $limit = '';
        }

        // Query Variables
        $json_array = '';
        $columns = [
            '@a:=@a+1 s_no',
            '(SELECT item_category from item_category where item_category.unique_id = ' . $table_sub . '.category) as category',
            '(SELECT item FROM item WHERE item.unique_id = ' . $table_sub . '.item ) AS item',
            'quantity',
            'unique_id',
            'screen_unique_id'
        ];

        $table_details = [
            $table_sub . ' , (SELECT @a:= ' . $start . ') AS a ',
            $columns
        ];

        $where = 'is_active = 1 AND is_delete = 0 AND screen_unique_id ="' . $screen_unique_id . '" ';

        $order_by = '';

        $sql_function = 'SQL_CALC_FOUND_ROWS';

        $result = $pdo->select($table_details, $where, $limit, $start, $order_by, $sql_function);
        $total_records = total_records();

        if ($result->status) {

            $res_array = $result->data;

            foreach ($res_array as $key => $value) {

                $btn_edit = btn_edit($btn_edit_delete, $value['unique_id']);
                $btn_delete = btn_delete($btn_edit_delete, $value['unique_id']);

                $value['unique_id'] = $btn_edit . $btn_delete;
                // $value['unique_id'] = '-';

                $data[] = array_values($value);
            }

            $json_array = [
                'draw' => intval($draw),
                'recordsTotal' => intval($total_records),
                'recordsFiltered' => intval($total_records),
                'data' => $data,
            ];
        } else {
            // print_r($result);
        }

        echo json_encode($json_array);
        break;

    case 'diet_chart_sub_delete':

        $unique_id = $_POST['unique_id'];

        $token = $_POST['csrf_token'];
        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        // Prepare SQL statement
        $sql = "UPDATE $table_sub SET is_delete = 1 WHERE unique_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $unique_id);

        // Execute the statement
        $status = $stmt->execute();

        // Prepare response
        if ($status) {
            $msg = 'success_delete';
            $data = $unique_id; // You can return the deleted unique_id if needed
            $error = "";
            $sql = $stmt->affected_rows;
        } else {
            $msg = 'error';
            $data = null;
            $error = $stmt->error;
            $sql = $stmt->affected_rows;
        }

        // Close statement
        $stmt->close();

        // JSON response
        $json_array = [
            'status' => $status,
            'data' => $data,
            'error' => $error,
            'msg' => $msg,
            // 'sql' => $sql
        ];

        echo json_encode($json_array);

        break;


    case 'diet_chart_sub_edit':

        $unique_id = $_POST['sub_unique_id'];
        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        // Prepare SQL statement
        $sql = "SELECT unique_id, category, item, quantity FROM $table_sub WHERE unique_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $unique_id);

        // Execute statement
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $status = true;
                $msg = 'success';
                $data = $row; // Send the selected row as JSON data
                $error = "";
            } else {
                $status = false;
                $msg = 'no_record_found';
                $data = null;
                $error = "";
            }
        } else {
            $status = false;
            $msg = 'error';
            $data = null;
            $error = $stmt->error;
        }

        $stmt->close();

        // JSON response
        $json_array = [
            'status' => $status,
            'msg' => $msg,
            'data' => $data,
            'error' => $error
        ];

        echo json_encode($json_array);
        break;


    default:
        break;
}