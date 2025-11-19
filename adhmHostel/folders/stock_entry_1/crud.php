<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];




// // Database Country Table Name
$table = "stock_entry_sub";
$table_main = "stock_entry";
$overall_table = "stock_inward";

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
// $fileUploadConfig->set("upload_folder",$fileUploadPath. $folder_name . DIRECTORY_SEPARATOR);
switch ($action) {
    case 'datatable':
        // DataTable Variables
        $length = isset($_POST['length']) ? intval($_POST['length']) : 10; // Default limit
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;

        $data = [];

        if ($length == '-1') {
            $limit = "";
        } else {
            $limit = $length;
        }

        // Query Variables
        $columns = [
            "' ' as sno",
            "entry_date",
            "stock_id",
            "(SELECT supplier_name FROM supplier_name_creation WHERE unique_id = main.supplier_name) as supplier_name",
            "address",
	    "tot_amount",
            "bill_no",
	    "file_name",
            "(SELECT hostel_name FROM hostel_name WHERE unique_id = main.hostel_name) as hostel_name",
            "(SELECT district_name FROM district_name WHERE unique_id = main.district) as district",
            "(SELECT taluk_name FROM taluk_creation WHERE unique_id = main.taluk) as taluk",
            "main.unique_id",
	    "screen_unique_id",
        ];

        $table_main = "stock_entry"; // Replace with your actual main table name

        // Prepare WHERE clause and parameters
        $where = "main.is_delete = 0 AND main.hostel_name = ?";
        $params = [$_SESSION['hostel_id']];

        // Perform DataTable search if applicable
        $sql = "SELECT " . implode(", ", $columns) . " FROM $table_main AS main WHERE $where";

        // Prepare COUNT query for total records
        $count_sql = "SELECT COUNT(main.unique_id) AS total_records FROM $table_main AS main WHERE $where";


        // Prepare COUNT statement
        $stmt_count = $mysqli->prepare($count_sql);

        // Bind parameters for COUNT query
        $stmt_count->bind_param('s', $_SESSION['hostel_id']);

        // Execute COUNT query
        $stmt_count->execute();

        // Bind result variables
        $stmt_count->bind_result($total_records);

        // Fetch total records
        $stmt_count->fetch();

        // Close COUNT statement
        $stmt_count->close();

        // Adjust SQL for pagination
        $sql .= " LIMIT ?, ?";

        // Prepare main SELECT statement with pagination
        $stmt = $mysqli->prepare($sql);

        // Bind parameters for main SELECT statement
        $stmt->bind_param('sii', $_SESSION['hostel_id'], $start, $length);

        // Execute main SELECT statement
        $stmt->execute();

        // Get result set
        $result = $stmt->get_result();

        // Process fetched data
        $sno = $start + 1;
        while ($row = $result->fetch_assoc()) {
            $row['sno'] = $sno++;
            $unique_id = $row['unique_id'];
$screen_unique_id = $row['screen_unique_id'];
$row['file_name'] = image_view("adhmHostel", $row['unique_id'], $row['file_name']);

            $btn_update = btn_update($folder_name, $unique_id); // Assuming these are defined elsewhere
            $btn_delete = btn_delete($folder_name, $screen_unique_id); // Assuming these are defined elsewhere
            $row['unique_id'] = $btn_update . $btn_delete;
            $data[] = array_values($row);
        }

        // Prepare JSON response
        $json_array = [
            "draw" => intval($draw),
            "recordsTotal" => intval($total_records),
            "recordsFiltered" => intval($total_records), // Adjust if implementing actual filtering
            "data" => $data,
        ];

        // Close statement and connection
        $stmt->close();
        
        // Output JSON
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
    $discount = !empty($_POST["discount"]) ? $_POST["discount"] : null;
    $expense = !empty($_POST["expense"]) ? $_POST["expense"] : null;
    $gst = !empty($_POST["gst"]) ? $_POST["gst"] : null;
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
if($unique_id){
$msg = 'update';
}else{
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


    case 'stock_add_update':
      
        // Assuming $mysqli is already initialized and connected
        $stock_id = sanitizeInput($_POST["stock_id"]);
        $item_name = sanitizeInput($_POST["item_name"]);
        $qty = sanitizeInput($_POST["qty"]);
        $unit = sanitizeInput($_POST["unit"]);
        $rate = sanitizeInput($_POST["rate"]);
        $amount = sanitizeInput($_POST["amount"]);
        $is_active = sanitizeInput($_POST["is_active"]);
        $unique_id = sanitizeInput($_POST["unique_id"]);
        $screen_unique_id = sanitizeInput($_POST["screen_unique_id"]);
	$product_category = product_category($item_name)[0]['product_category'];

        // Convert qty to float and format to 2 decimal places
        $stock_qty = number_format(floatval($qty), 2, '.', '');

        // Prepare SQL statements for INSERT and UPDATE
        if ($unique_id) {
            // Update query
            $sql = "UPDATE $table SET category_name=?, item_name=?, qty=?, unit=?, rate=?, amount=?, stock_id=?, screen_unique_id=? WHERE unique_id=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("sssssssss", $product_category, $item_name, $stock_qty, $unit, $rate, $amount, $stock_id, $screen_unique_id, $unique_id);
        } else {
            // Insert query
            $sql = "INSERT INTO $table (category_name, item_name, qty, unit, rate, amount, stock_id, screen_unique_id, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $mysqli->prepare($sql);



            $stmt->bind_param("sssssssss", $product_category, $item_name, $stock_qty, $unit, $rate, $amount, $stock_id, $screen_unique_id, unique_id($prefix));
        }

        // Execute statement
        // $insert_update = $stmt->execute();

        // Check for errors
        // if ($insert_update === false) {
        //     $error = "Execute failed: " . $stmt->error;
        //     echo $error; // Output error for debugging
        // } else {
        // Check affected rows
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



    case 'stock_in_add_update':


        $stock_id = sanitizeInput($_POST["stock_id"]);
        $item_name = sanitizeInput($_POST["item_name"]);
        $qty = sanitizeInput($_POST["qty"]);
        $unit = sanitizeInput($_POST["unit"]);
        $rate = sanitizeInput($_POST["rate"]);
        $amount = sanitizeInput($_POST["amount"]);
        $is_active = sanitizeInput($_POST["is_active"]);
        $unique_id = sanitizeInput($_POST["unique_id"]);
        $screen_unique_id = sanitizeInput($_POST["screen_unique_id"]);
	$product_category = product_category($item_name)[0]['product_category'];

        $floatValue = floatval($qty);
        $stock_qty = number_format($floatValue, 2, '.', '');


        // Prepare SQL statements for INSERT and UPDATE
        if ($unique_id) {
            // Update query
            $sql = "UPDATE $overall_table SET category_name=?, item_name=?, qty=?, unit=?, rate=?, amount=?, stock_id=?, screen_unique_id=? WHERE unique_id=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("sssssssss", $product_category, $item_name, $stock_qty, $unit, $rate, $amount, $stock_id, $screen_unique_id, $unique_id);
        } else {
            // Insert query
            $sql = "INSERT INTO $overall_table (category_name, item_name, qty, unit, rate, amount, stock_id, screen_unique_id, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("sssssssss", $product_category, $item_name, $stock_qty, $unit, $rate, $amount, $stock_id, $screen_unique_id, unique_id($prefix));
        }

        // Execute statement
        // $stmt->execute();

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

        // Close statement and connection
        $stmt->close();


        // Prepare JSON response
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg
        ];

        echo json_encode($json_array);

        break;


    case 'stock_sub_datatable':

       

        // DataTable Variables
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $screen_unique_id = $_POST['screen_unique_id'];
        $folder_name = "stock_sub";
        $limit = $length == '-1' ? "" : $length;

        $data = [];

        // Query Variables
        $json_array = "";
        $columns = [
            "' ' as sno",
            "(select product_type from product_type where product_type.unique_id = stock_entry_sub.item_name) as item_name",
            "qty",
            "(select unit_measurement from unit_measurement where unique_id = stock_entry_sub.unit) as unit",
            "rate",
            "amount",
            "unique_id",
"screen_unique_id",
"item_name as item_id",
        ];
        $table = "stock_entry_sub";
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
                $value['item_name'] = disname($value['item_name']);
                $unique = $value['unique_id'];
                $btn_delete = btn_delete_stk($folder_name, $value['screen_unique_id'],$value['item_id']);

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

    case 'get_unit_name':

        $item_name = $_POST['item_name'];
        $item_name_options = product_type($item_name);

        $item_name_options = select_option_acc($item_name_options, 'Select Unit');

        echo $item_name_options;

        break;






    case 'sub_delete':
        $unique_id = $_POST['id'];
	$item_id = $_POST['item_id'];



        // Prepare the SQL statement
        $sql = "UPDATE $table SET is_delete = 1 WHERE screen_unique_id = ? and item_name = ?";
$sql_1 = "UPDATE $overall_table SET is_delete = 1 WHERE screen_unique_id = ? and item_name = ?";


        // Prepare the statement
        $stmt = $mysqli->prepare($sql);
$stmt_1 = $mysqli->prepare($sql_1);


        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("ss", $unique_id, $item_id);
$stmt_1->bind_param("ss", $unique_id, $item_id);


            // Execute the statement
            $stmt->execute();
$stmt_1->execute();


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







    default:

        break;
}

