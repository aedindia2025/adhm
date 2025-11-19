<?php 
// Get folder Name From Currnent Url 
$folder_name        = explode("/",$_SERVER['PHP_SELF']);
$folder_name        = $folder_name[count($folder_name)-2];

// Database Country Table Name
$table             = "rcs_mapping";

// Include DB file and Common Functions
include '../../config/dbconfig.php';
include 'function.php';

// Variables Declaration
$action             = $_POST['action'];
$action_obj         = (object) [
    "status"    => 0,
    "data"      => "",
    "error"     => "Action Not Performed"
];
$json_array         = "";
$sql                = "";

$main_screen        = "";
$section_name       = "";
$screen_name        = "";
$screen_folder_name = "";
$icon_name          = "";
$order_no           = "";
$user_actions       = "";
$is_active          = "";
$unique_id          = "";
$prefix             = "";

$data               = "";
$msg                = "";
$error              = "";
$status             = "";
$test               = ""; // For Developer Testing Purpose

function validateCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

switch ($action) {

    case 'createupdate':
        // Assuming $pdo->select, $pdo->update, and $pdo->insert are replaced with mysqli queries
        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        $category = sanitizeInput($_POST["category"]);
        $item_name = sanitizeInput($_POST["item_name"]);
        $school_college = sanitizeInput($_POST["school_college"]);
        $quantity = sanitizeInput($_POST["quantity"]);
        $unit = sanitizeInput($_POST["unit"]);
        $procure_mode = sanitizeInput($_POST["procurement_mode"]);
        $is_active = sanitizeInput($_POST["is_active"]);
        // Check if student already exists
        $select_where = 'item = ? AND school_college = ? AND is_delete = 0';
        $params = [$item_name, $school_college];

        if ($unique_id) {
            $select_where .= ' AND unique_id != ?';
            $params[] = $unique_id;
        }

        $query = "SELECT COUNT(unique_id) AS count FROM $table WHERE $select_where";

        $select_stmt = $mysqli->prepare($query);

        if ($select_stmt === false) {
            die('Select statement preparation failed: ' . $mysqli->error);
        }
        // Bind parameters
        $types = str_repeat('s', count($params));
        $select_stmt->bind_param($types, ...$params);

        // Execute query
        $select_stmt->execute();

        // Bind result variables
        $select_stmt->bind_result($count);

        // Fetch result
        $select_stmt->fetch();

        // Close statement
        $select_stmt->close();
        if ($count && $unique_id != '') {
            $msg = "already";
        } else {

            if ($unique_id) {
                // Update existing record
                $update_columns = [
                    "category" => $category,
                    "item" => $item_name,
                    "school_college" => $school_college,
                    "quantity" => $quantity,
                    "unit" => $unit,
                    "procure_mode" => $procure_mode,
                    "is_active" => $is_active
                ];

                // Build update query
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

                // Bind parameters
                $types = str_repeat('s', count($update_params));
                $update_stmt->bind_param($types, ...$update_params);

                // Execute update query
                $update_stmt->execute();

                // Check for update success
                if ($update_stmt->affected_rows > 0) {
                    $msg = "update";
                } else {
                    $msg = "error";
                }

                // Close statement
                $update_stmt->close();
            } else {
                // Insert new record
                $insert_columns = [
                    "category" => $category,
                    "item" => $item_name,
                    "school_college" => $school_college,
                    "quantity" => $quantity,
                    "unit" => $unit,
                    "procure_mode" => $procure_mode,
                    "is_active" => $is_active,
                    "unique_id" => unique_id($prefix) // Assuming unique_id function exists
                ];

                // Build insert query
                $insert_query = "INSERT INTO $table (";
                $insert_columns_str = implode(", ", array_keys($insert_columns));
                $insert_query .= $insert_columns_str . ") VALUES (";
                $insert_query .= rtrim(str_repeat('?, ', count($insert_columns)), ', ') . ")";

                $insert_stmt = $mysqli->prepare($insert_query);
                if ($insert_stmt === false) {
                    die('Insert statement preparation failed: ' . $mysqli->error);
                }

                // Bind parameters
                $types = str_repeat('s', count($insert_columns));
                $insert_stmt->bind_param($types, ...array_values($insert_columns));

                // Execute insert query
                $insert_stmt->execute();

                // Check for insert success
                if ($insert_stmt->affected_rows > 0) {
                    $msg = "create";
                } else {
                    $msg = "error";
                }

                // Close statement
                $insert_stmt->close();
            }
        }

        // Prepare response JSON
        $json_array = [
            "status" => true, // Assuming success based on your implementation
            "data" => [], // Assuming data to be empty array
            "error" => "", // Assuming error to be empty string
            "msg" => $msg,
            // "sql"       => $sql
        ];

        // Output JSON
        echo json_encode($json_array);

        // Close connection
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
            // "item",
            "(SELECT name FROM items WHERE unique_id = $table.item AND is_delete = 0) AS items",
            "school_college",
            "quantity",
            "unit",
            "procure_mode",
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
        // print_r($sql);

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

                if ($value['school_college'] == '1') {
                    $value['school_college'] = 'School';
                } elseif ($value['school_college'] == '2') {
                    $value['school_college'] = 'College';
                }

                if ($value['unit'] == '1') {
                    $value['unit'] = 'Kgs';
                } elseif ($value['unit'] == '2') {
                    $value['unit'] = 'Grams';
                } elseif ($value['unit'] == '3') {
                    $value['unit'] = 'Lts';
                } elseif ($value['unit'] == '4') {
                    $value['unit'] = 'Mili Lts';
                } elseif ($value['unit'] == '5') {
                    $value['unit'] = 'Pcs';
                }

                if ($value['procure_mode'] == '1') {
                    $value['procure_mode'] = 'RCS';
                } elseif ($value['procure_mode'] == '2') {
                    $value['procure_mode'] = 'Open Market';
                }

                $btn_update                     = btn_update($folder_name,$value['unique_id']);
                $btn_delete                     = btn_delete($folder_name,$value['unique_id']);
                $value['unique_id']             = $btn_update.$btn_delete;

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

    case 'get_items':

        $category = $_POST['category'];

        $item_name_options = item_name('', $category);

        $item_name_options = select_option_host($item_name_options, "Select Items");

        echo $item_name_options;

        break;

    case 'delete':

        // Assuming $unique_id is passed via POST
        $unique_id = $_POST['unique_id'];

        // Update columns and where condition
        $columns = [
            "is_delete" => 1
        ];

        $update_where = [
            "unique_id" => $unique_id
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
            "msg" => $msg,
            // "sql" => $update_query
        ];

        // Output JSON
        echo json_encode($json_array);

        // Close connection
        $mysqli->close();

        break;

    default:
        break;
}