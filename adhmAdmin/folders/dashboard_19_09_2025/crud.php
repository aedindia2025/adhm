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

$unique_id    = $_GET["unique_id"];


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
        $rating               = $_POST["rating3"];
        $description          = $_POST["description"];
        $student_id           = $_POST["student_id"];
        $district_id          = $_POST['district_id'];
        $taluk_id             = $_POST['taluk_id'];
        $hostel_id            = $_POST['hostel_id'];
        
        $date                 = $_POST['date'];
        // $is_active          = $_POST["is_active"];
        $unique_id            = $_POST["unique_id"];

        

        $update_where         = "";

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

 case 'get_applied_count':

        $district_name = $_POST['district_name'];
        $taluk_name = $_POST['taluk_name'];
        $hostel_name = $_POST['hostel_name'];

        $where = "";
        $batch_where = "";

        if ($district_name != '') {
            $where = " AND hostel_district_1 ='" . $district_name . "'";
            $batch_where = " AND hostel_district ='" . $district_name . "'";
        }
        if ($taluk_name != '') {
            $where = " AND hostel_taluk_1 ='" . $taluk_name . "'";
            $batch_where = " AND hostel_taluk ='" . $taluk_name . "'";
        }
        if ($hostel_name != '') {
            $where = " AND hostel_1 ='" . $hostel_name . "'";
            $batch_where = " AND hostel_name ='" . $hostel_name . "'";
        }

        $json_array = "";
        $columns = [
            "(select count(id) from std_app_s where is_delete= 0 " . $where . " ) as applied_cnt",
            // "(select count(id) from batch_creation where is_delete = 0 " . $batch_where . " ) as accp_cnt",
            // "(select count(id) from std_reg_s where is_delete = 0  " . $where . ") as approved_cnt",
            // "(select count(id) from batch_creation where is_delete = 0 and status = '2' " . $batch_where . ") as rejected_cnt",
        ];
        $table_details = [
            "std_app_s",
            $columns
        ];

        $result = $pdo->select($table_details);
        // print_r($result);

        $res_array = $result->data;

        $applied_cnt = 0;
        // $accp_cnt = 0;
        // $approved_cnt = 0;
        // $rejected_cnt = 0;

        foreach ($res_array as $value) {

            $applied_cnt = $value['applied_cnt'];

            // $accp_cnt = $value['accp_cnt'];
            // $approved_cnt = $value['approved_cnt'];
            // $rejected_cnt = $value['rejected_cnt'];

        }

        $json_array = [
            "applied_cnt" => $applied_cnt,
            // "accp_cnt" => $accp_cnt,
            // "approved_cnt" => $approved_cnt,
            // "rejected_cnt" => $rejected_cnt,

        ];

        echo json_encode($json_array);

        break;


    case 'get_accept_count':

        $district_name = $_POST['district_name'];
        $taluk_name = $_POST['taluk_name'];
        $hostel_name = $_POST['hostel_name'];

        $where = "";
        $batch_where = "";

        if ($district_name != '') {
            $where = " AND hostel_district_1 ='" . $district_name . "'";
            $batch_where = " AND hostel_district ='" . $district_name . "'";
        }
        if ($taluk_name != '') {
            $where = " AND hostel_taluk_1 ='" . $taluk_name . "'";
            $batch_where = " AND hostel_taluk ='" . $taluk_name . "'";
        }
        if ($hostel_name != '') {
            $where = " AND hostel_1 ='" . $hostel_name . "'";
            $batch_where = " AND hostel_name ='" . $hostel_name . "'";
        }

        $json_array = "";
        $columns = [
            // "(select count(id) from std_app_s where is_delete= 0 " . $where . " ) as applied_cnt",
            "(select count(id) from batch_creation where is_delete = 0 " . $batch_where . " ) as accp_cnt",
            // "(select count(id) from std_reg_s where is_delete = 0  " . $where . ") as approved_cnt",
            // "(select count(id) from batch_creation where is_delete = 0 and status = '2' " . $batch_where . ") as rejected_cnt",
        ];
        $table_details = [
            "std_app_s",
            $columns
        ];

        $result = $pdo->select($table_details);

        $res_array = $result->data;

        // $applied_cnt = 0;
        $accp_cnt = 0;
        // $approved_cnt = 0;
        // $rejected_cnt = 0;

        foreach ($res_array as $value) {

            // $applied_cnt = $value['applied_cnt'];
            $accp_cnt = $value['accp_cnt'];
            // $approved_cnt = $value['approved_cnt'];
            // $rejected_cnt = $value['rejected_cnt'];

        }

        $json_array = [
            // "applied_cnt" => $applied_cnt,
            "accp_cnt" => $accp_cnt,
            // "approved_cnt" => $approved_cnt,
            // "rejected_cnt" => $rejected_cnt,

        ];

        echo json_encode($json_array);

        break;


    case 'get_approved_count':

        $district_name = $_POST['district_name'];
        $taluk_name = $_POST['taluk_name'];
        $hostel_name = $_POST['hostel_name'];

        $where = "";
        $batch_where = "";

        if ($district_name != '') {
            $where = " AND hostel_district_1 ='" . $district_name . "'";
            $batch_where = " AND hostel_district ='" . $district_name . "'";
        }
        if ($taluk_name != '') {
            $where = " AND hostel_taluk_1 ='" . $taluk_name . "'";
            $batch_where = " AND hostel_taluk ='" . $taluk_name . "'";
        }
        if ($hostel_name != '') {
            $where = " AND hostel_1 ='" . $hostel_name . "'";
            $batch_where = " AND hostel_name ='" . $hostel_name . "'";
        }

        $json_array = "";
        $columns = [
            // "(select count(id) from std_app_s where is_delete= 0 " . $where . " ) as applied_cnt",
            // "(select count(id) from batch_creation where is_delete = 0 " . $batch_where . " ) as accp_cnt",
            "(select count(id) from std_reg_s where is_delete = 0  " . $where . ") as approved_cnt",
            // "(select count(id) from batch_creation where is_delete = 0 and status = '2' " . $batch_where . ") as rejected_cnt",
        ];
        $table_details = [
            "std_app_s",
            $columns
        ];

        $result = $pdo->select($table_details);

        $res_array = $result->data;

        // $applied_cnt = 0;
        // $accp_cnt = 0;
        $approved_cnt = 0;
        // $rejected_cnt = 0;

        foreach ($res_array as $value) {

            // $applied_cnt = $value['applied_cnt'];
            // $accp_cnt = $value['accp_cnt'];
            $approved_cnt = $value['approved_cnt'];
            // $rejected_cnt = $value['rejected_cnt'];

        }

        $json_array = [
            // "applied_cnt" => $applied_cnt,
            // "accp_cnt" => $accp_cnt,
            "approved_cnt" => $approved_cnt,
            // "rejected_cnt" => $rejected_cnt,

        ];

        echo json_encode($json_array);

        break;

    case 'get_rejected_count':

        $district_name = $_POST['district_name'];
        $taluk_name = $_POST['taluk_name'];
        $hostel_name = $_POST['hostel_name'];

        $where = "";
        $batch_where = "";

        if ($district_name != '') {
            $where = " AND hostel_district_1 ='" . $district_name . "'";
            $batch_where = " AND hostel_district ='" . $district_name . "'";
        }
        if ($taluk_name != '') {
            $where = " AND hostel_taluk_1 ='" . $taluk_name . "'";
            $batch_where = " AND hostel_taluk ='" . $taluk_name . "'";
        }
        if ($hostel_name != '') {
            $where = " AND hostel_1 ='" . $hostel_name . "'";
            $batch_where = " AND hostel_name ='" . $hostel_name . "'";
        }

        $json_array = "";
        $columns = [
            // "(select count(id) from std_app_s where is_delete= 0 " . $where . " ) as applied_cnt",
            // "(select count(id) from batch_creation where is_delete = 0 " . $batch_where . " ) as accp_cnt",
            // "(select count(id) from std_reg_s where is_delete = 0  " . $where . ") as approved_cnt",
            "(select count(id) from batch_creation where is_delete = 0 and status = '2' " . $batch_where . ") as rejected_cnt",
        ];
        $table_details = [
            "std_app_s",
            $columns
        ];

        $result = $pdo->select($table_details);

        $res_array = $result->data;

        // $applied_cnt = 0;
        // $accp_cnt = 0;
        // $approved_cnt = 0;
        $rejected_cnt = 0;

        foreach ($res_array as $value) {

            // $applied_cnt = $value['applied_cnt'];
            // $accp_cnt = $value['accp_cnt'];
            // $approved_cnt = $value['approved_cnt'];
            $rejected_cnt = $value['rejected_cnt'];

        }

        $json_array = [
            // "applied_cnt" => $applied_cnt,
            // "accp_cnt" => $accp_cnt,
            // "approved_cnt" => $approved_cnt,
            "rejected_cnt" => $rejected_cnt,

        ];

        echo json_encode($json_array);

        break;



