<?php

// Get folder Name From Current Url
$folder_name = explode('/', $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

$table = 'feedback_creation'; // Replace with your actual table name

// Include DB file and Common Functions
include '../../config/dbconfig.php'; // Adjust the path as necessary

function validateCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Variables Declaration
$action = isset($_POST['action']) ? $_POST['action'] : '';
$prefix = '';
$msg = '';
$status = '';
$data = [];
$error = '';

switch ($action) {
    case 'createupdate':
        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            exit('CSRF validation failed.');
        }

        $vali_feedback_name = filter_input(INPUT_POST, 'feedback_name', FILTER_SANITIZE_STRING);
        $vali_rating3 = filter_input(INPUT_POST, 'rating3', FILTER_SANITIZE_STRING);
        $vali_description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
        $vali_student_id = filter_input(INPUT_POST, 'student_id', FILTER_SANITIZE_STRING);
        $vali_student_name = filter_input(INPUT_POST, 'student_name', FILTER_SANITIZE_STRING);
        $vali_district_id = filter_input(INPUT_POST, 'district_id', FILTER_SANITIZE_STRING);
        $vali_taluk_id = filter_input(INPUT_POST, 'taluk_id', FILTER_SANITIZE_STRING);
        $vali_hostel_id = filter_input(INPUT_POST, 'hostel_id', FILTER_SANITIZE_STRING);
        $vali_entry_date = filter_input(INPUT_POST, 'entry_date', FILTER_SANITIZE_STRING);
        $vali_current_date = filter_input(INPUT_POST, 'current_date', FILTER_SANITIZE_STRING);

        if (!$vali_feedback_name || !$vali_rating3 || !$vali_description || !$vali_student_id || !$vali_student_name || !$vali_entry_date || !$vali_current_date || !$vali_district_id || !$vali_taluk_id || !$vali_hostel_id) {
            $msg = 'form_alert';
        } else {
            // Sanitize inputs
            $feedback_name = sanitizeInput($_POST['feedback_name']);
            $rating = sanitizeInput($_POST['rating3']);
            $description = sanitizeInput($_POST['description']);
            $student_id = sanitizeInput($_POST['student_id']);
            $student_name = sanitizeInput($_POST['student_name']);
            $district_id = sanitizeInput($_POST['district_id']);
            $taluk_id = sanitizeInput($_POST['taluk_id']);
            $hostel_id = sanitizeInput($_POST['hostel_id']);
            $entry_date = sanitizeInput($_POST['entry_date']);
            $current_date = sanitizeInput($_POST['current_date']);
            $unique_id = sanitizeInput($_POST['unique_id']);

            // Prepare data for insert or update
            $columns = [
                'feedback_name' => $feedback_name,
                'rating' => $rating,
                'description' => $description,
                'student_id' => $student_id,
                'student_name' => $student_name,
                'district_id' => $district_id,
                'taluk_id' => $taluk_id,
                'hostel_id' => $hostel_id,
                'entry_date' => $entry_date,
                'curr_date' => $current_date,
            ];

            if ($unique_id) {
                // Update existing record
                $sql = "UPDATE $table SET 
                            feedback_name = ?,
                            rating = ?,
                            description = ?,
                            student_id = ?,
                            student_name = ?,
                            district_id = ?,
                            taluk_id = ?,
                            hostel_id = ?,
                            entry_date = ?,
                            curr_date = ?
                        WHERE unique_id = ?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param('sssssssssss', $feedback_name, $rating, $description, $student_id, $student_name, $district_id, $taluk_id, $hostel_id, $entry_date, $current_date, $unique_id);
            } else {
                // Insert new record
                $sql = "INSERT INTO $table (feedback_name, rating, description, student_id, student_name, district_id, taluk_id, hostel_id, entry_date, curr_date, unique_id, user_id) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param('ssssssssssss', $feedback_name, $rating, $description, $student_id, $student_name, $district_id, $taluk_id, $hostel_id, $entry_date, $current_date, unique_id($prefix), $_SESSION['sess_user_id']);
            }

            // Execute statement and handle result
            if ($stmt->execute()) {
                $status = true;
                $msg = $unique_id ? 'update' : 'create';
                $data = $unique_id ? ['unique_id' => $unique_id] : ['unique_id' => $mysqli->insert_id];
            } else {
                $status = false;
                $msg = 'error';
                $error = $stmt->error;
            }

            $stmt->close();
        }

        $json_array = [
            'status' => $status,
            'data' => $data,
            'error' => $error,
            'msg' => $msg,
        ];

        echo json_encode($json_array);
        break;

    case 'datatable':
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;

        $data = [];

        $feedback_name = $_POST['feedback_name'];
        $rating = $_POST['rating3'];
        $description = $_POST['description'];
        $student_id = $_POST['student_id'];
        $date = $_POST['date'];
        $is_active = $_POST['is_active'];
        $unique_id = $_POST['unique_id'];

        if ($length == '-1') {
            $limit = '';
        }

        $json_array = '';
        $columns = [
            '@a:=@a+1 s_no',
            'entry_date',
            "(SELECT feedback_type FROM feedback_type WHERE unique_id = $table.feedback_name) AS feedback_name",
            'rating',
            'description',
            'unique_id',
        ];

        $where = 'user_id = ? AND is_delete = ?';
        $order_by = '';
        $is_delete = '0';

        // Prepare SQL query
        $sql = 'SELECT SQL_CALC_FOUND_ROWS '.implode(',', $columns)." 
                    FROM $table, (SELECT @a:= ?) AS a 
                    WHERE $where 
                    LIMIT ?, ?";

        $stmt = $mysqli->prepare($sql);

        $param_start = $start;
        $param_student_id = $_SESSION['sess_user_id'];

        $stmt->bind_param('iisii', $param_start, $param_student_id, $is_delete, $start, $limit);

        $stmt->execute();
        $result = $stmt->get_result();

        // Get total records
        $total_records = $mysqli->query('SELECT FOUND_ROWS() as total')->fetch_assoc()['total'];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $btn_update = btn_update($folder_name, $row['unique_id']);
                $btn_delete = btn_delete($folder_name, $row['unique_id']);

                $row['unique_id'] = $btn_update.$btn_delete;
                $data[] = array_values($row);
            }

            $json_array = [
                'draw' => intval($draw),
                'recordsTotal' => intval($total_records),
                'recordsFiltered' => intval($total_records),
                'data' => $data,
                // "testing" => $result->sql
            ];
        } else {
            echo json_encode(['error' => 'Failed to execute query: '.$mysqli->error]);
            exit;
        }

        echo json_encode($json_array);
        $stmt->close();
        // $mysqli->close();
        break;

    case 'delete':
        // Validate input
        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            exit('CSRF validation failed.');
        }

        $unique_id = isset($_POST['unique_id']) ? $_POST['unique_id'] : '';

        if (!$unique_id) {
            $json_array = [
                'status' => false,
                'msg' => 'missing_unique_id',
            ];
            echo json_encode($json_array);
            break;
        }
        $is_delete = '1';
        // Prepare and execute SQL statement
        $sql = "UPDATE $table SET is_delete = ? WHERE unique_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('ss', $is_delete, $unique_id);

        // Execute statement and handle result
        if ($stmt->execute()) {
            $status = true;
            $msg = 'success_delete';
        } else {
            $status = false;
            $msg = 'error';
            $error = $stmt->error;
        }

        // Prepare JSON response
        $json_array = [
            'status' => $status,
            'msg' => $msg,
            'error' => $error,
        ];

        echo json_encode($json_array);
        break;

    default:
        $json_array = [
            'status' => false,
            'msg' => 'Invalid action',
        ];

        echo json_encode($json_array);
        break;
}

// Close MySQLi connection
$mysqli->close();

// Function to sanitize input (can be expanded as per requirements)