// $user_type          = $_POST["user_type"];
// $is_active          = $_POST["is_active"];
// $unique_id          = $_POST["unique_id"];

// $update_where       = "";

// //count user_type
// if($unique_id == ''){
//     $get_user_type=$pdo_conn->prepare("SELECT count(*) FROM `user_type` WHERE `user_type`= '".$user_type."' and `is_delete` != '1'");
// }else{
//     $get_user_type=$pdo_conn->prepare("SELECT count(*) FROM `user_type` WHERE `user_type`= '".$user_type."' and `is_delete` != '1' and `unique_id` != '".$unique_id."'" );
// }

// $get_user_type->execute();
// $user_type_count  = $get_user_type->fetchColumn();    

// if($user_type_count == 0){


//     if($unique_id == ''){//insert
//         $unique_id = uniqid().rand(10000,99999);

//         if($prefix) {
//             $unique_id = $prefix.$unique_id;
//         }

//         $Insql=$pdo_conn->prepare("insert into user_type (`unique_id`,`user_type`, `is_active`, `is_delete`)values('".$unique_id."','".$user_type."','1','0')");
//         $Insql->execute();
//         $msg = "Created";
//         echo $msg;
//     }else{//update
//         $Insql=$pdo_conn->prepare("UPDATE `user_type` SET `user_type`= '".$user_type."',`is_active`= '".$is_active."' WHERE unique_id = '".$unique_id."'");

