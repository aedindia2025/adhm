<?php
ob_start(); // Start output buffering at the very top
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../../assets/PHPExcel-1.8/Classes/PHPExcel.php");
require("../../config/dbconfig.php");

// Configure cache for large data
PHPExcel_Settings::setCacheStorageMethod(PHPExcel_CachedObjectStorageFactory::cache_to_discISAM);

// Database connection
$mysqli = new mysqli("localhost", "root", "4/rb5sO2s3TpL4gu", "adi_dravidar");
if ($mysqli->connect_errno) {
    die("DB connection failed: " . $mysqli->connect_error);
}

// Get filters
$table = "stock_inward";
$district_name = $_GET["district_name"] ?? '';
$taluk_name = $_GET["taluk_name"] ?? '';
$hostel_name = $_GET["hostel_name"] ?? '';
$month_fill = $_GET["month_fill"] ?? date('Y-m');

// Format month like
$month = date("F, Y", strtotime($month_fill));

// Prepare query
$columns = [
    "'' as s_no",
    "(SELECT hostel_name FROM hostel_name WHERE unique_id = $table.hostel_unique_id) AS hostel_name",
    "'-' as opening_stock",
    "'-' as in_qty",
    "'-' as out_qty",
    "'-' as closing_stock",
    "$table.unique_id",
    "$table.hostel_unique_id",
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

$group_by = "hostel_name";
$sql = "SELECT $table_details FROM $table WHERE $where GROUP BY $group_by";

$result = $mysqli->query($sql);
if (!$result) {
    die("Query Error: " . $mysqli->error);
}

// Create Excel sheet
$excel = new PHPExcel();
$sheet = $excel->setActiveSheetIndex(0);

// Title
$sheet->mergeCells('A1:G1');
$sheet->setCellValue('A1', "Current Stock Report - $month");
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

// Header Row
$headers = [
    'S.No',
    'Hostel Name',
    'Hostel ID',
    'Opening Stock (Kg)',
    'Inward Quantity (Kg)',
    'Outward Quantity (Kg)',
    'Closing Stock (Kg)'
];
$sheet->fromArray($headers, NULL, 'A3');
$sheet->getStyle('A3:G3')->getFont()->setBold(true);
$sheet->getStyle('A3:G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

// Fill Data
$rowIndex = 4;
$sno = 1;

while ($row = $result->fetch_assoc()) {
    $hostel_id = $row['hostel_unique_id'];
    if (empty($hostel_id))
        continue;

    // Compute stock values
    $opening_stock = opening_stock($hostel_id, $month_fill);
    $in_qty = get_in_qty($hostel_id, $month_fill);
    $out_qty = get_out_qty($hostel_id, $month_fill);
    $closing_stock = ($opening_stock + $in_qty) - $out_qty;

    // Write row
    $sheet->setCellValue("A$rowIndex", $sno++);
    $sheet->setCellValue("B$rowIndex", $row['hostel_name']);
    $sheet->setCellValue("C$rowIndex", $row['hostel_id']);
    $sheet->setCellValue("D$rowIndex", number_format((float)$opening_stock, 3, '.', ''));
    $sheet->setCellValue("E$rowIndex", number_format((float)$in_qty, 3, '.', ''));
    $sheet->setCellValue("F$rowIndex", number_format((float)$out_qty, 3, '.', ''));
    $sheet->setCellValue("G$rowIndex", number_format((float)$closing_stock, 3, '.', ''));

    $rowIndex++;
}

// Auto-size all columns
foreach (range('A', 'G') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Current_Stock_Report.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$objWriter->save('php://output');
exit;

// ===============================
//   STOCK CALCULATION FUNCTIONS
// ===============================

function get_in_qty($hostel_id, $month_val)
{
    global $pdo;

    $table_name = "stock_inward";
    $table_columns = ["sum(qty) as in_qty"];
    $table_details = [$table_name, $table_columns];
    $where = "hostel_unique_id = '" . $hostel_id . "' and date_format(entry_date,'%Y-%m') = '" . $month_val . "' and is_delete = 0";

    $result_values1 = $pdo->select($table_details, $where);

    if ($result_values1->status) {
        return $result_values1->data[0]['in_qty'];
    } else {
        return 0;
    }
}

function get_out_qty($hostel_id, $month_val)
{
    global $pdo;

    $table_name = "stock_outward";
    $table_columns = ["sum(qty) as out_qty"];
    $table_details = [$table_name, $table_columns];
    $where = "hostel_unique_id = '" . $hostel_id . "' and date_format(entry_date,'%Y-%m') = '" . $month_val . "' and is_delete = 0";

    $result_values1 = $pdo->select($table_details, $where);

    if ($result_values1->status) {
        return $result_values1->data[0]['out_qty'];
    } else {
        return 0;
    }
}

function opening_stock($hostel_id, $month_val)
{
    global $pdo;

    $table_name = "stock_inward";
    $table_columns = [
        "(select sum(qty) from stock_inward where is_delete = 0 and hostel_unique_id = '" . $hostel_id . "' and date_format(entry_date,'%Y-%m') < '" . $month_val . "') as in_qty",
        "(select sum(qty) from stock_outward where is_delete = 0 and hostel_unique_id = '" . $hostel_id . "' and date_format(entry_date,'%Y-%m') < '" . $month_val . "') as out_qty"
    ];
    $table_details = [$table_name, $table_columns];
    $where = "hostel_unique_id = '" . $hostel_id . "' and date_format(entry_date,'%Y-%m') < '" . $month_val . "' and is_delete = 0";

    $result_values1 = $pdo->select($table_details, $where);

    if ($result_values1->status) {
        $in_qty = $result_values1->data[0]['in_qty'];
        $out_qty = $result_values1->data[0]['out_qty'];
        return $in_qty - $out_qty;
    } else {
        return 0;
    }
}
?>
