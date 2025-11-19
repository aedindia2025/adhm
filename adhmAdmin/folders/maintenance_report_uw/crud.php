<?php 

// Get folder Name From Currnent Url 
$folder_name        = explode("/",$_SERVER['PHP_SELF']);
$folder_name        = $folder_name[count($folder_name)-2];

// // Database Country Table Name
$table             = "maintanance_creation";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action             = $_POST['action'];

$district_name      = "";
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

        $hostel_id      = $_POST["hostel_id"];
        $hostel_name      = $_POST["hostel_name"];
        $district_id      = $_POST["district_id"];
        $taluk_id      = $_POST["taluk_id"];
        $warden_name      = $_POST["warden_name"];
        $warden_id      = $_POST["warden_id"];
        $maintanance_no      = $_POST["maintanance_no"];
        $asset_category      = $_POST["asset_category"];
        $asset_name      = $_POST["asset_name"];
        
        $description      = $_POST["description"];
        $spend_amount      = $_POST["spend_amount"];
        // $is_active          = $_POST["is_active"];
        $unique_id          = $_POST["unique_id"];

        $update_where       = "";


        $allowedExts = array('pdf');

        $extension = pathinfo($_FILES["test_file"]['name'], PATHINFO_EXTENSION);
                    $file_exp = explode(".",$_FILES["test_file"]['name']);
                    // echo $file_exp;
           
                    $tem_name =  random_strings(25).".".$file_exp[1]; 

                    // echo $tem_name;

                move_uploaded_file($_FILES["test_file"]["tmp_name"], '../../uploads/maintanance/' . $tem_name);
            $file_names     = $tem_name;
            $file_org_names = $_FILES["test_file"]['name'];





        $columns            = [
            "hostel_id"       => $hostel_id,
            "hostel_name"       => $hostel_name,
            "hostel_district"       => $district_id,
            "hostel_taluk"       => $taluk_id,
            "warden_name"       => $warden_name,
            "warden_id"       => $warden_id,
            "maintanance_no"       => $maintanance_no,
            "asset_category"       => $asset_category,
            "asset_name"       => $asset_name,
            "description"       => $description,
            "spend_amount"       => $spend_amount,
            "file_name"          => $file_names,
            "file_org_name"          => $file_org_names,
            "entry_date"     => date('Y-m-d'),
            
            // "is_active"           => $is_active,
            "unique_id"           => unique_id($prefix)
        ];

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
        // print_r($action_obj);die();
        // print_r($action_obj);

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
        

        if($length == '-1') {
            $limit  = "";
        }

        // Query Variables
        $json_array     = "";
        $columns        = [
            "@a:=@a+1 s_no",
            "entry_date",
            "maintanance_no",
            "(select facility_type from facility_type_creation where facility_type_creation.unique_id = maintanance_creation.asset_category) as asset_category",
            "(select facility_name from facility_creation where facility_creation.unique_id = maintanance_creation.asset_name) as asset_name",
            "(select district_name from district_name where district_name.unique_id = maintanance_creation.hostel_district) as hostel_district",
            "(select hostel_name from hostel_name where hostel_name.unique_id = maintanance_creation.hostel_name) as hostel_name",
            "file_name",
            // "status",
            
            "unique_id"
        ];
        $table_details  = [
            $table." , (SELECT @a:= ".$start.") AS a ",
            $columns
        ];
        $where          = "is_delete = 0";
        $order_by       = "";

        // if ($_POST['search']['value']) {
        //    $where .= " AND district_name LIKE '".mysql_like($_POST['search']['value'])."' ";
        // }
        if ($_POST['academic_year']) {
            $where .= " AND academic_year = '".$_POST['academic_year']."'";
         }
         if ($_POST['district_name']) {
            $where .= " AND hostel_district = '".$_POST['district_name']."'";
         }
         if ($_POST['hostel_name']) {
            $where .= " AND hostel_name = '".$_POST['hostel_name']."'";
         }
         if ($_POST['taluk_name']) {
            $where .= " AND hostel_taluk = '".$_POST['taluk_name']."'";
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
                // $value['district_name'] = district_list($value['district_name']);
                // $value['is_active']     = is_active_show($value['is_active']);

                
                $value['file_name'] = image_view("adhmHostel", $value['unique_id'],  $value['file_name']);
                $unique_id = $value['unique_id'];
                $value['unique_id'] = '<a class="btn btn-action specl2"  href="javascript:view_app(\'' . $unique_id . '\')"><button type="button" ><i class="fa fa-eye" ></i></button></a>';
 
                
                    // $value['file_name'] = 

               
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


        case 'get_asset_name':
            $asset_category          = $_POST['asset_category'];
            $asset_category_options  = facility_name("",$asset_category);
    
            $asset_name_options  = select_option($asset_category_options,"Select Asset");
    // print_r($asset_name_options);
            echo $asset_name_options;
            
            break;


            case 'get_asset_count':
                // DataTable Variables
                $search     = $_POST['search']['value'];
                $length     = $_POST['length'];
                $start      = $_POST['start'];
                $draw       = $_POST['draw'];
                $limit      = $length;
                $asset_name = $_POST['asset_name'];
        
                $data       = [];
                
        
                if($length == '-1') {
                    $limit  = "";
                }
        
                // Query Variables
                $json_array     = "";
                $columns        = [
                    "(select digital_infra_facility_sub.quantity from digital_infra_creation left join digital_infra_facility_sub on digital_infra_creation.unique_id = digital_infra_facility_sub.form_main_unique_id where digital_infra_creation.hostel_id = '".$_SESSION['hostel_id']."' and digital_infra_facility_sub.facilities='".$asset_name."' and digital_infra_facility_sub.is_delete='0') as quantity",
                ];
                $table_details  = [
                    "digital_infra_creation",
                    $columns
                ];
                $where          = "is_delete = 0";
                $order_by       = "";
        
                if ($_POST['search']['value']) {
                   $where .= " AND district_name LIKE '".mysql_like($_POST['search']['value'])."' ";
                }
                
                // Datatable Searching
                $search         = datatable_searching($search,$columns);
        
                
        
                $sql_function   = "SQL_CALC_FOUND_ROWS";
        
                $result         = $pdo->select($table_details,$where,$limit,$start,$order_by,$sql_function);
                // print_r($result);
                $total_records  = total_records();
        
                if ($result->status) {
        
                    $res_array      = $result->data;
        
                    foreach ($res_array as $key => $value) {
                        // $value['district_name'] = district_list($value['district_name']);
                        $quantity = $value['quantity'];
                       
                        $data[]             = array_values($value);
                    }
                    
                    $json_array = [
                        "draw"              => intval($draw),
                        "recordsTotal"      => intval($total_records),
                        "recordsFiltered"   => intval($total_records),
                        "data"              => $data,
                        "quantity"              => $quantity,
                        "testing"           => $result->sql
                    ];
                } else {
                    print_r($result);
                }
                
                echo json_encode($json_array);
                break;



                case 'district_name':

                    $district_name = $_POST['district_name'];
        
        
                    $district_name_options = taluk_name(' ',$district_name);
        
                    $taluk_name_options = select_option($district_name_options,"Select Taluk");
                    
                    echo $taluk_name_options;
        
                    break;
        
                case 'get_hostel_by_taluk_name':
        
                    $taluk_name = $_POST['taluk_name'];
        
        
                    $hostel_name_options = hostel_name(' ',$taluk_name);
        
                    $hostel_name_options = select_option($hostel_name_options,"Select Hostel");
        
                    echo $hostel_name_options;
        
                    break;
        




    default:
        
        break;
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
                    $image_view .= '<a href="javascript:print_view(\'/' . $doc_file_name . '\')"><img src="../adhmHostel/uploads/maintanance/' . $doc_file_name . '"  width="40%" ></a>';
                    // $image_view .= '<img src="uploads/'.$folder_name.'/'.$doc_name.'"  height="50px" width="50px" >';
                } else if ($cfile_name[1] == 'pdf') {
                    $image_view .= '<a href="javascript:print_pdf(\'/' . $doc_file_name . '\')"><img src="uploads/pdf.png"   width="30%" style="margin-left: 15px;" ></a>';
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
