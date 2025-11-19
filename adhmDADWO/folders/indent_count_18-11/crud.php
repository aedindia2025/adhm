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
        $limit = $length == '-1' ? "" : $length;

        $data = [];

        // Query Variables
        $json_array = "";
        $columns = [
            "' ' as sno",
            "hostel_id",
            "(SELECT hostel_name FROM hostel_name WHERE hostel_name.unique_id = indent_count.hostel_unique_id) AS hostel_name",
            "(SELECT district_name FROM district_name WHERE district_name.unique_id = indent_count.district_name) AS district_name",
            "month_year",
            "(SELECT hostel_type FROM hostel_type WHERE hostel_type.unique_id = indent_count.hostel_type) AS hostel_type",
            // "base_count",
            "(SELECT COUNT(*) FROM std_reg_s WHERE academic_year = '$acc_year' AND is_delete = 0 AND hostel_1 = indent_count.hostel_unique_id) AS dadwo_approved_count",
            "dadwo_requested_count",
            "ho_approved_count",
            "final_count",
            "'' AS unique_id",
            "percentage_applied",
            "acc_rej_status"
        ];
        $table_details = $table . " , (SELECT @a:= ?) AS a ";
        $where = 'district_name = "' . $_SESSION['district_id'] . '"';
        // $order_by = " ORDER BY request_status DESC";

        $sql_function = "SQL_CALC_FOUND_ROWS";

        // SQL query for data fetching
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_details WHERE $where";
        //   WHERE $where
        if ($limit) {
            $sql .= " LIMIT ?, ?";
        }

        // print_r($sql);
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
                $value['month_year'] = formatMonthYear($value['month_year']);

                $btn_request = '
                        <button type="button" class="btn btn-sm openRequest" title="Request" 
                                data-hostel-id="' . $value['hostel_id'] . '" 
                                style="padding: 2px 8px; line-height: 1; border-radius: 4px; background-color: #007bff; color: #fff;">
                            Request
                        </button>';

                // Determine what to show in the button column
                if (!empty($value['percentage_applied']) && $value['percentage_applied'] != "-") {
                    // If percentage is applied, no button
                    $value['unique_id'] = "-";
                } elseif (!empty($value['dadwo_requested_count']) && $value['dadwo_requested_count'] != "-") {
                    // If already requested, check acceptance/rejection status
                    if (isset($value['acc_rej_status']) && $value['acc_rej_status'] == 1) {
                        $value['unique_id'] = '<span style="color: green; font-weight: bold;">Approved</span>';
                    } elseif (isset($value['acc_rej_status']) && $value['acc_rej_status'] == 2) {
                        $value['unique_id'] = '<span style="color: red; font-weight: bold;">Rejected</span><br>'.$btn_request;
                    } else {
                        $value['unique_id'] = '<span style="color: green; font-weight: bold;">Requested</span>';
                    }

                } else {
                    $value['unique_id'] = $btn_request;
                }


                $value['dadwo_requested_count'] = !empty($value['dadwo_requested_count']) ? $value['dadwo_requested_count'] : "-";
                $value['ho_approved_count'] = !empty($value['ho_approved_count']) ? $value['ho_approved_count'] : "-";
                $value['percentage_applied'] = !empty($value['percentage_applied']) ? $value['percentage_applied'] : "-";

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

    case 'request':
        $csrf_token = $_POST['csrf_token'];
        $req_hostel_id = $_POST['req_hostel_id'];
        $request_count = trim($_POST['request_count']);
        // CSRF validation

        if (!validateCSRFToken($csrf_token)) {
            echo json_encode(['status' => false, 'msg' => 'Invalid CSRF token']);
            exit;
        }

        // Update query to set request_status = 0 and store reject reason
        $sql = "UPDATE indent_count 
            SET request_status = 1, 
                dadwo_requested_count = ?, acc_rej_status = 0,
                updated_at = NOW()
            WHERE hostel_id = ? AND is_online = 1";

        $stmt = $mysqli->prepare($sql);


        $stmt->bind_param('is', $request_count, $req_hostel_id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => true, 'msg' => 'add']);
            } else {
                // No rows updated → hostel is not online
                echo json_encode(['status' => true, 'msg' => 'not_online']);
            }
        } else {
            echo json_encode(['status' => false, 'msg' => 'Failed to reject request.']);
        }
        $stmt->close();
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