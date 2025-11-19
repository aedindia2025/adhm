<?php 

// Get folder Name From Currnent Url 
$folder_name        = explode("/",$_SERVER['PHP_SELF']);
$folder_name        = $folder_name[count($folder_name)-2];

// // Database Country Table Name
$table             = "std_app_p1";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action             = $_POST['action'];

$feedback_type      = "";
$is_active          = "";
$unique_id          = "";
$prefix             = "";

$data               = "";
$msg                = "";
$error              = "";
$status             = "";
$test               = ""; // For Developer Testing Purpose

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
        $academic_year = $_POST['academic_year'];
        $app_status = $_POST['app_status'];
        
        

        if($length == '-1') {
            $limit  = "";
        }

        // Query Variables
        $json_array     = "";
        $columns        = [
            "@a:=@a+1 s_no",
            // "cur_date",
            "entry_date",
            "std_app_no",
            "std_name",
            "hostel_district",
            // "hostel_name",
            "batch_no",
            "status",
            "'' as reason",
            "unique_id",
            "hostel_taluk",
            
            
            "hostel_district",
            "batch_cr_date",
            
            // "is_active",
            // "unique_id"
        ];
        $table_details  = [
            $table." , (SELECT @a:= ".$start.") AS a ",
            $columns
        ];
        $where          = "is_delete = 0";
        $order_by       = "";

        if ($_POST['search']['value']) {
           $where .= " AND carrier_title LIKE '".mysql_like($_POST['search']['value'])."' ";
        }

        if($app_status != ''){
            $where .= " AND status ='".$app_status."'";
        }
        if($academic_year != ''){
            $where .= " AND academic_year ='".$academic_year."'";
        }
        if($district_name != ''){
            $where .= " AND hostel_district ='".$district_name."'";
        }
        if($taluk_name != ''){
            $where .= " AND hostel_taluk ='".$taluk_name."'";
        }
        if($hostel_name != ''){
            $where .= " AND hostel_name ='".$hostel_name."'";
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
                $reason = get_reason($value['unique_id']);
                if($reason != ''){
                $value['reason'] = $reason;
                }else{
                    $value['reason'] = '-';  
                }
                $batch_no = $value['batch_no'];
                if($batch_no != ''){
                    $value['batch_no'] = $batch_no;
                }else{
                        $value['batch_no'] = '-';  
                    }
                switch($value['status']){
                    case 0:
                        $value['status'] = 'Pending';
                        break;
                        case 1:
                            $value['status'] = 'Approved';
                            break;
                            case 2:
                                $value['status'] = 'Rejected';
                                break;
                }

                $hostel_district = district_name($value['hostel_district'])[0]['district_name'];
                $hostel_taluk = taluk_name($value['hostel_taluk'])[0]['taluk_name'];
                $hostel_name = hostel_name($value['hostel_name'])[0]['hostel_name'];
                $value['hostel_district'] = '<b>'.$hostel_name.'</b><br>'.$hostel_district.' / '.$hostel_taluk;
                $value['batch_no'] = '<b>'.$value['batch_no']. '</b><br>' . $value['batch_cr_date'];
                // $value['unique_id'] = $btn_update.$btn_delete;
                $unique_id = $value['unique_id'];
                $value['unique_id'] = '<a class="btn btn-action specl2"  href="javascript:view_app(\'' . $unique_id . '\')"><button type="button" ><i class="fa fa-eye" ></i></button></a>';

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

        case 'get_hostel_by_taluk_name':
        
            $taluk_name = $_POST['taluk_name'];


            $hostel_name_options = hostel_name('',$taluk_name);

            $hostel_name_options = select_option_host($hostel_name_options,"Select Hostel");

            echo $hostel_name_options;

            break;

    default:
        
        break;
}

function get_reason($unique_id = "") {
    // echo $zone_name;

    global $pdo;

    $table_name    = "batch_creation";
    $where         = [];
    $table_columns = [
        "reason",
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where     = [
        "is_active" => 1,
        "is_delete" => 0,
        // "batch_no"  => $unique_id,
        "p1_unique_id"    => $unique_id,
    ];

    // if ($unique_id) {
    //     // $where              = [];
    //     $where["batch_no"] .= $unique_id;
    // }
    // if ($unique_id) {
    //     $where              = [];
    //     $where["unique_id"] = $unique_id;
    // }

    $amc_name_list = $pdo->select($table_details, $where);

    // print_r( $amc_name_list);

    if ($amc_name_list->status) {
        return $amc_name_list->data[0]['reason'];
    } else {
        // print_r($amc_name_list);
        return 0;
    }



}
    
       
function image_view($folder_name = "", $unique_id = "", $doc_file_name = "")
{
    // echo $dc_file_name;
    $file_names = explode(',', $doc_file_name);
    $image_view = '';

    if ($doc_file_name) {
        foreach ($file_names as $file_key => $doc_file_name) {
            if ($file_key != 0) {
                if ($file_key % 4 != 0) {
                    $image_view .= "&nbsp";
                } else {
                    $image_view .= "<br><br>";
                }
            }

            $cfile_name = explode('.', $doc_file_name);

            if ($doc_file_name) {

                if (($cfile_name[1] == 'jpg') || ($cfile_name[1] == 'png') || ($cfile_name[1] == 'jpeg')) {
                    $image_view .= '<a href="javascript:print_view(\'/' . $doc_file_name . '\')"><img src="uploads/' . $folder_name . '/' . $doc_file_name . '"  width="20%" ></a>';
                    // $image_view .= '<img src="uploads/'.$folder_name.'/'.$doc_name.'"  height="50px" width="50px" >';
                } else if ($cfile_name[1] == 'pdf') {
                    $image_view .= '<a href="javascript:print_pdf(\'/' . $doc_file_name . '\')"><img src="uploads/pdf.png"   width="20%" style="margin-left: 15px;" ></a>';
                } else if (($cfile_name[1] == 'pdf') || ($cfile_name[1] == 'xls') || ($cfile_name[1] == 'xlsx')) {
                    $image_view .= '<a href="javascript:print(\'/' . $doc_file_name . '\')"><img src="uploads/excel.png"  height="30px" width="30px" ></a>';
                } else if (($cfile_name[1] == 'txt') || ($cfile_name[1] == 'docx') || ($cfile_name[1] == 'doc')) {
                    $image_view .= '<a href="javascript:print(\'/' . $doc_file_name . '\')"><img src="uploads/word.png"  height="30px" width="30px" ></a>';
                }
            }
        }
    }

    return $image_view;
}


   
?>
