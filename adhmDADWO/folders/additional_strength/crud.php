<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "additional_strength";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action = $_POST['action'];

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

        $district_id = sanitizeInput($_POST["district_id"]);
        $from_taluk_name = sanitizeInput($_POST["from_taluk_name"]);
        $from_hostel_name = sanitizeInput($_POST["from_hostel_name"]);
        $from_hostel_strength = sanitizeInput($_POST["from_hostel_strength"]);
        $to_taluk_name = sanitizeInput($_POST["to_taluk_name"]);
        $to_hostel_name = sanitizeInput($_POST["to_hostel_name"]);
        $to_hostel_strength = sanitizeInput($_POST["to_hostel_strength"]);
        $transfer_count = sanitizeInput($_POST["transfer_count"]);
        $unique_id = sanitizeInput($_POST["unique_id"]);
        $user_name = sanitizeInput($_POST["user_name"]);
        $user_type = sanitizeInput($_POST["user_type"]);
        $remarks = sanitizeInput($_POST["remarks"]);
        $is_active = sanitizeInput($_POST["is_active"]);

        $allowedExts = array('pdf', 'jpg', 'jpeg', 'png', 'gif', 'xlsx', 'xls');
        $file_names = null;
        $file_org_names = null;

        if (!empty($_FILES['test_file']['name'])) {
            
            $extension = pathinfo($_FILES["test_file"]['name'], PATHINFO_EXTENSION);

                if (!in_array($extension, $allowedExts)) {
                    die('File type not allowed.');
                }

            if (in_array($extension, $allowedExts)) {
                $file_exp = explode(".", $_FILES["test_file"]['name']);
                $tem_name = random_strings(25) . "." . end($file_exp);
                move_uploaded_file($_FILES["test_file"]["tmp_name"], '../../uploads/disbursement/' . $tem_name);
                $file_names = $tem_name;
                $file_org_names = $_FILES["test_file"]['name'];
            }
        }


        // Columns to insert or update
        $columns = [
            "district_name" => $district_id,
            "from_taluk_name" => $from_taluk_name,
            "from_hostel_name" => $from_hostel_name,
            "from_hostel_strength" => $from_hostel_strength,
            "to_taluk_name" => $to_taluk_name,
            "to_hostel_name" => $to_hostel_name,
            "to_hostel_strength" => $to_hostel_strength,
            "transfer_count" => $transfer_count,
            "remarks" => $remarks,
            "is_active" => $is_active,
            "unique_id" => unique_id($prefix)
        ];

        if ($file_names && $file_org_names) {
            $columns["file_name"] = $file_names;
            $columns["file_org_name"] = $file_org_names;
        }

        // Check if record already exists
        $sql = 'SELECT COUNT(unique_id) AS count FROM ' . $table . ' WHERE district_name = ? AND from_taluk_name = ? AND from_hostel_name = ? AND to_taluk_name = ? AND to_hostel_name = ? AND is_delete = 0';
        if ($unique_id) {
            $sql .= ' AND unique_id != ?';
        }

        $stmt = $mysqli->prepare($sql);

        if ($unique_id) {
            $stmt->bind_param("ssssss", $district_id, $from_taluk_name, $from_hostel_name, $to_taluk_name, $to_hostel_name, $unique_id);
        } else {
            $stmt->bind_param("sssss", $district_id, $from_taluk_name, $from_hostel_name, $to_taluk_name, $to_hostel_name);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        if ($data["count"] > 0) {
            $status = true;
            $msg = "already";
            $data = [];
            $error = "";
            $sql = $stmt->sqlstate;
        } else {
            if ($unique_id) {
                unset($columns['unique_id']);
                $update_columns = array_keys($columns);
                $update_values = array_values($columns);
                $update_values[] = $unique_id;

                $set_clause = implode(", ", array_map(function ($col) {
                    return "$col = ?";
                }, $update_columns));

                $sql = 'UPDATE ' . $table . ' SET ' . $set_clause . ' WHERE unique_id = ?';
                $stmt = $mysqli->prepare($sql);

                $bind_types = str_repeat('s', count($columns)) . 's';
                $stmt->bind_param($bind_types, ...$update_values);
            } else {
                $insert_columns = array_keys($columns);
                $insert_values = array_values($columns);

                $sql = 'INSERT INTO ' . $table . ' (' . implode(", ", $insert_columns) . ') VALUES (' . str_repeat('?, ', count($columns) - 1) . '?)';
                $stmt = $mysqli->prepare($sql);

                $bind_types = str_repeat('s', count($columns));
                $stmt->bind_param($bind_types, ...$insert_values);
            }

            if ($stmt->execute()) {
                $status = true;
                $data = $stmt->affected_rows;
                $error = "";
                $sql = $stmt->sqlstate;
                $msg = $unique_id ? "update" : "create";
            } else {
                $status = false;
                $data = [];
                $error = $stmt->error;
                $sql = $stmt->sqlstate;
                $msg = "error";
            }
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            "sql" => $sql
        ];

        echo json_encode($json_array);

        // Close connection
        $stmt->close();

        break;


        case 'datatable':
           
            // DataTable Variables
            $length = isset($_POST['length']) ? intval($_POST['length']) : 10;
            $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
            $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
            $limit = $length == '-1' ? "" : $length;
            $data = [];
        
            // Query Variables
            $columns = [
               "@a:=@a+1 s_no",
            "(select district_name from district_name where district_name.unique_id=.$table.district_name) as district_name",
            "from_taluk_name",
            "(SELECT hostel_name FROM hostel_name AS hostel_name WHERE hostel_name.unique_id = " . $table . ".from_hostel_name ) AS from_hostel_name",
            "from_hostel_strength",
            "to_taluk_name",
            "(SELECT hostel_name FROM hostel_name AS hostel_name WHERE hostel_name.unique_id = " . $table . ".to_hostel_name ) AS to_hostel_name",
            "to_hostel_strength",
            "transfer_count",
            // "document_upload",
            // "additional_strength_file",
            // "additional_strength_org_name",
            "remarks",
            "is_active",
            "unique_id"
            ];
            $table = "additional_strength"; // Adjust your table name here
            $table_details = "$table, (SELECT @a:=?) AS a";
            $where = "is_delete = ? and district_name = ?";
            $bind_params = "sss";
            $bind_values = [$start, '0', $_SESSION['district_id']];
        
          
        
            // SQL function for counting total records
            $sql_function = "SQL_CALC_FOUND_ROWS";
        
            // Build SQL query
            $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_details WHERE $where";
            if (!empty($limit)) {
                $sql .= " LIMIT ?, ?";
                $bind_params .= "ii";
                $bind_values[] = $start;
                $bind_values[] = $limit;
            }
        
            // Prepare statement
            $stmt = $mysqli->prepare($sql);
        
            // Bind parameters dynamically
            if (!empty($bind_params)) {
                $stmt->bind_param($bind_params, ...$bind_values);
            }
        
            // Execute statement
            $stmt->execute();
        
            // Get result set
            $result = $stmt->get_result();
        
            // Fetch the data
            if ($result) {
                while ($value = $result->fetch_assoc()) {
                    $value['from_taluk_name'] = taluk_name($value['from_taluk_name'])[0]['taluk_name'];
                    $value['to_taluk_name'] = taluk_name($value['to_taluk_name'])[0]['taluk_name'];
                    // $value['school_name']     = disname($value['school_name']);
    
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
        
                // Fetch total records
                $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
                $total_records = $total_records_result->fetch_assoc()['total'];
        
                // Prepare JSON response for DataTables
                $json_array = [
                    "draw" => intval($draw),
                    "recordsTotal" => intval($total_records),
                    "recordsFiltered" => intval($total_records),
                    "data" => $data
                ];
            } else {
                // Handle the error case
                $json_array = [
                    "draw" => intval($draw),
                    "recordsTotal" => 0,
                    "recordsFiltered" => 0,
                    "data" => [],
                    "error" => $stmt->error
                ];
            }
        
            // Close statement and connection
            $stmt->close();
        
            // Output JSON response
            echo json_encode($json_array);
        
            break;
        
        



    case 'district_name':

        $district_name = $_POST['district_name'];


        $district_name_options = taluk_name(' ', $district_name);

        $taluk_name_options = select_option($district_name_options, "Select Taluk");

        echo $taluk_name_options;

        break;

    case 'get_hostel_by_taluk_name':

        $from_taluk_name = $_POST['from_taluk_name'];


        $hostel_name_options = hostel_name('', $from_taluk_name);


        $hostel_name_options = select_option_host($hostel_name_options, "Select Hostel");

        // print_r($hostel_name_options);

        echo $hostel_name_options;

        break;

    case 'get_hostel_by_taluk_name_1':

        $to_taluk_name = $_POST['to_taluk_name'];

        $hostel_name_options = hostel_name('', $to_taluk_name);

        $hostel_name_options = select_option_host($hostel_name_options, "Select Hostel");
        // print_r($hostel_name_options);die();
        echo $hostel_name_options;

        break;



 case 'get_hostel_strength':

    $from_hostel_name = $_POST['from_hostel_name'];

        $hostel_name_options = hostel_name($from_hostel_name)[0]['sanctioned_strength'];

       
        echo $hostel_name_options;

        break;

    case 'get_hostel_strength1';

        $table = "hostel_name";

        $from_hostel_name = $_POST['from_hostel_name'];
        $where = [];

        $columns = [

            "sanctioned_strength",
            "unique_id"
        ];

        $table_details = [
            $table,
            $columns
        ];

        $where = "unique_id = '" . $_POST['from_hostel_name'] . "' ";


        $result_values = $pdo->select($table_details, $where);
        // print_r($result_values);die();

        if ($result_values->status) {

            $result_values = $result_values->data;
            if ($result_values != '') {
                $sanctioned_strength = $result_values[0]["sanctioned_strength"];
                // print_r($sanctioned_strength);
                // die();
            }


        }

        $json_array = [
            "data" => $data,
            "sanctioned_strength" => $sanctioned_strength,
        ];

        break;





    case 'delete':

        $token = $_POST['csrf_token'];

            if (!validateCSRFToken($token)) {
                die('CSRF validation failed.');
            }

        $unique_id = $_POST['unique_id'];

        // Database connection details
        

        // Prepare the SQL statement for updating the record
        $sql = "UPDATE $table SET is_delete = ? WHERE unique_id = ?";
        $stmt = $mysqli->prepare($sql);

        if ($stmt) {
            $is_delete = 1;
            $stmt->bind_param("is", $is_delete, $unique_id);

            if ($stmt->execute()) {
                $status = true;
                $data = $stmt->affected_rows;
                $error = "";
                $sqlstate = $stmt->sqlstate;
                $msg = "success_delete";
            } else {
                $status = false;
                $data = [];
                $error = $stmt->error;
                $sqlstate = $stmt->sqlstate;
                $msg = "error";
            }

            $stmt->close();
            // $mysqli->close();

        } else {
            $status = false;
            $data = [];
            $error = $mysqli->error;
            $sqlstate = $mysqli->sqlstate;
            $msg = "error";
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            "sql" => $sqlstate
        ];

        echo json_encode($json_array);

        // Close connection
        $mysqli->close();

        break;




    case 'get_to_hostel_strength';

        $table = "hostel_name";

        $to_hostel_name = $_POST['to_hostel_name'];
        // $where=[];

        $columns = [

            "sanctioned_strength",
            "unique_id"
        ];

        $table_details = [
            $table,
            $columns
        ];

        $where = "unique_id = '" . $_POST['to_hostel_name'] . "' ";


        $result_values = $pdo->select($table_details, $where);

        if ($result_values->status) {

            $result_values = $result_values->data;

            $sanctioned_strength_to = $result_values[0]["sanctioned_strength"];
            // print_r($sanctioned_strength_to);
            // die();
        }

        $json_array = [

            "data" => $data,
            "sanctioned_strength" => $sanctioned_strength_to,
        ];

        break;


    default:

        break;
}


?>