case 'fetch_chart_data':
           
    
                    $json_array     = "";
                    $columns        = [           
                       "reject_reason",
                       "count"
                    ];
                    $table_details  = [
                        "view_reject_reason_count",
                        $columns
                    ];
                    // $where        = "hostel_1 = '".$_SESSION['hostel_id']."'";
                    $where = "is_delete = 0";
            
                   
                    $result         = $pdo->select($table_details);
                    // print_r($result);
                    $res_array      = $result->data;
                //    print_r($result);
                    foreach($res_array as $value){
                    
                        $reject_reason[]         = $value['reject_reason'];
                        $count[]         = $value['count'];
                     
                        
                        }
                            
                    $json_array = [
                            "reject_reason"        => $reject_reason,    
                            "count"        => $count,    
                        ];
                    
                     echo json_encode($json_array);
                     
                    break;

	case 'district_name':
        $district_name = $_POST['district_name'];

        $district_options = taluk_name('', $district_name);

        $hostel_taluk_options = select_option($district_options, 'Select Taluk');

        echo $hostel_taluk_options;

        break;

        case 'get_hostel_by_taluk_name':

            $taluk_name = $_POST['taluk_name'];
    
    
            $hostel_name_options = hostel_name('', $taluk_name);
    
            $hostel_name_options = select_option($hostel_name_options, "Select Hostel");
            // print_r( $hostel_name_options);
    
            echo $hostel_name_options;
    
            break;    


        case 'datatable':
            // DataTable Variables
            $search     = $_POST['search']['value'];
            $length     = $_POST['length'];
            $start      = $_POST['start'];
            $draw       = $_POST['draw'];
            $limit      = $length;
    
            $data       = [];

            $feedback_name         = $_POST["feedback_name"];
            $rating                = $_POST["rating"];
            $description           = $_POST["description"];
            $student_id            = $_POST["student_id"];
            $date                  = $_POST['date']; 
            $is_active             = $_POST["is_active"];
            $unique_id             = $_POST["unique_id"];
    
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

            case 'get_vacancy_count':

                $json_array     = "";
                $columns        = [           
                    "(select sum(sanctioned_strength) as str_cnt from hostel_name where district_name = '".$_SESSION['district_id']."') as tot_cap",
                    "'' as old_std",
                    "count(id) as approved_cnt",

                    // "'' as hos_vacancy",
                    // "(select COUNT(id) where stage_1_status = 3 and is_delete = 0) as cancel_comp",
                ];
                $table_details  = [
                    "std_app_s",
                    $columns
                ];
                $where        = "hostel_district_1 = '".$_SESSION["district_id"]."' and status = '1'";
                $result         = $pdo->select($table_details,$where);
                $res_array      = $result->data;
            //    print_r($result);die();
                foreach($res_array as $value){
                
                    $tot_cap         = $value['tot_cap'];
                    // $accp_cnt       = $value['old_std'];
                    if($value['old_std'] == ''){
                        $old_std = '0';
                    }
                    $approved_cnt   = $value['approved_cnt'];
                    
                        $hos_vac = $tot_cap - $approved_cnt;
                    
                    
                    }
                        
                $json_array = [
                        "tot_cap"        => $tot_cap,
                        "old_std"      => $old_std,
                        "approved_cnt"  => $approved_cnt,
                        "hos_vac"    => $hos_vac,
                        
                        
                    ];
                
                 echo json_encode($json_array);
                 
                break;


        case 'total_hostels':


            // $district_name = $_SESSION["district_id"]; 
        

            $table = "hostel_name";

            $columns        = [      
                "count(hostel_name)as hostel_count"    
               
            ];
            $table_details  = [
                $table,
                $columns
            ];

          $where = "is_delete = 0 ";

            $result         = $pdo->select($table_details,$where);
            $res_array      = $result->data;


        //    print_r($result);die();

            foreach($res_array as $value){

                   $hostel_count = $value['hostel_count'];
            
                // $data[]             = array_values($value);
            }      
            $json_array = [

                    // "data"  => $data,
                    "hostel_count" => $hostel_count  
                ];
            
             echo json_encode($json_array);
             
            break;

            case 'total_students':


                // $district_name = $_SESSION["district_id"]; 
            
    
                $table = "std_reg_s";

                $json_array = [];
    
                $columns        = [ 

                    // "count(id)as student_name",
                  "count(id) as student_name",

                   
                ];
                $table_details  = [
                    $table,
                    $columns
                ];
    
              $where = "is_delete = 0 AND dropout_status = 1 ";
    
                $result         = $pdo->select($table_details,$where);
                $res_array      = $result->data;
    
    
            //    print_r($result);die();
    
                foreach($res_array as $value){
                       $student_name = $value['student_name'];
                
                    // $data[]             = array_values($value);
                }      
                $json_array = [
    
                        // "data"  => $data,
                        "student_name"  => $student_name,
                       
                    ];
                
                 echo json_encode($json_array);
                 
                break;

                case 'total_staff_strength':


                    // $district_name = $_SESSION["district_id"]; 
                
        
                    $table = "establishment_registration";
        
                    $columns        = [ 
    
                        "count(staff_name)as staff_name"    
                       
                    ];
                    $table_details  = [
                        $table,
                        $columns
                    ]; 
        
                  $where = "is_delete = 0";
        
                    $result         = $pdo->select($table_details,$where);
                    $res_array      = $result->data;
        
        
                //    print_r($result);die();
        
                    foreach($res_array as $value){

                        $staff_name = $value['staff_name'];
                    
                        // $data[]             = array_values($value);
                    }      
                    $json_array = [
        
                            // "data"  => $data,
                            "staff_cnt"  => $staff_name,
                           
                        ];
                    
                     echo json_encode($json_array);
                     
                    break;







         case 'applied_leave_details':

                        $table_leave ="leave_application";
            
                        $district_name = $_SESSION["district_id"]; 
                
                        $json_array     = "";
                        // $today  =  date('Y-m-d');
            
                        $columns_leave       = [
                            "from_date",
                            "no_of_days",
                            "approval_status",
                            "count(student_name)as student_name"
                            // "approval_status"
                           ];

                        $table_details_leave  = [
                            $table_leave,
                            $columns_leave,
                        ];
            
                        $where        = "is_delete = 0";
            
                
                        $sql_function   = "SQL_CALC_FOUND_ROWS";
            
                        
                
                
                        $result         = $pdo->select($table_details_leave, $where);
                        // print_r($result);
                        $total_records  = total_records();
            
                        if ($result->status) {
                
                            $res_array      = $result->data;
                    
                            foreach ($res_array as $key => $value) {
            
                                // $from_date = $value['from_date'];
                                $no_of_student_name = $value['student_name'];

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
                        // $data[]             = array_values($value);
                            }     
                        $json_array = [

                                "applied_leave_details" => $res_array,
                             
                                "no_of_student_name"            => $no_of_student_name, 
                            
                                // "approval_status"    =>     $value['approval_status']
                            ];
                        
                         echo json_encode($json_array);
                        }
            
                         
                    break;





                    case 'staff_applied_leave_details':

                        $table_leave ="staff_leave_application";
            
                        // $district_name = $_SESSION["district_id"]; 
                
                        $json_array     = "";
                        // $today  =  date('Y-m-d');
            
                        $columns_leave       = [
                            "from_date",
                            "no_of_days",
                            "approval_status",
                            "count(staff_name)as staff_name"
                            // "approval_status"
                           ];

                        $table_details_leave  = [
                            $table_leave,
                            $columns_leave,
                        ];
            
                        $where        = "is_delete = 0";
            
                
                        $sql_function   = "SQL_CALC_FOUND_ROWS";
            
                        
                
                
                        $result         = $pdo->select($table_details_leave, $where);
                        // print_r($result);
                        $total_records  = total_records();
            
                        if ($result->status) {
                
                            $res_array      = $result->data;
                    
                            foreach ($res_array as $key => $value) {
            
                                // $from_date = $value['from_date'];
                                $staff_name = $value['staff_name'];

                        $res_array      = $result->data;
                        // $data[]             = array_values($value);
                            }     
                        $json_array = [

                                "applied_leave_details" => $res_array,
                             
                                "staff_name"            => $staff_name, 
                            
                                // "approval_status"    =>     $value['approval_status']
                            ];
                        
                         echo json_encode($json_array);
                        }
            
                         
                    break;




                    case 'hostel_vaccancy':

                        $table_hs = "hostel_name";
                        $json_array     = "";
                        $columns        = [      
                            // "hostel_name",
                            // "hostel_id",
                            // "district_name",
                            // "taluk_name",
                            
                            "sum(sanctioned_strength) as sanctioned_count",

                            

                            // "(select count(hostel_1 from std_reg_s where std_reg_s.hostel_1 = $table_hs.unique_id))as reg_hostel",
                            // "student_count",
                            // "unique_id"     
                       
                        ];
                        $table_details  = [

                            "hostel_name",
                            $columns

                        ];

                        $where        = "is_delete = 0";
                        $result         = $pdo->select($table_details,$where);
                        // print_r($result);
                        $res_array      = $result->data;
                    //    
                        foreach($res_array as $value){

                          $sanctioned_cnt = $value['sanctioned_count'];

                          $registered_cnt  = total_reg_count();
                        
                            
                         $hostel_vaccancy = $sanctioned_cnt - $registered_cnt;
     }
                                
                        $json_array = [

                                "approved_cnt"  => $sanctioned_cnt, 
                                "registered_cnt"    => $registered_cnt,
                                "hostel_vaccancy"   =>  $hostel_vaccancy
                                
                                
                            ];
                        
                         echo json_encode($json_array);
                         
                        break;

                        case 'district_wise_count':


                            $table_dis  = "district_name";

                            $json_array     = [];
                            $columns        = [  

                                "district_name",
                                "(select count(unique_id) from std_reg_s where std_reg_s.hostel_district_1 =$table_dis.unique_id)as reg_district "
                            ];

                            $table_details  = [
                                $table_dis,
                                $columns
                            ];

                            $where        =  "is_delete = 0";

                            $result         = $pdo->select($table_details,$where);

                            // print_r($result);
                            $res_array      = $result->data;

                           
                           $district_names = [];
                           $reg_district = [];

                            foreach($res_array as $value){
                            
                                $district_names[] = ($value['district_name']);

                                
                                $reg_district[] = $value['reg_district']; 

                                // print_r($reg_district);
                                
                                }
                                    
                            $json_array = [

                                   "district_names" => $district_names,
                                   "reg_district"  => $reg_district 
                                    
                                ];
                            
                             echo json_encode($json_array);
                             
                            break;
            





            case 'get_application_count':

                $json_array     = "";
                $columns        = [           
                    "(select count(id) from std_app_s where is_delete = 0 and hostel_district_1 = '".$_SESSION['district_id']."') as applied_cnt",
                    "(select count(id) from std_app_s where is_delete = 0 and batch_no != '' and hostel_district_1 = '".$_SESSION['district_id']."') as accp_cnt",
                    "(select count(id) from std_app_s where is_delete = 0 and status = '1' and hostel_district_1 = '".$_SESSION['district_id']."') as approved_cnt",
                    "(select count(id) from std_app_s where is_delete = 0 and status = '2' and hostel_district_1 = '".$_SESSION['district_id']."') as rejected_cnt",

                    // "(select COUNT(id) where stage_1_status = 3 and is_delete = 0) as cancel_comp",
                ];
                $table_details  = [
                    "std_app_s",
                    $columns
                ];
                $where        = "hostel_district_1 = '".$_SESSION["district_id"]."'";
                $result         = $pdo->select($table_details,$where);
                $res_array      = $result->data;
            //    print_r($result);
                foreach($res_array as $value){
                
                    $applied_cnt         = $value['applied_cnt'];
                    $accp_cnt            = $value['accp_cnt'];
                    $approved_cnt        = $value['approved_cnt'];
                    $rejected_cnt        = $value['rejected_cnt'];
                    
                    }
                        
                $json_array = [
                        "applied_cnt"        => $applied_cnt,
                        "accp_cnt"      => $accp_cnt,
                        "approved_cnt"  => $approved_cnt,
                        "rejected_cnt"    => $rejected_cnt,
                        
                        
                    ];
                
                 echo json_encode($json_array);
                 
                break;
         

    default:
        
        break;
}
   
