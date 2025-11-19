<?php

// Get folder Name From Currnent Url
$folder_name = explode('/', $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = 'supplier_name_creation';

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action = $_POST['action'];

$supplier_name = '';
$mobile_number = '';
$email_id = '';
$city = '';
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
        // Sanitize and fetch input
        $supplier_name = sanitizeInput($_POST['supplier_name']);
        $mobile_number = sanitizeInput($_POST['mobile_number']);
        $email_id = sanitizeInput($_POST['email_id']);
        $city = sanitizeInput($_POST['city']);
        $gst_no = sanitizeInput($_POST['gst_no']);
        $pan_number = sanitizeInput($_POST['pan_number']);
        $building_no = sanitizeInput($_POST['building_no']);
        $street = sanitizeInput($_POST['street']);
        $area = sanitizeInput($_POST['area']);
        $pincode = sanitizeInput($_POST['pincode']);
        $bank_name = sanitizeInput($_POST['bank_name']);
        $account_num = sanitizeInput($_POST['account_num']);
        $ifsc_code = sanitizeInput($_POST['ifsc_code']);
        $bank_address = sanitizeInput($_POST['bank_address']);
        $is_active = sanitizeInput($_POST['is_active']);
        $unique_id = sanitizeInput($_POST['unique_id']);

        // Check if the entry already exists
        $select_sql = "SELECT COUNT(unique_id) AS count FROM $table WHERE supplier_name = ? AND is_delete = 0";
        if ($unique_id) {
            $select_sql .= ' AND unique_id != ?';
        }

        $stmt = $mysqli->prepare($select_sql);

        if ($unique_id) {
            $stmt->bind_param('ss', $supplier_name, $unique_id);
        } else {
            $stmt->bind_param('s', $supplier_name);
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
                $update_sql = "UPDATE $table SET supplier_name = ?, mobile_number = ?, email_id = ?, city = ?, gst_no = ?, pan_number = ?, building_no = ?, street = ?, area = ?, pincode = ?, bank_name = ?, account_num = ?, ifsc_code = ?, bank_address = ?, is_active = ? WHERE unique_id = ?";
                $stmt = $mysqli->prepare($update_sql);
                $stmt->bind_param('ssssssssssssssss', $supplier_name, $mobile_number, $email_id, $city, $gst_no, $pan_number, $building_no, $street, $area, $pincode, $bank_name, $account_num, $ifsc_code, $bank_address, $is_active, $unique_id);
            } else {
                $columns = [
                    'supplier_name' => $supplier_name,
                    'mobile_number' => $mobile_number,
                    'email_id' => $email_id,
                    'city' => $city,
                    'gst_no' => $gst_no,
                    'pan_number' => $pan_number,
                    'building_no' => $building_no,
                    'street' => $street,
                    'area' => $area,
                    'pincode' => $pincode,
                    'bank_name' => $bank_name,
                    'account_num' => $account_num,
                    'ifsc_code' => $ifsc_code,
                    'bank_address' => $bank_address,
                    'is_active' => $is_active,
                    'unique_id' => unique_id($prefix),
                ];
                $insert_sql = "INSERT INTO $table (supplier_name, mobile_number, email_id, city, gst_no, pan_number, building_no, street, area, pincode, bank_name, account_num, ifsc_code, bank_address, is_active, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $mysqli->prepare($insert_sql);
                $stmt->bind_param('ssssssssssssssss', $supplier_name, $mobile_number, $email_id, $city, $gst_no, $pan_number, $building_no, $street, $area, $pincode, $bank_name, $account_num, $ifsc_code, $bank_address, $is_active, $columns['unique_id']);
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
            'supplier_name',
            'mobile_number',
            'email_id',
            'city',
            'is_active',
            'unique_id',
        ];
        $sql_function = 'SQL_CALC_FOUND_ROWS';
        $where = 'is_delete = 0';
        $order_by = ''; // You can modify this to add an order by clause if needed

        // SQL query for data fetching
        $sql = "SELECT $sql_function ".implode(', ', $columns)." FROM $table, (SELECT @a:=?) AS a WHERE $where";
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
                $value['supplier_name'] = disname($value['supplier_name']);
                $value['mobile_number'] = disname($value['mobile_number']);
                $value['email_id'] = disname($value['email_id']);
                $value['city'] = disname($value['city']);
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
        $sql = "UPDATE $table SET is_delete = ? WHERE unique_id = ?";
        $stmt = $mysqli->prepare($sql);

        if ($stmt) {
            $is_delete = 1;
            $stmt->bind_param('is', $is_delete, $unique_id);

            if ($stmt->execute()) {
                $status = true;
                $data = $stmt->affected_rows;
                $error = '';
                $msg = 'success_delete';
            } else {
                $status = false;
                $data = [];
                $error = $stmt->error;
                $msg = 'error';
            }

            $stmt->close();
        } else {
            $status = false;
            $data = [];
            $error = $mysqli->error;
            $msg = 'error';
        }

        $json_array = [
            'status' => $status,
            'data' => $data,
            'error' => $error,
            'msg' => $msg,
            // "sql" => $sqlstate // Uncomment for debugging purposes
        ];

        echo json_encode($json_array);

        // Close the connection
        $mysqli->close();

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
