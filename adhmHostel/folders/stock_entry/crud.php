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

function validateCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

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
            "supplier_name",
            "bill_no",
            "file_name",
            "main.unique_id",
            "(SELECT hostel_name FROM hostel_name WHERE unique_id = main.hostel_name) as hostel_name",
            "(SELECT district_name FROM district_name WHERE unique_id = main.district) as district",
            "(SELECT taluk_name FROM taluk_creation WHERE unique_id = main.taluk) as taluk",

            "screen_unique_id",
            "address",
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
            $row['supplier_name'] = $row['supplier_name'] . '<br><span style="font-size:12px;">' . $row['address'] . '</span>';

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

    // case 'main_createupdate':

    //     $token = $_POST['csrf_token'];

    //     // Validate CSRF token
    //     if (!validateCSRFToken($token)) {
    //         die(json_encode(['status' => 'error', 'msg' => 'CSRF validation failed.']));
    //     }

    //     // Sanitize input values
    //     $stock_id = $_POST["stock_id"];
    //     $supplier_name = $_POST["supplier_name"];
    //     $address = $_POST["address"];
    //     $text_address = $_POST["text_address"];
    //     $bill_no = $_POST["bill_no"];
    //     $fssai_no = $_POST["fssai_no"];
    //     $purchase_item = $_POST["purchase_item"];
    //     $veg_item = $_POST["veg_item"];
    //     $hostel_name = $_POST["hostel_name"];
    //     $discount = $_POST["discount"];
    //     $expense = $_POST["expense"];
    //     $gst = $_POST["gst"];
    //     $net_total_amount = $_POST["net_total_amount"];
    //     $tot_qty = $_POST["tot_qty"];
    //     $tot_amount = $_POST["tot_amount"];
    //     $district = $_POST["district"];
    //     $taluk = $_POST["taluk"];
    //     $is_active = $_POST["is_active"];
    //     $screen_unique_id = $_POST["screen_unique_id"];
    //     $academic_year = $_POST["academic_year"];
    //     // $academic_year = $_SESSION["academic_year"];
    //     $unique_id = $_POST["unique_id"];

    //     // Handle entry date
    //     $entry_date = ($_POST["entry_date"] == '') ? date('Y-m-d') : $_POST["entry_date"];

    //     // File upload handling
    //     $file_names = '';
    //     $file_org_names = '';
    //     if ($veg_item) {

    //         $supplier_names = $veg_item;
    //     } else if ($supplier_name) {
    //         $supplier_names = $supplier_name;

    //     }

    //     if ($address) {

    //         $supp_address = $address;

    //     } else if ($text_address) {
    //         $supp_address = $text_address;

    //     }

    //     $allowedExts = array('pdf', 'jpg', 'jpeg', 'png');
    //     if (isset($_FILES["test_file"]) && $_FILES["test_file"]["error"] === UPLOAD_ERR_OK) {
    //         $extension = pathinfo($_FILES["test_file"]['name'], PATHINFO_EXTENSION);

    //         // Check if file extension is allowed
    //         if (in_array($extension, $allowedExts)) {
    //             $file_exp = explode(".", $_FILES["test_file"]['name']);
    //             $tem_name = random_strings(25) . "." . $file_exp[1];
    //             if (move_uploaded_file($_FILES["test_file"]["tmp_name"], '../../uploads/stock_entry/' . $tem_name)) {
    //                 $file_names = $tem_name;
    //                 $file_org_names = $_FILES["test_file"]['name'];
    //             } else {
    //                 die(json_encode(['status' => 'error', 'msg' => 'Failed to move uploaded file.']));
    //             }
    //         } else {
    //             die(json_encode(['status' => 'error', 'msg' => 'File type not allowed.']));
    //         }
    //     }

    //     // Prepare SQL statements for INSERT and UPDATE
    //     if ($unique_id) {
    //         // Update query
    //         if ($file_names != '') {
    //             $sql = "UPDATE stock_entry SET supplier_name=?, address=?, entry_date=?, bill_no=?, hostel_name=?, stock_id=?, discount=?, expense=?, gst=?, net_total_amount=?, academic_year=?, district=?, taluk=?, tot_qty=?, tot_amount=?, purchase_item=?, veg_item=?, fssai_no=?, file_name=?, file_org_name=?, screen_unique_id=? WHERE unique_id=?";
    //             $stmt = $mysqli->prepare($sql);
    //             $stmt->bind_param("ssssssssssssssssssssss", $supplier_names, $supp_address, $entry_date, $bill_no, $hostel_name, $stock_id, $discount, $expense, $gst, $net_total_amount, $academic_year, $district, $taluk, $tot_qty, $tot_amount, $purchase_item, $veg_item, $fssai_no, $file_names, $file_org_names, $screen_unique_id, $unique_id);
    //         } else {
    //             $sql = "UPDATE stock_entry SET supplier_name=?, address=?, entry_date=?, bill_no=?, hostel_name=?, stock_id=?, discount=?, expense=?, gst=?, net_total_amount=?, academic_year=?, district=?, taluk=?, tot_qty=?, tot_amount=?, purchase_item=?, veg_item=?, fssai_no=?, screen_unique_id=? WHERE unique_id=?";
    //             $stmt = $mysqli->prepare($sql);
    //             $stmt->bind_param("ssssssssssssssssssss", $supplier_names, $supp_address, $entry_date, $bill_no, $hostel_name, $stock_id, $discount, $expense, $gst, $net_total_amount, $academic_year, $district, $taluk, $tot_qty, $tot_amount, $purchase_item, $veg_item, $fssai_no, $screen_unique_id, $unique_id);
    //         }
    //     } else {
    //         // Insert query
    //         if ($file_names != '') {
    //             $sql = "INSERT INTO stock_entry (supplier_name, address, entry_date, bill_no, hostel_name, stock_id, discount, expense, gst, net_total_amount, academic_year, district, taluk, tot_qty, tot_amount, purchase_item, veg_item, fssai_no, file_name, file_org_name, unique_id, screen_unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    //             $stmt = $mysqli->prepare($sql);
    //             $stmt->bind_param("ssssssssssssssssssssss", $supplier_names, $supp_address, $entry_date, $bill_no, $hostel_name, $stock_id, $discount, $expense, $gst, $net_total_amount, $academic_year, $district, $taluk, $tot_qty, $tot_amount, $purchase_item, $veg_item, $fssai_no, $file_names, $file_org_names, unique_id($prefix), $screen_unique_id);
    //         } else {
    //             $sql = "INSERT INTO stock_entry (supplier_name, address, entry_date, bill_no, hostel_name, stock_id, discount, expense, gst, net_total_amount, academic_year, district, taluk, tot_qty, tot_amount, purchase_item, veg_item, fssai_no, unique_id, screen_unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    //             $stmt = $mysqli->prepare($sql);
    //             $stmt->bind_param("ssssssssssssssssssss", $supplier_names, $supp_address, $entry_date, $bill_no, $hostel_name, $stock_id, $discount, $expense, $gst, $net_total_amount, $academic_year, $district, $taluk, $tot_qty, $tot_amount, $purchase_item, $veg_item, $fssai_no, unique_id($prefix), $screen_unique_id);
    //         }
    //     }

    //     // Execute statement
    //     if ($stmt->execute()) {
    //         $status = "success";
    //         $data = [];
    //         $error = "";
    //         $msg = $unique_id ? 'update' : 'create';
    //     } else {
    //         $status = "error";
    //         $data = [];
    //         $error = "Failed to execute query: " . $stmt->error;
    //         $msg = "error";
    //     }

    //     // Close statement
    //     $stmt->close();

    //     // Construct JSON response
    //     $json_array = [
    //         "status" => $status,
    //         "data" => $data,
    //         "error" => $error,
    //         "msg" => $msg,
    //     ];

    //     echo json_encode($json_array);

    //     break;

    case 'main_createupdate':

        $token = $_POST['csrf_token'] ?? '';
        if (!validateCSRFToken($token)) {
            echo json_encode(['status' => 'error', 'msg' => 'CSRF validation failed.']);
            exit;
        }

        // ---------- Sanitize Inputs ----------
        $stock_id = trim($_POST["stock_id"] ?? '');
        $supplier_name = trim($_POST["supplier_name"] ?? '');
        $address = trim($_POST["address"] ?? '');
        $text_address = trim($_POST["text_address"] ?? '');
        $bill_no = trim($_POST["bill_no"] ?? '');
        $fssai_no = trim($_POST["fssai_no"] ?? '');
        $purchase_item = trim($_POST["purchase_item"] ?? '');
        $veg_item = trim($_POST["veg_item"] ?? '');
        $hostel_name = trim($_POST["hostel_name"] ?? '');
        // $discount = trim($_POST["discount"] ?? '0');
        // $expense = trim($_POST["expense"] ?? '0');
        // $gst = trim($_POST["gst"] ?? '0');
        $net_total_amount = trim($_POST["net_total_amount"] ?? '0');
        $tot_qty = trim($_POST["tot_qty"] ?? '0');
        $tot_amount = trim($_POST["tot_amount"] ?? '0');
        $district = trim($_POST["district"] ?? '');
        $taluk = trim($_POST["taluk"] ?? '');
        $is_active = trim($_POST["is_active"] ?? '1');
        $screen_unique_id = trim($_POST["screen_unique_id"] ?? '');
        $academic_year = trim($_POST["academic_year"] ?? '');
        $unique_id = trim($_POST["unique_id"] ?? '');

        $entry_date = empty($_POST["entry_date"]) ? date('Y-m-d') : $_POST["entry_date"];

        // ---------- Derived supplier info ----------
        $supplier_names = $veg_item ?: $supplier_name;
        $supp_address = $address ?: $text_address;

        // ---------- File Upload Handling ----------
        $file_names = '';
        $file_org_names = '';
        $allowedExts = ['pdf', 'jpg', 'jpeg', 'png'];

        if (isset($_FILES["test_file"]) && $_FILES["test_file"]["error"] === UPLOAD_ERR_OK) {
            $extension = strtolower(pathinfo($_FILES["test_file"]['name'], PATHINFO_EXTENSION));

            if (in_array($extension, $allowedExts)) {
                $tem_name = random_strings(25) . "." . $extension;
                $upload_path = '../../uploads/stock_entry/' . $tem_name;

                if (move_uploaded_file($_FILES["test_file"]["tmp_name"], $upload_path)) {
                    $file_names = $tem_name;
                    $file_org_names = $_FILES["test_file"]['name'];
                } else {
                    echo json_encode(['status' => 'error', 'msg' => 'Failed to move uploaded file.']);
                    exit;
                }
            } else {
                echo json_encode(['status' => 'error', 'msg' => 'File type not allowed.']);
                exit;
            }
        }

        // ---------- Begin Transaction ----------
        $mysqli->begin_transaction();

        try {
            if ($unique_id) {
                // -------- UPDATE --------
                if ($file_names != '') {
                    $sql = "UPDATE stock_entry 
                        SET supplier_name=?, address=?, entry_date=?, bill_no=?, hostel_name=?, stock_id=?, 
                            net_total_amount=?, academic_year=?, district=?, taluk=?, 
                            tot_qty=?, tot_amount=?, purchase_item=?, veg_item=?, fssai_no=?, 
                            file_name=?, file_org_name=?, screen_unique_id=? 
                        WHERE unique_id=?";
                    // discount=?, expense=?, gst=?,
                    $stmt = $mysqli->prepare($sql);
                    if (!$stmt)
                        throw new Exception($mysqli->error);

                    $stmt->bind_param(
                        "sssssssssssssssssss",
                        // sss
                        $supplier_names,
                        $supp_address,
                        $entry_date,
                        $bill_no,
                        $hostel_name,
                        $stock_id,
                        // $discount,
                        // $expense,
                        // $gst,
                        $net_total_amount,
                        $academic_year,
                        $district,
                        $taluk,
                        $tot_qty,
                        $tot_amount,
                        $purchase_item,
                        $veg_item,
                        $fssai_no,
                        $file_names,
                        $file_org_names,
                        $screen_unique_id,
                        $unique_id
                    );
                } else {
                    $sql = "UPDATE stock_entry 
                        SET supplier_name=?, address=?, entry_date=?, bill_no=?, hostel_name=?, stock_id=?, 
                            net_total_amount=?, academic_year=?, district=?, taluk=?, 
                            tot_qty=?, tot_amount=?, purchase_item=?, veg_item=?, fssai_no=?, screen_unique_id=? 
                        WHERE unique_id=?";
                    // discount=?, expense=?, gst=?, 
                    $stmt = $mysqli->prepare($sql);
                    if (!$stmt)
                        throw new Exception($mysqli->error);

                    $stmt->bind_param(
                        "sssssssssssssssss",
                        // sss
                        $supplier_names,
                        $supp_address,
                        $entry_date,
                        $bill_no,
                        $hostel_name,
                        $stock_id,
                        // $discount,
                        // $expense,
                        // $gst,
                        $net_total_amount,
                        $academic_year,
                        $district,
                        $taluk,
                        $tot_qty,
                        $tot_amount,
                        $purchase_item,
                        $veg_item,
                        $fssai_no,
                        $screen_unique_id,
                        $unique_id
                    );
                }

                $action = 'update';

            } else {
                // -------- INSERT --------
                $new_unique_id = unique_id($prefix);

                if ($file_names != '') {
                    $sql = "INSERT INTO stock_entry 
                        (supplier_name, address, entry_date, bill_no, hostel_name, stock_id, 
                         net_total_amount, academic_year, district, taluk, tot_qty, tot_amount, purchase_item, veg_item, 
                         fssai_no, file_name, file_org_name, unique_id, screen_unique_id)
                        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                    // ,?,?,?
                    // discount, expense, gst, 
                    $stmt = $mysqli->prepare($sql);
                    if (!$stmt)
                        throw new Exception($mysqli->error);

                    $stmt->bind_param(
                        "sssssssssssssssssss",
                        // sss
                        $supplier_names,
                        $supp_address,
                        $entry_date,
                        $bill_no,
                        $hostel_name,
                        $stock_id,
                        // $discount,
                        // $expense,
                        // $gst,
                        $net_total_amount,
                        $academic_year,
                        $district,
                        $taluk,
                        $tot_qty,
                        $tot_amount,
                        $purchase_item,
                        $veg_item,
                        $fssai_no,
                        $file_names,
                        $file_org_names,
                        $new_unique_id,
                        $screen_unique_id
                    );
                } else {
                    $sql = "INSERT INTO stock_entry 
                        (supplier_name, address, entry_date, bill_no, hostel_name, stock_id, 
                         net_total_amount, academic_year, district, taluk, tot_qty, tot_amount, purchase_item, veg_item, 
                         fssai_no, unique_id, screen_unique_id)
                        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                    // ,?,?,?
                    //  discount, expense, gst, 
                    $stmt = $mysqli->prepare($sql);
                    if (!$stmt)
                        throw new Exception($mysqli->error);

                    $stmt->bind_param(
                        "sssssssssssssssss",
                        // sss
                        $supplier_names,
                        $supp_address,
                        $entry_date,
                        $bill_no,
                        $hostel_name,
                        $stock_id,
                        // $discount,
                        // $expense,
                        // $gst,
                        $net_total_amount,
                        $academic_year,
                        $district,
                        $taluk,
                        $tot_qty,
                        $tot_amount,
                        $purchase_item,
                        $veg_item,
                        $fssai_no,
                        $new_unique_id,
                        $screen_unique_id
                    );
                }

                $action = 'create';
            }

            // ---------- Execute Query ----------
            if (!$stmt->execute()) {
                throw new Exception("Query execution failed: " . $stmt->error);
            }

            $mysqli->commit();

            echo json_encode([
                'status' => 'success',
                'msg' => $action,
                'error' => '',
                'data' => []
            ]);
        } catch (Exception $e) {
            $mysqli->rollback();
            echo json_encode([
                'status' => 'error',
                'msg' => 'Database operation failed.',
                'error' => $e->getMessage(),
                'data' => []
            ]);
        }

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
        // $academic_year = sanitizeInput($_SESSION['academic_year']);
        $academic_year = sanitizeInput($_POST['academic_year']);

        if ($_POST["entry_date"] == '') {
            $entry_date = date('Y-m-d');
        } else {
            $entry_date = $_POST["entry_date"];
        }

        // Prepare SQL statement
        $sql = "UPDATE $overall_table 
                    SET entry_date=?, bill_no=?, hostel_unique_id=?, academic_year=?, district_unique_id=?, taluk_unique_id=?
                    WHERE stock_id=?";

        // Execute the statement
        $stmt = $mysqli->prepare($sql);
        // print_r($stmt);
        if ($stmt === false) {
            die('MySQL prepare error: ' . $mysqli->error);
        }

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
        $purchase_item = sanitizeInput($_POST["purchase_item"]);

        // Convert qty to float and format to 2 decimal places
        $stock_qty = number_format(floatval($qty), 2, '.', '');

        // Prepare SQL statements for INSERT and UPDATE
        if ($unique_id) {
            // Update query
            $sql = "UPDATE $table SET category_name=?, item_name=?, qty=?, unit=?, rate=?, amount=?, stock_id=?, screen_unique_id=? WHERE unique_id=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("sssssssss", $purchase_item, $item_name, $stock_qty, $unit, $rate, $amount, $stock_id, $screen_unique_id, $unique_id);
        } else {
            // Insert query
            $sql = "INSERT INTO $table (category_name, item_name, qty, unit, rate, amount, stock_id, screen_unique_id, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $mysqli->prepare($sql);



            $stmt->bind_param("sssssssss", $purchase_item, $item_name, $stock_qty, $unit, $rate, $amount, $stock_id, $screen_unique_id, unique_id($prefix));
        }

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

        // Insert query
        $sql = "INSERT INTO $overall_table (category_name, item_name, qty, unit, rate, amount, stock_id, screen_unique_id, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sssssssss", $product_category, $item_name, $stock_qty, $unit, $rate, $amount, $stock_id, $screen_unique_id, unique_id($prefix));

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
            "(select item_category from item_category where item_category.unique_id = stock_entry_sub.category_name) as category",
            "(select item from item where item.unique_id = stock_entry_sub.item_name) as item_name",
            "qty",
            "unit",
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
                $value['category'] = disname($value['category']);
                $value['unique_id'] = disname($value['unique_id']);
                $unique = $value['unique_id'];
                $btn_delete = btn_delete_stk($folder_name, $value['screen_unique_id'], $value['unique_id']);

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


    case 'product_supply':

        $purchase_item = $_POST['purchase_item'];

        $product_type_option = item_stock_monthinward('', $purchase_item, $_SESSION['hostel_id']);

        $product_type_options = select_option($product_type_option, 'Select item type');

        echo $product_type_options;

        break;

    case 'get_hostel_name':
        $taluk_name = $_POST['taluk'];

        $taluk_name_options = hostel_name_get("", $taluk_name);

        $taluk_name_options = select_option($taluk_name_options, 'Select Hostel Name');
        echo $taluk_name_options;

        break;

    case 'get_unit_name':
        $item_unique_id = $_POST['item_name'] ?? '';
        $screen_unique_id = $_POST['screen_unique_id'] ?? '';
        $hostel_id = $_SESSION['hostel_id'];
        $currentMonthYear = date('Y-m');
        if ($item_unique_id) {

            $sql = "SELECT unit FROM item WHERE unique_id = ? AND is_delete = 0 LIMIT 1";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("s", $item_unique_id);
            $stmt->execute();
            $stmt->bind_result($unit);
            $stmt->fetch();
            $stmt->close();

            $currentMonthYear = date('Y-m'); // e.g., "2025-11"

            $sql_inward = "
                            SELECT SUM(qty) AS total_qty
                            FROM stock_inward
                            WHERE item_name = ?
                            AND is_delete = 0
                            AND DATE_FORMAT(entry_date, '%Y-%m') = ?
                            AND hostel_unique_id = ? OR screen_unique_id = ?
                        ";

            $stmt = $mysqli->prepare($sql_inward);
            if (!$stmt) {
                error_log("Prepare failed (inward): " . $mysqli->error);
                echo json_encode(["unit" => $unit ?? '', "available_qty" => 0]);
                exit;
            }
            $stmt->bind_param("ssss", $item_unique_id, $currentMonthYear, $hostel_id, $screen_unique_id);
            $stmt->execute();
            $stmt->bind_result($inwardqty);
            $stmt->fetch();
            $stmt->close();

            $inwardqty = $inwardqty ?? 0;


            // Same fix for issued qty
            $sql_issued = "SELECT SUM(quantity) 
               FROM monthly_indent_items 
               WHERE hostel_id = ? 
               AND item = ? 
               AND is_delete = 0 
               AND month_year = ?";

            $stmt = $mysqli->prepare($sql_issued);
            $stmt->bind_param("sss", $hostel_id, $item_unique_id, $currentMonthYear);
            $stmt->execute();
            $stmt->bind_result($issuedqty);
            $stmt->fetch();
            $stmt->close();

            $issuedqty = $issuedqty ?? 0;

            // print_r($issuedqty);
            $availablenward = number_format($issuedqty - $inwardqty, 2, '.', '');


            echo json_encode([
                "unit" => $unit ?? '',
                "available_qty" => $availablenward
            ]);
        } else {
            echo json_encode([
                "unit" => '',
                "available_qty" => 0
            ]);
        }

        break;

    // case 'get_unit_name':
    //     $item_unique_id = $_POST['item_name'] ?? '';
    //     $screen_unique_id = $_POST['screen_unique_id'] ?? '';
    //     $hostel_id = $_SESSION['hostel_id'];
    //     $currentMonthYear = date('Y-m');
    //     if ($item_unique_id) {

    //         $sql = "SELECT unit FROM item WHERE unique_id = ? AND is_delete = 0 LIMIT 1";
    //         $stmt = $mysqli->prepare($sql);
    //         $stmt->bind_param("s", $item_unique_id);
    //         $stmt->execute();
    //         $stmt->bind_result($unit);
    //         $stmt->fetch();
    //         $stmt->close();

    //         $item_name = $_POST['item_name'];
    //         $hostel_id = $_SESSION['hostel_id'];

    //         $where = "item_name = '" . $item_name . "' AND hostel_unique_id = '$hostel_id' and is_delete = 0";
    //         $table_entry_sub = "stock_inward";

    //         $columns = [
    //             "(select sum(qty) from stock_inward where item_name = '" . $item_name . "' AND hostel_unique_id = '$hostel_id' and is_delete = 0 ) as in_qty",
    //             "(select sum(qty) from stock_outward where item_name = '" . $item_name . "' AND hostel_unique_id = '$hostel_id' and is_delete = 0) as out_qty",
    //         ];

    //         $table_details = [
    //             $table_entry_sub,
    //             $columns
    //         ];

    //         $result_values = $pdo->select($table_details, $where);

    //         // print_r($result_values);die();

    //         if ($result_values->status) {

    //             $result_values = $result_values->data;

    //             $format_con_qty = number_format((float) $result_values[0]["out_qty"], 3, '.', '');
    //             $item_in_qty = $result_values[0]["in_qty"];
    //             $rem_qty = $item_in_qty - $format_con_qty;

    //             if (fmod($rem_qty, 1) == 0) {
    //                 // It's a whole number (no decimal part)
    //                 $res_qty = (int) $rem_qty; // show as 5
    //             } else {
    //                 // It's a decimal value
    //                 $res_qty = number_format((float) $rem_qty, 3, '.', ''); // show as 5.250
    //             }

    //         }

    //         echo json_encode([
    //             "unit" => $unit ?? '',
    //             "available_qty" => $res_qty
    //         ]);
    //     } else {
    //         echo json_encode([
    //             "unit" => '',
    //             "available_qty" => 0
    //         ]);
    //     }

    //     break;


    case 'sub_delete':
        $screenUniqueId = $_POST['id'];
        $unique_id = $_POST['unique_id'];



        // Prepare the SQL statement
        $sql = "UPDATE $table SET is_delete = 1 WHERE screen_unique_id = ? and unique_id = ?";
        $sql_1 = "UPDATE $overall_table SET is_delete = 1 WHERE screen_unique_id = ? and unique_id = ?";


        // Prepare the statement
        $stmt = $mysqli->prepare($sql);
        $stmt_1 = $mysqli->prepare($sql_1);


        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("ss", $screenUniqueId, $unique_id);
            $stmt_1->bind_param("ss", $screenUniqueId, $unique_id);


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


            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $result->data[0],
            ];
        } else {

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
                "tot_amount" => $tot_amount
            ];
        } else {
            // print_r($result);
        }

        echo json_encode($json_array);
        break;


    case 'purchase_category':

        $purchase_item = $_POST['purchase_item'];
        $digital_category_option = type_of_equipment('', $purchase_item);

        $digital_category_options = select_option($digital_category_option, 'Select equipment');

        echo $digital_category_options;

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
                    $image_view .= '<a href="javascript:print_view(\'/' . $doc_file_name . '\')"><img src="uploads/stock_entry/' . $doc_file_name . '"  width="30px" ></a>';
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