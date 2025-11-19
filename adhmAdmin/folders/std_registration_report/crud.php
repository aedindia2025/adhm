<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "hostel_name";

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
    // DataTable Variables
    $search = $_POST['search']['value'];
    $length = $_POST['length'];
    $start = $_POST['start'];
    $draw = $_POST['draw'];
    $limit = $length;

    $data = [];

    if ($length == '-1') {
        $limit = "";
    }

    // Initialize @a only once
    $pdo->query("SET @a := {$start};");

    // Query Variables
    $json_array = "";
    $columns = [
        "@a:=@a+1 s_no",
        "(SELECT district_name FROM district_name WHERE district_name.unique_id = hostel_name.district_name) as district_name",
        "(SELECT taluk_name FROM taluk_creation WHERE taluk_creation.unique_id = hostel_name.taluk_name) as taluk_name",
        "hostel_name",
        "hostel_id",
        "sanctioned_strength",
        "'' AS reg_hostel",
	    "'' AS reg_device",
        "unique_id"
    ];
    $table_details = [
        $table,
        $columns
    ];

    $where = "is_delete = 0 AND hostel_id NOT LIKE '%ADWN%' AND is_active = 1";

    if ($_POST['district_name']) {
        $where .= " AND district_name = '" . $_POST['district_name'] . "'";
    }

    if ($_POST['taluk_name']) {
        $where .= " AND taluk_name = '" . $_POST['taluk_name'] . "'";
    }

    if ($_POST['hostel_name']) {
        $where .= " AND unique_id = '" . $_POST['hostel_name'] . "'";
    }

    $order_by = "";
    $sql_function = "SQL_CALC_FOUND_ROWS";

    $result = $pdo->select($table_details, $where, $limit, $start, $order_by, $sql_function);

    $total_records = total_records();

    if ($result->status) {
        $res_array = $result->data;

        foreach ($res_array as $key => $value) {
            $value['reg_hostel'] = get_std_hostel_reg_count($value['unique_id']);
            $value['reg_device'] = get_std_device_reg_count($value['unique_id']);
            
            $data[] = array_values($value);
        }

        $json_array = [
            "draw" => intval($draw),
            "recordsTotal" => intval($total_records),
            "recordsFiltered" => intval($total_records),
            "data" => $data,
            "testing" => $result->sql
        ];
    } else {
        print_r($result);
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

case 'get_applied_application_count':
        $unique_id = $_POST['unique_id'];

        $applied_count = get_applied_application_count($unique_id);

        echo json_encode($applied_count);

        break;
    default:

        break;
}


function get_std_hostel_reg_count($unique_id = "") {
    global $pdo;

    $table_name    = "std_reg_s";
    $where         = [];
    $table_columns = [
        "COUNT(id) as count"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where     = [
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

function get_std_device_reg_count($unique_id = "") {
    global $pdo;

    $table_name    = "std_reg_s";
    $where         = [];
    $table_columns = [
        "COUNT(id) as count"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where     = [
        "is_delete" => 0,
        "bio_reg_status" => 1,
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


function get_applied_application_count($unique_id = "") {
    global $pdo;

    $table_name = "std_app_s";
    $table_columns = ["COUNT(id) as count"];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "hostel_1" => $unique_id
    ];

    $result = $pdo->select($table_details, $where);

    if ($result->status) {
        return $result->data[0]['count'];
    } else {
        print_r($result);
        return 0;
    }
}