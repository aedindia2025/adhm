<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "block_name";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action = $_POST['action'];

$block_name = "";
$district_name = "";
$is_active = "";
$unique_id = "";
$prefix = "";

$data = "";
$msg = "";
$error = "";
$status = "";
$test = ""; // For Developer Testing Purpose
function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}


switch ($action) {
    case 'createupdate':
        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        $district_name = sanitizeInput($_POST["district_name"]);
        $block_name = sanitizeInput($_POST["block_name"]);
        $is_active = $_POST["is_active"];
        $unique_id = $_POST["unique_id"];
        $prefix = ''; // Define $prefix as needed

        // Generate unique_id if not updating
        // if (!$unique_id) {
        //     $unique_id = unique_id($prefix);
        // }

        // Check if the record already exists
        $select_sql = "SELECT COUNT(unique_id) AS count FROM $table WHERE block_name = ? AND is_delete = 0";
        if ($unique_id) {
            $select_sql .= " AND unique_id != ?";
        }

        $stmt = $mysqli->prepare($select_sql);
        if ($unique_id) {
            $stmt->bind_param("ss", $block_name, $unique_id);
        } else {
            $stmt->bind_param("s", $block_name);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();

        if ($data["count"]) {
            $msg = "already";
	    $status = true;
        } else {
            // Prepare data for insert or update
            $columns = [
                "district_name" => $district_name,
                "block_name" => $block_name,
                "is_active" => $is_active,
                "unique_id" => $unique_id
            ];

            if ($unique_id) {
                // Update
                $update_sql = "UPDATE $table SET district_name = ?, block_name = ?, is_active = ? WHERE unique_id = ?";
                $stmt = $mysqli->prepare($update_sql);
                $stmt->bind_param("ssss", $district_name, $block_name, $is_active, $unique_id);
            } else {
                // Insert
                $insert_sql = "INSERT INTO $table (district_name, block_name, is_active, unique_id) VALUES (?, ?, ?, ?)";
                $stmt = $mysqli->prepare($insert_sql);
$columns['unique_id'] = unique_id($prefix); 
                $stmt->bind_param("ssss", $district_name, $block_name, $is_active, unique_id($prefix));
            }

            if ($stmt->execute()) {
                $status = true;
                $msg = $unique_id ? "update" : "create";
                $data = [];
                $error = "";
                $sql = $stmt->sqlstate;
            } else {
                $status = false;
                $data = [];
                $error = $stmt->error;
                $sql = $stmt->sqlstate;
                $msg = "error";
            }

            $stmt->close();
        }

        $json_array = [
            "status" => $status,
           "data" => [
                "unique_id" => $unique_id ? $unique_id : $columns['unique_id'],
            ],
            "error" => $error,
            "msg" => $msg,
            "sql" => $sql
        ];

        echo json_encode($json_array);
        break;
        
        case 'datatable':
            // DataTable Variables
            $length = $_POST['length'];
            $start = $_POST['start'];
            $draw = $_POST['draw'];
            $limit = $length;
    
            if ($length == '-1') {
                $limit = "";
            }
    
            // Query Variables
            $json_array = "";
            $columns = [
                "@a:=@a+1 AS s_no",
                "(SELECT district_name FROM district_name WHERE district_name.unique_id = $table.district_name ) AS district_name",
                "block_name",
                "is_active",
                "unique_id"
            ];
    
            $columns_str = implode(", ", $columns);
            $table_details = "$table, (SELECT @a:= $start) AS a";
            $where = "is_delete = 0";
            $order_by = "";  // Define your order by if needed
    
            // SQL_CALC_FOUND_ROWS to get total records
            $sql = "SELECT SQL_CALC_FOUND_ROWS $columns_str FROM $table_details WHERE $where";
    
            if (!empty($order_by)) {
                $sql .= " ORDER BY $order_by";
            }
    
            if (!empty($limit)) {
                $sql .= " LIMIT $start, $limit";
            }
    
            $result = $mysqli->query($sql);
            $data = [];
    
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $row['is_active'] = is_active_show($row['is_active']);
    
                    $btn_update = btn_update($folder_name, $row['unique_id']);
                    $btn_delete = btn_delete($folder_name, $row['unique_id']);
    
                    if ($row['unique_id'] == "5f97fc3257f2525529") {
                        $btn_update = "";
                        $btn_delete = "";
                    }
    
                    $row['unique_id'] = $btn_update . $btn_delete;
                    $data[] = array_values($row);
                }
            }
    
            // Get total records
            $total_records_result = $mysqli->query("SELECT FOUND_ROWS() AS total");
            $total_records = $total_records_result->fetch_assoc()['total'];
    
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data,
                // "testing" => $sql  // Uncomment for debugging
            ];
    
            echo json_encode($json_array);
            break;
    
        

        case 'delete':

            $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }
           
            $unique_id = $_POST['unique_id'];
        
            $status = false;
            $data = [];
            $error = "";
            $msg = "";

            $is_delete = 1;
        
            // Prepare update query
            $sql = "UPDATE $table SET is_delete = ? WHERE unique_id = ?";
            $stmt = $mysqli->prepare($sql);
        
            // Bind parameters
            $stmt->bind_param("is", $is_delete,$unique_id);
        
            // Execute query
            if ($stmt->execute()) {
                $status = true;
                $msg = "success_delete";
            } else {
                $error = $mysqli->error;
                $msg = "error";
            }
        
            $stmt->close();
        
           
        
            // Prepare JSON response
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