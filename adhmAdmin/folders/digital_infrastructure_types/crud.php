<?php

// Get folder Name From Currnent Url
$folder_name = explode('/', $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = 'digital_infrastructure_types';

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action = $_POST['action'];

$fund_name = '';
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
        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            exit('CSRF validation failed.');
        }

        $digital_infrastructure_types = sanitizeInput($_POST['digital_infrastructure_types']);
        $description = sanitizeInput($_POST['description']);
        $is_active = sanitizeInput($_POST['is_active']);
        $unique_id = sanitizeInput($_POST['unique_id']);

      
        // Check if the digital infrastructure type already exists
        $select_sql = "SELECT COUNT(unique_id) AS count FROM $table WHERE digital_infrastructure_types = ? AND is_delete = 0";
        if ($unique_id) {
            $select_sql .= ' AND unique_id != ?';
        }

        $stmt = $mysqli->prepare($select_sql);

        if ($unique_id) {
            $stmt->bind_param('ss', $digital_infrastructure_types, $unique_id);
        } else {
            $stmt->bind_param('s', $digital_infrastructure_types);
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
                $columns = [
                    'digital_infrastructure_types' => $digital_infrastructure_types,
                    'description' => $description,
                    'is_active' => $is_active,
                ];
                $update_sql = "UPDATE $table SET digital_infrastructure_types = ?, description = ?, is_active = ? WHERE unique_id = ?";
                $stmt = $mysqli->prepare($update_sql);
                $stmt->bind_param('ssss', $digital_infrastructure_types, $description, $is_active, $unique_id);
            } else {
                $columns = [
                    'digital_infrastructure_types' => $digital_infrastructure_types,
                    'description' => $description,
                    'is_active' => $is_active,
                    'unique_id' => unique_id($prefix),
                ];
                $insert_sql = "INSERT INTO $table (digital_infrastructure_types, description, is_active, unique_id) VALUES (?, ?, ?, ?)";
                $stmt = $mysqli->prepare($insert_sql);
                $stmt->bind_param('ssss', $digital_infrastructure_types, $description, $is_active, $columns['unique_id']);
            }

            if ($stmt->execute()) {
                $status = true;
                $msg = $unique_id ? 'update' : 'create';
                $data = $unique_id ? $unique_id : $stmt->insert_id;
            } else {
                $status = false;
                $msg = 'error';
                $error = $stmt->error;
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
        $limit = $length == '-1' ? '' : $length;

        $data = [];

        // Query Variables
        $json_array = '';
        $columns = [
            '@a:=@a+1 s_no',
            'digital_infrastructure_types',
            'description',
            'is_active',
            'unique_id',
        ];
        $table_details = $table.', (SELECT @a:= ?) AS a ';
        $where = 'is_delete = 0';
        $order_by = ''; // You can modify this to add an order by clause if needed

        $sql_function = 'SQL_CALC_FOUND_ROWS';

        // SQL query for data fetching
        $sql = "SELECT $sql_function ".implode(', ', $columns)." FROM $table_details WHERE $where";
        if ($limit) {
            $sql .= ' LIMIT ?, ?';
        }

        // Prepare and bind SQL parameters
        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            exit('Prepare failed: '.$mysqli->error);
        }

        if ($limit) {
            $stmt->bind_param('iii', $start, $start, $limit);
        } else {
            $stmt->bind_param('i', $start);
        }

        // Execute SQL query
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch total records
        $total_records_result = $mysqli->query('SELECT FOUND_ROWS() as total');
        if ($total_records_result === false) {
            exit('Fetching total records failed: '.$mysqli->error);
        }
        $total_records = $total_records_result->fetch_assoc()['total'];

        // Process fetched data
        if ($result) {
            $res_array = $result->fetch_all(MYSQLI_ASSOC);

            foreach ($res_array as $key => $value) {
                // Replace disname and is_active_show with your actual functions
                $value['fund_name'] = disname($value['fund_name']);
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
                'testing' => $stmt->sqlstate, // Optionally include for testing/debugging
            ];
        } else {
            exit('Query execution failed: '.$stmt->error);
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
        $sql = 'UPDATE digital_infrastructure_types SET is_delete = ? WHERE unique_id = ?';
        $stmt = $mysqli->prepare($sql);

        if ($stmt) {
            // Bind parameters
            $is_delete = 1;
            $stmt->bind_param('is', $is_delete, $unique_id);

            // Execute SQL statement
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

            // Close statement
            $stmt->close();
        } else {
            $status = false;
            $data = [];
            $error = $mysqli->error;
            $sqlstate = $mysqli->sqlstate;
            $msg = 'error';
        }

        // Close connection
        $mysqli->close();

        // Construct JSON response
        $json_array = [
            'status' => $status,
            'data' => $data,
            'error' => $error,
            'msg' => $msg,
            'sql' => $sqlstate,
        ];

        echo json_encode($json_array);

        break;

    default:
        break;
}
