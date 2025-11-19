<?php 

// Get folder Name From Currnent Url 
$folder_name        = explode("/",$_SERVER['PHP_SELF']);
$folder_name        = $folder_name[count($folder_name)-2];

// // Database Country Table Name
$table             = "user_creation";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action             = $_POST['action'];

$user_type          = "";
$is_active          = "";
$unique_id          = "";
$prefix             = "";

$data               = "";
$msg                = "";
$error              = "";
$status             = "";
$test               = ""; // For Developer Testing Purpose

switch ($action) {
    case 'createupdate':

        $user_type          = sanitizeInput($_POST["user_type"]);
        $user_name          = sanitizeInput($_POST["user_name"]);
        $staff_name         = sanitizeInput($_POST["staff_name"]);
        $password           = sanitizeInput($_POST["password"]);
        $conform_password   = sanitizeInput($_POST["conform_password"]);
        $branch             = sanitizeInput($_POST["branch"]);
        $warehouse          = sanitizeInput($_POST["warehouse"]);
        $is_active          = sanitizeInput($_POST["is_active"]);
        $unique_id          = sanitizeInput($_POST["unique_id"]);

        $update_where       = "";

        $columns            = [
            "user_type"           => $user_type,
            "user_name"           => $user_name,
            "staff_name"          => $staff_name,
            "password"            => $password,
            "conform_password"    => $conform_password,
            "branch"              => $branch,
            "warehouse"           => $warehouse,
            "password"            => $password,
            "is_active"           => $is_active,
            "unique_id"           => unique_id($prefix)
        ];

            // check already Exist Or not
        $table_details      = [
            $table,
            [
                "COUNT(unique_id) AS count"
            ]
        ];
        $select_where       = 'user_type = "'.$user_type.'" AND user_name = "'.$user_name.'"  AND is_delete = 0  ';

        // When Update Check without current id
        if ($unique_id) {
            $select_where   .= ' AND unique_id !="'.$unique_id.'" ';
        }

        $action_obj         = $pdo->select($table_details,$select_where);

        if ($action_obj->status) {
            $status     = $action_obj->status;
            $data       = $action_obj->data;
            $error      = "";
            $sql        = $action_obj->sql;

        } else {
            $status     = $action_obj->status;
            $data       = $action_obj->data;
            $error      = $action_obj->error;
            $sql        = $action_obj->sql;
            $msg        = "error";
        }
        if ($data[0]["count"]) {
            $msg        = "already";
        } else if ($data[0]["count"] == 0) {
            // Update Begins
            if($unique_id) {

                unset($columns['unique_id']);

                $update_where   = [
                    "unique_id"     => $unique_id
                ];

                $action_obj     = $pdo->update($table,$columns,$update_where);

            // Update Ends
            } else {

                // Insert Begins            
                $action_obj     = $pdo->insert($table,$columns);
                // Insert Ends

            }

            if ($action_obj->status) {
                $status     = $action_obj->status;
                $data       = $action_obj->data;
                $error      = "";
                $sql        = $action_obj->sql;

                if ($unique_id) {
                    $msg        = "update";
                } else {
                    $msg        = "create";
                }
            } else {
                $status     = $action_obj->status;
                $data       = $action_obj->data;
                $error      = $action_obj->error;
                $sql        = $action_obj->sql;
                $msg        = "error";
            }
        }

        $json_array   = [
            "status"    => $status,
            "data"      => $data,
            "error"     => $error,
            "msg"       => $msg,
            // "sql"       => $sql
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
            "(SELECT user_type FROM user_type AS user_type WHERE user_type.unique_id = ".$table.".user_type ) AS user_type",
            "(SELECT staff_name FROM staff WHERE staff.unique_id = ".$table.".staff_name ) AS staff_name",
            "user_name",
            "password",
            "(SELECT branch_name FROM branch_creation WHERE branch_creation.unique_id = ".$table.".branch) as branch_name",
            "(SELECT warehouse_name FROM warehouse_creation WHERE warehouse_creation.unique_id = ".$table.".warehouse) as warehouse_name",
            "is_active",
            "unique_id"
        ];
        $table_details  = [
            $table." , (SELECT @a:= ".$start.") AS a ",
            $columns
        ];
        $where          = "is_delete = 0";
        $order_by       = "";
        if ($_POST['search']['value']) {
            $where .= " AND  (user_name LIKE '".mysql_like($_POST['search']['value'])."' ";
            $where .= " OR user_type IN (".user_type_like($_POST['search']['value']).")";
            $where .= " OR staff_name IN (".staff_name_like($_POST['search']['value']).")";
            $where .= " OR branch IN (".branch_name_like($_POST['search']['value']).")";
            $where .= " OR warehouse IN (".warehouse_name_like($_POST['search']['value']).") )";
        }


        $sql_function   = "SQL_CALC_FOUND_ROWS";

        $result         = $pdo->select($table_details,$where,$limit,$start,$order_by,$sql_function);
        $total_records  = total_records();

        if ($result->status) {

            $res_array      = $result->data;

            foreach ($res_array as $key => $value) {
                $value['user_type'] = disname($value['user_type']);
                $value['is_active'] = is_active_show($value['is_active']);

                $btn_update         = btn_update($folder_name,$value['unique_id']);
                $btn_delete         = btn_delete($folder_name,$value['unique_id']);


                $value['unique_id'] = $btn_update.$btn_delete;
                $data[]             = array_values($value);
            }
            
            $json_array = [
                "draw"              => intval($draw),
                "recordsTotal"      => intval($total_records),
                "recordsFiltered"   => intval($total_records),
                "data"              => $data,
                // "testing"           => $result->sql
            ];
        } else {
            // print_r($result);
        }
        
        echo json_encode($json_array);
        break;
    
    
    case 'delete':
        
        $unique_id      = $_POST['unique_id'];

        $columns        = [
            "is_delete"   => 1
        ];

        $update_where   = [
            "unique_id"     => $unique_id
        ];

        $action_obj     = $pdo->update($table,$columns,$update_where);

        if ($action_obj->status) {
            $status     = $action_obj->status;
            $data       = $action_obj->data;
            $error      = "";
            $sql        = $action_obj->sql;
            $msg        = "success_delete";

        } else {
            $status     = $action_obj->status;
            $data       = $action_obj->data;
            $error      = $action_obj->error;
            $sql        = $action_obj->sql;
            $msg        = "error";
        }

        $json_array   = [
            "status"    => $status,
            "data"      => $data,
            "error"     => $error,
            "msg"       => $msg,
            // "sql"       => $sql
        ];

        echo json_encode($json_array);
        break;

    case 'warehouse':

        $branch           = $_POST['branch'];

        $warehouse_options  = warehouse("",$branch);

        $warehouse_options  = select_option($warehouse_options,"Select Warehouse");

        echo $warehouse_options;
        
        break;
    default:
        
        break;
}
?>
    
