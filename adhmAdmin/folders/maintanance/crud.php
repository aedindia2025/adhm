<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "maintanance_creation";

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

function validateCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}


switch ($action) {
    case 'createupdate':

        $hostel_id = $_POST["hostel_id"];
        $hostel_name = $_POST["hostel_name"];
        $district_id = $_POST["district_id"];
        $taluk_id = $_POST["taluk_id"];
        $warden_name = $_POST["warden_name"];
        $warden_id = $_POST["warden_id"];
        $maintanance_no = $_POST["maintanance_no"];
        $asset_category = $_POST["asset_category"];
        $asset_name = $_POST["asset_name"];
        $description = sanitizeInput($_POST["description"]);
        $spend_amount = sanitizeInput($_POST["spend_amount"]);
        $unique_id = $_POST["unique_id"]; // Assuming you're retrieving this from POST

        $update_where = "";
        $file_names = "";
        $file_org_names = "";

        $allowedExts = array('pdf', 'jpg', 'jpeg', 'png', 'gif', 'xlsx', 'xls');
        $extension = pathinfo($_FILES['test_file']['name'], PATHINFO_EXTENSION);

        if (in_array($extension, $allowedExts)) {
            $file_exp = explode(".", $_FILES["test_file"]['name']);
            $tem_name = random_strings(25) . "." . $file_exp[1];
            move_uploaded_file($_FILES["test_file"]["tmp_name"], '../../../adhmHostel/uploads/maintanance/' . $tem_name);
            $file_names = $tem_name;
            $file_org_names = $_FILES["test_file"]['name'];
        }

        $entry_date = date('Y-m-d');

        if ($unique_id) {
            // Update existing record
            $sql = "UPDATE maintanance_creation 
                    SET hostel_id = ?, 
                        hostel_name = ?, 
                        hostel_district = ?, 
                        hostel_taluk = ?, 
                        warden_name = ?, 
                        warden_id = ?, 
                        maintanance_no = ?, 
                        asset_category = ?, 
                        asset_name = ?, 
                        description = ?, 
                        spend_amount = ?, 
                        file_name = ?, 
                        file_org_name = ?, 
                        entry_date = ?
                    WHERE unique_id = ?";

            $stmt = $mysqli->prepare($sql);
            if ($stmt === false) {
                http_response_code(500); // Internal Server Error
                echo json_encode(["error" => "Error preparing statement: " . $mysqli->error]);
                exit();
            }

            $stmt->bind_param(
                "ssssssssssssssi",
                $hostel_id,
                $hostel_name,
                $district_id,
                $taluk_id,
                $warden_name,
                $warden_id,
                $maintanance_no,
                $asset_category,
                $asset_name,
                $description,
                $spend_amount,
                $file_names,
                $file_org_names,
                $entry_date,
                $unique_id
            );

            $action_result = $stmt->execute();
            $stmt->close();
        } else {
            // Insert new record
            $sql = "INSERT INTO maintanance_creation 
                    (hostel_id, hostel_name, hostel_district, hostel_taluk, warden_name, warden_id, 
                    maintanance_no, asset_category, asset_name, description, spend_amount, 
                    file_name, file_org_name, entry_date)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $mysqli->prepare($sql);
            if ($stmt === false) {
                http_response_code(500); // Internal Server Error
                echo json_encode(["error" => "Error preparing statement: " . $mysqli->error]);
                exit();
            }

            $stmt->bind_param(
                "ssssssssssssss",
                $hostel_id,
                $hostel_name,
                $district_id,
                $taluk_id,
                $warden_name,
                $warden_id,
                $maintanance_no,
                $asset_category,
                $asset_name,
                $description,
                $spend_amount,
                $file_names,
                $file_org_names,
                $entry_date
            );

            $action_result = $stmt->execute();
            $stmt->close();
        }

        if ($action_result) {
            $status = true;
            $msg = $unique_id ? "update" : "create";
        } else {
            $status = false;
            $msg = "error";
        }

        $json_array = [
            "status" => $status,
            "msg" => $msg
        ];

        echo json_encode($json_array);

        break;

    case 'datatable':
        // DataTable Variables
        $search = $_POST['search']['value'];
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length == '-1' ? "" : $length;

        $data = [];

        $table = "maintanance_creation";

    
        $columns = [
            "@a:=@a+1 s_no",
            "entry_date",
            "maintanance_no",
            "(select facility_type from facility_type_creation where facility_type_creation.unique_id = maintanance_creation.asset_category) as asset_category",
            "(select facility_name from facility_creation where facility_creation.unique_id = maintanance_creation.asset_name) as asset_name",
            "(select district_name from district_name where district_name.unique_id = maintanance_creation.hostel_district) as hostel_district",
            "(select hostel_name from hostel_name where hostel_name.unique_id = maintanance_creation.hostel_name) as hostel_name",
            "file_name",
            // "status",

            "unique_id",
            "unique_id as unq_id"
        ];
        $table_details = $table . " , (SELECT @a:= ?) AS a ";

        $where = 'is_delete = ?';

        $bind_params = "ii";
        $bind_values = [$start,'0'];

        if (!empty($_POST['district_name'])) {
            $where .= " AND hostel_district = ?";
            $bind_params .= "s";
            $bind_values[] = $_POST['district_name'];
        }
        if (!empty($_POST['taluk_name'])) {
            $where .= " AND hostel_taluk = ?";
            $bind_params .= "s";
            $bind_values[] = $_POST['taluk_name'];
        }
        if (!empty($_POST['hostel_name'])) {
            $where .= " AND hostel_name = ?";
            $bind_params .= "s";
            $bind_values[] = $_POST['hostel_name'];
        }

        $sql = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns) . " FROM " . $table_details . " WHERE " . $where;

        if (!empty($limit)) {
            $sql .= " LIMIT ?, ?";
            $bind_params .= "ii"; // Add types for integer parameters
            $bind_values[] = intval($start);
            $bind_values[] = intval($length);
        }

        $stmt = $mysqli->prepare($sql);

        if ($stmt === false) {
            die('Error in preparing SQL statement: ' . $mysqli->error);
        }

        // Bind parameters if there are any
        if (!empty($bind_params)) {
            $stmt->bind_param($bind_params, ...$bind_values);
        }

        // Execute the statement
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch total records count using FOUND_ROWS()
        $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
        $total_records = $total_records_result->fetch_assoc()['total'];

        
        // $total_records = total_records();
        // print_r($result);

        if ($result) {

            $res_array = $result->fetch_all(MYSQLI_ASSOC);

            foreach ($res_array as $key => $value) {
                // $value['district_name'] = district_list($value['district_name']);
                // $value['is_active']     = is_active_show($value['is_active']);


                $value['file_name'] = image_view("adhmHostel", $value['unique_id'], $value['file_name']);
                $unique_id = $value['unique_id'];
                $value['unique_id'] = '<a class="btn btn-action specl2-icon"  href="javascript:view_app(\'' . $unique_id . '\')"><button type="button" ><i class="fa fa-eye" ></i></button></a>';


                $btn_delete = btn_delete($folder_name, $value['unq_id']);
                // $btn_update = btn_update($folder_name, $value['unq_id']);


                // $value['unq_id'] = $btn_update . $btn_delete;
                $value['unq_id'] = $btn_delete;


                $data[] = array_values($value);
            }
        

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data,
                // "testing"           => $result->sql
            ];
        }else {
            die('Query execution failed: ' . $mysqli->error);

        }
        
        echo json_encode($json_array);
        break;

    case 'delete':

        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        $unique_id = $_POST['unique_id'];

        // Update specific record
        $sql = "UPDATE maintanance_creation SET is_delete = 1 WHERE unique_id = ?";

        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            http_response_code(500); // Internal Server Error
            echo json_encode(["error" => "Error preparing statement: " . $mysqli->error]);
            exit();
        }

        $stmt->bind_param("s", $unique_id);

        $action_result = $stmt->execute();
        $stmt->close();

        if ($action_result) {
            $status = true;
            $msg = "success_delete";
        } else {
            $status = false;
            $msg = "error";
        }

        $json_array = [
            "status" => $status,
            "msg" => $msg
        ];

        echo json_encode($json_array);

        $mysqli->close();
        break;


    case 'get_asset_name':
        $asset_category = $_POST['asset_category'];
        $asset_category_options = facility_name("", $asset_category);

        $asset_name_options = select_option($asset_category_options, "Select Asset");
        // print_r($asset_name_options);
        echo $asset_name_options;

        break;


    case 'get_asset_count':
        // DataTable Variables
        $search = $_POST['search']['value'];
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;
        $asset_name = $_POST['asset_name'];

        $data = [];


        if ($length == '-1') {
            $limit = "";
        }

        // Query Variables
        $json_array = "";
        $columns = [
            "(select digital_infra_facility_sub.quantity from digital_infra_creation left join digital_infra_facility_sub on digital_infra_creation.unique_id = digital_infra_facility_sub.form_main_unique_id where digital_infra_creation.hostel_id = '" . $_SESSION['hostel_id'] . "' and digital_infra_facility_sub.facilities='" . $asset_name . "' and digital_infra_facility_sub.is_delete='0') as quantity",
        ];
        $table_details = [
            "digital_infra_creation",
            $columns
        ];
        $where = "is_delete = 0";
        $order_by = "";

        if ($_POST['search']['value']) {
            $where .= " AND district_name LIKE '" . mysql_like($_POST['search']['value']) . "' ";
        }

        // Datatable Searching
        $search = datatable_searching($search, $columns);



        $sql_function = "SQL_CALC_FOUND_ROWS";

        $result = $pdo->select($table_details, $where, $limit, $start, $order_by, $sql_function);
        // print_r($result);
        $total_records = total_records();

        if ($result->status) {

            $res_array = $result->data;

            foreach ($res_array as $key => $value) {
                // $value['district_name'] = district_list($value['district_name']);
                $quantity = $value['quantity'];

                $data[] = array_values($value);
            }

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data,
                "quantity" => $quantity,
                "testing" => $result->sql
            ];
        } else {
            print_r($result);
        }

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

