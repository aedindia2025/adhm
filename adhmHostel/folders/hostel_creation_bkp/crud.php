<?php

// Get folder Name From Currnent Url     
$folder_name        = explode("/", $_SERVER['PHP_SELF']);
$folder_name        = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table             = "hostel_name";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action             = $_POST['action'];

$hostel_name          = "";
$is_active          = "";
$unique_id          = "";
$prefix             = "";

$data               = "";
$msg                = "";
$error              = "";
$status             = "";
$test               = ""; // For Developer Testing Purpose

$ses_hostel_id      = $_SESSION['hostel_id'];


switch ($action) {
    case 'createupdate':

        $hostel_name                    = $_POST["hostel_name"];
        $hostel_id                      = $_POST["hostel_id"];
        $district_name                  = $_POST["district_name"];
        $taluk_name                     = $_POST["taluk_name"];
        $special_tahsildar              = $_POST["special_tahsildar"];
        $assembly_const                 = $_POST["assembly_const"];
        $parliment_const                = $_POST["parliment_const"];
        $address                        = $_POST["address"];
        $hostel_location                = $_POST["hostel_location"];
        $urban_type                     = $_POST["urban_type"];
        $corporation                    = $_POST["corporation"];
        $municipality                   = $_POST["municipality"];
        $town_panchayat                 = $_POST["town_panchayat"];
        $block                          = $_POST["block"];
        $village_name                   = $_POST["village_name"];
        $hostel_type                    = $_POST["hostel_type"];
        $gender_type                    = $_POST["gender_type"];
        $yob                            = $_POST["yob"];
        $sanctioned_strength            = $_POST["sanctioned_strength"];
        $distance_btw_phc               = $_POST["distance_btw_phc"];
        $phc_name                       = $_POST["phc_name"];
        $distance_btw_ps                = $_POST["distance_btw_ps"];
        $staff_count                    = $_POST["staff_count"];
        $ps_name                        = $_POST["ps_name"];
        $latitude                       = $_POST["latitude"];
        $longitude                      = $_POST["longitude"];
        $is_active                      = $_POST["is_active"];
        $unique_id                      = $_POST["unique_id"];

        $allowedExts = array('pdf');
        $extension = pathinfo($_FILES['test_file']['name'], PATHINFO_EXTENSION);
        if ($_FILES['test_file']['type'] == 'application/pdf' && in_array(strtolower($extension), $allowedExts)) {
            $tem_name = random_strings(25) . '.pdf';

            move_uploaded_file($_FILES['test_file']['tmp_name'], '../../uploads/hostel_creation/' . $tem_name);

            // Set $file_names to the stored filename with .pdf extension
            $file_names = $tem_name;
            $file_org_names = $_FILES['test_file']['name'];
        }

        $update_where       = "";

        $columns            = [
            "hostel_name"                   => $hostel_name,
            "hostel_id"                     => $hostel_id,
            "district_name"                 => $district_name,
            "taluk_name"                    => $taluk_name,
            "special_tahsildar"             => $special_tahsildar,
            "assembly_const"                => $assembly_const,
            "parliment_const"               => $parliment_const,
            "address"                       => $address,
            "hostel_location"               => $hostel_location,
            "urban_type"                    => $urban_type,
            "corporation"                   => $corporation,
            "municipality"                  => $municipality,
            "town_panchayat"                => $town_panchayat,
            "block"                         => $block,
            "village_name"                  => $village_name,
            "hostel_type"                   => $hostel_type,
            "gender_type"                   => $gender_type,
            "yob"                           => $yob,
            "sanctioned_strength"           => $sanctioned_strength,
            "distance_btw_phc"              => $distance_btw_phc,
            "phc_name"                      => $phc_name,
            "distance_btw_ps"               => $distance_btw_ps,
            "ps_name"                       => $ps_name,
            "staff_count"                   => $staff_count,
            "go_attach_file"                => $file_names,
            "go_attach_org_name"            => $file_org_names,
            "latitude"                      => $latitude,
            "longitude"                     => $longitude,
            "is_ac8tive"                     => $is_active,
          
            "unique_id"                     => unique_id($prefix)
        ];

        // check already Exist Or not
        $table_details      = [
            $table,
            [
                "COUNT(unique_id) AS count"
            ]
        ];
        $select_where       = 'hostel_id = "' . $hostel_id . '"  AND is_delete = 0  ';

        // When Update Check without current id
        if ($unique_id) {
            $select_where   .= ' AND unique_id !="' . $unique_id . '" ';
        }

        $action_obj         = $pdo->select($table_details, $select_where);

        if ($action_obj->status) {
            $status     = $action_obj->status;
            $data       = $action_obj->data;
            $error      = "";
            $sql        = $action_obj->sql;
        } else {
            $status     = $action_obj->status;
            $data       = $action_obj->data;
            $error      = $action_obj->error;
            $sql        = $action_obj->sql;
            $msg        = "error";
        }
        if ($data[0]["count"]) {
            $msg        = "already";
        } else if ($data[0]["count"] == 0) {
            // Update Begins
            if ($unique_id) {

                unset($columns['unique_id']);

                $update_where   = [
                    "unique_id"     => $unique_id
                ];

                $action_obj     = $pdo->update($table, $columns, $update_where);

                // Update Ends
            } else {

                // Insert Begins            
                $action_obj     = $pdo->insert($table, $columns);
                // Insert Ends

            }

            if ($action_obj->status) {
                $status     = $action_obj->status;
                $data       = $action_obj->data;
                $error      = "";
                $sql        = $action_obj->sql;

                if ($unique_id) {
                    $msg        = "update";
                } else {
                    $msg        = "create";
                }
            } else {
                $status     = $action_obj->status;
                $data       = $action_obj->data;
                $error      = $action_obj->error;
                $sql        = $action_obj->sql;
                $msg        = "error";
            }
        }

        $json_array   = [
            "status"    => $status,
            "data"      => $data,
            "error"     => $error,
            "msg"       => $msg,
            "sql"       => $sql
        ];

        echo json_encode($json_array);

        break;

    case 'datatable':
        // DataTable Variables
        $search     = $_POST['search']['value'];
        $length     = $_POST['length'];
        $start      = $_POST['start'];
        $draw       = $_POST['draw'];
        $limit      = $length;

        $data       = [];


        if ($length == '-1') {
            $limit  = "";
        }

        // Query Variables
        $json_array     = "";
        $columns        = [
            "@a:=@a+1 s_no",
            "hostel_name",
            "is_active",
            "unique_id"
        ];
        $table_details  = [
            $table . " , (SELECT @a:= " . $start . ") AS a ",
            $columns
        ];
        $where          = "is_delete = 0 and unique_id = '$ses_hostel_id'";
        $order_by       = "";

        if ($_POST['search']['value']) {
            $where .= " AND hostel_name LIKE '" . mysql_like($_POST['search']['value']) . "' ";
        }

        // Datatable Searching
        $search         = datatable_searching($search, $columns);

        if ($search) {
            if ($where) {
                $where .= " AND ";
            }

            $where .= $search;
        }

        $sql_function   = "SQL_CALC_FOUND_ROWS";

        $result         = $pdo->select($table_details, $where, $limit, $start, $order_by, $sql_function);
        $total_records  = total_records();

        if ($result->status) {

            $res_array      = $result->data;

            foreach ($res_array as $key => $value) {
                $value['hostel_name'] = disname($value['hostel_name']);
                $value['is_active'] = is_active_show($value['is_active']);

                $btn_update         = btn_update($folder_name, $value['unique_id']);

                $unique_id  = $value['unique_id'];
                $eye_button = '<a class="btn btn-action specl2"  href="javascript:hwHostel_print(\'' . $unique_id . '\')"><button type="button" ><i class="fa fa-eye" ></i></button></a>';


                $value['unique_id'] = $btn_update.$eye_button;
                $data[]             = array_values($value);
            }

            $json_array = [
                "draw"              => intval($draw),
                "recordsTotal"      => intval($total_records),
                "recordsFiltered"   => intval($total_records),
                "data"              => $data,
                "testing"           => $result->sql
            ];
        } else {
            print_r($result);
        }

        echo json_encode($json_array);
        break;


    case 'delete':

        $unique_id      = $_POST['unique_id'];

        $columns        = [
            "is_delete"   => 1
        ];

        $update_where   = [
            "unique_id"     => $unique_id
        ];

        $action_obj     = $pdo->update($table, $columns, $update_where);

        if ($action_obj->status) {
            $status     = $action_obj->status;
            $data       = $action_obj->data;
            $error      = "";
            $sql        = $action_obj->sql;
            $msg        = "success_delete";
        } else {
            $status     = $action_obj->status;
            $data       = $action_obj->data;
            $error      = $action_obj->error;
            $sql        = $action_obj->sql;
            $msg        = "error";
        }

        $json_array   = [
            "status"    => $status,
            "data"      => $data,
            "error"     => $error,
            "msg"       => $msg,
            "sql"       => $sql
        ];

        echo json_encode($json_array);
        break;

    case 'get_taluk_name':

        $district_name          = $_POST['district_name'];

        $district_options  = taluk_name("", $district_name);

        $hostel_taluk_options  = select_option($district_options, "Select Taluk");

        echo $hostel_taluk_options;

        break;

    default:

        break;
}
