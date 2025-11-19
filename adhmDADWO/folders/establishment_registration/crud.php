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
switch ($action) {
    case 'createupdate':
        // $user_type          = $_POST["user_type"];
        // $district_
        $staff_name = $_POST["staff_name"];
        $father_name = $_POST["father_name"];
        $gender_name = $_POST["gender_name"];
        // $dob                = $_POST["dob"];
        $age = $_POST["age"];
        $mobile_num = $_POST["mobile_num"];
        $district_name = $_POST["district_name"];
        $taluk_name = $_POST["taluk_name"];
        $address = $_POST["address"];
        $aadhaar_no = $_POST["aadhaar_no"];
        $email_id = $_POST["email_id"];
        $doj = $_POST["doj"];
        $department = $_POST["department"];
        $designation = $_POST["department_new"];
        $district_name_office = $_POST["district_office"];
        $taluk_name_office = $_POST["taluk_office"];
        $hostel_name = $_POST["hostel_office"];

        // $current_date           =$_POST["current_date"];
        $user_name = $_POST["user_name"];
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];


        $biometric_id = $_POST["biometric_id"];

        $is_active = $_POST["is_active"];
        $unique_id = $_POST["unique_id"];
        $update_where = "";

        $columns = [
            "staff_name" => $staff_name,
            "father_name" => $father_name,
            "gender_name" => $gender_name,
            "age" => $age,
            "mobile_num" => $mobile_num,
            "district_name" => $district_name,
            "taluk_name" => $taluk_name,
            "address" => $address,
            "aadhaar_no" => $aadhaar_no,
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

            // "is_active"           => $is_active,
            "unique_id" => unique_id($prefix)
        ];
        // check already Exist Or not
        $table_details = [
            $table,
            [
                "COUNT(unique_id) AS count"
            ]
        ];
        $select_where = 'is_delete = 0 ';
        // When Update Check without current id
        if ($unique_id) {
            $select_where .= ' AND unique_id !="' . $unique_id . '" ';
        }
        $action_obj = $pdo->select($table_details, $select_where);
        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;
        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "error";
        }
        // if ($data[0]["count"]) {
        //     $msg        = "already";
        // } else if ($data[0]["count"] == 0) {
        // Update Begins
        if ($unique_id) {
            unset($columns['unique_id']);
            $update_where = [
                "unique_id" => $unique_id
            ];
            $action_obj = $pdo->update($table, $columns, $update_where);
            // Update Ends
        } else {
            // Insert Begins            
            $action_obj = $pdo->insert($table, $columns);
            // Insert Ends
        }
        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;
            if ($unique_id) {
                $msg = "update";
            } else {
                $msg = "create";
            }
        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "error";
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql"       => $sql
        ];
        echo json_encode($json_array);
        break;

    case 'datatable':
        // DataTable Variables
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length == '-1' ? "" : $length;

        $data = [];

        // Create connection

        // Check connection
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Query Variables
        $columns = [
            "@a:=@a+1 s_no",
            "staff_name",
            "(SELECT establishment_type FROM establishment_type WHERE unique_id = $table.designation) AS designation",
           
            "(SELECT district_name FROM district_name WHERE unique_id = $table.district_office) AS district_office",
            "(SELECT taluk_name FROM taluk_creation WHERE unique_id = $table.taluk_office) AS taluk_office",
            "(SELECT hostel_name FROM hostel_name WHERE unique_id = $table.hostel_name) AS hostel_name",
            "status",
            "status_upd_date",
            "unique_id"
        ];
        $table_details = $table . " , (SELECT @a:= ?) AS a ";
        $where = "is_delete = 0";

        // Prepare conditions for bind_param
        $bind_params = "s"; // Types of parameters (s for string)

        // Initialize array for bind_param values
        $bind_values = [$start];

        // Additional conditions
        if (!empty($_POST["district_name"])) {
            $where .= " AND district_office = ?";
            $bind_params .= "s"; // Add type for string parameter
            $bind_values[] = $_POST["district_name"];
        }
        if (!empty($_POST["taluk_name"])) {
            $where .= " AND taluk_office = ?";
            $bind_params .= "s"; // Add type for string parameter
            $bind_values[] = $_POST["taluk_name"];
        }
        if (!empty($_POST["hostel_name"])) {
            $where .= " AND hostel_name = ?";
            $bind_params .= "s"; // Add type for string parameter
            $bind_values[] = $_POST["hostel_name"];
        }
        if (!empty($_POST["department_new"])) {
            $where .= " AND designation = ?";
            $bind_params .= "s"; // Add type for string parameter
            $bind_values[] = $_POST["department_new"];
        }

        $sql_function = "SQL_CALC_FOUND_ROWS";

        // SQL query for data fetching
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_details WHERE $where";
        if ($limit) {
            $sql .= " LIMIT ?, ?";
            $bind_params .= "ii"; // Add types for limit parameters
            $bind_values[] = $start;
            $bind_values[] = $limit;
        }

        $stmt = $mysqli->prepare($sql);

        // Bind parameters dynamically
        $stmt->bind_param($bind_params, ...$bind_values);

        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch total records
        $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
        $total_records = $total_records_result->fetch_assoc()['total'];

        if ($result) {
            $res_array = $result->fetch_all(MYSQLI_ASSOC);

            foreach ($res_array as $key => $value) {

                if ($value['status'] == 0) {
                    $acceptButton = '<button class="accept-btn"   data-unique-id="' . $value['unique_id'] . '">Accept</button>';
                    $rejectButton = '<button class="reject-btn"   data-unique-id="' . $value['unique_id'] . '">Reject</button>';
                    $value['status'] = $acceptButton . ' ' . $rejectButton;
                    $value['status_upd_date'] = '-';
                } elseif ($value['status'] == 1) {
                    $value['status'] = '<span style="color: green;">Accepted</span>';
                    $value['status_upd_date'] = disdate($value['status_upd_date']);
                } elseif ($value['status'] == 2) {
                    $value['status'] = '<span style="color: red;">Rejected</span>';
                    $value['status_upd_date'] = disdate($value['status_upd_date']);

                }
                

                $eye_button = '<a class="btn btn-action specl2" href="javascript:view_print(\'' . $value['unique_id'] . '\')"><button type="button"><i class="fa fa-eye"></i></button></a>';

               
                // Modify fields as needed
                // For example, showing is_active status
                $value['is_active'] = is_active_show($value['is_active']);
$value['staff_name'] = disname($value['staff_name']);
                
                $value['unique_id'] = $eye_button;

                $data[] = array_values($value);
            }

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data
            ];
        } else {
            // Handle the error case
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => $stmt->error
            ];
        }

        echo json_encode($json_array);

        // Close connection
        $stmt->close();
        $mysqli->close();

        break;

    case 'delete':
        $unique_id = $_POST['unique_id'];
        $columns = [
            "is_delete" => 1
        ];
        $update_where = [
            "unique_id" => $unique_id
        ];
        $action_obj = $pdo->update($table, $columns, $update_where);

        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;
            $msg = "success_delete";
        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "error";
        }
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql"       => $sql
        ];
        echo json_encode($json_array);
        break;

        case 'at_accept':

      
            $table = 'establishment_registration';
            $unique_id = $_POST['uniqueId'];
           
           
                $status_upd_date = date('Y-m-d');
                $stmt = $mysqli->prepare("UPDATE $table SET status = ?, status_upd_date = ? WHERE unique_id = ?");
                $stmt->bind_param("sss", $status, $status_upd_date, $unique_id);
                $status = 1;
                $stmt->execute();
                $action_obj = $stmt->affected_rows;
                $stmt->close();
                // echo "ff";
    
            
    
                
    
                // Handle errors if needed
                if ($action_obj === false) {
                    $status = false; // Assuming $status is used to track success/failure
                    $error = $mysqli->error;
                    $msg = "error";
                } else {
                    $status = true; // Assuming $status is used to track success/failure
                    $msg = "success"; // Assuming this is the success message
                }
           
    
            $json_array = [
                "status" => $status,
                "error" => $error ?? null, // Set error to null if not defined
                "msg" => $msg,
                // Optionally include more data if needed
            ];
    
            echo json_encode($json_array);
    
            // Close MySQLi connection
            $mysqli->close();
    
            break;
    
    
        case 'at_reject':
    
          
    
            $table = 'establishment_registration';
            $unique_id = $_POST['uniqueId'];
          
            $reason = $_POST['reason'];
           
                $status_upd_date = date('Y-m-d');
               
                $stmt = $mysqli->prepare("UPDATE $table SET status = ?, status_upd_date = ?, reject_reason = ? WHERE unique_id = ?");
                $stmt->bind_param("ssss", $status, $status_upd_date, $reason, $unique_id);
                $status = 2;
                $stmt->execute();
                $action_obj = $stmt->affected_rows;
                $stmt->close();
              
    
            // Handle errors if needed
            if ($action_obj === false) {
                $status = false; // Assuming $status is used to track success/failure
                $error = $mysqli->error;
                $msg = "error";
            } else {
                $status = true; // Assuming $status is used to track success/failure
                $msg = "success_delete"; // Assuming this is the success message
            }
    
            $json_array = [
                "status" => $status,
                "error" => $error ?? null, // Set error to null if not defined
                "msg" => $msg,
                // Optionally include more data if needed
            ];
    
            echo json_encode($json_array);
    
            // Close MySQLi connection
            $mysqli->close();
    
            break;

	case 'get_data':
        $table = 'establishment_registration';
        $unique_id = $_POST['uniqueId'] ?? '';

        // Ensure `uniqueId` is provided
        if (empty($unique_id)) {
            echo json_encode([
                "status" => false,
                "data" => [],
                "error" => "Unique ID is missing",
                "msg" => "Invalid request"
            ]);
            break;
        }

        $table_details = [
            $table,
            [
		"unique_id",
                "staff_name",
                "gender_name",
                "dob",
                "mobile_num",
                "ifhrms_id",
                "aadhaar_no",
                "district_name",
                "designation",
                "warden_category",
                "district_office",
                "taluk_office",
                "hostel_name",
                "status",
                "status_upd_date",
                "reject_reason",
                "is_active",
                "is_delete"
            ]
        ];
        $select_where = 'is_delete = 0 AND unique_id = "'. $unique_id .'"';

      
        $action_obj = $pdo->select($table_details, $select_where);

        if ($action_obj->status) {
            $json_array = [
                "status" => $action_obj->status,
                "data" => $action_obj->data,
                "error" => "",
                "msg" => "Data retrieved successfully",
            ];
        } else {
            $json_array = [
                "status" => $action_obj->status,
                "data" => [],
                "error" => $action_obj->error,
                "msg" => "Error retrieving data",
            ];
        }

        echo json_encode($json_array);
        break;





    case 'district_name':

        $district_name = $_POST['district_name'];


        $district_name_options = taluk_name(' ', $district_name);

        $taluk_name_options = select_option($district_name_options, "Select Taluk");

        echo $taluk_name_options;
        // print_r($taluk_name_options);

        break;

    case 'get_hostel_by_taluk_name':

        $taluk_name = $_POST['taluk_name'];


        $hostel_name_options = hostel_name('', $taluk_name);

        $hostel_name_options = select_option_host($hostel_name_options, "Select Hostel");
        // print_r( $hostel_name_options);

        echo $hostel_name_options;

        break;


    case 'get_district_new_name':

        $district_name = $_POST['district_name_new'];


        $district_name_options = taluk_name(' ', $district_name);

        $taluk_name_options = select_option($district_name_options, "Select Taluk");

        echo $taluk_name_options;

        break;

    case 'get_hostel_by_new_name':

        $taluk_name_new = $_POST['taluk_name_new'];


        $hostel_name_options = hostel_name(' ', $taluk_name_new);



        $hostel_name_options = select_option($hostel_name_options, "Select Hostel");
        // print_r( $hostel_name_options);die();

        echo $hostel_name_options;

        break;
}

