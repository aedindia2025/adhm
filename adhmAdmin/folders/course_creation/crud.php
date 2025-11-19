<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "course_creation";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';



// // Variables Declaration
$action = $_POST['action'];

$stream_name = "";
$university_name = "";
$college_name = "";
$course_name = "";
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
        // Get POST variables
        $token = $_POST['csrf_token'];
        $college_name = sanitizeInput($_POST["college_name"]);
        $course_name = sanitizeInput($_POST["course_name"]);
        $is_active = sanitizeInput($_POST["is_active"]);
        $unique_id = sanitizeInput($_POST["unique_id"]);

        // Validate CSRF token
        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        // Initialize variables
        $status = false;
        $data = [];
        $error = "";
        $msg = "";

        // Check if the entry already exists
        $select_query = "SELECT COUNT(unique_id) AS count FROM $table WHERE college_unique_id = ? AND course_name = ? AND is_delete = 0";

        // If updating, exclude the current record
        if ($unique_id) {
            $select_query .= " AND unique_id != ?";
        }

        // Prepare statement for select query
        $stmt = $mysqli->prepare($select_query);

        // Bind parameters for select query
        if ($unique_id) {
            $stmt->bind_param('sss', $college_name, $course_name, $unique_id);
        } else {
            $stmt->bind_param('ss', $college_name, $course_name);
        }

        // Execute select query
        $stmt->execute();

        // Get result of select query
        $result = $stmt->get_result();

        // Fetch data from result
        $data = $result->fetch_assoc();

        // Handle result of select query
        if ($data['count']) {
            $msg = "already";
            $status = true;
        } else {
            // Perform update or insert based on whether unique_id is set
            if ($unique_id) {
                // Update existing record
                $update_query = "UPDATE $table SET college_unique_id = ?, course_name = ?, is_active = ? WHERE unique_id = ?";
                $stmt = $mysqli->prepare($update_query);
                $stmt->bind_param('ssss', $college_name, $course_name, $is_active, $unique_id);
            } else {
                // Insert new record
                $insert_query = "INSERT INTO $table (college_unique_id, course_name, is_active, unique_id) VALUES (?, ?, ?, ?)";
                $stmt = $mysqli->prepare($insert_query);
                $stmt->bind_param('ssss', $college_name, $course_name, $is_active, unique_id($prefix));
            }

            // Execute update or insert query
            if ($stmt->execute()) {
                $status = true;
                $msg = $unique_id ? "update" : "create";
            } else {
                $error = $stmt->error;
                $msg = "error";
            }
        }

        // Prepare response as JSON
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg
        ];

        echo json_encode($json_array);

        // Close statement and connection
        $stmt->close();
        // $mysqli->close();

        break;

    case 'datatable':
        // DataTable Variables
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;

        $data = [];


        // Query Variables
        $json_array = "";
        $columns = [
            "@a:=@a+1 s_no",
            "(SELECT college_name FROM college_creation AS college_name WHERE college_name.unique_id = " . $table . ".college_unique_id ) AS college_name",
            "course_name",
            "is_active",
            "unique_id"
        ];
        $table_details = "$table , (SELECT @a:= ?) AS a";
        $where = "is_delete = 0";
        $bind_params = "i"; // Types of parameters (i for integer)
        $bind_values = [$start];


        $sql_function = "SQL_CALC_FOUND_ROWS";
        $order_by = ""; // Set the order by clause if necessary

        // SQL query for data fetching
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_details WHERE $where";
        if ($limit !== "") {
            $sql .= " LIMIT ?, ?";
            $bind_params .= "ii"; // Add types for limit parameters
            $bind_values[] = $start;
            $bind_values[] = $limit;
        }

        $stmt = $mysqli->prepare($sql);

        if ($stmt === false) {
            // Handle prepare error
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => $mysqli->error
            ];
            echo json_encode($json_array);
            break;
        }

        // Bind parameters dynamically
        $bind_params_arr = array_merge([$bind_params], ...array_map(function ($v) {
            return [$v];
        }, $bind_values));
        call_user_func_array([$stmt, 'bind_param'], $bind_params_arr);

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
            // print_r($result);
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => $stmt->error
            ];
        }

        echo json_encode($json_array);
        break;


    case 'delete':

        $token = $_POST['csrf_token'];
        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }
        $unique_id = $_POST['unique_id'];

        // Prepare SQL statement
        $sql = "UPDATE $table SET is_delete = 1 WHERE unique_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $unique_id);

        // Execute SQL statement
        if ($stmt->execute()) {
            $status = "success";
            $msg = "success_delete";
        } else {
            $status = "error";
            $msg = "error";
        }

        $stmt->close();

        $json_array = [
            "status" => $status,
            "msg" => $msg,
        ];

        echo json_encode($json_array);
        break;

    case 'get_taluk_name':

        $district_name = $_POST['district_name'];

        $district_options = taluk_name("", $district_name);

        $hostel_taluk_options = select_option($district_options, "Select Taluk");

        echo $hostel_taluk_options;

        break;

    default:

        break;
}


?>