<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "master_diet_chart_sublist";
$table_main = "master_diet_chart";

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

    case 'createupdate':

        // CSRF Token Validation
        $token = $_POST['csrf_token'] ?? '';
        if (!validateCSRFToken($token)) {
            throw new Exception('CSRF validation failed.');
        }

        // Input sanitization
        $hostel_type = sanitizeInput($_POST["hostel_type"] ?? '');
        $description = sanitizeInput($_POST["description"] ?? '');
        $screen_unique_id = sanitizeInput($_POST["screen_unique_id"] ?? '');
        $unique_id = sanitizeInput($_POST["unique_id"] ?? '');

        // Validate required fields
        if (!$hostel_type || !$screen_unique_id) {
            throw new Exception("Missing required fields.");
        }

        $sql_check = "SELECT COUNT(*) as count FROM master_diet_chart WHERE is_delete = 0 AND hostel_type = ? AND unique_id != ?";

        $stmt = $mysqli->prepare($sql_check);
        $stmt->bind_param('ss', $hostel_type, $unique_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        if ($row['count'] > 0) {
            $msg = "already_2";
            $status = false;
        } else {
            if (!empty($unique_id)) {
                // UPDATE
                $query = "UPDATE $table_main SET hostel_type = ?, description = ?, screen_unique_id = ? WHERE unique_id = ?";
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param("ssss", $hostel_type, $description, $screen_unique_id, $unique_id);
            } else {
                // INSERT
                $unique_id_generated = unique_id($prefix);
                $query = "INSERT INTO $table_main (hostel_type, description, screen_unique_id, unique_id) VALUES (?, ?, ?, ?)";
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param("ssss", $hostel_type, $description, $screen_unique_id, $unique_id_generated);
            }

            if ($stmt->execute()) {
                $msg = $unique_id ? "update" : "create";
                $status = true;
                $data = ["affected_rows" => $stmt->affected_rows];
                $error = "";
            } else {
                throw new Exception("Execute failed: " . $stmt->error);
            }

            $stmt->close();
        }
        // Output JSON
        echo json_encode([
            "status" => $status,
            "msg" => $msg,
            "error" => $error,
            "data" => $data
        ]);

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
            "(SELECT hostel_type FROM hostel_type WHERE hostel_type.unique_id = $table_main.hostel_type) AS hostel_type",
            "description",
            "unique_id",
            "screen_unique_id",
        ];
        $table_details = $table_main . " , (SELECT @a:= ?) AS a ";
        $where = "is_delete = 0";
        $order_by = "";

        $sql_function = "SQL_CALC_FOUND_ROWS";

        // SQL query for data fetching
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_details WHERE $where";
        if ($limit) {
            $sql .= " LIMIT ?, ?";
        }

        $stmt = $mysqli->prepare($sql);
        if ($limit) {
            $stmt->bind_param("iii", $start, $start, $limit);
        } else {
            $stmt->bind_param("i", $start);
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
                $unique = $value['unique_id'];
                $btn_update = btn_update($folder_name, $value['unique_id']);
                $btn_delete = btn_delete($folder_name, $value['screen_unique_id']);
                // $btn_copy = '<a href="#" class="text-primary openPopup" title="Copy" 
                //     data-id="' . $value['unique_id'] . '" 
                //     data-screen-id="' . $value['screen_unique_id'] . '">
                //     <i class="fa fa-copy fa-lg"></i>
                // </a>';

                $btn_copy = '<a href="#" class="openPopup" title="Copy" 
                    data-id="' . $value['unique_id'] . '" 
                    data-screen-id="' . $value['screen_unique_id'] . '">
                    <i class="fa fa-copy" style="font-size: 0.98em;"></i>
                </a>';

                $value['unique_id'] = $btn_update . ' ' . $btn_delete . ' &nbsp;&nbsp; ' . $btn_copy;
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

    case 'diet_chart_sub_datatable':

        // DataTable Parameters
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length == '-1' ? "" : $length;

        $screen_unique_id = $_POST['screen_unique_id'];
        $data = [];

        // Query Columns
        $columns = [
            "' ' as sno",
            "(SELECT item_category FROM item_category WHERE item_category.unique_id = master_diet_chart_sublist.category) AS category",
            "CASE 
                WHEN master_diet_chart_sublist.item = 'C-V' THEN 'Common Veg' 
                ELSE (SELECT item FROM item WHERE item.unique_id = master_diet_chart_sublist.item) 
                END AS item",
            "unit",
            "quantity",
            "unique_id"
        ];

        $sql_function = "SQL_CALC_FOUND_ROWS";

        $where = "is_delete = 0 AND screen_unique_id = ?";
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM master_diet_chart_sublist WHERE $where";

        if ($limit) {
            $sql .= " LIMIT ?, ?";
        }

        $stmt = $mysqli->prepare($sql);

        // Bind params
        if ($limit) {
            $stmt->bind_param("sii", $screen_unique_id, $start, $limit);
        } else {
            $stmt->bind_param("s", $screen_unique_id);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch total records
        $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
        $total_records = $total_records_result->fetch_assoc()['total'];

        if ($result) {
            $res_array = $result->fetch_all(MYSQLI_ASSOC);
            $sno = $start + 1;

            foreach ($res_array as $value) {
                $edit_btn = '<a href="#" onclick="edit_sublist(\'' . $value['unique_id'] . '\')" class="font-18 text-info me-2" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Edit" data-bs-original-title="Edit"><i class="uil uil-pen"></i></a>';
                $delete_btn = '<a href="#" onclick="delete_sublist(\'' . $value['unique_id'] . '\')" class="font-18 text-danger" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Delete" data-bs-original-title="Delete"><i class="uil uil-trash"></i></a>';

                $action_btns = $edit_btn . " " . $delete_btn;

                $row = [
                    "sno" => $sno++,
                    "category" => $value['category'],
                    "item" => $value['item'],
                    "unit" => $value['unit'],
                    "quantity" => $value['quantity'],
                    "action" => $action_btns
                ];

                $data[] = array_values($row);
            }

            echo json_encode([
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data
            ]);
        } else {
            echo json_encode([
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => $stmt->error
            ]);
        }

        // Close connection
        $stmt->close();
        $mysqli->close();

        break;

    case 'delete':
        $unique_id = $_POST['unique_id'];
        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        // Prepare SQL statements
        $stmt = $mysqli->prepare("UPDATE $table_main SET is_delete = ? WHERE screen_unique_id = ?");
        $stmt_2 = $mysqli->prepare("UPDATE $table SET is_delete = ? WHERE screen_unique_id = ?");

        $is_delete = 1;

        if ($stmt && $stmt_2) {
            // Bind parameters
            $stmt->bind_param("is", $is_delete, $unique_id);
            $stmt_2->bind_param("is", $is_delete, $unique_id);

            // Execute both statements
            $stmt_result = $stmt->execute();
            $stmt2_result = $stmt_2->execute();

            if ($stmt_result && $stmt2_result) {
                $status = true;
                $data = null;
                $error = "";
                $msg = "success_delete";
            } else {
                $status = false;
                $data = null;
                $error = $stmt->error . ' ' . $stmt_2->error;
                $msg = "error";
            }

            $stmt->close();
            $stmt_2->close();
        } else {
            $status = false;
            $data = null;
            $error = ($stmt ? '' : $mysqli->error . ' (stmt1)') . ' ' . ($stmt_2 ? '' : $mysqli->error . ' (stmt2)');
            $msg = "error";
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            "sql" => "" // You can log actual SQL here if needed
        ];

        echo json_encode($json_array);
        break;

    case 'sublist_cu':
        $screen_unique_id = $_POST['screen_unique_id'];
        $hostel_type = $_POST['hostel_type'] ?? '';
        $category = $_POST['item_category'] ?? '';
        $item = $_POST['item'] ?? '';
        $unit = $_POST['unit'] ?? '';
        $quantity = $_POST['quantity'] ?? '';
        $sublist_unique_id = $_POST['sublist_unique_id'] ?? '';

        if (empty($screen_unique_id) || empty($category) || empty($item) || empty($quantity)) {
            echo json_encode([
                'status' => false,
                'msg' => 'form_alert'
            ]);
            return;
        }

        $check_exist = "SELECT COUNT(*) FROM master_diet_chart_sublist WHERE hostel_type = ? AND item = ? AND is_delete = 0 AND unique_id != ?";
        $count = $mysqli->prepare($check_exist);
        $count->bind_param("sss", $hostel_type, $item, $sublist_unique_id);
        $count->execute();
        $count->bind_result($total);
        $count->fetch();
        $count->close();

        if ($total > 0) {
            $msg = 'already';
        } else {
            if ($sublist_unique_id) {
                // Update existing
                $sql = "UPDATE master_diet_chart_sublist 
                SET hostel_type = ?, category = ?, item = ?, unit = ?, quantity = ? 
                WHERE unique_id = ? AND is_delete = 0";

                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("ssssss", $hostel_type, $category, $item, $unit, $quantity, $sublist_unique_id);

                if ($stmt->execute()) {
                    $msg = "update";
                } else {
                    $msg = "error";
                }
            } else {
                // Create new
                $new_id = unique_id(); // assuming this exists
                $sql = "INSERT INTO master_diet_chart_sublist (unique_id, screen_unique_id, hostel_type, category, item, unit, quantity) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("sssssss", $new_id, $screen_unique_id, $hostel_type, $category, $item, $unit, $quantity);

                if ($stmt->execute()) {
                    $msg = "create";
                } else {
                    $msg = "error";
                }
            }
        }

        echo json_encode([
            "status" => true,
            "msg" => $msg
        ]);
        break;



    case 'sublist_delete':
        $unique_id = $_POST['unique_id'];
        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            echo json_encode(['status' => false, 'msg' => 'csrf_failed']);
            return;
        }

        $sql = "UPDATE master_diet_chart_sublist SET is_delete = 1 WHERE unique_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $unique_id);

        if ($stmt->execute()) {
            echo json_encode(['status' => true, 'msg' => 'deleted']);
        } else {
            echo json_encode(['status' => false, 'msg' => 'error']);
        }

        break;


    case 'get_sublist_item':
        $unique_id = $_POST['unique_id'];
        $sql = "SELECT * FROM master_diet_chart_sublist WHERE unique_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $unique_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        echo json_encode([
            "status" => true,
            "data" => $data
        ]);
        break;

    case 'item_category':
        $item_category = $_POST['item_category'];
        $veg_type = $_POST['veg_type'] ?? '';

        $item_category_options = item_veg('', $item_category, $veg_type);
        $item_name_options = select_option($item_category_options, "Select item");

        echo $item_name_options;
        break;


    case 'copy_record':

        $csrf_token = $_POST['csrf_token'] ?? '';
        if (!validateCSRFToken($csrf_token)) {
            throw new Exception('CSRF validation failed.');
        }

        $screen_unique_id = sanitizeInput($_POST['screen_unique_id'] ?? '');
        $new_hostel_type = sanitizeInput($_POST['hostel_type'] ?? '');

        $status = false;
        $msg = '';
        $data = [];

        // Check if hostel_type already exists in master_diet_chart (and not deleted)
        $stmt = $mysqli->prepare("SELECT COUNT(*) as cnt FROM master_diet_chart WHERE hostel_type = ? AND is_delete = 0");
        $stmt->bind_param("s", $new_hostel_type);
        $stmt->execute();
        $exists = $stmt->get_result()->fetch_assoc()['cnt'];
        $stmt->close();

        if ($exists > 0) {
            echo json_encode(["status" => false, "msg" => "already", "description" => "Hostel type already exists in diet chart."]);
            break;
        }

        // Check if hostel_type exists in sublist (active)
        $stmt = $mysqli->prepare("SELECT COUNT(*) as cnt FROM master_diet_chart_sublist WHERE hostel_type = ? AND is_delete = 0");
        $stmt->bind_param("s", $new_hostel_type);
        $stmt->execute();
        $exists_sublist = $stmt->get_result()->fetch_assoc()['cnt'];
        $stmt->close();

        if ($exists_sublist > 0) {
            echo json_encode(["status" => false, "msg" => "Hostel type already exists in sublist."]);
            break;
        }

        // Fetch master record by screen_unique_id
        $stmt = $mysqli->prepare("SELECT * FROM master_diet_chart WHERE screen_unique_id = ? AND is_delete = 0 LIMIT 1");
        $stmt->bind_param("s", $screen_unique_id);
        $stmt->execute();
        $master = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$master) {
            echo json_encode(["status" => false, "msg" => "Original master record not found."]);
            break;
        }

        // Generate new IDs
        $new_unique_id = unique_id();
        $new_screen_id = unique_id();

        // Insert new master record
        $stmt = $mysqli->prepare("INSERT INTO master_diet_chart (unique_id, screen_unique_id, hostel_type, is_delete) VALUES (?, ?, ?, 0)");
        $stmt->bind_param("sss", $new_unique_id, $new_screen_id, $new_hostel_type);
        $stmt->execute();
        $stmt->close();

        // Fetch related sublist records
        $stmt = $mysqli->prepare("SELECT * FROM master_diet_chart_sublist WHERE screen_unique_id = ? AND is_delete = 0");
        $stmt->bind_param("s", $screen_unique_id);
        $stmt->execute();
        $sublist = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        // Insert copied sublist records with new screen_unique_id and new unique_ids
        foreach ($sublist as $row) {
            $sub_unique_id = unique_id($prefix);
            $category = $row['category'];
            $item = $row['item'];
            $unit = $row['unit'];
            $quantity = $row['quantity'];

            $stmt = $mysqli->prepare("INSERT INTO master_diet_chart_sublist (unique_id, screen_unique_id, hostel_type, category, item, unit, quantity, is_delete) VALUES (?, ?, ?, ?, ?, ?, ?, 0)");
            $stmt->bind_param("sssssss", $sub_unique_id, $new_screen_id, $new_hostel_type, $category, $item, $unit, $quantity);
            $stmt->execute();
        }

        $status = true;
        $msg = "add";
        $description = "Copied Successfully";
        echo json_encode([
            "status" => $status,
            "msg" => $msg,
            "description" => $description
        ]);
        break;

    case 'get_unit':
        $item_unique_id = $_POST['item_name'] ?? '';

        if ($item_unique_id) {
            // Prepare SQL
            $sql = "SELECT unit FROM item WHERE unique_id = ? AND is_delete = 0 LIMIT 1";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("s", $item_unique_id);
            $stmt->execute();
            $stmt->bind_result($unit);
            $stmt->fetch();
            $stmt->close();

            // Return the unit as plain text (for JS)
            echo $unit ?? '';
        } else {
            echo '';
        }
        break;

    case 'silent_delete':
        if (!empty($_POST['screen_unique_id'])) {
            $screen_unique_id = $_POST['screen_unique_id'];

            $stmt = $mysqli->prepare("DELETE FROM master_diet_chart_sublist WHERE screen_unique_id = ?");
            $stmt->bind_param("s", $screen_unique_id);
            $stmt->execute();
            $stmt->close();
        }
        exit; // no output, completely silent
        break;


    default:

        break;
}
