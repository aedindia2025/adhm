<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "feedback_creation";

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
        $limit = $length == '-1' ? "" : $length;
        $data = [];

        $table = "feedback_creation";

        $district_name = sanitizeInput($_POST["district_name"]);
        $taluk_name = sanitizeInput($_POST["taluk_name"]);
        $hostel_name = sanitizeInput($_POST["hostel_name"]);

        // Prepare statement variables
        $columns = [
            "@a:=@a+1 s_no",
            "entry_date",
            "student_id",
            "student_name",
            "district_id",
            "taluk_id",
            "hostel_id",
            "feedback_name",
            "rating",
            "description",
            "unique_id"
        ];

        // Build the query
        $select_columns = implode(", ", $columns);
        $table_details = "$table, (SELECT @a:= ?) AS a";
        $where = "is_delete = 0";

        if ($district_name != '') {
            $where .= " AND district_id = ?";
        }
        if ($taluk_name != '') {
            $where .= " AND taluk_id = ?";
        }
        if ($hostel_name != '') {
            $where .= " AND hostel_id = ?";
        }

        $order_by = ""; // You can modify this to add an order by clause if needed

        // Prepare the main query
        $query = "SELECT SQL_CALC_FOUND_ROWS $select_columns FROM $table_details WHERE $where $order_by LIMIT ? OFFSET ?";
        $stmt = $mysqli->prepare($query);

        if ($stmt === false) {
            die('Prepare statement failed: ' . $mysqli->error);
        }

        // Bind parameters
        $params = [];
        $types = "i"; // For @a:= start

        $params[] = $start; // @a:= start

        if ($district_name != '') {
            $params[] = $district_name;
            $types .= "s";
        }
        if ($taluk_name != '') {
            $params[] = $taluk_name;
            $types .= "s";
        }
        if ($hostel_name != '') {
            $params[] = $hostel_name;
            $types .= "s";
        }
        $params[] = $limit;
        $params[] = $start;
        $types .= "ii";

        // Bind parameters to statement
        $stmt->bind_param($types, ...$params);

        // Execute the query
        $stmt->execute();

        // Get result set
        $result = $stmt->get_result();

        // Fetch data
        $res_array = [];
        while ($row = $result->fetch_assoc()) {
            $row['district_id'] = district_name_un($row['district_id']);
            $row['taluk_id'] = taluk_name_un($row['taluk_id']);
            $row['hostel_id'] = hostel_name_un($row['hostel_id']);
            $row['feedback_name'] = feedback_name_un($row['feedback_name']);
            $row['unique_id'] = btn_delete($folder_name, $row['unique_id']);
            $res_array[] = array_values($row);
        }

        // Close statement and result set
        $stmt->close();

        // Get total records count (unfiltered)
        $total_records_query = "SELECT FOUND_ROWS() AS total";
        $total_result = $mysqli->query($total_records_query);
        $total_records = 0;
        if ($total_result) {
            $total_records_row = $total_result->fetch_assoc();
            $total_records = isset($total_records_row['total']) ? $total_records_row['total'] : 0;
        }

        // Prepare response JSON
        $json_array = [
            "draw" => intval($draw),
            "recordsTotal" => intval($total_records),
            "recordsFiltered" => intval($total_records),
            "data" => $res_array,
            // "testing" => $query
        ];

        // Output JSON
        echo json_encode($json_array);

        // Close connection
        $mysqli->close();

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

        // Prepare update query
        $update_query = "UPDATE $table SET is_delete = 1 WHERE unique_id = ?";
        $stmt = $mysqli->prepare($update_query);

        if ($stmt === false) {
            die('Prepare statement failed: ' . $mysqli->error);
        }

        // Bind parameter
        $stmt->bind_param("s", $unique_id);

        // Execute query
        $stmt->execute();

        // Check execution status
        if ($stmt->affected_rows > 0) {
            $status = true;
            $msg = "success_delete";
        } else {
            $status = false;
            $msg = "error";
        }

        // Close statement
        $stmt->close();

        // Prepare response JSON
        $json_array = [
            "status" => $status,
            "data" => [], // You can add data if needed
            "error" => "", // You can populate error message if needed
            "msg" => $msg,
            // "sql"       => $update_query // Uncomment if you want to include SQL query in response
        ];

        // Output JSON
        echo json_encode($json_array);


        break;

    default:

        break;
}

//
?>