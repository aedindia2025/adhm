<?php

// Get folder Name From Currnent Url
$folder_name = explode('/', $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// Database Country Table Name
$table = 'user';

// Include DB file and Common Functions
include '../../config/dbconfig.php';

// Database connection details

// Variables Declaration
$action = $_POST['action'];
$action_obj = (object) [
    'status' => 0,
    'data' => '',
    'error' => 'Action Not Performed',
];
$json_array = '';
$sql = '';

$unique_id = '';
$prefix = '';

$data = '';
$msg = '';
$error = '';
$status = '';
$test = ''; // For Developer Testing Purpose

function validateCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

switch ($action) {
    case 'createupdate':
        // Validate CSRF token
        $token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
        if (!validateCSRFToken($token)) {
            exit('CSRF validation failed.');
        }

        // Sanitize and fetch input data
        $staff_name = isset($_POST['staff_name']) ? filter_input(INPUT_POST, 'staff_name', FILTER_SANITIZE_STRING) : '';
        $user_name = isset($_POST['user_name']) ? filter_input(INPUT_POST, 'user_name', FILTER_SANITIZE_STRING) : '';
        $password = isset($_POST['password']) ? filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING) : '';
        $user_type = isset($_POST['user_type']) ? filter_input(INPUT_POST, 'user_type', FILTER_SANITIZE_STRING) : '';
        $is_active = isset($_POST['is_active']) ? filter_input(INPUT_POST, 'is_active', FILTER_SANITIZE_STRING) : '';
        $phone_no = isset($_POST['phone_no']) ? filter_input(INPUT_POST, 'phone_no', FILTER_SANITIZE_STRING) : '';
        $hashedPassword = isset($_POST['hashedPassword']) ? filter_input(INPUT_POST, 'hashedPassword', FILTER_SANITIZE_STRING) : '';
        $unique_id = isset($_POST['unique_id']) ? $_POST['unique_id'] : '';

        // Prepare columns for SQL query
        $columns = [
            'staff_name' => sanitizeInput($staff_name),
            'user_name' => sanitizeInput($user_name),
            'password' => sanitizeInput($password),
            'phone_no' => sanitizeInput($phone_no),
            'is_active' => sanitizeInput($is_active),
            'hashedPassword' => sanitizeInput($hashedPassword),
            'user_type_unique_id' => sanitizeInput($user_type),
            'unique_id' => unique_id($prefix),
        ];

        // Initialize variables
        $status = false;
        $data = [];
        $error = '';
        $msg = '';
        $is_delete = '0';
        // Check if user_name or phone_no already exists
        $sql_check = "SELECT COUNT(unique_id) AS count FROM {$table} WHERE (user_name = ? OR phone_no = ?) AND is_delete = ?";
        $params_check = [$user_name, $phone_no, $is_delete];

        // Exclude current unique_id if updating
        if ($unique_id) {
            $sql_check .= ' AND unique_id != ?';
            $params_check[] = $unique_id;
        }

        // Execute the check query
        $stmt_check = $mysqli->prepare($sql_check);
        if ($stmt_check) {
            // Bind parameters and execute
            $stmt_check->bind_param(str_repeat('s', count($params_check)), ...$params_check);
            $stmt_check->execute();
            $stmt_check->bind_result($count);
            $stmt_check->fetch();
            $stmt_check->close();

            // Check if user_name or phone_no already exists
            if ($count > 0) {
                $msg = 'already';
            } else {
                // Perform update or insert operation
                if ($unique_id) {
                    // Update query
                    unset($columns['unique_id']);
                    $sql_update = "UPDATE {$table} SET ";
                    foreach ($columns as $key => $value) {
                        $sql_update .= "{$key} = ?, ";
                    }
                    $sql_update = rtrim($sql_update, ', ');
                    $sql_update .= ' WHERE unique_id = ?';
                    $params_update = array_values($columns);
                    $params_update[] = $unique_id;

                    // Prepare and execute update statement
                    $stmt = $mysqli->prepare($sql_update);
                    if ($stmt) {
                        $stmt->bind_param(str_repeat('s', count($params_update)), ...$params_update);
                        $stmt->execute();
                        if ($stmt->affected_rows > 0) {
                            $msg = 'update';
                            $status = true;
                        } else {
                            $error = 'Update operation failed.';
                        }
                        $stmt->close();
                    } else {
                        $error = 'Error preparing update statement: '.$mysqli->error;
                    }
                } else {
                    // Insert query
                    $sql_insert = "INSERT INTO {$table} (";
                    $sql_values = '';
                    $params_insert = [];
                    foreach ($columns as $key => $value) {
                        $sql_insert .= "{$key}, ";
                        $sql_values .= '?, ';
                        $params_insert[] = $value;
                    }
                    $sql_insert = rtrim($sql_insert, ', ').') VALUES ('.rtrim($sql_values, ', ').')';

                    // Prepare and execute insert statement
                    $stmt = $mysqli->prepare($sql_insert);
                    if ($stmt) {
                        $stmt->bind_param(str_repeat('s', count($params_insert)), ...$params_insert);
                        $stmt->execute();
                        if ($stmt->affected_rows > 0) {
                            $msg = 'create';
                            $status = true;
                        } else {
                            $error = 'Insert operation failed.';
                        }
                        $stmt->close();
                    } else {
                        $error = 'Error preparing insert statement: '.$mysqli->error;
                    }
                }
            }
        } else {
            $error = 'Error preparing statement for checking existence: '.$mysqli->error;
        }

        // Prepare JSON response
        $json_array = [
            'status' => $status,
            'data' => $data,
            'error' => $error,
            'msg' => $msg,
        ];

        // Output JSON response
        echo json_encode($json_array);

        // Close MySQLi connection
        $mysqli->close();
        break;

    case 'datatable':
        // DataTable Variables
        // Database connection details

        // DataTable Variables

        $length = isset($_POST['length']) ? $_POST['length'] : '';
        $start = isset($_POST['start']) ? $_POST['start'] : '';
        $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
        $limit = $length;

        $data = [];

        // Query Variables
        $columns = [
            '@a:=@a+1 s_no',
            'staff_name',
            'user_name',
            'phone_no',
            "(SELECT user_type FROM user_type WHERE unique_id = {$table}.user_type_unique_id ) AS user_type",
            'is_active',
            'unique_id',
        ];

        $table_details = "{$table} , (SELECT @a:= ".$start.') AS a';
        $where = ' is_delete = ? ';
        $is_delete = 0; // Assuming is_delete is an integer

        // Prepare SQL query
        $sql = 'SELECT SQL_CALC_FOUND_ROWS '.implode(', ', $columns)."
        FROM {$table} , (SELECT @a:= ?) AS a
        WHERE {$where}
        LIMIT ?, ?";

        // Prepare statement
        $stmt = $mysqli->prepare($sql);

        // Bind parameters
        $stmt->bind_param('ssii', $start, $is_delete, $start, $limit);
        // Execute statement
        $stmt->execute();

        // Bind result variables
        $stmt->bind_result($s_no, $staff_name, $user_name, $phone_no, $user_type, $is_active, $unique_id);

        // Fetch results and process
        while ($stmt->fetch()) {
            // Process each row
            $row = [
                's_no' => $s_no,
                'staff_name' => $staff_name,
                'user_name' => $user_name,
                'phone_no' => $phone_no,
                'user_type' => $user_type,
                'is_active' => $is_active == '1' ? 'Active' : 'Inactive',
                'unique_id' => $unique_id,
            ];

            // Customize actions based on unique_id condition
            $btn_update = btn_update($folder_name, $row['unique_id']);
            $btn_delete = btn_delete($folder_name, $row['unique_id']);
            $row['unique_id'] = $btn_update.$btn_delete;

            // Push row to data array
            $data[] = array_values($row);
        }

        // Total records count
        $total_records_sql = 'SELECT FOUND_ROWS()';
        $total_records_result = $mysqli->query($total_records_sql);
        $total_records = $total_records_result->fetch_row()[0];

        // Prepare JSON response
        $json_array = [
            'draw' => $draw,
            'recordsTotal' => intval($total_records),
            'recordsFiltered' => intval($total_records),
            'data' => $data,
            // "testing" => $stmt->sql // Uncomment for debugging purposes
        ];

        // Output JSON response
        echo json_encode($json_array);

        // Close statement and connection
        $stmt->close();
        $mysqli->close();

        break;

    case 'delete':
        
        // Validate input
        $unique_id = isset($_POST['unique_id']) ? $_POST['unique_id'] : '';

        if (!$unique_id) {
            $json_array = [
                'status' => false,
                'msg' => 'missing_unique_id',
            ];
            echo json_encode($json_array);
            break;
        }
        $is_delete = '1';
        // Prepare and execute SQL statement
        $sql = "UPDATE $table SET is_delete = ? WHERE unique_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('ss', $is_delete, $unique_id);

        // Execute statement and handle result
        if ($stmt->execute()) {
            $status = true;
            $msg = 'success_delete';
        } else {
            $status = false;
            $msg = 'error';
            $error = $stmt->error;
        }

        // Prepare JSON response
        $json_array = [
            'status' => $status,
            'msg' => $msg,
            'error' => $error,
        ];

        echo json_encode($json_array);
        break;

    case 'user_options':
        $under_user = $_POST['under_user'];

        $user_name_options = under_user($under_user);

        $user_name_options = select_option($user_name_options, 'Select');

        echo $user_name_options;

        break;

    case 'mobile':
        $staff_id = $_POST['staff_id'];

        $staff_mobile_no = staff_name($staff_id);

        echo $staff_mobile_no[0]['phone_no'];

        break;

    default:
        break;
}
