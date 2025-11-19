<?php

// Get folder Name From Currnent Url 
$folder_name        = explode("/", $_SERVER['PHP_SELF']);
$folder_name        = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table             = "inspection";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action             = $_POST['action'];

$district_name      = "";
$taluk_name         = "";
$inspection_date    = "";
$hostel_name        = "";
$desc_text          = "";
$is_active          = "";
$unique_id          = "";
$prefix             = "";

$data               = "";
$msg                = "";
$error              = "";
$status             = "";
$test               = ""; // For Developer Testing Purpose

switch ($action) {

        //
    case 'createupdate':

        $user_name              = $_POST["user_name"];
        $user_type                 = $_POST["user_type"];
        $district_name              = $_POST["district_name"];
        $taluk_name                 = $_POST["taluk_name"];
        $inspection_date            = $_POST["inspection_date"];
        $hostel_name                = $_POST["hostel_name"];
        $description                  = $_POST["description"];
        $is_active                  = $_POST["is_active"];
        $inspection_id                  = $_POST["inspection_id"];
        $unique_id                  = $_POST["unique_id"];
       
       
        $update_where       = "";
        $allowedExts = array('pdf');

        $extension = pathinfo($_FILES["test_file"]['name'], PATHINFO_EXTENSION);
                    $file_exp = explode(".",$_FILES["test_file"]['name']);
           
                    $tem_name =  random_strings(25).".".$file_exp[1]; 
                move_uploaded_file($_FILES["test_file"]["tmp_name"], '../../../uploads/inspection/' . $tem_name);
            $file_names     = $tem_name;
            $file_org_names = $_FILES["test_file"]['name'];

        // print_r($file_org_names);
        // die();
       

        $columns            = [
            "user_name"               => $user_name,
            "user_type"                  => $user_type,
            "district_name"               => $district_name,
            "taluk_name"                  => $taluk_name,
            "inspection_id"               => $inspection_id,
            "inspection_date"             => $inspection_date,
            "hostel_name"                 => $hostel_name,
            "description"                   => $description,
            "file_name"         => $file_names,
            "file_org_names"     => $file_org_names,
            "is_active"                   => $is_active,
            "unique_id"                   => unique_id($prefix)
        ];

        // check already Exist Or not
        $table_details      = [
            $table,
            [
                "COUNT(unique_id) AS count"
            ]
        ];
        $select_where       = ' AND is_delete = 0  ';

        // When Update Check without current id
        if ($unique_id) {
            $select_where   .= ' AND unique_id !="' . $unique_id . '" ';
        }

        $action_obj         = $pdo->select($table_details, $select_where);

        if ($action_obj->status) {
            $status     = $action_obj->status;
            $data       = $action_obj->data;
            $error      = "";
            $sql        = $action_obj->sql;
        } else {
            $status     = $action_obj->status;
            $data       = $action_obj->data;
            $error      = $action_obj->error;
            $sql        = $action_obj->sql;
            $msg        = "error";
        }
        if ($data[0]["count"]) {
            $msg        = "already";
        } else if ($data[0]["count"] == 0) {
            // Update Begins
            if ($unique_id) {

                unset($columns['unique_id']);

                $update_where   = [
                    "unique_id"     => $unique_id
                ];

                $action_obj     = $pdo->update($table, $columns, $update_where);

                // Update Ends
            } else {

                // Insert Begins            
                $action_obj     = $pdo->insert($table, $columns);
                // Insert Ends

            }

            if ($action_obj->status) {
                $status     = $action_obj->status;
                $data       = $action_obj->data;
                $error      = "";
                $sql        = $action_obj->sql;

                if ($unique_id) {
                    $msg        = "update";
                } else {
                    $msg        = "create";
                }
            } else {
                $status     = $action_obj->status;
                $data       = $action_obj->data;
                $error      = $action_obj->error;
                $sql        = $action_obj->sql;
                $msg        = "error";
            }
        }

        $json_array   = [
            "status"    => $status,
            "data"      => $data,
            "error"     => $error,
            "msg"       => $msg,
            // "sql"       => $sql
        ];

        echo json_encode($json_array);

        break;

        case 'datatable':

       
        
        // DataTable Variables
        $length         = $_POST['length'];
        $start          = $_POST['start'];
        $draw           = $_POST['draw'];
        $limit          = $length;
        
        $district_name  = isset($_POST["district_name"]) ? $_POST["district_name"] : '';
        $taluk_name     = isset($_POST["taluk_name"]) ? $_POST["taluk_name"] : '';
        $hostel_name    = isset($_POST["hostel_name"]) ? $_POST["hostel_name"] : '';
        
        $data           = [];
        
      
        
        // Query Variables
        $columns = [
            "@a:=@a+1 s_no",
            "inspection_date",
            "inspection_id",
            "(SELECT staff_name FROM staff_registration WHERE staff_registration.unique_id = $table.user_name) AS user_name",
            "(SELECT hostel_name FROM hostel_name WHERE hostel_name.unique_id = $table.hostel_name) AS hostel_name",
            "description",
            "file_name",
            "unique_id"
        ];
        $table = "inspection"; // Replace with your actual table name
        $table_details = $table . ", (SELECT @a:= ?) AS a";
        $where = "is_delete = ?";
        $params = [$start, '0'];
        
        // Build WHERE clause with parameters
        if (!empty($district_name)) {
            $where .= " AND district_name = ?";
            $params[] = $district_name;
        }
        if (!empty($taluk_name)) {
            $where .= " AND taluk_name = ?";
            $params[] = $taluk_name;
        }
        if (!empty($hostel_name)) {
            $where .= " AND hostel_name = ?";
            $params[] = $hostel_name;
        }
        
        // Prepare SQL statement
        $sql = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns) . " FROM " . $table_details . " WHERE " . $where;
        
        if (!empty($limit)) {
            $sql .= " LIMIT ?, ?";
            $params[] = intval($start);
            $params[] = intval($limit);
        }
        
        // Prepare and bind parameters
        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            die('MySQL prepare error: ' . $mysqli->error);
        }
        
        // Bind parameters
        if (!empty($params)) {
            $types = str_repeat('s', count($params)); // Assuming all parameters are strings for simplicity
            $stmt->bind_param($types, ...$params);
        }
        
        // Execute query
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Fetch data
        $data = [];
        while ($row = $result->fetch_assoc()) {

            $file_name = $row["file_name"];
            $row["file_name"] = image_view($file_name); // Assuming image_view function is defined elsewhere
            // Modify other fields or perform additional processing here
            $row['is_active'] = is_active_show($row['is_active']);
            $unique_id = $row['unique_id'];
            $eye_button = '<a class="btn btn-action specl2" href="javascript:leave_print(\'' . $unique_id . '\')"><button type="button"><i class="fa fa-eye"></i></button></a>';
        
            if ($row['unique_id'] == "5f97fc3257f2525529") {
                // Adjust actions based on unique_id if needed
            }
        
            $row['unique_id'] = $eye_button;
            $data[] = array_values($row);
        }
        
        // Total records count
        $total_records = $mysqli->query("SELECT FOUND_ROWS() AS total")->fetch_assoc()['total'];
        
        // JSON response
        $json_array = [
            "draw"              => intval($draw),
            "recordsTotal"      => intval($total_records),
            "recordsFiltered"   => intval($total_records),
            "data"              => $data
        ];
        
        echo json_encode($json_array);
        
        // Close statement and connection
        $stmt->close();
        $mysqli->close();

        break;
        

    case 'delete':

        $unique_id = $_POST['unique_id'];

        // Define table and update values
        $table = "inspection"; // Replace with your actual table name
        $columns = [
            "is_delete" => 1
        ];
        $update_where = [
            "unique_id" => $unique_id
        ];
        
        // Build the SQL UPDATE statement
        $sql = "UPDATE " . $table . " SET ";
        $update_columns = [];
        foreach ($columns as $key => $value) {
            $update_columns[] = $key . " = ?";
        }
        $sql .= implode(", ", $update_columns) . " WHERE ";
        $update_conditions = [];
        foreach ($update_where as $key => $value) {
            $update_conditions[] = $key . " = ?";
        }
        $sql .= implode(" AND ", $update_conditions);
        
        // Prepare and execute the statement
        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            die('MySQL prepare error: ' . $mysqli->error);
        }
        
        // Bind parameters dynamically
        $params = array_merge(array_values($columns), array_values($update_where));
        $types = str_repeat('s', count($params)); // Assuming all parameters are strings for simplicity
        $stmt->bind_param($types, ...$params);
        
        // Execute the update
        if ($stmt->execute()) {
            // Update successful
            $status = true;
            $data = [];
            $error = "";
            $msg = "success_delete";
        } else {
            // Update failed
            $status = false;
            $data = [];
            $error = $stmt->error;
            $msg = "error";
        }
        
        // Close statement and connection
        $stmt->close();
        $mysqli->close();
        
        // Prepare JSON response
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
        ];
        
        echo json_encode($json_array);
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

  

    case 'hostel_to_taluk':

        $hostel_name   = $_POST["hostel_name"];