//         $Insql->execute();
//         $msg  = "Updated";
//         echo $msg;
//     }
// }else{ 
//     $msg  = "already";
//     echo $msg;
// }
//     break;
//     case 'delete' :
//         $unique_id          = $_POST["unique_id"];
//         $del_sql=$pdo_conn->prepare("update user_type set is_delete='1' where unique_id ='".$unique_id."'" );
//         $del_sql->execute();
//             $msg  = "success_delete";
//             echo $msg;

//     break;
//     default:

//     break;
// }

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
                    $image_view .= '<a href="javascript:print_view(\'/' . $doc_file_name . '\')"><img src="uploads/stock_entry/' . $doc_file_name . '"  width="30px" ></a>';
                    // $image_view .= '<img src="uploads/'.$folder_name.'/'.$doc_name.'"  height="50px" width="50px" >';
                } else if ($cfile_name[1] == 'pdf') {
                    $image_view .= '<a href="javascript:print_pdf(\'/' . $doc_file_name . '\')"><img src="assets/images/pdf.png"   width="20%" style="margin-left: 15px;" ></a>';
                } else if (($cfile_name[1] == 'pdf') || ($cfile_name[1] == 'xls') || ($cfile_name[1] == 'xlsx')) {
                    $image_view .= '<a href="javascript:print(\'/' . $doc_file_name . '\')"><img src="assets/images/excel.png"  height="30px" width="30px" ></a>';
                } else if (($cfile_name[1] == 'txt') || ($cfile_name[1] == 'docx') || ($cfile_name[1] == 'doc')) {
                    $image_view .= '<a href="javascript:print(\'/' . $doc_file_name . '\')"><img src="assets/images/word.png"  height="30px" width="30px" ></a>';
                }
            }
        }
    }

    return $image_view;
}
?>