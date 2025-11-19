<?php 

// Get folder Name From Currnent Url 
$folder_name        = explode("/",$_SERVER['PHP_SELF']);
$folder_name        = $folder_name[count($folder_name)-2];

// // Database Country Table Name
$table             = "academic_year";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action             = $_POST['action'];

$user_type          = "";
$is_active          = "1";
$unique_id          = "";
$prefix             = "";

$data               = "";
$msg                = "";
$error              = "";
$status             = "";
$test               = ""; // For Developer Testing Purpose

switch ($action) {
   
    case 'datatable':
    //     // DataTable Variables
        $search     = $_POST['search']['value'];
        $length     = $_POST['length'];
        $start      = $_POST['start'];
        $draw       = $_POST['draw'];
        $limit      = $length;

        $data       = [];
        

    //     if($length == '-1') {
    //         $limit  = "";
    //     }

    //     // Query Variables
        $json_array     = "";
        $columns        = [
            // "@a:=@a+1 s_no",
            // "entry_date",
            // "student_name",
            // "hostel_name_id",
            // "discription"
            // "unique_id"
        ];
    //     // ." , (SELECT @a:= ".$start.") AS a "
        $table_details  = [
            $table,
            $columns
        ];
    //     // $where          = "is_delete = 0";
        
    //     $order_column   = $_POST["order"][0]["column"];
    //     $order_dir      = $_POST["order"][0]["dir"];

    //     // Datatable Ordering 
    //     // $order_by       = datatable_sorting($order_column,$order_dir,$columns);

    //     // if ($_POST['search']['value']) {
    //     //   $where .= " AND student_name LIKE '".mysql_like($_POST['search']['value'])."' ";
    //     //     $where .= " and student_no LIKE '".mysql_like($_POST['search']['value'])."' ";
    //     // }
        
        
    //     // Datatable Searching
    //     // $search         = datatable_searching($search,$columns);

    //     // if ($search) {
    //     //     if ($where) {
    //     //         $where = " AND ";
    //     //     }

    //     //     $where = $search;
    //     // }
        
    //     // $where .= "order by student_id asc";

    //     $sql_function   = "SQL_CALC_FOUND_ROWS";
    //     // $limit,$start,$order_by,$sql_function
        $result         = $pdo->select($table_details);
        // print_r($result);
        $total_records  = total_records();

        if ($result->status) {

            $res_array      = $result->data;

            foreach ($res_array as $key => $value) {
                
                //  $value['student_name'] = student_name($value['student_name'])[0]['student_name'];
                // $value['hostel_name']   = hostel_name($value['hostel_name'])[0]['hostel_name'];
                // $value['discription']     = discription($value['discription'])[0]['discription'];
                

                // $value['is_active']     = is_active_show($value['is_active']);

                // $btn_update             = btn_update($folder_name,$value['unique_id']);
                // $btn_delete         = btn_delete($folder_name,$value['unique_id']);

                // if ($value['unique_id'] == "5f97fc3257f2525529") {
                //     $btn_update         = "";
                //     $btn_delete         = "";
                // } 
                // $app_no =$value['application_no'];
                // $btn_prints             = btn_prints($folder_name,$value['application_no']);

                // $value['unique_id'] = '<i onclick="new_external_window_prints("'.$app_no.'");" class="fa fa-print" ></i>';
                // $value['unique_id'] =$btn_prints;
                
                // '<button type="button" class="btn btn-danger   m-t-15 btn-rounded waves-effect waves-light float-right ml-2" ><i class="fa fa-print" onclick="new_external_window_print(event,"folders/apply_application_form/print.php","print");"></i></button>';
                // $btn_update.$btn_delete;
                $data[]             = array_values($value);
            }
            
            $json_array = [
                "draw"              => intval($draw),
                "recordsTotal"      => intval($total_records),
                "recordsFiltered"   => intval($total_records),
                "data"              => $data,
                "testing"           => $result->sql
            ];
    //     } else {
    //         print_r($result);
    //     }
        
        echo json_encode($json_array);
        break;
        }
    default:
        
        break;
}
    