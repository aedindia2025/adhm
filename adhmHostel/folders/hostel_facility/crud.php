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

        // Validate and sanitize inputs
        $vali_district_id = filter_input(INPUT_POST, 'district_id', FILTER_SANITIZE_STRING);
        $vali_taluk_id = filter_input(INPUT_POST, 'taluk_id', FILTER_SANITIZE_STRING);
        $vali_entry_date = filter_input(INPUT_POST, 'entry_date', FILTER_SANITIZE_STRING);
        $vali_hostel_id = filter_input(INPUT_POST, 'hostel_id', FILTER_SANITIZE_STRING);
        $vali_facility_type = filter_input(INPUT_POST, 'facility_type', FILTER_SANITIZE_STRING);
        $vali_facility_name = filter_input(INPUT_POST, 'facility_name', FILTER_SANITIZE_STRING);
        $vali_received_date = filter_input(INPUT_POST, 'received_date', FILTER_SANITIZE_STRING);
        $vali_staff_name = filter_input(INPUT_POST, 'staff_name', FILTER_SANITIZE_STRING);
        $vali_acc_year = filter_input(INPUT_POST, 'acc_year', FILTER_SANITIZE_STRING);

        if (!$vali_district_id || !$vali_taluk_id || !$vali_entry_date || !$vali_hostel_id || !$vali_facility_type || !$vali_facility_name || !$vali_received_date || !$vali_staff_name) {
            $msg = "form_alert";
        } else {
            // Sanitize inputs
            $district_id = sanitizeInput($_POST["district_id"]);
            $taluk_id = sanitizeInput($_POST["taluk_id"]);
            $entry_date = sanitizeInput($_POST["entry_date"]);
            $hostel_name = sanitizeInput($_POST["hostel_id"]);
            $facility_type = sanitizeInput(disname($_POST["facility_type"]));
            $facility_name = sanitizeInput($_POST["facility_name"]);
            $received_date = sanitizeInput($_POST["received_date"]);
            $user_name = sanitizeInput($_POST["staff_name"]);
            $acc_year = sanitizeInput($_POST["acc_year"]);
            $is_active = sanitizeInput($_POST["is_active"]);
            $unique_id = sanitizeInput($_POST["unique_id"]);

            // Check if unique_id exists for update or insert
            if ($unique_id) {
                // Update existing record
                $sql = "UPDATE $table SET hostel_name=?, entry_date=?, district_id=?, taluk_id=?, facility_type=?, facility_name=?, received_date=?, user_name=?, acc_year=?, is_active=? WHERE unique_id=?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("sssssssssss", $hostel_name, $entry_date, $district_id, $taluk_id, $facility_type, $facility_name, $received_date, $user_name, $acc_year, $is_active, $unique_id);
            } else {
                // Insert new record
                $sql = "INSERT INTO $table (hostel_name, entry_date, district_id, taluk_id, facility_type, facility_name, received_date, user_name, acc_year, is_active, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("sssssssssss", $hostel_name, $entry_date, $district_id, $taluk_id, $facility_type, $facility_name, $received_date, $user_name, $acc_year, $is_active, unique_id($prefix));
            }

            if ($stmt->execute()) {
                if ($unique_id) {
                    $msg = "update";
                } else {
                    $msg = "create";
                }
            } else {
                $msg = "error";
            }

            $stmt->close();
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg
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
            "entry_date",
            "facility_type",
            "facility_name",
            "received_date",
            "is_active",
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
            $stmt->bind_param("isii", $start, $_SESSION['hostel_id'], $start, $limit);
        } else {
            $stmt->bind_param("is", $start, $_SESSION['hostel_id']);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch total records
        $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
        $total_records = $total_records_result->fetch_assoc()['total'];

        if ($result) {
            $res_array = $result->fetch_all(MYSQLI_ASSOC);

            foreach ($res_array as $key => $value) {
                $value['is_active'] = is_active_show($value['is_active']);

                $btn_update = btn_update($folder_name, $value['unique_id']);
                $btn_delete = btn_delete($folder_name, $value['unique_id']);

                if ($value['unique_id'] == "5f97fc3257f2525529") {
                    $btn_update = "";
                    $btn_delete = "";
                }

                $value['unique_id'] = $btn_update . $btn_delete;
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

        // Update is_delete field
        $sql = "UPDATE $table SET is_delete = 1 WHERE unique_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $unique_id);

        if ($stmt->execute()) {
            $msg = "success_delete";
        } else {
            $msg = "error";
        }

        $stmt->close();

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg
        ];

        echo json_encode($json_array);
        break;


    default:

        break;
}


?>