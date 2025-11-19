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

$feedback_type = "";
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

        $district_name = sanitizeInput($_POST['district_name']);
        $taluk_name = sanitizeInput($_POST['taluk_name']);
        $hostel_name = sanitizeInput($_POST['hostel_name']);
        $academic_year = sanitizeInput($_POST['academic_year']);
        $app_status = sanitizeInput($_POST['app_status']);

        if ($length == '-1') {
            $limit = "";
        }

        // Query Variables
        $columns = [
            "@a:=@a+1 s_no",
            "entry_date",
            "std_app_no",
            "student_type",
            "submit_status",
            "'' as std_umis_emis_no",
            "'' as std_umis_emis_name",
            "(select gender from std_app_s2 where std_app_s2.s1_unique_id = $table.unique_id) as gender",
            "(select address from std_app_s2 where std_app_s2.s1_unique_id = $table.unique_id) as a_address",

            "hostel_district_1",
            "hostel_taluk_1",
            "hostel_1",
            "(select print_status from batch_creation where batch_creation.s1_unique_id = $table.unique_id limit 1) as print_status",
            "(select print_status from batch_creation where batch_creation.s1_unique_id = $table.unique_id limit 1) as phy_status",
            "(select rec_status from batch_creation where batch_creation.s1_unique_id = $table.unique_id limit 1) as rec_status",
            "status",
            "status_upd_date",
            "unique_id",


        ];
        $table = "std_app_s";
        $table_details = $table . ", (SELECT @a:= " . $start . ") AS a";
        $where = "is_delete = 0";
        $bindParams = [];
        $bindTypes = '';
        $order_by = "";

        if ($app_status != '') {
            $where .= " AND status =?";
            $bindParams[] = $app_status;
            $bindTypes .= 's';
        }
        if (!empty($academic_year)) {
            $where .= " AND academic_year=?";
            $bindParams[] = $academic_year;
            $bindTypes .= 's';
        }
        if (!empty($district_name)) {
            $where .= " AND hostel_district_1=?";
            $bindParams[] = $district_name;
            $bindTypes .= 's';
        }
        if (!empty($taluk_name)) {
            $where .= " AND hostel_taluk_1=?";
            $bindParams[] = $taluk_name;
            $bindTypes .= 's';
        }
        if (!empty($hostel_name)) {
            $where .= " AND hostel_1=?";
            $bindParams[] = $hostel_name;
            $bindTypes .= 's';
        }



        $sql = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns) . " FROM " . $table_details . " WHERE " . $where;
        $sql .= " ORDER BY entry_date DESC";

        if (!empty($limit)) {
            $sql .= " LIMIT ?, ?";
            $bindParams[] = $start;
            $bindParams[] = $limit;
            $bindTypes .= 'ii';
        }

        // Prepare statement
        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            die('MySQL prepare error: ' . $mysqli->error);
        }

        // Bind parameters
        if (!empty($bindParams)) {
            $stmt->bind_param($bindTypes, ...$bindParams);
        }

        // Execute query
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch data
        $data = [];
        while ($value = $result->fetch_assoc()) {
            $value['gender'] = hostel_gender_name($value['gender'])[0]['gender_type'];
            $value['student_type'] = hostel_type_name($value['student_type'])[0]['hostel_type'];

            $std_emis_name = fetchStdEmisNo($value['unique_id']);
            $umis_name = fetchUmisName($value['unique_id']);
            $no_umis_name = fetchNoUmisName($value['unique_id']);

            if (!empty($std_emis_name[0]['std_name'])) {
                $value['std_umis_emis_name'] = $std_emis_name[0]['std_name'];
                $value['std_umis_emis_no'] = $std_emis_name[0]['emis_no'];
            } elseif (!empty($umis_name[0]['umis_name'])) {
                $value['std_umis_emis_name'] = $umis_name[0]['umis_name'];
                $value['std_umis_emis_no'] = $umis_name[0]['umis_no'];
            } elseif (!empty($no_umis_name)) {
                $value['std_umis_emis_name'] = $no_umis_name;
                $value['std_umis_emis_no'] = 'No UMIS';
            } else {
                $value['std_umis_emis_name'] = '-';
                $value['std_umis_emis_no'] = '-';
            }
            switch ($value['submit_status']) {
                case 0:
                    $value['submit_status'] = 'Partially Submitted';
                    break;
                case 1:
                    $value['submit_status'] = 'Submitted';
                    break;

            }


            switch ($value['status']) {
                case 0:
                    $value['status'] = 'Pending';
                    break;
                case 1:
                    $value['status'] = 'Approved';
                    break;
                case 2:
                    $value['status'] = 'Rejected';
                    break;
            }
            $value['status_upd_date'] = $value['status_upd_date'] ?: '-';

            switch ($value['print_status']) {
                case 0:
                    $value['print_status'] = 'Pending';
                    break;
                case 1:
                    $value['print_status'] = 'Print For Disptatch';
                    break;

                case 2:
                    $value['print_status'] = 'Print For Disptatch';
                    break;
            }

            switch ($value['phy_status']) {
                case 0:
                    $value['phy_status'] = 'Pending';
                    break;
                case 1:
                    $value['phy_status'] = 'Printed';
                    break;
                case 2:
                    $value['phy_status'] = 'Submitted';
                    break;
            }

            $value['rec_status'] = $value['rec_status'] == '1' ? '<span style="color:green;">Received</span>' : '<span style="color:blue;">Pending</span>';
            if ($value['hostel_taluk_1'] != '') {
                $value['hostel_taluk_1'] = taluk_name($value['hostel_taluk_1'])[0]['taluk_name'];
            } else {
                $value['hostel_taluk_1'] = '-';
            }
            if ($value['hostel_1'] != '') {
                $value['hostel_1'] = hostel_name($value['hostel_1'])[0]['hostel_name'];
            } else {
                $value['hostel_1'] = '-';
            }
            if ($value['hostel_district_1'] != '') {
                $value['hostel_district_1'] = district_name($value['hostel_district_1'])[0]['district_name'];
            } else {
                $value['hostel_district_1'] = '-';
            }
            $unique_id = $value['unique_id'];
            $value['unique_id'] = '<a class="btn btn-action specl2-icon"  href="javascript:view_app(\'' . $unique_id . '\')"><button type="button" ><i class="fa fa-eye" ></i></button></a>';

            $data[] = array_values($value);
        }

        // Total records count
        $total_records = $mysqli->query("SELECT FOUND_ROWS() AS total")->fetch_assoc()['total'];

        $json_array = [
            "draw" => intval($draw),
            "recordsTotal" => intval($total_records),
            "recordsFiltered" => intval($total_records),
            "data" => $data
        ];

        echo json_encode($json_array);
        break;
    case 'delete':

        $unique_id = $_POST['unique_id'];

        $columns = [
            "is_delete" => 1
        ];

        $update_where = [
            "unique_id" => $unique_id
        ];

        $action_obj = $pdo->update($table, $columns, $update_where);

        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;
            $msg = "success_delete";
        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "error";
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            "sql" => $sql
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

