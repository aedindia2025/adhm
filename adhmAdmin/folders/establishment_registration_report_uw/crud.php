<?php
// Get folder Name From Currnent Url 
$folder_name        = explode("/", $_SERVER['PHP_SELF']);
$folder_name        = $folder_name[count($folder_name) - 2];
// // Database Country Table Name
$table             = "establishment_registration";
// // Include DB file and Common Functions
include '../../config/dbconfig.php';
// // Variables Declaration
$action             = $_POST['action'];
// $user_type          = "";

// $district_name  = $_POST["district_name"];
// $taluk_name =    $_POST["taluk_name"];
// $hostel_name       =$_POST["hostel_name"];
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
        // $user_type          = $_POST["user_type"];
        // $district_
        $staff_name         = $_POST["staff_name"];
        $father_name        = $_POST["father_name"];
        $gender_name        = $_POST["gender_name"];
        // $dob                = $_POST["dob"];
        $age                = $_POST["age"];
        $mobile_num         = $_POST["mobile_num"];
        $district_name      = $_POST["district_name"];
        $taluk_name         = $_POST["taluk_name"];
        $address            = $_POST["address"];
        $aadhaar_no         = $_POST["aadhaar_no"];
        $email_id           = $_POST["email_id"];
        $doj                = $_POST["doj"];
        $department         = $_POST["department"];
        $designation        = $_POST["department_new"];
        $district_name_office = $_POST["district_office"];
        $taluk_name_office      = $_POST["taluk_office"];
        $hostel_name        =    $_POST["hostel_office"];
        // $current_date           =$_POST["current_date"];
        $user_name              = $_POST["user_name"];
        $password               = $_POST["password"];
        $confirm_password       = $_POST["confirm_password"];

       
        $biometric_id = $_POST["biometric_id"];
     
        $is_active          = $_POST["is_active"];
        $unique_id          = $_POST["unique_id"];
        $update_where       = "";

        $columns            = [
            "staff_name"           => $staff_name,
            "father_name"          =>   $father_name,
            "gender_name"           => $gender_name,
            "age"                   =>  $age,
            "mobile_num"            =>  $mobile_num,
            "district_name"         =>   $district_name,
            "taluk_name"            => $taluk_name, 
            "address"               => $address,
            "aadhaar_no"            => $aadhaar_no,
            "email_id"              => $email_id,
            "doj"                   => $doj,
            "department"            => $department,
            "designation"           => $designation,
            "district_office"       => $district_name_office,
            "taluk_office"          =>  $taluk_name_office,
            "hostel_name"           =>  $hostel_name,
            "user_name"             =>  $user_name,
            "password"              =>  $password,
            "confirm_password"      => $confirm_password,
            "biometric_id"          => $biometric_id,
           
            // "is_active"           => $is_active,
            "unique_id"           => unique_id($prefix)
        ];
        // check already Exist Or not
        $table_details      = [
            $table,
            [
                "COUNT(unique_id) AS count"
            ]
        ];
        $select_where       = 'is_delete = 0 ';
        // When Update Check without current id
        if ($unique_id) {
            $select_where   .= ' AND unique_id !="' . $unique_id . '" ';
        }
        $action_obj         = $pdo->select($table_details, $select_where);
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
        // if ($data[0]["count"]) {
        //     $msg        = "already";
        // } else if ($data[0]["count"] == 0) {
            // Update Begins
            if ($unique_id) {
                unset($columns['unique_id']);
                $update_where   = [
                    "unique_id"     => $unique_id
                ];
                $action_obj     = $pdo->update($table, $columns,$update_where);
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

        $staff_name         = $_POST["staff_name"];
        $father_name        = $_POST["father_name"];
        $gender_name        = $_POST["gender_name"];
        // $dob                = $_POST["dob"];
        $age                = $_POST["age"];
        $mobile_num         = $_POST["mobile_num"];
        $district_name      = $_POST["district_name"];
        $taluk_name         = $_POST["taluk_name"];
        $address            = $_POST["address"];
        $aadhaar_no         = $_POST["aadhaar_no"];
        $email_id           = $_POST["email_id"];
        $doj                = $_POST["doj"];
        $department         = $_POST["department"];
        $designation        = $_POST["department_new"];
        $district_name_office = $_POST["district_office"];
        $taluk_name_office      = $_POST["taluk_office"];
        $hostel_name_office     = $_POST["hostel_office"];
        $user_name              = $_POST["user_name"];
        $password               = $_POST["password"];
        $confirm_password       = $_POST["confirm_password"];
        // $hostel_office          = $_POST["hostel_div_warden"];

        $is_active          = $_POST["is_active"];
        $unique_id          = $_POST["unique_id"];
        
        $update_where       = "";
        if ($length == '-1') {
            $limit  = "";
        }
        // Query Variables
        $json_array     = "";
        $columns        = [
            "@a:=@a+1 s_no",
            "staff_name",  
            "(select designation_name from designation_creation where unique_id = $table.designation)AS designation",
            "mobile_num",              
            "(select district_name from district_name where unique_id = $table.district_name)AS district_name",         
            "(select taluk_name from taluk_creation where unique_id = $table.taluk_name)AS taluk_name", 
            // "is_active",
            "unique_id"
        ];
        $table_details  = [
            $table . " , (SELECT @a:= " . $start . ") AS a ",
            $columns
        ];
        $where          = "is_delete = 0";

        if($district_name != ''){
            $where .= " AND district_name='".$district_name."'";
        }
        if($taluk_name != ''){
            $where .= " AND taluk_name='".$taluk_name."'";
        }
        if($designation != ''){
            $where .= " AND designation='".$designation."'";
        }
       

        $order_by       = "";
        if ($_POST['search']['value']) {
            $where .= " AND user_type LIKE '" . mysql_like($_POST['search']['value']) . "' ";
        }
        // Datatable Searching
        $search         = datatable_searching($search, $columns);
        if ($search) {
            if ($where) {
                $where .= " AND ";
            }
            $where .= $search;
        }
        $sql_function   = "SQL_CALC_FOUND_ROWS";
        $result         = $pdo->select($table_details, $where, $limit, $start, $order_by, $sql_function);
        // print_r($result);
        $total_records  = total_records();
        if ($result->status) {
            $res_array      = $result->data;
            foreach ($res_array as $key => $value) {

                // $value['designation'] = designation_un($value['designation']);
                // $value['district_name'] = district_name_un($value['district_name']);
                // $value['taluk_name'] = taluk_name_un($value['taluk_name']);
              

                // $value['user_type'] = disname($value['user_type']);
                $value['is_active'] = is_active_show($value['is_active']);
                $btn_update         = btn_update($folder_name, $value['unique_id']);
                $btn_delete         = btn_delete($folder_name, $value['unique_id']);
                // if ($value['unique_id'] == "5f97fc3257f2525529") {
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
        $action_obj     = $pdo->update($table, $columns, $update_where);

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

        case 'hostel_warden':




        case 'district_name':

            $district_name = $_POST['district_name'];


            $district_name_options = taluk_name(' ',$district_name);

            $taluk_name_options = select_option($district_name_options,"Select Taluk");
            
            echo $taluk_name_options;
            // print_r($taluk_name_options);

            break;

        case 'get_hostel_by_taluk_name':

            $taluk_name = $_POST['taluk_name'];


            $hostel_name_options = hostel_name(' ',$taluk_name);

            $hostel_name_options = select_option($hostel_name_options,"Select Hostel");
            // print_r( $hostel_name_options);

            echo $hostel_name_options;

            break;


            case 'get_district_new_name':

                $district_name = $_POST['district_name_new'];
    
    
                $district_name_options = taluk_name(' ',$district_name);
    
                $taluk_name_options = select_option($district_name_options,"Select Taluk");
                
                echo $taluk_name_options;
    
                break;
    
            case 'get_hostel_by_new_name':
    
                $taluk_name_new = $_POST['taluk_name_new'];
    
                
                $hostel_name_options = hostel_name(' ',$taluk_name_new);


    
                $hostel_name_options = select_option($hostel_name_options,"Select Hostel");
                // print_r( $hostel_name_options);die();
    
                echo $hostel_name_options;
    
                break;
}

