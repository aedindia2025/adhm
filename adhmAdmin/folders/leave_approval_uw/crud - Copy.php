<?php
// Get folder Name From Currnent Url 
$folder_name        = explode("/", $_SERVER['PHP_SELF']);
$folder_name        = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table             = "leave_application";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action             = $_POST['action'];
$userid = $_SESSION['user_id'];
// print_r("$userid");
$infrastructure_types          = "";
$is_active          = "";
$unique_id          = "";
$prefix             = "";

$data               = "";
$msg                = "";
$error              = "";
$status             = "";
$test               = ""; // For Developer Testing Purpose



switch ($action) {

    case 'approval_create':

        $status          = $_POST["status"];
        $description          = $_POST["description"];
        $warden_name          = $_POST["warden_name"];
        $unique_id          = $_POST["unique_id"];


        $columns = [
            "warden_name" => $warden_name,
            "status" => $status,
            "description" => $description
        ];

        if ($unique_id) {

            unset($columns['unique_id']);

            $update_where   = [
                "unique_id"     => $unique_id
            ];

            $action_obj     = $pdo->update($table, $columns, $update_where);

            // Update Ends
        }
        if ($action_obj->status) {
            $status     = $action_obj->status;
            $data       = $action_obj->data;
            $error      = "";
            $sql        = $action_obj->sql;
            if ($unique_id) {
                $msg        = "update";
            } else {
                $msg        = "insert";
            }
        } else {
            $status     = $action_obj->status;
            $data       = $action_obj->data;
            $error      = $action_obj->error;
            $sql        = $action_obj->sql;
            $msg        = "error";
        }
        // }
        $json_array   = [
            "status"    => $status,
            "data"      => $data,
            "error"     => $error,
            "msg"       => $msg,
            "sql"       => $sql
        ];
        echo json_encode($json_array);
        break;

    case 'datatable':
        // DataTable Variables
        $search     = $_POST['search']['value'];
        $length     = $_POST['length'];
        $start      = $_POST['start'];
        $draw       = $_POST['draw'];
        $limit      = $length;

        $data       = [];

        if ($length == '-1') {
            $limit  = "";
        }

        // Query Variables
        $json_array     = "";
        $columns        = [
            "'' as  s_no",
            "student_id",
            "student_name",
            "no_of_days",
            "reason",
            "unique_id",
            "from_date ",
            "to_date",
        ];
        $table_details  = [
            $table . " , (SELECT @a:= " . $start . ") AS a ",
            $columns
        ];
        $where          = "is_delete = 0";

        if ($status != '') {
            $where .= "AND status ='" . $status . "'";
        }
        if ($academic_year != '') {
            $where .= "AND academic_year ='" . $academic_year . "'";
        }


        $order_by       = "";


        if ($_POST['search']['value']) {
            $where .= " AND student_id LIKE '" . mysql_like($_POST['search']['value']) . "' ";
        }

        // Datatable Searching
        $search         = datatable_searching($search, $columns);

        if ($search) {
            if ($where) {
                $where .= " AND ";
            }

            $where .= $search;
        }



        $sql_function   = "SQL_CALC_FOUND_ROWS";

        $result         = $pdo->select($table_details, $where, $limit, $start, $order_by, $sql_function);
        // print_r($result);
        $total_records  = total_records();

        if ($result->status) {

            $res_array      = $result->data;
            $sno = 1;
            foreach ($res_array as $key => $value) {

                $value['s_no'] = $sno++;


                $unique_id = $value['unique_id'];

                $value['unique_id'] = '<a class="btn btn-action specl2"  href="javascript:print(\'' . $unique_id . '\')"><button type="button" ><i class="fa fa-eye" ></i></button></a>';

                $data[]             = array_values($value);
            }

            $json_array = [
                "draw"              => intval($draw),
                "recordsTotal"      => intval($total_records),
                "recordsFiltered"   => intval($total_records),
                "data"              => $data,
                "testing"           => $result->sql
            ];
        } else {
            print_r($result);
        }

        echo json_encode($json_array);
        break;



    case 'delete':

        $unique_id      = $_POST['unique_id'];

        $columns        = [
            "is_delete"   => 1
        ];

        $update_where   = [
            "unique_id"     => $unique_id
        ];

        $action_obj     = $pdo->update($table, $columns, $update_where);

        if ($action_obj->status) {
            $status     = $action_obj->status;
            $data       = $action_obj->data;
            $error      = "";
            $sql        = $action_obj->sql;
            $msg        = "success_delete";
        } else {
            $status     = $action_obj->status;
            $data       = $action_obj->data;
            $error      = $action_obj->error;
            $sql        = $action_obj->sql;
            $msg        = "error";
        }

        $json_array   = [
            "status"    => $status,
            "data"      => $data,
            "error"     => $error,
            "msg"       => $msg,
            "sql"       => $sql
        ];

        echo json_encode($json_array);
        break;

    default:

        break;
}
