<?php 

// Get folder Name From Currnent Url 
$folder_name        = explode("/",$_SERVER['PHP_SELF']);
$folder_name        = $folder_name[count($folder_name)-2];

// // Database Country Table Name
$table             = "product_type";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';
function validateCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}



// // Variables Declaration
$action             = $_POST['action'];

$zone_name         = "";
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
        $token = $_POST['csrf_token'];
        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }
        // Assuming these are the inputs from your form or wherever you're getting them
        $product_category = sanitizeInput($_POST["product_category"]);
        $product_type = sanitizeInput($_POST["product_type"]);
        $unit_category = sanitizeInput($_POST["unit_category"]);
        $description = sanitizeInput($_POST["description"]);
        $is_active = sanitizeInput($_POST["is_active"]);
        $unique_id = sanitizeInput($_POST["unique_id"]);
    
        // Initialize variables
        $update_where = "";
        $status = false;
        $data = [];
        $error = "";
        $msg = "";
    
        // Prepare columns array
        $columns = [
            "product_category" => $product_category,
            "product_type" => $product_type,
            "unit_category" => $unit_category,
            "description" => $description,
            "is_active" => $is_active,
            "unique_id" => unique_id($prefix) // Ensure $prefix is defined or replaced appropriately
        ];
    
        // Check if the entry already exists
        $select_query = "SELECT COUNT(unique_id) AS count FROM $table WHERE product_type = ? AND is_delete = 0";
    
        // If updating, exclude the current record
        if ($unique_id) {
            $select_query .= " AND unique_id != ?";
        }
    
        // Prepare statement for select query
        $stmt = $mysqli->prepare($select_query);
    
        // Bind parameters for select query
        if ($unique_id) {
            $stmt->bind_param('ss', $product_type, $unique_id);
        } else {
            $stmt->bind_param('s', $product_type);
        }
    
        // Execute select query
        $stmt->execute();
    
        // Get result of select query
        $result = $stmt->get_result();
    
        // Fetch data from result
        $data = $result->fetch_assoc();
    
        // Handle result of select query
        if ($data['count']) {
            $msg = "already";
            $status = true;
        } else {
            // Perform update or insert based on whether unique_id is set
            if ($unique_id) {
                // Update existing record
                $update_query = "UPDATE $table SET product_category = ?, product_type = ?, unit_category = ?, description = ?, is_active = ? WHERE unique_id = ?";
                $stmt = $mysqli->prepare($update_query);
                $stmt->bind_param('ssssis', $product_category, $product_type, $unit_category, $description, $is_active, $unique_id);
            } else {
                // Insert new record
                $insert_query = "INSERT INTO $table (product_category, product_type, unit_category, description, is_active, unique_id) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $mysqli->prepare($insert_query);
                $stmt->bind_param('ssssis', $product_category, $product_type, $unit_category, $description, $is_active, $columns['unique_id']);
            }
    
            // Execute update or insert query
            if ($stmt->execute()) {
                $status = true;
                $msg = $unique_id ? "update" : "create";
                $sql = $stmt->sql;
            } else {
                $error = $stmt->error;
                $msg = "error";
                $sql = $stmt->sql;
            }
        }
    
        // Prepare response as JSON
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            "sql" => $sql // Optionally include SQL query for debugging
        ];
    
        echo json_encode($json_array);
    
        // Close statement and connection
        $stmt->close();
        $mysqli->close();
    
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
                "(SELECT product_category FROM product_category WHERE product_category.unique_id={$table}.product_category) AS product_category",
                "product_type",
                "(SELECT unit_measurement FROM unit_measurement WHERE unit_measurement.unique_id={$table}.unit_category) AS unit_category",
                "description",
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
                $stmt->bind_result($s_no, $product_category, $product_type, $unit_category, $description, $is_active, $unique_id);
        
                // Process results
                $sno = $start + 1;
                while ($stmt->fetch()) {
                    $row = [
                        's_no' => $sno++,
                        'product_category' => disname($product_category),
                        'product_type' => disname($product_type),
                        'unit_category' => disname($unit_category),
                        'description' => disname($description),
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
                    "data" => $data,
                   
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