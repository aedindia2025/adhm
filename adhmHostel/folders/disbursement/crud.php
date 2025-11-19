<?php
// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "disbursement_creation";

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

        $vali_hostel_name = filter_input(INPUT_POST, 'hostel_name', FILTER_SANITIZE_STRING);
        $vali_taluk_name = filter_input(INPUT_POST, 'taluk_name', FILTER_SANITIZE_STRING);
        $vali_district_name = filter_input(INPUT_POST, 'district_name', FILTER_SANITIZE_STRING);
        $vali_applied_date = filter_input(INPUT_POST, 'applied_date', FILTER_SANITIZE_STRING);
        $vali_disbursement_type = filter_input(INPUT_POST, 'disbursement_type', FILTER_SANITIZE_STRING);
        $vali_cur_month = filter_input(INPUT_POST, 'cur_month', FILTER_SANITIZE_STRING);
        $vali_connection_no = filter_input(INPUT_POST, 'connection_no', FILTER_SANITIZE_STRING);
        $vali_letter_no = filter_input(INPUT_POST, 'letter_no', FILTER_SANITIZE_STRING);
        $vali_letter_date = filter_input(INPUT_POST, 'letter_date', FILTER_SANITIZE_STRING);
        $vali_academic_year = filter_input(INPUT_POST, 'academic_year', FILTER_SANITIZE_STRING);
        $vali_login_user_id = filter_input(INPUT_POST, 'login_user_id', FILTER_SANITIZE_STRING);

        if (!$vali_hostel_name || !$vali_taluk_name || !$vali_district_name || !$vali_applied_date || !$vali_disbursement_type || !$vali_cur_month || !$vali_connection_no || !$vali_letter_no || !$vali_letter_date || !$vali_academic_year || !$vali_login_user_id) {
            $msg = "form_alert";
        } else {
            $hostel_name = sanitizeInput($_POST["hostel_name"]);
            $taluk_name = sanitizeInput($_POST["taluk_name"]);
            $district_name = sanitizeInput($_POST["district_name"]);
            $applied_date = sanitizeInput($_POST["applied_date"]);
            $disbursement_type = sanitizeInput($_POST["disbursement_type"]);
            $cur_month = sanitizeInput($_POST["cur_month"]);
            $connection_no = sanitizeInput($_POST["connection_no"]);
            $letter_no = sanitizeInput($_POST["letter_no"]);
            $letter_date = sanitizeInput($_POST["letter_date"]);
            $academic_year = sanitizeInput($_POST["academic_year"]);
            $unique_id = sanitizeInput($_POST["unique_id"]);
            $login_user_id = sanitizeInput($_POST["login_user_id"]);

            // File upload handling
            $allowedExts = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'xlsx', 'xls'];
            $extension = pathinfo($_FILES["test_file"]['name'], PATHINFO_EXTENSION);

 $file_names = '';
                $file_org_names = '';


