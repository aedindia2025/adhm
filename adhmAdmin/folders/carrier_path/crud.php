<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "carrier_path_creation";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action = $_POST['action'];

$feedback_type = "";
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

        $district_name = $_POST["district_name"];
        $taluk_name = $_POST["taluk_name"];
        $hostel_name = $_POST["hostel_name"];
        // $emp_cr = $_POST["employment_course"];

        $student_name = $_POST["student_name"];
        $student_id = $_POST["std_reg_no"];
        $student_class = $_POST["student_class"];
        $emp_cr = $_POST["employment_course"];
        $job = $_POST["job"];
        $course = $_POST["course"];
        $is_active = $_POST["is_active"];
        $unique_id = $_POST["unique_id"];

        $update_where = "";

        $columns = [

            "student_name" => $student_name,
            "student_id" => $student_id,
            "student_class" => $student_class,
            "employment_course" => $emp_cr,
            "job" => $job,
            "course" => $course,
            "qualification" => $student_class,
            "district_name" => $district_name,
            "taluk_name" => $taluk_name,
            "hostel_name" => $hostel_name,
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
        $select_where = 'is_delete = 0 and student_id = "' . $student_id . '"';

        // When Update Check without current id
        if ($unique_id) {
            $select_where .= ' AND unique_id !="' . $unique_id . '" ';
        }

        $action_obj = $pdo->select($table_details, $select_where);

        // print_r($action_obj);

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
        if ($data[0]["count"]) {
            $msg = "already";
        } else if ($data[0]["count"] == 0) {
            // Update Begins
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
        }


        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql" => $sql
        ];

        echo json_encode($json_array);

        break;

    case 'datatable':

        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;

        $district_name = sanitizeInput($_POST["district_name"]);
        $taluk_name = sanitizeInput($_POST["taluk_name"]);
        $hostel_name = sanitizeInput($_POST["hostel_name"]);

        $data = [];

        if ($length == '-1') {
            $limit = "";
        }

        // Query Variables
        $json_array = "";
        $columns = [
            "@a:=@a+1 s_no",
            "student_name",
            "(SELECT std_reg_no FROM std_reg_s WHERE std_reg_s.unique_id = carrier_path_creation.student_id) AS student_id",
            "employment_course",
            "job",
            "course",
            "unique_id"
        ];
        $table = "carrier_path_creation";
        $table_details = "$table, (SELECT @a:= ?) AS a";
        $where = "is_delete = 0";
        $bind_params = "i"; // Initial bind_param type for the @a:= start
        $bind_values = [intval($start)]; // Initial bind_param value for the @a:= start

        // Conditional bindings
        if (!empty($district_name)) {
            $where .= " AND district_name = ?";
            $bind_params .= "s"; // Assuming district_name is a string
            $bind_values[] = $district_name;
        }
        if (!empty($taluk_name)) {
            $where .= " AND taluk_name = ?";
            $bind_params .= "s"; // Assuming taluk_name is a string
            $bind_values[] = $taluk_name;
        }
        if (!empty($hostel_name)) {
            $where .= " AND hostel_name = ?";
            $bind_params .= "s"; // Assuming hostel_name is a string
            $bind_values[] = $hostel_name;
        }

        $order_by = "";

        $sql_function = "SQL_CALC_FOUND_ROWS";

        // Prepare SQL query
        $query = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_details WHERE $where";

        if (!empty($limit)) {
            $query .= " LIMIT ?, ?";
            $bind_params .= "ii"; // Add types for integer parameters
            $bind_values[] = intval($start);
            $bind_values[] = intval($length);
        }

        // Prepare statement
        $stmt = $mysqli->prepare($query);

        if ($stmt === false) {
            die('Prepare statement failed: ' . $mysqli->error);
        }

        // Bind parameters dynamically
        $stmt->bind_param($bind_params, ...$bind_values);

        // Execute query
        $stmt->execute();

        // Get result
        $result = $stmt->get_result();

        // Check if query was successful
        if ($result) {
            $total_records_result = $mysqli->query("SELECT FOUND_ROWS() AS total_rows");
            $total_records = $total_records_result->fetch_assoc()['total_rows'];

            while ($row = $result->fetch_assoc()) {
                // Example function call
                $row['is_active'] = is_active_show($row['is_active']);

                $unique_id = $row['unique_id'];
                $eye_button = '<a class="btn btn-action specl2-icon" href="javascript:carrier_print(\'' . $unique_id . '\')"><button type="button"><i class="fa fa-eye"></i></button></a>';
                $row['unique_id'] = $eye_button;

                $data[] = array_values($row);
            }

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data,
                // "testing" => $stmt->sql // Uncomment for debugging purposes
            ];
        } else {
            die('Query execution failed: ' . $mysqli->error);
        }

        // Output JSON
        echo json_encode($json_array);

        // Close statement and connection
        $stmt->close();
        $mysqli->close();
        break;


    case 'district_name':

        $district_name = $_POST['district_name'];


        $district_name_options = taluk_name('', $district_name);

        $taluk_name_options = select_option($district_name_options, "Select Taluk");

        echo $taluk_name_options;

        break;

    case 'get_hostel_by_taluk_name':

        $taluk_name = $_POST['taluk_name'];


        $hostel_name_options = hostel_name('', $taluk_name);

        $hostel_name_options = select_option_host($hostel_name_options, "Select Hostel");

        echo $hostel_name_options;

        break;




    case 'students_details':

        $table_1 = "std_reg_p1";

        $json_array = "";
        // $today  =  date('Y-m-d');

        $student_name = $_POST['student_name'];


        $columns_1 = [
            "academic_year",
            "std_name",
            "std_reg_no",
            "hostel_name",
            "hostel_district",
            "hostel_taluk",
            "unique_id"

            // "actions"
        ];
        $table_details_1 = [
            $table_1,
            $columns_1
        ];

        // $where = "is_delete = 0 and std_name = '.$student_name'.'";

        $where = "is_delete = 0 AND unique_id = '" . $student_name . "'";



        $result = $pdo->select($table_details_1, $where);

        // print_r($result);die();

        if ($result->status) {

            $res_array = $result->data;

            foreach ($res_array as $key => $value) {

                $acc_year = $value["academic_year"];

                $student_name = $value["std_name"];

                $std_reg_no = $value["std_reg_no"];

                $hostel_name = $value["hostel_name"];


                $hostel_district = $value["hostel_district"];

                $hostel_taluk = $value["hostel_taluk"];



                $data[] = array_values($value);

            }
            $json_array = [

                "data" => $data,
                "student_details" => $res_array,

                "std_name" => $student_name,

                "std_reg_no" => $std_reg_no,

                "hostel_name" => $hostel_name,
                "hostel_district" => $hostel_district,
                "hostel_taluk" => $hostel_taluk
            ];

            // echo json_encode($json_array);

        } else {
            // print_r($result);
        }

        echo json_encode($json_array);
        break;

    case 'get_std_name':

        $student_id = $_POST['student_id'];


        // Query Variables
        $json_array = "";
        $columns = [

            "std_name"

        ];
        $table_details = [
            "std_reg_p1",
            $columns
        ];
        $where = "is_delete = 0 and unique_id = '" . $student_id . "'";




        // $sql_function   = "SQL_CALC_FOUND_ROWS";

        $result = $pdo->select($table_details, $where);
        $total_records = total_records();

        if ($result->status) {

            $res_array = $result->data;

            foreach ($res_array as $key => $value) {


                $student_name = $value['std_name'];

                $data[] = array_values($value);
            }

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data,
                "student_name" => $student_name,
                "testing" => $result->sql
            ];
        } else {
            print_r($result);
        }

        echo json_encode($json_array);
        break;



    // case 'applied_leave_details':

    //     $table_leave ="leave_application";

    //     $json_array     = "";
    //     // $today  =  date('Y-m-d');

    //     $columns_leave       = [
    //         "from_date",
    //         "no_of_days",
    //         "approval_status",

    //         // "content",
    //         // "actions"
    //        ];
    //     $table_details_leave  = [
    //         $table_leave,
    //         $columns_leave,
    //     ];

    //     $where        = "is_delete = 0";

    //     // if($actions){
    //     //      $where .= "AND actions =65589f69ce65d32654";

    //     // }


    //     // $where .= " AND user_type
    //     // $order_by       = "date ASC";

    //     $sql_function   = "SQL_CALC_FOUND_ROWS";




    //     $result         = $pdo->select($table_details_leave,$where);
    //     // print_r($result);
    //     $total_records  = total_records();

    //     if ($result->status) {

    //         $res_array      = $result->data;

    //         foreach ($res_array as $key => $value) {

    //             $from_date = $value['from_date'];
    //             $no_of_days = $value['no_of_days'];

    //         if ($value['approval_status'] == 1) {
    //             $value['approval_status'] = 'Pending';
    //         }
    //         if ($value['approval_status'] == 2) {
    //             $value['approval_status'] = 'Rejected';
    //         }
    //         if ($value['approval_status'] == 3) {
    //             $value['approval_status'] = 'Approved';
    //         }

    //         $status_text = '';
    //         $status_color = '';
    //         switch ($value['approval_status']) {
    //             case 1:
    //                 $status_text = 'Approved';
    //                 $status_color = 'green';
    //                 break;
    //             case 2:
    //                 $status_text = 'Rejected';
    //                 $status_color = 'red';
    //                 break;
    //             default:
    //                 $status_text = 'Pending';
    //                 $status_color = 'blue';
    //                 break;
    //         }

    //         // Assigning color to status
    //         $value['approval_status'] = '<span style="color: ' . $status_color . ';">' . $status_text . '</span>';


    //     $res_array      = $result->data;
    //         }     
    //     $json_array = [
    //             "applied_leave_details" => $res_array,
    //             "from_date"             => $from_date,
    //             "no_of_days"            => $no_of_days,   
    //             "approval_status"    =>     $value['approval_status']
    //         ];

    //      echo json_encode($json_array);
    //     }


    // break;








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
            // "sql" => $sql
        ];

        echo json_encode($json_array);
        break;

    default:

        break;
}




?>