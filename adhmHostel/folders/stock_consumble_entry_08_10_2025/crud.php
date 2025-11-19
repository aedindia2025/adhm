<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];



// // Database Country Table Name
$table = "stock_consumble_entry_sub";
$table_main = "stock_consumble_entry";
$overall_table = "stock_outward";

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

function validateCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

switch ($action) {
    case 'main_createupdate':

            $token = $_POST['csrf_token'];

            if (!validateCSRFToken($token)) {
                die('CSRF validation failed.');
            }

        $stock_id = $_POST["stock_id"];
        $hostel_name = $_POST["hostel_name"];
        $district = $_POST["district"];
        $taluk = $_POST["taluk"];
        $screen_unique_id = $_POST["screen_unique_id"];
        $unique_id = $_POST["unique_id"] ?? null;
        $is_active = $_POST["is_active"];

        if (empty($_POST["entry_date"])) {
            $entry_date = date('Y-m-d');
        } else {
            $entry_date = $_POST["entry_date"];
        }

        // Check if record already exists
        $select_where = 'stock_id = ? AND is_delete = 0';
        $params = [$stock_id];
        $types = "s";

        // When updating, exclude the current id
        if ($unique_id) {
            $select_where .= ' AND unique_id != ?';
            $params[] = $unique_id;
            $types .= "s";
        }

        $sql = "SELECT COUNT(unique_id) AS count FROM $table_main WHERE $select_where";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();

        if ($data["count"] > 0) {
            $msg = "already";
            $status = false;
            $error = "";
            $sql = "";
        } else {
            // Insert or Update
            $status = true;
            $error = "";

            if ($unique_id) {
                $sql = "UPDATE $table_main SET entry_date = ?, hostel_name = ?, stock_id = ?, district = ?, taluk = ?, screen_unique_id = ? WHERE unique_id = ?";
                $params = [$entry_date, $hostel_name, $stock_id, $district, $taluk, $screen_unique_id, $unique_id];
                $types = "sssssss";
            } else {
                $generated_unique_id = unique_id($prefix);
                $sql = "INSERT INTO $table_main (entry_date, hostel_name, stock_id, district, taluk, screen_unique_id, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $params = [$entry_date, $hostel_name, $stock_id, $district, $taluk, $screen_unique_id, $generated_unique_id];
                $types = "sssssss";
            }

            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param($types, ...$params);
            // $stmt->execute();

            if ($stmt->execute()) {
                $msg = $unique_id ? "update" : "create";
            } else {
                $status = false;
                $msg = "error";
                $error = $stmt->error;
            }
            $stmt->close();
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            "sql" => $sql
        ];

        echo json_encode($json_array);
        break;



    case 'datatable':


        // DataTable Variables
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length == '-1' ? "" : $length;

        $data = [];

        // Query Variables
        $json_array = "";
        $columns = [
            "' ' as sno",
            "entry_date",
            "stock_id",
            "(select hostel_name from hostel_name where unique_id = $table_main.hostel_name) as hostel_name",
            "(select district_name from district_name where unique_id = $table_main.district) as district",
            "(select taluk_name from taluk_creation where unique_id = $table_main.taluk) as taluk",
            "unique_id",
"screen_unique_id",
        ];
        $table_details = $table_main . " , (SELECT @a:= ?) AS a ";
        $where = "is_delete = 0 and hostel_name = ?";
        $order_by = ""; // You can modify this to add an order by clause if needed

        $sql_function = "SQL_CALC_FOUND_ROWS";

        // SQL query for data fetching
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_details WHERE $where";
        if ($limit) {
            $sql .= " LIMIT ?, ?";
        }

        $stmt = $mysqli->prepare($sql);
        if ($limit) {
            $stmt->bind_param("isii", $start, $_SESSION['hostel_id'], $start, $limit);
        } else {
            $stmt->bind_param("is", $start, $_SESSION['hostel_id']);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch total records
        $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
        $total_records = $total_records_result->fetch_assoc()['total'];

        if ($result) {
            $res_array = $result->fetch_all(MYSQLI_ASSOC);
            $sno = $start + 1;
            foreach ($res_array as $key => $value) {
                $value['sno'] = $sno++;
                $value['entry_date'] = disdate($value['entry_date']);
                $unique = $value['unique_id'];
                $btn_update = btn_update($folder_name, $value['unique_id']);
                $btn_delete = btn_delete($folder_name, $value['screen_unique_id']);

                $value['unique_id'] = $btn_update . $btn_delete;
                $data[] = array_values($value);
            }

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data,
                "testing" => $stmt->sqlstate
            ];
        } else {
            print_r($result);
        }

        echo json_encode($json_array);

        // Close connection
        $stmt->close();
        $mysqli->close();

        break;



    case 'overall_createupdate':

        

        $stock_id = $_POST["stock_id"];
        $bill_no = $_POST["bill_no"];
        $hostel_name = $_POST["hostel_name"];
        $district = $_POST["district"];
        $taluk = $_POST["taluk"];
        $academic_year = $_SESSION['academic_year'];

        if ($_POST["entry_date"] == '') {
            $entry_date = date('Y-m-d');
        } else {
            $entry_date = $_POST["entry_date"];
        }

        $update_where = "";


        unset($columns['stock_id']);

        $update_where = [
            "stock_id" => $stock_id
        ];

        // Create a connection to the database



        // Prepare the SQL statement with placeholders
        $stmt = $mysqli->prepare("UPDATE $overall_table SET entry_date = ?, hostel_unique_id = ?, academic_year = ?, district_unique_id = ?, taluk_unique_id = ? WHERE stock_id = ?");

        // Bind the parameters to the placeholders
        $stmt->bind_param("ssssss", $entry_date, $hostel_name, $academic_year, $district, $taluk, $stock_id);

        // Execute the statement
        if ($stmt->execute()) {
            $status = true;
            $data = "Record updated successfully";
            $error = "";
            $sql = $stmt->sqlstate; // Not accurate, just a placeholder for the actual executed SQL
            $msg = $_POST["unique_id"] ? "update" : "create";
        } else {
            $status = false;
            $data = null;
            $error = $stmt->error;
            $sql = $stmt->sqlstate; // Not accurate, just a placeholder for the actual executed SQL
            $msg = "error";
        }

        // Close the statement and the connection
        $stmt->close();


        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql" => $sql
        ];

        echo json_encode($json_array);

        break;


    case 'createupdate_overall':

        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        $stock_id = $_POST["stock_id"];
        $item_name = $_POST["item_name"];
        $qty = $_POST["qty"];
        $act_qty = $_POST["act_qty"];
        $is_active = $_POST["is_active"];
        $unique_id = $_POST["unique_id"];
        $purchase_item = $_POST["purchase_item"];

        $screen_unique_id = $_POST["screen_unique_id"];
        $floatValue = floatval($qty);
        $consume_qty = number_format($floatValue, 2, '.', '');

        // Check if record already exists
        $select_where = 'item_name = ? AND stock_id = ? AND is_delete = 0';
        $params = [$item_name, $stock_id];
        $types = "ss";

        // When updating, exclude the current id
        if ($unique_id) {
            $select_where .= ' AND unique_id != ?';
            $params[] = $unique_id;
            $types .= "s";
        }

        $sql = "SELECT COUNT(unique_id) AS count FROM $overall_table WHERE $select_where";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();


        $status = true;
       // if ($unique_id) {
         //   $sql = "UPDATE $overall_table SET purchase_item = ?, item_name = ?, qty = ?, screen_unique_id = ?, act_qty = ?, stock_id = ? WHERE unique_id = ?";
           // $params = [$purchase_item, $item_name, $consume_qty, $screen_unique_id, $act_qty, $stock_id, $unique_id];
           // $types = "sssssss";
        //} else {
            // Generate unique id (assuming unique_id($prefix) is a function to generate it)
            $generated_unique_id = unique_id($prefix);

            $sql = "INSERT INTO $overall_table (purchase_item, item_name, qty, screen_unique_id, act_qty, stock_id, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $params = [$purchase_item, $item_name, $consume_qty, $screen_unique_id, $act_qty, $stock_id, $generated_unique_id];
            $types = "sssssss";
        //}

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $stmt->close();

        $msg = $unique_id ? "update" : "create";


        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => "",
            "msg" => $msg,
            // "sql" => $sql
        ];

        echo json_encode($json_array);
        break;



    case 'createupdate':

        $stock_id = $_POST["stock_id"];
        $item_name = $_POST["item_name"];
        $actual_qty = $_POST["act_qty"];
        $purchase_item = $_POST["purchase_item"];

        $qty = $_POST["qty"];
        $is_active = $_POST["is_active"];
        $screen_unique_id = $_POST["screen_unique_id"];
        $unique_id = $_POST["unique_id"] ?? null;

        $floatValue1 = floatval($actual_qty);
        $act_qty = number_format($floatValue1, 2, '.', '');

        $floatValue = floatval($qty);
        $consume_qty = number_format($floatValue, 2, '.', '');

        // Check if record already exists
        $select_where = 'item_name = ? AND stock_id = ? AND is_delete = 0';
        $params = [$item_name, $stock_id];
        $types = "ss";

        // When updating, exclude the current id
        if ($unique_id) {
            $select_where .= ' AND unique_id != ?';
            $params[] = $unique_id;
            $types .= "s"; 
        }

        $sql = "SELECT COUNT(unique_id) AS count FROM $table WHERE $select_where";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();


        // if ($data["count"] > 0) {
        //     $msg = "already";
        //     $status = false;
        //     $error = "";
        //     $sql = "";
        // } else {
        // Insert or Update
        $status = true;
        $error = "";


        $generated_unique_id = unique_id($prefix);
        $sql = "INSERT INTO $table (purchase_item, item_name, actual_qty, qty, stock_id, screen_unique_id,  unique_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $params = [$purchase_item, $item_name, $act_qty, $consume_qty, $stock_id, $screen_unique_id, $generated_unique_id];
        $types = "sssssss";


        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param($types, ...$params);
        // $stmt->execute();

        if ($stmt->execute()) {
            $msg = $unique_id ? "update" : "create";
        } else {
            $status = false;
            $msg = "error";
            $error = $stmt->error;
        }
        $stmt->close();


        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql" => $sql
        ];

        echo json_encode($json_array);
        break;



    case 'stock_out_sub_datatable':


        // DataTable Variables
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length == '-1' ? "" : $length;
        $screen_unique_id = $_POST['screen_unique_id'];

        $data = [];

        $fold_name = "stock_out_sub";

        // Query Variables
        $json_array = "";
        $columns = [
            "' ' as sno",
            "(select product_type from product_type where unique_id = $table.item_name) as item_name",
            "actual_qty",
            "qty",
            "unique_id",
            "id",	
	    "screen_unique_id",
	    "item_name as item_id"
        ];

        $table_details = $table . " , (SELECT @a:= ?) AS a ";
        $where = "is_delete = 0 and screen_unique_id = ?";

        // SQL query for data fetching
        $sql = "SELECT " . implode(", ", $columns) . " FROM $table_details WHERE $where";
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
            $sno = $start + 1;
            foreach ($res_array as $key => $value) {
                $value['sno'] = $sno++;
                $value['item_name'] = disname($value['item_name']);
                $value['entry_date'] = disdate($value['entry_date']);
                $unique = $value['unique_id'];
                $btn_delete = btn_delete_stk($fold_name, $value['screen_unique_id'], $value['item_id']);

                $value['unique_id'] = $btn_update . $btn_delete;
                $data[] = array_values($value);
            }

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data,
                "testing" => $stmt->sqlstate
            ];
        } else {
            print_r($result);
        }

        echo json_encode($json_array);

        // Close connection
        $stmt->close();
        $mysqli->close();

        break;

