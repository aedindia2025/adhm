<?php
// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// Database Country Table Name
$table = "leave_application";

// Include DB file and Common Functions
include '../../config/dbconfig.php';



// Variables Declaration
$action = $_POST['action'];
$userid = $_SESSION['user_id'];
$ses_hostel_id = $_SESSION['hostel_id'];

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
    
        $vali_approval_status = filter_input(INPUT_POST, 'approval_status', FILTER_SANITIZE_STRING);
        $vali_reject_reason = filter_input(INPUT_POST, 'reject_reason', FILTER_SANITIZE_STRING);
        $vali_warden_name = filter_input(INPUT_POST, 'warden_name', FILTER_SANITIZE_STRING);
    
        if (!$vali_approval_status || !$vali_warden_name) {
            $msg = "form_alert";
        } else {
            $approval_status = sanitizeInput($_POST["approval_status"]);
            $reject_reason = !empty($_POST["reject_reason"]) ? sanitizeInput($_POST["reject_reason"]) : null;
            $warden_name = sanitizeInput($_POST["warden_name"]);
            $unique_id = sanitizeInput($_POST["unique_id"]);
    
            $columns = [
                "warden_name" => $warden_name,
                "approval_status" => $approval_status,
                "reject_reason" => $reject_reason
            ];
    
            if ($unique_id) {
                // Update record
                $sql = "UPDATE $table SET warden_name = ?, approval_status = ?, reject_reason = ? WHERE unique_id = ?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("ssss", $warden_name, $approval_status, $reject_reason, $unique_id);
            } else {
                // Insert record
                $sql = "INSERT INTO $table (warden_name, approval_status, reject_reason) VALUES (?, ?, ?)";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("sss", $warden_name, $approval_status, $reject_reason);
            }
    
            if ($stmt->execute()) {
                $status = true;
                $data = $stmt->affected_rows;
                $error = "";
                if ($unique_id) {
                    $msg = "update";
                } else {
                    $msg = "insert";
                }
            } else {
                $status = false;
                $data = [];
                $error = $stmt->error;
                $msg = "error";
            }
    
            $stmt->close();
        }
    
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
        ];
        echo json_encode($json_array);
    
        $mysqli->close();
    
        break;
    

        case 'datatable':
            // DataTable Variables
            $length = $_POST['length'];
            $start = $_POST['start'];
            $draw = $_POST['draw'];
            $limit = $length == '-1' ? "" : $length;
    
            $data = [];
    
            // Query Variables
            $columns = [
                "@a:=@a+1 s_no",
                "student_id",
                "student_name",
                "no_of_days",
                "reason",
                "approval_status",
                "unique_id"
            ];
            $table_details = $table . " , (SELECT @a:= ?) AS a ";
            $where = "is_delete = 0 AND hostel_name = ?";
            $order_by = ""; // Modify as needed
    
            // Prepare conditions for bind_param
            $bind_params = "ss"; // Types of parameters (s for string)
    
            // Initialize array for bind_param values
            $bind_values = [$start, $ses_hostel_id];
    
            // Additional conditions
            $approval_status = $_POST['approval_status'];
            $academic_year = $_POST['academic_year'];
    
            if ($approval_status) {
                $where .= " AND approval_status = ?";
                $bind_params .= "s"; // Add type for integer parameter
                $bind_values[] = $approval_status;
            }if ($academic_year) {
                $where .= " AND academic_year = ?";
                $bind_params .= "s"; // Add type for string parameter
                $bind_values[] = $academic_year;
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
                    $status_text = '';
                    $status_color = '';
                    switch ($value['approval_status']) {
                        case 2:
                            $status_text = 'Approved';
                            $status_color = 'green';
                            break;
                        case 3:
                            $status_text = 'Rejected';
                            $status_color = 'red';
                            break;
                        default:
                            $status_text = 'Pending';
                            $status_color = 'blue';
                            break;
                    }
        
                    // Assigning color to status
                    $value['approval_status'] = '<span style="color: ' . $status_color . ';">' . $status_text . '</span>';
        
                    $unique_id = $value['unique_id'];
                    $btn_update = btn_update($folder_name, $value['unique_id']);
                    $eye_button = '<a class="btn btn-action specl2"  href="javascript:leave_print(\'' . $unique_id . '\')"><button type="button" ><i class="fa fa-eye" ></i></button></a>';
        
                    $value['unique_id'] = $btn_update . $eye_button;
        
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

        // $token = $_POST['csrf_token'];

        // if (!validateCSRFToken($token)) {
        //     die('CSRF validation failed.');
        // }


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
            "sql" => $sql
        ];

        echo json_encode($json_array);
        break;

    default:

        break;
}
