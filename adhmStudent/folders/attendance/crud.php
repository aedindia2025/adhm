<?php 

// Get folder Name From Currnent Url 
$folder_name        = explode("/",$_SERVER['PHP_SELF']);
$folder_name        = $folder_name[count($folder_name)-2];

// // Database Country Table Name
$table             = "std_reg_s";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action             = $_POST['action'];

$user_type          = "";
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

        $user_type          = $_POST["user_type"];
        $is_active          = $_POST["is_active"];
        $unique_id          = $_POST["unique_id"];

        $update_where       = "";

        $columns            = [
            "user_type"           => $user_type,
            "is_active"           => $is_active,
            "unique_id"           => unique_id($prefix)
        ];

            // check already Exist Or not
        $table_details      = [
            $table,
            [
                "COUNT(unique_id) AS count"
            ]
        ];
        $select_where       = 'user_type = "'.$user_type.'"  AND is_delete = 0  ';

        // When Update Check without current id
        if ($unique_id) {
            $select_where   .= ' AND unique_id !="'.$unique_id.'" ';
        }

        $action_obj         = $pdo->select($table_details,$select_where);

        if ($action_obj->status) {
            $status     = $action_obj->status;
            $data       = $action_obj->data;
            $error      = "";
            $sql        = $action_obj->sql;

        } else {
            $status     = $action_obj->status;
            $data       = $action_obj->data;
            $error      = $action_obj->error;
            $sql        = $action_obj->sql;
            $msg        = "error";
        }
        if ($data[0]["count"]) {
            $msg        = "already";
        } else if ($data[0]["count"] == 0) {
            // Update Begins
            if($unique_id) {

                unset($columns['unique_id']);

                $update_where   = [
                    "unique_id"     => $unique_id
                ];

                $action_obj     = $pdo->update($table,$columns,$update_where);

            // Update Ends
            } else {

                // Insert Begins            
                $action_obj     = $pdo->insert($table,$columns);
                // Insert Ends

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
        

        if($length == '-1') {
            $limit  = "";
        }

        // Query Variables
        $json_array     = "";
        $columns        = [
            "@a:=@a+1 s_no",
            "entry_date",
            "std_reg_no",
            "attendance_type",
            
            "entry_time",
            "unique_id"
        ];
        $table_details  = [
            "attendance_creation , (SELECT @a:= ".$start.") AS a ",
            $columns
        ];
        $where          = "is_delete = 0 AND s1_unique_id = '".$_SESSION['user_id']."'";
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
               
                $attendance_type = $value['attendance_type'];
                switch($attendance_type){
                    case 1:
                        $value['attendance_type'] = 'Check IN';
                        break;
                        case 2:
                            $value['attendance_type'] = 'Check OUT';
                            break;
                }
                $value['entry_time'] = date("g:i A",strtotime($value['entry_time']));

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
                "testing"           => $result->sql
            ];
        } else {
            print_r($result);
        }
        
        echo json_encode($json_array);
        break;


        case 'att_cr_datatable':
            // print_r("hii");
            // DataTable Variables
            $search     = $_POST['search']['value'];
            $length     = $_POST['length'];
            $start         = $_POST['start'];
            $draw         = $_POST['draw'];
            $limit         = $length;
            $data        = [];

            $hostel_name     = $_POST["hostel_name"];
            // $batch_no     = $_POST["batch_no"];
            $hostel_taluk     = $_POST["hostel_taluk"];
            $hostel_district     = $_POST["hostel_district"];
            $academic_year     = $_POST['academic_year'];

            if ($length == '-1') {
                $limit  = "";
            }
            // Query Variables
            $json_array     = "";
            $columns        = [
                "unique_id",
                "'' as  s_no",
                "std_name",
                "std_reg_no",
                
                "unique_id as p1_unique_id",
                "academic_year",
                
                
            ];
            $table_details  = [
                $table,
                $columns
            ];
            $where = "is_delete = '0' and hostel_name = '".$_SESSION['hostel_id']."'";
            
            $order_column   = $_POST["order"][0]["column"];
            $order_dir      = $_POST["order"][0]["dir"];
            // Datatable Ordering 
            $order_by       = datatable_sorting($order_column, $order_dir, $columns);
            // Datatable Searching
            $search         = datatable_searching($search, $columns);
            if ($search) {
                if ($where) {
                    $where .= " AND ";
                }
                $where .= $search;
            }
            $sql_function   = "SQL_CALC_FOUND_ROWS";
            // $group_by = 'invoice_no';
            $result         = $pdo->select($table_details, $where, $limit, $start, $order_by, $sql_function);
            // print_r($result);die();
            $total_records  = total_records();
            if ($result->status) {
                $res_array      = $result->data;
                foreach ($res_array as $key => $value) {
                    $value['s_no'] = $sno + 1;
                    $value['std_name'] = strtoupper($value['std_name']); 
                    $value['hostel_name'] = hostel_name($value['hostel_name'])[0]['hostel_name'];
                    
        
                    
                    $value['unique_id'] = '<input class="myCheck" type="checkbox" id="invoice_check" name="invoice_check[]">
                    <input type="text" id="p1_unique_id" name="p1_unique_id[]" value="' . $value['p1_unique_id'] . '">  
                      
                    <input type="text" id="hostel_name" name="hostel_name[]" value="' . $hostel_name . '">  
                    <input type="text" id="hostel_taluk" name="hostel_taluk[]" value="' . $hostel_taluk . '">  
                    
                    <input type="text" id="hostel_district" name="hostel_district[]" value="' . $hostel_district . '">  
                    <input type="text" id="std_name" name="std_name[]" value="' . $value['std_name'] . '">
                    <input type="text" id="academic_year" name="academic_year[]" value="' . $value['academic_year'] . '">
                    <input type="text" id="std_reg_no" name="std_reg_no[]" value="' . $value['std_reg_no'] . '">';
                    
                    // $value['unique_id'] = $btn_update . $btn_delete;
                    $data[]             = array_values($value);
                    $sno++;
                }
                $json_array = [
                    "draw"                => intval($draw),
                    "recordsTotal"         => intval($total_records),
                    "recordsFiltered"     => intval($total_records),
                    "data"                 => $data,
                    // "testing"            => $result->sql
                ];
            } else {
                // print_r($result);
            }
            echo json_encode($json_array);
            break;

            case 'att_create':

                $cur_date = date('Y-m-d');

                $table_details      = [
                    "attendance_creation",
                    [
                        "count(entry_date) as count"
                    ]
                ];
                $select_where       = 'entry_date = "'.$cur_date.'"  AND is_delete = 0  group by entry_date';
        
                
        
                $action_obj         = $pdo->select($table_details,$select_where);
                // print_r($action_obj);
        
                if ($action_obj->status) {
                    $status     = $action_obj->status;
                    $data       = $action_obj->data;
                    $error      = "";
                    $sql        = $action_obj->sql;
        
                } else {
                    $status     = $action_obj->status;
                    $data       = $action_obj->data;
                    $error      = $action_obj->error;
                    $sql        = $action_obj->sql;
                    $msg        = "error";
                }
                // if($data[0]["count"] != '2'){
                if ($data[0]["count"]) {
                    $p1_unique_id         = $_POST["p1_unique_id"];
               
                $hostel_name          = $_POST["hostel_name"];
                $hostel_taluk         = $_POST["hostel_taluk"];
                $hostel_district          = $_POST["hostel_district"];
                $std_name           = $_POST["std_name"];
                $std_reg_no           = $_POST["std_reg_no"];
                $academic_year           = $_POST["academic_year"];
                
                 
                
                $columns            = [
                    "p1_unique_id"      => $p1_unique_id,
                    
                    "hostel_name"           => $hostel_name,
                    "hostel_taluk"           => $hostel_taluk,
                    "hostel_district"     => $hostel_district,
                    "std_name"           => $std_name,
                    "std_reg_no"           => $std_reg_no,
                    "academic_year"           => $academic_year,
                    "entry_date"           => date('Y-m-d'),
                    "entry_time"           => date('H:i:s'),
                    "attendance_type"       => '2',
                     
                    
                    "unique_id"           => unique_id($prefix)
                ];
                $action_obj     = $pdo->insert("attendance_creation", $columns);

                } 
                else if ($data[0]["count"] == 0) {
                





                $p1_unique_id         = $_POST["p1_unique_id"];
               
                $hostel_name          = $_POST["hostel_name"];
                $hostel_taluk         = $_POST["hostel_taluk"];
                $hostel_district          = $_POST["hostel_district"];
                $std_name           = $_POST["std_name"];
                $std_reg_no           = $_POST["std_reg_no"];
                $academic_year           = $_POST["academic_year"];
                
                 
                
                $columns            = [
                    "p1_unique_id"      => $p1_unique_id,
                    
                    "hostel_name"           => $hostel_name,
                    "hostel_taluk"           => $hostel_taluk,
                    "hostel_district"     => $hostel_district,
                    "std_name"           => $std_name,
                    "std_reg_no"           => $std_reg_no,
                    "academic_year"           => $academic_year,
                    "entry_date"           => date('Y-m-d'),
                    "entry_time"           => date('H:i:s'),
                    "attendance_type"       => '1',
                    
                    
                    "unique_id"           => unique_id($prefix)
                ];
                
                
                $action_obj     = $pdo->insert("attendance_creation", $columns);
            }
        // }else{
        //     $
        // }

                                
                if ($action_obj->status) {
                    $status     = $action_obj->status;
                    $data       = $action_obj->data;
                    $error      = "";
                    $sql        = $action_obj->sql;
                    // if ($unique_id) {
                    //     $msg        = "update";
                    // } else {
                    $msg        = "create";
                    // }
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
            // "sql"       => $sql
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

function total_count($unique_id = "") {
    // echo $zone_name;

    global $pdo;
    
    $table_name    = "std_reg_p1";
    $where         = [];
    $table_columns = [
        "count(id) as tot_cnt",
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where     = [
        "is_active" => 1,
        "is_delete" => 0,
        "hostel_name" => $_SESSION['hostel_id'],
    ];

    
    // if ($unique_id) {
    //     $where              = [];
    //     $where["unique_id"] = $unique_id;
    // }

    $amc_name_list = $pdo->select($table_details, $where);

    // print_r( $amc_name_list);

    if ($amc_name_list->status) {
        return $amc_name_list->data[0]['tot_cnt'];
    } else {
        print_r($amc_name_list);
        return 0;
    }
}


function present_count($entry_date = "") {
    // echo $zone_name;

    global $pdo;
    
    $table_name    = "attendance_creation";
    $where         = [];
    $table_columns = [
        "count(id) as tot_cnt",
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where     = [
        "is_active" => 1,
        "is_delete" => 0,
        "hostel_name" => $_SESSION['hostel_id'],
        "entry_date" => $entry_date,
        "attendance_type" => '1',
    ];

    
    // if ($unique_id) {
    //     $where              = [];
    //     $where["unique_id"] = $unique_id;
    // }

    $amc_name_list = $pdo->select($table_details, $where);

    // print_r( $amc_name_list);

    if ($amc_name_list->status) {
        return $amc_name_list->data[0]['tot_cnt'];
    } else {
        print_r($amc_name_list);
        return 0;
    }
}
?>
