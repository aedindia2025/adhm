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

        function selectWithParams($mysqli, $table, $select_where, &$status, &$data, &$error)
        {
            $sql = "SELECT COUNT(unique_id) AS count FROM $table WHERE $select_where";
            $stmt = $mysqli->prepare($sql);
            if (!$stmt) {
                $error = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                return false;
            }

            $stmt->execute();
            $result = $stmt->get_result();
            if (!$result) {
                $error = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                return false;
            }

            $data = $result->fetch_assoc();
            $status = true;
            $stmt->close();
            return true;
        }

        // Prepare SQL statement and bind parameters for insert/update
        $sql = "";
        $params = [];
        $bind_types = "";

        if (!empty($unique_id)) {
            // Update existing record
            $sql = "UPDATE $table SET hostel_type=?, is_active=? WHERE unique_id=?";
            $bind_types = "ssi";
            $params = [$hostel_type, $is_active, $unique_id];
        } else {
            // Insert new record
            $new_unique_id = unique_id($prefix); // Assuming unique_id() generates a new unique ID
            $sql = "INSERT INTO $table (hostel_type, is_active, unique_id) VALUES (?, ?, ?)";
            $bind_types = "ssi";
            $params = [$hostel_type, $is_active, $new_unique_id];
        }

        // Prepare statement for insert/update
        $stmt = $mysqli->prepare($sql);
        if (!$stmt) {
            $status = false;
            $error = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        } else {
            // Bind parameters to statement
            $stmt->bind_param($bind_types, ...$params);

            // Execute the statement
            if ($stmt->execute()) {
                // Determine operation type
                if (!empty($unique_id)) {
                    $msg = "update";
                } else {
                    $msg = "create";
                }
                $status = true;
                $data = []; // Additional data if needed
                $error = "";
            } else {
                // Error handling
                $status = false;
                $data = [];
                $error = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            }

            // Close statement
            $stmt->close();
        }

        // Select existing record count to determine if record already exists
        $select_where = 'hostel_type = ? AND is_delete = 0';
        $params_select = [$hostel_type];
        $status_select = false;
        $data_select = [];
        $error_select = "";

        // Execute select query
        $select_success = selectWithParams($mysqli, $table, $select_where, $status_select, $data_select, $error_select);

        if (!$select_success) {
            $status = false;
            $data = [];
            $error = $error_select;
            $msg = "error";
        } else {
            // Check count result from select query
            $count = $data_select['count'] ?? 0;
            if ($count > 0) {
                $msg = "already";
            }
        }

        // JSON response
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql" => $sql
        ];

        // Output JSON response
        echo json_encode($json_array);

        break;

    case 'datatable':
        // Database connection
       

        // DataTable Variables
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;

        $data = [];
        $table_batch = "bill_submission";

        if ($length == '-1') {
            $limit = "";
        }

        // Query Variables
        $json_array = "";
        $columns = [
            "'' as s_no",
            "entry_date",
            "bill_no",
            "SUM(amount) as amount",
            "COUNT(id) as bill_cnt",
            "'' as app_amt",
            "'' as rej_amt",
            "'' as app_bill_cnt",
            "'' as rej_bill_cnt",
	    "print_status",
            "rec_status",
            "rec_time",
            "bill_no as unique_id",
        ];
        $table_details = implode(", ", $columns);
        $where = "is_delete = 0 AND bill_no IS NOT NULL AND hostel_name = ? GROUP BY bill_no";
        $sql_function = "SQL_CALC_FOUND_ROWS";
        $order_by = "";

        // SQL query for data fetching
        $sql = "SELECT $sql_function $table_details FROM $table_batch WHERE $where";
        if ($limit) {
            $sql .= " LIMIT ?, ?";
        }

        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            die("MySQLi prepare failed: " . $mysqli->error);
        }

        if ($limit) {
            $stmt->bind_param("sii", $_SESSION['hostel_id'], $start, $limit);
        } else {
            $stmt->bind_param("s", $_SESSION['hostel_id']);
        }

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

                $app_amt = app_amt($value['unique_id']);
                $value['app_amt'] = $app_amt ? '<p style="color:green">' . $app_amt . '</p>' : '-';

                $rej_amt = rej_amt($value['unique_id']);
                $value['rej_amt'] = $rej_amt ? '<p style="color:red">' . $rej_amt . '</p>' : '-';

                $app_bill_cnt = app_bill_cnt($value['unique_id']);
                $value['app_bill_cnt'] = $app_bill_cnt ? $app_bill_cnt : '-';

                $rej_bill_cnt = rej_bill_cnt($value['unique_id']);
                $value['rej_bill_cnt'] = $rej_bill_cnt ? $rej_bill_cnt : '-';

                $unique_id = $value['unique_id'];
                $value['unique_id'] = btn_print1("monthly_bill_submission", $unique_id, "batch_print");

		if ($value['rec_status'] == '1') {
                    $value['rec_status'] = '<span style="color:green;">Received</span>';
                } else if ($value['rec_status'] == '0') {
                    $value['rec_status'] = '<span style="color:blue;">Pending</span>';
                    $value['rec_time'] = '-';
                }
                if ($value['print_status'] == '2') {
                    $disabled = "disabled";
                } else {
                    $disabled = "";
                } 

                  // Add select box HTML for the status column
                  $status_select = '<select class="select2 form-control" id="print_id" style="cursor: pointer;" onchange="updateStatus(\'' . $unique_id . '\', this.value)" ' . $disabled . '>';
                  $status_select .= '<option value="0"' . ($value['print_status'] == '0' ? ' selected' : '') . '>Pending</option>';
                  $status_select .= '<option value="1"' . ($value['print_status'] == '1' ? ' selected' : '') . '>Printed</option>';
                  $status_select .= '<option value="2"' . ($value['print_status'] == '2' ? ' selected' : '') . '>Submitted</option>';
                  $status_select .= '</select>';
  
                  $value['print_status'] = $status_select;

                $data[] = array_values($value);
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

        // Close connection
        $stmt->close();
        $mysqli->close();

        break;

