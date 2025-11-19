<?php

// Get folder Name From Currnent Url
$folder_name = explode('/', $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];
// // Database Country Table Name
$table = 'cctv_live';

// // Include DB file and Common Functions
include '../../config/dbconfig.php';
// // Variables Declaration
$action = $_POST['action'];
$user_type = '';
$is_active = '';
$unique_id = '';
$prefix = '';
$data = '';
$msg = '';
$error = '';
$status = '';
$test = ''; // For Developer Testing Purpose
switch ($action) {
    case 'createupdate':

        $vali_district_name = filter_input(INPUT_POST, 'district_name', FILTER_SANITIZE_STRING);
        $vali_taluk_name = filter_input(INPUT_POST, 'taluk_name', FILTER_SANITIZE_STRING);
        $vali_hostel_name = filter_input(INPUT_POST, 'hostel_name', FILTER_SANITIZE_STRING);
        $vali_academic_year = filter_input(INPUT_POST, 'academic_year', FILTER_SANITIZE_STRING);
        $vali_cam_name = filter_input(INPUT_POST, 'cam_name', FILTER_SANITIZE_STRING);
        $vali_link1 = filter_input(INPUT_POST, 'link1', FILTER_SANITIZE_STRING);
        $vali_link2 = filter_input(INPUT_POST, 'link2', FILTER_SANITIZE_STRING);
        // $vali_gender_type = filter_input(INPUT_POST,'gender_type',FILTER_SANITIZE_STRING);

        if (!$vali_district_name || !$vali_taluk_name || !$vali_hostel_name || !$vali_academic_year || !$vali_cam_name || !$vali_link1 || !$vali_link2) {
            $msg = "form_alert";
        } else {

            $district_name = $_POST['district_name'];
            $taluk_name = $_POST['taluk_name'];
            $hostel_name = $_POST['hostel_name'];
            $academic_year = $_POST['academic_year'];
            $cam_name = sanitizeInput($_POST['cam_name']);
            $link1 = $_POST['link1'];
            $link2 = $_POST['link2'];

            $is_active = $_POST['is_active'];
            $unique_id = $_POST['unique_id'];
            $update_where = '';
            $columns = [
                'district_name' => $district_name,
                'taluk_name' => $taluk_name,
                'hostel_name' => $hostel_name,
                'academic_year' => $academic_year,
                'cam_name' => $cam_name,
                'cam_link1' => $link1,
                'cam_link2' => $link2,
                'is_active' => 1,
                'unique_id' => unique_id($prefix),
            ];
            // check already Exist Or not
            $table_details = [
                $table,
                [
                    'COUNT(unique_id) AS count',
                ],
            ];
            $select_where = 'cam_name = "' . $cam_name . '"  AND is_delete = 0  ';
            // When Update Check without current id
            if ($unique_id) {
                $select_where .= ' AND unique_id !="' . $unique_id . '" ';
            }
            $action_obj = $pdo->select($table_details, $select_where);
            if ($action_obj->status) {
                $status = $action_obj->status;
                $data = $action_obj->data;
                $error = '';
                $sql = $action_obj->sql;
            } else {
                $status = $action_obj->status;
                $data = $action_obj->data;
                $error = $action_obj->error;
                $sql = $action_obj->sql;
                $msg = 'error';
            }
            if ($data[0]['count']) {
                $msg = 'already';
            } elseif ($data[0]['count'] == 0) {
                // Update Begins
                if ($unique_id) {
                    unset($columns['unique_id']);
                    $update_where = [
                        'unique_id' => $unique_id,
                    ];
                    $action_obj = $pdo->update($table, $columns, $update_where);
                    // Update Ends
                } else {
                    // Insert Begins
                    $action_obj = $pdo->insert($table, $columns);
                    // Insert Ends
                }
                if ($action_obj->status) {
                    $status = $action_obj->status;
                    $data = $action_obj->data;
                    $error = '';
                    $sql = $action_obj->sql;
                    if ($unique_id) {
                        $msg = 'update';
                    } else {
                        $msg = 'create';
                    }
                } else {
                    $status = $action_obj->status;
                    $data = $action_obj->data;
                    $error = $action_obj->error;
                    $sql = $action_obj->sql;
                    $msg = 'error';
                }
            }
        }
        $json_array = [
            'status' => $status,
            'data' => $data,
            'error' => $error,
            'msg' => $msg,
            // 'sql' => $sql,
        ];
        echo json_encode($json_array);
        break;
    case 'datatable1':
        // DataTable Variables
        $search = $_POST['search']['value'];
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;
        $data = [];
        if ($length == '-1') {
            $limit = '';
        }
        // Query Variables
        $json_array = '';
        $columns = [
            '@a:=@a+1 s_no',
            'user_type',
            'is_active',
            'unique_id',
        ];
        $table_details = [
            $table . ' , (SELECT @a:= ' . $start . ') AS a ',
            $columns,
        ];
        $where = 'is_delete = 0';
        $order_by = '';
        if ($_POST['search']['value']) {
            $where .= " AND user_type LIKE '" . mysql_like($_POST['search']['value']) . "' ";
        }
        // Datatable Searching
        $search = datatable_searching($search, $columns);
        if ($search) {
            if ($where) {
                $where .= ' AND ';
            }
            $where .= $search;
        }
        $sql_function = 'SQL_CALC_FOUND_ROWS';
        $result = $pdo->select($table_details, $where, $limit, $start, $order_by, $sql_function);
        $total_records = total_records();
        if ($result->status) {
            $res_array = $result->data;
            foreach ($res_array as $key => $value) {
                $value['user_type'] = disname($value['user_type']);
                $value['is_active'] = is_active_show($value['is_active']);
                $btn_update = btn_update($folder_name, $value['unique_id']);
                $btn_delete = btn_delete($folder_name, $value['unique_id']);
                if ($value['unique_id'] == '5f97fc3257f2525529') {
                    $btn_update = '';
                    $btn_delete = '';
                }
                $value['unique_id'] = $btn_update . $btn_delete;
                $data[] = array_values($value);
            }
            $json_array = [
                'draw' => intval($draw),
                'recordsTotal' => intval($total_records),
                'recordsFiltered' => intval($total_records),
                'data' => $data,
                // 'testing' => $result->sql,
            ];
        } else {
            // print_r($result);
        }
        echo json_encode($json_array);
        break;


        
    case 'datatable':
        // DataTable Variables
        $search = $_POST['search']['value'];
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;
        $data = [];
        if ($length == '-1') {
            $limit = '';
        }
        $district_name = $_POST['district_name'];
        $taluk_name = $_POST['taluk_name'];
        $hostel_name = $_POST['hostel_name'];
        $academic_year = $_POST['academic_year'];

        // Query Variables
        $json_array = '';
        $columns = [
            "'' as s_no",
            '(SELECT district_name FROM district_name AS district_name WHERE district_name.unique_id = ' . $table . '.district_name ) AS district_name',
            '(SELECT taluk_name FROM taluk_creation AS taluk_name WHERE taluk_name.unique_id = ' . $table . '.taluk_name ) AS taluk_name',
            '(SELECT hostel_name FROM hostel_name AS hostel_name WHERE hostel_name.unique_id = ' . $table . '.hostel_name ) AS hostel_name',
            '(SELECT hostel_id FROM hostel_name  WHERE hostel_name.unique_id = ' . $table . '.hostel_name ) AS hostel_id',
            'cam_name',
            'cam_link1 as cam_link1',
            'cam_link2 as cam_link2',
            'unique_id',
        ];
        $table_details = [
            $table . ' , (SELECT @a:= ' . $start . ') AS a ',
            $columns,
        ];
        $where = 'is_delete = 0';
        if ($district_name) {
            $where .= " And district_name = '$district_name'";
        }
        if ($taluk_name) {
            $where .= " And taluk_name = '$taluk_name'";
        }
        if ($hostel_name) {
            $where .= " And hostel_name = '$hostel_name'";
        }
        if ($academic_year) {
            $where .= " And academic_year = '$academic_year'";
        }

        $order_by = '';
        if ($_POST['search']['value']) {
            // $where .= " AND user_type LIKE '".mysql_like($_POST['search']['value'])."' ";
        }
        // Datatable Searching
        $search = datatable_searching($search, $columns);
        if ($search) {
            if ($where) {
                $where .= ' AND ';
            }
            $where .= $search;
        }
        $sql_function = 'SQL_CALC_FOUND_ROWS';
        $result = $pdo->select($table_details, $where, $limit, $start, $order_by, $sql_function);
        $total_records = total_records();
        if ($result->status) {
            $res_array = $result->data;
            foreach ($res_array as $key => $value) {
                $value['s_no'] = $sno++;
                // $card_content = '';
                if ($value['cam_link2'] == '') {
                    
                    $card_content = '<iframe width="100%" height="300" src="https://nallosaims.tn.gov.in:5443/WebRTCApp/play.html?id=' . $value['cam_link1'] . '" frameborder="0" allowfullscreen></iframe>';
                //  $card_content = '<iframe width="100%" height="300" src="http://125.17.238.158:5080/LiveApp/play.html?id=' . $value['cam_link1'] . '" frameborder="0" allowfullscreen></iframe>';
                } else {
                    $card_content = '<div style="display: flex;">'
                        . '<iframe width="100%" height="300" src="https://nallosaims.tn.gov.in:5443/WebRTCApp/play.html?id=' . $value['cam_link1'] . '" frameborder="0" allowfullscreen style="margin-right: 10px;"></iframe>'
                        . '<iframe width="50%" height="300" src="https://nallosaims.tn.gov.in:5443/WebRTCApp/play.html?id=' . $value['cam_link2'] . '" frameborder="0" allowfullscreen></iframe>'
                        . '</div>';
                }

                $card_header = '<div class="card-header"><b>District Name:</b> ' . $value['district_name'] . ' &nbsp;&nbsp;&nbsp;&nbsp; <b>Taluk Name:</b> ' . $value['taluk_name'] . ' &nbsp;&nbsp;&nbsp;&nbsp; <b>Hostel Name:</b> ' . $value['hostel_name'] . '' . $value['hostel_id'] . '</div><hr style="border-color: #00000029;">';
                $cards .= '<div style="width: 100%; margin: 10px; background-color: #e2e2e2;">'
                    . $card_header
                    . '<div class="card-body">'
                    . $card_contents
                    . '</div></div>';
                // $value['unique_id'] = $cards;
                // print_r($cards);
                $data[] = '<div style="display: flex; flex-wrap: wrap;">' . $cards . '</div>';

                // $data[] = array_values($cards);
            }
            $json_array = [
                'draw' => intval($draw),
                'recordsTotal' => intval($total_records),
                'recordsFiltered' => intval($total_records),
                'data' => $data,
                // 'testing' => $result->sql,
            ];
        } else {
            // print_r($result);
        }
        echo json_encode($json_array);
        break;

    case 'get_video':
        // DataTable Variables

        $per_page = 10; // Records per page
        // dd($_GET['page']);
        // $current_page = (isset($_POST['start']) && is_numeric($_POST['start'])) ? (int) $_POST['start'] / $per_page : 1;
        // $current_page = $_POST['page'];
        // $current_page = $_GET['page'];
        $current_page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int) $_GET['page'] : 1;
        // echo $current_page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int) $_GET['page'] : 1;

        $search = $_POST['search']['value'];
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        // $limit = ($length == '-1') ? '' : $length;
        $data = [];

        // Query Variables
        $district_name = $_POST['district_name'];
        $taluk_name = $_POST['taluk_name'];
        $hostel_name = $_POST['hostel_name'];
        $academic_year = $_POST['academic_year'];

        // $district_name = $_GET['district_name'];
        // $taluk_name = $_GET['taluk_name'];
        // $hostel_name = $_GET['hostel_name'];
        // $academic_year = $_GET['academic_year'];
        // $limit = 10;
        $page = 1;

        // Fetch the data for the current page
        // if ($_POST['page'] > 1) {
        //     $start = ($_POST['page'] - 1) * $limit;
        // } else {
        //     $start = 0;
        // }


        $columns = [
            "'' as s_no",
            '(SELECT district_name FROM district_name AS district_name WHERE district_name.unique_id = ' . $table . '.district_name ) AS district_name',
            '(SELECT taluk_name FROM taluk_creation AS taluk_name WHERE taluk_name.unique_id = ' . $table . '.taluk_name ) AS taluk_name',
            '(SELECT hostel_name FROM hostel_name AS hostel_name WHERE hostel_name.unique_id = ' . $table . '.hostel_name ) AS hostel_name',
            'cam_name',
            '(SELECT hostel_id FROM hostel_name AS hostel_name WHERE hostel_name.unique_id = ' . $table . '.hostel_name ) AS hostel_id',
            'cam_link1 as cam_link1',
            'cam_link2 as cam_link2',
            'unique_id',
        ];
        $table_details = [
            $table,
            $columns,
        ];
        $where = 'is_delete = 0';
        if ($_POST['district_name']) {
            $where .= " And district_name = '$district_name'";

        }
        // else{
        //     $password = '3sc3RLrpd17';
        //     $enc_method = 'aes-256-cbc';
        //     $enc_password = substr(hash('sha256', $password, true), 0, 32);
        //     $enc_iv = 'av3DYGLkwBsErphc';


        //     $gd =  openssl_decrypt(base64_decode($_GET['district_name']), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);

        //     $where .= " And district_name = '$gd'";
        // }
        if ($_POST['taluk_name']) {
            $where .= " And taluk_name = '$taluk_name'";

        }
        // else{
        //     $password = '3sc3RLrpd17';
        //     $enc_method = 'aes-256-cbc';
        //     $enc_password = substr(hash('sha256', $password, true), 0, 32);
        //     $enc_iv = 'av3DYGLkwBsErphc';



        //     $gt =  openssl_decrypt(base64_decode($_GET['taluk_name']), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);

        //     $where .= " And taluk_name = '$gt'";
        // }
        if ($_POST['hostel_name']) {
            $where .= " And hostel_name = '$hostel_name'";
        }
        // }else{
        //     $password = '3sc3RLrpd17';
        //     $enc_method = 'aes-256-cbc';
        //     $enc_password = substr(hash('sha256', $password, true), 0, 32);
        //     $enc_iv = 'av3DYGLkwBsErphc';



        //     $gh = openssl_decrypt(base64_decode($_GET['hostel_name']), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);

        //     $where .= " And hostel_name = '$gh'";
        // }
        if ($_POST['academic_year']) {
            $where .= " And academic_year = '$academic_year'";

        }
        // else{
        //     $password = '3sc3RLrpd17';
        //     $enc_method = 'aes-256-cbc';
        //     $enc_password = substr(hash('sha256', $password, true), 0, 32);
        //     $enc_iv = 'av3DYGLkwBsErphc';



        //     $gac =  openssl_decrypt(base64_decode($_GET['academic_year']), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);
        //     $where .= " And academic_year = '$gac'";
        // }

        // // $limits = ($current_page - 1) * $per_page.', '.$per_page;
        $results = $pdo->select($table_details, $where);
        $total_records1 = total_records();
        // No of Products per Page
        $no_of_products_per_page = 3;

        // Calculate no of pages
        $no_of_pages = ceil($total_records1 / $no_of_products_per_page);
        // $page = 1;
        //  echo $_REQUEST['page'];
        //     $password = '3sc3RLrpd17';
        //     $enc_method = 'aes-256-cbc';
        //     $enc_password = substr(hash('sha256', $password, true), 0, 32);
        //     $enc_iv = 'av3DYGLkwBsErphc';
        //     // echo 'good'.$_GET['name'];
        //     if($_GET['page']) {
        //     $folder_name_dec = str_replace(' ', '+', $_GET['page']);

        //      $get_dec_file = openssl_decrypt(base64_decode($folder_name_dec), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);
        //     if ($get_dec_file) {
        //         $page = $get_dec_file;
        //     }
        // }
        // $page = 1;
        // if($_POST['get_val'] == ''){
        //     $page = 1;
        // } 

        if ($_POST['get_val']) {
            $page = $_POST['get_val'];
        }

        // else if($district_name !='' && $_POST['get_val'] != ''){
        //     $page = 1;
        // }
        // if($taluk_name =='' && $_POST['get_val'] != '') {
        //     $page = $_POST['get_val'];
        // }
        // else if($taluk_name !='' && $_POST['get_val'] != ''){
        //     $page = 1;
        // }
        // if($hostel_name =='' && $_POST['get_val'] != '') {
        //     $page = $_POST['get_val'];
        // }
        // else if($hostel_name !='' && $_POST['get_val'] != ''){
        //     $page = 1;
        // }
        // if($academic_year =='' && $_POST['get_val'] != '') {
        //     $page = $_POST['get_val'];
        // }
        // else if($academic_year !='' && $_POST['get_val'] != ''){
        //     $page = 1;
        // }
        // $lmt = ($page - 1) * $no_of_products_per_page ;
        // if($_GET['district_name'] ==''){
        if ($page == 1 && $taluk_name == '') {
            $data_limit = ($page - 1) * $no_of_products_per_page;
        }
        if ($page == 1 && $taluk_name != '') {
            $data_limit = ($page - 1) * $no_of_products_per_page;
        }
        if ($page != 1 && $taluk_name == '') {
            $data_limit = ($page - 1) * $no_of_products_per_page;
        }
        if ($page != 1 && $taluk_name != '') {
            $test = $page - 1;
            $data_limit = ($test - 1) * $no_of_products_per_page;
        }

        //  if($page == 1 && $taluk_name =='' && $hostel_name != ''){
        //     $data_limit = ($page - 1) * $no_of_products_per_page;
        // }
        // if($page == 1 && $taluk_name !='' && $hostel_name != ''){
        //     $data_limit = ($page - 1) * $no_of_products_per_page;
        // }
        //  if($page != 1 && $taluk_name !='' && $hostel_name ==''){
        //     $data_limit = ($page - 1) * $no_of_products_per_page;
        // }
        // if($page == 1 && $taluk_name !='' && $hostel_name !=''){
        //     $data_limit = ($page - 1) * $no_of_products_per_page;
        // }
        // if($page != 1 && $taluk_name !='' && $hostel_name !=''){
        //     $test = $page - 1;
        //     $data_limit = ($test - 1)  * $no_of_products_per_page;
        // }

        // if($page == 1 && $taluk_name !='' && $hostel_name == '' && $academic_year == ''){
        //     $data_limit = ($page - 1) * $no_of_products_per_page;
        // }
        // if($page != 1 && $taluk_name !='' && $hostel_name =='' && $academic_year ==''){
        //     $data_limit = ($page - 1) * $no_of_products_per_page;
        // }
        // if($page == 1 && $taluk_name !='' && $hostel_name !='' && $academic_year != ''){
        //     $data_limit = ($page - 1) * $no_of_products_per_page;
        // }
        // if($page != 1 && $taluk_name !='' && $hostel_name !='' && $academic_year != ''){
        //     $test = $page - 1;
        //     $data_limit = ($test - 1)  * $no_of_products_per_page;
        // }

        // }else{
        //     $data_limit = $lmt;
        // }
        // $data_limit1 = 0;
        // $limit =  $start_limit;

        $order_by = '';
        // Datatable Searching
        $search = datatable_searching($search, $columns);
        if ($search) {
            if ($where) {
                $where .= ' AND ';
            }
            $where .= $search;
        }
        $sql_function = 'SQL_CALC_FOUND_ROWS';
        // $page = 1;
        // echo $_GET['name'];
        // if($page == 1){

        $result = $pdo->select($table_details, $where . ' LIMIT ' . $data_limit . ', ' . $no_of_products_per_page);

        // }
        // else{
        //     $result = $pdo->select($table_details, $where.' LIMIT '.$data_limit1.', '.$no_of_products_per_page);
        // }
        // print_r($result);   
        // }else{
        //     $result = $pdo->select($table_details, $where.' LIMIT '.$data_limit1.', '.$no_of_products_per_page); 
        // }

        //     if($district_name || $taluk_name || $hostel_name || $academic_year == ''){

        //         $result = $pdo->select($table_details, $where.' LIMIT '.$data_limit.', '.$no_of_products_per_page);
