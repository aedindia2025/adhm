<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "special_menu_chart_sub";
$table_main = "special_menu_chart";

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
        $date = sanitizeInput($_POST["date"] ?? '');
        $festival = sanitizeInput($_POST["festival"] ?? '');
        $screen_unique_id = sanitizeInput($_POST["screen_unique_id"] ?? '');
        $unique_id = sanitizeInput($_POST["unique_id"] ?? '');

        // Validate required fields
        if (!$date || !$screen_unique_id) {
            throw new Exception("Missing required fields.");
        }

        $sql_check = "SELECT COUNT(*) as count FROM special_menu_chart WHERE is_delete = 0 AND date = ? AND unique_id != ?";

        $stmt = $mysqli->prepare($sql_check);
        $stmt->bind_param('ss', $date, $unique_id);
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
                $query = "UPDATE $table_main SET date = ?, festival = ?, screen_unique_id = ? WHERE unique_id = ?";
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param("ssss", $date, $festival, $screen_unique_id, $unique_id);
            } else {
                // INSERT
                $unique_id_generated = unique_id($prefix);
                $query = "INSERT INTO $table_main (date, festival, screen_unique_id, unique_id) VALUES (?, ?, ?, ?)";
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param("ssss", $date, $festival, $screen_unique_id, $unique_id_generated);
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
            "date",
            "festival",
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
                $value['date'] = disdate($value['date']);
                $btn_update = btn_update($folder_name, $value['unique_id']);
                $btn_delete = btn_delete($folder_name, $value['screen_unique_id']);

                $value['unique_id'] = $btn_update . ' ' . $btn_delete;
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

    case 'special_menu_sub_datatable':

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
            "(SELECT item_category FROM item_category WHERE item_category.unique_id = special_menu_chart_sub.category) AS category",
            "CASE 
                WHEN special_menu_chart_sub.item = 'C-V' THEN 'Common Veg' 
                ELSE (SELECT item FROM item WHERE item.unique_id = special_menu_chart_sub.item) 
                END AS item",
            "unit",
            "quantity",
            "unique_id"
        ];

        $sql_function = "SQL_CALC_FOUND_ROWS";

        $where = "is_delete = 0 AND screen_unique_id = ?";
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM special_menu_chart_sub WHERE $where";

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
        $date = $_POST['date'] ?? '';
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

        $check_exist = "SELECT COUNT(*) FROM special_menu_chart_sub WHERE date = ? AND item = ? AND is_delete = 0 AND unique_id != ?";
        $count = $mysqli->prepare($check_exist);
        $count->bind_param("sss", $date, $item, $sublist_unique_id);
        $count->execute();
        $count->bind_result($total);
        $count->fetch();
        $count->close();

        if ($total > 0) {
            $msg = 'already';
        } else {
            if ($sublist_unique_id) {
                // Update existing
                $sql = "UPDATE special_menu_chart_sub 
                SET date = ?, category = ?, item = ?, unit = ?, quantity = ? 
                WHERE unique_id = ? AND is_delete = 0";

                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("ssssss", $date, $category, $item, $unit, $quantity, $sublist_unique_id);

                if ($stmt->execute()) {
                    $msg = "update";
                } else {
                    $msg = "error";
                }
            } else {
                // Create new
                $new_id = unique_id(); // assuming this exists
                $sql = "INSERT INTO special_menu_chart_sub (unique_id, screen_unique_id, date, category, item, unit, quantity) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("sssssss", $new_id, $screen_unique_id, $date, $category, $item, $unit, $quantity);

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

        $sql = "UPDATE special_menu_chart_sub SET is_delete = 1 WHERE unique_id = ?";
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
        $sql = "SELECT * FROM special_menu_chart_sub WHERE unique_id = ?";
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

            $stmt = $mysqli->prepare("DELETE FROM special_menu_chart_sub WHERE screen_unique_id = ?");
            $stmt->bind_param("s", $screen_unique_id);
            $stmt->execute();
            $stmt->close();
        }
        exit; // no output, completely silent
        break;


    default:

        break;
}
