<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "indent_count";

// // Include DB file and Common Functions
include '../config/dbconfig.php';

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
$acc_year = $_SESSION['academic_year'];

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
    $search_value = $_POST['search']['value'] ?? '';
    $limit = $length == '-1' ? "" : $length;

    $data = [];

    // Columns to fetch
    $columns = [
        "unique_id",
        "'' as s_no",
        "hostel_id",
        "(SELECT taluk_name FROM taluk_creation WHERE taluk_creation.unique_id = hostel_name.taluk_name) AS taluk",
        "hostel_name"
        
    ];

    $table_details = 'hostel_name';

    // WHERE condition
    $where = 'district_name = "' . $_SESSION['district_id'] . '" and is_delete = 0 ';

    // Global search
    if (!empty($search_value)) {
        $where .= " AND (
            hostel_id LIKE '%$search_value%'
        )";
    }

    $order_by = " ORDER BY hostel_id ASC";

    $sql_function = "SQL_CALC_FOUND_ROWS";

    // Main query
    $sql = "SELECT $sql_function " . implode(", ", $columns) . " 
            FROM $table_details 
            WHERE $where
            $order_by";

    // if ($limit) {
    //     $sql .= " LIMIT ?, ?";
    // }

    // Prepare
    $stmt = $mysqli->prepare($sql);

    // if ($limit) {
    //     $stmt->bind_param("ii", $start, $limit);
    // } else {
    //     $stmt->bind_param("i", $start);
    // }

    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch total records
    $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
    $total_records = $total_records_result->fetch_assoc()['total'];

    if ($result) {

        $res_array = $result->fetch_all(MYSQLI_ASSOC);
        $sno = $start + 1;

        foreach ($res_array as $value) {
            $value['s_no'] = $sno++;
            $value['unique_id'] = '<input type="checkbox" class="row-check" onclick="toggleRowCheckbox()" value="' . $value['unique_id'] . '">
                                <input type="hidden" id="hostel_id" name="hostel_id[]" value="' . $value['hostel_id'] . '">';

            $data[] = array_values($value);
        }

        $json_array = [
            "draw" => intval($draw),
            "recordsTotal" => intval($total_records),
            "recordsFiltered" => intval($total_records),
            "data" => $data
        ];

    } else {
        print_r($result);
    }

    echo json_encode($json_array);

    // Close connection
    $stmt->close();
    $mysqli->close();

    break;


    case 'reject':
        $csrf_token = $_POST['csrf_token'];
        $hostel_id_rej_val = $_POST['hostel_id_rej_val'];
        $reject_reason = trim($_POST['reject_reason']);
        $month_year = trim($_POST['month_year']);
        // CSRF validation

        if (!validateCSRFToken($csrf_token)) {
            echo json_encode(['status' => false, 'msg' => 'Invalid CSRF token']);
            exit;
        }

        // Update query to set request_status = 0 and store reject reason
        $sql = "UPDATE indent_count 
            SET request_status = 0, acc_rej_status = 2, 
                reject_reason = ?, 
                updated_at = NOW()
            WHERE hostel_id = ? AND month_year = ?";

        $stmt = $mysqli->prepare($sql);

        $stmt->bind_param('sss', $reject_reason, $hostel_id_rej_val, $month_year);

        if ($stmt->execute()) {
            echo json_encode(['status' => true, 'msg' => 'reject']);
        } else {
            echo json_encode(['status' => false, 'msg' => 'Failed to reject request.']);
        }
        $stmt->close();
        break;

    case 'approve_count':

        $hostel_id = $_POST['hostel_id'] ?? '';
        $request_count = $_POST['request_count'] ?? '';
        $approved_count = $_POST['approved_count'] ?? '';
        $base_count = $_POST['base_count'] ?? '';
        $month_year = $_POST['month_year'] ?? '';
        $csrf_token = $_POST['csrf_token'] ?? '';

        if (empty($hostel_id)) {
            echo json_encode([
                'status' => false,
                'msg' => 'Invalid Hostel ID.'
            ]);
            return;
        }

        if (!is_numeric($approved_count) || !is_numeric($base_count)) {
            echo json_encode([
                'status' => false,
                'msg' => 'Invalid count values.'
            ]);
            return;
        }

        $sql = "UPDATE indent_count 
            SET ho_approved_count = ?, request_status = 0, final_count = ?, acc_rej_status = 1, 
                updated_at = NOW() 
            WHERE hostel_id = ? AND month_year = ?";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("iiss", $approved_count, $approved_count, $hostel_id, $month_year);

        if ($stmt->execute()) {
            echo json_encode([
                'status' => true,
                'msg' => 'add'
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'msg' => 'Database update failed.'
            ]);
        }

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

    case 'notification_count':
        // Example query: count of unread requests
        $stmt = $mysqli->prepare("SELECT COUNT(*) as cnt FROM indent_count WHERE district_name ='". $_SESSION['district_id']. "'and request_status = 1");
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        echo json_encode([
            'status' => true,
            'count' => $result['cnt'] ?? 0
        ]);
        break;

    case 'notification_list':
        $stmt = $mysqli->prepare("SELECT hostel_id, dadwo_requested_count FROM indent_count WHERE district_name ='". $_SESSION['district_id']. "' and request_status = 1");
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        if (count($result) > 0) {
            echo json_encode([
                'status' => true,
                'data' => $result
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'message' => 'No new notifications available'
            ]);
        }
        break;

case 'get_hostels':
    $stmt = $mysqli->prepare("SELECT unique_id, hostel_id, hostel_name, (select taluk_name from taluk_creation where unique_id=hostel_name.taluk_name and is_delete=0) as taluk_name FROM hostel_name WHERE district_name ='". $_SESSION['district_id']. "'");
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    if (count($result) > 0) {
        echo json_encode([
            'status' => true,
            'data' => $result
        ]);
    } else {
        echo json_encode([
            'status' => false,
            'message' => 'No Data available'
        ]);
    }
    break;

    
    default:

        break;
}

function formatMonthYear($month_year) {
    $month_year = trim($month_year);
    $month_year = str_replace(['/', '.', ' '], '-', $month_year); // normalize separators

    $parts = explode('-', $month_year);

    // Determine which part is month and which is year
    if (strlen($parts[0]) == 4) {
        // Format is YYYY-MM
        $year = $parts[0];
        $month = $parts[1];
    } else {
        // Format is MM-YYYY
        $month = $parts[0];
        $year = $parts[1];
    }

    // Ensure month is two digits
    $month = str_pad($month, 2, '0', STR_PAD_LEFT);

    // Get month name
    $monthName = date('F', mktime(0, 0, 0, $month, 1));

    return $monthName . "<br>" . $year;
}