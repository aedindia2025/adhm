<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
// $table             = "leave_application";
$table_std_app = "std_app_p1";
$table = "holiday_creation";
$table_sub = "complaint_creation_doc_upload";
$table_stage_1 = "stage_1";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action = $_POST['action'];

$unique_id = $_GET["unique_id"];


$fund_name = "";
$is_active = "";
$unique_id = "";
$prefix = "";

$data = "";
$msg = "";
$error = "";
$status = "";
$test = ""; // For Developer Testing Purpose

$district_name = $_SESSION['district_id'];

switch ($action) {

    case 'get_applied_count':

        // $district_name = $_POST['district_name'];
        $taluk_name = $_POST['taluk_name'];
        $hostel_name = $_POST['hostel_name'];
        $gender_type = $_POST['gender_type'];
        $hostel_type = $_POST['hostel_type'];

        // Select only the count (no JOIN)
        $columns = [
            "COUNT(*) AS applied_cnt"
        ];

        // Table: only std_app_s
        $table_details = [
            "std_app_s a",
            $columns
        ];

        // Base WHERE for std_app_s
        $where = "a.is_delete = 0";

        if ($district_name != '') {
            $where .= " AND a.hostel_district_1 = '" . $district_name . "'";
        }
        if ($taluk_name != '') {
            $where .= " AND a.hostel_taluk_1 = '" . $taluk_name . "'";
        }
        if ($hostel_name != '') {
            $where .= " AND a.hostel_1 = '" . $hostel_name . "'";
        }

        // Build EXISTS subquery for hostel_name filtering
        $exists_conditions = "b.unique_id = a.hostel_1 AND b.is_delete = 0";

        // ✅ Gender filter supports multiple
        if (!empty($gender_type)) {
            if (is_array($gender_type)) {
                $gender_type = array_filter($gender_type, fn($val) => $val !== '');
                if (!empty($gender_type)) {
                    $gender_list = array_map(fn($val) => "'" . $val . "'", $gender_type);
                    $gender_str = implode(",", $gender_list);
                    $exists_conditions .= " AND b.gender_type IN (" . $gender_str . ")";
                }
            } else {
                if ($gender_type !== '') {
                    $exists_conditions .= " AND b.gender_type = '" . $gender_type . "'";
                }
            }
        }

        // ✅ Hostel type filter supports multiple
        if (!empty($hostel_type) && is_array($hostel_type)) {
            $hostel_type = array_filter($hostel_type, fn($val) => $val !== '');
            if (!empty($hostel_type)) {
                $hostel_type_list = array_map(fn($val) => "'" . $val . "'", $hostel_type);
                $hostel_type_str = implode(",", $hostel_type_list);
                $exists_conditions .= " AND b.hostel_type IN (" . $hostel_type_str . ")";
            }
        }

        // Add EXISTS subquery to WHERE
        $where .= " AND EXISTS (
        SELECT 1
        FROM hostel_name b
        WHERE $exists_conditions
        )";

        // Execute
        $result = $pdo->select($table_details, $where);
        $res_array = $result->data;

        $applied_cnt = 0;
        foreach ($res_array as $value) {
            $applied_cnt = $value['applied_cnt'];
        }

        $json_array = [
            "applied_cnt" => $applied_cnt,
            "sql" => $result->sql // keep for debugging
        ];

        echo json_encode($json_array);
        break;

    case 'get_accept_count':

        // $district_name = $_POST['district_name'];
        $taluk_name = $_POST['taluk_name'];
        $hostel_name = $_POST['hostel_name'];
        $gender_type = $_POST['gender_type'] ?? [];
        $hostel_type = $_POST['hostel_type'] ?? [];

        // Select only the count (no JOIN)
        $columns = [
            "COUNT(*) AS accp_cnt"
        ];

        // Table: only batch_creation
        $table_details = [
            "batch_creation a",
            $columns
        ];

        // Base WHERE for batch_creation
        $where = "a.is_delete = 0";

        if ($district_name != '') {
            $where .= " AND a.hostel_district = '" . $district_name . "'";
        }
        if ($taluk_name != '') {
            $where .= " AND a.hostel_taluk = '" . $taluk_name . "'";
        }
        if ($hostel_name != '') {
            $where .= " AND a.hostel_name = '" . $hostel_name . "'";
        }

        // Build EXISTS subquery for hostel_name filtering
        $exists_conditions = "b.unique_id = a.hostel_name AND b.is_delete = 0";

        // ✅ Gender filter (supports multiple)
        if (!empty($gender_type)) {
            if (is_array($gender_type)) {
                $gender_type = array_filter($gender_type, fn($val) => $val !== '');
                if (!empty($gender_type)) {
                    $gender_list = array_map(fn($val) => "'" . $val . "'", $gender_type);
                    $gender_str = implode(",", $gender_list);
                    $exists_conditions .= " AND b.gender_type IN (" . $gender_str . ")";
                }
            } else {
                if ($gender_type !== '') {
                    $exists_conditions .= " AND b.gender_type = '" . $gender_type . "'";
                }
            }
        }

        // ✅ Hostel type filter (supports multiple)
        if (!empty($hostel_type) && is_array($hostel_type)) {
            $hostel_type = array_filter($hostel_type, fn($val) => $val !== '');
            if (!empty($hostel_type)) {
                $hostel_type_list = array_map(fn($val) => "'" . $val . "'", $hostel_type);
                $hostel_type_str = implode(",", $hostel_type_list);
                $exists_conditions .= " AND b.hostel_type IN (" . $hostel_type_str . ")";
            }
        }

        // Add EXISTS subquery to WHERE
        $where .= " AND EXISTS (
                        SELECT 1
                        FROM hostel_name b
                        WHERE $exists_conditions
                    )";

        // Execute
        $result = $pdo->select($table_details, $where);
        $res_array = $result->data;

        $accp_cnt = 0;
        foreach ($res_array as $value) {
            $accp_cnt = $value['accp_cnt'];
        }

        $json_array = [
            "accp_cnt" => $accp_cnt,
            "sql" => $result->sql // keep for debugging
        ];

        echo json_encode($json_array);
        break;

    case 'get_approved_count':

        // $district_name = $_POST['district_name'];
        $taluk_name = $_POST['taluk_name'];
        $hostel_name = $_POST['hostel_name'];
        $gender_type = $_POST['gender_type'] ?? [];
        $hostel_type = $_POST['hostel_type'] ?? [];

        // Select only the count
        $columns = [
            "COUNT(*) AS approved_cnt"
        ];

        // Table: only std_reg_s
        $table_details = [
            "std_reg_s a",
            $columns
        ];

        // Base WHERE for std_reg_s
        $where = "a.is_delete = 0 AND a.dropout_status = '1'";

        if ($district_name != '') {
            $where .= " AND a.hostel_district_1 = '" . $district_name . "'";
        }
        if ($taluk_name != '') {
            $where .= " AND a.hostel_taluk_1 = '" . $taluk_name . "'";
        }
        if ($hostel_name != '') {
            $where .= " AND a.hostel_1 = '" . $hostel_name . "'";
        }

        // Build EXISTS subquery for hostel_name filtering
        $exists_conditions = "b.unique_id = a.hostel_1 AND b.is_delete = 0";

        // ✅ Gender filter
        if (!empty($gender_type)) {
            if (is_array($gender_type)) {
                $gender_type = array_filter($gender_type, fn($val) => $val !== '');
                if (!empty($gender_type)) {
                    $gender_list = array_map(fn($val) => "'" . $val . "'", $gender_type);
                    $exists_conditions .= " AND b.gender_type IN (" . implode(",", $gender_list) . ")";
                }
            } elseif ($gender_type !== '') {
                $exists_conditions .= " AND b.gender_type = '" . $gender_type . "'";
            }
        }

        // ✅ Hostel type filter
        if (!empty($hostel_type) && is_array($hostel_type)) {
            $hostel_type = array_filter($hostel_type, fn($val) => $val !== '');
            if (!empty($hostel_type)) {
                $hostel_list = array_map(fn($val) => "'" . $val . "'", $hostel_type);
                $exists_conditions .= " AND b.hostel_type IN (" . implode(",", $hostel_list) . ")";
            }
        }

        // Add EXISTS subquery to WHERE
        $where .= " AND EXISTS (
        SELECT 1
        FROM hostel_name b
        WHERE $exists_conditions
            )";

        // Execute
        $result = $pdo->select($table_details, $where);
        $res_array = $result->data;

        $approved_cnt = 0;
        foreach ($res_array as $value) {
            $approved_cnt = $value['approved_cnt'];
        }

        $json_array = [
            "approved_cnt" => $approved_cnt,
            "sql" => $result->sql // keep for debugging
        ];

        echo json_encode($json_array);
        break;

    case 'get_rejected_count':

        // $district_name = $_POST['district_name'];
        $taluk_name = $_POST['taluk_name'];
        $hostel_name = $_POST['hostel_name'];
        $gender_type = $_POST['gender_type'] ?? [];
        $hostel_type = $_POST['hostel_type'] ?? [];

        // Select count
        $columns = [
            "COUNT(*) AS rejected_cnt"
        ];

        // Table: batch_creation only
        $table_details = [
            "batch_creation a",
            $columns
        ];

        // Base WHERE for rejected batches
        $where = "a.is_delete = 0 AND a.status = '2'";

        if ($district_name != '') {
            $where .= " AND a.hostel_district = '" . $district_name . "'";
        }
        if ($taluk_name != '') {
            $where .= " AND a.hostel_taluk = '" . $taluk_name . "'";
        }
        if ($hostel_name != '') {
            $where .= " AND a.hostel_name = '" . $hostel_name . "'";
        }

        // Build EXISTS subquery for hostel_name filters
        $exists_conditions = "b.unique_id = a.hostel_name AND b.is_delete = 0";

        // Gender filter
        if (!empty($gender_type)) {
            if (is_array($gender_type)) {
                $gender_type = array_filter($gender_type, fn($val) => $val !== '');
                if (!empty($gender_type)) {
                    $gender_list = array_map(fn($val) => "'" . $val . "'", $gender_type);
                    $exists_conditions .= " AND b.gender_type IN (" . implode(",", $gender_list) . ")";
                }
            } elseif ($gender_type !== '') {
                $exists_conditions .= " AND b.gender_type = '" . $gender_type . "'";
            }
        }

        // Hostel type filter
        if (!empty($hostel_type) && is_array($hostel_type)) {
            $hostel_type = array_filter($hostel_type, fn($val) => $val !== '');
            if (!empty($hostel_type)) {
                $hostel_list = array_map(fn($val) => "'" . $val . "'", $hostel_type);
                $exists_conditions .= " AND b.hostel_type IN (" . implode(",", $hostel_list) . ")";
            }
        }

        // Add EXISTS subquery to WHERE
        $where .= " AND EXISTS (
        SELECT 1
        FROM hostel_name b
        WHERE $exists_conditions
    )";

        // Execute
        $result = $pdo->select($table_details, $where);
        $res_array = $result->data;

        $rejected_cnt = 0;
        foreach ($res_array as $value) {
            $rejected_cnt = $value['rejected_cnt'];
        }

        $json_array = [
            "rejected_cnt" => $rejected_cnt,
            "sql" => $result->sql // debug, remove later
        ];

        echo json_encode($json_array);

        break;

    case 'get_dropout_count':

        // $district_name = $_POST['district_name'];
        $taluk_name = $_POST['taluk_name'];
        $hostel_name = $_POST['hostel_name'];
        $gender_type = $_POST['gender_type'] ?? [];
        $hostel_type = $_POST['hostel_type'] ?? [];

        // Select only the count
        $columns = [
            "COUNT(*) AS dropout_cnt"
        ];

        // Table: std_reg_s only
        $table_details = [
            "std_reg_s a",
            $columns
        ];

        // Base WHERE for dropouts
        $where = "a.is_delete = 0 AND a.dropout_status = '2'"; // 2 = dropout

        if ($district_name != '') {
            $where .= " AND a.hostel_district_1 = '" . $district_name . "'";
        }
        if ($taluk_name != '') {
            $where .= " AND a.hostel_taluk_1 = '" . $taluk_name . "'";
        }
        if ($hostel_name != '') {
            $where .= " AND a.hostel_1 = '" . $hostel_name . "'";
        }

        // Build EXISTS subquery for hostel_name filtering
        $exists_conditions = "b.unique_id = a.hostel_1 AND b.is_delete = 0";

        // Gender filter
        if (!empty($gender_type)) {
            if (is_array($gender_type)) {
                $gender_type = array_filter($gender_type, fn($val) => $val !== '');
                if (!empty($gender_type)) {
                    $gender_list = array_map(fn($val) => "'" . $val . "'", $gender_type);
                    $exists_conditions .= " AND b.gender_type IN (" . implode(",", $gender_list) . ")";
                }
            } elseif ($gender_type !== '') {
                $exists_conditions .= " AND b.gender_type = '" . $gender_type . "'";
            }
        }

        // Hostel type filter
        if (!empty($hostel_type) && is_array($hostel_type)) {
            $hostel_type = array_filter($hostel_type, fn($val) => $val !== '');
            if (!empty($hostel_type)) {
                $hostel_list = array_map(fn($val) => "'" . $val . "'", $hostel_type);
                $exists_conditions .= " AND b.hostel_type IN (" . implode(",", $hostel_list) . ")";
            }
        }

        // Add EXISTS subquery to WHERE
        $where .= " AND EXISTS (
                        SELECT 1
                        FROM hostel_name b
                        WHERE $exists_conditions
                    )";

        // Execute query
        $result = $pdo->select($table_details, $where);
        $res_array = $result->data;
        // print_r($result);

        $dropout_cnt = 0;
        foreach ($res_array as $value) {
            $dropout_cnt = $value['dropout_cnt'];
        }

        $json_array = [
            "dropout_cnt" => $dropout_cnt,
            "sql" => $result->sql // keep for debugging
        ];

        echo json_encode($json_array);
        break;

    case 'fetch_chart_data':


        $json_array = "";
        $columns = [
            "reject_reason",
            "count"
        ];
        $table_details = [
            "view_reject_reason_count",
            $columns
        ];
        // $where        = "hostel_1 = '".$_SESSION['hostel_id']."'";
        $where = "is_delete = 0";


        $result = $pdo->select($table_details);
        // print_r($result);
        $res_array = $result->data;
        //    print_r($result);
        foreach ($res_array as $value) {

            $reject_reason[] = $value['reject_reason'];
            $count[] = $value['count'];


        }

        $json_array = [
            "reject_reason" => $reject_reason,
            "count" => $count,
        ];

        echo json_encode($json_array);

        break;

    case 'district_name':

        // $district_name = $_POST['district_name'];

        $district_name_options = taluk_name('', $district_name);

        $taluk_name_options = select_option($district_name_options, "Select Taluk");

        echo $taluk_name_options;

        break;

    case 'get_hostel_by_taluk_name':

        $taluk_name = $_POST['taluk_name'];

        $hostel_name_options = hostel_name('', $taluk_name);

        $hostel_name_options = select_option($hostel_name_options, "Select Hostel");

        echo $hostel_name_options;

        break;


    case 'datatable':
        // DataTable Variables
        $search = $_POST['search']['value'];
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;

        $data = [];

        $feedback_name = $_POST["feedback_name"];
        $rating = $_POST["rating"];
        $description = $_POST["description"];
        $student_id = $_POST["student_id"];
        $date = $_POST['date'];
        $is_active = $_POST["is_active"];
        $unique_id = $_POST["unique_id"];

        if ($length == '-1') {
            $limit = "";
        }

        // Query Variables
        $json_array = "";
        $columns = [
            "@a:=@a+1 s_no",
            "current_date",
            "(select feedback_type from feedback_type where unique_id = $table.feedback_name)as feedback_name",
            "rating",
            "description",
            "is_active",
            "unique_id"
        ];
        $table_details = [
            $table . " , (SELECT @a:= " . $start . ") AS a ",
            $columns
        ];
        $where = "is_delete = 0 ";
        $order_by = "";

        if ($_POST['search']['value']) {
            $where .= " AND user_type LIKE '" . mysql_like($_POST['search']['value']) . "' ";
        }

        // Datatable Searching
        $search = datatable_searching($search, $columns);

        if ($search) {
            if ($where) {
                $where .= " AND ";
            }

            $where .= $search;
        }

        $sql_function = "SQL_CALC_FOUND_ROWS";

        $result = $pdo->select($table_details, $where, $limit, $start, $order_by, $sql_function);
        $total_records = total_records();

        if ($result->status) {

            $res_array = $result->data;

            foreach ($res_array as $key => $value) {
                $value['is_active'] = is_active_show($value['is_active']);


                $btn_update = btn_update($folder_name, $value['unique_id']);
                $btn_delete = btn_delete($folder_name, $value['unique_id']);

                $value['unique_id'] = $btn_update . $btn_delete;
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

    case 'get_leave':
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

        // Query Variables
        $json_array = "";
        $columns = [
            "@a:=@a+1 s_no",
            "current_date",
            "(select feedback_type from feedback_type where unique_id = $table.feedback_name)as feedback_name",
            "rating",
            "description",
            "is_active",
            "unique_id"
        ];
        $table_details = [
            $table . " , (SELECT @a:= " . $start . ") AS a ",
            $columns
        ];
        $where = "is_delete = 0 ";
        $order_by = "";

        if ($_POST['search']['value']) {
            $where .= " AND user_type LIKE '" . mysql_like($_POST['search']['value']) . "' ";
        }

        // Datatable Searching
        $search = datatable_searching($search, $columns);

        if ($search) {
            if ($where) {
                $where .= " AND ";
            }

            $where .= $search;
        }

        $sql_function = "SQL_CALC_FOUND_ROWS";

        $result = $pdo->select($table_details, $where, $limit, $start, $order_by, $sql_function);
        $total_records = total_records();

        if ($result->status) {

            $res_array = $result->data;

            foreach ($res_array as $key => $value) {
                $value['is_active'] = is_active_show($value['is_active']);


                $btn_update = btn_update($folder_name, $value['unique_id']);
                $btn_delete = btn_delete($folder_name, $value['unique_id']);

                $value['unique_id'] = $btn_update . $btn_delete;
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

    case 'get_vacancy_count':

        $json_array = "";
        $columns = [
            "(select sum(sanctioned_strength) as str_cnt from hostel_name where district_name = '" . $_SESSION['district_id'] . "') as tot_cap",
            "'' as old_std",
            "count(id) as approved_cnt",

        ];
        $table_details = [
            "std_app_s",
            $columns
        ];
        $where = "hostel_district_1 = '" . $_SESSION["district_id"] . "' and status = '1'";
        $result = $pdo->select($table_details, $where);
        $res_array = $result->data;
        //    print_r($result);die();
        foreach ($res_array as $value) {

            $tot_cap = $value['tot_cap'];
            if ($value['old_std'] == '') {
                $old_std = '0';
            }
            $approved_cnt = $value['approved_cnt'];

            $hos_vac = $tot_cap - $approved_cnt;


        }

        $json_array = [
            "tot_cap" => $tot_cap,
            "old_std" => $old_std,
            "approved_cnt" => $approved_cnt,
            "hos_vac" => $hos_vac,


        ];

        echo json_encode($json_array);

        break;


    case 'total_hostels':

        $table = "hostel_name";

        // $district_name = $_POST['district_name'];
        $taluk_name = $_POST['taluk_name'];
        $hostel_name = $_POST['hostel_name'];
        $gender_type = $_POST['gender_type'];
        $hostel_type = $_POST['hostel_type'];

        $columns = [
            "count(hostel_name)as hostel_count"
        ];
        $table_details = [
            $table,
            $columns
        ];

        $where = "is_delete = 0 AND hostel_id NOT LIKE '%ADWN%' ";

        if ($district_name != '') {
            $where .= " AND district_name = '" . $district_name . "'";
        }
        if ($taluk_name != '') {
            $where .= " AND taluk_name = '" . $taluk_name . "'";
        }
        if ($hostel_name != '') {
            $where .= " AND unique_id = '" . $hostel_name . "'";
        }

        // ✅ Gender filter supports multiple
        if (!empty($gender_type)) {
            if (is_array($gender_type)) {
                $gender_type = array_filter($gender_type, fn($val) => $val !== '');
                if (!empty($gender_type)) {
                    $gender_list = array_map(fn($val) => "'" . $val . "'", $gender_type);
                    $gender_str = implode(",", $gender_list);
                    $where .= " AND gender_type IN (" . $gender_str . ")";
                }
            } else {
                if ($gender_type !== '') {
                    $where .= " AND gender_type = '" . $gender_type . "'";
                }
            }
        }

        // ✅ Hostel type filter supports multiple
        if (!empty($hostel_type) && is_array($hostel_type)) {
            $hostel_type = array_filter($hostel_type, fn($val) => $val !== '');
            if (!empty($hostel_type)) {
                $hostel_type_list = array_map(fn($val) => "'" . $val . "'", $hostel_type);
                $hostel_type_str = implode(",", $hostel_type_list);
                $where .= " AND hostel_type IN (" . $hostel_type_str . ")";
            }
        }

        $result = $pdo->select($table_details, $where);
        // print_r($result);
        $res_array = $result->data;


        foreach ($res_array as $value) {

            $hostel_count = $value['hostel_count'];
        }
        $json_array = [

            // "data"  => $data,
            "hostel_count" => $hostel_count
        ];

        echo json_encode($json_array);

        break;

    case 'get_total_staffs':

        // $district_name = $_POST['district_name'];
        $taluk_name = $_POST['taluk_name'];
        $hostel_name = $_POST['hostel_name'];
        $gender_type = $_POST['gender_type'];
        $hostel_type = $_POST['hostel_type'];

        $table = "establishment_registration AS er";

        $where = "er.is_delete = 0";

        if ($district_name != '') {
            $where .= " AND er.district_office = '" . $district_name . "'";
        }
        if ($taluk_name != '') {
            $where .= " AND er.taluk_office = '" . $taluk_name . "'";
        }
        if ($hostel_name != '') {
            $where .= " AND er.hostel_name = '" . $hostel_name . "'";
        }

        // Build EXISTS subquery for hostel_name filtering
        $exists_conditions = "b.unique_id = er.hostel_name AND b.is_delete = 0";

        // ✅ Gender filter supports multiple
        if (!empty($gender_type)) {
            if (is_array($gender_type)) {
                $gender_type = array_filter($gender_type, fn($val) => $val !== '');
                if (!empty($gender_type)) {
                    $gender_list = array_map(fn($val) => "'" . $val . "'", $gender_type);
                    $gender_str = implode(",", $gender_list);
                    $exists_conditions .= " AND b.gender_type IN (" . $gender_str . ")";
                }
            } else {
                if ($gender_type !== '') {
                    $exists_conditions .= " AND b.gender_type = '" . $gender_type . "'";
                }
            }
        }

        // ✅ Hostel type filter supports multiple
        if (!empty($hostel_type) && is_array($hostel_type)) {
            $hostel_type = array_filter($hostel_type, fn($val) => $val !== '');
            if (!empty($hostel_type)) {
                $hostel_type_list = array_map(fn($val) => "'" . $val . "'", $hostel_type);
                $hostel_type_str = implode(",", $hostel_type_list);
                $exists_conditions .= " AND b.hostel_type IN (" . $hostel_type_str . ")";
            }
        }

        // Add EXISTS subquery to WHERE
        $where .= " AND EXISTS (
        SELECT 1
        FROM hostel_name b
        WHERE $exists_conditions
    )";

        // ✅ Correct: wrap $where into subqueries as WHERE (...) not as AND ...
        $columns = [
            "(SELECT count(id) FROM establishment_registration WHERE designation = '679344dd0298475852' AND $where) AS warden_cnt",
            "(SELECT count(id) FROM establishment_registration WHERE designation = '679344eba32e272880' AND $where) AS warden_inc_cnt",
            "(SELECT count(id) FROM establishment_registration WHERE designation = '679344f6b8df890572' AND $where) AS cook_cnt",
            "(SELECT count(id) FROM establishment_registration WHERE designation = '6793454ad335487663' AND $where) AS cook_dep_cnt",
            "(SELECT count(id) FROM establishment_registration WHERE designation = '67934553276a759813' AND $where) AS watchman_cnt",
            "(SELECT count(id) FROM establishment_registration WHERE designation = '67a0602466d5438020' AND $where) AS watchman_dep_cnt",
            "(SELECT count(id) FROM establishment_registration WHERE designation = '6795b2be36a0239724' AND $where) AS sweeper_cnt",
            "(SELECT count(id) FROM establishment_registration WHERE designation = '67a078c608d5850476' AND $where) AS sweeper_dep_cnt",
        ];

        $table_details = [
            $table,
            $columns
        ];

        $result = $pdo->select($table_details, $where);

        // print_r($result);
        $res_array = $result->data;

        foreach ($res_array as $value) {
            $warden_cnt = $value['warden_cnt'];
            $warden_inc_cnt = $value['warden_inc_cnt'];
            $cook_cnt = $value['cook_cnt'];
            $cook_dep_cnt = $value['cook_dep_cnt'];
            $watchman_cnt = $value['watchman_cnt'];
            $watchman_dep_cnt = $value['watchman_dep_cnt'];
            $sweeper_cnt = $value['sweeper_cnt'];
            $sweeper_dep_cnt = $value['sweeper_dep_cnt'];
        }

        $json_array = [
            "warden_cnt" => $warden_cnt,
            "warden_inc_cnt" => $warden_inc_cnt,
            "cook_cnt" => $cook_cnt,
            "cook_dep_cnt" => $cook_dep_cnt,
            "watchman_cnt" => $watchman_cnt,
            "watchman_dep_cnt" => $watchman_dep_cnt,
            "sweeper_cnt" => $sweeper_cnt,
            "sweeper_dep_cnt" => $sweeper_dep_cnt,
        ];

        echo json_encode($json_array);

        break;



    case 'total_students':

        $table = "std_reg_s a";

        // $district_name = $_POST['district_name'];
        $taluk_name = $_POST['taluk_name'];
        $hostel_name = $_POST['hostel_name'];
        $gender_type = $_POST['gender_type'];
        $hostel_type = $_POST['hostel_type'];

        $json_array = [];

        $columns = [
            "count(id) as student_name",
        ];
        $table_details = [
            $table,
            $columns
        ];

        $where = "a.is_delete = 0 AND a.dropout_status = 1 ";

        if ($district_name != '') {
            $where .= " AND a.hostel_district_1 = '" . $district_name . "'";
        }
        if ($taluk_name != '') {
            $where .= " AND a.hostel_taluk_1 = '" . $taluk_name . "'";
        }
        if ($hostel_name != '') {
            $where .= " AND a.hostel_1 = '" . $hostel_name . "'";
        }

        // Build EXISTS subquery for hostel_name filtering
        $exists_conditions = "b.unique_id = a.hostel_1 AND b.is_delete = 0";

        // ✅ Gender filter supports multiple
        if (!empty($gender_type)) {
            if (is_array($gender_type)) {
                $gender_type = array_filter($gender_type, fn($val) => $val !== '');
                if (!empty($gender_type)) {
                    $gender_list = array_map(fn($val) => "'" . $val . "'", $gender_type);
                    $gender_str = implode(",", $gender_list);
                    $exists_conditions .= " AND b.gender_type IN (" . $gender_str . ")";
                }
            } else {
                if ($gender_type !== '') {
                    $exists_conditions .= " AND b.gender_type = '" . $gender_type . "'";
                }
            }
        }

        // ✅ Hostel type filter supports multiple
        if (!empty($hostel_type) && is_array($hostel_type)) {
            $hostel_type = array_filter($hostel_type, fn($val) => $val !== '');
            if (!empty($hostel_type)) {
                $hostel_type_list = array_map(fn($val) => "'" . $val . "'", $hostel_type);
                $hostel_type_str = implode(",", $hostel_type_list);
                $exists_conditions .= " AND b.hostel_type IN (" . $hostel_type_str . ")";
            }
        }

        // Add EXISTS subquery to WHERE
        $where .= " AND EXISTS (
                        SELECT 1
                        FROM hostel_name b
                        WHERE $exists_conditions
                    )";

        $result = $pdo->select($table_details, $where);
        // print_r($result);
        $res_array = $result->data;


        foreach ($res_array as $value) {
            $student_name = $value['student_name'];

        }
        $json_array = [

            // "data"  => $data,
            "student_name" => $student_name,

        ];

        echo json_encode($json_array);

        break;

    case 'total_staff_strength':

        // $district_name = $_POST['district_name'];
        $taluk_name = $_POST['taluk_name'];
        $hostel_name = $_POST['hostel_name'];
        $gender_type = $_POST['gender_type'];
        $hostel_type = $_POST['hostel_type'];

        $table = "establishment_registration a";

        $columns = [

            "count(*)as staff_name"

        ];
        $table_details = [
            $table,
            $columns
        ];

        $where = "is_delete = 0";

        if ($district_name != '') {
            $where .= " AND a.district_office = '" . $district_name . "'";
        }
        if ($taluk_name != '') {
            $where .= " AND a.taluk_office = '" . $taluk_name . "'";
        }
        if ($hostel_name != '') {
            $where .= " AND a.hostel_name = '" . $hostel_name . "'";
        }

        // Build EXISTS subquery for hostel_name filtering
        $exists_conditions = "b.unique_id = a.hostel_name AND b.is_delete = 0";

        // ✅ Gender filter supports multiple
        if (!empty($gender_type)) {
            if (is_array($gender_type)) {
                $gender_type = array_filter($gender_type, fn($val) => $val !== '');
                if (!empty($gender_type)) {
                    $gender_list = array_map(fn($val) => "'" . $val . "'", $gender_type);
                    $gender_str = implode(",", $gender_list);
                    $exists_conditions .= " AND b.gender_type IN (" . $gender_str . ")";
                }
            } else {
                if ($gender_type !== '') {
                    $exists_conditions .= " AND b.gender_type = '" . $gender_type . "'";
                }
            }
        }

        // ✅ Hostel type filter supports multiple
        if (!empty($hostel_type) && is_array($hostel_type)) {
            $hostel_type = array_filter($hostel_type, fn($val) => $val !== '');
            if (!empty($hostel_type)) {
                $hostel_type_list = array_map(fn($val) => "'" . $val . "'", $hostel_type);
                $hostel_type_str = implode(",", $hostel_type_list);
                $exists_conditions .= " AND b.hostel_type IN (" . $hostel_type_str . ")";
            }
        }

        // Add EXISTS subquery to WHERE
        $where .= " AND EXISTS (
        SELECT 1
        FROM hostel_name b
        WHERE $exists_conditions
        )";

        $result = $pdo->select($table_details, $where);
        $res_array = $result->data;


        //    print_r($result);die();

        foreach ($res_array as $value) {

            $staff_name = $value['staff_name'];

            // $data[]             = array_values($value);
        }
        $json_array = [

            // "data"  => $data,
            "staff_cnt" => $staff_name,

        ];

        echo json_encode($json_array);

        break;

    case 'applied_leave_details':

        $table_leave = "leave_application";

        $district_name = $_SESSION["district_id"];

        $json_array = "";
        // $today  =  date('Y-m-d');

        $columns_leave = [
            "from_date",
            "no_of_days",
            "approval_status",
            "count(student_name)as student_name"
            // "approval_status"
        ];

        $table_details_leave = [
            $table_leave,
            $columns_leave,
        ];

        $where = "is_delete = 0 AND district_name = '" . $district_name . "'";


        $sql_function = "SQL_CALC_FOUND_ROWS";




        $result = $pdo->select($table_details_leave, $where);
        // print_r($result);
        $total_records = total_records();

        if ($result->status) {

            $res_array = $result->data;

            foreach ($res_array as $key => $value) {

                // $from_date = $value['from_date'];
                $no_of_student_name = $value['student_name'];

                $status_color = '';

                if ($value['approval_status'] == 1) {
                    $value['approval_status'] = 'Pending';
                    $status_color = 'blue';
                }
                if ($value['approval_status'] == 2) {
                    $value['approval_status'] = 'Approved';
                    $status_color = 'green';
                }
                if ($value['approval_status'] == 3) {
                    $value['approval_status'] = 'Rejected';
                    $status_color = 'red';
                }

                $status_text = $value['approval_status'];
                $res_array = $result->data;
            }
            $json_array = [

                "applied_leave_details" => $res_array,

                "no_of_student_name" => $no_of_student_name,
            ];

            echo json_encode($json_array);
        }


        break;





    case 'staff_applied_leave_details':

        $table_leave = "staff_leave_application";

        $json_array = "";
        $columns_leave = [
            "from_date",
            "no_of_days",
            "approval_status",
            "count(staff_name)as staff_name"
        ];

        $table_details_leave = [
            $table_leave,
            $columns_leave,
        ];

        $where = "is_delete = 0 AND district_name = '" . $district_name . "'";

        $sql_function = "SQL_CALC_FOUND_ROWS";

        $result = $pdo->select($table_details_leave, $where);
        // print_r($result);
        $total_records = total_records();

        if ($result->status) {

            $res_array = $result->data;

            foreach ($res_array as $key => $value) {

                // $from_date = $value['from_date'];
                $staff_name = $value['staff_name'];

                $res_array = $result->data;
                // $data[]             = array_values($value);
            }
            $json_array = [

                "applied_leave_details" => $res_array,

                "staff_name" => $staff_name,

                // "approval_status"    =>     $value['approval_status']
            ];

            echo json_encode($json_array);
        }


        break;




    case 'hostel_vaccancy':

        // $district_name = $_POST['district_name'];
        $taluk_name = $_POST['taluk_name'];
        $hostel_name = $_POST['hostel_name'];
        $gender_type = $_POST['gender_type'] ?? [];
        $hostel_type = $_POST['hostel_type'] ?? [];

        $table_hs = "hostel_name";
        $json_array = "";
        $columns = [

            "sum(sanctioned_strength) as sanctioned_count",

        ];
        $table_details = [
            "hostel_name",
            $columns
        ];

        $where = "is_delete = 0";

        if ($district_name != '') {
            $where .= " AND district_name = '" . $district_name . "'";
        }
        if ($taluk_name != '') {
            $where .= " AND taluk_name = '" . $taluk_name . "'";
        }
        if ($hostel_name != '') {
            $where .= " AND unique_id = '" . $hostel_name . "'";
        }

        // ✅ Gender filter supports multiple
        if (!empty($gender_type)) {
            if (is_array($gender_type)) {
                $gender_type = array_filter($gender_type, fn($val) => $val !== '');
                if (!empty($gender_type)) {
                    $gender_list = array_map(fn($val) => "'" . $val . "'", $gender_type);
                    $gender_str = implode(",", $gender_list);
                    $where .= " AND gender_type IN (" . $gender_str . ")";
                }
            } else {
                if ($gender_type !== '') {
                    $where .= " AND gender_type = '" . $gender_type . "'";
                }
            }
        }

        // ✅ Hostel type filter supports multiple
        if (!empty($hostel_type) && is_array($hostel_type)) {
            $hostel_type = array_filter($hostel_type, fn($val) => $val !== '');
            if (!empty($hostel_type)) {
                $hostel_type_list = array_map(fn($val) => "'" . $val . "'", $hostel_type);
                $hostel_type_str = implode(",", $hostel_type_list);
                $where .= " AND hostel_type IN (" . $hostel_type_str . ")";
            }
        }

        // $where = "is_delete = 0";
        $result = $pdo->select($table_details, $where);
        // print_r($result);
        $res_array = $result->data;
        //    
        foreach ($res_array as $value) {

            $sanctioned_cnt = $value['sanctioned_count'];

            $registered_cnt = total_reg_count('', $district_name, $taluk_name, $hostel_name, $gender_type, $hostel_type);

            $hostel_vaccancy = $sanctioned_cnt - $registered_cnt;
        }

        $json_array = [
            "approved_cnt" => $sanctioned_cnt,
            "registered_cnt" => $registered_cnt,
            "hostel_vaccancy" => $hostel_vaccancy
        ];

        echo json_encode($json_array);

        break;

    case 'district_wise_count':


        $table_dis = "district_name";

        $json_array = [];
        $columns = [

            "district_name",
            "(select count(unique_id) from std_reg_s where std_reg_s.hostel_district_1 =$table_dis.unique_id)as reg_district "
        ];

        $table_details = [
            $table_dis,
            $columns
        ];

        $where = "is_delete = 0";

        $result = $pdo->select($table_details, $where);

        // print_r($result);
        $res_array = $result->data;


        $district_names = [];
        $reg_district = [];

        foreach ($res_array as $value) {

            $district_names[] = ($value['district_name']);


            $reg_district[] = $value['reg_district'];

            // print_r($reg_district);

        }

        $json_array = [

            "district_names" => $district_names,
            "reg_district" => $reg_district

        ];

        echo json_encode($json_array);

        break;

    case 'get_application_count':

        $json_array = "";
        $columns = [
            "(select count(id) from std_app_s where is_delete = 0 and hostel_district_1 = '" . $_SESSION['district_id'] . "') as applied_cnt",
            "(select count(id) from std_app_s where is_delete = 0 and batch_no != '' and hostel_district_1 = '" . $_SESSION['district_id'] . "') as accp_cnt",
            "(select count(id) from std_app_s where is_delete = 0 and status = '1' and hostel_district_1 = '" . $_SESSION['district_id'] . "') as approved_cnt",
            "(select count(id) from std_app_s where is_delete = 0 and status = '2' and hostel_district_1 = '" . $_SESSION['district_id'] . "') as rejected_cnt",

            // "(select COUNT(id) where stage_1_status = 3 and is_delete = 0) as cancel_comp",
        ];
        $table_details = [
            "std_app_s",
            $columns
        ];
        $where = "hostel_district_1 = '" . $_SESSION["district_id"] . "'";
        $result = $pdo->select($table_details, $where);
        $res_array = $result->data;
        //    print_r($result);
        foreach ($res_array as $value) {

            $applied_cnt = $value['applied_cnt'];
            $accp_cnt = $value['accp_cnt'];
            $approved_cnt = $value['approved_cnt'];
            $rejected_cnt = $value['rejected_cnt'];

        }

        $json_array = [
            "applied_cnt" => $applied_cnt,
            "accp_cnt" => $accp_cnt,
            "approved_cnt" => $approved_cnt,
            "rejected_cnt" => $rejected_cnt,


        ];

        echo json_encode($json_array);

        break;


    case 'student_attendance':

        // $district_name = $_SESSION["district_id"]; 

        $from_date = $_POST['from_date'];
        // $to_date = $_POST['to_date'];

        $table = "std_reg_s";

        $json_array = [];

        $columns = [

            // "count(id)as student_name",
            "count(id) as student_name",


        ];
        $table_details = [
            $table,
            $columns
        ];

        $where = "is_delete = 0 AND dropout_status = 1 AND hostel_district_1 = '" . $district_name . "'";

        if ($from_date) {
            $where .= " AND entry_date = '" . $from_date . "' ";
        }
        // if ($to_date) {
        //     $where .= " AND entry_date <= '" . $to_date . "' ";
        // }

        $result = $pdo->select($table_details, $where);
        // print_r($result);
        $res_array = $result->data;

        //    print_r($result);die();

        foreach ($res_array as $value) {
            $student_name = $value['student_name'];

            // $data[]             = array_values($value);
        }
        $json_array = [

            // "data"  => $data,
            "student_name" => $student_name,

        ];

        echo json_encode($json_array);

        break;

    case 'staff_attendance':

        $from_date = $_POST['from_date'];
        // $to_date = $_POST['to_date'];

        $table = "establishment_registration";

        $json_array = [];

        $columns = [
            "count(id) as staff_name",
        ];
        $table_details = [
            $table,
            $columns
        ];

        $where = "is_delete = 0 AND district_office = '" . $district_name . "'";

        if ($from_date) {
            $where .= " AND entry_date = '" . $from_date . "' ";
        }
        // if ($to_date) {
        //     $where .= " AND entry_date <= '" . $to_date . "' ";
        // }

        $result = $pdo->select($table_details, $where);
        // print_r($result);
        $res_array = $result->data;

        //    print_r($result);die();

        foreach ($res_array as $value) {
            $staff_name = $value['staff_name'];

        }
        $json_array = [

            // "data"  => $data,
            "staff_name" => $staff_name,

        ];

        echo json_encode($json_array);

        break;

    case 'get_face_and_finger':

        $where = "";
        $batch_where = "";

        $json_array = "";
        $columns = [
            "(SELECT count(unique_id) FROM std_reg_s WHERE is_delete = 0 AND dropout_status = 1 AND face_id_status = 1 AND hostel_district_1 = '" . $district_name . "') as face_reg",
            "(SELECT count(unique_id) FROM std_reg_s WHERE is_delete = 0 AND dropout_status = 1 AND face_id_status != 1 AND hostel_district_1 = '" . $district_name . "') as face_not_reg",
            "(SELECT count(unique_id) FROM std_reg_s WHERE is_delete = 0 AND dropout_status = 1 AND fingerprint_status = 1 AND hostel_district_1 = '" . $district_name . "') as finger_reg",
            "(SELECT count(unique_id) FROM std_reg_s WHERE is_delete = 0 AND dropout_status = 1 AND fingerprint_status != 1 AND hostel_district_1 = '" . $district_name . "') as finger_not_reg"
        ];
        $table_details = [
            "std_reg_s",
            $columns
        ];

        $result = $pdo->select($table_details);
        // print_r($result);

        $res_array = $result->data;

        $face_reg = 0;
        $face_not_reg = 0;
        $finger_reg = 0;
        $finger_not_reg = 0;

        foreach ($res_array as $value) {

            $face_reg = $value['face_reg'];
            $face_not_reg = $value['face_not_reg'];
            $finger_reg = $value['finger_reg'];
            $finger_not_reg = $value['finger_not_reg'];

        }

        $json_array = [
            "face_reg" => $face_reg,
            "face_not_reg" => $face_not_reg,
            "finger_reg" => $finger_reg,
            "finger_not_reg" => $finger_not_reg,

        ];

        echo json_encode($json_array);

        break;

    case 'get_biometric_details':

        $where = "";
        $batch_where = "";

        $json_array = "";
        $columns = [
            "(SELECT count(unique_id) FROM std_reg_s WHERE is_delete = 0 AND dropout_status = 1 AND bio_reg_status = 1 AND hostel_district_1 = '" . $district_name . "') as std_pushed",
            "(SELECT count(unique_id) FROM std_reg_s WHERE is_delete = 0 AND dropout_status = 1 AND hostel_district_1 = '" . $district_name . "') as students_approved"
        ];
        $table_details = [
            "std_reg_s",
            $columns
        ];

        $result = $pdo->select($table_details);
        // print_r($result);

        $res_array = $result->data;

        $std_pushed = 0;
        $students_approved = 0;

        foreach ($res_array as $value) {

            $std_pushed = $value['std_pushed'];
            $students_approved = $value['students_approved'];

        }

        $json_array = [
            "std_pushed" => $std_pushed,
            "students_approved" => $students_approved,

        ];

        echo json_encode($json_array);

        break;


    case 'total_warden_strength':

        // $district_name = $_POST['district_name'];
        $taluk_name = $_POST['taluk_name'];
        $hostel_name = $_POST['hostel_name'];
        $gender_type = $_POST['gender_type'];
        $hostel_type = $_POST['hostel_type'];

        $table = "establishment_registration a";

        $columns = [

            "count(*)as warden_count"

        ];
        $table_details = [
            $table,
            $columns
        ];

        $where = "is_delete = 0 AND designation = '679344dd0298475852'";

        if ($district_name != '') {
            $where .= " AND a.district_office = '" . $district_name . "'";
        }
        if ($taluk_name != '') {
            $where .= " AND a.taluk_office = '" . $taluk_name . "'";
        }
        if ($hostel_name != '') {
            $where .= " AND a.hostel_name = '" . $hostel_name . "'";
        }

        // Build EXISTS subquery for hostel_name filtering
        $exists_conditions = "b.unique_id = a.hostel_name AND b.is_delete = 0";

        // ✅ Gender filter supports multiple
        if (!empty($gender_type)) {
            if (is_array($gender_type)) {
                $gender_type = array_filter($gender_type, fn($val) => $val !== '');
                if (!empty($gender_type)) {
                    $gender_list = array_map(fn($val) => "'" . $val . "'", $gender_type);
                    $gender_str = implode(",", $gender_list);
                    $exists_conditions .= " AND b.gender_type IN (" . $gender_str . ")";
                }
            } else {
                if ($gender_type !== '') {
                    $exists_conditions .= " AND b.gender_type = '" . $gender_type . "'";
                }
            }
        }

        // ✅ Hostel type filter supports multiple
        if (!empty($hostel_type) && is_array($hostel_type)) {
            $hostel_type = array_filter($hostel_type, fn($val) => $val !== '');
            if (!empty($hostel_type)) {
                $hostel_type_list = array_map(fn($val) => "'" . $val . "'", $hostel_type);
                $hostel_type_str = implode(",", $hostel_type_list);
                $exists_conditions .= " AND b.hostel_type IN (" . $hostel_type_str . ")";
            }
        }

        // Add EXISTS subquery to WHERE
        $where .= " AND EXISTS (
        SELECT 1
        FROM hostel_name b
        WHERE $exists_conditions
        )";

        $result = $pdo->select($table_details, $where);
        $res_array = $result->data;


        //    print_r($result);die();

        foreach ($res_array as $value) {

            $warden_count = $value['warden_count'];

            // $data[]             = array_values($value);
        }
        $json_array = [

            // "data"  => $data,
            "warden_count" => $warden_count,

        ];

        echo json_encode($json_array);

        break;

    case 'total_warden_incharge_strength':

        // $district_name = $_POST['district_name'];
        $taluk_name = $_POST['taluk_name'];
        $hostel_name = $_POST['hostel_name'];
        $gender_type = $_POST['gender_type'];
        $hostel_type = $_POST['hostel_type'];

        $table = "establishment_registration a";

        $columns = [

            "count(*)as warden_incharge_count"

        ];
        $table_details = [
            $table,
            $columns
        ];

        $where = "is_delete = 0 AND designation = '679344eba32e272880'";

        if ($district_name != '') {
            $where .= " AND a.district_office = '" . $district_name . "'";
        }
        if ($taluk_name != '') {
            $where .= " AND a.taluk_office = '" . $taluk_name . "'";
        }
        if ($hostel_name != '') {
            $where .= " AND a.hostel_name = '" . $hostel_name . "'";
        }

        // Build EXISTS subquery for hostel_name filtering
        $exists_conditions = "b.unique_id = a.hostel_name AND b.is_delete = 0";

        // ✅ Gender filter supports multiple
        if (!empty($gender_type)) {
            if (is_array($gender_type)) {
                $gender_type = array_filter($gender_type, fn($val) => $val !== '');
                if (!empty($gender_type)) {
                    $gender_list = array_map(fn($val) => "'" . $val . "'", $gender_type);
                    $gender_str = implode(",", $gender_list);
                    $exists_conditions .= " AND b.gender_type IN (" . $gender_str . ")";
                }
            } else {
                if ($gender_type !== '') {
                    $exists_conditions .= " AND b.gender_type = '" . $gender_type . "'";
                }
            }
        }

        // ✅ Hostel type filter supports multiple
        if (!empty($hostel_type) && is_array($hostel_type)) {
            $hostel_type = array_filter($hostel_type, fn($val) => $val !== '');
            if (!empty($hostel_type)) {
                $hostel_type_list = array_map(fn($val) => "'" . $val . "'", $hostel_type);
                $hostel_type_str = implode(",", $hostel_type_list);
                $exists_conditions .= " AND b.hostel_type IN (" . $hostel_type_str . ")";
            }
        }

        // Add EXISTS subquery to WHERE
        $where .= " AND EXISTS (
        SELECT 1
        FROM hostel_name b
        WHERE $exists_conditions
        )";

        $result = $pdo->select($table_details, $where);
        $res_array = $result->data;


        //    print_r($result);die();

        foreach ($res_array as $value) {

            $warden_incharge_count = $value['warden_incharge_count'];

            // $data[]             = array_values($value);
        }
        $json_array = [

            // "data"  => $data,
            "warden_incharge_count" => $warden_incharge_count,

        ];

        echo json_encode($json_array);

        break;

    case 'total_cook_strength':

        // $district_name = $_POST['district_name'];
        $taluk_name = $_POST['taluk_name'];
        $hostel_name = $_POST['hostel_name'];
        $gender_type = $_POST['gender_type'];
        $hostel_type = $_POST['hostel_type'];

        $table = "establishment_registration a";

        $columns = [

            "count(*)as cook_count"

        ];
        $table_details = [
            $table,
            $columns
        ];

        $where = "is_delete = 0 AND designation = '679344f6b8df890572'";

        if ($district_name != '') {
            $where .= " AND a.district_office = '" . $district_name . "'";
        }
        if ($taluk_name != '') {
            $where .= " AND a.taluk_office = '" . $taluk_name . "'";
        }
        if ($hostel_name != '') {
            $where .= " AND a.hostel_name = '" . $hostel_name . "'";
        }

        // Build EXISTS subquery for hostel_name filtering
        $exists_conditions = "b.unique_id = a.hostel_name AND b.is_delete = 0";

        // ✅ Gender filter supports multiple
        if (!empty($gender_type)) {
            if (is_array($gender_type)) {
                $gender_type = array_filter($gender_type, fn($val) => $val !== '');
                if (!empty($gender_type)) {
                    $gender_list = array_map(fn($val) => "'" . $val . "'", $gender_type);
                    $gender_str = implode(",", $gender_list);
                    $exists_conditions .= " AND b.gender_type IN (" . $gender_str . ")";
                }
            } else {
                if ($gender_type !== '') {
                    $exists_conditions .= " AND b.gender_type = '" . $gender_type . "'";
                }
            }
        }

        // ✅ Hostel type filter supports multiple
        if (!empty($hostel_type) && is_array($hostel_type)) {
            $hostel_type = array_filter($hostel_type, fn($val) => $val !== '');
            if (!empty($hostel_type)) {
                $hostel_type_list = array_map(fn($val) => "'" . $val . "'", $hostel_type);
                $hostel_type_str = implode(",", $hostel_type_list);
                $exists_conditions .= " AND b.hostel_type IN (" . $hostel_type_str . ")";
            }
        }

        // Add EXISTS subquery to WHERE
        $where .= " AND EXISTS (
        SELECT 1
        FROM hostel_name b
        WHERE $exists_conditions
        )";

        $result = $pdo->select($table_details, $where);
        $res_array = $result->data;


        //    print_r($result);die();

        foreach ($res_array as $value) {

            $cook_count = $value['cook_count'];

            // $data[]             = array_values($value);
        }
        $json_array = [

            // "data"  => $data,
            "cook_count" => $cook_count,

        ];

        echo json_encode($json_array);

        break;

    case 'total_cook_deputation_strength':

        // $district_name = $_POST['district_name'];
        $taluk_name = $_POST['taluk_name'];
        $hostel_name = $_POST['hostel_name'];
        $gender_type = $_POST['gender_type'];
        $hostel_type = $_POST['hostel_type'];

        $table = "establishment_registration a";

        $columns = [

            "count(*)as cook_deputation_count"

        ];
        $table_details = [
            $table,
            $columns
        ];

        $where = "is_delete = 0 AND designation = '6793454ad335487663'";

        if ($district_name != '') {
            $where .= " AND a.district_office = '" . $district_name . "'";
        }
        if ($taluk_name != '') {
            $where .= " AND a.taluk_office = '" . $taluk_name . "'";
        }
        if ($hostel_name != '') {
            $where .= " AND a.hostel_name = '" . $hostel_name . "'";
        }

        // Build EXISTS subquery for hostel_name filtering
        $exists_conditions = "b.unique_id = a.hostel_name AND b.is_delete = 0";

        // ✅ Gender filter supports multiple
        if (!empty($gender_type)) {
            if (is_array($gender_type)) {
                $gender_type = array_filter($gender_type, fn($val) => $val !== '');
                if (!empty($gender_type)) {
                    $gender_list = array_map(fn($val) => "'" . $val . "'", $gender_type);
                    $gender_str = implode(",", $gender_list);
                    $exists_conditions .= " AND b.gender_type IN (" . $gender_str . ")";
                }
            } else {
                if ($gender_type !== '') {
                    $exists_conditions .= " AND b.gender_type = '" . $gender_type . "'";
                }
            }
        }

        // ✅ Hostel type filter supports multiple
        if (!empty($hostel_type) && is_array($hostel_type)) {
            $hostel_type = array_filter($hostel_type, fn($val) => $val !== '');
            if (!empty($hostel_type)) {
                $hostel_type_list = array_map(fn($val) => "'" . $val . "'", $hostel_type);
                $hostel_type_str = implode(",", $hostel_type_list);
                $exists_conditions .= " AND b.hostel_type IN (" . $hostel_type_str . ")";
            }
        }

        // Add EXISTS subquery to WHERE
        $where .= " AND EXISTS (
        SELECT 1
        FROM hostel_name b
        WHERE $exists_conditions
        )";

        $result = $pdo->select($table_details, $where);
        $res_array = $result->data;


        //    print_r($result);die();

        foreach ($res_array as $value) {

            $cook_deputation_count = $value['cook_deputation_count'];

            // $data[]             = array_values($value);
        }
        $json_array = [

            // "data"  => $data,
            "cook_deputation_count" => $cook_deputation_count,

        ];

        echo json_encode($json_array);

        break;

    case 'total_watchman_strength':

        // $district_name = $_POST['district_name'];
        $taluk_name = $_POST['taluk_name'];
        $hostel_name = $_POST['hostel_name'];
        $gender_type = $_POST['gender_type'];
        $hostel_type = $_POST['hostel_type'];

        $table = "establishment_registration a";

        $columns = [

            "count(*)as watchman_count"

        ];
        $table_details = [
            $table,
            $columns
        ];

        $where = "is_delete = 0 AND designation = '67934553276a759813'";

        if ($district_name != '') {
            $where .= " AND a.district_office = '" . $district_name . "'";
        }
        if ($taluk_name != '') {
            $where .= " AND a.taluk_office = '" . $taluk_name . "'";
        }
        if ($hostel_name != '') {
            $where .= " AND a.hostel_name = '" . $hostel_name . "'";
        }

        // Build EXISTS subquery for hostel_name filtering
        $exists_conditions = "b.unique_id = a.hostel_name AND b.is_delete = 0";

        // ✅ Gender filter supports multiple
        if (!empty($gender_type)) {
            if (is_array($gender_type)) {
                $gender_type = array_filter($gender_type, fn($val) => $val !== '');
                if (!empty($gender_type)) {
                    $gender_list = array_map(fn($val) => "'" . $val . "'", $gender_type);
                    $gender_str = implode(",", $gender_list);
                    $exists_conditions .= " AND b.gender_type IN (" . $gender_str . ")";
                }
            } else {
                if ($gender_type !== '') {
                    $exists_conditions .= " AND b.gender_type = '" . $gender_type . "'";
                }
            }
        }

        // ✅ Hostel type filter supports multiple
        if (!empty($hostel_type) && is_array($hostel_type)) {
            $hostel_type = array_filter($hostel_type, fn($val) => $val !== '');
            if (!empty($hostel_type)) {
                $hostel_type_list = array_map(fn($val) => "'" . $val . "'", $hostel_type);
                $hostel_type_str = implode(",", $hostel_type_list);
                $exists_conditions .= " AND b.hostel_type IN (" . $hostel_type_str . ")";
            }
        }

        // Add EXISTS subquery to WHERE
        $where .= " AND EXISTS (
        SELECT 1
        FROM hostel_name b
        WHERE $exists_conditions
        )";

        $result = $pdo->select($table_details, $where);
        $res_array = $result->data;


        //    print_r($result);die();

        foreach ($res_array as $value) {

            $watchman_count = $value['watchman_count'];

            // $data[]             = array_values($value);
        }
        $json_array = [

            // "data"  => $data,
            "watchman_count" => $watchman_count,

        ];

        echo json_encode($json_array);

        break;

    case 'total_watchman_deputation_strength':

        // $district_name = $_POST['district_name'];
        $taluk_name = $_POST['taluk_name'];
        $hostel_name = $_POST['hostel_name'];
        $gender_type = $_POST['gender_type'];
        $hostel_type = $_POST['hostel_type'];

        $table = "establishment_registration a";

        $columns = [

            "count(*)as watchman_deputation_count"

        ];
        $table_details = [
            $table,
            $columns
        ];

        $where = "is_delete = 0 AND designation = '67a0602466d5438020'";

        if ($district_name != '') {
            $where .= " AND a.district_office = '" . $district_name . "'";
        }
        if ($taluk_name != '') {
            $where .= " AND a.taluk_office = '" . $taluk_name . "'";
        }
        if ($hostel_name != '') {
            $where .= " AND a.hostel_name = '" . $hostel_name . "'";
        }

        // Build EXISTS subquery for hostel_name filtering
        $exists_conditions = "b.unique_id = a.hostel_name AND b.is_delete = 0";

        // ✅ Gender filter supports multiple
        if (!empty($gender_type)) {
            if (is_array($gender_type)) {
                $gender_type = array_filter($gender_type, fn($val) => $val !== '');
                if (!empty($gender_type)) {
                    $gender_list = array_map(fn($val) => "'" . $val . "'", $gender_type);
                    $gender_str = implode(",", $gender_list);
                    $exists_conditions .= " AND b.gender_type IN (" . $gender_str . ")";
                }
            } else {
                if ($gender_type !== '') {
                    $exists_conditions .= " AND b.gender_type = '" . $gender_type . "'";
                }
            }
        }

        // ✅ Hostel type filter supports multiple
        if (!empty($hostel_type) && is_array($hostel_type)) {
            $hostel_type = array_filter($hostel_type, fn($val) => $val !== '');
            if (!empty($hostel_type)) {
                $hostel_type_list = array_map(fn($val) => "'" . $val . "'", $hostel_type);
                $hostel_type_str = implode(",", $hostel_type_list);
                $exists_conditions .= " AND b.hostel_type IN (" . $hostel_type_str . ")";
            }
        }

        // Add EXISTS subquery to WHERE
        $where .= " AND EXISTS (
        SELECT 1
        FROM hostel_name b
        WHERE $exists_conditions
        )";

        $result = $pdo->select($table_details, $where);
        $res_array = $result->data;


        //    print_r($result);die();

        foreach ($res_array as $value) {

            $watchman_deputation_count = $value['watchman_deputation_count'];

            // $data[]             = array_values($value);
        }
        $json_array = [

            // "data"  => $data,
            "watchman_deputation_count" => $watchman_deputation_count,

        ];

        echo json_encode($json_array);

        break;

    case 'total_sweeper_strength':

        // $district_name = $_POST['district_name'];
        $taluk_name = $_POST['taluk_name'];
        $hostel_name = $_POST['hostel_name'];
        $gender_type = $_POST['gender_type'];
        $hostel_type = $_POST['hostel_type'];

        $table = "establishment_registration a";

        $columns = [

            "count(*)as sweeper_count"

        ];
        $table_details = [
            $table,
            $columns
        ];

        $where = "is_delete = 0 AND designation = '6795b2be36a0239724'";

        if ($district_name != '') {
            $where .= " AND a.district_office = '" . $district_name . "'";
        }
        if ($taluk_name != '') {
            $where .= " AND a.taluk_office = '" . $taluk_name . "'";
        }
        if ($hostel_name != '') {
            $where .= " AND a.hostel_name = '" . $hostel_name . "'";
        }

        // Build EXISTS subquery for hostel_name filtering
        $exists_conditions = "b.unique_id = a.hostel_name AND b.is_delete = 0";

        // ✅ Gender filter supports multiple
        if (!empty($gender_type)) {
            if (is_array($gender_type)) {
                $gender_type = array_filter($gender_type, fn($val) => $val !== '');
                if (!empty($gender_type)) {
                    $gender_list = array_map(fn($val) => "'" . $val . "'", $gender_type);
                    $gender_str = implode(",", $gender_list);
                    $exists_conditions .= " AND b.gender_type IN (" . $gender_str . ")";
                }
            } else {
                if ($gender_type !== '') {
                    $exists_conditions .= " AND b.gender_type = '" . $gender_type . "'";
                }
            }
        }

        // ✅ Hostel type filter supports multiple
        if (!empty($hostel_type) && is_array($hostel_type)) {
            $hostel_type = array_filter($hostel_type, fn($val) => $val !== '');
            if (!empty($hostel_type)) {
                $hostel_type_list = array_map(fn($val) => "'" . $val . "'", $hostel_type);
                $hostel_type_str = implode(",", $hostel_type_list);
                $exists_conditions .= " AND b.hostel_type IN (" . $hostel_type_str . ")";
            }
        }

        // Add EXISTS subquery to WHERE
        $where .= " AND EXISTS (
        SELECT 1
        FROM hostel_name b
        WHERE $exists_conditions
        )";

        $result = $pdo->select($table_details, $where);
        $res_array = $result->data;


        //    print_r($result);die();

        foreach ($res_array as $value) {

            $sweeper_count = $value['sweeper_count'];

            // $data[]             = array_values($value);
        }
        $json_array = [

            // "data"  => $data,
            "sweeper_count" => $sweeper_count,

        ];

        echo json_encode($json_array);

        break;

    case 'total_sweeper_deputation_strength':

        // $district_name = $_POST['district_name'];
        $taluk_name = $_POST['taluk_name'];
        $hostel_name = $_POST['hostel_name'];
        $gender_type = $_POST['gender_type'];
        $hostel_type = $_POST['hostel_type'];

        $table = "establishment_registration a";

        $columns = [

            "count(*)as sweeper_deputation_count"

        ];
        $table_details = [
            $table,
            $columns
        ];

        $where = "is_delete = 0 AND designation = '67a078c608d5850476'";

        if ($district_name != '') {
            $where .= " AND a.district_office = '" . $district_name . "'";
        }
        if ($taluk_name != '') {
            $where .= " AND a.taluk_office = '" . $taluk_name . "'";
        }
        if ($hostel_name != '') {
            $where .= " AND a.hostel_name = '" . $hostel_name . "'";
        }

        // Build EXISTS subquery for hostel_name filtering
        $exists_conditions = "b.unique_id = a.hostel_name AND b.is_delete = 0";

        // ✅ Gender filter supports multiple
        if (!empty($gender_type)) {
            if (is_array($gender_type)) {
                $gender_type = array_filter($gender_type, fn($val) => $val !== '');
                if (!empty($gender_type)) {
                    $gender_list = array_map(fn($val) => "'" . $val . "'", $gender_type);
                    $gender_str = implode(",", $gender_list);
                    $exists_conditions .= " AND b.gender_type IN (" . $gender_str . ")";
                }
            } else {
                if ($gender_type !== '') {
                    $exists_conditions .= " AND b.gender_type = '" . $gender_type . "'";
                }
            }
        }

        // ✅ Hostel type filter supports multiple
        if (!empty($hostel_type) && is_array($hostel_type)) {
            $hostel_type = array_filter($hostel_type, fn($val) => $val !== '');
            if (!empty($hostel_type)) {
                $hostel_type_list = array_map(fn($val) => "'" . $val . "'", $hostel_type);
                $hostel_type_str = implode(",", $hostel_type_list);
                $exists_conditions .= " AND b.hostel_type IN (" . $hostel_type_str . ")";
            }
        }

        // Add EXISTS subquery to WHERE
        $where .= " AND EXISTS (
        SELECT 1
        FROM hostel_name b
        WHERE $exists_conditions
        )";

        $result = $pdo->select($table_details, $where);
        $res_array = $result->data;


        //    print_r($result);die();

        foreach ($res_array as $value) {

            $sweeper_deputation_count = $value['sweeper_deputation_count'];

            // $data[]             = array_values($value);
        }
        $json_array = [

            // "data"  => $data,
            "sweeper_deputation_count" => $sweeper_deputation_count,

        ];

        echo json_encode($json_array);

        break;

    default:
        break;
}

