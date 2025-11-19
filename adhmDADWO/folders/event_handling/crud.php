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

switch ($action) {
    case 'createupdate':

        $cur_date = $_POST["cur_date"];
        $event_name = $_POST["event_name"];

        $remarks = $_POST["remarks"];
        $is_active = $_POST["is_active"];
        $unique_id = $_POST["unique_id"];

        // // Image Upload
        // $extension = pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION);
        // // print_r($extension);die();

        // // if ($_FILES['image_file']['type'] == 'application/jpg' && in_array(strtolower($extension), $allowedExts)) {
        //     $tem_name = random_strings(25) . '.'.$extension.'';

        //     move_uploaded_file($_FILES['image_file']['tmp_name'], '../../uploads/event_handling/images/' . $tem_name);

        //     // Set $file_names to the stored filename with .pdf extension
        //     $img_file_name = $tem_name;
        //     $img_file_org_name = $_FILES['image_file']['name'];
        // // }
        // IMAGE START
        if (is_array($_FILES["test_file"]['name'])) {

            if ($_FILES["test_file"]['name'][0] != "") {

                // Multi file Upload 
                $confirm_upload = $fileUpload->uploadFiles("test_file");

                if (is_array($confirm_upload)) {
                    // print_r($_FILES["test_file"]['name']);
                    $_FILES["test_file"]['file_name'] = [];
                    foreach ($confirm_upload as $c_key => $c_value) {
                        if ($c_value->status == 1) {
                            $c_file_name = $c_value->name ? $c_value->name . "." . $c_value->ext : "";
                            array_push($_FILES["test_file"]['file_name'], $c_file_name);
                        } else {// if Any Error Occured in File Upload Stop the loop
                            $status = $confirm_upload->status;
                            $data = "file not uploaded";
                            $error = $confirm_upload->error;
                            $sql = "file upload error";
                            $msg = "file_error";
                            break;
                        }
                    }

                } else if (!empty($_FILES["test_file"]['name'])) {// Single File Upload
                    $confirm_upload = $fileUpload->uploadFile("test_file");

                    if ($confirm_upload->status == 1) {
                        $c_file_name = $confirm_upload->name ? $confirm_upload->name . "." . $confirm_upload->ext : "";
                        $_FILES["test_file"]['file_name'] = $c_file_name;
                    } else {// if Any Error Occured in File Upload Stop the loop
                        $status = $confirm_upload->status;
                        $data = "file not uploaded";
                        $error = $confirm_upload->error;
                        $sql = "file upload error";
                        $msg = "file_error";
                    }
                }
            }
        }

        // print_r($_FILES["test_file"]['name']);

        if (is_array($_FILES["test_file"]['name'])) {
            if ($_FILES["test_file"]['name'][0] != "") {
                $file_names = implode(",", $_FILES["test_file"]['file_name']);
                $file_org_names = implode(",", $_FILES["test_file"]['name']);
            }
        } else if (!empty($_FILES["test_file"]['name'])) {
            $file_names = $_FILES["test_file"]['file_name'];
            $file_org_names = $_FILES["test_file"]['name'];
        }
        // IMAGE END


        // // doc Upload
        // $allowedExts1 = array('pdf');
        // $extension = pathinfo($_FILES['doc_file']['name'], PATHINFO_EXTENSION);
        // if ($_FILES['doc_file']['type'] == 'application/pdf' && in_array(strtolower($extension), $allowedExts1)) {
        //     $tem_name = random_strings(25) . '.'.$extension.'';

        //     move_uploaded_file($_FILES['doc_file']['tmp_name'], '../../uploads/event_handling/documents/' . $tem_name);

        //     // Set $file_names to the stored filename with .pdf extension
        //     $doc_file_name = $tem_name;
        //     $doc_file_org_name = $_FILES['doc_file']['name'];
        // }

        // // video Upload
        $allowedExts2 = array('mp4');
        $extension = pathinfo($_FILES['video_file']['name'], PATHINFO_EXTENSION);

        // if ($_FILES['video_file']['type'] == 'application/mp4' && in_array(strtolower($extension), $allowedExts2)) {
        // print_r($_FILES['video_file']['name']);die();

        $tem_name1 = random_strings(25) . '.' . $extension . '';

        move_uploaded_file($_FILES['video_file']['tmp_name'], '../../uploads/event_handling/videos/' . $tem_name1);

        // Set $file_names to the stored filename with .pdf extension
        $video_file_name = $tem_name1;
        $video_file_org_name = $_FILES['video_file']['name'];
        // }

        $update_where = "";
        if ($file_names != '') {
            $columns = [
                "cur_date" => $cur_date,
                "event_name" => $event_name,
                "remarks" => $remarks,
                "image_file_name" => $file_names,
                "image_file_org_name" => $file_org_names,
                "video_file_name" => $video_file_name,
                "video_file_org_name" => $video_file_org_name,
                "is_active" => $is_active,
                "unique_id" => unique_id($prefix)
            ];
        }
        if ($file_names == '') {
            $columns = [
                "cur_date" => $cur_date,
                "event_name" => $event_name,
                "remarks" => $remarks,
                // "image_file_name"     => $file_names,
                // "image_file_org_name" => $file_org_names,
                // "video_file_name"     => $video_file_name,
                // "video_file_org_name" => $video_file_org_name,
                "is_active" => $is_active,
                "unique_id" => unique_id($prefix)
            ];
        }



        // Update Begins
        if ($unique_id) {

            unset($columns['unique_id']);

            $update_where = [
                "unique_id" => $unique_id
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

            if ($unique_id) {
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


        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql"       => $sql
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
            $where = "is_active = ? AND is_delete = ? AND district_unique_id = ?";
        
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
                    $stmt->bind_param("iiiisi", $start, $is_active, $is_delete, $_SESSION['district_id'], $start, $limit);
                } else {
                    $stmt->bind_param("iiis", $start, $is_active, $is_delete, $_SESSION['district_id']);
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
                        if (isset($row['image_file_name']) && !empty($row['image_file_name'])) {
                            $image_file_names = explode(",", $row['image_file_name']);
                            $modal_image_content = '';
        
                            foreach ($image_file_names as $image_file_name) {
                                $modal_image_content .= '<div class="row mt-2">';
                                $modal_image_content .= '<div class="col-md-6"><img src="../adhmHostel/uploads/event_handling/images/' . trim($image_file_name) . '" class="img-fluid"></div>';
                                $modal_image_content .= '</div>';
                            }
        
                            $row['image_file_name'] = '<i class="mdi mdi-tooltip-image" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl2' . $i . '" type="button"></i>
                                    <div class="modal fade bs-example-modal-xl2' . $i . '" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title w-100 text-center" id="myExtraLargeModalLabel">Image Attachment</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">' . $modal_image_content . '</div>
                                                <div class="modal-footer">
                                                    <a href="javascript:void(0);" class="btn btn-link link-success fw-medium" data-bs-dismiss="modal">
                                                        <i class="ri-close-line me-1 align-middle"></i> Close
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
                        }
        
                        // Handle video file names
                        $video_file_name = $row['video_file_name'];
                        $row['video_file_name'] = '<i class="mdi mdi-play-circle" data-bs-toggle="modal" data-bs-target=".bs-example-modal-x3' . $i . '" type="button"></i>
                                <div class="modal fade bs-example-modal-x3' . $i . '" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title w-100 text-center" id="myExtraLargeModalLabel">Video Attachment</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row mt-2">
                                                    <video id="video-help-' . $i . '" width="530" controls>
                                                        <source id="videoPath" src="../adhmHostel/uploads/event_handling/videos/' . $video_file_name . '" type="video/mp4">
                                                    </video>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <a href="javascript:void(0);" class="btn btn-link link-success fw-medium" data-bs-dismiss="modal">
                                                    <i class="ri-close-line me-1 align-middle"></i> Close
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
        
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
        
    case 'approval_create':


        $id = $_POST["id"];
        $session_user_id = $_POST["session_user_id"];


        //    if($status=='1'){
        $columns = [
            "approve_by" => '625e8b34be81d50553',
            "status" => 1
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

        $update_where = 'id="' . $id . '"';

        $action_obj_update = $pdo->update($table, $columns, $update_where);

        // Update Ends

        // print_r($action_obj_update);
        if ($action_obj_update->status) {
            $status = $action_obj_update->status;
            $data = $action_obj_update->data;
            $error = "";
            $sql = $action_obj_update->sql;

            $msg = "update";

        } else {
            $status = $action_obj_update->status;
            $data = $action_obj_update->data;
            $error = $action_obj_update->error;
            $sql = $action_obj_update->sql;
            $msg = "error";
        }
        // }
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql"       => $sql
        ];
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
            // "sql"       => $sql
        ];

        echo json_encode($json_array);
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
?>