if($_FILES["test_file"]['name']){
            if (in_array($extension, $allowedExts)) {
                $file_exp = explode(".", $_FILES["test_file"]['name']);
                $tem_name = random_strings(25) . "." . $file_exp[1];
                $upload_dir = '../../uploads/disbursement/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                move_uploaded_file($_FILES["test_file"]["tmp_name"], $upload_dir . $tem_name);
                $file_names = $tem_name;
                $file_org_names = $_FILES["test_file"]['name'];
            } else {
                $file_names = '';
                $file_org_names = '';
            }
}

            if ($unique_id) {
                // Update
                if ($file_org_names == '') {
                    $sql = "UPDATE $table SET hostel_name=?, taluk_name=?, district_unique_id=?, applied_date=?, disbursement_type=?, academic_year=?, month=?, connection_no=?, letter_no=?, letter_date=?, warden_name=? WHERE unique_id=?";
                    $stmt = $mysqli->prepare($sql);
                    if (!$stmt) {
                        error_log("Prepare failed: " . $mysqli->error);
                        die("Prepare failed: " . $mysqli->error);
                    }
                    $stmt->bind_param('ssssssssssss', $hostel_name, $taluk_name, $district_name, $applied_date, $disbursement_type, $academic_year, $cur_month, $connection_no, $letter_no, $letter_date, $login_user_id, $unique_id);
                } else {
                    $sql = "UPDATE $table SET hostel_name=?, taluk_name=?, district_unique_id=?, applied_date=?, disbursement_type=?, academic_year=?, month=?, connection_no=?, letter_no=?, letter_date=?, warden_name=?, disbursement_file=?, disbursement_org_name=? WHERE unique_id=?";
                    $stmt = $mysqli->prepare($sql);
                    if (!$stmt) {
                        error_log("Prepare failed: " . $mysqli->error);
                        die("Prepare failed: " . $mysqli->error);
                    }
                    $stmt->bind_param('ssssssssssssss', $hostel_name, $taluk_name, $district_name, $applied_date, $disbursement_type, $academic_year, $cur_month, $connection_no, $letter_no, $letter_date, $login_user_id, $file_names, $file_org_names, $unique_id);
                
                }
            } else {
                // Insert
                $sql = "INSERT INTO $table (hostel_name, taluk_name, district_unique_id, applied_date, disbursement_type, academic_year, month, connection_no, letter_no, letter_date, warden_name, disbursement_file, disbursement_org_name, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $mysqli->prepare($sql);
                if (!$stmt) {
                    error_log("Prepare failed: " . $mysqli->error);
                    die("Prepare failed: " . $mysqli->error);
                }
                $stmt->bind_param('ssssssssssssss', $hostel_name, $taluk_name, $district_name, $applied_date, $disbursement_type, $academic_year, $cur_month, $connection_no, $letter_no, $letter_date, $login_user_id, $file_names, $file_org_names, unique_id($prefix));
            }

            // Execute the statement
            try {
                $stmt->execute();
                $status = true;
                $data = $stmt->insert_id;
                $error = "";
                if ($unique_id) {
                    $msg = "update";
                } else {
                    $msg = "create";
                }
            } catch (mysqli_sql_exception $e) {
                $status = false;
                $data = null;
                $error = $e->getMessage();
                $msg = "error";
                error_log("Execute failed: " . $e->getMessage());
            }

            // Close statement
            $stmt->close();
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql" => $sql // Uncomment for debugging purposes
        ];

        echo json_encode($json_array);
        break;

        case 'datatable':
            // DataTable Variables
            $length = isset($_POST['length']) ? $_POST['length'] : 10;
            $start = isset($_POST['start']) ? $_POST['start'] : 0;
            $draw = isset($_POST['draw']) ? $_POST['draw'] : 1;
            $limit = $length;
        
            $data = [];
        
            if ($length == '-1') {
                $limit = "";
            }
        
            // Query Variables
            $json_array = "";
            $columns = [
                "@a:=@a+1 s_no",
                '(SELECT disbursement_type FROM disbursement_type AS dis WHERE dis.unique_id = ' . $table . '.disbursement_type ) AS disbursement_type',
                '(SELECT amc_year FROM academic_year_creation AS acc_year WHERE acc_year.unique_id = ' . $table . '.academic_year ) AS academic_year',
                "month",
                "connection_no",
                "letter_no",
                "applied_date",
                "disbursement_file",
                "unique_id"
            ];
            $sql_columns = implode(", ", $columns);
            $table_with_counter = $table . ", (SELECT @a:=?) AS a";
            $where = "is_delete = ? AND hostel_name = ?";
            $is_delete = 0;
        
            // Initialize total records variable
            $total_records = total_records();
        
            // Prepare SQL query
            $sql_query = "SELECT SQL_CALC_FOUND_ROWS {$sql_columns} FROM {$table_with_counter} WHERE {$where}";
        
            if ($limit !== "") {
                $sql_query .= " LIMIT ?, ?";
            }
        
            // Execute query with parameterized statements
            $stmt = $mysqli->prepare($sql_query);
            if ($stmt) {
                // Bind parameters
                if ($limit !== "") {
                    $stmt->bind_param("iiisi", $start, $is_delete, $_SESSION['hostel_id'], $start, $limit);
                } else {
                    $stmt->bind_param("iis", $start, $is_delete, $_SESSION['hostel_id']);
                }
        
                // Execute the query
                $stmt->execute();
                
                // Fetch the result
                $result = $stmt->get_result();
        
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        // Handle disbursement file
                        $disbursement_file = $row['disbursement_file'];
                        $row['disbursement_file'] = image_view("disbursement", $row['unique_id'], $disbursement_file);
        
                        // Handle buttons
                        $btn_update = btn_update($folder_name, $row['unique_id']);
                        $btn_delete = btn_delete($folder_name, $row['unique_id']);
                        $row['unique_id'] = $btn_update . $btn_delete;
        
                        $data[] = array_values($row);
                    }
        
                    // Fetch the total filtered records
                    $stmt_filtered = $mysqli->prepare("SELECT FOUND_ROWS()");
                    if ($stmt_filtered) {
                        $stmt_filtered->execute();
                        $stmt_filtered->bind_result($total_filtered);
                        $stmt_filtered->fetch();
                        $stmt_filtered->close();
                    } else {
                        $total_filtered = $total_records;
                    }
        
                    // Prepare JSON response
                    $json_array = [
                        "draw" => intval($draw),
                        "recordsTotal" => intval($total_filtered),
                        "recordsFiltered" => intval($total_filtered),
                        "data" => $data,
                    ];
                }
        
                $stmt->close();
                $mysqli->close();
            }
        
            // Output JSON response
            echo json_encode($json_array);
            break;
        

        case 'delete':

        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

            $unique_id = $_POST['unique_id'];
    
            $sql = "UPDATE $table SET is_delete = 1 WHERE unique_id = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param('s', $unique_id);
    
            // Execute the statement
            if ($stmt->execute()) {
                $status = true;
                $data = $stmt->affected_rows;
                $error = "";
                $msg = "success_delete";
            } else {
                $status = false;
                $data = null;
                $error = $stmt->error;
                $msg = "error";
            }
    
            // Close statement
            $stmt->close();
    
            $json_array = [
                "status" => $status,
                "data" => $data,
                "error" => $error,
                "msg" => $msg,
                // "sql" => $sql // Uncomment for debugging purposes
            ];
    
            echo json_encode($json_array);
            break;

    default:

        break;
}