function get_reason($unique_id = "")
{
    // echo $zone_name;

    global $pdo;

    $table_name = "batch_creation";
    $where = [];
    $table_columns = [
        "reason",
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0,
        // "batch_no"  => $unique_id,
        // "p1_unique_id"    => $unique_id,
    ];

    // if ($unique_id) {
    //     // $where              = [];
    //     $where["batch_no"] .= $unique_id;
    // }
    // if ($unique_id) {
    //     $where              = [];
    //     $where["unique_id"] = $unique_id;
    // }

    $amc_name_list = $pdo->select($table_details, $where);

    // print_r( $amc_name_list);

    if ($amc_name_list->status) {
        return $amc_name_list->data[0]['reason'];
    } else {
        print_r($amc_name_list);
        return 0;
    }
}

function fetchStdEmisNo($unique_id = "")
{
    // echo $zone_name;

    global $pdo;

    $table_name = "std_app_emis_s3";
    $where = [];
    $table_columns = [
        "std_name",
        "emis_no",
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0,
        // "batch_no"  => $unique_id,
        // "p1_unique_id"    => $unique_id,
    ];

    if ($unique_id) {
        // $where              = [];
        $where["s1_unique_id"] .= $unique_id;
    }
    // if ($unique_id) {
    //     $where              = [];
    //     $where["unique_id"] = $unique_id;
    // }

    $amc_name_list = $pdo->select($table_details, $where);

    // print_r( $amc_name_list);

    if ($amc_name_list->status) {
        return $amc_name_list->data;
    } else {
        print_r($amc_name_list);
        return 0;
    }
}


