<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];


// // Database Country Table Name
$table = "staff_leave_application";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action = $_POST['action'];
$ses_hostel_id = $_SESSION['hostel_id'];

$infrastructure_types = "";
//$is_active = "";
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

        $vali_staff_id = filter_input(INPUT_POST, 'staff_id', FILTER_SANITIZE_STRING);
        $vali_staff_name = filter_input(INPUT_POST, 'staff_name', FILTER_SANITIZE_STRING);
        $vali_from_date = filter_input(INPUT_POST, 'from_date', FILTER_SANITIZE_STRING);
        $vali_to_date = filter_input(INPUT_POST, 'to_date', FILTER_SANITIZE_STRING);
        $vali_reason = filter_input(INPUT_POST, 'reason', FILTER_SANITIZE_STRING);
        $vali_no_of_days = filter_input(INPUT_POST, 'no_of_days', FILTER_SANITIZE_STRING);
        $vali_status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
        $vali_district_id = filter_input(INPUT_POST, 'district_id', FILTER_SANITIZE_STRING);
        $vali_taluk_id = filter_input(INPUT_POST, 'taluk_id', FILTER_SANITIZE_STRING);
        $vali_hostel_id = filter_input(INPUT_POST, 'hostel_id', FILTER_SANITIZE_STRING);
        $vali_academic_year = filter_input(INPUT_POST, 'academic_year', FILTER_SANITIZE_STRING);

        if (!$vali_staff_name || !$vali_from_date || !$vali_to_date || !$vali_reason || !$vali_no_of_days
            || !$vali_status || !$vali_district_id || !$vali_taluk_id || !$vali_academic_year || !$vali_hostel_id
        ) {
            $msg = "form_alert";
        } else {
            $staff_id = sanitizeInput($_POST["staff_id"]);
            $staff_name = sanitizeInput($_POST["staff_name"]);
            $from_date = sanitizeInput($_POST["from_date"]);
            $to_date = sanitizeInput($_POST["to_date"]);
            $reason = sanitizeInput($_POST["reason"]);
            $no_of_days = sanitizeInput($_POST["no_of_days"]);
            $approval_status = sanitizeInput($_POST["status"]);
            $district_name = sanitizeInput($_POST["district_id"]);
            $taluk_name = sanitizeInput($_POST["taluk_id"]);
            $hostel_name = sanitizeInput($_POST["hostel_id"]);
            $academic_year = sanitizeInput($_POST["academic_year"]);
            $unique_id = sanitizeInput($_POST["unique_id"]);

            if ($unique_id) {
                // Update existing record
                $stmt = $mysqli->prepare("UPDATE $table SET staff_id = ?, staff_name = ?, from_date = ?, to_date = ?, reason = ?, no_of_days = ?, approval_status = ?, district_name = ?, taluk_name = ?, hostel_name = ?, academic_year = ? WHERE unique_id = ?");
                $stmt->bind_param("ssssssssssss", $staff_id, $staff_name, $from_date, $to_date, $reason, $no_of_days, $approval_status, $district_name, $taluk_name, $hostel_name, $academic_year, $unique_id);
            } else {
                // Insert new record
                // $unique_id = unique_id($prefix);
                $stmt = $mysqli->prepare("INSERT INTO $table (staff_id, staff_name, from_date, to_date, reason, no_of_days, approval_status, district_name, taluk_name, hostel_name, academic_year, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssssssss", $staff_id, $staff_name, $from_date, $to_date, $reason, $no_of_days, $approval_status, $district_name, $taluk_name, $hostel_name, $academic_year, unique_id($prefix));
            }

            if ($stmt->execute()) {
                $status = true;
                $data = null;
                $error = "";
                $sql = ""; // For security reasons, not showing SQL
                $msg = $unique_id ? "update" : "create";
            } else {
                $status = false;
                $data = null;
                $error = $stmt->error;
                $sql = "";
                $msg = "error";
            }

            $stmt->close();
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



    case 'datatable':

       

        // DataTable Variables
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        
        $limit = $length == '-1' ? "" : $length;

        $data = [];

        // Query Variables
        $json_array = "";
        $columns = [
            "@a:=@a+1 s_no",
            "staff_id",
            "staff_name",
            "from_date",
            "to_date",
            "no_of_days",
            "reason",
            "approval_status",
            "unique_id"
        ];
        $table_details = $table . " , (SELECT @a:= ?) AS a ";
        $where = "is_delete = 0 and hostel_name = ?";
        $order_by = ""; // You can modify this to add an order by clause if needed

        $sql_function = "SQL_CALC_FOUND_ROWS";

        // SQL query for data fetching
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_details WHERE $where";
        if ($limit) {
            $sql .= " LIMIT ?, ?";
        }

        $stmt = $mysqli->prepare($sql);
        if ($limit) {
            $stmt->bind_param("isii", $start, $ses_hostel_id, $start, $limit);
        } else {
            $stmt->bind_param("is", $start, $ses_hostel_id);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch total records
        $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
        $total_records = $total_records_result->fetch_assoc()['total'];

        if ($result) {
            $res_array = $result->fetch_all(MYSQLI_ASSOC);

            foreach ($res_array as $key => $value) {
                $value['staff_id'] = disname($value['staff_id']);
                $value['staff_name'] = disname($value['staff_name']);
                $value['applied_date'] = disdate($value['applied_date']);
                $value['reason'] = disname($value['reason']);

                $status_text = '';
                $status_color = '';
                switch ($value['approval_status']) {
                    case 1:
                        $status_text = 'Pending';
                        $status_color = 'blue';
                        break;
                    case 2:
                        $status_text = 'Approved';
                        $status_color = 'green';
                        break;
                    case 3:
                        $status_text = 'Rejected';
                        $status_color = 'red';
                        break;
                    default:
                        break;
                }

                $value['approval_status'] = '<span style="color: ' . $status_color . ';">' . $status_text . '</span>';
               
                $unique_id = $value['unique_id'];
                $btn_update = btn_update($folder_name, $value['unique_id']);
                $btn_delete = btn_delete($folder_name, $value['unique_id']);
                $eye_button = '<a class="btn btn-action specl2-icon" href="javascript:leave_print(\'' . $unique_id . '\')"><button type="button"><i class="fa fa-eye"></i></button></a>';

                if ($status_text == 'Pending') {
                    $value['unique_id'] = $btn_update . $btn_delete . $eye_button;
                } else {
                    $value['unique_id'] = $eye_button;
                }

                $data[] = array_values($value);
            }

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data,
            ];
        } else {
            // Handle the error case
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => $stmt->error,
            ];
        }

        echo json_encode($json_array);

        // Close connection
        $stmt->close();
        $mysqli->close();

        break;


    case 'delete':

        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }
        
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

