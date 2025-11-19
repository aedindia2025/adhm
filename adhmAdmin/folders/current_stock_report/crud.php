<?php
// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "stock_inward";

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

    case 'datatable':

        // DataTable Variables
        $length = intval($_POST['length']);
        $start = intval($_POST['start']);
        $draw = intval($_POST['draw']);

        $district_name = $_POST["district_name"];
        $taluk_name = $_POST["taluk_name"];
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
            "(SELECT hostel_name FROM hostel_name WHERE unique_id = $table.hostel_unique_id) AS hostel_name",
            "'-' AS opening_stock",
            "'-' AS in_qty",
            "'-' AS out_qty",
            "'-' AS closing_stock",
            "unique_id",
            "hostel_unique_id",
            "unit",
            "(SELECT hostel_id FROM hostel_name WHERE unique_id = $table.hostel_unique_id) AS hostel_id"
        ];
        $table_details = implode(", ", $columns);

        $where = "is_delete = 0";
        if ($district_name != '')
            $where .= " AND district_unique_id = '$district_name'";
        if ($taluk_name != '')
            $where .= " AND taluk_unique_id = '$taluk_name'";
        if ($hostel_name != '')
            $where .= " AND hostel_unique_id = '$hostel_name'";

        $group_by = "hostel_unique_id";

        // ✅ Count total grouped records
        $count_sql = "SELECT COUNT(*) AS total 
                  FROM (SELECT 1 FROM $table WHERE $where GROUP BY $group_by) AS temp";
        $count_result = $mysqli->query($count_sql);
        $total_records = $count_result->fetch_assoc()['total'];

        // ✅ Main data query
        $sql = "SELECT $table_details FROM $table WHERE $where GROUP BY $group_by";
        if ($length != -1) {
            $sql .= " LIMIT ?, ?";
        }

        $stmt = $mysqli->prepare($sql);
        if ($length != -1) {
            $stmt->bind_param("ii", $start, $length);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $sno = $start + 1;

        while ($value = $result->fetch_assoc()) {
            $hostel_id = $value['hostel_unique_id'];
            if ($hostel_id == "")
                continue;

            $value['s_no'] = $sno++;

            $value['opening_stock'] = opening_stock($hostel_id, $month_fill);

            $in_qty = get_in_qty($hostel_id, $month_fill);
            $out_qty = get_out_qty($hostel_id, $month_fill);

            $value['in_qty'] = $in_qty ?: 0;
            $value['out_qty'] = number_format((float) $out_qty, 3, '.', '') ?: 0;

            $closing_stock = ($value['opening_stock'] + $in_qty) - $out_qty;
            $value['closing_stock'] = number_format((float) $closing_stock, 3, '.', '');

            $month = formatMonthYear($month_fill);

            $btn_view = '<a href="#" class="openPopup" title="View" 
            data-id="' . $value['hostel_unique_id'] . '"
            data-hostel-id="' . $value['hostel_id'] . '"
            data-hostel-name="' . $value['hostel_name'] . '" 
            data-month="' . $month . '">
            <i class="fa fa-eye" style="font-size: 0.98em;"></i>
        </a>';

            $value['unique_id'] = $btn_view;

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

        // Database connection - Assuming $mysqli is your connection object

        // DataTable Variables
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;

        $data = [];

        $district_name = $_POST["district_name"];
        $taluk_name = $_POST["taluk_name"];
        $hostel_name = $_POST["hostel_name"];
        $hostel_id = $_POST["hostel_id"];
        $month_fill = $_POST["month_fill"];

        $month_val = date("F/Y", strtotime($month_fill));

        $date = new DateTime($month_fill);
        $date->modify('-1 month');
        $dec_date = $date->format('Y-m');

        if ($length == '-1') {
            $limit = "";
        }

        // Query Variables
        $json_array = "";
        $columns = [
            "'' as s_no",
            "item_name",
            "'-' as opening_stock",
            "'-' as in_qty",
            "'-' as out_qty",
            "'-' as closing_stock",
            "hostel_unique_id",
            "unit",
        ];
        $table_details = implode(", ", $columns);
        // Ensure $table is defined (e.g., $table = 'your_stock_table')
        $where = "hostel_unique_id = ? AND is_delete = 0";
        $order_by = "";
        $group_by = "item_name";


        if ($district_name != '') {
            $where .= " AND district_unique_id = '$district_name'";
        }
        if ($taluk_name != '') {
            $where .= " AND taluk_unique_id = '$taluk_name'";
        }
        if ($hostel_name != '') {
            $where .= " AND hostel_unique_id = '$hostel_name'";
        }

        $sql = "SELECT SQL_CALC_FOUND_ROWS $table_details FROM $table WHERE $where GROUP BY $group_by";
        if ($limit) {
            $sql .= " LIMIT ?, ?";
        }

        $stmt = $mysqli->prepare($sql);

        if ($limit) {
            $stmt->bind_param("sii", $hostel_id, $start, $limit);
        } else {
            $stmt->bind_param("s", $hostel_id);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
        $total_records = $total_records_result->fetch_assoc()['total'];

        if ($result) {
            $res_array = $result->fetch_all(MYSQLI_ASSOC);
            $sno = $start + 1;
            foreach ($res_array as $key => $value) {
                $value['s_no'] = $sno++;
                $item_name = $value['item_name'];
                $hostel_id = $value['hostel_unique_id'];

                $value['item_name'] = disname(item_stock_inward($value['item_name'])[0]['item']) . '  (' . $value['unit'] . ')';
                $value['opening_stock'] = opening_stock_item($item_name, $hostel_id, $month_fill);
                $in_qty = get_in_qty_item($item_name, $hostel_id, $month_fill);
                $out_qty = get_out_qty_item($item_name, $hostel_id, $month_fill);
                $value['in_qty'] = $in_qty ?: 0;
                $value['out_qty'] = number_format((float) $out_qty, 3, '.', '') ?: 0;
                $closing_stock = ($value['opening_stock'] + $in_qty) - $out_qty;
                $value['closing_stock'] = number_format((float) $closing_stock, 3, '.', '');

                $btn_update = btn_update($folder_name, $value['unique_id']);
                $btn_delete = btn_delete($folder_name, $value['unique_id']);

                $value['unique_id'] = $btn_update . $btn_delete;
                $data[] = array_values($value);
            }

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data,
                "testing" => $result->sqlstate
            ];
        } else {
            print_r($result);
        }

        echo json_encode($json_array);

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



?>