function image_view($folder_name = "", $unique_id = "", $disbursement_file = "")
{
    // echo $dc_file_name;
    $file_names = explode(',', $disbursement_file);
    $image_view = '';

    if ($disbursement_file) {
        foreach ($file_names as $file_key => $disbursement_file) {
            if ($file_key != 0) {
                if ($file_key % 4 != 0) {
                    $image_view .= "&nbsp";
                } else {
                    $image_view .= "<br><br>";
                }
            }

            $cfile_name = explode('.', $disbursement_file);

            if ($disbursement_file) {

                if (($cfile_name[1] == 'jpg') || ($cfile_name[1] == 'png') || ($cfile_name[1] == 'jpeg')) {
                    $image_view .= '<a href="javascript:print_view(\'/' . $disbursement_file . '\')"><img src="uploads/' . $folder_name . '/' . $disbursement_file . '"  width="30%" style="margin-left: 15px;"></a>';
                    // $image_view .= '<img src="uploads/'.$folder_name.'/'.$doc_name.'"  height="50px" width="50px" >';
                } else if ($cfile_name[1] == 'pdf') {
                    $image_view .= '<a href="javascript:print_pdf(\'/' . $disbursement_file . '\')"><img src="assets/images/pdf.png"   width="20%" style="margin-left: 15px;" ></a>';
                } else if (($cfile_name[1] == 'pdf') || ($cfile_name[1] == 'xls') || ($cfile_name[1] == 'xlsx')) {
                    $image_view .= '<a href="javascript:print(\'/' . $disbursement_file . '\')"><img src="assets/images/excel.png"  height="30px" width="30px" style="margin-left: 15px;"></a>';
                } else if (($cfile_name[1] == 'txt') || ($cfile_name[1] == 'docx') || ($cfile_name[1] == 'doc')) {
                    $image_view .= '<a href="javascript:print(\'/' . $disbursement_file . '\')"><img src="assets/images/word.png"  height="30px" width="30px" style="margin-left: 15px;" ></a>';
                }
            }
        }
    }

    return $image_view;
}


?>