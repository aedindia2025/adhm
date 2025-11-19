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

        // Validate and sanitize input
        $vali_district_name = filter_input(INPUT_POST, 'district_name', FILTER_SANITIZE_STRING);
        $vali_taluk_name = filter_input(INPUT_POST, 'taluk_name', FILTER_SANITIZE_STRING);
        $vali_hostel_name = filter_input(INPUT_POST, 'hostel_name', FILTER_SANITIZE_STRING);
        $vali_student_name = filter_input(INPUT_POST, 'student_name', FILTER_SANITIZE_STRING);
        $vali_std_reg_no = filter_input(INPUT_POST, 'std_reg_no', FILTER_SANITIZE_STRING);
        $vali_student_class = filter_input(INPUT_POST, 'student_class', FILTER_SANITIZE_STRING);
        $vali_employment_course = filter_input(INPUT_POST, 'employment_course', FILTER_SANITIZE_STRING);
        $vali_job = filter_input(INPUT_POST, 'job', FILTER_SANITIZE_STRING);
        $vali_course = filter_input(INPUT_POST, 'course', FILTER_SANITIZE_STRING);
        $vali_is_active = filter_input(INPUT_POST, 'is_active', FILTER_SANITIZE_STRING);
        // Validate required fields
        if (!$vali_district_name || !$vali_taluk_name || !$vali_hostel_name || !$vali_student_name || !$vali_std_reg_no || !$vali_student_class || !$vali_employment_course) {
            $msg = "form_alert";

        } else {

            // Sanitize inputs
           
	    $district_name = !empty($_POST["district_name"]) ? sanitizeInput($_POST["district_name"]) : null;
$taluk_name = !empty($_POST["taluk_name"]) ? sanitizeInput($_POST["taluk_name"]) : null;
$hostel_name = !empty($_POST["hostel_name"]) ? sanitizeInput($_POST["hostel_name"]) : null;
$student_name = !empty($_POST["student_name"]) ? sanitizeInput($_POST["student_name"]) : null;
$student_id = !empty($_POST["std_reg_no"]) ? sanitizeInput($_POST["std_reg_no"]) : null;
$student_class = !empty($_POST["student_class"]) ? sanitizeInput($_POST["student_class"]) : null;
$emp_cr = !empty($_POST["employment_course"]) ? sanitizeInput($_POST["employment_course"]) : null;
$job = !empty($_POST["job"]) ? sanitizeInput($_POST["job"]) : null;
$course = !empty($_POST["course"]) ? sanitizeInput($_POST["course"]) : null;
            $is_active = $_POST["is_active"];
            $unique_id = $_POST["unique_id"];

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
                "unique_id" => unique_id($prefix)
            ];


            $select_where = 'is_delete = 0 AND student_id = ?';
            if ($unique_id) {
                $select_where .= ' AND unique_id != ?';
            }

            $stmt = $mysqli->prepare("SELECT COUNT(unique_id) AS count FROM $table WHERE $select_where");

            if ($unique_id) {
                $stmt->bind_param('ss', $student_id, $unique_id);
            } else {
                $stmt->bind_param('s', $student_id);
            }

            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            $stmt->close();


            if ($data['count']) {
                $msg = "already";
            } else if ($data['count'] == 0) {
                if ($unique_id) {

                    unset($columns['unique_id']);

                    $update_set = [];
                    $params = [];
                    $types = '';
                    foreach ($columns as $key => $value) {
                        $update_set[] = "$key = ?";
                        $params[] = $value;
                        $types .= 's';
                    }
                    $params[] = $unique_id;
                    $types .= 's';

                    $sql = "UPDATE $table SET " . implode(', ', $update_set) . " WHERE unique_id = ?";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param($types, ...$params);
                    $stmt->execute();
                    $status = $stmt->affected_rows > 0;
                    $stmt->close();
                    $msg = $status ? "update" : "error";
                } else {

                    $insert_columns = implode(", ", array_keys($columns));
                    $insert_values = implode(", ", array_fill(0, count($columns), '?'));
                    $params = array_values($columns);
                    $types = str_repeat('s', count($params));


                    $sql = "INSERT INTO $table ($insert_columns) VALUES ($insert_values)";

                    $stmt = $mysqli->prepare($sql);

                    $stmt->bind_param($types, ...$params);
                    $stmt->execute();

                    $status = $stmt->affected_rows > 0;
                    $stmt->close();
                    $msg = $status ? "create" : "error";
                }
            }
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $mysqli->error,
            "msg" => $msg,
        ];

        echo json_encode($json_array);

        $mysqli->close();

        break;




    case 'datatable':
        // Database connection using MySQLi
      

        // DataTable Variables
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length == '-1' ? "" : $length;

        $data = [];

        // Query Variables
        $columns = [
            "@a:=@a+1 s_no",
            "student_name",
            "student_id",
            "(SELECT std_reg_no FROM std_reg_s WHERE std_reg_s.unique_id = carrier_path_creation.student_id) AS student_id",
            "employment_course",
            "job",
            "course",
            "unique_id"
        ];
        $table_details = "carrier_path_creation, (SELECT @a:= ?) AS a ";
        $where = "is_delete = ? AND hostel_name = ?";
        $order_by = ""; // You can modify this to add an order by clause if needed
        $is_delete = "0";
        $sql_function = "SQL_CALC_FOUND_ROWS";

        // SQL query for data fetching
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_details WHERE $where";
        if ($limit) {
            $sql .= " LIMIT ?, ?";
        }

        $stmt = $mysqli->prepare($sql);

        // Bind parameters
        if ($limit) {
            $stmt->bind_param("iissi", $start, $is_delete, $_SESSION['hostel_id'], $start, $limit);
        } else {
            $stmt->bind_param("iis", $start, $is_delete, $_SESSION['hostel_id']);
        }

        // Execute query
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch total records
        $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
        $total_records = $total_records_result->fetch_assoc()['total'];

        // Process results
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $row['is_active'] = is_active_show($row['is_active']);

                $unique_id = $row['unique_id'];
                $btn_update = btn_update($folder_name, $row['unique_id']);
                if (empty($row['job'])) {
                    $row['job'] = '-';
                }

                if (empty($row['course'])) {
                    $row['course'] = '-';
                }

                $eye_button = '<a class="btn btn-action specl2-icon"  href="javascript:carrier_print(\'' . $unique_id . '\')"><button type="button" ><i class="fa fa-eye" ></i></button></a>';

                $row['unique_id'] = $btn_update . $eye_button;

                $data[] = array_values($row);
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
                "error" => $mysqli->error
            ];
        }

        // Output JSON
        echo json_encode($json_array);

        // Close statement and connection
        $stmt->close();
        $mysqli->close();

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

        // Validate $student_id if needed

        // Assuming $mysqli is your MySQLi database connection
        $table = "std_reg_s2";
        $columns = ["std_name"];
        $is_delete = '0';

        // Build SQL query with parameterized statement
        $sql = "SELECT " . implode(", ", $columns) . " FROM $table WHERE is_delete = ? AND s1_unique_id = ?";
        $stmt = $mysqli->prepare($sql);

        if ($stmt === false) {
            die('MySQL prepare error: ' . $mysqli->error);
        }

        // Bind parameter
        $stmt->bind_param("ss", $is_delete, $student_id);

        // Execute statement
        $stmt->execute();

        // Bind result variables
        $stmt->bind_result($std_name);

        // Fetch result
        $stmt->fetch();

        // Close statement
        $stmt->close();

        if ($std_name !== null) {
            $json_array = [
                "student_name" => $std_name
            ];
        } else {
            $json_array = [
                "error" => "Student ID not found or query error"
            ];
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

        // Prepare an SQL statement
        $stmt = $mysqli->prepare("UPDATE $table SET is_delete = ? WHERE unique_id = ?");

        if ($stmt) {
            $is_delete = 1;
            $stmt->bind_param("is", $is_delete, $unique_id);

            // Execute the statement
            if ($stmt->execute()) {
                $status = true;
                $data = null;
                $error = "";
                $sql = ""; // For security reasons, not showing SQL
                $msg = "success_delete";
            } else {
                $status = false;
                $data = null;
                $error = $stmt->error;
                $sql = "";
                $msg = "error";
            }

            $stmt->close();
        } else {
            $status = false;
            $data = null;
            $error = $mysqli->error;
            $sql = "";
            $msg = "error";
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql" => $sql // Not returning SQL for security reasons
        ];

        echo json_encode($json_array);

        break;


    default:

        break;
}




?>