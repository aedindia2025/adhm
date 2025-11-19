<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "std_app_s";
$table_p2 = "std_app_p2";
$table_p3 = "std_app_p3";
$table_p4 = "std_app_p4";
$table_p5 = "std_app_p5";
$table_p6 = "std_app_p6";
$table_p7 = "std_app_p7";
$table_p8 = "std_app_p8";
$table_p9 = "std_app_p9";
$table_p10 = "std_app_p10";
$table_p11 = "std_app_p11";
$table_p12 = "std_app_p12";
$table_batch = "batch_creation";

$table2 = "print_for_dispatch";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';
// include 'function.php';

// // Variables Declaration
$action = $_POST['action'];

$hostel_type = "";
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

        $hostel_type = $_POST["hostel_type"];
        $is_active = $_POST["is_active"];
        $unique_id = $_POST["unique_id"];

        $update_where = "";

        $columns = [
            "hostel_type" => $hostel_type,
            "is_active" => $is_active,
            "unique_id" => unique_id($prefix)
        ];

        // check already Exist Or not
        $table_details = [
            $table,
            [
                "COUNT(unique_id) AS count"
            ]
        ];
        $select_where = 'hostel_type = "' . $hostel_type . '"  AND is_delete = 0  ';

        // When Update Check without current id
        if ($unique_id) {
            $select_where .= ' AND unique_id !="' . $unique_id . '" ';
        }

        $action_obj = $pdo->select($table_details, $select_where);

        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;

        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "error";
        }
        if ($data[0]["count"]) {
            $msg = "already";
        } else if ($data[0]["count"] == 0) {
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
        // DataTable Variables
        $length = isset($_POST['length']) ? intval($_POST['length']) : 10;
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
        $limit = $length;
        $data = [];
        $table = "bill_submission";

        if ($length == '-1') {
            $limit = "";
        }

if($_POST['district_name']){
    $where_fil .= " and hostel_district = '".$_POST['district_name']."'";
}
if($_POST['taluk_name']){
    $where_fil .= " and hostel_taluk = '".$_POST['taluk_name']."'";
}
if($_POST['hostel_name']){
    $where_fil .= " and hostel_name = '".$_POST['hostel_name']."'";
}


        // Query Variables
        $columns = [
            "'' as s_no",
            "entry_date",
            "(SELECT hostel_name FROM hostel_name WHERE hostel_name.unique_id = bill_submission.hostel_name) AS hostel_name",
            "bill_no",
            "SUM(amount) as amount",
            "COUNT(id) as bill_cnt",
            "'' as app_amt",
            "'' as rej_amt",
            "'' as app_bill_cnt",
            "'' as rej_bill_cnt",
            "rec_status",
            "bill_no as unique_id",
            "print_status",
        ];
        $table_details = $table;
        $where = "is_delete = ? AND bill_no IS NOT NULL $where_fil";
        $is_delete = "0";
        $bind_params = "i";
        $bind_values = [$is_delete];

        // Prepare SQL query
        $sql_function = "SQL_CALC_FOUND_ROWS";
        $sql_query = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_details WHERE $where GROUP BY bill_no";
        if (!empty($limit)) {
            $sql_query .= " LIMIT ?, ?";
            $bind_params .= "ii";
            $bind_values[] = $start;
            $bind_values[] = $limit;
        }

        // Prepare statement
        $stmt = $mysqli->prepare($sql_query);

        // Bind parameters dynamically
        if (!empty($bind_params)) {
            $stmt->bind_param($bind_params, ...$bind_values);
        }

        // Execute statement
        $stmt->execute();

        // Get result set
        $result = $stmt->get_result();

        // Process results
        if ($result) {
            $s_no = 0;
            while ($row = $result->fetch_assoc()) {
                $row['s_no'] = ++$s_no;
                $row['app_amt'] = app_amt($row['unique_id']) ?: '-';
                $row['rej_amt'] = rej_amt($row['unique_id']) ?: '-';
                $row['app_bill_cnt'] = app_bill_cnt($row['unique_id']);
                $row['rej_bill_cnt'] = rej_bill_cnt($row['unique_id']);
                // Example status handling
                $row['status'] = $row['status'] == "0" ? "Pending" : "Accepted";
                $row['unique_id'] = btn_print_approval("monthly_bill_submission", $row['unique_id'], "batch_print");

                if ($row['rec_status'] == 1) {
                    $row['rec_status'] = '<span style="color:green;">Received !!</span>';
                } else {
                    $row['rec_status'] = '<span style="color:Red;">Pending</span>';
                }


                $data[] = array_values($row);
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
        $mysqli->close();

        // Output JSON response
        echo json_encode($json_array);

        break;

    case 'rec_status':
        $bill_no = $_POST['batchNo'];

        // Create connection

        // Prepared statement
        $sql = "UPDATE bill_submission SET rec_status = 1, rec_time = ? WHERE bill_no = ?";

        // Prepare and bind
        if ($stmt = $mysqli->prepare($sql)) {
            $rec_time = date('Y-m-d H:i:s');
            $stmt->bind_param("ss", $rec_time, $bill_no);

            // Execute the update
            $stmt->execute();

            // Check if update was successful
            if ($stmt->affected_rows > 0) {
                $status = true;
                $data = "Updated all records with batch number: $bill_no";
                $error = "";
            } else {
                $status = false;
                $data = "";
                $error = "Failed to update records with batch number: $bill_no";
            }

            $stmt->close();
        } else {
            $status = false;
            $data = "";
            $error = "Prepare statement error: " . $mysqli->error;
        }

        // JSON response
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => ($status ? "Updated all records with batch number: $bill_no" : "Error updating records"),
            "sql" => $sql
        ];

        echo json_encode($json_array);

        // Close connection
        $mysqli->close();

        break;




    case 'get_student_details':
        // DataTable Variables


        $from_date = $_POST['from_date'];
        $to_date = $_POST['to_date'];


        // Query Variables
        $json_array = "";
        $columns = [
            // "@a:=@a+1 s_no",
            "std_name",
            // "",
            // "",
            // ""
        ];
        $table_details = [
            $table,
            $columns
        ];
        $where = "is_delete = 0 and entry_date >= '" . $from_date . "' and entry_date <= '" . $to_date . "'";

        // if (isset($_POST['academic_year'])) {
        //     if ($_POST['academic_year']) {
        //         $where .= " AND academic_year >= '" . $_POST['academic_year'] . "' ";
        //     }
        // }

        $order_by = "";

        $sql_function = "SQL_CALC_FOUND_ROWS";

        $result = $pdo->select($table_details, $where);
        $total_records = total_records();

        if ($result->status) {

            $res_array = $result->data;

            foreach ($res_array as $key => $value) {

                // $data[]             = array_values($value);
                $data .= "<h4>'" . $value["std_name"] . "'</h4>";
            }

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data,
                // "testing" => $result->sql
            ];
        } else {
            // print_r($result);
        }

        echo json_encode($json_array);
        break;







    case 'bill_dispatch_datatable':
        // print_r("hii");
        // DataTable Variables
        $table1 = 'stock_entry';
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;
        $data = [];
        $hostel_id1 = $_SESSION['hostel_id'];
        $apptype = $_POST["appilicationtype"];
        $hostel_name = $_POST["hostel_name"];
        $bill_no = $_POST["bill_no"];
        $hostel_taluk = $_POST["hostel_taluk"];
        $hostel_district = $_POST["hostel_district"];
        // $hostel_district     = $_POST['hostel_district'];

        if ($length == '-1') {
            $limit = "";
        }
        // Query Variables
        $json_array = "";
        $columns = [
            "unique_id",
            "'' as  s_no",
            "entry_date",
            "supplier_name",
            "net_total_amount",
            "file_name",
            "academic_year",



        ];
        $table_details = [
            $table1,
            $columns
        ];
        // $where = "is_delete = '0' AND ('" . $_SESSION['hostel_id'] . "' IN (hostel_1, hostel_2, hostel_3)) AND (batch_no IS NULL or batch_no = '')  ";




        $where = " is_delete = '0' and  hostel_name = '" . $_SESSION['hostel_id'] . "' AND (batch_bill_no IS NULL or batch_bill_no = '')";




        $order_column = $_POST["order"][0]["column"];
        $order_dir = $_POST["order"][0]["dir"];
        // Datatable Ordering 
        $order_by = datatable_sorting($order_column, $order_dir, $columns);

        $sql_function = "SQL_CALC_FOUND_ROWS";
        // $group_by = 'invoice_no';
        $result = $pdo->select($table_details, $where, $limit, $start, $order_by, $sql_function);
        // print_r($result);die();
        $total_records = total_records();
        if ($result->status) {
            $res_array = $result->data;
            foreach ($res_array as $key => $value) {
                $value['s_no'] = $sno + 1;
                // $value['std_name'] = strtoupper($value['std_name']);
                $supplier_name = $value['supplier_name'];
                $value['supplier_name'] = supplier_name_creation($value['supplier_name'])[0]['supplier_name'];
                $file_name = $value['file_name'];
                $value['file_name'] = image_view("adhmHostel", $value['unique_id'], $value['file_name']);



                $value['unique_id'] = '<input class="myCheck" type="checkbox" id="invoice_check" name="invoice_check[]">
                            <input type="hidden" id="unique_id" name="unique_id[]" value="' . $value['unique_id'] . '">  
                            <input type="hidden" id="batch_cr_date" name="batch_cr_date[]" value="' . date('Y-m-d') . '">  
                            <input type="hidden" id="hostel_name" name="hostel_name[]" value="' . $hostel_name . '">  
                            <input type="hidden" id="hostel_taluk" name="hostel_taluk[]" value="' . $hostel_taluk . '">  
                            <input type="hidden" id="batch_no" name="batch_no[]" value="' . $bill_no . '">  
                            <input type="hidden" id="hostel_district" name="hostel_district[]" value="' . $hostel_district . '">  
                            <input type="hidden" id="supplier_name" name="supplier_name[]" value="' . $supplier_name . '">
                            <input type="hidden" id="academic_year" name="academic_year[]" value="' . $value['academic_year'] . '">
                            <input type="hidden" id="net_total_amount" name="net_total_amount[]" value="' . $value['net_total_amount'] . '">
                            <input type="hidden" id="file_name" name="file_name[]" value="' . $file_name . '">';


                // $value['unique_id'] = $btn_update . $btn_delete;
                $data[] = array_values($value);
                $sno++;
            }
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data,
                // "testing" => $result->sql
            ];
        } else {
            // print_r($result);
        }
        echo json_encode($json_array);
        break;


    case 'batch_add':
        $unique_id = $_POST["unique_id"];
        $net_total_amount = $_POST["net_total_amount"];
        $hostel_name = $_POST["hostel_name"];
        $hostel_taluk = $_POST["hostel_taluk"];
        $hostel_district = $_POST["hostel_district"];
        $supplier_name = $_POST["supplier_name"];
        $file_name = $_POST["file_name"];
        $academic_year = $_POST["academic_year"];
        $batch_no = $_POST["batch_no"];


        $columns = [
            "st_unique_id" => $unique_id,
            "amount" => $net_total_amount,
            "hostel_name" => $hostel_name,
            "hostel_taluk" => $hostel_taluk,
            "hostel_district" => $hostel_district,
            "supplier_name" => $supplier_name,
            "file_name" => $file_name,
            "academic_year" => $academic_year,
            "bill_cr_date" => date('Y-m-d'),
            "entry_date" => date('Y-m-d'),

            "bill_no" => $batch_no,
            // "status"        => 0,
            "unique_id" => unique_id($prefix)
        ];


        $action_obj = $pdo->insert("bill_submission", $columns);

        $columns_update = [

            // "academic_year"           => $academic_year,
            "batch_bill_cr_date" => date('Y-m-d'),

            "batch_bill_no" => $batch_no,
            // "status"        => 0,
            // "unique_id"           => unique_id($prefix)
        ];

        $update_where = "is_delete = '0' and unique_id = '" . $unique_id . "'";

        $action_obj_update = $pdo->update("stock_entry", $columns_update, $update_where);

        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;
            // if ($unique_id) {
            //     $msg        = "update";
            // } else {
            $msg = "create";
            // }
        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "error";
        }
        // if ($action_obj_update->status) {
        //     $status     = $action_obj_update->status;
        //     $data       = $action_obj_update->data;
        //     $error      = "";
        //     $sql        = $action_obj_update->sql;
        //     // if ($unique_id) {
        //     //     $msg        = "update";
        //     // } else {
        //     $msg        = "create";
        //     // }
        // } else {
        //     $status     = $action_obj_update->status;
        //     $data       = $action_obj_update->data;
        //     $error      = $action_obj_update->error;
        //     $sql        = $action_obj_update->sql;
        //     $msg        = "error";
        // }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql" => $sql
        ];
        // $json_array_update   = [
        //     "status"    => $status,
        //     "data"      => $data,
        //     "error"     => $error,
        //     "msg"       => $msg,
        //     "sql"       => $sql
        // ];
        echo json_encode($json_array);
        // echo json_encode($json_array_update);
        break;



    case 'batch_detail_datatable':


        // DataTable Variables
        $table = "bill_submission";
        $length = isset($_POST['length']) ? intval($_POST['length']) : 10;
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
        $limit = $length;

        $data = [];
        $batch_no = $_POST['batch_no'];

        if ($length == '-1') {
            $limit = "";
        }

        // Query Variables
        $columns = [
            "@a:=@a+1 s_no",
            "bill_date",
            "(SELECT bill_no FROM stock_entry WHERE stock_entry.unique_id = bill_submission.st_unique_id) AS bill_no",
            "supplier_name",
            "amount",
            "file_name",
            "status",
            "st_unique_id",
            "(select screen_unique_id from stock_entry where stock_entry.unique_id = bill_submission.st_unique_id) as screen_unique_id"

        ];
        $table_with_counter = "{$table}, (SELECT @a := ?) AS a";
        $where = "is_delete = ? AND bill_no = ?";
        $is_delete = "0";
        $bind_params = "iss";
        $bind_values = [$start, $is_delete, $batch_no];

        // Prepare SQL query
        $sql_function = "SQL_CALC_FOUND_ROWS";
        $sql_query = "SELECT {$sql_function} " . implode(", ", $columns) . " FROM {$table_with_counter} WHERE {$where}";
        if (!empty($limit)) {
            $sql_query .= " LIMIT ?, ?";
            $bind_params .= "ii";
            $bind_values[] = $start;
            $bind_values[] = $limit;
        }

        // Prepare statement
        $stmt = $mysqli->prepare($sql_query);

        // Bind parameters dynamically
        if (!empty($bind_params)) {
            $stmt->bind_param($bind_params, ...$bind_values);
        }

        // Execute statement
        $stmt->execute();

        // Get result set
        $result = $stmt->get_result();

        // Process results
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $row['file_name'] = image_view("adhmHostel", $row['unique_id'], $row['file_name']);

                $unique_id = $row['screen_unique_id'];
                $row['screen_unique_id'] = '<a class="btn btn-action specl2-icon" href="javascript:category_print(\'' . $unique_id . '\')"><button type="button"><i class="fa fa-eye"></i></button></a>';


                switch ($row['status']) {
                    case 0:

                        $row['status'] = '<span style="color: blue;">Pending</span>';
                        break;
                    case 1:
                        $row['status'] = '<span style="color: green;">Accepted</span>';
                        break;
                    case 2:
                        $row['status'] = '<span style="color: red;">Rejected</span>';
                        break;
                    default:
                        break;
                }

                $unique_id = $row['st_unique_id'];
                $row['st_unique_id'] = '<a class="btn btn-action specl2-icon" href="javascript:batch_print(\'' . $unique_id . '\')"><button type="button"><i class="fa fa-eye"></i></button></a>';


                $data[] = array_values($row);
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
        $mysqli->close();

        // Output JSON response
        echo json_encode($json_array);

        break;



    case 'at_accept':
        $table1 = 'bill_submission';
        $table2 = 'stock_entry';
        $unique_id = sanitizeInput($_POST['uniqueId']);
        $batch_no = sanitizeInput($_POST['batchNo']);


        $columns_table1 = [
            "status" => 1,
            "approved_date" => date('Y-m-d H:i:s')
        ];

        $columns_table2 = [
            "status" => 1,
            "approved_date" => date('Y-m-d H:i:s')
        ];

        $update_where = [
            "st_unique_id" => $unique_id,
            "status" => 0,
            "bill_no" => $batch_no

        ];

        $update_where_table2 = [
            "unique_id" => $unique_id,
            // "status" => 0

        ];



        $action_obj_table1 = $pdo->update($table1, $columns_table1, $update_where);


        $action_obj_table2 = $pdo->update($table2, $columns_table2, $update_where_table2);



        if (!$action_obj_table2->status) {
            // If updating the second table fails, handle the error
            $status = $action_obj_table2->status;
            $data = $action_obj_table2->data;
            $error = $action_obj_table2->error;
            $sql = $action_obj_table2->sql;
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


    case 'at_reject':
        $table1 = 'bill_submission';
        $table2 = 'stock_entry';

        $unique_id = $_POST['uniqueId'];
        $batch_no = $_POST['batchNo'];
        $reason = $_POST['reason'];
        $hostel_name = $_POST['hostelId'];

        $columns = [
            "status" => 2,
            "reason" => $reason,
            "approved_date" => date('Y-m-d H:i:s')

        ];

        $columns_table2 = [
            "status" => 2,
            "reason" => $reason,
            "approved_date" => date('Y-m-d H:i:s')
        ];


        $update_where = [
            "st_unique_id" => $unique_id,
            "status" => 0,
            "bill_no" => $batch_no

        ];

        $update_where_table2 = [
            "unique_id" => $unique_id,
            // "status" => 0

        ];



        $action_obj = $pdo->update($table1, $columns, $update_where);
        $action_obj_table2 = $pdo->update($table2, $columns_table2, $update_where_table2);

        if (!$action_obj_table2->status) {
            // If updating the second table fails, handle the error
            $status = $action_obj_table2->status;
            $data = $action_obj_table2->data;
            $error = $action_obj_table2->error;
            $sql = $action_obj_table2->sql;
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



    case 'update_status':
        $batch_no = $_POST['unique_id'];
        $print_status = $_POST['print_status'];

        $table_name = "batch_creation";

        // Define the condition for updating records
        $where = [
            "batch_no" => $batch_no
        ];

        // Set the column to update based on print_status
        if ($print_status == 1) {
            $columns = [
                "print_status" => $print_status,
                "print_time" => date('Y-m-d H:i:s') // Update print_time with current date and time
            ];
        } elseif ($print_status == 2) {
            $columns = [
                "print_status" => $print_status,
                "submitted_time" => date('Y-m-d H:i:s') // Update submitted_time with current date and time
            ];
        } else {
            // Invalid print_status value, handle the error
            $json_array = [
                "status" => "error",
                "data" => null,
                "error" => "Invalid print_status value",
                "msg" => "Invalid print_status value provided",
                "sql" => ""
            ];
            echo json_encode($json_array);
            break;
        }

        // Perform the update
        $action_obj = $pdo->update($table_name, $columns, $where);

        // Check if the update operation was successful
        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;
            $msg = "Updated all records with batch number: $batch_no";
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


  case 'district_name':

        $district_name = $_POST['district_name'];


        $district_name_options = taluk_name('', $district_name);

        $taluk_name_options = select_option($district_name_options, "Select Taluk");

        echo $taluk_name_options;

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

function app_amt($unique_id = "")
{
    // echo $zone_name;

    global $pdo;

    $table_name = "bill_submission";
    $where = [];
    $table_columns = [
        "sum(amount) as amount",
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0,
        "status" => 1
    ];

    if ($unique_id) {
        // $where              = [];
        $where["bill_no"] .= $unique_id;
    }
    // if ($unique_id) {
    //     $where              = [];
    //     $where["unique_id"] = $unique_id;
    // }

    $amc_name_list = $pdo->select($table_details, $where);

    // print_r( $amc_name_list);

    if ($amc_name_list->status) {
        return $amc_name_list->data[0]['amount'];
    } else {
        print_r($amc_name_list);
        return 0;
    }
}

function rej_amt($unique_id = "")
{
    // echo $zone_name;

    global $pdo;

    $table_name = "bill_submission";
    $where = [];
    $table_columns = [
        "sum(amount) as amount",
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0,
        // "batch_no"  => $unique_id,
        "status" => 2
    ];

    if ($unique_id) {
        // $where              = [];
        $where["bill_no"] .= $unique_id;
    }
    // if ($unique_id) {
    //     $where              = [];
    //     $where["unique_id"] = $unique_id;
    // }

    $amc_name_list = $pdo->select($table_details, $where);

    // print_r( $amc_name_list);

    if ($amc_name_list->status) {
        return $amc_name_list->data[0]['amount'];
    } else {
        print_r($amc_name_list);
        return 0;
    }



}

function app_bill_cnt($unique_id = "")
{
    // echo $zone_name;

    global $pdo;

    $table_name = "bill_submission";
    $where = [];
    $table_columns = [
        "count(id) as tot_cnt",
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0,
        // "batch_no"  => $unique_id,
        "status" => 1
    ];

    if ($unique_id) {
        // $where              = [];
        $where["bill_no"] .= $unique_id;
    }
    // if ($unique_id) {
    //     $where              = [];
    //     $where["unique_id"] = $unique_id;
    // }

    $amc_name_list = $pdo->select($table_details, $where);

    // print_r( $amc_name_list);

    if ($amc_name_list->status) {
        return $amc_name_list->data[0]['tot_cnt'];
    } else {
        print_r($amc_name_list);
        return 0;
    }
}

function rej_bill_cnt($unique_id = "")
{
    // echo $zone_name;

    global $pdo;

    $table_name = "bill_submission";
    $where = [];
    $table_columns = [
        "count(id) as tot_cnt",
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0,
        // "batch_no"  => $unique_id,
        "status" => 2
    ];

    if ($unique_id) {
        // $where              = [];
        $where["bill_no"] .= $unique_id;
    }
    // if ($unique_id) {
    //     $where              = [];
    //     $where["unique_id"] = $unique_id;
    // }

    $amc_name_list = $pdo->select($table_details, $where);

    // print_r( $amc_name_list);

    if ($amc_name_list->status) {
        return $amc_name_list->data[0]['tot_cnt'];
    } else {
        print_r($amc_name_list);
        return 0;
    }
}

// function batch_no($academic_year = "")
// {
//     // $date = date("Y");
//     // $st_date = substr($date, 4);

//     $servername = "localhost";
//     $username = "root";
//     $password = "4/rb5sO2s3TpL4gu";
//     $database_name = "adi_dravidar";

//     try {
//         $conn = new PDO("mysql:host=$servername;dbname=adi_dravidar", $username, $password);
//         // set the PDO error mode to exception
//         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//         //echo "Connected successfully";
//     } catch (PDOException $e) {
//         // echo "Connection failed: " . $e->getMessage();
//     }

//     $acmc_year = academic_year($academic_year)[0]['acc_year'];
//     $a = str_split($acmc_year);
//     $splt_acc_yr = $a[0] . $a[1] . $a[2] . $a[3];
//     //  $hostel_id = '65584660e85as2403119'; 
//     //  $hos_id = substr($hostel_id,);




//     // echo "SELECT * FROM apply_application_form where application_no LIKE 'APN%' order by id desc";echo "<br>";
//     $stmt = $conn->query("SELECT max(batch_no) as batch_no FROM batch_creation where is_delete = '0' and hostel_name = '" . $hostel_id . "' order by id desc");


//     // if($res1=$stmt->fetch($stmt))
//     if ($res1 = $stmt->fetch()) {
//         if ($res1['batch_no'] != '') {


//             $pur_array = explode("-", $res1['batch_no']);


//             //  echo $pur_array[1];

//             $booking_no = $pur_array[1];
//         }
//         // else{
//         //     $booking_no  = '';
//         // }

//     }
//     //  $booking_nos = 'APN-' . $splt_acc_yr .'-' . ''. $splt_dis .'-' .'' .$splt_zone.'-' .'' .$splt_host.'-' .'-0001';
//     if ($booking_no == '') {
//         // echo "ff";
//         $booking_nos = $splt_acc_yr . 'BAT-' . '0001';
//     }
//     // else if ($year != date("Y")){
//     //     $booking_nos = 'APN-'.date('Y'). $splt_acc_yr . ''.$splt_dis.'' .$splt_zone.'' .$splt_host.'-0001';
//     // }
//     else {
//         $booking_no += 1;

//         $booking_nos = $splt_acc_yr . 'BAT-' . str_pad($booking_no, 4, '0', STR_PAD_LEFT);
//     }

//     return $booking_nos;
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
                    $image_view .= '<a href="javascript:print_view(\'/' . $doc_file_name . '\')"><img src="../adhmHostel/uploads/stock_entry/' . $doc_file_name . '"  width="50px" ></a>';
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




