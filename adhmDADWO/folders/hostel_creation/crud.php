<?php

// Get folder Name From Currnent Url
$folder_name = explode('/', $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = 'hostel_name';

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action = $_POST['action'];

$hostel_name = '';
$is_active = '';
$unique_id = '';
$prefix = '';

$data = '';
$msg = '';
$error = '';
$status = '';
$test = ''; // For Developer Testing Purpose

function validateCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
switch ($action) {
    case 'createupdate':
       
        // Sanitize input values - You can replace filter_input with your sanitizeInput function
       
        $vali_sanc_staff_count = isset($_POST['sanc_staff_count']) ? sanitizeInput($_POST['sanc_staff_count']) : null;
      
        $unique_id = isset($_POST['unique_id']) ? sanitizeInput($_POST['unique_id']) : null;
       

        // Prepare SQL statement
        
                // Update existing record
                $sql = 'UPDATE hostel_name SET sanc_staff_count=? WHERE unique_id=?';
                $stmt = $mysqli->prepare($sql);
                if ($stmt === false) {
                    $msg = 'error';
                    $error = $mysqli->error;
                } else {
                    $stmt->bind_param('ss', $vali_sanc_staff_count, $unique_id);
                  
                    $stmt->execute();

                    if ($stmt->error) {
                        $msg = 'error';
                        $error = $stmt->error;
                    } else {
                        $msg = 'update';
                        $status = 'Success'; // Assuming success if no errors
                    }

                    $stmt->close();
                }
          
        // Prepare JSON response
        $json_array = [
            'status' => $status ?? '',
            'error' => $error ?? '',
            'msg' => $msg ?? '',
        ];

        echo json_encode($json_array);

        // Close MySQL connection
        $mysqli->close();

        break;




    case 'hostel_upgrade_datatable':
        // DataTable Variables
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length == '-1' ? '' : $length;

        $data = [];


        $columns = [
            '@a:=@a+1 s_no',
            "go_no",
            "go_attachment",
            'unique_id',
        ];
        $table_upgrade = 'hostel_upgrade';
        $table_details = $table_upgrade . ' , (SELECT @a:= ?) AS a ';
        $where = 'is_delete = 0 and hostel_unique_id = "' . $_POST['hostel_unique_id'] . '"';

        $bind_params = 'i';
        $bind_values = [$start];



        $sql = 'SELECT SQL_CALC_FOUND_ROWS ' . implode(', ', $columns) . ' FROM ' . $table_details . ' WHERE ' . $where;

        if (!empty($limit)) {
            $sql .= ' LIMIT ?, ?';
            $bind_params .= 'ii'; // Add types for integer parameters
            $bind_values[] = intval($start);
            $bind_values[] = intval($length);
        }

        // Prepare the statement
        $stmt = $mysqli->prepare($sql);



        // Bind parameters if there are any
        if (!empty($bind_params)) {
            $stmt->bind_param($bind_params, ...$bind_values);
        }

        // Execute the statement
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch total records count using FOUND_ROWS()
        $total_records_result = $mysqli->query('SELECT FOUND_ROWS() as total');
        $total_records = $total_records_result->fetch_assoc()['total'];

        if ($result) {
            $res_array = $result->fetch_all(MYSQLI_ASSOC);

            foreach ($res_array as $key => $value) {


                $value['go_attachment'] = image_view($value['go_attachment']);

                $btn_delete = btn_delete("hostel_upgrade", $value['unique_id']);

                if ($value['unique_id'] == '5f97fc3257f2525529') {
                    $btn_update = '';
                    $btn_delete = '';
                }

                // $value['unique_id'] = $btn_update . $btn_delete;
                $data[] = array_values($value);
            }

            $json_array = [
                'draw' => intval($draw),
                'recordsTotal' => intval($total_records),
                'recordsFiltered' => intval($total_records),
                'data' => $data,
                // "testing" => $result->sql
            ];
        } else {
            exit('Query execution failed: ' . $mysqli->error);
        }

        echo json_encode($json_array);
        break;

    case 'datatable':
        // DataTable Variables
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length == '-1' ? '' : $length;

        $data = [];

        $columns = [
            '@a:=@a+1 s_no',
            "district_name",
            "taluk_name",
            "hostel_id",
            'hostel_name',
            'go_attach_file',
            'entrance_image',
            'dining_image',
            'building_image',
            'unique_id',
        ];
        $table_details = $table . ' , (SELECT @a:= ?) AS a ';
        $where = 'is_delete = 0 and district_name = "' . $_SESSION['district_id'] . '"';

        $bind_params = 'i';
        $bind_values = [$start];

        if (!empty($_POST['district_name'])) {
            $where .= ' AND district_name = ?';
            $bind_params .= 's';
            $bind_values[] = $_POST['district_name'];
        }

        if (!empty($_POST['taluk_name'])) {
            $where .= ' AND taluk_name = ?';
            $bind_params .= 's';
            $bind_values[] = $_POST['taluk_name'];
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS ' . implode(', ', $columns) . ' FROM ' . $table_details . ' WHERE ' . $where;

        if (!empty($limit)) {
            $sql .= ' LIMIT ?, ?';
            $bind_params .= 'ii'; // Add types for integer parameters
            $bind_values[] = intval($start);
            $bind_values[] = intval($length);
        }

        // Prepare the statement
        $stmt = $mysqli->prepare($sql);

        if ($stmt === false) {
            exit('Error in preparing SQL statement: ' . $mysqli->error);
        }

        // Bind parameters if there are any
        if (!empty($bind_params)) {
            $stmt->bind_param($bind_params, ...$bind_values);
        }

        // Execute the statement
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch total records count using FOUND_ROWS()
        $total_records_result = $mysqli->query('SELECT FOUND_ROWS() as total');
        $total_records = $total_records_result->fetch_assoc()['total'];

        if ($result) {
            $res_array = $result->fetch_all(MYSQLI_ASSOC);

            foreach ($res_array as $key => $value) {

                $value['district_name'] = district_name($value['district_name'])[0]['district_name'];
                $value['taluk_name'] = taluk_name($value['taluk_name'])[0]['taluk_name'];
                $value['hostel_name'] = disname($value['hostel_name']);
                $value['is_active'] = is_active_show($value['is_active']);

                if($value['go_attach_file']){
                    $value['go_attach_file'] = image_view_hostel($value['go_attach_file']);
                }else{
                    $value['go_attach_file'] = '-';
                }

                $value['entrance_image'] = isset($value['entrance_image']) ? image_view_hostel($value['entrance_image']) : '-';
                $value['dining_image'] = isset($value['dining_image']) ? image_view_hostel($value['dining_image']) : '-';
                $value['building_image'] = isset($value['building_image']) ? image_view_hostel($value['building_image']) : '-';

                $btn_update = btn_update($folder_name, $value['unique_id']);
                $btn_delete = btn_delete($folder_name, $value['unique_id']);

                if ($value['unique_id'] == '5f97fc3257f2525529') {
                    $btn_update = '';
                    $btn_delete = '';
                }

                $value['unique_id'] = $btn_update;
                $data[] = array_values($value);
            }

            $json_array = [
                'draw' => intval($draw),
                'recordsTotal' => intval($total_records),
                'recordsFiltered' => intval($total_records),
                'data' => $data,
                // "testing" => $result->sql
            ];
        } else {
            exit('Query execution failed: ' . $mysqli->error);
        }

        echo json_encode($json_array);
        break;


    case 'hostel_upgrade_delete':

        $unique_id = $_POST['unique_id'];
        $hostel_unique_id = $_POST['hostel_unique_id'];

        // Update specific record
        $is_delete = '1';
        $sql = 'UPDATE hostel_upgrade SET is_delete = ? WHERE unique_id = ?';

        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'Error preparing statement: ' . $mysqli->error]);
            exit;
        }

        $stmt->bind_param('ss', $is_delete, $unique_id);

        $action_result = $stmt->execute();

        $sub_cnt = get_sub_cnt($hostel_unique_id);
        if ($sub_cnt == '0') {
            $sql_main = 'UPDATE hostel_name SET hostel_upgrade = "No" WHERE unique_id = "' . $hostel_unique_id . '"';
            $stmt_main = $mysqli->prepare($sql_main);
            $stmt_main->execute();

        }


        $stmt->close();

        if ($action_result) {
            $status = true;
            $msg = 'success_delete';
        } else {
            $status = false;
            $msg = 'error';
        }

        $json_array = [
            'status' => $status,
            'msg' => $msg,
            'sub_cnt' => $sub_cnt,
        ];

        echo json_encode($json_array);

        $mysqli->close();
        break;

    case 'delete':
        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            exit('CSRF validation failed.');
        }
        $unique_id = $_POST['unique_id'];

        // Update specific record
        $is_delete = '1';
        $sql = 'UPDATE hostel_name SET is_delete = ? WHERE unique_id = ?';

        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'Error preparing statement: ' . $mysqli->error]);
            exit;
        }

        $stmt->bind_param('ss', $is_delete, $unique_id);

        $action_result = $stmt->execute();
        $stmt->close();

        if ($action_result) {
            $status = true;
            $msg = 'success_delete';
        } else {
            $status = false;
            $msg = 'error';
        }

        $json_array = [
            'status' => $status,
            'msg' => $msg,
        ];

        echo json_encode($json_array);

        $mysqli->close();
        break;

    case 'get_taluk_name':
        $district_name = $_POST['district_name'];

        $district_options = taluk_name('', $district_name);

        $hostel_taluk_options = select_option($district_options, 'Select Taluk');

        echo $hostel_taluk_options;

        break;

    case 'get_assembly':
        $district_name = $_POST['district_name'];

        $district_options = assembly_constituency('', $district_name);

        $assembly_options = select_option($district_options, 'Select Assembly Constituency');

        echo $assembly_options;

        break;

    case 'get_parliament':
        $district_name = $_POST['district_name'];

        $district_options = parliment_constituency('', $district_name);

        $parliament_options = select_option($district_options, 'Select Parliament Constituency');

        echo $parliament_options;

        break;

    case 'get_block':
        $district_name = $_POST['district_name'];

        $district_options = block('', $district_name);

        $block_options = select_option($district_options, 'Select Block Name');

        echo $block_options;

        break;

    case 'get_village':
        $block_name = $_POST['block_name'];

        $block_options = village_name('', $block_name);

        $village_options = select_option($block_options, 'Select Village Name');

        echo $village_options;

        break;

    case 'get_corporation':
        $district_name = $_POST['district_name'];

        $district_options = corporation('', $district_name);

        $corporation_options = select_option($district_options, 'Select Corporation Name');

        echo $corporation_options;

        break;

    case 'get_municipality':
        $district_name = $_POST['district_name'];

        $district_options = municipality('', $district_name);

        $municipality_options = select_option($district_options, 'Select Municipality Name');

        echo $municipality_options;

        break;

    case 'get_town_panchayat':
        $district_name = $_POST['district_name'];

        $district_options = town_panchayat('', $district_name);

        $town_panchayat_options = select_option($district_options, 'Select Town Panchayat Name');

        echo $town_panchayat_options;

        break;

    case 'district_id':
        $district_id = $_POST['district_id'];

        $district_id_options = taluk_name('', $district_id);

        $taluk_name_options = select_option($district_id_options, 'Select Taluk');

        echo $taluk_name_options;
        // print_r($taluk_name_options);

        break;


    case "update_dev_reg":

        $hostel_unique_id = $_POST['hostel_unique_id'];
        $sql = "update hostel_name set dev_reg = 1 where unique_id = ?";
        $stmt = $mysqli->prepare($sql);

        if ($stmt === false) {
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'Error preparing statement: ' . $mysqli->error]);
            exit;
        }

        $stmt->bind_param("s", $hostel_unique_id);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            $status = true;
            $msg = 'update';
        } else {
            $status = false;
            $msg = 'error';
        }

        $json_array = [
            'status' => $status,
            'msg' => $msg,
        ];

        echo json_encode($json_array);
        $mysqli->close();


        break;

    case 'store_staff_count':

        // Sanitize input values - You can replace filter_input with your sanitizeInput function
        $vali_warden_cnt    = !empty($_POST['warden_cnt'])    ? $_POST['warden_cnt']    : 0;
        $vali_cook_cnt      = !empty($_POST['cook_cnt'])      ? $_POST['cook_cnt']      : 0;
        $vali_sweeper_cnt   = !empty($_POST['sweeper_cnt'])   ? $_POST['sweeper_cnt']   : 0;
        $vali_watchman_cnt  = !empty($_POST['watchman_cnt'])  ? $_POST['watchman_cnt']  : 0;
        $vali_helper_cnt    = !empty($_POST['helper_cnt'])    ? $_POST['helper_cnt']    : 0;


        $hostel_unique_id = $_POST['unique_id'];


        $total_staff_count = $vali_warden_cnt + $vali_cook_cnt + $vali_sweeper_cnt + $vali_watchman_cnt + $vali_helper_cnt;

        $sql = 'UPDATE hostel_name_1 SET warden_cnt=?,cook_cnt=?,sweeper_cnt=?,watchman_cnt=?,helper_cnt=?,sanc_staff_count=? WHERE unique_id=?';

        $stmt = $mysqli->prepare($sql);

        if ($stmt === false) {
            $msg = 'error';

            $error = $mysqli->error;
        } else {
            $stmt->bind_param('iiiiiis', $vali_warden_cnt, $vali_cook_cnt, $vali_sweeper_cnt, $vali_watchman_cnt, $vali_helper_cnt, $total_staff_count, $hostel_unique_id);
            $stmt->execute();

            if ($stmt->error) {
                $msg = 'error';
                $error = $stmt->error;
            } else {
                $msg = 'create';
                $status = 'Success'; // Assuming success if no errors
            }

            $stmt->close();
        }



        // Prepare JSON response
        $json_array = [
            'status' => $status ?? '',
            'error' => $error ?? '',
            'msg' => $msg ?? '',
            "total_staff_count" => $total_staff_count
        ];

        echo json_encode($json_array);

        // Close MySQL connection
        $mysqli->close();

        break;


    default:
        break;
}

