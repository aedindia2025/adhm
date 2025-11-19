<?php

// Get folder Name From Currnent Url

$folder_name = explode('/', $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// Database Country Table Name
$table = 'carrier_guidance';

// Include DB file and Common Functions
include '../../config/dbconfig.php';

// Variables Declaration
$action = $_POST['action'];

$feedback_type = '';
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
        // Validate CSRF token (assuming validateCSRFToken function is defined elsewhere)
        $token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
        if (!validateCSRFToken($token)) {
            exit('CSRF validation failed.');
        }

        // echo "dd".$_POST["unique_id"]; die();

        // Sanitize and retrieve POST data
        $cur_date = isset($_POST['cur_date']) ? sanitizeInput($_POST['cur_date']) : '';
        $carrier_title = isset($_POST['carrier_title']) ? sanitizeInput($_POST['carrier_title']) : '';
        $soc_media_link = isset($_POST['soc_media_link']) ? sanitizeInput($_POST['soc_media_link']) : '';
        $remarks = isset($_POST['remarks']) ? sanitizeInput($_POST['remarks']) : '';
        $is_active = isset($_POST['is_active']) ? sanitizeInput($_POST['is_active']) : '';
        $unique_id = isset($_POST['unique_id']) ? sanitizeInput($_POST['unique_id']) : '';
        $img_org = isset($_POST['img_org']) ? sanitizeInput($_POST['img_org']) : '';
        $doc_org = isset($_POST['doc_org']) ? sanitizeInput($_POST['doc_org']) : '';
        $video_org = isset($_POST['video_org']) ? sanitizeInput($_POST['video_org']) : '';
        $allowedExts = ['pdf', 'jpg', 'jpeg', 'PNG', 'png', 'gif', 'xlsx', 'xls'];

        // Handle file uploads
        $img_file_name = $img_org;
        $doc_file_name = $doc_org;
        $video_file_name = $video_org;

        if (!empty($_FILES['image_upload']['name'])) {
            $extension = pathinfo($_FILES['image_upload']['name'], PATHINFO_EXTENSION);

            if (!in_array($extension, $allowedExts)) {
                exit('image File type not allowed.');
            }
            $img_file_name = handleFileUpload($_FILES['image_upload'], '../../uploads/carrier_guidance/images/');
        }

        if (!empty($_FILES['doc_upload']['name'])) {
            $extension = pathinfo($_FILES['doc_upload']['name'], PATHINFO_EXTENSION);

            if (!in_array($extension, $allowedExts)) {
                exit('doc File type not allowed.');
            }
            $doc_file_name = handleFileUpload($_FILES['doc_upload'], '../../uploads/carrier_guidance/documents/');
        }

        if (!empty($_FILES['video_upload']['name'])) {
            $extension = pathinfo($_FILES['video_upload']['name'], PATHINFO_EXTENSION);
	    $allowedExtsVc = ['mp4', 'webm', 'ogg'];

            if (!in_array($extension, $allowedExtsVc)) {
                exit('video File type not allowed.');
            }
            $video_file_name = handleFileUpload($_FILES['video_upload'], '../../uploads/carrier_guidance/videos/');
        }

        // Prepare statement
        if ($unique_id) {
            // Update query
            $stmt = $mysqli->prepare("UPDATE $table SET cur_date=?, carrier_title=?, soc_media_link=?, remarks=?, image_file_name=?, doc_file_name=?, video_file_name=?, is_active=? WHERE unique_id=?");
            $stmt->bind_param('sssssssis', $cur_date, $carrier_title, $soc_media_link, $remarks, $img_file_name, $doc_file_name, $video_file_name, $is_active, $unique_id);
        } else {
            // Insert query
            $stmt = $mysqli->prepare("INSERT INTO $table (cur_date, carrier_title, soc_media_link, remarks, image_file_name, doc_file_name, video_file_name, is_active, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('sssssssis', $cur_date, $carrier_title, $soc_media_link, $remarks, $img_file_name, $doc_file_name, $video_file_name, $is_active, unique_id($prefix));
        }

        // Execute statement
        if ($stmt->execute()) {
            $msg = $unique_id ? 'update' : 'create';
            $status = 'success';
        } else {
            $msg = 'error';
            $status = 'error';
            $error = $mysqli->error;
        }

        $json_array = [
            'status' => $status,
            'msg' => $msg,
            'error' => isset($error) ? $error : null,
            // Add other data if needed
        ];

        echo json_encode($json_array);
        break;

    case 'datatable':
        // DataTable Variables
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length == '-1' ? '' : $length;

        $data = [];

        // Query Variables
        $json_array = '';
        $columns = [
            '@a:=@a+1 s_no',
            'cur_date',
            'carrier_title',
            'soc_media_link',
            'is_active',
            'unique_id',
        ];

        $table_details = $table.' , (SELECT @a:= ?) AS a ';
        $where = 'is_delete = 0';
        $order_by = ''; // You can modify this to add an order by clause if needed

        $sql_function = 'SQL_CALC_FOUND_ROWS';

        // SQL query for data fetching
        $sql = "SELECT $sql_function ".implode(', ', $columns)." FROM $table_details WHERE $where";
        if ($limit) {
            $sql .= ' LIMIT ?, ?';
        }

        $stmt = $mysqli->prepare($sql);
        if ($limit) {
            $stmt->bind_param('iii', $start, $start, $limit);
        } else {
            $stmt->bind_param('i', $start);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch total records
        $total_records_result = $mysqli->query('SELECT FOUND_ROWS() as total');
        $total_records = $total_records_result->fetch_assoc()['total'];

        if ($result) {
            $res_array = $result->fetch_all(MYSQLI_ASSOC);

            foreach ($res_array as $key => $value) {
                $value['cur_date'] = disdate($value['cur_date']);
                $value['carrier_title'] = disname($value['carrier_title']);
                $value['soc_media_link'] = dislink($value['soc_media_link']);
                $value['is_active'] = is_active_show($value['is_active']);

                $btn_update = btn_update($folder_name, $value['unique_id']);
                $btn_delete = btn_delete($folder_name, $value['unique_id']);

                $value['unique_id'] = $btn_update.$btn_delete;
                $data[] = array_values($value);
            }

            $json_array = [
                'draw' => intval($draw),
                'recordsTotal' => intval($total_records),
                'recordsFiltered' => intval($total_records),
                'data' => $data,
                'testing' => $result->sql,
            ];
        } else {
            // print_r($result);
            $json_array = [
                'draw' => intval($draw),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $stmt->error,
                'testing' => $stmt->sqlstate,
            ];
        }

        echo json_encode($json_array);
        break;

    case 'delete':
        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            exit('CSRF validation failed.');
        }

        $unique_id = $_POST['unique_id'];
        // $csrf_token = $_POST['csrf_token'];

        // Prepare statement
        $stmt = $mysqli->prepare("UPDATE $table SET is_delete=? WHERE unique_id=?");
        $is_delete = 1; // Assuming is_delete is set to 1 for deletion
        $stmt->bind_param('is', $is_delete, $unique_id);

        // Execute statement
        if ($stmt->execute()) {
            $status = 'success';
            $msg = 'success_delete';
        } else {
            $status = 'error';
            $msg = 'error';
            $error = $mysqli->error;
        }

        $stmt->close();

        $json_array = [
            'status' => $status,
            'data' => $data,
            'error' => $error,
            'msg' => $msg,
            // "sql" => $sql
        ];

        echo json_encode($json_array);

        break;

    default:
        break;
}

// function saveUploadedFile($file, $upload_dir, $file_ext) {
//     $tem_name = random_strings(25) . '.' . $file_ext;
//     move_uploaded_file($file['tmp_name'], $upload_dir . $tem_name);
//     return $tem_name;
// }

// function random_strings($length) {
//     // Implement your random string generation logic
//     // Example implementation using alphanumeric characters:
//     $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
//     $random_string = '';
//     for ($i = 0; $i < $length; $i++) {
//         $random_string .= $characters[rand(0, strlen($characters) - 1)];
//     }
//     return $random_string;
// }

function handleFileUpload($file, $upload_dir)
{
    $file_name = basename($file['name']);
    $target_file = $upload_dir.$file_name;
    $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

    // Validate file type and size (add more checks as needed)
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return $file_name;
    } else {
        exit('Error uploading file.');
    }
}
