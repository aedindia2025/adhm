<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "event_handling";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';
// File Upload Library Call
// $fileUpload         = new Alirdn\SecureUPload\SecureUPload( $fileUploadConfig );

// $fileUploadPath = $fileUploadConfig->get("upload_folder");

// // Create Folder in root->uploads->(this_folder_name) Before using this file upload
// $fileUploadConfig->set("upload_folder",$fileUploadPath. $folder_name . DIRECTORY_SEPARATOR);
// // // Variables Declaration
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

        $vali_cur_date = filter_input(INPUT_POST, 'cur_date', FILTER_SANITIZE_STRING);
        $vali_event_name = filter_input(INPUT_POST, 'event_name', FILTER_SANITIZE_STRING);
        $vali_remarks = filter_input(INPUT_POST, 'remarks', FILTER_SANITIZE_STRING);
        $vali_hostel_name = filter_input(INPUT_POST, 'hostel_name', FILTER_SANITIZE_STRING);

        if (!$vali_cur_date || !$vali_event_name || !$vali_remarks || !$vali_hostel_name) {
            $msg = "form_alert";
        } else {
            $cur_date = sanitizeInput($_POST["cur_date"]);
            $event_name = sanitizeInput($_POST["event_name"]);
            $remarks = sanitizeInput($_POST["remarks"]);
            $unique_id = sanitizeInput($_POST["unique_id"]);
            $hostel_name = sanitizeInput($_POST["hostel_name"]);
            $district_id = sanitizeInput($_POST["district_id"]);
            $taluk_id = sanitizeInput($_POST["taluk_id"]);
            
            // Image Upload
            $file_names = [];
            $file_org_names = [];
            
            foreach ($_FILES['test_file']['name'] as $key => $name) {
                $allowedExts = array('pdf', 'jpg', 'jpeg', 'png', 'gif', 'xlsx', 'xls');
                $extension = pathinfo($name, PATHINFO_EXTENSION);
if($_FILES['test_file']['name']){
            if (!in_array($extension, $allowedExts)) {
                die('File type not allowed.');
            }
}

                $extension = pathinfo($name, PATHINFO_EXTENSION);
             
                $temp_name = random_strings(25) . '.' . $extension;
                if (move_uploaded_file($_FILES['test_file']['tmp_name'][$key], '../../uploads/event_handling/images/' . $temp_name)) {
                    $file_names[] = $temp_name;
                    $file_org_names[] = $name;
                }
            }
            $file_names_str = implode(",", $file_names);
            $file_org_names_str = implode(",", $file_org_names);

            // Video Upload
            $allowedExt = array('mp4');
            $extension = pathinfo($_FILES['video_file']['name'], PATHINFO_EXTENSION);
if($_FILES['video_file']['name']){
            if (!in_array($extension, $allowedExt)) {
                die('File type not allowed.');
            }
}
            $tem_name1 = random_strings(25) . '.' . $extension;
            if (move_uploaded_file($_FILES['video_file']['tmp_name'], '../../uploads/event_handling/videos/' . $tem_name1)) {
                $video_file_name = $tem_name1;
                $video_file_org_name = $_FILES['video_file']['name'];
            } else {
                $video_file_name = '';
                $video_file_org_name = '';
            }



            if ($file_names_str != '') {
                $columns = [
                    "cur_date" => $cur_date,
                    "event_name" => $event_name,
                    "district_unique_id" => $district_id,
                    "taluk_unique_id" => $taluk_id,
                    "remarks" => $remarks,
                    "image_file_name" => $file_names_str,
                    "image_file_org_name" => $file_org_names_str,
                    "video_file_name" => $video_file_name,
                    "video_file_org_name" => $video_file_org_name,
                    "is_active" => 1,
                    "hostel_name" => $_SESSION['hostel_id'],
                    "user_id" => $_SESSION['sess_user_id'],
                    "unique_id" => unique_id($prefix)
                ];
            } else {
                $columns = [
                    "cur_date" => $cur_date,
                    "event_name" => $event_name,
                    "district_unique_id" => $district_id,
                    "taluk_unique_id" => $taluk_id,
                    "remarks" => $remarks,
                    "is_active" => 1,
                    "hostel_name" => $_SESSION['hostel_id'],
                    "user_id" => $_SESSION['sess_user_id'],
                    "unique_id" => $unique_id
                ];
            }

            if ($unique_id) {
                // Update query
                $update_query = "UPDATE $table SET cur_date=?, event_name=?, district_unique_id=?, taluk_unique_id=?, remarks=?,  hostel_name=?, user_id=? WHERE unique_id=?";
                $stmt = $mysqli->prepare($update_query);
                if ($stmt) {
                    $is_active = 1;
                    $stmt->bind_param("ssssssss", $cur_date, $event_name, $district_id, $taluk_id, $remarks, $_SESSION['hostel_id'], $_SESSION['sess_user_id'], $unique_id);
                    $stmt->execute();
                    $msg = "update";
                    $status = true;
                    $data = ["affected_rows" => $stmt->affected_rows];
                    $error = "";
                } else {
                    $msg = "error";
                }
            } else {
                // Insert query

                $insert_query = "INSERT INTO $table (cur_date, event_name, district_unique_id, taluk_unique_id, remarks, image_file_name, image_file_org_name, video_file_name, video_file_org_name,  hostel_name, user_id, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $mysqli->prepare($insert_query);
                if ($stmt) {
                    $is_active = 1;
                    $new_unique_id = unique_id($prefix); // Adjusting length
                    $stmt->bind_param("ssssssssssss", $cur_date, $event_name, $district_id, $taluk_id, $remarks, $file_names_str, $file_org_names_str, $video_file_name, $video_file_org_name, $_SESSION['hostel_id'], $_SESSION['sess_user_id'], $new_unique_id);
                    $stmt->execute();
                    $msg = "create";
                    $status = true;
                    $data = ["affected_rows" => $stmt->affected_rows];
                    $error = "";
                } else {
                    $msg = "error";
                   
                }
            }

            // Close statement and connection
            // $stmt->close();
            $mysqli->close();
        }

        // Return JSON response
        $json_array = [
            "status" => true,
            "data" => isset($data) ? $data : null,
            "error" => isset($error) ? $error : null,
            "msg" => $msg,
            // "url" => $url
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
        
            // SQL Query Variables
            $columns = [
                "@a:=@a+1 s_no",
                "cur_date",
                "(SELECT hostel_name FROM hostel_name WHERE unique_id = $table.hostel_name) as hostel_name",
                "event_name",
                "image_file_name",
                "video_file_name",
                "unique_id"
            ];
        $is_delete = "0";
        $is_active = "1";
            $sql_columns = implode(", ", $columns);
            $table_with_counter = $table . ", (SELECT @a:=?) AS a";
            $where = "is_active = ? AND is_delete = ? AND hostel_name = ?";
        
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
                    $stmt->bind_param("iiisii", $start, $is_active, $is_delete, $_SESSION['hostel_id'], $start, $limit);
                } else {
                    $stmt->bind_param("iiis", $start, $is_active, $is_delete, $_SESSION['hostel_id']);
                }
        
                // Execute the query
                $stmt->execute();
                
                // Fetch the result
                $result = $stmt->get_result();
                
                if ($result) {
                    $sno = 1;
                    while ($row = $result->fetch_assoc()) {
                        $i = $sno;
                        $row['cur_date'] = disdate($row['cur_date']);
                        $row['event_name'] = disname($row['event_name']);
        
                        $btn_update = btn_update($folder_name, $row['unique_id']);
                        $row['unique_id'] = $btn_update;
        
                        // Handle image file names
 // Modal for Image
                   $row['image_file_name'] = '<a href="#" onclick="showOverlay(\'image\', \'' . $row['image_file_name'] . '\'); return false;"><i class="mdi mdi-tooltip-image ci"></i></a>';


        
                        // Handle video file names
                        $video_file_name = $row['video_file_name'];
                        // Modal for Video
                   $row['video_file_name'] = '<a href="#" onclick="showOverlay(\'video\', \'' . $row['video_file_name'] . '\'); return false;"><i class="mdi mdi-play-circle ci"></i></a>';
        
                        $data[] = array_values($row);
                        $sno++;
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
            }
        
            // Output JSON response
            echo json_encode($json_array);
        
            break;
        

    case 'delete':

        $unique_id = $_POST['unique_id'];

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
            "sql" => $sql
        ];

        echo json_encode($json_array);
        break;

    default:

        break;
}


function image_view1($image_file_name)
{
    // print_r($image_file_name);
    $file_names = explode(',', $image_file_name);

    $image_view = '';

    if ($image_file_name) {
        foreach ($file_names as $file_key => $image_file_name) {

            if ($file_key != 0) {
                if ($file_key % 4 != 0) {
                    $image_view .= "&nbsp";
                } else {
                    $image_view .= "&nbsp";
                }
            }

            $cfile_name = explode('.', $image_file_name);
            // print_r($cfile_name);
            if ($image_file_name) {
                if (($cfile_name[1] == 'jpg') || ($cfile_name[1] == 'png') || ($cfile_name[1] == 'jpeg') || ($cfile_name[1] == 'jfif')) {

                    $image_view .= '<a href="javascript:print(\'uploads/event_handling/images/' . $image_file_name . '\')"><img src="uploads/event_handling/images/' . $image_file_name . '"  height="80px" width="77px" ></a>';

                }
            }

        }
    }
    // print_r($image_view);    
    return $image_view;
}
?>