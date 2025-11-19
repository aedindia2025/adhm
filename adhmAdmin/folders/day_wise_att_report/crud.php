<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// Database Country Table Name
$table = "dayWise_att_status";

// Include DB file and Common Functions
include '../../config/dbconfig.php';

// Variables Declaration
$action = $_POST['action'];

$json_array = "";
$sql = "";

$feedback_type = "";
//$is_active = "";
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
        $search     = $_POST['search']['value'];
        $length     = $_POST['length'];
        $start      = $_POST['start'];
        $draw       = $_POST['draw'];
        $limit      = $length;

        $data       = [];

        if ($length == '-1') {
            $limit = "";
        }


        if ($_POST['from_date']) {
            $where = 'currentDate = "' . $_POST['from_date'] . '"';
        }


        // Query Variables
        $json_array = "";
        $columns = [
            "'' as s_no",
            "(SELECT district_name FROM district_name WHERE district_name.unique_id = dayWise_att_status.district_name) as district",
            "hostel_id",
            "(SELECT hostel_name FROM hostel_name WHERE hostel_name.unique_id = dayWise_att_status.hostel_unique_id) as hostel_name",
            "(SELECT sanctioned_strength FROM hostel_name WHERE hostel_name.unique_id = dayWise_att_status.hostel_unique_id) as sanctioned_strength",
            "'' as dadwo_approved",
            "'' as bio_reg",
            "'' as mrg_status",
            "'' as eve_status",
            "hostel_unique_id"
        ];
        $table_details = [
            $table,
            $columns
        ];
        $order_by = "";

        $where .= "GROUP BY hostel_id, currentDate ORDER BY hostel_id ASC";

        $sql_function = "SQL_CALC_FOUND_ROWS";

        $result = $pdo->select($table_details, $where, $limit, $start, $order_by, $sql_function);
        $total_records = total_records();

        if ($result->status) {
            $res_array = $result->data;

            // Start serial number from the correct position
            $sno = $start + 1;

            foreach ($res_array as $key => $value) {
                $value['s_no'] = $sno++;

                $value['dadwo_approved'] = get_std_hostel_reg_count($value['hostel_unique_id']);
                $value['bio_reg'] = get_std_hostel_push_count($value['hostel_unique_id']);
                $value['mrg_status'] = get_status($value['hostel_id'], $_POST['from_date'], 1);
                $value['eve_status'] = get_status($value['hostel_id'], $_POST['from_date'], 2);

                $data[] = array_values($value);
            }

            $json_array = [
                "draw"              => intval($draw),
                "recordsTotal"      => intval($total_records),
                "recordsFiltered"   => intval($total_records),
                "data"              => $data,
            ];
        }

        echo json_encode($json_array);
        break;

    case 'get_hostel_by_taluk_name':

        $taluk_name = $_POST['taluk_name'];


        $hostel_name_options = hostel_name('', $taluk_name);

        $hostel_name_options = select_option_host($hostel_name_options, "Select Hostel");

        echo $hostel_name_options;

        break;

    case 'district_name':

        $district_name = $_POST['district_name'];


        $district_name_options = taluk_name('', $district_name);

        $taluk_name_options = select_option($district_name_options, "Select Taluk");

        echo $taluk_name_options;

        break;

    default:

        break;
}


function get_std_hostel_reg_count($unique_id = "")
{
    global $pdo;

    $table_name = "std_reg_s";
    $where = [];
    $table_columns = [
        "COUNT(id) as count"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_delete" => 0,
        "hostel_1" => $unique_id
    ];

    $desination_type_list = $pdo->select($table_details, $where);

    if ($desination_type_list->status) {
        return $desination_type_list->data[0]['count'];
    } else {
        print_r($desination_type_list);
        return 0;
    }
}

function get_std_hostel_push_count($unique_id = "")
{
    global $pdo;

    $table_name = "std_reg_s";
    $where = [];
    $table_columns = [
        "COUNT(id) as count"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_delete" => 0,
        "hostel_1" => $unique_id,
        "bio_reg_status" => 1
    ];

    $desination_type_list = $pdo->select($table_details, $where);

    if ($desination_type_list->status) {
        return $desination_type_list->data[0]['count'];
    } else {
        print_r($desination_type_list);
        return 0;
    }
}


// function get_status($hostel_id, $currentDate, $am_pm)
// {
//     global $pdo;

//     // Determine the column based on am_pm value
//     $punch_column = ($am_pm == 1) ? "morning_punch_count" : "evening_punch_count";

//     // Check attendance record for punch count
//     $attendance_table = "attendance_report";
//     $attendance_where = [
//         "hostel_id" => $hostel_id,
//         "report_date" => $currentDate
//     ];

//     $attendance_columns = [$punch_column];
//     $attendance_details = [$attendance_table, $attendance_columns];
//     $attendance_result = $pdo->select($attendance_details, $attendance_where);

