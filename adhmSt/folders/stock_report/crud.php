<?php
// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "view_stock_in_outward_list";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';
include 'function.php';


// // Variables Declaration
$action = $_POST["action"];
// print_r($action);die();

$feedback_type = "";
$is_active = "";
$unique_id = "";
$prefix = "";

$data = "";
$msg = "";
$error = "";
$status = "";
$test = ""; // For Developer Testing Purpose

switch ($action) {

    case 'createupdate':
        $hostel_name = $_POST["hostel_name"];
        $taluk_name = $_POST["taluk_name"];
        $applied_date = $_POST["applied_date"];
        $disbursement_type = $_POST["disbursement_type"];
        $cur_month = $_POST["cur_month"];
        $connection_no = $_POST["connection_no"];
        $letter_no = $_POST["letter_no"];
        $letter_date = $_POST["letter_date"];
        $academic_year = $_POST["academic_year"];
        $unique_id = $_POST["unique_id"];
        $login_user_id = $_POST["login_user_id"];


        $allowedExts = array('pdf');

        $extension = pathinfo($_FILES["test_file"]['name'], PATHINFO_EXTENSION);
        $file_exp = explode(".", $_FILES["test_file"]['name']);

        $tem_name = random_strings(25) . "." . $file_exp[1];
        move_uploaded_file($_FILES["test_file"]["tmp_name"], '../../uploads/disbursement/' . $tem_name);
        $file_names = $tem_name;
        $file_org_names = $_FILES["test_file"]['name'];

        $update_where = "";

        $columns = [
            "hostel_name" => $hostel_name,
            "taluk_name" => $taluk_name,
            "applied_date" => $applied_date,
            "disbursement_type" => $disbursement_type,
            "academic_year" => $academic_year,
            "month" => $cur_month,
            "connection_no" => $connection_no,
            "letter_no" => $letter_no,
            "letter_date" => $letter_date,
            "warden_name" => $login_user_id,
            "disbursement_file" => $file_names,
            "disbursement_org_name" => $file_org_names,
            "unique_id" => unique_id($prefix)
        ];


        // print($unique_id);die();
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
        // }

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
            $search = $_POST['search']['value'];
            $length = $_POST['length'];
            $start = $_POST['start'];
            $draw = $_POST['draw'];
            $limit = $length;
        
            $data = [];
        
            $academic_year = $_POST["academic_year"];
            $district_name = $_POST["district_name"];
            $taluk_name = $_POST["taluk_name"];
            $hostel_name = $_POST["hostel_name"];
            $month_fill = $_POST["month_fill"];
        
            $formatted_date = date("F/Y", strtotime($month_fill));
            $date = new DateTime($month_fill);
            $date->modify('-1 month');
            $dec_date = $date->format('Y-m');
        
            // Create connection
           
        
            // Query Variables
            $columns = [
                "@a:=@a+1 s_no",
"hostel_unique_id",
                "item_name",
                "'-' as opening_stock",
                "'-' as in_qty",
                "'-' as out_qty",
                "'-' as closing_stock",
                
                "month_year",
                "(SELECT unit_measurement FROM unit_measurement WHERE unique_id = unit) AS unit" // Assuming unit is a valid column in $table
            ];
        
            $table_details = $table . " , (SELECT @a:= ?) AS a ";
            $where = "month_year = ?"; // Initialize the where clause
        
            // Prepare conditions for bind_param
            $bind_params = "ss"; // Types of parameters (s for string)
        
            // Initialize array for bind_param values
            $bind_values = [$start, $formatted_date];
        
            // Additional conditions
            if (!empty($district_name)) {
                $where .= " AND district_unique_id = ?";
                $bind_params .= "s"; // Add type for string parameter
                $bind_values[] = $district_name;
            }
            if (!empty($taluk_name)) {
                $where .= " AND taluk_unique_id = ?";
                $bind_params .= "s"; // Add type for string parameter
                $bind_values[] = $taluk_name;
            }
            if (!empty($hostel_name)) {
                $where .= " AND hostel_unique_id = ?";
                $bind_params .= "s"; // Add type for string parameter
                $bind_values[] = $hostel_name;
            }
        
            // SQL function and ordering
            $sql_function = "SQL_CALC_FOUND_ROWS";
            $group_by = "item_name";
            $order_by = "item_name ASC";
        
            // SQL query for data fetching
            $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_details WHERE $where";
            if (!empty($limit)) {
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
                $sno = 1;
        
                foreach ($res_array as $key => $value) {
                    $value['s_no'] = $sno;
        
                    $item_name = $value['item_name'];
                    $hostel_id = $value['hostel_unique_id'];
                    $current_date = $value['month_year'];
        	    $value['hostel_unique_id'] = hostel_name($hostel_id)[0]['hostel_name'];
                    $value['item_name'] = disname($value['item_name']) . ' (' . $value['unit'] . ')';
                    $value['opening_stock'] = opening_stock($item_name, $hostel_id, $dec_date);
        
                    $in_qty = get_in_qty($item_name, $hostel_id, $month_fill);
                    $out_qty = get_out_qty($item_name, $hostel_id, $month_fill);
        
                    $value['in_qty'] = ($in_qty == '') ? 0 : $in_qty;
                    $value['out_qty'] = ($out_qty == '') ? 0 : $out_qty;
                    $value['closing_stock'] = ($value['opening_stock'] + $value['in_qty']) - $value['out_qty'];
        
                    $value['is_active'] = is_active_show($value['is_active']);
        
                    $btn_update = btn_update($folder_name, $value['unique_id']);
                    $btn_delete = btn_delete($folder_name, $value['unique_id']);
                    $value['unique_id'] = $btn_update . $btn_delete;
        
                    $data[] = array_values($value);
                    $sno++;
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
                    "error" => $stmt->error
                ];
            }
        
            echo json_encode($json_array);
        
            // Close statement
            $stmt->close();
        
            // Close connection
        
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
            "sql" => $sql
        ];

        echo json_encode($json_array);
        $mysqli->close();

        break;

    default:

        break;
}



?>