<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table_main = "district_percentage";
$table = "district_percentage_sub";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

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
        // CSRF Token Validation
        $token = $_POST['csrf_token'] ?? '';
        if (!validateCSRFToken($token)) {
            throw new Exception('CSRF validation failed.');
        }
        // Input sanitization
        $district = sanitizeInput($_POST["district"] ?? '');
        $month = sanitizeInput($_POST["month"] ?? '');
        $screen_unique_id = sanitizeInput($_POST["screen_unique_id"] ?? '');
        $unique_id = sanitizeInput($_POST["unique_id"] ?? '');

        // Validate required fields
        if (!$district || !$month || !$screen_unique_id) {
            throw new Exception("Missing required fields.");
        }

        $sql_check = "SELECT COUNT(*) as count FROM district_percentage WHERE is_delete = 0 AND district = ? AND month = ? AND unique_id != ?";

        $stmt = $mysqli->prepare($sql_check);
        $stmt->bind_param('sss', $district, $month, $unique_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row['count'] > 0) {

            $msg = "already";
            $status = "error";
        } else {

            if (!empty($unique_id)) {

                // UPDATE
                $query = "UPDATE $table_main SET district = ?, month = ?, screen_unique_id = ? WHERE unique_id = ?";
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param("ssss", $district, $month, $screen_unique_id, $unique_id);
            } else {

                // INSERT
                $unique_id_generated = unique_id($prefix);
                $query = "INSERT INTO $table_main (district, month, screen_unique_id, unique_id) VALUES (?, ?, ?, ?)";
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param("ssss", $district, $month, $screen_unique_id, $unique_id_generated);


            }

            if ($stmt->execute()) {
                
                $query_1 = "UPDATE $table SET district = ?, month = ? WHERE unique_id = ?";
$stmt = $mysqli->prepare($query_1);
$stmt->bind_param("sss", $district, $month, $unique_id);

                $msg = $unique_id ? "update" : "create";
                $status = true;
                $data = ["affected_rows" => $stmt->affected_rows];
                $error = "";
            } else {

                throw new Exception("Execute failed: " . $stmt->error);
            }

            $stmt->close();
        }

        // Output JSON
        echo json_encode([
            "status" => $status,
            "msg" => $msg,
            "error" => $error,
            "data" => $data
        ]);

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
            "' ' as sno",
            "(SELECT district_name FROM district_name WHERE district_name.unique_id = $table_main.district) AS district",
            "month",
            "unique_id",
            "screen_unique_id",
        ];
        $table_details = $table_main . " , (SELECT @a:= ?) AS a ";
       $where = "is_delete = 0 AND district = '" . $_SESSION['district_id'] . "'";

        $order_by = "";

        $sql_function = "SQL_CALC_FOUND_ROWS";

        // SQL query for data fetching
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_details WHERE $where";
        if ($limit) {
            $sql .= " LIMIT ?, ?";
        }

        $stmt = $mysqli->prepare($sql);
        if ($limit) {
            $stmt->bind_param("iii", $start, $start, $limit);
        } else {
            $stmt->bind_param("i", $start);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch total records
        $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
        $total_records = $total_records_result->fetch_assoc()['total'];

        if ($result) {
            $res_array = $result->fetch_all(MYSQLI_ASSOC);
            $sno = $start + 1;
            foreach ($res_array as $key => $value) {
                $value['sno'] = $sno++;
                $unique = $value['unique_id'];
                $btn_update = btn_update($folder_name, $value['unique_id']);
                $btn_delete = btn_delete($folder_name, $value['screen_unique_id']);

                $btn_copy = '<a href="#" class="openPopup" title="Copy" 
                    data-id="' . $value['unique_id'] . '" 
                    data-month="' . $value['month'] . '" 
                    data-screen-id="' . $value['screen_unique_id'] . '">
                    <i class="fa fa-copy" style="font-size: 0.98em;"></i>
                </a>';

                $value['month'] = formatMonthYear($value['month']);

                $value['unique_id'] = $btn_update . ' ' . $btn_delete . ' &nbsp;&nbsp; ' . $btn_copy;
                // $value['unique_id'] = $btn_update . ' ' . $btn_delete ;
                $data[] = array_values($value);
            }

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data,
                "testing" => $stmt->sqlstate
            ];
        } else {
            print_r($result);
        }

        echo json_encode($json_array);

        // Close connection
        $stmt->close();
        $mysqli->close();

        break;

    case 'district_percent_sub_datatable':

        // DataTable Parameters
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length == '-1' ? "" : $length;

        $screen_unique_id = $_POST['screen_unique_id'];
        $data = [];

        // Query Columns
        $columns = [
            "' ' as sno",
            "(SELECT hostel_type FROM hostel_type WHERE hostel_type.unique_id = district_percentage_sub.hostel_type) AS hostel_type",
            "month",
            "percentage",
            "unique_id"
        ];

        $sql_function = "SQL_CALC_FOUND_ROWS";

        $where = "is_delete = 0 AND screen_unique_id = ?";
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM district_percentage_sub WHERE $where";

        if ($limit) {
            $sql .= " LIMIT ?, ?";
        }

        $stmt = $mysqli->prepare($sql);

        // Bind params
        if ($limit) {
            $stmt->bind_param("sii", $screen_unique_id, $start, $limit);
        } else {
            $stmt->bind_param("s", $screen_unique_id);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch total records
        $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
        $total_records = $total_records_result->fetch_assoc()['total'];

        if ($result) {
            $res_array = $result->fetch_all(MYSQLI_ASSOC);
            $sno = $start + 1;

            foreach ($res_array as $value) {
                $edit_btn = '<a href="#" onclick="edit_sublist(\'' . $value['unique_id'] . '\')" class="font-18 text-info me-2" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Edit" data-bs-original-title="Edit"><i class="uil uil-pen"></i></a>';
                $delete_btn = '<a href="#" onclick="delete_sublist(\'' . $value['unique_id'] . '\')" class="font-18 text-danger" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Delete" data-bs-original-title="Delete"><i class="uil uil-trash"></i></a>';

                $action_btns = $edit_btn . " " . $delete_btn;

                $row = [
                    "sno" => $sno++,
                    "hostel_type" => $value['hostel_type'],
                    "month" => formatMonthYear($value['month']),
                    "percentage" => $value['percentage'],
                    "action" => $action_btns
                ];

                $data[] = array_values($row);
            }

            echo json_encode([
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data
            ]);
        } else {
            echo json_encode([
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => $stmt->error
            ]);
        }

        // Close connection
        $stmt->close();
        $mysqli->close();

        break;

    case 'delete':
        $unique_id = $_POST['unique_id'];
        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        // Prepare SQL statements
        $stmt = $mysqli->prepare("UPDATE $table_main SET is_delete = ? WHERE screen_unique_id = ?");
        $stmt_2 = $mysqli->prepare("UPDATE $table SET is_delete = ? WHERE screen_unique_id = ?");

        $is_delete = 1;

        if ($stmt && $stmt_2) {
            // Bind parameters
            $stmt->bind_param("is", $is_delete, $unique_id);
            $stmt_2->bind_param("is", $is_delete, $unique_id);

            // Execute both statements
            $stmt_result = $stmt->execute();
            $stmt2_result = $stmt_2->execute();

            if ($stmt_result && $stmt2_result) {
                $status = true;
                $data = null;
                $error = "";
                $msg = "success_delete";
            } else {
                $status = false;
                $data = null;
                $error = $stmt->error . ' ' . $stmt_2->error;
                $msg = "error";
            }

            $stmt->close();
            $stmt_2->close();
        } else {
            $status = false;
            $data = null;
            $error = ($stmt ? '' : $mysqli->error . ' (stmt1)') . ' ' . ($stmt_2 ? '' : $mysqli->error . ' (stmt2)');
            $msg = "error";
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            "sql" => "" // You can log actual SQL here if needed
        ];

        echo json_encode($json_array);
        break;

    case 'sublist_cu':
        $screen_unique_id = $_POST['screen_unique_id'];
        $district = $_POST['district'] ?? '';
        $hostel_type = $_POST['hostel_type'] ?? '';
        $month = $_POST['month'] ?? '';
        $percentage = $_POST['percentage'] ?? '';
        $sublist_unique_id = $_POST['sublist_unique_id'] ?? '';

        if (empty($screen_unique_id) || empty($district) || empty($hostel_type) || empty($month) || empty($percentage)) {
            echo json_encode([
                'status' => false,
                'msg' => 'form_alert'
            ]);
            return;
        }

        $check_exist = "SELECT COUNT(*) FROM district_percentage_sub WHERE screen_unique_id = ? AND hostel_type = ?  AND is_delete = 0 AND unique_id != ?";
        $count = $mysqli->prepare($check_exist);
        // $count->bind_param("ssss", $district, $hostel_type, $month, $sublist_unique_id);
        $count->bind_param("sss", $screen_unique_id, $hostel_type, $sublist_unique_id);
        $count->execute();
        $count->bind_result($total);
        $count->fetch();
        $count->close();

        if ($total > 0) {
            $msg = 'already';
        } else {
            if ($sublist_unique_id) {
                // Update existing
                $sql = "UPDATE district_percentage_sub 
                SET  hostel_type = ?,  percentage = ? WHERE unique_id = ? AND is_delete = 0";

                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("sss",  $hostel_type,  $percentage, $sublist_unique_id);

                if ($stmt->execute()) {
                    $msg = "update";
                } else {
                    $msg = "error";
                }
            } else {
                // Create new
                $new_id = unique_id(); // assuming this exists
                $sql = "INSERT INTO district_percentage_sub (unique_id, screen_unique_id,  hostel_type,   percentage) 
                VALUES (?, ?, ?, ?)";

                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("ssss", $new_id, $screen_unique_id, $hostel_type,  $percentage);

                if ($stmt->execute()) {
                    $msg = "create";
                } else {
                    $msg = "error";
                }
            }
        }

        echo json_encode([
            "status" => true,
            "msg" => $msg
        ]);
        break;



    case 'sublist_delete':
        $unique_id = $_POST['unique_id'];
        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            echo json_encode(['status' => false, 'msg' => 'csrf_failed']);
            return;
        }

        $sql = "UPDATE $table SET is_delete = 1 WHERE unique_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $unique_id);

        if ($stmt->execute()) {
            echo json_encode(['status' => true, 'msg' => 'deleted']);
        } else {
            echo json_encode(['status' => false, 'msg' => 'error']);
        }

        break;


    case 'get_sublist_item':
        $unique_id = $_POST['unique_id'];
        $sql = "SELECT * FROM district_percentage_sub WHERE unique_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $unique_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        echo json_encode([
            "status" => true,
            "data" => $data
        ]);
        break;

    case 'item_category':
        $item_category = $_POST['item_category'];
        $veg_type = $_POST['veg_type'] ?? '';

        $item_category_options = item_veg('', $item_category, $veg_type);
        $item_name_options = select_option($item_category_options, "Select item");

        echo $item_name_options;
        break;

    case 'copy_record':

        $csrf_token = $_POST['csrf_token'] ?? '';
        if (!validateCSRFToken($csrf_token)) {
            throw new Exception('CSRF validation failed.');
        }

        $screen_unique_id = sanitizeInput($_POST['screen_unique_id'] ?? '');
        $new_districts = $_POST['district'] ?? []; // Array of districts
        $month = sanitizeInput($_POST['month'] ?? '');

        // Remove any empty values (like the first empty one if it starts with a comma)
        $new_districts = array_filter($new_districts, fn($v) => !empty(trim($v)));

        if (empty($new_districts)) {
            echo json_encode(["status" => false, "msg" => "No district selected."]);
            break;
        }

        // Fetch master record by screen_unique_id
        $stmt = $mysqli->prepare("SELECT * FROM district_percentage WHERE screen_unique_id = ? AND is_delete = 0 LIMIT 1");
        $stmt->bind_param("s", $screen_unique_id);
        $stmt->execute();
        $master = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$master) {
            echo json_encode(["status" => false, "msg" => "Original master record not found."]);
            break;
        }

        // Fetch related sublist records
        $stmt = $mysqli->prepare("SELECT * FROM district_percentage_sub WHERE screen_unique_id = ? AND is_delete = 0");
        $stmt->bind_param("s", $screen_unique_id);
        $stmt->execute();
        $sublist = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        foreach ($new_districts as $new_district) {

            // Check if district-month already exists in master
            $stmt = $mysqli->prepare("SELECT COUNT(*) as cnt FROM district_percentage WHERE district = ? AND month = ? AND is_delete = 0");
            $stmt->bind_param("ss", $new_district, $month);
            $stmt->execute();
            $exists = $stmt->get_result()->fetch_assoc()['cnt'];
            $stmt->close();

            if ($exists > 0)
                continue; // Skip existing district-month

            // Check sublist duplicates
            $stmt = $mysqli->prepare("SELECT COUNT(*) as cnt FROM district_percentage_sub WHERE district = ? AND month = ? AND is_delete = 0");
            $stmt->bind_param("ss", $new_district, $month);
            $stmt->execute();
            $exists_sublist = $stmt->get_result()->fetch_assoc()['cnt'];
            $stmt->close();

            if ($exists_sublist > 0)
                continue; // Skip existing sublist

            // Generate new IDs for master
            $new_unique_id = unique_id();
            $new_screen_id = unique_id();

            // Insert master record for this district
            $stmt = $mysqli->prepare("INSERT INTO district_percentage (unique_id, screen_unique_id, district, month, is_delete) VALUES (?, ?, ?, ?, 0)");
            $stmt->bind_param("ssss", $new_unique_id, $new_screen_id, $new_district, $month);
            $stmt->execute();
            $stmt->close();

            // Insert sublist records for this district
            foreach ($sublist as $row) {
                $sub_unique_id = unique_id();
                $hostel_type = $row['hostel_type'];
                $percentage = $row['percentage'];
                $row_month = $row['month'];

                $stmt = $mysqli->prepare("INSERT INTO district_percentage_sub (unique_id, screen_unique_id, district, month, hostel_type, percentage, is_delete) VALUES (?, ?, ?, ?, ?, ?, 0)");
                $stmt->bind_param("ssssss", $sub_unique_id, $new_screen_id, $new_district, $row_month, $hostel_type, $percentage);
                $stmt->execute();
            }
        }

        $status = true;
        $msg = "add";
        $description = "Copied Successfully";

        echo json_encode([
            "status" => $status,
            "msg" => $msg,
            "description" => "Selected districts copied successfully."
        ]);
        break;


    case 'get_unit':
        $item_unique_id = $_POST['item_name'] ?? '';

        if ($item_unique_id) {
            // Prepare SQL
            $sql = "SELECT unit FROM item WHERE unique_id = ? AND is_delete = 0 LIMIT 1";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("s", $item_unique_id);
            $stmt->execute();
            $stmt->bind_result($unit);
            $stmt->fetch();
            $stmt->close();

            // Return the unit as plain text (for JS)
            echo $unit ?? '';
        } else {
            echo '';
        }
        break;

    case 'silent_delete':
        if (!empty($_POST['screen_unique_id'])) {
            $screen_unique_id = $_POST['screen_unique_id'];

            $stmt = $mysqli->prepare("DELETE FROM district_percentage_sub WHERE screen_unique_id = ?");
            $stmt->bind_param("s", $screen_unique_id);
            $stmt->execute();
            $stmt->close();
        }
        exit; // no output, completely silent
        break;

    default:

        break;
}

function formatMonthYear($value) {
    $date = DateTime::createFromFormat('Y-m', $value);
    return $date ? $date->format('F, Y') : '';
}