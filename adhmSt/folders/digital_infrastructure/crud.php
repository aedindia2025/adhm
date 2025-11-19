<?php
// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "digital_infra_creation";
$table_facility_details = "digital_infra_facility_sub";
$table_sub_building = "buildings_sub";
// // Include DB file and Common Functions
include '../../config/dbconfig.php';


// $fileUploadPath = $fileUploadConfig->get('upload_folder');
// // Create Folder in root->uploads->( this_folder_name ) Before using this file upload
// $fileUploadConfig->set('upload_folder', $fileUploadPath . $folder_name . DIRECTORY_SEPARATOR);
// // $fileUploadPath = $fileUploadConfig->get( 'upload_folder' );

// $fileUpload         = new Alirdn\SecureUPload\SecureUPload($fileUploadConfig);

// // Variables Declaration
$action = $_POST["action"];
// print_r($action);die();

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





    case 'createupdate':

        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }



        $acc_year = $_POST["acc_year"];
        $hostel_name = $_POST["hostel_name"];
        $hostel_id = $_POST["hostel_id"];
        $taluk = $_POST["taluk_name"];
        $district = $_POST["district"];
        $land_type = $_POST["land_type"];
        $owner_of_land = $_POST["owner_of_land"];
        $reg_of_land = sanitizeInput($_POST["reg_of_land"]);
        $area_of_land = sanitizeInput($_POST["area_of_land"]);
        $con_area_land = sanitizeInput($_POST["con_area_land"]);
        $existing_demolished = $_POST["existing_demolished"];

        $no_floors = sanitizeInput($_POST["no_floors"]);
        $toilet_each_floor = sanitizeInput($_POST["toilet_each_floor"]);
        $compound_wall = $_POST["compound_wall"];
        $water_facilities = sanitizeInput($_POST["water_facilities"]);
        $living_area = sanitizeInput($_POST["living_area"]);
        $living_area_size = sanitizeInput($_POST["living_area_size"]);
        $no_of_rooms = sanitizeInput($_POST["no_of_rooms"]);
        $room_size = sanitizeInput($_POST["room_size"]);
        $no_of_kitchen = sanitizeInput($_POST["no_of_kitchen"]);
        $kitchen_size = sanitizeInput($_POST["kitchen_size"]);
        $demolished = $_POST["demolished"];

        $unique_id = $_POST["unique_id"];

        $update_unique_id = $_POST["update_unique_id"];


        $allowedExts = array('pdf', 'jpg', 'jpeg', 'png', 'gif', 'xlsx', 'xls');

        $extension = pathinfo($_FILES["doc_file"]['name'], PATHINFO_EXTENSION);

        if (in_array($extension, $allowedExts)) {

            $file_exp = explode(".", $_FILES["doc_file"]['name']);

            $tem_name = random_strings(25) . "." . $file_exp[1];
            move_uploaded_file($_FILES["doc_file"]["tmp_name"], '../../uploads/digital_infrastructure/' . $tem_name);
            $file_names = $tem_name;
            $file_org_names = $_FILES["doc_file"]['name'];

            $update_where = "";

            if ($file_org_names == '') {
                $columns = [
                    "academic_year" => $acc_year,
                    "hostel_name" => $hostel_name,
                    "hostel_id" => $hostel_id,
                    "taluk" => $taluk,
                    "district" => $district,
                    "land_type" => $land_type,
                    "owner_of_land" => $owner_of_land,
                    "reg_of_land" => $reg_of_land,
                    "area_of_land" => $area_of_land,
                    "con_area_land" => $con_area_land,
                    "existing_demolished" => $existing_demolished,
                    "no_floors" => $no_floors,
                    "toilet_each_floor" => $toilet_each_floor,

                    "compound_wall" => $compound_wall,
                    "water_facilities" => $water_facilities,
                    "living_area" => $living_area,
                    "living_area_size" => $living_area_size,
                    "no_of_rooms" => $no_of_rooms,
                    "room_size" => $room_size,
                    "no_of_kitchen" => $no_of_kitchen,
                    "kitchen_size" => $kitchen_size,
                    "demolished" => $demolished,
                    "unique_id" => $unique_id,

                ];
            } else {
                $columns = [
                    "academic_year" => $acc_year,
                    "hostel_name" => $hostel_name,
                    "hostel_id" => $hostel_id,
                    "taluk" => $taluk,
                    "district" => $district,
                    "land_type" => $land_type,
                    "owner_of_land" => $owner_of_land,
                    "reg_of_land" => $reg_of_land,
                    "area_of_land" => $area_of_land,
                    "con_area_land" => $con_area_land,
                    "existing_demolished" => $existing_demolished,
                    "no_floors" => $no_floors,
                    "toilet_each_floor" => $toilet_each_floor,

                    "compound_wall" => $compound_wall,
                    "water_facilities" => $water_facilities,
                    "living_area" => $living_area,
                    "living_area_size" => $living_area_size,
                    "no_of_rooms" => $no_of_rooms,
                    "room_size" => $room_size,
                    "no_of_kitchen" => $no_of_kitchen,
                    "kitchen_size" => $kitchen_size,
                    "demolished" => $demolished,
                    "land_doc_name" => $file_names,
                    "land_doc_org_name" => $file_org_names,
                    "unique_id" => $unique_id,

                ];
            }



            // print($unique_id);die();
            // Update Begins
            if ($update_unique_id) {

                unset($columns['unique_id']);

                $update_where = [
                    "unique_id" => $update_unique_id
                ];

                $action_obj = $pdo->update($table, $columns, $update_where);

                // Update Ends
            } else {

                // Insert Begins            
                $action_obj = $pdo->insert($table, $columns);
                // Insert Ends
                // print_r($action_obj);die();

            }

            if ($action_obj->status) {
                $status = $action_obj->status;
                $data = $action_obj->data;
                $error = "";
                $sql = $action_obj->sql;

                if ($update_unique_id) {
                    $msg = "update";
                } else {
                    $msg = "create";
                }
            } else {
                $status = $action_obj->status;
                $data = $action_obj->data;
                $error = $action_obj->error;
                $sql = $action_obj->sql;
                $msg = "error";
            }

        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "doc_error";
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql" => $sql
        ];

        echo json_encode($json_array);

        break;

    case 'datatable':
        // DataTable Variables
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length == '-1' ? "" : $length;

        $data = [];

        // Query Variables
        $json_array = "";
        $columns = [
            "@a:=@a+1 s_no",
            '(SELECT amc_year FROM academic_year_creation WHERE academic_year_creation.unique_id = ' . $table . '.academic_year ) AS amc_year',
            "land_type",
            "owner_of_land",
            "existing_demolished",
            "unique_id"
        ];
        $table_details = $table . " , (SELECT @a:= ?) AS a ";
        $where = "is_delete = 0 and taluk = ?";
