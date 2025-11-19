<?php
// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "indent_dispatch_confirm";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

$action = $_POST["action"];

$feedback_type = "";
$is_active = "";
$unique_id = "";
$prefix = "";

$data = "";
$msg = "";
$error = "";
$status = "";
$test = ""; // For Developer Testing Purpose

$hostel_id = $_SESSION['hostel_id'];
$academic_year = $_SESSION['academic_year'];


switch ($action) {

    case 'datatable':

        // DataTable Variables
        header('Content-Type: application/json; charset=utf-8');

        $length = intval($_POST['length']);
        $start = intval($_POST['start']);
        $draw = intval($_POST['draw']);

        $hostel_name = $_POST["hostel_name"];
        $month_fill = $_POST["month_fill"];

        $month_val = date("F/Y", strtotime($month_fill));

        $date = new DateTime($month_fill);
        $date->modify('-1 month');
        $dec_date = $date->format('Y-m');

        $data = [];

        // Define your column selections
        $columns = [
            "'' AS s_no",
            "entry_date",
            "(SELECT hostel_id FROM hostel_name WHERE unique_id = $table.hostel_id) AS hostel_id",
            "(SELECT hostel_name FROM hostel_name WHERE unique_id = $table.hostel_id) AS hostel_name",
            "month_year",
            "'' AS screen_unique_id",
            "(SELECT unique_id FROM hostel_name WHERE unique_id = $table.hostel_id) AS hostel_unique_id",
        ];
        $table_details = implode(", ", $columns);

        $where = "1 = 1";

        if ($hostel_id != '')
            $where .= " AND hostel_id = '$hostel_id'";
        if ($month_fill != '')
            $where .= " AND month_year = '$month_fill'";

        $groupby = "hostel_id, month_year";

        // ✅ Count total grouped records
        $count_sql = "SELECT COUNT(*) AS total 
                  FROM (SELECT 1 FROM $table WHERE $where) AS temp";
        $count_result = $mysqli->query($count_sql);
        $total_records = $count_result->fetch_assoc()['total'];

        // ✅ Main data query
        $sql = "SELECT $table_details FROM $table WHERE $where GROUP BY $groupby";
        if ($length != -1) {
            $sql .= " LIMIT ?, ?";
        }
        // echo $sql;
        $stmt = $mysqli->prepare($sql);
        if ($length != -1) {
            $stmt->bind_param("ii", $start, $length);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $sno = $start + 1;

        while ($value = $result->fetch_assoc()) {
            $hostel_id = $value['hostel_id'];
            if ($hostel_id == "")
                continue;

            $value['s_no'] = $sno++;

            $value['entry_date'] = date('d-m-Y', strtotime($value['entry_date']));

            $month = formatMonthYear($value['month_year']);
            $value['month_year'] = formatMonthYear($value['month_year']);

            $btn_view = '<a href="#" class="openPopup" title="View" 
                data-id="' . $value['screen_unique_id'] . '"
                data-hostel-name="' . $value['hostel_name'] . '" 
                data-hostel-id="' . $value['hostel_id'] . '" 
                data-hostel-unique-id="' . $value['hostel_unique_id'] . '" 
                data-month="' . $month . '">
                <i class="fa fa-eye" style="font-size: 0.98em;"></i>
            </a>';

            $value['screen_unique_id'] = $btn_view;

            $data[] = array_values($value);
        }

        $json_array = [
            "draw" => intval($draw),
            "recordsTotal" => intval($total_records),
            "recordsFiltered" => intval($total_records),
            "data" => $data
        ];

        echo json_encode($json_array);

        $stmt->close();
        $mysqli->close();

        break;

    case 'get_taluk':
        $district_name = $_POST['district_name'];


        $district_name_options = taluk_name('', $district_name);

        $taluk_name_options = select_option($district_name_options, "Select Taluk");

        echo $taluk_name_options;

        break;

    case 'get_hostel':

        $taluk_name = $_POST['taluk_name'];

        $hostel_name_options = hostel_name('', $taluk_name);

        $hostel_name_options = select_option($hostel_name_options, "Select Hostel");

        echo $hostel_name_options;

        break;

    case 'item_details':

        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = intval($length);

        $month_fill = $_POST["month_fill"];
        $hostel_unique_id = $_POST["hostel_unique_id"];

        // ✅ Define main table
        $table = "indent_dispatch_confirm";

        // ✅ Select columns with JOIN to get item name
        $sql = "
                SELECT SQL_CALC_FOUND_ROWS
                    '' AS s_no,
                    i.item AS item_name,
                    idc.dispatch_qty,
                    idc.unit,
                    idc.item AS item_id,
                    idc.unique_id,
                    idc.received_status,
                    idc.received_qty,
                    idc.remarks,
                    idc.status,
                    idc.entry_date,
                    idc.category
                FROM $table AS idc
                LEFT JOIN item AS i ON i.unique_id = idc.item
                WHERE idc.hostel_id = ?
                LIMIT ?, ?
            ";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sii", $hostel_unique_id, $start, $limit);
        $stmt->execute();
        $result = $stmt->get_result();

        $total_records_result = $mysqli->query("SELECT FOUND_ROWS() AS total");
        $total_records = $total_records_result->fetch_assoc()['total'];

        $data = [];
        $sno = $start + 1;

        while ($row = $result->fetch_assoc()) {
            $row['s_no'] = $sno++;
            $data[] = $row;
        }

        echo json_encode([
            "draw" => intval($draw),
            "recordsTotal" => intval($total_records),
            "recordsFiltered" => intval($total_records),
            "data" => $data
        ]);

        $stmt->close();
        break;

    case 'update_dispatch_status':

        $unique_id = $_POST['unique_id'] ?? '';
        $item_id = $_POST['item_id'] ?? '';
        $status = $_POST['status'] ?? '';
        $received_qty = $_POST['received_qty'] ?? '';
        $remarks = $_POST['remarks'] ?? '';
        $month_fill = $_POST['month_fill'] ?? '';
        $screen_unique_id = $_POST['screen_unique_id'] ?? '';
        $hostel_unique_id = $_POST['hostel_unique_id'] ?? '';
        $stock_id = $_POST['stock_id'] ?? '';

        $hostel_id = $_SESSION['hostel_id'];
        $district_id = $_SESSION['district_id'];
        $taluk_id = $_SESSION['taluk_id'];
        $academic_year = $_SESSION['academic_year'];

        $screen_id = $screen_unique_id;
        $entry_date = date('Y-m-d');
        $bill_no = "";
        $category_name = $_POST['category'];
        $qty = $received_qty;
        $unit = $_POST['unit'] ?? '';
        $rate = '';
        $amount = '';
        $is_active = 1;
        $is_delete = 0;

        // Check required fields
        if (!$item_id || !$status || !$hostel_unique_id || !$month_fill) {
            echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
            exit;
        }
        // ---------- UPDATE QUERY ----------
        $sql_update = "UPDATE indent_dispatch_confirm 
        SET received_status = ?, 
            received_qty = ?, 
            remarks = ?, 
            confirmation_date = ?, 
            status = '1'
        WHERE unique_id = ?";

        $stmt1 = $mysqli->prepare($sql_update);

        if (!$stmt1) {
            echo json_encode(['status' => 'error', 'message' => 'Prepare failed (Update): ' . $mysqli->error]);
            exit;
        }

        $stmt1->bind_param(
            "sdsss",
            $status,
            $received_qty,
            $remarks,
            date('Y-m-d'),
            $unique_id
        );

        if (!$stmt1->execute()) {
            echo json_encode(["status" => "error", "message" => $stmt1->error]);
            exit;
        }

        $stmt1->close();

        // ❗ IF NOT RECEIVED & APPROVED → RETURN SUCCESS IMMEDIATELY
        if ($status !== "Received & Approved") {
            echo json_encode(["status" => "success"]);
            exit;  // Stop execution of rest of the case
        }

        $stmt5 = $mysqli->prepare("SELECT 1 FROM stock_entry WHERE screen_unique_id = ?");
        $stmt5->bind_param("s", $screen_unique_id);
        $stmt5->execute();
        $stmt5->store_result();
        $num_rows = $stmt5->num_rows; // Store the result count
        $stmt5->close(); // Close the statement after getting results

        $new_unique_id = unique_id();

        if ($num_rows == 0) {

            // Insert in to stock entry main table
            $sql4 = "INSERT INTO stock_entry 
                        (supplier_name, address, entry_date, bill_no, hostel_name, stock_id, 
                         net_total_amount, academic_year, district, taluk, tot_qty, tot_amount, purchase_item, veg_item, 
                         fssai_no, file_name, file_org_name, unique_id, screen_unique_id)
                        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt4 = $mysqli->prepare($sql4);
            if (!$stmt4)
                throw new Exception($mysqli->error);
            $stmt4->bind_param(
                "sssssssssssssssssss",
                // sss
                $supplier_names,
                $supp_address,
                $entry_date,
                $bill_no,
                $hostel_id,
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
                $screen_unique_id,

            );
            $stmt4->execute();
            $stmt4->close();
        }

        // Insert query stock entry sub table
        $sql = "INSERT INTO stock_entry_sub (category_name, item_name, qty, unit, rate, amount, stock_id, screen_unique_id, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt2 = $mysqli->prepare($sql);

        $stmt2->bind_param("sssssssss", $category_name, $item_id, $qty, $unit, $rate, $amount, $stock_id, $screen_unique_id, unique_id($prefix));
        $stmt2->execute();
        $stmt2->close();

        // INSERT QUERY stock inward table
        $sql_insert = "INSERT INTO stock_inward
                    (
                        unique_id, 
                        screen_unique_id,
                        stock_id,
                        academic_year,
                        district_unique_id,
                        taluk_unique_id,
                        hostel_unique_id,
                        entry_date,
                        bill_no,
                        category_name,
                        item_name,
                        qty,
                        unit,
                        rate,
                        amount,
                        is_active,
                        is_delete,
                        acc_year,
                        session_id,
                        sess_user_type,
                        sess_user_id
                    )
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt3 = $mysqli->prepare($sql_insert);
        if (!$stmt3) {
            echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $mysqli->error]);
            exit;
        }

        $new_unique_id = unique_id();

        // session details
        $session_id = $_SESSION['sess_company_id'] ?? "";
        $sess_user_type = $_SESSION['sess_user_type'] ?? "";
        $sess_user_id = $_SESSION['user_id'] ?? "";

        $stmt3->bind_param(
            "sssssssssssssssiiisss",
            $new_unique_id,
            $screen_id,
            $stock_id,
            $academic_year,
            $district_id,
            $taluk_id,
            $hostel_id,
            $entry_date,
            $bill_no,
            $category_name,
            $item_id,
            $qty,
            $unit,
            $rate,
            $amount,
            $is_active,
            $is_delete,
            $acc_year,
            $session_id,
            $sess_user_type,
            $sess_user_id
        );

        if ($stmt3->execute()) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => $stmt3->error]);
        }

        $stmt3->close();

        break;


    case 'fetch_pdf_data':

        $month = reverseMonthYear($_POST['month']);

        // 🧩 Fetch all items for this hostel + month
        $sql = "
        SELECT 
            (SELECT item FROM item WHERE item.unique_id = idc.item) AS item_name,
            idc.dispatch_qty,
            idc.unit,
            idc.received_status,
            idc.received_qty,
            idc.remarks,
            idc.status
        FROM indent_dispatch_confirm AS idc
        WHERE idc.hostel_id = ? AND idc.month_year = ?
    ";

        $stmt = $mysqli->prepare($sql);

        $stmt->bind_param("ss", $hostel_id, $month);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        echo json_encode($data);
        $stmt->close();
        break;


    default:

        break;
}

function formatMonthYear($dateStr)
{
    // Create a DateTime object from the input string
    $date = DateTime::createFromFormat('Y-m', $dateStr);

    // Check if valid
    if ($date === false) {
        return "Invalid date format";
    }

    // Return formatted output like "October, 2025"
    return $date->format('F, Y');
}

function reverseMonthYear($formattedStr)
{
    // Trim and create a DateTime object
    $date = DateTime::createFromFormat('F, Y', trim($formattedStr));

    // Validate the format
    if ($date === false) {
        return "Invalid date format";
    }

    // Return as "YYYY-MM"
    return $date->format('Y-m');
}

?>