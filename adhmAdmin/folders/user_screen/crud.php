<?php 
// Get folder Name From Currnent Url 
$folder_name        = explode("/",$_SERVER['PHP_SELF']);
$folder_name        = $folder_name[count($folder_name)-2];

// Database Country Table Name
$table             = "user_screen";

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
        $main_screen = sanitizeInput($_POST["main_screen"]);
        $sub_screen = sanitizeInput($_POST["sub_screen"]);
        $sub_screen_icon_name = sanitizeInput($_POST["sub_screen_icon_name"]);
        $screen_name = sanitizeInput($_POST["screen_name"]);
        $screen_folder_name = sanitizeInput($_POST["screen_folder_name"]);
        $icon_name = sanitizeInput($_POST["icon_name"]);
        $order_no = sanitizeInput($_POST["order_no"]);
        $user_actions = sanitizeInput($_POST["user_actions"]);
        $is_active = sanitizeInput($_POST["is_active"]);
        $description = sanitizeInput($_POST["description"]);
        $unique_id = sanitizeInput($_POST["unique_id"]);
        $prefix = ''; // Define your prefix here if needed
    
        $update_where = "";
        $columns = [
            "main_screen_unique_id" => $main_screen,
            "sub_screen_unique_id" => $sub_screen,
            "sub_screen_icon" => $sub_screen_icon_name,
            "screen_name" => $screen_name,
            "folder_name" => $screen_folder_name,
            "actions" => $user_actions,
            "icon_name" => $icon_name,
            "order_no" => $order_no,
            "is_active" => $is_active,
            "description" => $description,
            "unique_id" => unique_id($prefix)
        ];
    
        // Check if record already exists
        $select_sql = "SELECT COUNT(unique_id) AS count FROM $table WHERE ((order_no = ? AND main_screen_unique_id = ?) OR (folder_name = ? AND main_screen_unique_id = ? AND sub_screen_unique_id = ?)) AND is_delete = 0";
        if ($unique_id) {
            $select_sql .= " AND unique_id != ?";
        }
   
        // Prepare statement
        $stmt = $mysqli->prepare($select_sql);
        if ($unique_id) {
            $stmt->bind_param("ssssss", $order_no, $main_screen, $screen_folder_name, $main_screen, $sub_screen, $unique_id);
        } else {
            $stmt->bind_param("sssss", $order_no, $main_screen, $screen_folder_name, $main_screen, $sub_screen);
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
                // Update
                $update_sql = "UPDATE $table SET main_screen_unique_id = ?, sub_screen_unique_id = ?, sub_screen_icon = ?, screen_name = ?, folder_name = ?, actions = ?, icon_name = ?, order_no = ?, is_active = ?, description = ? WHERE unique_id = ?";
                $stmt = $mysqli->prepare($update_sql);
                $stmt->bind_param("sssssssssss", $main_screen, $sub_screen, $sub_screen_icon_name, $screen_name, $screen_folder_name, $user_actions, $icon_name, $order_no, $is_active, $description, $unique_id);
            } else {
                // Insert
                $insert_sql = "INSERT INTO $table (main_screen_unique_id, sub_screen_unique_id, sub_screen_icon, screen_name, folder_name, actions, icon_name, order_no, is_active, description, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $mysqli->prepare($insert_sql);
                $stmt->bind_param("sssssssssss", $main_screen, $sub_screen, $sub_screen_icon_name, $screen_name, $screen_folder_name, $user_actions, $icon_name, $order_no, $is_active, $description, $columns['unique_id']);
            }
    
            // Execute statement
            $stmt->execute();
    
            if ($stmt->affected_rows > 0) {
                $msg = $unique_id ? "update" : "create";
                $status = true;
                $data = [];
                $error = "";
            } else {
                $msg = "error";
                $status = false;
                $data = [];
                $error = $stmt->error;
            }
    
            $stmt->close();
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
                "@a:=@a+1 s_no",
                "screen_name",
                "(SELECT screen_main_name FROM user_screen_main AS usm WHERE usm.unique_id = {$table}.main_screen_unique_id) AS main_screen",
                "order_no",
                "is_active",
                "unique_id"
            ];
            $table_with_counter = "{$table}, (SELECT @a := ?) AS a";
            $where = "is_delete = ?";
            $order_by = "main_screen_unique_id, order_no";
        
         
            $sql_function = "SQL_CALC_FOUND_ROWS";
            $sql_query = "SELECT {$sql_function} " . implode(", ", $columns) . " FROM {$table_with_counter} WHERE {$where}";
        
            if (!empty($limit)) {
                $sql_query .= " LIMIT ?, ?";
            }
        
            // Prepare statement
            $stmt = $mysqli->prepare($sql_query);
        
            if ($stmt) {
                // Bind parameters
                $bind_params = "si";
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
                $stmt->bind_result($s_no, $screen_name, $main_screen, $order_no, $is_active, $unique_id);
        
                // Process results
                $sno = $start + 1;
                while ($stmt->fetch()) {
                    $row = [
                        's_no' => $sno++,
                        'screen_name' => disname($screen_name),
                        'main_screen' => disname($main_screen),
                        'order_no' => $order_no,
                        'is_active' => is_active_show($is_active),
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
            // Validate input
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
    
    default:
        
        break;
}

?>