
<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "maintanance_creation";

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

switch ($action) {
    
    case 'datatable':
        // DataTable Variables
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length == '-1' ? "" : $length;

        $data = [];

        // Query Variables
        $columns = [
            "@a:=@a+1 s_no",
            "(select taluk_name from taluk_creation where taluk_creation.unique_id = hostel_name.taluk_name) as taluk_name",
            "hostel_name",
            "(select count(id) from establishment_registration where is_delete = '0' and designation = '679344dd0298475852' and hostel_name.unique_id = establishment_registration.hostel_name) as warden",
            "(select count(id) from establishment_registration where is_delete = '0' and designation = '679344eba32e272880' and hostel_name.unique_id = establishment_registration.hostel_name) as warden_incharge",
            "(select count(id) from establishment_registration where is_delete = '0' and designation = '679344f6b8df890572' and hostel_name.unique_id = establishment_registration.hostel_name) as cook",
            "(select count(id) from establishment_registration where is_delete = '0' and designation = '6793454ad335487663' and hostel_name.unique_id = establishment_registration.hostel_name) as deputation_cook",
            "(select count(id) from establishment_registration where is_delete = '0' and designation = '67934553276a759813' and hostel_name.unique_id = establishment_registration.hostel_name) as watchman",
            "(select count(id) from establishment_registration where is_delete = '0' and designation = '6795b2be36a0239724' and hostel_name.unique_id = establishment_registration.hostel_name) as sweeper",
            "unique_id"
        ];
        $table = "hostel_name"; // Adjust your table name here
        $table_details = $table . " , (SELECT @a:= ?) AS a ";
        $where = "is_delete = ? AND district_name = ?";
        $bind_params = "iis"; // Types of parameters (s for string)
        $bind_values = [$start, '0', $_SESSION["district_id"]];

        // Additional conditions based on POST values
       
        if (!empty($_POST['hostel_name'])) {
            $where .= " AND unique_id = ?";
            $bind_params .= "s"; // Add type for string parameter
            $bind_values[] = $_POST['hostel_name'];
        }
        if (!empty($_POST['taluk_name'])) {
            $where .= " AND taluk_name = ?";
            $bind_params .= "s"; // Add type for string parameter
            $bind_values[] = $_POST['taluk_name'];
        }

        $sql_function = "SQL_CALC_FOUND_ROWS";

        // SQL query for data fetching
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_details WHERE $where";
     
        if (!empty($limit)) {
            $sql .= " LIMIT ?, ?";
            $bind_params .= "ii"; // Add types for limit parameters
            $bind_values[] = $start;
            $bind_values[] = $limit;
        }

        $stmt = $mysqli->prepare($sql);

        // Bind parameters dynamically
        if (!empty($bind_params)) {
            $stmt->bind_param($bind_params, ...$bind_values);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch total records
        $total_records_result = $mysqli->query("SELECT FOUND_ROWS() AS total");
        $total_records = $total_records_result->fetch_assoc()['total'];

        if ($result) {


            $res_array = $result->fetch_all(MYSQLI_ASSOC);


            foreach ($res_array as $key => $value) {

              

                // Modify unique_id for view action
                $unique_id = $value['unique_id'];
                $value['warden'] = '<a href="javascript:view_app(\'' . $unique_id . '\', \'679344dd0298475852\')">' . $value['warden'] . '</a>';
                $value['warden_incharge'] = '<a href="javascript:view_app(\'' . $unique_id . '\',\'679344eba32e272880\')">'.$value['warden_incharge'].'</a>';
                $value['cook'] = '<a href="javascript:view_app(\'' . $unique_id . '\',\'679344f6b8df890572\')">'.$value['cook'].'</a>';
                $value['deputation_cook'] = '<a href="javascript:view_app(\'' . $unique_id . '\',\'6793454ad335487663\')">'.$value['deputation_cook'].'</a>';
                $value['watchman'] = '<a href="javascript:view_app(\'' . $unique_id . '\',\'67934553276a759813\')">'.$value['watchman'].'</a>';
                $value['sweeper'] = '<a href="javascript:view_app(\'' . $unique_id . '\',\'6795b2be36a0239724\')">'.$value['sweeper'].'</a>';


                $data[] = array_values($value);
            }

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
                "error" => $mysqli->error
            ];
        }

        echo json_encode($json_array);

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
            // "sql"       => $sql
        ];

        echo json_encode($json_array);
        break;


    case 'get_asset_name':
        $asset_category = $_POST['asset_category'];
        $asset_category_options = facility_name("", $asset_category);

        $asset_name_options = select_option($asset_category_options, "Select Asset");
        // print_r($asset_name_options);
        echo $asset_name_options;

        break;

        case 'update_status':
            $unique_id = $_POST['unique_id'];
            $status = $_POST['status'];
    
            // Database connection setup (assuming already established)
            $host = "localhost";
            $username = "root";
            $password = "H_Cw3O4CM*fXcGtz";
            $databasename = "adi_dravidar";
    
            $mysqli = new mysqli($host, $username, $password, $databasename);
    
            // Check connection
            if ($mysqli->connect_error) {
                die("Connection failed: " . $mysqli->connect_error);
            }
    
            // Define the SQL statement
            $sql = "";
            $params = [];
    
        
                $sql = "UPDATE maintanance_creation SET status = ?, status_upd_date = ? WHERE unique_id = ?";
                $params = [$status, date('Y-m-d'), $unique_id];
           
    
            // Prepare and execute the statement
            $stmt = $mysqli->prepare($sql);
            if ($stmt === false) {
                die('MySQL prepare error: ' . $mysqli->error);
            }
    
            // Bind parameters
            $types = 'iss'; // Assuming print_status is integer and batch_no is string/integer
            $stmt->bind_param($types, ...$params);
    
            // Execute the statement
            $stmt->execute();
    
            // Check for errors or success
            if ($stmt->affected_rows > 0) {
                $status = "success";
               
                $data = []; // You can set relevant data if needed
                $error = "";
            } else {
                $status = "error";
                $msg = "Failed to update records";
                $data = [];
                $error = $mysqli->error;
            }
    
            // Close statement and connection
            $stmt->close();
            $mysqli->close();
    
            $json_array = [
                "status" => $status,
                "data" => $data,
                "error" => $error,
                "msg" => $msg,
            ];
    
            echo json_encode($json_array);
    
            break;

    case 'get_asset_count':
        // DataTable Variables
        $search = $_POST['search']['value'];
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;
        $asset_name = $_POST['asset_name'];

        $data = [];


        if ($length == '-1') {
            $limit = "";
        }

        // Query Variables
        $json_array = "";
        $columns = [
            "(select digital_infra_facility_sub.quantity from digital_infra_creation left join digital_infra_facility_sub on digital_infra_creation.unique_id = digital_infra_facility_sub.form_main_unique_id where digital_infra_creation.hostel_id = '" . $_SESSION['hostel_id'] . "' and digital_infra_facility_sub.facilities='" . $asset_name . "' and digital_infra_facility_sub.is_delete='0') as quantity",
        ];
        $table_details = [
            "digital_infra_creation",
            $columns
        ];
        $where = "is_delete = 0";
        $order_by = "";

        if ($_POST['search']['value']) {
            $where .= " AND district_name LIKE '" . mysql_like($_POST['search']['value']) . "' ";
        }

        // Datatable Searching
        $search = datatable_searching($search, $columns);



        $sql_function = "SQL_CALC_FOUND_ROWS";

        $result = $pdo->select($table_details, $where, $limit, $start, $order_by, $sql_function);
        // print_r($result);
        $total_records = total_records();

        if ($result->status) {

            $res_array = $result->data;

            foreach ($res_array as $key => $value) {
                // $value['district_name'] = district_list($value['district_name']);
                $quantity = $value['quantity'];

                $data[] = array_values($value);
            }

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data,
                "quantity" => $quantity,
                // "testing"           => $result->sql
            ];
        } else {
            // print_r($result);
        }

        echo json_encode($json_array);
        break;

    case 'get_hostel_by_taluk_name':

        $taluk_name = $_POST['taluk_name'];


        $hostel_name_options = hostel_name('', $taluk_name);

        $hostel_name_options = select_option_host($hostel_name_options, "Select Hostel");

        echo $hostel_name_options;

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
                    $image_view .= '<a href="javascript:print_view(\'/' . $doc_file_name . '\')"><img src="assets/images/images.png"  width="20%" ></a>';
                    // $image_view .= '<img src="assets/images/'.$folder_name.'/'.$doc_name.'"  height="50px" width="50px" >';
                } else if ($cfile_name[1] == 'pdf') {
                    $image_view .= '<a href="javascript:print_pdf(\'/' . $doc_file_name . '\')"><img src="assets/images/pdf.png"   width="20%"  ></a>';
                } else if (($cfile_name[1] == 'pdf') || ($cfile_name[1] == 'xls') || ($cfile_name[1] == 'xlsx')) {
                    $image_view .= '<a href="javascript:print(\'/' . $doc_file_name . '\')"><img src="assets/images/excel.png"  height="20px" width="20px" ></a>';
                } else if (($cfile_name[1] == 'txt') || ($cfile_name[1] == 'docx') || ($cfile_name[1] == 'doc')) {
                    $image_view .= '<a href="javascript:print(\'/' . $doc_file_name . '\')"><img src="assets/images/word.png"  height="20px" width="20px" ></a>';
                }
            }
        }
    } 

    return $image_view;
}




?>