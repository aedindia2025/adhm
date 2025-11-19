<?php 
// Get folder Name From Currnent Url 
$folder_name        = explode("/",$_SERVER['PHP_SELF']);
$folder_name        = $folder_name[count($folder_name)-2];

// Database Country Table Name
$table             = "user_screen_permission";
$table_log         = "user_screen_permission_log";

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

switch ($action) {
    case 'createupdate':

        
        $json_data = json_decode($_POST['json_data']);
        $rows = [];
        $columns_data = [];
        $user_types = isset($_POST["user_type"]) ? sanitizeInput($_POST["user_type"]) : $_POST["unique_id"];
        $user_type = sanitizeInput($user_types);
        $main_screen = sanitizeInput($_POST["main_screen"]);
        $unique_id = $_POST["unique_id"];
    
        $columns = [
            "unique_id",
            "user_type",
            "main_screen_unique_id",
            "section_unique_id",
            "screen_unique_id",
            "action_unique_id"
        ];
    
        foreach ($json_data as $data_value) {
            $columns_data = [
                "unique_id" => unique_id($prefix),
                "user_type" => sanitizeInput($user_type),
                "main_screen_unique_id" => sanitizeInput($main_screen),
                "section_unique_id" => sanitizeInput($data_value->section),
                "screen_unique_id" => sanitizeInput($data_value->screen),
                "action_unique_id" => sanitizeInput($data_value->action)
            ];
    
            $rows[] = $columns_data;
        }
    
        // Check if record already exists
        $select_sql = 'SELECT COUNT(unique_id) AS count FROM ' . $table . ' WHERE main_screen_unique_id = ? AND user_type = ? AND is_delete = 0';
        if ($unique_id) {
            $select_sql .= ' AND user_type != ?';
        }
    
        // Prepare statement
        $stmt = $mysqli->prepare($select_sql);
        if ($unique_id) {
            $stmt->bind_param("ssi", $main_screen, $user_type, $unique_id);
        } else {
            $stmt->bind_param("ss", $main_screen, $user_type);
        }
    
        // Execute statement
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
    
        $stmt->close();
    
        if ($data["count"] > 0) {
            $msg = "already";
        } else {
            if ($unique_id) {
                // Delete existing records for update
                $delete_sql = 'DELETE FROM ' . $table . ' WHERE user_type = ? AND main_screen_unique_id = ?';
                $stmt = $mysqli->prepare($delete_sql);
                $stmt->bind_param("ss", $unique_id, $main_screen);
                $stmt->execute();
                $stmt->close();
            }
    
            // Insert new records
            $insert_sql = 'INSERT INTO ' . $table . ' (unique_id, user_type, main_screen_unique_id, section_unique_id, screen_unique_id, action_unique_id) VALUES (?, ?, ?, ?, ?, ?)';
            $stmt = $mysqli->prepare($insert_sql);
    
            foreach ($rows as $row) {
                $stmt->bind_param("ssssss", $row['unique_id'], $row['user_type'], $row['main_screen_unique_id'], $row['section_unique_id'], $row['screen_unique_id'], $row['action_unique_id']);
                $stmt->execute();
            }
    
            $stmt->close();
    
            // Log data entry
            $log_sql = 'INSERT INTO ' . $table_log . ' (unique_id, user_type, main_screen_unique_id, section_unique_id, screen_unique_id, action_unique_id) VALUES (?, ?, ?, ?, ?, ?)';
            $stmt = $mysqli->prepare($log_sql);
    
            foreach ($rows as $row) {
                $stmt->bind_param("ssssss", $row['unique_id'], $row['user_type'], $row['main_screen_unique_id'], $row['section_unique_id'], $row['screen_unique_id'], $row['action_unique_id']);
                $stmt->execute();
            }
    
            $stmt->close();
    
            $status = true;
            $data = [];
            $error = "";
            $msg = $unique_id ? "update" : "create";
        }
    
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
        ];
    
        echo json_encode($json_array);
    
        break;
    

        case 'datatable':
            // Database connection details
         
        
            // DataTable Variables
           
            $length = isset($_POST['length']) ? intval($_POST['length']) : -1;
            $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
            $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
            $limit = $length;
        
            $data = [];
        
            if ($length == -1) {
                $limit = "";
            }
        
            // Query Variables
            $json_array = "";
            $columns = [
                "(SELECT user_type FROM user_type AS ut WHERE ut.unique_id = {$table}.user_type) AS user_type",
                "user_type AS unique_id"
            ];
            $table_with_counter = "{$table}, (SELECT @a := ?) AS a";
            $where = "is_delete = ?";
            $group_by = "user_type";
            $order_by = "";
        
           
            $sql_function = "SQL_CALC_FOUND_ROWS";
            $sql_query = "SELECT {$sql_function} " . implode(", ", $columns) . " FROM {$table_with_counter} WHERE {$where} GROUP BY {$group_by}";
        
            if (!empty($limit)) {
                $sql_query .= " LIMIT ?, ?";
            }
        
            // Prepare statement
            $stmt = $mysqli->prepare($sql_query);
        
            if ($stmt) {
                // Bind parameters
                $bind_params = "is";
                $bind_values = [$start, '0'];
        
             
        
                if (!empty($limit)) {
                    $bind_params .= "ii";
                    $bind_values[] = $start;
                    $bind_values[] = $limit;
                }
        
                // Dynamically bind parameters
                $stmt->bind_param($bind_params, ...$bind_values);
        
                // Execute statement
                $stmt->execute();
        
                // Bind result variables
                $stmt->bind_result($user_type, $unique_id);
        
                // Process results
                $sno = $start + 1;
                while ($stmt->fetch()) {
                    $row = [
                        's_no' => $sno++,
                        'user_type' => disname($user_type),
                        'unique_id' => $unique_id
                    ];
        
                    $btn_update = btn_update($folder_name, $unique_id);
                    $btn_delete = btn_delete($folder_name, $unique_id);
        
                    if ($unique_id == "5f97fc3257f2525529") {
                        $btn_update = "";
                        $btn_delete = "";
                    }
        
                    $row['unique_id'] = $btn_update . $btn_delete;
                    $data[] = array_values($row);
                }
        
                // Fetch total records
                $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
                $total_records = $total_records_result->fetch_assoc()['total'];
        
                // Prepare JSON response for DataTables
                $json_array = [
                    "draw" => intval($draw),
                    "recordsTotal" => intval($total_records),
                    "recordsFiltered" => intval($total_records),
                    "data" => $data
                ];
        
                // Close statement
                $stmt->close();
            } else {
                // Handle statement preparation error
                $json_array = [
                    "draw" => intval($draw),
                    "recordsTotal" => 0,
                    "recordsFiltered" => 0,
                    "data" => [],
                    "error" => $mysqli->error
                ];
            }
        
            // Close MySQLi connection
            $mysqli->close();
        
            // Output JSON response
            echo json_encode($json_array);
            break;
        
    
    
            case 'delete':

                
                $unique_id = isset($_POST['unique_id']) ? $_POST['unique_id'] : '';
        
                if (!$unique_id) {
                    $json_array = [
                        "status" => false,
                        "msg" => "missing_unique_id"
                    ];
                    echo json_encode($json_array);
                    break;
                }
        $is_delete = '1';
                // Prepare and execute SQL statement
                $sql = "UPDATE $table SET is_delete = ? WHERE unique_id = ?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("ss",$is_delete,$unique_id);
        
                // Execute statement and handle result
                if ($stmt->execute()) {
                    $status = true;
                    $msg = "success_delete";
                } else {
                    $status = false;
                    $msg = "error";
                    $error = $stmt->error;
                }
        
                // Prepare JSON response
                $json_array = [
                    "status" => $status,
                    "msg" => $msg,
                    "error" => $error
                ];
        
                echo json_encode($json_array);
                break;

    case 'sections':

            $main_screen_id        = $_POST['main_screen_id'];

            $section_name_options  = section_name('',$main_screen_id);

            $section_name_options  = select_option($section_name_options,"Select the Screen Section");
    
            echo $section_name_options;
            
            break;
    
    case 'permission_ui':

        $main_screen_id         = $_POST['main_screen'];
        $user_type              = $_POST['user_type'];

        $perm_ui               = user_permission_ui($main_screen_id,$user_type);

        // $section_name_options  = section_name('',$main_screen_id);

        // $section_name_options  = select_option($section_name_options,"Select the Screen Section");

        echo $perm_ui;
        
        break;

    default:
            
            break;
}

?>