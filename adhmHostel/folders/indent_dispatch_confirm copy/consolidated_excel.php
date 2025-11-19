<?php
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../../assets/PHPExcel-1.8/Classes/PHPExcel.php");
require("../../config/dbconfig.php");

// Configure cache
PHPExcel_Settings::setCacheStorageMethod(PHPExcel_CachedObjectStorageFactory::cache_to_discISAM);

// DB Connection
$mysqli = new mysqli("localhost", "root", "4/rb5sO2s3TpL4gu", "adi_dravidar");
if ($mysqli->connect_errno) {
    die("DB connection failed: " . $mysqli->connect_error);
}

$table = "stock_inward";
$district_name = $_GET["district_name"] ?? '';
$taluk_name = $_GET["taluk_name"] ?? '';
$hostel_name = $_GET["hostel_name"] ?? '';
$month_fill = $_GET["month_fill"] ?? date('Y-m');
$month = date("F, Y", strtotime($month_fill));

// 🧾 Get all hostels
$where = "is_delete = 0";
if ($district_name != '') $where .= " AND district_unique_id = '$district_name'";
if ($taluk_name != '') $where .= " AND taluk_unique_id = '$taluk_name'";
if ($hostel_name != '') $where .= " AND hostel_unique_id = '$hostel_name'";

$sql = "SELECT hostel_unique_id, 
        (SELECT hostel_name FROM hostel_name WHERE unique_id = s.hostel_unique_id) AS hostel_name, 
        (SELECT hostel_id FROM hostel_name WHERE unique_id = s.hostel_unique_id) AS hostel_id
        FROM $table s WHERE $where GROUP BY hostel_unique_id";
$hostel_result = $mysqli->query($sql);
if (!$hostel_result) die("Query Error: " . $mysqli->error);

// Create Excel
$excel = new PHPExcel();
$sheet = $excel->setActiveSheetIndex(0);

// 🏷️ Report Title
$sheet->mergeCells('A1:F1');
$sheet->setCellValue('A1', "Consolidated Stock Report - $month");
$sheet->getStyle('A1')->applyFromArray([
    'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '1F4E78']],
    'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER],
    'fill' => [
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => ['rgb' => 'D9E1F2']
    ]
]);

$rowIndex = 3;

