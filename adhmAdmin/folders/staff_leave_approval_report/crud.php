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

// $fund_name          = "";
$is_active = "";
$unique_id = "";
$prefix = "";

$data = "";
$msg = "";
$error = "";
$status = "";
$test = ""; // For Developer Testing Purpose

$value['district_name'] = district_name_un($value['district_name']);
$value['taluk_name'] = taluk_name_un($value['taluk_name']);
$value['hostel_name'] = hostel_name_un($value['hostel_name']);

function validateCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

switch ($action) {


    case 'datatable':
        // DataTable Variables
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;

        $data = [];

        $table = "staff_leave_application";

        $district_name = sanitizeInput($_POST["district_name"]);
        $taluk_name = sanitizeInput($_POST["taluk_name"]);
        $hostel_name = sanitizeInput($_POST["hostel_name"]);

        if ($length == '-1') {
            $limit = "";
        }

        // Query Variables
        $json_array = "";
        $columns = [
            "@a:=@a+1 s_no",
            "DATE(entry_date) as entry_date",
            "staff_id",
            "staff_name",
            "no_of_days",
            "reason",
            "approval_status",
            "unique_id"
        ];
        $table_details = [
            $table . " , (SELECT @a:= ?) AS a ",
            $columns
        ];
        $where = "is_delete = ? ";

        $params = [$start, '0']; // Parameters array for binding

        if ($district_name != '') {
            $where .= " AND district_name = ?";
            $params[] = $district_name;
        }
        if ($taluk_name != '') {
            $where .= " AND taluk_name = ?";
            $params[] = $taluk_name;
        }
        if ($hostel_name != '') {
            $where .= " AND hostel_name = ?";
            $params[] = $hostel_name;
        }
        $order_by = "";

        $sql_function = "SQL_CALC_FOUND_ROWS";

        // Prepare the SELECT statement
        $sql = "SELECT $sql_function 
                    " . implode(', ', $columns) . "
                FROM $table, (SELECT @a:= ?) AS a 
                WHERE $where";
        if (!empty($limit)) {
            $sql .= " LIMIT ?, ?";
            $types .= "ii"; // Add types for integer parameters
            $params[] = intval($start);
            $params[] = intval($length);
        }

        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            die('MySQL prepare error: ' . $mysqli->error);
        }

        // Dynamically bind parameters
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);

        // Execute the query
        $stmt->execute();

        // Get the result set
        $result = $stmt->get_result();

        // Fetch data
        $res_array = [];
        while ($row = $result->fetch_assoc()) {
            $res_array[] = $row;
        }

        // Process fetched data (similar to your existing code)
        foreach ($res_array as $key => $value) {
            // Modify data as needed
            $value['district_name'] = district_name_un($value['district_name']);
            $value['taluk_name'] = taluk_name_un($value['taluk_name']);
            $value['hostel_name'] = hostel_name_un($value['hostel_name']);

            // Handle approval status
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
            $value['approval_status'] = '<span style="color: ' . $status_color . ';">' . $status_text . '</span>';

            // Prepare buttons and additional modifications
            $btn_delete = btn_delete($folder_name, $value['unique_id']);
            $unique_id = $value['unique_id'];
            $eye_button = '<a class="btn btn-action specl2"  href="javascript:leave_print(\'' . $unique_id . '\')"><button type="button" ><i class="fa fa-eye" ></i></button></a>';

            $value['unique_id'] = $btn_delete . $eye_button;
            $data[] = array_values($value);
        }

        // Get total records count
        $total_records = $mysqli->query("SELECT FOUND_ROWS()")->fetch_row()[0];

        // Prepare JSON response
        $json_array = [
            "draw" => intval($draw),
            "recordsTotal" => intval($total_records),
            "recordsFiltered" => intval($total_records),
            "data" => $data,
            "testing" => $sql  // For debugging, remove in production
        ];

        // Output JSON
        echo json_encode($json_array);

        // Close statement
        $stmt->close();
        break;



    case 'district_name':

        $district_name = $_POST['district_name'];


        $district_name_options = taluk_name('', $district_name);

        $taluk_name_options = select_option($district_name_options, "Select Taluk");

        echo $taluk_name_options;

        break;

    case 'get_hostel_by_taluk_name':

        $taluk_name = $_POST['taluk_name'];


        $hostel_name_options = hostel_name('', $taluk_name);

        $hostel_name_options = select_option_host($hostel_name_options, "Select Hostel");

        echo $hostel_name_options;

        break;




    case 'delete':

        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        $unique_id = $_POST['unique_id'];

        $table = "staff_leave_application";

        // Prepare the UPDATE statement
        $sql = "UPDATE $table SET is_delete = ? WHERE unique_id = ?";

        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            die('MySQL prepare error: ' . $mysqli->error);
        }
        $is_delete = '1';

        // Bind parameters
        $stmt->bind_param('ss', $is_delete, $unique_id);

        // Execute the query
        $status = $stmt->execute();

        if ($status === false) {
            $error = $stmt->error;
            $msg = "error";
        } else {
            $rows_affected = $stmt->affected_rows;
            if ($rows_affected > 0) {
                $msg = "success_delete";
            } else {
                $msg = "error";
                $error = "No rows updated.";
            }
        }

        // Prepare JSON response
        $json_array = [
            "status" => $status,
            "data" => [],
            "error" => $error ?? "",
            "msg" => $msg,
            "sql" => $sql  // For debugging, remove in production
        ];

        // Output JSON
        echo json_encode($json_array);

        // Close statement
        $stmt->close();
break;

    default:

        break;
}

//
?>