//     if ($attendance_result->status && !empty($attendance_result->data)) {
//         $punch_count = $attendance_result->data[0][$punch_column];

//         if ($punch_count > 0) {
//             return "Punched";
//         }
//     }

//     // Check dayWise_att_status table for error conditions
//     $status_table = "dayWise_att_status";
//     $status_where = [
//         "hostel_id" => $hostel_id,
//         "currentDate" => $currentDate,
//         "am_pm" => $am_pm
//     ];

//     $status_columns = ["error_count", "no_records", "manual_user"];
//     $status_details = [$status_table, $status_columns];
//     $status_result = $pdo->select($status_details, $status_where);

//     if ($status_result->status && !empty($status_result->data)) {
//         $error_count = $status_result->data[0]['error_count'];
//         $no_records = $status_result->data[0]['no_records'];
//         $manual_user = $status_result->data[0]['manual_user'];

//         if ($error_count > 0) {
//             return "Stranger";
//         } elseif ($no_records == 1) {
//             return "No record found";
//         } elseif ($manual_user > 0) {
//             return "Manual user";
//         }
//     }

//     return "Not Available";
// }


// function get_status($hostel_id, $currentDate, $am_pm)
// {
//     global $pdo;

//     // Define table and columns
//     $status_table = "dayWise_att_status";
//     $status_columns = ["offline", "success", "error_count", "no_records", "manual_user", "retry", "incorrect"];

//     // Fetch status details
//     $status_result = $pdo->select(
//         [$status_table, $status_columns],
//         ["hostel_id" => $hostel_id, "report_date" => $currentDate, "am_pm" => $am_pm]
//     );

//     if ($status_result->status && !empty($status_result->data)) {
//         $row = $status_result->data[0];
//         $offline = $row['offline'] ?? 0;
//         $success = $row['success'] ?? 0;
//         $error_count = $row['error_count'] ?? 0;
//         $no_records = $row['no_records'] ?? 0;
//         $manual_user = $row['manual_user'] ?? 0;
//         $retry = $row['retry'] ?? 0;
//         $incorrect = $row['incorrect'] ?? 0;

//         // Check status conditions
//         if ($success > 0 && $success !== $manual_user) {
//             return "Punched";
//         }
//         if ($offline == 1 && $no_records == 0 && $success == 0 && $error_count == 0 && $manual_user == 0 && $retry == 0 && $incorrect == 0) {
//             return "Offline";
//         }
//         if ($manual_user > 0 && $manual_user === $success) {
//             return "Manual Registered";
//         }
//         if ($no_records == 1 && $offline == 0 && $success == 0 && $error_count == 0 && $manual_user == 0 && $retry == 0 && $incorrect == 0) {
//             return "No Records Found";
//         }
//         if ($error_count > 0 && $success == 0) {
//             return "Stranger";
//         }
//         if ($incorrect == 1 && $success == 0 && $error_count == 0) {
//             return "Incorrect Credentials";
//         }
//         if ($retry == 1 && $success == 0 && $error_count == 0 && $manual_user == 0) {
//             return "Cant Fetch Network Issue";
//         }
//     }

//     return "Not Available";
// }



function get_status($hostel_id, $currentDate, $am_pm)
{
    global $pdo;

    $status_table = "dayWise_att_status";
    $status_where = [
        "hostel_id" => $hostel_id,
        "currentDate" => $currentDate,
        "am_pm" => $am_pm
    ];
    $status_columns = ["offline", "success", "error_count", "no_records", "manual_user", "retry", "incorrect"];
    $status_details = [$status_table, $status_columns];
    $status_result = $pdo->select($status_details, $status_where);

    if ($status_result->status && !empty($status_result->data)) {
        $offline = $status_result->data[0]['offline'];
        $success = $status_result->data[0]['success'];
        $error_count = $status_result->data[0]['error_count'];
        $no_records = $status_result->data[0]['no_records'];
        $manual_user = $status_result->data[0]['manual_user'];
        $retry = $status_result->data[0]['retry'];
        $incorrect = $status_result->data[0]['incorrect'];


        if ($success > 0 && $success !== $manual_user) {
            return "Punched";
        } elseif ($manual_user > 0 && $manual_user === $success) {
            return "Manual Registered";
        } elseif ($offline == 1 && !$no_records && !$success && !$error_count && !$manual_user && !$retry && !$incorrect) {
            return "Offline";
        } elseif ($no_records == 1 && !$offline && !$success && !$error_count && !$manual_user && !$retry && !$incorrect) {
            return "No Records Found";
        } elseif ($error_count > 0 && !$success) {
            return "Stranger";
        } elseif ($incorrect == 1 && !$success && !$error_count) {
            return "Incorrect Credentials";
        } elseif ($retry == 1 && !$success && !$error_count && !$manual_user) {
            return "Cant Fetch Network Issue";
        } else {
            return "Unknown Status";
        }
    }

    return "Not Available";
}
