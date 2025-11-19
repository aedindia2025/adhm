<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// Database Country Table Name
$table = "dayattreport";

// Include DB file and Common Functions
include '../../config/dbconfig.php';

// Variables Declaration
$action = $_POST['action'];

$json_array = "";
$sql = "";

$feedback_type = "";
//$is_active = "";
$unique_id = "";
$prefix = "";

$data = "";
$msg = "";
$error = "";
$status = "";
$test = ""; // For Developer Testing Purpose

function validateCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
switch ($action) {

    

        case 'datatable':
            // DataTable Variables
            $length = isset($_POST['length']) ? $_POST['length'] : 10;
            $start = isset($_POST['start']) ? $_POST['start'] : 0;
            $draw = isset($_POST['draw']) ? $_POST['draw'] : 1;
            $limit = $length;
        
            if ($length == '-1') {
                $limit = "";
            }

if($_POST['from_date'] && $_POST['to_date']){
$where = 'currentDate >= "'.$_POST['from_date'].'" and currentDate <= "'.$_POST['to_date'].'" and ';
}else{
    if($_POST['from_date']){
        $where = 'currentDate = "'.$_POST['from_date'].'" and ';
    }
    if($_POST['to_date']){
        $where = 'currentDate = "'.$_POST['to_date'].'" and ';
    }
}
        
            // Initialize response data
            $data = [];
            $json_array = [];
            
            // SQL Query Variables
            $columns = [
                "@a:=@a+1 s_no",
                "userId",
                "userName",
                "punchIn",
                "punchOut"
            ];
            
            $sql_columns = implode(", ", $columns);
            $table_with_counter = $table . ", (SELECT @a:=?) AS a";
            $where .= "hostel_unique_id = ?";
            $is_delete = "0";
            // Initialize total records variable
            $total_records = total_records();
            
            // Prepare SQL query
            $sql_query = "SELECT SQL_CALC_FOUND_ROWS {$sql_columns} FROM {$table_with_counter} WHERE {$where}";
            
            if ($limit !== "") {
                $sql_query .= " LIMIT ?, ?";
            }
        
            // Execute query with parameterized statements
            $stmt = $mysqli->prepare($sql_query);
            if ($stmt) {
                // Bind parameters
                if ($limit !== "") {
                    $stmt->bind_param("isii", $start,$_SESSION['hostel_id'], $start, $limit);
                } else {
                    $stmt->bind_param("is", $start,$_SESSION['hostel_id']);
                }
        
                // Execute the query
                $stmt->execute();
                
                // Fetch the result
                $result = $stmt->get_result();
              
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                       
                        $data[] = array_values($row);
                    }
        
                    // Fetch the total filtered records
                    $stmt_filtered = $mysqli->prepare("SELECT FOUND_ROWS()");
                    if ($stmt_filtered) {
                        $stmt_filtered->execute();
                        $stmt_filtered->bind_result($total_filtered);
                        $stmt_filtered->fetch();
                        $stmt_filtered->close();
                    } else {
                        $total_filtered = $total_records;
                    }
        
                    // Prepare JSON response
                    $json_array = [
                        "draw" => intval($draw),
                        "recordsTotal" => intval($total_filtered),
                        "recordsFiltered" => intval($total_filtered),
                        "data" => $data,
                    ];
                }
                
                $stmt->close();
            }
        
            // Output JSON response
            echo json_encode($json_array);
        
            break;
        

    case 'delete':
        
        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }
        
        $unique_id = $_POST['unique_id'];

        // Prepare SQL statement
        $sql = "UPDATE $table SET is_delete = ? WHERE unique_id = ?";

        // Prepare statement
        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            die('MySQL prepare error: ' . $mysqli->error);
        }

        // Bind parameters
        $is_delete = 1; // Assuming is_delete is an integer
        $stmt->bind_param('is', $is_delete, $unique_id);

        // Execute statement
        $stmt->execute();

        // Check for success
        if ($stmt->affected_rows > 0) {
            $status = true;
            $data = "Successfully deleted";
            $error = "";
            $msg = "success_delete";
        } else {
            $status = false;
            $data = "";
            $error = "Delete operation failed";
            $msg = "error";
        }

        // Close statement
        $stmt->close();

        // Prepare JSON response
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg
        ];

        echo json_encode($json_array);
        break;

    case 'get_std_name':

        $student_id = $_POST['student_id'];


        // Query Variables
        $json_array = [];
        $columns = [

            "std_name"

        ];
        $table_details = [
            "std_reg_s2",
            $columns
        ];
        $where = "is_delete = 0 and s1_unique_id = '" . $student_id . "'";




        $sql_function = "SQL_CALC_FOUND_ROWS";

        $result = $pdo->select($table_details, $where);
        // print_r($result);
        $total_records = total_records();

        if ($result->status) {

            $res_array = $result->data;

            foreach ($res_array as $key => $value) {


                $student_name = $value['std_name'];

                // $data[]             = array_values($value);
            }

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data,
                "student_name" => $student_name,
                // "testing"           => $result->sql
            ];
        } else {
            // print_r($result);
        }

        echo json_encode($json_array);
        break;

    default:

        break;
}

?>