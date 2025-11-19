<?php

// Get folder Name From Currnent Url
$folder_name = explode('/', $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = 'fund_category_creation';

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action = $_POST['action'];

$user_type = '';
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
        // Fetch input

        $token = $_POST['csrf_token'];
        if (!validateCSRFToken($token)) {
            exit('CSRF validation failed.');
        }
        $company_name = sanitizeInput($_POST['company_name']);
        $company_address = sanitizeInput($_POST['company_address']);
        $mobile_num = sanitizeInput($_POST['mobile_num']);
        $email_id = sanitizeInput($_POST['email_id']);
        $fund_category = sanitizeInput($_POST['fund_cat']);
        $cost_category = sanitizeInput($_POST['cost_category']);
        $is_active = sanitizeInput($_POST['is_active']);
        $unique_id = sanitizeInput($_POST['unique_id']);

        $update_where = '';

        $columns = [
            'company_name' => $company_name,
            'company_address' => $company_address,
            'mobile_num' => $mobile_num,
            'email_id' => $email_id,
            'fund_category' => $fund_category,
            'cost_category' => $cost_category,
            'is_active' => $is_active,
        ];

        // Check if the entry already exists
        $select_query = 'SELECT COUNT(unique_id) AS count FROM '.$table.' WHERE company_name = ? AND is_delete = 0';

        if ($unique_id) {
            $select_query .= ' AND unique_id != ?';
        }

        $stmt = $mysqli->prepare($select_query);

        if ($unique_id) {
            $stmt->bind_param('ss', $company_name, $unique_id);
        } else {
            $stmt->bind_param('s', $company_name);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        if ($data['count']) {
            $msg = 'already';
            $status = true;
        } else {
            // Update or insert record
            if ($unique_id) {
                $update_columns = [];
                $update_values = [];
                foreach ($columns as $key => $value) {
                    $update_columns[] = "$key = ?";
                    $update_values[] = $value;
                }
                $update_values[] = $unique_id;

                $update_query = "UPDATE $table SET ".implode(', ', $update_columns).' WHERE unique_id = ?';
                $stmt = $mysqli->prepare($update_query);
                $stmt->bind_param(str_repeat('s', count($update_values)), ...$update_values);
            } else {
                $columns['unique_id'] = unique_id($prefix);  // Add unique_id to columns
                $insert_columns = array_keys($columns);
                $insert_values = array_values($columns);

                $insert_query = "INSERT INTO $table (".implode(', ', $insert_columns).') VALUES ('.implode(', ', array_fill(0, count($insert_columns), '?')).')';
                $stmt = $mysqli->prepare($insert_query);
                $stmt->bind_param(str_repeat('s', count($insert_values)), ...$insert_values);
            }

            if ($stmt->execute()) {
                if ($unique_id) {
                    $msg = 'update';
                } else {
                    $msg = 'create';
                }
                $status = true;
                $data = $unique_id ? $unique_id : $stmt->insert_id;
                $error = '';
            } else {
                $status = false;
                $data = [];
                $error = $stmt->error;
                $msg = 'error';
            }
        }

        $json_array = [
            'status' => $status,
            'data' => $data,
            'error' => $error,
            'msg' => $msg,
        ];

        echo json_encode($json_array);

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
            'company_name',
            'company_address',
            'mobile_num',
            'email_id',
            "(select fund_name from fund_name_creation where fund_name_creation.unique_id = $table.fund_category) as fund_category",
            'cost_category',
            'is_active',
            'unique_id',
        ];
        $table_details = $table.' , (SELECT @a:= ?) AS a ';
        $where = 'is_delete = 0';
        $order_by = ''; // You can modify this to add an order by clause if needed

        $sql_function = 'SQL_CALC_FOUND_ROWS';

        // SQL query for data fetching
        $sql = "SELECT $sql_function ".implode(', ', $columns)." FROM $table_details WHERE $where";
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
                $value['company_name'] = disname($value['company_name']);
                $value['company_address'] = disname($value['company_address']);
                $value['mobile_num'] = disname($value['mobile_num']);
                $value['email_id'] = disname($value['email_id']);
                $value['fund_category'] = disname($value['fund_category']);
                $value['cost_category'] = disname($value['cost_category']);
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
            ];
        } else {
            // Handle the error case
            $json_array = [
                'draw' => intval($draw),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $stmt->error,
                'testing' => $stmt->sqlstate,
            ];
        }

        echo json_encode($json_array);

        // Close connection
        $stmt->close();

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
