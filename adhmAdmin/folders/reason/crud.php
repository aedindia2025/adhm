<?php 
// Get folder Name From Currnent Url 
$folder_name        = explode("/",$_SERVER['PHP_SELF']);
$folder_name        = $folder_name[count($folder_name)-2];

// Database Country Table Name
$table             = "reason";

// Include DB file and Common Functions
include '../../config/dbconfig.php';
include 'function.php';

// Variables Declaration
$action             = $_POST['action'];
$action_obj         = (object) [
    "status"    => 0,
    "data"      => "",
    "error"     => "Action Not Performed"
];
$json_array         = "";
$sql                = "";

$main_screen        = "";
$section_name       = "";
$screen_name        = "";
$screen_folder_name = "";
$icon_name          = "";
$order_no           = "";
$user_actions       = "";
$is_active          = "";
$description        = "";
$unique_id          = "";
$prefix             = "";

$data               = "";
$msg                = "";
$error              = "";
$status             = "";
$test               = ""; // For Developer Testing Purpose
$mysqli = new mysqli("localhost", "root", "4/rb5sO2s3TpL4gu", "adi_dravidar");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

switch ($action) {
    case 'createupdate':
        // Retrieve and sanitize input data
        $user_type = $_POST["login"];
        $main_screen = $_POST["main_screen"];
        $screen_name = $_POST["sub_screen"];
        $user_actions = $_POST["process"];
        $is_active = $_POST["is_active"];
        $description = $_POST["description"];
        $unique_id = $_POST["unique_id"];

        // Prepare columns for SQL operations
        $columns = [
            "user_type" => $user_type,
            "main_screen_unique_id" => $main_screen,
            "screen_name" => $screen_name,
            "actions" => $user_actions,
            "is_active" => $is_active,
            "description" => $description,
            "unique_id" => unique_id($prefix) // Generate unique ID if not provided
        ];

        // Check if main screen already exists
        $sql = 'SELECT COUNT(unique_id) AS count FROM ' . $table . ' WHERE main_screen_unique_id = ? AND is_delete = 0';
        if ($unique_id) {
            $sql .= ' AND unique_id != ?';
        }

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $main_screen);
        if ($unique_id) {
            $stmt->bind_param("ss", $main_screen, $unique_id);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        if ($data["count"] > 0) {
            $msg = "already";
        } else {
            if ($unique_id) {
                // Perform update
                $sql = 'UPDATE ' . $table . ' SET user_type = ?, main_screen_unique_id = ?, screen_name = ?, actions = ?, is_active = ?, description = ? WHERE unique_id = ?';
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("ssssiss", $user_type, $main_screen, $screen_name, $user_actions, $is_active, $description, $unique_id);
            } else {
                // Perform insert
                $sql = 'INSERT INTO ' . $table . ' (user_type, main_screen_unique_id, screen_name, actions, is_active, description, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?)';
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("sssssiss", $user_type, $main_screen, $screen_name, $user_actions, $is_active, $description, $columns["unique_id"]);
            }

            if ($stmt->execute()) {
                if ($unique_id) {
                    $msg = "update";
                } else {
                    $msg = "create";
                }
            } else {
                $msg = "error";
            }
        }

        $json_array = [
            "status" => $stmt->affected_rows > 0,
            "data" => [],
            "error" => $mysqli->error,
            "msg" => $msg
        ];

        echo json_encode($json_array);

        // Close statement and connection
        $stmt->close();
        $mysqli->close();

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
            // "user_type",
            "(SELECT user_type  FROM user_type  WHERE user_type.unique_id = reason.user_type) as user_type",
            "(select screen_main_name from user_screen_main where user_screen_main.unique_id=reason.main_screen_unique_id)as user_screen_main",
            "(SELECT screen_name FROM user_screen  WHERE user_screen.unique_id = reason.screen_name) as main_screen",
            // "main_screen_unique_id",
            // "screen_name",
            "actions",
            // "description",
            // "is_active",
            "unique_id"
        ];
        $table_details  = [
            $table." , (SELECT @a:= ".$start.") AS a ",
            $columns
        ];
        $where          = "is_delete = 0";
        

        $sql_function   = "SQL_CALC_FOUND_ROWS";

        $result         = $pdo->select($table_details,$where,$limit,$start,$order_by,$sql_function);
        // print_r()
        $total_records  = total_records();

        if ($result->status) {

            $res_array      = $result->data;

            foreach ($res_array as $key => $value) {

                // $value['screen_name']           = disname($value['screen_name']);
                // $value['main_screen']           = disname($value['main_screen']);
                // $value['is_active']             = is_active_show($value['is_active']);
                $value['actions']               =  disname($value['actions']);
                $btn_update                     = btn_update($folder_name,$value['unique_id']);
                $btn_delete                     = btn_delete($folder_name,$value['unique_id']);
                $value['unique_id']             = $btn_update.$btn_delete;
                $data[]                         = array_values($value);
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

        $unique_id  = $_POST['unique_id'];

        $columns            = [
            "is_delete"   => 1,
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

    case 'sections':

            $main_screen_id        = $_POST['main_screen_id'];

            $section_name_options  = section_name('',$main_screen_id);

            $section_name_options  = select_option($section_name_options,"Select the Screen Section");
    
            echo $section_name_options;
            
            break;


    case 'sub_screen_menu':

        $main_screen       = $_POST['main_screen'];

        $screen_name_options  = sub_screen_main("","",$main_screen);

        $screen_name_options  = select_option($screen_name_options,"Select the Sub screens");

        // print_r($screen_name_options);die();

        echo $screen_name_options;
        
        break;
    
    default:
        
        break;
}

?>