// print_r($hostel_name);
        $details    = [
            "district_name"          => "",
            "taluk_name" => "",
            
        ];

        if ($hostel_name) {
            $staff_where = [
                "unique_id" => $hostel_name
            ];

            $staff_columns = [
                "district_name",
                "taluk_name",
                
            ];

            $staff_table_details = [
                "hostel_name",
                $staff_columns
            ];

            $staff_details = $pdo->select($staff_table_details, $staff_where);

            if ($staff_details->status) {
                if (!empty($staff_details->data)) {
                    $details = $staff_details->data[0];
                }
            } else {
                print_r($staff_details);
            }
        }

        echo json_encode($details);
//print_r($staff_details);
        break;

    case 'get_hostel_name':

        $taluk_name          = $_POST['taluk_name'];

        $taluk_options  = hostel_name("", $taluk_name);

        $hostel_name_options  = select_option($taluk_options, "Select Hostel");

        echo $hostel_name_options;

        break;

    default:

        break;
}
function image_view($file_name = "")
{
    // echo $file_name;
    $file_names = explode(',', $file_name);
    $image_view = '';

    if ($file_name) {
        foreach ($file_names as $file_key => $file_name) {
            if ($file_key != 0) {
                if ($file_key % 4 != 0) {
                    $image_view .= "&nbsp";
                } else {
                    $image_view .= "<br><br>";
                }
            }

            $cfile_name = explode('.', $file_name);

            if ($file_name) {

                if (($cfile_name[1] == 'jpg') || ($cfile_name[1] == 'png') || ($cfile_name[1] == 'jpeg')) {
                    $image_view .= '<a href="javascript:print_view(\'/' . $file_name . '\')"><img src="../assets/images/images.png"  width="20%" ></a>';
                    // $image_view .= '<img src="uploads/'.$folder_name.'/'.$doc_name.'"  height="50px" width="50px" >';
                } else if ($cfile_name[1] == 'pdf') {
                    $image_view .= '<a href="javascript:print_pdf(\'/' . $file_name . '\')"><img src="../assets/images/pdf.png"   width="20%" style="margin-left: 15px;" ></a>';
                }
                
            }
        }
    }

    return $image_view;
}
