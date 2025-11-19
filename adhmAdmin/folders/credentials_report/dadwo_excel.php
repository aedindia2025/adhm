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
$table = "staff_registration";
$district_name = $_GET["district_name"] ?? '';
$designation = $_GET["designation"];

// Prepare query
$columns = [
    "'' as s_no",
    "(SELECT district_name FROM district_name WHERE unique_id = $table.district_office) AS district_name",
    "user_name",
    "password"
];
$table_details = implode(", ", $columns);

$where = "is_delete = 0 AND designation = '$designation'";

if ($district_name != '')
    $where .= " AND district_office = '$district_name'";
if ($taluk_name != '')
    $where .= " AND taluk_office = '$taluk_name'";
if ($hostel_name != '')
    $where .= " AND hostel_name = '$hostel_name'";

$sql = "SELECT $table_details FROM $table WHERE $where";

$result = $mysqli->query($sql);
if (!$result) {
    die("Query Error: " . $mysqli->error);
}

// Create Excel sheet
$excel = new PHPExcel();
$sheet = $excel->setActiveSheetIndex(0);

// Set document properties
$excel->getProperties()
      ->setCreator("DADWO System")
      ->setLastModifiedBy("DADWO System")
      ->setTitle("DADWO Credential Report")
      ->setSubject("Staff Credentials")
      ->setDescription("Professional report of DADWO staff credentials")
      ->setKeywords("dadwo credentials staff")
      ->setCategory("Report");

// Title with professional styling
$sheet->mergeCells('A1:D1');
$sheet->mergeCells('A2:D2');
$sheet->setCellValue('A1', "DADWO CREDENTIAL REPORT");
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('366092');
$sheet->getStyle('A1')->getFont()->getColor()->setRGB('FFFFFF');

// Header Row with professional styling
$headers = [
    'S.No',
    'District',
    'User ID',
    'Password'
];
$sheet->fromArray($headers, NULL, 'A3');
$sheet->getStyle('A3:D3')->getFont()->setBold(true)->setSize(11);
$sheet->getStyle('A3:D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A3:D3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('4F81BD');
$sheet->getStyle('A3:D3')->getFont()->getColor()->setRGB('FFFFFF');
$sheet->getStyle('A3:D3')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

// Fill Data with alternating row colors
$rowIndex = 4;
$sno = 1;

while ($row = $result->fetch_assoc()) {
    // Write row
    $sheet->setCellValue("A$rowIndex", $sno++);
    $sheet->setCellValue("B$rowIndex", $row['district_name']);
    $sheet->setCellValue("C$rowIndex", $row['user_name']);
    $sheet->setCellValue("D$rowIndex", $row['password']);
    
    // Apply alternating row colors
    $fillColor = ($rowIndex % 2 == 0) ? 'FFFFFF' : 'F2F2F2';
    $sheet->getStyle("A$rowIndex:D$rowIndex")->getFill()
          ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
          ->getStartColor()->setRGB($fillColor);
    
    // Add borders to data rows
    $sheet->getStyle("A$rowIndex:D$rowIndex")->getBorders();
        //   ->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    
    // Center align S.No column
    $sheet->getStyle("A$rowIndex")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    
    $rowIndex++;
}

// Set professional column widths as requested
$sheet->getColumnDimension('A')->setWidth(8);   // S.No
$sheet->getColumnDimension('B')->setAutoSize(true); // District - auto size
$sheet->getColumnDimension('C')->setWidth(15);  // User ID
$sheet->getColumnDimension('D')->setWidth(25);  // Password

// Set print settings for professional output
$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$sheet->getPageSetup()->setFitToWidth(1);
$sheet->getPageSetup()->setFitToHeight(0);

// Set headers and output
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="dadwo_credential_report.xlsx"');
header('Cache-Control: max-age=0');
header('Expires: 0');
header('Pragma: public');

$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$objWriter->save('php://output');

$mysqli->close();
exit;
?>