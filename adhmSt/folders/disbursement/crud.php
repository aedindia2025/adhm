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
$action = $_POST['action'];

$feedback_type = "";
$is_active = "";
$unique_id = "";
$prefix = "";

$data = "";
$msg = "";
$error = "";
$status = "";
$test = ""; // For Developer Testing Purpose
$login_user_id = $_SESSION["user_id"];

switch ($action) {
    case 'createupdate':
        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        // Validate and sanitize inputs
        $vali_tah_letter_no = filter_input(INPUT_POST, 'tah_letter_no', FILTER_SANITIZE_STRING);
        $vali_tah_letter_date = filter_input(INPUT_POST, 'tah_letter_date', FILTER_SANITIZE_STRING);
        $vali_tah_login_user_id = filter_input(INPUT_POST, 'tah_login_user_id', FILTER_SANITIZE_STRING);

        if (!$vali_tah_letter_no || !$vali_tah_letter_date || !$vali_tah_login_user_id) {
            $msg = "form_alert";
        } else {
            // Sanitize inputs
            $tah_letter_no = sanitizeInput($_POST["tah_letter_no"]);
            $tah_letter_date = $_POST["tah_letter_date"];
            $unique_id = $_POST["unique_id"];
            $tah_login_user_id = sanitizeInput($_POST["tah_login_user_id"]);


            $file_names = "";
            $file_org_names = "";

            if (!empty($_FILES['test_file']['name'])) {
                $allowedExts = array('pdf', 'jpg', 'jpeg', 'png', 'gif', 'xlsx', 'xls');
                $extension = pathinfo($_FILES['test_file']['name'], PATHINFO_EXTENSION);

                if (in_array($extension, $allowedExts)) {
                    $tem_name = random_strings(25) . "." . $extension;
                    move_uploaded_file($_FILES["test_file"]["tmp_name"], '../../uploads/disbursement/' . $tem_name);
                    $file_names = $tem_name;
                    $file_org_names = $_FILES["test_file"]['name'];
                } else {
                    $msg = "error";
                    die('File type not allowed.');
                }
            }

            // Prepare SQL statement
            if ($unique_id) {
                // Update existing record
                if (!empty($_FILES['test_file']['name'])) {
                    $sql = "UPDATE $table SET tah_letter_no=?, tah_letter_date=?, tahsildar_name=?, tah_rec_file=?, tah_rec_org_name=? WHERE unique_id=?";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param("ssssss", $tah_letter_no, $tah_letter_date, $tah_login_user_id, $file_names, $file_org_names, $unique_id);
                } else {
                    $sql = "UPDATE $table SET tah_letter_no=?, tah_letter_date=?, tahsildar_name=? WHERE unique_id=?";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param("ssss", $tah_letter_no, $tah_letter_date, $tah_login_user_id, $unique_id);
                }
            } else {
                // Insert new record
                if (!empty($_FILES['test_file']['name'])) {
                    $sql = "INSERT INTO $table (tah_letter_no, tah_letter_date, tahsildar_name, tah_rec_file, tah_rec_org_name) VALUES (?, ?, ?, ?, ?)";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param("sssss", $tah_letter_no, $tah_letter_date, $tah_login_user_id, $file_names, $file_org_names);
                } else {
                    $sql = "INSERT INTO $table (tah_letter_no, tah_letter_date, tahsildar_name) VALUES (?, ?, ?)";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param("sss", $tah_letter_no, $tah_letter_date, $tah_login_user_id);
                }
            }

            // Execute SQL statement
            if ($stmt->execute()) {
                if ($unique_id) {
                    $msg = "update";
                } else {
                    $msg = "create";
                }
            } else {
                $msg = "error";
            }

            $stmt->close();
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
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
            '(SELECT disbursement_type FROM disbursement_type AS dis WHERE dis.unique_id = ' . $table . '.disbursement_type ) AS disbursement_type',
            '(SELECT amc_year FROM academic_year_creation AS acc_year WHERE acc_year.unique_id = ' . $table . '.academic_year ) AS academic_year',
            "month",
            "connection_no",
            "letter_no",
            "letter_date",
            // "is_active",
            "unique_id"
        ];
        $table_details = $table . " , (SELECT @a:= ?) AS a ";
        $where = "is_delete = 0 and taluk_name = ?";
        $order_by = ""; // You can modify this to add an order by clause if needed
$taluk_name = $_SESSION['taluk_id'];
        $sql_function = "SQL_CALC_FOUND_ROWS";

        // SQL query for data fetching
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_details WHERE $where";
        if ($limit) {
            $sql .= " LIMIT ?, ?";
        }

        $stmt = $mysqli->prepare($sql);
        if ($limit) {
            $stmt->bind_param("isii", $start, $taluk_name, $start, $limit);
        } else {
            $stmt->bind_param("is", $start, $taluk_name);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch total records
        $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
        $total_records = $total_records_result->fetch_assoc()['total'];



        if ($result) {

            $res_array = $result->fetch_all(MYSQLI_ASSOC);

            foreach ($res_array as $key => $value) {
                // $value['feedback'] = disname($value['feedback']);
                // $value['description'] = disname($value['description']);
                // $value['is_active'] = is_active_show($value['is_active']);
                $where_1 = "dadwo_letter_no != ''";

                $table_1 = "stock_position_main";

                $columns_1 = [
                    'COUNT(unique_id) AS count'
                ];

                $table_details_1 = [
                    $table,
                    $columns_1
                ];

                $result_values_12 = $pdo->select($table_details_1, $where_1);
                // print_r($result_values_12);die();
                $data_st = $result_values_12->data;

                $btn_update = btn_update($folder_name, $value['unique_id']);
                $btn_delete = btn_delete($folder_name, $value['unique_id']);


                $value['unique_id'] = $btn_update . $btn_delete;
                $data[] = array_values($value);
            }

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data,
                // "testing"           => $result->sql
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

        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

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


    default:

        break;
}


?>