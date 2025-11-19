<?php

// Get folder Name From Currnent Url
$folder_name = explode('/', $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = 'unit_measurement';

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action = $_POST['action'];

$unit_measurement = '';
$is_active = '';
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
        // Sanitize and fetch input
        $token = $_POST['csrf_token'];
        if (!validateCSRFToken($token)) {
            exit('CSRF validation failed.');
        }

        $unit_measurement = sanitizeInput($_POST['unit_measurement']);
        $is_active = sanitizeInput($_POST['is_active']);
        $unique_id = sanitizeInput($_POST['unique_id']);

        // Check if the entry already exists
        $select_sql = "SELECT COUNT(unique_id) AS count FROM $table WHERE unit_measurement = ? AND is_delete = 0";
        if ($unique_id) {
            $select_sql .= ' AND unique_id != ?';
        }

        $stmt = $mysqli->prepare($select_sql);

        if ($unique_id) {
            $stmt->bind_param('ss', $unit_measurement, $unique_id);
        } else {
            $stmt->bind_param('s', $unit_measurement);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        $msg = '';
        if ($data['count'] > 0) {
            $msg = 'already';
            $status = true;
        } else {
            // Prepare insert/update data
            if ($unique_id) {
                $update_sql = "UPDATE $table SET unit_measurement = ?, is_active = ? WHERE unique_id = ?";
                $stmt = $mysqli->prepare($update_sql);
                $stmt->bind_param('sss', $unit_measurement, $is_active, $unique_id);
            } else {
                $columns = [
                    'unit_measurement' => $unit_measurement,
                    'is_active' => $is_active,
                    'unique_id' => unique_id($prefix),
                ];
                $insert_sql = "INSERT INTO $table (unit_measurement, is_active, unique_id) VALUES (?, ?, ?)";
                $stmt = $mysqli->prepare($insert_sql);
                $stmt->bind_param('sss', $unit_measurement, $is_active, $columns['unique_id']);
            }

            if ($stmt->execute()) {
                $status = true;
                $msg = $unique_id ? 'update' : 'create';
                $data = $unique_id ? $unique_id : $stmt->insert_id;
                $error = '';
            } else {
                $status = false;
                $msg = 'error';
                $error = $stmt->error;
                $data = [];
            }
        }

        $json_array = [
            'status' => $status,
            'data' => $data,
            'error' => $error ?? '',
            'msg' => $msg,
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
        $limit = $length == '-1' ? '' : intval($length);

        $data = [];

        // Query Variables
        $json_array = '';
        $columns = [
            '@a:=@a+1 s_no',
            'unit_measurement',
            'is_active',
            'unique_id',
        ];
        $sql_function = 'SQL_CALC_FOUND_ROWS';
        $where = 'is_delete = 0';
        $order_by = ''; // You can modify this to add an order by clause if needed

        // SQL query for data fetching
        $sql = "SELECT $sql_function ".implode(', ', $columns)." FROM $table , (SELECT @a:=?) AS a WHERE $where";
        if ($limit) {
            $sql .= ' LIMIT ?, ?';
        }

        $stmt = $mysqli->prepare($sql);
        if ($limit) {
            $stmt->bind_param('iii', $start, $start, $limit);
        } else {
            $stmt->bind_param('i', $start);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch total records
        $total_records_result = $mysqli->query('SELECT FOUND_ROWS() as total');
        $total_records = $total_records_result->fetch_assoc()['total'];

        if ($result) {
            $res_array = $result->fetch_all(MYSQLI_ASSOC);

            foreach ($res_array as $key => $value) {
                $value['unit_measurement'] = disname($value['unit_measurement']);
                $value['is_active'] = is_active_show($value['is_active']);

                $btn_update = btn_update($folder_name, $value['unique_id']);
                $btn_delete = btn_delete($folder_name, $value['unique_id']);

                if ($value['unique_id'] == '5f97fc3257f2525529') {
                    $btn_update = '';
                    $btn_delete = '';
                }

                $value['unique_id'] = $btn_update.$btn_delete;
                $data[] = array_values($value);
            }

            $json_array = [
                'draw' => intval($draw),
                'recordsTotal' => intval($total_records),
                'recordsFiltered' => intval($total_records),
                'data' => $data,
                // "testing" => $stmt->sqlstate
            ];
        }

        echo json_encode($json_array);

        // Close statement and connection
        $stmt->close();
        $mysqli->close();

        break;

    case 'delete':
        $token = $_POST['csrf_token'];
        if (!validateCSRFToken($token)) {
            exit('CSRF validation failed.');
        }
        $unique_id = $_POST['unique_id'];

        // Prepare the SQL statement for updating the record
        $sql = "UPDATE $table SET is_delete = ? WHERE unique_id = ?"; // Replace 'your_table_name' with the actual table name
        $stmt = $mysqli->prepare($sql);

        if ($stmt) {
            $is_delete = 1;
            $stmt->bind_param('is', $is_delete, $unique_id);

            if ($stmt->execute()) {
                $status = true;
                $data = $stmt->affected_rows;
                $error = '';
                $sqlstate = $stmt->sqlstate;
                $msg = 'success_delete';
            } else {
                $status = false;
                $data = [];
                $error = $stmt->error;
                $sqlstate = $stmt->sqlstate;
                $msg = 'error';
            }

            $stmt->close();
        } else {
            $status = false;
            $data = [];
            $error = $mysqli->error;
            $sqlstate = $mysqli->sqlstate;
            $msg = 'error';
        }

        $json_array = [
            'status' => $status,
            'data' => $data,
            'error' => $error,
            'msg' => $msg,
            'sql' => $sqlstate,
        ];

        echo json_encode($json_array);

        // Close connection
        $mysqli->close();

        break;

    default:
        break;
}