// $user_type          = $_POST["user_type"];
// $is_active          = $_POST["is_active"];
// $unique_id          = $_POST["unique_id"];

// $update_where       = "";

// //count user_type
// if($unique_id == ''){
//     $get_user_type=$pdo_conn->prepare("SELECT count(*) FROM `user_type` WHERE `user_type`= '".$user_type."' and `is_delete` != '1'");
// }else{
//     $get_user_type=$pdo_conn->prepare("SELECT count(*) FROM `user_type` WHERE `user_type`= '".$user_type."' and `is_delete` != '1' and `unique_id` != '".$unique_id."'" );
// }

// $get_user_type->execute();
// $user_type_count  = $get_user_type->fetchColumn();    

// if($user_type_count == 0){


//     if($unique_id == ''){//insert
//         $unique_id = uniqid().rand(10000,99999);

//         if($prefix) {
//             $unique_id = $prefix.$unique_id;
//         }

//         $Insql=$pdo_conn->prepare("insert into user_type (`unique_id`,`user_type`, `is_active`, `is_delete`)values('".$unique_id."','".$user_type."','1','0')");
//         $Insql->execute();
//         $msg = "Created";
//         echo $msg;
//     }else{//update
//         $Insql=$pdo_conn->prepare("UPDATE `user_type` SET `user_type`= '".$user_type."',`is_active`= '".$is_active."' WHERE unique_id = '".$unique_id."'");

//         $Insql->execute();
//         $msg  = "Updated";
//         echo $msg;
//     }
// }else{ 
//     $msg  = "already";
//     echo $msg;
// }
//     break;
//     case 'delete' :
//         $unique_id          = $_POST["unique_id"];
//         $del_sql=$pdo_conn->prepare("update user_type set is_delete='1' where unique_id ='".$unique_id."'" );
//         $del_sql->execute();
//             $msg  = "success_delete";
//             echo $msg;

//     break;
//     default:

//     break;
// }
