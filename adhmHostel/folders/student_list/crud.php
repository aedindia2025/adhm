<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "std_reg_s";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action = $_POST['action'];

$hostel_type = "";
$is_active = "";
$unique_id = "";
$prefix = "";

$data = "";
$msg = "";
$error = "";
$status = "";
$test = ""; // For Developer Testing Purpose
$hostel_id = $_SESSION['hostel_id'];

switch ($action) {


    case 'datatable':
        // DataTable Variables
        $search = $_POST['search']['value'];
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;

        $data = [];


        if ($length == '-1') {
            $limit = "";
        }

        // Query Variables
        $json_array = "";
        $columns = [
            "@a:=@a+1 s_no",
            "std_name",
            "std_reg_no",
            "'' as std_biometric_no"
        ];
        $table_details = [
            $table . " , (SELECT @a:= " . $start . ") AS a ",
            $columns
        ];
        $where = "is_delete = 0 AND hostel_1 = '$hostel_id'";
        $order_by = "";

        $sql_function = "SQL_CALC_FOUND_ROWS";

        $result = $pdo->select($table_details, $where, $limit, $start, $order_by, $sql_function);
	// print_r($result);
        $total_records = total_records();

        if ($result->status) {

            $res_array = $result->data;

            foreach ($res_array as $key => $value) {

                // Extract numeric part from std_reg_no (e.g., remove alphabetic characters)
                $std_biometric_no = preg_replace('/[^0-9]/', '', $value['std_reg_no']);

                // Add this to the array
                $value['std_biometric_no'] = $std_biometric_no;

                $data[] = array_values($value);
            }

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data,
                "testing" => $result->sql
            ];
        } else {
            print_r($result);
        }

        echo json_encode($json_array);
        break;


    default:

        break;
}


?>