//         // print_r($result);
//     }else{
//     $result = $pdo->select($table_details, $where.' LIMIT '.$data_limit1.', '.$no_of_products_per_page); 
// }




        // print_r($result);
        $total_records = total_records();

        // $page = 1;

        $demo = 3;

        if ($result->status) {
            $res_array = $result->data;
            // print_r()

            if (count($result->data) == '0') {
                $value['unique_id'] = '<img width="300" height="300" src="../adhmAdmin/assets/images/novideo.jpg">';
                $data[] = $value['unique_id'];
            } else {
                $card_content .= "<div id='pagination_data'>";

                $sno = 1;
                $cards = '';
                $card_food = '';

                foreach ($res_array as $key => $value) {
                    $value['s_no'] = $sno++;
                    $card_content = '';
                    if ($value['cam_link2'] == '') {
                        $card_content = '<iframe width="100%" height="300" src="https://nallosaims.tn.gov.in:5443/WebRTCApp/play.html?id=' . $value['cam_link1'] . '" frameborder="0" allowfullscreen></iframe>';
                    } else {
                        $card_content = '<div style="display: flex;">'
                            . '<iframe width="50%" height="300" src="https://nallosaims.tn.gov.in:5443/WebRTCApp/play.html?id=' . $value['cam_link1'] . '" frameborder="0" allowfullscreen style="margin-right: 10px;"></iframe>'
                            . '<iframe width="50%" height="300" src="https://nallosaims.tn.gov.in:5443/WebRTCApp/play.html?id=' . $value['cam_link2'] . '" frameborder="0" allowfullscreen></iframe>'
                            . '</div>';
                    }

                    $card_header = '<div class="card-header"><b>District Name:</b> ' . $value['district_name'] . ' &nbsp;&nbsp;&nbsp;&nbsp; <b>Taluk Name:</b> ' . $value['taluk_name'] . ' &nbsp;&nbsp;&nbsp;&nbsp; <b>Hostel Name:</b> ' . $value['hostel_name'] . '&nbsp;&nbsp;&nbsp;&nbsp;<b>Hostel ID:</b>' . $value['hostel_id'] . '</div><hr style="border-color: #00000029;">';
                    $cards .= '<div style="width: 100%; margin: 10px; background-color: #e2e2e2;">'
                        . $card_header
                        . '<div class="card-body">'
                        . $card_content
                        . '</div></div>';
                    //     $total_pages = ceil($total_records / $per_page);
                    // $card_food = "<div id='pagination_links'>";
                    // if ($current_page > 1) {
                    //     $card_food .= "<a href='?start=".($current_page - 1) * $per_page."'>Previous</a>";
                    // }
                    // for ($i = 1; $i <= $total_pages; ++$i) {
                    //     if ($i == $current_page) {
                    //         $card_food .=  "<span class='current'>$i</span>";
                    //     } else {
                    //         $card_food .=  "<a href='?start=".($i * $per_page - $per_page)."'>$i</a>";
                    //     }
                    // }
                    // if ($current_page > $total_pages) {
                    //     $card_food .=  "<a href='?start=".($current_page * $per_page)."'>Next</a>";
                    // }
                    // $card_food .=  '</div>';
                }
                $card_food .= '<br><br>';
                // echo $_GET['pagename'];

                // Calculate no of pages
                $demos = ceil($total_records1 / $demo);
                // Generate Page Linkz
                $card_food .= '<div style="text-align: end;padding: 14px;">';
                for ($i = 1; $i <= $demos; ++$i) {
                    // echo "hello";
                    // $page = $_GET['page'];
                    // Check Active Page
                    // $a = base64_encode($i);
                    $password = '3sc3RLrpd17';
                    $enc_method = 'aes-256-cbc';
                    $enc_password = substr(hash('sha256', $password, true), 0, 32);
                    $enc_iv = 'av3DYGLkwBsErphc';

                    // $menu_screen = 'dashboard/form';
                    // $ab = base64_encode(openssl_encrypt($i, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));
                    // $d = base64_encode(openssl_encrypt($district_name, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));
                    // $t = base64_encode(openssl_encrypt($taluk_name, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));
                    // $h = base64_encode(openssl_encrypt($hostel_name, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));
                    // $ac = base64_encode(openssl_encrypt($academic_year, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));
                    $ab = base64_encode(openssl_encrypt($i, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));
                    $d = $district_name;
                    $t = $taluk_name;
                    $h = $hostel_name;
                    $ac = $academic_year;

                    if ($page == $i) {
                        $card_food .= "<a href='index.php?file=sG35YsNSNUzbv5pCM7Yx9A&page={$ab}&name={$i}&district_name={$d}&taluk_name={$t}&hostel_name={$h}&academic_year={$ac}&data_limit={$lmt}' class='link active'>{$i}</a>";
                    } else {
                        $card_food .= "<a href='index.php?file=sG35YsNSNUzbv5pCM7Yx9A&page={$ab}&name={$i}&district_name={$d}&taluk_name={$t}&hostel_name={$h}&academic_year={$ac}&data_limit={$lmt}' class='link'>{$i}</a>";
                    }

                    // if ($page == $i) {
                    //     $card_food .= "<a href='index.php?file=cctv_live.php?p  age={$ab}&name={$ab}' class='link active'>{$i}</a>";
                    // } else {
                    //     $card_food .= "<a href='index.php?file=cctv_live.php?page={$ab}&name={$ab}' class='link'>{$i}</a>";
                    // }
                }
                $card_food .= '</div >';
                // $cards .= $card_food;

                $card_content .= '</div>';

                // $data[] = '<div style="display: flex; flex-wrap: wrap;"><form method="GET">'.$cards.''.$card_food.'</form></div>';
                $data[] = '<div style="display: flex; flex-wrap: wrap;"><form method="GET">' . $cards . '' . $card_food . '</form></div>';
            }
            $json_array = [
                'draw' => intval($draw),
                'recordsTotal' => intval($total_records),
                'recordsFiltered' => intval($total_records),
                'data' => $data,
                // 'testing' => $result->sql,
            ];
        } else {
            // print_r($result);
        }
        echo json_encode($json_array);
        break;


        case 'get_video_1':
            // DataTable Variables
    
            $per_page = 10; // Records per page
            // dd($_GET['page']);
            // $current_page = (isset($_POST['start']) && is_numeric($_POST['start'])) ? (int) $_POST['start'] / $per_page : 1;
            // $current_page = $_POST['page'];
            // $current_page = $_GET['page'];
            $current_page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int) $_GET['page'] : 1;
            // echo $current_page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int) $_GET['page'] : 1;
    
            $search = $_POST['search']['value'];
            $length = $_POST['length'];
            $start = $_POST['start'];
            $draw = $_POST['draw'];
            // $limit = ($length == '-1') ? '' : $length;
            $data = [];
    
            // Query Variables
            $district_name = $_POST['district_name'];
            $taluk_name = $_POST['taluk_name'];
            $hostel_name = $_POST['hostel_name'];
            $academic_year = $_POST['academic_year'];
    
            // $district_name = $_GET['district_name'];
            // $taluk_name = $_GET['taluk_name'];
            // $hostel_name = $_GET['hostel_name'];
            // $academic_year = $_GET['academic_year'];
            // $limit = 10;
            $page = 1;
    
            // Fetch the data for the current page
            // if ($_POST['page'] > 1) {
            //     $start = ($_POST['page'] - 1) * $limit;
            // } else {
            //     $start = 0;
            // }
    
    
            $columns = [
                "'' as s_no",
                '(SELECT district_name FROM district_name AS district_name WHERE district_name.unique_id = ' . $table . '.district_name ) AS district_name',
                '(SELECT taluk_name FROM taluk_creation AS taluk_name WHERE taluk_name.unique_id = ' . $table . '.taluk_name ) AS taluk_name',
                '(SELECT hostel_name FROM hostel_name AS hostel_name WHERE hostel_name.unique_id = ' . $table . '.hostel_name ) AS hostel_name',
                'cam_name',
                '(SELECT hostel_id FROM hostel_name AS hostel_name WHERE hostel_name.unique_id = ' . $table . '.hostel_name ) AS hostel_id',
                'cam_link1 as cam_link1',
                'cam_link2 as cam_link2',
                'unique_id',
            ];
            $table_details = [
                $table,
                $columns,
            ];
            $where = 'is_delete = 0';
            if ($_POST['district_name']) {
                $where .= " And district_name = '$district_name'";
    
            }
            // else{
            //     $password = '3sc3RLrpd17';
            //     $enc_method = 'aes-256-cbc';
            //     $enc_password = substr(hash('sha256', $password, true), 0, 32);
            //     $enc_iv = 'av3DYGLkwBsErphc';
    
    
            //     $gd =  openssl_decrypt(base64_decode($_GET['district_name']), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);
    
            //     $where .= " And district_name = '$gd'";
            // }
            if ($_POST['taluk_name']) {
                $where .= " And taluk_name = '$taluk_name'";
    
            }
            // else{
            //     $password = '3sc3RLrpd17';
            //     $enc_method = 'aes-256-cbc';
            //     $enc_password = substr(hash('sha256', $password, true), 0, 32);
            //     $enc_iv = 'av3DYGLkwBsErphc';
    
    
    
            //     $gt =  openssl_decrypt(base64_decode($_GET['taluk_name']), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);
    
            //     $where .= " And taluk_name = '$gt'";
            // }
            if ($_POST['hostel_name']) {
                $where .= " And hostel_name = '$hostel_name'";
            }
            // }else{
            //     $password = '3sc3RLrpd17';
            //     $enc_method = 'aes-256-cbc';
            //     $enc_password = substr(hash('sha256', $password, true), 0, 32);
            //     $enc_iv = 'av3DYGLkwBsErphc';
    
    
    
            //     $gh = openssl_decrypt(base64_decode($_GET['hostel_name']), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);
    
            //     $where .= " And hostel_name = '$gh'";
            // }
            if ($_POST['academic_year']) {
                $where .= " And academic_year = '$academic_year'";
    
            }
            // else{
            //     $password = '3sc3RLrpd17';
            //     $enc_method = 'aes-256-cbc';
            //     $enc_password = substr(hash('sha256', $password, true), 0, 32);
            //     $enc_iv = 'av3DYGLkwBsErphc';
    
    
    
            //     $gac =  openssl_decrypt(base64_decode($_GET['academic_year']), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);
            //     $where .= " And academic_year = '$gac'";
            // }
    
            // // $limits = ($current_page - 1) * $per_page.', '.$per_page;
            $results = $pdo->select($table_details, $where);
            $total_records1 = total_records();
            // No of Products per Page
            $no_of_products_per_page = 3;
    
            // Calculate no of pages
            $no_of_pages = ceil($total_records1 / $no_of_products_per_page);
            // $page = 1;
            //  echo $_REQUEST['page'];
            //     $password = '3sc3RLrpd17';
            //     $enc_method = 'aes-256-cbc';
            //     $enc_password = substr(hash('sha256', $password, true), 0, 32);
            //     $enc_iv = 'av3DYGLkwBsErphc';
            //     // echo 'good'.$_GET['name'];
            //     if($_GET['page']) {
            //     $folder_name_dec = str_replace(' ', '+', $_GET['page']);
    
            //      $get_dec_file = openssl_decrypt(base64_decode($folder_name_dec), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);
            //     if ($get_dec_file) {
            //         $page = $get_dec_file;
            //     }
            // }
            // $page = 1;
            // if($_POST['get_val'] == ''){
            //     $page = 1;
            // } 
    
            if ($_POST['get_val']) {
                $page = $_POST['get_val'];
            }
    
            // else if($district_name !='' && $_POST['get_val'] != ''){
            //     $page = 1;
            // }
            // if($taluk_name =='' && $_POST['get_val'] != '') {
            //     $page = $_POST['get_val'];
            // }
            // else if($taluk_name !='' && $_POST['get_val'] != ''){
            //     $page = 1;
            // }
            // if($hostel_name =='' && $_POST['get_val'] != '') {
            //     $page = $_POST['get_val'];
            // }
            // else if($hostel_name !='' && $_POST['get_val'] != ''){
            //     $page = 1;
            // }
            // if($academic_year =='' && $_POST['get_val'] != '') {
            //     $page = $_POST['get_val'];
            // }
            // else if($academic_year !='' && $_POST['get_val'] != ''){
            //     $page = 1;
            // }
            // $lmt = ($page - 1) * $no_of_products_per_page ;
            // if($_GET['district_name'] ==''){
            if ($page == 1 && $taluk_name == '') {
                $data_limit = ($page - 1) * $no_of_products_per_page;
            }
            if ($page == 1 && $taluk_name != '') {
                $data_limit = ($page - 1) * $no_of_products_per_page;
            }
            if ($page != 1 && $taluk_name == '') {
                $data_limit = ($page - 1) * $no_of_products_per_page;
            }
            if ($page != 1 && $taluk_name != '') {
                $test = $page - 1;
                $data_limit = ($test - 1) * $no_of_products_per_page;
            }
    
            //  if($page == 1 && $taluk_name =='' && $hostel_name != ''){
            //     $data_limit = ($page - 1) * $no_of_products_per_page;
            // }
            // if($page == 1 && $taluk_name !='' && $hostel_name != ''){
            //     $data_limit = ($page - 1) * $no_of_products_per_page;
            // }
            //  if($page != 1 && $taluk_name !='' && $hostel_name ==''){
            //     $data_limit = ($page - 1) * $no_of_products_per_page;
            // }
            // if($page == 1 && $taluk_name !='' && $hostel_name !=''){
            //     $data_limit = ($page - 1) * $no_of_products_per_page;
            // }
            // if($page != 1 && $taluk_name !='' && $hostel_name !=''){
            //     $test = $page - 1;
            //     $data_limit = ($test - 1)  * $no_of_products_per_page;
            // }
    
            // if($page == 1 && $taluk_name !='' && $hostel_name == '' && $academic_year == ''){
            //     $data_limit = ($page - 1) * $no_of_products_per_page;
            // }
            // if($page != 1 && $taluk_name !='' && $hostel_name =='' && $academic_year ==''){
            //     $data_limit = ($page - 1) * $no_of_products_per_page;
            // }
            // if($page == 1 && $taluk_name !='' && $hostel_name !='' && $academic_year != ''){
            //     $data_limit = ($page - 1) * $no_of_products_per_page;
            // }
            // if($page != 1 && $taluk_name !='' && $hostel_name !='' && $academic_year != ''){
            //     $test = $page - 1;
            //     $data_limit = ($test - 1)  * $no_of_products_per_page;
            // }
    
            // }else{
            //     $data_limit = $lmt;
            // }
            // $data_limit1 = 0;
            // $limit =  $start_limit;
    
            $order_by = '';
            // Datatable Searching
            $search = datatable_searching($search, $columns);
            if ($search) {
                if ($where) {
                    $where .= ' AND ';
                }
                $where .= $search;
            }
            $sql_function = 'SQL_CALC_FOUND_ROWS';
            // $page = 1;
            // echo $_GET['name'];
            // if($page == 1){
    
            $result = $pdo->select($table_details, $where . ' LIMIT ' . $data_limit . ', ' . $no_of_products_per_page);
    
            // }
            // else{
            //     $result = $pdo->select($table_details, $where.' LIMIT '.$data_limit1.', '.$no_of_products_per_page);
            // }
            // print_r($result);   
            // }else{
            //     $result = $pdo->select($table_details, $where.' LIMIT '.$data_limit1.', '.$no_of_products_per_page); 
            // }
    
            //     if($district_name || $taluk_name || $hostel_name || $academic_year == ''){
    
            //         $result = $pdo->select($table_details, $where.' LIMIT '.$data_limit.', '.$no_of_products_per_page);
    //         // print_r($result);
    //     }else{
    //     $result = $pdo->select($table_details, $where.' LIMIT '.$data_limit1.', '.$no_of_products_per_page); 
    // }
    
    
    
    
            // print_r($result);
            $total_records = total_records();
    
            // $page = 1;
    
            $demo = 3;
    
            if ($result->status) {
                $res_array = $result->data;
                // print_r()
    
                if (count($result->data) == '0') {
                    $value['unique_id'] = '<img width="300" height="300" src="../adhmAdmin/assets/images/novideo.jpg">';
                    $data[] = $value['unique_id'];
                } else {
                    $card_content .= "<div id='pagination_data'>";
    
                    $sno = 1;
                    $cards = '';
                    $card_food = '';
    
                    foreach ($res_array as $key => $value) {
                        $value['s_no'] = $sno++;
                        $card_content = '';
                        if ($value['cam_link2'] == '') {
                            $card_content = '<iframe width="100%" height="300" src="https://nallosaims.tn.gov.in:5443/WebRTCApp/play.html?id=' . $value['cam_link1'] . '" frameborder="0" allowfullscreen></iframe>';
                        } else {
                            $card_content = '<div style="display: flex;">'
                                . '<iframe width="50%" height="300" src="https://nallosaims.tn.gov.in:5443/WebRTCApp/play.html?id=' . $value['cam_link1'] . '" frameborder="0" allowfullscreen style="margin-right: 10px;"></iframe>'
                                . '<iframe width="50%" height="300" src="https://nallosaims.tn.gov.in:5443/WebRTCApp/play.html?id=' . $value['cam_link2'] . '" frameborder="0" allowfullscreen></iframe>'
                                . '</div>';
                        }
    
                        $card_header = '<div class="card-header"><b>District Name:</b> ' . $value['district_name'] . ' &nbsp;&nbsp;&nbsp;&nbsp; <b>Taluk Name:</b> ' . $value['taluk_name'] . ' &nbsp;&nbsp;&nbsp;&nbsp; <b>Hostel Name:</b> ' . $value['hostel_name'] . '&nbsp;&nbsp;&nbsp;&nbsp;<b>Hostel ID:</b>' . $value['hostel_id'] . '</div><hr style="border-color: #00000029;">';
                        $cards .= '<div style="width: 100%; margin: 10px; background-color: #e2e2e2;">'
                            . $card_header
                            . '<div class="card-body">'
                            . $card_content
                            . '</div></div>';
                        //     $total_pages = ceil($total_records / $per_page);
                        // $card_food = "<div id='pagination_links'>";
                        // if ($current_page > 1) {
                        //     $card_food .= "<a href='?start=".($current_page - 1) * $per_page."'>Previous</a>";
                        // }
                        // for ($i = 1; $i <= $total_pages; ++$i) {
                        //     if ($i == $current_page) {
                        //         $card_food .=  "<span class='current'>$i</span>";
                        //     } else {
                        //         $card_food .=  "<a href='?start=".($i * $per_page - $per_page)."'>$i</a>";
                        //     }
                        // }
                        // if ($current_page > $total_pages) {
                        //     $card_food .=  "<a href='?start=".($current_page * $per_page)."'>Next</a>";
                        // }
                        // $card_food .=  '</div>';
                    }
                    $card_food .= '<br><br>';
                    // echo $_GET['pagename'];
    
                    // Calculate no of pages
                    $demos = ceil($total_records1 / $demo);
                    // Generate Page Linkz
                    $card_food .= '<div style="text-align: end;padding: 14px;">';
                    for ($i = 1; $i <= $demos; ++$i) {
                        // echo "hello";
                        // $page = $_GET['page'];
                        // Check Active Page
                        // $a = base64_encode($i);
                        $password = '3sc3RLrpd17';
                        $enc_method = 'aes-256-cbc';
                        $enc_password = substr(hash('sha256', $password, true), 0, 32);
                        $enc_iv = 'av3DYGLkwBsErphc';
    
                        // $menu_screen = 'dashboard/form';
                        // $ab = base64_encode(openssl_encrypt($i, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));
                        // $d = base64_encode(openssl_encrypt($district_name, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));
                        // $t = base64_encode(openssl_encrypt($taluk_name, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));
                        // $h = base64_encode(openssl_encrypt($hostel_name, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));
                        // $ac = base64_encode(openssl_encrypt($academic_year, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));
                        $ab = base64_encode(openssl_encrypt($i, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));
                        $d = $district_name;
                        $t = $taluk_name;
                        $h = $hostel_name;
                        $ac = $academic_year;
    
                        if ($page == $i) {
                            $card_food .= "<a href='index.php?file=sG35YsNSNUzbv5pCM7Yx9A&page={$ab}&name={$i}&district_name={$d}&taluk_name={$t}&hostel_name={$h}&academic_year={$ac}&data_limit={$lmt}' class='link active'>{$i}</a>";
                        } else {
                            $card_food .= "<a href='index.php?file=sG35YsNSNUzbv5pCM7Yx9A&page={$ab}&name={$i}&district_name={$d}&taluk_name={$t}&hostel_name={$h}&academic_year={$ac}&data_limit={$lmt}' class='link'>{$i}</a>";
                        }
    
                        // if ($page == $i) {
                        //     $card_food .= "<a href='index.php?file=cctv_live.php?p  age={$ab}&name={$ab}' class='link active'>{$i}</a>";
                        // } else {
                        //     $card_food .= "<a href='index.php?file=cctv_live.php?page={$ab}&name={$ab}' class='link'>{$i}</a>";
                        // }
                    }
                    $card_food .= '</div >';
                    // $cards .= $card_food;
    
                    $card_content .= '</div>';
    
                    // $data[] = '<div style="display: flex; flex-wrap: wrap;"><form method="GET">'.$cards.''.$card_food.'</form></div>';
                    $data[] = '<div style="display: flex; flex-wrap: wrap;"><form method="GET">' . $cards . '' . $card_food . '</form></div>';
                }
                $json_array = [
                    'draw' => intval($draw),
                    'recordsTotal' => intval($total_records),
                    'recordsFiltered' => intval($total_records),
                    'data' => $data,
                    // 'testing' => $result->sql,
                ];
            } else {
                // print_r($result);
            }
            echo json_encode($json_array);
            break;

    case 'delete':
        $unique_id = $_POST['unique_id'];
        $columns = [
            'is_delete' => 1,
        ];
        $update_where = [
            'unique_id' => $unique_id,
        ];
        $action_obj = $pdo->update($table, $columns, $update_where);
        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = '';
            $sql = $action_obj->sql;
            $msg = 'success_delete';
        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = 'error';
        }
        $json_array = [
            'status' => $status,
            'data' => $data,
            'error' => $error,
            'msg' => $msg,
            // 'sql' => $sql,
        ];
        echo json_encode($json_array);
        break;
    case 'district_name':
        $district_name = $_POST['district_name'];
        $district_name_options = taluk_name('', $district_name);
        $taluk_name_options = select_option($district_name_options, 'Select Taluk');
        // print_r($taluk_name_options);die();
        echo $taluk_name_options;
        break;
    case 'get_hostel_by_taluk_name':
        $taluk_name = $_POST['taluk_name'];
        $hostel_name_options = hostel_name('', $taluk_name);
        $hostel_name_options = select_option_host($hostel_name_options, 'Select Hostel');
        echo $hostel_name_options;
        break;
    default:
        break;
}
function show_video($doc_name = '')
{
    $file_names = explode(',', $doc_name);
    //  print_r($file_names);
    $image_view = '';

    if ($doc_name) {
        foreach ($file_names as $file_key => $doc_name) {
            if ($file_key != 0) {
                if ($file_key % 4 != 0) {
                    $image_view .= '&nbsp';
                } else {
                    $image_view .= '<br><br>';
                }
            }

            //    $cfile_name = explode('.',$doc_name);
            // print_r($cfile_name);
            //    if($doc_name){
            //        if(($cfile_name[1]=='jpg')||($cfile_name[1]=='png')||($cfile_name[1]=='jpeg')) {
            $image_view = '<div><iframe width="300" height="300" src="https://rtsp.me/embed/' . $doc_name . '" frameborder="0" allowfullscreen></iframe></div>';
            // $image_view .= '<img src="uploads/'.$folder_name.'/'.$doc_name.'"  height="50px" width="50px" >';
            //        }else if($cfile_name[1]=='pdf'){
            //            $image_view .= '<a href="javascript:print(\'uploads/staff/'.$doc_name.'\')"><img src="uploads/staff/pdf.png"  height="50px" width="50px" ></a>';
            //        }
            //        else if(($cfile_name[1]=='pdf')||($cfile_name[1]=='xls')||($cfile_name[1]=='xlsx')){
            //            $image_view .= '<a href="javascript:print(\'uploads/staff/'.$doc_name.'\')"><img src="uploads/staff/excel.png"  height="50px" width="50px" ></a>';
            //        }
            //    }
        }
    }

    // print_r($image_view);
    return $image_view;
}
