<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "dropout_reason";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';


// // Variables Declaration
$action = $_POST['action'];

$hostel_type = "";
$is_active = "";
$unique_id = "";
$prefix = "";

$data = "";
$msg = "";
$error = "";
$status = "";
$test = ""; // For Developer Testing Purpose

function validateCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

switch ($action) {
    case 'createupdate':

$token = $_POST['csrf_token'];
if (!validateCSRFToken($token)) {
    die('CSRF validation failed.');
}

        $dropout_reason = sanitizeInput($_POST["dropout_reason"]);
        $is_active = sanitizeInput($_POST["is_active"]);
        $unique_id = sanitizeInput($_POST["unique_id"]);

        $update_where = "";

        $columns = [
            "dropout_reason" => $dropout_reason,
            "is_active" => $is_active,
            "unique_id" => unique_id($prefix)
        ];

        // Check already Exist Or not
        $select_where = 'dropout_reason = ? AND is_delete = 0';

        // When Update Check without current id
        if ($unique_id) {
            $select_where .= ' AND unique_id != ?';
        }

        $stmt = $mysqli->prepare("SELECT COUNT(unique_id) AS count FROM $table WHERE $select_where");
        if ($unique_id) {
            $stmt->bind_param("ss", $hostel_type, $unique_id);
        } else {
            $stmt->bind_param("s", $hostel_type);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        if ($data["count"]) {
            $msg = "already";
	    $status = true;
        } else if ($data["count"] == 0) {
            // Update Begins
            if ($unique_id) {
                unset($columns['unique_id']);
                $stmt = $mysqli->prepare("UPDATE $table SET dropout_reason = ?, is_active = ? WHERE unique_id = ?");
                $stmt->bind_param("sss", $dropout_reason, $is_active, $unique_id);
                // Update Ends
            } else {
                // Insert Begins
                $stmt = $mysqli->prepare("INSERT INTO $table (dropout_reason, is_active, unique_id) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $dropout_reason, $is_active, $columns['unique_id']);
                // Insert Ends
            }

            $status = $stmt->execute();
            if ($status) {
                $data = [];
                $error = "";
                $sql = "";

                if ($unique_id) {
                    $msg = "update";
                } else {
                    $msg = "create";
                }
            } else {
                $status = false;
                $data = [];
                $error = $stmt->error;
                $sql = "";
                $msg = "error";
            }
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql" => $sql
        ];

        echo json_encode($json_array);

        break;

        case 'datatable':
            // DataTable Variables
            $search     = $_POST['search']['value'];
            $length     = $_POST['length'];
            $start      = $_POST['start'];
            $draw       = $_POST['draw'];
            $limit      = $length;
    
            $data       = [];
            
    
            if($length == '-1') {
                $limit  = "";
            }
    
            // Query Variables
            $json_array     = "";
            $columns        = [
                "@a:=@a+1 s_no",
                "dropout_reason",
                "is_active",
                "unique_id"
            ];
            $table_details  = [
                $table." , (SELECT @a:= 0) AS a ",
                $columns
            ];
            $where          = "is_delete = 0";
            $order_by       = "";
          
            $sql_function   = "SQL_CALC_FOUND_ROWS";
    
            $result         = $pdo->select($table_details,$where,$limit,$start,$order_by,$sql_function);
            $total_records  = total_records();
            // print_r($result);
    
            if ($result->status) {
    
                $res_array      = $result->data;
    
                foreach ($res_array as $key => $value) {
                   
                    $value['is_active'] = is_active_show($value['is_active']);

                    if($value['unique_id'] == '673f05bd7d90c91668'){
                        $btn_update         = '';
                    $btn_delete         = '';
                    }else{
                    $btn_update         = btn_update($folder_name,$value['unique_id']);
                    $btn_delete         = btn_delete($folder_name,$value['unique_id']);
                    }
    
                    $value['unique_id'] = $btn_update . $btn_delete;
                    $data[]             = array_values($value);
                }
                
                $json_array = [
                    "draw"              => intval($draw),
                    "recordsTotal"      => intval($total_records),
                    "recordsFiltered"   => intval($total_records),
                    "data"              => $data,
                    "testing"           => $result->sql
                ];
            } else {
                print_r($result);
            }
            
            echo json_encode($json_array);
            break;

        case 'delete':
            
$token = $_POST['csrf_token'];
if (!validateCSRFToken($token)) {
    die('CSRF validation failed.');
}
        
            $unique_id = $_POST['unique_id'];
    
            $stmt = $mysqli->prepare("UPDATE $table SET is_delete = 1 WHERE unique_id = ?");
            $stmt->bind_param("s", $unique_id);
            $status = $stmt->execute();
    
            if ($status) {
                $data = [];
                $error = "";
                $sql = "";
                $msg = "success_delete";
    
            } else {
                $data = [];
                $error = $stmt->error;
                $sql = "";
                $msg = "error";
            }
    
            $json_array = [
                "status" => $status,
                "data" => $data,
                "error" => $error,
                "msg" => $msg,
                // "sql" => $sql
            ];
    
            echo json_encode($json_array);
            break;

    default:

        break;
}


?>