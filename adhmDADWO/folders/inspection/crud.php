<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "inspection";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action = $_POST['action'];
$ses_userid = $_SESSION["user_id"];

$district_name = "";
$taluk_name = "";
$inspection_date = "";
$hostel_name = "";
$desc_text = "";
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

        // Sanitize and validate input
        $vali_user_name = filter_input(INPUT_POST, 'user_name', FILTER_SANITIZE_STRING);
        $vali_user_type = filter_input(INPUT_POST, 'user_type', FILTER_SANITIZE_STRING);
        $vali_district_name = filter_input(INPUT_POST, 'district_name', FILTER_SANITIZE_STRING);
        $vali_taluk_name = filter_input(INPUT_POST, 'taluk_name', FILTER_SANITIZE_STRING);
        $vali_inspection_date = filter_input(INPUT_POST, 'inspection_date', FILTER_SANITIZE_STRING);
        $vali_hostel_name = filter_input(INPUT_POST, 'hostel_name', FILTER_SANITIZE_STRING);
        $vali_description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
        $vali_inspection_id = filter_input(INPUT_POST, 'inspection_id', FILTER_SANITIZE_STRING);

        if (!$vali_user_name || !$vali_user_type || !$vali_district_name || !$vali_taluk_name || !$vali_inspection_date || !$vali_hostel_name || !$vali_description || !$vali_inspection_id) {
            $msg = "form_alert";
        } else {

           
            // Sanitize inputs
            $user_name = sanitizeInput($_POST["user_name"]);
            $user_type = sanitizeInput($_POST["user_type"]);
            $district_name = sanitizeInput($_POST["district_name"]);
            $taluk_name = sanitizeInput($_POST["taluk_name"]);
            $inspection_date = sanitizeInput($_POST["inspection_date"]);
            $hostel_name = sanitizeInput($_POST["hostel_name"]);
            $description = sanitizeInput($_POST["description"]);
            $inspection_id = sanitizeInput($_POST["inspection_id"]);
            $unique_id = $_POST["unique_id"];
            $allowedExts = array('pdf', 'jpg', 'jpeg', 'png', 'gif', 'xlsx', 'xls');

            // $allowedExts = array('pdf');

            if (!empty($_FILES['test_file']['name'])) {
                $extension = pathinfo($_FILES["test_file"]['name'], PATHINFO_EXTENSION);

                if (!in_array($extension, $allowedExts)) {
                    die('File type not allowed.');
                }

                if (in_array($extension, $allowedExts)) {
                    $file_exp = explode(".", $_FILES["test_file"]['name']);
                    $tem_name = random_strings(25) . "." . $file_exp[1];
                    move_uploaded_file($_FILES["test_file"]["tmp_name"], '../../../uploads/inspection/' . $tem_name);

                    $file_names = $tem_name;
                    $file_org_names = $_FILES["test_file"]['name'];
                } else {
                    $file_names = null;
                    $file_org_names = null;
                }
            } else {
                $file_names = null;
                $file_org_names = null;
            }

            // Check if the record already exists
            $sql = 'SELECT COUNT(unique_id) AS count FROM ' . $table . ' WHERE inspection_id = ? AND is_delete = 0';
            if ($unique_id) {
                $sql .= ' AND unique_id != ?';
            }

            $stmt = $mysqli->prepare($sql);

            if ($unique_id) {
                $stmt->bind_param("ss", $inspection_id, $unique_id);
            } else {
                $stmt->bind_param("s", $inspection_id);
            }

            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();

            // print_r($data);die();
            if ($data["count"] > 0) {
                $status = true;
                $msg = "already";
                $data = [];
                $error = "";
                $sqlstate = $stmt->sqlstate;
            } else {
                if ($unique_id) {
                    $sql = 'UPDATE ' . $table . ' SET user_name = ?, user_type = ?, district_name = ?, taluk_name = ?, inspection_id = ?, inspection_date = ?, hostel_name = ?, description = ?';
                    if (!empty($_FILES['test_file']['name'])) {
                        $sql .= ', file_name = ?, file_org_names = ?';
                    }
                    $sql .= ' WHERE unique_id = ?';
                    $stmt = $mysqli->prepare($sql);

                    if (!empty($_FILES['test_file']['name'])) {
                        $stmt->bind_param("sssssssssss", $user_name, $user_type, $district_name, $taluk_name, $inspection_id, $inspection_date, $hostel_name, $description, $file_names, $file_org_names, $unique_id);
                    } else {
                        $stmt->bind_param("sssssssss", $user_name, $user_type, $district_name, $taluk_name, $inspection_id, $inspection_date, $hostel_name, $description, $unique_id);
                    }
                } else {
                    $sql = 'INSERT INTO ' . $table . ' (user_name, user_type, district_name, taluk_name, inspection_id, inspection_date, hostel_name, description, unique_id';
                    if (!empty($_FILES['test_file']['name'])) { 
                        $sql .= ', file_name, file_org_names';
                    }
                    $sql .= ') VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?';
                    if (!empty($_FILES['test_file']['name'])) {
                        $sql .= ', ?, ?';
                    }
                    $sql .= ')';
                    $stmt = $mysqli->prepare($sql);

                    if (!empty($_FILES['test_file']['name'])) {
                        $stmt->bind_param("sssssssssss", $user_name, $user_type, $district_name, $taluk_name, $inspection_id, $inspection_date, $hostel_name, $description, unique_id($prefix), $file_names, $file_org_names);
                    } else {
                        $stmt->bind_param("sssssssss", $user_name, $user_type, $district_name, $taluk_name, $inspection_id, $inspection_date, $hostel_name, $description, unique_id($prefix));
                    }
                }

                if ($stmt->execute()) {
                    $status = true;
                    $data = $stmt->affected_rows;
                    $error = "";
                    $sqlstate = $stmt->sqlstate;
                    $msg = $unique_id ? "update" : "create";
                } else {
                    $status = false;
                    $data = [];
                    $error = $stmt->error;
                    $sqlstate = $stmt->sqlstate;
                    $msg = "error";
                }
            }

            $json_array = [
                "status" => $status,
                "data" => $data,
                "error" => $error,
                "msg" => $msg,
                "sql" => $sqlstate
            ];

            echo json_encode($json_array);

            // Close connection
            $stmt->close();
            $mysqli->close();
        }

        break;


        case 'datatable':

           
            // DataTable Variables
            $length = isset($_POST['length']) ? $_POST['length'] : '';
            $start = isset($_POST['start']) ? $_POST['start'] : '';
            $draw = isset($_POST['draw']) ? $_POST['draw'] : '';
            $limit = $length;
        
            // Ensure session user_id is safely retrieved
            $ses_userid = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : '';
        
            $data = [];
        
            if ($length == '-1') {
                $limit = "";
            }
        
            // Query Variables
            $columns = [
                "@a:=@a+1 s_no",
                "inspection_date",
                "inspection_id",
                "(SELECT hostel_name FROM hostel_name WHERE unique_id = {$table}.hostel_name ) AS hostel_name",
                "description",
                "file_name",
                "unique_id"
            ];
        
            $table_details = "{$table} , (SELECT @a:= ?) AS a";
            $where = "is_delete = ? AND user_name = ?";
            $order_by = "";
            $is_delete = "0";
        
            // Prepare SQL query
            $sql = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns) . "
                    FROM {$table} , (SELECT @a:= ?) AS a
                    WHERE {$where}
                    LIMIT ?, ?";
            // print_r($sql);
        
            // Prepare and bind parameters
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("issss", $start, $is_delete, $ses_userid, $start, $limit);
            // print_r($stmt);
        
            // Execute statement
            $stmt->execute();
        
            // Bind result variables
            $stmt->bind_result($s_no, $inspection_date, $inspection_id, $hostel_name, $description, $file_name, $unique_id);
        
            // Fetch results and process
            $data = [];
            while ($stmt->fetch()) {
                $row = [
                    "s_no" => $s_no,
                    "inspection_date" => disdate($inspection_date), // Assuming disdate function formats date
                    "inspection_id" => $inspection_id,
                    "hostel_name" => $hostel_name,
                    "description" => $description,
                    "file_name" => image_view($file_name), // Assuming image_view function handles file display
                    "unique_id" => $unique_id
                ];
        
                // Customize actions based on unique_id condition
                $btn_update = btn_update($folder_name, $row['unique_id']);
                $btn_delete = btn_delete($folder_name, $row['unique_id']);
                $eye_button = '<a class="btn btn-action specl2"  href="javascript:leave_print(\'' . $row['unique_id'] . '\')"><button type="button" ><i class="fa fa-eye" ></i></button></a>';
        
                // Example condition for disabling update and delete buttons
                if ($row['unique_id'] == "5f97fc3257f2525529") {
                    $btn_update = "";
                    $btn_delete = "";
                }
        
                // Combine action buttons into unique_id field
                $row['unique_id'] = $btn_update . $btn_delete . $eye_button;
        
                // Push row to data array
                $data[] = array_values($row);
            }
            // Total records count
    $total_records = $mysqli->query("SELECT FOUND_ROWS()")->fetch_row()[0];
        
            // Prepare JSON response
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records), // Ensure total_records() returns total count
                "recordsFiltered" => intval($total_records), // Same as total_records() for simplicity
                "data" => $data,
                // "testing" => $stmt->sql // Uncomment for debugging purposes
            ];
        
            // Output JSON response
            echo json_encode($json_array);
        
            // Close statement and connection
            $stmt->close();
            $mysqli->close();
            break;
        


    case 'get_hostel_by_taluk_name':

        $taluk_name = $_POST['taluk_name'];


        $hostel_name_options = hostel_name('', $taluk_name);

        $hostel_name_options = select_option_host($hostel_name_options, "Select Hostel");

        echo $hostel_name_options;

        break;



    case 'delete':

        $token = $_POST['csrf_token'];

            if (!validateCSRFToken($token)) {
                die('CSRF validation failed.');
            }


        $unique_id = $_POST['unique_id'];

        // Database connection details
        $servername = "localhost";
        $username = "root";
        $password = "4/rb5sO2s3TpL4gu";
        $dbname = "adi_dravidar";

        // Create connection
        $mysqli = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Prepare the SQL statement for updating the record
        $sql = "UPDATE $table SET is_delete = ? WHERE unique_id = ?";
        $stmt = $mysqli->prepare($sql);

        if ($stmt) {
            $is_delete = 1;
            $stmt->bind_param("is", $is_delete, $unique_id);

            if ($stmt->execute()) {
                $status = true;
                $data = $stmt->affected_rows;
                $error = "";
                $sqlstate = $stmt->sqlstate;
                $msg = "success_delete";
            } else {
                $status = false;
                $data = [];
                $error = $stmt->error;
                $sqlstate = $stmt->sqlstate;
                $msg = "error";
            }

            $stmt->close();
        } else {
            $status = false;
            $data = [];
            $error = $mysqli->error;
            $sqlstate = $mysqli->sqlstate;
            $msg = "error";
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            "sql" => $sqlstate
        ];

        echo json_encode($json_array);

        // Close connection
        $mysqli->close();

        break;

    // case 'hostel_to_taluk':

    //     $unique_id          = $_POST['hostel_name'];
    //     $where      = [
    //         "unique_id" => $unique_id
    //     ];

    //     $table_hostel      =  "hostel_name";

    //     $columns    = [
    //         "district_name",
    //         "taluk_name",
    //     ];

    //     $table_details   = [
    //         $table_hostel,
    //         $columns
    //     ];

    //     $result_values  = $pdo->select($table_details, $where);
    //     // print_r($result_values);
    //     if ($result_values->status) {

    //         $result_values              = $result_values->data[0];
    //         // $data       = $action_obj->data;
    //         // $district_name              = $result_values[0]["district_name"];
    //         // $taluk_name                 = $result_values[0]["taluk_name"];
    //     }
    //     // print_r($result_values);


    //     break;

    case 'hostel_to_taluk':

        $hostel_name = $_POST["hostel_name"];
        // print_r($hostel_name);
        $details = [
            "district_name" => "",
            "taluk_name" => "",

        ];

        if ($hostel_name) {
            $staff_where = [
                "unique_id" => $hostel_name
            ];

            $staff_columns = [
                "district_name",
                "taluk_name",

            ];

            $staff_table_details = [
                "hostel_name",
                $staff_columns
            ];

            $staff_details = $pdo->select($staff_table_details, $staff_where);

            if ($staff_details->status) {
                if (!empty($staff_details->data)) {
                    $details = $staff_details->data[0];
                }
            } else {
                // print_r($staff_details);
            }
        }

        echo json_encode($details);
        //print_r($staff_details);
        break;

    case 'get_hostel_name':

        $taluk_name = $_POST['taluk_name'];

        $taluk_options = hostel_name("", $taluk_name);

        $hostel_name_options = select_option($taluk_options, "Select Hostel");

        echo $hostel_name_options;

        break;

    default:

        break;
}

function image_view($file_name = "")
{
    // echo $file_name;
    $file_names = explode(',', $file_name);
    $image_view = '';

    if ($file_name) {
        foreach ($file_names as $file_key => $file_name) {
            if ($file_key != 0) {
                if ($file_key % 4 != 0) {
                    $image_view .= "&nbsp";
                } else {
                    $image_view .= "<br><br>";
                }
            }

            $cfile_name = explode('.', $file_name);

            if ($file_name) {

                if (($cfile_name[1] == 'jpg') || ($cfile_name[1] == 'png') || ($cfile_name[1] == 'jpeg')) {
                    $image_view .= '<a href="javascript:print_view(\'/' . $file_name . '\')"><img src="../assets/images/images.png"  width="20%" ></a>';
                    // $image_view .= '<img src="uploads/'.$folder_name.'/'.$doc_name.'"  height="50px" width="50px" >';
                } else if ($cfile_name[1] == 'pdf') {
                    $image_view .= '<a href="javascript:print_pdf(\'/' . $file_name . '\')"><img src="../assets/images/pdf.png"   width="20%" style="margin-left: 15px;" ></a>';
                }

            }
        }
    }

    return $image_view;
}
