<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "grievance_category";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action = $_POST['action'];

// $district_name      = "";
$test_file = "";
$is_active = "";
$unique_id = "";
$prefix = "";

$data = "";
$msg = "";
$error = "";
$status = "";
$test = ""; // For Developer Testing Purpose
// For Developer Testing Purpose

function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
// print_r('hi');die();

switch ($action) {


    case 'createupdate':
        $token = $_POST['csrf_token'];

       

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        $unique_id = $_POST["unique_id"];
        $student_name = $_POST["student_name"];
        $gr_no = $_POST["gr_no"];
        $std_reg_no = $_POST["std_reg_no"];
        $district_name = $_POST["district_name"];
        $taluk_name = $_POST["taluk_name"];
        $hostel_name = $_POST["hostel_name"];
        $hostel_id = $_POST["hostel_id"];
        $tahsildar_name = $_POST["tahsildar_name"];
        $grievance_category = $_POST["grievance_category"];
        $description = $_POST["description"];
        $district_id = $_POST["district_id"];
        $taluk_id = $_POST["taluk_id"];
        $hostel_main_id = $_POST["hostel_main_id"];




        $update_where = "";
        $allowedExts = array('pdf', 'jpg', 'jpeg', 'png', 'gif');

        $extension = pathinfo($_FILES["test_file"]['name'], PATHINFO_EXTENSION);
        if (in_array($extension, $allowedExts)) {

            $file_exp = explode(".", $_FILES["test_file"]['name']);


            $tem_name = random_strings(25) . "." . $file_exp[1];



            move_uploaded_file($_FILES["test_file"]["tmp_name"], '../../uploads/grievance_category/' . $tem_name);
            $file_names = $tem_name;
            $file_org_names = $_FILES["test_file"]['name'];


            $columns = [

                "student_name" => $student_name,
                "grievance_no" => $gr_no,
                "std_reg_no" => $std_reg_no,
                "district" => $district_name,
                "taluk" => $taluk_name,
                "hostel_name" => $hostel_name,
                "hostel_id" => $hostel_id,
                "tahsildar" => $tahsildar_name,
                "grievance_cate" => $grievance_category,
                "grievance_description" => $description,
                "district_id" => $district_id,
                "taluk_id" => $taluk_id,
                "hostel_main_id" => $hostel_main_id,
                "file_name" => $file_names,
                "file_org_name" => $file_org_names,
                "unique_id" => unique_id($prefix)
            ];
        }

        else{
            $columns = [

                "student_name" => $student_name,
                "grievance_no" => $gr_no,
                "std_reg_no" => $std_reg_no,
                "district" => $district_name,
                "taluk" => $taluk_name,
                "hostel_name" => $hostel_name,
                "hostel_id" => $hostel_id,
                "tahsildar" => $tahsildar_name,
                "grievance_cate" => $grievance_category,
                "grievance_description" => $description,
                "district_id" => $district_id,
                "taluk_id" => $taluk_id,
                "hostel_main_id" => $hostel_main_id,
                "unique_id" => unique_id($prefix)
            ];
        }

        $table_details = [
            $table,
            [
                "COUNT(unique_id) AS count"
            ]
        ];
        $select_where = 'is_delete = 0';

        // When Update Check without current id
        if ($unique_id) {
            $select_where .= ' AND unique_id !="' . $unique_id . '" ';
        }

        if ($data[0]["count"]) {
            $msg = "already";
        } else if ($data[0]["count"] == 0) {
            // Update Begins
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
                // print_r($action_obj);
                // Insert Ends

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
        }
         else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "doc_error";
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql" => $sql
        ];

        echo json_encode($json_array);

        break;

        case 'datatable':

            $length = isset($_POST['length']) ? intval($_POST['length']) : 10; // Default limit
            $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
            $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
            $district_name = isset($_POST["district_name"]) ? $_POST["district_name"] : '';
            $taluk_name = isset($_POST["taluk_name"]) ? $_POST["taluk_name"] : '';
            $hostel_name = isset($_POST["hostel_name"]) ? $_POST["hostel_name"] : '';
        
            $data = [];
        
            // Query Variables
            $columns = [
                "@a:=@a+1 s_no",
                "DATE_FORMAT(updated,'%d-%m-%Y') as created",
                "grievance_no",
                "(SELECT grievance_name FROM grievance_creation WHERE grievance_creation.unique_id = grievance_category.grievance_cate) AS grievance_cate",
                "grievance_description",
                "unique_id"
            ];
        
            $table_details = $table . ", (SELECT @a:=0) AS a";
            $where = "is_delete = 0";
        
            // Construct WHERE clause based on filters
            $params = [];
        
            if (!empty($district_name)) {
                $where .= " AND district_id = ?";
                $params[] = $district_name;
            }
        
            if (!empty($taluk_name)) {
                $where .= " AND taluk_id = ?";
                $params[] = $taluk_name;
            }
        
            if (!empty($hostel_name)) {
                $where .= " AND hostel_main_id = ?";
                $params[] = $hostel_name;
            }
        
            // Query to get total number of records without pagination
            $sql_count = "SELECT COUNT(*) as total FROM " . $table . " WHERE " . $where;
            
            // Prepare statement for count
            $stmt_count = $mysqli->prepare($sql_count);
            if ($stmt_count === false) {
                die("Error preparing statement for count: " . $mysqli->error);
            }
        
            // Dynamically bind parameters for count
            if ($params) {
                $bind_types_count = str_repeat('s', count($params));
                $stmt_count->bind_param($bind_types_count, ...$params);
            }
        
            // Execute statement for count
            $stmt_count->execute();
        
            // Get result set for count
            $result_count = $stmt_count->get_result();
            $total_records = $result_count->fetch_assoc()['total'];
        
            // Close statement for count
            $stmt_count->close();
        
            // Build SQL query for data with limit and offset
            $sql = "SELECT " . implode(", ", $columns) . " FROM " . $table_details . " WHERE " . $where . " LIMIT ?, ?";
        
            // Append limit and offset to params
            $params[] = $start;
            $params[] = $length;
        
            // Prepare statement for data
            $stmt = $mysqli->prepare($sql);
            if ($stmt === false) {
                die("Error preparing statement: " . $mysqli->error);
            }
        
            // Dynamically bind parameters for data
            $bind_types = str_repeat('s', count($params) - 2) . 'ii';
            $stmt->bind_param($bind_types, ...$params);
        
            // Execute statement
            $stmt->execute();
        
            // Get result set
            $result = $stmt->get_result();
        
            if ($result) {
                // Fetch data
                $sno = $start + 1;
                while ($row = $result->fetch_assoc()) {
                    $row['s_no'] = $sno++;
                    // Manipulate data as needed
                    $row['created'] = disdate($row['created']);
                    $row['grievance_cate'] = disname($row['grievance_cate']);
                    // Add other data manipulations as needed
        
                    // Example: Add eye button
                    $eye_button = '<a class="btn btn-action specl2" href="javascript:grievance_print(\'' . $row['unique_id'] . '\')"><button type="button"><i class="fa fa-eye"></i></button></a>';
                    $row['unique_id'] = $eye_button;
        
                    $data[] = array_values($row);
                }
        
                // Prepare JSON response
                $json_array = [
                    "draw" => $draw,
                    "recordsTotal" => intval($total_records),
                    "recordsFiltered" => intval($total_records),
                    "data" => $data,
                ];
        
                echo json_encode($json_array);
            } else {
                echo json_encode(["error" => "Query failed: " . $mysqli->error]);
            }
        
            // Close statement and connection
            $stmt->close();
            $mysqli->close();
            break;
        
            
            
           
     case 'district_name':

                $district_name = $_POST['district_id'];
    
    
                $district_name_options = taluk_name('',$district_name);
    
                $taluk_name_options = select_option($district_name_options,"Select Taluk");
                
                echo $taluk_name_options;
    
                break;
    
        case 'get_hostel_by_taluk_name':
    
                $taluk_name = $_POST['taluk_id'];
    
    
                $hostel_name_options = hostel_name('',$taluk_name);
    
                $hostel_name_options = select_option_host($hostel_name_options,"Select Hostel");
    
                echo $hostel_name_options;
    
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
            // "sql" => $sql
        ];

        echo json_encode($json_array);
        break;
    }

?>