case 'update_status':
            $bill_no = $_POST['unique_id'];
            $print_status = $_POST['print_status'];
        
            // Database connection setup (assuming already established)
            
        
            // Define the SQL statement
            $sql = "";
            $params = [];
        
            if ($print_status == 1) {
                $sql = "UPDATE bill_submission SET print_status = ?, print_time = ? WHERE bill_no = ?";
                $params = [$print_status, date('Y-m-d H:i:s'), $bill_no];
            } elseif ($print_status == 2) {
                $sql = "UPDATE bill_submission SET print_status = ?, submitted_time = ? WHERE bill_no = ?";
                $params = [$print_status, date('Y-m-d H:i:s'), $bill_no];
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
                $msg = "Updated all records with batch number: $bill_no";
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
            // $mysqli->close();
        
            // Construct JSON response
            $json_array = [
                "status" => $status,
                "data" => $data,
                "error" => $error,
                "msg" => $msg,
            ];
        
            echo json_encode($json_array);
        
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




    case 'batch_update':
        $from_date = $_POST["from_date"];
        $to_date = $_POST["to_date"];
        // 
        $batch_no = batch_no();

        $host = 'localhost'; // Change this to your database host
        $dbname = 'adi_dravidar'; // Change this to your database name
        $username = 'root'; // Change this to your database username
        $password = ''; // Change this to your database password

        // Create a PDO instance
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            // Set PDO to throw exceptions on error
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully";
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }

        // $stmt = $pdo->prepare("select std_app_p1.unique_id from std_app_p1 LEFT JOIN std_app_p2 on std_app_p1.unique_id = std_app_p2.p1_unique_id where std_app_p2.hostel_name = '65584660e85as2403310'");
        $stmt = $pdo->prepare("UPDATE std_app_p1 AS t1
                JOIN std_app_p2 AS t2 ON t1.unique_id = t2.p1_unique_id
                SET t1.batch_no = '" . $batch_no . "' and t1.batch_cr_date = '" . date('Y-m-d') . "'
                WHERE t2.hostel_name = '65584660e85as2403310'");
        // Bind parameters

        // $stmt->bindParam(':value2', $value2);
        // $stmt->bindParam(':condition', $condition);

        // Set parameter values
        $value1 = $batch_no;
        // $value2 = 'new_value2';
        // $condition = 'condition_value';

        // Execute the prepared statement
        $stmt->execute();

        echo "Records updated successfully";
        break;


    case 'bill_dispatch_datatable':
       
       
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

        if ($length == '-1') {
            $limit = "";
        }

        // Query Variables
        $json_array = "";
        $columns = [
            "unique_id",
            "'' as s_no",
            "entry_date",
            "supplier_name",
            "net_total_amount",
            "file_name",
            "academic_year",
        ];
        $table_details = implode(", ", $columns);
        $where = "is_delete = '0' AND hostel_name = ? AND (batch_bill_no IS NULL OR batch_bill_no = '' OR status = '2')";

        $order_column = $_POST["order"][0]["column"];
        $order_dir = $_POST["order"][0]["dir"];
        // Datatable Ordering 
        $order_by = datatable_sorting($order_column, $order_dir, $columns);

        $sql_function = "SQL_CALC_FOUND_ROWS";
        $sql = "SELECT $sql_function $table_details FROM $table1 WHERE $where $order_by";
        if ($limit) {
            $sql .= " LIMIT ?, ?";
        }

        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            die("MySQLi prepare failed: " . $mysqli->error);
        }

        if ($limit) {
            $stmt->bind_param("sii", $_SESSION['hostel_id'], $start, $limit);
        } else {
            $stmt->bind_param("s", $_SESSION['hostel_id']);
        }

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
            $sno = 0;
            foreach ($res_array as $key => $value) {
                $value['s_no'] = ++$sno;
                $supplier_name = $value['supplier_name'];
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
                        <input type="hidden" id="entry_date" name="entry_date[]" value="' . $value['entry_date'] . '">
                        <input type="hidden" id="file_name" name="file_name[]" value="' . $file_name . '">';

                $data[] = array_values($value);
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

        // Close connection
        $stmt->close();
        $mysqli->close();

        break;


    case 'batch_add':

    $unique_id = sanitizeInput($_POST["unique_id"]);
    $net_total_amount = sanitizeInput($_POST["net_total_amount"]);
    $hostel_name = sanitizeInput($_POST["hostel_name"]);
    $hostel_taluk = sanitizeInput($_POST["hostel_taluk"]);
    $hostel_district = sanitizeInput($_POST["hostel_district"]);
    $supplier_name = sanitizeInput($_POST["supplier_name"]);
    $file_name = sanitizeInput($_POST["file_name"]);
    $academic_year = sanitizeInput($_POST["academic_year"]);
    $batch_no = sanitizeInput($_POST["batch_no"]);
    $entry_date = sanitizeInput($_POST["entry_date"]);

    // Prepare SQL statement for inserting into "bill_submission" table
    $sql_insert = "INSERT INTO bill_submission (st_unique_id, amount, bill_date, hostel_name, hostel_taluk, hostel_district, supplier_name, file_name, academic_year, bill_cr_date, entry_date, bill_no, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = $mysqli->prepare($sql_insert);
    if ($stmt_insert) {
        $stmt_insert->bind_param(
            "sssssssssssss",
            $unique_id,
            $net_total_amount,
            $entry_date,
            $hostel_name,
            $hostel_taluk,
            $hostel_district,
            $supplier_name,
            $file_name,
            $academic_year,
            date('Y-m-d'), // Assuming bill_cr_date is of type DATE
            $entry_date,
            $batch_no,
            unique_id($prefix) // Generate new unique_id for insertion
        );

        // Execute insert statement
        if ($stmt_insert->execute()) {
            $status_insert = true;
            $data_insert = [];
            $error_insert = "";
        } else {
            $status_insert = false;
            $data_insert = [];
            $error_insert = "Insert failed: (" . $stmt_insert->errno . ") " . $stmt_insert->error;
        }
        $stmt_insert->close();
    } else {
        $status_insert = false;
        $data_insert = [];
        $error_insert = "Prepare insert statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }

    // Prepare SQL statement for updating "stock_entry" table
    $sql_update = "UPDATE stock_entry SET batch_bill_cr_date=?, batch_bill_no=?, status = ? WHERE is_delete = '0' AND unique_id = ?";
    $stmt_update = $mysqli->prepare($sql_update);
    if ($stmt_update) {
$status = '0';
        $stmt_update->bind_param(
            "ssss",
            date('Y-m-d'), // Assuming batch_bill_cr_date is of type DATE
            $batch_no,
            $status,
            $unique_id
        );

        // Execute update statement
        if ($stmt_update->execute()) {
            $status_update = true;
            $data_update = [];
            $error_update = "";
        } else {
            $status_update = false;
            $data_update = [];
            $error_update = "Update failed: (" . $stmt_update->errno . ") " . $stmt_update->error;
        }
        $stmt_update->close();
    } else {
        $status_update = false;
        $data_update = [];
        $error_update = "Prepare update statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }

    // Determine message based on insert/update status
    if ($status_insert && $status_update) {
        $msg = "create";
    } else {
        $msg = "error";
    }

    // JSON response
    $json_array = [
        "status" => $status_insert && $status_update, // both insert and update need to be successful
        "data" => array_merge($data_insert, $data_update), // merge data from both operations
        "error" => $error_insert ? $error_insert : $error_update, // or combine errors as per your logic
        "msg" => $msg,
        // "sql" => $sql_insert // Optionally include SQL statements for debugging
    ];

    // Output JSON response
    echo json_encode($json_array);

    break;



    case 'batch_detail_datatable':
       

       

        // DataTable Variables
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length == '-1' ? "" : $length;

        $data = [];
        $batch_no = $_POST['batch_no'];

        // Query Variables
        $json_array = "";
        $columns = [
            "@a:=@a+1 s_no",
            "bill_date",
            "(SELECT bill_no FROM stock_entry WHERE stock_entry.unique_id = bill_submission.st_unique_id) AS bill_no",
            "supplier_name",
            "amount",
            "file_name",
            "status",
            "reason"
        ];
        $table = "bill_submission";
        $table_details = $table . " , (SELECT @a:= ?) AS a ";
        $where = "is_delete = 0 AND bill_no = ?";
        $order_by = ""; // You can modify this to add an order by clause if needed

        $sql_function = "SQL_CALC_FOUND_ROWS";

        // SQL query for data fetching
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_details WHERE $where";
        if ($limit) {
            $sql .= " LIMIT ?, ?";
        }

        $stmt = $mysqli->prepare($sql);
        if ($limit) {
            $stmt->bind_param("issi", $start, $batch_no, $start, $limit);
        } else {
            $stmt->bind_param("isi", $start, $batch_no, $start);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch total records
        $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
        $total_records = $total_records_result->fetch_assoc()['total'];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                                $row['file_name'] = image_view("adhmHostel", $row['unique_id'], $row['file_name']);

                switch ($row['status']) {
                    case 0:
                        $row['status'] = '<p style="color:blue">Pending</p>';
                        break;
                    case 1:
                        $row['status'] = '<p style="color:green">Approved</p>';
                        break;
                    case 2:
                        $row['status'] = '<p style="color:red">Rejected</p>';
                        break;
                    default:
                        $row['status'] = '-';
                }

                $row['reason'] = $row['reason'] ? disname($row['reason']) : '-';

                // Ensure the order of columns in $data matches $columns
                $data[] = array_values($row);
            }

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data,
                "testing" => $stmt->sqlstate
            ];
        } else {
            // Handle the error case
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => $stmt->error,
                "testing" => $stmt->sqlstate
            ];
        }

        echo json_encode($json_array);

        // Close statement and connection
        $stmt->close();
        $mysqli->close();

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
            "sql" => $sql
        ];

        echo json_encode($json_array);

        break;


    default:

        break;
}

