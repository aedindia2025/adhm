<?php

// Get folder Name From Currnent Url
$folder_name = explode('/', $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = 'dropout';

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action = $_POST['action'];
// $action_obj         = (object) [
//     "status"    => 0,
//     "data"      => "",
//     "error"     => "Action Not Performed"
// ];

$json_array = '';
$sql = '';

$feedback_type = '';
$is_active = '';
$unique_id = '';
$prefix = '';

$data = '';
$msg = '';
$error = '';
$status = '';
$test = ''; // For Developer Testing Purpose

switch ($action) {
    case 'datatable':

        // DataTable Variables
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length == '-1' ? "" : $length;

        $data = [];


        // Query Variables
        $json_array = '';
        $columns = [
            '@a:=@a+1 s_no',
            'student_name',
            '(select std_reg_no from std_reg_s where std_reg_s.unique_id = dropout.student_id) as student_id',
            'reason',
            // "is_active",
            'unique_id',
        ];
        $table_details = $table . " , (SELECT @a:= ?) AS a ";
        $where = "is_delete = 0 and taluk_id = ?";
        $order_by = ""; // You can modify this to add an order by clause if needed

        $sql_function = "SQL_CALC_FOUND_ROWS";

        // SQL query for data fetching
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_details WHERE $where";
        if ($limit) {
            $sql .= " LIMIT ?, ?";
        }
$taluk_name = $_SESSION['taluk_id'];
        $stmt = $mysqli->prepare($sql);
        if ($limit) {
            $stmt->bind_param("isii", $start, $taluk_name, $start, $limit);
        } else {
            $stmt->bind_param("is", $start, $taluk_name);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch total records
        $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
        $total_records = $total_records_result->fetch_assoc()['total'];


        if ($result) {
            $res_array = $result->fetch_all(MYSQLI_ASSOC);

            foreach ($res_array as $key => $value) {
                $unique_id = $value['unique_id'];
                $btn_update = btn_update($folder_name, $value['unique_id']);
                $btn_delete = btn_delete($folder_name, $value['unique_id']);
                $eye_button = '<a class="btn btn-action specl2"  href="javascript:leave_print(\''.$unique_id.'\')"><button type="button" ><i class="fa fa-eye" ></i></button></a>';

                // $value['unique_id'] = $btn_update.$btn_delete;

                // if ($value['is_active'] == "1") {
                //     $value['is_active']   = "Active";
                // } else {

                //     $value['is_active']   = "Inactive";
                // }

                // if ( $value['unique_id'] == "5f97fc3257f2525529") {
                //     $btn_update         = "";
                //     $btn_delete         = "";
                // }

                $value['unique_id'] = $eye_button;
                $data[] = array_values($value);
            }

            $json_array = [
                'draw' => intval($draw),
                'recordsTotal' => intval($total_records),
                'recordsFiltered' => intval($total_records),
                'data' => $data,
                // "testing"           => $result->sql
            ];
        } else {
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => $stmt->error,
                "testing" => $stmt->sqlstate
            ];
        }

        echo json_encode($json_array);
        break;

    case 'delete':
        $unique_id = $_POST['unique_id'];

        $columns = [
            'is_delete' => 1,
        ];

        $update_where = [
            'unique_id' => $unique_id,
        ];

        $action_obj = $pdo->update($table, $columns, $update_where);

        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = '';
            $sql = $action_obj->sql;
            $msg = 'success_delete';
        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = 'error';
        }

        $json_array = [
            'status' => $status,
            'data' => $data,
            'error' => $error,
            'msg' => $msg,
            'sql' => $sql,
        ];

        echo json_encode($json_array);
        break;

    default:
        break;
}
