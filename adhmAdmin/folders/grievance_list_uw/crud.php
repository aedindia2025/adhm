<?php 

// Get folder Name From Currnent Url 
$folder_name        = explode("/",$_SERVER['PHP_SELF']);
$folder_name        = $folder_name[count($folder_name)-2];

// // Database Country Table Name
$table             = "grievance_category";
$table1 = "dadwo_approval";

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
function get_grievance_no()
{
    $date = date("Y");
    $st_date = substr($date, 2);
    $month = date("m");
    $datee = $st_date . $month;
    $servername = "localhost";
    $username = "root";
    $password = "4/rb5sO2s3TpL4gu";
    $database_name = "adi_dravidar";
    $hostel_id = $_POST['hostel_id'];
   
    try {
        $conn = new PDO("mysql:host=$servername;dbname=adi_dravidar", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "Connected successfully";
    } catch (PDOException $e) {
        // echo "Connection failed: " . $e->getMessage();
    }

    // $sql = $conn->query("SELECT * FROM academic_year where unique_id = '$user_acc_year' ");
    // $row = $sql->fetch();

     $acc_year = $date;
    $a = str_split($acc_year);
     $splt_acc_yr = $a[2].$a[3];

    // $b = str_split($district_name);
    //  $splt_dis = $b[16].$b[17];
    // $c = str_split($zone_name);
    //  $splt_zone = $c[16].$c[17];
    // $d = str_split($hostel_name);
    //  $splt_host = $d[16].$d[17];
    


    // echo "SELECT * FROM apply_application_form where application_no LIKE 'APN%' order by id desc";echo "<br>";
    // echo "SELECT * FROM  grievance_category where grievance_id  LIKE 'GRV' order by id desc";
    $stmt = $conn->query("SELECT * FROM grievance_category where grievance_id  LIKE 'GRV%' order by id desc");
    // echo $stmt;
    // $bill = $stmt->fetch();
    // $res_array = $bill['id'];
    // $result = $res_array + 1;


    // if($res1=$stmt->fetch($stmt))
    if ($res1 = $stmt->fetch()) {
        $pur_array = explode('-', $res1['grievance_id']);
        // echo $pur_array[0];echo"<br>";
            // echo $pur_array[1];echo"<br>";die();
            // echo $pur_array[2];echo"<br>";
            // echo $pur_array[3];echo"<br>";
            // echo $pur_array[4];echo"<br>";
            // echo $pur_array[5];echo"<br>";
            // die();
            // echo $pur_array[2];echo"<br>";die();


        $year1 = $pur_array[0];
        $year2 = substr($year1, 0, 2);
        $year = '20' . $year2;
        //  echo $booking_no = $pur_array[1];die();
          $booking_no  = substr($pur_array[1],16, 4);
            // echo $booking_no+1;die();
        //   echo $booking_no += 1;echo"<br>";
        //   die();
        //  die();
        // print_r($booking_no);die();
        // .date('Y').'-'
    }
    //  $booking_nos = 'APN-' . $splt_acc_yr .'-' . ''. $splt_dis .'-' .'' .$splt_zone.'-' .'' .$splt_host.'-' .'-0001';
    if ($booking_no == ''){
        $booking_nos =  'GRV-' . $splt_acc_yr . '/'. $month .'/' .$hostel_id.'/'.   '0001';
       
    }
    // else if ($year != date("Y")){
    //     $booking_nos = 'GRV-'.date('Y'). $splt_acc_yr . ''.$splt_dis.'' .$splt_zone.'' .$splt_host.'-0001';
    // }
    else {
        // echo $booking_no;die();
         $booking_no += 1;
    $booking_nos =  'GRV-' . $splt_acc_yr .  '/'.$month.'/' .$hostel_id.'/'. str_pad($booking_no, 4, '0', STR_PAD_LEFT);
    
    }

    
    return $booking_nos;
}
switch ($action) {
    case 'createupdate':

        $grievance_id      = $_POST["grievance_id"];
        $grievance_cate      = $_POST["grievance_category"];
        $grievance_description      = $_POST["description"];
        $is_active          = $_POST["is_active"];
        $unique_id          = $_POST["unique_id"];
        // $unique_id          = '';
        $entry_date = date('Y-m-d');
        $no = get_grievance_no();

        $student_name = $_POST["student_name"];

$reg_no = $_POST["reg_no"];
$hostel_name = $_POST["hostel_name"];
$grievance_no = $_POST["grievance_no"];

$district     = $_POST["district"];
$taluk     = $_POST["taluk"];
$tahsildar     = $_POST["tahsildar"];
        // $no=1;
        // print_r($no);
// if($_REQUEST["doc_option"] == "1"){
    $allowedExts = array("image");
 $extension = pathinfo($_FILES["test_file"]['name'], PATHINFO_EXTENSION);

   



        $aadhar_file_exp = explode(".",$_FILES["test_file"]['name']);
        // print_r($aadhar_file_exp);

        $aadhar_tem_name =  random_strings(25).".".$aadhar_file_exp[1]; 
        // print_r("gg".$tem_name);
        // print_r($aadhar_tem_name);
                     move_uploaded_file($_FILES["test_file"]["tmp_name"], "../../uploads/grievance_category/" . $aadhar_tem_name);

// }
if (!empty($_FILES["test_file"]['name'])) {
// print_r("hh".$tem_name);
$file_names     = $aadhar_tem_name;
$file_org_names = $_FILES["test_file"]['name'];
}
                             
   

// print_r($file_names);

        $update_where       = "";


        if($_FILES["test_file"]['name'] != ''){

        $columns            = [
             "grievance_description"       => $grievance_description,
            "grievance_cate"       => $grievance_cate,
            "entry_date"       => $entry_date,
            "grievance_id"       => $no,
            "file_name"       => $file_names,
            "file_org_name"       => $file_org_names,
            "is_active"           => $is_active,
            "student_name" => $student_name,
            "reg_no" => $reg_no,
            "hostel_name" => $hostel_name,
            "grievance_no" => $grievance_no,
            "district"     => $district,
            "taluk"     => $taluk,
            "tahsildar"     => $tahsildar,
            // "status" =>0,
            "unique_id"           => unique_id($prefix)
        ];
    }else{
        $columns            = [
            "grievance_description"       => $grievance_description,
           "grievance_cate"       => $grievance_cate,
        //    "entry_date"       => $entry_date,
        //    "grievance_id"       => $no,
        //    "file_name"       => $file_names,
        //    "file_org_name"       => $file_org_names,
        //    "is_active"           => $is_active,
        //    "student_name" => $student_name,
        //    "reg_no" => $reg_no,
        //    "hostel_name" => $hostel_name,
        //    "grievance_no" => $grievance_no,
        //    "district"     => $district,
        //    "taluk"     => $taluk,
        //    "tahsildar"     => $tahsildar,
        //    "unique_id"           => unique_id($prefix)
       ];
    }
            // check already Exist Or not
        $table_details      = [
            $table,
            [
                "COUNT(unique_id) AS count"
            ]
        ];
        $select_where       = 'grievance_cate != "'.$grievance_cate.'"  AND is_delete = 0  ';

        // When Update Check without current id district_name = "'.$district_name.'"  AND
        if ($unique_id) {
            $select_where   .= ' AND unique_id !="'.$unique_id.'" ';
        }

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
        case 'sub_add_update':

            $grievance_id      = $_POST["grievance_cate_id"];
            $grievance_cate      = $_POST["grievance_cate"];
            $grievance_description      = $_POST["grievance_description"];


            $status      = $_POST["status"];
            $reason      = $_POST["reason"];
            $is_active          = $_POST["is_active"];
            $unique_id          = $_POST["unique_id"];
            // $unique_id          = '';
            $entry_date = date('Y-m-d');
            // $no = get_grievance_no();
    
            $student_name = $_POST["student_name"];
    
    $reg_no = $_POST["reg_no"];
    $hostel_name = $_POST["hostel_name"];
    $grievance_no = $_POST["grievance_no"];
    
    $district     = $_POST["district"];
    $taluk     = $_POST["taluk"];
    $tahsildar     = $_POST["tahsildar"];
            // $no=1;
            // print_r($no);
    // // if($_REQUEST["doc_option"] == "1"){
    //     $allowedExts = array("image");
    //  $extension = pathinfo($_FILES["test_file"]['name'], PATHINFO_EXTENSION);
    
       
    
    
    
    //         $aadhar_file_exp = explode(".",$_FILES["test_file"]['name']);
    //         // print_r($aadhar_file_exp);
    
    //         $aadhar_tem_name =  random_strings(25).".".$aadhar_file_exp[1]; 
    //         // print_r("gg".$tem_name);
    //         // print_r($aadhar_tem_name);
    //                      move_uploaded_file($_FILES["test_file"]["tmp_name"], "../../uploads/grievance_category/" . $aadhar_tem_name);
    
    // // }
    // if (!empty($_FILES["test_file"]['name'])) {
    // // print_r("hh".$tem_name);
    // $file_names     = $aadhar_tem_name;
    // $file_org_names = $_FILES["test_file"]['name'];
    // }
                                 
       
    
    // print_r($file_names);
    
            $update_where       = "";
    
    
            // if($_FILES["test_file"]['name'] != ''){
    $table1 = "dadwo_approval";
            $columns            = [
                 "grievance_description"       => $grievance_description,
                "grievance_cate"       => $grievance_cate,
                "entry_date"       => $entry_date,
                "grievance_id"       => $grievance_id,
                "dadwo_status"       => $status,
                "reason"       => $reason,
                "is_active"           => 1,
                "student_name" => $student_name,
                "reg_no" => $reg_no,
                "hostel_name" => $hostel_name,
                "grievance_no" => $grievance_no,
                "district"     => $district,
                "taluk"     => $taluk,
                "tahsildar"     => $tahsildar,
                // "status" =>0,
                "unique_id"           => unique_id($prefix)
            ];
        
                // check already Exist Or not
            $table_details      = [
                $table1,
                [
                    "COUNT(unique_id) AS count"
                ]
            ];
            $select_where       = 'dadwo_status = "'.$status.'" AND grievance_id="'.$grievance_id.'"  AND is_delete = 0 ';
    
            // When Update Check without current id district_name = "'.$district_name.'"  AND
            if ($unique_id) {
                $select_where   .= ' AND unique_id !="'.$unique_id.'" ';
            }
    
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
            if ($data[0]["count"]) {
                $msg        = "already";
            } else if ($data[0]["count"] == 0) {
                // Update Begins
                // if($unique_id) {
    
                //     unset($columns['unique_id']);
                 $status      = $_POST["status"];
            // if()
            
            if($status == 0){
                $update_columns = [
                    "status" => 0,
                ];
            }
            if($status == 1){
                $update_columns = [
                    "status" => 1,
                ];
            }
            if($status == 2){
                $update_columns = [
                    "status" => 2,
                ];
            }
                // $update_columns = [
                //     "status" => $status,
                // ];
                // $update_columns        = 'status = "'.$status.'" ';
                $update_where        = 'grievance_id = "'.$grievance_id.'"';
                // print_R($update_where);
                    // $update_where   = [
                    //     "grievance_id"     => $grievance_id
                    // ];
    
                    $action_obj_update     = $pdo->update($table,$update_columns,$update_where);
    
                // Update Ends
                // } else {
    
                    // Insert Begins            
                    $action_obj     = $pdo->insert($table1,$columns);
                    // Insert Ends
    
                // }
    // print_r($action_obj_update);die();
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
            case 'status_sub_datatable':
                // Function Name button prefix
                $btn_edit_delete = "status_sub";
        
                // Fetch Data
                $grievance_cate_id = $_POST['grievance_cate_id'];
                // $screen_unique_id = $_POST['screen_unique_id'];
        
                // DataTable 
                $search = $_POST['search']['value'];
                $length = $_POST['length'];
                $start = $_POST['start'];
                $draw = $_POST['draw'];
                $limit = $length;
        
                $data = [];
        
                if ($length == '-1') {
                    $limit = "";
                }
                $table1 = "dadwo_approval";
                // Query Variables
                $json_array = "";
                $columns = [
                    "@a:=@a+1 s_no",
                    "entry_date",
                    "grievance_id",
                    "dadwo_status",
                    "reason",
                     "unique_id",
                    
                    
                ];
                $table_details = [
                    $table1. " , (SELECT @a:= '" . $start . "') AS a ",
                    $columns
                ];
        
                // $where = [
                //     //"purchase_unique_id"            => $unique_id,
                //     // "screen_unique_id" => $screen_unique_id,
                //     "is_active" => 1,
                //     "is_delete" => 0
                // ];
        
    
                $order_by = "";
        $where = "grievance_id = '$grievance_cate_id' and is_active = '1' and is_delete = '0' ";
        

                $sql_function = "SQL_CALC_FOUND_ROWS";
        
                $result = $pdo->select($table_details, $where);
                // print_r($result);
                $total_records = total_records();
        
                if ($result->status) {
        
                    $res_array = $result->data;
        
                    foreach ($res_array as $key => $value) {
                        if($value['dadwo_status'] == 0){
                            $value['dadwo_status'] = 'Pending';
                        }
                        if($value['dadwo_status'] == 1){
                            $value['dadwo_status'] = 'Processing';
                        }
                        if($value['dadwo_status'] == 2){
                            $value['dadwo_status'] = 'Complete';
                        }
                        
                        
                       
                        $btn_delete         = btn_delete($btn_edit_delete, $value['unique_id']);
                        $value['unique_id']     = $btn_delete; 
        
        
        
                        $data[] = array_values($value);
                    }
        
                    $json_array = [
                        "draw" => intval($draw),
                        "recordsTotal" => intval($total_records),
                        "recordsFiltered" => intval($total_records),
                        "data" => $data,
                        "testing" => $result->sql,
        
                    ];
                } else {
                    print_r($result);
                }
        
                echo json_encode($json_array);
        
                break;
                case 'status_sub_delete':
                    $btn_edit_delete = "status_sub";
                    $unique_id  = $_POST['unique_id'];
                    // $screen_unique_id  = $_POST['screen_unique_id'];
            
                    $columns            = [
                        "is_delete"   => 1,
                    ];
            
                    $update_where   = [
                        "unique_id"     => $unique_id
                    ];
            
                    $action_obj     = $pdo->update($table1, $columns, $update_where);
            
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
            "grievance_id",
            "grievance_cate",
            "grievance_description",
            // "file_name",
            "status",

            // "is_active",
            "unique_id",
            "status as id",
            "student_name",
            "reg_no",
            "hostel_name as hostel_name_id",
            "(select hostel_name from hostel_name where unique_id = $table.hostel_name) as hostel_name",
            // "(select hostel_id from hostel_name where unique_id = $table.hostel_id) as hostel_id",
            "hostel_id as hostel_id",
            "hostel_id as hostel_id_val",
            "grievance_no",
            "district as district_id_val",
            "(select district_name from district_name where unique_id = $table.district) as district",
            "taluk as taluk_id_val",
            "(select taluk_name from taluk_creation where unique_id = $table.taluk) as taluk",
            "tahsildar",
            "'' as reason",
        ];
        $table_details  = [
            $table." , (SELECT @a:= ".$start.") AS a ",
            $columns
        ];
        $where          = "is_delete = 0";
        // if($_POST['from_date'] == ''){
        //     $from_date = date('Y-m-d');
        // }else{
        //     $from_date = $_POST['from_date'];
        // }
        // if($_POST['to_date'] == ''){
        //     $to_date = date('Y-m-d');
        // }else{
        //     $to_date = $_POST['to_date'];
        // }
        // if ($from_date || $to_date != '') {

        //     $where .= " AND DATE_FORMAT(entry_date, '%Y-%m-%d') <= '" . $from_date . "' AND DATE_FORMAT(entry_date, '%Y-%m-%d') >= '" . $to_date . "'  ";

        // }
        if($_POST['status'] !=''){
            $where .= " AND status= '" . $_POST['status'] . "'";

        }else{
            $where .= " AND status= '0'";
        }
       
        if($_POST['district_name'] !=''){
            $where .= " AND district= '" . $_POST['district_name'] . "'";

        }
        if($_POST['taluk_name'] !=''){
            $where .= " AND taluk = '" . $_POST['taluk_name'] . "'";

        }
        if($_POST['hostel_name'] !=''){
            $where .= " AND hostel_name= '" . $_POST['hostel_name'] . "'";

        }
        // if($_POST['academic_year'] !=''){
        //     $where .= " AND DATE_FORMAT(entry_date, '%Y')= '" . $_POST['academic_year'] . "'";

        // }
        
        $order_by       = "";

        if ($_POST['search']['value']) {
           $where .= " AND grievance_id LIKE '".mysql_like($_POST['search']['value'])."' ";
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
        // print_r($result);
        $total_records  = total_records();

        if ($result->status) {

            $res_array      = $result->data;

            foreach ($res_array as $key => $value) {
                $sno = $sno + 1;
$reason = get_reason_name($value['grievance_id'],$value['id']);
 $value['reason'] = $reason[0]['reason'];
                // $value['entry_date'] = disdate($value['entry_date']);
                $d = explode(" ", $reason[0]['entry_date']);
                $value['entry_date'] = disdate($d[0]).'<br>'.disdate($d[1]);
                if($value['grievance_cate'] == '0'){
                    $value['grievance_cate'] = 'Test';
                }else{
                    $value['grievance_cate'] = 'Demo';
                }

                if($value['status'] == '0'){
                    $value['status'] = '<p style="color:red">Pending</p>';
                }
                if($value['status'] == '1'){
                    $value['status'] = '<p style="color:orange">Processing</p>';
                }
                if($value['status'] == '2'){
                    $value['status'] = '<p style="color:green">Completed</p>';
                }


                
                $value['is_active']     = is_active_show($value['is_active']);

                // $btn_update         = btn_update($folder_name,$value['unique_id']);
                // $btn_delete         = btn_delete($folder_name,$value['unique_id']);

                if ( $value['unique_id'] == "5f97fc3257f2525529") {
                    $btn_update         = "";
                    $btn_delete         = "";
                } 
$id = $value['unique_id'];
// echo $value['id'];
if($value['id'] == '0'){

    $value['unique_id'] = '<a href="index.php?file=grievance_list/model&unique_id='.$id.'"><img src="../adhmDADWO/uploads/pending3.png"s width="35px"></a>'.' '.'<img src="../adhmDADWO/uploads/view1.png"  height="30px" width="30px" data-bs-toggle="modal" data-bs-target=".bs-example-modal-x2' . $sno . '" type="button">
    <div class="modal fade bs-example-modal-x2' .  $sno . '" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center" id="myExtraLargeModalLabel">Approvel List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div class="row mt-2" >
                <div class="col-md-4">
                <label for="student_id" class="form-label">Grievance No</label>
                </div>
                <div class="col-md-8">
                <label for="">'.$value['grievance_id'].'</label>
                <input type="hidden" id="student_id" name="student_id" class="form-control" value="'.$value['grievance_id'].'" required></input>
                </div>
                </div>
                <div class="row mt-2" >
                <div class="col-md-4">
                <label for="student_id" class="form-label">Student Id: </label>
                </div>
                <div class="col-md-8">
                <label for="">'.$value['reg_no'].'</label>
                <input type="hidden" id="student_id" name="student_id" class="form-control" value="'.$value['student_id'].'" required></input>
                <input type="hidden" id="unique_id" name="unique_id" class="form-control" value="'.$value['unique_id'].'" required></input>
                <input type="hidden" id="warden_name" name="warden_name" class="form-control" value="'.$userid.'" required></input>
                 
                </div>
                </div>
                <div class="row mt-2" >
                <div class="col-md-4">
                <label for="student_id" class="form-label">Student Name: </label>
                    </div>
                <div class="col-md-8">
                <label for="" value="">'.$value['student_name'].'</label>
                    <input type="hidden" id="student_name" name="student_name" class="form-control" value="'.$value['student_id'].'" required></input>
                   </div>
                </div>
                <div class="row mt-2" >
                <div class="col-md-4">
                <label for="example-select" class="form-label">Entry Date</label>
                </div>
                <div class="col-md-8">
                <label for="">'.$value['entry_date'].'</label>
                <input type="hidden" id="from_date" name="from_date" class="form-control" value="'.$value['entry_date'].'" required></input>
                            </div>
                </div>
                <div class="row mt-2" >
                <div class="col-md-4">
                <label for="example-select" class="form-label">Hostel Name</label>
                </div>
                <div class="col-md-8">
                <label for="">'.$value['hostel_name'].'</label>
                <input type="hidden" id="to_date" name="to_date" class="form-control" value="'.$value['hostel_name'].'" required></input>
                </div>
                </div><div class="row mt-2" >
                <div class="col-md-4">
                <label for="example-select" class="form-label">Grievance No</label>
                </div>
                <div class="col-md-8">
                <label for="">'.$value['grievance_no'].'</label>
                <input type="hidden" id="no_of_days" name="no_of_days" class="form-control" value="'.$value['grievance_no'].'"></input>
                </div>
                </div>
                <div class="row mt-2" >
                <div class="col-md-4">
                <label for="example-select" class="form-label">District</label>
                </div>
                <div class="col-md-8">
                <label for="">'.$value['district'].'</label>
                <input type="hidden" id="no_of_days" name="no_of_days" class="form-control" value="'.$value['district'].'"></input>
                </div>
                </div>
                <div class="row mt-2" >
                <div class="col-md-4">
                <label for="example-select" class="form-label">Taluk</label>
                </div>
                <div class="col-md-8">
                <label for="">'.$value['taluk'].'</label>
                <input type="hidden" id="no_of_days" name="no_of_days" class="form-control" value="'.$value['taluk'].'"></input>
                </div>
                </div>
                
                <div class="row mt-2" >
                <div class="col-md-4">
                <label for="example-select" class="form-label">Tahslidar</label>
                   </div>
                <div class="col-md-8">
                <label for="">'.$value['tahsildar'].'</label>
                <input type="hidden" id="reason" name="reason" class="form-control" value=""></input>
                </div>
                </div>
                <div class="row mt-2" >
                <div class="col-md-4">
                <label for="example-select" class="form-label">Reason</label>
                   </div>
                <div class="col-md-8">
                <label for="">'.$value['reason'].'</label>
                <input type="hidden" id="reason" name="reason" class="form-control" value=""></input>
                </div>
                </div>
                <div class="row mt-2" >
                <div class="col-md-4">
                <label class="form-label me-2" for="example-select">Status:</label>
                </div>
                <div class="col-md-8">
                
                
                <label for="" style="color:red">'.$value['status'].'</label>
                <input type="hidden" id="reason" name="reason" class="form-control" value=""></input>
            
             
                </div>
                </div>
                </div><div class="row mt-2" id="description_div" style="display:none;" >
                <div class="col-md-4" >
                <label for="example-select" class="form-label">Reject Reason:</label>
                  </div>
                <div class="col-md-8" >
                <textarea type="text" id="description" name="description" class="form-control" value=""></textarea>
                </div>
                </div>
                  <div class="modal-footer">
                <a href="javascript:void(0);" class="btn btn-link link-success fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
                <button type="button" class="btn btn-primary " onclick="approval_create()">Save</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->';
}
if($value['id'] == '1'){
    $value['unique_id'] = '<a href="index.php?file=grievance_list/model&unique_id='.$id.'"><img src="../adhmDADWO/uploads/processing.png" width="35px"></a>'.' '.'<img src="../adhmDADWO/uploads/view1.png"  height="30px" width="30px" data-bs-toggle="modal" data-bs-target=".bs-example-modal-x2' . $sno . '" type="button">
    <div class="modal fade bs-example-modal-x2' .  $sno . '" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center" id="myExtraLargeModalLabel">Approvel List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mt-2" >
                <div class="col-md-4">
                <label for="student_id" class="form-label">Grievance No</label>
                </div>
                <div class="col-md-8">
                <label for="">'.$value['grievance_id'].'</label>
                <input type="hidden" id="student_id" name="student_id" class="form-control" value="'.$value['grievance_id'].'" required></input>
                </div>
                </div>
                <div class="col-md-4">
                <label for="student_id" class="form-label">Student Id: </label>
                </div>
                <div class="col-md-8">
                <label for="">'.$value['reg_no'].'</label>
                <input type="hidden" id="student_id" name="student_id" class="form-control" value="'.$value['student_id'].'" required></input>
                <input type="hidden" id="unique_id" name="unique_id" class="form-control" value="'.$value['unique_id'].'" required></input>
                <input type="hidden" id="warden_name" name="warden_name" class="form-control" value="'.$userid.'" required></input>
                 
                </div>
                </div>
                <div class="row mt-2" >
                <div class="col-md-4">
                <label for="student_id" class="form-label">Student Name: </label>
                    </div>
                <div class="col-md-8">
                <label for="" value="">'.$value['student_name'].'</label>
                    <input type="hidden" id="student_name" name="student_name" class="form-control" value="'.$value['student_id'].'" required></input>
                   </div>
                </div>
                <div class="row mt-2" >
                <div class="col-md-4">
                <label for="example-select" class="form-label">Entry Date</label>
                </div>
                <div class="col-md-8">
                <label for="">'.$value['entry_date'].'</label>
                <input type="hidden" id="from_date" name="from_date" class="form-control" value="'.$value['entry_date'].'" required></input>
                            </div>
                </div>
                <div class="row mt-2" >
                <div class="col-md-4">
                <label for="example-select" class="form-label">Hostel Name</label>
                </div>
                <div class="col-md-8">
                <label for="">'.$value['hostel_name'].'</label>
                <input type="hidden" id="to_date" name="to_date" class="form-control" value="'.$value['hostel_name'].'" required></input>
                </div>
                </div><div class="row mt-2" >
                <div class="col-md-4">
                <label for="example-select" class="form-label">Grievance No</label>
                </div>
                <div class="col-md-8">
                <label for="">'.$value['grievance_no'].'</label>
                <input type="hidden" id="no_of_days" name="no_of_days" class="form-control" value="'.$value['grievance_no'].'"></input>
                </div>
                </div>
                <div class="row mt-2" >
                <div class="col-md-4">
                <label for="example-select" class="form-label">District</label>
                </div>
                <div class="col-md-8">
                <label for="">'.$value['district'].'</label>
                <input type="hidden" id="no_of_days" name="no_of_days" class="form-control" value="'.$value['district'].'"></input>
                </div>
                </div>
                <div class="row mt-2" >
                <div class="col-md-4">
                <label for="example-select" class="form-label">Taluk</label>
                </div>
                <div class="col-md-8">
                <label for="">'.$value['taluk'].'</label>
                <input type="hidden" id="no_of_days" name="no_of_days" class="form-control" value="'.$value['taluk'].'"></input>
                </div>
                </div>
                
                <div class="row mt-2" >
                <div class="col-md-4">
                <label for="example-select" class="form-label">Tahslidar</label>
                   </div>
                <div class="col-md-8">
                <label for="">'.$value['tahsildar'].'</label>
                <input type="hidden" id="reason" name="reason" class="form-control" value=""></input>
                </div>
                </div>
                <div class="row mt-2" >
                <div class="col-md-4">
                <label for="example-select" class="form-label">Reason</label>
                   </div>
                <div class="col-md-8">
                <label for="">'.$value['reason'].'</label>
                <input type="hidden" id="reason" name="reason" class="form-control" value=""></input>
                </div>
                </div>
                <div class="row mt-2" >
                <div class="col-md-4">
                <label class="form-label me-2" for="example-select">Status:</label>
                </div>
                <div class="col-md-8">
                
                <label for="" style="color:orange">'.$value['status'].'</label>
                <input type="hidden" id="reason" name="reason" class="form-control" value=""></input>
           </div>
                </div>
                </div><div class="row mt-2" id="description_div" style="display:none;" >
                <div class="col-md-4" >
                <label for="example-select" class="form-label">Reject Reason:</label>
                  </div>
                <div class="col-md-8" >
                <textarea type="text" id="description" name="description" class="form-control" value=""></textarea>
                </div>
                </div>
                  <div class="modal-footer">
                <a href="javascript:void(0);" class="btn btn-link link-success fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
                <button type="button" class="btn btn-primary " onclick="approval_create()">Save</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->';
}
if($value['id'] == '2'){
    $value['unique_id'] = '<a href="index.php?file=grievance_list/model&unique_id='.$id.'"><img src="../adhmDADWO/uploads/completed.png" width="35px"></a>'.' '.'<img src="../adhmDADWO/uploads/view1.png"  height="30px" width="30px" data-bs-toggle="modal" data-bs-target=".bs-example-modal-x2' . $sno . '" type="button">
    <div class="modal fade bs-example-modal-x2' .  $sno . '" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center" id="myExtraLargeModalLabel">Approvel List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mt-2" >
                <div class="col-md-4">
                <label for="student_id" class="form-label">Grievance No</label>
                </div>
                <div class="col-md-8">
                <label for="">'.$value['grievance_id'].'</label>
                <input type="hidden" id="student_id" name="student_id" class="form-control" value="'.$value['grievance_id'].'" required></input>
                </div>
                
                <div class="col-md-4">
                <label for="student_id" class="form-label">Student Id: </label>
                </div>
                <div class="col-md-8">
                <label for="">'.$value['reg_no'].'</label>
                <input type="hidden" id="student_id" name="student_id" class="form-control" value="'.$value['student_id'].'" required></input>
                <input type="hidden" id="unique_id" name="unique_id" class="form-control" value="'.$value['unique_id'].'" required></input>
                <input type="hidden" id="warden_name" name="warden_name" class="form-control" value="'.$userid.'" required></input>
                 
                </div>
                </div>
                <div class="row mt-2" >
                <div class="col-md-4">
                <label for="student_id" class="form-label">Student Name: </label>
                    </div>
                <div class="col-md-8">
                <label for="" value="">'.$value['student_name'].'</label>
                    <input type="hidden" id="student_name" name="student_name" class="form-control" value="'.$value['student_id'].'" required></input>
                   </div>
                </div>
                <div class="row mt-2" >
                <div class="col-md-4">
                <label for="example-select" class="form-label">Entry Date</label>
                </div>
                <div class="col-md-8">
                <label for="">'.$value['entry_date'].'</label>
                <input type="hidden" id="from_date" name="from_date" class="form-control" value="'.$value['entry_date'].'" required></input>
                            </div>
                </div>
                <div class="row mt-2" >
                <div class="col-md-4">
                <label for="example-select" class="form-label">Hostel Name</label>
                </div>
                <div class="col-md-8">
                <label for="">'.$value['hostel_name'].'</label>
                <input type="hidden" id="to_date" name="to_date" class="form-control" value="'.$value['hostel_name'].'" required></input>
                </div>
                </div><div class="row mt-2" >
                <div class="col-md-4">
                <label for="example-select" class="form-label">Grievance No</label>
                </div>
                <div class="col-md-8">
                <label for="">'.$value['grievance_no'].'</label>
                <input type="hidden" id="no_of_days" name="no_of_days" class="form-control" value="'.$value['grievance_no'].'"></input>
                </div>
                </div>
                <div class="row mt-2" >
                <div class="col-md-4">
                <label for="example-select" class="form-label">District</label>
                </div>
                <div class="col-md-8">
                <label for="">'.$value['district'].'</label>
                <input type="hidden" id="no_of_days" name="no_of_days" class="form-control" value="'.$value['district'].'"></input>
                </div>
                </div>
                <div class="row mt-2" >
                <div class="col-md-4">
                <label for="example-select" class="form-label">Taluk</label>
                </div>
                <div class="col-md-8">
                <label for="">'.$value['taluk'].'</label>
                <input type="hidden" id="no_of_days" name="no_of_days" class="form-control" value="'.$value['taluk'].'"></input>
                </div>
                </div>
                
                <div class="row mt-2" >
                <div class="col-md-4">
                <label for="example-select" class="form-label">Tahslidar</label>
                   </div>
                <div class="col-md-8">
                <label for="">'.$value['tahsildar'].'</label>
                <input type="hidden" id="reason" name="reason" class="form-control" value=""></input>
                </div>
                </div>
                <div class="row mt-2" >
                <div class="col-md-4">
                <label for="example-select" class="form-label">Reason</label>
                   </div>
                <div class="col-md-8">
                <label for="">'.$value['reason'].'</label>
                <input type="hidden" id="reason" name="reason" class="form-control" value=""></input>
                </div>
                </div>
                <div class="row mt-2" >
                <div class="col-md-4">
                <label class="form-label me-2" for="example-select">Status:</label>
                </div>
                <div class="col-md-8"><label for="" style="color:green">'.$value['status'].'</label>
                <input type="hidden" id="reason" name="reason" class="form-control" value=""></input></div>
                </div>
                </div><div class="row mt-2" id="description_div" style="display:none;" >
                <div class="col-md-4" >
                <label for="example-select" class="form-label">Reject Reason:</label>
                  </div>
                <div class="col-md-8" >
                <textarea type="text" id="description" name="description" class="form-control" value=""></textarea>
                </div>
                </div>
                  <div class="modal-footer">
                <a href="javascript:void(0);" class="btn btn-link link-success fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
                <button type="button" class="btn btn-primary " onclick="approval_create()">Save</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->';
}
                
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
        case 'district_name':

            $district_name = $_POST['district_name'];


            $district_name_options = taluk_name(' ',$district_name);

            $taluk_name_options = select_option($district_name_options,"Select Taluk");
            // print_r($taluk_name_options);die();
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
    
        
?>
<?php

?>