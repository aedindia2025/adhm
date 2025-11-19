<?php

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
        $search = $_POST['search']['value'];
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = ($length == '-1') ? "" : intval($length);
        $data = [];

        // Define columns
        $columns = [
            "@a:=@a+1 as s_no",
            "(SELECT district_name FROM district_name WHERE unique_id = h.district_name) AS district_name",
            "(SELECT taluk_name FROM taluk_creation WHERE unique_id = h.taluk_name) AS taluk_name",
            "h.hostel_id",
            "h.hostel_name",
            "sanctioned_strength",
            "(SELECT gender_type FROM hostel_gender_type WHERE unique_id = h.gender_type) AS gender_type",
            "(SELECT hostel_type FROM hostel_type WHERE unique_id = h.hostel_type) AS hostel_type",
            "h.address",
            " '' AS latlong",
            "h.latitude",
            "h.longitude"
        ];

        // Table with LEFT JOIN to std_reg_s to exclude used hostels
        $table_details = [
            "hostel_name h 
         LEFT JOIN std_reg_s s ON s.hostel_1 = h.unique_id,
         (SELECT @a:=0) AS a",
            $columns
        ];

        // WHERE condition: only hostels not assigned to any student
        $where = "s.unique_id IS NULL";
        // Filter conditions
        if (!empty($_POST['district_name'])) {
            $where .= " AND district_name = '" . $_POST['district_name'] . "'";
        }

        if (!empty($_POST['taluk_name'])) {
            $where .= " AND taluk_name = '" . $_POST['taluk_name'] . "'";
        }

        if (!empty($_POST['hostel_name'])) {
            $where .= " AND hostel_name = '" . $_POST['hostel_name'] . "'";
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

                // $value['latlong'] = $value['latitude'] . $value['longitude'];
                $value['latlong'] = '<p>Lat: ' . $value['latitude'] . '</p>' .
                    '<p>Lon: ' . $value['longitude'] . '</p>';

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