function total_reg_count($unique_id = "") {
    global $pdo;

    $table_name = "std_reg_s";
    $where = [
        "is_active" => 1,
        "is_delete" => 0,
    ];

    // if ($unique_id) {
    //     $where["hostel_1"] = $unique_id; // Corrected the way unique_id is added to the where clause
    // }

    $table_columns = [
        "count(id) as tot_cnt",
    ];

    $table_details = [
        $table_name,
        $table_columns,
    ];

    // Use the select method from $pdo to query the database
    $amc_name_list = $pdo->select($table_details, $where);

    // print_r($amc_name_list);

    if ($amc_name_list->status) {
        return $amc_name_list->data[0]['tot_cnt'];
    } else {
        print_r($amc_name_list);
        return 0;
    }
}


// function total_reg_count($unique_id = "")
// {
//     // echo $zone_name;

//     global $pdo;

//     $table_name = "std_reg_s";
//     $where = [];
//     $table_columns = [

//         "count(id) as tot_cnt",
//     ];

//     $table_details = [
//         $table_name,
//         $table_columns
//     ];
    

//     $where = [
//         "is_active" => 1,
//         "is_delete" => 0,
    
//         // "dropout_status"=>1
//     ];

//     if ($unique_id) {
//         // $where              = [];
//         $where["hostel_1"] .= $unique_id;
//     }
//     // if ($unique_id) {
//     //     $where              = [];
//     //     $where["unique_id"] = $unique_id;
//     // }

//     $amc_name_list = $pdo->select($table_details, $where);

//     // print_r( $amc_name_list);

//     if ($amc_name_list->status) {
//         return $amc_name_list->data[0]['tot_cnt'];
       
//     } else {
//         print_r($amc_name_list);
//         return 0;
//     }
// }

        //
?>
