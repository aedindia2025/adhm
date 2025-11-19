<?php 

// Get folder Name From Currnent Url     
$folder_name        = explode("/",$_SERVER['PHP_SELF']);
$folder_name        = $folder_name[count($folder_name)-2];

$host = "localhost";
$username = "root";
$password = "4/rb5sO2s3TpL4gu";
$databasename = "adi_dravidar";

$mysqli = new mysqli($host, $username, $password, $databasename);

// Include DB file and Common Functions
include 'config/dbconfig.php';


// // Variables Declaration
$action             = $_POST['action'];
// print_r($action);
$hostel_name          = "";
$is_active          = "";
$unique_id          = "";
$prefix             = "";

$data               = "";
$msg                = "";
$error              = "";
$status             = "";
$test               = ""; // For Developer Testing Purpose

switch ($action) {

    case 'search':
        // DataTable Variables
        $app_number = $_POST['app_number'];
        $academic_year = $_POST['academic_year'];
        $date = $_POST['date'];
        $app_no = application_no($app_number, $academic_year)[0]['unique_id'];

    
        // Query Variables
        $table = "std_app_s6";
        $status = false;
        $data = [];
        $error = "";
        $msg = "";
    
        // Prepare SQL query and parameters
         $sql = "SELECT COUNT(s6.unique_id) AS count,
       s6.s1_unique_id
FROM std_app_s6 s6
INNER JOIN std_app_s s 
    ON s6.s1_unique_id = s.unique_id  
WHERE s6.is_delete = 0
AND s6.s1_unique_id = '".$app_no."'  
AND s6.dob = ?                    
AND s.academic_year = ?          
";

        $params = [$date, $academic_year];
        $types = "ss"; // Assuming both s1_unique_id and dob are strings
        // Prepare statement
        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            die('MySQL prepare error: ' . $mysqli->error);
        }
    
        // Bind parameters
        $stmt->bind_param($types, ...$params);
    
        // Execute query
        if ($stmt->execute()) {
        
            $result = $stmt->get_result();
            $select_data = $result->fetch_assoc();
    
            if ($select_data['count']) {
                $status = true;
                $std_app_no = $select_data['std_app_no'];
                $s1_unique_id = $select_data['s1_unique_id'];
                $data = $select_data;
    
                $json_array = [
                    "status" => $status,
                    "data" => $data,
                    "error" => $error,
                    "msg" => "found",
                    "std_app_no" => $std_app_no,
                    "s1_unique_id" => $s1_unique_id,
                    // "sql" => $sql // Optionally include SQL query for debugging
                ];
            } else {
                $msg = "Enter Valid Details";
                $json_array = [
                    "status" => $status,
                    "data" => "Enter Valid Details",
                    "error" => $error,
                    "msg" => $msg,
                    // "sql" => $sql // Optionally include SQL query for debugging
                ];
            }
        } else {
            $error = $stmt->error;
            $msg = "error";
            $json_array = [
                "status" => $status,
                "data" => $data,
                "error" => $error,
                "msg" => $msg,
                // "sql" => $sql // Optionally include SQL query for debugging
            ];
        }
    
        $stmt->close();
    
        // Close MySQLi connection
        $mysqli->close();
    
        echo json_encode($json_array);
        break;

case 'app_download':
        // DataTable Variables
        $uuid = $_POST['uuid'];



        $table = "std_app_s";
        $status = false;
        $data = [];
        $error = "";
        $msg = "";

        // Prepare SQL query and parameters
        $sql = "SELECT unique_id FROM $table WHERE is_delete = 0 AND uuid = ? order by id desc limit 1";
        $params = [$uuid];
        $types = "s"; // Assuming both s1_unique_id and dob are strings

        // Prepare statement
        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            die('MySQL prepare error: ' . $mysqli->error);
        }

        // Bind parameters
        $stmt->bind_param($types, ...$params);

        // Execute query
        if ($stmt->execute()) {

            $result = $stmt->get_result();
            $select_data = $result->fetch_assoc();

            if ($select_data['unique_id']) {
                $status = true;

                $s1_unique_id = $select_data['unique_id'];
                $data = $select_data;

                $json_array = [
                    "status" => $status,
                    "data" => $data,
                    "error" => $error,
                    "msg" => "found", 
                    "s1_unique_id" => $s1_unique_id,
                    // "sql" => $sql // Optionally include SQL query for debugging
                ];
            } else {
                $msg = "Enter Valid Details";
                $json_array = [
                    "status" => $status,
                    "data" => "Enter Valid Details",
                    "error" => $error,
                    "msg" => $msg,
                    // "sql" => $sql // Optionally include SQL query for debugging
                ];
            }
        } else {
            $error = $stmt->error;
            $msg = "error";
            $json_array = [
                "status" => $status,
                "data" => $data,
                "error" => $error,
                "msg" => $msg,
                // "sql" => $sql // Optionally include SQL query for debugging
            ];
        }

        $stmt->close();

        // Close MySQLi connection
        $mysqli->close();

        echo json_encode($json_array);
        break;


    case 'app_download_datatable':
        // DataTable Variables
        $search = $_POST['search']['value'];
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;
        $table = "std_app_s";
        $data = [];

        if ($length == '-1') {
            $limit = '';
        }
        $unique_id = $_POST['unique_id'];

        // Query Variables
        $json_array = '';
        $columns = [
            "'' as s_no",
            "entry_date",
            "std_app_no",
            "std_name",
            "unique_id",
        ];
        $table_details = [
            $table . " , (SELECT @a:= '" . $start . "') AS a ",
            $columns,
        ];
        $where = 'is_delete = 0 and unique_id = "' . $unique_id . '"';
        $order_by = '';


        $sql_function = 'SQL_CALC_FOUND_ROWS';

        $result = $pdo->select($table_details, $where, $limit, $start, $order_by, $sql_function);
        //    print_r($result);die();
        $total_records = total_records();

        if ($result->status) {
            $res_array = $result->data;
            $s_no = 1;

            foreach ($res_array as $key => $value) {
                $value['s_no'] = $s_no++;
                $unique_id = $value['unique_id'];
                $value['unique_id'] = '<a class="btn btn-action specl2-icon" href="javascript:print_app(\'' . $unique_id . '\')"><button type="button"><i class="fa fa-print" style="
    font-size: 20px;color: #128807;"></i></button></a>';

                $data[] = array_values($value);

            }

            $json_array = [
                'draw' => intval($draw),
                'recordsTotal' => intval($total_records),
                'recordsFiltered' => intval($total_records),
                'data' => $data,
                'testing' => $result->sql,
            ];
        } else {
            print_r($result);
        }

        echo json_encode($json_array);
        break;

    
        }
        ?>