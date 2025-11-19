<?php
ob_end_clean(); // clear any existing output
ob_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../../assets/PHPExcel-1.8/Classes/PHPExcel.php");
require("../../config/dbconfig.php");

// Database
$mysqli = new mysqli("localhost", "root", "4/rb5sO2s3TpL4gu", "adi_dravidar");
if ($mysqli->connect_errno) {
    die("DB connection failed: " . $mysqli->connect_error);
}

// Filters
$district_name = $_GET["district_name"] ?? '';
$taluk_name = $_GET["taluk_name"] ?? '';
$hostel_name = $_GET["hostel_name"] ?? '';

// Create Excel object
$excel = new PHPExcel();
$sheet = $excel->setActiveSheetIndex(0);

// ----------------------------
// SECTION 1: DADWO CREDENTIALS
// ----------------------------
$rowIndex = 1;

// Title with coloring
$sheet->mergeCells("A{$rowIndex}:D{$rowIndex}");
$sheet->setCellValue("A{$rowIndex}", "DADWO Credential Report");
$sheet->getStyle("A{$rowIndex}")->getFont()->setBold(true)->setSize(14);
$sheet->getStyle("A{$rowIndex}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle("A{$rowIndex}")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('366092');
$sheet->getStyle("A{$rowIndex}")->getFont()->getColor()->setRGB('FFFFFF');
$rowIndex += 2;

// Headers with coloring
$headers1 = ['S.No', 'District', 'User ID', 'Password'];
$sheet->fromArray($headers1, NULL, "A{$rowIndex}");
$sheet->getStyle("A{$rowIndex}:D{$rowIndex}")->getFont()->setBold(true);
$sheet->getStyle("A{$rowIndex}:D{$rowIndex}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle("A{$rowIndex}:D{$rowIndex}")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('4F81BD');
$sheet->getStyle("A{$rowIndex}:D{$rowIndex}")->getFont()->getColor()->setRGB('FFFFFF');
$rowIndex++;

// Query
$columns_dadwo = [
    "'' as s_no",
    "(SELECT district_name FROM district_name WHERE unique_id = district_office) AS district_name",
    "user_name",
    "password"
];
$sql_dadwo = "
    SELECT " . implode(", ", $columns_dadwo) . "
    FROM staff_registration
    WHERE is_delete = 0 AND designation = '65f31975f0ce678724'
";
if ($district_name != '')
    $sql_dadwo .= " AND district_office = '$district_name'";

$result1 = $mysqli->query($sql_dadwo);
if (!$result1)
    die("DADWO Query Error: " . $mysqli->error);

// Fill data with alternating row colors
$sno = 1;
$dadwoStartRow = $rowIndex;
while ($row = $result1->fetch_assoc()) {
    $sheet->setCellValue("A{$rowIndex}", $sno++);
    $sheet->setCellValue("B{$rowIndex}", $row['district_name']);
    $sheet->setCellValue("C{$rowIndex}", $row['user_name']);
    $sheet->setCellValue("D{$rowIndex}", $row['password']);
    
    // Alternating row colors
    $fillColor = ($rowIndex % 2 == 0) ? 'FFFFFF' : 'F2F2F2';
    $sheet->getStyle("A{$rowIndex}:D{$rowIndex}")->getFill()
          ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
          ->getStartColor()->setRGB($fillColor);
    
    $rowIndex++;
}
$dadwoEndRow = $rowIndex - 1;
$rowIndex += 2; // Leave space before next section

// ----------------------------
// SECTION 2: WARDEN CREDENTIALS
// ----------------------------

// Title with coloring
$sheet->mergeCells("A{$rowIndex}:G{$rowIndex}");
$sheet->setCellValue("A{$rowIndex}", "Warden Credential Report");
$sheet->getStyle("A{$rowIndex}")->getFont()->setBold(true)->setSize(14);
$sheet->getStyle("A{$rowIndex}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle("A{$rowIndex}")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('366092');
$sheet->getStyle("A{$rowIndex}")->getFont()->getColor()->setRGB('FFFFFF');
$rowIndex += 2;

// Headers with coloring - Set individually instead of using fromArray
$sheet->setCellValue("A{$rowIndex}", 'S.No');
$sheet->setCellValue("B{$rowIndex}", 'District');
$sheet->setCellValue("C{$rowIndex}", 'Taluk');

$sheet->setCellValue("D{$rowIndex}", 'Hostel ID');
$sheet->setCellValue("E{$rowIndex}", 'Hostel');

$sheet->setCellValue("F{$rowIndex}", 'User ID');
$sheet->setCellValue("G{$rowIndex}", 'Password');

$sheet->getStyle("A{$rowIndex}:G{$rowIndex}")->getFont()->setBold(true);
$sheet->getStyle("A{$rowIndex}:G{$rowIndex}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle("A{$rowIndex}:G{$rowIndex}")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('4F81BD');
$sheet->getStyle("A{$rowIndex}:G{$rowIndex}")->getFont()->getColor()->setRGB('FFFFFF');
$rowIndex++;

// Query
$columns_warden = [
    "'' as s_no",
    "(SELECT district_name FROM district_name WHERE unique_id = s.district_office) AS district_name",
    "(SELECT taluk_name FROM taluk_creation WHERE unique_id = s.taluk_office) AS taluk_name",
    "(SELECT hostel_id FROM hostel_name WHERE unique_id = s.hostel_name) AS hostel_id",
    "(SELECT hostel_name FROM hostel_name WHERE unique_id = s.hostel_name) AS hostel_name",
    "user_name",
    "password"
];
$sql_warden = "
    SELECT " . implode(", ", $columns_warden) . "
    FROM staff_registration s
    WHERE s.is_delete = 0 AND s.designation = '65f3191aa725518258'
";
if ($district_name != '')
    $sql_warden .= " AND s.district_office = '$district_name'";
if ($taluk_name != '')
    $sql_warden .= " AND s.taluk_office = '$taluk_name'";
if ($hostel_name != '')
    $sql_warden .= " AND s.hostel_name = '$hostel_name'";

$result2 = $mysqli->query($sql_warden);
if (!$result2)
    die("Warden Query Error: " . $mysqli->error);

// Fill data with merged Hostel cells and alternating colors
$sno = 1;
$wardenStartRow = $rowIndex;
while ($row = $result2->fetch_assoc()) {
    $sheet->setCellValue("A{$rowIndex}", $sno++);
    $sheet->setCellValue("B{$rowIndex}", $row['district_name']);
    $sheet->setCellValue("C{$rowIndex}", $row['taluk_name']);
    $sheet->setCellValue("D{$rowIndex}", $row['hostel_id']);
    
    $sheet->setCellValue("E{$rowIndex}", $row['hostel_name']);
    
    $sheet->setCellValue("F{$rowIndex}", $row['user_name']);
    $sheet->setCellValue("G{$rowIndex}", $row['password']);
    
    // Apply text wrapping and alignment for merged Hostel cells
    $sheet->getStyle("E{$rowIndex}")->getAlignment()->setWrapText(true);
    $sheet->getStyle("E{$rowIndex}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle("E{$rowIndex}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    
    // Alternating row colors for warden data
    $fillColor = ($rowIndex % 2 == 0) ? 'FFFFFF' : 'F2F2F2';
    $sheet->getStyle("A{$rowIndex}:G{$rowIndex}")->getFill()
          ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
          ->getStartColor()->setRGB($fillColor);
    
    $rowIndex++;
}
$wardenEndRow = $rowIndex - 1;

// Set manual column widths for better formatting
$sheet->getColumnDimension('A')->setWidth(8);   // S.No
$sheet->getColumnDimension('B')->setWidth(20);  // District
$sheet->getColumnDimension('C')->setWidth(20);  // Taluk
$sheet->getColumnDimension('D')->setWidth(20);  // Hostel ID
$sheet->getColumnDimension('E')->setWidth(80); // Hostel Name
$sheet->getColumnDimension('F')->setWidth(25);  // User ID
$sheet->getColumnDimension('G')->setWidth(25);  // Password

// ----------------------------
// OUTPUT EXCEL FILE
// ----------------------------
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="credentials_report.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$objWriter->save('php://output');

ob_end_flush();
exit;
?>