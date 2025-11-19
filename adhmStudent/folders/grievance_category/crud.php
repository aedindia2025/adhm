<?php

// Get folder Name From Currnent Url
$folder_name = explode('/', $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = 'grievance_category';

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action = $_POST['action'];

// $district_name      = "";
$test_file = '';
$is_active = '';
$unique_id = '';
$prefix = '';

$data = '';
$msg = '';
$error = '';
$status = '';
$test = ''; // For Developer Testing Purpose
// For Developer Testing Purpose
function validateCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// print_r('hi');die();

switch ($action) {
    case 'createupdate':
        $token = $_POST['csrf_token'];
        if (!validateCSRFToken($token)) {
            exit('CSRF validation failed.');
        }

        $vali_student_name = filter_input(INPUT_POST, 'student_name', FILTER_SANITIZE_STRING);
        $vali_gr_no = filter_input(INPUT_POST, 'gr_no', FILTER_SANITIZE_STRING);
        $vali_std_reg_no = filter_input(INPUT_POST, 'std_reg_no', FILTER_SANITIZE_STRING);
        $vali_district_name = filter_input(INPUT_POST, 'district_name', FILTER_SANITIZE_STRING);
        $vali_taluk_name = filter_input(INPUT_POST, 'taluk_name', FILTER_SANITIZE_STRING);
        $vali_hostel_name = filter_input(INPUT_POST, 'hostel_name', FILTER_SANITIZE_STRING);
        $vali_hostel_id = filter_input(INPUT_POST, 'hostel_id', FILTER_SANITIZE_STRING);
        $vali_tahsildar_name = filter_input(INPUT_POST, 'tahsildar_name', FILTER_SANITIZE_STRING);
        $vali_grievance_category = filter_input(INPUT_POST, 'grievance_category', FILTER_SANITIZE_STRING);
        $vali_description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
        $vali_district_id = filter_input(INPUT_POST, 'district_id', FILTER_SANITIZE_STRING);
        $vali_taluk_id = filter_input(INPUT_POST, 'taluk_id', FILTER_SANITIZE_STRING);
        $vali_hostel_main_id = filter_input(INPUT_POST, 'hostel_main_id', FILTER_SANITIZE_STRING);

        if (!$vali_student_name || !$vali_gr_no || !$vali_std_reg_no || !$vali_district_name || !$vali_taluk_name || !$vali_hostel_name || !$vali_hostel_id || !$vali_grievance_category || !$vali_description || !$vali_district_id || !$vali_taluk_id || !$vali_hostel_main_id) {
            $msg = 'form_alert';
        } else {
            $unique_id = sanitizeInput($_POST['unique_id']);
            $student_name = sanitizeInput($_POST['student_name']);
            $gr_no = sanitizeInput($_POST['gr_no']);
            $std_reg_no = sanitizeInput($_POST['std_reg_no']);
            $district_name = sanitizeInput($_POST['district_name']);
            $taluk_name = sanitizeInput($_POST['taluk_name']);
            $hostel_name = sanitizeInput($_POST['hostel_name']);
            $hostel_id = sanitizeInput($_POST['hostel_id']);
            $tahsildar_name = sanitizeInput($_POST['tahsildar_name']);
            $grievance_category = sanitizeInput($_POST['grievance_category']);
            $description = sanitizeInput($_POST['description']);
            $district_id = sanitizeInput($_POST['district_id']);
            $taluk_id = sanitizeInput($_POST['taluk_id']);
            $hostel_main_id = sanitizeInput($_POST['hostel_main_id']);

            $update_where = '';
            
            $allowedExts = array('pdf', 'jpg', 'jpeg', 'png', 'xlsx', 'xls', 'docx');
            $extension = pathinfo($_FILES["test_file"]['name'], PATHINFO_EXTENSION);
if($_FILES["test_file"]['name']){
            if (!in_array($extension, $allowedExts)) {
                die('File type not allowed.');
            }
}

            $file_exp = explode('.', $_FILES['test_file']['name']);

            $tem_name = random_strings(25).'.'.$file_exp[1];

            move_uploaded_file($_FILES['test_file']['tmp_name'], '../../uploads/grievance_category/'.$tem_name);
            $file_names = $tem_name;
            $file_org_names = $_FILES['test_file']['name'];

            $columns = [
                'student_name' => $student_name,
                'grievance_no' => $gr_no,
                'std_reg_no' => $std_reg_no,
                'district' => $district_name,
                'taluk' => $taluk_name,
                'hostel_name' => $hostel_name,
                'hostel_id' => $hostel_id,
                'tahsildar' => $tahsildar_name,
                'grievance_cate' => $grievance_category,
                'grievance_description' => $description,
                'district_id' => $district_id,
                'taluk_id' => $taluk_id,
                'hostel_main_id' => $hostel_main_id,
                'file_name' => $file_names,
                'file_org_name' => $file_org_names,
                'unique_id' => $unique_id ? $unique_id : unique_id($prefix),
            ];

            if ($unique_id) {
                $sql = "UPDATE $table SET student_name=?, grievance_no=?, std_reg_no=?, district=?, taluk=?, hostel_name=?, hostel_id=?, tahsildar=?, grievance_cate=?, grievance_description=?, district_id=?, taluk_id=?, hostel_main_id=?, file_name=?, file_org_name=? WHERE unique_id=?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param('ssssssssssssssss', $columns['student_name'], $columns['grievance_no'], $columns['std_reg_no'], $columns['district'], $columns['taluk'], $columns['hostel_name'], $columns['hostel_id'], $columns['tahsildar'], $columns['grievance_cate'], $columns['grievance_description'], $columns['district_id'], $columns['taluk_id'], $columns['hostel_main_id'], $columns['file_name'], $columns['file_org_name'], $columns['unique_id']);
            } else {
                $sql = "INSERT INTO $table (student_name, grievance_no, std_reg_no, district, taluk, hostel_name, hostel_id, tahsildar, grievance_cate, grievance_description, district_id, taluk_id, hostel_main_id, file_name, file_org_name, unique_id, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param('sssssssssssssssss', $columns['student_name'], $columns['grievance_no'], $columns['std_reg_no'], $columns['district'], $columns['taluk'], $columns['hostel_name'], $columns['hostel_id'], $columns['tahsildar'], $columns['grievance_cate'], $columns['grievance_description'], $columns['district_id'], $columns['taluk_id'], $columns['hostel_main_id'], $columns['file_name'], $columns['file_org_name'], $columns['unique_id'], $_SESSION['sess_user_id']);
            }

            if ($stmt->execute()) {
                $status = true;
                $msg = $unique_id ? 'update' : 'create';
            } else {
                $status = false;
                $msg = 'error';
                $error = $stmt->error;
            }

            $stmt->close();
            $mysqli->close();
        }

        $json_array = [
            'status' => $status,
            'data' => $data,
            'error' => $error,
            'msg' => $msg,
        ];

        echo json_encode($json_array);
        break;

        // case 'createupdate':

        //     $grievance_id           = $_POST["grievance_id"];
        //     $grievance_cate         = $_POST["grievance_category"];
        //     $grievance_description      = $_POST["description"];
        //     // $is_active          = $_POST["is_active"];
        //     $unique_id          = $_POST["unique_id"];
        //     // $no = get_grievance_no();
        //     $student_name = $_POST["student_name"];
        //     $std_reg_no         = $_POST["std_reg_no"];
        //     $hostel_name    = $_POST["hostel_name"];
        //     $hostel_id      = $_POST["hostel_id"];
        //     $grievance_no    = $_POST["grievance_no"];
        //     $district       = $_POST["district_name"];
        //     $taluk          = $_POST["taluk_name"];
        //     $tahsildar      = $_POST["tahsildar_name"];

        // $no=1;

        // if($_REQUEST["doc_option"] == "1"){
        //     $allowedExts = array("image");
        //  $extension = pathinfo($_FILES["test_file"]['name'], PATHINFO_EXTENSION);

        //  if($extension === 'pdf' || $extension === 'jpg' || $extension === 'png' || $extension === 'jpeg') {
        //     // echo 'test';
        //         $aadhar_file_exp = explode(".",$_FILES["test_file"]['name']);
        //         // print_r($aadhar_file_)exp;
        //         echo  $aadhar_file_exp;

        //         $aadhar_temp_name =  random_strings(25).".".$aadhar_file_exp[1];

        //        echo  $aadhar_temp_name;
        //         move_uploaded_file($_FILES["test_file"]["tmp_name"], "../../uploads/grievance_category/" . $aadhar_temp_name);

        //                     }
        // $allowedExts = array("pdf", "jpg", "jpeg", "png");
        // $extension = strtolower(pathinfo($_FILES["test_file"]['name'], PATHINFO_EXTENSION));

        // if (in_array($extension, $allowedExts)) {
        //     $aadhar_file_exp = explode(".", $_FILES["test_file"]['name']);
        //     // Access individual elements of the array
        //     echo $aadhar_file_exp[0]; // File name without extension
        //     echo "<br>";
        //     echo $aadhar_file_exp[1]; // File extension

        //     $aadhar_temp_name = random_strings(25) . "." . $aadhar_file_exp;

        //     echo $aadhar_temp_name;

        //     move_uploaded_file($_FILES["test_file"]["tmp_name"], "../../uploads/grievance_category/" . $aadhar_temp_name);
        // } else {
        //     echo "Invalid file extension!";
        // }

        // if (!empty($_FILES["test_file"]['name'])) {

        // // print_r("hh".$tem_name);
        // $file_names     = $aadhar_temp_name;
        // $file_org_names = $_FILES["test_file"]['name'];
        // }

        //         $update_where       = "";

        //         // if($_FILES["test_file"]['name'] != '' ){

        //         $columns            = [

        //             // "grievance_id"       => $no,

        //             "std_reg_no"    => $std_reg_no,
        //             "student_name"  => $student_name,

        //             "hostel_name" => $hostel_name,
        //             "hostel_id" => $hostel_id,
        //             "grievance_no" => $grievance_no,
        //             "district"     => $district,
        //             "taluk"     => $taluk,
        //             "tahsildar"     => $tahsildar,
        //             "grievance_description" => $grievance_description,
        //             "grievance_cate"       => $grievance_cate,
        //             "entry_date"       => $entry_date,
        //             "file_name"       => $file_names,
        //             "file_org_name"       => $file_org_names,
        //             "is_active"           => $is_active,

        //             // "status" =>0,
        //             "unique_id"           => unique_id($prefix)
        //         ];

        // }else{
        //     $columns            = [
        //         "grievance_id"       => $no,
        //         "student_name" => $student_name,
        //         "std_reg_no" => $reg_no,
        //         "hostel_name" => $hostel_name,
        //         "hostel_id" => $hostel_id,
        //         "grievance_no" => $grievance_no,
        //         "district"     => $district,
        //         "taluk"     => $taluk,
        //         "tahsildar"     => $tahsildar,
        //         "grievance_description"       => $grievance_description,
        //         "grievance_cate"       => $grievance_cate,
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

        // check already Exist Or not
        // $table_details      = [
        //     $table,
        //    $columns
        // ];
        // // $select_where       = 'grievance_cate != "'.$grievance_cate.'"  AND is_delete = 0  ';

        // // When Update Check without current id district_name = "'.$district_name.'"  AND
        // if ($unique_id) {
        //     $select_where   .= ' AND unique_id !="'.$unique_id.'" ';
        // }

        // // $action_obj         = $pdo->select($table_details);

        // if ($action_obj->status) {
        //     $status     = $action_obj->status;
        //     $data       = $action_obj->data;
        //     $error      = "";
        //     $sql        = $action_obj->sql;

        // } else {
        //     $status     = $action_obj->status;
        //     $data       = $action_obj->data;
        //     $error      = $action_obj->error;
        //     $sql        = $action_obj->sql;
        //     $msg        = "error";

        //     // print_r('hi');die();
        // // }
        // // if ($data[0]["count"]) {
        // //     $msg        = "already";
        // // } else if ($data[0]["count"] == 0) {
        //     // Update Begins
        //     if($unique_id) {

        //         unset($columns['unique_id']);

        //         $update_where   = [
        //             "unique_id"     => $unique_id
        //         ];

        //         $action_obj     = $pdo->update($table,$columns,$update_where);

        //     // Update Ends
        //     } else {

        //         // Insert Begins
        //         $action_obj     = $pdo->insert($table,$columns);

        //         print_r($action_obj); die();

        //         // Insert Ends

        //     }

        //     if ($action_obj->status) {
        //         $status     = $action_obj->status;
        //         $data       = $action_obj->data;
        //         $error      = "";
        //         $sql        = $action_obj->sql;

        //         if ($unique_id) {
        //             $msg        = "update";
        //         } else {
        //             $msg        = "create";
        //         }
        //     } else {
        //         $status     = $action_obj->status;
        //         $data       = $action_obj->data;
        //         $error      = $action_obj->error;
        //         $sql        = $action_obj->sql;
        //         $msg        = "error";
        //     }
        // }

        // $json_array   = [
        //     "status"    => $status,
        //     "data"      => $data,
        //     "error"     => $error,
        //     "msg"       => $msg,
        //     "sql"       => $sql
        // ];

        // echo json_encode($json_array);

        // break;
    case 'delete':
        // Validate input


        $unique_id = isset($_POST['unique_id']) ? $_POST['unique_id'] : '';

        if (!$unique_id) {
            $json_array = [
                'status' => false,
                'msg' => 'missing_unique_id',
            ];
            echo json_encode($json_array);
            break;
        }
        $is_delete = '1';
        // Prepare and execute SQL statement
        $sql = "UPDATE $table SET is_delete = ? WHERE unique_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('ss', $is_delete, $unique_id);

        // Execute statement and handle result
        if ($stmt->execute()) {
            $status = true;
            $msg = 'success_delete';
        } else {
            $status = false;
            $msg = 'error';
            $error = $stmt->error;
        }

        // Prepare JSON response
        $json_array = [
            'status' => $status,
            'msg' => $msg,
            'error' => $error,
        ];

        echo json_encode($json_array);
        break;

    default:
        $json_array = [
            'status' => false,
            'msg' => 'Invalid action',
        ];

        echo json_encode($json_array);
        break;

    case 'datatable':
        // DataTable Variables
        $search = $_POST['search']['value'];
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;

        $data = [];

        if ($length == '-1') {
            $limit = '';
        }

        // Prepare query
        $sql = "SELECT SQL_CALC_FOUND_ROWS 
                        @a:=@a+1 s_no, 
                        date_format(updated,'%d-%m-%Y') as created,
                        grievance_no,
                        (SELECT grievance_name FROM grievance_creation WHERE grievance_creation.unique_id = grievance_category.grievance_cate) AS grievance_cate,
                        grievance_description,
                        unique_id
                    FROM 
                        $table, (SELECT @a:= ?) AS a 
                    WHERE 
                        is_delete = ? AND user_id = ?";

        // Additional conditions
        $is_delete = '0';
        $params = [$start, $is_delete, $_SESSION['sess_user_id']];

        if ($_POST['status'] != '') {
            $sql .= ' AND status = ?';
            $params[] = $_POST['status'];
        }

        // Limit and offset
        $sql .= ' LIMIT ?, ?';
        $params[] = (int) $start;
        $params[] = (int) $limit;

        // Prepare statement
        $stmt = $mysqli->prepare($sql);

        // Bind parameters
        $types = str_repeat('s', count($params)); // Assuming all parameters are strings
        $stmt->bind_param($types, ...$params);

        // Execute statement
        $stmt->execute();

        // Get result
        $result = $stmt->get_result();

        // Initialize array for JSON response
        $json_array = [
            'draw' => intval($draw),
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => [],
        ];

        // Fetch results and construct data array
        while ($row = $result->fetch_assoc()) {
            $btn_update = btn_update($folder_name, $row['unique_id']);
            $eye_button = '<a class="btn btn-action specl2"  href="javascript:grievance_print(\''.$row['unique_id'].'\')"><button type="button" ><i class="fa fa-eye" ></i></button></a>';
            $row['unique_id'] = $btn_update.$eye_button;
            $data[] = array_values($row);
        }

        // Get total records
        $total_records_result = $mysqli->query('SELECT FOUND_ROWS() as total');
        $total_records = $total_records_result->fetch_assoc()['total'];

        // Update JSON array with total records
        $json_array['recordsTotal'] = intval($total_records);
        $json_array['recordsFiltered'] = intval($total_records);
        $json_array['data'] = $data;

        // Output JSON response
        echo json_encode($json_array);

        // Close statement and connection
        $stmt->close();
        $mysqli->close();
        break;
}
