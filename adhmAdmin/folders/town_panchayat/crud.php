<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "town_panchayat";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action = $_POST['action'];

$town_panchayat_name = "";
$district_name = "";
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

        $district_name = sanitizeInput($_POST["district_name"]);
        $town_panchayat_name = sanitizeInput($_POST["town_panchayat_name"]);
        $is_active = $_POST["is_active"];
        $unique_id = $_POST["unique_id"];

        $update_where = "";

        $columns = [
            "district_name" => $district_name,
            "town_panchayat_name" => $town_panchayat_name,
            "is_active" => $is_active,
            "unique_id" => unique_id($prefix)
        ];

        // Check if already exists
        $query = "SELECT COUNT(unique_id) AS count FROM $table WHERE town_panchayat_name = ? AND is_delete = 0";
        if ($unique_id) {
            $query .= " AND unique_id != ?";
        }

        if ($stmt = $mysqli->prepare($query)) {
            if ($unique_id) {
                $stmt->bind_param("ss", $town_panchayat_name, $unique_id);
            } else {
                $stmt->bind_param("s", $town_panchayat_name);
            }
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count) {
                $msg = "already";
            } else {
                // Update or Insert
                if ($unique_id) {
                    $update_columns = $columns;
                    unset($update_columns['unique_id']);
                    $set_clause = implode(" = ?, ", array_keys($update_columns)) . " = ?";
                    $query = "UPDATE $table SET $set_clause WHERE unique_id = ?";
                    if ($stmt = $mysqli->prepare($query)) {
                        $stmt->bind_param(
                            "ssss",
                            $update_columns['district_name'],
                            $update_columns['town_panchayat_name'],
                            $update_columns['is_active'],
                            $unique_id
                        );
                        $stmt->execute();
                        $stmt->close();
                        $msg = "update";
                    }
                } else {
                    $columns_placeholder = implode(", ", array_keys($columns));
                    $values_placeholder = implode(", ", array_fill(0, count($columns), "?"));
                    $query = "INSERT INTO $table ($columns_placeholder) VALUES ($values_placeholder)";
                    if ($stmt = $mysqli->prepare($query)) {
                        $stmt->bind_param(
                            "ssss",
                            $columns['district_name'],
                            $columns['town_panchayat_name'], 
                            $columns['is_active'],
                            $columns['unique_id']
                        );
                        $stmt->execute();
                        $stmt->close();
                        $msg = "create";
                    }
                }
            }

            $json_array = [
                "status" => true,
                "data" => [
                "unique_id" => $unique_id ? $unique_id : $columns['unique_id'],
           	 ],
                "error" => $error,
                "msg" => $msg
            ];
        } else {
            $json_array = [
                "status" => false,
                "data" => $data,
                "error" => $mysqli->error,
                "msg" => "error"
            ];
        }

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
        $json_array = "";
        $columns = [
            "@a:=@a+1 s_no",
            "(SELECT district_name FROM district_name AS district_name WHERE district_name.unique_id = " . $table . ".district_name ) AS district_name",
            "town_panchayat_name",
            "is_active",
            "unique_id"
        ];
        $table_details = "$table , (SELECT @a:= ?) AS a";
        $where = "is_delete = 0";
        $bind_params = "i"; // Types of parameters (i for integer)
        $bind_values = [$start];


        $sql_function = "SQL_CALC_FOUND_ROWS";
        $order_by = ""; // Set the order by clause if necessary

        // SQL query for data fetching
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_details WHERE $where";
        if ($limit !== "") {
            $sql .= " LIMIT ?, ?";
            $bind_params .= "ii"; // Add types for limit parameters
            $bind_values[] = $start;
            $bind_values[] = $limit;
        }

        $stmt = $mysqli->prepare($sql);

        if ($stmt === false) {
            // Handle prepare error
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => $mysqli->error
            ];
            echo json_encode($json_array);
            break;
        }

        // Bind parameters dynamically
        $bind_params_arr = array_merge([$bind_params], ...array_map(function ($v) {
            return [$v];
        }, $bind_values));
        call_user_func_array([$stmt, 'bind_param'], $bind_params_arr);

        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch total records
        $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
        $total_records = $total_records_result->fetch_assoc()['total'];
        if ($result) {

            $res_array = $result->fetch_all(MYSQLI_ASSOC);

            foreach ($res_array as $key => $value) {
                $value['is_active'] = is_active_show($value['is_active']);

                $btn_update = btn_update($folder_name, $value['unique_id']);
                $btn_delete = btn_delete($folder_name, $value['unique_id']);

                if ($value['unique_id'] == "5f97fc3257f2525529") {
                    $btn_update = "";
                    $btn_delete = "";
                }

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
        }

        echo json_encode($json_array);
        break;


    case 'delete':

        $token = $_POST['csrf_token'];
        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        $unique_id = $_POST['unique_id'];

        $columns = [
            "is_delete" => 1
        ];

        $update_where = [
            "unique_id" => $unique_id
        ];

        $query = "UPDATE $table SET is_delete = ? WHERE unique_id = ?";
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param("is", $columns['is_delete'], $unique_id);
            $stmt->execute();
            if ($stmt->affected_rows) {
                $msg = "success_delete";
            } else {
                $msg = "error";
            }
            $stmt->close();
        } else {
            $msg = "error";
        }

        $json_array = [
            "status" => true,
            "data" => $data,
            "error" => $error,
            "msg" => $msg
        ];

        echo json_encode($json_array);
        break;

   

    default:

        break;
}


?>