case 'get_stock_val':
            $screen_unique_id = $_POST['screen_unique_id'];

            $stock_in_val = stock_in_val($screen_unique_id);

            // print_r($stock_in_val);
            
            $ex_stock_val = product_type_name("",$stock_in_val);
        //    print_r($ex_stock_val);
            $ex_stock_val_options = select_option($ex_stock_val, 'Select Item Name');
    
            echo $ex_stock_val_options;
    
            break;

    case 'get_zone_name':
        $district_name = $_POST['district'];
        $district_name_options = taluk_name_get('', $district_name);

        $district_name_options = select_option($district_name_options, 'Select Taulk');

        echo $district_name_options;

        break;

    case 'get_hostel_name':
        $taluk_name = $_POST['taluk'];
        $taluk_name_options = hostel_name('', $taluk_name);

        $taluk_name_options = select_option($taluk_name_options, 'Select Hostel Name ');

        echo $taluk_name_options;

        break;

    case 'get_unit_name':

        $item_name = $_POST['item_name'];
        
        $hostel_id = $_POST['hostel_id'];

        $item_id = product_type_name($_POST['item_name'])[0]['product_type'];

        $where = "item_name = '" . $item_name . "' AND hostel_unique_id = '$hostel_id' and is_delete = 0";

        $table_entry_sub = "stock_inward";

        $columns = [
            "(select sum(in_qty) from view_stock_inward where item_name = '" . $item_name . "' AND hostel_unique_id = '$hostel_id' ) as in_qty",
            "(select sum(out_qty) from view_stock_outward where item_name = '" . $item_name . "' AND hostel_unique_id = '$hostel_id') as out_qty",
        ];

        $table_details = [
            $table_entry_sub,
            $columns
        ];

        $result_values = $pdo->select($table_details, $where);

        // print_r($result_values);die();

        if ($result_values->status) {

            $result_values = $result_values->data;


            $item_in_qty = $result_values[0]["in_qty"];
            $item_out_qty = $result_values[0]["out_qty"];
            $rem_qty = $item_in_qty - $item_out_qty;

        }

        echo $rem_qty;

        break;

    case 'get_item_price':
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;

        $data = [];


        if ($length == '-1') {
            $limit = "";
        }

        $item_name = $_POST['item_name'];
        $where = "item_name = '$item_name'";
        $table_entry_sub = "stock_entry_sub";

        $columns = [
            "rate",
            "qty"
        ];

        $table_details = [
            $table_entry_sub,
            $columns
        ];

        $order_by = "id desc";

        $sql_function = "SQL_CALC_FOUND_ROWS";

        $result_values = $pdo->select($table_details, $where, $limit, $start, $order_by, $sql_function);

        if ($result_values->status) {

            $result_values = $result_values->data;


            $item_rate_per_unit = $result_values[0]["rate"];
            print_r($item_rate_per_unit);
            die();

        }

        break;

    case 'document_upload_sub_datatable':
        // DataTable Variables
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;

        $data = [];

        $stock_id = $_POST['stock_id'];

        if ($length == '-1') {
            $limit = "";
        }
        // Query Variables
        $json_array = "";

        $columns = [
            "@a:=@a+1 s_no",
            "item_name",
            "actual_qty",
            "qty",
            "unique_id",
            "id"

        ];
        $table_details = [
            $table,
            $columns
        ];
        $where = "is_delete = 0 and stock_id='$stock_id'";

        $result = $pdo->select($table_details, $where);
        $total_records = total_records();
        if ($result->status) {

            $res_array = $result->data;

            foreach ($res_array as $key => $value) {
                // $value['feedback'] = disname($value['feedback']);
                // $value['description'] = disname($value['description']);
                // $value['is_active'] = is_active_show($value['is_active']);
                $id = $value['id'];
                $unique_id = $value['unique_id'];
                $tot_qty += $value['qty'];
                // $tot_amount += $value['amount'];
                // $btn_update = '<i class="uil uil-pen" onclick="get_records(' . $id . ')"></i>';
                $btn_delete = '<i class="uil uil-trash" onclick="get_delete(' . $id . ')"></i>';

                if ($value['unique_id'] == "5f97fc3257f2525529") {
                    $btn_update = "";
                    $btn_delete = "";
                }

                $value['unique_id'] = $btn_delete;
                // $value['tot_qty'] = '';
                // $data[]             = array_values($value);

            }

            $table_data .= '<tr>
                            <td></td>
                            <td></td>
                            <td>Qty : <br>"' . $tot_qty . '"</td>
                            <td></td>
                            <td></td>
                            <td></td>
           
                            <tr>';
            // $data[] .= 

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                // "data" => $table_data,
                // "testing" => $result->sql,
                // "tot_qty" => $tot_qty,
                // "tot_amount" => $tot_amount,
            ];
        } else {
            print_r($result);
        }

        echo json_encode($json_array);
        break;

    case 'sub_delete':

       $unique_id = $_POST['unique_id'];
        $item_name = $_POST['item_name'];

        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        //   echo $unique_id;die();
        // Prepare the SQL statement with placeholders
        $stmt = $mysqli->prepare("UPDATE $table SET is_delete = ? WHERE screen_unique_id = ? and item_name = ?");
        $stmt_1 = $mysqli->prepare("UPDATE $overall_table SET is_delete = ? WHERE screen_unique_id = ? and item_name = ?");

        // Bind the parameters to the placeholders
        $is_delete = 1;
        $stmt->bind_param("iss", $is_delete, $unique_id, $item_name);
        $stmt_1->bind_param("iss", $is_delete, $unique_id, $item_name);

        // Execute the statement
        if ($stmt->execute()) {
            $stmt_1->execute();
            $status = true;
            $data = "Record updated successfully";
            $error = "";
            $sql = $stmt->sqlstate; // Not accurate, just a placeholder for the actual executed SQL
            $msg = "success_delete";
        } else {
            $status = false;
            $data = null;
            $error = $stmt->error;
            $sql = $stmt->sqlstate; // Not accurate, just a placeholder for the actual executed SQL
            $msg = "error";
        }

        // Close the statement and the connection
        $stmt->close();
        // $mysqli->close();

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            "sql" => $sql
        ];

        echo json_encode($json_array);
        break;



    case 'delete':
        $unique_id = $_POST['unique_id'];

        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        // Prepare an SQL statement
        $stmt = $mysqli->prepare("UPDATE $table_main SET is_delete = ? WHERE screen_unique_id = ?");
        $stmt_1 = $mysqli->prepare("UPDATE $table SET is_delete = ? WHERE screen_unique_id = ?");
        $stmt_2 = $mysqli->prepare("UPDATE $overall_table SET is_delete = ? WHERE screen_unique_id = ?");

        if ($stmt) {
            $is_delete = 1;
            $stmt->bind_param("is", $is_delete, $unique_id);
            $stmt_1->bind_param("is", $is_delete, $unique_id);
            $stmt_2->bind_param("is", $is_delete, $unique_id);

            // Execute the statement
            if ($stmt->execute()) {
                $stmt_1->execute();
                $stmt_2->execute();
                $status = true;
                $data = null;
                $error = "";
                $sql = $stmt->get_result();
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
            "sql" => $sql
        ];

        echo json_encode($json_array);
        break;

        case 'product_supply':

            $purchase_item = $_POST['purchase_item'];
            $screen_unique_id = $_POST['screen_unique_id'];
            $stock_in_val = stock_in_val($screen_unique_id);
            // echo $purchase_item;
            $product_type_option = product_type_names('',$purchase_item,$stock_in_val);
    
            $product_type_options = select_option($product_type_option, 'Select product type');
    
            echo $product_type_options;
    
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
                "testing" => $result->sql
            ];
        } else {
            print_r($result);
        }

        echo json_encode($json_array);
        break;



    default:

        break;
}


function stock_in_val($screen_unique_id = '')
{
    global $pdo;

    $table_name = 'stock_consumble_entry_sub';
    $where = '';
    $table_columns = [
        "GROUP_CONCAT(CONCAT('\"', item_name, '\"') SEPARATOR ', ') AS item_name",
    ];

    $table_details = [
        $table_name,
        $table_columns,
    ];

    // $where = [
    //     "is_active" => 1,
    //     "is_delete" => 0
    // ];

    $where = 'is_delete = 0 and screen_unique_id = "'.$screen_unique_id.'" ';

    $amc_name_list = $pdo->select($table_details, $where);
// print_r($amc_name_list);
    if ($amc_name_list->status) {
        return $amc_name_list->data[0]['item_name'];
    } else {
        print_r($amc_name_list);

        return 0;
    }
}
