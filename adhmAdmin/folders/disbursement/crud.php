<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "disbursement_creation";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

$mysqli = new mysqli("localhost", "root", "4/rb5sO2s3TpL4gu", "adi_dravidar");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
// // Variables Declaration
$action = $_POST['action'];

$feedback_type = "";
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

        // Validate CSRF token
        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        // Sanitize and retrieve input values
        $dadwo_letter_no = sanitizeInput($_POST["dadwo_letter_no"]);
        $dadwo_letter_date = sanitizeInput($_POST["dadwo_letter_date"]);
        $unique_id = sanitizeInput($_POST["unique_id"]);
        $dadwo_login_user_id = sanitizeInput($_POST["dadwo_login_user_id"]);

        // File upload handling
        $file_names = '';
        $file_org_names = '';

        if (!empty($_FILES['test_file']['name'])) {
            $allowedExts = array('pdf');
            $extension = pathinfo($_FILES['test_file']['name'], PATHINFO_EXTENSION);

            if ($_FILES['test_file']['type'] == 'application/pdf' && in_array(strtolower($extension), $allowedExts)) {
                $tem_name = random_strings(25) . '.pdf';
                move_uploaded_file($_FILES['test_file']['tmp_name'], '../../uploads/disbursement/' . $tem_name);
                $file_names = $tem_name;
                $file_org_names = $_FILES['test_file']['name'];
            } else {
                die('Invalid file format. Only PDF files are allowed.');
            }
        }

        // Prepare SQL statement
        if ($unique_id) {
            // Update existing record
            $sql = "UPDATE $table SET dadwo_letter_no = ?, dadwo_letter_date = ?, dadwo_off_name = ?, dadwo_attach_file = ?, dadwo_attach_org_name = ? WHERE unique_id = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("ssssss", $dadwo_letter_no, $dadwo_letter_date, $dadwo_login_user_id, $file_names, $file_org_names, $unique_id);
        } else {
            // Insert new record
            $sql = "INSERT INTO $table (dadwo_letter_no, dadwo_letter_date, dadwo_off_name, dadwo_attach_file, dadwo_attach_org_name) VALUES (?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("sssss", $dadwo_letter_no, $dadwo_letter_date, $dadwo_login_user_id, $file_names, $file_org_names);
        }

        // Execute statement
        $stmt->execute();

        // Check execution status
        if ($stmt->affected_rows > 0) {
            $status = true;
            $data = null;
            $error = "";
            $msg = ($unique_id) ? "update" : "create";
            $sql = $sql; // If needed for debugging
        } else {
            $status = false;
            $data = null;
            $error = $stmt->error;
            $msg = "error";
            $sql = $sql; // If needed for debugging
        }

        // Close statement
        $stmt->close();

        // Prepare JSON response
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql" => $sql // Uncomment if you want to include the SQL statement in the response
        ];

        echo json_encode($json_array);

        break;


    case 'datatable':
        // DataTable Variables
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length == '-1' ? "" : intval($length);

        $district_name = sanitizeInput($_POST["district_name"]);
        $taluk_name = sanitizeInput($_POST["taluk_name"]);
        $hostel_name = sanitizeInput($_POST["hostel_name"]);

        $data = [];

        // Query Variables
        $columns = [
            "@a:=@a+1 s_no",
            '(SELECT disbursement_type FROM disbursement_type AS dis WHERE dis.unique_id = ' . $table . '.disbursement_type ) AS disbursement_type',
            '(SELECT amc_year FROM academic_year_creation AS acc_year WHERE acc_year.unique_id = ' . $table . '.academic_year ) AS academic_year',
            "month",
            "connection_no",
            "letter_no",
            "applied_date",
            "tah_letter_no",
            "dadwo_letter_no",
            "unique_id"
        ];
        $table_details = $table . " , (SELECT @a:= ? ) AS a ";
        $where = "is_delete = 0 AND tah_letter_no != ''";
        $order_by = ''; // Modify as needed

        // Prepare statement
        $params = [$start];
        $types = "i";

        if ($district_name != '') {
            $where .= " AND district_unique_id = ?";
            $params[] = $district_name;
            $types .= "s";
        }
        if ($taluk_name != '') {
            $where .= " AND taluk_name = ?";
            $params[] = $taluk_name;
            $types .= "s";
        }
        if ($hostel_name != '') {
            $where .= " AND hostel_name = ?";
            $params[] = $hostel_name;
            $types .= "s";
        }

        $sql_function = "SQL_CALC_FOUND_ROWS";

        // Construct query
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM " . $table_details . " WHERE " . $where;

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

        $stmt = $mysqli->prepare($sql);

        if ($stmt === false) {
            die('Prepare statement failed: ' . $mysqli->error);
        }

        if ($types) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch data
        while ($row = $result->fetch_assoc()) {
            $btn_update = btn_update($folder_name, $row['unique_id']);
            $btn_delete = btn_delete($folder_name, $row['unique_id']);

            if ($row['unique_id'] == "5f97fc3257f2525529") {
                $btn_update = "";
                $btn_delete = "";
            }

            $row['unique_id'] = $btn_update . $btn_delete;
            $data[] = array_values($row);
        }

        // Get total records
        $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
        $total_records = $total_records_result->fetch_assoc()['total'];

        // Prepare response JSON
        $json_array = [
            "draw" => intval($draw),
            "recordsTotal" => intval($total_records),
            "recordsFiltered" => intval($total_records),
            "data" => $data,
            // "testing" => $stmt->sql // Uncomment for debugging purposes
        ];

        echo json_encode($json_array);

        // Close statement and connection
        $stmt->close();
        $mysqli->close();
        break;



    case 'delete':
        
        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        $unique_id = $_POST['unique_id'];

        // Prepare SQL statement
        $sql = "UPDATE disbursement_creation SET is_delete = ? WHERE unique_id = ?";
        $stmt = $mysqli->prepare($sql);

        if ($stmt === false) {
            $error = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            $json_array = [
                "status" => false,
                "data" => null,
                "error" => $error,
                "msg" => "error"
            ];
            echo json_encode($json_array);
            break;
        }

        // Bind parameters and execute statement
        $is_delete = 1;
        $stmt->bind_param("is", $is_delete, $unique_id);
        $stmt->execute();

        // Check execution status
        if ($stmt->affected_rows > 0) {
            $status = true;
            $data = null;
            $error = "";
            $msg = "success_delete";
        } else {
            $status = false;
            $data = null;
            $error = "No rows affected.";
            $msg = "error";
        }

        // Close statement and MySQLi connection
        $stmt->close();
        $mysqli->close();

        // Prepare JSON response
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql" => $sql // Uncomment if you want to include the SQL statement in the response
        ];

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


    default:

        break;
}




?>