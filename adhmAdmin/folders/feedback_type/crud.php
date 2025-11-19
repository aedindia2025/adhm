<?php 

// Get folder Name From Currnent Url 
$folder_name        = explode("/",$_SERVER['PHP_SELF']);
$folder_name        = $folder_name[count($folder_name)-2];

// // Database Country Table Name
$table             = "feedback_type";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action             = $_POST['action'];

$feedback_type      = "";
$is_active          = "";
$unique_id          = "";
$prefix             = "";

$data               = "";
$msg                = "";
$error              = "";
$status             = "";
$test               = ""; // For Developer Testing Purpose
function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
switch ($action) {
    
    case 'createupdate':
        $token = $_POST['csrf_token'];
    
        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }
    
        $feedback_type = sanitizeInput($_POST["feedback_type"]);
        $is_active = sanitizeInput($_POST["is_active"]);
        $unique_id = sanitizeInput($_POST["unique_id"]);
    
        $update_where = "";
    
        $columns = [
            "feedback_type" => $feedback_type,
            "is_active" => $is_active,
            "unique_id" => unique_id($prefix)
        ];
    
        // Check if the feedback_type already exists
        $select_where = 'feedback_type = ? AND is_delete = 0';
    
        // When updating, exclude the current unique_id
        if ($unique_id) {
            $select_where .= ' AND unique_id != ?';
        }
    
        $query = "SELECT COUNT(unique_id) AS count FROM $table WHERE $select_where";
        $stmt = $mysqli->prepare($query);
    
        if ($unique_id) {
            $stmt->bind_param("ss", $feedback_type, $unique_id);
        } else {
            $stmt->bind_param("s", $feedback_type);
        }
    
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
    
        if ($data["count"]) {
            $msg = "already";
            $status = true;
        } else if ($data["count"] == 0) {
            // Update or Insert
            if ($unique_id) {
                unset($columns['unique_id']);
    
                $update_query = "UPDATE $table SET feedback_type = ?, is_active = ? WHERE unique_id = ?";
                $stmt = $mysqli->prepare($update_query);
                $stmt->bind_param("sss", $feedback_type, $is_active, $unique_id);
    
                if ($stmt->execute()) {
                    $msg = "update";
                    $status = true;
                } else {
                    $msg = "error";
                    $status = false;
                    $error = $stmt->error;
                }
            } else {
                $insert_query = "INSERT INTO $table (feedback_type, is_active, unique_id) VALUES (?, ?, ?)";
                $stmt = $mysqli->prepare($insert_query);
                $stmt->bind_param("sss", $feedback_type, $is_active, $columns['unique_id']);
    
                if ($stmt->execute()) {
                    $msg = "create";
                    $status = true;
                } else {
                    $msg = "error";
                    $status = false;
                    $error = $stmt->error;
                }
            }
        }
    
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => isset($error) ? $error : '',
            "msg" => $msg,
            // "sql" => $sql
        ];
    
        echo json_encode($json_array);
    
        break;
    

        case 'datatable':
        
        
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
                "feedback_type",
                "is_active",
                "unique_id"
            ];
            $table_with_counter = "{$table}, (SELECT @a := ?) AS a";
            $where = "is_delete = ?";
            $order_by = "";
        
            $sql_function = "SQL_CALC_FOUND_ROWS";
            $sql_query = "SELECT {$sql_function} " . implode(", ", $columns) . " FROM {$table_with_counter} WHERE {$where}";
        
            if (!empty($limit)) {
                $sql_query .= " LIMIT ?, ?";
            }
        
            // Prepare statement
            $stmt = $mysqli->prepare($sql_query);
        
            if ($stmt) {
                // Bind parameters
                $bind_params = "ii";
                $bind_values = [$start, 0];
        
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
                $stmt->bind_result($s_no, $feedback_type, $is_active, $unique_id);
        
                // Process results
                $sno = $start + 1;
                while ($stmt->fetch()) {
                    $row = [
                        's_no' => $sno++,
                        'feedback_type' => disname($feedback_type),
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
            // $mysqli->close();
        
            // Output JSON response
            echo json_encode($json_array);
            break;
          
        
        
    
    
            case 'delete':

                $token = $_POST['csrf_token'];
    
        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }
        
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
