<?php

// Get folder Name From Currnent Url
$folder_name = explode('/', $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = 'leave_application';

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action = $_POST['action'];

$infrastructure_types = '';
$is_active = '';
$unique_id

    = '';
$prefix = '';

$data = '';
$msg = '';
$error = '';
$status = '';
$test = ''; // For Developer Testing Purpose

$ses_userid = $_SESSION['user_id'];
$ses_user_name = $_SESSION['user_name'];

function validateCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

switch ($action) {
    case 'createupdate':
        // Validate and sanitize inputs

        $token = $_POST['csrf_token'];
        if (!validateCSRFToken($token)) {
            exit('CSRF validation failed.');
        }

        $vali_student_id = filter_input(INPUT_POST, 'student_id', FILTER_SANITIZE_STRING);
        $vali_student_name = filter_input(INPUT_POST, 'student_name', FILTER_SANITIZE_STRING);
        $vali_from_date = filter_input(INPUT_POST, 'from_date', FILTER_SANITIZE_STRING);
        $vali_to_date = filter_input(INPUT_POST, 'to_date', FILTER_SANITIZE_STRING);
        $vali_reason = filter_input(INPUT_POST, 'reason', FILTER_SANITIZE_STRING);
        $vali_no_of_days = filter_input(INPUT_POST, 'no_of_days', FILTER_SANITIZE_STRING);
        $vali_status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
        $vali_district_id = filter_input(INPUT_POST, 'district_id', FILTER_SANITIZE_STRING);
        $vali_taluk_id = filter_input(INPUT_POST, 'taluk_id', FILTER_SANITIZE_STRING);
        $vali_academic_year = filter_input(INPUT_POST, 'academic_year', FILTER_SANITIZE_STRING);
        $vali_hostel_id = filter_input(INPUT_POST, 'hostel_id', FILTER_SANITIZE_STRING);

        // Check if required fields are empty
        if (!$vali_student_id || !$vali_student_name || !$vali_from_date || !$vali_to_date || !$vali_reason || !$vali_no_of_days || !$vali_status || !$vali_district_id || !$vali_taluk_id || !$vali_academic_year || !$vali_hostel_id) {
            $msg = 'form_alert';
            break;
        }

        // Sanitize all inputs
        $student_id = sanitizeInput($_POST['student_id']);
        $student_name = sanitizeInput($_POST['student_name']);
        $from_date = sanitizeInput($_POST['from_date']);
        $to_date = sanitizeInput($_POST['to_date']);
        $reason = sanitizeInput($_POST['reason']);
        $no_of_days = sanitizeInput($_POST['no_of_days']);
        $status = sanitizeInput($_POST['status']);
        $district_name = sanitizeInput($_POST['district_id']);
        $taluk_name = sanitizeInput($_POST['taluk_id']);
        $hostel_name = sanitizeInput($_POST['hostel_id']);
        $academic_year = sanitizeInput($_POST['academic_year']);
        $unique_id = sanitizeInput($_POST['unique_id']);

        // Prepare columns for SQL query
        $columns = [
            'student_id' => $student_id,
            'student_name' => $student_name,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'reason' => $reason,
            'district_name' => $district_name,
            'taluk_name' => $taluk_name,
            'hostel_name' => $hostel_name,
            'approval_status' => $status,
            'no_of_days' => $no_of_days,
            'academic_year' => $academic_year,
        ];

        // Prepare and execute SQL statement
        if ($unique_id) {
            // Update operation
            $sql = "UPDATE $table SET student_id=?, student_name=?, from_date=?, to_date=?, reason=?, district_name=?, taluk_name=?, hostel_name=?, approval_status=?, no_of_days=?, academic_year=? WHERE unique_id=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param('ssssssssssss', $student_id, $student_name, $from_date, $to_date, $reason, $district_name, $taluk_name, $hostel_name, $status, $no_of_days, $academic_year, $unique_id);
        } else {
            // Insert operation
            $sql = "INSERT INTO $table (student_id, student_name, from_date, to_date, reason, district_name, taluk_name, hostel_name, approval_status, no_of_days, academic_year, unique_id, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param('sssssssssssss', $student_id, $student_name, $from_date, $to_date, $reason, $district_name, $taluk_name, $hostel_name, $status, $no_of_days, $academic_year, unique_id($prefix), $_SESSION['user_id']);
        }

        // Execute statement and handle result
        if ($stmt->execute()) {
            if ($unique_id) {
                $msg = 'update';
            } else {
                $msg = 'create';
            }
            $status = true;
            $data = ['unique_id' => $mysqli->insert_id]; // Optional: Return inserted unique_id for create operation
        } else {
            $msg = 'error';
            $status = false;
            $error = $stmt->error;
        }

        // Prepare JSON response
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
        $search = $_POST['search']['value'];
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;

        $data = [];
        $date = $_POST['date'];
        $status = $_POST['status'];
        $is_active = $_POST['is_active'];
        $unique_id = $_POST['unique_id'];

        $ses_userid = $_SESSION['user_id'];
        $ses_user_name = $_SESSION['user_name'];

        if ($length == '-1') {
            $limit = '';
        }

        // Query Variables
        $json_array = '';
        $columns = [
            '@a:=@a+1 s_no',
            'student_id',
            'student_name',
            'from_date',
            'to_date',
            'no_of_days',
            'reason',
            'approval_status',
            'unique_id',
            'sess_user_id',
        ];

        $where = ' user_id = ? AND is_delete = ?';
        $order_by = '';

        // Prepare SQL query
        $sql = 'SELECT SQL_CALC_FOUND_ROWS '.implode(',', $columns)." 
                    FROM $table, (SELECT @a:= ?) AS a 
                    WHERE $where 
                    LIMIT ?, ?";
        // echo $sql;
        $stmt = $mysqli->prepare($sql);

        $param_start = $start;
        $param_user_id = $ses_userid;
        // echo $param_user_id;
        $is_delete = '0';

        if ($search) {
            $stmt->bind_param('iisss', $param_start, $param_user_id, $is_delete, $start, $limit);
        } else {
            $stmt->bind_param('issii', $param_start, $param_user_id, $is_delete, $start, $limit);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // Get total records
        $total_records = $mysqli->query('SELECT FOUND_ROWS() as total')->fetch_assoc()['total'];

        // if ($result) {
        while ($row = $result->fetch_assoc()) {
            $row['student_id'] = disname($row['student_id']);
            $row['student_name'] = disname($row['student_name']);
            $row['applied_date'] = disdate($row['applied_date']);
            $row['reason'] = disname($row['reason']);
            $row['status'] = disname($row['status']);

            $status_text = '';
            $status_color = '';

            switch ($row['approval_status']) {
                case 1:
                    $status_text = 'Pending';
                    $status_color = 'blue';
                    break;
                case 2:
                    $status_text = 'Approved';
                    $status_color = 'green';
                    break;
                case 3:
                    $status_text = 'Rejected';
                    $status_color = 'red';
                    break;
                default:
                    break;
            }

            // Assigning color to status
            $row['approval_status'] = '<span style="color: '.$status_color.';">'.$status_text.'</span>';

            $row['is_active'] = is_active_show($row['is_active']);

            $btn_update = btn_update($folder_name, $row['unique_id']);
            $btn_delete = btn_delete($folder_name, $row['unique_id']);

            if ($status_text == 'Pending') {
                $row['unique_id'] = $btn_update.$btn_delete;
            } else {
                $row['unique_id'] = '-';
            }
            $data[] = array_values($row);
        }

        $json_array = [
            'draw' => intval($draw),
            'recordsTotal' => intval($total_records),
            'recordsFiltered' => intval($total_records),
            'data' => $data,
            // "testing" => $result->sql
        ];
        // } else {
        //     echo json_encode(['error' => 'Failed to execute query: ' . $mysqli->error]);
        //     exit();
        // }

        echo json_encode($json_array);
        $stmt->close();
        $mysqli->close();
        break;

    case 'delete':
        // Validate input

        $token = $_POST['csrf_token'];
        if (!validateCSRFToken($token)) {
            exit('CSRF validation failed.');
        }

        $unique_id = isset($_POST['unique_id']) ? $_POST['unique_id'] : '';

        // Prepare and execute SQL statement
        $is_delete = '1';
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
            // Optionally, you can include additional data or SQL statement if needed
            // "data" => $data,
            // "sql" => $sql
        ];

        echo json_encode($json_array);
        break;

    default:
        break;
}

// $user_type          = $_POST["user_type"];
// $is_active          = $_POST["is_active"];
// $unique_id          = $_POST["unique_id"];

// $update_where       = "";

// //count user_type
// if($unique_id == ''){
//     $get_user_type=$pdo_conn->prepare("SELECT count(*) FROM `user_type` WHERE `user_type`= '".$user_type."' and `is_delete` != '1'");
// }else{
//     $get_user_type=$pdo_conn->prepare("SELECT count(*) FROM `user_type` WHERE `user_type`= '".$user_type."' and `is_delete` != '1' and `unique_id` != '".$unique_id."'" );
// }

// $get_user_type->execute();
// $user_type_count  = $get_user_type->fetchColumn();

// if($user_type_count == 0){

//     if($unique_id == ''){//insert
//         $unique_id = uniqid().rand(10000,99999);

//         if($prefix) {
//             $unique_id = $prefix.$unique_id;
//         }

//         $Insql=$pdo_conn->prepare("insert into user_type (`unique_id`,`user_type`, `is_active`, `is_delete`)values('".$unique_id."','".$user_type."','1','0')");
//         $Insql->execute();
//         $msg = "Created";
//         echo $msg;
//     }else{//update
//         $Insql=$pdo_conn->prepare("UPDATE `user_type` SET `user_type`= '".$user_type."',`is_active`= '".$is_active."' WHERE unique_id = '".$unique_id."'");

//         $Insql->execute();
//         $msg  = "Updated";
//         echo $msg;
//     }
// }else{
//     $msg  = "already";
//     echo $msg;
// }
//     break;
//     case 'delete' :
//         $unique_id          = $_POST["unique_id"];
//         $del_sql=$pdo_conn->prepare("update user_type set is_delete='1' where unique_id ='".$unique_id."'" );
//         $del_sql->execute();
//             $msg  = "success_delete";
//             echo $msg;

//     break;
//     default:

//     break;
// }
