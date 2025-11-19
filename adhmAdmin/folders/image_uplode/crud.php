<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "content_management";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';


// // Variables Declaration
$action = $_POST['action'];
$is_active = "";
$unique_id = "";
$prefix = "";

$data = "";
$msg = "";
$error = "";
$status = "";
$test = "";

// $fileUpload         = new Alirdn\SecureUPload\SecureUPload($fileUploadConfig);
// $fileUploadPath = $fileUploadConfig->get("upload_folder");
// // Create Folder in root->uploads->(this_folder_name) Before using this file upload
// $fileUploadConfig->set("upload_folder", $fileUploadPath . $folder_name . DIRECTORY_SEPARATOR);


switch ($action) {

    case 'createupdate':
        $ambedkar_quotes = sanitizeInput($_POST["ambedkar_quotes"]);
        $thirukkural = sanitizeInput($_POST["thirukkural"]);
        $is_active = $_POST["is_active"];
        $unique_id = $_POST["unique_id"];

      

        $columns = [
            "ambedkar_quotes" => $ambedkar_quotes,
            "thirukkural" => $thirukkural,
           
            "entry_date" => date('Y-m-d'),
            "is_active" => $is_active,
            "unique_id" => unique_id($prefix)
        ];

        $query = "";
        $types = "";
        $params = [];

        if ($unique_id) {
            $query = "UPDATE $table SET ambedkar_quotes=?, thirukkural=?,  entry_date=?, is_active=? WHERE unique_id=?";
            $types = "sssis";
            $params = [$ambedkar_quotes, $thirukkural,  date('Y-m-d'), $is_active, $unique_id];
        } else {

            $query = "INSERT INTO $table (ambedkar_quotes, thirukkural,  entry_date, is_active, unique_id) VALUES (?, ?, ?, ?, ?)";
            $types = "sssis";
            $params = [$ambedkar_quotes, $thirukkural,  date('Y-m-d'), $is_active, unique_id($prefix)];
        }

        $stmt = $mysqli->prepare($query);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {

            $status = true;
            $data = $stmt->insert_id;
            $error = "";
            $msg = $unique_id ? "update" : "create";
        } else {
            $status = false;
            $data = null;
            $error = $stmt->error;
            $msg = "error";
        }

        $stmt->close();

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
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
        $json_array = "";
        $columns = [
            // "@a:=@a+1 s_no",
           // 'ambedkar_image',
            'ambedkar_quotes',
           // "cm_image",
            "thirukkural",
            "is_active",
            "unique_id"
        ];
        $table_details = [
            $table,
            $columns
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

                $s_no = $key + 1;

                $value['is_active'] = is_active_show($value['is_active']);


                // Assuming 'ambedkar_image_original' and 'cm_image' contain the image URLs
              //  $ambedkar_image = "<img src='" . $value['ambedkar_image_original'] . "' alt='Ambedkar Image'>";
               // $cm_image = "<img src='" . $value['cm_image'] . "' alt='CM Image'>";


                $btn_update = btn_update($folder_name, $value['unique_id']);
                $btn_delete = btn_delete($folder_name, $value['unique_id']);

                if ($value['unique_id'] == "") {
                    $btn_update = "";
                    $btn_delete = "";
                }
                // $value['file_name'] = image_view("image_uplode", $value['unique_id'], $value['file_name']);
                $value['unique_id'] = $btn_update . $btn_delete;
                array_unshift($value, $s_no);
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
        $unique_id = $_POST['unique_id'];
        $columns = [
            "is_delete" => 1
        ];

        $query = "UPDATE $table SET is_delete=? WHERE unique_id=?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("is", $columns['is_delete'], $unique_id);

        if ($stmt->execute()) {
            $status = true;
            $data = $stmt->affected_rows;
            $error = "";
            $msg = "success_delete";
        } else {
            $status = false;
            $data = null;
            $error = $stmt->error;
            $msg = "error";
        }

        $stmt->close();

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
        ];

        echo json_encode($json_array);
        break;

    default;
        break;
}
