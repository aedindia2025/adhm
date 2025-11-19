<?php
// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// Database Country Table Name
$table = "staff_leave_application";

// Include DB file and Common Functions
include '../../config/dbconfig.php';

// Variables Declaration
$action = $_POST['action'];
$userid = $_SESSION['user_id'];
$ses_taluk_id = $_SESSION['taluk_id'];

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

        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        // Retrieve POST parameters
        $approval_status = sanitizeInput($_POST["approval_status"]);
        $reject_reason = !empty($_POST["reject_reason"]) ? sanitizeInput($_POST["reject_reason"]) : null;
        $st_name = sanitizeInput($_POST["st_name"]);
        $unique_id = sanitizeInput($_POST["unique_id"]);

        // Prepare columns for update
        $columns = [
            "st_name" => $st_name,
            "approval_status" => $approval_status,
            "reject_reason" => $reject_reason
        ];

        $status = false;
        $data = [];
        $error = "";
        $msg = "";

        // Database table name
        $table = 'staff_leave_application';

        // Check if unique_id is provided for update
        if ($unique_id) {
            $update_where = [
                "unique_id" => $unique_id
            ];

            $update_query = "UPDATE $table SET ";
            $update_columns = [];
            $params = [];
            $param_types = '';

            foreach ($columns as $key => $value) {
                $update_columns[] = "$key = ?";
                $params[] = $value;
                $param_types .= 's';
            }
            $update_query .= implode(', ', $update_columns) . " WHERE unique_id = ?";
            $params[] = $unique_id;
            $param_types .= 's';

            $stmt = $mysqli->prepare($update_query);
            if ($stmt) {
                $stmt->bind_param($param_types, ...$params);
                $status = $stmt->execute();
                if ($status) {
                    $msg = "update";
                    $data = [
                        "affected_rows" => $stmt->affected_rows
                    ];
                } else {
                    $msg = "error";
                    $error = $stmt->error;
                }
                $stmt->close();
            } else {
                $msg = "error";
                $error = $mysqli->error;
            }
        } else {
            $msg = "error";
            $error = "Unique ID not provided.";
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
        $search = $_POST['search']['value'];
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length == '-1' ? "" : $length;

        $data = [];

        $approval_status = $_POST['approval_status'];
        $academic_year = $_POST['academic_year'];

        // Query Variables
        $columns = [
            "@a:=@a+1 s_no",
            "staff_id",
            "staff_name",
            "no_of_days",
            "reason",
            "approval_status",
            "unique_id"
        ];
        $table_details = $table . " , (SELECT @a:= ?) AS a ";
        $where = "is_delete = 0 and taluk_name = ?";
        $order_by = ""; // Modify as needed

        // Prepare conditions for bind_param
        $bind_params = "is"; // Types of parameters (i for integer, s for string)
        $bind_values = [$start, $ses_taluk_id];

        // Additional conditions
        if ($approval_status) {
            $where .= " AND approval_status = ?";
            $bind_params .= "i";
            $bind_values[] = $approval_status;
        }
        if ($academic_year) {
            $where .= " AND academic_year = ?";
            $bind_params .= "s";
            $bind_values[] = $academic_year;
        }

        $sql_function = "SQL_CALC_FOUND_ROWS";

        // SQL query for data fetching
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_details WHERE $where";
        if ($limit) {
            $sql .= " LIMIT ?, ?";
            $bind_params .= "ii";
            $bind_values[] = $start;
            $bind_values[] = $limit;
        }

        $stmt = $mysqli->prepare($sql);
        if ($stmt) {
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

            $stmt->close();
        } else {
            // Handle the error case for statement preparation failure
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => $mysqli->error
            ];
        }

        echo json_encode($json_array);

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

    default:

        break;
}
