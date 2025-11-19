<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "leave_application";

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
$test = "";
// For Developer Testing Purpose





switch ($action) {
    case 'datatable':

        $length = $_POST['length'];
        $start = isset($_POST['start']) ? $_POST['start'] : 0;
        $draw = $_POST['draw'];
        $district_name = sanitizeInput($_POST["district_name"]);
        $taluk_name = sanitizeInput($_POST["taluk_name"]);
        $hostel_name = sanitizeInput($_POST["hostel_name"]);

        // Initialize arrays for data and JSON response
        $data = [];
        $json_array = [];

        // Query Variables
        $columns = [
            "@a:=@a+1 s_no",
            "DATE(entry_date) as entry_date",
            "student_id",
            "student_name",
            "no_of_days",
            "reason",
            "approval_status",
            "unique_id"
        ];
        // $table = "your_table_name"; // Replace with your actual table name
        $table_details = "$table , (SELECT @a:= ?) AS a ";
        $where = "is_delete = 0";

        // Prepare WHERE clause based on filters
        $params = array(0); // Starting value for @a

        if (!empty($district_name)) {
            $where .= " AND district_name = ?";
            $params[] = $district_name;
        }
        if (!empty($taluk_name)) {
            $where .= " AND taluk_name = ?";
            $params[] = $taluk_name;
        }
        if (!empty($hostel_name)) {
            $where .= " AND hostel_name = ?";
            $params[] = $hostel_name;
        }

        // SQL query for data fetching
        $sql_function = "SQL_CALC_FOUND_ROWS";
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_details WHERE $where";
        if ($length != '-1') {
            $sql .= " LIMIT ?, ?";
            $params[] = intval($start);
            $params[] = intval($length);
        }

        // Prepare and execute SQL query with parameter binding
        $stmt = $mysqli->prepare($sql);
        if ($stmt) {
            // Dynamically bind parameters based on types (all assumed to be strings here)
            $types = str_repeat('s', count($params)); // Assuming all parameters are strings
            $stmt->bind_param($types, ...$params);
            $stmt->execute();

            // Get result and total records count
            $result = $stmt->get_result();
            $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
            $total_records = $total_records_result->fetch_assoc()['total'];

            if ($result) {
                // Fetch all rows into associative array
                $res_array = $result->fetch_all(MYSQLI_ASSOC);

                foreach ($res_array as $key => $value) {
                    // Modify columns as needed
                    $value['district_name'] = district_name_un($value['district_name']);
                    $value['taluk_name'] = taluk_name_un($value['taluk_name']);
                    $value['hostel_name'] = hostel_name_un($value['hostel_name']);

                    // Modify approval status display
                    switch ($value['approval_status']) {
                        case 2:
                            $status_text = 'Approved';
                            $status_color = 'green';
                            break;
                        case 3:
                            $status_text = 'Rejected';
                            $status_color = 'red';
                            break;
                        default:
                            $status_text = 'Pending';
                            $status_color = 'blue';
                            break;
                    }
                    $value['approval_status'] = '<span style="color: ' . $status_color . ';">' . $status_text . '</span>';

                    // Modify unique_id to include action buttons or other elements
                    $value['unique_id'] = '<a class="btn btn-action specl2" href="javascript:leave_print(\'' . $value['unique_id'] . '\')"><button type="button"><i class="fa fa-eye"></i></button></a>';

                    // Add modified row to data array
                    $data[] = array_values($value);
                }

                // Prepare JSON response
                $json_array = [
                    "draw" => intval($draw),
                    "recordsTotal" => intval($total_records),
                    "recordsFiltered" => intval($total_records),
                    "data" => $data
                ];
            } else {
                // Handle SQL execution error
                $json_array = [
                    "draw" => intval($draw),
                    "recordsTotal" => 0,
                    "recordsFiltered" => 0,
                    "data" => [],
                    "error" => $stmt->error
                ];
            }

            // Close statement
            $stmt->close();
        } else {
            // Handle prepare statement error
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => $mysqli->error
            ];
        }

        // Output JSON response
        echo json_encode($json_array);

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

        $unique_id = $_POST['unique_id'];

        // Define update columns and update where condition
        $columns = [
            "is_delete" => 1
        ];

        $table = "leave_application"; // Replace with your actual table name

        $update_where = "unique_id = ?";
        $params = [$unique_id];
        $types = 's';

        // Prepare SQL statement
        $sql = "UPDATE $table SET " . implode(" = ?, ", array_keys($columns)) . " = ? WHERE $update_where";

        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            die('MySQLi prepare() error: ' . $mysqli->error);
        }

        // Bind parameters
        $bind_params = array_merge([$types], array_values($columns), $params);
        $stmt->bind_param(...$bind_params);

        // Execute the update query
        if ($stmt->execute()) {
            $status = true;
            $data = [];
            $error = "";
            $msg = "success_delete";
            $sql = $stmt->sqlstate;
        } else {
            $status = false;
            $data = [];
            $error = $stmt->error;
            $msg = "error";
            $sql = $stmt->sqlstate;
        }

        // Close statement
        $stmt->close();

        // Output JSON response
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

//
?>