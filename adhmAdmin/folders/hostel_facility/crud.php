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
        $district_id = sanitizeInput($_POST["district_id"]);
        $taluk_id = sanitizeInput($_POST["taluk_id"]);
        $entry_date = sanitizeInput($_POST["entry_date"]);
        $hostel_name = sanitizeInput($_POST["hostel_name"]);
        $facility_type = sanitizeInput(disname($_POST["facility_type"]));
        $facility_name = sanitizeInput($_POST["facility_name"]);
        $received_date = sanitizeInput($_POST["received_date"]);
        $user_name = sanitizeInput($_POST["user_name"]);
        $acc_year = sanitizeInput($_POST["acc_year"]);
        $is_active = sanitizeInput($_POST["is_active"]);
        $unique_id = sanitizeInput($_POST["unique_id"]);
        $token = sanitizeInput($_POST["csrf_token"]);
        // Check if unique_id is provided for updating
        $is_update = !empty($unique_id);

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }



        // Prepare the SQL statement
        if ($is_update) {
            $sql = "UPDATE hostel_facility SET district_id=?, taluk_id=?, entry_date=?, hostel_name=?, facility_type=?, facility_name=?, received_date=?, user_name=?, acc_year=?, is_active=? WHERE unique_id=?";
        } else {
            $sql = "INSERT INTO hostel_facility (district_id, taluk_id, entry_date, hostel_name, facility_type, facility_name, received_date, user_name, acc_year, is_active, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        }

        $stmt = $mysqli->prepare($sql);

        if ($stmt === false) {
            $json_array = [
                "status" => false,
                "error" => "Preparation of statement failed: " . $mysqli->error,
                "msg" => "error"
            ];
            echo json_encode($json_array);
            break;
        }

        // Bind parameters
        if ($is_update) {
            $stmt->bind_param("sssssssssis", $district_id, $taluk_id, $entry_date, $hostel_name, $facility_type, $facility_name, $received_date, $user_name, $acc_year, $is_active, $unique_id);
        } else {
            $stmt->bind_param("sssssssssis", $district_id, $taluk_id, $entry_date, $hostel_name, $facility_type, $facility_name, $received_date, $user_name, $acc_year, $is_active, $unique_id);
        }

        // Execute statement
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $status = true;
            $msg = $is_update ? "update" : "create";
        } else {
            $status = false;
            $error = $stmt->error;
            $msg = "error";
        }

        // Close statement
        $stmt->close();

        // Close MySQLi connection
        $mysqli->close();

        // Prepare JSON response
        $json_array = [
            "status" => $status,
            "error" => isset($error) ? $error : "",
            "msg" => $msg
        ];

        echo json_encode($json_array);

        break;


    case 'datatable':
        // DataTable Variables
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length == '-1' ? "" : intval($length);

        $data = [];

        $district_name = sanitizeInput($_POST["district_name"]);
        $taluk_name = sanitizeInput($_POST["taluk_name"]);
        $hostel_name = sanitizeInput($_POST["hostel_name"]);

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
        $table_details = $table . ", (SELECT @a:= ? ) AS a";
        $where = "is_delete = 0";
        $params = [$start];
        $types = "i";

        if ($district_name != '') {
            $where .= " AND district_id=?";
            $params[] = $district_name;
            $types .= "s";
        }
        if ($taluk_name != '') {
            $where .= " AND taluk_id=?";
            $params[] = $taluk_name;
            $types .= "s";
        }
        if ($hostel_name != '') {
            $where .= " AND hostel_name=?";
            $params[] = $hostel_name;
            $types .= "s";
        }

        // Construct the query
        $sql = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns) . " FROM " . $table_details . " WHERE " . $where;

        if (!empty($limit)) {
            $sql .= " LIMIT ?, ?";
            $params[] = intval($start);
            $params[] = intval($limit);
            $types .= "ii";
        } else {
            // Adding placeholder for limit and offset even if limit is empty
            $sql .= " LIMIT ?, ?";
            $params[] = intval($start);
            $params[] = PHP_INT_MAX; // A large number to simulate no limit
            $types .= "ii";
        }

        // Prepare statement
        $stmt = $mysqli->prepare($sql);

        if ($stmt === false) {
            die('Prepare statement failed: ' . $mysqli->error);
        }

        // Bind parameters dynamically
        if (!empty($types)) {
            $stmt->bind_param($types, ...$params);
        }

        // Execute the query
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch data
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

        // Fetch total filtered records
        $sql_filtered = "SELECT FOUND_ROWS() as filtered";
        $result_filtered = $mysqli->query($sql_filtered);
        $total_filtered = $result_filtered->fetch_assoc()['filtered'];

        // Fetch total records without filter
        $sql_total = "SELECT COUNT(*) as total FROM " . $table . " WHERE is_delete = 0";
        $result_total = $mysqli->query($sql_total);
        $total_records = $result_total->fetch_assoc()['total'];

        // Prepare response JSON
        $json_array = [
            "draw" => intval($draw),
            "recordsTotal" => intval($total_records),
            "recordsFiltered" => intval($total_filtered),
            "data" => $data,
        ];

        echo json_encode($json_array);

        // Close statement and connection
        $stmt->close();
        $mysqli->close();
        break;


    case 'delete':

        $token = $_POST["csrf_token"];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        // Assuming $_POST['unique_id'] is properly sanitized
        $unique_id = $_POST['unique_id'];

        // Update specific record
        $sql = "UPDATE hostel_facility SET is_delete = 1 WHERE unique_id = ?";
        $stmt = $mysqli->prepare($sql);

        if ($stmt === false) {
            http_response_code(500); // Internal Server Error
            echo json_encode(["error" => "Error preparing statement: " . $mysqli->error]);
            exit();
        }

        $stmt->bind_param("s", $unique_id);

        $action_result = $stmt->execute();
        // print_r($action);
        // $stmt->close();

        if ($action_result) {
            $status = true;
            $msg = "success_delete";
        } else {
            $status = false;
            $msg = "error";
        }

        $json_array = [
            "status" => $status,
            "msg" => $msg
        ];

        echo json_encode($json_array);

        // $mysqli->close();
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


    default:

        break;
}


?>