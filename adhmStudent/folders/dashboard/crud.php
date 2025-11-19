<?php 

// Get folder Name From Currnent Url 
$folder_name        = explode("/",$_SERVER['PHP_SELF']);
$folder_name        = $folder_name[count($folder_name)-2];

// // Database Country Table Name
// $table             = "leave_application";
$table_std_app  = "std_app_p1";
$table          = "holiday_creation";
$table_sub      = "complaint_creation_doc_upload";
$table_stage_1  = "stage_1";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action             = $_POST['action'];

$fund_name          = "";
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

        $feedback_name        = $_POST["feedback_name"];
        $rating         =       $_POST["rating3"];
        $description           = $_POST["description"];
        $student_id            = $_POST["student_id"];
        $district_id            = $_POST['district_id'];
        $taluk_id               = $_POST['taluk_id'];
        $hostel_id             = $_POST['hostel_id'];
        
        $date               = $_POST['date'];
        // $is_active          = $_POST["is_active"];
        $unique_id          = $_POST["unique_id"];
        

        $update_where       = "";

        $columns            = [
            "feedback_name"           => $feedback_name,
            "rating"             =>  $rating,
            "description"           => $description,
            "student_id"           => $student_id,
            "district_id"         => $district_id,

            "taluk_id"            => $taluk_id,
            "hostel_id"           => $hostel_id,
            
            // "is_active"           => $is_active,
            "unique_id"           => unique_id($prefix)
        ];

            // check already Exist Or not
        $table_details1      = [
            $table,
            [
                "COUNT(unique_id) AS count"
            ]
        ];

        // $where1 = "from_year = '".$from_year."' AND to_year ='".$to_year."' AND amc_year ='".$amc_year."' ";
        
        $result = $pdo->select($table_details1,$where1);

        $res_array      = $result->data;



        // print_r($res_array);die();
       
      
        if($unique_id) {

            unset($columns['unique_id']);

            $update_where   = [
                "unique_id"     => $unique_id
            ];

            $action_obj     = $pdo->update($table,$columns,$update_where);
            

    
        } else {
            // if($res_array[0]['count'] == 0){
                $action_obj     = $pdo->insert($table,$columns);    
            
                // $msg = "already";
            }

        

        if ($action_obj->status) {
            $status     = $action_obj->status;
            $data       = $action_obj->data;
            $error      = "";
            $sql        = $action_obj->sql;

            if ($unique_id) {
                $msg        = "update";
            } else {
                $msg        = "create";
            }
        } else {
            $status     = $action_obj->status;
            $data       = $action_obj->data;
            $error      = $action_obj->error;
            $sql        = $action_obj->sql;
            $msg        = "error";
        }
    

    $json_array   = [
        "status"    => $status,
        "data"      => $data,
        "error"     => $error,
        "msg"       => $msg,
        "sql"       => $sql
    ];

    echo json_encode($json_array);

    break;

    
        case 'datatable':
            // DataTable Variables
            $search     = $_POST['search']['value'];
            $length     = $_POST['length'];
            $start      = $_POST['start'];
            $draw       = $_POST['draw'];
            $limit      = $length;
    
            $data       = [];

            $feedback_name        = $_POST["feedback_name"];
            $rating         = $_POST["rating"];
            $description           = $_POST["description"];
            $student_id            = $_POST["student_id"];
            $date               = $_POST['date']; 
            $is_active          = $_POST["is_active"];
            $unique_id          = $_POST["unique_id"];
    
            if($length == '-1') {
                $limit  = "";
            }
    
            // Query Variables
            $json_array     = "";
            $columns        = [
                "@a:=@a+1 s_no",
                "current_date",
                "(select feedback_type from feedback_type where unique_id = $table.feedback_name)as feedback_name",
                "rating",
                "description",
                // "student_id",
                // "hostel_name",
                
                

                "is_active",
                "unique_id"
            ];
            $table_details  = [
                $table." , (SELECT @a:= ".$start.") AS a ",
                $columns
            ];
            $where          = "is_delete = 0 ";
            $order_by       = "";
    
            if ($_POST['search']['value']) {
               $where .= " AND user_type LIKE '".mysql_like($_POST['search']['value'])."' ";
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
    
            if ($result->status) {
    
                $res_array      = $result->data;
    
                foreach ($res_array as $key => $value) {
                    // $value['product_category'] = disname($value['product_category']);
                    // $value['description'] = disname($value['description']);

                    // $from_year = $value['from_year'];

                    // $to_year   = $value['$to_year'];
                    $value['is_active'] = is_active_show($value['is_active']);
                    
    
                    $btn_update         = btn_update($folder_name,$value['unique_id']);
                    $btn_delete         = btn_delete($folder_name,$value['unique_id']);
    
                    // if ( $value['unique_id'] == "5f97fc3257f2525529") {
                    //     $btn_update         = "";
                    //     $btn_delete         = "";
                    // } 
    
                    $value['unique_id'] = $btn_update.$btn_delete;
                    $data[]             = array_values($value);
                }
                
                $json_array = [
                    "draw"              => intval($draw),
                    "recordsTotal"      => intval($total_records),
                    "recordsFiltered"   => intval($total_records),
                    "data"              => $data,
                    "testing"           => $result->sql
                ];
            } else {
                print_r($result);
            }
            
            echo json_encode($json_array);
            break;
         
          
    
    case 'delete':
        
        $unique_id      = $_POST['unique_id'];

        $columns        = [
            "is_delete"   => 1
        ];

        $update_where   = [
            "unique_id"     => $unique_id
        ];

        $action_obj     = $pdo->update($table,$columns,$update_where);

        if ($action_obj->status) {
            $status     = $action_obj->status;
            $data       = $action_obj->data;
            $error      = "";
            $sql        = $action_obj->sql;
            $msg        = "success_delete";

        } else {
            $status     = $action_obj->status;
            $data       = $action_obj->data;
            $error      = $action_obj->error;
            $sql        = $action_obj->sql;
            $msg        = "error";
        }

        $json_array   = [
            "status"    => $status,
            "data"      => $data,
            "error"     => $error,
            "msg"       => $msg,
            "sql"       => $sql
        ];

        echo json_encode($json_array);
        break;


        case 'holiday_details':

            $table = "holiday_creation";
    
            $json_array     = "";
            $today  =  date('Y-m-d');
            $columns        = [           
                "date",
                "holiday"
               ];
            $table_details  = [
                $table,
                $columns
            ];
            $where        = "is_delete = 0";
            $order_by       = "date ASC";
    
            $sql_function   = "SQL_CALC_FOUND_ROWS";
    
    
            $result         = $pdo->select($table_details,$where,$limit,$start,$order_by,$sql_function);

            if ($result->status) {
    
                $res_array      = $result->data;
        
                foreach ($res_array as $key => $value) {

                    $date = $value['date'];
                    $holiday = $value['holiday'];
                }    
            $json_array = [
                    "holiday_details"       	=> $res_array,
                    "date"                      => $date,
                    "holiday"                   => $holiday       
                ];
            
             echo json_encode($json_array);
            } 
        break;


        case 'notification_details':

            $table_1 = "notification";
    
            $json_array     = "";
            // $today  =  date('Y-m-d');

            $columns_1        = [
                "date",
                "title",
                "content",
                // "actions"
               ];
            $table_details_1  = [
                $table_1,
                $columns_1
            ];

            $where        = "is_delete = 0";

            if($actions){
                 $where .= "AND actions =65589f69ce65d32654";
                
            }

            
            $order_by       = "date ASC";
    
            $sql_function   = "SQL_CALC_FOUND_ROWS";

            
    
    
            $result         = $pdo->select($table_details_1,$where,$limit,$start,$order_by,$sql_function);

            // print_r($result);die();

    
            $res_array      = $result->data;
                   
            $json_array = [

                    "notification_details" => $res_array        
                ];
            
             echo json_encode($json_array);
             
        break;

        
        case 'applied_leave_details':

            $table_leave ="leave_application";

           $user_name= $_SESSION["user_name"];
    
            $json_array     = "";
            // $today  =  date('Y-m-d');

            $columns_leave       = [
               "student_id",
                "from_date",
                "no_of_days",
                "approval_status"

               ];
            $table_details_leave  = [
                $table_leave,
                $columns_leave,
            ];

            $where        = "is_delete = 0  AND student_id = '".$user_name."' ";

    
            $sql_function   = "SQL_CALC_FOUND_ROWS";

            
    
    
            $result         = $pdo->select($table_details_leave, $where);
            // print_r($result);
            $total_records  = total_records();

            if ($result->status) {
    
                $res_array      = $result->data;
        
                foreach ($res_array as $key => $value) {

                    $from_date = $value['from_date'];
                    $no_of_days = $value['no_of_days'];
                    $status_color = '';

                if ($value['approval_status'] == 1) {
                    $value['approval_status'] = 'Pending';
                    $status_color = 'blue';
                }
                if ($value['approval_status'] == 2) {
                    $value['approval_status'] = 'Approved';
                    $status_color = 'green';
                }
                if ($value['approval_status'] == 3) {
                    $value['approval_status'] = 'Rejected';
                    $status_color = 'red';
                }

                $status_text = $value['approval_status'];
                // switch ($value['approval_status']) {
                //     case 1:
                //         $status_text = 'Approved';
                //         $status_color = 'green';
                //         break;
                //     case 2:
                //         $status_text = 'Rejected';
                //         $status_color = 'red';
                //         break;
                //     case 3:
                //         $status_text = 'Pending';
                //         $status_color = 'blue';
                //         break;
                // }
            
                // Assigning color to status
                // $value['approval_status'] = '<button type="button"  class="btn" style="margin-left:80px;" id="approval_status">Pending</button>';
                // $value['approval_status'] = '<span style="background: ' . $status_color . ';">' . $status_text . '</span>';
    
            $res_array      = $result->data;
                }     
            $json_array = [
                    "applied_leave_details" => $res_array,
                    "from_date"             => $from_date,
                    "no_of_days"            => $no_of_days,   
                    "approval_status"    =>     $value['approval_status']
                ];
            
             echo json_encode($json_array);
            }

             
        break;



        case 'get_leave':
            // DataTable Variables
            $search     = $_POST['search']['value'];
            $length     = $_POST['length'];
            $start      = $_POST['start'];
            $draw       = $_POST['draw'];
            $limit      = $length;
    
            $data       = [];

           
    
            if($length == '-1') {
                $limit  = "";
            }
    
            // Query Variables
            $json_array     = "";
            $columns        = [
                "@a:=@a+1 s_no",
                "current_date",
                "(select feedback_type from feedback_type where unique_id = $table.feedback_name)as feedback_name",
                "rating",
                "description",
                // "student_id",
                // "hostel_name",
                
                

                "is_active",
                "unique_id"
            ];
            $table_details  = [
                $table." , (SELECT @a:= ".$start.") AS a ",
                $columns
            ];
            $where          = "is_delete = 0 ";
            $order_by       = "";
    
            if ($_POST['search']['value']) {
               $where .= " AND user_type LIKE '".mysql_like($_POST['search']['value'])."' ";
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
    
            if ($result->status) {
    
                $res_array      = $result->data;
    
                foreach ($res_array as $key => $value) {
                    // $value['product_category'] = disname($value['product_category']);
                    // $value['description'] = disname($value['description']);

                    // $from_year = $value['from_year'];

                    // $to_year   = $value['$to_year'];
                    $value['is_active'] = is_active_show($value['is_active']);
                    
    
                    $btn_update         = btn_update($folder_name,$value['unique_id']);
                    $btn_delete         = btn_delete($folder_name,$value['unique_id']);
    
                    // if ( $value['unique_id'] == "5f97fc3257f2525529") {
                    //     $btn_update         = "";
                    //     $btn_delete         = "";
                    // } 
    
                    $value['unique_id'] = $btn_update.$btn_delete;
                    $data[]             = array_values($value);
                }
                
                $json_array = [
                    "draw"              => intval($draw),
                    "recordsTotal"      => intval($total_records),
                    "recordsFiltered"   => intval($total_records),
                    "data"              => $data,
                    "testing"           => $result->sql
                ];
            } else {
                print_r($result);
            }
            
            echo json_encode($json_array);
            break;
         

    default:
        
        break;
}
    
        //
?>
