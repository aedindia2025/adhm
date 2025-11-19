<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table_std_app = "std_app_p1";
$table = "holiday_creation";
$table_sub = "complaint_creation_doc_upload";
$table_stage_1 = "stage_1";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';
include 'function.php';

// // Variables Declaration
$action = $_POST['action'];


$academic_year = $_SESSION['academic_year'];
$hostel_unique_id = $_SESSION['hostel_id'];




//$user_type          = "";
$is_active = "";
$unique_id = "";
$prefix = "";

$data = "";
$msg = "";
$error = "";
$status = "";
$test = ""; // For Developer Testing Purpose

switch ($action) {
    // case 'holiday_details':

    //     $json_array     = "";
    //     $today  =  date('Y-m-d');
    //     $columns        = [           
    //         "date",
    //         "holiday"
    //        ];
    //     $table_details  = [
    //         $table,
    //         $columns
    //     ];
    //     $where        = "is_delete = 0 AND ";
    //     $order_by       = "date ASC";

    //     $sql_function   = "SQL_CALC_FOUND_ROWS";


    //     $result         = $pdo->select($table_details,$where,$limit,$start,$order_by,$sql_function);

    //     $res_array      = $result->data;

    //     $json_array = [
    //             "holiday_details"       	=> $res_array        
    //         ];

    //      echo json_encode($json_array);

    // break;  

    case 'get_application_count':

        $json_array = "";
        $columns = [
            "(select COALESCE(count(unique_id),0) from std_app_s where is_delete='0' and hostel_1 = '" . $_SESSION['hostel_id'] . "') as applied_cnt",
            "(select COALESCE(count(unique_id),0) from batch_creation where is_delete = 0 and batch_no != '' and hostel_name = '" . $_SESSION['hostel_id'] . "') as accp_cnt",
            "(select COALESCE(count(unique_id),0) from std_app_s where is_delete = 0 and status = '1' and hostel_1 = '" . $_SESSION['hostel_id'] . "') as approved_cnt",
            "(select COALESCE(count(unique_id),0) from std_app_s where is_delete = 0 and status = '2' and hostel_1 = '" . $_SESSION['hostel_id'] . "') as rejected_cnt",
            // "(select COUNT(id) where stage_1_status = 3 and is_delete = 0) as cancel_comp",
        ];
        $table_details = [
            "std_app_s",
            $columns
        ];
        $where = "hostel_1 = '" . $_SESSION['hostel_id'] . "'";
        $result = $pdo->select($table_details, $where);
        $res_array = $result->data;
        //    print_r($result);
        foreach ($res_array as $value) {
            if (empty($value['applied_cnt'])) {

                $applied_cnt = '0';
            } else {

                $applied_cnt = $value['applied_cnt'];
            }
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


    case 'get_vacancy_count':

        $json_array = "";
        $columns = [
            "ifnull((select sanctioned_strength from hostel_name where unique_id = '" . $_SESSION['hostel_id'] . "'),0) as tot_cap",
            "'' as old_std",
            "count(id) as approved_cnt",
            // "'' as hos_vacancy",
            // "(select COUNT(id) where stage_1_status = 3 and is_delete = 0) as cancel_comp",
        ];
        $table_details = [
            "std_reg_s",
            $columns
        ];
        $where = "hostel_1 = '" . $_SESSION['hostel_id'] . "' and status = '1' and dropout_status = '1'";
        $result = $pdo->select($table_details, $where);
        $res_array = $result->data;
        // print_r($res_array);
        foreach ($res_array as $value) {

            if ($value['tot_cap'] == '') {
                $value['tot_cap'] = '0';
            }

            $tot_cap = $value['tot_cap'];

            // $accp_cnt       = $value['old_std'];
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




    // case "department_details":
    //     $table_data = ' <table class="table table-hover table-centered mb-0 spel-table">
    //                         <thead>
    //                             <tr>
    //                                 <th></th>
    //                                 <th>Department</th>
    //                                 <th>Task</th>
    //                                 <th>Percentage</th>
    //                                 <th>Overdue</th>
    //                             </tr>
    //                         </thead>
    //                         <tbody>';

    //                          $table_data .=     '<tr>
    //                                 <td><i class="mdi mdi-circle-double text-info me-1"></i></td>
    //                                 <td>
    //                                     <h5>Account Department</h5>Kumar
    //                                 </td>
    //                                 <td>0/25</td>
    //                                 <td>
    //                                     <div class="progress" style="height: 6px;">
    //                                         <div class="progress-bar bg-info" role="progressbar" style="width: 55%;" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
    //                                     </div>
    //                                 </td>
    //                                 <td>18</td>
    //                             </tr>';



    //                        $table_data .=   '</tbody>
    //                     </table>';

    //      $json_array = [
    //         'data'            => $table_data,
    //     ];



    //     echo json_encode($json_array);
    //     break;

    case "department_details":
        $month = $_POST['month'];
        $columns = [
            "department_name",
            "assign_by",
            "count(complaint_no) as total_complaints",
            // "(select count(complaint_no) from complaint_creation where stage_1_status = '2' GROUP BY department_name) as comp_complaint",
        ];

        $table_details = [
            'complaint_creation',
            $columns
        ];

        $where = "is_delete = 0 and 	entry_date like'%" . $month . "%' GROUP BY department_name ";

        $result = $pdo->select($table_details, $where);
        // print_r($result);

        if ($result->status) {

            $res_array = $result->data;


            $table_data .= ' <table class="table table-hover table-centered mb-0 spel-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Department</th>
                                    <th>Task</th>
                                    <th>Percentage</th>
                                    <th>Overdue</th>
                                </tr>
                            </thead>
                            <tbody>';

            if ($res_array) {

                $i = 0;

                foreach ($res_array as $key => $value) {

                    $department_name = $value['department_name'];
                    $assign = $value['assign_by'];
                    // $dept_name = department_type($value['department_name'])[0]['department_type'];
                    // $assign_by = disname(user_name($value['assign_by'])[0]['user_name']);

                    $pending_details = get_pending_details($department_name, $assign);



                    $over_due = get_overdue_cnt($department_name, $assign);

                    if ($pending_details == '') {
                        $pending_details = 0;
                    }
                    if ($over_due == '') {
                        $over_due = 0;
                    }
                    $progress_bar_per = (($pending_details / $value['total_complaints']) * 100);


                    $table_data .= '<tr>
                                    <td><i class="mdi mdi-circle-double text-info me-1"></i></td>
                                    <td>
                                        <h5>' . $dept_name . '</h5>' . $assign_by . '
                                    </td>
                                    <td>' . $pending_details . '/' . $value['total_complaints'] . '</td>

                                    <td>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: ' . $progress_bar_per . '%;" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>
                                    <td>' . $over_due . '</td>
                                </tr>';
                }
            }
        }
        $table_data .= '</tbody>
                        </table>';

        $json_array = [
            'data' => $table_data,

        ];



        echo json_encode($json_array);
        break;

    case "top_most_completed":

        $date = $_POST['month'];
        $columns = [
            //"department_name",
            "assign_by",
            "cnt",

        ];

        $table_details = [
            'view_assign_by_completed_cnt',
            $columns
        ];

        $where = "assign_by != '' and entry_month = '" . $date . "'  order by cnt DESC LIMIT 5";

        $result = $pdo->select($table_details, $where);
        //print_r($result);

        if ($result->status) {

            $res_array = $result->data;


            $table_data .= ' <table class="table table-hover table-centered mb-0">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Name</th>
                                        <th>Count</th>
                                    </tr>
                                </thead>
                                <tbody>';

            if ($res_array) {

                $i = 1;

                foreach ($res_array as $key => $value) {

                    $department_name = $value['department_name'];
                    $assign = $value['assign_by'];
                    $department = get_department($value['assign_by']);
                    $count = $value['cnt'];
                    // $dept_name = department_type($value['department_name'])[0]['department_type'];
                    // $assign_by = disname(user_name($value['assign_by'])[0]['user_name']);


                    $table_data .= '<tr>
                                        <td>' . $i++ . '</td>
                                        <td>
                                            <p class="paara">' . $assign_by . '</p><span class="light">(' . $department . ')</span>
                                        </td>
                                        <td class="bold">' . $count . '</td>
                                    </tr>';
                }
            }
        }
        $table_data .= '</tbody>
                            </table>';

        $json_array = [
            'data' => $table_data,

        ];



        echo json_encode($json_array);
        break;


    case "top_most_complaints":
        $date = $_POST['month'];
        $columns = [
            //"department_name",
            "assign_by",
            "cnt",
            //"count(complaint_no) as total_complaints",
            // "(select count(complaint_no) from complaint_creation where stage_1_status = '2' GROUP BY department_name) as comp_complaint",
        ];

        $table_details = [
            'view_asign_by_mnth_cnt',
            $columns
        ];

        $where = "assign_by != '' and entry_month = '" . $date . "'  order by cnt DESC LIMIT 5";

        $result = $pdo->select($table_details, $where);
        // print_r($result);

        if ($result->status) {

            $res_array = $result->data;


            $table_data .= ' <table class="table table-hover table-centered mb-0">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Name</th>
                                            <th>Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

            if ($res_array) {

                $i = 1;

                foreach ($res_array as $key => $value) {

                    $department_name = $value['department_name'];
                    $assign = $value['assign_by'];
                    $count = $value['cnt'];
                    $department = get_department($value['assign_by']);
                    // $dept_name = department_type($value['department_name'])[0]['department_type'];
                    // $assign_by = disname(user_name($value['assign_by'])[0]['user_name']);

                    $table_data .= '<tr>
                                            <td>' . $i++ . '</td>
                                            <td>
                                                <p class="paara">' . $assign_by . '</p><span class="light">(' . $department . ')</span>
                                            </td>
                                            <td class="bold">' . $count . '</td>
                                        </tr>';
                }
            }
        }
        $table_data .= '</tbody>
                                </table>';

        $json_array = [
            'data' => $table_data,

        ];



        echo json_encode($json_array);
        break;


    case 'holiday_details':

        $table = "holiday_creation";

        $json_array = "";
        $today = date('Y-m-d');
        $columns = [
            "date",
            "holiday"
        ];
        $table_details = [
            $table,
            $columns
        ];
        $where = "is_delete = 0";
        $order_by = "date ASC";

        $sql_function = "SQL_CALC_FOUND_ROWS";


        $result = $pdo->select($table_details, $where, $limit, $start, $order_by, $sql_function);

        if ($result->status) {

            $res_array = $result->data;

            foreach ($res_array as $key => $value) {

                $date = $value['date'];
                $holiday = $value['holiday'];
            }
            $json_array = [
                "holiday_details" => $res_array,
                "date" => $date,
                "holiday" => $holiday
            ];

            echo json_encode($json_array);
        }
        break;


    case 'notification_details':

        $table_1 = "notification";

        $json_array = "";
        // $today  =  date('Y-m-d');

        $columns_1 = [
            "date",
            "title",
            "content",
            // "actions"
        ];
        $table_details_1 = [
            $table_1,
            $columns_1
        ];

        $where = "is_delete = 0";

        if ($actions) {
            $where .= "AND actions =65589f69ce65d32654";
        }


        // $where .= " AND user_type
        $order_by = "date ASC";

        $sql_function = "SQL_CALC_FOUND_ROWS";




        $result = $pdo->select($table_details_1, $where, $limit, $start, $order_by, $sql_function);



        // print_r($result);die();


        $res_array = $result->data;

        $json_array = [

            "notification_details" => $res_array
        ];

        echo json_encode($json_array);

        break;



    case 'applied_leave_details':

        $table_leave = "leave_application";

        $hostel_name = $_SESSION['hostel_id'];

        $json_array = "";
        
        $columns_leave = [

            "count(student_name)as student_name",
          
        ];

        $table_details_leave = [
            $table_leave,
            $columns_leave,
        ];

        $where = "is_delete = 0  AND hostel_name = '" . $hostel_name . "' ";
        $sql_function = "SQL_CALC_FOUND_ROWS";
        $result = $pdo->select($table_details_leave, $where);
        // print_r($result);
        $total_records = total_records();

        if ($result->status) {

            $res_array = $result->data;

            foreach ($res_array as $key => $value) {

                // $from_date = $value['from_date'];
                $student_name = $value['student_name'];

                $status_color = '';

            }
            $json_array = [
                "applied_leave_details" => $res_array, 
                "student_name" => $student_name,
                "data" => $data
                
            ];

            echo json_encode($json_array);
        }


        break;

    case 'get_attendance_details':

        $cur_date = date('Y-m-d');

        $json_array = "";
        $columns = [
            "(select count(id) from std_reg_s where is_delete = 0 and dropout_status != 2 and hostel_1 = '" . $_SESSION['hostel_id'] . "') as total_strength",
            "count(id) as present",
            // "(select count(id) from std_app_s where is_delete = 0 and status = '1' and hostel_1 = '".$_SESSION['hostel_id']."') as approved_cnt",

        ];
        $table_details = [
            "dayattreport",
            $columns
        ];

        $total_strength = 0;
        $present = 0;
        $absent = 0;
        $where = "hostel_unique_id= '" . $_SESSION["hostel_id"] . "' and (punch_mrg IS NOT NULL or punch_eve IS NOT NULL) and currentDate = '" . $cur_date . "'";
        $result = $pdo->select($table_details, $where);
        $res_array = $result->data;
        //    print_r($result);
        foreach ($res_array as $value) {

            $total_strength = $value['total_strength'];
            $present = $value['present'];
            $absent = $total_strength - $present;
        }

        $json_array = [
            "total_strength" => $total_strength,
            "present" => $present,
            "absent" => $absent,

        ];

        echo json_encode($json_array);

        break;




    case 'zone_wise_map':
        $zone = [];
        $today_cnt = [];

        $cummulative_cnt = [];

        $entry_date = $_POST['month'];

        //print_r($newdate);

        $json_array = "";


        $columns = [
            "entry_date",
            "zone_1",
            "zone_2",
            "zone_3",
            "zone_4",


        ];
        $table_details = [
            "view_all_zone_complaints",
            $columns
        ];
        $where = "  month = '" . $entry_date . "' group by entry_date order by entry_date ASC";

        $result = $pdo->select($table_details, $where);
        //print_r($result);
        $res_array = $result->data;
        foreach ($res_array as $value) {
            if ($value['zone_1'] == '') {
                $value['zone_1'] = '0';
            }
            if ($value['zone_2'] == '') {
                $value['zone_2'] = '0';
            }
            if ($value['zone_3'] == '') {
                $value['zone_3'] = '0';
            }
            if ($value['zone_4'] == '') {
                $value['zone_4'] = '0';
            }
            $date[] = date('d-M', strtotime($value['entry_date']));
            $today_cnt[] = $value['total_cnt'];

            $zone_1[] = $value['zone_1'];
            $zone_2[] = $value['zone_2'];
            $zone_3[] = $value['zone_3'];
            $zone_4[] = $value['zone_4'];
        }



        $json_array = [
            "date" => $date,
            "zone_1" => $zone_1,
            "zone_2" => $zone_2,
            "zone_3" => $zone_3,
            "zone_4" => $zone_4,
        ];

        echo json_encode($json_array);
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

    case 'get_special_tahsildar':

        $district_name = $_POST['district_name'];

        $district_options = special_tahsildar('', $district_name);

        $hostel_special_tahsildar_options = select_option($district_options, 'Select Special Tahsildar');

        echo $hostel_special_tahsildar_options;

        break;


    case 'hostel_update':

        $vali_special_tahsildar = isset($_POST['special_tahsildar']) ? sanitizeInput($_POST['special_tahsildar']) : null;
        $vali_assembly_const = isset($_POST['assembly_const']) ? sanitizeInput($_POST['assembly_const']) : null;
        $vali_parliment_const = isset($_POST['parliment_const']) ? sanitizeInput($_POST['parliment_const']) : null;
        $vali_address = isset($_POST['address']) ? sanitizeInput($_POST['address']) : null;
        $vali_hostel_location = isset($_POST['hostel_location']) ? sanitizeInput($_POST['hostel_location']) : null;
        $vali_urban_type = isset($_POST['urban_type']) ? sanitizeInput($_POST['urban_type']) : null;
        $vali_corporation = isset($_POST['corporation']) ? sanitizeInput($_POST['corporation']) : null;
        $vali_municipality = isset($_POST['municipality']) ? sanitizeInput($_POST['municipality']) : null;
        $vali_town_panchayat = isset($_POST['town_panchayat']) ? sanitizeInput($_POST['town_panchayat']) : null;
        $vali_block_name = isset($_POST['block_name']) ? sanitizeInput($_POST['block_name']) : null;
        $vali_village_name = isset($_POST['village_name']) ? sanitizeInput($_POST['village_name']) : null;
        $vali_yob = isset($_POST['yob']) ? sanitizeInput($_POST['yob']) : null;
        $vali_distance_btw_phc = isset($_POST['distance_btw_phc']) ? sanitizeInput($_POST['distance_btw_phc']) : null;
        $vali_phc_name = isset($_POST['phc_name']) ? sanitizeInput($_POST['phc_name']) : null;
        $vali_distance_btw_ps = isset($_POST['distance_btw_ps']) ? sanitizeInput($_POST['distance_btw_ps']) : null;
        $vali_staff_count = isset($_POST['staff_count']) ? sanitizeInput($_POST['staff_count']) : null;
        $vali_ps_name = isset($_POST['ps_name']) ? sanitizeInput($_POST['ps_name']) : null;
        $vali_ownership = isset($_POST['ownership']) ? sanitizeInput($_POST['ownership']) : null;
        $vali_building_status = isset($_POST['building_status']) ? sanitizeInput($_POST['building_status']) : null;
        $vali_rental_reason = isset($_POST['rental_reason']) ? sanitizeInput($_POST['rental_reason']) : null;
        $unique_id = isset($_POST['unique_id']) ? sanitizeInput($_POST['unique_id']) : null;
        $latitude = isset($_POST['latitude']) ? sanitizeInput($_POST['latitude']) : null;
        $longitude = isset($_POST['longitude']) ? sanitizeInput($_POST['longitude']) : null;
        $address = isset($_POST['address']) ? sanitizeInput($_POST['address']) : null;


        $allowedExts = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'xlsx', 'xls'];

        // Handle file upload
        $go_attach_org_name = '';
        $go_attach_file = '';

        if (isset($_FILES['test_file']) && $_FILES['test_file']['error'] === UPLOAD_ERR_OK) {
            $extension = pathinfo($_FILES['test_file']['name'], PATHINFO_EXTENSION);

            if (in_array($extension, $allowedExts)) {
                $tem_name = random_strings(25) . '.' . $extension;
                move_uploaded_file($_FILES['test_file']['tmp_name'], '../../uploads/hostel_creation/' . $tem_name);
                $go_attach_file = $tem_name;
                $go_attach_org_name = $_FILES['test_file']['name'];
            } else {
                exit(json_encode(['status' => 'error', 'msg' => 'File type not allowed.']));
            }
        }

        if (isset($_POST['entrance_image']) && !empty($_POST['entrance_image'])) {
            $data = $_POST['entrance_image'];

            // Remove the prefix 'data:image/png;base64,'
            $data = str_replace('data:image/png;base64,', '', $data);
            $data = str_replace(' ', '+', $data);
            $decodedData = base64_decode($data);

            if ($decodedData === false) {
                echo "Failed to decode base64 data.";
                die();
            }

            $entrance_image = random_strings(25) . '.png';

            $uploadDir = '../../uploads/hostel_creation/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $filePath = '../../uploads/hostel_creation/' . $entrance_image;

            // Save the file
            if (file_put_contents($filePath, $decodedData)) {
                // echo "Image saved successfully at $filePath";

                // Optionally store $filePath in DB
            } else {
                echo "Failed to save image.";
            }
        } else {
            echo "No image captured";
        }

        if (isset($_POST['dining_image']) && !empty($_POST['dining_image'])) {
            $data = $_POST['dining_image'];

            // Remove the prefix 'data:image/png;base64,'
            $data = str_replace('data:image/png;base64,', '', $data);
            $data = str_replace(' ', '+', $data);
            $decodedData = base64_decode($data);

            if ($decodedData === false) {
                echo "Failed to decode base64 data.";
                die();
            }

            $dining_image = random_strings(25) . '.png';

            $uploadDir = '../../uploads/hostel_creation/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $filePath = '../../uploads/hostel_creation/' . $dining_image;


            // Save the file
            if (file_put_contents($filePath, $decodedData)) {
                // echo "Image saved successfully at $filePath";

                // Optionally store $filePath in DB
            } else {
                echo "Failed to save image.";
            }
        } else {
            echo "No image captured";
        }

        if (isset($_POST['building_image']) && !empty($_POST['building_image'])) {
            $data = $_POST['building_image'];

            // Remove the prefix 'data:image/png;base64,'
            $data = str_replace('data:image/png;base64,', '', $data);
            $data = str_replace(' ', '+', $data);
            $decodedData = base64_decode($data);

            // if ($decodedData === false) {
            //     echo "Failed to decode base64 data.";
            //     die();
            // }

            $building_image = random_strings(25) . '.png';

            $uploadDir = '../../uploads/hostel_creation/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $filePath = '../../uploads/hostel_creation/' . $building_image;

            // Save the file
            if (file_put_contents($filePath, $decodedData)) {
                // echo "Image saved successfully at $filePath";

            } else {
                echo "Failed to save image.";
            }
        } else {
            echo "No image captured";
        }


        if (!$unique_id) {
            $msg = 'form_alert';
            $status = 'Error';
            $error = 'Unique ID is missing.';
        } else {
            if ($go_attach_file) {
                $sql = 'UPDATE hostel_name
                            SET special_tahsildar=?, assembly_const=?, parliment_const=?, hostel_location=?, 
                                urban_type=?, corporation=?, municipality=?, town_panchayat=?, block_name=?, 
                                village_name=?, yob=?, distance_btw_phc=?, phc_name=?, distance_btw_ps=?, ps_name=?, 
                                staff_count=?, latitude=?, longitude=?, entrance_image=?, dining_image=?, building_image=?, go_attach_org_name=?, go_attach_file=?, address=?
                            WHERE unique_id=?';

                $stmt = $mysqli->prepare($sql);

                if ($stmt === false) {
                    $msg = 'error';
                    $error = $mysqli->error;
                } else {
                    $stmt->bind_param(
                        'sssssssssssssssssssssssss',
                        $vali_special_tahsildar,
                        $vali_assembly_const,
                        $vali_parliment_const,
                        $vali_hostel_location,
                        $vali_urban_type,
                        $vali_corporation,
                        $vali_municipality,
                        $vali_town_panchayat,
                        $vali_block_name,
                        $vali_village_name,
                        $vali_yob,
                        $vali_distance_btw_phc,
                        $vali_phc_name,
                        $vali_distance_btw_ps,
                        $vali_ps_name,
                        $vali_staff_count,
                        $latitude,
                        $longitude,
                        $entrance_image,
                        $dining_image,
                        $building_image,
                        $go_attach_org_name,
                        $go_attach_file,
                        $address,
                        $unique_id
                    );

                    $stmt->execute();

                    if ($stmt->error) {
                        $msg = 'error';
                        $error = $stmt->error;
                    } else {
                        $msg = 'update';
                        $status = 'Success';
                    }

                    $stmt->close();
                }
                // }
            } else {
                $sql = 'UPDATE hostel_name
                            SET special_tahsildar=?, assembly_const=?, parliment_const=?, hostel_location=?, 
                                urban_type=?, corporation=?, municipality=?, town_panchayat=?, block_name=?, 
                                village_name=?, yob=?, distance_btw_phc=?, phc_name=?, distance_btw_ps=?, ps_name=?, 
                                staff_count=?, latitude=?, longitude=?, entrance_image=?, dining_image=?, building_image=?, address=?
                            WHERE unique_id=?';

                $stmt = $mysqli->prepare($sql);

                if ($stmt === false) {
                    $msg = 'error';
                    $error = $mysqli->error;
                } else {
                    $stmt->bind_param(
                        'sssssssssssssssssssssss',
                        $vali_special_tahsildar,
                        $vali_assembly_const,
                        $vali_parliment_const,
                        $vali_hostel_location,
                        $vali_urban_type,
                        $vali_corporation,
                        $vali_municipality,
                        $vali_town_panchayat,
                        $vali_block_name,
                        $vali_village_name,
                        $vali_yob,
                        $vali_distance_btw_phc,
                        $vali_phc_name,
                        $vali_distance_btw_ps,
                        $vali_ps_name,
                        $vali_staff_count,
                        $latitude,
                        $longitude,
                        $entrance_image,
                        $dining_image,
                        $building_image,
                        $address,
                        $unique_id
                    );

                    $stmt->execute();

                    if ($stmt->error) {
                        $msg = 'error';
                        $error = $stmt->error;
                    } else {
                        $msg = 'update';
                        $status = 'Success';
                    }

                    $stmt->close();
                }
            }





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

    case 'checkHostelFields':

        $unique_id = $_POST['unique_id'];
        $table_hostel = 'hostel_name';

        $columns = [
            'special_tahsildar',
            'assembly_const',
            'parliment_const',
            'hostel_location',
            'urban_type',
            'corporation',
            'municipality',
            'town_panchayat',
            'block_name',
            'village_name',
            'yob',
            'distance_btw_phc',
            'phc_name',
            'distance_btw_ps',
            'ps_name',
            'staff_count',
            'go_attach_org_name',
            'go_attach_file'
        ];

        $sql = 'SELECT ' . implode(', ', $columns) . ' FROM ' . $table_hostel . ' WHERE unique_id = ?';

        $stmt = $mysqli->prepare($sql);
        if (!$stmt) {
            echo json_encode(['status' => 'error', 'msg' => 'SQL Error: ' . $mysqli->error]);
            break;
        }

        $stmt->bind_param('s', $unique_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            echo json_encode(['status' => 'success', 'data' => $row]);
        } else {
            echo json_encode(['status' => 'error', 'msg' => 'No data found.']);
        }

        break;


    case 'updateCompletionStatus':

        $unique_id = $_POST['unique_id'];
        $table = 'hostel_name';

        $stmt = $mysqli->prepare("UPDATE $table SET com_status = 1 WHERE unique_id = ?");
        if ($stmt === false) {
            echo json_encode(['status' => 'error', 'msg' => 'SQL prepare failed']);
            exit;
        }

        $stmt->bind_param("s", $unique_id);
        $success = $stmt->execute();

        if ($success) {
            echo json_encode(['status' => 'success', 'msg' => 'Completion status updated']);
        } else {
            echo json_encode(['status' => 'error', 'msg' => 'Update failed']);
        }

        break;
}








function get_pending_details($department_name, $assign)
{

    global $pdo;

    $table_name = "complaint_creation";
    $where = [];
    $table_columns = [
        // "(select count(complaint_no) from complaint_creation where stage_1_status = '1' GROUP BY department_name) as comp_complaint",
        "ifnull(count(complaint_no),0) as pending_complaint",

    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = "stage_1_status != '2' and is_delete = 0 and department_name = '" . $department_name . "' and assign_by = '" . $assign . "' GROUP BY department_name";


    $cnt_status = $pdo->select($table_details, $where);
    //echo $cnt_status;
    if (!($cnt_status->status)) {

        print_r($cnt_status);
    } else {

        if (!empty($cnt_status->data[0])) {
            $cnt_sts = $cnt_status->data[0]['pending_complaint'];
            //print_r("HH".$cnt_sts);
        } else {
            $cnt_sts = "";
        }
    }
    return $cnt_sts;
}

function get_ending_date($assign_by, $department, $stage)
{
    global $pdo;

    $table_name = "periodic_creation_sub";
    $where = [];
    $table_columns = [
        "ending_count",
    ];

    $table_details = [
        "periodic_creation_sub",
        $table_columns
    ];

    $where = "department_name = '" . $department . "' and stage = " . $stage . " and is_delete = 0 and form_unique_id != ''";


    $cnt_status = $pdo->select($table_details, $where);
    //echo $cnt_status;
    if (!($cnt_status->status)) {

        print_r($cnt_status);
    } else {

        if (!empty($cnt_status->data[0])) {
            $cnt_sts = $cnt_status->data[0]['ending_count'];
        } else {
            $cnt_sts = "";
        }
    }
    return $cnt_sts;
}

function get_overdue_cnt($department_name, $assign)
{

    global $pdo;



    $table_name = "complaint_creation";
    $where = [];
    $table_columns = [
        "count(complaint_no) as pending_complaint",

    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    // $stage_1_ending_date  = get_ending_date($value['assign_by'], $value['department_name'], 1);
    // $stage_2_ending_date  = get_ending_date($value['assign_by'], $value['department_name'], 2);

    // if($stage_1_ending_date == ''){
    //     $stage_1_ending_date = 1; 
    // }
    //    $where  = "stage_1_status != '2' and is_delete = 0 and department_name = '".$department_name."' and assign_by = '".$assign."' and  DATEDIFF(CURDATE(), entry_date) > ".$stage_1_ending_date." GROUP BY department_name";


    $cnt_status = $pdo->select($table_details, $where);
    //echo $cnt_status;
    if (!($cnt_status->status)) {

        print_r($cnt_status);
    } else {

        if (!empty($cnt_status->data[0])) {
            $cnt_sts = $cnt_status->data[0]['pending_complaint'];
        } else {
            $cnt_sts = "";
        }
    }
    return $cnt_sts;
}

function get_department($user_id)
{
    global $pdo;

    $table_name = "periodic_creation_sub";
    $where = [];
    $table_columns = [
        //"GROUP_CONCAT(department_name) AS department_name",
        "GROUP_CONCAT((select department_type from department_creation where periodic_creation_sub.department_name=department_creation.unique_id)) AS department_name"
    ];

    $table_details = [
        "periodic_creation_sub",
        $table_columns
    ];

    $where = "user_id = '" . $user_id . "' and  is_delete = 0 and form_unique_id != ''";


    $cnt_status = $pdo->select($table_details, $where);
    // print_r($cnt_status);
    if (!($cnt_status->status)) {

        print_r($cnt_status);
    } else {

        if (!empty($cnt_status->data[0])) {
            $cnt_sts = $cnt_status->data[0]['department_name'];
        } else {
            $cnt_sts = "";
        }
    }
    return $cnt_sts;
}