function total_reg_count($unique_id = "", $district_name = "", $taluk_name = "", $hostel_name = "", $gender_type = [], $hostel_type = [])
{
    global $pdo;

    // $table_name = "std_reg_s";
    $where = [
        "is_active" => 1,
        "is_delete" => 0,
    ];

    $table_columns = [
        "count(id) as tot_cnt",
    ];

    // Table: only std_app_s
    $table_details = [
        "std_reg_s a",
        $table_columns
    ];

    // Base WHERE for std_app_s
    $where = "a.is_delete = 0 AND a.dropout_status = 1";

    if ($district_name != '') {
        $where .= " AND a.hostel_district_1 = '" . $district_name . "'";
    }
    if ($taluk_name != '') {
        $where .= " AND a.hostel_taluk_1 = '" . $taluk_name . "'";
    }
    if ($hostel_name != '') {
        $where .= " AND a.hostel_1 = '" . $hostel_name . "'";
    }

    // Build EXISTS subquery for hostel_name filtering
    $exists_conditions = "b.unique_id = a.hostel_1 AND b.is_delete = 0";

    // ✅ Gender filter supports multiple
    if (!empty($gender_type)) {
        if (is_array($gender_type)) {
            $gender_type = array_filter($gender_type, fn($val) => $val !== '');
            if (!empty($gender_type)) {
                $gender_list = array_map(fn($val) => "'" . $val . "'", $gender_type);
                $gender_str = implode(",", $gender_list);
                $exists_conditions .= " AND b.gender_type IN (" . $gender_str . ")";
            }
        } else {
            if ($gender_type !== '') {
                $exists_conditions .= " AND b.gender_type = '" . $gender_type . "'";
            }
        }
    }

    // ✅ Hostel type filter supports multiple
    if (!empty($hostel_type) && is_array($hostel_type)) {
        $hostel_type = array_filter($hostel_type, fn($val) => $val !== '');
        if (!empty($hostel_type)) {
            $hostel_type_list = array_map(fn($val) => "'" . $val . "'", $hostel_type);
            $hostel_type_str = implode(",", $hostel_type_list);
            $exists_conditions .= " AND b.hostel_type IN (" . $hostel_type_str . ")";
        }
    }

    // Add EXISTS subquery to WHERE
    $where .= " AND EXISTS (
        SELECT 1
        FROM hostel_name b
        WHERE $exists_conditions
        )";


    // Use the select method from $pdo to query the database
    $amc_name_list = $pdo->select($table_details, $where);

    // print_r($amc_name_list);

    if ($amc_name_list->status) {
        return $amc_name_list->data[0]['tot_cnt'];
    } else {
        print_r($amc_name_list);
        return 0;
    }
}

?>