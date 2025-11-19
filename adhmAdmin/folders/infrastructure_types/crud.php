<?php 

// Get folder Name From Currnent Url 
$folder_name        = explode("/",$_SERVER['PHP_SELF']);
$folder_name        = $folder_name[count($folder_name)-2];

// // Database Country Table Name
$table             = "infrastructure_types";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// Connect to the database using MySQLi
$mysqli = new mysqli("localhost", "root", "4/rb5sO2s3TpL4gu", "adi_dravidar");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// // Variables Declaration
$action             = $_POST['action'];

$infrastructure_types          = "";
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
    
        // Sanitizing input
        $infrastructure_types = sanitizeInput($_POST["infrastructure_types"]);
        $description = sanitizeInput($_POST["description"]);
        $is_active = $_POST["is_active"];
        $unique_id = $_POST["unique_id"];
        
        $update_where = "";
    
        $columns = [
            "infrastructure_types" => $infrastructure_types,
            "description" => $description,
            "is_active" => $is_active,
            "unique_id" => unique_id($prefix)
        ];
    
        // Check if already exists
        $select_where = 'infrastructure_types = ? AND is_delete = 0';
    
        // When update check without current id
        if ($unique_id) {
            $select_where .= ' AND unique_id != ?';
        }
    
        $stmt = $mysqli->prepare("SELECT COUNT(unique_id) AS count FROM $table WHERE $select_where");
        
        if ($unique_id) {
            $stmt->bind_param('ss', $infrastructure_types, $unique_id);
        } else {
            $stmt->bind_param('s', $infrastructure_types);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        
        if ($data['count']) {
            $msg = "already";
        } else {
            // Update or insert record
            if ($unique_id) {
                unset($columns['unique_id']);
                $update_columns = [];
                $update_values = [];
                foreach ($columns as $key => $value) {
                    $update_columns[] = "$key = ?";
                    $update_values[] = $value;
                }
                $update_values[] = $unique_id;
    
                $update_query = "UPDATE $table SET " . implode(', ', $update_columns) . " WHERE unique_id = ?";
                $stmt = $mysqli->prepare($update_query);
                $stmt->bind_param(str_repeat('s', count($update_values)), ...$update_values);
            } else {
                $insert_columns = array_keys($columns);
                $insert_values = array_values($columns);
    
                $insert_query = "INSERT INTO $table (" . implode(', ', $insert_columns) . ") VALUES (" . implode(', ', array_fill(0, count($insert_columns), '?')) . ")";
                $stmt = $mysqli->prepare($insert_query);
                $stmt->bind_param(str_repeat('s', count($insert_values)), ...$insert_values);
            }
    
            $stmt->execute();
    
            if ($stmt->affected_rows > 0) {
                if ($unique_id) {
                    $msg = "update";
                } else {
                    $msg = "create";
                }
                $status = true;
                $data = $stmt->insert_id;
                $error = "";
            } else {
                $status = false;
                $data = [];
                $error = $stmt->error;
                $msg = "error";
            }
        }
    
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg
        ];
    
        echo json_encode($json_array);
    
        break;
    

    case 'datatable':
        // DataTable Variables
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
            "infrastructure_types",
            "description",
            "is_active",
            "unique_id"
        ];
        $table_details  = [
            $table." , (SELECT @a:= ".$start.") AS a ",
            $columns
        ];
        $where          = "is_delete = 0";
        $order_by       = "";

        $sql_function   = "SQL_CALC_FOUND_ROWS";

        $result         = $pdo->select($table_details,$where,$limit,$start,$order_by,$sql_function);
        // print_r($result);
        $total_records  = total_records();

        if ($result->status) {

            $res_array      = $result->data;

            foreach ($res_array as $key => $value) {
               // $value['fund_name'] = disname($value['fund_name']);
                $value['is_active'] = is_active_show($value['is_active']);
                

                $btn_update         = btn_update($folder_name,$value['unique_id']);
                $btn_delete         = btn_delete($folder_name,$value['unique_id']);

                if ( $value['unique_id'] == "5f97fc3257f2525529") {
                    $btn_update         = "";
                    $btn_delete         = "";
                } 

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
?>
