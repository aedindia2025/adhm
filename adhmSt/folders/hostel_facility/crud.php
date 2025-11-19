<?php 

// Get folder Name From Currnent Url 
$folder_name        = explode("/",$_SERVER['PHP_SELF']);
$folder_name        = $folder_name[count($folder_name)-2];

// // Database Country Table Name
$table             = "hostel_facility";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';



// // Variables Declaration
$action             = $_POST['action'];

$district_id        = "";
$taluk_id           = "";
$entry_date         = "";
$hostel_name        = "";
$facility_type      = "";
$facility_name      = "";
$received_date      = "";
$user_name          = "";
// $curr_date_time     = "";
$acc_year           = "";
$is_active          = "";
$unique_id          = "";
$prefix             = "";

$data               = "";
$msg                = "";
$error              = "";
$status             = "";
$test               = ""; // For Developer Testing Purpose

switch ($action) {
    case 'createupdate':

        $district_id                = $_POST["district_id"];
        $taluk_id                   = $_POST["taluk_id"];
        $entry_date                 = $_POST["entry_date"];
        $hostel_name                = $_POST["hostel_name"];
        $facility_type              = disname($_POST["facility_type"]);
        $facility_name              = $_POST["facility_name"];
        $received_date              = $_POST["received_date"];
        $user_name                  = $_POST["user_name"];
        // $curr_date_time             = $_POST["curr_date_time"];
        $acc_year                   = $_POST["acc_year"];
        $is_active                  = $_POST["is_active"];
        $unique_id                  = $_POST["unique_id"];

        $update_where       = "";

        $columns            = [
            "district_id"                 => $district_id,
            "taluk_id"                    => $taluk_id,
            "entry_date"                  => $entry_date,
            "hostel_name"                 => $hostel_name,
            "facility_type"               => $facility_type,
            "facility_name"               => $facility_name,
            "received_date"               => $received_date,
            "user_name"                   => $user_name,
            // "curr_date_time"              => $curr_date_time,
            "acc_year"                    => $acc_year,
            "is_active"                   => $is_active,
            "unique_id"                   => unique_id($prefix)
        ];

            // check already Exist Or not
        $table_details      = [
            $table,
            [
                "COUNT(unique_id) AS count"
            ]
        ];

        // When Update Check without current id
        if ($unique_id) {
            $select_where   .= ' AND unique_id !="'.$unique_id.'" ';
        }

        $action_obj         = $pdo->select($table_details,$select_where);

        if ($action_obj->status) {
            $status     = $action_obj->status;
            $data       = $action_obj->data;
            $error      = "";
            $sql        = $action_obj->sql;

        } else {
            $status     = $action_obj->status;
            $data       = $action_obj->data;
            $error      = $action_obj->error;
            $sql        = $action_obj->sql;
            $msg        = "error";
        }
        if ($data[0]["count"]) {
            $msg        = "already";
        } else if ($data[0]["count"] == 0) {
            // Update Begins
            if($unique_id) {

                unset($columns['unique_id']);

                $update_where   = [
                    "unique_id"     => $unique_id
                ];

                $action_obj     = $pdo->update($table,$columns,$update_where);

            // Update Ends
            } else {

                // Insert Begins            
                $action_obj     = $pdo->insert($table,$columns);
                // Insert Ends

            }

            if ($action_obj->status) {
                $status     = $action_obj->status;
                $data       = $action_obj->data;
                $error      = "";
                $sql        = $action_obj->sql;

                if ($unique_id) {
                    $msg        = "update";
                } else {
                    $msg        = "create";
                }
            } else {
                $status     = $action_obj->status;
                $data       = $action_obj->data;
                $error      = $action_obj->error;
                $sql        = $action_obj->sql;
                $msg        = "error";
            }
        }

        $json_array   = [
            "status"    => $status,
            "data"      => $data,
            "error"     => $error,
            "msg"       => $msg,
            // "sql"       => $sql
        ];

        echo json_encode($json_array);

        break;

    case 'datatable':
        // DataTable Variables
        $length     = $_POST['length'];
        $start      = $_POST['start'];
        $draw       = $_POST['draw'];
        $limit = $length == '-1' ? "" : $length;

        $data       = [];
  

        // Query Variables
        $json_array     = "";
        $columns        = [
            "@a:=@a+1 s_no",
            "entry_date",
            "facility_type",
            "facility_name",
            "received_date"
        ];
        $table_details = $table . " , (SELECT @a:= ?) AS a ";
        $where = "is_delete = 0 and taluk_id = ?";
        $order_by = ""; // You can modify this to add an order by clause if needed
$taluk_name = $_SESSION['taluk_id'];
        $sql_function = "SQL_CALC_FOUND_ROWS";

        // SQL query for data fetching
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_details WHERE $where";
        if ($limit) {
            $sql .= " LIMIT ?, ?";
        }

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
                $value['is_active']     = is_active_show($value['is_active']);

                $btn_update         = btn_update($folder_name,$value['unique_id']);
                $btn_delete         = btn_delete($folder_name,$value['unique_id']);

                if ( $value['unique_id'] == "5f97fc3257f2525529") {
                    $btn_update         = "";
                    $btn_delete         = "";
                } 

                $value['unique_id'] = $btn_update.$btn_delete;
                $data[]             = array_values($value);
            }
            
            $json_array = [
                "draw"              => intval($draw),
                "recordsTotal"      => intval($total_records),
                "recordsFiltered"   => intval($total_records),
                "data"              => $data,
                // "testing"           => $result->sql
            ];
        } else {
            // print_r($result);
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
        
        $unique_id      = $_POST['unique_id'];

        $columns        = [
            "is_delete"   => 1
        ];

        $update_where   = [
            "unique_id"     => $unique_id
        ];

        $action_obj     = $pdo->update($table,$columns,$update_where);

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
    
        
?>
