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
// $user_type          = "";

// $district_name  = $_POST["district_name"];
// $taluk_name =    $_POST["taluk_name"];
// $hostel_name       =$_POST["hostel_name"];
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


    // Establish MySQLi connection
    case 'createupdate':
        // Function to sanitize input (to prevent SQL injection)

        // Validate CSRF token (assuming validateCSRFToken function exists)
        $token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        // Initialize variables from $_POST
        $staff_name = sanitizeInput($_POST["staff_name"]);
        $father_name = sanitizeInput($_POST["father_name"]);
        $gender_name = sanitizeInput($_POST["gender_name"]);
        $dob = sanitizeInput($_POST["dob"]);
        $age = sanitizeInput($_POST["age"]);
        $mobile_num = sanitizeInput($_POST["mobile_num"]);
        $district_name = sanitizeInput($_POST["district_name"]);
        $taluk_name = sanitizeInput($_POST["taluk_name"]);
        $address = sanitizeInput($_POST["address"]);
        // $aadhaar_no = sanitizeInput($_POST["aadhaar_no"]);
        $email_id = sanitizeInput($_POST["email_id"]);
        $doj = sanitizeInput($_POST["doj"]);
        $department = sanitizeInput($_POST["department"]);
        $designation = sanitizeInput($_POST["department_new"]);
        $district_name_office = sanitizeInput($_POST["district_office"]);
        $taluk_name_office = sanitizeInput($_POST["taluk_office"]);
        $hostel_name = sanitizeInput($_POST["hostel_office"]);
        $user_name = sanitizeInput($_POST["user_name"]);
        $password = sanitizeInput($_POST["password"]);
        $confirm_password = sanitizeInput($_POST["confirm_password"]);
        $biometric_id = sanitizeInput($_POST["biometric_id"]);
        $is_active = sanitizeInput($_POST["is_active"]);
        $unique_id = isset($_POST["unique_id"]) ? $_POST["unique_id"] : '';

        // Prepare columns for insert or update
        $columns = [
            "staff_name" => $staff_name,
            "father_name" => $father_name,
            "gender_name" => $gender_name,
            "dob" => $dob,
            "age" => $age,
            "mobile_num" => $mobile_num,
            "district_name" => $district_name,
            "taluk_name" => $taluk_name,
            "address" => $address,
            // "aadhaar_no" => $aadhaar_no,
            "email_id" => $email_id,
            "doj" => $doj,
            "department" => $department,
            "designation" => $designation,
            "district_office" => $district_name_office,
            "taluk_office" => $taluk_name_office,
            "hostel_name" => $hostel_name,
            "user_name" => $user_name,
            "password" => $password,
            "confirm_password" => $confirm_password,
            "biometric_id" => $biometric_id,
            "is_active" => $is_active
        ];

        // Check if unique_id exists for update, otherwise insert
        if (!empty($unique_id)) {
            // Update operation
            $update_columns = [];
            foreach ($columns as $key => $value) {
                $update_columns[] = $key . " = '" . $value . "'";
            }
            $sql = "UPDATE establishment_registration SET " . implode(", ", $update_columns) . " WHERE unique_id = '" . $unique_id . "'";
        } else {
            // Insert operation
            $sql = "INSERT INTO establishment_registration (" . implode(", ", array_keys($columns)) . ") VALUES ('" . implode("', '", array_values($columns)) . "')";
        }

        // Execute the query
        if ($mysqli->query($sql) === TRUE) {
            // Success
            $status = true;
            $msg = !empty($unique_id) ? "update" : "create";
            $data = [];
            $error = "";
        } else {
            // Error
            $status = false;
            $msg = "error";
            $data = [];
            $error = $mysqli->error;
        }

        // Close MySQLi connection
        $mysqli->close();

        // Prepare JSON response
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg
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

        // // Function to sanitize input (to prevent SQL injection)
        // function sanitizeInput($input) {
        //     global $mysqli;
        //     return $mysqli->real_escape_string($input);
        // }

        $staff_name = sanitizeInput($_POST["staff_name"]);
        $father_name = sanitizeInput($_POST["father_name"]);
        $gender_name = sanitizeInput($_POST["gender_name"]);
        $dob = sanitizeInput($_POST["dateofbirth"]);
        $age = sanitizeInput($_POST["age"]);
        $mobile_num = sanitizeInput($_POST["mobile_num"]);
        $district_name = sanitizeInput($_POST["district_name"]);
        $taluk_name = sanitizeInput($_POST["taluk_name"]);
        $address = sanitizeInput($_POST["address"]);
        // $aadhaar_no = sanitizeInput($_POST["aadhaar_no"]);
        $email_id = sanitizeInput($_POST["email_id"]);
        $doj = sanitizeInput($_POST["doj"]);
        $department = sanitizeInput($_POST["department"]);
        $designation = sanitizeInput($_POST["department_new"]);
        $district_name_office = sanitizeInput($_POST["district_office"]);
        $taluk_name_office = sanitizeInput($_POST["taluk_office"]);
        $hostel_name_office = sanitizeInput($_POST["hostel_office"]);
        $user_name = sanitizeInput($_POST["user_name"]);
        $password = sanitizeInput($_POST["password"]);
        $confirm_password = sanitizeInput($_POST["confirm_password"]);
        $is_active = $_POST["is_active"];
        $unique_id = sanitizeInput($_POST["unique_id"]);

        $update_where = "";

        // Query Variables
        $columns = [
            "@a:=@a+1 s_no",
            "staff_name",
            "(SELECT designation_name FROM designation_creation WHERE unique_id = establishment_registration.designation) AS designation",
            "mobile_num",
            "(SELECT district_name FROM district_name WHERE unique_id = establishment_registration.district_name) AS district_name",
            "(SELECT taluk_name FROM taluk_creation WHERE unique_id = establishment_registration.taluk_name) AS taluk_name",
            // "is_active",
            "unique_id"
        ];
        $table = "establishment_registration"; // Replace with your actual table name
        $table_details = $table . ", (SELECT @a:= ?) AS a";
        $where = "is_delete = ?";
        $bindParams = [$start, '0'];
        $bindTypes = 'ss';

        // Build the WHERE clause with parameters and types
        if (!empty($district_name)) {
            $where .= " AND district_name=?";
            $bindParams[] = $district_name;
            $bindTypes .= 's'; // 's' for string
        }
        if (!empty($taluk_name)) {
            $where .= " AND taluk_name=?";
            $bindParams[] = $taluk_name;
            $bindTypes .= 's'; // 's' for string
        }
        if (!empty($designation)) {
            $where .= " AND designation=?";
            $bindParams[] = $designation;
            $bindTypes .= 's'; // 's' for string
        }

        $order_by = "";

        // Prepare SQL statement
        $sql = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns) . " FROM " . $table_details . " WHERE " . $where;

        if (!empty($limit)) {
            $sql .= " LIMIT ?, ?";
            $bindParams[] = $start;
            $bindParams[] = $limit;
            $bindTypes .= 'ii'; // 'i' for integer
        }

        // Prepare statement
        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            die('MySQL prepare error: ' . $mysqli->error);
        }

        // Bind parameters
        if (!empty($bindParams)) {
            $stmt->bind_param($bindTypes, ...$bindParams);
        }

        // Execute query
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch data
        $data = [];
        while ($row = $result->fetch_assoc()) {
            // Modify columns as necessary (e.g., convert boolean to readable format)
            $row['is_active'] = is_active_show($row['is_active']);
            $row['unique_id'] = btn_delete($folder_name, $row['unique_id']);
            $data[] = array_values($row);
        }

        // Total records count
        $total_records = $mysqli->query("SELECT FOUND_ROWS() AS total")->fetch_assoc()['total'];

        // JSON response
        $json_array = [
            "draw" => intval($draw),
            "recordsTotal" => intval($total_records),
            "recordsFiltered" => intval($total_records),
            "data" => $data
        ];

        echo json_encode($json_array);

        // Close statement and connection
        // $stmt->close();
        // $mysqli->close();

        break;
    // case 'hostel_warden':

        
        case 'delete':
        
            $token = $_POST['csrf_token'];

            if (!validateCSRFToken($token)) {
                die('CSRF validation failed.');
            }
    
            $unique_id = $_POST['unique_id'];
            
            
            // Prepare the update query
            $sql = "UPDATE $table SET is_delete = ? WHERE unique_id = ?";
            $is_delete = "1";
            // Prepare statement
            $stmt = $mysqli->prepare($sql);
            if ($stmt) {
                // Bind parameter
                $stmt->bind_param("ss", $is_delete, $unique_id);
                
                // Execute query
                $stmt->execute();
            
                // Check if update was successful
                if ($stmt->affected_rows > 0) {
                    $status = true;
                    $data = [];
                    $error = "";
                    $msg = "success_delete";
                } else {
                    $status = false;
                    $data = [];
                    $error = "No rows updated";
                    $msg = "error";
                }
            
                // Close statement
                $stmt->close();
            } else {
                $status = false;
                $data = [];
                $error = "Prepare statement error: " . $mysqli->error;
                $msg = "error";
            }
            
            // Close MySQL connection
            $mysqli->close();
            
            // Prepare JSON response
            $json_array = [
                "status" => $status,
                "data" => $data,
                "error" => $error,
                "msg" => $msg,
            ];
            
            echo json_encode($json_array);
           break;

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

