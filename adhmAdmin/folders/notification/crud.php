<?php 

// Get folder Name From Currnent Url 
$folder_name        = explode("/",$_SERVER['PHP_SELF']);
$folder_name        = $folder_name[count($folder_name)-2];

// // Database Country Table Name
$table             = "notification";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';
include 'function.php';

// // Variables Declaration
$action             = $_POST['action'];

$district_name      = "";
$is_active          = "";
$unique_id          = "";
$prefix             = "";
$user_actions       = "";
$data               = "";
$msg                = "";
$error              = "";
$status             = "";
$test               = ""; // For Developer Testing Purpose

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

        // Retrieve POST data
        $date = sanitizeInput($_POST["date"]);
        $expire_date = sanitizeInput($_POST["expire_date"]);
        $title = sanitizeInput($_POST["title"]);
        $content = sanitizeInput($_POST["content"]);
        $user_actions = sanitizeInput($_POST["user_actions"]);
        $is_active = sanitizeInput($_POST["is_active"]);
        $unique_id = sanitizeInput($_POST["unique_id"]);
    
        // Prepare update columns
        $columns = [
            "date" => $date,
            "expire_date" => $expire_date,
            "title" => $title,
            "content" => $content,
            "actions" => $user_actions,
            "is_active" => $is_active,
        ];
    
        // Check if unique_id exists for update scenario
        if ($unique_id) {
            $update_where = "unique_id = ?";
            $query = "UPDATE $table SET date=?, expire_date=?, title=?, content=?, actions=?, is_active=? WHERE unique_id=?";
    
            // Bind parameters
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("sssssss", $date, $expire_date, $title, $content, $user_actions, $is_active, $unique_id);
        } else {
            // Insert scenario
            $query = "INSERT INTO $table (date, expire_date, title, content, actions, is_active, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
    
            // Bind parameters
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("sssssss", $date, $expire_date, $title, $content, $user_actions, $is_active, unique_id($prefix));
        }
    
        // Execute the statement
        // $stmt->execute();
    
        if ($stmt->execute()) {
            if ($unique_id) {
                $msg = "update";
            } else {
                $msg = "create";
            }
            $status = true;
            $data = ["affected_rows" => $stmt->affected_rows];
            $error = "";
        } else {
            $status = false;
            $data = [];
            $error = "Execute statement failed: " . $stmt->error;
            $msg = "error";
        }
    
        // Close statement
        $stmt->close();
    
        // JSON response
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
        
        // Query Variables
        $json_array     = "";
        $columns        = [
            "@a:=@a+1 s_no",
            "date",
            "expire_date",
            "title",
            "content",
            "is_active",
            "unique_id"
        ];
        $table_details = "$table , (SELECT @a:= ?) AS a";
        $where = "is_delete = 0";
        $bind_params = "i"; // Types of parameters (i for integer)
        $bind_values = [$start];


        $sql_function = "SQL_CALC_FOUND_ROWS";
        $order_by = ""; // Set the order by clause if necessary

        // SQL query for data fetching
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_details WHERE $where";
        if ($limit !== "") {
            $sql .= " LIMIT ?, ?";
            $bind_params .= "ii"; // Add types for limit parameters
            $bind_values[] = $start;
            $bind_values[] = $limit;
        }

        $stmt = $mysqli->prepare($sql);

        if ($stmt === false) {
            // Handle prepare error
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => $mysqli->error
            ];
            echo json_encode($json_array);
            break;
        }

        // Bind parameters dynamically
        $bind_params_arr = array_merge([$bind_params], ...array_map(function ($v) {
            return [$v];
        }, $bind_values));
        call_user_func_array([$stmt, 'bind_param'], $bind_params_arr);

        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch total records
        $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
        $total_records = $total_records_result->fetch_assoc()['total'];

        if ($result) {

            $res_array = $result->fetch_all(MYSQLI_ASSOC);

            foreach ($res_array as $key => $value) {
                // $value['date'] = district_list($value['date']);
                // $value['date'] = disname($value['date']);
                // $value['date']     = disdate($value['date'])[0]['date'];
                // $value['expire_date']     = disdate($value['expire_date'])[0]['expire_date'];
                //  $value['title']     = ($value['title']) [0]['$title'];
                // $value['school_name']     = disname($value['school_name']);

                $value['is_active']     = is_active_show($value['is_active']);

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

            $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

            $unique_id = $_POST['unique_id'];
    
            // Prepare the SQL statement
            $query = "UPDATE $table SET is_delete = 1 WHERE unique_id = ?";
            $stmt = $mysqli->prepare($query);
            
            // Bind the parameters
            $stmt->bind_param("s", $unique_id);
            
            // Execute the statement
            $stmt->execute();
    
            // Check the result
            if ($stmt->affected_rows > 0) {
                $status = true;
                $data = ["affected_rows" => $stmt->affected_rows];
                $error = "";
                $msg = "success_delete";
            } else {
                $status = false;
                $data = [];
                $error = "Execute statement failed: " . $stmt->error;
                $msg = "error";
            }
    
            // Close the statement
            $stmt->close();
    
            // JSON response
            $json_array = [
                "status" => $status,
                "data" => $data,
                "error" => $error,
                "msg" => $msg
            ];
    
            echo json_encode($json_array);
            break;

    default:
        
        break;
}
    
        
?>
