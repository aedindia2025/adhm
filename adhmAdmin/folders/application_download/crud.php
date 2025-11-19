<?php

// Get folder Name From Currnent Url
$folder_name = explode('/', $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = 'application_download';

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// $fileUpload         = new Alirdn\SecureUPload\SecureUPload($fileUploadConfig);

// $fileUploadPath = $fileUploadConfig->get("upload_folder");

// Create Folder in root->uploads->(this_folder_name) Before using this file upload
// $fileUploadConfig->set("upload_folder", $fileUploadPath . $folder_name . DIRECTORY_SEPARATOR);

// // Variables Declaration
$action = $_POST['action'];

$test_file = '';
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

        $vali_current_date = filter_input(INPUT_POST, 'current_date', FILTER_SANITIZE_STRING);
        $vali_validate_date = filter_input(INPUT_POST, 'validate_date', FILTER_SANITIZE_STRING);
        $vali_application_name = filter_input(INPUT_POST, 'application_name', FILTER_SANITIZE_STRING);
        $vali_description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

        if (!$vali_current_date || !$vali_validate_date || !$vali_application_name || !$vali_description) {
            $msg = "form_alert";
        } else {

            $current_date = sanitizeInput($_POST['current_date']);
            $validate_date = sanitizeInput($_POST['validate_date']);
            $application_name = sanitizeInput($_POST['application_name']);
            $description = sanitizeInput($_POST['description']);
            $is_active = sanitizeInput($_POST['is_active']);
            $unique_id = sanitizeInput($_POST['unique_id']);
            $update_where = '';

            $allowedExts = array('pdf', 'jpg', 'jpeg', 'png', 'gif', 'xlsx', 'xls', 'docx');

            $extension = pathinfo($_FILES['test_file']['name'], PATHINFO_EXTENSION);
if($_FILES['test_file']['name']){
            if (!in_array($extension, $allowedExts)) {
                die('File type not allowed.');
            }
        }
            if ($_FILES['test_file']['type'] == 'application/pdf' || in_array(strtolower($extension), $allowedExts)) {
                $file_exp = explode('.', $_FILES['test_file']['name']);

                $tem_name = random_strings(25) . '.' . $file_exp[1]; // generate random name for the file
                move_uploaded_file($_FILES['test_file']['tmp_name'], '../../uploads/application_download/' . $tem_name);

                $file_names = $tem_name;

                $file_org_names = $_FILES['test_file']['name'];

                $columns = [
                    'cur_date' => $current_date,
                    'validate_date' => $validate_date,
                    'application_name' => $application_name,
                    'description' => $description,
                    'file_name' => $file_names,
                    'file_org_name' => $file_org_names,
                    'is_active' => $is_active,
                    'unique_id' => unique_id($prefix),
                ];
            } else {
                $columns = [
                    'cur_date' => $current_date,
                    'validate_date' => $validate_date,
                    'application_name' => $application_name,
                    'description' => $description,
                    'is_active' => $is_active,
                    'unique_id' => unique_id($prefix),
                ];
            }
            // check already Exist Or not
            $select_where = 'application_name = ? AND is_delete = 0';

            // When Update Check without current id
            if ($unique_id) {
                $select_where .= ' AND unique_id != ?';
            }

            $stmt = $mysqli->prepare("SELECT COUNT(unique_id) AS count FROM $table WHERE $select_where");
            if ($unique_id) {
                $stmt->bind_param('ss', $application_name, $unique_id);
            } else {
                $stmt->bind_param('s', $application_name);
            }
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count) {
                $msg = 'already';
            } elseif ($count == 0) {
                // Update Begins
                if ($unique_id) {
                    unset($columns['unique_id']);

                    $update_where = [
                        'unique_id' => $unique_id,
                    ];

                    $set_columns = '';
                    foreach ($columns as $key => $value) {
                        $set_columns .= "$key = ?, ";
                    }
                    $set_columns = rtrim($set_columns, ', ');

                    $stmt = $mysqli->prepare("UPDATE $table SET $set_columns WHERE unique_id = ?");
                    $types = str_repeat('s', count($columns)) . 's';
                    $values = array_values($columns);
                    $values[] = $unique_id;

                    $stmt->bind_param($types, ...$values);
                    $action_obj = $stmt->execute();
                    $stmt->close();
                } else {
                    // Insert Begins
                    $columns['unique_id'] = unique_id($prefix);

                    $fields = implode(',', array_keys($columns));
                    $placeholders = implode(',', array_fill(0, count($columns), '?'));

                    $stmt = $mysqli->prepare("INSERT INTO $table ($fields) VALUES ($placeholders)");
                    $types = str_repeat('s', count($columns));
                    $values = array_values($columns);

                    $stmt->bind_param($types, ...$values);
                    $action_obj = $stmt->execute();
                    $stmt->close();
                }

                if ($action_obj) {
                    $status = true;
                    $msg = $unique_id ? 'update' : 'create';
                } else {
                    $status = false;
                    $msg = 'error';
                }
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
        $limit = $length;

        $data = [];

        if ($length == '-1') {
            $limit = '';
        }

        // Query Variables
        $json_array = '';
        $columns = [
            '@a:=@a+1 s_no',
            'validate_date',
            'application_name',
            'description',
            'is_active',
            'unique_id',
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
                // $value['district_name'] = district_list($value['district_name']);
                $value['is_active'] = is_active_show($value['is_active']);

                $btn_update = btn_update($folder_name, $value['unique_id']);
                $btn_delete = btn_delete($folder_name, $value['unique_id']);

                // if ( $value['unique_id'] == "5f97fc3257f2525529") {
                //     $btn_update         = "";
                //     $btn_delete         = "";
                // }

                $value['unique_id'] = $btn_update . $btn_delete;
                $data[] = array_values($value);
            }

            $json_array = [
                'draw' => intval($draw),
                'recordsTotal' => intval($total_records),
                'recordsFiltered' => intval($total_records),
                'data' => $data,
                // 'testing' => $result->sql,
            ];
        } else {
            // print_r($result);
        }

        echo json_encode($json_array);
        break;

    case 'delete':

        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            exit('CSRF validation failed.');
        }
        $unique_id = $_POST['unique_id'];

        $columns = [
            'is_delete' => 1,
        ];

        $stmt = $mysqli->prepare("UPDATE $table SET is_delete = ? WHERE unique_id = ?");
        $stmt->bind_param('is', $columns['is_delete'], $unique_id);
        $action_obj = $stmt->execute();
        $stmt->close();

        if ($action_obj) {
            $status = true;
            $msg = 'success_delete';
        } else {
            $status = false;
            $msg = 'error';
        }

        $json_array = [
            'status' => $status,
            'data' => $data,
            'error' => $error,
            'msg' => $msg,
        ];

        echo json_encode($json_array);
        break;

    default:
        break;
}
