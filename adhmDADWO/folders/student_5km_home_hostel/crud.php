<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "std_app_s";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action = $_POST['action'];

$district_name = "";
$is_active = "";
$unique_id = "";
$prefix = "";

$data = "";
$msg = "";
$error = "";
$status = "";
$test = ""; // For Developer Testing Purpose


switch ($action) {


  case 'datatable':
    $search = $_POST['search']['value'];
    $length = $_POST['length'];
    $start = $_POST['start'];
    $draw = $_POST['draw'];
    $limit = ($length == '-1') ? "" : intval($length);
    $data = [];

    // Define columns
    $columns = [
        "@a:=@a+1 as s_no",
        "std_name",
        "(SELECT district_name FROM district_name WHERE district_name.unique_id = std_app_s.hostel_district_1) as district_name",
        "(SELECT taluk_name FROM taluk_creation WHERE taluk_creation.unique_id = std_app_s.hostel_taluk_1) as taluk_name",
        "(SELECT hostel_name FROM hostel_name WHERE hostel_name.unique_id = std_app_s.hostel_1) as hostel_name"
    ];

    $table_details = [
        $table . ", (SELECT @a:=0) AS a",
        $columns
    ];

    // Base where clause
    $where = "is_delete = 0 AND std_to_hostel_distance <= 5 ";

    // Filter conditions
    if (!empty($_POST['district_name'])) {
        $where .= " AND hostel_district_1 = '" . $_POST['district_name'] . "'";
    }

    if (!empty($_POST['taluk_name'])) {
        $where .= " AND hostel_taluk_1 = '" . $_POST['taluk_name'] . "'";
    }

    if (!empty($_POST['hostel_name'])) {
        $where .= " AND hostel_1 = '" . $_POST['hostel_name'] . "'";
    }

    if (!empty($_POST['academic_year'])) {
        $where .= " AND academic_year = '" . $_POST['academic_year'] . "'";
    }

    // Optional search
    if (!empty($search)) {
        $where .= " AND (std_name LIKE '%$search%' OR std_reg_no LIKE '%$search%' OR batch_no LIKE '%$search%')";
    }

    $order_by = ""; // You can add sorting if needed
    $sql_function = "SQL_CALC_FOUND_ROWS";

    $result = $pdo->select($table_details, $where, $limit, $start, $order_by, $sql_function);
    $total_records = total_records();

    if ($result->status) {
        $res_array = $result->data;

        foreach ($res_array as $value) {
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
            "testing" => $result->sql // Optional: show the final SQL for debugging
        ];
    } else {
        // Return an empty response with error info
        $json_array = [
            "draw" => intval($draw),
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => [],
            "error" => $result->error ?? "Query failed"
        ];
    }

    echo json_encode($json_array);
    break;

    case 'district_name':
        $district_name = $_POST['district_name'];

        $district_id_options = taluk_name('', $district_name);

        $taluk_name_options = select_option($district_id_options, 'Select Taluk');

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


