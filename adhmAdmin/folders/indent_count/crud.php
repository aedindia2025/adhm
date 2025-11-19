<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "indent_count";

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

        // Query Variables
        $json_array = "";
        $columns = [
            "'' as sno",
            "hostel_id",
            "(SELECT hostel_name FROM hostel_name WHERE hostel_name.unique_id = indent_count.hostel_unique_id) AS hostel_name",
            "(SELECT district_name FROM district_name WHERE district_name.unique_id = indent_count.district_name) AS district_name",
            "month_year",
            "(SELECT hostel_type FROM hostel_type WHERE hostel_type.unique_id = indent_count.hostel_type) AS hostel_type",
            "base_count",
            "(SELECT COUNT(*) FROM std_reg_s WHERE academic_year = '$acc_year' AND is_delete = 0 AND hostel_1 = indent_count.hostel_unique_id) AS dadwo_approved_count",
            "dadwo_requested_count",
            "ho_approved_count",
            "final_count",
            "request_status",
            "acc_rej_status",
            "percentage_applied"
        ];

        $table_details = $table;

        $bind_params = "";
        $bind_values = [];

        $where = "1=1";

        if (!empty($_POST['district_name'])) {
            $where .= "district_name = ?";
            $bind_params .= "s";
            $bind_values[] = $_POST['district_name'];
        }
        if (!empty($_POST['taluk_name'])) {
            $where .= " AND taluk_name = ?";
            $bind_params .= "s";
            $bind_values[] = $_POST['taluk_name'];
        }
        if (!empty($_POST['hostel_name'])) {
            $where .= " AND hostel_unique_id = ?";
            $bind_params .= "s";
            $bind_values[] = $_POST['hostel_name'];
        }

        // Global search
        if (!empty($search_value)) {
            $where .= " AND (
                hostel_id LIKE '%$search_value%' OR
                month_year LIKE '%$search_value%' OR
                base_count LIKE '%$search_value%' OR
                dadwo_requested_count LIKE '%$search_value%' OR
                ho_approved_count LIKE '%$search_value%' OR
                final_count LIKE '%$search_value%' OR
                percentage_applied LIKE '%$search_value%' OR
                (SELECT hostel_name FROM hostel_name WHERE hostel_name.unique_id = indent_count.hostel_unique_id) LIKE '%$search_value%' OR
                (SELECT district_name FROM district_name WHERE district_name.unique_id = indent_count.district_name) LIKE '%$search_value%' OR
                (SELECT hostel_type FROM hostel_type WHERE hostel_type.unique_id = indent_count.hostel_type) LIKE '%$search_value%'
            )";
        }

        $order_by = " ORDER BY request_status DESC";

        $sql_function = "SQL_CALC_FOUND_ROWS";

        // SQL query for data fetching
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_details WHERE $where $order_by";
        //   WHERE $where
        if ($limit) {
            $sql .= " LIMIT ?, ?";
        }
        // echo $sql;
        $stmt = $mysqli->prepare($sql);

        if ($limit) {
            $stmt->bind_param("ii", $start, $limit);
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
                $value['month_year'] = formatMonthYear($value['month_year']);

                $btn_approve = '
                <button type="button" class="btn btn-sm openPopup" 
                        title="Approve" 
                        data-hostel-id="' . $value['hostel_id'] . '" 
                        data-requested-count="' . $value['dadwo_requested_count'] . '" 
                        data-base-count="' . $value['base_count'] . '" 
                        data-final-count="' . $value['final_count'] . '" 
                        style="padding: 2px 8px; line-height: 1; border-radius: 4px; background-color: #009f00ff; color: #fff;">
                    Approve
                </button>';

                $btn_reject = '
                <button type="button" class="btn btn-sm openReject" 
                        title="Reject" 
                        data-hostel-id="' . $value['hostel_id'] . '" 
                        data-requested-count="' . $value['dadwo_requested_count'] . '" 
                        style="padding: 2px 8px; line-height: 1; border-radius: 4px; background-color: #ff0000ff; color: #fff;">
                    Reject
                </button>';

                $value['dadwo_requested_count'] = !empty($value['dadwo_requested_count']) ? $value['dadwo_requested_count'] : "-";
                $value['ho_approved_count'] = !empty($value['ho_approved_count']) ? $value['ho_approved_count'] : "-";
                $value['percentage_applied'] = !empty($value['percentage_applied']) ? $value['percentage_applied'] : "-";

                // Make entire row bold if request_status is 1
                if (isset($value['request_status']) && $value['request_status'] == 1) {
                    foreach ($value as $k => $v) {
                        if ($k !== 'request_status' && $k !== 'acc_rej_status') { // skip request_status and acc_rej_status
                            $value[$k] = "<b>" . $v . "</b>";
                        }
                    }
                }

                if (isset($value['request_status']) && $value['request_status'] == 1 && $value['acc_rej_status'] == 0) {
                    $value['request_status'] = $btn_approve . ' ' . $btn_reject;
                } else if ($value['acc_rej_status'] == 1) {
                    $value['request_status'] = '<span style="color: green; font-weight: bold;">Accepted</span>';
                } else if ($value['acc_rej_status'] == 2) {
                    $value['request_status'] = '<span style="color: red; font-weight: bold;">Rejected</span>';
                } else {
                    $value['request_status'] = '-';
                }

                $data[] = array_values(array: $value);
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

    case 'reject':
        $csrf_token = $_POST['csrf_token'];
        $hostel_id_rej_val = $_POST['hostel_id_rej_val'];
        $reject_reason = trim($_POST['reject_reason']);
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
            WHERE hostel_id = ?";

        $stmt = $mysqli->prepare($sql);

        $stmt->bind_param('ss', $reject_reason, $hostel_id_rej_val);

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
            WHERE hostel_id = ?";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("iis", $approved_count, $approved_count, $hostel_id);

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
        $stmt = $mysqli->prepare("SELECT COUNT(*) as cnt FROM indent_count WHERE request_status = 1");
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        echo json_encode([
            'status' => true,
            'count' => $result['cnt'] ?? 0
        ]);
        break;

    case 'notification_list':
        $stmt = $mysqli->prepare("SELECT hostel_id, dadwo_requested_count FROM indent_count WHERE request_status = 1");
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