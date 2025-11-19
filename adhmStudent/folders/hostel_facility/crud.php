<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "hostel_facility";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';



// // Variables Declaration
$action = $_POST['action'];

$district_id = "";
$taluk_id = "";
$entry_date = "";
$hostel_name = "";
$facility_type = "";
$facility_name = "";
$received_date = "";
$user_name = "";
// $curr_date_time     = "";
$acc_year = "";
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

        $student_name = sanitizeInput($_POST["student_name"]);

        $district_id = $_POST["district_id"];
        $taluk_id = $_POST["taluk_id"];
        $entry_date = $_POST["entry_date"];
        $hostel_name = $_POST["hostel_name"];
        $facility_type = disname($_POST["facility_type"]);
        $facility_name = $_POST["facility_name"];
        $received_date = $_POST["received_date"];
        $user_name = $_POST["user_name"];
        // $curr_date_time             = $_POST["curr_date_time"];
        $acc_year = $_POST["acc_year"];
        $is_active = $_POST["is_active"];
        $unique_id = $_POST["unique_id"];

        $update_where = "";

        $columns = [
            "district_id" => $district_id,
            "taluk_id" => $taluk_id,
            "entry_date" => $entry_date,
            "hostel_name" => $hostel_name,
            "facility_type" => $facility_type,
            "facility_name" => $facility_name,
            "received_date" => $received_date,
            "user_name" => $user_name,
            // "curr_date_time"              => $curr_date_time,
            "acc_year" => $acc_year,
            "is_active" => $is_active,
            "unique_id" => unique_id($prefix)
        ];

        // check already Exist Or not
        $table_details = [
            $table,
            [
                "COUNT(unique_id) AS count"
            ]
        ];

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
        if ($data[0]["count"]) {
            $msg = "already";
        } else if ($data[0]["count"] == 0) {
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
            // "sql"       => $sql
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
        
            if($length == '-1') {
                $limit  = "";
            }
        
            // Query Variables
            $json_array     = "";
            $columns        = [
                "@a:=@a+1 s_no",
                "entry_date",
                "facility_type",
                "facility_name",
                "received_date",
                // "is_active",
                // "unique_id"
            ];
           
            $where          = "is_delete = ? AND hostel_name = ?";
            $order_by       = "";
        
        $is_delete = "0";
            // Prepare SQL query
            $sql = "SELECT SQL_CALC_FOUND_ROWS " . implode(",", $columns) . " 
                    FROM $table, (SELECT @a:= ?) AS a 
                    WHERE $where  
                    LIMIT ?, ?";
            
            $stmt = $mysqli->prepare($sql);
        
            $param_start = $start;
            $hostel_name = $_SESSION['hostel_name'];
            
        
            if ($search) {
                $stmt->bind_param("isssi", $param_start,  $is_delete, $hostel_name, $start, $limit);
            } else {
                $stmt->bind_param("issis", $param_start,  $is_delete, $hostel_name, $start, $limit);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            // Get total records
            $total_records = $mysqli->query("SELECT FOUND_ROWS() as total")->fetch_assoc()['total'];
        
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $row['is_active'] = is_active_show($row['is_active']);
                    $btn_update = btn_update($folder_name, $row['unique_id']);
                    $btn_delete = btn_delete($folder_name, $row['unique_id']);
        
                    if ($row['unique_id'] == "5f97fc3257f2525529") {
                        $btn_update = "";
                        $btn_delete = "";
                    }
        
                    $row['unique_id'] = $btn_update . $btn_delete;
                    $data[] = array_values($row);
                }
        
                $json_array = [
                    "draw" => intval($draw),
                    "recordsTotal" => intval($total_records),
                    "recordsFiltered" => intval($total_records),
                    "data" => $data,
                    "testing" => $result->sql
                ];
            } else {
                echo json_encode(['error' => 'Failed to execute query: ' . $mysqli->error]);
                exit();
            }
        
            echo json_encode($json_array);
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
            "sql" => $sql
        ];

        echo json_encode($json_array);
        break;


    default:

        break;
}


?>