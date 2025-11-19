<?php

// Get folder Name From Currnent Url
$folder_name = explode('/', $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = 'event_handling';

// // Include DB file and Common Functions
include '../../config/dbconfig.php';
// File Upload Library Call
// $fileUpload         = new Alirdn\SecureUPload\SecureUPload( $fileUploadConfig );

// $fileUploadPath = $fileUploadConfig->get("upload_folder");

// // Create Folder in root->uploads->(this_folder_name) Before using this file upload
// $fileUploadConfig->set("upload_folder",$fileUploadPath. $folder_name . DIRECTzzORY_SEPARATOR);
// // Variables Declaration
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
        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            exit('CSRF validation failed.');
        }

        $cur_date = sanitizeInput($_POST['cur_date']);
        $event_name = sanitizeInput($_POST['event_name']);
        $remarks = sanitizeInput($_POST['remarks']);
        $is_active = sanitizeInput($_POST['is_active']);
        $unique_id = sanitizeInput($_POST['unique_id']);
        $allowedExts = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'xlsx', 'xls'];

        // Handle file uploads
        $file_names = '';
        $file_org_names = '';

        if (!empty($_FILES['test_file']['name'][0])) {
            $extension = pathinfo($_FILES['test_file']['name'], PATHINFO_EXTENSION);
            if (!in_array($extension, $allowedExts)) {
                exit('File type not allowed.');
            }
            $fileUpload = new FileUpload(); // Assuming FileUpload is your class for handling file uploads

            // Multi file Upload
            $confirm_upload = $fileUpload->uploadFiles('test_file');

            foreach ($confirm_upload as $c_key => $c_value) {
                if ($c_value->status == 1) {
                    $c_file_name = $c_value->name.'.'.$c_value->ext;
                    $_FILES['test_file']['file_name'][] = $c_file_name;
                    $_FILES['test_file']['name'][] = $c_value->name;
                } else {
                    $status = $confirm_upload->status;
                    $error = $confirm_upload->error;
                    $msg = 'file_error';
                    break;
                }
            }

            // Prepare file names for database storage
            $file_names = implode(',', $_FILES['test_file']['file_name']);
            $file_org_names = implode(',', $_FILES['test_file']['name']);
        }

        // Handle video upload
        $video_file_name = '';
        $video_file_org_name = '';

        if (!empty($_FILES['video_file']['name'])) {
            $allowedExts2 = ['mp4'];
            $extension = pathinfo($_FILES['video_file']['name'], PATHINFO_EXTENSION);
            $tem_name1 = random_strings(25).'.'.$extension;
            if (!in_array($extension, $allowedExts)) {
                exit('File type not allowed.');
            }

            if (move_uploaded_file($_FILES['video_file']['tmp_name'], '../../uploads/event_handling/videos/'.$tem_name1)) {
                $video_file_name = $tem_name1;
                $video_file_org_name = $_FILES['video_file']['name'];
            } else {
                $status = false;
                $error = 'Failed to move video file.';
                $msg = 'file_error';
            }
        }

        // Prepare columns for insertion or update
        if ($file_names != '') {
            $columns = [
                'cur_date' => $cur_date,
                'event_name' => $event_name,
                'remarks' => $remarks,
                'image_file_name' => $file_names,
                'image_file_org_name' => $file_org_names,
                'video_file_name' => $video_file_name,
                'video_file_org_name' => $video_file_org_name,
                'is_active' => $is_active,
            ];
        } else {
            $columns = [
                'cur_date' => $cur_date,
                'event_name' => $event_name,
                'remarks' => $remarks,
                'is_active' => $is_active,
            ];
        }

        // Prepare and execute SQL statement
        if ($unique_id) {
            // Update operation
            $sql = "UPDATE $table SET cur_date=?, event_name=?, remarks=?, is_active=?";

            // Append image and video fields conditionally
            if ($file_names != '') {
                $sql .= ', image_file_name=?, image_file_org_name=?';
            }
            if ($video_file_name != '') {
                $sql .= ', video_file_name=?, video_file_org_name=?';
            }

            $sql .= ' WHERE unique_id=?';

            $stmt = $mysqli->prepare($sql);

            // Bind parameters
            if ($file_names != '' && $video_file_name != '') {
                $stmt->bind_param('sssisssss', $cur_date, $event_name, $remarks, $is_active, $file_names, $file_org_names, $video_file_name, $video_file_org_name, $unique_id);
            } elseif ($file_names != '') {
                $stmt->bind_param('sssisss', $cur_date, $event_name, $remarks, $is_active, $file_names, $file_org_names, $unique_id);
            } elseif ($video_file_name != '') {
                $stmt->bind_param('sssisss', $cur_date, $event_name, $remarks, $is_active, $video_file_name, $video_file_org_name, $unique_id);
            } else {
                $stmt->bind_param('sssis', $cur_date, $event_name, $remarks, $is_active, $unique_id);
            }
        } else {
            // Insert operation
            $sql = "INSERT INTO $table (cur_date, event_name, remarks, image_file_name, image_file_org_name, video_file_name, video_file_org_name, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param('sssssssi', $cur_date, $event_name, $remarks, $file_names, $file_org_names, $video_file_name, $video_file_org_name, $is_active);
        }

        // Execute statement
        if ($stmt->execute()) {
            $status = true;
            $data = $stmt->affected_rows;
            $error = '';
            // $sql = $stmt->sqlstate;
            $msg = $unique_id ? 'update' : 'create';
        } else {
            $status = false;
            $data = [];
            $error = $stmt->error;
            $sql = $stmt->sqlstate;
            $msg = 'error';
        }

        // Prepare JSON response
        $json_array = [
            'status' => $status,
            'data' => $data,
            'error' => $error,
            'msg' => $msg,
            // "sql" => $sql
        ];

        echo json_encode($json_array);

        // Close statement and connection
        $stmt->close();
        $mysqli->close();

        break;

    case 'datatable':
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length == '-1' ? '' : intval($length);

        $hostel_name = isset($_POST['hostel_name']) ? $_POST['hostel_name'] : '';

        $data = [];

        // Query Variables
        $columns = [
            '@a:=@a+1 s_no',
            'cur_date',
            "(SELECT hostel_name FROM hostel_name WHERE unique_id = $table.hostel_name) AS hostel_name",
            'event_name',
            'image_file_name',
            'video_file_name',
            'unique_id',
        ];

        // SQL_CALC_FOUND_ROWS is used to get total count ignoring limit clause
        $sql_function = 'SQL_CALC_FOUND_ROWS';

        // Base SQL query
        $sql = "SELECT $sql_function ".implode(', ', $columns)." FROM $table, (SELECT @a:= ?) AS a";

        // WHERE clause
        $where = ' WHERE is_active = 1 AND is_delete = 0';
        $bind_params = 'i'; // For @a:= $start
        $bind_values = [$start];

        // Conditional bindings
        if (!empty($hostel_name)) {
            $where .= ' AND hostel_name = ?';
            $bind_params .= 's'; // Assuming hostel_name is a string
            $bind_values[] = $hostel_name;
        }

        // Complete SQL with WHERE clause
        $sql .= $where;

        // Add LIMIT clause if necessary
        if (!empty($limit)) {
            $sql .= ' LIMIT ?, ?';
            $bind_params .= 'ii'; // Assuming $start and $length are integers
            $bind_values[] = intval($start);
            $bind_values[] = intval($limit);
        }

        // Prepare the statement
        $stmt = $mysqli->prepare($sql);

        if ($stmt === false) {
            exit('Error in preparing SQL statement: '.$mysqli->error);
        }

        // Bind parameters
        $stmt->bind_param($bind_params, ...$bind_values);

        // Execute the statement
        $stmt->execute();

        // Handle errors if any
        if ($stmt->error) {
            exit('Execution error: '.$stmt->error);
        }

        // Get result set
        $result = $stmt->get_result();

        // Fetch total records count using FOUND_ROWS()
        $total_records_result = $mysqli->query('SELECT FOUND_ROWS() as total');
        $total_records = $total_records_result->fetch_assoc()['total'];

        // Process result set
        if ($result) {
            $sno = $start + 1; // Start serial number from the correct index

            while ($row = $result->fetch_assoc()) {
                $row['s_no'] = $sno;
                $row['cur_date'] = disdate($row['cur_date']);
                $row['event_name'] = disname($row['event_name']);

                // Process image file names if available
                if (isset($row['image_file_name']) && !empty($row['image_file_name'])) {
                    $image_file_names = explode(',', $row['image_file_name']);

                    // Build HTML for modal
                    $row['image_file_name'] = '<i class="mdi mdi-tooltip-image ci" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl2'.$sno.'" type="button"></i>
                    <div class="modal fade bs-example-modal-xl2'.$sno.'" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title w-100 text-center" id="myExtraLargeModalLabel">Image Attachment</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">';

                    // Loop through each image file name
                    foreach ($image_file_names as $image_file_name) {
                        $row['image_file_name'] .= '<div class="row mt-2">';
                        $row['image_file_name'] .= '<div class="col-md-6"><img src="../adhmHostel/uploads/event_handling/images/'.trim($image_file_name).'" class="img-fluid"></div>';
                        $row['image_file_name'] .= '</div>';
                    }

                    // Append the rest of modal HTML
                    $row['image_file_name'] .= '</div>
                                <div class="modal-footer">
                                    <a href="javascript:void(0);" class="btn btn-link link-success fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->';
                }

                // Process video file name if available
                if (isset($row['video_file_name']) && !empty($row['video_file_name'])) {
                    $row['video_file_name'] = '<i class="mdi mdi-play-circle ci" data-bs-toggle="modal" data-bs-target=".bs-example-modal-x3'.$sno.'" type="button"></i>
                    <div class="modal fade bs-example-modal-x3'.$sno.'" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title w-100 text-center" id="myExtraLargeModalLabel">Video Attachment</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mt-2">
                                        <video id="video-help-'.$sno.'" width="530" controls>
                                            <source id="videoPath" src="../adhmHostel/uploads/event_handling/videos/'.$row['video_file_name'].'" type="video/mp4">
                                        </video>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="javascript:void(0);" class="btn btn-link link-success fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->';
                }

                // Modify unique_id to include buttons
                $btn_update = btn_update($folder_name, $row['unique_id']);
                $btn_delete = btn_delete($folder_name, $row['unique_id']);
                $row['unique_id'] = $btn_update.$btn_delete;

                // Push modified row into data array
                $data[] = array_values($row);

                // Increment serial number
                ++$sno;
            }

            // Prepare JSON response
            $json_array = [
                'draw' => intval($draw),
                'recordsTotal' => intval($total_records),
                'recordsFiltered' => intval($total_records),
                'data' => $data,
                // "testing" => $stmt->sql // Uncomment for debugging purposes
            ];

            // Encode and output JSON
            echo json_encode($json_array);
        } else {
            // Handle query execution failure
            exit('Query execution failed: '.$mysqli->error);
        }

        // Close statement and connection
        $stmt->close();
        $mysqli->close();
        break;

    case 'approval_create':
        $id = $_POST['id'];
        $session_user_id = $_POST['session_user_id'];

        //    if($status=='1'){
        $columns = [
            'approve_by' => '625e8b34be81d50553',
            'status' => 1,
        ];

        //    }
        //    if($status == '2'){

        //     $update_columns = [
        //         // "approve_by" =>$session_user_id,
        //         "approve_by" => '625e8b34be81d50553',
        //         "status" => 2
        //      ];
        //    }

        // print_r($update_columns);

        $update_where = 'id="'.$id.'"';

        $action_obj_update = $pdo->update($table, $columns, $update_where);

        // Update Ends

        // print_r($action_obj_update);
        if ($action_obj_update->status) {
            $status = $action_obj_update->status;
            $data = $action_obj_update->data;
            $error = '';
            $sql = $action_obj_update->sql;

            $msg = 'update';
        } else {
            $status = $action_obj_update->status;
            $data = $action_obj_update->data;
            $error = $action_obj_update->error;
            $sql = $action_obj_update->sql;
            $msg = 'error';
        }
        // }
        $json_array = [
            'status' => $status,
            'data' => $data,
            'error' => $error,
            'msg' => $msg,
            // "sql"       => $sql
        ];
        echo json_encode($json_array);
        break;

    case 'district_name':
        $district_name = $_POST['district_name'];

        $district_name_options = taluk_name(' ', $district_name);

        $taluk_name_options = select_option($district_name_options, 'Select Taluk');

        echo $taluk_name_options;

        break;

    case 'get_hostel_by_taluk_name':
        $taluk_name = $_POST['taluk_name'];

        $hostel_name_options = hostel_name('', $taluk_name);

        $hostel_name_options = select_option_host($hostel_name_options, 'Select Hostel');

        echo $hostel_name_options;

        break;

    case 'delete':
        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            exit('CSRF validation failed.');
        }
        $unique_id = $_POST['unique_id'];

        // Prepare the SQL statement
        $stmt = $mysqli->prepare("UPDATE $table SET is_delete = 1 WHERE unique_id = ?");

        if ($stmt === false) {
            exit('Error in preparing SQL statement: '.$mysqli->error);
        }

        // Bind the parameter
        $stmt->bind_param('s', $unique_id);

        // Execute the statement
        $stmt->execute();

        // Check execution status
        if ($stmt->affected_rows > 0) {
            $status = true;
            $data = null;
            $error = '';
            $msg = 'success_delete';
        } else {
            $status = false;
            $data = null;
            $error = $stmt->error;
            $msg = 'error';
        }

        // Prepare JSON response
        $json_array = [
            'status' => $status,
            'data' => $data,
            'error' => $error,
            'msg' => $msg,
            // "sql"       => $sql
        ];

        // Encode and output JSON
        echo json_encode($json_array);

        // Close statement and connection
        $stmt->close();
        $mysqli->close();
        break;
    default:
        break;
}

