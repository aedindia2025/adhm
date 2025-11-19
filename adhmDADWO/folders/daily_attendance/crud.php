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
        $search     = $_POST['search']['value'];
        $length     = $_POST['length'];
        $start      = $_POST['start'];
        $draw       = $_POST['draw'];
        $limit      = $length;

        $data       = [];
         
        $district_name = $_POST['district_name'];
        $taluk_name = $_POST['taluk_name'];
        $hostel_name = $_POST['hostel_name'];
                
        

        if($length == '-1') {
            $limit  = "";
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


        // Query Variables
        $json_array     = "";
        $columns        = [
             "@a:=@a+1 s_no",
                "userId",
                "userName",
                "punchIn",
                "punchOut"        ];
        $table_details  = [
            $table." , (SELECT @a:= ".$start.") AS a ",
            $columns
        ];
        $where          .= "district_name = '".$district_name."'";
        $order_by       = "";

        if ($_POST['search']['value']) {
           $where .= " AND carrier_title LIKE '".mysql_like($_POST['search']['value'])."' ";
        }

                 if($taluk_name != ''){
            $where .= " AND taluk_name ='".$taluk_name."'";
        }
        if($hostel_name != ''){
            $where .= " AND hostel_unique_id ='".$hostel_name."'";
        }



        
        // Datatable Searching
        $search         = datatable_searching($search,$columns);

        if ($search) {
            if ($where) {
                $where .= " AND ";
            }

            $where .= $search;
        }

        $sql_function   = "SQL_CALC_FOUND_ROWS";

        $result         = $pdo->select($table_details,$where,$limit,$start,$order_by,$sql_function);
        $total_records  = total_records();
        $sno = 1;

        if ($result->status) {

            $res_array      = $result->data;

            foreach ($res_array as $key => $value) {
                
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

case 'get_hostel_by_taluk_name':
        
            $taluk_name = $_POST['taluk_name'];


            $hostel_name_options = hostel_name('',$taluk_name);

            $hostel_name_options = select_option_host($hostel_name_options,"Select Hostel");

            echo $hostel_name_options;

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