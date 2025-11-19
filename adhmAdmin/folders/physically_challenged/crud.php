<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "physically_challenged";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action = $_POST['action'];

$hostel_type = "";
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

        $physically_challenged = sanitizeInput($_POST["physically_challenged"]);
        $is_active = sanitizeInput($_POST["is_active"]);
        $unique_id = sanitizeInput($_POST["unique_id"]);

        $status = false;
        $data = [];
        $error = "";
        $msg = "";

        // Check if record already exists
        $select_where = 'physically_challenged = ? AND is_delete = 0';
        if ($unique_id) {
            $select_where .= ' AND unique_id != ?';
        }

        $sql_check = "SELECT COUNT(unique_id) AS count FROM $table WHERE $select_where";
        if ($stmt_check = $mysqli->prepare($sql_check)) {
            if ($unique_id) {
                $stmt_check->bind_param("ss", $physically_challenged, $unique_id);
            } else {
                $stmt_check->bind_param("s", $physically_challenged);
            }

            $stmt_check->execute();
            $stmt_check->bind_result($count);
            $stmt_check->fetch();
            $stmt_check->close();

            if ($count > 0) {
                $msg = "already";
		$status = true;
            } else {
                if ($unique_id) {
                    // Update
                    $sql_update = "UPDATE $table SET physically_challenged=?, is_active=? WHERE unique_id=?";
                    if ($stmt_update = $mysqli->prepare($sql_update)) {
                        $stmt_update->bind_param("sss", $physically_challenged, $is_active, $unique_id);
                        $status = $stmt_update->execute();
                        $data = $stmt_update->affected_rows;
                        $error = $status ? "" : $stmt_update->error;
                        $msg = $status ? "update" : "error";
                        $stmt_update->close();
                    } else {
                        $error = $mysqli->error;
                        $msg = "error";
                    }
                } else {
                    // Insert
                    $new_unique_id = unique_id($prefix);
                    $sql_insert = "INSERT INTO $table (physically_challenged, is_active, unique_id) VALUES (?, ?, ?)";
                    if ($stmt_insert = $mysqli->prepare($sql_insert)) {
                        $stmt_insert->bind_param("sss", $physically_challenged, $is_active, $new_unique_id);
                        $status = $stmt_insert->execute();
                        $data = $stmt_insert->insert_id;
                        $error = $status ? "" : $stmt_insert->error;
                        $msg = $status ? "create" : "error";
                        $stmt_insert->close();
                    } else {
                        $error = $mysqli->error;
                        $msg = "error";
                    }
                }
            }
        } else {
            $error = $mysqli->error;
            $msg = "error";
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql" => $sql_check // Uncomment for debugging purposes
        ];

        echo json_encode($json_array);

        break;
    case 'datatable':
        // DataTable Variables
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;

        $data = [];



        // Query Variables
        $columns = [
            "@a:=@a+1 s_no",
            "physically_challenged",
            "is_active",
            "unique_id"
        ];
        $table = "physically_challenged"; // Replace with your actual table name

        $where = "is_delete = 0";
        $order_by = "";

        $sql_function = "SQL_CALC_FOUND_ROWS";

        // Build the SELECT query
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table, (SELECT @a:= $start) AS a WHERE $where";
        if ($limit != "") {
            $sql .= " LIMIT $limit OFFSET $start";
        }

        // Execute the query
        $result = $mysqli->query($sql);

        if ($result) {
            // Get the total number of records
            $total_records_result = $mysqli->query("SELECT FOUND_ROWS()");
            $total_records = $total_records_result->fetch_row()[0];

            while ($row = $result->fetch_assoc()) {
                $row['is_active'] = is_active_show($row['is_active']);

                $btn_update = btn_update($folder_name, $row['unique_id']);
                $btn_delete = btn_delete($folder_name, $row['unique_id']);

                if ($row['unique_id'] == "5f97fc3257f2525529") {
                    $btn_update = "";
                    $btn_delete = "";
                }

                $row['unique_id'] = $btn_update . $btn_delete;
                $data[] = array_values($row);
            }

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data,
                // "testing" => $sql // Uncomment for debugging purposes
            ];
        } else {
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => $mysqli->error
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