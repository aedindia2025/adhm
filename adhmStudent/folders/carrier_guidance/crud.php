<?php 

// Get folder Name From Currnent Url 
$folder_name        = explode("/",$_SERVER['PHP_SELF']);
$folder_name        = $folder_name[count($folder_name)-2];

// // Database Country Table Name
$table             = "carrier_guidance";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action             = $_POST['action'];

$feedback_type      = "";
$is_active          = "";
$unique_id          = "";
$prefix             = "";

$data               = "";
$msg                = "";
$error              = "";
$status             = "";
$test               = ""; // For Developer Testing Purpose

switch ($action) {
    
    case 'datatable':
        // DataTable Variables
        $search = $_POST['search']['value'];
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
            "carrier_title",
            "soc_media_link",
            "image_file_name",
            "doc_file_name",
            "video_file_name"
        ];
        $table_details = [
            $table . " , (SELECT @a:= ?) AS a ",
            $columns
        ];
        $where = "is_delete = ?";
        $order_by = "";

        $is_delete = "0";
    
        // Prepare SQL query
        $sql = "SELECT SQL_CALC_FOUND_ROWS " . implode(",", $columns) . " 
                FROM $table, (SELECT @a:= ?) AS a 
                WHERE $where 
                LIMIT ?, ?";
    
        $stmt = $mysqli->prepare($sql);
    
        // Bind parameters
        $param_start = $start;
        $param_limit = $limit;
        $stmt->bind_param("iiii", $param_start, $is_delete, $param_start, $param_limit);
    
        // Execute statement
        $stmt->execute();
        $result = $stmt->get_result();
    
        // Get total records
        $total_records = $mysqli->query("SELECT FOUND_ROWS() as total")->fetch_assoc()['total'];
    
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $row['sno'] = $sno++;
                $row['soc_media_link'] = "<a href='" . $row['soc_media_link'] . "' target='_blank'>" . $row['soc_media_link'] . "</a>";
                $row['doc_file_name'] = image_view("carrier_guidance", $row['unique_id'], $row['doc_file_name']);
    
                // Modal for Image
                $row['image_file_name'] = '<i class="mdi mdi-tooltip-image" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl' . $sno . '" type="button"></i>
                    <div class="modal fade bs-example-modal-xl' . $sno . '" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title w-100 text-center" id="myExtraLargeModalLabel">Image Attachment</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <img src="../adhmAdmin/uploads/carrier_guidance/images/' . $row['image_file_name'] . '" width="100%">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="javascript:void(0);" class="btn btn-link link-success fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->';
    
                // Modal for Video
                $row['video_file_name'] = '<i class="mdi mdi-play-circle" data-bs-toggle="modal" data-bs-target=".bs-example-modal-x2' . $sno . '" type="button"></i>
                    <div class="modal fade bs-example-modal-x2' . $sno . '" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title w-100 text-center" id="myExtraLargeModalLabel">Video Attachment</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mt-2">
                                        <video id="video-help" width="530" controls>
                                            <source id="videoPath" src="../adhmAdmin/uploads/carrier_guidance/videos/' . $row['video_file_name'] . '" type="video/mp4">
                                        </video>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="javascript:void(0);" class="btn btn-link link-success fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->';
    
                // Buttons for update and delete
                $btn_update = btn_update($folder_name, $row['unique_id']);
                $btn_delete = btn_delete($folder_name, $row['unique_id']);
                $row['unique_id'] = $btn_update . $btn_delete;
    
                $data[] = array_values($row);
            }
    
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data,
                // "testing" => $result->sql
            ];
        } else {
            echo json_encode(['error' => 'Failed to execute query: ' . $mysqli->error]);
            exit();
        }
    
        // Output JSON response
        echo json_encode($json_array);
    
        // Close statement and connection
        $stmt->close();
        $mysqli->close();
        break;
    
    
    case 'delete':
        
        $unique_id      = $_POST['unique_id'];

        $columns        = [
            "is_delete"   => 1
        ];

        $update_where   = [
            "unique_id"     => $unique_id
        ];

        $action_obj     = $pdo->update($table,$columns,$update_where);

        if ($action_obj->status) {
            $status     = $action_obj->status;
            $data       = $action_obj->data;
            $error      = "";
            $sql        = $action_obj->sql;
            $msg        = "success_delete";

        } else {
            $status     = $action_obj->status;
            $data       = $action_obj->data;
            $error      = $action_obj->error;
            $sql        = $action_obj->sql;
            $msg        = "error";
        }

        $json_array   = [
            "status"    => $status,
            "data"      => $data,
            "error"     => $error,
            "msg"       => $msg,
            "sql"       => $sql
        ];

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
                    $image_view .= '<a href="javascript:print_view(\'/' . $doc_file_name . '\')"><img src="uploads/' . $folder_name . '/' . $doc_file_name . '"  width="20%" ></a>';
                    // $image_view .= '<img src="uploads/'.$folder_name.'/'.$doc_name.'"  height="50px" width="50px" >';
                } else if ($cfile_name[1] == 'pdf') {
                    $image_view .= '<a href="javascript:downloadFile(\'/' . $doc_file_name . '\')"><i class="mdi mdi-file-pdf-box"></i></a>';
                } else if (($cfile_name[1] == 'pdf') || ($cfile_name[1] == 'xls') || ($cfile_name[1] == 'xlsx')) {
                    $image_view .= '<a href="javascript:downloadFile(\'/' . $doc_file_name . '\')"><img src="uploads/excel.png"  height="30px" width="30px" ></a>';
                } else if (($cfile_name[1] == 'txt') || ($cfile_name[1] == 'docx') || ($cfile_name[1] == 'doc')) {
                    $image_view .= '<a href="javascript:downloadFile(\'/' . $doc_file_name . '\')"><img src="uploads/word.png"  height="30px" width="30px" ></a>';
                }
            }
        }
    }

    return $image_view;
}


   
?>
