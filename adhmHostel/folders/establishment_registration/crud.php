<?php
// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];
// // Database Country Table Name
$table = "establishment_registration";
// // Include DB file and Common Functions
include '../../config/dbconfig.php';
// // Variables Declaration
$action = $_POST['action'];
$is_active = "";
$unique_id = "";
$prefix = "";
$data = "";
$msg = "";
$error = "";
$status = "";
$test = ""; // For Developer Testing Purpose

function validateCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}


switch ($action) {
    
    case 'createupdate':

        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        $vali_staff_name = filter_input(INPUT_POST, 'staff_name', FILTER_SANITIZE_STRING);
        $vali_ifhrms_id = filter_input(INPUT_POST, 'ifhrms_id', FILTER_SANITIZE_STRING);
        $vali_gender_name = filter_input(INPUT_POST, 'gender_name', FILTER_SANITIZE_STRING);
        $vali_dob = filter_input(INPUT_POST, 'dob', FILTER_SANITIZE_STRING);
        $vali_age = filter_input(INPUT_POST, 'age', FILTER_SANITIZE_STRING);
        $vali_mobile_num = filter_input(INPUT_POST, 'mobile_num', FILTER_SANITIZE_STRING);
        $vali_district_name = filter_input(INPUT_POST, 'district_name', FILTER_SANITIZE_STRING);
       
        $vali_designation = filter_input(INPUT_POST, 'designation', FILTER_SANITIZE_STRING);
        
        $vali_district_office = filter_input(INPUT_POST, 'district_office', FILTER_SANITIZE_STRING);
        $vali_taluk_office = filter_input(INPUT_POST, 'taluk_office', FILTER_SANITIZE_STRING);
        $vali_hostel_office = filter_input(INPUT_POST, 'hostel_office', FILTER_SANITIZE_STRING);
       
        $vali_aadhaar_no = filter_input(INPUT_POST, 'aadhaar_no', FILTER_SANITIZE_STRING);
        $vali_staff_count = filter_input(INPUT_POST, 'staff_count', FILTER_SANITIZE_STRING);

        if (!$vali_staff_name || !$vali_ifhrms_id || !$vali_gender_name || !$vali_dob || !$vali_mobile_num || !$vali_district_name || !$vali_designation || !$vali_district_office || !$vali_taluk_office || !$vali_hostel_office || !$vali_aadhaar_no) {
            $msg = "form_alert";
        } else {
            $staff_name = sanitizeInput($_POST["staff_name"]);
            $ifhrms_id = sanitizeInput($_POST["ifhrms_id"]);
            $gender_name = sanitizeInput($_POST["gender_name"]);
            $dob = sanitizeInput($_POST["dob"]);
          
            $mobile_num = sanitizeInput($_POST["mobile_num"]);
            $district_name = sanitizeInput($_POST["district_name"]);
           
            $designation = sanitizeInput($_POST["designation"]);
            $warden_category = sanitizeInput($_POST["warden_category"]);
            $district_name_office = sanitizeInput($_POST["district_office"]);
            $taluk_name_office = sanitizeInput($_POST["taluk_office"]);
            $hostel_name = sanitizeInput($_POST["hostel_office"]);
            // $user_name = sanitizeInput($_POST["user_name"]);
            // $password = sanitizeInput($_POST["password"]);
            // $con_pass = sanitizeInput($_POST["con_pass"]);
            $aadhaar_no = sanitizeInput($_POST["aadhaar_no"]);
            $staff_count = sanitizeInput($_POST['staff_count']);
            $is_active = sanitizeInput($_POST["is_active"]);
            $unique_id = sanitizeInput($_POST["unique_id"]);
          
            $update_where = "";

             // Handle file upload
        if (!empty($_FILES['image_file']['name'])) {
            $file_name = $_FILES['image_file']['name'];
            $file_tmp = $_FILES['image_file']['tmp_name'];

            // Validate file extension (optional)
            $allowed_extensions = ['jpg', 'jpeg', 'png']; // Example of allowed extensions
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            if (!in_array($file_ext, $allowed_extensions)) {
                die('Invalid file type. Allowed types: jpg, jpeg, png');
               
            }

            // Generate unique file name
            $file_names = random_strings(25) . "." . $file_ext;

            // Move uploaded file to destination directory
            move_uploaded_file($file_tmp, '../../uploads/establishment/' . $file_names);

            // Set original file name
            $file_org_names = $file_name;
        }
if(!empty($_FILES['image_file']['name'])){
   

            $columns = [
                "staff_name" => $staff_name,
                "ifhrms_id" => $ifhrms_id,
                "gender_name" => $gender_name,
               
                "dob" => $dob,
                "mobile_num" => $mobile_num,
                "district_name" => $district_name,
                
                "designation" => $designation,
                "warden_category" => $warden_category,
                "district_office" => $district_name_office,
                "taluk_office" => $taluk_name_office,
                "hostel_name" => $hostel_name,
                "image_file" => $file_names,
                "entry_date" => date('Y-m-d'),

               
                "aadhaar_no" => $aadhaar_no,
                "unique_id" => unique_id($prefix)
            ];
        }else{
          
            $columns = [
                "staff_name" => $staff_name,
                "ifhrms_id" => $ifhrms_id,
                "gender_name" => $gender_name,
               
                "dob" => $dob,
                "mobile_num" => $mobile_num,
                "district_name" => $district_name,
                
                "designation" => $designation,
                "warden_category" => $warden_category,
                "district_office" => $district_name_office,
                "taluk_office" => $taluk_name_office,
                "hostel_name" => $hostel_name,
                 "entry_date" => date('Y-m-d'),
              
               
                "aadhaar_no" => $aadhaar_no,
                "unique_id" => unique_id($prefix)
            ];
        }

            // Check already exist or not
            $select_where = 'is_delete = 0 AND ifhrms_id = ? and hostel_name = ? and status != 2';
            $params = [$ifhrms_id,$hostel_name];
            $param_types = 'ss';

            // When Update Check without current id
            if ($unique_id) {
                $select_where .= ' AND unique_id != ?';
                $params[] = $unique_id;
                $param_types .= 's';
            }

            $stmt = $mysqli->prepare("SELECT COUNT(id) AS count FROM $table WHERE $select_where");
            if ($stmt === false) {
                $error = "Prepare failed: " . $mysqli->error;
                $msg = "error";
            } else {
                // Bind parameters
                $stmt->bind_param($param_types, ...$params);
                $stmt->execute();
                $result = $stmt->get_result();
                $data = $result->fetch_assoc();
                $stmt->close();

                if ($data['count'] > 0) {
                    $msg = "already";
                    $status = true;
                } else {
                    if ($unique_id) {
                       
                        unset($columns['unique_id']);
                        $update_where = "unique_id = ?";
                        $params = array_values($columns);
                        $params[] = $unique_id;
                        $param_types = str_repeat('s', count($columns)) . 's';
//                         echo $date;
// echo "UPDATE $table SET " . implode(' = ?, ', array_keys($columns)) . " = ? WHERE $update_where";
                        $update_stmt = $mysqli->prepare("UPDATE $table SET " . implode(' = ?, ', array_keys($columns)) . " = ? WHERE $update_where");
                        if ($update_stmt === false) {
                            $error = "Prepare failed: " . $mysqli->error;
                            $msg = "error";
                        } else {
                            $update_stmt->bind_param($param_types, ...$params);
                            if ($update_stmt->execute()) {
                                $status = true;
                                $msg = "update";
                            } else {
                                $error = "Execute failed: " . $update_stmt->error;
                                $msg = "error";
                            }
                            $update_stmt->close();
                        }
                    } else {
                   
                        $params = array_values($columns);
                        $param_types = str_repeat('s', count($columns));
// echo "INSERT INTO $table (" . implode(',', array_keys($columns)) . ") VALUES (" . str_repeat('?,', count($columns) - 1) . "?)";
                        $insert_stmt = $mysqli->prepare("INSERT INTO $table (" . implode(',', array_keys($columns)) . ") VALUES (" . str_repeat('?,', count($columns) - 1) . "?)");
                        if ($insert_stmt === false) {
                            $error = "Prepare failed: " . $mysqli->error;
                            $msg = "error";
                        } else {
                            $insert_stmt->bind_param($param_types, ...$params);
                            if ($insert_stmt->execute()) {
                                $status = true;
                                $msg = "create";
                            } else {
                                $error = "Execute failed: " . $insert_stmt->error;
                                $msg = "error";
                            }
                            $insert_stmt->close();
                        }
                    }
                }
            }
        }
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
        ];
        echo json_encode($json_array);
        break;

    case 'datatable':
        // DataTable Variables
        $length = isset($_POST['length']) ? $_POST['length'] : 10;
        $start = isset($_POST['start']) ? $_POST['start'] : 0;
        $draw = isset($_POST['draw']) ? $_POST['draw'] : 1;
        $limit = ($length == '-1') ? "" : $length;
        $data = [];

        // Query Variables
        $columns = [
            "@a:=@a+1 s_no",
            "staff_name",
            "(SELECT establishment_type FROM establishment_type WHERE establishment_type.unique_id = {$table}.designation) AS designation",
            "mobile_num",
            "(SELECT district_name FROM district_name WHERE unique_id = {$table}.district_name) AS district_name",
            "status",
            "status_upd_date",
            "reject_reason",
            "unique_id"
        ];
        $sql_columns = implode(", ", $columns);
        $table_with_counter = "{$table}, (SELECT @a:=? ) AS a";
        $where = "is_delete = ? AND hostel_name = '".$_SESSION['hostel_id']."'";
        $is_delete = 0;

        // Prepare SQL query
        $sql_query = "SELECT SQL_CALC_FOUND_ROWS {$sql_columns} FROM {$table_with_counter} WHERE {$where}";
    
        if ($limit !== "") {
            $sql_query .= " LIMIT ?, ?";
        }

        // Initialize total records variable
        $total_records = total_records();

        // Execute query with parameterized statements
        $stmt = $mysqli->prepare($sql_query);
        if ($stmt) {
            // Bind parameters
            $params = [$start, $is_delete];
            if ($limit !== "") {
                $params[] = $start;
                $params[] = $limit;
            }

            // Bind parameters dynamically
            $types = str_repeat('i', count($params)); // assuming all parameters are integers
            $stmt->bind_param($types, ...$params);

            // Execute the query
            $stmt->execute();

            // Fetch the result
            $res_array = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

            if ($res_array) {
                foreach ($res_array as $value) {

                    if($value['status'] == '0'){
                        $value['status'] = '<p style="color:blue">Pending</p>';
                        $value['status_upd_date'] ='-';
                        $value['reject_reason'] ='-';

			$btn_update = btn_update($folder_name, $value['unique_id']);
                    	$btn_delete = btn_delete($folder_name, $value['unique_id']);
                    	$value['unique_id'] = $btn_update . $btn_delete;
                    }elseif($value['status'] == '1'){
                        $value['status'] = '<p style="color:green">Approved</p>';
                        $value['status_upd_date'] = disdate($value['status_upd_date']);
                        $value['reject_reason'] = '-';
			$value['unique_id'] = '-';
                    }elseif($value['status'] == '2'){
                        $value['status'] = '<p style="color:red">Rejected</p>';
                        $value['status_upd_date'] = disdate($value['status_upd_date']);
                        // $value['reject_reason'] = $value['reject_reason'];
			$value['unique_id'] = '-';
                    }

                    $value['is_active'] = is_active_show($value['is_active']);
                    
                    $data[] = array_values($value);
                }

                // Fetch the total filtered records
                
            }
$stmt_filtered = $mysqli->query("SELECT FOUND_ROWS()");
                $total_filtered = $stmt_filtered->fetch_row()[0];

                // Prepare JSON response
                $json_array = [
                    "draw" => intval($draw),
                    "recordsTotal" => intval($total_filtered),
                    "recordsFiltered" => intval($total_filtered),
                    "data" => $data,
                ];

            $stmt->close();
        } else {
            // Handle query error
            error_log("MySQLi error: " . $mysqli->error);
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
            ];
        }

        // Output JSON response
        echo json_encode($json_array);
        break;


    case 'delete':

        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        $unique_id = $_POST['unique_id'];

        // Prepare SQL statement
        $sql = "UPDATE $table SET is_delete = ? WHERE unique_id = ?";

        // Prepare statement
        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            die('MySQL prepare error: ' . $mysqli->error);
        }

        // Bind parameters
        $is_delete = 1; // Assuming is_delete is an integer
        $stmt->bind_param('is', $is_delete, $unique_id);

        // Execute statement
        $stmt->execute();

        // Check for success
        if ($stmt->affected_rows > 0) {
            $status = true;
            $data = "Successfully deleted";
            $error = "";
            $msg = "success_delete";
        } else {
            $status = false;
            $data = "";
            $error = "Delete operation failed";
            $msg = "error";
        }

        // Close statement
        $stmt->close();

        // Prepare JSON response
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg
        ];

        echo json_encode($json_array);
        break;


    case 'hostel_warden':




    case 'district_name':

        $district_name = $_POST['district_name'];


        $district_name_options = taluk_name('', $district_name);

        $taluk_name_options = select_option($district_name_options, "Select Taluk");

        echo $taluk_name_options;
        // print_r($taluk_name_options);

        break;

    case 'get_hostel_by_taluk_name':

        $taluk_name = $_POST['taluk_name'];


        $hostel_name_options = hostel_name('', $taluk_name);

        $hostel_name_options = select_option($hostel_name_options, "Select Hostel");
        // print_r( $hostel_name_options);

        echo $hostel_name_options;

        break;


    case 'get_district_new_name':

        $district_name = $_POST['district_name_new'];


        $district_name_options = taluk_name('', $district_name);

        $taluk_name_options = select_option($district_name_options, "Select Taluk");

        echo $taluk_name_options;

        break;

    case 'get_hostel_by_new_name':

        $taluk_name_new = $_POST['taluk_name_new'];


        $hostel_name_options = hostel_name('', $taluk_name_new);



        $hostel_name_options = select_option($hostel_name_options, "Select Hostel");
        // print_r( $hostel_name_options);die();

        echo $hostel_name_options;

        break;
}