function total_count($unique_id = "")
{
    // echo $zone_name;

    global $pdo;

    $table_name = "batch_creation";
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
        "is_delete" => 0
    ];

    if ($unique_id) {
        // $where              = [];
        $where["batch_no"] .= $unique_id;
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
        // print_r($amc_name_list);
        return 0;
    }
}

function app_count($unique_id = "")
{
    // echo $zone_name;

    global $pdo;

    $table_name = "batch_creation";
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
        $where["batch_no"] .= $unique_id;
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
        // print_r($amc_name_list);
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
        // print_r($amc_name_list);
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
        // print_r($amc_name_list);
        return 0;
    }
}


function rej_count($unique_id = "")
{
    // echo $zone_name;

    global $pdo;

    $table_name = "batch_creation";
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
        $where["batch_no"] .= $unique_id;
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
        // print_r($amc_name_list);
        return 0;
    }
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
        // print_r($amc_name_list);
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
        // print_r($amc_name_list);
        return 0;
    }



}

function batch_no($academic_year = "")
{
    // $date = date("Y");
    // $st_date = substr($date, 4);

    $servername = "localhost";
    $username = "root";
    $password = "4/rb5sO2s3TpL4gu";
    $database_name = "adi_dravidar";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=adi_dravidar", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "Connected successfully";
    } catch (PDOException $e) {
        // echo "Connection failed: " . $e->getMessage();
    }

    $acmc_year = academic_year($academic_year)[0]['amc_year'];
    $a = str_split($acmc_year);
    $splt_acc_yr = $a[0] . $a[1] . $a[2] . $a[3];
    //  $hostel_id = '65584660e85as2403119'; 
    //  $hos_id = substr($hostel_id,);




    // echo "SELECT * FROM apply_application_form where application_no LIKE 'APN%' order by id desc";echo "<br>";
    $stmt = $conn->query("SELECT max(batch_no) as batch_no FROM batch_creation where is_delete = '0' and hostel_name = '" . $hostel_id . "' order by id desc");


    // if($res1=$stmt->fetch($stmt))
    if ($res1 = $stmt->fetch()) {
        if ($res1['batch_no'] != '') {


            $pur_array = explode("-", $res1['batch_no']);


            //  echo $pur_array[1];

            $booking_no = $pur_array[1];
        }
        // else{
        //     $booking_no  = '';
        // }

    }
    //  $booking_nos = 'APN-' . $splt_acc_yr .'-' . ''. $splt_dis .'-' .'' .$splt_zone.'-' .'' .$splt_host.'-' .'-0001';
    if ($booking_no == '') {
        // echo "ff";
        $booking_nos = $splt_acc_yr . 'BAT-' . '0001';
    }
    // else if ($year != date("Y")){
    //     $booking_nos = 'APN-'.date('Y'). $splt_acc_yr . ''.$splt_dis.'' .$splt_zone.'' .$splt_host.'-0001';
    // }
    else {
        $booking_no += 1;

        $booking_nos = $splt_acc_yr . 'BAT-' . str_pad($booking_no, 4, '0', STR_PAD_LEFT);
    }

    return $booking_nos;
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




