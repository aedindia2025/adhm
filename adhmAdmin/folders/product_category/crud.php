<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "product_category";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';



// // Variables Declaration
$action = $_POST['action'];

$fund_name = "";
$is_active = "";
$unique_id = "";
$prefix = "";

$data = "";
$msg = "";
$error = "";
$status = "";
$test = ""; // For Developer Testing Purpose\
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
        $product_category = sanitizeInput($_POST["product_category"]);
        $description = sanitizeInput($_POST["description"]);
        $is_active = sanitizeInput($_POST["is_active"]);
        $unique_id = sanitizeInput($_POST["unique_id"]);

        $update_where = "";

        try {
            // Check if record already exists
            $select_query = "SELECT COUNT(unique_id) AS count FROM $table WHERE product_category = ? AND is_delete = 0";
            $params = [$product_category];

            // When updating, exclude the current record
            if ($unique_id) {
                $select_query .= " AND unique_id != ?";
                $params[] = $unique_id;
            }

            // Prepare and execute SELECT query
            $stmt = $mysqli->prepare($select_query);
            if ($stmt === false) {
                throw new Exception("Failed to prepare SELECT query: " . $mysqli->error);
            }

            // Bind parameters
            $types = str_repeat('s', count($params)); // Assuming all parameters are strings
            $stmt->bind_param($types, ...$params);

            // Execute statement
            $stmt->execute();

            // Bind result variables
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count > 0) {
                $msg = "already";
            } else {
                // No existing record found, proceed to update or insert
                if ($unique_id) {
                    // Update existing record
                    $update_query = "UPDATE $table SET product_category = ?, description = ?, is_active = ? WHERE unique_id = ?";
                    $stmt_update = $mysqli->prepare($update_query);
                    if ($stmt_update === false) {
                        throw new Exception("Failed to prepare UPDATE query: " . $mysqli->error);
                    }

                    // Bind parameters for update query
                    $stmt_update->bind_param("ssis", $product_category, $description, $is_active, $unique_id);

                    // Execute update statement
                    if ($stmt_update->execute()) {
                        $msg = "update";
                    } else {
                        throw new Exception("Update query execution failed: " . $stmt_update->error);
                    }

                    $stmt_update->close();
                } else {
                    // Insert new record
                    $insert_query = "INSERT INTO $table (product_category, description, is_active, unique_id) VALUES (?, ?, ?, ?)";
                    $stmt_insert = $mysqli->prepare($insert_query);
                    if ($stmt_insert === false) {
                        throw new Exception("Failed to prepare INSERT query: " . $mysqli->error);
                    }

                    // Generate a new unique_id
                    $new_unique_id = unique_id($prefix);

                    // Bind parameters for insert query
                    $stmt_insert->bind_param("ssis", $product_category, $description, $is_active, $new_unique_id);

                    // Execute insert statement
                    if ($stmt_insert->execute()) {
                        $msg = "create";
                    } else {
                        throw new Exception("Insert query execution failed: " . $stmt_insert->error);
                    }

                    $stmt_insert->close();
                }
            }

            $mysqli->close();

            // Set success status
            $status = true;

        } catch (Exception $e) {
            // Handle exceptions
            $error = $e->getMessage();
            $msg = "error";
        }

        // Construct JSON response
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            "sql" => $sql
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
                "product_category",
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
                $stmt->bind_result($s_no, $product_category, $description, $is_active, $unique_id);
        
                // Process results
                $sno = $start + 1;
                while ($stmt->fetch()) {
                    $row = [
                        's_no' => $sno++,
                        'product_category' => disname($product_category),
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
                // Validate input
                $token = $_POST['csrf_token'];
        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }
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