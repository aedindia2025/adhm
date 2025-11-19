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

    case 'get_video':
        $per_page = 10; // Records per page

        $current_page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int) $_GET['page'] : 1;

        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $data = [];

        // Query Variables
        $district_name = $_POST['district_name'];
        $taluk_name = $_POST['taluk_name'];
        $hostel_name = $_POST['hostel_name'];
        $academic_year = $_POST['academic_year'];

        $page = 1;

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
            $table,
            $columns,
        ];
        $where = 'is_delete = 0';
        if ($_POST['district_name']) {
            $where .= " And district_name = '$district_name'";

        }
        if ($_POST['taluk_name']) {
            $where .= " And taluk_name = '$taluk_name'";

        }

        if ($_POST['hostel_name']) {
            $where .= " And hostel_name = '$hostel_name'";
        }

        if ($_POST['academic_year']) {
            $where .= " And academic_year = '$academic_year'";

        }

        $results = $pdo->select($table_details, $where);
        $total_records1 = total_records();
        
        // No of Products per Page
        $no_of_products_per_page = 3;

        // Calculate no of pages
        $no_of_pages = ceil($total_records1 / $no_of_products_per_page);

        if ($_POST['get_val']) {
            $page = $_POST['get_val'];
        }

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

        $order_by = '';
        // Datatable Searching
       
        $sql_function = 'SQL_CALC_FOUND_ROWS';

        $result = $pdo->select($table_details, $where . ' LIMIT ' . $data_limit . ', ' . $no_of_products_per_page);

        // print_r($result);
        $total_records = total_records();

        $demo = 3;

        if ($result->status) {
            $res_array = $result->data;
            // print_r()

            if (count($result->data) == '0') {
                $value['unique_id'] = '<img width="300" height="300" src="../adhmAdmin/uploads/novideo.jpg">';
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
                        $card_content = '<iframe width="100%" height="300" src="http://125.17.238.158:5080/LiveApp/play.html?id=' . $value['cam_link1'] . '" frameborder="0" allowfullscreen></iframe>';
                    } else {
                        $card_content = '<div style="display: flex;">'
                            . '<iframe width="50%" height="300" src="http://125.17.238.158:5080/LiveApp/play.html?id=' . $value['cam_link1'] . '" frameborder="0" allowfullscreen style="margin-right: 10px;"></iframe>'
                            . '<iframe width="50%" height="300" src="http://125.17.238.158:5080/LiveApp/play.html?id=' . $value['cam_link2'] . '" frameborder="0" allowfullscreen></iframe>'
                            . '</div>';
                    }

                    $card_header = '<div class="card-header"><b>District Name:</b> ' . $value['district_name'] . ' &nbsp;&nbsp;&nbsp;&nbsp; <b>Taluk Name:</b> ' . $value['taluk_name'] . ' &nbsp;&nbsp;&nbsp;&nbsp; <b>Hostel Name:</b> ' . $value['hostel_name'] . '&nbsp;&nbsp;&nbsp;&nbsp;<b>Hostel ID:</b>' . $value['hostel_id'] . '</div><hr style="border-color: #00000029;">';
                    $cards .= '<div style="width: 100%; margin: 10px; background-color: #e2e2e2;">'
                        . $card_header
                        . '<div class="card-body">'
                        . $card_content
                        . '</div></div>';
                }
                $card_food .= '<br><br>';

                // Calculate no of pages
                $demos = ceil($total_records1 / $demo);
                // Generate Page Linkz
                $card_food .= '<div style="text-align: end;padding: 14px;">';
                for ($i = 1; $i <= $demos; ++$i) {

                    $password = '3sc3RLrpd17';
                    $enc_method = 'aes-256-cbc';
                    $enc_password = substr(hash('sha256', $password, true), 0, 32);
                    $enc_iv = 'av3DYGLkwBsErphc';

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

                }
                $card_food .= '</div >';

                $card_content .= '</div>';

                $data[] = '<div style="display: flex; flex-wrap: wrap;"><form method="GET">' . $cards . '' . $card_food . '</form></div>';
            }
            $json_array = [
                'draw' => intval($draw),
                'recordsTotal' => intval($total_records),
                'recordsFiltered' => intval($total_records),
                'data' => $data,
            ];
        } else {
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

            $image_view = '<div><iframe width="300" height="300" src="https://rtsp.me/embed/' . $doc_name . '" frameborder="0" allowfullscreen></iframe></div>';

        }
    }

    // print_r($image_view);
    return $image_view;
}
