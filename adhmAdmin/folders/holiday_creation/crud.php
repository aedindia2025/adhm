<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "holiday_creation";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action = $_POST['action'];

$fund_name = "";
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
        $academic_year = $_POST["academic_year"];
        $date = $_POST["date"];
        $holiday = sanitizeInput($_POST["holiday"]);
        $description = sanitizeInput($_POST["description"]);
        $is_active = $_POST["is_active"];
        $unique_id = $_POST["unique_id"];

        $update_where = "";

        $columns = [
            "academic_year" => $academic_year,
            "date" => $date,
            "holiday" => $holiday,
            "description" => $description,
            "is_active" => $is_active,
            "unique_id" => unique_id($prefix)
        ];

        if ($unique_id) {
            unset($columns['unique_id']);

            $set_clause = "";
            foreach ($columns as $key => $value) {
                $set_clause .= "$key = '$value', ";
            }
            $set_clause = rtrim($set_clause, ', ');

            $update_where = "unique_id = '$unique_id'";
            $sql = "UPDATE $table SET $set_clause WHERE $update_where";

            if ($mysqli->query($sql) === TRUE) {
                $status = true;
                $msg = "update";
            } else {
                $status = false;
                $error = $mysqli->error;
                $msg = "error";
            }
        } else {
            $columns_str = implode(", ", array_keys($columns));
            $values_str = implode(", ", array_map(function ($value) {
                return "'$value'";
            }, $columns));

            $sql = "INSERT INTO $table ($columns_str) VALUES ($values_str)";

            if ($mysqli->query($sql) === TRUE) {
                $status = true;
                $msg = "create";
            } else {
                $status = false;
                $error = $mysqli->error;
                $msg = "error";
            }
        }

        $json_array = [
            "status" => $status,
            "error" => $error ?? '',
            "msg" => $msg
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
            "date",
            "holiday",
            "is_active",
            "unique_id"
        ];

        $columns_str = implode(", ", $columns);
        $table_details = "$table, (SELECT @a:= $start) AS a";
        $where = "is_delete = 0";
        $order_by = "";
        $limit_str = $limit ? "LIMIT $start, $limit" : "";

        // SQL Query
        $sql = "SELECT SQL_CALC_FOUND_ROWS $columns_str FROM $table_details WHERE $where $order_by $limit_str";
        $result = $mysqli->query($sql);

        if ($result) {
            $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total_records");
            $total_records = $total_records_result->fetch_assoc()['total_records'];

            while ($row = $result->fetch_assoc()) {
                $row['is_active'] = is_active_show($row['is_active']);
                $row['date'] = date('d-m-Y', strtotime($row['date']));

                $btn_update = btn_update($folder_name, $row['unique_id']);
                $btn_delete = btn_delete($folder_name, $row['unique_id']);

                // Optional condition for specific unique_id
                // if ($row['unique_id'] == "5f97fc3257f2525529") {
                //     $btn_update = "";
                //     $btn_delete = "";
                // }

                $row['unique_id'] = $btn_update . $btn_delete;
                $data[] = array_values($row);
            }

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data,
                // "testing" => $sql // Uncomment this line for debugging
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

//
?>