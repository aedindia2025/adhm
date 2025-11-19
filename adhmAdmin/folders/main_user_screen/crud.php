<?php 
// Get folder Name From Currnent Url 
$folder_name        = explode("/",$_SERVER['PHP_SELF']);
$folder_name        = $folder_name[count($folder_name)-2];

// Database Country Table Name
$table             = "user_screen_main";

// Include DB file and Common Functions
include '../../config/dbconfig.php';

// Variables Declaration
$action             = $_POST['action'];
$action_obj         = (object) [
    "status"    => 0,
    "data"      => "",
    "error"     => "Action Not Performed"
];
$json_array         = "";
$sql                = "";

$screen_type        = "";
$screen_name        = "";
$icon_name        = "";
$order_no           = "";
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
        $screen_type = sanitizeInput($_POST["screen_type"]);
        $screen_name = sanitizeInput($_POST["screen_name"]);
        $icon_name = sanitizeInput($_POST["icon_name"]);
        $order_no = sanitizeInput($_POST["order_no"]);
        $is_active = sanitizeInput($_POST["active_status"]);
        $description = sanitizeInput($_POST["description"]);
        $unique_id = $_POST["unique_id"];
        $prefix = ''; // Define your prefix here if needed
    
        $update_where = "";
        $columns = [
            "screen_type_unique_id" => $screen_type,
            "screen_main_name" => $screen_name,
            "icon_name" => $icon_name,
            "order_no" => $order_no,
            "is_active" => $is_active,
            "description" => $description,
            "unique_id" => unique_id($prefix)
        ];
    
        // Check if record already exists
        $select_sql = "SELECT COUNT(unique_id) AS count FROM $table WHERE (screen_main_name = ? OR order_no = ?) AND is_delete = 0";
        if ($unique_id) {
            $select_sql .= " AND unique_id != ?";
        }
    
        // Prepare statement
        $stmt = $mysqli->prepare($select_sql);
        if ($unique_id) {
            $stmt->bind_param("ssi", $screen_name, $order_no, $unique_id);
        } else {
            $stmt->bind_param("ss", $screen_name, $order_no);
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
                $update_sql = "UPDATE $table SET screen_type_unique_id = ?, screen_main_name = ?, icon_name = ?, order_no = ?, is_active = ?, description = ? WHERE unique_id = ?";
                $stmt = $mysqli->prepare($update_sql);
                $stmt->bind_param("ssssiss", $screen_type, $screen_name, $icon_name, $order_no, $is_active, $description, $unique_id);
            } else {
                // Insert
                $insert_sql = "INSERT INTO $table (screen_type_unique_id, screen_main_name, icon_name, order_no, is_active, description, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $mysqli->prepare($insert_sql);
                $stmt->bind_param("ssssiss", $screen_type, $screen_name, $icon_name, $order_no, $is_active, $description, $columns['unique_id']);
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
            $length = intval($_POST['length']);
            $start = intval($_POST['start']);
            $draw = intval($_POST['draw']);
            $limit = $length;
        
            $data = [];
        
            if ($length == '-1') {
                $limit = "";
            }
        
            // Query Variables
            $columns = [
                "@a:=@a+1 s_no",
                "screen_main_name",
                "(SELECT type_name FROM user_screen_type AS ust WHERE ust.unique_id = " . $table . ".screen_type_unique_id ) AS screen_type",
                "order_no",
                "is_active",
                "unique_id"
            ];
            $table_with_counter = "{$table}, (SELECT @a := ?) AS a";
            $where = "is_delete = ?";
        
            // DataTable Searching
           
        
            $sql_function = "SQL_CALC_FOUND_ROWS";
            $sql_query = "SELECT {$sql_function} " . implode(", ", $columns) . " FROM {$table_with_counter} WHERE {$where}";
        
            if (!empty($limit)) {
                $sql_query .= " LIMIT ?, ?";
            }
        
            // Prepare statement
            $stmt = $mysqli->prepare($sql_query);
        
            // Bind parameters dynamically
            if ($stmt) {
                $bind_params = ['ii'];
                $bind_values = [$start, 0];
        
               
        
                if (!empty($limit)) {
                    $bind_params[] = 'ii';
                    $bind_values[] = $start;
                    $bind_values[] = $limit;
                }
        
                $stmt->bind_param(implode('', $bind_params), ...$bind_values);
        
                // Execute statement
                $stmt->execute();
        
                // Bind result variables
                $result = $stmt->get_result();
        
                // Process results
                $sno = $start + 1;
                while ($row = $result->fetch_assoc()) {
                    $row['s_no'] = $sno++;
                    $row['screen_main_name'] = disname($row['screen_main_name']);
                    $row['screen_type'] = disname($row['screen_type']);
                    $row['is_active'] = is_active_show($row['is_active']);
        
                    $unique_id = $row['unique_id'];
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
        
                // Close statement and connection
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
    default:
        
        break;
}

?>