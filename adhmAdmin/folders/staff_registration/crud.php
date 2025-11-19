<?php

// Get folder Name From Currnent Url
$folder_name = explode('/', $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];
// // Database Country Table Name
$table = 'staff_registration';
// // Include DB file and Common Functions
include '../../config/dbconfig.php';
// // Variables Declaration
$action = $_POST['action'];

$district_name = $_POST['district_name'];
$taluk_name = $_POST['taluk_name'];
// $hostel_name        = $_POST["hostel_name"];
$is_active = '';
$unique_id = '';
$prefix = '';
$data = '';
$msg = '';
$error = '';
$status = '';
$test = ''; // For Developer Testing Purpose\

function validateCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Assuming $action is defined somewhere in your code
switch ($action) {
    case 'createupdate':
        $token = $_POST['csrf_token'];

        // Example function to validate CSRF token

        if (!validateCSRFToken($token)) {
            exit('CSRF validation failed.');
        }

        // Initialize variables
        $msg = '';
        $status = false;
        $data = [];
        $error = '';

        // Validate and sanitize inputs
        $vali_mob_no = filter_input(INPUT_POST, 'mobile_num', FILTER_SANITIZE_STRING);
        $vali_staff_name = filter_input(INPUT_POST, 'staff_name', FILTER_SANITIZE_STRING);
        $vali_staff_id = filter_input(INPUT_POST, 'staff_id', FILTER_SANITIZE_STRING);
        $vali_address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
        $vali_department = filter_input(INPUT_POST, 'department', FILTER_SANITIZE_STRING);
        $vali_user_name = filter_input(INPUT_POST, 'user_name', FILTER_SANITIZE_STRING);
        $vali_password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        $vali_confirm_password = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_STRING);
        $vali_biometric_id = filter_input(INPUT_POST, 'biometric_id', FILTER_SANITIZE_STRING);
        $vali_hashedPassword = filter_input(INPUT_POST, 'hashedPassword', FILTER_SANITIZE_STRING);

        // Check if any required fields are empty
        if (!$vali_mob_no || !$vali_staff_name || !$vali_staff_id || !$vali_address || !$vali_department || !$vali_user_name || !$vali_password || !$vali_confirm_password || !$vali_biometric_id || !$vali_hashedPassword) {
            $msg = 'form_alert';
        } else {
            // Sanitize and assign inputs
            $staff_name = sanitizeInput($_POST['staff_name']);
            $staff_id = sanitizeInput($_POST['staff_id']);
            $mobile_num = sanitizeInput($_POST['mobile_num']);
            $dob = sanitizeInput($_POST['dob']); // Assuming sanitizeInput() is a valid function
            $father_name = sanitizeInput($_POST['father_name']);
            $gender_name = sanitizeInput($_POST['gender_name']);
            $age = isset($_POST['age']) ? $_POST['age'] : null; // Ensure age is not sensitive to SQL injection
            $district_name = sanitizeInput($_POST['district_name']);
            $taluk_name = sanitizeInput($_POST['taluk_name']);
            $address = sanitizeInput($_POST['address']);
            $email_id = sanitizeInput($_POST['email_id']);
            $doj = sanitizeInput($_POST['doj']);
            $department = sanitizeInput($_POST['department']);
            $designation = sanitizeInput($_POST['department_new']); // Assuming department_new is valid
            $district_name_office = sanitizeInput($_POST['district_office']);
            $taluk_name_office = !empty($_POST['taluk_office']) ? sanitizeInput($_POST['taluk_office']) : null;
            $warden_hostel_name = !empty($_POST['hostel_warden']) ? sanitizeInput($_POST['hostel_warden']) : null;
            $tah_hostel_name = !empty($_POST['hostel_tash']) ? sanitizeInput($_POST['hostel_tash']) : null;
            $hostel_name = !empty($_POST['hostel_name']) ? sanitizeInput($_POST['hostel_name']) : null;
            $academic_year = sanitizeInput($_POST['academic_year']);
            $user_name = sanitizeInput($_POST['user_name']);
            $password = sanitizeInput($_POST['password']);
            $hashedPassword = sanitizeInput($_POST['hashedPassword']);
            $confirm_password = sanitizeInput($_POST['confirm_password']);
            $biometric_id = sanitizeInput($_POST['biometric_id']);
            $user_type = sanitizeInput($_POST['user_type']);
            $unique_id = sanitizeInput($_POST['unique_id']);

            // File upload handling (if any)
            $file_names = '';
            $file_org_names = '';
            if (!empty($_FILES['test_file1']['name'])) {
                $allowedExts = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'xlsx', 'xls'];
                $extension = pathinfo($_FILES['test_file1']['name'], PATHINFO_EXTENSION);
                if (in_array($extension, $allowedExts)) {
                    $tem_name1 = random_strings(25).'.'.$extension;
                    if (move_uploaded_file($_FILES['test_file1']['tmp_name'], '../../uploads/staff_registration/'.$tem_name1)) {
                        $file_names = $tem_name1;
                        $file_org_names = $_FILES['test_file1']['name'];
                    } else {
                        $msg = 'Failed to move uploaded file.';
                    }
                } else {
                    $msg = 'Invalid file format';
                }
            }

	 if($designation == '65f3191aa725518258'){
                $where = " hostel_name = ? and designation = ? and is_delete = '0'";
                // $hostel_name = $hostel_name;
            }else if($designation == '65f31975f0ce678724'){
                $where = " district_office = ? and designation = ? and is_delete = '0'";
            }else if($designation == '65f3195bb6bcf35260'){
                $where = " taluk_office = ? and designation = ? and is_delete = '0'";
            }

	  if($unique_id){
                $where .= ' and unique_id != ?';
            }


            // print_r($hostel_name.$district_name_office.$taluk_name_office);die();

            // Check if the record already exists
            // $count_query = "SELECT COUNT(unique_id) AS count FROM staff_registration WHERE hostel_name = ? AND designation = ? AND district_office = ? AND taluk_office = ?";

           // $count_query = 'SELECT COUNT(unique_id) AS count FROM staff_registration WHERE unique_id = ?';

            $count_query = 'SELECT COUNT(unique_id) AS count FROM staff_registration WHERE '.$where.'';



            // Assuming $hostel_name, $designation, $district_name_office, $taluk_name_office are already sanitized and defined
            $stmt = $mysqli->prepare($count_query);

            if ($stmt) {
               if($designation == '65f3191aa725518258'){
                    if($unique_id){
                    $stmt->bind_param('sss', $hostel_name, $designation, $unique_id);
                    }else{
                    $stmt->bind_param('ss', $hostel_name, $designation);
                    }
                }else if($designation == '65f31975f0ce678724'){
                    if($unique_id){
                    $stmt->bind_param('sss', $district_name_office, $designation, $unique_id);
                }else{
                    $stmt->bind_param('ss', $district_name_office, $designation);
                    }
                }else if($designation == '65f3195bb6bcf35260'){
                    if($unique_id){
                    $stmt->bind_param('sss', $taluk_name_office, $designation, $unique_id);
                }else{
                    $stmt->bind_param('ss', $taluk_name_office, $designation);
                    }
                }  
      $stmt->execute();
                $result = $stmt->get_result();

                if ($result) {
                    $row = $result->fetch_assoc();

                    if ($row['count'] > 0) {
$status = true;
 $msg = 'already';
  } else {
                           
                        
                        if ($unique_id) {
				if(!$file_names){
                            $sql = 'UPDATE staff_registration SET staff_name=?, staff_id=?, dob=?, father_name=?, gender_name=?, age=?, mobile_num=?, district_name=?, taluk_name=?, address=?, email_id=?, doj=?, department=?, designation=?, district_office=?, taluk_office=?, hostel_name=?, academic_year=?, user_name=?, password=?, hashedPassword=?, confirm_password=?, biometric_id=?, user_type=? WHERE unique_id=?';

                            $stmt = $mysqli->prepare($sql);

                            $stmt->bind_param('sssssssssssssssssssssssss', $staff_name, $staff_id, $dob, $father_name, $gender_name, $age, $mobile_num, $district_name, $taluk_name, $address, $email_id, $doj, $department, $designation, $district_name_office, $taluk_name_office, $hostel_name, $academic_year, $user_name, $password, $hashedPassword, $confirm_password, $biometric_id, $user_type, $unique_id);

                            $action = $stmt->execute();

                            $msg = 'update';
                            $status = true;
}else{
 $sql = 'UPDATE staff_registration SET staff_name=?, staff_id=?, dob=?, father_name=?, gender_name=?, age=?, mobile_num=?, district_name=?, taluk_name=?, address=?, email_id=?, doj=?, department=?, designation=?, district_office=?, taluk_office=?, hostel_name=?, academic_year=?, user_name=?, password=?, hashedPassword=?, confirm_password=?, biometric_id=?, user_type=?, file_names = ?, file_org_names = ?  WHERE unique_id=?';

                            $stmt = $mysqli->prepare($sql);

                            $stmt->bind_param('sssssssssssssssssssssssssss', $staff_name, $staff_id, $dob, $father_name, $gender_name, $age, $mobile_num, $district_name, $taluk_name, $address, $email_id, $doj, $department, $designation, $district_name_office, $taluk_name_office, $hostel_name, $academic_year, $user_name, $password, $hashedPassword, $confirm_password, $biometric_id, $user_type, $file_names, $file_org_names, $unique_id);

                            $action = $stmt->execute();

                            $msg = 'update';
                            $status = true;

}
                      
                    } else {
                        $sql = 'INSERT INTO staff_registration(staff_name, staff_id, dob, father_name, gender_name, age, mobile_num, district_name, taluk_name, address, email_id, doj, department, designation, district_office, taluk_office, hostel_name, academic_year, user_name, password, hashedPassword, confirm_password, biometric_id, user_type, file_names, file_org_names, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

                        $stmt = $mysqli->prepare($sql);

                        $stmt->bind_param('sssssssssssssssssssssssssss', $staff_name, $staff_id, $dob, $father_name, $gender_name, $age, $mobile_num, $district_name, $taluk_name, $address, $email_id, $doj, $department, $designation, $district_name_office, $taluk_name_office, $hostel_name, $academic_year, $user_name, $password, $hashedPassword, $confirm_password, $biometric_id, $user_type, $file_names, $file_org_names, unique_id($prefix));

                        $action = $stmt->execute();
                        $msg = 'create';
                        $status = true;
                    }
}
                    // $msg = ($action) ? ($unique_id ? "update" : "create") : "error";
                } else {
                    $error = $mysqli->error;
                    $msg = 'error';
                }

                $stmt->close();
            } else {
                $error = $mysqli->error;
                $msg = 'error';
            }

            $mysqli->close();
        }

        // Prepare JSON response
        $json_array = [
            'status' => $status,
            'data' => $data,
            'error' => $error,
            'msg' => $msg,
        ];

        echo json_encode($json_array);

        break;

    case 'datatable':
        // DataTable Variables

        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;
        $data = [];

        $designation = sanitizeInput($_POST['department_new']);
        $district_name_office = sanitizeInput($_POST['district_name_new']);
        $taluk_name_office = sanitizeInput($_POST['taluk_name_new']);
        $hostel_name_office = sanitizeInput($_POST['hostel_new']);

        if ($length == '-1') {
            $limit = '';
        }

        // Database connection details
        // $mysqli = new mysqli("localhost", "root", "4/rb5sO2s3TpL4gu", "adi_dravidar");

        // if ($mysqli->connect_error) {
        //     die("Connection failed: " . $mysqli->connect_error);
        // }

        // Prepare the SQL query
        $sql = 'SELECT SQL_CALC_FOUND_ROWS 
            @a:=@a+1 s_no,
            staff_name,
            (SELECT designation_name FROM designation_creation WHERE unique_id = staff_registration.designation) AS designation,
            mobile_num,
            district_office,
            taluk_office,
            unique_id
        FROM staff_registration, (SELECT @a:= ?) AS a
        WHERE is_delete = 0';

        // Array to store parameters and their types for binding
        $params = [];
        $types = '';

        // Bind parameters dynamically based on conditions
        $params[] = &$start;
        $types .= 'i';

        if ($district_name_office != '') {
            $sql .= ' AND district_office = ?';
            $params[] = &$district_name_office;
            $types .= 's';
        }
        if ($taluk_name_office != '') {
            $sql .= ' AND taluk_office = ?';
            $params[] = &$taluk_name_office;
            $types .= 's';
        }
        if ($designation != '') {
            $sql .= ' AND designation = ?';
            $params[] = &$designation;
            $types .= 's';
        }

        // Add limit clause
        $sql .= ' LIMIT ?, ?';
        $params[] = &$start;
        $params[] = &$limit;
        $types .= 'ii';

        // Prepare statement
        $stmt = $mysqli->prepare($sql);
        if ($stmt) {
            // Bind parameters
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }

            // Execute query
            $stmt->execute();

            // Get result set
            $result = $stmt->get_result();

            if ($result) {
                $total_records = $mysqli->query('SELECT FOUND_ROWS()')->fetch_row()[0];

                while ($row = $result->fetch_assoc()) {
                    // Additional processing as per your requirements
                    $row['district_office'] = district_name_un($row['district_office']);
                    $row['taluk_office'] = taluk_name_un($row['taluk_office']);

                    if ($row['designation'] == 'DADWO-Officer') {
                        $row['taluk_office'] = 'ALL';
                    }

                    $btn_update = btn_update($folder_name, $row['unique_id']);
                    $btn_delete = btn_delete($folder_name, $row['unique_id']);

                    $row['unique_id'] = $btn_update.$btn_delete;

                    $data[] = array_values($row);
                }

                // Prepare JSON response
                $json_array = [
                    'draw' => intval($draw),
                    'recordsTotal' => intval($total_records),
                    'recordsFiltered' => intval($total_records),
                    'data' => $data,
                ];

                echo json_encode($json_array);
            } else {
                echo json_encode(['error' => 'Query execution error']);
            }

            // Close statement and connection
            $stmt->close();
        } else {
            echo json_encode(['error' => 'Prepare statement error: '.$mysqli->error]);
        }

        // Close MySQL connection
        $mysqli->close();
        break;

    case 'delete':
        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            exit('CSRF validation failed.');
        }

        $unique_id = $_POST['unique_id'];

        // Prepare the update query
        $sql = "UPDATE $table SET is_delete = 1 WHERE unique_id = ?";

        // Prepare statement
        $stmt = $mysqli->prepare($sql);
        if ($stmt) {
            // Bind parameter
            $stmt->bind_param('s', $unique_id);

            // Execute query
            $stmt->execute();

            // Check if update was successful
            if ($stmt->affected_rows > 0) {
                $status = true;
                $data = [];
                $error = '';
                $msg = 'success_delete';
            } else {
                $status = false;
                $data = [];
                $error = 'No rows updated';
                $msg = 'error';
            }

            // Close statement
            $stmt->close();
        } else {
            $status = false;
            $data = [];
            $error = 'Prepare statement error: '.$mysqli->error;
            $msg = 'error';
        }

        // Close MySQL connection
        $mysqli->close();

        // Prepare JSON response
        $json_array = [
            'status' => $status,
            'data' => $data,
            'error' => $error,
            'msg' => $msg,
        ];

        echo json_encode($json_array);
        break;

    case 'district_name':
        $district_name = $_POST['district_name'];

        $district_name_options = taluk_name('', $district_name);

        $taluk_name_options = select_option($district_name_options, 'Select Taluk');

        echo $taluk_name_options;
        // print_r($taluk_name_options);

        break;

    case 'get_hostel_by_taluk_name':
        $taluk_name = $_POST['taluk_name'];

        $hostel_name_options = hostel_name('', $taluk_name);

        $hostel_name_options = select_option($hostel_name_options, 'Select Hostel');
        // print_r( $hostel_name_options);

        echo $hostel_name_options;

        break;

    case 'get_district_new_name':
        $district_name = $_POST['district_name_new'];

        $district_name_options = taluk_name('', $district_name);

        $taluk_name_options = select_option($district_name_options, 'Select Taluk');

        echo $taluk_name_options;

        break;

    case 'get_hostel_by_new_name':
        $taluk_name_new = $_POST['taluk_name_new'];

        $hostel_name_options = hostel_name('', $taluk_name_new);

        $hostel_name_options = select_option($hostel_name_options, 'Select Hostel');
        // print_r( $hostel_name_options);die();

        echo $hostel_name_options;

        break;
}