function get_sub_cnt($unique_id = "")
{
    global $pdo;

    $table_name = "hostel_upgrade";
    $where = 'hostel_unique_id = "' . $unique_id . '" and is_delete = "0"';

    // if ($unique_id) {
    //     $where["hostel_1"] = $unique_id; // Corrected the way unique_id is added to the where clause
    // }

    $table_columns = [
        "count(id) as tot_cnt",
    ];

    $table_details = [
        $table_name,
        $table_columns,
    ];

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

function image_view($doc_file_name = "")
{
    $image_view = "";

    $cfile_name = explode('.', $doc_file_name);

    if ($doc_file_name) {

        if (($cfile_name[1] == 'jpg') || ($cfile_name[1] == 'png') || ($cfile_name[1] == 'jpeg') || ($cfile_name[1] == 'PNG')) {
            // echo "dd";
            $image_view .= '<center><a href="javascript:print_view(\'/' . $doc_file_name . '\')"><img src="../adhmAdmin/uploads/hostel_upgrade_docs/' . $doc_file_name . '"  width="20%" ></a></center>';
            // $image_view .= '<img src="uploads/'.$folder_name.'/'.$doc_name.'"  height="50px" width="50px" >';
        } else if ($cfile_name[1] == 'pdf') {
            $image_view .= '<center><a href="javascript:print_pdf(\'/' . $doc_file_name . '\')"><img src="../assets/images/pdf.png"    style="margin-left: 15px;  width:35px; height:40px;" ></a></center>';
        }
        // else if (($cfile_name[1] == 'pdf') || ($cfile_name[1] == 'xls') || ($cfile_name[1] == 'xlsx')) {
        //     $image_view .= '<a href="javascript:print(\'/' . $doc_file_name . '\')"><img src="uploads/excel.png"  height="30px" width="30px" ></a>';
        // } else if (($cfile_name[1] == 'txt') || ($cfile_name[1] == 'docx') || ($cfile_name[1] == 'doc')) {
        //     $image_view .= '<a href="javascript:print(\'/' . $doc_file_name . '\')"><img src="uploads/word.png"  height="30px" width="30px" ></a>';
        // }
    }
    return $image_view;
}

function image_view_hostel($doc_file_name = "")
{
    $image_view = "";

            $cfile_name = explode('.', $doc_file_name);

            if ($doc_file_name) {

                if (($cfile_name[1] == 'jpg') || ($cfile_name[1] == 'png') || ($cfile_name[1] == 'jpeg') || ($cfile_name[1] == 'PNG')) {
                    // echo "dd";
                    $image_view .= '<a href="javascript:print_view_image(\'/' . $doc_file_name . '\')"><img src="../adhmHostel/uploads/hostel_creation/' . $doc_file_name . '" style="width:50px; height:50px;" ></a>';
                    // $image_view .= '<img src="uploads/'.$folder_name.'/'.$doc_name.'"  height="50px" width="50px" >';
                } else if ($cfile_name[1] == 'pdf') {
                    $image_view .= '<a href="javascript:print_pdf_go(\'/' . $doc_file_name . '\')"><img src="../assets/images/pdf.png" style="margin-left: 15px; width:35px; height:40px;" ></a>';
                } 
               
            }
            return $image_view;
        }

