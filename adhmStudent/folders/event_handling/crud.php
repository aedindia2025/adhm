<?php



// Include any required files or configurations
include '../../config/dbconfig.php'; // Assuming this file contains other configurations

// Define the table name
$table = "event_handling";

// Variables initialization
$action = $_POST['action'];
$data = [];
$msg = "";
$error = "";
$status = "";

// Switch case based on the action
switch ($action) {
    case 'createupdate':
        // Retrieve data from POST
        $cur_date = $_POST["cur_date"];
        $event_name = $_POST["event_name"];
        $remarks = $_POST["remarks"];
        $is_active = $_POST["is_active"];
        $unique_id = $_POST["unique_id"];

        // Prepare and bind parameters for UPDATE statement
        $stmt = $mysqli->prepare("UPDATE event_handling SET cur_date=?, event_name=?, remarks=?, is_active=? WHERE unique_id=?");
        $stmt->bind_param("ssssi", $cur_date, $event_name, $remarks, $is_active, $unique_id);
        $stmt->execute();

        // Check for affected rows and set status/message accordingly
        if ($stmt->affected_rows > 0) {
            $status = true;
            $msg = "Update successful";
        } else {
            $status = false;
            $msg = "Update failed";
        }

        // Close the statement
        $stmt->close();

        // Prepare JSON response
        $json_array = [
            "status" => $status,
            "msg" => $msg
        ];

        echo json_encode($json_array);

        break;

        case 'datatable':
            // DataTable Variables
            $search = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $length = isset($_POST['length']) ? $_POST['length'] : '';
            $start = isset($_POST['start']) ? $_POST['start'] : '';
            $draw = isset($_POST['draw']) ? $_POST['draw'] : '';
            $limit = $length;
        
            $data = [];
        
            if ($length == '-1') {
                $limit = "";
            }
        
            // Query Variables
            $columns = [
                "@a:=@a+1 s_no",
                "cur_date",
                "(SELECT hostel_name FROM hostel_name WHERE unique_id = {$table}.hostel_name) AS hostel_name",
                "event_name",
                "image_file_name",
                "video_file_name",
                "unique_id"
            ];
        
            $table_details = "{$table} , (SELECT @a:= ?) AS a";
            $where = "is_delete = ? and hostel_name = ?";
            $order_by = "";
        
          $is_delete = "0";
        
            // Prepare SQL query
            $sql = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns) . "
                    FROM {$table}, (SELECT @a:= ?) AS a
                    WHERE {$where}
                    LIMIT ?, ?";

                   
        
            // Prepare and bind parameters
            $stmt = $mysqli->prepare($sql);
        
            // Bind parameters
            $stmt->bind_param("iisss", $start, $is_delete, $_SESSION['hostel_name'],$start, $limit);
        
            // Execute statement
            $stmt->execute();
        
            // Bind result variables
            $stmt->bind_result($s_no, $cur_date, $hostel_name, $event_name, $image_file_name, $video_file_name, $unique_id);
        
            // Fetch results and process
            $data = [];
            while ($stmt->fetch()) {
                $row = [
                    "s_no" => $s_no,
                    "cur_date" => disdate($cur_date),
                    "hostel_name" => $hostel_name,
                    "event_name" => disname($event_name),
                    "image_file_name" => $image_file_name,
                    "video_file_name" => $video_file_name,
                    "unique_id" => $unique_id
                ];
        
                // Handling image_file_name for modal popup
                if (!empty($row['image_file_name'])) {
                    $image_file_names = explode(",", $row['image_file_name']);
                    $modal_images = '';
                    foreach ($image_file_names as $index => $image_name) {
                        $modal_images .= '<div class="row mt-2">';
                        $modal_images .= '<div class="col-md-6"><img src="../adhmHostel/uploads/event_handling/images/' . trim($image_name) . '" class="img-fluid"></div>';
                        $modal_images .= '</div>';
                    }
        
                    // Update image_file_name to include modal HTML
                    $row['image_file_name'] = '<i class="mdi mdi-tooltip-image" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl2' . $row['unique_id'] . '" type="button"></i>
                                            <div class="modal fade bs-example-modal-xl2' . $row['unique_id'] . '" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-xl">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title w-100 text-center" id="myExtraLargeModalLabel">Image Attachment </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">' . $modal_images . '</div>
                                                        <div class="modal-footer">
                                                            <a href="javascript:void(0);" class="btn btn-link link-success fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
                                                        </div>
                                                    </div><!-- /.modal-content -->
                                                </div><!-- /.modal-dialog -->
                                            </div><!-- /.modal -->';
                }
        
                // Handling video_file_name for modal popup
                if (!empty($row['video_file_name'])) {
                    $row['video_file_name'] = '<i class="mdi mdi-play-circle" data-bs-toggle="modal" data-bs-target=".bs-example-modal-x3' . $row['unique_id'] . '" type="button"></i>
                                            <div class="modal fade bs-example-modal-x3' . $row['unique_id'] . '" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-xl">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title w-100 text-center" id="myExtraLargeModalLabel">Video Attachment </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row mt-2">
                                                                <video id="video-help-' . $row['unique_id'] . '" width="530" controls>
                                                                    <source id="videoPath" src="../adhmHostel/uploads/event_handling/videos/' . $row['video_file_name'] . '" type="video/mp4">
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
        
                // Add update and delete buttons
                $btn_update = btn_update($folder_name, $row['unique_id']);
                // $btn_delete = btn_delete($folder_name, $row['unique_id']);
                $row['unique_id'] = $btn_update; // . $btn_delete;
        
                // Push row to data array
                $data[] = array_values($row);
            }
        
              // Get total records
              $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
              $total_records = $total_records_result->fetch_assoc()['total'];
          
            // Prepare JSON response
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data,
                // "testing" => $stmt->sql // Uncomment for debugging purposes
            ];
        
            // Output JSON response
            echo json_encode($json_array);
        
            // Close statement and connection
            $stmt->close();
            break;
        

    case 'approval_create':
        // Process approval creation
        $id = $_POST["id"];
        $session_user_id = $_POST["session_user_id"];

        // Prepare and bind parameters for approval update
        $stmt = $mysqli->prepare("UPDATE " . $table . " SET approve_by=?, status=1 WHERE id=?");
        $stmt->bind_param("si", $session_user_id, $id);
        $stmt->execute();

        // Check for affected rows and set status/message accordingly
        if ($stmt->affected_rows > 0) {
            $status = true;
            $msg = "Approval successful";
        } else {
            $status = false;
            $msg = "Approval failed";
        }

        // Close the statement
        $stmt->close();

        // Prepare JSON response
        $json_array = [
            "status" => $status,
            "msg" => $msg
        ];

        echo json_encode($json_array);
        break;

    case 'delete':
        // Process deletion
        $unique_id = $_POST['unique_id'];

        // Prepare and bind parameters for deletion
        $stmt = $mysqli->prepare("UPDATE " . $table . " SET is_delete=1 WHERE unique_id=?");
        $stmt->bind_param("s", $unique_id);
        $stmt->execute();

        // Check for affected rows and set status/message accordingly
        if ($stmt->affected_rows > 0) {
            $status = true;
            $msg = "Deletion successful";
        } else {
            $status = false;
            $msg = "Deletion failed";
        }

        // Close the statement
        $stmt->close();

        // Prepare JSON response
        $json_array = [
            "status" => $status,
            "msg" => $msg
        ];

        echo json_encode($json_array);
        break;

    default:
        // Handle any other actions or errors
        $json_array = [
            "status" => false,
            "msg" => "Invalid action"
        ];

        echo json_encode($json_array);
        break;
}

// Close the database connection
$mysqli->close();
?>