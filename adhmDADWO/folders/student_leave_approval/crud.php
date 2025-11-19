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
$ses_district_id = $_SESSION["district_id"];

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

        $approval_status = $_POST["approval_status"];
        $reject_reason = $_POST["reject_reason"];
        $warden_name = $_POST["warden_name"];
        $unique_id = $_POST["unique_id"];


        $columns = [
            "warden_name" => $warden_name,
            "approval_status" => $approval_status,
            "reject_reason" => $reject_reason
        ];

        if ($unique_id) {

            unset($columns['unique_id']);

            $update_where = [
                "unique_id" => $unique_id
            ];

            $action_obj = $pdo->update($table, $columns, $update_where);

            // Update Ends
        }
        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;
            if ($unique_id) {
                $msg = "update";
            } else {
                $msg = "insert";
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
        $where = "is_delete = ? and district_name = ?";
        $order_by = ""; // Modify as needed
        $is_delete = "0";

        // Prepare conditions for bind_param
        $bind_params = "sss"; // Types of parameters (s for string)

        // Initialize array for bind_param values
        $bind_values = [$start, $is_delete, $ses_district_id];

        // Additional conditions
        $approval_status = $_POST['approval_status'];
        $academic_year = $_POST['academic_year'];

        if ($approval_status) {
            $where .= " AND approval_status = ?";
            $bind_params .= "i"; // Add type for integer parameter
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
                // Modify approval_status as per your requirement
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
                        $status_text = '';
                        $status_color = '';
                        break;
                }

                // Assigning color to status
                $value['approval_status'] = '<span style="color: ' . $status_color . ';">' . $status_text . '</span>';

                $unique_id = $value['unique_id'];
                $eye_button = '<a class="btn btn-action specl2"  href="javascript:leave_print(\'' . $unique_id . '\')"><button type="button" ><i class="fa fa-eye" ></i></button></a>';

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

    default:

        break;
}
