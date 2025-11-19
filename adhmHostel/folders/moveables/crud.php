<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$overall_table = "moveables";
$table_1 = "moveable_kitchen_sub";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action = $_POST['action'];

$feedback_type = "";
$is_active = "";
$unique_id = "";
$prefix = "";

$data = "";
$msg = "";
$error = "";
$status = "";
$test = ""; // For Developer Testing Purpose

// $fileUpload         = new Alirdn\SecureUPload\SecureUPload( $fileUploadConfig );


// $fileUploadPath = $fileUploadConfig->get("upload_folder");

// // Create Folder in root->uploads->(this_folder_name) Before using this file upload
function validateCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}


function generateAssetID($prefix = 'MAD', $numericCode = '17002', $lastNumber = 1) {
    $newNumber = str_pad($lastNumber, 5, '0', STR_PAD_LEFT);
    return sprintf('%s-%s-%s', $prefix, $numericCode, $newNumber);
}

function extract_numbers($string)
{
    // Use preg_replace to remove all non-numeric characters from the string
    $numbers = preg_replace('/\D/', '', $string);
    return $numbers;
}

switch ($action) {

    case 'datatable':
        // DataTable Variables
        $search     = $_POST['search']['value'];
        $length     = $_POST['length'];
        $start      = $_POST['start'];
        $draw       = $_POST['draw'];
    
        $hostel_id = $_SESSION["hostel_id"];
        $list_type = $_POST['list_type'];
        $category_type = $_POST['list_category'];
        $list_asset = $_POST['list_asset'];
        
        $limit      = $length;
    
        $data       = [];
    
        if($length == '-1') {
            $limit  = "";
        }
    
        $table = 'view_moveables_asset';
    
        // Query Variables
        $json_array     = "";
        $columns        = [
            "@a:=@a+1 s_no",
            "asset_id",
            "category",
            "asset",
            "quantity",
            "unique_id"
        ];
        $table_details  = [
            $table." , (SELECT @a:= ".$start.") AS a ",
            $columns
        ];
    
        $where = "hostel_id = '".$_SESSION['hostel_id']."'";
    
        $order_column   = $_POST["order"][0]["column"];
        $order_dir      = $_POST["order"][0]["dir"];
    
        // Datatable Ordering 
        $order_by       = datatable_sorting($order_column,$order_dir,$columns);
    
        // Datatable Searching
        $search         = datatable_searching($search,$columns);
    
        if (!empty($list_type)) {
            $where .= " and k_d_category = '$list_type'";
        }
        if (!empty($category_type)) {
            $where .= " AND category = '$category_type' ";
        }
        if (!empty($list_asset)) {
            $where .= " AND asset = '$list_asset'  ";   
        }
    
        $sql_function   = "SQL_CALC_FOUND_ROWS";
    
        $result         = $pdo->select($table_details,$where,$limit,$start,$order_by,$sql_function);
    
        $total_records  = total_records();
    
        if ($result->status) {
    
            $res_array = $result->data;
            $s_no = $start + 1;  // Start serial number based on the current page index
    
            foreach ($res_array as $key => $value) {
    
                $value['s_no'] = $s_no++;  // Increment serial number with each row
        
                $explode = explode('-', $value['asset_id']);
                $firstPart = $explode[0];
                if ($firstPart == 'MAD') {
                    $value['category'] = digital_category($value['category'])[0]['digital_category'];
                    $value['asset'] = type_of_equipment($value['asset'])[0]['digital_asset'];
                  
                } else if ($firstPart == 'MAK') {
                    $value['category'] = kitchen_category_type($value['category'])[0]['category'];
                    $value['asset'] = kitchen_asset_type($value['asset'])[0]['kitchen_asset'];
                
                }
    
                $unique_id = $value['unique_id'];
                // $btn_update = btn_update($folder_name, $unique_id);
                if ($firstPart == 'MAD') {
                    $btn_update = btn_edits($folder_name, $unique_id, 'digital');
                } else if ($firstPart == 'MAK') {
                    $btn_update = btn_edits($folder_name, $unique_id, 'kitchen');
                }
                
                
                $btn_delete = btn_delete_stk($folder_name, $unique_id, $firstPart);
                $value['unique_id'] = $btn_update . $btn_delete;
    
                $data[] = array_values($value);
            }
    
            $json_array = [
                "draw"              => intval($draw),
                "recordsTotal"      => intval($total_records),
                "recordsFiltered"   => intval($total_records),
                "data"              => $data,
                "testing"           => $result->sql
            ];
        } else {
            print_r($result);
        }
        
        echo json_encode($json_array);
        break;
    
 

    case 'datatable_1':
        // Ensure session is started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    
        // Validate if hostel_id is set in the session
        if (!isset($_SESSION["hostel_id"])) {
            echo json_encode([
                "draw" => intval($_POST['draw'] ?? 0),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => "Hostel ID is not set in session."
            ]);
            break;
        }
    
        // Fetch hostel_id from session
        $hostel_id = $_SESSION["hostel_id"];
    
        // DataTable Variables
        $search = $_POST['search']['value'] ?? '';
        $length = intval($_POST['length'] ?? 10);
        $start = intval($_POST['start'] ?? 0);
        $draw = intval($_POST['draw'] ?? 1);
        $limit = $length;
    
        $data = [];
        $table_batch = "view_moveables_asset";
    
        if ($length == '-1') {
            $limit = "";
        }
    
        // Query Variables
        $columns = [
            "@a:=@a+1 s_no",
            "asset_id",
            "category",
            "asset",
            "quantity",
            "unique_id"
        ];
    
        $table_details = [
            $table_batch . ", (SELECT @a:= " . $start . ") AS a ",
            $columns
        ];
    
        $where = "hostel_id = ?"; // Prepared statement to prevent SQL injection
    
        // Datatable Ordering 
        $order_column = $_POST["order"][0]["column"] ?? 0;
        $order_dir = $_POST["order"][0]["dir"] ?? 'asc';
        $order_by = datatable_sorting($order_column, $order_dir, $columns);
    
        // Datatable Searching
        $search = datatable_searching($search, $columns);
    
        if ($search) {
            $where .= " AND " . $search;
        }
    
        $sql_function = "SQL_CALC_FOUND_ROWS";
    
        // SQL query for data fetching with hostel_id
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM " . $table_batch . " WHERE $where $order_by LIMIT $start, $limit";
    
        $stmt = $mysqli->prepare($sql);
    
        if (!$stmt) {
            die("MySQLi prepare failed: " . $mysqli->error);
        }
    
        // Bind hostel_id parameter to the statement
        $stmt->bind_param('s', $hostel_id);
    
        if ($stmt->execute() === false) {
            die("MySQLi execute failed: " . $stmt->error);
        }
    
        $result = $stmt->get_result();
    
        // Fetch total records
        $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
        if ($total_records_result === false) {
            die("MySQLi query failed: " . $mysqli->error);
        }
        $total_records = $total_records_result->fetch_assoc()['total'];
    
        if ($result) {
            $res_array = $result->fetch_all(MYSQLI_ASSOC);
            $s_no = 0;
            foreach ($res_array as $key => $value) {
                $value['s_no'] = ++$s_no;
    
                $explode = explode('-', $value['asset_id']);
                $firstPart = $explode[0];
                if ($firstPart == 'MAD') {
                    $value['category'] = digital_category($value['category'])[0]['digital_category'];
                    $value['asset'] = type_of_equipment($value['asset'])[0]['digital_asset'];
                } else if ($firstPart == 'MAK') {
                    $value['category'] = kitchen_category_type($value['category'])[0]['category'];
                    $value['asset'] = kitchen_asset_type($value['asset'])[0]['kitchen_asset'];
                }
    
                $unique_id = $value['unique_id'];
                $btn_update = btn_update($folder_name, $unique_id);
                $btn_delete = btn_delete_stk($folder_name, $unique_id, $firstPart);
                $value['unique_id'] = $btn_update . $btn_delete;
    
                $data[] = array_values($value);
            }
    
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data,
            ];
        } else {
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => "No data found."
            ];
        }
    
        echo json_encode($json_array);
        break;
    


    // case 'datatable':
    //     // Ensure session is started
    //     if (session_status() == PHP_SESSION_NONE) {
    //         session_start();
    //     }
    
    //     // Validate if hostel_id is set in the session
    //     if (!isset($_SESSION["hostel_id"])) {
    //         echo json_encode([
    //             "draw" => intval($_POST['draw'] ?? 0),
    //             "recordsTotal" => 0,
    //             "recordsFiltered" => 0,
    //             "data" => [],
    //             "error" => "Hostel ID is not set in session."
    //         ]);
    //         break;
    //     }
    
    //     // Fetch hostel_id from session
    //     $hostel_id = $_SESSION["hostel_id"];
    
    //     // DataTable Variables
    //     $length = intval($_POST['length'] ?? 10);
    //     $start = intval($_POST['start'] ?? 0);
    //     $draw = intval($_POST['draw'] ?? 1);
    //     $limit = $length;
    
    //     $data = [];
    //     $table_batch = "view_moveables_asset";
    
    //     if ($length == '-1') {
    //         $limit = "";
    //     }
    
    //     // Query Variables
    //     $json_array = "";
    //     $columns = [
    //         "'' as s_no",
    //         "asset_id",
    //         "category",
    //         "asset",
    //         "quantity",
    //         "unique_id"
    //     ];
    //     $table_details = implode(", ", $columns);
    //     $where = "hostel_id = ?"; // Prepared statement to prevent SQL injection
    //     $sql_function = "SQL_CALC_FOUND_ROWS";
    //     $order_by = "";
    
    //     // SQL query for data fetching with hostel_id
    //     $sql = "SELECT $sql_function $table_details FROM $table_batch  where hostel_id = ?";
    // echo $sql;
    //     $stmt = $mysqli->prepare($sql);
    
    //     if (!$stmt) {
    //         die("MySQLi prepare failed: " . $mysqli->error);
    //     }
    // echo $hostel_id;
    //     // Bind hostel_id parameter to the statement
    //     $stmt->bind_param('s', $hostel_id);
    
    //     if ($stmt->execute() === false) {
    //         die("MySQLi execute failed: " . $stmt->error);
    //     }
    
    //     $result = $stmt->get_result();
    
    //     // Fetch total records
    //     $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
    //     if ($total_records_result === false) {
    //         die("MySQLi query failed: " . $mysqli->error);
    //     }
    //     $total_records = $total_records_result->fetch_assoc()['total'];
    
    //     if ($result) {
    //         $res_array = $result->fetch_all(MYSQLI_ASSOC);
    //         $s_no = 0;
    //         foreach ($res_array as $key => $value) {
    //             $value['s_no'] = ++$s_no;
    
    //             $explode = explode('-', $value['asset_id']);
    //             $firstPart = $explode[0];
    //             if ($firstPart == 'MAD') {
    //                 $value['category'] = digital_category($value['category'])[0]['digital_category'];
    //                 $value['asset'] = type_of_equipment($value['asset'])[0]['digital_asset'];
    //             } else if ($firstPart == 'MAK') {
    //                 $value['category'] = kitchen_category_type($value['category'])[0]['category'];
    //                 $value['asset'] = kitchen_asset_type($value['asset'])[0]['kitchen_asset'];
    //             }
    //             $value['quantity'];
    //             $unique_id = $value['unique_id'];
    //             $btn_update = btn_update($folder_name, $unique_id);
    //             $btn_delete = btn_delete_stk($folder_name, $unique_id, $firstPart);
    //             $value['unique_id'] = $btn_update . $btn_delete;
    
    //             $data[] = array_values($value);
    //         }
    
    //         $json_array = [
    //             "draw" => intval($draw),
    //             "recordsTotal" => intval($total_records),
    //             "recordsFiltered" => intval($total_records),
    //             "data" => $data,
    //             // "testing" => $result->sql
    //         ];
    //     } else {
    //         $json_array = [
    //             "draw" => intval($draw),
    //             "recordsTotal" => 0,
    //             "recordsFiltered" => 0,
    //             "data" => [],
    //             "error" => "No data found."
    //         ];
    //     }
    
    //     echo json_encode($json_array);
    //     break;
    


    // case 'datatable':
    //     // Database connection
       

    //     // DataTable Variables
    //     $length = $_POST['length'];
    //     $start = $_POST['start'];
    //     $draw = $_POST['draw'];
    //     $limit = $length;
    //     $hostel_id = $_SESSION["hostel_id"];
    //     $data = [];
    //     $table_batch = "view_moveables_asset";

    //     if ($length == '-1') {
    //         $limit = "";
    //     }

    //     // echo $hostel_id;
    //     // Query Variables
    //     $json_array = "";
    //     $columns = [
    //         "'' as s_no",
    //        "asset_id",
    //        "category",
    //        "asset",
    //        "quantity",
    //        "unique_id"
    //     ];
    //     $table_details = implode(", ", $columns);
    //     $where = "hostel_id = ?";
    //     $sql_function = "SQL_CALC_FOUND_ROWS";
    //     $order_by = "";

    //     // SQL query for data fetching
    //     $sql = "SELECT $sql_function $table_details FROM $table_batch where $where";
       
    //     $stmt = $mysqli->prepare($sql);
        
    //     if ($stmt->execute() === false) {
    //         die("MySQLi execute failed: " . $stmt->error);
    //     }

    //     $result = $stmt->get_result();

    //     // Fetch total records
    //     $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
    //     if ($total_records_result === false) {
    //         die("MySQLi query failed: " . $mysqli->error);
    //     }
    //     $total_records = $total_records_result->fetch_assoc()['total'];

    //     if ($result) {
    //         $res_array = $result->fetch_all(MYSQLI_ASSOC);
    //         $s_no = 0;
    //         // foreach ($res_array as $key => $value) {
    //             // if ($result) {
    //                 foreach ($res_array as $key => $value) {
    //                     // echo"fff";
    //                     $value['s_no'] = ++$s_no;
        
    //                     $explode=explode('-',$value['asset_id']);
    //     $firstPart = $explode[0];
    //     if($firstPart=='MAD'){
    //         $value['category']=digital_category($value['category'])[0]['digital_category'];
    //         $value['asset']=type_of_equipment($value['asset'])[0]['digital_asset'];
    //     }else if($firstPart == 'MAK'){
    //         $value['category']=kitchen_category_type($value['category'])[0]['category'];
    //         $value['asset']=kitchen_asset_type($value['asset'])[0]['kitchen_asset'];
    // }
    // $value['quantity'];
    // $unique_id=$value['unique_id'];
    // $btn_update = btn_update($folder_name, $unique_id);
    // $btn_delete = btn_delete_stk($folder_name, $unique_id, $firstPart);
    // $value['unique_id']=$btn_update.$btn_delete;

    //                     $data[] = array_values($value);
    //                 }
                
    //         $json_array = [
    //             "draw" => intval($draw),
    //             "recordsTotal" => intval($total_records),
    //             "recordsFiltered" => intval($total_records),
    //             "data" => $data,
    //             // "testing" => $result->sql
    //         ];
    //     }else{

    //     }
       
    //     echo json_encode($json_array);

    //     // Close connection
    //     // $stmt->close();
    //     // $mysqli->close();

    //     break;
        
 
    case 'main_createupdate':

        $token = $_POST['csrf_token'];

        // Validate CSRF token
        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        // Sanitize input values
        $stock_id = $_POST["stock_id"];
        $supplier_name = $_POST["supplier_name"];
        $address = $_POST["address"];
        $bill_no = $_POST["bill_no"];
        $hostel_name = $_POST["hostel_name"];
        $discount = $_POST["discount"];
        $expense = $_POST["expense"];
        $gst = $_POST["gst"];
        $net_total_amount = $_POST["net_total_amount"];
        $tot_qty = $_POST["tot_qty"];
        $tot_amount = $_POST["tot_amount"];
        $district = $_POST["district"];
        $taluk = $_POST["taluk"];
        $is_active = $_POST["is_active"];
        $screen_unique_id = $_POST["screen_unique_id"];
        $academic_year = $_SESSION["academic_year"];
        $unique_id = $_POST["unique_id"];
        // Handle entry date
        $entry_date = ($_POST["entry_date"] == '') ? date('Y-m-d') : $_POST["entry_date"];

        // File upload handling
        $file_names = '';
        $file_org_names = '';

        $allowedExts = array('pdf', 'jpg', 'jpeg', 'png');
        $extension = pathinfo($_FILES["test_file"]['name'], PATHINFO_EXTENSION);

        // Check if file extension is allowed
        if ($_FILES["test_file"]['name']) {
            if (in_array($extension, $allowedExts)) {
                $file_exp = explode(".", $_FILES["test_file"]['name']);
                $tem_name = random_strings(25) . "." . $file_exp[1];
                move_uploaded_file($_FILES["test_file"]["tmp_name"], '../../uploads/stock_entry/' . $tem_name);
                $file_names = $tem_name;
                $file_org_names = $_FILES["test_file"]['name'];
            } else {
                die('File type not allowed.');
            }
        }

        // Prepare SQL statements for INSERT and UPDATE
        if ($unique_id) {

            // Update query
            if ($file_names != '') {
                $sql = "UPDATE stock_entry SET supplier_name=?, address=?, entry_date=?, bill_no=?, hostel_name=?, stock_id=?, discount=?, expense=?, gst=?, net_total_amount=?, academic_year=?, district=?, taluk=?, tot_qty=?, tot_amount=?, file_name=?, file_org_name=?, screen_unique_id=? WHERE unique_id=?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("sssssssssssssssssss", $supplier_name, $address, $entry_date, $bill_no, $hostel_name, $stock_id, $discount, $expense, $gst, $net_total_amount, $academic_year, $district, $taluk, $tot_qty, $tot_amount, $file_names, $file_org_names, $screen_unique_id, $unique_id);
            } else {
                $sql = "UPDATE stock_entry SET supplier_name=?, address=?, entry_date=?, bill_no=?, hostel_name=?, stock_id=?, discount=?, expense=?, gst=?, net_total_amount=?, academic_year=?, district=?, taluk=?, tot_qty=?, tot_amount=?, screen_unique_id=? WHERE unique_id=?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("sssssssssssssssss", $supplier_name, $address, $entry_date, $bill_no, $hostel_name, $stock_id, $discount, $expense, $gst, $net_total_amount, $academic_year, $district, $taluk, $tot_qty, $tot_amount, $screen_unique_id, $unique_id);
            }
        } else {
            // Insert query
            if ($file_names != '') {
                $sql = "INSERT INTO stock_entry (supplier_name, address, entry_date, bill_no, hostel_name, stock_id, discount, expense, gst, net_total_amount, academic_year, district, taluk, tot_qty, tot_amount, file_name, file_org_name, unique_id, screen_unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("sssssssssssssssssss", $supplier_name, $address, $entry_date, $bill_no, $hostel_name, $stock_id, $discount, $expense, $gst, $net_total_amount, $academic_year, $district, $taluk, $tot_qty, $tot_amount, $file_names, $file_org_names, unique_id($prefix), $screen_unique_id);
            } else {
                $sql = "INSERT INTO stock_entry (supplier_name, address, entry_date, bill_no, hostel_name, stock_id, discount, expense, gst, net_total_amount, academic_year, district, taluk, tot_qty, tot_amount, unique_id, screen_unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("sssssssssssssssss", $supplier_name, $address, $entry_date, $bill_no, $hostel_name, $stock_id, $discount, $expense, $gst, $net_total_amount, $academic_year, $district, $taluk, $tot_qty, $tot_amount, unique_id($prefix), $screen_unique_id);
            }
        }
        // Execute statement
        if ($stmt->execute()) {
            $msg = $msg;
            $status = "success";
            $data = [];
            $error = "";
        } else {
            $status = "error";
            $data = [];
            $error = "Failed to execute query: " . $stmt->error;
            $msg = "error";
        }
        if ($unique_id) {
            $msg = 'update';
        } else {
            $msg = 'create';
        }


        // Close statement
        $stmt->close();

        // Construct JSON response
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
        ];

        echo json_encode($json_array);

        break;

    case 'list_category_type':

        $list_type = $_POST['list_type'];

        $list_options = list_category_type('', $list_type);

        $category_options = select_option($list_options, 'Select Category');

        echo $category_options;

        break;

    case 'list_asset_type':

        $list_type = $_POST['list_type'];

        $list_category = $_POST['list_category'];

        $category_options = list_asset_type('', $list_category, $list_type);

        $asset_options = select_option($category_options, 'Select Asset');

        echo $asset_options;

        break;

    case 'overall_createupdate':
        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        $stock_id = sanitizeInput($_POST["stock_id"]);
        $bill_no = sanitizeInput($_POST["bill_no"]);
        $hostel_name = sanitizeInput($_POST["hostel_name"]);
        $district = sanitizeInput($_POST["district"]);
        $taluk = sanitizeInput($_POST["taluk"]);
        $academic_year = sanitizeInput($_SESSION['academic_year']);

        if ($_POST["entry_date"] == '') {
            $entry_date = date('Y-m-d');
        } else {
            $entry_date = $_POST["entry_date"];
        }

        // Prepare SQL statement
        $sql = "UPDATE $overall_table 
                    SET entry_date=?, bill_no=?, hostel_unique_id=?, academic_year=?, district_unique_id=?, taluk_unique_id=?
                    WHERE stock_id=?";
        // $params = array($entry_date, $bill_no, $hostel_name, $academic_year, $district, $taluk, $stock_id);


        // Execute the statement
        $stmt = $mysqli->prepare($sql);
        // print_r($stmt);
        if ($stmt === false) {
            die('MySQL prepare error: ' . $mysqli->error);
        }

        // Bind parameters
        // $types = str_repeat('s', count($params)); // All parameters are strings
        $stmt->bind_param('sssssss', $entry_date, $bill_no, $hostel_name, $academic_year, $district, $taluk, $stock_id);


        if ($stmt->execute()) {
            $status = "success";
            $msg = ($unique_id) ? "update" : "create";
            $data = [];
            $error = "";
        } else {
            $status = "error";
            $msg = "error";
            $data = [];
            $error = $mysqli->error;
        }

        // Close statement
        $stmt->close();

        // Construct JSON response
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
        ];

        echo json_encode($json_array);

        break;

        case 'main_createupdate':

            $token = $_POST['csrf_token'];
    
            // Validate CSRF token
            if (!validateCSRFToken($token)) {
                die('CSRF validation failed.');
            }
    
            // Sanitize input values
            $stock_id = $_POST["stock_id"];
            $supplier_name = $_POST["supplier_name"];
            $address = $_POST["address"];
            $bill_no = $_POST["bill_no"];
            $hostel_name = $_POST["hostel_name"];
            $discount = $_POST["discount"];
            $expense = $_POST["expense"];
            $gst = $_POST["gst"];
            $net_total_amount = $_POST["net_total_amount"];
            $tot_qty = $_POST["tot_qty"];
            $tot_amount = $_POST["tot_amount"];
            $district = $_POST["district"];
            $taluk = $_POST["taluk"];
            $is_active = $_POST["is_active"];
            $screen_unique_id = $_POST["screen_unique_id"];
            $academic_year = $_SESSION["academic_year"];
            $unique_id = $_POST["unique_id"];
            // Handle entry date
            $entry_date = ($_POST["entry_date"] == '') ? date('Y-m-d') : $_POST["entry_date"];
    
            // File upload handling
            $file_names = '';
            $file_org_names = '';
    
            $allowedExts = array('pdf', 'jpg', 'jpeg', 'png');
            $extension = pathinfo($_FILES["test_file"]['name'], PATHINFO_EXTENSION);
    
            // Check if file extension is allowed
            if ($_FILES["test_file"]['name']) {
                if (in_array($extension, $allowedExts)) {
                    $file_exp = explode(".", $_FILES["test_file"]['name']);
                    $tem_name = random_strings(25) . "." . $file_exp[1];
                    move_uploaded_file($_FILES["test_file"]["tmp_name"], '../../uploads/stock_entry/' . $tem_name);
                    $file_names = $tem_name;
                    $file_org_names = $_FILES["test_file"]['name'];
                } else {
                    die('File type not allowed.');
                }
            }
    
            // Prepare SQL statements for INSERT and UPDATE
            if ($unique_id) {
    
                // Update query
                if ($file_names != '') {
                    $sql = "UPDATE stock_entry SET supplier_name=?, address=?, entry_date=?, bill_no=?, hostel_name=?, stock_id=?, discount=?, expense=?, gst=?, net_total_amount=?, academic_year=?, district=?, taluk=?, tot_qty=?, tot_amount=?, file_name=?, file_org_name=?, screen_unique_id=? WHERE unique_id=?";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param("sssssssssssssssssss", $supplier_name, $address, $entry_date, $bill_no, $hostel_name, $stock_id, $discount, $expense, $gst, $net_total_amount, $academic_year, $district, $taluk, $tot_qty, $tot_amount, $file_names, $file_org_names, $screen_unique_id, $unique_id);
                } else {
                    $sql = "UPDATE stock_entry SET supplier_name=?, address=?, entry_date=?, bill_no=?, hostel_name=?, stock_id=?, discount=?, expense=?, gst=?, net_total_amount=?, academic_year=?, district=?, taluk=?, tot_qty=?, tot_amount=?, screen_unique_id=? WHERE unique_id=?";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param("sssssssssssssssss", $supplier_name, $address, $entry_date, $bill_no, $hostel_name, $stock_id, $discount, $expense, $gst, $net_total_amount, $academic_year, $district, $taluk, $tot_qty, $tot_amount, $screen_unique_id, $unique_id);
                }
            } else {
                // Insert query
                if ($file_names != '') {
                    $sql = "INSERT INTO stock_entry (supplier_name, address, entry_date, bill_no, hostel_name, stock_id, discount, expense, gst, net_total_amount, academic_year, district, taluk, tot_qty, tot_amount, file_name, file_org_name, unique_id, screen_unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param("sssssssssssssssssss", $supplier_name, $address, $entry_date, $bill_no, $hostel_name, $stock_id, $discount, $expense, $gst, $net_total_amount, $academic_year, $district, $taluk, $tot_qty, $tot_amount, $file_names, $file_org_names, unique_id($prefix), $screen_unique_id);
                } else {
                    $sql = "INSERT INTO stock_entry (supplier_name, address, entry_date, bill_no, hostel_name, stock_id, discount, expense, gst, net_total_amount, academic_year, district, taluk, tot_qty, tot_amount, unique_id, screen_unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param("sssssssssssssssss", $supplier_name, $address, $entry_date, $bill_no, $hostel_name, $stock_id, $discount, $expense, $gst, $net_total_amount, $academic_year, $district, $taluk, $tot_qty, $tot_amount, unique_id($prefix), $screen_unique_id);
                }
            }
            // Execute statement
            if ($stmt->execute()) {
                $msg = $msg;
                $status = "success";
                $data = [];
                $error = "";
            } else {
                $status = "error";
                $data = [];
                $error = "Failed to execute query: " . $stmt->error;
                $msg = "error";
            }
            if ($unique_id) {
                $msg = 'update';
            } else {
                $msg = 'create';
            }
    
    
            // Close statement
            $stmt->close();
    
            // Construct JSON response
            $json_array = [
                "status" => $status,
                "data" => $data,
                "error" => $error,
                "msg" => $msg,
            ];
    
            echo json_encode($json_array);
    
            break;
    
    case 'edit_already_digital_assert':
            
        $table = 'moveables_digital_sub';

        // Retrieve POST data
        $asset = $_POST["type_of_equipment"];
        $hostel_id = $_SESSION['hostel_id'];
        $screen_unique_id = $_POST["screen_unique_id"];
        $unique_id = $_POST["unique_id"];
        // Include the connection
        $mysqli = new mysqli("localhost", "root", "", "adi_dravidar");

        // Check connection
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Prepare the SQL statement
        $sql = "SELECT COUNT(asset) AS count FROM " . $table . " WHERE asset = ? AND hostel_id = ?";
        if (!$stmt = $mysqli->prepare($sql)) {
            error_log("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
            $json_array = [
                "status" => false,
                "msg" => "error"
            ];
            echo json_encode($json_array);
            $mysqli->close();
            break;
        }

        // Bind the parameters
        $stmt->bind_param("ss", $asset, $hostel_id);

        // Execute the statement
        if (!$stmt->execute()) {
            error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            $json_array = [
                "status" => false,
                "msg" => "error"
            ];
            echo json_encode($json_array);
            $stmt->close();
            $mysqli->close();
            break;
        }

        // Bind the result variable
        $stmt->bind_result($count);

        // Fetch the result
        $status = false;
        $msg = "error";
        if ($stmt->fetch()) {
            $status = true;
            $msg = ($count > 0) ? "already_exists" : "not_found";
        }

        // Close the statement
        $stmt->close();

        // Close the connection
        $mysqli->close();

        // Prepare JSON response
        $json_array = [
            "status" => $status,
            "msg" => $msg
        ];

        echo json_encode($json_array);
        break;

        case 'edit_already_assert':
            
            $table = 'moveable_kitchen_sub';
    
            // Retrieve POST data
            $asset = $_POST["asset"];
            $hostel_id = $_SESSION['hostel_id'];
            $screen_unique_id = $_POST["screen_unique_id"];
            $unique_id = $_POST["unique_id"];
            // Include the connection
            $mysqli = new mysqli("localhost", "root", "", "adi_dravidar");
    
            // Check connection
            if ($mysqli->connect_error) {
                die("Connection failed: " . $mysqli->connect_error);
            }
    
            // Prepare the SQL statement
            $sql = "SELECT COUNT(asset) AS count FROM " . $table . " WHERE asset = ? AND hostel_id = ?";
            if (!$stmt = $mysqli->prepare($sql)) {
                error_log("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
                $json_array = [
                    "status" => false,
                    "msg" => "error"
                ];
                echo json_encode($json_array);
                $mysqli->close();
                break;
            }
    
            // Bind the parameters
            $stmt->bind_param("ss", $asset, $hostel_id);
    
            // Execute the statement
            if (!$stmt->execute()) {
                error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
                $json_array = [
                    "status" => false,
                    "msg" => "error"
                ];
                echo json_encode($json_array);
                $stmt->close();
                $mysqli->close();
                break;
            }
    
            // Bind the result variable
            $stmt->bind_result($count);
    
            // Fetch the result
            $status = false;
            $msg = "error";
            if ($stmt->fetch()) {
                $status = true;
                $msg = ($count > 0) ? "already_exists" : "not_found";
            }
    
            // Close the statement
            $stmt->close();
    
            // Close the connection
            $mysqli->close();
    
            // Prepare JSON response
            $json_array = [
                "status" => $status,
                "msg" => $msg
            ];
    
            echo json_encode($json_array);
            break;

        case 'moveable_add_update':

            $overall_table = "moveables";
        
            // Retrieve session variables
            $district_id = $_SESSION["district_id"];
            $taluk_id = $_SESSION['taluk_id'];
            $hostel_id = $_SESSION['hostel_id'];
            $screen_unique_id = sanitizeInput($_POST["screen_unique_id"]);
            $unique_id = sanitizeInput($_POST["unique_id"]);
        // echo "hii";
            // Check for existing records with the same hostel_id where is_delete=0
            $check_sql = "SELECT COUNT(*) AS count FROM $overall_table WHERE hostel_id = ? AND is_delete = 0";
            $check_stmt = $mysqli->prepare($check_sql);
            $check_stmt->bind_param("i", $hostel_id);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            $row = $check_result->fetch_assoc();
            $count = $row['count'];
            $check_stmt->close();
        // echo "jii";
            // Determine whether to update or insert
            if ($count > 0) {
                // echo "yyyy";
                // If more than one record found
                $status = "error";
                $data = [];
                $error = "You should store only one record with this hostel_id.";
                $msg = "more_than";
                // echo $msg;
            } else {
                if ($unique_id) {
                    // Update query
                    $sql = "UPDATE $overall_table SET district_id=?, taluk_id=?, hostel_id=?, screen_unique_id=? WHERE unique_id=?";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param("sssss", $district_id, $taluk_id, $hostel_id, $screen_unique_id, $unique_id);
                } else {
                    // Insert query
                    $sql = "INSERT INTO $overall_table (district_id, taluk_id, hostel_id, screen_unique_id, unique_id) VALUES (?, ?, ?, ?, ?)";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param("sssss", $district_id, $taluk_id, $hostel_id, $screen_unique_id, unique_id($prefix));
                }
        
                // Execute statement
                if ($stmt->execute()) {
                    if ($unique_id) {
                        $msg = "update";
                    } else {
                        $msg = "create";
                    }
                    $status = "success";
                    $data = [];
                    $error = "";
                } else {
                    $status = "error";
                    $data = [];
                    $error = "Failed to execute query: " . $stmt->error;
                    $msg = "error";
                }
            }
            // echo $msg;
        
            // Close statement
        
        // echo $msg;
            // Prepare JSON response
            $json_array = [
                "status" => $status,
                "data" => $data,
                "error" => $error,
                "msg" => $msg
            ];
       
            echo json_encode($json_array);
            $stmt->close();
            break;
        

    // case 'moveable_add_update':

    //     $overall_table = "moveables";


    //     $district_id = $_SESSION["district_id"];
    //     $taluk_id = $_SESSION['taluk_id'];
    //     $hostel_id = $_SESSION['hostel_id'];
    //     $screen_unique_id = sanitizeInput($_POST["screen_unique_id"]);
    //     $unique_id = sanitizeInput($_POST["unique_id"]);

    //   $check_sql = "SELECT COUNT(*) AS count FROM $overall_table WHERE hostel_id=$hostel_id and is_delete = 0";
    //         $count = $row['count'];

    //     // Prepare SQL statements for INSERT and 
     
    // if ($count > 1) {
    //     // If more than one record found
    //     $status = "error";
    //     $data = [];
    //     $error = "";
    //     $msg = "more_than";
    // } else {
    //     if ($unique_id) {
    //         // Update query
    //         $sql = "UPDATE $overall_table SET district_id=?, taluk_id=?, hostel_id=?, screen_unique_id=? WHERE unique_id=?";
    //         $stmt = $mysqli->prepare($sql);
    //         $stmt->bind_param("sssssssss", $district_id, $taluk_id, $hostel_id, $screen_unique_id, $unique_id);
    //     } else {
    //         // Insert query
    //         $sql = "INSERT INTO $overall_table (district_id, taluk_id, hostel_id, screen_unique_id, unique_id) VALUES (?, ?, ?, ?, ?)";
    //         $stmt = $mysqli->prepare($sql);
    //         $stmt->bind_param("sssss", $district_id, $taluk_id, $hostel_id, $screen_unique_id, unique_id($prefix));
    //     }
    
    //     // Execute statement
    //     // $stmt->execute();

    //     if ($stmt->execute()) {
    //         if ($unique_id) {
    //             $msg = "update";
    //         } else {
    //             $msg = "create";
    //         }
    //         $status = "success";
    //         $data = [];
    //         $error = "";
    //     } else {
    //         $status = "error";
    //         $data = [];
    //         $error = "Failed to execute query: " . $stmt->error;
    //         $msg = "error";
    //     }
    // }
    //     // Close statement and connection
    //     $stmt->close();


    //     // Prepare JSON response
    //     $json_array = [
    //         "status" => $status,
    //         "data" => $data,
    //         "error" => $error,
    //         "msg" => $msg
    //     ];

    //     echo json_encode($json_array);

    //     break;

  

        case 'update_kitchen':

            $table = "moveable_kitchen_sub";
    
            // Sanitize inputs
            $hostel_id = $_SESSION['hostel_id'];
            $screen_unique_id = sanitizeInput($_POST["screen_unique_id"]);
            $newCapacity = sanitizeInput($_POST["newCapacity"]);
            $asset = sanitizeInput($_POST["asset"]);
            // echo $screen_unique_id;
            // echo $newCapacity;
            // echo $asset;
            // Initialize response variables
            $status = "error";
            $data = [];
            $error = "";
            $msg = "";
            // echo "  before if";
            // Prepare SQL statements for UPDATE
            if ($asset) {
                // echo " inside if";
                $sql = "UPDATE $table SET quantity=? WHERE asset=? and hostel_id=?";
                // echo $sql;
                $status = "error";  // Default to error
                $msg = "error";     // Default to error
    
                if ($stmt = $mysqli->prepare($sql)) {
                    $stmt->bind_param("iss", $newCapacity, $asset, $hostel_id);
    
                    if ($stmt->execute()) {
                        $status = "success";
                        $msg = "update";
                    } else {
                        $error = "Failed to execute query: " . $stmt->error;
                    }
    
                    $stmt->close();
                } else {
                    $error = "Failed to prepare SQL statement: " . $mysqli->error;
                }
            }
    
            // Prepare JSON response
            $json_array = [
                "status" => $status,
                "data" => $data,
                "error" => $error,
                "msg" => $msg
            ];
    
            echo json_encode($json_array);
    
            break;


    case 'moveables_sub_datatable':

        // DataTable Variables
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $screen_unique_id = $_POST['screen_unique_id'];
        $folder_name = "kitchen_sub";
        $limit = $length == '-1' ? "" : $length;

        $data = [];

        // Query Variables
        $json_array = "";
        $columns = [
            "' ' as sno",
            "(select category from kitchen_category where kitchen_category.unique_id = moveable_kitchen_sub.kitchen_category) as kitchen_category",
            "(select kitchen_asset from kitchen_asset where kitchen_asset.unique_id = moveable_kitchen_sub.kitchen_asset) as kitchen_asset",
            "quantity",
            "unit",
            // "rate",
            // "amount",
            "unique_id",
            "screen_unique_id",
            // "item_name as item_id",
        ];
        $table = "moveable_kitchen_sub";
        $table_details = $table . " , (SELECT @a:= ?) AS a ";
        $where = "is_delete = 0 and screen_unique_id = ?";
        $order_by = ""; // You can modify this to add an order by clause if needed

        $sql_function = "SQL_CALC_FOUND_ROWS";

        // SQL query for data fetching
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_details WHERE $where";
        if ($limit) {
            $sql .= " LIMIT ?, ?";
        }

        $stmt = $mysqli->prepare($sql);
        if ($limit) {
            $stmt->bind_param("isii", $start, $screen_unique_id, $start, $limit);
        } else {
            $stmt->bind_param("is", $start, $screen_unique_id);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch total records
        $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
        $total_records = $total_records_result->fetch_assoc()['total'];

        if ($result) {
            $res_array = $result->fetch_all(MYSQLI_ASSOC);
            $sno = 0;
            foreach ($res_array as $key => $value) {
                $sno = $sno + 1;
                $value['sno'] = $sno;
                $unique = $value['unique_id'];
                // $btn_delete = btn_delete($folder_name, $unique);

                $value['unique_id'] = $btn_delete;
                $data[] = array_values($value);
            }

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data,
            ];
        } else {
            // Handle the error case
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => $stmt->error,
            ];
        }

        echo json_encode($json_array);

        // Close connection
        $stmt->close();
        $mysqli->close();

        break;

//         case 'moveable_digital':

//             $table = "moveables_digital_sub";
//             $district_id = $_SESSION["district_id"];
//             $taluk_id = $_SESSION['taluk_id'];
//             $hostel_id = $_SESSION['hostel_id'];
//             // Assuming $mysqli is already initialized and connected
//             $digital_category = sanitizeInput($_POST["digital_category"]);
//             $type_of_equipment = sanitizeInput($_POST["type_of_equipment"]);
//             // echo $type_of_equipment;
//             $no_of_dev = sanitizeInput($_POST["no_of_dev"]);
//             $location_dev = sanitizeInput($_POST["location_dev"]);
//             $spe_devices = sanitizeInput($_POST["spe_devices"]);
//             $brand = sanitizeInput($_POST["brand"]);
//             $other_brand = sanitizeInput($_POST["other_brand"]);

//             $asset = sanitizeInput($_POST["asset"]);

    
//             $size = sanitizeInput($_POST["size"]);
//             $procurement_year = sanitizeInput($_POST["procurement_year"]);
//             $cableConnection = sanitizeInput($_POST["cableConnection"]);
    
    
//             $is_active = sanitizeInput($_POST["is_active"]);
//             $unique_id = sanitizeInput($_POST["unique_id"]);
//             $screen_unique_id = sanitizeInput($_POST["screen_unique_id"]);
//             // echo $unique_id;
//             // Convert qty to float and format to 2 decimal places
//             $stock_qty = number_format(floatval($qty), 2, '.', '');

//             $asset_id = generateAssetID($prefix, $numericCode, $lastNumber);

//             // Prepare SQL statements for INSERT and UPDATE
//             if ($unique_id) {
//                 // Update query
    
//                 $sql = "UPDATE $table SET screen_unique_id=?, category=?, asset=?, quantity=?, location_dev=?, spe_devices=?, brand=?,other_brand=?, size=?, procurement_year=?, cableConnection=? WHERE unique_id=? and hostel_id=? and taluk_id=? and district_id=?";
//                 $stmt = $mysqli->prepare($sql);
//                 $stmt->bind_param("sssssssssssssss", $screen_unique_id, $digital_category, $type_of_equipment, $no_of_dev, $location_dev, $spe_devices, $brand, $other_brand, $size, $procurement_year, $cableConnection, $unique_id, $hostel_id, $taluk_id, $district_id);
//                 // print_r($stmt);die();
//             } else {

// //                 $prefix_1 = 'MAD';
// // $numericCode = extract_numbers($row['last_asset_id']); // Extract the numeric part from last_asset_id
// // if ($row['last_asset_id']) {
// //     $lastNumber = (int)substr($row['last_asset_id'], -5) + 1;
// // } else {
// //     $lastNumber = 1;
// // }

// // $asset_id = generateAssetID($prefix_1, $numericCode, $lastNumber);
// // echo "Generated Asset ID: " . $asset_id;

               
        
//                 // Generate asset_id
//                 $prefix_1 = 'MAD';
//                 $numericCode = extract_numbers($_SESSION['hostel_main_id']);

//             $lastAssetIdQuery = "SELECT MAX(asset_id) AS last_asset_id FROM $table WHERE hostel_id=? AND taluk_id=? AND district_id=?";
//                 $stmt = $mysqli->prepare($lastAssetIdQuery);
//                 $stmt->bind_param("sss", $hostel_id, $taluk_id, $district_id);
//                 $stmt->execute();
//                 $result = $stmt->get_result();
//                 $row = $result->fetch_assoc();

//                 // echo "Last asset ID: " . $row['last_asset_id']; // Debugging output

//                 if ($row['last_asset_id']) {
//                     $lastNumber = (int)substr($row['last_asset_id'], -5) + 1;
//                     // echo "Next number: " . $lastNumber; // Debugging output
//                 } else {
//                     $lastNumber = 1;
//                 }

    
//                 $asset_id = generateAssetID($prefix_1, $numericCode, $lastNumber);

//                 // print_r($asset_id);

    
//                 // Insert query
//                 $sql = "INSERT INTO $table (screen_unique_id, category, asset, quantity, location_dev, spe_devices, brand,other_brand, size, procurement_year, cableConnection, hostel_id, taluk_id, district_id, asset_id, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?)";
//                 // print_r($sql);
//                 $stmt = $mysqli->prepare($sql);
//                 $stmt->bind_param("ssssssssssssssss", $screen_unique_id, $digital_category, $type_of_equipment, $no_of_dev, $location_dev, $spe_devices, $brand, $other_brand, $size, $procurement_year, $cableConnection, $hostel_id, $taluk_id, $district_id, $asset_id, unique_id($prefix));
//             }
    
//             if ($stmt->execute()) {
//                 $msg = $unique_id ? "update" : "create";
    
//                 // echo $msg;
    
//                 $status = "success";
//                 $data = [];
//                 $error = "";
//             } else {
//                 $status = "error";
//                 $data = [];
//                 $error = "No rows affected";
//                 $msg = "error";
//             }
    
//             // Prepare JSON response
//             $json_array = [
//                 "status" => $status,
//                 "data" => $data,
//                 "error" => $error,
//                 "msg" => $msg
//             ];
    
//             echo json_encode($json_array);
    
    
//             break;


case 'moveable_digital':

    $table = "moveables_digital_sub";
    $district_id = $_SESSION["district_id"];
    $taluk_id = $_SESSION['taluk_id'];
    $hostel_id = $_SESSION['hostel_id'];

    // Sanitize input
    $digital_category = sanitizeInput($_POST["digital_category"]);
    $type_of_equipment = sanitizeInput($_POST["type_of_equipment"]);
    $no_of_dev = sanitizeInput($_POST["no_of_dev"]);
    $location_dev = sanitizeInput($_POST["location_dev"]);
    $spe_devices = sanitizeInput($_POST["spe_devices"]);
    $brand = sanitizeInput($_POST["brand"]);
    $other_brand = sanitizeInput($_POST["other_brand"]);
    $asset = sanitizeInput($_POST["asset"]);
    $size = sanitizeInput($_POST["size"]);
    $procurement_year = sanitizeInput($_POST["procurement_year"]);
    $cableConnection = sanitizeInput($_POST["cableConnection"]);
    $is_active = sanitizeInput($_POST["is_active"]);
    $unique_id = sanitizeInput($_POST["unique_id"]);
    $screen_unique_id = sanitizeInput($_POST["screen_unique_id"]);

    // Prepare SQL statements for INSERT and UPDATE
    if ($unique_id) {
        // Update query
        $sql = "UPDATE $table SET screen_unique_id=?, category=?, asset=?, quantity=?, location_dev=?, spe_devices=?, brand=?, other_brand=?, size=?, procurement_year=?, cableConnection=? WHERE unique_id=? AND hostel_id=? AND taluk_id=? AND district_id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sssssssssssssss", $screen_unique_id, $digital_category, $type_of_equipment, $no_of_dev, $location_dev, $spe_devices, $brand, $other_brand, $size, $procurement_year, $cableConnection, $unique_id, $hostel_id, $taluk_id, $district_id);
    } else {
        // Generate asset_id
        $prefix_1 = 'MAD';
        $numericCode = extract_numbers($_SESSION['hostel_main_id']);

        $lastAssetIdQuery = "SELECT MAX(asset_id) AS last_asset_id FROM $table WHERE hostel_id=? AND taluk_id=? AND district_id=?";
        // printr($lastAssetIdQuery);
        $stmt = $mysqli->prepare($lastAssetIdQuery);
        $stmt->bind_param("sss", $hostel_id, $taluk_id, $district_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row['last_asset_id']) {
            $lastNumber = (int)substr($row['last_asset_id'], -5) + 1;
        } else {
            $lastNumber = 1;
        }

        $asset_id = generateAssetID($prefix_1, $numericCode, $lastNumber);

        // Insert query
        $sql = "INSERT INTO $table (screen_unique_id, category, asset, quantity, location_dev, spe_devices, brand, other_brand, size, procurement_year, cableConnection, hostel_id, taluk_id, district_id, asset_id, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ssssssssssssssss", $screen_unique_id, $digital_category, $type_of_equipment, $no_of_dev, $location_dev, $spe_devices, $brand, $other_brand, $size, $procurement_year, $cableConnection, $hostel_id, $taluk_id, $district_id, $asset_id, unique_id($prefix));
    }

    if ($stmt->execute()) {
        $msg = $unique_id ? "update" : "create";
        $status = "success";
        $data = [];
        $error = "";
    } else {
        $status = "error";
        $data = [];
        $error = "No rows affected";
        $msg = "error";
    }

    // Prepare JSON response
    $json_array = [
        "status" => $status,
        "data" => $data,
        "error" => $error,
        "msg" => $msg
    ];

    echo json_encode($json_array);

    break;

    

            case 'moveables_digit_datatable':

                // DataTable Variables
                $length = $_POST['length'];
                $start = $_POST['start'];
                $draw = $_POST['draw'];
                $screen_unique_id = $_POST['screen_unique_id'];
                $unique_id = $_POST['unique_id'];
                $folder_name = "moveables_1";
                $limit = $length == '-1' ? "" : $length;
        
                $data = [];
        
                // Query Variables
                $json_array = "";
                $columns = [
                    "' ' as sno",
                    "(select digital_category from digital_category where digital_category.unique_id = moveables_digital_sub.digital_category) as digital_category",
                    "(select digital_asset from digital_asset where digital_asset.unique_id = moveables_digital_sub.type_of_equipment) as digital_asset",
                    "no_of_dev",
                    "location_dev",
                    "unique_id",
                    "screen_unique_id",
                   ];
                $table = "moveables_digital_sub";
                $table_details = $table . " , (SELECT @a:= ?) AS a ";
                $where = "is_delete = 0 and screen_unique_id = ?";
                $order_by = ""; // You can modify this to add an order by clause if needed
        
                $sql_function = "SQL_CALC_FOUND_ROWS";
        
                // SQL query for data fetching
                $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_details WHERE $where";
                if ($limit) {
                    $sql .= " LIMIT ?, ?";
                }
        
                $stmt = $mysqli->prepare($sql);
                if ($limit) {
                    $stmt->bind_param("isii", $start, $screen_unique_id, $start, $limit);
                } else {
                    $stmt->bind_param("is", $start, $screen_unique_id);
                }
        
                $stmt->execute();
                $result = $stmt->get_result();
        // echo $result;
                // Fetch total records
                $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
                $total_records = $total_records_result->fetch_assoc()['total'];
        
                if ($result) {
                    $res_array = $result->fetch_all(MYSQLI_ASSOC);
                    $sno = 0;
                    foreach ($res_array as $key => $value) {
                        $sno = $sno + 1;
                        $value['sno'] = $sno;
                        $unique_id = $value['unique_id'];
                        $btn_delete = btn_delete($folder_name, $value['unique_id']);
        
                        $value['unique_id'] = $btn_delete;
                        $data[] = array_values($value);
                    }
        
                    $json_array = [
                        "draw" => intval($draw),
                        "recordsTotal" => intval($total_records),
                        "recordsFiltered" => intval($total_records),
                        "data" => $data,
                    ];
                } else {
                    // Handle the error case
                    $json_array = [
                        "draw" => intval($draw),
                        "recordsTotal" => 0,
                        "recordsFiltered" => 0,
                        "data" => [],
                        "error" => $stmt->error,
                    ];
                }
        
                echo json_encode($json_array);
        
                // Close connection
                $stmt->close();
                $mysqli->close();
        
                break;
            
        
    case 'digital_delete':

        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }
        
        $unique_id = $_POST['unique_id'];
        $firstPart = $_POST['firstPart'];

        if($firstPart=='MAD'){
            $table_1= 'moveables_digital_sub';
        }else{
            $table_1= 'moveable_kitchen_sub';
        }
        
        // Prepare an SQL statement
        $stmt = $mysqli->prepare("UPDATE $table_1 SET is_delete = ? WHERE unique_id = ?");

        if ($stmt) {
            $is_delete = 1;
            $stmt->bind_param("ss", $is_delete, $unique_id);

            // Execute the statement
            if ($stmt->execute()) {
                $status = true;
                $data = null;
                $error = "";
                $sql = ""; // For security reasons, not showing SQL
                $msg = "success_delete";
            } else {
                $status = false;
                $data = null;
                $error = $stmt->error;
                $sql = "";
                $msg = "error";
            }

            $stmt->close();
        } else {
            $status = false;
            $data = null;
            $error = $mysqli->error;
            $sql = "";
            $msg = "error";
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql" => $sql // Not returning SQL for security reasons
        ];

        echo json_encode($json_array);
        break;

    case 'type_of_equipment':

        $digital_category = $_POST['digital_category'];

        $type_of_equipment = $_POST['type_of_equipment'];

        $digital_category_option = type_of_equipment('',$digital_category);

        $digital_category_options = select_option($digital_category_option, 'Select equipment', $type_of_equipment);

        echo $digital_category_options;

        break;                       
    case 'get_zone_name':
        $district_name = $_POST['district'];
        $district_name_options = taluk_name_get('', $district_name);

        $district_name_options = select_option($district_name_options, 'Select Taulk');

        echo $district_name_options;

        break;

    case 'get_sup_address':
        $supplier_name = $_POST['supplier_name'];



        // Prepare SQL statement
        $sql = "SELECT building_no, street, area, pincode FROM supplier_name_creation WHERE unique_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $supplier_name);
        $stmt->execute();
        $stmt->bind_result($building_no, $street, $area, $pincode);

        $supplier_address = "";

        if ($stmt->fetch()) {
            // Concatenate address components
            $supplier_address = $building_no . ',' . $street . ',' . $area . ',' . $pincode;
        }

        // Close statement and connection
        $stmt->close();
        // $conn->close();

        // Output the supplier address
        echo $supplier_address;

        break;


    case 'get_hostel_name':
        $taluk_name = $_POST['taluk'];

        $taluk_name_options = hostel_name_get("", $taluk_name);

        $taluk_name_options = select_option($taluk_name_options, 'Select Hostel Name');
        echo $taluk_name_options;

        break;

    case 'get_category_name':

        $category = $_POST['category'];
        $category_options = category_type($category);

        $category_options = select_option_acc($category_options, 'Select Category');

        echo $category_options;

        break;

    case 'get_asset_name':

        $category = $_POST['category'];
        $asset = $_POST['asset'];
        $category_options = asset_type('',$category);

        $asset_options = select_option($category_options, 'Select Asset', $asset);

        echo $asset_options;

        break;
    

    case 'sub_delete':
        $unique_id = $_POST['id'];

        $overall_table = "moveables";
        $table_1 = "moveable_kitchen_sub";

        // Prepare the SQL statement
        $sql = "UPDATE $table_1 SET is_delete = 1 WHERE unique_id = ?";
        // $sql_1 = "UPDATE $overall_table SET is_delete = 1 WHERE screen_unique_id = ?";


        // Prepare the statement
        $stmt = $mysqli->prepare($sql);
        // $stmt_1 = $mysqli->prepare($sql_1);

        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("s", $unique_id);
            // $stmt_1->bind_param("s", $unique_id);


            // Execute the statement
            $stmt->execute();
            // echo $stmt;
            // $stmt_1->execute();


            // Check for successful execution
            if ($stmt->affected_rows > 0) {
                $status = true;
                $msg = "success_delete";
            } else {
                $error = "No rows affected.";
            }

            // Close statement
            $stmt->close();
        } else {
            $error = "Prepare statement failed: " . $mysqli->error;
        }

        // Close connection
        // $mysqli->close();

        $json_array = [
            "status" => $status ?? false,
            "data" => null,
            "error" => $error ?? "",
            "msg" => $msg ?? "error",
        ];

        echo json_encode($json_array);
        break;

    case 'delete':

        $unique_id = $_POST['unique_id'];

        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        // Prepare the SQL statement
        $sql = "UPDATE $table_main SET is_delete = 1 WHERE screen_unique_id = ?";
        $sql_1 = "UPDATE $table SET is_delete = 1 WHERE screen_unique_id = ?";
        $sql_2 = "UPDATE $overall_table SET is_delete = 1 WHERE screen_unique_id = ?";


        // Prepare the statement
        $stmt = $mysqli->prepare($sql);
        $stmt_1 = $mysqli->prepare($sql_1);
        $stmt_2 = $mysqli->prepare($sql_2);


        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("s", $unique_id);
            $stmt_1->bind_param("s", $unique_id);
            $stmt_2->bind_param("s", $unique_id);


            // Execute the statement
            $stmt->execute();
            $stmt_1->execute();
            $stmt_2->execute();


            // Check for successful execution
            if ($stmt->affected_rows > 0) {
                $status = true;
                $msg = "success_delete";
            } else {
                $error = "No rows affected.";
            }

            // Close statement
            $stmt->close();
        } else {
            $error = "Prepare statement failed: " . $mysqli->error;
        }

        // Close connection
        // $mysqli->close();

        $json_array = [
            "status" => $status ?? false,
            "data" => null,
            "error" => $error ?? "",
            "msg" => $msg ?? "error",
        ];

        echo json_encode($json_array);
        break;




    case 'updatevalues':
        // DataTable Variables
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;

        $data = [];

        $stock_id = $_POST['id'];
        if ($length == '-1') {
            $limit = "";
        }

        // Query Variables
        $json_array = "";

        $columns = [
            "@a:=@a+1 s_no",
            "item_name",
            "qty",
            "unit",
            "rate",
            "amount",
            "stock_id",
            "unique_id",
            "id",
        ];
        $table_details = [
            $table,
            $columns
        ];
        $where = "is_delete = 0 and id='$stock_id'";
        $order_by = "";

        $sql_function = "SQL_CALC_FOUND_ROWS";

        $result = $pdo->select($table_details, $where, $limit, $start, $order_by, $sql_function);
        $total_records = total_records();

        if ($result->status) {

            // $res_array      = $result->data;

            //             foreach ($res_array as $key => $value) {
            //                 // $value['feedback'] = disname($value['feedback']);
            //                 // $value['description'] = disname($value['description']);
            //                 // $value['is_active'] = is_active_show($value['is_active']);
            //                 $id = $value['id'];
            // $unique_id = $value['unique_id'];
            //                 $btn_update         ='<i class="uil uil-pen" onclick="get_records('.$id.')"></i>';
            //                 $btn_delete         = btn_delete($folder_name,$value['unique_id']);

            //                 if ( $value['unique_id'] == "5f97fc3257f2525529") {
            //                     $btn_update         = "";
            //                     $btn_delete         = "";
            //                 } 

            //                 $value['unique_id'] = $btn_update.$btn_delete;
            //                 // $data[]             = array_values($value);
            //                 $data            = array("id"=>$value['id'],"item_name"=>$value['item_name'],"qty"=>$value['qty'],"unit"=>$value['unit'],"rate"=>$value['rate'],"amount"=>$value['amount'],"stock_id"=>$value['stock_id']);
            //             }

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $result->data[0],
                // "testing" => $result->sql
            ];
        } else {
            // print_r($result);
        }

        echo json_encode($json_array);
        break;

    case 'stock_tot_qty_amt':
        // DataTable Variables
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;
        $screen_unique_id = $_POST['screen_unique_id'];

        $data = [];


        if ($length == '-1') {
            $limit = "";
        }

        // Query Variables
        $json_array = "";
        $columns = [
            "ifnull(sum(qty),0) as tot_qty",
            "ifnull(sum(amount),0) as tot_amount",
        ];
        $table_details = [
            "stock_entry_sub",
            $columns
        ];
        $where = "is_delete = 0 and screen_unique_id = '" . $screen_unique_id . "'";
        $order_by = "";

        $sql_function = "SQL_CALC_FOUND_ROWS";

        $result = $pdo->select($table_details, $where, $limit, $start, $order_by, $sql_function);

        $total_records = total_records();

        if ($result->status) {

            $res_array = $result->data;

            foreach ($res_array as $key => $value) {

                $tot_qty = $value['tot_qty'];
                $tot_amount = $value['tot_amount'];

                $data[] = array_values($value);
            }

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data,
                "tot_qty" => $tot_qty,
                "tot_amount" => $tot_amount,
                // "testing" => $result->sql
            ];
        } else {
            // print_r($result);
        }

        echo json_encode($json_array);
        break;

        case 'moveable_kitchen':
            $table = "moveable_kitchen_sub";
        
            // Assuming $mysqli is already initialized and connected
            $district_id = $_SESSION["district_id"];
            $taluk_id = $_SESSION['taluk_id'];
            $hostel_id = $_SESSION['hostel_id'];
        
            $category = sanitizeInput($_POST["category"]);
            $asset = sanitizeInput($_POST["asset"]);
            $big_small = sanitizeInput($_POST["big_small"]);
            $capacity = sanitizeInput($_POST["capacity"]);
            $unit = sanitizeInput($_POST["unit"]);
            $p_year = sanitizeInput($_POST["p_year"]);
            $is_active = sanitizeInput($_POST["is_active"]);
            $unique_id = sanitizeInput($_POST["unique_id"]);
            $screen_unique_id = sanitizeInput($_POST["screen_unique_id"]);
        
            if ($unique_id) {

                // echo $unique_id;
                // Update query
                $sql = "UPDATE $table SET category=?, asset=?, big_small=?, quantity=?, unit=?, procurement_year=? WHERE unique_id=? AND hostel_id=? AND taluk_id=? AND district_id=?";
                $stmt = $mysqli->prepare($sql);
                // print_r($sql);
                $stmt->bind_param("ssssssssss",  $category, $asset, $big_small, $capacity, $unit, $p_year, $unique_id, $hostel_id, $taluk_id, $district_id);
        
                $executionSuccess = $stmt->execute();
            } else {
                // Generate asset_id
                $prefix1 = 'MAK';
                $numericCode = extract_numbers($_SESSION['hostel_main_id']);
                $lastAssetIdQuery = "SELECT MAX(asset_id) AS last_asset_id FROM $table WHERE hostel_id=? AND taluk_id=? AND district_id=?";
                $stmt = $mysqli->prepare($lastAssetIdQuery);
                $stmt->bind_param("sss", $hostel_id, $taluk_id, $district_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
        
                if ($row['last_asset_id']) {
                    $lastNumber = (int)substr($row['last_asset_id'], -5) + 1;
                } else {
                    $lastNumber = 1;
                }
        
                $asset_id = generateAssetID($prefix1, $numericCode, $lastNumber);
        
                // Insert query
                $sql1 = "INSERT INTO $table (screen_unique_id, district_id, taluk_id, hostel_id, category, asset, big_small, quantity, unit, procurement_year, asset_id, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
               
                $stmt1 = $mysqli->prepare($sql1);
                $stmt1->bind_param("ssssssssssss", $screen_unique_id, $district_id, $taluk_id, $hostel_id, $category, $asset, $big_small, $capacity, $unit, $p_year, $asset_id, unique_id($prefix));
        
                $executionSuccess = $stmt1->execute();
            }
        
            if ($executionSuccess) {
                $msg = $unique_id ? "update" : "create";
                $status = "success";
                $data = [];
                $error = "";
            } else {
                $status = "error";
                $data = [];
                $error = "No rows affected";
                $msg = "error";
            }
        
            // Prepare JSON response
            $json_array = [
                "status" => $status,
                "data" => $data,
                "error" => $error,
                "msg" => $msg
            ];
        
            echo json_encode($json_array);
            break;

    case "get_unit_name":
        $asset = $_POST["asset"];

        $details = [
           "unit" => "",
           "capacity" => ""
        ];

        if ($asset) {
            $staff_where = [
                "unique_id" => $asset
            ];

            $staff_columns = [
                "unit",
                "capacity"
            ];

            $staff_table_details = [
                "kitchen_asset",
                $staff_columns
            ];

            $get_unit_name = $pdo->select($staff_table_details, $staff_where);

            if ($get_unit_name->status) {
                if (!empty($get_unit_name->data)) {
                    $details = $get_unit_name->data[0];
                    // $details = $get_unit_name->data[1];
                }
            } else {
                print_r($get_unit_name);
            }
        }

        echo json_encode($details);

        break;

    case "get_capacity_name":
        $asset = $_POST["asset"];

        $details = [
           "big_small" => ""
        ];

        if ($asset) {
            $staff_where = [
                "unique_id" => $asset
            ];

            $staff_columns = [
                "capacity"
            ];

            $staff_table_details = [
                "kitchen_asset",
                $staff_columns
            ];

            $get_capacity_name = $pdo->select($staff_table_details, $staff_where);

            if ($get_capacity_name->status) {
                if (!empty($get_capacity_name->data)) {
                    $details = $get_capacity_name->data[0];
                }
            } else {
                print_r($get_capacity_name);
            }
        }

        echo json_encode($details);

        break;

    default:

        break;
}


?>