function fetchNoUmisName($unique_id = "")
{
    // echo $zone_name;

    global $pdo;

    $table_name = "std_app_umis_s4";
    $where = [];
    $table_columns = [
        "no_umis_name",
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0,
        // "batch_no"  => $unique_id,
        // "p1_unique_id"    => $unique_id,
    ];

    if ($unique_id) {
        // $where              = [];
        $where["s1_unique_id"] .= $unique_id;
    }
    // if ($unique_id) {
    //     $where              = [];
    //     $where["unique_id"] = $unique_id;
    // }

    $amc_name_list = $pdo->select($table_details, $where);

    // print_r( $amc_name_list);

    if ($amc_name_list->status) {
        return $amc_name_list->data[0]['no_umis_name'];
    } else {
        print_r($amc_name_list);
        return 0;
    }
}

function fetchUmisName($unique_id = "")
{
    // echo $zone_name;

    global $pdo;

    $table_name = "std_app_umis_s4";
    $where = [];
    $table_columns = [
        "umis_name",
        "umis_no",
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0,
        // "batch_no"  => $unique_id,
        // "p1_unique_id"    => $unique_id,
    ];

    if ($unique_id) {
        // $where              = [];
        $where["s1_unique_id"] .= $unique_id;
    }
    // if ($unique_id) {
    //     $where              = [];
    //     $where["unique_id"] = $unique_id;
    // }

    $amc_name_list = $pdo->select($table_details, $where);

    // print_r( $amc_name_list);

    if ($amc_name_list->status) {
        return $amc_name_list->data;
    } else {
        print_r($amc_name_list);
        return 0;
    }
}



function image_view($folder_name = "", $unique_id = "", $doc_file_name = "")
{
    // echo $dc_file_name;
    $file_names = explode(',', $doc_file_name);
    $image_view = '';

    if ($doc_file_name) {
        foreach ($file_names as $file_key => $doc_file_name) {
            if ($file_key != 0) {
                if ($file_key % 4 != 0) {
                    $image_view .= "&nbsp";
                } else {
                    $image_view .= "<br><br>";
                }
            }

            $cfile_name = explode('.', $doc_file_name);

            if ($doc_file_name) {

                if (($cfile_name[1] == 'jpg') || ($cfile_name[1] == 'png') || ($cfile_name[1] == 'jpeg')) {
                    $image_view .= '<a href="javascript:print_view(\'/' . $doc_file_name . '\')"><img src="uploads/' . $folder_name . '/' . $doc_file_name . '"  width="20%" ></a>';
                    // $image_view .= '<img src="uploads/'.$folder_name.'/'.$doc_name.'"  height="50px" width="50px" >';
                } else if ($cfile_name[1] == 'pdf') {
                    $image_view .= '<a href="javascript:print_pdf(\'/' . $doc_file_name . '\')"><img src="uploads/pdf.png"   width="20%" style="margin-left: 15px;" ></a>';
                } else if (($cfile_name[1] == 'pdf') || ($cfile_name[1] == 'xls') || ($cfile_name[1] == 'xlsx')) {
                    $image_view .= '<a href="javascript:print(\'/' . $doc_file_name . '\')"><img src="uploads/excel.png"  height="30px" width="30px" ></a>';
                } else if (($cfile_name[1] == 'txt') || ($cfile_name[1] == 'docx') || ($cfile_name[1] == 'doc')) {
                    $image_view .= '<a href="javascript:print(\'/' . $doc_file_name . '\')"><img src="uploads/word.png"  height="30px" width="30px" ></a>';
                }
            }
        }
    }

    return $image_view;
}