// $user_type          = $_POST["user_type"];
// $is_active          = $_POST["is_active"];
// $unique_id          = $_POST["unique_id"];

// $update_where       = "";

// //count user_type
// if($unique_id == ''){
//     $get_user_type=$pdo_conn->prepare("SELECT count(*) FROM `user_type` WHERE `user_type`= '".$user_type."' and `is_delete` != '1'");
// }else{
//     $get_user_type=$pdo_conn->prepare("SELECT count(*) FROM `user_type` WHERE `user_type`= '".$user_type."' and `is_delete` != '1' and `unique_id` != '".$unique_id."'" );
// }

// $get_user_type->execute();
// $user_type_count  = $get_user_type->fetchColumn();

// if($user_type_count == 0){

//     if($unique_id == ''){//insert
//         $unique_id = uniqid().rand(10000,99999);

//         if($prefix) {
//             $unique_id = $prefix.$unique_id;
//         }

//         $Insql=$pdo_conn->prepare("insert into user_type (`unique_id`,`user_type`, `is_active`, `is_delete`)values('".$unique_id."','".$user_type."','1','0')");
//         $Insql->execute();
//         $msg = "Created";
//         echo $msg;
//     }else{//update
//         $Insql=$pdo_conn->prepare("UPDATE `user_type` SET `user_type`= '".$user_type."',`is_active`= '".$is_active."' WHERE unique_id = '".$unique_id."'");

//         $Insql->execute();
//         $msg  = "Updated";
//         echo $msg;
//     }
// }else{
//     $msg  = "already";
//     echo $msg;
// }
//     break;
//     case 'delete' :
//         $unique_id          = $_POST["unique_id"];
//         $del_sql=$pdo_conn->prepare("update user_type set is_delete='1' where unique_id ='".$unique_id."'" );
//         $del_sql->execute();
//             $msg  = "success_delete";
//             echo $msg;

//     break;
//     default:

//     break;
// }
