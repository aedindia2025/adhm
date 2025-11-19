<?php
// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "monthly_indent_master";
$table_sub = "monthly_indent_items";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';
// include 'function.php';


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

$hostel_id = $_SESSION['hostel_id'];

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
            "(SELECT hostel_id FROM hostel_name WHERE unique_id = $table.hostel_id) AS hostel_id",
            "(SELECT hostel_name FROM hostel_name WHERE unique_id = $table.hostel_id) AS hostel_name",
            "month_year",
            "total_items",
            "total_amount",
            "screen_unique_id",
            "(SELECT unique_id FROM hostel_name WHERE unique_id = $table.hostel_id) AS hostel_unique_id",
        ];
        $table_details = implode(", ", $columns);

        $where = "is_delete = 0";

        if ($hostel_id != '')
            $where .= " AND hostel_id = '$hostel_id'";
        if ($month_fill != '')
            $where .= " AND month_year = '$month_fill'";
// echo '$where';
        // ✅ Count total grouped records
        $count_sql = "SELECT COUNT(*) AS total 
                  FROM (SELECT 1 FROM $table WHERE $where) AS temp";
        $count_result = $mysqli->query($count_sql);
        $total_records = $count_result->fetch_assoc()['total'];

        // ✅ Main data query
        $sql = "SELECT $table_details FROM $table WHERE $where";
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

    header('Content-Type: application/json; charset=utf-8');
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $length = $_POST['length'];
    $start = $_POST['start'];
    $draw = $_POST['draw'];
    $limit = $length;

    $month_fill = $_POST["month_fill"];
    $hostel_unique_id = $_POST["hostel_unique_id"];

    $columns = [
        "'' as s_no",
        "(SELECT item FROM item WHERE item.unique_id = monthly_indent_items.item) AS item_name",
        "quantity",
        "'-' as price",
        "gst_amount",
        "total_price",
        "unit_price",
        "unit",
        "item"
    ];

    $table_details = implode(", ", $columns);

    $where = "hostel_id = '$hostel_unique_id' AND is_delete = 0";
    $params = [];
    $types = "";

    $sql = "SELECT SQL_CALC_FOUND_ROWS $table_details FROM $table_sub WHERE $where";

    if ($limit && $limit != '-1') {
        $sql .= " LIMIT ?, ?";
        $types .= "ii";
        $params[] = intval($start);
        $params[] = intval($limit);
    }

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
    $total_records = $total_records_result->fetch_assoc()['total'];

    $data = [];
    $sno = $start + 1;

    while ($row = $result->fetch_assoc()) {
        $row['s_no'] = $sno++;

        $quantity = floatval(str_replace(',', '', $row['quantity'] ?? 0));
        $unit_price = floatval(str_replace(',', '', $row['unit_price'] ?? 0));
        $row['price'] = round($quantity * $unit_price, 2);

        if (isset($row['item']) && $row['item'] == "C-V") {
            $row['item_name'] = 'Vegetables (Kg)';
        } else {
            $row['item_name'] = $row['item_name'] . ' (' . $row['unit'] . ')';
        }

        $data[] = array_values($row);
    }

    echo json_encode([
        "draw" => intval($draw),
        "recordsTotal" => intval($total_records),
        "recordsFiltered" => intval($total_records),
        "data" => $data
    ]);

    $stmt->close();
    exit;

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



?>