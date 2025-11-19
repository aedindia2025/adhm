<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// Database Country Table Name
$table = "attendance_report";

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

        if ($_POST['from_date'] && $_POST['to_date']) {
            $where = 'report_date >= "' . $_POST['from_date'] . '" and report_date <= "' . $_POST['to_date'] . '"';
        } else {
            if ($_POST['from_date']) {
                $where = 'report_date = "' . $_POST['from_date'] . '"';
            }
            if ($_POST['to_date']) {
                $where = 'report_date = "' . $_POST['to_date'] . '"';
            }
        }

        // Query Variables
        $json_array = "";
        $columns = [
            "'' as s_no",
            "hostel_id",
            "hostel_name",
            "district_name",
            "hostel_type",
            "sanctioned_strength",
            "dadwo_approved_count",
            "biometric_reg_count",
            "report_date",
            "morning_punch_count as morning_punch_count",  // Sum of morning punches
            "eve_punch_count as eve_punch_count"
        ];
        $table_details = [
            $table,
            $columns
        ];
        $order_by = "report_date ASC"; 

    
        $where .= " and hostel_unique_id = '".$_SESSION['hostel_id']."'"; 

        $sql_function = "SQL_CALC_FOUND_ROWS";

        $result = $pdo->select($table_details, $where, $limit, $start, $order_by, $sql_function);

        $total_records = total_records();

        if ($result->status) {
            $res_array = $result->data;

            // Start serial number from the correct position
            $sno = $start + 1;

            foreach ($res_array as $key => $value) {
                $value['s_no'] = $sno++;
		if (!empty($value['report_date'])) {
                    $originalDate = $value['report_date'];
                    $formattedDate = date("d-m-Y", strtotime($originalDate)); // Convert to DD-MM-YYYY
                    $value['report_date'] = $formattedDate;
                }


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