// function total_records() {
//     global $mysqli, $table, $where;

//     $sql = "SELECT COUNT(*) AS total FROM " . $table . " WHERE " . $where;
//     $result = $mysqli->query($sql);
//     $row = $result->fetch_assoc();
//     return $row['total'];
// }
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
                    $image_view .= '<a href="javascript:print_view(\'/' . $doc_file_name . '\')"><img src="../adhmHostel/assets/images/images.png"  width="40%" ></a>';
                    // $image_view .= '<img src="uploads/'.$folder_name.'/'.$doc_name.'"  height="50px" width="50px" >';
                } else if ($cfile_name[1] == 'pdf') {
                    $image_view .= '<a href="javascript:print_pdf(\'/' . $doc_file_name . '\')"><i class="mdi mdi-file-pdf-box ci"    ></i></a>';
                } else if (($cfile_name[1] == 'pdf') || ($cfile_name[1] == 'xls') || ($cfile_name[1] == 'xlsx')) {
                    $image_view .= '<a href="javascript:print(\'/' . $doc_file_name . '\')"><img src="assets/images/excel.png"  height="30px" width="30px" ></a>';
                } else if (($cfile_name[1] == 'txt') || ($cfile_name[1] == 'docx') || ($cfile_name[1] == 'doc')) {
                    $image_view .= '<a href="javascript:print(\'/' . $doc_file_name . '\')"><img src="assets/images/word.png"  height="30px" width="30px" ></a>';
                }
            }
        }
    }

    return $image_view;
}




?>