while ($hostel = $hostel_result->fetch_assoc()) {
    $hostel_id = $hostel['hostel_unique_id'];
    $hostel_name_val = $hostel['hostel_name'];
    $hostel_code = $hostel['hostel_id'];
    if ($hostel_id == "") continue;

    // 🏫 Hostel Header
    $sheet->mergeCells("A{$rowIndex}:F{$rowIndex}");
    $sheet->setCellValue("A{$rowIndex}", strtoupper("$hostel_name_val - $hostel_code"));
    $sheet->getStyle("A{$rowIndex}")->applyFromArray([
        'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '000000']],
        'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER],
        'fill' => [
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => ['rgb' => 'FFF2CC']
        ]
    ]);

    $rowIndex++;

    // 🧱 Table Headers
    $headers = ['S.No', 'Item Name', 'Opening Stock (Kg)', 'Inward Quantity (Kg)', 'Outward Quantity (Kg)', 'Closing Stock (Kg)'];
    $sheet->fromArray($headers, NULL, "A{$rowIndex}");
    $sheet->getStyle("A{$rowIndex}:F{$rowIndex}")->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => '1F4E78']],
        'fill' => [
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => ['rgb' => 'D9E1F2']
        ],
        'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER]
    ]);

    $rowIndex++;

    // 📦 Fetch Items
    $item_sql = "SELECT DISTINCT item_name, (SELECT item FROM item WHERE unique_id = stock_inward.item_name) AS item_org_name 
                FROM stock_inward WHERE hostel_unique_id = '$hostel_id' AND is_delete = 0";
    $item_result = $mysqli->query($item_sql);
    if (!$item_result) die("Item Query Error: " . $mysqli->error);

    $sno = 1;
    $totalClosing = 0;

    while ($item = $item_result->fetch_assoc()) {
        $item_name = $item['item_name'];
        $item_org_name = $item['item_org_name'];
        if ($item_name == "") continue;

        // Compute stock
        $opening_stock = opening_stock_item($item_name, $hostel_id, $month_fill);
        $in_qty = get_in_qty_item($item_name, $hostel_id, $month_fill);
        $out_qty = get_out_qty_item($item_name, $hostel_id, $month_fill);
        $closing_stock = ($opening_stock + $in_qty) - $out_qty;
        $totalClosing += $closing_stock;

        $sheet->setCellValue("A{$rowIndex}", $sno++);
        $sheet->setCellValue("B{$rowIndex}", $item_org_name);
        $sheet->setCellValue("C{$rowIndex}", number_format((float) $opening_stock, 3, '.', ''));
        $sheet->setCellValue("D{$rowIndex}", number_format((float) $in_qty, 3, '.', ''));
        $sheet->setCellValue("E{$rowIndex}", number_format((float) $out_qty, 3, '.', ''));
        $sheet->setCellValue("F{$rowIndex}", number_format((float) $closing_stock, 3, '.', ''));

        // Apply zebra stripes
        $fillColor = ($rowIndex % 2 == 0) ? 'FFFFFF' : 'F2F2F2';
        $sheet->getStyle("A{$rowIndex}:F{$rowIndex}")->applyFromArray([
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => ['rgb' => $fillColor]
            ],
            'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT]
        ]);

        $sheet->getStyle("B{$rowIndex}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $rowIndex++;
    }

    $rowIndex += 2;
}

// Auto-size
foreach (range('A', 'F') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Page setup
$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$sheet->getHeaderFooter()->setOddFooter('&LGenerated on &D &RPage &P of &N');

// Output file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Consolidated_Stock_Report.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$objWriter->save('php://output');
exit;

// ===================================================
// FUNCTIONS
// ===================================================
function get_in_qty_item($item_name, $hostel_id, $month_val) {
    global $pdo;
    $table_name = "stock_inward";
    $cols = ["sum(qty) as in_qty"];
    $details = [$table_name, $cols];
    $where = "hostel_unique_id='$hostel_id' and item_name='$item_name' and date_format(entry_date,'%Y-%m')='$month_val' and is_delete=0";
    $res = $pdo->select($details, $where);
    return $res->status ? $res->data[0]['in_qty'] : 0;
}
function get_out_qty_item($item_name, $hostel_id, $month_val) {
    global $pdo;
    $table_name = "stock_outward";
    $cols = ["sum(qty) as out_qty"];
    $details = [$table_name, $cols];
    $where = "hostel_unique_id='$hostel_id' and item_name='$item_name' and date_format(entry_date,'%Y-%m')='$month_val' and is_delete=0";
    $res = $pdo->select($details, $where);
    return $res->status ? $res->data[0]['out_qty'] : 0;
}
function opening_stock_item($item_name, $hostel_id, $month_val) {
    global $pdo;
    $cols = [
        "(select sum(qty) from stock_inward where is_delete=0 and hostel_unique_id='$hostel_id' and item_name='$item_name' and date_format(entry_date,'%Y-%m')<'$month_val') as in_qty",
        "(select sum(qty) from stock_outward where is_delete=0 and hostel_unique_id='$hostel_id' and item_name='$item_name' and date_format(entry_date,'%Y-%m')<'$month_val') as out_qty"
    ];
    $details = ["stock_inward", $cols];
    $where = "hostel_unique_id='$hostel_id' and is_delete=0";
    $res = $pdo->select($details, $where);
    if ($res->status) {
        $in = $res->data[0]['in_qty'];
        $out = $res->data[0]['out_qty'];
        return ($in - $out);
    }
    return 0;
}
?>
