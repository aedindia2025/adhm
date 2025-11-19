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

        $token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';

        // Validate CSRF token
        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        // Sanitize and validate incoming data
        $hostel_id = sanitizeInput($_POST['hostel_id']);
        $hostel_name = sanitizeInput($_POST['hostel_name']);
        $district_id = sanitizeInput($_POST['district_id']);
        $taluk_id = sanitizeInput($_POST['taluk_id']);
        $warden_name = sanitizeInput($_POST['warden_name']);
        $warden_id = sanitizeInput($_POST['warden_id']);
        $maintanance_no = sanitizeInput($_POST['maintanance_no']);
        $asset_category = sanitizeInput($_POST['asset_category']);
        $asset_name = sanitizeInput($_POST['asset_name']);
        $academic_year = sanitizeInput($_POST['academic_year']);
        $description = sanitizeInput($_POST['description']);
        $spend_amount = isset($_POST['spend_amount']) ? $_POST['spend_amount'] : 0;
        $unique_id = isset($_POST['unique_id']) ? sanitizeInput($_POST['unique_id']) : '';

        $file_names = '';
        $file_org_names = '';

        // Handle file upload
        if (!empty($_FILES['test_file']['name'])) {
            $file_name = $_FILES['test_file']['name'];
            $file_tmp = $_FILES['test_file']['tmp_name'];

            // Validate file extension (optional)
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf']; // Example of allowed extensions
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            if (!in_array($file_ext, $allowed_extensions)) {
                die('Invalid file type. Allowed types: jpg, jpeg, png, pdf');
            }

            // Generate unique file name
            $file_names = random_strings(25) . "." . $file_ext;

            // Move uploaded file to destination directory
            move_uploaded_file($file_tmp, '../../uploads/maintanance/' . $file_names);

            // Set original file name
            $file_org_names = $file_name;
        }

        // Prepare SQL statement
        if ($unique_id) {
            if ($file_names) {
                // Update existing record with file_name and file_org_name
                $sql = "UPDATE $table SET hostel_id=?, hostel_name=?, hostel_district=?, hostel_taluk=?, warden_name=?, warden_id=?, maintanance_no=?, asset_category=?, asset_name=?, academic_year=?, description=?, spend_amount=?, file_name=?, file_org_name=? WHERE unique_id=?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("sssssssssssssss", $hostel_id, $hostel_name, $district_id, $taluk_id, $warden_name, $warden_id, $maintanance_no, $asset_category, $asset_name, $academic_year, $description, $spend_amount, $file_names, $file_org_names, $unique_id);
            } else {
                // Update existing record without file_name and file_org_name
                $sql = "UPDATE $table SET hostel_id=?, hostel_name=?, hostel_district=?, hostel_taluk=?, warden_name=?, warden_id=?, maintanance_no=?, asset_category=?, asset_name=?, academic_year=?, description=?, spend_amount=? WHERE unique_id=?";
                //echo $sql;
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("sssssssssssss", $hostel_id, $hostel_name, $district_id, $taluk_id, $warden_name, $warden_id, $maintanance_no, $asset_category, $asset_name, $academic_year, $description, $spend_amount, $unique_id);
            }
        } else {
            // Insert new record
            $unique_id = unique_id($prefix);
            $entry_date = date('Y-m-d');
            $sql = "INSERT INTO $table (hostel_id, hostel_name, hostel_district, hostel_taluk, warden_name, warden_id, maintanance_no, asset_category, asset_name, academic_year, description, spend_amount, file_name, file_org_name, entry_date, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("ssssssssssssssss", $hostel_id, $hostel_name, $district_id, $taluk_id, $warden_name, $warden_id, $maintanance_no, $asset_category, $asset_name, $academic_year, $description, $spend_amount, $file_names, $file_org_names, $entry_date, $unique_id);
        }

        // Execute SQL statement
        if ($stmt->execute()) {
            $status = true;
            $msg = $unique_id ? 'update' : 'create';
        } else {
            $status = false;
            $error = $stmt->error;
            $msg = 'error';
        }

        // Close statement and connection
        $stmt->close();
        $mysqli->close();

        // Prepare JSON response
        $response = [
            'status' => $status,
            'msg' => $msg,
            'error' => isset($error) ? $error : ''
        ];

        echo json_encode($response);
        break;

    case 'datatable':
        // Database connection parameters
      

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
            "entry_date",
            "maintanance_no",
            "(SELECT facility_type FROM facility_type_creation WHERE facility_type_creation.unique_id = maintanance_creation.asset_category) AS asset_category",
            "(SELECT facility_name FROM facility_creation WHERE facility_creation.unique_id = maintanance_creation.asset_name) AS asset_name",
            "file_name",
            "unique_id"
        ];
        $table = "maintanance_creation"; // Replace with your actual table name
        $table_details = $table . " , (SELECT @a:= ?) AS a ";
        $where = "is_delete = 0 AND hostel_name = ?";
        $order_by = "";

       

        $sql_function = "SQL_CALC_FOUND_ROWS";

        // SQL query for data fetching
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_details WHERE $where";
        if (!empty($limit)) {
            $sql .= " LIMIT ?, ?";
        }

        $stmt = $mysqli->prepare($sql);

        // Bind parameters
        $param_types = "is"; // Types for the bind_param
        $bind_params = [$start, $_SESSION['hostel_id']]; // Parameters to bind


        if (!empty($limit)) {
            $param_types .= "ii";
            $bind_params[] = $start;
            $bind_params[] = $limit;
        }

        // Dynamically bind parameters
        if (!empty($bind_params)) {
            $stmt->bind_param($param_types, ...$bind_params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch total records
        $total_records_result = $mysqli->query("SELECT FOUND_ROWS() AS total");
        $total_records = $total_records_result->fetch_assoc()['total'];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                // Format status
                switch ($row['status']) {
                    case 1:
                        $row['status'] = 'Approved';
                        break;
                    case 2:
                        $row['status'] = 'Cancelled';
                        break;
                    case 0:
                        $row['status'] = 'Pending';
                        break;
                }

                // Modify file_name if needed (e.g., for image_view function)
                $row['file_name'] = image_view("adhmHostel", $row['unique_id'], $row['file_name']);

                // Generate action buttons
                $btn_update = btn_update($folder_name, $row['unique_id']);
                $btn_delete = btn_delete($folder_name, $row['unique_id']);
                $row['unique_id'] = $btn_update . $btn_delete; // Append action buttons to unique_id field

                $data[] = array_values($row); // Add row data to $data array
            }

            // Construct JSON response for DataTables
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
                "error" => $mysqli->error // Provide error details if any
            ];
        }

        echo json_encode($json_array); // Output JSON response

        // Close statement and connection
        $stmt->close();
        $mysqli->close();
        break;


    case 'delete':

        $unique_id = $_POST['unique_id'];

        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

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
            // "sql" => $sql
        ];

        echo json_encode($json_array);
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
                // "testing" => $result->sql
            ];
        } else {
            // print_r($result);
        }

        echo json_encode($json_array);
        break;





    default:

        break;
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
                    $image_view .= '<a href="javascript:print_view(\'/' . $doc_file_name . '\')"><img src="assets/images/images.png"   class="res-img" ></a>';
                    // $image_view .= '<img src="uploads/'.$folder_name.'/'.$doc_name.'"  height="50px" width="50px" >';
                } else if ($cfile_name[1] == 'pdf') {
                    $image_view .= '<a href="javascript:print_pdf(\'/' . $doc_file_name . '\')"><img src="assets/images/pdf.png"  class="res-img"  ></a>';
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