$taluk_id = $_SESSION['taluk_id'];
        $order_by = ""; // You can modify this to add an order by clause if needed

        $sql_function = "SQL_CALC_FOUND_ROWS";

        // SQL query for data fetching
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_details WHERE $where";
        if ($limit) {
            $sql .= " LIMIT ?, ?";
        }

        $stmt = $mysqli->prepare($sql);
        if ($limit) {
            $stmt->bind_param("isii", $start, $taluk_id, $start, $limit);
        } else {
            $stmt->bind_param("is", $start, $taluk_id);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch total records
        $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
        $total_records = $total_records_result->fetch_assoc()['total'];


        if ($result) {

            $res_array = $result->fetch_all(MYSQLI_ASSOC);

            foreach ($res_array as $key => $value) {
                if ($value['land_type'] == "own_land") {
                    $value['land_type'] = "Having own land";
                } else {
                    $value['land_type'] = "Not having own land";
                }

                $value['existing_demolished'] = disname($value['existing_demolished']);
                // $value['description'] = disname($value['description']);
                $value['is_active'] = is_active_show($value['is_active']);

                $unique_id = $value['unique_id'];
                $eye_button = '<a class="btn btn-action specl2"  href="javascript:assetInfra_print(\'' . $unique_id . '\')"><button type="button" ><i class="fa fa-eye" ></i></button></a>';



                $btn_update = btn_update($folder_name, $value['unique_id']);
                $btn_delete = btn_delete($folder_name, $value['unique_id']);


                $value['unique_id'] = $eye_button;
                $data[] = array_values($value);
            }

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data,
                // "testing" => $result->sql
            ];
        } else {
            // print_r($result);
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => $stmt->error,
                "testing" => $stmt->sqlstate
            ];
        }

        echo json_encode($json_array);
        break;


    case 'delete':
        $unique_id = $_POST['unique_id'];

        // Prepare SQL statement
        $sql = "UPDATE $table SET is_delete = 1 WHERE unique_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $unique_id);

        // Execute SQL statement
        if ($stmt->execute()) {
            $status = "success";
            $msg = "success_delete";
        } else {
            $status = "error";
            $msg = "error";
        }

        $stmt->close();

        $json_array = [
            "status" => $status,
            "msg" => $msg,
        ];

        echo json_encode($json_array);
        break;

    // case 'facilities':

    //     $facility_type = $_POST['facility_type'];

    //     $facility_name_options = facility_type($facility_type);

    //     $facility_name_options = select_option($facility_name_options, "Select Facilities");

    //     echo $facility_name_options;
    //     // print_r($taluk_name_options);

    //     break;


    case 'facilities_add_update':
        $facilities_type = $_POST['facilities_type'];
        $facilities = $_POST['facilities'];
        $quantity = $_POST['quantity'];
        $description = $_POST['description'];

        $form_main_unique_id = $_POST['form_main_unique_id'];
        $update_where = '';
        // print_r( 'hii'.$po_name );
        $columns = [
            'facilities_type' => $facilities_type,
            'facilities' => $facilities,
            'quantity' => $quantity,
            'description' => $description,

            'form_main_unique_id' => $form_main_unique_id,
            'unique_id' => unique_id($prefix)
        ];

        // check already Exist Or not
        // $table_details      = [
        //     $table_facility_details,
        //     [
        //         'COUNT(unique_id) AS count'
        //     ]
        // ];

        // $select_where= 'is_delete = 0 AND form_main_unique_id ="' . $form_main_unique_id . '"';
        // // 

        // $action_obj         = $pdo->select($table_details, $select_where);
        // if ($action_obj->status) {
        //     $status     = $action_obj->status;
        //     $data       = $action_obj->data;
        //     $error      = '';
        //     $sql        = $action_obj->sql;
        // } else {
        //     $status     = $action_obj->status;
        //     $data       = $action_obj->data;
        //     $error      = $action_obj->error;
        //     $sql        = $action_obj->sql;
        //     $msg        = 'error';
        // } 
        //  if (($data[0]['count'] == 0)) {
        // Update Begins
        // //  if() {

        // $table_details1      = [
        //     'product_details_sub',
        //     [
        //         'COUNT(form_main_unique_id) AS count'
        //     ]
        // ];
        // $count_where       = ' is_delete = 0 AND form_main_unique_id ="' . $form_main_unique_id . '" ';

        // $total_record        = $pdo->select($table_details1, $count_where);
        // // print_r( $total_record );

        // $data_record       = $total_record->data;

        // if ($data_record[0]['count'] == $_POST['no_of_po']) {
        //     $msg        = 'product';
        // } else {
        // Insert Begins
        $action_obj = $pdo->insert($table_facility_details, $columns);
        $msg = 'add';
        // Insert Ends

        // }

        // Insert Ends

        // }
        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = '';
            $sql = $action_obj->sql;
            if ($unique_id) {
                $msg = "update";
            } else {
                $msg = "add";
            }
        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = 'error';
        }
        // }

        $json_array = [
            'status' => $status,
            'data' => $data,
            'error' => $error,
            'msg' => $msg,
            // 'sql' => $sql
        ];

        echo json_encode($json_array);

        break;

    case 'digital_infra_datatable':

        // Function Name button prefix
        $btn_edit_delete = 'digital_infra_details';

        // Fetch Data
        $form_main_unique_id = $_POST['form_main_unique_id'];

        // DataTable
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
            'facilities_type',
            '(SELECT facility_name FROM facility_creation WHERE facility_creation.unique_id = ' . $table_facility_details . '.facilities ) AS disbursement_type',
            'quantity',
            'description',
            'unique_id',
            'form_main_unique_id'
        ];

        $table_details = [
            $table_facility_details . ' , (SELECT @a:= ' . $start . ') AS a ',
            $columns
        ];
        //     $where          = [
        //         'is_active'                     => 1,
        //         'is_delete'                     => 0
        //    ];

        $where = 'is_active = 1 AND is_delete = 0 AND form_main_unique_id ="' . $form_main_unique_id . '" ';

        // if ( $form_main_unique_id != '' ) {
        // $where = [];
        // $where = ['form_main_unique_id'    => $form_main_unique_id,];
        // }
        $order_by = '';

        $sql_function = 'SQL_CALC_FOUND_ROWS';

        $result = $pdo->select($table_details, $where, $limit, $start, $order_by, $sql_function);
        $total_records = total_records();
        //print_r($total_records);

        if ($result->status) {

            $res_array = $result->data;

            foreach ($res_array as $key => $value) {



                // $btn_edit               = btn_edit($btn_edit_delete, $value['unique_id']);
                $btn_delete = btn_delete($btn_edit_delete, $value['unique_id']);


                $value['unique_id'] = $btn_delete;


                $data[] = array_values($value);
            }

            $json_array = [
                'draw' => intval($draw),
                'recordsTotal' => intval($total_records),
                'recordsFiltered' => intval($total_records),
                'data' => $data,
                // 'testing' => $result->sql
            ];
        } else {
            // print_r($result);
        }

        echo json_encode($json_array);
        break;


    case 'digital_infra_details_delete':
        $unique_id = $_POST['unique_id'];

        // Prepare SQL statement
        $sql = "UPDATE $table_facility_details SET is_delete = 1 WHERE unique_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $unique_id);

        // Execute SQL statement
        if ($stmt->execute()) {
            $status = "success";
            $msg = "success_delete";
        } else {
            $status = "error";
            $msg = "error";
        }

        $stmt->close();

        $json_array = [
            "status" => $status,
            "msg" => $msg,
        ];

        echo json_encode($json_array);
        break;



    case 'buildings_sub_add_update':

        $no_floors = $_POST["no_floors"];
        $toilet_each_floor = $_POST["toilet_each_floor"];
        $living_area = $_POST["living_area"];
        $living_area_size = $_POST["living_area_size"];
        $no_of_rooms = $_POST["no_of_rooms"];
        $room_size = $_POST["room_size"];
        $no_of_kitchen = $_POST["no_of_kitchen"];
        $kitchen_size = $_POST["kitchen_size"];
        $form_main_unique_id = $_POST["form_unique_id"];
        // print_r($form_main_unique_id);die();



        // $is_active          = $_POST["is_active"];
        $unique_id = $_POST["unique_id"];

        $update_where = "";

        $columns = [
            "no_floors" => $no_floors,
            "toilet_each_floor" => $toilet_each_floor,
            "living_area" => $living_area,
            "living_area_size" => $living_area_size,
            "no_of_rooms" => $no_of_rooms,
            "room_size" => $room_size,
            "no_of_kitchen" => $no_of_kitchen,
            "kitchen_size" => $kitchen_size,
            "form_main_unique_id" => $form_main_unique_id,
            "unique_id" => unique_id($prefix)
        ];



        // Insert Begins            
        $action_obj = $pdo->insert($table_sub_building, $columns);
        //print_r($action_obj);die();
        // Insert Ends

        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;

            if ($unique_id) {
                $msg = "update";
            } else {
                $msg = "save";
            }
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
            // "sql" => $sql
        ];

        echo json_encode($json_array);

        break;


    case 'buildings_sub_datatable':
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

        $delete = "buildings_sub";
        $form_main_unique_id = $_POST['form_main_unique_id'];

        // Query Variables
        $json_array = "";
        $columns = [
            "@a:=@a+1 s_no",
            "no_floors",
            "toilet_each_floor",
            "living_area",
            "living_area_size",
            "no_of_rooms",
            "room_size",
            "no_of_kitchen",
            "kitchen_size",
            "unique_id"
        ];
        $table_details = [
            $table_sub_building . " , (SELECT @a:= '" . $start . "') AS a ",
            $columns
        ];
        $where = "is_delete = 0 and form_main_unique_id = '" . $form_main_unique_id . "'";
        $order_by = "";



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
        // print_r($result);
        $total_records = total_records();

        if ($result->status) {

            $res_array = $result->data;

            foreach ($res_array as $key => $value) {

                $btn_delete = btn_delete($delete, $value['unique_id']);


                $value['unique_id'] = $btn_delete;
                $data[] = array_values($value);
            }

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data,
                //   /  "testing" => $result->sql
            ];
        } else {
            // print_r($result);
        }

        echo json_encode($json_array);
        break;


    case 'buildings_sub_delete':
        $unique_id = $_POST['unique_id'];

        // Prepare SQL statement
        $sql = "UPDATE $table_sub_building SET is_delete = 1 WHERE unique_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $unique_id);

        // Execute SQL statement
        if ($stmt->execute()) {
            $status = "success";
            $msg = "success_delete";
        } else {
            $status = "error";
            $msg = "error";
        }

        $stmt->close();

        $json_array = [
            "status" => $status,
            "msg" => $msg,
        ];

        echo json_encode($json_array);
        break;




    case 'get_asset_name':
        $facilities_type = $_POST['facilities_type'];
        $asset_category_options = facility_name("", $facilities_type);

        $asset_name_options = select_option($asset_category_options, "Select Facility Name");
        echo $asset_name_options;

        break;
    default:

        break;
}
