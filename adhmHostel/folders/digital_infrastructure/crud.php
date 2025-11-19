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
function validateCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

switch ($action) {

    case 'createupdate':

            $token = $_POST['csrf_token'];
        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        // Sanitize input fields
        $acc_year = !empty($_POST["acc_year"]) ? sanitizeInput($_POST["acc_year"]) : null;
$hostel_name = !empty($_POST["hostel_name"]) ? sanitizeInput($_POST["hostel_name"]) : null;
$hostel_id = !empty($_POST["hostel_id"]) ? sanitizeInput($_POST["hostel_id"]) : null;
$taluk = !empty($_POST["taluk_name"]) ? sanitizeInput($_POST["taluk_name"]) : null;
$district = !empty($_POST["district"]) ? sanitizeInput($_POST["district"]) : null;
$land_type = !empty($_POST["land_type"]) ? sanitizeInput($_POST["land_type"]) : null;
$owner_of_land = !empty($_POST["owner_of_land"]) ? sanitizeInput($_POST["owner_of_land"]) : null;
$reg_of_land = !empty($_POST["reg_of_land"]) ? sanitizeInput($_POST["reg_of_land"]) : null;
$area_of_land = !empty($_POST["area_of_land"]) ? sanitizeInput($_POST["area_of_land"]) : null;
$con_area_land = !empty($_POST["con_area_land"]) ? sanitizeInput($_POST["con_area_land"]) : null;
$existing_demolished = !empty($_POST["existing_demolished"]) ? sanitizeInput($_POST["existing_demolished"]) : null;
$no_floors = !empty($_POST["no_floors"]) ? sanitizeInput($_POST["no_floors"]) : null;
$toilet_each_floor = !empty($_POST["toilet_each_floor"]) ? sanitizeInput($_POST["toilet_each_floor"]) : null;
$compound_wall = !empty($_POST["compound_wall"]) ? sanitizeInput($_POST["compound_wall"]) : null;
$water_facilities = !empty($_POST["water_facilities"]) ? sanitizeInput($_POST["water_facilities"]) : null;
$living_area = !empty($_POST["living_area"]) ? sanitizeInput($_POST["living_area"]) : null;
$living_area_size = !empty($_POST["living_area_size"]) ? sanitizeInput($_POST["living_area_size"]) : null;
$no_of_rooms = !empty($_POST["no_of_rooms"]) ? sanitizeInput($_POST["no_of_rooms"]) : null;
$room_size = !empty($_POST["room_size"]) ? sanitizeInput($_POST["room_size"]) : null;
$no_of_kitchen = !empty($_POST["no_of_kitchen"]) ? sanitizeInput($_POST["no_of_kitchen"]) : null;
$kitchen_size = !empty($_POST["kitchen_size"]) ? sanitizeInput($_POST["kitchen_size"]) : null;
$demolished = !empty($_POST["demolished"]) ? sanitizeInput($_POST["demolished"]) : null;
        $unique_id = $_POST["unique_id"];
        $update_unique_id = $_POST["update_unique_id"];

        // File upload handling
        $file_names = '';
        $file_org_names = '';

        
        if (!empty($_FILES['doc_file']['name'])) {
            $allowedExts = array('pdf', 'jpg', 'jpeg', 'png', 'gif', 'xlsx', 'xls');
            $extension = pathinfo($_FILES["doc_file"]['name'], PATHINFO_EXTENSION);
            if (in_array($extension, $allowedExts)) {
                $tem_name = random_strings(25) . "." . $extension;
                move_uploaded_file($_FILES["doc_file"]["tmp_name"], '../../uploads/digital_infrastructure/' . $tem_name);
                $file_names = $tem_name;
                $file_org_names = $_FILES["doc_file"]['name'];
            } else {
                die('Invalid file type. Allowed types: pdf, jpg, jpeg, png, gif, xlsx, xls');
            }
        }
if($file_names){
        // Prepare columns array for either insert or update
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
            "unique_id" => $unique_id
        ];
}else{
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
                       "unique_id" => $unique_id
        ];


}

        // Use prepared statements for MySQLi
        if ($update_unique_id) {
            // Update existing record
            $sql = "UPDATE digital_infra_creation SET ";
            $updates = [];
            $params = [];
            foreach ($columns as $key => $value) {
                if ($key !== 'unique_id') {
                    $updates[] = "$key = ?";
                    $params[] = $value;
                }
            }
            $sql .= implode(", ", $updates);
            $sql .= " WHERE unique_id = ?";
            $params[] = $update_unique_id;
            $stmt = $mysqli->prepare($sql);

            // Bind parameters
            $types = str_repeat('s', count($params)); // 'sss...' for string parameters
            $stmt->bind_param($types, ...$params);

            // Execute SQL statement
            $action_obj = new stdClass();
            if ($stmt->execute()) {
                $action_obj->status = true;
                $action_obj->data = $update_unique_id; // Assuming you want to return the updated unique_id
                $msg = "update";
            } else {
                $action_obj->status = false;
                $action_obj->error = $stmt->error;
                $msg = "error";
            }

            $stmt->close();
        } else {
            // Insert new record
            $sql = "INSERT INTO digital_infra_creation (";
            $sql .= implode(", ", array_keys($columns));
            $sql .= ") VALUES (";
            $sql .= implode(", ", array_fill(0, count($columns), "?"));
            $sql .= ")";
            $stmt = $mysqli->prepare($sql);

            // Bind parameters
            $types = str_repeat('s', count($columns)); // 'sss...' for string parameters
            $stmt->bind_param($types, ...array_values($columns));

            // Execute SQL statement
            $action_obj = new stdClass();
            if ($stmt->execute()) {
                $action_obj->status = true;
                $action_obj->data = $mysqli->insert_id; // Assuming you want to return the new insert id
                $msg = "create";
            } else {
                $action_obj->status = false;
                $action_obj->error = $stmt->error;
                $msg = "error";
            }

            $stmt->close();
        }

        // Prepare JSON response
        $json_array = [
            "status" => $action_obj->status,
            "data" => isset($action_obj->data) ? $action_obj->data : null,
            "error" => isset($action_obj->error) ? $action_obj->error : "",
            "msg" => $msg
        ];

        echo json_encode($json_array);

        break;


    case 'datatable':
       
     

        // DataTable Variables
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
            '(SELECT amc_year FROM academic_year_creation WHERE academic_year_creation.unique_id = ' . $table . '.academic_year ) AS amc_year',
            "land_type",
            "owner_of_land",
            "existing_demolished",
            "unique_id"
        ];
        $table_details = $table . " , (SELECT @a:= ?) AS a ";
        $where = "is_active = 1 AND is_delete = 0 AND hostel_name = ?";
        $order_by = "";

        $sql_function = "SQL_CALC_FOUND_ROWS";

        // SQL query for data fetching
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_details WHERE $where";
        if (!empty($limit)) {
            $sql .= " LIMIT ?, ?";
        }

        $stmt = $mysqli->prepare($sql);

        // Bind parameters
        if (!empty($limit)) {
            $stmt->bind_param("isii", $start, $_SESSION['hostel_id'], $start, $limit);
        } else {
            $stmt->bind_param("is", $start, $_SESSION['hostel_id']);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch total records
        $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
        $total_records = $total_records_result->fetch_assoc()['total'];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                if ($row['land_type'] == "own_land") {
                    $row['land_type'] = "Having own land";
                } else {
                    $row['land_type'] = "Not having own land";
                }

                $row['existing_demolished'] = disname($row['existing_demolished']);
                $row['is_active'] = is_active_show($row['is_active']);

                $unique_id = $row['unique_id'];
                $eye_button = '<a class="btn btn-action specl2-icon" href="javascript:assetInfra_print(\'' . $unique_id . '\')"><button type="button"><i class="fa fa-eye"></i></button></a>';

                $btn_update = btn_update($folder_name, $row['unique_id']);
                $btn_delete = btn_delete($folder_name, $row['unique_id']);

                $row['unique_id'] = $btn_update . $btn_delete . $eye_button;
                $data[] = array_values($row);
            }

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data
            ];
        } else {
            // Handle the error case
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => $mysqli->error
            ];
        }

        echo json_encode($json_array);

        // Close statement and connection
        $stmt->close();
      

        break;


    case 'delete':

        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }
        $unique_id = $_POST['unique_id'];

        // Prepare SQL statement
        $sql = "UPDATE $table SET is_delete = 1 WHERE unique_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $unique_id);

        // Execute the statement
        $stmt->execute();

        // Check if the statement was successful
        if ($stmt->affected_rows > 0) {
            $status = true;
            $data = ['unique_id' => $unique_id];
            $error = "";
            $msg = "success_delete";
        } else {
            $status = false;
            $data = null;
            $error = "Failed to delete record";
            $msg = "error";
        }

        // Close statement
        $stmt->close();

        // JSON response
        $json_array = [
            'status' => $status,
            'data' => $data,
            'error' => $error,
            'msg' => $msg,
            // 'sql' => $sql
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

        $token = $_POST['csrf_token'];
        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        $facilities_type = $_POST['facilities_type'];
        $facilities = $_POST['facilities'];
        $quantity = $_POST['quantity'];
        $description = $_POST['description'];
        $form_main_unique_id = $_POST['form_main_unique_id'];
        $unique_id = $_POST['unique_id']; // Assuming this is for update operation

        // Prepare SQL statement for insert or update
        if ($unique_id) {
            // Update operation
            $sql = "UPDATE $table_facility_details SET 
                    facilities_type = ?, 
                    facilities = ?, 
                    quantity = ?, 
                    description = ?, 
                    form_main_unique_id = ? 
                    WHERE unique_id = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param(
                "ssssss",
                $facilities_type,
                $facilities,
                $quantity,
                $description,
                $form_main_unique_id,
                $unique_id
            );
        } else {
            // Insert operation
            $sql = "INSERT INTO $table_facility_details 
                    (facilities_type, facilities, quantity, description, form_main_unique_id, unique_id) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param(
                "ssssss",
                $facilities_type,
                $facilities,
                $quantity,
                $description,
                $form_main_unique_id,
                unique_id($prefix)
            );
        }

        // Execute the statement
        $status = $stmt->execute();

        // Prepare response
        if ($status) {
            $msg = $unique_id ? "update" : "add";
            $data = $mysqli->insert_id; // Return last inserted ID for insert operation
            $error = "";
            $sql = $stmt->affected_rows;
        } else {
            $msg = "error";
            $data = null;
            $error = $stmt->error;
            $sql = $stmt->affected_rows;
        }

        // Close statement
        $stmt->close();

        // JSON response
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            "sql" => $sql
        ];

        echo json_encode($json_array);

        break;


    case 'digital_infra_datatable':

        // Function Name button prefix
        $btn_edit_delete = 'digital_infra_details';

        // Fetch Data
        $form_main_unique_id = $_POST['form_main_unique_id'];
        // print_r($form_main_unique_id);die();
        // DataTable

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
            '(select facility_type from facility_type_creation where facility_type_creation.unique_id = ' . $table_facility_details . '.facilities_type) as facilities_type',
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

        $token = $_POST['csrf_token'];
        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        // Prepare SQL statement
        $sql = "UPDATE $table_facility_details SET is_delete = 1 WHERE unique_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $unique_id);

        // Execute the statement
        $status = $stmt->execute();

        // Prepare response
        if ($status) {
            $msg = 'success_delete';
            $data = $unique_id; // You can return the deleted unique_id if needed
            $error = "";
            $sql = $stmt->affected_rows;
        } else {
            $msg = 'error';
            $data = null;
            $error = $stmt->error;
            $sql = $stmt->affected_rows;
        }

        // Close statement
        $stmt->close();

        // JSON response
        $json_array = [
            'status' => $status,
            'data' => $data,
            'error' => $error,
            'msg' => $msg,
            // 'sql' => $sql
        ];

        echo json_encode($json_array);

        break;



    case 'buildings_sub_add_update':

        $token = $_POST['csrf_token'];
        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }
        $no_floors = $_POST["no_floors"];
        $toilet_each_floor = $_POST["toilet_each_floor"];
        $living_area = $_POST["living_area"];
        $living_area_size = $_POST["living_area_size"];
        $no_of_rooms = $_POST["no_of_rooms"];
        $room_size = $_POST["room_size"];
        $no_of_kitchen = $_POST["no_of_kitchen"];
        $kitchen_size = $_POST["kitchen_size"];
        $form_main_unique_id = $_POST["form_unique_id"];
        $unique_id = $_POST["unique_id"]; // Assuming this is for update operation

        // Prepare SQL statement for insert or update
        if ($unique_id) {
            // Update operation
            $sql = "UPDATE $table_sub_building SET 
                        no_floors = ?, 
                        toilet_each_floor = ?, 
                        living_area = ?, 
                        living_area_size = ?, 
                        no_of_rooms = ?, 
                        room_size = ?, 
                        no_of_kitchen = ?, 
                        kitchen_size = ?, 
                        form_main_unique_id = ? 
                        WHERE unique_id = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param(
                "ssssssssss",
                $no_floors,
                $toilet_each_floor,
                $living_area,
                $living_area_size,
                $no_of_rooms,
                $room_size,
                $no_of_kitchen,
                $kitchen_size,
                $form_main_unique_id,
                $unique_id
            );
        } else {
            // Insert operation
            $sql = "INSERT INTO $table_sub_building 
                        (no_floors, toilet_each_floor, living_area, living_area_size, 
                         no_of_rooms, room_size, no_of_kitchen, kitchen_size, 
                         form_main_unique_id, unique_id) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param(
                "ssssssssss",
                $no_floors,
                $toilet_each_floor,
                $living_area,
                $living_area_size,
                $no_of_rooms,
                $room_size,
                $no_of_kitchen,
                $kitchen_size,
                $form_main_unique_id,
                unique_id($prefix)
            );
        }

        // Execute the statement
        $status = $stmt->execute();

        // Prepare response
        if ($status) {
            $msg = $unique_id ? "update" : "save";
            $data = $mysqli->insert_id; // Return last inserted ID for insert operation
            $error = "";
        } else {
            $msg = "error";
            $data = null;
            $error = $mysqli->error;
        }

        // Close statement
        $stmt->close();

        // JSON response
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
        ];

        echo json_encode($json_array);

        break;



    case 'buildings_sub_datatable':
        // DataTable Variables
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
                // "testing" => $result->sql
            ];
        } else {
            // print_r($result);
        }

        echo json_encode($json_array);
        break;


    case 'buildings_sub_delete':

        $token = $_POST['csrf_token'];
        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }
        $unique_id = $_POST['unique_id'];

        // Prepare SQL statement
        $sql = "UPDATE $table_sub_building SET is_delete = 1 WHERE unique_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $unique_id);

        // Execute the statement
        $status = $stmt->execute();

        // Prepare response
        if ($status) {
            $msg = 'success_delete';
            $data = $unique_id; // You can return the deleted unique_id if needed
            $error = "";
            $sql = $stmt->affected_rows;
        } else {
            $msg = 'error';
            $data = null;
            $error = $stmt->error;
            $sql = $stmt->affected_rows;
        }

        // Close statement
        $stmt->close();

        // JSON response
        $json_array = [
            'status' => $status,
            'data' => $data,
            'error' => $error,
            'msg' => $msg,
            // 'sql' => $sql
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
